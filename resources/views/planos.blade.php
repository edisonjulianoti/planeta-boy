@extends('layouts.app')

@section('title', 'Planos - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950 py-20">

    <x-ui.container size="lg">
        {{-- Alertas --}}
        @if(session('success'))
            <div class="mb-8">
                <div class="max-w-4xl mx-auto p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
                    <p class="text-green-400 text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-8">
                <div class="max-w-4xl mx-auto p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
                    <p class="text-red-400 text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="py-16 flex flex-col items-center gap-8 text-center">
            <h1 class="text-heading-1 font-heading text-white italic">ESCOLHA SEU PLANO PREMIUM</h1>
            <p class="text-zinc-400 text-lg max-w-2xl">
                Eleve sua carreira com um plano que combina com seu estilo. Mais visibilidade, mais clientes, mais sucesso.
            </p>
        </div>

        {{-- Cards de Planos --}}
        <div class="pb-16">
            <div class="flex gap-8 justify-center flex-wrap">
                @foreach($plans as $plan)
                @php
                    $theme = match($plan->slug) {
                        'free' => ['bg' => 'from-zinc-700 to-zinc-900', 'border' => 'border-zinc-800', 'icon' => '☆'],
                        'silver' => ['bg' => 'from-slate-400 to-slate-600', 'border' => 'border-zinc-800', 'icon' => '✦'],
                        'gold' => ['bg' => 'from-yellow-500 to-amber-700', 'border' => 'border-primary/30', 'icon' => '★'],
                        'premium' => ['bg' => 'from-purple-500 to-indigo-700', 'border' => 'border-zinc-800', 'icon' => '♦'],
                        default => ['bg' => 'from-zinc-700 to-zinc-900', 'border' => 'border-zinc-800', 'icon' => '●'],
                    };
                @endphp
                <div class="bg-zinc-900 rounded-xl p-12 flex flex-col gap-8 w-full max-w-[340px] relative
                    {{ $plan->slug === 'gold' ? 'border-2 border-primary/30' : ($plan->price > 0 ? 'border border-zinc-800' : '') }}">

                    {{-- Badge "Mais Popular" --}}
                    @if($plan->slug === 'gold')
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary text-black text-[11px] font-black uppercase tracking-widest px-4 py-1 rounded-full">
                        Mais Popular
                    </div>
                    @endif

                    <div class="flex flex-col items-center gap-4">
                        {{-- Plan image / icon --}}
                        @if($plan->image)
                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 {{ $plan->slug === 'gold' ? 'border-primary' : 'border-zinc-700' }}">
                            <img src="{{ asset('storage/' . $plan->image) }}" alt="{{ $plan->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="bg-gradient-to-br {{ $theme['bg'] }} rounded-full w-20 h-20 flex items-center justify-center shadow-lg">
                            <span class="text-white text-[32px] font-black">{{ $theme['icon'] }}</span>
                        </div>
                        @endif
                        <h3 class="text-white text-[24px] font-black italic">{{ $plan->name }}</h3>
                        <p class="text-zinc-400 text-sm text-center w-60">{{ $plan->description }}</p>
                    </div>
                    <div class="text-white text-[40px] font-black text-center">
                        @if($plan->price > 0)
                            R${{ number_format($plan->price, 2, ',', '.') }}
                            <span class="text-zinc-500 text-lg font-bold">/mês</span>
                        @else
                            Grátis
                        @endif
                    </div>
                    <div class="flex flex-col gap-4 flex-1">
                        @foreach($plan->features as $feature)
                        <div class="flex items-center gap-3">
                            <span class="text-primary text-sm">✓</span>
                            <span class="text-zinc-400 text-sm">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                    @auth
                        @if(auth()->user()->hasPlan($plan->slug))
                            <div class="w-full h-[56px] bg-zinc-800 text-zinc-500 rounded-lg flex items-center justify-center font-black text-sm cursor-default">
                                Plano Atual
                            </div>
                        @else
                            @if($plan->price > 0)
                                <a href="#" onclick="event.preventDefault(); document.getElementById('form-{{ $plan->slug }}').submit();"
                                   class="w-full h-[56px] bg-transparent border border-zinc-800 text-white font-bold text-base rounded-full flex items-center justify-center hover:border-primary hover:text-primary transition-all cursor-pointer
                                   {{ $plan->slug === 'gold' ? 'bg-primary text-black border-primary hover:brightness-110' : '' }}">
                                    Assinar Agora
                                </a>
                                <form id="form-{{ $plan->slug }}" action="{{ route('planos.contratar') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="plan_slug" value="{{ $plan->slug }}">
                                </form>
                            @endif
                        @endif
                    @else
                        @if($plan->price > 0)
                            <a href="{{ route('registro') }}"
                               class="w-full h-[56px] bg-transparent border border-zinc-800 text-white font-bold text-base rounded-full flex items-center justify-center hover:border-primary hover:text-primary transition-all cursor-pointer
                               {{ $plan->slug === 'gold' ? 'bg-primary text-black border-primary hover:brightness-110' : '' }}">
                                Assinar Agora
                            </a>
                        @endif
                    @endauth
                </div>
                @endforeach
            </div>
        </div>

        {{-- Discounts info --}}
        <div class="pb-16">
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-8 max-w-2xl mx-auto">
                <h3 class="text-white font-black text-lg mb-4 text-center">Descontos por Período</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-zinc-950 rounded-lg p-4">
                        <p class="text-primary font-black text-xl">10%</p>
                        <p class="text-zinc-400 text-sm">Trimestral</p>
                    </div>
                    <div class="bg-zinc-950 rounded-lg p-4">
                        <p class="text-primary font-black text-xl">15%</p>
                        <p class="text-zinc-400 text-sm">Semestral</p>
                    </div>
                    <div class="bg-zinc-950 rounded-lg p-4">
                        <p class="text-primary font-black text-xl">20%</p>
                        <p class="text-zinc-400 text-sm">Anual</p>
                    </div>
                </div>
                <p class="text-zinc-500 text-xs text-center mt-4">Descontos aplicados sobre o valor mensal. Consulte condições.</p>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="py-16">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4 text-center">
                    <h2 class="text-white text-[24px] font-black">Perguntas Frequentes</h2>
                </div>
                @if($faqs->count() > 0)
                    <div class="flex flex-col gap-4">
                        @foreach($faqs as $faq)
                        <div class="bg-zinc-900 rounded-xl p-6">
                            <h3 class="text-white text-lg font-bold mb-2">{{ $faq->pergunta }}</h3>
                            <p class="text-zinc-400">{{ $faq->resposta }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-zinc-900 rounded-xl p-12 text-center">
                        <p class="text-zinc-400">Nenhuma pergunta encontrada no momento.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- CTA Banner --}}
        <div class="py-16">
            <div class="bg-zinc-900 rounded-xl overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    {{-- Imagem --}}
                    <div class="w-full md:w-[400px] h-[200px] md:h-[320px]">
                        <img src="https://images.unsplash.com/photo-1587261505607-d08b27359e24?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w4NDM0ODN8MHwxfHJhbmRvbXx8fHx8fHx8fDE3NzY3MTkxODV8&ixlib=rb-4.1.0&q=80&w=1080" alt="CTA" class="w-full h-full object-cover">
                    </div>

                    {{-- Conteúdo --}}
                    <div class="flex-1 p-8 md:p-12 flex flex-col gap-6 justify-center">
                        <div class="flex items-center gap-4">
                            <div class="bg-primary rounded-full w-14 h-14 flex items-center justify-center shrink-0">
                                <span class="text-black text-2xl">★</span>
                            </div>
                            <h3 class="text-white text-[26px] font-black italic">ACOMPANHANTE: ELEVE SUA CARREIRA</h3>
                        </div>
                        <p class="text-zinc-400 text-base">
                            Seu perfil é exclusivo e livre de anúncios de concorrentes. Assuma o controle total e destaque-se para o público que realmente importa.
                        </p>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('registro') }}" class="h-[56px] px-6 py-3 bg-transparent border border-zinc-800 text-white font-bold text-sm rounded-full hover:border-primary hover:text-primary transition-all cursor-pointer">
                                Começar Agora
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.container>

</div>
@endsection
