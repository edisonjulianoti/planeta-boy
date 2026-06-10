@extends('layouts.app')

@section('title', 'Regras da Plataforma - PLANETA BOYS')

@section('description', 'Regras da plataforma, código de conduta e diretrizes do Planeta Boy. Saiba o que é permitido, proibido e como mantemos um ambiente seguro e respeitoso para todos.')

@section('content')
<div class="min-h-screen bg-black">
    <x-ui.container size="lg" class="py-16">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="mb-12 flex flex-col items-center text-center gap-4">
                <div class="inline-flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-4 py-1.5">
                    <div class="w-2 h-2 bg-primary rounded-full"></div>
                    <span class="text-primary text-xs font-bold tracking-wider uppercase">Regras</span>
                </div>
                <h1 class="text-heading-2 font-heading text-white">Regras da Plataforma</h1>
                <p class="text-zinc-400 text-base max-w-2xl">
                    Para manter a qualidade e a segurança da comunidade, todos os usuários devem respeitar
                    as seguintes diretrizes.
                </p>
            </div>

            <div class="space-y-6">

                {{-- É Permitido --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-green-500/20 rounded-lg p-2.5">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">É Permitido</h2>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 mt-1 shrink-0 font-bold">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                Publicar anúncios de serviços adultos voluntários.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 mt-1 shrink-0 font-bold">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                Utilizar fotos próprias e autorizadas.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 mt-1 shrink-0 font-bold">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                Informar dados reais sobre idade e localização.
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-green-500 mt-1 shrink-0 font-bold">✓</span>
                            <span class="text-zinc-300 leading-relaxed">
                                Atualizar informações do perfil sempre que necessário.
                            </span>
                        </li>
                    </ul>
                </div>

                {{-- É Proibido --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-red-500/20 rounded-lg p-2.5">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">É Proibido</h2>
                    </div>
                    <div class="space-y-4">
                        <p class="text-zinc-300 leading-relaxed">São expressamente proibidos na plataforma:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-zinc-800/50 rounded-lg p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <span class="text-zinc-300 text-sm leading-relaxed">Qualquer conteúdo envolvendo menores de 18 anos</span>
                            </div>
                            <div class="bg-zinc-800/50 rounded-lg p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <span class="text-zinc-300 text-sm leading-relaxed">Divulgação de material ilegal ou ofensivo</span>
                            </div>
                            <div class="bg-zinc-800/50 rounded-lg p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <span class="text-zinc-300 text-sm leading-relaxed">Uso de fotos ou informações de terceiros sem autorização</span>
                            </div>
                            <div class="bg-zinc-800/50 rounded-lg p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <span class="text-zinc-300 text-sm leading-relaxed">Incentivo à violência, exploração, coerção ou tráfico de pessoas</span>
                            </div>
                            <div class="bg-zinc-800/50 rounded-lg p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <span class="text-zinc-300 text-sm leading-relaxed">Spam, perfis duplicados ou informações falsas</span>
                            </div>
                            <div class="bg-zinc-800/50 rounded-lg p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <span class="text-zinc-300 text-sm leading-relaxed">Linguagem discriminatória ou preconceituosa</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Idade Mínima --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-primary/20 rounded-lg p-2.5">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M9 12l2 2 4-4"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Idade Mínima</h2>
                    </div>
                    <p class="text-zinc-400 leading-relaxed">
                        O cadastro e utilização do <strong class="text-white">Planeta Boy</strong> são
                        permitidos exclusivamente para pessoas maiores de 18 anos.
                    </p>
                </div>

                {{-- Código de Conduta --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-primary/20 rounded-lg p-2.5">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M9 12l2 2 4-4"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Código de Conduta</h2>
                    </div>
                    <p class="text-zinc-400 leading-relaxed mb-4">
                        Esperamos respeito entre todos os usuários da plataforma. O descumprimento das regras
                        poderá resultar na suspensão ou exclusão definitiva da conta, sem aviso prévio.
                    </p>
                    <div class="bg-zinc-800/50 rounded-lg p-4">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            <strong class="text-white">Denúncias:</strong> Caso identifique uma violação
                            destas regras, utilize o formulário de
                            <a href="{{ route('contato') }}" class="text-primary hover:underline">contato</a>
                            ou envie um e-mail para
                            <strong class="text-white">suporte@planetaboys.com.br</strong>.
                            Todas as denúncias são tratadas com confidencialidade.
                        </p>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="mt-12 pt-8 border-t border-zinc-800 text-center">
                <p class="text-zinc-500 text-sm">
                    Última atualização: {{ now()->format('d/m/Y') }} &mdash; Estas regras podem ser
                    atualizadas periodicamente. Recomendamos revisitar esta página com frequência.
                </p>
            </div>
        </div>
    </x-ui.container>
</div>
@endsection
