<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MarketplaceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $integrations = $user->marketplaceIntegrations()->orderBy('platform')->get();
        $hasAccess = $user->hasMarketplaceAccess();
        return view('marketplace.index', [
            'integrations' => $integrations,
            'hasAccess' => $hasAccess,
        ]);
    }

    public function create()
    {
        if (! Auth::user()->hasMarketplaceAccess()) {
            return redirect()->route('subscription.index')->withErrors(['plan' => 'Marketplace hanya untuk paket Marketplace.']);
        }
        $existing = Auth::user()->marketplaceIntegrations()->pluck('platform')->all();
        $platforms = collect(MarketplaceIntegration::PLATFORMS)
            ->reject(fn ($_, $k) => in_array($k, $existing, true))
            ->all();
        return view('marketplace.connect', ['platforms' => $platforms]);
    }

    public function store(Request $request)
    {
        if (! Auth::user()->hasMarketplaceAccess()) {
            abort(403);
        }
        $data = $request->validate([
            'platform' => ['required', Rule::in(array_keys(MarketplaceIntegration::PLATFORMS))],
            'shop_name' => ['nullable', 'string', 'max:120'],
            'api_key' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        $user = Auth::user();
        if ($user->marketplaceIntegrations()->where('platform', $data['platform'])->exists()) {
            return back()->withErrors(['platform' => 'Platform ini sudah terhubung.']);
        }

        $user->marketplaceIntegrations()->create([
            'platform' => $data['platform'],
            'shop_name' => $data['shop_name'] ?? null,
            'api_key_encrypted' => $data['api_key'],
            'is_active' => true,
            'connected_at' => now(),
        ]);

        return redirect()->route('marketplace.index')->with('success', 'Akun marketplace berhasil dihubungkan.');
    }

    public function toggle(MarketplaceIntegration $integration)
    {
        if ($integration->user_id !== Auth::id()) abort(403);
        $integration->update(['is_active' => ! $integration->is_active]);
        return back()->with('success', 'Status integrasi diperbarui.');
    }

    public function destroy(MarketplaceIntegration $integration)
    {
        if ($integration->user_id !== Auth::id()) abort(403);
        $integration->delete();
        return back()->with('success', 'Integrasi dihapus.');
    }
}
