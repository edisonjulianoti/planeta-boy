<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicoSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // Main
            ['name' => 'Boquete', 'slug' => 'boquete', 'category' => 'main'],
            ['name' => 'Sexo anal', 'slug' => 'anal', 'category' => 'main'],
            ['name' => 'Sexo vaginal', 'slug' => 'vaginal', 'category' => 'main'],
            ['name' => 'Dupla penetração', 'slug' => 'dupla-penetracao', 'category' => 'main'],
            ['name' => 'Beijo na boca', 'slug' => 'beijo', 'category' => 'main'],
            ['name' => 'Mamada nos seios', 'slug' => 'mamada', 'category' => 'main'],
            ['name' => 'Chupada completa', 'slug' => 'chupada', 'category' => 'main'],
            ['name' => 'Strip-tease', 'slug' => 'strip', 'category' => 'main'],
            ['name' => 'Massagem erótica', 'slug' => 'massagem', 'category' => 'main'],
            // Fetishes
            ['name' => 'Fetiche de pés', 'slug' => 'pes', 'category' => 'fetishes'],
            ['name' => 'Dominatrix', 'slug' => 'dominatrix', 'category' => 'fetishes'],
            ['name' => 'Submissa', 'slug' => 'submissa', 'category' => 'fetishes'],
            ['name' => 'Trio', 'slug' => 'trio', 'category' => 'fetishes'],
            ['name' => 'Lesbo show', 'slug' => 'lesbo', 'category' => 'fetishes'],
            ['name' => 'Spanking', 'slug' => 'spanking', 'category' => 'fetishes'],
            ['name' => 'Bondage', 'slug' => 'bondage', 'category' => 'fetishes'],
            // Specials
            ['name' => 'Chuva dourada', 'slug' => 'chuva-dourada', 'category' => 'specials'],
            ['name' => 'Chuva marrom', 'slug' => 'chuva-marrom', 'category' => 'specials'],
            // Duration
            ['name' => '1 hora', 'slug' => '1h', 'category' => 'duration'],
            ['name' => '2 horas', 'slug' => '2h', 'category' => 'duration'],
            ['name' => 'Noite', 'slug' => 'night', 'category' => 'duration'],
            ['name' => 'Dia inteiro', 'slug' => 'day', 'category' => 'duration'],
            ['name' => 'Fim de semana', 'slug' => 'weekend', 'category' => 'duration'],
            ['name' => 'Viagem', 'slug' => 'travel', 'category' => 'duration'],
            ['name' => 'Jantar', 'slug' => 'dinner', 'category' => 'duration'],
            ['name' => 'Evento', 'slug' => 'event', 'category' => 'duration'],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service + ['active' => true]);
        }
    }
}
