<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestaqueController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $perfis = Profile::with(['images', 'user', 'services', 'physicalAttributes'])
            ->where('verified', true)
            ->where('active', true)
            ->orderBy('rating', 'desc')
            ->paginate(16);

        if ($request->wantsJson() || $request->has('scroll')) {
            return response()->json([
                'html' => view('components.perfil-card', compact('perfis'))->render(),
                'next_page_url' => $perfis->nextPageUrl(),
                'has_more_pages' => $perfis->hasMorePages(),
            ]);
        }

        return view('destaques', compact('perfis'));
    }
}
