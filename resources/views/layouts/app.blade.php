<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PLANETA BOYS - Premium Directory')</title>
    <meta name="description" content="@yield('description', 'O diretório mais exclusivo para o público masculino. Descubra acompanhantes de alto nível em todo o Brasil. Privacidade, segurança e experiências inesquecíveis.')">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'PLANETA BOYS - Premium Directory')">
    <meta property="og:description" content="@yield('description', 'O diretório mais exclusivo para o público masculino.')">
    <meta property="og:image" content="{{ asset('main.jpg') }}">
    <meta property="og:locale" content="pt_BR">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'PLANETA BOYS - Premium Directory')">
    <meta name="twitter:description" content="@yield('description', 'O diretório mais exclusivo para o público masculino.')">
    <meta name="twitter:image" content="{{ asset('main.jpg') }}">

    {{-- Google Fonts: Open Sans com preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Alpine.js via CDN (antes de qualquer script que use x-data) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Assets buildados (Vite dev server desligado) --}}
    @php
        $viteManifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    @endphp
    <link rel="stylesheet" href="{{ asset('build/' . $viteManifest['resources/css/app.css']['file']) }}">
    <script type="module" src="{{ asset('build/' . $viteManifest['resources/js/app.js']['file']) }}"></script>
</head>
<body class="min-h-screen bg-black text-white antialiased">

    {{-- Age Gate - Validação de Idade --}}
    @if(isset($show_age_gate) && $show_age_gate)
        <x-age-gate :show="true" />
    @endif

    {{-- Header --}}
    <header id="header" class="fixed top-0 left-0 right-0 z-50 h-20 transition-all duration-300 border-b border-zinc-800/50" data-scrolled="false">
        <div class="container mx-auto flex h-full items-center justify-between px-4 lg:px-8">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="relative z-10 flex items-center gap-3 cursor-pointer">
                <span class="text-2xl font-black uppercase italic tracking-tight text-white"><span class="text-primary">PLANETA</span><span class="text-foreground">BOYS</span></span>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('explorar') }}" class="flex items-center gap-2 text-[13px] font-bold text-zinc-400 hover:text-white transition-all duration-200 uppercase tracking-[1.5px] cursor-pointer">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Explorar
                </a>
                <a href="{{ route('destaques') }}" class="flex items-center gap-2 text-[13px] font-bold text-yellow-400 hover:text-yellow-300 transition-all duration-200 uppercase tracking-[1.5px] cursor-pointer">
                    <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    Destaques
                </a>
                @if(!auth()->check() || !auth()->user()->isAdmin())
                <a href="{{ route('planos') }}" class="flex items-center gap-2 text-[13px] font-bold text-zinc-400 hover:text-white transition-all duration-200 uppercase tracking-[1.5px] cursor-pointer">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7z"/></svg>
                    Planos
                </a>
                @endif
            </nav>

            {{-- Desktop Auth --}}
            <div class="hidden md:flex items-center gap-6">
                @auth
                    {{-- Dropdown do Perfil --}}
                    <div class="relative" id="user-dropdown-container">
                        <button id="user-dropdown-btn" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-zinc-800 transition-all duration-200 group cursor-pointer">
                            {{-- Avatar — foto do perfil (thumbnail) ou avatar manual ou ícone --}}
                            <div class="w-8 h-8 rounded-full bg-zinc-800 border border-zinc-700 overflow-hidden shrink-0">
                                @php
                                    $avatarSrc = auth()->user()->profile?->primaryImage()?->thumbUrl()
                                        ?? auth()->user()->avatar;
                                @endphp
                                @if($avatarSrc)
                                    <img src="{{ $avatarSrc }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-zinc-800">
                                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                @endif
                            </div>
                            <svg id="dropdown-chevron" class="w-4 h-4 text-zinc-500 group-hover:text-zinc-300 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
                        </button>

                        {{-- Card de Opções --}}
                        <div id="user-dropdown-menu" class="hidden absolute right-0 top-full mt-2 w-64 bg-zinc-900 border border-zinc-800 rounded-xl shadow-2xl shadow-black/50 overflow-hidden z-50 origin-top-right">
                            {{-- Cabeçalho com info do usuário + thumbnail --}}
                            <div class="px-4 py-3 border-b border-zinc-800 bg-zinc-900/50 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-zinc-800 border border-zinc-700 overflow-hidden shrink-0">
                                    @php
                                        $dropdownAvatar = auth()->user()->profile?->primaryImage()?->thumbUrl()
                                            ?? auth()->user()->avatar;
                                    @endphp
                                    @if($dropdownAvatar)
                                        <img src="{{ $dropdownAvatar }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-zinc-800">
                                            <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-white font-bold text-sm truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-zinc-500 text-xs truncate">{{ auth()->user()->email }}</p>
                                </div>
                            </div>

                            {{-- Opções do Menu --}}
                            <div class="py-1">
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-300 hover:text-white hover:bg-zinc-800/50 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                    Administração
                                </a>
                                @else
                                <a href="{{ route('perfil') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-300 hover:text-white hover:bg-zinc-800/50 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Meu Perfil
                                </a>
                                <a href="{{ route('favoritos.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-300 hover:text-white hover:bg-zinc-800/50 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    Meus Favoritos
                                </a>
                                <a href="{{ route('meu.plano') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-300 hover:text-white hover:bg-zinc-800/50 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7z"/></svg>
                                    Meu Plano
                                </a>
                                <a href="{{ route('conta.excluir.form') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-400 hover:text-red-400 hover:bg-zinc-800/50 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Excluir Conta
                                </a>
                                @endif
                            </div>

                            {{-- Divisor --}}
                            <div class="h-px bg-zinc-800 mx-3"></div>

                            {{-- Sair --}}
                            <div class="py-1">
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-400 hover:text-red-400 hover:bg-zinc-800/50 transition-colors text-left cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2 px-6 py-3 text-sm font-bold text-zinc-400 hover:text-white rounded-lg hover:bg-zinc-800 transition-all uppercase tracking-widest cursor-pointer">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Login
                    </a>
                    <a href="{{ route('registro') }}" class="px-6 py-3 text-base font-bold text-black bg-primary hover:brightness-110 rounded-full transition-all uppercase tracking-wider cursor-pointer">
                        Criar Perfil
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 text-zinc-400 hover:text-white transition-colors relative z-10 cursor-pointer" aria-label="Abrir menu">
                <svg id="icon-menu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="fixed inset-0 top-20 z-40 bg-black/95 backdrop-blur-xl md:hidden hidden">
            <nav class="flex flex-col p-6 gap-2">
                <a href="{{ route('explorar') }}" class="flex items-center gap-3 px-4 py-4 text-lg font-bold text-zinc-300 hover:text-white hover:bg-zinc-800/50 rounded-xl transition-all uppercase tracking-wider cursor-pointer">Explorar</a>
                <a href="{{ route('destaques') }}" class="flex items-center gap-3 px-4 py-4 text-lg font-bold text-yellow-400 hover:text-yellow-300 hover:bg-zinc-800/50 rounded-xl transition-all uppercase tracking-wider cursor-pointer">⭐ Destaques</a>
                @if(!auth()->check() || !auth()->user()->isAdmin())
                <a href="{{ route('planos') }}" class="flex items-center gap-3 px-4 py-4 text-lg font-bold text-zinc-300 hover:text-white hover:bg-zinc-800/50 rounded-xl transition-all uppercase tracking-wider cursor-pointer">Planos</a>
                @endif
                <div class="h-px bg-zinc-800 my-4"></div>
                @auth
                    <div class="px-4 py-3 border-b border-zinc-800 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-zinc-800 border border-zinc-700 overflow-hidden shrink-0">
                            @php
                                $mobileAvatar = auth()->user()->profile?->primaryImage()?->thumbUrl()
                                    ?? auth()->user()->avatar;
                            @endphp
                            @if($mobileAvatar)
                                <img src="{{ $mobileAvatar }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-zinc-800">
                                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-white font-bold text-sm truncate">{{ auth()->user()->name }}</p>
                            <p class="text-zinc-500 text-xs truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-4 text-lg font-bold text-zinc-300 hover:text-white hover:bg-zinc-800/50 rounded-xl transition-all uppercase tracking-wider cursor-pointer">Administração</a>
                    @else
                    <a href="{{ route('perfil') }}" class="flex items-center gap-3 px-4 py-4 text-lg font-bold text-zinc-300 hover:text-white hover:bg-zinc-800/50 rounded-xl transition-all uppercase tracking-wider cursor-pointer">Meu Perfil</a>
                    <a href="{{ route('favoritos.index') }}" class="flex items-center gap-3 px-4 py-4 text-lg font-bold text-zinc-300 hover:text-white hover:bg-zinc-800/50 rounded-xl transition-all uppercase tracking-wider cursor-pointer">Meus Favoritos</a>
                    <a href="{{ route('meu.plano') }}" class="flex items-center gap-3 px-4 py-4 text-lg font-bold text-zinc-300 hover:text-white hover:bg-zinc-800/50 rounded-xl transition-all uppercase tracking-wider cursor-pointer">Meu Plano</a>
                    <a href="{{ route('conta.excluir.form') }}" class="flex items-center gap-3 px-4 py-4 text-lg font-bold text-zinc-500 hover:text-red-400 hover:bg-zinc-800/50 rounded-xl transition-all uppercase tracking-wider cursor-pointer">Excluir Conta</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-4 text-lg font-bold text-zinc-500 hover:text-white transition-colors uppercase tracking-wider cursor-pointer">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-4 text-lg font-bold text-zinc-300 hover:text-white rounded-xl hover:bg-zinc-800/50 transition-all uppercase tracking-wider cursor-pointer">Entrar</a>
                    <a href="{{ route('registro') }}" class="px-4 py-4 text-lg font-bold text-primary-foreground bg-primary hover:brightness-110 rounded-xl transition-all uppercase tracking-wider text-center cursor-pointer">Cadastrar</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Main --}}
    <main class="grow pt-20">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="w-full bg-zinc-900">
        <x-ui.container size="lg">
            <div class="flex flex-col md:flex-row justify-between gap-16 py-16">
            {{-- Coluna Esquerda --}}
            <div class="flex flex-col gap-4 max-w-[300px]">
                <h3 class="text-heading-3 font-heading"><span class="text-primary">PLANETA</span> <span class="text-foreground">BOYS</span></h3>
                <p class="text-zinc-400 text-[14px] font-normal">O melhor diretório premium para conectar você aos melhores acompanhantes masculinos e trans.</p>
            </div>

            {{-- Links Container --}}
            <div class="flex flex-col md:flex-row gap-16">
                {{-- Coluna 1 - Plataforma --}}
                <div class="flex flex-col gap-4">
                    <h4 class="text-white text-[16px] font-bold">Plataforma</h4>
                    <a href="{{ route('explorar') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Explorar Modelos</a>
                    <a href="{{ route('destaques') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Destaques 💎</a>
                    <a href="{{ route('sobre') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Sobre Nós</a>
                    <a href="{{ route('planos') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Planos Premium</a>
                    <a href="{{ route('faq') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Como Funciona</a>
                </div>

                {{-- Coluna 2 - Suporte --}}
                <div class="flex flex-col gap-4">
                    <h4 class="text-white text-[16px] font-bold">Suporte</h4>
                    <a href="{{ route('faq') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">FAQ</a>
                    <a href="{{ route('contato') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Contato</a>
                    <a href="{{ route('seguranca') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Segurança</a>
                    <a href="{{ route('regras') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Regras</a>
                    <a href="{{ route('termos') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Termos de Uso</a>
                    <a href="{{ route('privacidade') }}" class="text-zinc-400 text-[14px] font-normal hover:text-white transition-colors cursor-pointer">Privacidade</a>
                </div>

                {{-- Coluna 3 - Redes Sociais --}}
                <div class="flex flex-col gap-4">
                    <h4 class="text-white text-[16px] font-bold">Redes Sociais</h4>
                    <div class="flex items-center gap-3">
                        <a href="https://instagram.com/planetaboys" target="_blank" rel="noopener noreferrer" class="text-zinc-400 hover:text-primary transition-colors cursor-pointer" aria-label="Instagram">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        </a>
                        <a href="https://x.com/planetaboys" target="_blank" rel="noopener noreferrer" class="text-zinc-400 hover:text-primary transition-colors cursor-pointer" aria-label="Twitter / X">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-2.6.5-5.4 2.9-6.4-1.3-.2-2.6-.6-3.6-1.4C9 1 7.4.6 5.8 1.2 4 2 2.6 4 3 6c-.8.4-1.6.8-2.4 1.2C.5 7.3 0 8.5 0 10c0 4.4 3.6 8 8 8 5.5 0 10-4.5 10-10 0-.2 0-.5-.1-.7z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </x-ui.container>
        {{-- Copyright Bar --}}
        <div class="border-t border-zinc-800 py-6">
            <x-ui.container size="lg">
                <p class="text-zinc-500 text-[12px] text-center">&copy; 2026 Planeta Boys. Todos os direitos reservados.</p>
            </x-ui.container>
        </div>
    </footer>

    {{-- Cookie Consent Banner --}}
    <x-cookie-consent />

    @stack('scripts')
</body>
</html>