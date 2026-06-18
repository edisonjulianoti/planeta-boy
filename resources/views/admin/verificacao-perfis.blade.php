@extends('admin.layout')

@section('title', 'Perfis com Verificação')

@section('content')

<x-admin.table>
    <x-slot:headers>
        <tr class="border-b border-zinc-800">
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Perfil</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Usuário</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Status</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Docs</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Verificado</th>
            <th class="text-left px-4 sm:px-6 py-3 sm:py-4 text-zinc-500 text-xs uppercase tracking-widest font-bold">Ações</th>
        </tr>
    </x-slot:headers>
    <tbody class="divide-y divide-zinc-800">
        @forelse($profiles as $profile)
        <tr class="hover:bg-zinc-800/50 transition-colors">
            <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-white">{{ $profile->name }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4 text-zinc-400 text-xs">{{ $profile->user?->email ?? '—' }}</td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                @php
                    $sv = match($profile->verification_status) {
                        'approved' => ['label' => 'Aprovado', 'variant' => 'success'],
                        'pending' => ['label' => 'Pendente', 'variant' => 'warning'],
                        'rejected' => ['label' => 'Rejeitado', 'variant' => 'danger'],
                        default => ['label' => 'Nenhum', 'variant' => 'neutral'],
                    };
                @endphp
                <x-admin.badge :variant="$sv['variant']" :text="$sv['label']" />
            </td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                @php $docCount = $profile->verificationDocuments->count(); @endphp
                <span class="text-zinc-400 text-xs">{{ $docCount }} documento{{ $docCount !== 1 ? 's' : '' }}</span>
            </td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                @if($profile->verified)
                    <x-admin.badge variant="primary" text="✓ Verificado" />
                @else
                    <x-admin.badge variant="neutral" text="—" />
                @endif
            </td>
            <td class="px-4 sm:px-6 py-3 sm:py-4">
                <a href="{{ route('admin.profiles.edit', $profile) }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                    ✏️ Editar
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center py-12 text-zinc-500">
                Nenhum perfil com solicitação de verificação.
            </td>
        </tr>
        @endforelse
    </tbody>
</x-admin.table>

<div class="mt-6">
    {{ $profiles->links() }}
</div>
@endsection
