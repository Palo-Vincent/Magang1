<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Inventaris Sekolah') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-900">
    @php
        $authUser = auth()->user();
        $menu = $authUser->isAdmin()
            ? [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => 'dashboard'],
                ['label' => 'Kategori', 'route' => 'categories.index', 'match' => 'categories.*'],
                ['label' => 'Barang', 'route' => 'items.index', 'match' => 'items.*'],
                ['label' => 'Peminjaman', 'route' => 'loans.index', 'match' => 'loans.*'],
                ['label' => 'Pengembalian', 'route' => 'returns.index', 'match' => 'returns.*'],
                ['label' => 'Activity log', 'route' => 'activity-logs.index', 'match' => 'activity-logs.*'],
                ['label' => 'Pengguna', 'route' => 'users.index', 'match' => 'users.*'],
            ]
            : [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => 'dashboard'],
                ['label' => 'Daftar barang', 'route' => 'catalog.index', 'match' => 'catalog.*'],
                ['label' => 'Ajukan peminjaman', 'route' => 'loans.create', 'match' => 'loans.create'],
                ['label' => 'Peminjaman saya', 'route' => 'loans.index', 'match' => 'loans.index'],
            ];
        $menu = collect($menu)->filter(fn($entry) => Route::has($entry['route']))->all();
    @endphp
    <div x-data="{ open: false }" class="min-h-screen lg:flex">
        <div @click="open = false" :class="open ? '' : 'hidden'" class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

        <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-200 bg-white transition-transform lg:static lg:translate-x-0">
            <div class="flex h-16 items-center border-b border-slate-200 px-6 text-base font-semibold text-teal-800">
                Inventaris Sekolah
            </div>
            <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                @foreach ($menu as $entry)
                    <a href="{{ route($entry['route']) }}"
                        class="block rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs($entry['match']) ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-100' }}">
                        {{ $entry['label'] }}
                    </a>
                @endforeach
            </nav>
            <div class="border-t border-slate-200 p-4 text-sm">
                <p class="truncate font-medium">{{ $authUser->name }}</p>
                <p class="mb-3 text-xs text-slate-500">{{ $authUser->isAdmin() ? 'Admin' : 'User' }}</p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="text-slate-600 hover:text-teal-700">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-600 hover:text-red-600">Keluar</button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex h-16 items-center gap-3 border-b border-slate-200 bg-white px-4 sm:px-6">
                <button type="button" @click="open = true"
                    class="rounded-md p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Buka menu">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-1">{{ $header ?? '' }}</div>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                @if (session('success'))
                    <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                        {{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                        {{ session('error') }}</div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>