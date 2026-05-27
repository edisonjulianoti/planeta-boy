@extends('layouts.app')

@section('title', 'Meu Plano')

@section('content')
<div class="min-h-screen bg-zinc-950 py-12">
    <x-ui.container size="lg" class="max-w-3xl">

        {{-- Alertas --}}
        @if(session('success'))
            <x-alerts.alert type="success" :message="session('success')" />
        @endif
        @if(session('error'))
            <x-alerts.alert type="error" :message="session('error')" />
        @endif

        {{-- Plano atual --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-8 mb-6">
            <h1 class="text-white font-black uppercase tracking-wider text-lg mb-6 flex items-center gap-2">
                <div class="w-1 h-5 bg-primary rounded-full"></div>Meu Plano
            </h1>

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Plano Atual</p>
                    <p class="text-3xl font-black text-white uppercase">{{ $user->plan }}</p>

                    @if($user->plan !== 'free' && $user->plan_expires_at)
                        <p class="text-zinc-400 text-sm mt-2">
                            Válido até
                            <span class="{{ $user->planIsActive() ? 'text-green-400' : 'text-red-400' }} font-bold">
                                {{ \Carbon\Carbon::parse($user->plan_expires_at)->format('d/m/Y') }}
                            </span>
                            @if(!$user->planIsActive())
                                <span class="text-red-400 font-bold ml-1">(Expirado)</span>
                            @endif
                        </p>
                    @elseif($user->plan === 'free')
                        <p class="text-zinc-500 text-sm mt-2">Plano gratuito — sem expiração</p>
                    @endif
                </div>

                <div class="text-right">
                    @if($user->plan !== 'free' && $user->planIsActive())
                        <x-admin.badge variant="success" text="Ativo" />
                    @elseif($user->plan === 'free')
                        <x-admin.badge variant="neutral" text="Gratuito" />
                    @else
                        <x-admin.badge variant="danger" text="Expirado" />
                    @endif
                </div>
            </div>

            @if($user->plan !== 'free')
            <div class="mt-6 pt-6 border-t border-zinc-800 flex gap-3">
                <a href="{{ route('planos') }}" class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-white rounded-lg text-sm font-bold uppercase tracking-wider transition-all cursor-pointer">
                    Trocar Plano
                </a>
                <form action="{{ route('meu.plano.cancelar') }}" method="POST"
                      onsubmit="return confirm('Tem certeza? Seu plano será revertido para Gratuito.')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg text-sm font-bold uppercase tracking-wider transition-all cursor-pointer">
                        Cancelar Assinatura
                    </button>
                </form>
            </div>
            @else
            <div class="mt-6 pt-6 border-t border-zinc-800">
                <a href="{{ route('planos') }}" class="px-4 py-2 bg-primary hover:brightness-110 text-primary-foreground rounded-lg text-sm font-bold uppercase tracking-wider transition-all inline-block cursor-pointer">
                    Ver Planos Disponíveis
                </a>
            </div>
            @endif
        </div>

        {{-- Planos disponíveis (se pendente) --}}
        @php $pending = $requests->firstWhere('status', 'pending'); @endphp
        @if($pending)
        <div class="bg-matrix-500/5 border border-matrix-500/30 rounded-2xl p-6 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-matrix-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    <p class="text-matrix-400 font-bold text-sm">Solicitação Pendente</p>
                    <p class="text-zinc-400 text-xs mt-0.5">Você solicitou o plano <strong class="text-white uppercase">{{ $pending->plan_slug }}</strong> em {{ $pending->created_at->format('d/m/Y') }}. Aguardando aprovação.</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Histórico --}}
        @if($requests->isNotEmpty())
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <h2 class="text-white font-black uppercase tracking-wider text-sm mb-4 flex items-center gap-2">
                <div class="w-1 h-4 bg-primary rounded-full"></div>Histórico de Solicitações
            </h2>
            <div class="space-y-3">
                @foreach($requests as $req)
                <div class="flex items-center justify-between py-3 border-b border-zinc-800 last:border-0">
                    <div>
                        <p class="text-white text-sm font-bold uppercase">{{ $req->plan_slug }}</p>
                        <p class="text-zinc-500 text-xs">{{ $req->created_at->format('d/m/Y H:i') }}</p>
                        @if($req->admin_notes)
                            <p class="text-zinc-400 text-xs mt-1 italic">"{{ $req->admin_notes }}"</p>
                        @endif
                    </div>
                    <x-admin.badge 
                        :variant="match($req->status) {
                            'pending'  => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                        }"
                        :text="match($req->status) {
                            'pending'  => 'Pendente',
                            'approved' => 'Aprovado',
                            'rejected' => 'Rejeitado',
                        }"
                    />
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </x-ui.container>
</div>
@endsection
