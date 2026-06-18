@extends('admin.layout')

@section('title', 'Verificações')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
        <p class="text-zinc-500 text-xs uppercase tracking-widest font-bold mb-1">Pendentes</p>
        <p class="text-2xl font-black text-yellow-400">{{ $stats['pending'] }}</p>
    </div>
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
        <p class="text-zinc-500 text-xs uppercase tracking-widest font-bold mb-1">Aprovados</p>
        <p class="text-2xl font-black text-green-400">{{ $stats['approved'] }}</p>
    </div>
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
        <p class="text-zinc-500 text-xs uppercase tracking-widest font-bold mb-1">Rejeitados</p>
        <p class="text-2xl font-black text-red-400">{{ $stats['rejected'] }}</p>
    </div>
</div>

{{-- Filtros --}}
<div class="flex gap-2 mb-6">
    <a href="{{ route('admin.verificacoes', ['status' => 'pending']) }}"
        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request('status', 'pending') === 'pending' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'bg-zinc-800 text-zinc-400 border border-zinc-700 hover:text-white' }} cursor-pointer">
        Pendentes
    </a>
    <a href="{{ route('admin.verificacoes', ['status' => 'approved']) }}"
        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request('status') === 'approved' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-zinc-800 text-zinc-400 border border-zinc-700 hover:text-white' }} cursor-pointer">
        Aprovados
    </a>
    <a href="{{ route('admin.verificacoes', ['status' => 'rejected']) }}"
        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request('status') === 'rejected' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-zinc-800 text-zinc-400 border border-zinc-700 hover:text-white' }} cursor-pointer">
        Rejeitados
    </a>
    <a href="{{ route('admin.verificacao-perfis') }}"
        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.verificacao-perfis') ? 'bg-primary/10 text-primary border border-primary/30' : 'bg-zinc-800 text-zinc-400 border border-zinc-700 hover:text-white' }} cursor-pointer">
        Perfis
    </a>
</div>

<x-admin.card title="Documentos de Verificação" padding="p-0">
    <x-admin.table>
        <x-slot:headers>
            <tr class="border-b border-zinc-800">
                <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Perfil</th>
                <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Usuário</th>
                <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Tipo</th>
                <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Status</th>
                <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Enviado em</th>
                <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ações</th>
            </tr>
        </x-slot:headers>
        <tbody class="divide-y divide-zinc-800">
            @forelse($documents as $doc)
            <tr class="hover:bg-zinc-800/50 transition-colors">
                <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-white">{{ $doc->profile->name }}</td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs">{{ $doc->profile->user->email }}</td>
                <td class="px-4 sm:px-6 py-3 sm:py-4">
                    <x-admin.badge variant="neutral" :text="$doc->getDocumentTypeLabel()" />
                </td>
                <td class="px-4 sm:px-6 py-3 sm:py-4">
                    @php
                        $statusVariant = match($doc->status) {
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default => 'warning',
                        };
                        $statusLabel = match($doc->status) {
                            'approved' => 'Aprovado',
                            'rejected' => 'Rejeitado',
                            default => 'Pendente',
                        };
                    @endphp
                    <x-admin.badge :variant="$statusVariant" :text="$statusLabel" />
                </td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs">
                    {{ $doc->submitted_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-4 sm:px-6 py-3 sm:py-4">
                    <a href="{{ route('admin.verificacoes.show', $doc) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                        Revisar
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-zinc-500">
                    Nenhum documento encontrado.
                </td>
            </tr>
            @endforelse
        </tbody>
    </x-admin.table>

    @if($documents->hasPages())
    <div class="p-4 border-t border-zinc-800">
        {{ $documents->links() }}
    </div>
    @endif
</x-admin.card>
@endsection
