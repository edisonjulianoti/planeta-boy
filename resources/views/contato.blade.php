@extends('layouts.app')

@section('title', 'Contato - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950">

    {{-- Header --}}
    <div class="py-[120px] px-[160px]">
        <div class="flex flex-col items-center gap-16 text-center max-w-4xl mx-auto">
            <h1 class="text-[40px] font-black text-white italic">FALE CONOSCO</h1>
            <p class="text-zinc-400 text-base">
                Tem alguma dúvida ou precisa de suporte? Entre em contato conosco e responderemos rapidamente.
            </p>
        </div>
    </div>

    {{-- Formulário de Contato --}}
    <div class="pb-[160px] px-[160px]">
        <div class="flex gap-16">
            {{-- Coluna Esquerda - Informações --}}
            <div class="flex flex-col gap-6 flex-1">
                {{-- E-mail de Suporte --}}
                <div class="bg-zinc-900 rounded-xl p-8 flex items-center gap-6">
                    <div class="text-primary text-[32px]">✉</div>
                    <div class="flex flex-col gap-2">
                        <h3 class="text-white font-bold text-lg">E-mail de Suporte</h3>
                        <p class="text-zinc-400 text-sm">atendimento@planetaboys.com</p>
                    </div>
                </div>

                {{-- Horário de Atendimento --}}
                <div class="bg-zinc-900 rounded-xl p-8 flex items-center gap-6">
                    <div class="text-primary text-[32px]">⏱</div>
                    <div class="flex flex-col gap-2">
                        <h3 class="text-white font-bold text-lg">Horário de Atendimento</h3>
                        <p class="text-zinc-400 text-sm">Segunda a Sexta, 09h às 18h</p>
                    </div>
                </div>
            </div>

            {{-- Coluna Direita - Formulário --}}
            <div class="flex-1 bg-zinc-900 rounded-xl p-12 flex flex-col gap-6">
                <h2 class="text-white font-bold text-2xl">Deixe sua Mensagem</h2>

                @if(session('success'))
                    <div class="p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
                        <p class="text-green-400 text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('contato.store') }}" method="POST" class="flex flex-col gap-6">
                    @csrf
                    {{-- Nome Completo --}}
                    <div class="flex flex-col gap-3">
                        <label class="text-white font-semibold text-sm">Nome Completo</label>
                        <input type="text" name="name" required
                               class="w-full h-[56px] bg-zinc-950 border border-zinc-800 rounded-lg px-6 py-4 text-zinc-400 placeholder-zinc-400 focus:outline-none focus:border-primary transition-colors"
                               placeholder="Digite seu nome">
                    </div>

                    {{-- E-mail --}}
                    <div class="flex flex-col gap-3">
                        <label class="text-white font-semibold text-sm">E-mail</label>
                        <input type="email" name="email" required
                               class="w-full h-[56px] bg-zinc-950 border border-zinc-800 rounded-lg px-6 py-4 text-zinc-400 placeholder-zinc-400 focus:outline-none focus:border-primary transition-colors"
                               placeholder="exemplo@email.com">
                    </div>

                    {{-- Assunto --}}
                    <div class="flex flex-col gap-3">
                        <label class="text-white font-semibold text-sm">Assunto</label>
                        <input type="text" name="subject" required
                               class="w-full h-[56px] bg-zinc-950 border border-zinc-800 rounded-lg px-6 py-4 text-zinc-400 placeholder-zinc-400 focus:outline-none focus:border-primary transition-colors"
                               placeholder="Sobre o que deseja falar?">
                    </div>

                    {{-- Mensagem --}}
                    <div class="flex flex-col gap-3">
                        <label class="text-white font-semibold text-sm">Mensagem</label>
                        <textarea name="message" rows="5" required
                                  class="w-full h-[160px] bg-zinc-950 border border-zinc-800 rounded-lg px-6 py-4 text-zinc-400 placeholder-zinc-400 focus:outline-none focus:border-primary transition-colors resize-none"
                                  placeholder="Escreva sua mensagem detalhada aqui..."></textarea>
                    </div>

                    {{-- Botão --}}
                    <button type="submit"
                            class="w-full h-[56px] bg-primary hover:brightness-110 text-black font-black text-base rounded-lg transition-all">
                        ENVIAR MENSAGEM
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
