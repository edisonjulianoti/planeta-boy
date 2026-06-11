<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfilePhysicalAttribute;
use App\Models\User;
use Database\Seeders\ProfilePhysicalAttributeSeeder;
use Database\Seeders\ProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePhysicalAttributeSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_attributes_for_all_profiles(): void
    {
        User::factory()->count(5)->create();
        $this->seed(ProfileSeeder::class);
        $profileCount = Profile::count();

        $this->seed(ProfilePhysicalAttributeSeeder::class);

        $this->assertEquals($profileCount, ProfilePhysicalAttribute::count());
    }

    public function test_height_is_between_150_and_190(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfilePhysicalAttributeSeeder::class);

        $attribute = ProfilePhysicalAttribute::where('profile_id', $profile->id)->first();

        $this->assertGreaterThanOrEqual(150, $attribute->height);
        $this->assertLessThanOrEqual(190, $attribute->height);
    }

    public function test_weight_is_between_50_and_90(): void
    {
        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfilePhysicalAttributeSeeder::class);

        $attribute = ProfilePhysicalAttribute::where('profile_id', $profile->id)->first();

        $this->assertGreaterThanOrEqual(50, $attribute->weight);
        $this->assertLessThanOrEqual(90, $attribute->weight);
    }

    public function test_hair_color_is_valid(): void
    {
        $validColors = ['preto', 'castanho', 'loiro', 'ruivo', 'grisalho'];

        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfilePhysicalAttributeSeeder::class);

        $attribute = ProfilePhysicalAttribute::where('profile_id', $profile->id)->first();

        $this->assertContains($attribute->hair_color, $validColors);
    }

    public function test_eye_color_is_valid(): void
    {
        $validColors = ['castanho', 'azul', 'verde', 'preto'];

        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfilePhysicalAttributeSeeder::class);

        $attribute = ProfilePhysicalAttribute::where('profile_id', $profile->id)->first();

        $this->assertContains($attribute->eye_color, $validColors);
    }

    public function test_ethnicity_is_valid(): void
    {
        $validEthnicities = ['branca', 'negra', 'parda', 'amarela', 'indígena'];

        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfilePhysicalAttributeSeeder::class);

        $attribute = ProfilePhysicalAttribute::where('profile_id', $profile->id)->first();

        $this->assertContains($attribute->ethnicity, $validEthnicities);
    }

    public function test_body_type_is_valid(): void
    {
        $validBodyTypes = ['magro', 'atlético', 'musculoso', 'sarado', 'forte'];

        User::factory()->create();
        $profile = Profile::factory()->create();
        $this->seed(ProfilePhysicalAttributeSeeder::class);

        $attribute = ProfilePhysicalAttribute::where('profile_id', $profile->id)->first();

        $this->assertNotNull($attribute->body_type);
        $this->assertContains($attribute->body_type, $validBodyTypes);
    }
}
