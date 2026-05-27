@props([
    'type' => 'info', // success, error, warning, info
    'message' => '',
    'dismissible' => false,
    'icon' => true,
])

@php
    $classes = match($type) {
        'success' => 'bg-green-600/10 border-green-600/30 text-green-400',
        'error' => 'bg-red-600/10 border-red-600/30 text-red-400',
        'warning' => 'bg-matrix-600/10 border-matrix-600/30 text-matrix-400',
        'info' => 'bg-blue-600/10 border-blue-600/30 text-blue-400',
        default => 'bg-zinc-600/10 border-zinc-600/30 text-zinc-400',
    };

    $iconSvg = match($type) {
        'success' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>',
        'error' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        'warning' => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        'info' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
        default => '',
    };
@endphp

<div class="{{ $classes }} border rounded-xl px-5 py-3 mb-6 text-sm font-bold flex items-center gap-3 {{ $dismissible ? 'pr-12' : '' }}">
    @if($icon)
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {{ $iconSvg }}
        </svg>
    @endif
    
    {{ $message }}
    
    @if($dismissible)
        <button onclick="this.parentElement.remove()" class="absolute right-3 top-1/2 -translate-y-1/2 hover:opacity-70 transition-opacity cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    @endif
</div>
