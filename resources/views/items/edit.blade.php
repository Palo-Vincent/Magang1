<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Ubah barang</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Barang' => route('items.index'), 'Ubah' => null]" />

    <div class="max-w-xl rounded-lg bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('items.update', $item) }}">
            @method('PUT')
            @include('items._form')
        </form>
    </div>
</x-app-layout>