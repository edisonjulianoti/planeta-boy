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
            <h1 class="text-[40px] font-black text-white italic">ESCOLHA SEU PLANO PREMIUM</h1>
            <p class="text-zinc-400 text-lg max-w-2xl">
                Eleve sua carreira com um plano que combina com seu estilo. Mais visibilidade, mais clientes, mais sucesso.
            </p>
        </div>

        {{-- Cards de Planos --}}
        <div class="pb-16">
            <div class="flex gap-8 justify-center flex-wrap">
                @foreach($plans as $plan)
                <div class="bg-zinc-900 rounded-xl p-12 flex flex-col gap-8 w-full max-w-[340px] {{ $plan->price > 0 ? 'border border-zinc-800' : '' }}">
                    <div class="flex flex-col items-center gap-4">
                        <div class="bg-zinc-950 rounded-full w-16 h-16 flex items-center justify-center">
                            <span class="text-primary text-[28px]">★</span>
                        </div>
                        <h3 class="text-white text-[24px] font-black italic">{{ $plan->name }}</h3>
                        <p class="text-zinc-400 text-sm text-center w-60">{{ $plan->description }}</p>
                    </div>
                    <div class="text-white text-[40px] font-black">
                        @if($plan->price > 0)
                            R${{ number_format($plan->price, 2, ',', '.') }}
                        @else
                            Grátis
                        @endif
                    </div>
                    <div class="flex flex-col gap-4 flex-1">
                        @foreach($plan->features as $feature)
                        <div class="flex items-center gap-3">
                            <span class="text-zinc-400 text-sm">✓</span>
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
                                <a href="#" onclick="event.preventDefault(); document.getElementById('form-{{ $plan->slug }}').submit();" class="w-full h-[56px] bg-transparent border border-zinc-800 text-white font-bold text-base rounded-full flex items-center justify-center hover:border-primary hover:text-primary transition-all cursor-pointer">
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
                            <a href="{{ route('registro') }}" class="w-full h-[56px] bg-transparent border border-zinc-800 text-white font-bold text-base rounded-full flex items-center justify-center hover:border-primary hover:text-primary transition-all cursor-pointer">
                                Assinar Agora
                            </a>
                        @endif
                    @endauth
                </div>
                @endforeach
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
