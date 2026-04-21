<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfileReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_profile_report(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $report = ProfileReport::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'reason' => 'conteudo_inapropriado',
            'description' => 'Descrição da denúncia',
            'status' => 'pendente',
        ]);

        $this->assertDatabaseHas('profile_reports', [
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'reason' => 'conteudo_inapropriado',
        ]);
    }

    public function test_belongs_to_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $report = ProfileReport::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'reason' => 'conteudo_inapropriado',
        ]);

        $this->assertInstanceOf(Profile::class, $report->profile);
        $this->assertEquals($profile->id, $report->profile->id);
    }

    public function test_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $report = ProfileReport::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'reason' => 'conteudo_inapropriado',
        ]);

        $this->assertInstanceOf(User::class, $report->user);
        $this->assertEquals($user->id, $report->user->id);
    }

    public function test_status_defaults_to_pendente(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $report = ProfileReport::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'reason' => 'conteudo_inapropriado',
            'status' => 'pendente',
        ]);

        $this->assertEquals('pendente', $report->status);
    }

    public function test_status_is_valid_enum(): void
    {
        $validStatuses = ['pendente', 'analise', 'resolvido', 'rejeitado'];

        foreach ($validStatuses as $status) {
            $user = User::factory()->create();
            $profile = Profile::factory()->create(['user_id' => $user->id]);

            $report = ProfileReport::create([
                'profile_id' => $profile->id,
                'user_id' => $user->id,
                'reason' => 'conteudo_inapropriado',
                'status' => $status,
            ]);

            $this->assertEquals($status, $report->status);
        }
    }

    public function test_reason_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        ProfileReport::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'reason' => null,
        ]);
    }

    public function test_description_is_nullable(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $report = ProfileReport::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'reason' => 'conteudo_inapropriado',
            'description' => null,
        ]);

        $this->assertNull($report->description);
    }
}
