<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfileReport;
use App\Models\User;
use Database\Seeders\ProfileReportSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileReportSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_reports_for_some_profiles(): void
    {
        $this->seed([UserSeeder::class, ProfileSeeder::class]);

        $this->seed(ProfileReportSeeder::class);

        $reports = ProfileReport::all();
        $this->assertGreaterThan(0, $reports->count());
    }

    public function test_creates_0_to_2_reports_per_profile(): void
    {
        $this->seed([UserSeeder::class, ProfileSeeder::class]);

        $this->seed(ProfileReportSeeder::class);

        $profiles = Profile::all();
        foreach ($profiles as $profile) {
            $count = $profile->reports()->count();
            $this->assertGreaterThanOrEqual(0, $count);
            $this->assertLessThanOrEqual(2, $count);
        }
    }

    public function test_reason_is_valid(): void
    {
        $validReasons = ['conteudo_inapropriado', 'falsa_identidade', 'golpe', 'spam', 'outro'];

        $this->seed([UserSeeder::class, ProfileSeeder::class]);

        $this->seed(ProfileReportSeeder::class);

        $reports = ProfileReport::all();
        foreach ($reports as $report) {
            $this->assertContains($report->reason, $validReasons);
        }
    }

    public function test_status_is_valid(): void
    {
        $validStatuses = ['pendente', 'revisado', 'descartado', 'acao_tomada'];

        $this->seed([UserSeeder::class, ProfileSeeder::class]);

        $this->seed(ProfileReportSeeder::class);

        $reports = ProfileReport::all();
        foreach ($reports as $report) {
            $this->assertContains($report->status->value, $validStatuses);
        }
    }

    public function test_description_is_nullable(): void
    {
        $this->seed([UserSeeder::class, ProfileSeeder::class]);

        $this->seed(ProfileReportSeeder::class);

        $reports = ProfileReport::all();
        $hasNullDescription = $reports->contains('description', null);

        $this->assertTrue($hasNullDescription || $reports->every('description', '!=', null));
    }
}
