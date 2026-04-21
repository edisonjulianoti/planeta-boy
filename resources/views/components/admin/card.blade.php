@props([
    'title' => null,
    'padding' => 'p-6',
])

<div class="bg-zinc-900 border border-zinc-800 rounded-2xl {{ $padding }}">
    @if($title)
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2">
                <div class="w-1 h-4 bg-primary rounded-full"></div>
                {{ $title }}
            </h2>
            {{ $header ?? '' }}
        </div>
    @endif
    
    {{ $slot }}
</div>
