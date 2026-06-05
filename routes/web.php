<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PdfController;

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
    Route::post('/dashboard/transactions', [DashboardController::class, 'transactionsStore'])->name('dashboard.transactions.store');
    Route::get('/dashboard/stock', [DashboardController::class, 'stock'])->name('dashboard.stock');
    Route::get('/dashboard/stock/{product}', [DashboardController::class, 'stockShow'])->name('dashboard.stock.show');
    Route::post('/dashboard/stock', [DashboardController::class, 'stockStore'])->name('dashboard.stock.store');
    Route::put('/dashboard/stock/{product}', [DashboardController::class, 'stockUpdate'])->name('dashboard.stock.update');
    Route::delete('/dashboard/stock/{product}', [DashboardController::class, 'stockDestroy'])->name('dashboard.stock.destroy');
    Route::get('/dashboard/reports', [DashboardController::class, 'reports'])->name('dashboard.reports');
    Route::get('/dashboard/reports/pdf', [PdfController::class, 'report'])->name('dashboard.reports.pdf');
    Route::get('/dashboard/insights', [DashboardController::class, 'insights'])->name('dashboard.insights');
    Route::get('/dashboard/community', [DashboardController::class, 'community'])->name('dashboard.community');
    Route::get('/dashboard/community/create', [DashboardController::class, 'communityCreate'])->name('dashboard.community.create');
    Route::post('/dashboard/community', [DashboardController::class, 'communityStore'])->name('dashboard.community.store');
    Route::get('/dashboard/community/{post}', [DashboardController::class, 'communityShow'])->name('dashboard.community.show');
    Route::post('/dashboard/community/{post}/comments', [DashboardController::class, 'communityCommentStore'])->name('dashboard.community.comment.store');
    Route::post('/dashboard/community/{post}/like', [DashboardController::class, 'communityLikeToggle'])->name('dashboard.community.like');
});
