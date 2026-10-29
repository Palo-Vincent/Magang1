<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->q, fn ($query, $search) => $query->where(function ($w) use ($search) {
                $w->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            }))
            ->when($request->role, fn ($query, $value) => $query->where('role', $value))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = new User();
        $user->forceFill($data + ['email_verified_at' => now()])->save();

        ActivityLog::record('create_user', "Menambah akun {$user->name} ({$user->role})");

        return redirect()->route('users.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return back()->withInput()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        ActivityLog::record('update_user', "Mengubah akun {$user->name} ({$user->role})");

        return redirect()->route('users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->loans()->whereIn('status', ['menunggu', 'dipinjam', 'terlambat'])->exists()) {
            return back()->with('error', 'Akun tidak dapat dihapus karena masih memiliki peminjaman aktif.');
        }

        $user->delete();

        ActivityLog::record('delete_user', "Menghapus akun {$user->name}");

        return redirect()->route('users.index')->with('success', 'Akun berhasil diarsipkan.');
    }
}