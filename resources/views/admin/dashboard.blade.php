@extends('admin.layout')

@section('title', 'Visão geral')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <x-admin.card padding="p-4 sm:p-6">
        <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Usuários cadastrados</p>
        <p class="text-2xl sm:text-3xl font-black text-white">{{ number_format($stats['total_users']) }}</p>
    </x-admin.card>
    <x-admin.card padding="p-4 sm:p-6">
        <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Contas administrativas</p>
        <p class="text-2xl sm:text-3xl font-black text-white">{{ number_format($stats['total_admins']) }}</p>
    </x-admin.card>
    <x-admin.card padding="p-4 sm:p-6">
        <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Usuários Premium</p>
        <p class="text-2xl sm:text-3xl font-black text-primary">{{ number_format($stats['premium_users']) }}</p>
    </x-admin.card>
    <x-admin.card padding="p-4 sm:p-6">
        <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Usuários Bloqueados</p>
        <p class="text-2xl sm:text-3xl font-black text-orange-400">{{ number_format($stats['blocked_users']) }}</p>
    </x-admin.card>
    <x-admin.card padding="p-4 sm:p-6">
        <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Total de Perfis</p>
        <p class="text-2xl sm:text-3xl font-black text-white">{{ number_format($stats['total_profiles']) }}</p>
    </x-admin.card>
    <x-admin.card padding="p-4 sm:p-6">
        <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Perfis Verificados</p>
        <p class="text-2xl sm:text-3xl font-black text-primary">{{ number_format($stats['verified_profiles']) }}</p>
    </x-admin.card>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

    {{-- Últimos usuários --}}
    <x-admin.card title="Últimos Usuários">
        <x-slot:header>
            <a href="{{ route('admin.users') }}" class="text-xs text-primary hover:brightness-125 font-bold uppercase tracking-wider">Ver todos</a>
        </x-slot:header>
        <div class="space-y-3">
            @foreach($recent_users as $user)
            <div class="flex items-center justify-between py-2 border-b border-zinc-800 last:border-0">
                <div>
                    <p class="text-white text-sm font-bold">{{ $user->name }}</p>
                    <p class="text-zinc-500 text-xs">{{ $user->email }}</p>
                </div>
                <div class="text-right">
                    <x-admin.badge :variant="$user->plan === 'premium' ? 'primary' : 'neutral'" :text="$user->plan" />
                    @if($user->is_admin)
                        <x-admin.badge variant="danger" text="Admin" />
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </x-admin.card>

    {{-- Últimos perfis --}}
    <x-admin.card title="Últimos Perfis">
        <x-slot:header>
            <a href="{{ route('admin.profiles') }}" class="text-xs text-primary hover:brightness-125 font-bold uppercase tracking-wider">Ver todos</a>
        </x-slot:header>
        <div class="space-y-3">
            @foreach($recent_profiles as $profile)
            <div class="flex items-center justify-between py-2 border-b border-zinc-800 last:border-0">
                <div>
                    <p class="text-white text-sm font-bold">{{ $profile->name }}</p>
                    <p class="text-zinc-500 text-xs">{{ $profile->city }}, {{ $profile->state }} · {{ $profile->age }} anos</p>
                </div>
                <div class="flex gap-1">
                    @if($profile->verified)
                        <x-admin.badge variant="primary" text="Verificado" />
                    @endif
                    <x-admin.badge :variant="$profile->active ? 'success' : 'neutral'" :text="$profile->active ? 'Ativo' : 'Inativo'" />
                </div>
            </div>
            @endforeach
        </div>
    </x-admin.card>
</div>

@endsection
