<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\City;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as SupportCollection;

final class CityService
{
    /**
     * Get featured cities for the search dropdown.
     *
     * @return Collection<int, City>
     */
    public function getFeaturedCities(int $limit = 6): Collection
    {
        return City::active()
            ->orderBy('featured', 'desc')
            ->orderBy('order')
            ->limit($limit)
            ->get();
    }

    /**
     * Get featured cities with profile counts.
     *
     * @return Collection<int, array>
     */
    public function getFeaturedWithCounts(int $limit = 6): SupportCollection
    {
        $cities = $this->getFeaturedCities($limit);

        $cityNames = $cities->pluck('name');

        $profileCounts = Profile::active()
            ->whereIn('city', $cityNames)
            ->select('city', DB::raw('COUNT(*) as count'))
            ->groupBy('city')
            ->pluck('count', 'city');

        return $cities->map(function (City $city) use ($profileCounts) {
            return [
                'name'     => $city->name,
                'state'    => $city->state,
                'count'    => $profileCounts->get($city->name, 0),
                'slug'     => $city->slug,
                'image'    => $city->image,
                'featured' => $city->featured,
            ];
        });
    }
}
