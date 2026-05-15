@extends('layouts.app')

@section('title', '@yield("page-title", "Dashboard - Bookify")')

@section('content')
<!-- Dashboard Page -->
<div id="dashboardPage" class="page active">
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <h1>Bookify</h1>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard.home') }}" class="nav-item {{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-label">Home</span>
                </a>
                <a href="{{ route('dashboard.transactions') }}" class="nav-item {{ request()->routeIs('dashboard.transactions') ? 'active' : '' }}">
                    <span class="nav-icon">💳</span>
                    <span class="nav-label">Transaksi</span>
                </a>
                <a href="{{ route('dashboard.stock') }}" class="nav-item {{ request()->routeIs('dashboard.stock') ? 'active' : '' }}">
                    <span class="nav-icon">📦</span>
                    <span class="nav-label">Stok</span>
                </a>
                <a href="{{ route('dashboard.reports') }}" class="nav-item {{ request()->routeIs('dashboard.reports') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span>
                    <span class="nav-label">Laporan</span>
                </a>
                <a href="{{ route('dashboard.insights') }}" class="nav-item {{ request()->routeIs('dashboard.insights') ? 'active' : '' }}">
                    <span class="nav-icon">✨</span>
                    <span class="nav-label">Insight</span>
                </a>
                <a href="{{ route('dashboard.community') }}" class="nav-item {{ request()->routeIs('dashboard.community') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span>
                    <span class="nav-label">Komunitas</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; text-align: left; cursor: pointer; border: none; background: none; padding: 12px 16px;">
                        <span class="nav-icon">🚪</span>
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
                    <button class="menu-toggle" id="menuToggle">☰</button>
                    <h2 id="pageTitle">@yield('page-title', 'Home')</h2>
                </div>
                <div class="top-bar-right">
                    <button class="notification-btn">🔔</button>
                    <div class="user-profile">
                        <img src="https://via.placeholder.com/40" alt="Profile" class="profile-avatar">
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
