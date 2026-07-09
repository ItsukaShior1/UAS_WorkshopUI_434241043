<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = $request->get('status');

        // Hanya tampilkan transaksi pembayaran langganan (bukan pembukuan user)
        $base = Transaction::query()->subscriptionPayments();

        $transactions = (clone $base)
            ->with(['user', 'cancelledBy'])
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('item_name', 'like', "%$q%")
                      ->orWhere('notes', 'like', "%$q%")
                      ->orWhere('payment_reference', 'like', "%$q%")
                      ->orWhere('refund_reference', 'like', "%$q%")
                      ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%"));
                });
            })
            ->when($status, fn ($qq) => $qq->where('status', $status))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total' => (clone $base)->count(),
            'completed' => (clone $base)->where('status', Transaction::STATUS_COMPLETED)->count(),
            'cancelled' => (clone $base)->where('status', Transaction::STATUS_CANCELLED)->count(),
            'refunded' => (clone $base)->where('status', Transaction::STATUS_REFUNDED)->count(),
            'revenue_total' => (int) (clone $base)->where('status', Transaction::STATUS_COMPLETED)->sum('amount'),
            'refund_total' => (int) (clone $base)->whereNotNull('refund_amount')->sum('refund_amount'),
        ];

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'search' => $q,
            'status' => $status,
            'summary' => $summary,
        ]);
    }

    public function show(Transaction $transaction)
    {
        abort_unless($transaction->isSubscriptionPayment(), 404);
        $transaction->load(['user', 'cancelledBy']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function cancel(Request $request, Transaction $transaction)
    {
        abort_unless($transaction->isSubscriptionPayment(), 404);

        if (! $transaction->isCancellable()) {
            return back()->withErrors(['error' => 'Transaksi ini tidak dapat dibatalkan (status: '.$transaction->status_label.').']);
        }

        $data = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
            'refund' => ['nullable'],
            'refund_amount' => ['nullable', 'integer', 'min:0'],
        ]);

        $isRefund = $request->boolean('refund');
        $refundAmount = $isRefund ? (int) ($data['refund_amount'] ?? $transaction->amount) : null;

        if ($isRefund && $refundAmount > (int) $transaction->amount) {
            return back()->withErrors(['refund_amount' => 'Nominal refund tidak boleh melebihi jumlah transaksi.'])->withInput();
        }

        $transaction->update([
            'status' => $isRefund ? Transaction::STATUS_REFUNDED : Transaction::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
            'cancellation_reason' => $data['cancellation_reason'],
            'refund_reference' => $isRefund ? 'REFUND-'.strtoupper(Str::random(8)).'-'.now()->format('YmdHis') : null,
            'refund_amount' => $isRefund ? $refundAmount : null,
        ]);

        $message = $isRefund
            ? 'Transaksi direfund sebesar Rp '.number_format($refundAmount, 0, ',', '.').'.'
            : 'Transaksi dibatalkan tanpa refund.';

        return redirect()->route('admin.transactions.show', $transaction)->with('success', $message);
    }
}