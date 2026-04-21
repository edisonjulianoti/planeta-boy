@props([
    'variant' => 'neutral', // success, warning, danger, neutral, primary
    'size' => 'sm', // sm, md
    'text' => '',
])

@php
    $classes = match($variant) {
        'success' => 'bg-green-500/10 text-green-400 border-green-500/30',
        'warning' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
        'danger' => 'bg-red-500/10 text-red-400 border-red-500/30',
        'primary' => 'bg-primary/10 text-primary border-primary/30',
        'neutral' => 'bg-zinc-800 text-zinc-400 border-zinc-700',
        default => 'bg-zinc-800 text-zinc-400 border-zinc-700',
    };

    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        default => 'px-2 py-0.5 text-xs',
    };
@endphp

<span class="inline-block {{ $classes }} {{ $sizeClasses }} rounded-full font-black uppercase border transition-all">
    {{ $text ?? $slot }}
</span>
