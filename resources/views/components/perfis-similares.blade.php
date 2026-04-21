@props(['perfil' => null])

@php
    $similarProfiles = null;
    if($perfil) {
        $similarProfiles = \App\Models\Profile::where('id', '!=', $perfil->id)
            ->where('city', $perfil->city)
            ->where('active', true)
            ->with(['user', 'images', 'physicalAttributes'])
            ->limit(5)
            ->get();
    }
@endphp

<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h2 class="text-white font-bold text-xl">Perfis Similares</h2>
        <a href="{{ route('explorar') }}" class="text-primary font-bold text-sm hover:text-primary/80 transition-colors">
            Ver todos
        </a>
    </div>

    {{-- Grid de Perfis --}}
    @if($similarProfiles && $similarProfiles->isNotEmpty())
    <div class="grid grid-cols-5 gap-4">
        @foreach($similarProfiles as $similarPerfil)
        <a href="{{ route('perfil.ver', $similarPerfil->id) }}" 
           class="bg-zinc-900 border border-zinc-700 rounded-xl overflow-hidden hover:border-primary/50 hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 cursor-pointer">
            @php
                $userPlan = $similarPerfil->user ? $similarPerfil->user->plan : 'free';
                $isPremium = in_array($userPlan, ['premium', 'gold']);
                $physical = $similarPerfil->physicalAttributes;
            @endphp
            {{-- Imagem --}}
            <div class="relative h-[200px] bg-zinc-800 flex flex-col justify-between p-3">
                @if($similarPerfil->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $similarPerfil->images->first()->url) }}" alt="{{ $similarPerfil->name }}"
                         class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                @else
                    <div class="absolute inset-0 w-full h-full flex items-center justify-center bg-zinc-800">
                        <svg class="w-12 h-12 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                @endif
                {{-- Badge de verificação e plano --}}
                <div class="relative flex justify-between">
                    @if($similarPerfil->verified)
                        <div class="bg-green-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                            💎
                        </div>
                    @endif
                    @if($isPremium)
                        <div class="bg-primary text-black rounded-full px-2 py-0.5 text-[10px] font-black uppercase">
                            {{ $userPlan }}
                        </div>
                    @endif
                </div>
                <div class="relative flex items-center gap-1 text-white text-[10px] font-semibold">
                    📍 {{ $similarPerfil->city }}, {{ $similarPerfil->state }}
                </div>
            </div>

            {{-- Info --}}
            <div class="p-4 flex flex-col gap-2">
                {{-- Nome e idade --}}
                <div class="flex flex-col gap-1">
                    <h3 class="text-white font-black text-sm">{{ $similarPerfil->name }}</h3>
                    <p class="text-zinc-400 text-[11px]">{{ $similarPerfil->age }} anos</p>
                </div>

                {{-- Stats --}}
                <div class="flex gap-2 text-[10px]">
                    <span class="text-primary">⭐ {{ number_format($similarPerfil->rating, 1) }}</span>
                    <span class="text-zinc-400">👁 {{ number_format($similarPerfil->views) }}</span>
                </div>

                {{-- Descrição --}}
                @if($similarPerfil->description)
                    <p class="text-zinc-400 text-[10px] line-clamp-2">{{ $similarPerfil->description }}</p>
                @endif
            </div>
        </a>
        @endforeach
    </div>
    @else
        <p class="text-zinc-500 text-sm text-center py-8">Nenhum perfil similar encontrado.</p>
    @endif
</div>
