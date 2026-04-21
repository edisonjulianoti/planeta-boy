@extends('layouts.app')

@section('title', $perfil->name . ' - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950">
    <x-ui.container size="lg" class="py-16 max-w-7xl">

        <div class="grid grid-cols-1 lg:grid-cols-[440px_1fr] gap-16">

            {{-- Coluna Esquerda: Galeria + Ações + Info --}}
            <div class="space-y-8">

                {{-- Galeria Principal --}}
                <div class="relative rounded-2xl overflow-hidden h-[400px] sm:h-[560px] p-4 bg-zinc-900">
                    @if($perfil->images->isNotEmpty())
                        @php $mainImage = $perfil->images()->where('is_main', true)->first() ?? $perfil->images->first(); @endphp
                        <div class="relative w-full h-full rounded-xl overflow-hidden">
                            <img id="img-principal" src="{{ asset('storage/' . $mainImage->url) }}" alt="{{ $perfil->name }}"
                                 class="w-full h-full object-cover">
                            {{-- Badge de Verificação --}}
                            @if($perfil->verified)
                            <div class="absolute top-4 right-4">
                                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                                </div>
                            </div>
                            @endif
                        </div>
                        {{-- Dots de Navegação --}}
                        @if($perfil->images->count() > 1)
                        <div class="absolute bottom-4 left-4 right-4 flex justify-center gap-2">
                            @foreach($perfil->images->sortBy('order') as $index => $imagem)
                                <button type="button"
                                        onclick="document.getElementById('img-principal').src='{{ asset('storage/' . $imagem->url) }}'"
                                        class="w-2 h-2 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-zinc-500' }} transition-colors">
                                </button>
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-zinc-800 rounded-xl">
                            <svg class="w-24 h-24 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                    @endif
                </div>

                {{-- Menu de Ações --}}
                <div class="space-y-4">
                    {{-- Botão WhatsApp --}}
                    @if($perfil->user->phone)
                    <a href="https://wa.me/55{{ preg_replace('/\D/', '', $perfil->user->phone) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 py-4 bg-green-500 hover:bg-green-600 text-white font-black uppercase tracking-wider rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                        WhatsApp
                    </a>
                    @endif
                    {{-- Ícones de ação --}}
                    <div class="flex gap-4">
                        <button class="flex-1 py-4 bg-zinc-900 border border-zinc-800 rounded-xl hover:border-primary transition-colors">
                            <svg class="w-6 h-6 mx-auto text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                        <button class="flex-1 py-4 bg-zinc-900 border border-zinc-800 rounded-xl hover:border-primary transition-colors">
                            <svg class="w-6 h-6 mx-auto text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Disponibilidade --}}
                <div class="bg-zinc-900 rounded-xl p-6 space-y-4">
                    <h3 class="text-white font-black uppercase tracking-wider text-sm">Disponibilidade</h3>
                    <p class="text-zinc-400 text-sm flex items-center gap-2">
                        <span>📍</span> {{ $perfil->city }}, {{ $perfil->state }}
                    </p>
                    @if($perfil->availability)
                    <p class="text-zinc-400 text-sm flex items-center gap-2">
                        <span>⏱</span> Responde em até 5 minutos
                    </p>
                    @endif
                </div>

                {{-- Estatísticas --}}
                <div class="bg-zinc-900 rounded-xl p-6 space-y-4">
                    <h3 class="text-white font-black uppercase tracking-wider text-sm">Estatísticas</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-400 text-sm">Visualizações</span>
                        <span class="text-white font-bold text-sm">{{ number_format($perfil->views) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-400 text-sm">Avaliação</span>
                        <span class="text-primary font-bold text-sm">⭐ {{ number_format($perfil->rating, 1) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-400 text-sm">Status</span>
                        <span class="text-green-500 font-bold text-sm">🟢 Online</span>
                    </div>
                </div>

                {{-- Aviso de Segurança --}}
                <div class="rounded-xl p-6 space-y-3 bg-primary/10 border border-primary">
                    <p class="text-primary text-sm text-center leading-relaxed">
                        ⚠️ Mantenha a conversa na plataforma. Não compartilhe dados pessoais antes de confirmar o encontro.
                    </p>
                </div>

                {{-- Informações de Atendimento --}}
                <div class="bg-zinc-900 rounded-xl p-6 space-y-4">
                    <h3 class="text-white font-black uppercase tracking-wider text-base">ℹ️ Informações de Atendimento</h3>
                    <div class="space-y-3 text-zinc-400 text-sm">
                        <p><span class="text-white font-medium">Local</span><br>Ambiente climatizado, discreto e seguro. Estacionamento disponível na região.</p>
                        <p><span class="text-white font-medium">Atendimento</span><br>Por agendamento prévio. Confirmar com 30 minutos de antecedência.</p>
                    </div>
                </div>
            </div>

            {{-- Coluna Direita: Info Principal --}}
            <div class="space-y-8">

                {{-- Info Wrap --}}
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <h1 class="text-3xl font-black text-white">{{ $perfil->name }}</h1>
                        @if($perfil->verified)
                        <span class="text-2xl">✔️</span>
                        @endif
                    </div>
                    <p class="text-zinc-400 text-sm">{{ $perfil->age }} anos • {{ $perfil->city }} - {{ $perfil->state }}</p>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="text-primary font-bold">⭐ {{ number_format($perfil->rating, 1) }}</span>
                        <span class="text-zinc-400">👁 {{ number_format($perfil->views) }}</span>
                        <span class="text-green-500 font-bold">🟢 Online</span>
                    </div>
                </div>

                {{-- Sobre Mim --}}
                @if($perfil->description)
                <div class="space-y-4">
                    <h2 class="text-white font-bold text-lg">Sobre mim</h2>
                    <p class="text-zinc-400 text-sm leading-relaxed">{{ $perfil->description }}</p>
                </div>
                @endif

                {{-- Seção de Verificação --}}
                @if($perfil->verified)
                <div class="rounded-xl p-6 space-y-4 bg-green-500/10 border border-green-500/30 flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="space-y-1">
                        <p class="text-white font-bold text-sm">Perfil Verificado</p>
                        <p class="text-green-400 text-xs">Identidade confirmada através de documentos válidos.</p>
                    </div>
                </div>
                @endif

                {{-- Características --}}
                @if($perfil->physicalAttributes)
                <div class="space-y-4">
                    <h2 class="text-white font-bold text-lg">Características</h2>
                    @php $af = $perfil->physicalAttributes; @endphp
                    <div class="grid grid-cols-2 gap-4">
                        @if($af->height)
                        <div class="bg-zinc-900 rounded-xl p-4 space-y-1">
                            <p class="text-zinc-500 text-xs">📏 Altura</p>
                            <p class="text-white font-bold text-sm">{{ $af->height }}cm</p>
                        </div>
                        @endif
                        @if($af->weight)
                        <div class="bg-zinc-900 rounded-xl p-4 space-y-1">
                            <p class="text-zinc-500 text-xs">⚖️ Peso</p>
                            <p class="text-white font-bold text-sm">{{ $af->weight }}kg</p>
                        </div>
                        @endif
                        @if($af->hair_color)
                        <div class="bg-zinc-900 rounded-xl p-4 space-y-1">
                            <p class="text-zinc-500 text-xs">👱 Cabelo</p>
                            <p class="text-white font-bold text-sm capitalize">{{ $af->hair_color }}</p>
                        </div>
                        @endif
                        @if($af->eye_color)
                        <div class="bg-zinc-900 rounded-xl p-4 space-y-1">
                            <p class="text-zinc-500 text-xs">👁️ Olhos</p>
                            <p class="text-white font-bold text-sm capitalize">{{ $af->eye_color }}</p>
                        </div>
                        @endif
                        @if($af->ethnicity)
                        <div class="bg-zinc-900 rounded-xl p-4 space-y-1 col-span-2">
                            <p class="text-zinc-500 text-xs">👤 Etnia</p>
                            <p class="text-white font-bold text-sm capitalize">{{ $af->ethnicity }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Localização --}}
                <div class="space-y-4">
                    <h2 class="text-white font-bold text-lg">Localização</h2>
                    <div class="bg-zinc-900 rounded-xl p-6 space-y-2">
                        <p class="text-white font-bold text-sm">{{ $perfil->city }}, {{ $perfil->state }}</p>
                        <p class="text-zinc-400 text-xs">Atendo em local próprio e realizo viagens.</p>
                    </div>
                </div>

                {{-- Serviços --}}
                @if($perfil->services->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-white font-bold text-lg">Serviços</h2>
                        <span class="text-zinc-500 text-xs">{{ $perfil->services->count() }} serviços</span>
                    </div>
                    @php $grupos = $perfil->services->groupBy('category'); @endphp
                    @foreach($grupos as $categoria => $servicos)
                        <div class="space-y-3">
                            <p class="text-white font-bold text-sm">{{ ucfirst($categoria) }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($servicos as $servico)
                                    <span class="px-4 py-2 bg-zinc-900 border border-zinc-700 rounded-full text-zinc-300 text-sm hover:border-primary transition-colors cursor-pointer">{{ $servico->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Comentários e Avaliações - Seção Full Width --}}
        <div class="mt-16">
            <x-comentarios :perfil="$perfil" :comments="$perfil->comments" />
        </div>

        {{-- Perfis Similares - Seção Full Width --}}
        <div class="mt-16">
            <x-perfis-similares :perfil="$perfil" />
        </div>
    </x-ui.container>
</div>
@endsection
