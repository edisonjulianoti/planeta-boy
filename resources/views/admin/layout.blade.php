<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — PLANETA BOYS</title>
    <meta name="description" content="@yield('description', 'Painel administrativo PLANETA BOYS')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-foreground overflow-hidden m-0">

<div class="flex fixed inset-0 overflow-hidden">

    {{-- Mobile Menu Button --}}
    <button id="admin-mobile-menu-btn" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-zinc-900 border border-zinc-800 rounded-lg text-zinc-400 hover:text-white hover:border-zinc-700 transition-all cursor-pointer" aria-label="Abrir menu">
        <svg id="admin-icon-menu" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        <svg id="admin-icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>

    {{-- Overlay --}}
    <div id="admin-sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 hidden md:hidden"></div>

    {{-- Sidebar --}}
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-60 bg-zinc-900 border-r border-zinc-800 flex flex-col h-full transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out">
        <div class="p-5 border-b border-zinc-800">
            <a href="{{ route('home') }}" class="text-lg font-black uppercase italic tracking-tight cursor-pointer">
                <span class="text-primary">PLANETA</span><span class="text-foreground">BOYS</span>
            </a>
            <p class="text-zinc-500 text-xs mt-1 uppercase tracking-widest">Painel Admin</p>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.users') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.users') ? 'bg-primary/10 text-primary' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Usuários
            </a>
            <a href="{{ route('admin.profiles') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.profiles') ? 'bg-primary/10 text-primary' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Perfis
            </a>
            <a href="{{ route('admin.plans') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.plans*') ? 'bg-primary/10 text-primary' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                Planos
            </a>
            <a href="{{ route('admin.cities') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.cities*') ? 'bg-primary/10 text-primary' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Cidades
            </a>
            <a href="{{ route('admin.services') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.services*') ? 'bg-primary/10 text-primary' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                Serviços
            </a>
            <a href="{{ route('admin.subscriber-categories') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.subscriber-categories*') ? 'bg-primary/10 text-primary' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Categorias Assinantes
            </a>
            <a href="{{ route('admin.faqs') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.faqs*') ? 'bg-primary/10 text-primary' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                FAQs
            </a>
            <a href="{{ route('admin.subscriptions') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.subscriptions*') ? 'bg-primary/10 text-primary' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }} cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                Assinaturas
            </a>
        </nav>

        <div class="p-4 border-t border-zinc-800 mt-auto">
            <p class="text-zinc-500 text-xs mb-2 truncate">{{ auth()->user()->name }}</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-zinc-500 hover:text-red-400 transition-colors uppercase tracking-wider font-bold cursor-pointer">Sair</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-zinc-900/50 border-b border-zinc-800 px-8 md:px-8 py-4 md:py-4">
            <h1 class="text-white font-black uppercase tracking-wider text-lg">@yield('title', 'Dashboard')</h1>
        </header>

        <main class="flex-1 p-4 md:p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

<script>
(function() {
    const menuBtn = document.getElementById('admin-mobile-menu-btn');
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');
    const iconMenu = document.getElementById('admin-icon-menu');
    const iconClose = document.getElementById('admin-icon-close');
    let menuOpen = false;

    function toggleMenu() {
        menuOpen = !menuOpen;
        if (menuOpen) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            iconMenu.classList.add('hidden');
            iconClose.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            iconMenu.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }
    }

    function closeMenu() {
        if (menuOpen) {
            menuOpen = false;
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            iconMenu.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }
    }

    if (menuBtn) {
        menuBtn.addEventListener('click', toggleMenu);
    }

    if (overlay) {
        overlay.addEventListener('click', closeMenu);
    }

    // Fechar menu ao clicar em links da sidebar
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', closeMenu);
    });
})();
</script>

</body>
</html>
