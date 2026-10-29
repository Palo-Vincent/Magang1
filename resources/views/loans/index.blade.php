<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">{{ $isAdmin ? 'Peminjaman' : 'Peminjaman saya' }}</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), ($isAdmin ? 'Peminjaman' : 'Peminjaman saya') => null]" />

    <div class="rounded-lg bg-white p-6 shadow-sm">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap gap-2">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="{{ $isAdmin ? 'Cari peminjam atau barang' : 'Cari barang' }}" autofocus
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                <select name="status"
                    class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                    <option value="">Semua status</option>
                    @foreach (['menunggu', 'dipinjam', 'terlambat', 'dikembalikan', 'ditolak'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <x-btn type="submit" variant="secondary">Filter</x-btn>
            </form>
            @unless ($isAdmin)
                <x-btn :href="route('loans.create')">Ajukan peminjaman</x-btn>
            @endunless
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-200 text-left text-slate-500">
                    <tr>
                        <th class="py-2 pr-4 font-medium">No</th>
                        @if ($isAdmin)
                            <th class="py-2 pr-4 font-medium">Peminjam</th>
                        @endif
                        <th class="py-2 pr-4 font-medium">Barang</th>
                        <th class="py-2 pr-4 font-medium">Jumlah</th>
                        <th class="py-2 pr-4 font-medium">Tanggal pinjam</th>
                        <th class="py-2 pr-4 font-medium">Jatuh tempo</th>
                        <th class="py-2 pr-4 font-medium">Tanggal kembali</th>
                        <th class="py-2 pr-4 font-medium">Status</th>
                        <th class="py-2 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($loans as $loan)
                        <tr>
                            <td class="py-2 pr-4">@num($loans->firstItem() + $loop->index)</td>
                            @if ($isAdmin)
                                <td class="py-2 pr-4">{{ $loan->user->name }}</td>
                            @endif
                            <td class="py-2 pr-4">{{ $loan->item->name }}</td>
                            <td class="py-2 pr-4">@num($loan->quantity)</td>
                            <td class="py-2 pr-4">@tgl($loan->loan_date)</td>
                            <td class="py-2 pr-4">@tgl($loan->due_date)</td>
                            <td class="py-2 pr-4">@tgl(optional($loan->returnLog)->returned_at)</td>
                            <td class="py-2 pr-4">
                                <x-badge
                                    :type="\App\Support\Format::badge($loan->status)">{{ ucfirst($loan->status) }}</x-badge>
                                @if ($loan->status === 'ditolak' && $loan->rejection_note)
                                    <p class="mt-1 text-xs text-slate-500">{{ $loan->rejection_note }}</p>
                                @endif
                            </td>
                            <td class="whitespace-nowrap py-2 text-right">
                                @if ($isAdmin && $loan->status === 'menunggu')
                                    <form method="POST" action="{{ route('loans.approve', $loan) }}" class="inline"
                                        onsubmit="return confirm('Setujui peminjaman ini? Stok akan dikurangi.')">
                                        @csrf
                                        <button type="submit" class="text-teal-700 hover:underline">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('loans.reject', $loan) }}" class="ml-3 inline"
                                        onsubmit="const n = prompt('Alasan penolakan (opsional):'); if (n === null) return false; this.rejection_note.value = n;">
                                        @csrf
                                        <input type="hidden" name="rejection_note">
                                        <button type="submit" class="text-red-600 hover:underline">Tolak</button>
                                    </form>
                                @elseif ($isAdmin && in_array($loan->status, ['dipinjam', 'terlambat']))
                                    <a href="{{ route('returns.create', $loan) }}" class="text-teal-700 hover:underline">Proses
                                        pengembalian</a>
                                @elseif (!$isAdmin && $loan->status === 'menunggu')
                                    <form method="POST" action="{{ route('loans.destroy', $loan) }}" class="inline"
                                        onsubmit="return confirm('Batalkan pengajuan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Batalkan</button>
                                    </form>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 9 : 8 }}" class="py-6 text-center text-slate-500">Belum ada data
                                peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $loans->links() }}</div>
    </div>
</x-app-layout>