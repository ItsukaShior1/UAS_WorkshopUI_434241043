@extends('admin.layout')

@section('page-title', 'Edit Pengguna')

@section('admin-content')
<div class="admin-form-page">
    <div class="admin-page-header">
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn-admin-text">Kembali</a>
            <h2>Edit Pengguna</h2>
            <p>Perbarui data akun {{ $user->name }}.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul>@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="admin-form-card">
        @csrf
        @method('PUT')
        <div class="form-row">
            <label>Nama <span class="req">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="admin-input">
        </div>
        <div class="form-row">
            <label>Email <span class="req">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="admin-input">
        </div>
        <div class="form-row">
            <label>Tipe Bisnis</label>
            <input type="text" name="business_type" value="{{ old('business_type', $user->business_type) }}" class="admin-input">
        </div>
        <div class="form-grid">
            <div class="form-row">
                <label>Role <span class="req">*</span></label>
                <select name="role" required class="admin-input" @disabled($user->id === auth()->id())>
                    <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                </select>
                @if ($user->id === auth()->id())
                    <small class="form-help">Role akun sendiri tidak dapat diubah.</small>
                @endif
            </div>
            <div class="form-row">
                <label>Status</label>
                <label class="switch">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active)) @disabled($user->id === auth()->id())>
                    <span class="switch-slider"></span>
                    <span class="switch-label">{{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}</span>
                </label>
                @if (! $user->is_active && $user->deactivated_reason)
                    <small class="form-help">Alasan: {{ $user->deactivated_reason }}</small>
                @endif
            </div>
        </div>
        @if (! $user->is_active)
            <div class="form-row">
                <label>Alasan Non-Aktif</label>
                <input type="text" name="deactivated_reason" value="{{ old('deactivated_reason', $user->deactivated_reason) }}" maxlength="200" class="admin-input">
            </div>
        @endif
        <div class="form-row">
            <label>Password Baru <small>(kosongkan jika tidak diubah)</small></label>
            <input type="password" name="password" minlength="8" class="admin-input">
        </div>
        <div class="form-row">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" minlength="8" class="admin-input">
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-admin-text">Batal</a>
            <button type="submit" class="btn-admin-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.admin-form-page { display: flex; flex-direction: column; gap: 20px; max-width: 720px; }
.admin-page-header h2 { margin: 4px 0 4px; color: #0f172a; }
.admin-page-header p { margin: 0; color: #64748b; font-size: 14px; }
.admin-form-card { background: #fff; border-radius: 18px; padding: 28px; border: 1px solid #e2e8f0; box-shadow: 0 6px 18px rgba(15,23,42,.04); display: flex; flex-direction: column; gap: 16px; }
.form-row { display: flex; flex-direction: column; gap: 8px; }
.form-row label { font-weight: 600; color: #1e293b; font-size: 14px; }
.form-row small { color: #64748b; font-size: 12px; font-weight: 400; }
.req { color: #dc2626; }
.form-help { color: #94a3b8; font-size: 12px; }
.admin-input { border: 1px solid #cbd5e1; border-radius: 12px; padding: 12px 14px; font-size: 14px; font-family: inherit; background: #fff; }
.admin-input:focus { outline: none; border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,.08); }
.admin-input:disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.switch { display: inline-flex; align-items: center; gap: 12px; cursor: pointer; }
.switch input { display: none; }
.switch-slider { width: 44px; height: 24px; background: #cbd5e1; border-radius: 999px; position: relative; transition: .2s; }
.switch-slider::after { content: ""; position: absolute; top: 3px; left: 3px; width: 18px; height: 18px; background: #fff; border-radius: 50%; transition: .2s; }
.switch input:checked + .switch-slider { background: #16a34a; }
.switch input:checked + .switch-slider::after { transform: translateX(20px); }
.switch-label { color: #1e293b; font-size: 14px; font-weight: 600; }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; align-items: center; padding-top: 8px; border-top: 1px solid #f1f5f9; margin-top: 8px; }
.btn-admin-primary { background: #0f172a; color: #fff; padding: 12px 20px; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; }
.btn-admin-primary:hover { background: #1e293b; }
.btn-admin-text { color: #64748b; text-decoration: none; padding: 12px 14px; font-weight: 600; }
.admin-alert { padding: 12px 16px; border-radius: 12px; }
.admin-alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.admin-alert ul { margin: 0; padding-left: 18px; }
</style>
@endpush
