@extends('admin.layout')

@section('title', 'Nova Cidade')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.cities') }}"
       class="inline-flex items-center gap-2 text-zinc-400 hover:text-white text-sm transition-colors cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        Voltar
    </a>
</div>

<h2 class="text-white font-black uppercase tracking-wider text-2xl mb-6">Nova Cidade</h2>

<form action="{{ route('admin.cities.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
    @csrf

    @if($errors->any())
        <x-alerts.alert type="error" :message="$errors->first()" />
    @endif

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 space-y-6">
        <x-forms.input name="name" label="Nome da cidade" placeholder="Ex: Curitiba" required />

        <div>
            <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">Estado (UF)</label>
            <select name="state" required
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-primary uppercase">
                <option value="">Selecione</option>
                <option value="AC">AC - Acre</option>
                <option value="AL">AL - Alagoas</option>
                <option value="AP">AP - Amapá</option>
                <option value="AM">AM - Amazonas</option>
                <option value="BA">BA - Bahia</option>
                <option value="CE">CE - Ceará</option>
                <option value="DF">DF - Distrito Federal</option>
                <option value="ES">ES - Espírito Santo</option>
                <option value="GO">GO - Goiás</option>
                <option value="MA">MA - Maranhão</option>
                <option value="MT">MT - Mato Grosso</option>
                <option value="MS">MS - Mato Grosso do Sul</option>
                <option value="MG">MG - Minas Gerais</option>
                <option value="PA">PA - Pará</option>
                <option value="PB">PB - Paraíba</option>
                <option value="PR">PR - Paraná</option>
                <option value="PE">PE - Pernambuco</option>
                <option value="PI">PI - Piauí</option>
                <option value="RJ">RJ - Rio de Janeiro</option>
                <option value="RN">RN - Rio Grande do Norte</option>
                <option value="RS">RS - Rio Grande do Sul</option>
                <option value="RO">RO - Rondônia</option>
                <option value="RR">RR - Roraima</option>
                <option value="SC">SC - Santa Catarina</option>
                <option value="SP">SP - São Paulo</option>
                <option value="SE">SE - Sergipe</option>
                <option value="TO">TO - Tocantins</option>
            </select>
            @error('state')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">Imagem</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/jpg"
                   class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-primary">
            @error('image')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <x-forms.input name="order" type="number" label="Ordem" placeholder="0" />

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="featured" value="1"
                   class="h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-primary">
            <span class="text-sm text-zinc-300">Destaque (aparece primeiro)</span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="active" value="1" checked
                   class="h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-primary">
            <span class="text-sm text-zinc-300">Ativo</span>
        </label>

        <button type="submit"
                class="w-full py-2.5 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg transition-all cursor-pointer">
            Criar Cidade
        </button>
    </div>
</form>

@endsection
