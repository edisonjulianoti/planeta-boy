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
                <input type="hidden" name="remove_images" value="">

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

                {{-- Características Físicas --}}
                <div class="border-t border-zinc-800 pt-6">
                    <h3 class="text-white font-black uppercase tracking-wider text-sm mb-4 flex items-center gap-2">
                        <div class="w-1 h-4 bg-primary rounded-full"></div>Características
                    </h3>
                    <p class="text-zinc-600 text-xs mb-4">Informe suas características físicas (opcional)</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-forms.input name="height" type="number" label="Altura (cm)" placeholder="Ex: 180"
                            :value="old('height', $perfil?->physicalAttributes?->height ?? null)" min="100" max="250" variant="dark" />

                        <x-forms.input name="weight" type="number" label="Peso (kg)" placeholder="Ex: 75"
                            :value="old('weight', $perfil?->physicalAttributes?->weight ?? null)" min="30" max="300" variant="dark" />

                        <x-forms.input name="hair_color" label="Cor do Cabelo" placeholder="Ex: castanho, loiro, preto"
                            :value="old('hair_color', $perfil?->physicalAttributes?->hair_color ?? null)" variant="dark" />

                        <x-forms.input name="eye_color" label="Cor dos Olhos" placeholder="Ex: verdes, castanhos, azuis"
                            :value="old('eye_color', $perfil?->physicalAttributes?->eye_color ?? null)" variant="dark" />

                        <x-forms.input name="ethnicity" label="Etnia" placeholder="Ex: branco, pardo, negro, asiático"
                            :value="old('ethnicity', $perfil?->physicalAttributes?->ethnicity ?? null)" variant="dark" />

                        <div>
                            <label class="block text-zinc-500 text-xs uppercase tracking-wider mb-1.5">Tipo Físico</label>
                            <select name="body_type"
                                class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                <option value="">Selecione</option>
                                @php
                                    $bodyTypes = ['magro', 'atlético', 'musculoso', 'sarado', 'forte', 'gordo', 'tanquinho'];
                                    $selectedBodyType = old('body_type', $perfil?->physicalAttributes?->body_type ?? null);
                                @endphp
                                @foreach($bodyTypes as $type)
                                    <option value="{{ $type }}" {{ $selectedBodyType === $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Serviços --}}
                <div>
                    <label class="block text-zinc-500 text-xs uppercase tracking-wider mb-1.5">Serviços Prestados</label>
                    <p class="text-zinc-600 text-xs mb-3">Selecione os serviços que você oferece</p>
                    @php
                        $todosServicos = \App\Models\Service::where('active', true)->orderBy('name')->get();
                        $agrupados = $todosServicos->groupBy('category');
                        $selectedServices = old('services', $perfil?->services->pluck('id')->toArray() ?? []);
                    @endphp
                    @forelse($agrupados as $categoria => $servicos)
                        <div class="mb-4">
                            <h4 class="text-zinc-400 text-sm font-semibold mb-2 uppercase tracking-wider">{{ $categoria }}</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($servicos as $servico)
                                    <label class="group cursor-pointer">
                                        <input type="checkbox" name="services[]" value="{{ $servico->id }}"
                                               class="peer sr-only"
                                               {{ in_array($servico->id, $selectedServices) ? 'checked' : '' }}>
                                        <span class="inline-block px-4 py-2 rounded-full text-sm transition-all
                                                     peer-checked:bg-primary peer-checked:text-black peer-checked:border-primary
                                                     bg-transparent border border-zinc-700 text-zinc-300
                                                     group-hover:border-zinc-500">
                                            {{ $servico->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-zinc-600 text-sm">Nenhum serviço disponível no momento.</p>
                    @endforelse
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
                    <label class="block text-zinc-500 text-xs uppercase tracking-wider mb-1.5">Vídeos</label>
                    <input type="file" name="video_files[]" accept="video/mp4,video/webm" multiple
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-4 py-2.5 text-white text-sm 
                                  file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 
                                  file:text-sm file:font-semibold file:bg-primary file:text-primary-foreground
                                  hover:file:brightness-110 active:file:bg-black cursor-pointer" />
                    <p class="text-zinc-600 text-xs mt-1">Formatos: MP4, WebM (máx. 50MB cada, até 5 vídeos)</p>
                    @if($perfil && $perfil->videos->isNotEmpty())
                        <div class="mt-2 space-y-2">
                            <p class="text-zinc-500 text-xs">Vídeos atuais ({{ $perfil->videos->count() }}):</p>
                            @foreach($perfil->videos as $video)
                                <div class="p-2 bg-zinc-900/50 rounded-lg border border-zinc-800">
                                    <video controls class="w-full max-h-32 rounded-lg">
                                        <source src="{{ asset('storage/' . $video->path) }}" type="video/mp4">
                                    </video>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('perfil') }}" class="px-6 py-2.5 text-zinc-400 hover:text-white text-sm font-medium rounded-lg transition-colors cursor-pointer">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-primary hover:brightness-110 active:bg-black text-primary-foreground text-sm font-bold rounded-lg transition-all uppercase tracking-wider cursor-pointer">
                        {{ $perfil ? 'Salvar Alterações' : 'Criar Perfil' }}
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
