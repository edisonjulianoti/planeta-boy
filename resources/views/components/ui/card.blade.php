@props([
    'padding' => 'md', // sm, md, lg
    'radius' => 'md', // sm, md, lg
    'class' => '',
])

@php
    $paddingMap = [
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];
    $radiusMap = [
        'sm' => 'rounded-lg',
        'md' => 'rounded-xl',
        'lg' => 'rounded-2xl',
    ];
    $paddingClass = $paddingMap[$padding] ?? $paddingMap['md'];
    $radiusClass = $radiusMap[$radius] ?? $radiusMap['md'];
@endphp

<div class="bg-zinc-900 border border-zinc-800 {{ $paddingClass }} {{ $radiusClass }} {{ $class }}">
    {{ $slot }}
</div>
