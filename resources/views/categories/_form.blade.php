@csrf
<div>
    <label for="name" class="block text-sm font-medium text-slate-700">Nama kategori <span
            class="text-red-600">*</span></label>
    <input id="name" name="name" type="text" value="{{ old('name', $category->name ?? '') }}"
        placeholder="Contoh: Alat Lab" required autofocus
        class="mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
    @error('name')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div class="mt-6 flex items-center gap-3">
    <x-btn type="submit">Simpan</x-btn>
    <x-btn :href="route('categories.index')" variant="secondary">Batal</x-btn>
</div>