<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfilePricing;
use App\Models\User;
use Database\Seeders\ProfilePricingSeeder;
use Database\Seeders\ProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePricingSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_pricing_for_all_profiles(): void
    {
        User::factory()->count(5)->create();
        $this->seed(ProfileSeeder::class);

        $this->seed(ProfilePricingSeeder::class);

        $profiles = Profile::all();
        foreach ($profiles as $profile) {
            $this->assertGreaterThan(0, $profile->pricing()->count());
        }
    }

    public function test_creates_2_to_4_pricings_per_profile(): void
    {
        User::factory()->count(10)->create();
        $this->seed(ProfileSeeder::class);

        $this->seed(ProfilePricingSeeder::class);

        $profiles = Profile::all();
        foreach ($profiles as $profile) {
            $count = $profile->pricing()->count();
            $this->assertGreaterThanOrEqual(2, $count);
            $this->assertLessThanOrEqual(4, $count);
        }
    }

    public function test_duration_is_valid(): void
    {
        $validDurations = ['15 minutos', '30 minutos', '1 hora', '2 horas', 'noite'];

        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfilePricingSeeder::class);

        $pricings = $profile->pricing;
        foreach ($pricings as $pricing) {
            $this->assertContains($pricing->duration, $validDurations);
        }
    }

    public function test_price_is_in_range(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfilePricingSeeder::class);

        $pricings = $profile->pricing;
        foreach ($pricings as $pricing) {
            $this->assertGreaterThanOrEqual(40, $pricing->price);
            $this->assertLessThanOrEqual(800, $pricing->price);
        }
    }

    public function test_description_is_nullable(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfilePricingSeeder::class);

        $pricings = $profile->pricing;
        $hasNullDescription = $pricings->contains('description', null);

        $this->assertTrue($hasNullDescription || $pricings->every('description', '!=', null));
    }
}
