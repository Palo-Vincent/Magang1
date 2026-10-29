@csrf
@php($field = 'mt-1 block w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600')
@php($isEdit = isset($user))
<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Nama <span
                class="text-red-600">*</span></label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}"
            placeholder="Contoh: Budi Santoso" required autofocus class="{{ $field }}">
        @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-slate-700">Email <span
                class="text-red-600">*</span></label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}"
            placeholder="nama@sekolah.sch.id" required class="{{ $field }}">
        @error('email')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="role" class="block text-sm font-medium text-slate-700">Role <span
                class="text-red-600">*</span></label>
        <select id="role" name="role" required class="{{ $field }}">
            <option value="user" @selected(old('role', $user->role ?? 'user') === 'user')>User</option>
            <option value="admin" @selected(old('role', $user->role ?? 'user') === 'admin')>Admin</option>
        </select>
        @error('role')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="password" class="block text-sm font-medium text-slate-700">
            Password @unless ($isEdit)<span class="text-red-600">*</span>@endunless
        </label>
        <input id="password" name="password" type="password"
            placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}" @unless ($isEdit)
            required @endunless autocomplete="new-password" class="{{ $field }}">
        @error('password')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
<div class="mt-6 flex items-center gap-3">
    <x-btn type="submit">Simpan</x-btn>
    <x-btn :href="route('users.index')" variant="secondary">Batal</x-btn>
</div>