<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\ProfileComment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileCommentSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::all();
        $users = User::where('is_admin', false)->get();
        $comments = [
            'Excelente experiência, recomendo!',
            'Muito profissional e atencioso.',
            'Momentos inesquecíveis.',
            'Perfeito em todos os sentidos.',
            'Superou todas as expectativas.',
            'Atendimento impecável.',
            'Vale cada centavo.',
            'Já voltei várias vezes.',
        ];

        foreach ($profiles as $profile) {
            $numComments = rand(0, 5);

            for ($i = 0; $i < $numComments; $i++) {
                if ($users->isNotEmpty()) {
                    ProfileComment::create([
                        'profile_id' => $profile->id,
                        'user_id' => $users->random()->id,
                        'comment' => fake()->randomElement($comments),
                        'rating' => fake()->randomFloat(1, 3, 5),
                    ]);
                }
            }
        }

        $this->command->info('Comentários criados com sucesso!');
    }
}
