<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $profile = auth()->user()->profile;

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Perfil não encontrado'
            ], 404);
        }

        $profile->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location_enabled' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Localização atualizada com sucesso'
        ]);
    }
}
