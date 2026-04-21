@extends('admin.layout')

@section('title', 'Cidades')

@section('content')

@if(session('success'))
    <x-alerts.alert type="success" :message="session('success')" />
@endif

<div class="flex items-center justify-between mb-6">
    <h2 class="text-white font-black uppercase tracking-wider text-sm">Cidades</h2>
    <a href="{{ route('admin.cities.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg text-sm transition-all cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        Nova Cidade
    </a>
</div>

<x-admin.table>
    <x-slot:headers>
        <tr class="border-b border-zinc-800">
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Nome</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Estado</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Slug</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Imagem</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Status</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ordem</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ações</th>
        </tr>
    </x-slot:headers>
    <tbody class="divide-y divide-zinc-800">
        @foreach($cities as $city)
        <tr class="hover:bg-zinc-800/50 transition-colors">
            <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-white">{{ $city->name }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs sm:text-sm">{{ $city->state }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 font-mono text-xs">{{ $city->slug }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                @if($city->image)
                    <img src="{{ asset('storage/' . $city->image) }}" alt="{{ $city->name }}" class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded-lg">
                @else
                    <span class="text-zinc-500 text-xs">Sem imagem</span>
                @endif
            </td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <x-admin.badge :variant="$city->active ? 'success' : 'neutral'" :text="$city->active ? 'Ativo' : 'Inativo'" />
            </td>
            <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs sm:text-sm">{{ $city->order }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.cities.edit', $city) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                        ✏️ Editar
                    </a>
                    <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar esta cidade?')">
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
