<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Modelagem',
                'slug' => 'modelagem',
                'category' => 'Acompanhamento',
                'active' => true,
            ],
            [
                'name' => 'Massagem Erótica',
                'slug' => 'massagem-erotica',
                'category' => 'Massagem',
                'active' => true,
            ],
            [
                'name' => 'Massagem Corporal',
                'slug' => 'massagem-corporal',
                'category' => 'Massagem',
                'active' => true,
            ],
            [
                'name' => 'Disponível para Vídeos',
                'slug' => 'disponivel-para-videos',
                'category' => 'Conteúdo',
                'active' => true,
            ],
            [
                'name' => 'Experiência de Namorado',
                'slug' => 'experiencia-de-namorado',
                'category' => 'Acompanhamento',
                'active' => true,
            ],
            [
                'name' => 'Acompanhamento Social',
                'slug' => 'acompanhamento-social',
                'category' => 'Acompanhamento',
                'active' => true,
            ],
            [
                'name' => 'Overnight',
                'slug' => 'overnight',
                'category' => 'Acompanhamento',
                'active' => true,
            ],
            [
                'name' => 'Fetiche',
                'slug' => 'fetiche',
                'category' => 'Especial',
                'active' => true,
            ],
            [
                'name' => 'BDSM',
                'slug' => 'bdsm',
                'category' => 'Especial',
                'active' => true,
            ],
            [
                'name' => 'Dominação',
                'slug' => 'dominacao',
                'category' => 'Especial',
                'active' => true,
            ],
            [
                'name' => 'Boquete',
                'slug' => 'boquete',
                'category' => 'Sexo Oral',
                'active' => true,
            ],
            [
                'name' => 'Sexo Anal',
                'slug' => 'sexo-anal',
                'category' => 'Sexo',
                'active' => true,
            ],
            [
                'name' => 'Beijo',
                'slug' => 'beijo',
                'category' => 'Intimidade',
                'active' => true,
            ],
            [
                'name' => 'Massagem Tântrica',
                'slug' => 'massagem-tantrica',
                'category' => 'Massagem',
                'active' => true,
            ],
            [
                'name' => 'Role Play',
                'slug' => 'role-play',
                'category' => 'Especial',
                'active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service);
        }
    }
}
