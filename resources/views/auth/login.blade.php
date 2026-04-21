@extends('layouts.app')

@section('title', 'Entrar - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-black flex flex-col justify-center py-12">
    <x-ui.container size="lg">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <span class="text-2xl font-black uppercase italic tracking-tight text-white"><span class="text-primary">PLANETA</span><span class="text-foreground">BOYS</span></span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-white">
                Entrar na sua conta
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-400">
                Ou <a href="{{ route('registro') }}" class="font-medium text-primary hover:brightness-125 transition-colors cursor-pointer">crie uma conta gratuita</a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-zinc-900/50 backdrop-blur-xl py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10 border border-zinc-800">

            @if($errors->any())
                <x-alerts.alert type="error" :message="$errors->first()" />
            @endif

            @if(session('status'))
                <x-alerts.alert type="success" :message="session('status')" />
            @endif

            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <x-forms.input
                    name="email"
                    type="email"
                    label="Email"
                    placeholder="seu@email.com"
                    :value="old('email')"
                    autocomplete="email"
                    required
                    icon="<path d='M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z'/><polyline points='22,6 12,13 2,6'/>"
                />

                <x-forms.input 
                    name="password" 
                    type="password" 
                    label="Senha" 
                    placeholder="••••••••" 
                    autocomplete="current-password" 
                    required
                    icon="<rect x='3' y='11' width='18' height='11' rx='2' ry='2'/><path d='M7 11V7a5 5 0 0 1 10 0v4'/>" 
                />

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-primary">
                        <label for="remember_me" class="ml-2 block text-sm text-zinc-400">Lembrar de mim</label>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-primary hover:brightness-125 transition-colors cursor-pointer">Esqueceu sua senha?</a>
                    </div>
                </div>

                <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 rounded-lg text-sm font-bold text-primary-foreground bg-primary hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-black transition-all duration-200 uppercase tracking-wider cursor-pointer">
                    Entrar
                </button>
            </form>
        </div>
    </div>
    </x-ui.container>
</div>
@endsection
