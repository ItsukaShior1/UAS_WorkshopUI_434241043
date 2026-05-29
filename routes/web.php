<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Landing Page
Route::get('/', [AuthController::class, 'landing'])->name('landing');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes (Protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'home'])->name('dashboard.home');
    Route::get('/dashboard/transactions', [DashboardController::class, 'transactions'])->name('dashboard.transactions');
    Route::get('/dashboard/stock', [DashboardController::class, 'stock'])->name('dashboard.stock');
    Route::get('/dashboard/reports', [DashboardController::class, 'reports'])->name('dashboard.reports');
    Route::get('/dashboard/insights', [DashboardController::class, 'insights'])->name('dashboard.insights');
    Route::get('/dashboard/community', [DashboardController::class, 'community'])->name('dashboard.community');
    Route::get('/dashboard/community/create', [DashboardController::class, 'communityCreate'])->name('dashboard.community.create');
    Route::get('/dashboard/community/{post}', [DashboardController::class, 'communityShow'])->name('dashboard.community.show');
});
