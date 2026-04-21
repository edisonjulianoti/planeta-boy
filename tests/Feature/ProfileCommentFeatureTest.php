<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\ProfileComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class ProfileCommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_comment(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/comentar", [
                'comment' => 'Comentário de teste',
                'rating' => 4.5,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('profile_comments', [
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'comment' => 'Comentário de teste',
        ]);
    }

    public function test_guest_cannot_comment(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->post("/perfis/{$profile->id}/comentar", [
            'comment' => 'Comentário de teste',
            'rating' => 4.5,
        ])
            ->assertRedirect(route('login'));
    }

    public function test_comment_requires_comment_field(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/comentar", [
                'comment' => '',
                'rating' => 4.5,
            ])
            ->assertSessionHasErrors('comment');
    }

    public function test_rating_is_optional(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/comentar", [
                'comment' => 'Comentário de teste',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('profile_comments', [
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'comment' => 'Comentário de teste',
        ]);
    }

    public function test_comment_redirects_to_profile(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/comentar", [
                'comment' => 'Comentário de teste',
                'rating' => 4.5,
            ])
            ->assertRedirect();
    }

    public function test_comment_stores_in_database(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post("/perfis/{$profile->id}/comentar", [
                'comment' => 'Comentário de teste',
                'rating' => 4.5,
            ]);

        $comment = ProfileComment::where('profile_id', $profile->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($comment);
        $this->assertEquals('Comentário de teste', $comment->comment);
        $this->assertEquals(4.5, $comment->rating);
    }
}
