<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\ProfileReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class ProfileReportFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_report(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/denunciar", [
                'reason' => 'conteudo_inapropriado',
                'description' => 'Descrição da denúncia',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('profile_reports', [
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'reason' => 'conteudo_inapropriado',
        ]);
    }

    public function test_guest_cannot_report(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->post("/perfis/{$profile->id}/denunciar", [
            'reason' => 'conteudo_inapropriado',
            'description' => 'Descrição da denúncia',
        ])
            ->assertRedirect(route('login'));
    }

    public function test_report_requires_reason_field(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/denunciar", [
                'reason' => '',
                'description' => 'Descrição da denúncia',
            ])
            ->assertSessionHasErrors('reason');
    }

    public function test_description_is_optional(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/denunciar", [
                'reason' => 'conteudo_inapropriado',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('profile_reports', [
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'reason' => 'conteudo_inapropriado',
        ]);
    }

    public function test_report_redirects_to_profile(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/denunciar", [
                'reason' => 'conteudo_inapropriado',
                'description' => 'Descrição da denúncia',
            ])
            ->assertRedirect();
    }

    public function test_report_stores_in_database(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/denunciar", [
                'reason' => 'conteudo_inapropriado',
                'description' => 'Descrição da denúncia',
            ]);

        $report = ProfileReport::where('profile_id', $profile->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($report);
        $this->assertEquals('conteudo_inapropriado', $report->reason);
        $this->assertEquals('pendente', $report->status);
    }

    public function test_report_status_defaults_to_pendente(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/denunciar", [
                'reason' => 'conteudo_inapropriado',
            ]);

        $report = ProfileReport::where('profile_id', $profile->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertEquals('pendente', $report->status);
    }
}
