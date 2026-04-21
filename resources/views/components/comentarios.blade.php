@props(['perfil' => null, 'comments' => null])

<div class="space-y-6">
    {{-- Título --}}
    <h2 class="text-white font-bold text-lg">Comentários e Avaliações</h2>

    {{-- Formulário de Comentário --}}
    @auth
    <div class="bg-zinc-900 rounded-xl p-6 space-y-4">
        <h3 class="text-white font-bold text-sm">Deixe sua avaliação</h3>
        <form action="{{ route('perfil.comentar', $perfil->id) }}" method="POST" class="space-y-4">
            @csrf
            <textarea 
                name="comment" 
                rows="3" 
                required
                class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-4 text-white placeholder-zinc-500 focus:outline-none focus:border-primary transition-colors resize-none" 
                placeholder="Escreva algo sobre este perfil..."></textarea>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary/90 text-black font-bold tracking-wider rounded-xl transition-all">
                    Publicar Comentário
                </button>
            </div>
        </form>
    </div>
    @else
    <a href="{{ route('login') }}" class="block bg-zinc-900 rounded-xl p-6 text-center text-zinc-400 text-sm hover:bg-zinc-800 transition-colors">
        Faça login para deixar um comentário
    </a>
    @endif

    {{-- Lista de Comentários --}}
    @if($comments && $comments->isNotEmpty())
        <div class="space-y-4">
            @foreach($comments as $comment)
            <div class="bg-zinc-900 rounded-xl p-6 space-y-4">
                {{-- Header do Comentário --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-zinc-700 flex items-center justify-center text-white text-xl">
                            👤
                        </div>
                        <div class="space-y-1">
                            <p class="text-white font-medium text-sm">{{ $comment->user->name ?? 'Anônimo' }}</p>
                            <p class="text-zinc-500 text-xs">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($comment->rating)
                        <p class="text-primary text-xs">{{ str_repeat('⭐', round($comment->rating)) }}</p>
                    @endif
                </div>
                {{-- Texto do Comentário --}}
                <p class="text-zinc-400 text-sm leading-relaxed">{{ $comment->comment }}</p>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-zinc-500 text-sm text-center py-8">Nenhum comentário ainda.</p>
    @endif
</div>
