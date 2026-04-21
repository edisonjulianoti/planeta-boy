<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfileComment;
use App\Models\User;
use Database\Seeders\ProfileCommentSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileCommentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_comments_for_profiles(): void
    {
        $this->seed([UserSeeder::class, ProfileSeeder::class]);

        $this->seed(ProfileCommentSeeder::class);

        $profiles = Profile::all();
        $hasComments = $profiles->contains(function ($profile) {
            return $profile->comments()->count() > 0;
        });

        $this->assertTrue($hasComments);
    }

    public function test_creates_0_to_5_comments_per_profile(): void
    {
        $this->seed([UserSeeder::class, ProfileSeeder::class]);

        $this->seed(ProfileCommentSeeder::class);

        $profiles = Profile::all();
        foreach ($profiles as $profile) {
            $count = $profile->comments()->count();
            $this->assertGreaterThanOrEqual(0, $count);
            $this->assertLessThanOrEqual(5, $count);
        }
    }

    public function test_comment_belongs_to_user(): void
    {
        $this->seed([UserSeeder::class, ProfileSeeder::class]);

        $this->seed(ProfileCommentSeeder::class);

        $comment = ProfileComment::first();
        $this->assertNotNull($comment->user);
        $this->assertInstanceOf(User::class, $comment->user);
    }

    public function test_rating_is_between_3_and_5(): void
    {
        $this->seed([UserSeeder::class, ProfileSeeder::class]);

        $this->seed(ProfileCommentSeeder::class);

        $comments = ProfileComment::whereNotNull('rating')->get();
        foreach ($comments as $comment) {
            $this->assertGreaterThanOrEqual(3, $comment->rating);
            $this->assertLessThanOrEqual(5, $comment->rating);
        }
    }
}
