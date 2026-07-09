@extends('admin.layout')

@section('page-title', 'Manajemen Pengguna')

@section('admin-content')
<div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h2>Manajemen Pengguna</h2>
            <p>Kelola seluruh akun pengguna Bookify termasuk role dan status aktif.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-admin-primary">
            <i data-lucide="user-plus"></i>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="admin-alert admin-alert-error">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.users.index') }}" class="admin-filter-bar">
        <div class="admin-filter-field">
            <i data-lucide="search"></i>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama atau email...">
        </div>
        <select name="status" class="admin-filter-select">
            <option value="">Semua Status</option>
            <option value="active" @selected($status === 'active')>Aktif</option>
            <option value="inactive" @selected($status === 'inactive')>Non-Aktif</option>
            <option value="admin" @selected($status === 'admin')>Role Admin</option>
            <option value="user" @selected($status === 'user')>Role User</option>
        </select>
        <button type="submit" class="btn-admin-secondary">Filter</button>
        @if ($search || $status)
            <a href="{{ route('admin.users.index') }}" class="btn-admin-text">Reset</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $row)
                    <tr>
                        <td>
                            <div class="admin-user-cell">
                                <div class="admin-user-avatar">{{ strtoupper(substr($row->name, 0, 1)) }}</div>
                                <div>
                                    <div class="admin-user-name">{{ $row->name }}</div>
                                    <div class="admin-user-meta">{{ $row->business_type ?: 'UMKM' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $row->email }}</td>
                        <td>
                            <span class="role-badge role-{{ $row->role }}">{{ $row->role === 'admin' ? 'Admin' : 'User' }}</span>
                        </td>
                        <td>
                            @if ($row->is_active)
                                <span class="status-badge status-active">Aktif</span>
                            @else
                                <span class="status-badge status-inactive" title="{{ $row->deactivated_reason }}">Non-Aktif</span>
                            @endif
                        </td>
                        <td>{{ optional($row->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="admin-action-row">
                                <a href="{{ route('admin.users.edit', $row) }}" class="btn-icon" title="Edit">
                                    <i data-lucide="pencil"></i>
                                </a>
                                @if ($row->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle', $row) }}" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn-icon" title="{{ $row->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i data-lucide="{{ $row->is_active ? 'user-x' : 'user-check' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $row) }}" class="inline-form" onsubmit="return confirm('Hapus pengguna {{ $row->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="admin-self-tag">Anda</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="admin-empty">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="admin-pagination">{{ $users->links() }}</div>
</div>
@endsection

@push('styles')
<style>
.admin-page { display: flex; flex-direction: column; gap: 20px; }
.admin-page-header { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; }
.admin-page-header h2 { margin: 0 0 4px; color: #0f172a; }
.admin-page-header p { margin: 0; color: #64748b; font-size: 14px; }
.btn-admin-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: #0f172a; color: #fff; padding: 12px 18px; border-radius: 14px;
    text-decoration: none; font-weight: 600; border: none; cursor: pointer;
}
.btn-admin-primary:hover { background: #1e293b; }
.btn-admin-primary i { width: 18px; height: 18px; }
.btn-admin-secondary {
    background: #0f172a; color: #fff; padding: 12px 18px; border-radius: 14px;
    border: none; cursor: pointer; font-weight: 600;
}
.btn-admin-secondary:hover { background: #1e293b; }
.btn-admin-text {
    color: #64748b; text-decoration: none; padding: 12px 8px; font-weight: 600;
}
.admin-filter-bar {
    display: flex; gap: 12px; align-items: center; flex-wrap: wrap;
    background: #fff; padding: 14px; border-radius: 16px;
    border: 1px solid #e2e8f0;
}
.admin-filter-field {
    flex: 1; min-width: 220px; display: flex; align-items: center; gap: 8px;
    background: #f1f5f9; border-radius: 12px; padding: 10px 14px;
}
.admin-filter-field i { width: 16px; height: 16px; color: #64748b; }
.admin-filter-field input {
    border: none; background: transparent; flex: 1; outline: none; font-size: 14px;
}
.admin-filter-select {
    background: #f1f5f9; border: none; border-radius: 12px; padding: 12px 14px;
    font-size: 14px; min-width: 160px;
}
.admin-alert {
    padding: 12px 16px; border-radius: 12px; font-weight: 600;
}
.admin-alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.admin-alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.admin-table-wrap { background: #fff; border-radius: 18px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 6px 18px rgba(15,23,42,.04); }
.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th, .admin-table td { padding: 14px 16px; text-align: left; font-size: 14px; }
.admin-table th { background: #f8fafc; color: #475569; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: .05em; border-bottom: 1px solid #e2e8f0; }
.admin-table tbody tr + tr { border-top: 1px solid #f1f5f9; }
.admin-user-cell { display: flex; align-items: center; gap: 12px; }
.admin-user-avatar {
    width: 36px; height: 36px; border-radius: 50%; background: #0f172a; color: #fff;
    display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;
}
.admin-user-name { font-weight: 600; color: #0f172a; }
.admin-user-meta { font-size: 12px; color: #94a3b8; }
.role-badge, .status-badge {
    display: inline-block; padding: 4px 10px; border-radius: 999px;
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
}
.role-admin { background: #fef3c7; color: #92400e; }
.role-user { background: #e0f2fe; color: #075985; }
.status-active { background: #dcfce7; color: #166534; }
.status-inactive { background: #fee2e2; color: #991b1b; }
.admin-action-row { display: flex; align-items: center; gap: 8px; }
.btn-icon {
    background: #f1f5f9; border: none; width: 34px; height: 34px; border-radius: 10px;
    display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: #0f172a;
    text-decoration: none;
}
.btn-icon:hover { background: #e2e8f0; }
.btn-icon i { width: 16px; height: 16px; }
.btn-icon-danger { color: #b91c1c; }
.btn-icon-danger:hover { background: #fee2e2; }
.inline-form { display: inline; margin: 0; }
.admin-self-tag { font-size: 11px; color: #64748b; font-weight: 600; padding: 4px 10px; background: #f1f5f9; border-radius: 999px; }
.admin-empty { text-align: center; color: #94a3b8; padding: 32px 0; }
.admin-pagination { margin-top: 8px; }
</style>
@endpush
