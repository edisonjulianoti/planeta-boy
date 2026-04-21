@extends('admin.layout')

@section('title', 'Perfis')

@section('content')

<x-admin.table>
    <x-slot:headers>
        <tr class="border-b border-zinc-800">
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Nome</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Usuário</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Cidade</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Idade</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Status</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Criado em</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ações</th>
        </tr>
    </x-slot:headers>
    <tbody class="divide-y divide-zinc-800">
            @foreach($profiles as $profile)
            <tr class="hover:bg-zinc-800/50 transition-colors">
                <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-white">{{ $profile->name }}</td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs">{{ $profile->user?->email ?? '—' }}</td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs sm:text-sm">{{ $profile->city }}, {{ $profile->state }}</td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs sm:text-sm">{{ $profile->age }}</td>
                <td class="px-4 sm:px-6 py-3 sm:py-4">
                    <div class="flex gap-1 flex-wrap">
                        <x-admin.badge :variant="$profile->active ? 'success' : 'neutral'" :text="$profile->active ? 'Ativo' : 'Inativo'" />
                        @if($profile->verified)
                            <x-admin.badge variant="primary" text="Verificado" />
                        @endif
                    </div>
                </td>
                <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs">{{ $profile->created_at->format('d/m/Y') }}</td>
                <td class="px-4 sm:px-6 py-3 sm:py-4">
                    <div class="flex flex-wrap gap-2">
                        @if(!$profile->user?->is_admin)
                        <a href="{{ route('admin.profiles.edit', $profile) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                            ✏️ Editar
                        </a>
                        @endif
                        <a href="{{ route('perfil.ver', $profile->id) }}" target="_blank"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-primary text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                            👁️ Ver
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
    </tbody>
</x-admin.table>

<div class="mt-6">
    {{ $profiles->links() }}
</div>

@endsection
