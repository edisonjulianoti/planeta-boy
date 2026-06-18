@props([
    'name' => '',
    'id' => null,
    'type' => 'text',
    'label' => '',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'icon' => null,
    'error' => null,
    'autocomplete' => null,
    'disabled' => false,
    'variant' => 'default', // default, dark
])

@php
    $inputId = $id ?? $name;
    $hasError = $error !== null || ($errors && $errors->has($name));
    $borderColor = $hasError ? 'border-red-500 focus:border-red-500' : ($variant === 'dark' ? 'border-zinc-800 focus:border-primary' : 'border-zinc-700 focus:border-primary');
    $bgColor = $variant === 'dark' ? 'bg-zinc-950' : 'bg-zinc-800';
@endphp

<div>
    @if($label)
        <label for="{{ $inputId }}" class="block text-xs sm:text-sm font-medium text-zinc-300 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-400">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {{ $icon }}
                </svg>
            </div>
        @endif

        @if($type === 'select')
            <select
                id="{{ $inputId }}"
                name="{{ $name }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                class="block w-full {{ $icon ? 'pl-10' : 'pl-3' }} pr-3 py-2 sm:py-2.5 {{ $bgColor }} border {{ $borderColor }} rounded-lg text-white text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                {{ $slot }}
            </select>
        @else
            <input
                id="{{ $inputId }}"
                name="{{ $name }}"
                type="{{ $type }}"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $autocomplete ? 'autocomplete="' . $autocomplete . '"' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $attributes->get('inputmode') ? 'inputmode="' . $attributes->get('inputmode') . '"' : '' }}
                {{ $attributes->get('step') ? 'step="' . $attributes->get('step') . '"' : '' }}
                {{ $attributes->get('min') ? 'min="' . $attributes->get('min') . '"' : '' }}
                {{ $attributes->get('max') ? 'max="' . $attributes->get('max') . '"' : '' }}
                class="block w-full {{ $icon ? 'pl-10' : 'pl-3' }} pr-3 py-2 sm:py-2.5 {{ $bgColor }} border {{ $borderColor }} rounded-lg text-white placeholder-zinc-500 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
        @endif
    </div>

    @if($error)
        <p class="mt-1 text-xs sm:text-sm text-red-400">{{ $error }}</p>
    @elseif($errors && $errors->has($name))
        <p class="mt-1 text-xs sm:text-sm text-red-400">{{ $errors->first($name) }}</p>
    @endif
</div>
