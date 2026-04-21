<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\ProfileComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_profile_comment(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $comment = ProfileComment::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'comment' => 'Comentário de teste',
            'rating' => 4.5,
        ]);

        $this->assertDatabaseHas('profile_comments', [
            'profile_id' => $profile->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_belongs_to_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $comment = ProfileComment::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'comment' => 'Comentário de teste',
        ]);

        $this->assertInstanceOf(Profile::class, $comment->profile);
        $this->assertEquals($profile->id, $comment->profile->id);
    }

    public function test_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $comment = ProfileComment::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'comment' => 'Comentário de teste',
        ]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    public function test_rating_is_decimal(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $comment = ProfileComment::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'comment' => 'Comentário de teste',
            'rating' => 4.5,
        ]);

        $this->assertEquals(4.5, $comment->rating);
    }

    public function test_rating_is_nullable(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $comment = ProfileComment::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'comment' => 'Comentário de teste',
            'rating' => null,
        ]);

        $this->assertNull($comment->rating);
    }

    public function test_comment_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        ProfileComment::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'comment' => null,
        ]);
    }
}
