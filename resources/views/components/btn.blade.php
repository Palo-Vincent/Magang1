@props(['variant' => 'primary', 'href' => null, 'type' => 'button'])
@php
    $base = 'inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2';
    $styles = [
        'primary' => 'bg-teal-700 text-white hover:bg-teal-800',
        'secondary' => 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
    ][$variant] ?? 'bg-teal-700 text-white hover:bg-teal-800';
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "{$base} {$styles}"]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "{$base} {$styles}"]) }}>{{ $slot }}</button>
@endif