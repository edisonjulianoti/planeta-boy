@extends('layouts.app')

@section('title', 'Como Anunciar - PLANETA BOYS')
@section('description', 'Crie seu perfil e conquiste mais visibilidade no Planeta Boy. Plataforma discreta e organizada para acompanhantes. Cadastro gratuito.')

@section('content')

{{-- ============================================================
     HERO — Discurso de Vendas
     ============================================================ --}}
<x-ui.section padding="lg" class="bg-zinc-950 relative overflow-hidden">
    {{-- Glow sutil --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[600px] bg-primary/5 rounded-full blur-[120px] pointer-events-none"></div>

    <x-ui.container size="lg" class="relative z-10">
        <div class="flex flex-col items-center gap-8 text-center max-w-4xl mx-auto pt-16 pb-8">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-4 py-1.5">
                <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                <span class="text-primary text-xs font-bold uppercase tracking-wider">Para Acompanhantes</span>
            </div>

            {{-- Headline real do Discurso de Vendas --}}
            <h1 class="text-display font-heading text-white leading-tight">
                Por que anunciar<br>
                <span class="text-primary italic">no Planeta Boy?</span>
            </h1>

            <p class="text-lg text-zinc-400 max-w-3xl leading-relaxed">
                O Planeta Boy foi desenvolvido para ajudar você a conquistar mais visibilidade
                e ampliar suas oportunidades de contato com pessoas realmente interessadas.
            </p>

            {{-- CTA principal --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                <a href="{{ route('registro') }}"
                   class="h-[56px] px-10 bg-primary text-black font-black text-base rounded-full
                          hover:brightness-110 transition-all uppercase tracking-wider
                          flex items-center justify-center shadow-lg shadow-primary/20">
                    Criar Perfil Grátis
                </a>
                <a href="#planos"
                   class="h-[56px] px-10 border border-zinc-700 text-zinc-300 font-bold text-base rounded-full
                          hover:border-primary hover:text-primary transition-all uppercase tracking-wider
                          flex items-center justify-center">
                    Ver Planos
                </a>
            </div>

            {{-- Prova social --}}
            <p class="text-zinc-600 text-sm mt-4">
                ⚡ Mais de <span class="text-zinc-400 font-bold">200 acompanhantes</span> já anunciam no Planeta Boy
            </p>
        </div>
    </x-ui.container>
</x-ui.section>

{{-- ============================================================
     BENEFÍCIOS — 8 benefícios reais do Discurso de Vendas
     ============================================================ --}}
<x-ui.section padding="lg" class="bg-black">
    <x-ui.container size="lg">
        <div class="flex flex-col items-center gap-16">

            {{-- Header --}}
            <div class="flex flex-col items-center gap-4 text-center max-w-2xl">
                <div class="inline-flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-4 py-1.5">
                    <span class="text-zinc-400 text-xs font-bold uppercase tracking-wider">Vantagens Exclusivas</span>
                </div>
                <h2 class="text-heading-2 font-heading text-white">
                    Benefícios de <span class="text-primary">anunciar</span>
                </h2>
                <p class="text-zinc-400 text-lg">
                    Tudo que você precisa para divulgar seu trabalho com autonomia, praticidade e privacidade.
                </p>
            </div>

            {{-- Grid 4 colunas (2 linhas) --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full">

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-8 flex flex-col gap-5 hover:border-zinc-700 transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-2xl">👤</div>
                    <div>
                        <h3 class="text-white font-black text-lg mb-2">Perfil Profissional</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Crie um perfil completo e personalizado para apresentar seu trabalho da melhor forma.
                        </p>
                    </div>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-8 flex flex-col gap-5 hover:border-zinc-700 transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-2xl">🎯</div>
                    <div>
                        <h3 class="text-white font-black text-lg mb-2">Público Qualificado</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Exposição para visitantes qualificados que realmente buscam por acompanhantes.
                        </p>
                    </div>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-8 flex flex-col gap-5 hover:border-zinc-700 transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-2xl">🗺️</div>
                    <div>
                        <h3 class="text-white font-black text-lg mb-2">Organizado por Cidade</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Divulgação organizada por cidade e categoria para alcançar clientes da sua região.
                        </p>
                    </div>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-8 flex flex-col gap-5 hover:border-zinc-700 transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-2xl">⚡</div>
                    <div>
                        <h3 class="text-white font-black text-lg mb-2">Atualização Rápida</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Atualize seu anúncio sempre que quiser — fotos, descrição, contatos. Tudo online.
                        </p>
                    </div>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-8 flex flex-col gap-5 hover:border-zinc-700 transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-2xl">✨</div>
                    <div>
                        <h3 class="text-white font-black text-lg mb-2">Recursos de Destaque</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Aumente sua visibilidade com recursos especiais de destaque e selo verificado.
                        </p>
                    </div>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-8 flex flex-col gap-5 hover:border-zinc-700 transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-2xl">📊</div>
                    <div>
                        <h3 class="text-white font-black text-lg mb-2">Estatísticas</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Acompanhe o desempenho do seu anúncio com estatísticas de visualização.
                        </p>
                    </div>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-8 flex flex-col gap-5 hover:border-zinc-700 transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-2xl">💬</div>
                    <div>
                        <h3 class="text-white font-black text-lg mb-2">Suporte Dedicado</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Suporte dedicado aos anunciantes para ajudar no que precisar.
                        </p>
                    </div>
                </div>

                <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-8 flex flex-col gap-5 hover:border-zinc-700 transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-2xl">🔒</div>
                    <div>
                        <h3 class="text-white font-black text-lg mb-2">Ambiente Discreto</h3>
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Ambiente discreto e de fácil utilização, pensado para sua privacidade.
                        </p>
                    </div>
                </div>

            </div>

            {{-- CTA --}}
            <a href="{{ route('registro') }}"
               class="h-[56px] px-10 bg-primary text-black font-black text-base rounded-full
                      hover:brightness-110 transition-all uppercase tracking-wider shadow-lg shadow-primary/20
                      flex items-center justify-center">
                Quero Anunciar Agora
            </a>
        </div>
    </x-ui.container>
</x-ui.section>

{{-- ============================================================
     PLANOS — Preços reais do Preço dos Planos.docx
     ============================================================ --}}
<x-ui.section id="planos" padding="lg" class="bg-zinc-950">
    <x-ui.container size="lg">
        <div class="flex flex-col items-center gap-16">

            {{-- Header --}}
            <div class="flex flex-col items-center gap-4 text-center max-w-2xl">
                <h2 class="text-heading-2 font-heading text-white">
                    Escolha Seu <span class="text-primary">Plano</span>
                </h2>
                <p class="text-zinc-400 text-lg">
                    Comece grátis e evolua conforme sua carreira cresce.
                </p>
            </div>

            {{-- Grid 3 colunas (Free + 3 pagos) --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 w-full">

                {{-- FREE --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-8 flex flex-col gap-6">
                    <div>
                        <h3 class="text-white font-black text-2xl italic">Free</h3>
                        <p class="text-zinc-500 text-sm mt-1">Para começar</p>
                    </div>
                    <div class="text-white text-[40px] font-black">
                        Grátis
                    </div>
                    <ul class="flex flex-col gap-3 flex-1">
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> 1 perfil
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Até 5 fotos
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> WhatsApp / Telegram
                        </li>
                        <li class="flex items-center gap-3 text-zinc-500 text-sm">
                            <span class="text-zinc-600">—</span> Destaque nas buscas
                        </li>
                        <li class="flex items-center gap-3 text-zinc-500 text-sm">
                            <span class="text-zinc-600">—</span> Selo verificado
                        </li>
                        <li class="flex items-center gap-3 text-zinc-500 text-sm">
                            <span class="text-zinc-600">—</span> Estatísticas
                        </li>
                    </ul>
                    <a href="{{ route('registro') }}"
                       class="w-full h-[48px] bg-transparent border border-zinc-700 text-zinc-300 font-bold text-sm rounded-full
                              hover:border-primary hover:text-primary transition-all
                              flex items-center justify-center">
                        Criar Grátis
                    </a>
                </div>

                {{-- SILVER — R$ 39,90 --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-8 flex flex-col gap-6">
                    <div>
                        <h3 class="text-white font-black text-2xl italic">Silver</h3>
                        <p class="text-zinc-500 text-sm mt-1">Maior visibilidade</p>
                    </div>
                    <div class="text-white text-[40px] font-black">
                        R$39,90<small class="text-zinc-500 text-lg font-bold">/mês</small>
                    </div>
                    <ul class="flex flex-col gap-3 flex-1">
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> 1 perfil
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Até 10 fotos
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> 1 vídeo
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> WhatsApp / Telegram / Instagram
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Destaque básico nas buscas
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Estatísticas básicas
                        </li>
                        <li class="flex items-center gap-3 text-zinc-500 text-sm">
                            <span class="text-zinc-600">—</span> Selo verificado
                        </li>
                    </ul>
                    <a href="{{ route('registro') }}"
                       class="w-full h-[48px] bg-transparent border border-zinc-700 text-zinc-300 font-bold text-sm rounded-full
                              hover:border-primary hover:text-primary transition-all
                              flex items-center justify-center">
                        Assinar
                    </a>
                </div>

                {{-- GOLD (destaque) — R$ 79,90 --}}
                <div class="bg-zinc-900 border-2 border-primary/30 rounded-xl p-8 flex flex-col gap-6 relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary text-black text-[11px] font-black uppercase tracking-widest px-4 py-1 rounded-full">
                        Mais Popular
                    </div>
                    <div>
                        <h3 class="text-white font-black text-2xl italic">Gold</h3>
                        <p class="text-zinc-500 text-sm mt-1">Máximo impacto</p>
                    </div>
                    <div class="text-white text-[40px] font-black">
                        R$79,90<small class="text-zinc-500 text-lg font-bold">/mês</small>
                    </div>
                    <ul class="flex flex-col gap-3 flex-1">
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Até 2 perfis
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Até 20 fotos
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Até 3 vídeos
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> WhatsApp / Telegram / Instagram
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Destaque prioritário nas buscas
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Destaque por cidade
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Selo verificado
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Estatísticas completas
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Suporte prioritário
                        </li>
                    </ul>
                    <a href="{{ route('registro') }}"
                       class="w-full h-[48px] bg-primary text-black font-black text-sm rounded-full
                              hover:brightness-110 transition-all shadow-lg shadow-primary/20
                              flex items-center justify-center">
                        Escolher Gold
                    </a>
                </div>

                {{-- PREMIUM — R$ 149,90 --}}
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-8 flex flex-col gap-6">
                    <div>
                        <h3 class="text-white font-black text-2xl italic">Premium</h3>
                        <p class="text-zinc-500 text-sm mt-1">O topo do mercado</p>
                    </div>
                    <div class="text-white text-[40px] font-black">
                        R$149,90<small class="text-zinc-500 text-lg font-bold">/mês</small>
                    </div>
                    <ul class="flex flex-col gap-3 flex-1">
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Até 5 perfis
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Fotos ilimitadas
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Vídeos ilimitados
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> WhatsApp / Telegram / Instagram
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Destaque máximo nas buscas
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Destaque por cidade
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Destaque na página inicial
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Selo verificado + badge exclusivo
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Estatísticas avançadas
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Suporte prioritário
                        </li>
                        <li class="flex items-center gap-3 text-zinc-400 text-sm">
                            <span class="text-primary">✓</span> Perfil em destaque automático
                        </li>
                    </ul>
                    <a href="{{ route('registro') }}"
                       class="w-full h-[48px] bg-transparent border border-zinc-700 text-zinc-300 font-bold text-sm rounded-full
                              hover:border-primary hover:text-primary transition-all
                              flex items-center justify-center">
                        Assinar
                    </a>
                </div>

            </div>

            {{-- Descontos especiais --}}
            <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-6 w-full max-w-3xl text-center">
                <h4 class="text-white font-bold text-lg mb-3">📅 Economize com planos de longo prazo</h4>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div class="bg-zinc-800/50 rounded-lg p-4">
                        <span class="text-primary font-black text-lg block">10%</span>
                        <span class="text-zinc-400">Trimestral</span>
                    </div>
                    <div class="bg-zinc-800/50 rounded-lg p-4">
                        <span class="text-primary font-black text-lg block">15%</span>
                        <span class="text-zinc-400">Semestral</span>
                    </div>
                    <div class="bg-zinc-800/50 rounded-lg p-4">
                        <span class="text-primary font-black text-lg block">20%</span>
                        <span class="text-zinc-400">Anual</span>
                    </div>
                </div>
            </div>

            {{-- Nota --}}
            <p class="text-zinc-600 text-sm text-center">
                * Planos podem ser alterados ou cancelados a qualquer momento. <br class="hidden sm:inline">
                Cancele quando quiser, sem multa.
            </p>
        </div>
    </x-ui.container>
</x-ui.section>

{{-- ============================================================
     SOBRE — Sobre o Planeta Boy.docx
     ============================================================ --}}
<x-ui.section padding="lg" class="bg-black">
    <x-ui.container size="lg">
        <div class="flex flex-col items-center gap-12 text-center max-w-4xl mx-auto">

            <div class="flex flex-col items-center gap-4">
                <h2 class="text-heading-2 font-heading text-white">
                    Sobre o <span class="text-primary">Planeta Boy</span>
                </h2>
            </div>

            <div class="prose prose-invert max-w-3xl text-zinc-400 text-base leading-relaxed space-y-4">
                <p>
                    O Planeta Boy nasceu com o objetivo de oferecer uma plataforma moderna, discreta e organizada
                    para divulgação de perfis adultos. Nossa missão é proporcionar um ambiente digital onde
                    anunciantes possam apresentar seus serviços com autonomia, enquanto visitantes encontram
                    informações claras e perfis atualizados de forma simples e segura.
                </p>
                <p>
                    Acreditamos que respeito, transparência e privacidade são fundamentais para a construção
                    de uma comunidade séria e confiável. Por isso, investimos em ferramentas de moderação,
                    regras de utilização e recursos que valorizam a experiência tanto dos anunciantes quanto
                    dos visitantes.
                </p>
                <p>
                    Nosso diferencial está na facilidade de uso, na valorização dos perfis anunciantes por
                    meio de recursos de destaque e na busca constante por oferecer uma plataforma segura,
                    intuitiva e eficiente.
                </p>
                <p class="text-zinc-500 text-sm">
                    O Planeta Boy atua exclusivamente como uma plataforma de classificados online. Não
                    intermedia negociações, não participa de acordos entre as partes e não presta os
                    serviços anunciados pelos usuários cadastrados.
                </p>
            </div>

        </div>
    </x-ui.container>
</x-ui.section>

{{-- ============================================================
     FAQ — Perguntas Frequentes (FAQ.docx real)
     ============================================================ --}}
<x-ui.section padding="lg" class="bg-zinc-950">
    <x-ui.container size="lg">
        <div class="flex flex-col items-center gap-12">

            {{-- Header --}}
            <div class="flex flex-col items-center gap-4 text-center max-w-2xl">
                <h2 class="text-heading-2 font-heading text-white">
                    Dúvidas <span class="text-primary">Frequentes</span>
                </h2>
            </div>

            {{-- FAQ Items --}}
            <div class="w-full max-w-3xl flex flex-col gap-3">

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>O cadastro é gratuito?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Sim. O cadastro na plataforma é gratuito. Os recursos adicionais estão disponíveis nos planos pagos.
                        </p>
                    </div>
                </details>

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>O Planeta Boy intermedia os serviços?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Não. O Planeta Boy atua apenas como plataforma de anúncios e não participa das negociações entre anunciantes e clientes.
                        </p>
                    </div>
                </details>

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>Quem pode anunciar?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Somente pessoas maiores de 18 anos, de acordo com os Termos de Uso da plataforma.
                        </p>
                    </div>
                </details>

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>Posso alterar minhas fotos e descrição?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Sim. Os anunciantes podem atualizar seus perfis conforme os recursos disponíveis em cada plano.
                        </p>
                    </div>
                </details>

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>Quanto tempo leva para meu anúncio aparecer?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Após a análise e aprovação pela equipe de moderação, o perfil é publicado.
                        </p>
                    </div>
                </details>

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>Como faço para destacar meu perfil?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Assinando um dos planos pagos ou adquirindo recursos adicionais de destaque.
                        </p>
                    </div>
                </details>

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>Posso cancelar meu plano?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Sim. O cancelamento pode ser solicitado a qualquer momento, permanecendo ativo até o término do período contratado.
                        </p>
                    </div>
                </details>

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>O site garante clientes?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Não. O Planeta Boy oferece visibilidade aos anunciantes, mas não garante contatos ou resultados financeiros.
                        </p>
                    </div>
                </details>

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>Como denunciar um perfil?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Cada perfil possui ferramentas de denúncia para que situações suspeitas sejam analisadas pela moderação.
                        </p>
                    </div>
                </details>

                <details class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-6 py-5 text-white font-bold text-base cursor-pointer
                                    hover:bg-zinc-800/50 transition-colors list-none">
                        <span>Meus dados ficam protegidos?</span>
                        <svg class="w-5 h-5 text-zinc-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="px-6 pb-5">
                        <p class="text-zinc-400 text-sm leading-relaxed">
                            Sim. O tratamento das informações segue as diretrizes da legislação aplicável e da Política de Privacidade da plataforma.
                        </p>
                    </div>
                </details>

            </div>

            {{-- CTA final --}}
            <div class="flex flex-col items-center gap-4 text-center pt-4">
                <p class="text-zinc-400 text-lg">
                    Se você busca uma plataforma séria para divulgar seu trabalho com autonomia, praticidade e privacidade, o Planeta Boy é o lugar certo para você.
                </p>
                <a href="{{ route('registro') }}"
                   class="h-[56px] px-10 bg-primary text-black font-black text-base rounded-full
                          hover:brightness-110 transition-all uppercase tracking-wider shadow-lg shadow-primary/20
                          flex items-center justify-center">
                    Criar Perfil Grátis
                </a>
                <p class="text-zinc-600 text-sm mt-2">
                    Mais visibilidade, mais oportunidades.
                </p>
            </div>

        </div>
    </x-ui.container>
</x-ui.section>

@endsection
