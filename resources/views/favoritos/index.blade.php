@extends('layouts.app')

@section('title', 'Meus Favoritos - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950 py-20">

    <x-ui.container size="lg">
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <h1 class="text-heading-1 font-heading text-white">Meus <span class="text-primary">Favoritos</span></h1>
            </div>
            <p class="text-[16px] text-zinc-400">{{ $perfis->total() }} perfis salvos</p>
        </div>

        @if($perfis->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <svg class="w-16 h-16 text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <p class="text-zinc-400 text-lg font-bold">Nenhum favorito ainda</p>
                <p class="text-zinc-600 text-sm mt-1">Explore perfis e salve seus favoritos</p>
                <a href="{{ route('explorar') }}" class="mt-6 px-6 py-3 bg-primary hover:brightness-110 text-black font-bold rounded-full transition-all uppercase tracking-wider">
                    Explorar Perfis
                </a>
            </div>
        @else
            <div class="grid grid-cols-[repeat(auto-fit,minmax(232px,1fr))] gap-6">
                @include('components.perfil-card', ['perfis' => $perfis, 'favoritedIds' => $favoritedIds])
            </div>

            <div class="mt-8">
                {{ $perfis->links() }}
            </div>
        @endif
    </x-ui.container>
</div>
@endsection
