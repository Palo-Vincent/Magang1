<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h1 class="text-xl font-semibold text-gray-800">Masuk</h1>
    <p class="mb-4 text-sm text-gray-500">Sistem Inventaris Sekolah</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email">Email <span class="text-red-600">*</span></x-input-label>
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
                placeholder="nama@sekolah.sch.id" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password">Password <span class="text-red-600">*</span></x-input-label>
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password"
                placeholder="Masukkan password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-teal-700 shadow-sm focus:ring-teal-600" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('register') }}">Belum punya
                akun?</a>
            <x-primary-button>Masuk</x-primary-button>
        </div>
    </form>
</x-guest-layout>