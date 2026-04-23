@extends('admin.layout')

@section('title', 'Editar Perfil')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.profiles') }}" class="text-zinc-400 hover:text-white text-sm font-bold uppercase tracking-wider flex items-center gap-2 cursor-pointer">
            ← Voltar para perfis
        </a>
    </div>

    <x-admin.card title="Editar Perfil" padding="p-8">

        <form action="{{ route('admin.profiles.update', $profile) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Informações do usuário --}}
            <div class="bg-zinc-800/50 rounded-xl p-4 border border-zinc-700">
                <p class="text-zinc-400 text-xs uppercase tracking-wider font-bold mb-2">Usuário</p>
                <p class="text-white font-bold">{{ $profile->user->name }}</p>
                <p class="text-zinc-500 text-sm">{{ $profile->user->email }}</p>
            </div>

            {{-- Nome --}}
            <x-forms.input name="name" label="Nome do perfil" :value="$profile->name" required />

            {{-- Idade --}}
            <x-forms.input name="age" type="number" label="Idade" :value="$profile->age" min="18" max="100" required />

            {{-- Cidade --}}
            <x-forms.input name="city" label="Cidade" :value="$profile->city" required />

            {{-- Estado --}}
            <x-forms.input name="state" label="Estado (UF)" :value="$profile->state" maxlength="2" required class="uppercase" />

            {{-- Descrição --}}
            <div>
                <label class="block text-zinc-400 text-xs uppercase tracking-wider font-bold mb-2">Descrição</label>
                <textarea name="description" rows="4"
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary resize-none"
                    placeholder="Descrição do perfil...">{{ $profile->description ?? '' }}</textarea>
            </div>

            {{-- Status --}}
            <div class="flex gap-6">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="verified" id="verified" {{ $profile->verified ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-zinc-600 bg-zinc-800 text-primary focus:ring-primary focus:ring-offset-zinc-900">
                    <label for="verified" class="text-white font-bold">Verificado</label>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="active" id="active" {{ $profile->active ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-zinc-600 bg-zinc-800 text-primary focus:ring-primary focus:ring-offset-zinc-900">
                    <label for="active" class="text-white font-bold">Ativo</label>
                </div>
            </div>

            {{-- Botões --}}
            <div class="flex gap-4 pt-4">
                <a href="{{ route('admin.profiles') }}"
                    class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 text-white font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-primary hover:bg-primary/80 text-black font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
