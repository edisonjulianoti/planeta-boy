<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CityService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly CityService $cityService,
    ) {}

    public function index(): View
    {
        $cidades = $this->cityService->getFeaturedWithCounts();
        $cidadesObj = $this->cityService->getFeaturedCities();

        return view('home', compact('cidades', 'cidadesObj'));
    }
}
