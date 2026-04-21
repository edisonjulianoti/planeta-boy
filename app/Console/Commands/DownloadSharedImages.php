<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadSharedImages extends Command
{
    protected $signature = 'app:download-shared-images';
    protected $description = 'Baixar 6 imagens compartilhadas (1 principal + 5 galeria)';

    public function handle()
    {
        $this->info('Baixando imagens compartilhadas...');

        // Criar diretório
        Storage::disk('public')->makeDirectory('profiles/shared');

        // Imagem principal
        $mainUrl = 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&h=1200&fit=crop&crop=face';
        $this->downloadAndSave($mainUrl, 'profiles/shared/main.jpg');

        // 5 imagens da galeria
        $galleryUrls = [
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=1200&fit=crop',
            'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&h=1200&fit=crop',
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&h=1200&fit=crop',
            'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=800&h=1200&fit=crop',
            'https://images.unsplash.com/photo-1504257432389-52343af06ae3?w=800&h=1200&fit=crop',
        ];

        foreach ($galleryUrls as $index => $url) {
            $this->downloadAndSave($url, "profiles/shared/gallery_" . ($index + 1) . ".jpg");
        }

        $this->info('Imagens compartilhadas baixadas com sucesso!');
    }

    private function downloadAndSave(string $url, string $path)
    {
        try {
            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                Storage::disk('public')->put($path, $response->body());
                $this->info("  ✓ Imagem salva: {$path}");
            } else {
                $this->warn("  ✗ Falha ao baixar: {$url}");
            }
        } catch (\Exception $e) {
            $this->error("  ✗ Erro ao baixar {$url}: {$e->getMessage()}");
        }
    }
}
