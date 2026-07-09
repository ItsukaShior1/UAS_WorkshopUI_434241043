<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ArticleManagementController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PlanManagementController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionPaymentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MarketplaceController;

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
    Route::get('/dashboard/community/{post}/edit', [DashboardController::class, 'communityEdit'])->name('dashboard.community.edit');
    Route::put('/dashboard/community/{post}', [DashboardController::class, 'communityUpdate'])->name('dashboard.community.update');
    Route::delete('/dashboard/community/{post}', [DashboardController::class, 'communityDestroy'])->name('dashboard.community.destroy');
    Route::get('/dashboard/community/{post}', [DashboardController::class, 'communityShow'])->name('dashboard.community.show');
    Route::post('/dashboard/community/{post}/comments', [DashboardController::class, 'communityCommentStore'])->name('dashboard.community.comment.store');
    Route::post('/dashboard/community/{post}/like', [DashboardController::class, 'communityLikeToggle'])->name('dashboard.community.like');
});

// Admin Routes (Protected by auth + admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    Route::get('/articles', [ArticleManagementController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleManagementController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleManagementController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleManagementController::class, 'edit'])->name('articles.edit');
    Route::get('/articles/{article}', [ArticleManagementController::class, 'show'])->name('articles.show');
    Route::put('/articles/{article}', [ArticleManagementController::class, 'update'])->name('articles.update');
    Route::post('/articles/{article}/toggle-publish', [ArticleManagementController::class, 'togglePublish'])->name('articles.toggle');
    Route::delete('/articles/{article}', [ArticleManagementController::class, 'destroy'])->name('articles.destroy');

    Route::get('/plans', [PlanManagementController::class, 'index'])->name('plans.index');
    Route::get('/plans/create', [PlanManagementController::class, 'create'])->name('plans.create');
    Route::post('/plans', [PlanManagementController::class, 'store'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [PlanManagementController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{plan}', [PlanManagementController::class, 'update'])->name('plans.update');
    Route::post('/plans/{plan}/toggle-active', [PlanManagementController::class, 'toggleActive'])->name('plans.toggle');
    Route::delete('/plans/{plan}', [PlanManagementController::class, 'destroy'])->name('plans.destroy');

    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [AdminSubscriptionController::class, 'show'])->name('subscriptions.show');

    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/cancel', [AdminTransactionController::class, 'cancel'])->name('transactions.cancel');
});

// Subscription
Route::middleware('auth')->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('/subscription/my', [SubscriptionController::class, 'my'])->name('subscription.my');
    Route::get('/subscription/payments', [SubscriptionPaymentController::class, 'index'])->name('subscription.payments');
    Route::get('/subscription/{plan}/checkout', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/subscription/{plan}/pay', [SubscriptionController::class, 'pay'])->name('subscription.pay');
    Route::post('/subscription/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/{item}/select', [CartController::class, 'toggleSelect'])->name('cart.toggle-select');
    Route::post('/cart/select-all', [CartController::class, 'selectAll'])->name('cart.select-all');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Subscription add-to-cart
    Route::post('/subscription/{plan}/add-to-cart', [CartController::class, 'addPlan'])->name('subscription.add-to-cart');

    // Marketplace
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/connect', [MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store');
    Route::post('/marketplace/{integration}/toggle', [MarketplaceController::class, 'toggle'])->name('marketplace.toggle');
    Route::delete('/marketplace/{integration}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');
});
