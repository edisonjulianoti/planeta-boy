{{-- Sessão Plataforma --}}
<x-ui.section padding="lg" class="bg-zinc-950">
    <x-ui.container size="lg" class="flex flex-col items-center gap-16">

        {{-- Header --}}
        <div class="max-w-[800px] w-full mx-auto text-center flex flex-col items-center gap-6">
            <div class="inline-flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-4 py-1.5">
                <div class="w-2 h-2 bg-primary rounded-full"></div>
                <span class="text-primary text-xs font-bold">Porque escolher</span>
            </div>
            <h2 class="text-heading-1 font-heading text-white inline-flex flex-wrap justify-center items-center gap-2">
                <span>A Plataforma Mais</span>
                <span class="text-primary">Confiável</span>
            </h2>
            <p class="text-zinc-400 text-base leading-normal max-w-[800px] mx-auto">
                Oferecemos a melhor experiência com segurança, discrição e as acompanhantes mais exclusivas do Brasil.
            </p>
        </div>

        {{-- Grid de cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-[1200px] w-full mx-auto">

            {{-- Perfis Reais --}}
            <a href="#" class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-8 flex flex-col gap-4 hover:border-primary/50 hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 cursor-pointer">
                <div class="bg-zinc-950 rounded-lg p-3 w-12 h-12 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <h3 class="text-white font-bold text-lg">Perfis Reais</h3>
                <p class="text-zinc-400 text-sm leading-normal">Todo perfil possui o Selo de Verificação para garantir sua segurança e evitar fakes.</p>
            </a>

            {{-- Zero Anúncios --}}
            <a href="#" class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-8 flex flex-col gap-4 hover:border-primary/50 hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 cursor-pointer">
                <div class="bg-zinc-950 rounded-lg p-3 w-12 h-12 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 3h12l4 6-10 13L2 9Z"/><path d="M11 3 8 9l4 13 4-13-3-6"/><path d="M2 9h20"/></svg>
                </div>
                <h3 class="text-white font-bold text-lg">Zero Anúncios</h3>
                <p class="text-zinc-400 text-sm leading-normal">Uma experiência limpa e premium, livre de banners, pop-ups e poluição visual.</p>
            </a>

            {{-- Total Anonimato --}}
            <a href="#" class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-8 flex flex-col gap-4 hover:border-primary/50 hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 cursor-pointer">
                <div class="bg-zinc-950 rounded-lg p-3 w-12 h-12 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                </div>
                <h3 class="text-white font-bold text-lg">Total Anonimato</h3>
                <p class="text-zinc-400 text-sm leading-normal">Não exigimos cadastro do usuário. Sua navegação é privada e seus dados protegidos.</p>
            </a>

            {{-- Design de Luxo --}}
            <a href="#" class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-8 flex flex-col gap-4 hover:border-primary/50 hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 cursor-pointer">
                <div class="bg-zinc-950 rounded-lg p-3 w-12 h-12 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                </div>
                <h3 class="text-white font-bold text-lg">Design de Luxo</h3>
                <p class="text-zinc-400 text-sm leading-normal">Plataforma moderna e intuitiva, pensada para funcionar perfeitamente no seu celular e web.</p>
            </a>

            {{-- Contato Direto --}}
            <a href="#" class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-8 flex flex-col gap-4 hover:border-primary/50 hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 cursor-pointer">
                <div class="bg-zinc-950 rounded-lg p-3 w-12 h-12 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h3 class="text-white font-bold text-lg">Contato Direto</h3>
                <p class="text-zinc-400 text-sm leading-normal">Fale diretamente com a acompanhante pelo WhatsApp, sem intermediários ou taxas surpresa.</p>
            </a>

            {{-- O Melhor do Brasil --}}
            <a href="#" class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-8 flex flex-col gap-4 hover:border-primary/50 hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 cursor-pointer">
                <div class="bg-zinc-950 rounded-lg p-3 w-12 h-12 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7z"/></svg>
                </div>
                <h3 class="text-white font-bold text-lg">O Melhor do Brasil</h3>
                <p class="text-zinc-400 text-sm leading-normal">Você merece uma experiência premium, não jogue seu dinheiro no lixo com portais amadores.</p>
            </a>

        </div>
    </x-ui.container>
</x-ui.section>
