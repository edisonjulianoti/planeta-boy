@extends('admin.layout')

@section('title', 'Planos')

@section('content')

@if(session('success'))
    <x-alerts.alert type="success" :message="session('success')" />
@endif

<x-admin.table>
    <x-slot:headers>
        <tr class="border-b border-zinc-800">
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Imagem</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Nome</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Slug</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Preço/mês</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Status</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Assinantes</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ações</th>
        </tr>
    </x-slot:headers>
    <tbody class="divide-y divide-zinc-800">
            @foreach($plans as $plan)
            <tr class="hover:bg-zinc-800/50 transition-colors">
                <td class="px-4 sm:px-6 py-3 sm:py-4">
                    @if($plan->image)
                        <img src="{{ asset('storage/' . $plan->image) }}" alt="{{ $plan->name }}"
                             class="w-10 h-10 rounded-lg object-cover">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center text-zinc-600 text-xs">?</div>
                    @endif
                </td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-white">{{ $plan->name }}</td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 font-mono text-xs">{{ $plan->slug }}</td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-300 text-xs sm:text-sm">
                    {{ $plan->price == 0 ? 'Grátis' : 'R$ ' . number_format($plan->price, 2, ',', '.') }}
                </td>
                <td class="px-4 sm:px-6 py-3 sm:py-4">
                    <x-admin.badge :variant="$plan->active ? 'success' : 'neutral'" :text="$plan->active ? 'Ativo' : 'Inativo'" />
                </td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs sm:text-sm">
                    {{ \App\Models\User::where('plan', $plan->slug)->count() }} usuários
                </td>
                <td class="px-4 sm:px-6 py-3 sm:py-4">
                    <a href="{{ route('admin.plans.edit', $plan) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                        ✏️ Editar
                    </a>
                </td>
            </tr>
            @endforeach
    </tbody>
</x-admin.table>

@endsection
