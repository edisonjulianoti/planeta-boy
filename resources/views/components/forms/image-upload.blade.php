@props([
    'name' => 'gallery',
    'label' => 'Imagens',
    'multiple' => true,
    'existingImages' => null,
    'mainImageId' => null,
])

<div class="space-y-4">
    <label class="block text-zinc-500 text-xs uppercase tracking-wider mb-1.5">{{ $label }}</label>
    
    {{-- Área de Upload --}}
    <div id="upload-area-{{ $name }}" class="border-2 border-dashed border-zinc-700 rounded-xl p-8 text-center transition-all hover:border-primary hover:bg-zinc-900/50 cursor-pointer" onclick="document.getElementById('input-{{ $name }}').click()">
        <input 
            type="file" 
            id="input-{{ $name }}" 
            name="{{ $name }}[]" 
            accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
            {{ $multiple ? 'multiple' : '' }}
            class="hidden"
            onchange="previewNewImages(this, '{{ $name }}')"
        >
        
        <div class="mt-2">
            <svg class="w-12 h-12 text-zinc-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-zinc-400 text-sm">Selecione imagens</p>
        </div>
    </div>
    
    {{-- Preview das novas imagens sendo enviadas --}}
    <div id="new-preview-{{ $name }}" class="grid grid-cols-4 gap-3 hidden"></div>
    
    {{-- Preview das imagens existentes --}}
    <div id="preview-{{ $name }}" class="grid grid-cols-4 gap-3">
        @if($existingImages && $existingImages->isNotEmpty())
            @foreach($existingImages as $image)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $image->url) }}" alt="Imagem" class="w-full aspect-square object-cover rounded-lg">
                    
                    <div class="absolute top-2 left-2">
                        <input 
                            type="radio" 
                            name="main_image_id" 
                            value="{{ $image->id }}"
                            {{ $mainImageId == $image->id ? 'checked' : '' }}
                            class="w-5 h-5 rounded-full border-2 border-white cursor-pointer"
                            onchange="clearNewMainImage('{{ $name }}')"
                        >
                    </div>
                    
                    @if($image->is_main)
                        <div class="absolute top-2 right-2 bg-primary text-black text-xs font-bold px-2 py-1 rounded-full">
                            Principal
                        </div>
                    @endif
                    
                    <button 
                        type="button"
                        onclick="removeExistingImage(this, {{ $image->id }})"
                        class="absolute bottom-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    
                    <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                </div>
            @endforeach
        @endif
    </div>
    
    <p class="text-zinc-600 text-xs">Formatos: JPEG, PNG, GIF, WebP (máx. 5MB cada). Selecione a imagem principal clicando no círculo.</p>
</div>

<script>
(function() {
    const componentName = '{{ $name }}';
    
    window.removeExistingImage = function(button, imageId) {
        const container = button.parentElement;
        const hiddenInput = container.querySelector('input[type="hidden"]');
        hiddenInput.name = 'remove_images[]';
        container.remove();
    };

    window.previewNewImages = function(input, name) {
        const container = document.getElementById('new-preview-' + name);
        container.innerHTML = '';
        container.classList.remove('hidden');
        
        const files = input.files;
        
        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Nova imagem" class="w-full aspect-square object-cover rounded-lg">
                        
                        <div class="absolute top-2 left-2">
                            <input 
                                type="radio" 
                                name="new_main_image_index" 
                                value="${i}"
                                ${i === 0 ? 'checked' : ''}
                                class="w-5 h-5 rounded-full border-2 border-white cursor-pointer"
                                onchange="clearExistingMainImage('${name}')"
                            >
                        </div>
                        
                        ${i === 0 ? `
                            <div class="absolute top-2 right-2 bg-primary text-black text-xs font-bold px-2 py-1 rounded-full">
                                Principal
                            </div>
                        ` : ''}
                        
                        <button 
                            type="button"
                            onclick="removeNewImage(this, ${i}, '${name}')"
                            class="absolute bottom-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    `;
                    container.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        } else {
            container.classList.add('hidden');
        }
    };

    window.removeNewImage = function(button, index, name) {
        const container = button.parentElement;
        container.remove();
        
        const newContainer = document.getElementById('new-preview-' + name);
        if (newContainer.children.length === 0) {
            newContainer.classList.add('hidden');
        }
        
        // Reindexar os radio buttons
        const radios = newContainer.querySelectorAll('input[name="new_main_image_index"]');
        radios.forEach((radio, idx) => {
            radio.value = idx;
            if (idx === 0 && !radio.checked) {
                radio.checked = true;
                updateMainBadge(newContainer, 0);
            }
        });
    };

    window.clearExistingMainImage = function(name) {
        const existingContainer = document.getElementById('preview-' + name);
        if (!existingContainer) return;
        
        const radios = existingContainer.querySelectorAll('input[name="main_image_id"]');
        radios.forEach(radio => radio.checked = false);
        
        const badges = existingContainer.querySelectorAll('.bg-primary');
        badges.forEach(badge => badge.remove());
    };

    window.clearNewMainImage = function(name) {
        const newContainer = document.getElementById('new-preview-' + name);
        if (!newContainer) return;
        
        const radios = newContainer.querySelectorAll('input[name="new_main_image_index"]');
        radios.forEach(radio => radio.checked = false);
        
        const badges = newContainer.querySelectorAll('.bg-primary');
        badges.forEach(badge => badge.remove());
    };

    window.updateMainBadge = function(container, index) {
        const badges = container.querySelectorAll('.bg-primary');
        badges.forEach(badge => badge.remove());
        
        const items = container.children;
        if (items[index]) {
            const badge = document.createElement('div');
            badge.className = 'absolute top-2 right-2 bg-primary text-black text-xs font-bold px-2 py-1 rounded-full';
            badge.textContent = 'Principal';
            items[index].appendChild(badge);
        }
    };

    // Adicionar listener para atualizar badge quando radio button muda
    document.addEventListener('change', function(e) {
        if (e.target.name === 'new_main_image_index') {
            const container = e.target.closest('#new-preview-' + componentName);
            if (container) {
                const index = parseInt(e.target.value);
                updateMainBadge(container, index);
            }
        }
    });
})();
</script>
