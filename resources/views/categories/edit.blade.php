<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Ubah kategori</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Kategori' => route('categories.index'), 'Ubah' => null]" />

    <div class="max-w-xl rounded-lg bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @method('PUT')
            @include('categories._form')
        </form>
    </div>
</x-app-layout>