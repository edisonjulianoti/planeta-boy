@extends('admin.layout')

@section('title', 'Assinaturas')

@section('content')

@if(session('success'))
    <x-alerts.alert type="success" :message="session('success')" />
@endif

{{-- Pendentes --}}
<div class="mb-8">
    <h2 class="text-white font-black uppercase tracking-wider text-sm mb-4 flex items-center gap-2">
        <div class="w-1 h-4 bg-yellow-400 rounded-full"></div>
        Solicitações Pendentes
        @if($pending->count())
            <x-admin.badge variant="warning" :text="$pending->count()" />
        @endif
    </h2>

    @if($pending->isEmpty())
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 sm:p-8 text-center text-zinc-500 text-sm">
            Nenhuma solicitação pendente.
        </div>
    @else
        <div class="space-y-4">
            @foreach($pending as $req)
            <div class="bg-zinc-900 border border-yellow-500/20 rounded-2xl p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
                    <div class="flex-1">
                        <p class="text-white font-bold">{{ $req->user->name }}</p>
                        <p class="text-zinc-400 text-xs">{{ $req->user->email }}</p>
                        <p class="text-zinc-400 text-xs sm:text-sm mt-2">
                            Solicita o plano <span class="text-primary font-black uppercase">{{ $req->plan_slug }}</span>
                            · <span class="text-zinc-500">{{ $req->created_at->diffForHumans() }}</span>
                        </p>
                        <p class="text-zinc-500 text-xs mt-1">
                            Plano atual: <span class="uppercase font-bold text-zinc-400">{{ $req->user->plan }}</span>
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 shrink-0 w-full sm:w-auto">
                        {{-- Aprovar --}}
                        <form action="{{ route('admin.subscriptions.approve', $req) }}" method="POST" class="flex items-start gap-2">
                            @csrf
                            <div class="w-full sm:w-auto">
                                <input type="date" name="expires_at"
                                    value="{{ \Carbon\Carbon::now()->addMonth()->format('Y-m-d') }}"
                                    min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                    class="w-full sm:w-auto bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-xs focus:outline-none focus:border-primary mb-1">
                                <input type="text" name="admin_notes" placeholder="Observação (opcional)"
                                    class="block w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-xs focus:outline-none focus:border-primary">
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white font-black text-xs uppercase tracking-wider rounded-lg transition-all whitespace-nowrap cursor-pointer">
                                Aprovar
                            </button>
                        </form>

                        {{-- Rejeitar --}}
                        <form action="{{ route('admin.subscriptions.reject', $req) }}" method="POST" class="flex items-start gap-2">
                            @csrf
                            <input type="text" name="admin_notes" placeholder="Motivo (opcional)"
                                class="bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-xs focus:outline-none focus:border-primary">
                            <button type="submit"
                                class="px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 border border-red-500/30 font-black text-xs uppercase tracking-wider rounded-lg transition-all whitespace-nowrap cursor-pointer">
                                Rejeitar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Histórico --}}
<div>
    <h2 class="text-white font-black uppercase tracking-wider text-sm mb-4 flex items-center gap-2">
        <div class="w-1 h-4 bg-primary rounded-full"></div>Histórico
    </h2>

    @if($history->isEmpty())
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-8 text-center text-zinc-500 text-sm">
            Nenhum histórico ainda.
        </div>
    @else
        <x-admin.table>
            <x-slot:headers>
                <tr class="border-b border-zinc-800">
                    <th class="text-left px-6 py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Usuário</th>
                    <th class="text-left px-6 py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Plano</th>
                    <th class="text-left px-6 py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Status</th>
                    <th class="text-left px-6 py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Válido até</th>
                    <th class="text-left px-6 py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Obs.</th>
                    <th class="text-left px-6 py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Data</th>
                    <th class="text-left px-6 py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ações</th>
                </tr>
            </x-slot:headers>
            <tbody class="divide-y divide-zinc-800">
                    @foreach($history as $req)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-white font-bold">{{ $req->user->name }}</p>
                            <p class="text-zinc-500 text-xs">{{ $req->user->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-primary font-black uppercase text-xs">{{ $req->plan_slug }}</td>
                        <td class="px-6 py-4">
                            <x-admin.badge 
                                :variant="$req->status === 'approved' ? 'success' : 'danger'"
                                :text="$req->status === 'approved' ? 'Aprovado' : 'Rejeitado'"
                            />
                        </td>
                        <td class="px-6 py-4 text-zinc-400 text-xs">{{ $req->expires_at ? \Carbon\Carbon::parse($req->expires_at)->format('d/m/Y') : '—' }}</td>
                        <td class="px-6 py-4 text-zinc-500 text-xs">{{ $req->admin_notes ?? '—' }}</td>
                        <td class="px-6 py-4 text-zinc-500 text-xs">{{ $req->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.subscriptions.edit', $req) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                                ✏️ Editar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </x-admin.table>
        </div>
        <div class="mt-4">{{ $history->links() }}</div>
    @endif
</div>

@endsection
