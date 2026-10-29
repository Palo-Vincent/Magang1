@csrf
@php($field = 'mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600')
<div class="space-y-4">
    <div>
        <label for="category_id" class="block text-sm font-medium text-slate-700">Kategori <span
                class="text-red-600">*</span></label>
        <select id="category_id" name="category_id" required autofocus class="{{ $field }}">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id ?? '') == $category->id)>
                    {{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Nama barang <span
                class="text-red-600">*</span></label>
        <input id="name" name="name" type="text" value="{{ old('name', $item->name ?? '') }}"
            placeholder="Contoh: Mikroskop" required class="{{ $field }}">
        @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="code" class="block text-sm font-medium text-slate-700">Kode barang <span
                class="text-red-600">*</span></label>
        <input id="code" name="code" type="text" value="{{ old('code', $item->code ?? '') }}"
            placeholder="Contoh: LAB-001" required class="{{ $field }}">
        @error('code')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="stock" class="block text-sm font-medium text-slate-700">Stok <span
                class="text-red-600">*</span></label>
        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $item->stock ?? '') }}"
            placeholder="Contoh: 5" required class="{{ $field }}">
        @error('stock')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="condition" class="block text-sm font-medium text-slate-700">Kondisi <span
                class="text-red-600">*</span></label>
        <select id="condition" name="condition" required class="{{ $field }}">
            @foreach (['baik', 'rusak ringan', 'rusak berat'] as $condition)
                <option value="{{ $condition }}" @selected(old('condition', $item->condition ?? 'baik') === $condition)>
                    {{ ucfirst($condition) }}</option>
            @endforeach
        </select>
        @error('condition')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
<div class="mt-6 flex items-center gap-3">
    <x-btn type="submit">Simpan</x-btn>
    <x-btn :href="route('items.index')" variant="secondary">Batal</x-btn>
</div>