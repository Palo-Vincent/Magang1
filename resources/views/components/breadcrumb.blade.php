@props(['items' => []])
<nav class="mb-4 text-sm text-slate-500" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1.5">
        @foreach ($items as $label => $url)
            <li class="flex items-center gap-1.5">
                @if (! $loop->first)<span aria-hidden="true">/</span>@endif
                @if ($url)
                    <a href="{{ $url }}" class="hover:text-teal-700">{{ $label }}</a>
                @else
                    <span class="font-medium text-slate-800">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>