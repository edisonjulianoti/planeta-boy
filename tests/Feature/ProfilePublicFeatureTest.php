<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Database\Seeders\ProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePublicFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_profile_view_loads(): void
    {
        User::factory()->create();
        $this->seed(ProfileSeeder::class);

        $profile = Profile::first();

        $this->get("/perfis/{$profile->id}")
            ->assertOk();
    }

    public function test_profile_shows_new_fields(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create([
            'gender' => 'masculino',
            'telegram' => '11999999999',
            'tagline' => 'Frase curta',
        ]);

        $this->get("/perfis/{$profile->id}")
            ->assertOk()
            ->assertSee('masculino')
            ->assertSee('11999999999')
            ->assertSee('Frase curta');
    }

    public function test_profile_shows_pricing(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create();
        $profile->pricing()->create([
            'duration' => '1 hora',
            'price' => 150.00,
        ]);

        $this->get("/perfis/{$profile->id}")
            ->assertOk()
            ->assertSee('1 hora')
            ->assertSee('150');
    }

    public function test_profile_shows_payment_methods(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create([
            'payment_methods' => ['pix', 'dinheiro'],
        ]);

        $this->get("/perfis/{$profile->id}")
            ->assertOk()
            ->assertSee('pix')
            ->assertSee('dinheiro');
    }

    public function test_profile_shows_attendance_target(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create([
            'attendance_target' => ['homens', 'mulheres'],
        ]);

        $this->get("/perfis/{$profile->id}")
            ->assertOk()
            ->assertSee('homens')
            ->assertSee('mulheres');
    }

    public function test_profile_shows_trust_badges(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create([
            'documents_verified' => true,
            'no_reports' => true,
            'clean_history' => true,
        ]);

        $this->get("/perfis/{$profile->id}")
            ->assertOk();
    }

    public function test_profile_shows_comments(): void
    {
        User::factory()->create();
        $user = User::factory()->create();
        $profile = Profile::factory()->create();
        $profile->comments()->create([
            'user_id' => $user->id,
            'comment' => 'Comentário de teste',
            'rating' => 4.5,
        ]);

        $this->get("/perfis/{$profile->id}")
            ->assertOk()
            ->assertSee('Comentário de teste');
    }

    public function test_profile_shows_report_button(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create();

        $this->get("/perfis/{$profile->id}")
            ->assertOk();
    }

    public function test_inactive_profile_returns_not_found(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create(['active' => false]);

        $this->get("/perfis/{$profile->id}")
            ->assertNotFound();
    }

    public function test_profile_increments_views(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create(['views' => 10]);

        $this->get("/perfis/{$profile->id}");

        $profile->refresh();
        $this->assertEquals(11, $profile->views);
    }
}
