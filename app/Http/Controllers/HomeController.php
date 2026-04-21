<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $cidadesObj = City::where('active', true)
            ->orderBy('featured', 'desc')
            ->orderBy('order')
            ->limit(6)
            ->get();

        // Obter contagens de perfis por cidade em uma única query
        $cityNames = $cidadesObj->pluck('name');
        $profileCounts = Profile::whereIn('city', $cityNames)
            ->select('city', DB::raw('COUNT(*) as count'))
            ->groupBy('city')
            ->pluck('count', 'city');

        $cidades = $cidadesObj->map(function ($city) use ($profileCounts) {
            return [
                'name'     => $city->name,
                'state'    => $city->state,
                'count'    => $profileCounts->get($city->name, 0),
                'slug'     => $city->slug,
                'image'    => $city->image,
                'featured' => $city->featured,
            ];
        });

        return view('home', compact('cidades', 'cidadesObj'));
    }
}
