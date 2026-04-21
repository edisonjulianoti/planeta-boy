<?php

namespace Database\Seeders;

use App\Models\SubscriberCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriberCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Básico',
                'slug' => 'basico',
                'description' => 'Categoria básica com restrições de serviços',
                'active' => true,
            ],
            [
                'name' => 'Intermediário',
                'slug' => 'intermediario',
                'description' => 'Categoria intermediária com menos restrições',
                'active' => true,
            ],
            [
                'name' => 'Avançado',
                'slug' => 'avancado',
                'description' => 'Categoria avançada com acesso a todos os serviços',
                'active' => true,
            ],
        ];

        foreach ($categories as $category) {
            SubscriberCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
