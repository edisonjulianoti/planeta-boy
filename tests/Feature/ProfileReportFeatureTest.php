<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\ProfileReport;
use App\Models\User;
use App\Enums\ReportStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileReportFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_report(): void
    {

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $slug = strtolower(str_replace(' ', '-', $profile->name));

        $this->actingAs($user)
            ->post("/perfis/{$slug}/denunciar", [
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

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $slug = strtolower(str_replace(' ', '-', $profile->name));

        $this->post("/perfis/{$slug}/denunciar", [
            'reason' => 'conteudo_inapropriado',
            'description' => 'Descrição da denúncia',
        ])
            ->assertRedirect(route('login'));
    }

    public function test_report_requires_reason_field(): void
    {

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $slug = strtolower(str_replace(' ', '-', $profile->name));

        $this->actingAs($user)
            ->post("/perfis/{$slug}/denunciar", [
                'reason' => '',
                'description' => 'Descrição da denúncia',
            ])
            ->assertSessionHasErrors('reason');
    }

    public function test_description_is_optional(): void
    {

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $slug = strtolower(str_replace(' ', '-', $profile->name));

        $this->actingAs($user)
            ->post("/perfis/{$slug}/denunciar", [
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

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $slug = strtolower(str_replace(' ', '-', $profile->name));

        $this->actingAs($user)
            ->post("/perfis/{$slug}/denunciar", [
                'reason' => 'conteudo_inapropriado',
                'description' => 'Descrição da denúncia',
            ])
            ->assertRedirect();
    }

    public function test_report_stores_in_database(): void
    {

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $slug = strtolower(str_replace(' ', '-', $profile->name));

        $this->actingAs($user)
            ->post("/perfis/{$slug}/denunciar", [
                'reason' => 'conteudo_inapropriado',
                'description' => 'Descrição da denúncia',
            ]);

        $report = ProfileReport::where('profile_id', $profile->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($report);
        $this->assertEquals('conteudo_inapropriado', $report->reason);
        $this->assertEquals(ReportStatus::Pending, $report->status);
    }

    public function test_report_status_defaults_to_pendente(): void
    {

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $slug = strtolower(str_replace(' ', '-', $profile->name));

        $this->actingAs($user)
            ->post("/perfis/{$slug}/denunciar", [
                'reason' => 'conteudo_inapropriado',
            ]);

        $report = ProfileReport::where('profile_id', $profile->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertEquals(ReportStatus::Pending, $report->status);
    }
}
