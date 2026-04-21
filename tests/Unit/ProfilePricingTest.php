<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfilePricing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_profile_pricing(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $pricing = ProfilePricing::create([
            'profile_id' => $profile->id,
            'duration' => '1 hora',
            'price' => 150.00,
            'description' => 'Descrição do preço',
        ]);

        $this->assertDatabaseHas('profile_pricing', [
            'profile_id' => $profile->id,
            'duration' => '1 hora',
        ]);
    }

    public function test_belongs_to_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $pricing = ProfilePricing::create([
            'profile_id' => $profile->id,
            'duration' => '1 hora',
            'price' => 150.00,
        ]);

        $this->assertInstanceOf(Profile::class, $pricing->profile);
        $this->assertEquals($profile->id, $pricing->profile->id);
    }

    public function test_price_is_decimal(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $pricing = ProfilePricing::create([
            'profile_id' => $profile->id,
            'duration' => '1 hora',
            'price' => 150.50,
        ]);

        $this->assertEquals(150.50, $pricing->price);
    }

    public function test_duration_is_string(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $pricing = ProfilePricing::create([
            'profile_id' => $profile->id,
            'duration' => '1 hora',
            'price' => 150.00,
        ]);

        $this->assertIsString($pricing->duration);
    }

    public function test_description_is_nullable(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $pricing = ProfilePricing::create([
            'profile_id' => $profile->id,
            'duration' => '1 hora',
            'price' => 150.00,
            'description' => null,
        ]);

        $this->assertNull($pricing->description);
    }
}
