@props(['perfil' => null, 'comments' => null])

<div class="space-y-6">
    {{-- Título --}}
    <h2 class="text-white font-bold text-lg">Comentários e Avaliações</h2>

    {{-- Formulário de Comentário (qualquer um pode enviar) --}}
    <div class="bg-zinc-900 rounded-xl p-6 space-y-4">
        <h3 class="text-white font-bold text-sm">Deixe sua avaliação</h3>
        <form action="{{ route('perfil.comentar', $perfil->id) }}" method="POST" class="space-y-4">
            @csrf
            {{-- Seletor de Avaliação --}}
            <div class="flex items-center gap-3">
                <label for="rating-select" class="text-zinc-400 text-sm whitespace-nowrap">Avaliação:</label>
                <select name="rating" id="rating-select"
                    class="w-32 bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary transition-colors">
                    <option value="">Sem avaliação</option>
                    <option value="1">⭐ 1 estrela</option>
                    <option value="2">⭐⭐ 2 estrelas</option>
                    <option value="3">⭐⭐⭐ 3 estrelas</option>
                    <option value="4">⭐⭐⭐⭐ 4 estrelas</option>
                    <option value="5">⭐⭐⭐⭐⭐ 5 estrelas</option>
                </select>
            </div>
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

    {{-- Lista de Comentários Aprovados --}}
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
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-sm {{ $i <= round($comment->rating) ? 'text-yellow-400' : 'text-zinc-600' }}">★</span>
                            @endfor
                        </div>
                    @endif
                </div>
                {{-- Texto do Comentário --}}
                <p class="text-zinc-400 text-sm leading-relaxed">{{ $comment->comment }}</p>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-zinc-500 text-sm text-center py-8">Nenhum comentário ainda. Seja o primeiro a avaliar!</p>
    @endif

    {{-- Link para gerenciar comentários (visível apenas para o dono do perfil) --}}
    @auth
        @if(auth()->id() === $perfil->user_id)
            <div class="border-t border-zinc-800 pt-6 mt-8">
                <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl p-6 text-center">
                    <p class="text-zinc-400 text-sm mb-3">Você é o dono deste perfil. Gerencie os comentários pendentes e aprovados.</p>
                    <a href="{{ route('perfil.comentarios') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-black font-bold text-sm rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        Gerenciar Comentários
                    </a>
                </div>
            </div>
        @endif
    @endauth
</div>
