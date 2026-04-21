<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible_to_guests(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect(route('home'));
    }

    public function test_user_login_redirects_to_profile(): void
    {
        $user = User::factory()->create([
            'email'    => 'user@example.com',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ])
            ->assertRedirect(route('perfil'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create([
            'email'    => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email'    => $admin->email,
            'password' => 'password',
        ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_rejects_invalid_password(): void
    {
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => 'correct-password',
        ]);

        $this->from(route('login'))
            ->post('/login', [
                'email'    => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrors('email')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_rejects_nonexistent_email(): void
    {
        $this->from(route('login'))
            ->post('/login', [
                'email'    => 'nonexistent@example.com',
                'password' => 'any-password',
            ])
            ->assertSessionHasErrors('email')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_validates_required_fields(): void
    {
        $this->from(route('login'))
            ->post('/login', [])
            ->assertSessionHasErrors(['email', 'password'])
            ->assertRedirect(route('login'));
    }

    public function test_login_validates_email_format(): void
    {
        $this->from(route('login'))
            ->post('/login', [
                'email'    => 'not-an-email',
                'password' => 'password',
            ])
            ->assertSessionHasErrors('email')
            ->assertRedirect(route('login'));
    }

    public function test_remember_me_creates_persistent_session(): void
    {
        $user = User::factory()->create([
            'email'    => 'remember@example.com',
            'password' => 'password',
        ]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
            'remember' => true,
        ])
            ->assertRedirect(route('perfil'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($this->app['auth']->user()->getRememberToken());
    }

    public function test_logout_destroys_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_guest_cannot_access_user_panel(): void
    {
        $this->get(route('perfil'))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
