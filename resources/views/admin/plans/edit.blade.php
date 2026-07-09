@extends('admin.layout')

@section('page-title', 'Edit Paket')

@section('admin-content')
<div class="admin-form-page">
    <div class="admin-page-header">
        <div>
            <a href="{{ route('admin.plans.index') }}" class="btn-admin-text">Kembali</a>
            <h2>Edit Paket</h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul>@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="admin-form-card">
        @csrf
        @method('PUT')
        @include('admin.plans._form', ['plan' => $plan])
        <div class="form-actions">
            <a href="{{ route('admin.plans.index') }}" class="btn-admin-text">Batal</a>
            <button type="submit" class="btn-admin-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.admin-form-page { display: flex; flex-direction: column; gap: 20px; max-width: 720px; }
.admin-page-header h2 { margin: 4px 0; color: #0f172a; }
.admin-form-card { background: #fff; border-radius: 18px; padding: 28px; border: 1px solid #e2e8f0; box-shadow: 0 6px 18px rgba(15,23,42,.04); display: flex; flex-direction: column; gap: 16px; }
.form-row { display: flex; flex-direction: column; gap: 6px; }
.form-row label { font-weight: 600; color: #1e293b; font-size: 14px; }
.form-row .admin-input, .form-row textarea { border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 14px; font-family: inherit; }
.form-row textarea { min-height: 110px; resize: vertical; }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-help { color: #94a3b8; font-size: 12px; }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 8px; border-top: 1px solid #f1f5f9; }
.btn-admin-primary { background: #0f172a; color: #fff; padding: 12px 20px; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; }
.btn-admin-text { color: #64748b; text-decoration: none; padding: 12px 14px; font-weight: 600; }
.admin-alert { padding: 12px 16px; border-radius: 12px; }
.admin-alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.admin-alert ul { margin: 0; padding-left: 18px; }
</style>
@endpush
