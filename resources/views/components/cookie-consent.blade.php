<div id="cookie-consent" class="fixed bottom-0 left-0 right-0 z-[100] bg-zinc-900/95 backdrop-blur-xl border-t border-zinc-800 shadow-2xl shadow-black/50 hidden" role="dialog" aria-label="Aviso de cookies">
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-4 flex-1">
                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center shrink-0 mt-1">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5A10 10 0 0 0 12 2z"/>
                        <path d="M8.5 8.5v.01"/>
                        <path d="M16 15.5v.01"/>
                        <path d="M12 12v.01"/>
                        <path d="M11 17v.01"/>
                        <path d="M7 14v.01"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-sm mb-1">Privacidade e Cookies</h3>
                    <p class="text-zinc-400 text-xs leading-relaxed max-w-2xl">
                        Utilizamos cookies essenciais para o funcionamento da plataforma e, com o seu consentimento, cookies de análise para melhorar sua experiência. 
                        Ao clicar em "Aceitar Todos", você concorda com o uso de todos os cookies. 
                        Leia nossa <a href="{{ route('privacidade') }}" class="text-primary hover:brightness-125 underline cursor-pointer">Política de Privacidade</a>.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <button id="cookie-reject" class="px-5 py-2.5 text-xs font-bold text-zinc-400 hover:text-white border border-zinc-700 hover:border-zinc-500 rounded-lg transition-all uppercase tracking-wider cursor-pointer">
                    Recusar
                </button>
                <button id="cookie-accept" class="px-5 py-2.5 text-xs font-bold text-black bg-primary hover:brightness-110 rounded-lg transition-all uppercase tracking-wider cursor-pointer">
                    Aceitar Todos
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var cookieConsent = document.getElementById('cookie-consent');
    var acceptBtn = document.getElementById('cookie-accept');
    var rejectBtn = document.getElementById('cookie-reject');

    if (cookieConsent && !localStorage.getItem('cookie_consent')) {
        cookieConsent.classList.remove('hidden');
    }

    if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
            localStorage.setItem('cookie_consent', 'accepted');
            cookieConsent.classList.add('hidden');
        });
    }

    if (rejectBtn) {
        rejectBtn.addEventListener('click', function() {
            localStorage.setItem('cookie_consent', 'rejected');
            cookieConsent.classList.add('hidden');
        });
    }
});
</script>
