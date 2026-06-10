<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileFavorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoritoController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $perfis = Profile::with(['images', 'user', 'services', 'physicalAttributes'])
            ->whereHas('favoritedByUsers', fn($q) => $q->where('user_id', $user->id))
            ->paginate(16);

        $favoritedIds = $perfis->pluck('id')->toArray();

        return view('favoritos.index', compact('perfis', 'favoritedIds'));
    }

    public function toggle(Request $request, int $profileId): JsonResponse|RedirectResponse
    {
        $user = auth()->user();
        $profile = Profile::active()->findOrFail($profileId);

        $favorite = ProfileFavorite::where('user_id', $user->id)
            ->where('profile_id', $profile->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $favoritou = false;
        } else {
            ProfileFavorite::create([
                'user_id'    => $user->id,
                'profile_id' => $profile->id,
            ]);
            $favoritou = true;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'favoritou' => $favoritou,
                'message'   => $favoritou
                    ? 'Perfil adicionado aos favoritos!'
                    : 'Perfil removido dos favoritos.',
            ]);
        }

        return redirect()->back()
            ->with('success', $favoritou
                ? 'Perfil adicionado aos favoritos!'
                : 'Perfil removido dos favoritos.');
    }
}
