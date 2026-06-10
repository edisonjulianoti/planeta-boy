@extends('layouts.app')

@section('title', 'Sobre o Planeta Boy - PLANETA BOYS')

@section('description', 'Conheça o Planeta Boy — plataforma moderna, discreta e organizada para divulgação de perfis adultos. Respeito, transparência e privacidade para anunciantes e visitantes.')

@section('content')
<div class="min-h-screen bg-black">
    <x-ui.section padding="lg">
        <x-ui.container size="lg">
            <div class="max-w-4xl mx-auto space-y-12">

            {{-- Hero da Página --}}
            <div class="text-center space-y-6">
                <h1 class="text-heading-1 font-heading text-white uppercase italic tracking-tight">
                    Sobre o Planeta Boy
                </h1>
                <p class="text-zinc-400 text-lg leading-relaxed max-w-2xl mx-auto">
                    Uma plataforma moderna, discreta e organizada para divulgação de perfis adultos.
                </p>
            </div>

            {{-- Missão da Plataforma --}}
            <section>
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8 md:p-12 space-y-6">
                    <h2 class="text-heading-2 font-heading text-white">Nossa Proposta</h2>
                    <div class="space-y-4 text-zinc-400 leading-relaxed">
                        <p>
                            O <strong class="text-white">Planeta Boy</strong> nasceu com o objetivo de oferecer
                            uma plataforma moderna, discreta e organizada para divulgação de perfis adultos.
                            Nossa missão é proporcionar um ambiente digital onde anunciantes possam apresentar
                            seus serviços com autonomia, enquanto visitantes encontram informações claras e
                            perfis atualizados de forma simples e segura.
                        </p>
                        <p>
                            Acreditamos que respeito, transparência e privacidade são fundamentais para a
                            construção de uma comunidade séria e confiável. Por isso, investimos em ferramentas
                            de moderação, regras de utilização e recursos que valorizam a experiência tanto dos
                            anunciantes quanto dos visitantes.
                        </p>
                        <p>
                            Nosso diferencial está na facilidade de uso, na valorização dos perfis anunciantes
                            por meio de recursos de destaque e na busca constante por oferecer uma plataforma
                            segura, intuitiva e eficiente.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Aviso Importante --}}
            <section>
                <div class="bg-zinc-900 border border-primary/20 rounded-lg p-8 md:p-12 space-y-4">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M12 8v4"/><path d="M12 16h.01"/>
                            </svg>
                        </div>
                        <h2 class="text-heading-2 font-heading text-white">Como Funcionamos</h2>
                    </div>
                    <p class="text-zinc-400 leading-relaxed">
                        O <strong class="text-white">Planeta Boy</strong> atua exclusivamente como uma
                        plataforma de classificados online. Não intermedia negociações, não participa de
                        acordos entre as partes e não presta os serviços anunciados pelos usuários cadastrados.
                    </p>
                </div>
            </section>

            {{-- Seja Bem-Vindo --}}
            <section class="text-center space-y-6">
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8 md:p-12">
                    <h2 class="text-heading-2 font-heading text-white mb-4">Seja Bem-Vindo ao Planeta Boy</h2>
                    <p class="text-zinc-400 max-w-2xl mx-auto mb-8">
                        Cadastre-se e descubra tudo o que nossa plataforma tem a oferecer.
                        Sua experiência começa aqui.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('registro') }}" class="px-8 py-4 text-base font-bold text-black bg-primary hover:brightness-110 rounded-full transition-all uppercase tracking-wider cursor-pointer inline-block">
                            Criar Perfil Grátis
                        </a>
                        <a href="{{ route('explorar') }}" class="px-8 py-4 text-base font-bold text-white border border-zinc-700 hover:border-zinc-500 rounded-full transition-all uppercase tracking-wider cursor-pointer inline-block">
                            Explorar Modelos
                        </a>
                    </div>
                </div>
            </section>

        </div>
    </x-ui.container>
    </x-ui.section>
</div>
@endsection
