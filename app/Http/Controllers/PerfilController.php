<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileComment;
use App\Models\ProfileReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function ver(int $id): View
    {
        $perfil = Profile::with([
            'images',
            'videos',
            'user',
            'physicalAttributes',
            'services',
            'availability',
            'pricing',
            'comments.user',
        ])->where('active', true)->findOrFail($id);

        $perfil->increment('views');

        // Buscar perfis similares da mesma cidade
        $perfisSimilares = Profile::with(['images'])
            ->where('active', true)
            ->where('id', '!=', $perfil->id)
            ->where('city', $perfil->city)
            ->where('state', $perfil->state)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('perfil.ver', compact('perfil', 'perfisSimilares'));
    }

    public function meu(): View
    {
        $perfil = auth()->user()->profile;

        return view('perfil.meu', compact('perfil'));
    }

    public function atualizar(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio'   => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($validated);

        return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function criar(): View|RedirectResponse
    {
        $perfil = auth()->user()->profile;

        if ($perfil) {
            return redirect()->route('perfil.editar', $perfil->id);
        }

        return view('perfil.criar', ['perfil' => null]);
    }

    public function salvar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'age'         => ['required', 'integer', 'min:18', 'max:100'],
            'city'        => ['required', 'string', 'max:255'],
            'state'       => ['required', 'string', 'max:2'],
            'description' => ['nullable', 'string', 'max:2000'],
            'gallery'     => ['nullable', 'array', 'max:10'],
            'gallery.*'   => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'video_url'   => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)/'],
        ]);

        $user = auth()->user();

        if ($user->profile) {
            $user->profile->update([
                'name'        => $validated['name'],
                'age'         => $validated['age'],
                'city'        => $validated['city'],
                'state'       => strtoupper($validated['state']),
                'description' => $validated['description'] ?? null,
            ]);

            $this->handleMediaUploads($user->profile, $request);

            return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');
        }

        $profile = $user->profile()->create([
            'name'        => $validated['name'],
            'age'         => $validated['age'],
            'city'        => $validated['city'],
            'state'       => strtoupper($validated['state']),
            'description' => $validated['description'] ?? null,
            'active'      => true,
            'verified'    => false,
            'rating'      => 0,
            'views'       => 0,
        ]);

        $this->handleMediaUploads($profile, $request);

        return redirect()->route('perfil')->with('success', 'Perfil criado com sucesso!');
    }

    private function handleMediaUploads(Profile $profile, Request $request): void
    {
        // Remover imagens marcadas
        if ($request->has('remove_images')) {
            $profile->images()->whereIn('id', $request->input('remove_images'))->delete();
        }

        // Upload novas imagens
        $newImages = [];
        if ($request->hasFile('gallery')) {
            $currentOrder = $profile->images()->max('order') ?? 0;
            
            foreach ($request->file('gallery') as $index => $file) {
                $path = $file->store('profiles/images', 'public');
                
                $image = $profile->images()->create([
                    'url'     => $path,
                    'is_main' => false,
                    'order'   => $currentOrder + $index + 1,
                ]);
                
                $newImages[] = $image;
            }
        }

        // Definir imagem principal
        // Prioridade: new_main_image_index > main_image_id > primeira nova imagem > primeira imagem existente
        $mainImageSet = false;

        // 1. Verificar se selecionou uma nova imagem como principal
        if ($request->filled('new_main_image_index')) {
            $mainIndex = (int) $request->input('new_main_image_index');
            
            if (isset($newImages[$mainIndex])) {
                // Remover is_main de todas as imagens
                $profile->images()->update(['is_main' => false]);
                
                // Definir nova imagem como principal
                $newImages[$mainIndex]->update(['is_main' => true, 'order' => 0]);
                $mainImageSet = true;
            }
        }

        // 2. Se não, verificar se selecionou uma imagem existente como principal
        if (!$mainImageSet && $request->filled('main_image_id')) {
            $mainImageId = $request->input('main_image_id');
            
            // Verificar se a imagem ainda existe (não foi removida)
            if ($profile->images()->where('id', $mainImageId)->exists()) {
                // Remover is_main de todas as imagens
                $profile->images()->update(['is_main' => false]);
                
                // Definir como principal
                $profile->images()->where('id', $mainImageId)->update(['is_main' => true, 'order' => 0]);
                $mainImageSet = true;
            }
        }

        // 3. Se ainda não definiu principal e tem novas imagens, definir a primeira como principal
        if (!$mainImageSet && !empty($newImages)) {
            // Remover is_main de todas as imagens
            $profile->images()->update(['is_main' => false]);
            
            // Definir primeira nova imagem como principal
            $newImages[0]->update(['is_main' => true, 'order' => 0]);
            $mainImageSet = true;
        }

        // 4. Se ainda não definiu principal e não tem imagens existentes com principal, definir a primeira existente
        if (!$mainImageSet && !$profile->images()->where('is_main', true)->exists()) {
            $firstImage = $profile->images()->orderBy('order')->first();
            
            if ($firstImage) {
                $firstImage->update(['is_main' => true, 'order' => 0]);
            }
        }

        // Processar URL do YouTube
        if ($request->filled('video_url')) {
            $videoId = $this->extractYouTubeId($request->input('video_url'));
            
            if ($videoId) {
                // Remover vídeo anterior se existir
                $profile->videos()->delete();
                
                $profile->videos()->create([
                    'url'      => $request->input('video_url'),
                    'video_id' => $videoId,
                    'platform' => 'youtube',
                    'is_main'  => true,
                    'order'    => 0,
                ]);
            }
        }
    }

    private function extractYouTubeId(string $url): ?string
    {
        $pattern = '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/';
        
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    public function editar(int $id): View
    {
        $perfil = auth()->user()->profile()->findOrFail($id);

        return view('perfil.criar', compact('perfil'));
    }

    public function comentar(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:1000'],
            'rating'  => ['nullable', 'decimal:0,2', 'min:0', 'max:5'],
        ]);

        $perfil = Profile::where('active', true)->findOrFail($id);

        ProfileComment::create([
            'profile_id' => $perfil->id,
            'user_id'    => auth()->id(),
            'comment'    => $validated['comment'],
            'rating'     => $validated['rating'] ?? null,
        ]);

        return redirect()->route('perfil.ver', $id)->with('success', 'Comentário adicionado com sucesso!');
    }

    public function denunciar(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'reason'      => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $perfil = Profile::where('active', true)->findOrFail($id);

        ProfileReport::create([
            'profile_id'  => $perfil->id,
            'user_id'     => auth()->id(),
            'reason'      => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status'      => 'pendente',
        ]);

        return redirect()->route('perfil.ver', $id)->with('success', 'Denúncia enviada com sucesso!');
    }
}
