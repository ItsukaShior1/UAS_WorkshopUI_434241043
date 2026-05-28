@extends('layouts.app')

@section('title', '@yield("page-title", "Dashboard - Bookify")')

@section('content')
<!-- Dashboard Page -->
<div id="dashboardPage" class="page active">
    <input type="checkbox" id="sidebarToggle" class="sidebar-toggle-input">
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-toggle-wrap">
                <label class="menu-toggle menu-toggle-close" for="sidebarToggle" aria-label="Close sidebar">×</label>
            </div>
            <div class="sidebar-logo">
                <h1>Bookify</h1>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard.home') }}" class="nav-item {{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
                    <img src="{{ asset('img/icons/home.png') }}" alt="Home" class="nav-icon-img" style="width:14px;height:14px;min-width:14px;min-height:14px;max-width:14px;max-height:14px;flex:0 0 14px;object-fit:contain;">
                    <span class="nav-label">Home</span>
                </a>
                <a href="{{ route('dashboard.transactions') }}" class="nav-item {{ request()->routeIs('dashboard.transactions') ? 'active' : '' }}">
                    <img src="{{ asset('img/icons/transactions.png') }}" alt="Transaksi" class="nav-icon-img" style="width:14px;height:14px;min-width:14px;min-height:14px;max-width:14px;max-height:14px;flex:0 0 14px;object-fit:contain;">
                    <span class="nav-label">Transaksi</span>
                </a>
                <a href="{{ route('dashboard.stock') }}" class="nav-item {{ request()->routeIs('dashboard.stock') ? 'active' : '' }}">
                    <img src="{{ asset('img/icons/stock.png') }}" alt="Stok" class="nav-icon-img" style="width:14px;height:14px;min-width:14px;min-height:14px;max-width:14px;max-height:14px;flex:0 0 14px;object-fit:contain;">
                    <span class="nav-label">Stok</span>
                </a>
                <a href="{{ route('dashboard.reports') }}" class="nav-item {{ request()->routeIs('dashboard.reports') ? 'active' : '' }}">
                    <img src="{{ asset('img/icons/reports.png') }}" alt="Laporan" class="nav-icon-img" style="width:14px;height:14px;min-width:14px;min-height:14px;max-width:14px;max-height:14px;flex:0 0 14px;object-fit:contain;">
                    <span class="nav-label">Laporan</span>
                </a>
                <a href="{{ route('dashboard.insights') }}" class="nav-item {{ request()->routeIs('dashboard.insights') ? 'active' : '' }}">
                    <img src="{{ asset('img/icons/insights.png') }}" alt="Insight" class="nav-icon-img" style="width:14px;height:14px;min-width:14px;min-height:14px;max-width:14px;max-height:14px;flex:0 0 14px;object-fit:contain;">
                    <span class="nav-label">Insight</span>
                </a>
                <a href="{{ route('dashboard.community') }}" class="nav-item {{ request()->routeIs('dashboard.community') ? 'active' : '' }}">
                    <img src="{{ asset('img/icons/community.png') }}" alt="Komunitas" class="nav-icon-img" style="width:14px;height:14px;min-width:14px;min-height:14px;max-width:14px;max-height:14px;flex:0 0 14px;object-fit:contain;">
                    <span class="nav-label">Komunitas</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; text-align: left; cursor: pointer; border: none; background: none; padding: 12px 16px; margin: 0;">
                        <span class="nav-icon-img nav-icon-text">⎋</span>
                        <span class="nav-label">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <label class="menu-toggle menu-toggle-open" for="sidebarToggle" aria-label="Open sidebar">☰</label>
                    <h2 id="pageTitle">@yield('page-title', 'Home')</h2>
                </div>
                <div class="top-bar-right">
                    <button class="notification-btn">🔔</button>
                    <div class="user-profile">
                        <div class="profile-avatar profile-avatar-fallback">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <span class="profile-name">{{ explode(' ', $user->name)[0] }}</span>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @yield('dashboard-content')
            </div>
        </main>
    </div>
</div>

@endsection
