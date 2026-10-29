<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Kategori barang</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Kategori' => null]" />

    <div class="rounded-lg bg-white p-6 shadow-sm">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kategori" autofocus
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                <x-btn type="submit" variant="secondary">Cari</x-btn>
            </form>
            <div class="flex gap-2">
                <x-btn :href="route('archive.index', 'categories')" variant="secondary">Arsip</x-btn>
                <x-btn :href="route('categories.create')">Tambah kategori</x-btn>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-200 text-left text-slate-500">
                    <tr>
                        <th class="py-2 pr-4 font-medium">No</th>
                        <th class="py-2 pr-4 font-medium">Nama kategori</th>
                        <th class="py-2 pr-4 font-medium">Jumlah barang</th>
                        <th class="py-2 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="py-2 pr-4">@num($categories->firstItem() + $loop->index)</td>
                            <td class="py-2 pr-4">{{ $category->name }}</td>
                            <td class="py-2 pr-4">@num($category->items_count)</td>
                            <td class="py-2 text-right">
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="text-teal-700 hover:underline">Ubah</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                    class="ml-3 inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500">Belum ada kategori. Tambahkan kategori
                                pertama Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $categories->links() }}</div>
    </div>
</x-app-layout>