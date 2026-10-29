<x-guest-layout>
    <h1 class="text-xl font-semibold text-gray-800">Daftar</h1>
    <p class="mb-4 text-sm text-gray-500">Buat akun untuk meminjam barang inventaris</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name">Nama <span class="text-red-600">*</span></x-input-label>
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')"
                placeholder="Contoh: Budi Santoso" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email">Email <span class="text-red-600">*</span></x-input-label>
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
                placeholder="nama@sekolah.sch.id" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password">Password <span class="text-red-600">*</span></x-input-label>
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password"
                placeholder="Minimal 8 karakter" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation">Konfirmasi password <span
                    class="text-red-600">*</span></x-input-label>
            <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password"
                name="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-between">
            <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('login') }}">Sudah punya
                akun?</a>
            <x-primary-button>Daftar</x-primary-button>
        </div>
    </form>
</x-guest-layout>