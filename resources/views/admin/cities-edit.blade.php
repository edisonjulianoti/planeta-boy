@extends('admin.layout')

@section('title', 'Editar Cidade')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.cities') }}"
       class="inline-flex items-center gap-2 text-zinc-400 hover:text-white text-sm transition-colors cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        Voltar
    </a>
</div>

<h2 class="text-white font-black uppercase tracking-wider text-2xl mb-6">Editar Cidade</h2>

<form action="{{ route('admin.cities.update', $city) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
    @csrf
    @method('PUT')

    @if($errors->any())
        <x-alerts.alert type="error" :message="$errors->first()" />
    @endif

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 space-y-6">
        <x-forms.input name="name" label="Nome da cidade" :value="$city->name" required />

        <div>
            <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">Estado (UF)</label>
            <select name="state" required
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-primary uppercase">
                <option value="">Selecione</option>
                <option value="AC" {{ $city->state === 'AC' ? 'selected' : '' }}>AC - Acre</option>
                <option value="AL" {{ $city->state === 'AL' ? 'selected' : '' }}>AL - Alagoas</option>
                <option value="AP" {{ $city->state === 'AP' ? 'selected' : '' }}>AP - Amapá</option>
                <option value="AM" {{ $city->state === 'AM' ? 'selected' : '' }}>AM - Amazonas</option>
                <option value="BA" {{ $city->state === 'BA' ? 'selected' : '' }}>BA - Bahia</option>
                <option value="CE" {{ $city->state === 'CE' ? 'selected' : '' }}>CE - Ceará</option>
                <option value="DF" {{ $city->state === 'DF' ? 'selected' : '' }}>DF - Distrito Federal</option>
                <option value="ES" {{ $city->state === 'ES' ? 'selected' : '' }}>ES - Espírito Santo</option>
                <option value="GO" {{ $city->state === 'GO' ? 'selected' : '' }}>GO - Goiás</option>
                <option value="MA" {{ $city->state === 'MA' ? 'selected' : '' }}>MA - Maranhão</option>
                <option value="MT" {{ $city->state === 'MT' ? 'selected' : '' }}>MT - Mato Grosso</option>
                <option value="MS" {{ $city->state === 'MS' ? 'selected' : '' }}>MS - Mato Grosso do Sul</option>
                <option value="MG" {{ $city->state === 'MG' ? 'selected' : '' }}>MG - Minas Gerais</option>
                <option value="PA" {{ $city->state === 'PA' ? 'selected' : '' }}>PA - Pará</option>
                <option value="PB" {{ $city->state === 'PB' ? 'selected' : '' }}>PB - Paraíba</option>
                <option value="PR" {{ $city->state === 'PR' ? 'selected' : '' }}>PR - Paraná</option>
                <option value="PE" {{ $city->state === 'PE' ? 'selected' : '' }}>PE - Pernambuco</option>
                <option value="PI" {{ $city->state === 'PI' ? 'selected' : '' }}>PI - Piauí</option>
                <option value="RJ" {{ $city->state === 'RJ' ? 'selected' : '' }}>RJ - Rio de Janeiro</option>
                <option value="RN" {{ $city->state === 'RN' ? 'selected' : '' }}>RN - Rio Grande do Norte</option>
                <option value="RS" {{ $city->state === 'RS' ? 'selected' : '' }}>RS - Rio Grande do Sul</option>
                <option value="RO" {{ $city->state === 'RO' ? 'selected' : '' }}>RO - Rondônia</option>
                <option value="RR" {{ $city->state === 'RR' ? 'selected' : '' }}>RR - Roraima</option>
                <option value="SC" {{ $city->state === 'SC' ? 'selected' : '' }}>SC - Santa Catarina</option>
                <option value="SP" {{ $city->state === 'SP' ? 'selected' : '' }}>SP - São Paulo</option>
                <option value="SE" {{ $city->state === 'SE' ? 'selected' : '' }}>SE - Sergipe</option>
                <option value="TO" {{ $city->state === 'TO' ? 'selected' : '' }}>TO - Tocantins</option>
            </select>
            @error('state')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">Imagem</label>
            @if($city->image)
                <img src="{{ asset('storage/' . $city->image) }}" alt="{{ $city->name }}" class="w-32 h-32 object-cover rounded-lg mb-2">
            @endif
            <input type="file" name="image" accept="image/jpeg,image/png,image/jpg"
                   class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-primary">
            @error('image')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <x-forms.input name="order" type="number" label="Ordem" :value="$city->order" />

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="featured" value="1" {{ $city->featured ?? false ? 'checked' : '' }}
                   class="h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-primary">
            <span class="text-sm text-zinc-300">Destaque (aparece primeiro)</span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="active" value="1" {{ $city->active ? 'checked' : '' }}
                   class="h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-primary">
            <span class="text-sm text-zinc-300">Ativo</span>
        </label>

        <button type="submit"
                class="w-full py-2.5 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg transition-all cursor-pointer">
            Atualizar Cidade
        </button>
    </div>
</form>

@endsection
