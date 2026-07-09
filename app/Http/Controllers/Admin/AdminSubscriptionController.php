<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = $request->get('status');

        UserSubscription::syncExpired();

        $subs = UserSubscription::query()
            ->with(['user', 'plan'])
            ->when($q !== '', function ($qq) use ($q) {
                $qq->whereHas('user', fn ($u) => $u->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%"));
            })
            ->when($status, fn ($qq) => $qq->where('status', $status))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.subscriptions.index', [
            'subs' => $subs,
            'search' => $q,
            'status' => $status,
        ]);
    }

    public function show(UserSubscription $subscription)
    {
        $subscription->load(['user', 'plan']);
        return view('admin.subscriptions.show', compact('subscription'));
    }
}
