@extends('admin.layout')

@section('title', 'Revisar Documento')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.verificacoes') }}" class="text-zinc-400 hover:text-white text-sm font-bold uppercase tracking-wider flex items-center gap-2 cursor-pointer">
            ← Voltar para verificações
        </a>
    </div>

    <x-admin.card title="Revisar Documento" padding="p-8">
        {{-- Informações do perfil --}}
        <div class="bg-zinc-800/50 rounded-xl p-4 border border-zinc-700 mb-6">
            <p class="text-zinc-400 text-xs uppercase tracking-wider font-bold mb-2">Perfil</p>
            <p class="text-white font-bold text-lg">{{ $document->profile->name }}</p>
            <p class="text-zinc-500 text-sm">{{ $document->profile->user->name }} · {{ $document->profile->user->email }}</p>
            <p class="text-zinc-500 text-xs mt-1">{{ $document->profile->city }}, {{ $document->profile->state }} · {{ $document->profile->age }} anos</p>
        </div>

        {{-- Documento --}}
        <div class="mb-6">
            <p class="text-zinc-400 text-xs uppercase tracking-wider font-bold mb-2">Tipo: {{ $document->getDocumentTypeLabel() }}</p>
            <p class="text-zinc-500 text-xs mb-3">Enviado em {{ $document->submitted_at->format('d/m/Y H:i') }}</p>

            <div class="bg-zinc-800 border border-zinc-700 rounded-xl overflow-hidden">
                @php $ext = pathinfo($document->file_path, PATHINFO_EXTENSION); @endphp
                @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                    <img src="{{ route('admin.verificacoes.foto', $document) }}" alt="Documento" class="w-full max-h-96 object-contain bg-black">
                @else
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 text-zinc-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                        <p class="text-zinc-500 text-sm">Documento em PDF</p>
                    </div>
                @endif
            </div>

            <div class="mt-2 text-right">
                <a href="{{ route('admin.verificacoes.foto', $document) }}" target="_blank"
                   class="text-xs text-primary hover:brightness-125 cursor-pointer">Abrir em nova aba</a>
            </div>
        </div>

        {{-- Status atual --}}
        @if($document->isRejected())
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6">
            <p class="text-red-400 text-xs uppercase tracking-wider font-bold mb-1">Rejeitado</p>
            <p class="text-zinc-300 text-sm">{{ $document->rejection_reason }}</p>
            @if($document->reviewer)
                <p class="text-zinc-500 text-xs mt-2">Por: {{ $document->reviewer->name }} em {{ $document->reviewed_at->format('d/m/Y H:i') }}</p>
            @endif
        </div>
        @elseif($document->isApproved())
        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 mb-6">
            <p class="text-green-400 text-xs uppercase tracking-wider font-bold mb-1">Aprovado</p>
            @if($document->reviewer)
                <p class="text-zinc-500 text-xs">Por: {{ $document->reviewer->name }} em {{ $document->reviewed_at->format('d/m/Y H:i') }}</p>
            @endif
        </div>
        @endif

        {{-- Ações --}}
        @if($document->isPending())
        <div class="space-y-4 border-t border-zinc-700 pt-6">
            {{-- Aprovar --}}
            <form method="POST" action="{{ route('admin.verificacoes.approve', $document) }}">
                @csrf
                <button type="submit"
                    class="w-full py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-lg transition-all uppercase tracking-wider cursor-pointer">
                    ✅ Aprovar Documento
                </button>
            </form>

            {{-- Rejeitar --}}
            <form method="POST" action="{{ route('admin.verificacoes.reject', $document) }}" class="space-y-3 p-4 bg-zinc-800/50 rounded-xl border border-zinc-700">
                @csrf
                <label class="block text-zinc-400 text-xs uppercase tracking-wider font-bold">Motivo da Rejeição</label>
                <textarea name="rejection_reason" rows="3" required minlength="10" maxlength="2000" placeholder="Explique o motivo da rejeição para o usuário..."
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-red-500 resize-none"></textarea>
                <button type="submit"
                    class="w-full py-2.5 bg-red-600 hover:bg-red-500 text-white font-bold rounded-lg transition-all uppercase tracking-wider cursor-pointer">
                    ❌ Rejeitar Documento
                </button>
            </form>
        </div>
        @endif
    </x-admin.card>
</div>
@endsection
