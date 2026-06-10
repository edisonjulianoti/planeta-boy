<div class="w-full max-w-md shadow-lg shadow-primary/10 mx-auto">
    <div class="relative">
        <select 
            id="cidade-search" 
            name="cidade"
            class="w-full px-4 py-3 pr-10 bg-zinc-900 border border-zinc-800 rounded-xl text-white appearance-none cursor-pointer focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
            onchange="window.location.href=this.value"
        >
            <option value="">Selecione ou busque sua Cidade...</option>
            @foreach($cidades as $cidade)
                <option value="{{ route('explorar', ['cidade' => $cidade->slug]) }}">
                    {{ $cidade->name }} - {{ $cidade->state }}
                </option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
        </div>
    </div>
</div>
