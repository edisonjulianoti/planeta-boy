@extends('layouts.app')

@section('title', "{$city->name} - {$city->state} - PLANETA BOYS")

@section('description', "Encontre os melhores acompanhantes masculinos e trans em {$city->name}, {$city->state}. Confira {$totalCount} perfis verificados e premium em {$city->name}.")

@section('content')
<div class="min-h-screen bg-zinc-950 py-20">

    <x-ui.container size="lg">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-zinc-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors cursor-pointer">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            <span class="text-white">{{ $city->name }}</span>
        </nav>

        {{-- Header --}}
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <h1 class="text-heading-1 font-heading text-white">
                    {{ $city->name }}, {{ $city->state }}
                </h1>
            </div>
            <p class="text-[16px] text-zinc-400">{{ $totalCount }} perfis encontrados</p>
        </div>

        {{-- Grid de Perfis --}}
        <div class="flex-1">
            @if($perfis->isEmpty())
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <svg class="w-16 h-16 text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <p class="text-zinc-400 text-lg font-bold">Nenhum perfil encontrado em {{ $city->name }}</p>
                    <p class="text-zinc-600 text-sm mt-1">Tente explorar outras cidades</p>
                    <a href="{{ route('explorar') }}" class="mt-6 px-6 py-3 bg-primary text-black font-bold rounded-full hover:brightness-110 transition-all cursor-pointer">
                        Explorar todas as cidades
                    </a>
                </div>
            @else
                <div id="perfis-grid" class="grid grid-cols-[repeat(auto-fit,minmax(232px,1fr))] gap-6">
                    @include('components.perfil-card', ['perfis' => $perfis])
                </div>

                {{-- Paginação --}}
                <div class="mt-12">
                    {{ $perfis->links() }}
                </div>
            @endif
        </div>
    </x-ui.container>
</div>
@endsection
