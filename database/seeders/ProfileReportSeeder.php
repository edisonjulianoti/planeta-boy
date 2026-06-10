<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\ProfileReport;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileReportSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::all();
        $users = User::where('is_admin', false)->get();
        $reasons = ['conteudo_inapropriado', 'falsa_identidade', 'golpe', 'spam', 'outro'];

        foreach ($profiles as $profile) {
            if (fake()->boolean(20)) {
                $numReports = rand(0, 2);

                for ($i = 0; $i < $numReports; $i++) {
                    if ($users->isNotEmpty()) {
                        ProfileReport::create([
                            'profile_id' => $profile->id,
                            'user_id' => $users->random()->id,
                            'reason' => fake()->randomElement($reasons),
                            'description' => fake()->optional(0.5)->text(),
                            'status' => fake()->randomElement(['pendente', 'revisado', 'descartado', 'acao_tomada']),
                        ]);
                    }
                }
            }
        }

        $this->command->info('Denúncias criadas com sucesso!');
    }
}
