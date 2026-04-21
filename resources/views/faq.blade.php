@extends('layouts.app')

@section('title', 'FAQ - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950">

    {{-- Header --}}
    <x-ui.section padding="md">
        <x-ui.container size="lg" class="text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tight mb-4">
                Perguntas <span class="text-primary">Frequentes</span>
            </h1>
            <p class="text-zinc-400 text-lg max-w-2xl mx-auto">
                Encontre respostas para as dúvidas mais comuns sobre nossa plataforma.
            </p>
        </x-ui.container>
    </x-ui.section>

    {{-- FAQs --}}
    <x-ui.section padding="md">
        <x-ui.container size="lg" class="max-w-4xl">
            @if($faqs->count() > 0)
                <div class="space-y-4">
                    @foreach($faqs as $faq)
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ $faq->pergunta }}</h3>
                        <p class="text-zinc-400">{{ $faq->resposta }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-12 text-center">
                    <p class="text-zinc-400">Nenhuma pergunta encontrada no momento.</p>
                </div>
            @endif
        </x-ui.container>
    </x-ui.section>

</div>
@endsection
