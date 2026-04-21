@extends('admin.layout')

@section('title', 'Detalhes do Usuário')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.users') }}" class="text-zinc-400 hover:text-white text-sm font-bold uppercase tracking-wider cursor-pointer">
        ← Voltar para Usuários
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Informações do Usuário --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <h1 class="text-white font-black uppercase tracking-wider text-lg mb-6 flex items-center gap-2">
                <div class="w-1 h-5 bg-primary rounded-full"></div>Informações do Usuário
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Nome</p>
                    <p class="text-white font-bold">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">E-mail</p>
                    <p class="text-white">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Telefone</p>
                    <p class="text-white">{{ $user->phone ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Plano</p>
                    <x-admin.badge :variant="$user->plan === 'premium' ? 'primary' : 'neutral'" :text="$user->plan" />
                </div>
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Administrador</p>
                    <x-admin.badge variant="danger" :text="$user->is_admin ? 'Sim' : 'Não'" />
                </div>
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Bloqueado</p>
                    <x-admin.badge variant="warning" :text="$user->blocked ? 'Sim' : 'Não'" />
                </div>
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Data de Cadastro</p>
                    <p class="text-white">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($user->plan_expires_at)
                <div>
                    <p class="text-zinc-500 text-xs uppercase tracking-widest mb-1">Plano Expira em</p>
                    <p class="text-white">{{ $user->plan_expires_at->format('d/m/Y') }}</p>
                </div>
                @endif
            </div>

            @if($user->bio)
            <div class="mt-6 pt-6 border-t border-zinc-800">
                <p class="text-zinc-500 text-xs uppercase tracking-widest mb-2">Bio</p>
                <p class="text-zinc-300 text-sm">{{ $user->bio }}</p>
            </div>
            @endif
        </div>

        {{-- Perfil do Usuário --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <h2 class="text-white font-black uppercase tracking-wider text-lg mb-4 flex items-center gap-2">
                <div class="w-1 h-5 bg-primary rounded-full"></div>Perfil do Usuário
            </h2>

            @if($user->profile)
            <div class="flex items-center justify-between p-4 bg-zinc-800 rounded-xl">
                <div>
                    <p class="text-white font-bold">{{ $user->profile->name }}</p>
                    <p class="text-zinc-400 text-sm">{{ $user->profile->city }}, {{ $user->profile->state }} · {{ $user->profile->age }} anos</p>
                </div>
                <div class="flex gap-2">
                    @if($user->profile->verified)
                        <x-admin.badge variant="primary" text="Verificado" />
                    @endif
                    <x-admin.badge :variant="$user->profile->active ? 'success' : 'neutral'" :text="$user->profile->active ? 'Ativo' : 'Inativo'" />
                </div>
            </div>
            @else
            <p class="text-zinc-500 text-sm">Nenhum perfil cadastrado.</p>
            @endif
        </div>

        {{-- Histórico de Solicitações --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <h2 class="text-white font-black uppercase tracking-wider text-lg mb-4 flex items-center gap-2">
                <div class="w-1 h-5 bg-primary rounded-full"></div>Histórico de Solicitações
            </h2>

            @if($user->subscriptionRequests->count() > 0)
            <div class="space-y-3">
                @foreach($user->subscriptionRequests as $request)
                <div class="flex items-center justify-between p-4 bg-zinc-800 rounded-xl">
                    <div>
                        <p class="text-white font-bold uppercase">{{ $request->plan_slug }}</p>
                        <p class="text-zinc-400 text-sm">{{ $request->created_at->format('d/m/Y H:i') }}</p>
                        @if($request->admin_notes)
                            <p class="text-zinc-500 text-xs mt-1 italic">"{{ $request->admin_notes }}"</p>
                        @endif
                    </div>
                    <x-admin.badge 
                        :variant="match($request->status) {
                            'pending'  => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                        }"
                        :text="match($request->status) {
                            'pending'  => 'Pendente',
                            'approved' => 'Aprovado',
                            'rejected' => 'Rejeitado',
                        }"
                    />
                </div>
                @endforeach
            </div>
            @else
            <p class="text-zinc-500 text-sm">Nenhuma solicitação de plano.</p>
            @endif
        </div>
    </div>

    {{-- Ações Rápidas --}}
    <div class="space-y-4">
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <h2 class="text-white font-black uppercase tracking-wider text-lg mb-4 flex items-center gap-2">
                <div class="w-1 h-5 bg-primary rounded-full"></div>Ações Rápidas
            </h2>

            <div class="space-y-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="block w-full px-4 py-3 bg-zinc-800 hover:bg-zinc-700 text-white font-black uppercase tracking-wider rounded-lg text-sm transition-all text-center cursor-pointer">
                    Editar Usuário
                </a>

                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 {{ $user->is_admin ? 'bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-primary/10 hover:bg-primary/20 text-primary border border-primary/30' }} font-black uppercase tracking-wider rounded-lg text-sm transition-all cursor-pointer">
                        {{ $user->is_admin ? 'Rebaixar a Usuário' : 'Promover a Admin' }}
                    </button>
                </form>

                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.toggle-blocked', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 {{ $user->blocked ? 'bg-green-500/10 hover:bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 border border-orange-500/30' }} font-black uppercase tracking-wider rounded-lg text-sm transition-all cursor-pointer">
                        {{ $user->blocked ? 'Desbloquear Usuário' : 'Bloquear Usuário' }}
                    </button>
                </form>
                @endif

                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar este usuário?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 font-black uppercase tracking-wider rounded-lg text-sm transition-all cursor-pointer">
                        Deletar Usuário
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Editar Plano --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <h2 class="text-white font-black uppercase tracking-wider text-lg mb-4 flex items-center gap-2">
                <div class="w-1 h-5 bg-primary rounded-full"></div>Editar Plano
            </h2>

            <form action="{{ route('admin.users.plan.update', $user) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-zinc-400 text-xs uppercase tracking-widest font-bold block mb-1">Plano</label>
                    <select name="plan" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
                        @foreach(\App\Models\Plan::orderBy('price')->get() as $plan)
                            <option value="{{ $plan->slug }}" {{ $user->plan === $plan->slug ? 'selected' : '' }}>{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-zinc-400 text-xs uppercase tracking-widest font-bold block mb-1">Válido até</label>
                    <input type="date" name="plan_expires_at"
                        value="{{ $user->plan_expires_at ? \Carbon\Carbon::parse($user->plan_expires_at)->format('Y-m-d') : '' }}"
                        class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
                </div>
                <button type="submit" class="w-full py-2 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg text-xs transition-all cursor-pointer">Salvar</button>
            </form>
        </div>
    </div>
</div>

@endsection
