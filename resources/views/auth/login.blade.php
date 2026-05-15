@extends('layouts.app')

@section('title', 'Masuk - Bookify')

@section('content')
<!-- Login Page -->
<div id="loginPage" class="auth-page active">
    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-logo">
                <h1>Bookify</h1>
                <p>Kelola Keuangan UMKM dengan Mudah</p>
            </div>
            <div class="auth-features">
                <div class="feature-item">
                    <span class="feature-icon">📊</span>
                    <h3>Laporan Otomatis</h3>
                    <p>Dapatkan laporan keuangan dan rekomendasi untuk mengembangkan usaha Anda</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📦</span>
                    <h3>Kelola Stok</h3>
                    <p>Catat transaksi dengan cepat dan mudah, tanpa perlu keahlian khusus</p>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">💰</span>
                    <h3>Kelola Keuangan</h3>
                    <p>Bookify membantu UMKM mencatat dan menganalisis keuangan dengan mudah</p>
                </div>
            </div>
        </div>
        <div class="auth-right">
            <form action="{{ route('login.store') }}" method="POST" id="loginForm" class="auth-form">
                @csrf
                <h2>Masuk</h2>
                <p class="auth-subtitle">Masuk untuk melanjutkan ke Bookify</p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                
                <a href="#" class="forgot-password">Lupa Password?</a>
                
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                
                <div class="auth-divider">
                    <span>Demo Akun Tersedia</span>
                </div>
                
                <div class="demo-accounts">
                    <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Email: admin@bookify.com</p>
                    <p style="font-size: 12px; color: #666;">Password: password123</p>
                </div>
                
                <a href="{{ route('landing') }}" class="btn btn-text btn-block" style="margin-top: 20px;">← Kembali ke Landing Page</a>
            </form>
        </div>
    </div>
</div>
@endsection
