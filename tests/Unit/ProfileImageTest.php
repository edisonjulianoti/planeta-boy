<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfileImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProfileImageTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Relacionamentos ====================

    public function test_belongs_to_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $image = $profile->images()->create([
            'url'  => 'profiles/images/test.jpg',
            'order' => 1,
        ]);

        $this->assertInstanceOf(ProfileImage::class, $image);
        $this->assertTrue($image->profile()->exists());
        $this->assertEquals($profile->id, $image->profile->id);
    }

    public function test_images_are_ordered_by_order_column(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $profile->images()->create(['url' => 'img3.jpg', 'order' => 3]);
        $profile->images()->create(['url' => 'img1.jpg', 'order' => 1]);
        $profile->images()->create(['url' => 'img2.jpg', 'order' => 2]);

        $ordered = $profile->images()->orderBy('order')->get();

        $this->assertEquals('img1.jpg', $ordered[0]->url);
        $this->assertEquals('img2.jpg', $ordered[1]->url);
        $this->assertEquals('img3.jpg', $ordered[2]->url);
    }

    public function test_is_main_defaults_to_false(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $image = $profile->images()->create([
            'url'     => 'profiles/images/test.jpg',
            'is_main' => false,
            'order'   => 1,
        ]);

        $this->assertFalse($image->is_main);
    }

    // ==================== Helpers ====================

    public function test_image_url_returns_asset_url(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $image = $profile->images()->create([
            'url'  => 'profiles/images/test.jpg',
            'order' => 1,
        ]);

        $this->assertStringContainsString('storage/profiles/images/test.jpg', $image->imageUrl());
    }

    public function test_thumb_url_falls_back_to_image_url_when_no_thumb(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $image = $profile->images()->create([
            'url'  => 'profiles/images/test.jpg',
            'order' => 1,
        ]);

        $this->assertEquals($image->imageUrl(), $image->thumbUrl());
    }

    public function test_thumb_url_returns_thumb_path_when_available(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $image = $profile->images()->create([
            'url'       => 'profiles/images/test.jpg',
            'thumb_path' => 'profiles/thumbs/test_thumb.webp',
            'order'     => 1,
        ]);

        $this->assertStringContainsString('storage/profiles/thumbs/test_thumb.webp', $image->thumbUrl());
    }

    // ==================== Profile -> primaryImage ====================

    public function test_profile_primary_image_returns_main_image(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $img1 = $profile->images()->create(['url' => 'img1.jpg', 'is_main' => false, 'order' => 1]);
        $img2 = $profile->images()->create(['url' => 'img2.jpg', 'is_main' => true, 'order' => 0]);

        $this->assertEquals($img2->id, $profile->primaryImage()->id);
    }

    public function test_profile_primary_image_returns_null_when_no_images(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->assertNull($profile->primaryImage());
    }

    public function test_profile_primary_image_url_returns_string(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $profile->images()->create(['url' => 'main.jpg', 'is_main' => true, 'order' => 0]);

        $this->assertNotNull($profile->primaryImageUrl());
        $this->assertStringContainsString('main.jpg', $profile->primaryImageUrl());
    }

    public function test_profile_primary_image_url_returns_null_when_no_images(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->assertNull($profile->primaryImageUrl());
    }

    // ==================== Deleção ====================

    public function test_profile_images_are_deleted_with_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $profile->images()->create(['url' => 'img1.jpg', 'order' => 1]);
        $profile->images()->create(['url' => 'img2.jpg', 'order' => 2]);

        $this->assertDatabaseCount('profile_images', 2);

        $profile->delete();

        $this->assertDatabaseCount('profile_images', 0);
    }

    public function test_can_delete_specific_image(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $img1 = $profile->images()->create(['url' => 'keep.jpg', 'order' => 1]);
        $img2 = $profile->images()->create(['url' => 'delete.jpg', 'order' => 2]);

        $profile->images()->where('id', $img2->id)->delete();

        $this->assertDatabaseHas('profile_images', ['id' => $img1->id]);
        $this->assertDatabaseMissing('profile_images', ['id' => $img2->id]);
    }
}
