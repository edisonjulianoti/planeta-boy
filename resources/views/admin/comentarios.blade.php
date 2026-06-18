@extends('admin.layout')

@section('title', 'Comentários')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-zinc-400 text-sm">Gerencie todos os comentários da plataforma.</p>
        <div class="flex items-center gap-2">
            <span class="text-xs text-zinc-500">Total: {{ $comments->total() }}</span>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-zinc-900 rounded-xl p-4">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs text-zinc-500 uppercase tracking-wider mb-1">Status</label>
                <select name="status"
                    class="bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary transition-colors">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendentes</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovados</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-zinc-500 uppercase tracking-wider mb-1">Perfil</label>
                <select name="profile_id"
                    class="bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary transition-colors">
                    <option value="">Todos</option>
                    @foreach($profiles as $profile)
                        <option value="{{ $profile->id }}" {{ request('profile_id') == $profile->id ? 'selected' : '' }}>
                            {{ $profile->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="px-4 py-2 bg-primary hover:bg-primary/90 text-black text-sm font-bold rounded-lg transition-all">
                Filtrar
            </button>
            @if(request()->anyFilled(['status', 'profile_id']))
                <a href="{{ route('admin.comentarios') }}"
                    class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-400 text-sm font-bold rounded-lg transition-all">
                    Limpar
                </a>
            @endif
        </form>
    </div>

    {{-- Lista de Comentários --}}
    @if($comments->isEmpty())
        <div class="bg-zinc-900 rounded-xl p-12 text-center">
            <p class="text-zinc-500">Nenhum comentário encontrado.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($comments as $comment)
                <div class="bg-zinc-900 rounded-xl p-5 {{ !$comment->approved ? 'border-l-4 border-yellow-500' : '' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            {{-- Header --}}
                            <div class="flex items-center gap-3 flex-wrap mb-2">
                                <span class="text-white font-bold text-sm">
                                    {{ $comment->user->name ?? 'Anônimo' }}
                                </span>
                                <span class="text-zinc-600 text-xs">•</span>
                                <a href="{{ route('perfil.ver', $comment->profile->slug ?? $comment->profile->id) }}"
                                    target="_blank"
                                    class="text-primary hover:text-primary/80 text-sm underline">
                                    {{ $comment->profile->name }}
                                </a>
                                <span class="text-zinc-600 text-xs">•</span>
                                <span class="text-zinc-500 text-xs">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                @if($comment->rating)
                                    <span class="text-yellow-400 text-xs ml-1">
                                        {{ str_repeat('★', (int) round($comment->rating)) }}{{ str_repeat('☆', 5 - (int) round($comment->rating)) }}
                                    </span>
                                @endif
                                @if(!$comment->approved)
                                    <span class="px-2 py-0.5 bg-yellow-500/10 text-yellow-400 text-[10px] font-bold uppercase rounded-full">Pendente</span>
                                @else
                                    <span class="px-2 py-0.5 bg-green-500/10 text-green-400 text-[10px] font-bold uppercase rounded-full">Aprovado</span>
                                @endif
                            </div>
                            {{-- Comentário --}}
                            <p class="text-zinc-400 text-sm leading-relaxed">{{ $comment->comment }}</p>
                        </div>
                        {{-- Ações --}}
                        <div class="flex items-center gap-2 shrink-0">
                            @if(!$comment->approved)
                                <form action="{{ route('admin.comentarios.approve', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-green-600 hover:bg-green-500 text-white text-xs font-bold rounded-lg transition-all cursor-pointer">
                                        Aprovar
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.comentarios.destroy', $comment->id) }}" method="POST"
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

        {{-- Paginação --}}
        <div class="mt-6">
            {{ $comments->links() }}
        </div>
    @endif
</div>
@endsection
