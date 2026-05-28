@extends('layouts.app')

@section('title', 'Bookify - Kelola Keuangan UMKM')

@section('content')

<div class="modern-landing">

    <!-- NAVBAR -->
    <nav class="landing-navbar">
        <div class="nav-logo">
            <div class="logo-box">B</div>
            <span>Bookify</span>
        </div>

        <a href="{{ route('login') }}" class="nav-login-btn">
            Masuk →
        </a>
    </nav>

    <!-- HERO -->
    <section class="hero-section">

        <!-- LEFT -->
        <div class="hero-left">

            <div class="hero-badge">
                ● Gratis untuk semua UMKM
            </div>

            <h1>
                Kelola keuangan <br>
                UMKM, <span>lebih mudah</span> <br>
                dari sebelumnya.
            </h1>

            <p>
                Catat transaksi, pantau stok, dan dapatkan insight bisnis
                berbasis AI — semua dalam satu platform yang dirancang
                untuk pebisnis Indonesia.
            </p>

            <div class="hero-buttons">
                <a href="{{ route('login') }}" class="btn-start">
                    Mulai Gratis →
                </a>

                <a href="#" class="btn-demo">
                    Lihat Demo
                </a>
            </div>

            <div class="hero-info">
                Tanpa kartu kredit • Tanpa biaya tersembunyi
            </div>

        </div>

        <!-- RIGHT -->
        <div class="hero-right">

            <div class="hero-main-card">
                <img src="{{ asset('img/logo/bookify-logo.png') }}" alt="">
            </div>

            <div class="hero-small-cards">

                <div class="small-card">
                    <img src="{{ asset('img/features/transaction-history.jpg') }}" alt="">
                    <h4>Catat Transaksi</h4>
                </div>

                <div class="small-card">
                    <img src="{{ asset('img/features/stock-management.jpg') }}" alt="">
                    <h4>Kelola Stok</h4>
                </div>

            </div>

        </div>

    </section>

    <!-- FEATURES -->
    <section class="feature-section">

        <h5>FITUR UNGGULAN</h5>

        <div class="feature-grid">

            <div class="feature-card">
                <img src="{{ asset('img/features/transaction-history.jpg') }}">
                <h3>Catat Transaksi</h3>
                <p>Pemasukan & pengeluaran dalam hitungan detik</p>
            </div>

            <div class="feature-card">
                <img src="{{ asset('img/features/stock-management.jpg') }}">
                <h3>Kelola Stok</h3>
                <p>Monitor stok real-time, hindari kehabisan</p>
            </div>

            <div class="feature-card">
                <img src="{{ asset('img/features/insight-ai.jpg') }}">
                <h3>Insight AI</h3>
                <p>Rekomendasi bisnis berbasis data & tren</p>
            </div>

            <div class="feature-card">
                <img src="{{ asset('img/features/community.jpg') }}">
                <h3>Komunitas</h3>
                <p>Berbagi & belajar bersama UMKM lain</p>
            </div>

        </div>

    </section>

    <!-- FOOTER -->
    <section class="landing-footer">

        <div class="footer-left">
            <div class="avatars">
                <span>A</span>
                <span>B</span>
                <span>C</span>
                <span>D</span>
            </div>

            <p>Dipercaya <strong>2.400+ UMKM</strong> se-Indonesia</p>
        </div>

        <div class="footer-rating">
            ⭐⭐⭐⭐⭐
            <span>4.9 / 5 bintang</span>
        </div>

    </section>

</div>

@endsection