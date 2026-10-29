<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Arsip {{ $label }}</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Arsip ' . $label => null]" />

    <div class="rounded-lg bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-slate-200 text-left text-slate-500">
                    <tr>
                        <th class="py-2 pr-4 font-medium">No</th>
                        <th class="py-2 pr-4 font-medium">Nama</th>
                        <th class="py-2 pr-4 font-medium">Dihapus pada</th>
                        <th class="py-2 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($records as $record)
                        <tr>
                            <td class="py-2 pr-4">@num($records->firstItem() + $loop->index)</td>
                            <td class="py-2 pr-4">{{ $record->name }}</td>
                            <td class="py-2 pr-4">@tgl($record->deleted_at)</td>
                            <td class="py-2 text-right">
                                <form method="POST" action="{{ route('archive.restore', [$type, $record->id]) }}"
                                    onsubmit="return confirm('Pulihkan data ini?')">
                                    @csrf
                                    <button type="submit" class="text-teal-700 hover:underline">Pulihkan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500">Arsip kosong.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $records->links() }}</div>
    </div>
</x-app-layout>