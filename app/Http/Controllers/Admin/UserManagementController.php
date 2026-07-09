<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->orderByDesc('created_at');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'admin') {
                $query->where('role', User::ROLE_ADMIN);
            } elseif ($status === 'user') {
                $query->where('role', User::ROLE_USER);
            }
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'business_type' => ['nullable', 'string', 'max:60'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_USER])],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Pilih role pengguna.',
            'role.in' => 'Role tidak valid.',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'business_type' => $data['business_type'] ?? 'micro',
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active', true),
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($user->id)],
            'business_type' => ['nullable', 'string', 'max:60'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_USER])],
            'is_active' => ['nullable', 'boolean'],
            'deactivated_reason' => ['nullable', 'string', 'max:200'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan pengguna lain.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($user->id === Auth::id() && $data['role'] !== User::ROLE_ADMIN) {
            return back()->withErrors(['role' => 'Anda tidak dapat mengubah role akun sendiri dari admin.'])->withInput();
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->business_type = $data['business_type'] ?? $user->business_type;
        $user->role = $data['role'];

        $activeFlag = $request->boolean('is_active');
        $user->is_active = $activeFlag;
        $user->deactivated_reason = $activeFlag ? null : ($data['deactivated_reason'] ?? 'Dinonaktifkan oleh admin.');

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun sendiri.']);
        }

        $user->is_active = ! $user->is_active;
        $user->deactivated_reason = $user->is_active ? null : 'Dinonaktifkan oleh admin.';
        $user->save();

        return back()->with('success', $user->is_active ? 'Pengguna diaktifkan kembali.' : 'Pengguna dinonaktifkan.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun sendiri.']);
        }

        if ($user->posts()->exists() || $user->articles()->exists()) {
            $user->is_active = false;
            $user->deactivated_reason = 'Dinonaktifkan oleh admin (memiliki konten).';
            $user->save();
            return back()->with('success', 'Pengguna memiliki konten terkait sehingga dinonaktifkan, bukan dihapus.');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
