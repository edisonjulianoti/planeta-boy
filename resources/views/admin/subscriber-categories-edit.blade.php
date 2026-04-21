@extends('admin.layout')

@section('title', 'Editar Categoria de Assinante')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.subscriber-categories') }}"
       class="inline-flex items-center gap-2 text-zinc-400 hover:text-white text-sm transition-colors cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        Voltar
    </a>
</div>

<h2 class="text-white font-black uppercase tracking-wider text-2xl mb-6">Editar Categoria de Assinante</h2>

<form action="{{ route('admin.subscriber-categories.update', $category) }}" method="POST" class="max-w-2xl">
    @csrf
    @method('PUT')

    @if($errors->any())
        <x-alerts.alert type="error" :message="$errors->first()" />
    @endif

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 space-y-6">
        <x-forms.input name="name" label="Nome da categoria" :value="$category->name" required />

        <div>
            <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">Descrição</label>
            <textarea name="description" rows="4"
                      class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-primary resize-none"
                      placeholder="Descrição da categoria...">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="active" value="1" {{ $category->active ? 'checked' : '' }}
                   class="h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-primary">
            <span class="text-sm text-zinc-300">Ativo</span>
        </label>

        <button type="submit"
                class="w-full py-2.5 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg transition-all cursor-pointer">
            Atualizar Categoria
        </button>
    </div>
</form>

@endsection
