@extends('admin.layout')

@section('title', 'Editar Plano — ' . $plan->name)

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('admin.plans') }}" class="text-zinc-500 hover:text-white text-xs font-bold uppercase tracking-wider flex items-center gap-2 mb-6 transition-colors cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Voltar para Planos
    </a>

    <x-admin.card title="Editar: {{ $plan->name }}" padding="p-8">

        @if($errors->any())
            <x-alerts.alert type="error" :message="$errors->first()" />
        @endif

        <form action="{{ route('admin.plans.update', $plan) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <x-forms.input name="name" label="Nome" :value="old('name', $plan->name)" />

            <x-forms.input name="price" type="number" label="Preço (R$/mês)" :value="old('price', $plan->price)" step="0.01" min="0" />

            <x-forms.input name="description" label="Descrição" :value="old('description', $plan->description)" />

            {{-- Image upload --}}
            <div>
                <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">Imagem do Plano</label>

                @if($plan->image)
                <div class="mb-3" id="current-image-wrapper">
                    <img src="{{ asset('storage/' . $plan->image) }}" alt="{{ $plan->name }}"
                         class="w-24 h-24 object-cover rounded-lg border border-zinc-700">
                    <label class="inline-flex items-center gap-2 mt-2 text-zinc-500 hover:text-red-400 text-xs font-bold transition-colors cursor-pointer">
                        <input type="checkbox" name="remove_image" value="1" onchange="document.getElementById('current-image-wrapper')?.classList.toggle('opacity-40')">
                        Remover imagem atual
                    </label>
                </div>
                @endif

                <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                       class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-zinc-400 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary file:text-black hover:file:brightness-110 transition-all cursor-pointer"
                       onchange="previewImage(this)">

                <div id="image-preview" class="hidden mt-3">
                    <img id="image-preview-img" src="#" alt="Preview"
                         class="w-24 h-24 object-cover rounded-lg border border-zinc-700">
                </div>

                <p class="text-zinc-600 text-xs mt-1">Formatos: jpg, png, webp. Máx: 2MB</p>
            </div>

            <div>
                <label class="block text-zinc-400 text-xs uppercase tracking-widest font-bold mb-2">
                    Recursos <span class="text-zinc-600 normal-case font-normal">(um por linha)</span>
                </label>
                <textarea name="features" rows="6"
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary transition-colors resize-none">{{ old('features', is_array($plan->features) ? implode("\n", $plan->features) : '') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" id="active" value="1" {{ old('active', $plan->active) ? 'checked' : '' }}
                    class="w-4 h-4 accent-primary">
                <label for="active" class="text-zinc-300 text-sm font-bold">Plano ativo</label>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit"
                    class="px-6 py-3 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-xl text-sm transition-all cursor-pointer">
                    Salvar Alterações
                </button>
                <a href="{{ route('admin.plans') }}"
                    class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 text-white font-black uppercase tracking-wider rounded-xl text-sm transition-all cursor-pointer">
                    Cancelar
                </a>
            </div>
        </form>
    </x-admin.card>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const img = document.getElementById('image-preview-img');
    const currentWrapper = document.getElementById('current-image-wrapper');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
            if (currentWrapper) currentWrapper.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
