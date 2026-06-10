{{-- Seção Cidades em Destaque --}}
<x-ui.section padding="lg" class="bg-zinc-950 border-t border-zinc-800/20">
    <x-ui.container size="lg" class="flex flex-col gap-8">

        {{-- Header --}}
        <div class="flex items-center justify-between w-full">
            <h2 class="text-heading-3 font-bold text-white">Cidades em Destaque</h2>
            <a href="{{ route('explorar') }}" class="text-primary text-sm font-bold hover:brightness-110 transition-all cursor-pointer">
                Ver todas as cidades
            </a>
        </div>

        {{-- Grid de cidades --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cidades as $cidade)
            <a href="{{ route('explorar') }}?cidade={{ $cidade['slug'] }}"
               class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-matrix-500/20 cursor-pointer block">
                <div class="relative h-60 sm:h-80 w-full">
                    @if($cidade['image'])
                        <img src="{{ asset('storage/' . $cidade['image']) }}"
                             alt="{{ $cidade['name'] }}"
                             class="w-full h-full object-cover transition-opacity duration-300"
                             onerror="this.style.display='none'">
                    @endif
                    <div class="absolute inset-0 bg-black/40 z-10"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 z-20">
                        <div class="bg-black/70 rounded-xl px-4 py-3 flex flex-col gap-1 w-full">
                            <h3 class="text-white font-bold text-lg">{{ $cidade['name'] }}</h3>
                            <p class="text-primary text-xs font-bold">{{ $cidade['count'] }} modelos</p>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

    </x-ui.container>
</x-ui.section>
