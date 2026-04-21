<?php

namespace App\Console\Commands;

use App\Models\ProfileImage;
use Illuminate\Console\Command;

class FixImageUrls extends Command
{
    protected $signature = 'app:fix-image-urls';
    protected $description = 'Remover /storage/ duplicado das URLs das imagens';

    public function handle()
    {
        $this->info('Corrigindo URLs das imagens...');

        $images = ProfileImage::where('url', 'like', '/storage/%')->get();
        $count = 0;

        foreach ($images as $image) {
            $image->url = str_replace('/storage/', '', $image->url);
            $image->save();
            $count++;
        }

        $this->info("Atualizado: {$count} imagens");
        $this->info('Concluído!');
    }
}
