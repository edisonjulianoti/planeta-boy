<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Profile;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    /**
     * Generate the XML sitemap with all public URLs.
     */
    public function index(): Response
    {
        $pages = [
            // Páginas principais
            ['loc' => URL::to('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => URL::to('/explorar'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => URL::to('/destaques'), 'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => URL::to('/planos'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => URL::to('/faq'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => URL::to('/contato'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            // Páginas institucionais
            ['loc' => URL::to('/sobre'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => URL::to('/termos'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => URL::to('/privacidade'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => URL::to('/seguranca'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => URL::to('/regras'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => URL::to('/como-anunciar'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        // Perfis ativos
        $profiles = Profile::query()
            ->where('active', true)
            ->select('id', 'updated_at')
            ->get();

        foreach ($profiles as $profile) {
            $pages[] = [
                'loc'        => URL::to('/perfis/' . $profile->id),
                'priority'   => '0.6',
                'changefreq' => 'weekly',
                'lastmod'    => $profile->updated_at?->toAtomString(),
            ];
        }

        // Cidades ativas
        $cities = City::query()
            ->where('active', true)
            ->select('slug', 'updated_at')
            ->get();

        foreach ($cities as $city) {
            $pages[] = [
                'loc'        => URL::to('/cidade/' . $city->slug),
                'priority'   => '0.6',
                'changefreq' => 'weekly',
                'lastmod'    => $city->updated_at?->toAtomString(),
            ];
        }

        return response()
            ->view('sitemap', ['pages' => $pages])
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
