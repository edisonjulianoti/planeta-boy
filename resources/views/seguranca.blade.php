@extends('layouts.app')

@section('title', 'Dicas de Segurança - PLANETA BOYS')

@section('description', 'Dicas de segurança para visitantes e anunciantes no Planeta Boy. Saiba como se proteger, evitar golpes e manter sua experiência segura na plataforma.')

@section('content')
<div class="min-h-screen bg-black">
    <x-ui.container size="lg" class="py-16">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="mb-12 flex flex-col items-center text-center gap-4">
                <div class="inline-flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-4 py-1.5">
                    <div class="w-2 h-2 bg-primary rounded-full"></div>
                    <span class="text-primary text-xs font-bold tracking-wider uppercase">Segurança</span>
                </div>
                <h1 class="text-heading-2 font-heading text-white">Dicas de Segurança</h1>
                <p class="text-zinc-400 text-base max-w-2xl">
                    Sua segurança é prioridade. Recomendamos que anunciantes e visitantes adotem algumas
                    medidas simples para reduzir riscos.
                </p>
            </div>

            {{-- Aviso Emergência --}}
            <div class="bg-primary/10 border border-primary/20 rounded-lg p-6 mb-10 flex items-start gap-4">
                <svg class="w-6 h-6 text-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <h3 class="text-white font-bold text-sm mb-1">Emergência? Ligue 190</h3>
                    <p class="text-zinc-400 text-sm leading-relaxed">
                        Em caso de emergência, violência ou situação de risco, ligue imediatamente para a
                        Polícia Militar (190) ou procure a delegacia mais próxima. Sua integridade física
                        vem em primeiro lugar.
                    </p>
                </div>
            </div>

            <div class="space-y-6">

                {{-- Para Visitantes --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-primary/20 rounded-lg p-2.5">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Para Visitantes</h2>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Confirme informações</strong> antes de qualquer encontro.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Prefira locais seguros</strong> e de fácil acesso.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Informe alguém de confiança</strong> sobre seus deslocamentos.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Não realize pagamentos antecipados</strong> sem segurança.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Desconfie de propostas</strong> excessivamente vantajosas.
                            </span>
                        </li>
                    </ul>
                </div>

                {{-- Para Anunciantes --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-primary/20 rounded-lg p-2.5">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Para Anunciantes</h2>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Preserve seus dados pessoais.</strong>
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Utilize contatos profissionais.</strong>
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Evite compartilhar informações sensíveis.</strong>
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Bloqueie e denuncie</strong> usuários abusivos.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-primary mt-1 shrink-0">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                <strong class="text-white">Informe imediatamente</strong> qualquer tentativa de golpe.
                            </span>
                        </li>
                    </ul>
                </div>

                {{-- Aviso Importante --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-primary/20 rounded-lg p-2.5">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M12 8v4"/><path d="M12 16h.01"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Importante</h2>
                    </div>
                    <p class="text-zinc-400 leading-relaxed">
                        O <strong class="text-white">Planeta Boy</strong> não participa das negociações
                        realizadas entre usuários. Toda interação ocorre por responsabilidade exclusiva
                        das partes envolvidas.
                    </p>
                </div>

            </div>

            {{-- Footer --}}
            <div class="mt-12 pt-8 border-t border-zinc-800 text-center">
                <p class="text-zinc-500 text-sm">
                    Última atualização: {{ now()->format('d/m/Y') }} &mdash; Em caso de dúvidas, entre em
                    <a href="{{ route('contato') }}" class="text-primary hover:underline">contato conosco</a>.
                </p>
            </div>
        </div>
    </x-ui.container>
</div>
@endsection
