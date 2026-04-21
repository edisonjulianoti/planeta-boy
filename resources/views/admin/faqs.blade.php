@extends('admin.layout')

@section('title', 'FAQs')

@section('content')

@if(session('success'))
    <x-alerts.alert type="success" :message="session('success')" />
@endif

<div class="flex items-center justify-between mb-6">
    <h2 class="text-white font-black uppercase tracking-wider text-sm">Perguntas Frequentes</h2>
    <a href="{{ route('admin.faqs.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg text-sm transition-all cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        Nova FAQ
    </a>
</div>

<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.faqs', ['categoria' => 'todas']) }}"
       class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 {{ $categoria === 'todas' ? 'bg-primary text-primary-foreground' : 'bg-zinc-800 text-zinc-400 hover:bg-zinc-700' }} font-bold uppercase tracking-wider rounded-lg text-xs sm:text-sm transition-all cursor-pointer">
        Todas
    </a>
    <a href="{{ route('admin.faqs', ['categoria' => 'geral']) }}"
       class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 {{ $categoria === 'geral' ? 'bg-primary text-primary-foreground' : 'bg-zinc-800 text-zinc-400 hover:bg-zinc-700' }} font-bold uppercase tracking-wider rounded-lg text-xs sm:text-sm transition-all cursor-pointer">
        Geral
    </a>
    <a href="{{ route('admin.faqs', ['categoria' => 'planos']) }}"
       class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 {{ $categoria === 'planos' ? 'bg-primary text-primary-foreground' : 'bg-zinc-800 text-zinc-400 hover:bg-zinc-700' }} font-bold uppercase tracking-wider rounded-lg text-xs sm:text-sm transition-all cursor-pointer">
        Planos
    </a>
</div>

<x-admin.table>
    <x-slot:headers>
        <tr class="border-b border-zinc-800">
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Pergunta</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Resposta</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Categoria</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Status</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ações</th>
        </tr>
    </x-slot:headers>
    <tbody class="divide-y divide-zinc-800">
        @foreach($faqs as $faq)
        <tr class="hover:bg-zinc-800/50 transition-colors">
            <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-white">{{ $faq->pergunta }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs sm:text-sm max-w-xs sm:max-w-md truncate">{{ $faq->resposta }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <x-admin.badge :variant="$faq->categoria === 'geral' ? 'neutral' : 'primary'" :text="$faq->categoria === 'geral' ? 'Geral' : 'Planos'" />
            </td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <x-admin.badge :variant="$faq->ativo ? 'success' : 'neutral'" :text="$faq->ativo ? 'Ativo' : 'Inativo'" />
            </td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.faqs.edit', $faq) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                        ✏️ Editar
                    </a>
                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar esta FAQ?')">
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
