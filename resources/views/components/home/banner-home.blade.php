{{-- Hero Section --}}
<section class="relative py-24 lg:py-32 flex items-center justify-center min-h-[500px] md:min-h-[680px] overflow-hidden">

    {{-- Background preto sólido --}}
    <div class="absolute inset-0 z-0 bg-zinc-950"></div>

    {{-- Conteúdo --}}
    <div class="relative z-10 w-full">
        <x-ui.container size="lg" class="text-center">
            <div class="flex flex-col items-center gap-6 max-w-[800px] mx-auto w-full">

            {{-- Badge com glow --}}
            <div class="inline-flex items-center gap-2 bg-zinc-900/80 backdrop-blur-sm border border-zinc-700/50 rounded-full px-4 py-1.5 shadow-lg shadow-primary/5">
                <span class="relative flex w-2 h-2">
                    <span class="animate-ping absolute inline-flex w-full h-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex w-2 h-2 rounded-full bg-primary"></span>
                </span>
                <span class="text-primary text-xs font-bold tracking-wider">A Nova Referência Premium</span>
            </div>

            {{-- Título com gradiente --}}
            <h1 class="text-display font-heading text-white leading-tight w-full">
                O Encontro Perfeito <span class="bg-gradient-to-r from-primary via-primary to-primary/60 bg-clip-text text-transparent">Começa Aqui</span>
            </h1>

            {{-- Subtítulo --}}
            <p class="text-base text-zinc-400 leading-normal font-medium w-full max-w-2xl">
                Descubra uma seleção exclusiva de acompanhantes de alto nível em todo o Brasil. Privacidade, segurança e experiências inesquecíveis.
            </p>

            {{-- Banner Promocional com glass --}}
            <div class="w-full max-w-[600px]">
                <a href="{{ route('planos') }}"
                   class="group relative flex items-center justify-center gap-3 w-full rounded-full px-6 py-3.5 overflow-hidden transition-all duration-300">
                    {{-- Glass background --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/10 via-primary/5 to-transparent border border-primary/20 rounded-full group-hover:border-primary/30 transition-colors"></div>
                    <div class="absolute inset-0 rounded-full bg-white/[0.02] backdrop-blur-sm"></div>
                    {{-- Shimmer hover effect --}}
                    <div class="absolute -inset-full top-0 block w-1/2 h-full skew-x-[-25deg] bg-gradient-to-r from-transparent via-white/5 to-transparent group-hover:animate-[shimmer_1.5s_ease-in-out]"></div>
                    {{-- Content --}}
                    <span class="relative z-10 text-lg">⭐</span>
                    <span class="relative z-10 text-sm font-bold text-zinc-300 group-hover:text-white transition-colors">
                        Cadastre-se agora e ganhe <span class="text-primary">7 dias grátis</span> no plano Premium
                    </span>
                    <svg class="relative z-10 w-4 h-4 text-primary/60 group-hover:text-primary group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                </a>
            </div>

            {{-- Search Box com glass --}}
            <div class="w-full max-w-[600px] mt-4">
                <form action="{{ route('explorar') }}" method="GET">
                    <div class="relative flex items-center justify-between rounded-full px-2 py-2 gap-2 overflow-hidden">
                        <div class="absolute inset-0 bg-zinc-900/80 border border-zinc-800/80 rounded-full backdrop-blur-md"></div>
                        <div class="absolute inset-0 rounded-full shadow-[inset_0_1px_0_rgba(255,255,255,0.05)]"></div>
                        <div class="relative z-10 flex items-center gap-3 flex-1 pl-4">
                            <svg class="w-5 h-5 text-zinc-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input type="text" name="cidade" placeholder="Buscar por cidade..."
                                   class="flex-1 bg-transparent border-none text-zinc-300 placeholder-zinc-600 text-base focus:outline-none focus:ring-0">
                        </div>
                        <button type="submit" class="relative z-10 px-8 py-3.5 bg-primary hover:brightness-110 text-primary-foreground font-bold rounded-full transition-all cursor-pointer text-base shrink-0 shadow-lg shadow-primary/20 hover:shadow-primary/30">
                            Procurar
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </x-ui.container>
    </div>
</section>
