@props([
    'padding' => 'sm', // sm, md, lg
    'class' => '',
])

@php
    $paddingMap = [
        'sm' => 'py-16',
        'md' => 'py-20',
        'lg' => 'py-24',
    ];
    $paddingClass = $paddingMap[$padding] ?? $paddingMap['sm'];
@endphp

<section class="{{ $paddingClass }} {{ $class }}">
    {{ $slot }}
</section>
