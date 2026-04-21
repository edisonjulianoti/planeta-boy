<?php

namespace App\Console\Commands;

use App\Models\Profile;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

#[Signature('app:download-profile-images')]
#[Description('Baixar imagens do Unsplash para perfis existentes')]
class DownloadProfileImages extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando download de imagens para perfis...');

        $profiles = Profile::all();
        $totalProfiles = $profiles->count();

        if ($totalProfiles === 0) {
            $this->warn('Nenhum perfil encontrado. Execute os seeders primeiro.');
            return;
        }

        $this->info("Encontrados {$totalProfiles} perfis.");

        foreach ($profiles as $index => $profile) {
            $current = $index + 1;
            $this->info("Processando perfil {$current}/{$totalProfiles}: {$profile->name}");

            // Baixar imagem principal
            $this->downloadMainImage($profile);

            // Baixar 4 imagens da galeria
            for ($i = 1; $i <= 4; $i++) {
                $this->downloadGalleryImage($profile, $i);
            }

            $this->info("Imagens baixadas para perfil {$profile->id}");
        }

        $this->info('Download de imagens concluído com sucesso!');
    }

    private function downloadMainImage(Profile $profile)
    {
        // URL do Unsplash Source para imagem de homem (retrato)
        $url = 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&h=1200&fit=crop&crop=face';

        // Adicionar parâmetro aleatório para evitar cache
        $url .= '&sig=' . uniqid();

        $this->downloadAndSave($url, "profiles/main/profile_{$profile->id}_main.jpg");
    }

    private function downloadGalleryImage(Profile $profile, $index)
    {
        // URLs diferentes para variação
        $urls = [
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=1200&fit=crop',
            'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&h=1200&fit=crop',
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&h=1200&fit=crop',
            'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=800&h=1200&fit=crop',
        ];

        $url = $urls[$index - 1] . '&sig=' . uniqid();

        $this->downloadAndSave($url, "profiles/gallery/profile_{$profile->id}_gallery_{$index}.jpg");
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
