<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Ajukan peminjaman</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Peminjaman saya' => route('loans.index'), 'Ajukan' => null]" />

    @php($field = 'mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600')

    <div class="max-w-xl rounded-lg bg-white p-6 shadow-sm"
        x-data="{ item: '{{ old('item_id', request('item')) }}', stocks: {{ Js::from($items->pluck('stock', 'id')) }} }">
        <form method="POST" action="{{ route('loans.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="item_id" class="block text-sm font-medium text-slate-700">Barang <span
                            class="text-red-600">*</span></label>
                    <select id="item_id" name="item_id" x-model="item" required autofocus class="{{ $field }}">
                        <option value="">Pilih barang</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-500" x-show="item && stocks[item] !== undefined">
                        Stok tersedia: <span x-text="Number(stocks[item]).toLocaleString('id-ID')"></span>
                    </p>
                    @error('item_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="quantity" class="block text-sm font-medium text-slate-700">Jumlah <span
                            class="text-red-600">*</span></label>
                    <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}"
                        placeholder="Contoh: 1" required class="{{ $field }}">
                    @error('quantity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="loan_date" class="block text-sm font-medium text-slate-700">Tanggal pinjam <span
                            class="text-red-600">*</span></label>
                    <input id="loan_date" name="loan_date" type="date" min="{{ now()->toDateString() }}"
                        value="{{ old('loan_date', now()->toDateString()) }}" required class="{{ $field }}">
                    @error('loan_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="due_date" class="block text-sm font-medium text-slate-700">Batas pengembalian <span
                            class="text-red-600">*</span></label>
                    <input id="due_date" name="due_date" type="date" min="{{ now()->toDateString() }}"
                        value="{{ old('due_date', now()->addDays(3)->toDateString()) }}" required class="{{ $field }}">
                    @error('due_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-6 flex items-center gap-3">
                <x-btn type="submit">Ajukan</x-btn>
                <x-btn :href="route('loans.index')" variant="secondary">Batal</x-btn>
            </div>
        </form>
    </div>
</x-app-layout>