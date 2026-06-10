<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Services\CityService;
use Illuminate\View\View;

final class LayoutComposer
{
    public function __construct(
        private readonly CityService $cityService,
    ) {}

    /**
     * Bind data to the layout view.
     */
    public function compose(View $view): void
    {
        $cidades = $this->cityService->getFeaturedCities();

        $view->with('_cidades', $cidades);
    }
}
