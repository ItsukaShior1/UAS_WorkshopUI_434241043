<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanManagementController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $period = $request->get('period');

        $plans = Plan::query()
            ->when($q !== '', fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")->orWhere('description', 'like', "%$q%");
            }))
            ->when($period, fn ($qq) => $qq->where('billing_period', $period))
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.plans.index', [
            'plans' => $plans,
            'search' => $q,
            'period' => $period,
        ]);
    }

    public function create()
    {
        return view('admin.plans.create', [
            'plan' => new Plan(['billing_period' => 'monthly', 'is_active' => true]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Plan::create($data);

        return redirect()->route('admin.plans.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validated($request, $plan);

        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('success', 'Paket diperbarui.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->exists()) {
            return back()->withErrors(['error' => 'Paket masih memiliki pelanggan aktif. Nonaktifkan saja.']);
        }
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Paket dihapus.');
    }

    public function toggleActive(Plan $plan)
    {
        $plan->update(['is_active' => ! $plan->is_active]);
        return back()->with('success', 'Status paket diperbarui.');
    }

    protected function validated(Request $request, ?Plan $plan = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::in([Plan::CODE_APP, Plan::CODE_MARKETPLACE])],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'billing_period' => ['required', Rule::in(['monthly', 'yearly'])],
            'price' => ['required', 'numeric', 'min:0'],
            'includes_marketplace' => ['nullable'],
            'upgrade_discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_active' => ['nullable'],
            'sort_order' => ['nullable', 'integer'],
            'features' => ['nullable', 'string', 'max:2000'],
        ]) + [
            'includes_marketplace' => (bool) $request->boolean('includes_marketplace'),
            'is_active' => (bool) $request->boolean('is_active'),
            'sort_order' => (int) ($request->input('sort_order', 0)),
            'upgrade_discount_percent' => max(0, min(100, (int) $request->input('upgrade_discount_percent', 0))),
            'features' => array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $request->input('features', ''))))),
        ];
    }
}
