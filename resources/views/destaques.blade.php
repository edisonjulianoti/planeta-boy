@extends('layouts.app')

@section('title', 'Destaques 💎 - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950 py-20">

    <x-ui.container size="lg">
        {{-- Header com Badge de Destaque --}}
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl p-2.5 shadow-lg shadow-yellow-500/20">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-heading-1 font-heading text-white flex items-center gap-3">
                            Destaques
                            <span class="bg-yellow-500/20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full border border-yellow-500/30 uppercase tracking-wider">
                                💎 Premium
                            </span>
                        </h1>
                        <p class="text-[14px] text-zinc-500 mt-1">Perfis verificados com as melhores avaliações</p>
                    </div>
                </div>
            </div>
            <p class="text-[16px] text-zinc-400">{{ $perfis->total() }} perfis em destaque</p>
        </div>

        {{-- Grid de Perfis (mesmo grid do explorar) --}}
        <div>
            @if($perfis->isEmpty())
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <svg class="w-16 h-16 text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <p class="text-zinc-400 text-lg font-bold">Nenhum perfil em destaque no momento</p>
                    <p class="text-zinc-600 text-sm mt-1">Volte mais tarde para conferir</p>
                </div>
            @else
                <div id="perfis-grid" class="grid grid-cols-[repeat(auto-fit,minmax(232px,1fr))] gap-6">
                    @include('components.perfil-card', ['perfis' => $perfis])
                </div>

                <div id="loading" class="hidden justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-500"></div>
                </div>

                <div id="no-more" class="hidden text-center py-8 text-zinc-500">
                    Não há mais perfis em destaque
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
</script>
@endpush
