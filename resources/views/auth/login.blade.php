@extends('layouts.app')

@section('title', 'Masuk - Bookify')

@section('content')
<!-- Login Page -->
<div id="loginPage" class="auth-page active">
    <div class="auth-container login-single-container">

        <div class="auth-right login-centered">
            <form action="{{ route('login.store') }}" method="POST" id="loginForm" class="auth-form">
                @csrf

                <div class="login-header">
                    <h1 class="login-logo">Bookify</h1>

                    <h2>Masuk</h2>
                    <p class="auth-subtitle">
                        Masuk untuk melanjutkan ke Bookify
                    </p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <div class="form-group">
                    <label for="email">Email</label>

                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Masukkan email" 
                        value="{{ old('email') }}" 
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>

                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password" 
                        required
                    >
                </div>
                
                <a href="#" class="forgot-password">
                    Lupa Password?
                </a>
                
                <button type="submit" class="btn btn-primary btn-block">
                    Masuk
                </button>
                
                <div class="auth-divider">
                    <span>Demo Akun Tersedia</span>
                </div>
                
                <div class="demo-accounts">
                    <p>Email: admin@bookify.com</p>
                    <p>Password: password123</p>
                </div>
                
                <a href="{{ route('landing') }}" class="btn btn-text btn-block login-back-btn">
                    ← Kembali ke Landing Page
                </a>

            </form>
        </div>

    </div>
</div>
@endsection