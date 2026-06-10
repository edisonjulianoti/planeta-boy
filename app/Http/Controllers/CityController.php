<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Profile;
use Illuminate\View\View;

class CityController extends Controller
{
    /** Ordenação por prioridade de plano: premium > gold > silver > free */
    private const PLAN_PRIORITY_ORDER = "CASE 
        WHEN user_plan = 'premium' THEN 1 
        WHEN user_plan = 'gold' THEN 2 
        WHEN user_plan = 'silver' THEN 3 
        ELSE 4 
    END";

    public function show(string $slug): View
    {
        $city = City::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $perfis = Profile::with(['images', 'user', 'services', 'physicalAttributes'])
            ->where('active', true)
            ->where('city', $city->name)
            ->leftJoin('users', 'profiles.user_id', '=', 'users.id')
            ->select('profiles.*', 'users.plan as user_plan')
            ->orderByRaw(self::PLAN_PRIORITY_ORDER)
            ->latest('profiles.created_at')
            ->paginate(16);

        $totalCount = Profile::where('active', true)
            ->where('city', $city->name)
            ->count();

        return view('cidade', compact('city', 'perfis', 'totalCount'));
    }
}
