@props([
    'show' => true,
])

@if($show)
<div id="age-gate" class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-sm">
    <div class="relative bg-zinc-900 border border-zinc-800 rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl">
        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-600/20 rounded-full mb-4">
                <span class="text-4xl">🔞</span>
            </div>
            <h2 class="text-3xl font-black text-white uppercase tracking-wider mb-2">
                Acesso Restrito
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto rounded-full"></div>
        </div>

        {{-- Conteúdo --}}
        <div class="space-y-4 mb-6">
            <div class="bg-red-600/10 border border-red-600/30 rounded-xl p-4">
                <p class="text-red-400 text-sm font-bold uppercase tracking-wider mb-2">
                    Aviso de Conformidade (ECA Digital)
                </p>
                <p class="text-zinc-300 text-sm leading-relaxed">
                    Em cumprimento às leis brasileiras, o conteúdo explícito desta plataforma é destinado exclusivamente a maiores de 18 anos. Ao continuar, você confirma que tem idade legal para acessar este material.
                </p>
            </div>

            <p class="text-zinc-400 text-sm text-center">
                Este site contém material adulto. Ao acessar, você declara ter mais de 18 anos.
            </p>
        </div>

        {{-- Botões --}}
        <div class="space-y-3">
            <button onclick="confirmAge()" 
                    class="w-full py-4 bg-primary hover:bg-primary/90 text-black font-black uppercase tracking-wider rounded-xl transition-all duration-200 cursor-pointer">
                Tenho mais de 18 anos - Entrar
            </button>
            
            <a href="https://www.google.com" 
               class="block w-full py-4 bg-zinc-800 hover:bg-zinc-700 text-zinc-400 font-black uppercase tracking-wider rounded-xl transition-all duration-200 text-center cursor-pointer">
                Sou menor de idade - Sair
            </a>
        </div>

        {{-- Footer do Modal --}}
        <div class="mt-6 pt-6 border-t border-zinc-800 text-center">
            <a href="{{ route('termos') }}" class="text-zinc-500 hover:text-primary text-sm transition-colors cursor-pointer">
                Termos de Uso
            </a>
            <span class="text-zinc-700 mx-2">|</span>
            <a href="{{ route('privacidade') }}" class="text-zinc-500 hover:text-primary text-sm transition-colors cursor-pointer">
                Política de Privacidade
            </a>
        </div>
    </div>
</div>

<script>
function confirmAge() {
    fetch('{{ route('age-gate.confirm') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('age-gate').remove();
            document.body.style.overflow = '';
        }
    })
    .catch(error => console.error('Erro:', error));
}

// Bloquear scroll enquanto modal estiver ativo
document.body.style.overflow = 'hidden';
</script>
@endif
