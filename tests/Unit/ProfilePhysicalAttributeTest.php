<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfilePhysicalAttribute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProfilePhysicalAttributeTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_body_type_field(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $attribute = ProfilePhysicalAttribute::create([
            'profile_id' => $profile->id,
            'height' => 180,
            'weight' => 75,
            'body_type' => 'atlético',
        ]);

        $this->assertEquals('atlético', $attribute->body_type);
        $this->assertDatabaseHas('profile_physical_attributes', [
            'profile_id' => $profile->id,
            'body_type' => 'atlético',
        ]);
    }

    public function test_body_type_can_be_null(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $attribute = ProfilePhysicalAttribute::create([
            'profile_id' => $profile->id,
            'height' => 180,
        ]);

        $this->assertNull($attribute->body_type);
    }

    public function test_belongs_to_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $attribute = ProfilePhysicalAttribute::create([
            'profile_id' => $profile->id,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $attribute->profile());
        $this->assertEquals($profile->id, $attribute->profile->id);
    }

    public function test_cascade_delete_with_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        ProfilePhysicalAttribute::create([
            'profile_id' => $profile->id,
            'body_type' => 'musculoso',
        ]);

        $this->assertDatabaseHas('profile_physical_attributes', ['profile_id' => $profile->id]);

        $profile->delete();

        $this->assertDatabaseMissing('profile_physical_attributes', ['profile_id' => $profile->id]);
    }
}
