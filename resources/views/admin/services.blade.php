@extends('admin.layout')

@section('title', 'Serviços')

@section('content')

@if(session('success'))
    <x-alerts.alert type="success" :message="session('success')" />
@endif

<div class="flex items-center justify-between mb-6">
    <h2 class="text-white font-black uppercase tracking-wider text-sm">Serviços</h2>
    <a href="{{ route('admin.services.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg text-sm transition-all cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        Novo Serviço
    </a>
</div>

<x-admin.table>
    <x-slot:headers>
        <tr class="border-b border-zinc-800">
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Nome</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Slug</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Categoria</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Status</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ações</th>
        </tr>
    </x-slot:headers>
    <tbody class="divide-y divide-zinc-800">
        @foreach($services as $service)
        <tr class="hover:bg-zinc-800/50 transition-colors">
            <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-white">{{ $service->name }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 font-mono text-xs">{{ $service->slug }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs sm:text-sm">{{ $service->category }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <x-admin.badge :variant="$service->active ? 'success' : 'neutral'" :text="$service->active ? 'Ativo' : 'Inativo'" />
            </td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.services.edit', $service) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                        ✏️ Editar
                    </a>
                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar este serviço?')">
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

@endsection
