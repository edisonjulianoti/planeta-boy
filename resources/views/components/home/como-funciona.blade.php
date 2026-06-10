{{-- Sessão Como Funciona --}}
<x-ui.section padding="lg" class="bg-zinc-950 border-t border-zinc-800/20">
    <x-ui.container size="lg" class="flex flex-col items-center gap-16">

        {{-- Header --}}
        <div class="flex flex-col items-center gap-6 text-center">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-4 py-1.5">
                <div class="w-2 h-2 bg-primary rounded-full"></div>
                <span class="text-primary text-xs font-bold">Guia Rápido</span>
            </div>

            {{-- Título --}}
            <div class="flex flex-wrap justify-center items-center gap-3">
                <span class="text-heading-1 font-heading text-white leading-tight">Como o Paraíso</span>
                <span class="text-heading-1 font-heading text-primary leading-tight">Funciona</span>
            </div>

            {{-- Subtítulo --}}
            <p class="text-lg text-zinc-400">
                Experiência discreta e premium. Conexões exclusivas pensadas para o seu conforto e segurança.
            </p>
        </div>

        {{-- Colunas --}}
        <div class="flex flex-col md:flex-row gap-16 lg:gap-16 w-full">

            {{-- Coluna Esquerda --}}
            <div class="flex flex-col gap-10 flex-1">

                {{-- Header da coluna --}}
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-zinc-900 rounded-lg flex items-center justify-center text-xl">👤</div>
                    <h3 class="text-white font-bold text-xl">Para Clientes Exigentes</h3>
                </div>

                {{-- Lista --}}
                <div class="flex flex-col gap-8">

                    <div class="flex gap-4">
                        <div class="w-1 h-1 rounded-full bg-primary mt-2 shrink-0"></div>
                        <div class="flex flex-col gap-4">
                            <h4 class="text-white font-bold text-base">Navegação Sigilosa</h4>
                            <p class="text-zinc-400 text-sm">Descubra perfis de alto padrão de maneira totalmente anônima e privada. Sem necessidade de passos burocráticos e cadastros extensos para sua busca.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1 h-1 rounded-full bg-primary mt-2 shrink-0"></div>
                        <div class="flex flex-col gap-4">
                            <h4 class="text-white font-bold text-base">Selo de Confiança</h4>
                            <p class="text-zinc-400 text-sm">Sua tranquilidade é prioridade. Perfis verificados pela nossa equipe recebem um emblema de autenticidade para garantir interações 100% seguras.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1 h-1 rounded-full bg-primary mt-2 shrink-0"></div>
                        <div class="flex flex-col gap-4">
                            <h4 class="text-white font-bold text-base">Conexões Diretas</h4>
                            <p class="text-zinc-400 text-sm">Encontrou um modelo que atende suas expectativas? O desenrolar da conversa ocorre sem a necessidade de intermediários prejudicando a comunicação.</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Coluna Direita --}}
            <div class="flex flex-col gap-10 flex-1">

                {{-- Header da coluna --}}
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-zinc-900 rounded-lg flex items-center justify-center text-xl">👑</div>
                    <h3 class="text-white font-bold text-xl">Para Nossos Talentos</h3>
                </div>

                {{-- Lista --}}
                <div class="flex flex-col gap-8">

                    <div class="flex gap-4">
                        <div class="w-1 h-1 rounded-full bg-primary mt-2 shrink-0"></div>
                        <div class="flex flex-col gap-4">
                            <h4 class="text-white font-bold text-base">Destaque Seu Portfólio</h4>
                            <p class="text-zinc-400 text-sm">Construa sua vitrine digital com facilidade em poucos cliques. Adicione uma galeria de fotos atrativas e ressalte suas qualidades de forma elegante.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1 h-1 rounded-full bg-primary mt-2 shrink-0"></div>
                        <div class="flex flex-col gap-4">
                            <h4 class="text-white font-bold text-base">Independência Total</h4>
                            <p class="text-zinc-400 text-sm">As regras são totalmente suas. Tenha autonomia para gerenciar horários, honorários particulares e elevar seu alcance se tornando um talento verificado.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-1 h-1 rounded-full bg-primary mt-2 shrink-0"></div>
                        <div class="flex flex-col gap-4">
                            <h4 class="text-white font-bold text-base">Retenha Todo o Valor</h4>
                            <p class="text-zinc-400 text-sm">O contato flui de maneira particular com seu futuro cliente. Dessa maneira, você retém o valor pago e mantém autonomia total sob seus agendamentos.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </x-ui.container>
</x-ui.section>
