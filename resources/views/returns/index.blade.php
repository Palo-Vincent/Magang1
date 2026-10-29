<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Pengembalian</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Pengembalian' => null]" />

    <div class="rounded-lg bg-white p-6 shadow-sm">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari peminjam atau barang" autofocus
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                <select name="condition"
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                    <option value="">Semua kondisi</option>
                    @foreach (['baik', 'rusak ringan', 'rusak berat'] as $condition)
                        <option value="{{ $condition }}" @selected(request('condition') === $condition)>
                            {{ ucfirst($condition) }}</option>
                    @endforeach
                </select>
                <x-btn type="submit" variant="secondary">Filter</x-btn>
            </form>
            <x-btn :href="route('loans.index', ['status' => 'dipinjam'])" variant="secondary">Lihat peminjaman
                aktif</x-btn>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-200 text-left text-slate-500">
                    <tr>
                        <th class="py-2 pr-4 font-medium">No</th>
                        <th class="py-2 pr-4 font-medium">Peminjam</th>
                        <th class="py-2 pr-4 font-medium">Barang</th>
                        <th class="py-2 pr-4 font-medium">Jumlah</th>
                        <th class="py-2 pr-4 font-medium">Tanggal kembali</th>
                        <th class="py-2 pr-4 font-medium">Kondisi</th>
                        <th class="py-2 font-medium">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($returnLogs as $log)
                        <tr>
                            <td class="py-2 pr-4">@num($returnLogs->firstItem() + $loop->index)</td>
                            <td class="py-2 pr-4">{{ $log->loan->user->name }}</td>
                            <td class="py-2 pr-4">{{ $log->loan->item->name }}</td>
                            <td class="py-2 pr-4">@num($log->loan->quantity)</td>
                            <td class="py-2 pr-4">@tgl($log->returned_at)</td>
                            <td class="py-2 pr-4"><x-badge
                                    :type="\App\Support\Format::badge($log->condition_on_return)">{{ ucfirst($log->condition_on_return) }}</x-badge>
                            </td>
                            <td class="py-2">{{ $log->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-500">Belum ada data pengembalian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $returnLogs->links() }}</div>
    </div>
</x-app-layout>