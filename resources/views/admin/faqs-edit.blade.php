@extends('admin.layout')

@section('title', 'Editar FAQ')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.faqs') }}"
       class="inline-flex items-center gap-2 text-zinc-400 hover:text-white text-sm transition-colors cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        Voltar
    </a>
</div>

<h2 class="text-white font-black uppercase tracking-wider text-2xl mb-6">Editar FAQ</h2>

<form action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="max-w-2xl">
    @csrf
    @method('PUT')

    @if($errors->any())
        <x-alerts.alert type="error" :message="$errors->first()" />
    @endif

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 space-y-6">
        <div>
            <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">Pergunta</label>
            <input type="text" name="pergunta" value="{{ old('pergunta', $faq->pergunta) }}"
                   class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-primary"
                   placeholder="Digite a pergunta..." required>
            @error('pergunta')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">Resposta</label>
            <textarea name="resposta" rows="4"
                      class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-primary resize-none"
                      placeholder="Digite a resposta..." required>{{ old('resposta', $faq->resposta) }}</textarea>
            @error('resposta')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">Categoria</label>
            <select name="categoria"
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-primary cursor-pointer">
                <option value="geral" {{ old('categoria', $faq->categoria) === 'geral' ? 'selected' : '' }}>Geral</option>
                <option value="planos" {{ old('categoria', $faq->categoria) === 'planos' ? 'selected' : '' }}>Planos</option>
            </select>
            @error('categoria')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="ativo" value="1" {{ $faq->ativo ? 'checked' : '' }}
                   class="h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-primary">
            <span class="text-sm text-zinc-300">Ativo</span>
        </label>

        <button type="submit"
                class="w-full py-2.5 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg transition-all cursor-pointer">
            Atualizar FAQ
        </button>
    </div>
</form>

@endsection
