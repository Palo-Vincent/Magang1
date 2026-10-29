<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Dashboard</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => null]" />

    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">
        @foreach ($stats as $label => $value)
            <div class="rounded-lg bg-white p-4 shadow-sm">
                <p class="text-xs text-slate-500">{{ $label }}</p>
                <p class="mt-1 text-2xl font-semibold text-slate-900">@num($value)</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 {{ $isAdmin ? 'xl:grid-cols-3' : '' }}">
        <div class="rounded-lg bg-white p-6 shadow-sm {{ $isAdmin ? 'xl:col-span-2' : '' }}">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold text-slate-800">Peminjaman terbaru</h2>
                <div class="flex items-center gap-3">
                    @unless ($isAdmin)
                        <x-btn :href="route('loans.create')">Ajukan peminjaman</x-btn>
                    @endunless
                    <a href="{{ route('loans.index') }}" class="text-sm text-teal-700 hover:underline">Lihat semua</a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-b border-slate-200 text-left text-slate-500">
                        <tr>
                            @if ($isAdmin)
                            <th class="py-2 pr-4 font-medium">Peminjam</th>@endif
                            <th class="py-2 pr-4 font-medium">Barang</th>
                            <th class="py-2 pr-4 font-medium">Jumlah</th>
                            <th class="py-2 pr-4 font-medium">Jatuh tempo</th>
                            <th class="py-2 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentLoans as $loan)
                            <tr>
                                @if ($isAdmin)
                                <td class="py-2 pr-4">{{ $loan->user->name }}</td>@endif
                                <td class="py-2 pr-4">{{ $loan->item->name }}</td>
                                <td class="py-2 pr-4">@num($loan->quantity)</td>
                                <td class="py-2 pr-4">@tgl($loan->due_date)</td>
                                <td class="py-2"><x-badge
                                        :type="\App\Support\Format::badge($loan->status)">{{ ucfirst($loan->status) }}</x-badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500">Belum ada peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($isAdmin)
            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Barang paling sering dipinjam</h2>
                <ul class="divide-y divide-slate-100 text-sm">
                    @forelse ($popularItems as $item)
                        <li class="flex items-center justify-between py-2">
                            <span>{{ $item->name }}</span>
                            <span class="text-slate-500">@num($item->loans_count)x</span>
                        </li>
                    @empty
                        <li class="py-2 text-slate-500">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>
</x-app-layout>