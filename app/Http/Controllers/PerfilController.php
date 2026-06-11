<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Profile\CommentRequest;
use App\Http\Requests\Profile\ReportRequest;
use App\Http\Requests\Profile\StoreProfileRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Profile;
use App\Models\ProfileComment;
use App\Models\ProfileFavorite;
use App\Models\ProfileReport;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {}

    /**
     * View a public profile.
     */
    public function ver(string $slug): View
    {
        $perfil = $this->profileService->getForPublicView($slug);
        $perfisSimilares = $this->profileService->getSimilarProfiles($perfil);

        $isFavorited = auth()->check()
            ? ProfileFavorite::where('user_id', auth()->id())
                ->where('profile_id', $perfil->id)
                ->exists()
            : false;

        return view('perfil.ver', compact('perfil', 'perfisSimilares', 'isFavorited'));
    }

    /**
     * Show the authenticated user's profile page.
     */
    public function meu(): View
    {
        $perfil = auth()->user()->profile;

        return view('perfil.meu', compact('perfil'));
    }

    /**
     * Update user account details.
     */
    public function atualizar(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $user->update($request->validated());

        return redirect()->route('perfil')
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Show the profile creation form.
     */
    public function criar(): View|RedirectResponse
    {
        $perfil = auth()->user()->profile;

        if ($perfil) {
            return redirect()->route('perfil.editar', $perfil->id);
        }

        return view('perfil.criar', ['perfil' => null]);
    }

    /**
     * Store a new profile.
     */
    public function salvar(StoreProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $safeData = $request->safe()->only([
            'name', 'age', 'city', 'state', 'description', 'services',
            'height', 'weight', 'hair_color', 'eye_color', 'ethnicity', 'body_type',
        ]);

        if ($user->profile) {
            $this->profileService->update(
                profile: $user->profile,
                data: $safeData,
                images: $request->file('gallery', []),
                videoFiles: $request->file('video_files', []),
                removeImageIds: $request->filled('remove_images') ? (array) $request->input('remove_images') : [],
                mainImageId: $request->filled('main_image_id') ? (int) $request->input('main_image_id') : null,
                newMainImageIndex: $request->filled('new_main_image_index') ? (int) $request->input('new_main_image_index') : null,
            );

            return redirect()->route('perfil.editar', $user->profile->id)
                ->with('success', 'Perfil atualizado com sucesso!');
        }

        $profile = $this->profileService->create(
            user: $user,
            data: $safeData,
            images: $request->file('gallery', []),
            videoFiles: $request->file('video_files', []),
        );

        return redirect()->route('perfil.editar', $profile->id)
            ->with('success', 'Perfil criado com sucesso!');
    }

    /**
     * Show the edit form for a profile.
     */
    public function editar(int $id): View
    {
        $perfil = auth()->user()->profile()->findOrFail($id);

        return view('perfil.criar', compact('perfil'));
    }

    /**
     * Post a comment on a profile.
     */
    public function comentar(CommentRequest $request, string $slug): RedirectResponse
    {
        $perfil = $this->resolveProfile($slug);

        ProfileComment::create([
            'profile_id' => $perfil->id,
            'user_id'    => auth()->id(),
            'comment'    => $request->input('comment'),
            'rating'     => $request->input('rating'),
        ]);

        return redirect()->route('perfil.ver', $slug)
            ->with('success', 'Comentário adicionado com sucesso!');
    }

    /**
     * Report a profile.
     */
    public function denunciar(ReportRequest $request, string $slug): RedirectResponse
    {
        $perfil = $this->resolveProfile($slug);

        ProfileReport::create([
            'profile_id'  => $perfil->id,
            'user_id'     => auth()->id(),
            'reason'      => $request->input('reason'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('perfil.ver', $slug)
            ->with('success', 'Denúncia enviada com sucesso!');
    }

    /**
     * Resolve a profile by slug or ID.
     */
    private function resolveProfile(string $slug): Profile
    {
        return Profile::active()
            ->whereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [Str::lower($slug)])
            ->orWhere('id', is_numeric($slug) ? (int) $slug : 0)
            ->firstOrFail();
    }
}
