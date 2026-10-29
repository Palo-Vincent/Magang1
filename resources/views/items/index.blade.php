<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Data barang</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Barang' => null]" />

    <div class="rounded-lg bg-white p-6 shadow-sm">
        <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <form method="GET" class="flex flex-wrap gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau kode" autofocus
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                <select name="category_id"
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <select name="condition"
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                    <option value="">Semua kondisi</option>
                    @foreach (['baik', 'rusak ringan', 'rusak berat'] as $condition)
                        <option value="{{ $condition }}" @selected(request('condition') === $condition)>
                            {{ ucfirst($condition) }}
                        </option>
                    @endforeach
                </select>
                <select name="availability"
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                    <option value="">Semua stok</option>
                    <option value="tersedia" @selected(request('availability') === 'tersedia')>Stok tersedia</option>
                    <option value="habis" @selected(request('availability') === 'habis')>Stok habis</option>
                </select>
                <x-btn type="submit" variant="secondary">Filter</x-btn>
            </form>
            <div class="flex gap-2">
                <x-btn :href="route('archive.index', 'items')" variant="secondary">Arsip</x-btn>
                <x-btn :href="route('items.create')">Tambah barang</x-btn>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-200 text-left text-slate-500">
                    <tr>
                        <th class="py-2 pr-4 font-medium">No</th>
                        <th class="py-2 pr-4 font-medium">Kode</th>
                        <th class="py-2 pr-4 font-medium">Nama barang</th>
                        <th class="py-2 pr-4 font-medium">Kategori</th>
                        <th class="py-2 pr-4 font-medium">Stok</th>
                        <th class="py-2 pr-4 font-medium">Kondisi</th>
                        <th class="py-2 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($items as $item)
                        <tr>
                            <td class="py-2 pr-4">@num($items->firstItem() + $loop->index)</td>
                            <td class="py-2 pr-4">{{ $item->code }}</td>
                            <td class="py-2 pr-4">{{ $item->name }}</td>
                            <td class="py-2 pr-4">{{ $item->category->name }}</td>
                            <td class="py-2 pr-4">@num($item->stock)</td>
                            <td class="py-2 pr-4"><x-badge
                                    :type="\App\Support\Format::badge($item->condition)">{{ ucfirst($item->condition) }}</x-badge>
                            </td>
                            <td class="py-2 text-right">
                                <a href="{{ route('items.edit', $item) }}" class="text-teal-700 hover:underline">Ubah</a>
                                <form method="POST" action="{{ route('items.destroy', $item) }}" class="ml-3 inline"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-500">Tidak ada barang yang cocok dengan
                                pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $items->links() }}</div>
    </div>
</x-app-layout>