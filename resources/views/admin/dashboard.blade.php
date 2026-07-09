@extends('admin.layout')

@section('page-title', 'Dashboard Admin')

@section('admin-content')
<div class="admin-dashboard">
    <div class="admin-welcome">
        <div>
            <p class="admin-kicker">Selamat datang kembali</p>
            <h1>{{ auth()->user()->name }}</h1>
            <p class="admin-subtitle">Kelola pengguna, artikel, paket langganan, dan aktivitas langganan Bookify dari satu tempat.</p>
        </div>
    </div>

    <div class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-blue"><i data-lucide="users"></i></div>
            <div class="admin-stat-body">
                <span class="admin-stat-label">Total Pengguna</span>
                <span class="admin-stat-value">{{ number_format($stats['total_users']) }}</span>
                <span class="admin-stat-foot">Seluruh akun terdaftar</span>
            </div>
        </div>

        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-green"><i data-lucide="credit-card"></i></div>
            <div class="admin-stat-body">
                <span class="admin-stat-label">Langganan Aktif</span>
                <span class="admin-stat-value">{{ number_format($stats['active_subscriptions']) }}</span>
                <span class="admin-stat-foot">Subscription berjalan</span>
            </div>
        </div>

        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-amber"><i data-lucide="file-text"></i></div>
            <div class="admin-stat-body">
                <span class="admin-stat-label">Artikel Published</span>
                <span class="admin-stat-value">{{ number_format($stats['published_articles']) }}</span>
                <span class="admin-stat-foot">Tayang untuk pengguna</span>
            </div>
        </div>

        <div class="admin-stat-card">
            <div class="admin-stat-icon admin-stat-icon-violet"><i data-lucide="package"></i></div>
            <div class="admin-stat-body">
                <span class="admin-stat-label">Paket Aktif</span>
                <span class="admin-stat-value">{{ number_format($stats['active_plans']) }}</span>
                <span class="admin-stat-foot">Tersedia untuk dibeli</span>
            </div>
        </div>
    </div>

    
</div>
@endsection

@push('styles')
<style>
.admin-dashboard { display: flex; flex-direction: column; gap: 24px; }
.admin-welcome {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #fff;
    border-radius: 18px;
    padding: 28px 32px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, .12);
}
.admin-kicker {
    margin: 0 0 6px;
    text-transform: uppercase;
    letter-spacing: .12em;
    font-size: 11px;
    color: #94a3b8;
    font-weight: 700;
}
.admin-welcome h1 { margin: 0 0 8px; font-size: 28px; }
.admin-subtitle { margin: 0; color: #cbd5e1; line-height: 1.55; max-width: 720px; }
.admin-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}
.admin-stat-card {
    background: #fff;
    border-radius: 18px;
    padding: 20px;
    display: flex;
    gap: 16px;
    align-items: center;
    box-shadow: 0 6px 18px rgba(15, 23, 42, .06);
    border: 1px solid #e2e8f0;
}
.admin-stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    flex: 0 0 52px;
}
.admin-stat-icon i { width: 24px; height: 24px; }
.admin-stat-icon-blue { background: #2563eb; }
.admin-stat-icon-green { background: #16a34a; }
.admin-stat-icon-amber { background: #d97706; }
.admin-stat-icon-violet { background: #7c3aed; }
.admin-stat-body { display: flex; flex-direction: column; gap: 2px; }
.admin-stat-label { color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: .08em; font-weight: 600; }
.admin-stat-value { color: #0f172a; font-size: 26px; font-weight: 700; }
.admin-stat-foot { color: #94a3b8; font-size: 12px; }
.admin-info-card {
    background: #fff;
    border-radius: 18px;
    padding: 24px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 6px 18px rgba(15, 23, 42, .05);
}
.admin-info-card h3 { margin: 0 0 12px; color: #0f172a; }
.admin-info-list { margin: 0; padding-left: 0; list-style: none; display: flex; flex-direction: column; gap: 10px; color: #334155; }
.admin-info-list li { display: flex; align-items: flex-start; gap: 10px; line-height: 1.5; }
.dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-top: 7px; flex: 0 0 10px; }
.dot-green { background: #16a34a; }
.dot-amber { background: #d97706; }
</style>
@endpush
