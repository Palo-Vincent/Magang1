<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Pengguna</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Pengguna' => null]" />

    <div class="rounded-lg bg-white p-6 shadow-sm">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email" autofocus
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                <select name="role"
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                    <option value="">Semua role</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="user" @selected(request('role') === 'user')>User</option>
                </select>
                <x-btn type="submit" variant="secondary">Filter</x-btn>
            </form>
            <div class="flex gap-2">
                <x-btn :href="route('archive.index', 'users')" variant="secondary">Arsip</x-btn>
                <x-btn :href="route('users.create')">Tambah akun</x-btn>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-200 text-left text-slate-500">
                    <tr>
                        <th class="py-2 pr-4 font-medium">No</th>
                        <th class="py-2 pr-4 font-medium">Nama</th>
                        <th class="py-2 pr-4 font-medium">Email</th>
                        <th class="py-2 pr-4 font-medium">Role</th>
                        <th class="py-2 pr-4 font-medium">Terdaftar</th>
                        <th class="py-2 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr>
                            <td class="py-2 pr-4">@num($users->firstItem() + $loop->index)</td>
                            <td class="py-2 pr-4">{{ $user->name }}</td>
                            <td class="py-2 pr-4">{{ $user->email }}</td>
                            <td class="py-2 pr-4"><x-badge :type="$user->isAdmin() ? 'info' : 'gray'">{{ $user->isAdmin() ? 'Admin' : 'User' }}</x-badge></td>
                            <td class="py-2 pr-4">@tgl($user->created_at)</td>
                            <td class="py-2 text-right">
                                <a href="{{ route('users.edit', $user) }}" class="text-teal-700 hover:underline">Ubah</a>
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="ml-3 inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada akun yang cocok dengan
                                pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>