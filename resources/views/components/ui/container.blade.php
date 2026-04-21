@props([
    'size' => 'lg', // sm, md, lg, xl
    'class' => '',
])

@php
    $paddingMap = [
        'sm' => 'px-4',
        'md' => 'px-6',
        'lg' => 'px-8',
        'xl' => 'lg:px-[120px]',
    ];
    $paddingClass = $paddingMap[$size] ?? $paddingMap['lg'];
@endphp

<div class="container mx-auto {{ $paddingClass }} {{ $class }}">
    {{ $slot }}
</div>
