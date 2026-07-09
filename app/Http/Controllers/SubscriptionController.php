<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        UserSubscription::syncExpired();
        $plans = Plan::active()->orderBy('sort_order')->get();
        $mySub = Auth::user()->activeSubscription();
        $user = Auth::user();

        // Bangun konteks upgrade untuk tiap plan Marketplace
        $isAppOnlyActive = $mySub && $mySub->plan && $mySub->plan->code === Plan::CODE_APP;

        $plansContext = $plans->map(function (Plan $plan) use ($user, $mySub) {
            $upgrade = $plan->upgradePriceFor($user);
            $isCurrent = $mySub && $mySub->plan_id === $plan->id;
            $isSameCodeButDifferent = $mySub && $mySub->plan && $mySub->plan->code === $plan->code && ! $isCurrent;

            return [
                'plan' => $plan,
                'upgrade' => $upgrade,
                'is_current' => $isCurrent,
                'is_same_code' => $isSameCodeButDifferent,
            ];
        });

        return view('subscription.plans', [
            'plans' => $plans,
            'plansContext' => $plansContext,
            'mySub' => $mySub,
            'isAppOnlyActive' => $isAppOnlyActive,
        ]);
    }

    public function my()
    {
        UserSubscription::syncExpired();
        $subs = Auth::user()->subscriptions()->with('plan')->paginate(10);
        $active = Auth::user()->activeSubscription();
        $user = Auth::user();

        // Rekomendasi upgrade jika masih App-only
        $upgradeSuggestion = null;
        if ($active && $active->plan && $active->plan->code === Plan::CODE_APP) {
            $marketplacePlan = Plan::active()
                ->marketplace()
                ->where('billing_period', $active->plan->billing_period)
                ->orderBy('price')
                ->first();

            if ($marketplacePlan) {
                $upgradeSuggestion = [
                    'plan' => $marketplacePlan,
                    'upgrade' => $marketplacePlan->upgradePriceFor($user),
                ];
            }
        }

        return view('subscription.my', [
            'subs' => $subs,
            'active' => $active,
            'upgradeSuggestion' => $upgradeSuggestion,
        ]);
    }

    public function checkout(Plan $plan)
    {
        if (! $plan->is_active) {
            abort(404);
        }

        $upgrade = $plan->upgradePriceFor(Auth::user());

        return view('subscription.checkout', [
            'plan' => $plan,
            'upgrade' => $upgrade,
        ]);
    }

    public function pay(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'payment_method' => ['required', 'in:ewallet,va,qris'],
            'payment_channel' => ['nullable', 'string', 'max:50'],
        ]);

        $user = Auth::user();
        $now = Carbon::now();
        $ends = $plan->billing_period === 'yearly' ? $now->copy()->addYear() : $now->copy()->addMonth();

        // Tentukan harga efektif (dengan diskon upgrade bila berlaku)
        $upgrade = $plan->upgradePriceFor($user);
        $effectivePrice = $upgrade ? (float) $upgrade['final_price'] : (float) $plan->price;

        // Cancel any existing active subscription
        $user->subscriptions()->where('status', UserSubscription::STATUS_ACTIVE)->update(['status' => UserSubscription::STATUS_CANCELLED, 'cancelled_at' => $now]);

        $reference = strtoupper(substr($data['payment_method'], 0, 2)).'-'.now()->format('YmdHis').'-'.$user->id;

        $sub = UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => UserSubscription::STATUS_ACTIVE,
            'starts_at' => $now,
            'ends_at' => $ends,
            'payment_method' => $data['payment_method'].($data['payment_channel'] ? ':'.$data['payment_channel'] : ''),
            'payment_reference' => $reference,
            'amount_paid' => $effectivePrice,
            'metadata' => [
                'mock' => true,
                'ip' => $request->ip(),
                'upgrade_from' => $upgrade ? 'app' : null,
                'original_price' => $upgrade ? $upgrade['original_price'] : null,
                'discount_percent' => $upgrade ? $upgrade['discount_percent'] : null,
                'discount_amount' => $upgrade ? $upgrade['discount_amount'] : null,
            ],
        ]);

        // Catat juga ke tabel transactions agar admin bisa lihat di Kelola Transaksi
        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'kind' => \App\Models\Transaction::KIND_SUBSCRIPTION_PAYMENT,
            'type' => 'income',
            'item_name' => $plan->name.' ('.($plan->billing_period === 'yearly' ? 'Tahunan' : 'Bulanan').')',
            'category' => 'Langganan',
            'amount' => (int) round($effectivePrice),
            'quantity' => 1,
            'unit_price' => (int) round($effectivePrice),
            'notes' => $upgrade
                ? 'Upgrade dari paket App-only. Diskon '.$upgrade['discount_percent'].'% dari Rp '.number_format($upgrade['original_price'], 0, ',', '.').'.'
                : 'Pembelian paket langganan baru.',
            'product_id' => null,
            'status' => \App\Models\Transaction::STATUS_COMPLETED,
        ]);

        // Reactivate user if previously deactivated
        $user->forceFill(['is_active' => true, 'deactivated_reason' => null])->save();

        return redirect()->route('subscription.my')->with('success', 'Pembayaran berhasil! Langganan Anda aktif hingga '.$ends->translatedFormat('d F Y').'.');
    }

    public function cancel(UserSubscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }
        if ($subscription->status === UserSubscription::STATUS_ACTIVE) {
            $subscription->update(['status' => UserSubscription::STATUS_CANCELLED, 'cancelled_at' => now()]);
        }
        return back()->with('success', 'Langganan dibatalkan.');
    }
}