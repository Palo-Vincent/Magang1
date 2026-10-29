<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Activity log</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Activity log' => null]" />

    <div class="rounded-lg bg-white p-6 shadow-sm">
        <form method="GET" class="mb-4 flex flex-wrap gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari deskripsi atau nama pengguna"
                autofocus
                class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
            <select name="action"
                class="rounded-md border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600">
                <option value="">Semua aksi</option>
                @foreach ($actions as $action)
                    <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                @endforeach
            </select>
            <x-btn type="submit" variant="secondary">Filter</x-btn>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-200 text-left text-slate-500">
                    <tr>
                        <th class="py-2 pr-4 font-medium">Waktu</th>
                        <th class="py-2 pr-4 font-medium">Pengguna</th>
                        <th class="py-2 pr-4 font-medium">Aksi</th>
                        <th class="py-2 font-medium">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap py-2 pr-4">@tglwaktu($log->created_at)</td>
                            <td class="py-2 pr-4">{{ $log->user->name }}</td>
                            <td class="py-2 pr-4"><x-badge>{{ $log->action }}</x-badge></td>
                            <td class="py-2">{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500">Belum ada aktivitas yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $logs->links() }}</div>
    </div>
</x-app-layout>