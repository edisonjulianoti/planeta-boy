<?php

namespace App\Console\Commands;

use App\Models\ProfileImage;
use Illuminate\Console\Command;

class UpdateToSharedImages extends Command
{
    protected $signature = 'app:update-to-shared-images';
    protected $description = 'Atualizar URLs para usar imagens compartilhadas';

    public function handle()
    {
        $this->info('Atualizando URLs para imagens compartilhadas...');

        $count = 0;

        // Atualizar imagens principais
        ProfileImage::where('is_main', true)->get()->each(function ($image) use (&$count) {
            $image->url = 'profiles/shared/main.jpg';
            $image->save();
            $count++;
        });

        // Atualizar imagens da galeria
        ProfileImage::where('is_main', false)->get()->each(function ($image) use (&$count) {
            // Usar a ordem para determinar qual imagem da galeria usar (1-5)
            $galleryNum = ($image->order % 5) + 1;
            $image->url = "profiles/shared/gallery_{$galleryNum}.jpg";
            $image->save();
            $count++;
        });

        $this->info("Atualizado: {$count} imagens");
        $this->info('Concluído!');
    }
}
