@extends('layouts.app')

@section('title', 'Meu Perfil - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950">
    <div class="container mx-auto px-4 py-12 max-w-4xl">

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-black text-white uppercase italic">Meu <span class="text-primary">Perfil</span></h1>
            <div class="flex items-center gap-2 px-3 py-1.5 bg-primary/10 border border-primary/30 rounded-full">
                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7z"/></svg>
                <span class="text-primary text-xs font-black uppercase">{{ ucfirst(auth()->user()->plan) }}</span>
            </div>
        </div>

        {{-- Mensagem de sucesso --}}
        @if(session('success'))
            <x-alerts.alert type="success" :message="session('success')" />
        @endif

        {{-- Erros de validação --}}
        @if($errors->any())
            <x-alerts.alert type="error" :message="$errors->first()" />
        @endif

        {{-- Dados da Conta --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 mb-6" id="dados-conta-container">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2">
                    <div class="w-1 h-4 bg-primary rounded-full"></div>Dados da Conta
                </h2>
                <button id="btn-editar-perfil" class="text-sm text-primary hover:text-primary/80 font-medium transition-colors flex items-center gap-1.5 cursor-pointer">
                    <svg id="icon-editar" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span id="texto-btn">Editar</span>
                </button>
            </div>

            {{-- Visualização --}}
            <div id="visualizacao-dados" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <dt class="text-zinc-500 text-xs uppercase tracking-wider mb-1">Nome</dt>
                    <dd class="text-white font-medium">{{ auth()->user()->name }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500 text-xs uppercase tracking-wider mb-1">E-mail</dt>
                    <dd class="text-white font-medium">{{ auth()->user()->email }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500 text-xs uppercase tracking-wider mb-1">Telefone</dt>
                    <dd class="text-white font-medium">{{ auth()->user()->phone ?: 'Não informado' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-zinc-500 text-xs uppercase tracking-wider mb-1">Bio</dt>
                    <dd class="text-white font-medium">{{ auth()->user()->bio ?: 'Não informada' }}</dd>
                </div>
            </div>

            {{-- Formulário de Edição --}}
            <form id="formulario-edicao" method="POST" action="{{ route('perfil.atualizar') }}" class="hidden grid-cols-1 sm:grid-cols-2 gap-4">
                @csrf
                @method('PUT')

                <x-forms.input name="name" label="Nome" :value="old('name', auth()->user()->name)" required variant="dark" />

                <x-forms.input name="email" type="email" label="E-mail" :value="old('email', auth()->user()->email)" required variant="dark" />

                <x-forms.input name="phone" label="Telefone" :value="old('phone', auth()->user()->phone)" placeholder="(00) 00000-0000" variant="dark" />

                <div class="sm:col-span-2">
                    <label class="block text-zinc-500 text-xs uppercase tracking-wider mb-1.5">Bio</label>
                    <textarea name="bio" rows="3" placeholder="Fale um pouco sobre você..."
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all resize-none">{{ old('bio', auth()->user()->bio) }}</textarea>
                </div>

                <div class="sm:col-span-2 flex justify-end gap-3">
                    <button type="button" id="btn-cancelar" class="px-4 py-2.5 text-zinc-400 hover:text-white text-sm font-medium rounded-lg transition-colors cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-primary hover:brightness-110 text-primary-foreground text-sm font-bold rounded-lg transition-all uppercase tracking-wider cursor-pointer">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        {{-- Perfil --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2">
                    <div class="w-1 h-4 bg-primary rounded-full"></div>Meu Perfil
                </h2>
                @if($perfil)
                    <a href="{{ route('perfil.editar', $perfil->id) }}" class="text-sm text-primary hover:text-primary/80 font-medium transition-colors flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Editar
                    </a>
                @else
                    <a href="{{ route('perfil.criar') }}" class="text-sm text-primary hover:text-primary/80 font-medium transition-colors flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        Criar Perfil
                    </a>
                @endif
            </div>

            @if(!$perfil)
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-zinc-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <p class="text-zinc-500 mb-4">Você ainda não tem perfil cadastrado.</p>
                    <a href="{{ route('perfil.criar') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:brightness-110 text-primary-foreground text-sm font-bold rounded-lg transition-all uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        Criar meu perfil
                    </a>
                </div>
            @else
                <a href="{{ route('perfil.ver', $perfil->id) }}" class="group flex items-center gap-4 p-4 bg-zinc-800 hover:bg-zinc-700 rounded-xl transition-all border border-zinc-700 hover:border-primary/50 cursor-pointer">
                    <div class="w-14 h-14 rounded-lg bg-zinc-700 overflow-hidden shrink-0">
                        @if($perfil->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $perfil->images->first()->url) }}" alt="{{ $perfil->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-white font-bold group-hover:text-primary transition-colors">{{ $perfil->name }}</p>
                        <p class="text-zinc-400 text-sm">{{ $perfil->city }}, {{ $perfil->state }} · {{ $perfil->age }} anos</p>
                    </div>
                </a>

                {{-- Seção de Localização --}}
                <div class="mt-4 p-4 bg-zinc-800 rounded-xl border border-zinc-700">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-white text-sm font-bold uppercase tracking-wider">Localização</span>
                        </div>
                        @if($perfil->location_enabled)
                            <span class="flex items-center gap-1 text-xs text-green-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Ativa
                            </span>
                        @else
                            <span class="flex items-center gap-1 text-xs text-zinc-500">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                Inativa
                            </span>
                        @endif
                    </div>
                    <p class="text-zinc-400 text-xs mb-3">
                        @if($perfil->location_enabled)
                            Sua localização está ativa e você aparecerá nos resultados de busca por proximidade.
                        @else
                            Ative sua localização para aparecer nos resultados de busca por proximidade de outros usuários.
                        @endif
                    </p>
                    <button type="button" id="btn-atualizar-localizacao" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-zinc-700 hover:bg-zinc-600 border border-zinc-600 rounded-lg text-zinc-300 hover:text-white text-sm transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Atualizar minha localização</span>
                    </button>
                    <p id="localizacao-erro" class="hidden mt-2 text-xs text-red-400"></p>
                </div>
            @endif
        </div>

        <div class="mt-6 text-center">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-zinc-500 hover:text-red-400 text-sm transition-colors cursor-pointer">Sair da conta</button>
            </form>
        </div>
    </x-ui.container>
</div>

<script>
    (function() {
        const btnEditar = document.getElementById('btn-editar-perfil');
        const btnCancelar = document.getElementById('btn-cancelar');
        const visualizacao = document.getElementById('visualizacao-dados');
        const formulario = document.getElementById('formulario-edicao');
        const textoBtn = document.getElementById('texto-btn');
        let editando = false;

        function toggleEdicao() {
            editando = !editando;

            if (editando) {
                visualizacao.classList.add('hidden');
                formulario.classList.remove('hidden');
                formulario.classList.add('grid');
                textoBtn.textContent = 'Cancelar';
            } else {
                visualizacao.classList.remove('hidden');
                formulario.classList.add('hidden');
                formulario.classList.remove('grid');
                textoBtn.textContent = 'Editar';
            }
        }

        if (btnEditar) {
            btnEditar.addEventListener('click', toggleEdicao);
        }

        if (btnCancelar) {
            btnCancelar.addEventListener('click', toggleEdicao);
        }

        // Atualizar localização
        const btnAtualizarLocalizacao = document.getElementById('btn-atualizar-localizacao');
        const localizacaoErro = document.getElementById('localizacao-erro');

        if (btnAtualizarLocalizacao) {
            btnAtualizarLocalizacao.addEventListener('click', () => {
                if (!navigator.geolocation) {
                    localizacaoErro.textContent = 'Geolocalização não é suportada pelo seu navegador';
                    localizacaoErro.classList.remove('hidden');
                    return;
                }

                btnAtualizarLocalizacao.disabled = true;
                btnAtualizarLocalizacao.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Obtendo localização...</span>';
                localizacaoErro.classList.add('hidden');

                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                        try {
                            const response = await fetch('{{ route('localizacao.atualizar') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    latitude: position.coords.latitude,
                                    longitude: position.coords.longitude
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                location.reload();
                            } else {
                                throw new Error(data.message || 'Erro ao atualizar localização');
                            }
                        } catch (error) {
                            btnAtualizarLocalizacao.disabled = false;
                            btnAtualizarLocalizacao.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span>Atualizar minha localização</span>';
                            localizacaoErro.textContent = error.message || 'Erro ao atualizar localização';
                            localizacaoErro.classList.remove('hidden');
                        }
                    },
                    (error) => {
                        btnAtualizarLocalizacao.disabled = false;
                        btnAtualizarLocalizacao.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span>Atualizar minha localização</span>';
                        
                        let mensagem = 'Erro ao obter localização';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                mensagem = 'Permissão de localização negada';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                mensagem = 'Localização indisponível';
                                break;
                            case error.TIMEOUT:
                                mensagem = 'Tempo limite excedido';
                                break;
                        }
                        localizacaoErro.textContent = mensagem;
                        localizacaoErro.classList.remove('hidden');
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            });
        }
    })();
</script>
@endsection
