@extends('layouts.dashboard')

@section('page-title', 'Hubungkan Marketplace')

@section('dashboard-content')
<div class="mk-connect-shell">
    <a href="{{ route('marketplace.index') }}" class="btn-link">&larr; Kembali</a>
    <div class="mk-connect-card">
        <h2>Hubungkan Akun Marketplace</h2>
        <p>API key akan dienkripsi dan disimpan aman di server.</p>

        @if ($errors->any())
            <div class="alert alert-error"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        @if (empty($platforms))
            <div class="alert alert-success">Semua platform sudah terhubung.</div>
        @else
            <form method="POST" action="{{ route('marketplace.store') }}" class="mk-form">
                @csrf
                <div class="form-row">
                    <label>Platform <span class="req">*</span></label>
                    <select name="platform" required class="admin-input">
                        @foreach ($platforms as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <label>Nama Toko</label>
                    <input type="text" name="shop_name" maxlength="120" class="admin-input" placeholder="Toko Saya">
                </div>
                <div class="form-row">
                    <label>API Key <span class="req">*</span></label>
                    <input type="text" name="api_key" required minlength="8" maxlength="255" class="admin-input" placeholder="Minimal 8 karakter">
                    <small class="muted">Akan dienkripsi sebelum disimpan.</small>
                </div>
                <button class="btn-primary" type="submit">Hubungkan</button>
            </form>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.mk-connect-shell { display: flex; flex-direction: column; gap: 14px; max-width: 520px; }
.btn-link { color: #0f5a34; font-weight: 600; text-decoration: none; }
.mk-connect-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 26px; display: flex; flex-direction: column; gap: 10px; }
.mk-connect-card h2 { margin: 0; color: #123122; }
.mk-connect-card p { margin: 0; color: #54685f; }
.mk-form { display: flex; flex-direction: column; gap: 12px; }
.form-row { display: flex; flex-direction: column; gap: 4px; }
.form-row label { font-weight: 600; font-size: 13px; color: #1e293b; }
.req { color: #dc2626; }
.admin-input { border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 14px; font-family: inherit; }
.muted { color: #94a3b8; font-size: 12px; }
.btn-primary { background: #0f5a34; color: #fff; padding: 12px; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; }
.btn-primary:hover { background: #0a4326; }
.alert { padding: 12px 16px; border-radius: 12px; }
.alert-error { background: #fee2e2; color: #991b1b; }
.alert-success { background: #dcfce7; color: #166534; }
.alert ul { margin: 0; padding-left: 18px; }
</style>
@endpush
