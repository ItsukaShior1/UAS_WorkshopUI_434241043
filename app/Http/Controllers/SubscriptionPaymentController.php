<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionPaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');

        $payments = Transaction::query()
            ->subscriptionPayments()
            ->where('user_id', $user->id)
            ->with(['cancelledBy'])
            ->when($status, fn ($qq) => $qq->where('status', $status))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total' => Transaction::subscriptionPayments()->where('user_id', $user->id)->count(),
            'completed' => Transaction::subscriptionPayments()->where('user_id', $user->id)->where('status', Transaction::STATUS_COMPLETED)->count(),
            'cancelled' => Transaction::subscriptionPayments()->where('user_id', $user->id)->where('status', Transaction::STATUS_CANCELLED)->count(),
            'refunded' => Transaction::subscriptionPayments()->where('user_id', $user->id)->where('status', Transaction::STATUS_REFUNDED)->count(),
            'total_paid' => (int) Transaction::subscriptionPayments()->where('user_id', $user->id)->where('status', Transaction::STATUS_COMPLETED)->sum('amount'),
            'total_refunded' => (int) Transaction::subscriptionPayments()->where('user_id', $user->id)->whereNotNull('refund_amount')->sum('refund_amount'),
        ];

        return view('subscription.payments', [
            'payments' => $payments,
            'status' => $status,
            'summary' => $summary,
        ]);
    }
}