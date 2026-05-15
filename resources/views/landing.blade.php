@extends('layouts.app')

@section('title', 'Bookify - Kelola Keuangan UMKM dengan Mudah')

@section('content')
<!-- Landing Page -->
<div id="landingPage" class="landing-page active">
    <div class="landing-container">
        <div class="landing-content">
            <div class="landing-header">
                <h1 class="landing-title">Bookify</h1>
                <p class="landing-subtitle">Kelola Keuangan UMKM dengan Mudah</p>
            </div>

            <div class="landing-hero">
                <div class="hero-icon">📊</div>
                <h2>Solusi Keuangan untuk UMKM Anda</h2>
                <p>Catat transaksi, kelola stok, dan dapatkan insight bisnis dalam satu platform yang mudah digunakan.</p>
            </div>

            <div class="landing-features">
                <div class="landing-feature-card">
                    <div class="card-icon">📝</div>
                    <h3>Catat Transaksi</h3>
                    <p>Catat pemasukan dan pengeluaran dengan cepat tanpa ribet</p>
                </div>
                <div class="landing-feature-card">
                    <div class="card-icon">📦</div>
                    <h3>Kelola Stok</h3>
                    <p>Monitor stok barang dan hindari kehabisan atau kelebihan stok</p>
                </div>
                <div class="landing-feature-card">
                    <div class="card-icon">✨</div>
                    <h3>Insight AI</h3>
                    <p>Dapatkan rekomendasi bisnis berbasis data dan analisis mendalam</p>
                </div>
                <div class="landing-feature-card">
                    <div class="card-icon">👥</div>
                    <h3>Komunitas</h3>
                    <p>Berbagi pengalaman dan belajar dari UMKM lain</p>
                </div>
            </div>

            <div class="landing-cta">
                <p class="landing-info">Gratis untuk semua pengguna • Tanpa biaya tersembunyi</p>
                <div class="cta-button-container">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-large">
                        <span>Lanjutkan</span>
                        <span class="next-arrow">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
