{{-- Hero Section --}}
<x-ui.section padding="lg" class="bg-zinc-950 flex items-center justify-center min-h-[500px] md:min-h-[680px]">
    <x-ui.container size="lg" class="text-center">
        <div class="flex flex-col items-center gap-8 max-w-[800px] mx-auto w-full">

            {{-- Badge --}}
            <div class="inline-flex items-center justify-center px-4 py-1.5 bg-zinc-900 rounded-full">
                <span class="text-primary text-xs font-bold">🔥 A Nova Referência Premium</span>
            </div>

            {{-- Título --}}
            <h1 class="text-[48px] font-[800] text-white leading-tight w-full">
                O Encontro Perfeito Começa Aqui
            </h1>

            {{-- Subtítulo --}}
            <p class="text-base text-zinc-400 leading-normal font-medium w-full">
                Descubra uma seleção exclusiva de acompanhantes de alto nível em todo o Brasil. Privacidade, segurança e experiências inesquecíveis.
            </p>

            {{-- Search Box --}}
            <div class="w-full max-w-[600px] mt-4">
                <form action="{{ route('explorar') }}" method="GET">
                    <div class="flex items-center justify-between bg-zinc-900 rounded-[40px] px-6 py-2 gap-3">
                        <div class="flex items-center gap-3 flex-1">
                            <svg class="w-5 h-5 text-zinc-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input type="text" name="cidade" placeholder="Buscar por cidade..." class="flex-1 bg-transparent border-none text-zinc-400 placeholder-zinc-400 text-base focus:outline-none focus:ring-0">
                        </div>
                        <button type="submit" class="px-8 py-4 bg-primary hover:brightness-110 text-primary-foreground font-bold rounded-[32px] transition-all cursor-pointer text-base shrink-0">
                            Procurar
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </x-ui.container>
</x-ui.section>
