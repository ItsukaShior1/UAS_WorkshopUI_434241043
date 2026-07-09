<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::openFor(Auth::id());
        $cart->load('items');
        return view('cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $cart = Cart::openFor(Auth::id());

        // Extract base64 image
        $imageData = null;
        $imageMime = null;
        if (!empty($data['image'])) {
            if (preg_match('/^data:(image\/(?:jpeg|png|webp));base64,(.+)$/', $data['image'], $m)) {
                $imageMime = $m[1];
                $imageData = $m[2];
                if (strlen($imageData) > 2.7 * 1024 * 1024) {
                    return back()->withErrors(['image' => 'Ukuran gambar terlalu besar (maks 2MB).']);
                }
            } else {
                return back()->withErrors(['image' => 'Format gambar tidak valid.']);
            }
        }

        $cart->items()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'quantity' => $data['quantity'] ?? 1,
            'image_mime' => $imageMime,
            'image_data' => $imageData,
        ]);

        return redirect()->route('cart.index')->with('success', 'Item ditambahkan ke keranjang.');
    }

    public function update(Request $request, $itemId)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Cart::openFor(Auth::id());
        $item = $cart->items()->findOrFail($itemId);
        $item->update(['quantity' => $data['quantity']]);

        return back()->with('success', 'Jumlah diperbarui.');
    }

    public function destroy($itemId)
    {
        $cart = Cart::openFor(Auth::id());
        $item = $cart->items()->findOrFail($itemId);
        $item->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        $cart = Cart::openFor(Auth::id());
        $cart->items()->delete();
        return back()->with('success', 'Keranjang dikosongkan.');
    }

    public function toggleSelect(Request $request, $itemId)
    {
        $cart = Cart::openFor(Auth::id());
        $item = $cart->items()->findOrFail($itemId);
        $item->update(['selected' => !$item->selected]);

        if ($request->wantsJson()) {
            $cart->load('items');
            return response()->json([
                'selected' => (bool) $item->selected,
                'selected_subtotal' => (float) $cart->selected_subtotal,
                'selected_quantity' => (int) $cart->selected_quantity,
                'has_selected' => $cart->has_selected,
            ]);
        }

        return back();
    }

    public function selectAll(Request $request)
    {
        $data = $request->validate([
            'selected' => ['required', 'boolean'],
        ]);

        $cart = Cart::openFor(Auth::id());
        $cart->items()->update(['selected' => $data['selected']]);

        if ($request->wantsJson()) {
            $cart->load('items');
            return response()->json([
                'selected_subtotal' => (float) $cart->selected_subtotal,
                'selected_quantity' => (int) $cart->selected_quantity,
                'has_selected' => $cart->has_selected,
            ]);
        }

        return back()->with('success', 'Pilihan diperbarui.');
    }

    public function addPlan(Request $request, Plan $plan)
    {
        if (! $plan->is_active) {
            abort(404);
        }

        $cart = Cart::openFor(Auth::id());

        // If already in cart, do nothing (subscription plans are 1-unit)
        $existing = $cart->items()
            ->where('purchasable_type', Plan::class)
            ->where('purchasable_id', $plan->id)
            ->first();

        if ($existing) {
            return redirect()->route('cart.index')->with('success', 'Paket sudah ada di keranjang.');
        }

        $user = Auth::user();
        $upgrade = $plan->upgradePriceFor($user);
        $effectivePrice = $upgrade ? (float) $upgrade['final_price'] : (float) $plan->price;

        $cart->items()->create([
            'purchasable_type' => Plan::class,
            'purchasable_id' => $plan->id,
            'name' => $plan->name,
            'description' => $upgrade
                ? 'Langganan '.$plan->billing_label.' • Diskon upgrade '.$upgrade['discount_percent'].'%'
                : 'Langganan '.$plan->billing_label,
            'price' => $effectivePrice,
            'original_price' => $upgrade ? (float) $upgrade['original_price'] : (float) $plan->price,
            'quantity' => 1,
            'selected' => true,
        ]);

        return redirect()->route('cart.index')->with('success', $upgrade ? 'Paket ditambahkan dengan diskon upgrade '.$upgrade['discount_percent'].'%.' : 'Paket ditambahkan ke keranjang.');
    }

    public function checkout(Request $request)
    {
        $cart = Cart::openFor(Auth::id());
        $cart->load('items');

        $selectedItems = $cart->selectedItems()->with('purchasable')->get();

        if ($selectedItems->isEmpty()) {
            return back()->withErrors(['cart' => 'Pilih minimal satu item untuk di-checkout.']);
        }

        $request->validate([
            'payment_method' => ['required', 'in:ewallet,va,qris'],
        ]);

        $total = (float) $selectedItems->sum(fn ($i) => $i->price * $i->quantity);
        $reference = 'CART-'.strtoupper(Str::random(8)).'-'.now()->format('YmdHis');

        // Materialize subscription purchases
        foreach ($selectedItems as $item) {
            if ($item->purchasable_type === Plan::class && $item->purchasable) {
                $plan = $item->purchasable;
                $now = now();
                $ends = $plan->billing_period === 'yearly' ? $now->copy()->addYear() : $now->copy()->addMonth();

                $user = Auth::user();
                $user->subscriptions()
                    ->where('status', \App\Models\UserSubscription::STATUS_ACTIVE)
                    ->update(['status' => \App\Models\UserSubscription::STATUS_CANCELLED, 'cancelled_at' => $now]);

                $user->forceFill(['is_active' => true, 'deactivated_reason' => null])->save();

                $lineTotal = (float) $item->price * (int) $item->quantity;
                $upgrade = $plan->upgradePriceFor($user);

                $user->subscriptions()->create([
                    'plan_id' => $plan->id,
                    'status' => \App\Models\UserSubscription::STATUS_ACTIVE,
                    'starts_at' => $now,
                    'ends_at' => $ends,
                    'payment_method' => $request->input('payment_method'),
                    'payment_reference' => $reference.'-'.$plan->code,
                    'amount_paid' => $lineTotal,
                    'metadata' => [
                        'mock' => true,
                        'cart_item_id' => $item->id,
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
                    'amount' => (int) round($lineTotal),
                    'quantity' => 1,
                    'unit_price' => (int) round($lineTotal),
                    'notes' => $upgrade
                        ? 'Upgrade dari paket App-only via Cart. Diskon '.$upgrade['discount_percent'].'%.'
                        : 'Pembelian paket langganan via Cart.',
                    'product_id' => null,
                    'status' => \App\Models\Transaction::STATUS_COMPLETED,
                ]);
            }
        }

        // Remove checked-out items
        $selectedItems->each->delete();

        return view('cart.success', [
            'total' => $total,
            'reference' => $reference,
            'paymentMethod' => $request->input('payment_method'),
        ]);
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'image' => ['nullable', 'string'],
        ]);
    }
}
