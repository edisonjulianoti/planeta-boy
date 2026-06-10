@extends('layouts.app')

@section('title', 'Explorar - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950 py-20">

    <x-ui.container size="lg">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <button id="toggle-sidebar" class="md:hidden p-2 text-zinc-400 hover:text-white transition-colors cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <h1 class="text-heading-1 font-heading text-white">Explorar Perfis</h1>
            </div>
            <p class="text-[16px] text-zinc-400">{{ $perfis->total() }} perfis encontrados</p>
        </div>

        <div class="flex gap-8 h-[calc(100vh-100px)]">

        {{-- Sidebar de Filtros --}}
        <aside id="sidebar" class="hidden md:block w-[280px] shrink-0 pr-2 pb-8 overflow-y-auto h-full">
            <form action="{{ route('explorar') }}" method="GET" class="space-y-10" id="filtroForm">

                {{-- Localização --}}
                <div class="space-y-4">
                    <h3 class="text-[18px] font-bold text-white">Localização</h3>
                    <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">
                        <input type="text" 
                               name="cidade" 
                               placeholder="Qualquer cidade..." 
                               value="{{ request('cidade') }}"
                               class="w-full bg-transparent text-zinc-300 placeholder-zinc-500 focus:outline-none text-[14px] filtro-change"
                               onchange="document.getElementById('filtroForm').submit()">
                    </div>
                </div>

                {{-- Serviços Principais --}}
                <div class="space-y-4">
                    <h3 class="text-[18px] font-bold text-white">Serviços Principais</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($servicos->take(6) as $servico)
                            <?php
                            $servicosRequest = request('servicos');
                            $servicosArray = [];
                            if (is_array($servicosRequest)) {
                                foreach ($servicosRequest as $s) {
                                    if (is_string($s) && strpos($s, ',') !== false) {
                                        $servicosArray = array_merge($servicosArray, explode(',', $s));
                                    } else {
                                        $servicosArray[] = $s;
                                    }
                                }
                            } elseif (is_string($servicosRequest)) {
                                $servicosArray = explode(',', $servicosRequest);
                            }
                            $servicosArray = array_filter(array_map('intval', $servicosArray));
                            ?>
                            <button type="button" 
                                    onclick="toggleServico({{ $servico->id }})"
                                    data-servico="{{ $servico->id }}"
                                    class="px-4 py-2 rounded-full text-[14px] transition-all cursor-pointer {{ in_array($servico->id, $servicosArray) ? 'bg-primary text-black' : 'bg-transparent border border-zinc-700 text-zinc-300 hover:border-zinc-600' }}">
                                {{ $servico->name }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="servicos[]" id="servicos-input" value="{{ implode(',', request('servicos', [])) }}">
                </div>

                {{-- Faixa Etária --}}
                <div class="space-y-4">
                    <h3 class="text-[18px] font-bold text-white">Faixa Etária</h3>
                    <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">
                        <input type="text" 
                               name="idade" 
                               placeholder="Todas as idades" 
                               value="{{ request('idade') }}"
                               class="w-full bg-transparent text-zinc-300 placeholder-zinc-500 focus:outline-none text-[14px] filtro-change"
                               onchange="document.getElementById('filtroForm').submit()">
                    </div>
                </div>

                {{-- Verificado --}}
                <div class="space-y-4 px-4">
                    <div class="flex items-center gap-4">
                        <label class="relative cursor-pointer">
                            <input type="checkbox" 
                                   name="verificado" 
                                   value="1" 
                                   {{ request('verificado') ? 'checked' : '' }}
                                   class="sr-only peer filtro-change"
                                   onchange="document.getElementById('filtroForm').submit()">
                            <div class="w-11 h-6 bg-zinc-700 peer-checked:bg-primary rounded-full transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                        </label>
                        <div class="flex-1">
                            <h4 class="text-[16px] font-bold text-white">Somente Verificados</h4>
                            <p class="text-[13px] text-zinc-400">Exibir apenas diamantes 💎</p>
                        </div>
                    </div>
                </div>

                {{-- Características --}}
                <div class="space-y-4">
                    <h3 class="text-[18px] font-bold text-primary">Características</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="caracteristicas[]" value="loiro" {{ in_array('loiro', request('caracteristicas', [])) ? 'checked' : '' }} class="w-4 h-4 rounded border-zinc-700 bg-zinc-800 text-primary filtro-change">
                            <span class="text-sm text-zinc-300">Loiro</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="caracteristicas[]" value="moreno" {{ in_array('moreno', request('caracteristicas', [])) ? 'checked' : '' }} class="w-4 h-4 rounded border-zinc-700 bg-zinc-800 text-primary filtro-change">
                            <span class="text-sm text-zinc-300">Moreno</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="caracteristicas[]" value="ruivo" {{ in_array('ruivo', request('caracteristicas', [])) ? 'checked' : '' }} class="w-4 h-4 rounded border-zinc-700 bg-zinc-800 text-primary filtro-change">
                            <span class="text-sm text-zinc-300">Ruivo</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="caracteristicas[]" value="oriental" {{ in_array('oriental', request('caracteristicas', [])) ? 'checked' : '' }} class="w-4 h-4 rounded border-zinc-700 bg-zinc-800 text-primary filtro-change">
                            <span class="text-sm text-zinc-300">Oriental</span>
                        </label>
                    </div>
                </div>

                {{-- Avaliações --}}
                <div class="space-y-3">
                    <h3 class="text-[18px] font-bold text-primary">Avaliações</h3>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    onclick="setAvaliacao({{ $i }})"
                                    data-avaliacao="{{ $i }}"
                                    class="text-[32px] transition-colors cursor-pointer {{ $i <= (request('avaliacao_min', 0)) ? 'text-primary' : 'text-zinc-700 hover:text-zinc-500' }}">
                                ★
                            </button>
                        @endfor
                    </div>
                    <p class="text-[14px] text-zinc-400">{{ request('avaliacao_min') ? $i . '+ estrelas' : 'Todas as avaliações' }}</p>
                    <input type="hidden" name="avaliacao_min" id="avaliacao-input" value="{{ request('avaliacao_min', 0) }}">
                </div>

                <script>
                    document.querySelectorAll('.filtro-change').forEach(el => {
                        el.addEventListener('change', () => document.getElementById('filtroForm').submit());
                    });

                    function toggleServico(id) {
                        const input = document.getElementById('servicos-input');
                        let valores = input.value ? input.value.split(',').map(v => parseInt(v)) : [];
                        const index = valores.indexOf(id);
                        
                        if (index > -1) {
                            valores.splice(index, 1);
                        } else {
                            valores.push(id);
                        }
                        
                        input.value = valores.join(',');
                        
                        // Atualiza visual
                        document.querySelectorAll('[data-servico]').forEach(btn => {
                            const btnId = parseInt(btn.dataset.servico);
                            if (valores.includes(btnId)) {
                                btn.classList.remove('bg-transparent', 'border', 'border-zinc-700', 'text-zinc-300');
                                btn.classList.add('bg-primary', 'text-black');
                            } else {
                                btn.classList.remove('bg-primary', 'text-black');
                                btn.classList.add('bg-transparent', 'border', 'border-zinc-700', 'text-zinc-300');
                            }
                        });
                        
                        document.getElementById('filtroForm').submit();
                    }

                    function setAvaliacao(valor) {
                        const input = document.getElementById('avaliacao-input');
                        input.value = valor;
                        
                        // Atualiza visual
                        document.querySelectorAll('[data-avaliacao]').forEach(btn => {
                            const btnVal = parseInt(btn.dataset.avaliacao);
                            if (btnVal <= valor) {
                                btn.classList.remove('text-zinc-700');
                                btn.classList.add('text-primary');
                            } else {
                                btn.classList.remove('text-primary');
                                btn.classList.add('text-zinc-700');
                            }
                        });
                        
                        document.getElementById('filtroForm').submit();
                    }
                </script>

                @if(request()->anyFilled(['cidade', 'servicos', 'idade', 'verificado', 'caracteristicas', 'avaliacao_min']))
                <a href="{{ route('explorar') }}" class="block text-center text-xs text-zinc-500 hover:text-zinc-300 transition-colors cursor-pointer">
                    Limpar filtros
                </a>
                @endif

            </form>
        </aside>

        {{-- Grid de Perfis --}}
        <div class="flex-1 overflow-y-auto pr-2">
            @if($perfis->isEmpty())
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <svg class="w-16 h-16 text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <p class="text-zinc-400 text-lg font-bold">Nenhum perfil encontrado</p>
                    <p class="text-zinc-600 text-sm mt-1">Tente ajustar os filtros</p>
                </div>
            @else
                <div id="perfis-grid" class="grid grid-cols-[repeat(auto-fill,minmax(232px,1fr))] gap-6">
                    @include('components.perfil-card', ['perfis' => $perfis, 'favoritedIds' => $favoritedIds])
                </div>

                <div id="loading" class="hidden justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                </div>

                <div id="no-more" class="hidden text-center py-8 text-zinc-500">
                    Não há mais perfis
                </div>
            @endif
        </div>
    </x-ui.container>
</div>

@endsection

@push('scripts')
<script>
const InfiniteScroll = {
    loading: false,
    nextPageUrl: "{{ $perfis->nextPageUrl() }}",
    hasMorePages: {{ $perfis->hasMorePages() ? 'true' : 'false' }},
    controller: null,
    retryCount: 0,
    maxRetries: 3,
    prefetchedData: null,

    init() {
        if (!this.hasMorePages || !this.nextPageUrl) return;

        this.observer = new IntersectionObserver(
            (entries) => this.handleIntersection(entries),
            {
                root: null,
                rootMargin: '500px',
                threshold: 0
            }
        );

        this.sentinel = document.createElement('div');
        this.sentinel.className = 'h-4';
        this.sentinel.id = 'scroll-sentinel';

        const perfisGrid = document.getElementById('perfis-grid');
        if (perfisGrid) {
            perfisGrid.parentNode.insertBefore(this.sentinel, perfisGrid.nextSibling);
            this.observer.observe(this.sentinel);
        }

        setTimeout(() => this.prefetch(), 500);
    },

    handleIntersection(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && !this.loading && this.hasMorePages) {
                this.loadMore();
            }
        });
    },

    async prefetch() {
        if (!this.nextPageUrl || this.loading || this.prefetchedData) return;

        try {
            const url = new URL(this.nextPageUrl, window.location.origin);
            url.searchParams.set('scroll', '1');

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                this.prefetchedData = await response.json();
            }
        } catch (e) {
        }
    },

    async loadMore() {
        if (this.loading || !this.nextPageUrl) return;

        this.loading = true;
        this.controller = new AbortController();

        const loadingEl = document.getElementById('loading');
        const perfisGrid = document.getElementById('perfis-grid');
        const noMoreEl = document.getElementById('no-more');
        const errorEl = document.getElementById('loading-error');

        if (loadingEl) loadingEl.classList.remove('hidden');
        if (errorEl) errorEl.classList.add('hidden');

        const timeoutId = setTimeout(() => {
            this.controller?.abort();
        }, 15000);

        try {
            let data;

            if (this.prefetchedData) {
                data = this.prefetchedData;
                this.prefetchedData = null;
            } else {
                const url = new URL(this.nextPageUrl, window.location.origin);
                url.searchParams.set('scroll', '1');

                const response = await fetch(url, {
                    signal: this.controller.signal,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                data = await response.json();
            }

            if (data.html && perfisGrid) {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = data.html;
                Array.from(wrapper.children).forEach((el, i) => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(20px)';
                    el.style.transition = `opacity 0.3s ease ${i * 0.05}s, transform 0.3s ease ${i * 0.05}s`;
                    perfisGrid.appendChild(el);
                    requestAnimationFrame(() => {
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    });
                });
            }

            this.nextPageUrl = data.next_page_url;
            this.hasMorePages = data.has_more_pages;
            this.retryCount = 0;

            if (!this.hasMorePages) {
                if (noMoreEl) noMoreEl.classList.remove('hidden');
                this.observer?.disconnect();
                this.sentinel?.remove();
            } else {
                setTimeout(() => this.prefetch(), 300);
            }

        } catch (error) {
            if (error.name === 'AbortError') return;

            console.error('Erro ao carregar perfis:', error);

            if (++this.retryCount < this.maxRetries) {
                setTimeout(() => {
                    this.loading = false;
                    this.loadMore();
                }, 1000 * this.retryCount);
                return;
            }

            this.showRetryButton();
        } finally {
            clearTimeout(timeoutId);
            this.loading = false;
            if (loadingEl) loadingEl.classList.add('hidden');
            this.controller = null;
        }
    },

    showRetryButton() {
        let errorEl = document.getElementById('loading-error');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.id = 'loading-error';
            errorEl.className = 'text-center py-4';
            this.sentinel?.parentNode?.insertBefore(errorEl, this.sentinel);
        }

        errorEl.innerHTML = `
            <p class="text-zinc-500 text-sm mb-2">Erro ao carregar mais perfis</p>
            <button onclick="InfiniteScroll.retry()" class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 rounded-lg text-sm transition-colors cursor-pointer">
                Tentar novamente
            </button>
        `;
        errorEl.classList.remove('hidden');
    },

    retry() {
        this.retryCount = 0;
        const errorEl = document.getElementById('loading-error');
        if (errorEl) errorEl.classList.add('hidden');
        this.loadMore();
    }
};

document.addEventListener('DOMContentLoaded', () => InfiniteScroll.init());

// Toggle sidebar em mobile
const toggleSidebar = document.getElementById('toggle-sidebar');
const sidebar = document.getElementById('sidebar');

if (toggleSidebar && sidebar) {
    toggleSidebar.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        sidebar.classList.toggle('fixed');
        sidebar.classList.toggle('inset-0');
        sidebar.classList.toggle('z-50');
        sidebar.classList.toggle('bg-zinc-950');
        sidebar.classList.toggle('p-4');
    });
}
</script>
@endpush
