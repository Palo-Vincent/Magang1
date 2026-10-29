@props(['type' => 'gray'])
@php
    $color = [
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-sky-100 text-sky-800',
        'gray' => 'bg-slate-100 text-slate-700',
    ][$type] ?? 'bg-slate-100 text-slate-700';
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$color}"]) }}>{{ $slot }}</span>