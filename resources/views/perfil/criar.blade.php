@extends('layouts.app')

@section('title', $perfil ? 'Editar Perfil - PLANETA BOYS' : 'Criar Perfil - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950">
    <div class="container mx-auto px-4 py-12 max-w-4xl">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('perfil') }}" class="text-zinc-500 hover:text-white transition-colors cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-3xl font-black text-white uppercase italic">
                {{ $perfil ? 'Editar' : 'Criar' }} <span class="text-primary">Perfil</span>
            </h1>
        </div>

        {{-- Mensagem de sucesso --}}
        @if(session('success'))
            <x-alerts.alert type="success" :message="session('success')" />
        @endif

        {{-- Erros de validação --}}
        @if($errors->any())
            <x-alerts.alert type="error" :message="$errors->first()" />
        @endif

        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <form action="{{ route('perfil.salvar') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <x-forms.input name="name" label="Nome do perfil" placeholder="Como você gostaria de ser chamado?" :value="old('name', $perfil->name ?? null)" required variant="dark" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-forms.input name="age" type="number" label="Idade" placeholder="Sua idade" :value="old('age', $perfil->age ?? null)" min="18" max="100" required variant="dark" />

                    <x-forms.input name="state" label="Estado (UF)" placeholder="PR" :value="old('state', $perfil->state ?? null)" maxlength="2" required class="uppercase" variant="dark" />
                </div>

                <div>
                    <label class="block text-zinc-500 text-xs uppercase tracking-wider mb-1.5">Cidade</label>
                    <select name="city" required
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        <option value="">Selecione uma cidade</option>
                        @php $cidades = \App\Models\City::where('active', true)->orderBy('order')->get(); @endphp
                        @foreach($cidades as $cidade)
                            <option value="{{ $cidade->name }}" {{ old('city', $perfil->city ?? null) === $cidade->name ? 'selected' : '' }}>
                                {{ $cidade->name }} - {{ $cidade->state }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-zinc-500 text-xs uppercase tracking-wider mb-1.5">Descrição</label>
                    <textarea name="description" rows="4" placeholder="Descreva um pouco sobre você..."
                        class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all resize-none">{{ old('description', $perfil->description ?? '') }}</textarea>
                </div>

                {{-- Imagens --}}
                <x-forms.image-upload 
                    name="gallery" 
                    label="Imagens do Perfil"
                    :existingImages="$perfil ? $perfil->images : null"
                    :mainImageId="$perfil ? $perfil->images()->where('is_main', true)->first()?->id : null"
                />
                <p class="text-zinc-600 text-xs mt-1">Selecione a imagem principal clicando no círculo no canto superior esquerdo</p>

                {{-- Vídeos --}}
                <div>
                    <label class="block text-zinc-500 text-xs uppercase tracking-wider mb-1.5">Vídeo do YouTube</label>
                    <x-forms.input name="video_url" type="url" label="URL do vídeo" placeholder="https://www.youtube.com/watch?v=..." :value="old('video_url', $perfil?->videos()?->where('platform', 'youtube')->first()?->url ?? null)" variant="dark" />
                    <p class="text-zinc-600 text-xs mt-1">Cole a URL do vídeo do YouTube (ex: https://www.youtube.com/watch?v=xxx ou https://youtu.be/xxx)</p>
                    @if($perfil && $perfil->videos()->where('platform', 'youtube')->exists())
                        <div class="mt-2">
                            <p class="text-zinc-500 text-xs mb-1">Vídeo atual:</p>
                            <p class="text-zinc-400 text-sm">{{ $perfil->videos()->where('platform', 'youtube')->first()->url }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('perfil') }}" class="px-6 py-2.5 text-zinc-400 hover:text-white text-sm font-medium rounded-lg transition-colors cursor-pointer">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-primary hover:brightness-110 text-primary-foreground text-sm font-bold rounded-lg transition-all uppercase tracking-wider cursor-pointer">
                        {{ $perfil ? 'Salvar Alterações' : 'Criar Perfil' }}
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
