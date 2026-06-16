@extends('layouts.app')

@section('title', 'Verifique seu E-mail - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-black flex flex-col justify-center py-12">
    <x-ui.container size="lg">
        <div class="sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="flex justify-center">
                <span class="text-2xl font-black uppercase italic tracking-tight text-white"><span class="text-primary">PLANETA</span><span class="text-foreground">BOYS</span></span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-white">
                Verifique seu E-mail
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-400">
                Enviamos um link de confirmacao para <strong class="text-white">{{ auth()->user()->email }}</strong>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="bg-zinc-900/50 backdrop-blur-xl py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10 border border-zinc-800 text-center">

                @if(session('status'))
                    <div class="mb-6 p-4 bg-primary/10 border border-primary/20 rounded-lg">
                        <p class="text-sm text-primary font-bold">{{ session('status') }}</p>
                    </div>
                @endif

                <div class="flex justify-center mb-6">
                    <svg class="w-20 h-20 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>

                <p class="text-zinc-300 text-sm mb-6">
                    Clique no link enviado para <strong>{{ auth()->user()->email }}</strong> para ativar sua conta.
                    <br><br>
                    Nao recebeu o e-mail? Verifique sua caixa de spam ou clique no botao abaixo para reenviar.
                </p>

                <form action="{{ route('verification.resend') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full flex justify-center py-2.5 px-4 rounded-lg text-sm font-bold text-primary-foreground bg-primary hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-black transition-all duration-200 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        Reenviar E-mail de Verificacao
                    </button>
                </form>

                <p class="mt-6 text-xs text-zinc-500">
                    <a href="{{ route('logout') }}" class="text-primary hover:brightness-125 cursor-pointer"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair e criar conta com outro e-mail
                    </a>
                </p>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </x-ui.container>
</div>
@endsection
