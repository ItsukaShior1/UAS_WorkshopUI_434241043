<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_subscriptions' => $this->safeCount('user_subscriptions', "status = 'active'"),
            'published_articles' => $this->safeCount('articles', "is_published = 1"),
            'active_plans' => $this->safeCount('plans', "is_active = 1"),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    private function safeCount(string $table, ?string $condition = null): int
    {
        try {
            if (! Schema::hasTable($table)) {
                return 0;
            }
            $query = DB::table($table);
            if ($condition !== null) {
                $query->whereRaw($condition);
            }
            return (int) $query->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
