@extends('layouts.app')

@section('title', trim($__env->yieldContent('page-title') ?: 'Dashboard Admin') . ' - Bookify Admin')

@section('content')
<div id="adminPage" class="page active">
    <input type="checkbox" id="adminSidebarToggle" class="sidebar-toggle-input">

    <div class="dashboard-container admin-shell">
        <aside class="sidebar admin-sidebar">
            <div class="sidebar-toggle-wrap">
                <label class="menu-toggle menu-toggle-close" for="adminSidebarToggle" aria-label="Close sidebar">×</label>
            </div>

            <div class="sidebar-logo admin-logo">
                <h1>Bookify</h1>
                <span class="admin-logo-sub">Admin Panel</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" class="nav-icon-img"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i data-lucide="users" class="nav-icon-img"></i>
                    <span class="nav-label">Pengguna</span>
                </a>
                <a href="{{ route('admin.articles.index') }}" class="nav-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                    <i data-lucide="file-text" class="nav-icon-img"></i>
                    <span class="nav-label">Artikel</span>
                </a>
                <a href="{{ route('admin.plans.index') }}" class="nav-item {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                    <i data-lucide="package" class="nav-icon-img"></i>
                    <span class="nav-label">Paket Langganan</span>
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" class="nav-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                    <i data-lucide="credit-card" class="nav-icon-img"></i>
                    <span class="nav-label">Langganan</span>
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                    <i data-lucide="receipt" class="nav-icon-img"></i>
                    <span class="nav-label">Transaksi</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; text-align: left; cursor: pointer; border: none; background: none; padding: 12px 16px; margin: 0;">
                        <i data-lucide="log-out" class="nav-icon-img"></i>
                        <span class="nav-label">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content admin-main">
            <div class="top-bar admin-top-bar">
                <div class="top-bar-left">
                    <label class="menu-toggle menu-toggle-open" for="adminSidebarToggle" aria-label="Open sidebar">≡</label>
                    <h2 id="adminPageTitle">@yield('page-title', 'Dashboard Admin')</h2>
                </div>
                <div class="top-bar-right">
                    <div class="user-profile">
                        <div class="profile-avatar profile-avatar-fallback admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <span class="profile-name">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        <span class="admin-badge">Admin</span>
                    </div>
                </div>
            </div>

            <div class="content-area admin-content-area">
                @yield('admin-content')
            </div>
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
.admin-shell { background: #f1f5f9; }
.admin-sidebar {
    background: #0f172a;
    color: #e2e8f0;
    border-right: 1px solid #1e293b;
}
.admin-sidebar .sidebar-logo {
    border-bottom: 1px solid #1e293b;
    padding: 18px 16px;
}
.admin-logo h1 { color: #fff; margin: 0; font-size: 22px; }
.admin-logo-sub {
    display: block;
    color: #94a3b8;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .12em;
    margin-top: 4px;
    font-weight: 600;
}
.admin-sidebar .nav-item {
    color: #cbd5e1;
}
.admin-sidebar .nav-item:hover {
    background: #1e293b;
    color: #fff;
}
.admin-sidebar .nav-item.active {
    background: #1e293b;
    color: #fff;
    border-left: 3px solid #38b26d;
}
.admin-sidebar .sidebar-footer {
    border-top: 1px solid #1e293b;
}
.admin-sidebar .sidebar-footer form button {
    color: #cbd5e1;
}
.admin-sidebar .sidebar-footer form button:hover {
    background: #1e293b;
    color: #fff;
}
.admin-sidebar .nav-icon-img {
    width: 18px;
    height: 18px;
    min-width: 18px;
    min-height: 18px;
    flex: 0 0 18px;
    margin-right: 10px;
}
.admin-top-bar {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
}
.admin-top-bar h2 { color: #0f172a; margin: 0; font-size: 22px; }
.admin-avatar {
    background: #0f172a;
    color: #fff;
}
.admin-badge {
    background: #38b26d;
    color: #fff;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-left: 8px;
}
.admin-content-area {
    padding: 28px;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    });
</script>
@endpush
