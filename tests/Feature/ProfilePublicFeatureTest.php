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

    // ─── Helpers ────────────────────────────────────────

    private function makeProfile(array $overrides = []): Profile
    {
        User::factory()->create();
        return Profile::factory()->create($overrides);
    }

    private function slugify(Profile $profile): string
    {
        return strtolower(str_replace(' ', '-', $profile->name));
    }

    // ─── Tests ──────────────────────────────────────────

    public function test_public_profile_view_loads(): void
    {
        User::factory()->create();
        $this->seed(ProfileSeeder::class);

        $profile = Profile::first();
        $this->get("/perfis/{$this->slugify($profile)}")->assertOk();
    }

    public function test_profile_shows_new_fields(): void
    {
        $profile = $this->makeProfile([
            'gender' => 'masculino',
            'telegram' => '11999999999',
            'tagline' => 'Frase curta',
        ]);

        $response = $this->get("/perfis/{$this->slugify($profile)}");
        $response->assertOk();
        $response->assertSee($profile->name)
            ->assertSee((string) $profile->age);
    }

    public function test_profile_shows_pricing(): void
    {
        $profile = $this->makeProfile();
        $profile->pricing()->create([
            'duration' => '1 hora',
            'price' => 150.00,
        ]);

        $response = $this->get("/perfis/{$this->slugify($profile)}");
        $response->assertOk();
        $response->assertSee($profile->name);
    }

    public function test_profile_shows_payment_methods(): void
    {
        $profile = $this->makeProfile([
            'payment_methods' => ['pix', 'dinheiro'],
        ]);

        $response = $this->get("/perfis/{$this->slugify($profile)}");
        $response->assertOk();
        $response->assertSee($profile->name);
    }

    public function test_profile_shows_attendance_target(): void
    {
        $profile = $this->makeProfile([
            'attendance_target' => ['homens', 'mulheres'],
        ]);

        $response = $this->get("/perfis/{$this->slugify($profile)}");
        $response->assertOk();
        $response->assertSee($profile->name);
    }

    public function test_profile_shows_trust_badges(): void
    {
        $profile = $this->makeProfile([
            'documents_verified' => true,
            'no_reports' => true,
            'clean_history' => true,
        ]);

        $this->get("/perfis/{$this->slugify($profile)}")
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

        $slug = strtolower(str_replace(' ', '-', $profile->name));

        $this->get("/perfis/{$slug}")
            ->assertOk()
            ->assertSee('Comentário de teste');
    }

    public function test_profile_shows_report_button(): void
    {
        $this->makeProfile();
        $profile = Profile::first();

        $this->get("/perfis/{$this->slugify($profile)}")
            ->assertOk();
    }

    public function test_inactive_profile_returns_not_found(): void
    {
        $profile = $this->makeProfile(['active' => false]);

        $this->get("/perfis/{$this->slugify($profile)}")
            ->assertNotFound();
    }

    public function test_profile_increments_views(): void
    {
        $profile = $this->makeProfile(['views' => 10]);

        $this->get("/perfis/{$this->slugify($profile)}");

        $profile->refresh();
        $this->assertEquals(11, $profile->views);
    }
}
