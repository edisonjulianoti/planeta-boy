<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\ProfileImage;
use Illuminate\Database\Seeder;

class ProfileImageSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::all();

        foreach ($profiles as $profile) {
            // Criar imagem principal compartilhada
            ProfileImage::create([
                'profile_id' => $profile->id,
                'url' => 'profiles/shared/main.jpg',
                'order' => 0,
                'is_main' => true,
            ]);

            // Criar 5 imagens da galeria compartilhadas
            for ($i = 1; $i <= 5; $i++) {
                ProfileImage::create([
                    'profile_id' => $profile->id,
                    'url' => 'profiles/shared/gallery_' . $i . '.jpg',
                    'order' => $i,
                    'is_main' => false,
                ]);
            }
        }

        $this->command->info('Imagens de perfis criadas com sucesso!');
    }
}
