<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-semibold text-slate-800">Profil</h1>
    </x-slot>
    <x-breadcrumb :items="['Dashboard' => route('dashboard'), 'Profil' => null]" />

    <div class="max-w-3xl space-y-6">
        <div class="rounded-lg bg-white p-6 shadow-sm">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="rounded-lg bg-white p-6 shadow-sm">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</x-app-layout>