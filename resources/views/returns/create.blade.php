<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Proses pengembalian</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Peminjaman' => route('loans.index'), 'Pengembalian' => null]" />

    @php($field = 'mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600')

    <div class="max-w-xl rounded-lg bg-white p-6 shadow-sm">
        <dl class="mb-6 grid grid-cols-2 gap-x-4 gap-y-2 rounded-md bg-slate-50 p-4 text-sm">
            <dt class="text-slate-500">Peminjam</dt>
            <dd>{{ $loan->user->name }}</dd>
            <dt class="text-slate-500">Barang</dt>
            <dd>{{ $loan->item->name }} ({{ $loan->item->code }})</dd>
            <dt class="text-slate-500">Jumlah</dt>
            <dd>@num($loan->quantity)</dd>
            <dt class="text-slate-500">Tanggal pinjam</dt>
            <dd>@tgl($loan->loan_date)</dd>
            <dt class="text-slate-500">Jatuh tempo</dt>
            <dd>@tgl($loan->due_date)</dd>
            <dt class="text-slate-500">Status</dt>
            <dd><x-badge :type="\App\Support\Format::badge($loan->status)">{{ ucfirst($loan->status) }}</x-badge></dd>
        </dl>

        <form method="POST" action="{{ route('returns.store', $loan) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="returned_at" class="block text-sm font-medium text-slate-700">Tanggal pengembalian <span
                            class="text-red-600">*</span></label>
                    <input id="returned_at" name="returned_at" type="date" min="{{ $loan->loan_date->toDateString() }}"
                        max="{{ now()->toDateString() }}" value="{{ old('returned_at', now()->toDateString()) }}"
                        placeholder="Pilih tanggal" required autofocus class="{{ $field }}">
                    @error('returned_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="condition_on_return" class="block text-sm font-medium text-slate-700">Kondisi saat
                        dikembalikan <span class="text-red-600">*</span></label>
                    <select id="condition_on_return" name="condition_on_return" required class="{{ $field }}">
                        @foreach (['baik', 'rusak ringan', 'rusak berat'] as $condition)
                            <option value="{{ $condition }}" @selected(old('condition_on_return', 'baik') === $condition)>
                                {{ ucfirst($condition) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Kondisi "Rusak berat" tidak akan menambah stok barang.</p>
                    @error('condition_on_return')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700">Catatan</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Ada lecet pada bodi"
                        class="{{ $field }}">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-6 flex items-center gap-3">
                <x-btn type="submit">Simpan pengembalian</x-btn>
                <x-btn :href="route('loans.index')" variant="secondary">Batal</x-btn>
            </div>
        </form>
    </div>
</x-app-layout>