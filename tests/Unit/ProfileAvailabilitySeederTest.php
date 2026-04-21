<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfileAvailability;
use App\Models\User;
use Database\Seeders\ProfileAvailabilitySeeder;
use Database\Seeders\ProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileAvailabilitySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_availability_for_all_profiles(): void
    {
        User::factory()->count(5)->create();
        $this->seed(ProfileSeeder::class);
        $profileCount = Profile::count();

        $this->seed(ProfileAvailabilitySeeder::class);

        $this->assertEquals($profileCount, ProfileAvailability::count());
    }

    public function test_days_is_array(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfileAvailabilitySeeder::class);

        $availability = ProfileAvailability::where('profile_id', $profile->id)->first();

        $this->assertIsArray($availability->days);
    }

    public function test_days_contains_valid_days(): void
    {
        $validDays = ['segunda', 'terça', 'quarta', 'quinta', 'sexta', 'sábado', 'domingo'];

        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfileAvailabilitySeeder::class);

        $availability = ProfileAvailability::where('profile_id', $profile->id)->first();

        foreach ($availability->days as $day) {
            $this->assertContains($day, $validDays);
        }
    }

    public function test_start_time_is_time(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfileAvailabilitySeeder::class);

        $availability = ProfileAvailability::where('profile_id', $profile->id)->first();

        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $availability->start_time);
    }

    public function test_end_time_is_time(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfileAvailabilitySeeder::class);

        $availability = ProfileAvailability::where('profile_id', $profile->id)->first();

        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $availability->end_time);
    }
}
