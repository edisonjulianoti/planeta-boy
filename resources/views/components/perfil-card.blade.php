@foreach($perfis as $perfil)
@php
    $userPlan = $perfil->user ? $perfil->user->plan : 'free';
    $isPremium = in_array($userPlan, ['premium', 'gold']);
    $physical = $perfil->physicalAttributes;
@endphp
<a href="{{ route('perfil.ver', $perfil->id) }}"
   class="group bg-zinc-900 border border-zinc-800 rounded-lg overflow-hidden hover:border-primary/50 hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 cursor-pointer flex flex-col h-full">
    {{-- Imagem --}}
    <div class="relative h-[260px] bg-zinc-800 flex flex-col justify-between p-3">
        @if($perfil->images->isNotEmpty())
            <img src="{{ asset('storage/' . $perfil->images->first()->url) }}" alt="{{ $perfil->name }}"
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="absolute inset-0 w-full h-full flex items-center justify-center bg-zinc-800">
                <svg class="w-16 h-16 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
        @endif
        {{-- Badge de verificação e localização --}}
        <div class="relative flex justify-between">
            @if($perfil->verified)
                <div class="bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                    💎
                </div>
            @endif
            @if($isPremium)
                <div class="bg-primary text-black rounded-full px-2 py-1 text-xs font-black uppercase">
                    {{ $userPlan }}
                </div>
            @endif
        </div>
        <div class="relative flex items-center gap-1 text-white text-xs font-semibold">
            📍 {{ $perfil->city }}, {{ $perfil->state }}
        </div>
    </div>

    {{-- Info --}}
    <div class="p-4 flex-1 flex flex-col gap-3">
        {{-- Nome e idade --}}
        <div class="flex flex-col gap-1">
            <h3 class="text-white font-black text-[18px] group-hover:text-primary transition-colors">{{ $perfil->name }}</h3>
            <p class="text-zinc-400 text-[12px]">{{ $perfil->age }} anos</p>
        </div>

        {{-- Stats --}}
        <div class="flex gap-3 text-xs">
            <span class="text-primary">⭐ {{ number_format($perfil->rating, 1) }}</span>
            <span class="text-zinc-400">👁 {{ number_format($perfil->views) }}</span>
        </div>

        {{-- Descrição --}}
        @if($perfil->description)
            <p class="text-zinc-400 text-[11px] line-clamp-2">{{ $perfil->description }}</p>
        @endif

        {{-- Tags de características --}}
        @if($physical)
            <div class="flex flex-wrap gap-2">
                @if($physical->height)
                    <span class="bg-zinc-800 text-zinc-400 text-[10px] font-semibold px-2 py-1 rounded">
                        {{ $physical->height }}cm
                    </span>
                @endif
                @if($physical->hair_color)
                    <span class="bg-zinc-800 text-zinc-400 text-[10px] font-semibold px-2 py-1 rounded">
                        {{ $physical->hair_color }}
                    </span>
                @endif
                @if($physical->eye_color)
                    <span class="bg-zinc-800 text-zinc-400 text-[10px] font-semibold px-2 py-1 rounded">
                        {{ $physical->eye_color }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Botão --}}
        <div class="pt-4 flex justify-center mt-auto">
            <span class="bg-yellow-500 text-black font-black text-[14px] px-6 py-2 rounded-full group-hover:bg-yellow-400 transition-colors">Ver Perfil</span>
        </div>
    </div>
</a>
@endforeach
