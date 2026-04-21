@extends('admin.layout')

@section('title', 'Usuários')

@section('content')

@if(session('success'))
    <x-alerts.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-alerts.alert type="error" :message="session('error')" />
@endif

{{-- Filtros --}}
<div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 sm:p-6 mb-6">
    <form action="{{ route('admin.users') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-4 items-start sm:items-end">
        <div class="w-full sm:flex-1 min-w-64">
            <label class="text-zinc-400 text-xs uppercase tracking-widest font-bold block mb-1">Buscar</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou e-mail"
                class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
        </div>
        <div class="w-full sm:w-auto">
            <label class="text-zinc-400 text-xs uppercase tracking-widest font-bold block mb-1">Plano</label>
            <select name="plan" class="w-full sm:w-auto bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
                <option value="">Todos</option>
                <option value="free" {{ request('plan') === 'free' ? 'selected' : '' }}>Free</option>
                <option value="premium" {{ request('plan') === 'premium' ? 'selected' : '' }}>Premium</option>
            </select>
        </div>
        <div class="w-full sm:w-auto">
            <label class="text-zinc-400 text-xs uppercase tracking-widest font-bold block mb-1">Admin</label>
            <select name="is_admin" class="w-full sm:w-auto bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
                <option value="">Todos</option>
                <option value="1" {{ request('is_admin') === '1' ? 'selected' : '' }}>Sim</option>
                <option value="0" {{ request('is_admin') === '0' ? 'selected' : '' }}>Não</option>
            </select>
        </div>
        <div class="w-full sm:w-auto">
            <label class="text-zinc-400 text-xs uppercase tracking-widest font-bold block mb-1">Bloqueado</label>
            <select name="blocked" class="w-full sm:w-auto bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
                <option value="">Todos</option>
                <option value="1" {{ request('blocked') === '1' ? 'selected' : '' }}>Sim</option>
                <option value="0" {{ request('blocked') === '0' ? 'selected' : '' }}>Não</option>
            </select>
        </div>
        <div class="w-full sm:w-auto flex gap-2">
            <button type="submit" class="flex-1 sm:flex-none px-4 py-2 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg text-sm transition-all cursor-pointer">Filtrar</button>
            <a href="{{ route('admin.users') }}" class="flex-1 sm:flex-none px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-white font-black uppercase tracking-wider rounded-lg text-sm transition-all cursor-pointer">Limpar</a>
        </div>
    </form>
</div>

<x-admin.table>
    <x-slot:headers>
        <tr class="border-b border-zinc-800">
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Nome</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">E-mail</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Plano</th>
            <th class="hidden sm:table-cell text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Admin</th>
            <th class="hidden sm:table-cell text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Bloqueado</th>
            <th class="hidden sm:table-cell text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Cadastro</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ações</th>
        </tr>
    </x-slot:headers>
    <tbody class="divide-y divide-zinc-800">
        @foreach($users as $user)
        <tr class="hover:bg-zinc-800/50 transition-colors {{ $user->blocked ? 'opacity-50' : '' }}">
            <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-white">
                <a href="{{ route('admin.users.show', $user) }}" class="hover:text-primary transition-colors cursor-pointer">{{ $user->name }}</a>
            </td>
            <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs sm:text-sm">{{ $user->email }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <x-admin.badge :variant="$user->plan === 'premium' ? 'primary' : 'neutral'" :text="$user->plan" />
            </td>
            <td class="hidden sm:table-cell px-4 sm:px-6 py-3 sm:py-4">
                <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-2 py-0.5 rounded-full text-xs font-black uppercase border transition-all {{ $user->is_admin ? 'bg-red-500/10 text-red-400 border-red-500/30 hover:bg-red-500/20' : 'bg-zinc-800 text-zinc-400 border-zinc-700 hover:bg-zinc-700' }} cursor-pointer">
                        {{ $user->is_admin ? 'Sim' : 'Não' }}
                    </button>
                </form>
            </td>
            <td class="hidden sm:table-cell px-4 sm:px-6 py-3 sm:py-4">
                <form action="{{ route('admin.users.toggle-blocked', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-2 py-0.5 rounded-full text-xs font-black uppercase border transition-all {{ $user->blocked ? 'bg-orange-500/10 text-orange-400 border-orange-500/30 hover:bg-orange-500/20' : 'bg-zinc-800 text-zinc-400 border-zinc-700 hover:bg-zinc-700' }} cursor-pointer">
                        {{ $user->blocked ? 'Sim' : 'Não' }}
                    </button>
                </form>
            </td>
            <td class="hidden sm:table-cell px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                        ✏️ Editar
                    </a>
                    <details class="relative">
                        <summary class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer list-none">
                            📋 Plano
                        </summary>
                        <div class="absolute right-0 top-6 z-10 bg-zinc-800 border border-zinc-700 rounded-xl p-4 w-64 shadow-xl">
                            <form action="{{ route('admin.users.plan.update', $user) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="text-zinc-400 text-xs uppercase tracking-widest font-bold block mb-1">Plano</label>
                                    <select name="plan" class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
                                        @foreach(\App\Models\Plan::orderBy('price')->get() as $plan)
                                            <option value="{{ $plan->slug }}" {{ $user->plan === $plan->slug ? 'selected' : '' }}>{{ $plan->name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-zinc-400 text-xs uppercase tracking-widest font-bold block mb-1">Válido até</label>
                                    <input type="date" name="plan_expires_at"
                                        value="{{ $user->plan_expires_at ? \Carbon\Carbon::parse($user->plan_expires_at)->format('Y-m-d') : '' }}"
                                        class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary">
                                </div>
                                <button type="submit" class="w-full py-2 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg text-xs transition-all cursor-pointer">Salvar</button>
                            </form>
                        </div>
                    </details>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar este usuário?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600/20 hover:bg-red-600/40 text-red-400 border border-red-500/30 text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                            🗑️ Deletar
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</x-admin.table>

<div class="mt-6">
    {{ $users->links() }}
</div>

@endsection
