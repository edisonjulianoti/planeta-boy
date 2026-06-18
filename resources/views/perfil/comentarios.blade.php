@extends('layouts.app')

@section('title', 'Meus Comentários - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950">
    <div class="container mx-auto px-4 py-12 max-w-4xl">

        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('perfil') }}" class="text-zinc-500 hover:text-white text-sm transition-colors mb-2 inline-block">
                    ← Voltar ao Meu Perfil
                </a>
                <h1 class="text-3xl font-black text-white uppercase italic">Gerenciar <span class="text-primary">Comentários</span></h1>
            </div>
        </div>

        @if(session('success'))
            <x-alerts.alert type="success" :message="session('success')" />
        @endif

        {{-- Comentários Pendentes --}}
        @if($pendingComments->isNotEmpty())
        <div class="mb-10">
            <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-yellow-400 rounded-full"></div>
                Pendentes ({{ $pendingComments->count() }})
            </h2>
            <div class="space-y-3">
                @foreach($pendingComments as $comment)
                <div class="bg-zinc-900 border-l-4 border-yellow-500 rounded-xl p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 flex-wrap mb-2">
                                <span class="text-white font-bold text-sm">{{ $comment->user->name ?? 'Anônimo' }}</span>
                                <span class="text-zinc-600 text-xs">•</span>
                                <span class="text-zinc-500 text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                                @if($comment->rating)
                                    <span class="text-yellow-400 text-sm ml-1">
                                        {{ str_repeat('★', (int) round($comment->rating)) }}{{ str_repeat('☆', 5 - (int) round($comment->rating)) }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-zinc-400 text-sm leading-relaxed">{{ $comment->comment }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('perfil.comment.approve', $comment->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white text-xs font-bold rounded-lg transition-all cursor-pointer">
                                    Aprovar
                                </button>
                            </form>
                            <form action="{{ route('perfil.comment.reject', $comment->id) }}" method="POST"
                                onsubmit="return confirm('Rejeitar este comentário? Ele será excluído permanentemente.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white text-xs font-bold rounded-lg transition-all cursor-pointer">
                                    Rejeitar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="mb-10">
            <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-yellow-400 rounded-full"></div>
                Pendentes
            </h2>
            <div class="bg-zinc-900 rounded-xl p-8 text-center">
                <p class="text-zinc-500">Nenhum comentário pendente de aprovação.</p>
            </div>
        </div>
        @endif

        {{-- Comentários Aprovados --}}
        <div>
            <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-green-400 rounded-full"></div>
                Aprovados ({{ $approvedComments->count() }})
            </h2>
            @if($approvedComments->isNotEmpty())
            <div class="space-y-3">
                @foreach($approvedComments as $comment)
                <div class="bg-zinc-900/50 rounded-xl p-5 border border-zinc-800">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 flex-wrap mb-2">
                                <span class="text-white font-bold text-sm">{{ $comment->user->name ?? 'Anônimo' }}</span>
                                <span class="text-zinc-600 text-xs">•</span>
                                <span class="text-zinc-500 text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                                @if($comment->rating)
                                    <span class="text-yellow-400 text-sm ml-1">
                                        {{ str_repeat('★', (int) round($comment->rating)) }}{{ str_repeat('☆', 5 - (int) round($comment->rating)) }}
                                    </span>
                                @endif
                                <span class="px-2 py-0.5 bg-green-500/10 text-green-400 text-[10px] font-bold uppercase rounded-full">Aprovado</span>
                            </div>
                            <p class="text-zinc-400 text-sm leading-relaxed">{{ $comment->comment }}</p>
                        </div>
                        <div>
                            <form action="{{ route('perfil.comment.reject', $comment->id) }}" method="POST"
                                onsubmit="return confirm('Excluir este comentário permanentemente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1.5 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white text-xs font-bold rounded-lg transition-all cursor-pointer">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-zinc-900 rounded-xl p-8 text-center">
                <p class="text-zinc-500">Nenhum comentário aprovado ainda.</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
