<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CitySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $cidades = [
            [
                'name' => 'São Paulo',
                'state' => 'SP',
                'slug' => 'sao-paulo',
                'image_url' => 'https://images.unsplash.com/photo-1483729558449-99ef09a8c325?w=800&h=600&fit=crop',
                'order' => 1,
                'featured' => true,
            ],
            [
                'name' => 'Curitiba',
                'state' => 'PR',
                'slug' => 'curitiba',
                'image_url' => 'https://images.unsplash.com/photo-1596394723269-b2cbca4e6313?w=800&h=600&fit=crop',
                'order' => 2,
                'featured' => true,
            ],
            [
                'name' => 'Florianópolis',
                'state' => 'SC',
                'slug' => 'florianopolis',
                'image_url' => 'https://images.unsplash.com/photo-1594798287188-6c94855f5d27?w=800&h=600&fit=crop',
                'order' => 3,
                'featured' => true,
            ],
            [
                'name' => 'Joinville',
                'state' => 'SC',
                'slug' => 'joinville',
                'image_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&h=600&fit=crop',
                'order' => 4,
                'featured' => true,
            ],
            [
                'name' => 'Ponta Grossa',
                'state' => 'PR',
                'slug' => 'ponta-grossa',
                'image_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&h=600&fit=crop',
                'order' => 5,
                'featured' => true,
            ],
            [
                'name' => 'Guarulhos',
                'state' => 'SP',
                'slug' => 'guarulhos',
                'image_url' => 'https://images.unsplash.com/photo-1513407030348-c983a97b98d8?w=800&h=600&fit=crop',
                'order' => 6,
                'featured' => true,
            ],
            [
                'name' => 'Londrina',
                'state' => 'PR',
                'slug' => 'londrina',
                'image_url' => 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=800&h=600&fit=crop',
                'order' => 7,
                'featured' => false,
            ],
            [
                'name' => 'Maringá',
                'state' => 'PR',
                'slug' => 'maringa',
                'image_url' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=800&h=600&fit=crop',
                'order' => 8,
                'featured' => false,
            ],
            [
                'name' => 'Balneário Camboriú',
                'state' => 'SC',
                'slug' => 'balneario-camboriu',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'order' => 9,
                'featured' => false,
            ],
            [
                'name' => 'São José dos Campos',
                'state' => 'SP',
                'slug' => 'sao-jose-dos-campos',
                'image_url' => 'https://images.unsplash.com/photo-1496568816309-51d7c20e3b21?w=800&h=600&fit=crop',
                'order' => 10,
                'featured' => false,
            ],
        ];

        Storage::disk('public')->makeDirectory('cities');

        foreach ($cidades as $cidade) {
            $imageName = $cidade['slug'] . '.jpg';
            $imagePath = 'cities/' . $imageName;

            $response = Http::timeout(30)->get($cidade['image_url']);

            $imageSalva = null;

            if ($response->successful()) {
                Storage::disk('public')->put($imagePath, $response->body());
                $imageSalva = $imagePath;
                $this->command->info("Imagem baixada: {$imageName}");
            } else {
                $this->command->warn("Falha ao baixar imagem de {$cidade['name']}");
            }

            City::updateOrCreate(
                ['slug' => $cidade['slug']],
                [
                    'name' => $cidade['name'],
                    'state' => $cidade['state'],
                    'image' => $imageSalva,
                    'active' => true,
                    'order' => $cidade['order'],
                    'featured' => $cidade['featured'] ?? false,
                ]
            );
        }

        $this->command->info('Cidades criadas com sucesso!');
    }
}
