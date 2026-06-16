<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Database\Seeders\PlanSeeder;
use Database\Seeders\ProfileSeeder;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SiteRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_explore_and_plans_respond_ok(): void
    {
        $this->seed([ProfileSeeder::class, PlanSeeder::class]);

        $this->get('/')->assertOk();
        $this->get('/explorar')->assertOk();
        $this->get('/planos')->assertOk();
    }

    public function test_profile_show_responds_ok_for_demo_slug(): void
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'name' => 'Lucas Santos',
            'active' => true,
        ]);

        $this->get('/perfis/lucas-santos')->assertOk();
    }

    public function test_profile_show_returns_not_found_for_unknown_slug(): void
    {
        $this->get('/perfis/inexistente')->assertNotFound();
    }

    public function test_auth_pages_respond_ok(): void
    {
        $this->get('/entrar')->assertOk();
        $this->get('/cadastro')->assertOk();
    }

    public function test_public_legal_pages_respond_ok(): void
    {
        $this->get('/privacidade')->assertOk();
        $this->get('/termos')->assertOk();
    }

    public function test_legacy_english_paths_redirect_to_portuguese(): void
    {
        $this->get('/privacy')->assertRedirect('/privacidade');
        $this->get('/terms')->assertRedirect('/termos');
    }

    public function test_register_creates_user_and_logs_in(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->from('/cadastro')
            ->post('/cadastro', [
                'nome' => 'Edison Teste',
                'email' => 'edison-auth@example.com',
                'senha' => 'senha-segura-8',
                'senha_confirmation' => 'senha-segura-8',
                'cpf' => '529.982.247-25',
                'data_nascimento' => '15/05/1990',
                'lgpd_consent' => '1',
            ])
            ->assertRedirect(route('verification.notice'))
            ->assertSessionHas('status');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'edison-auth@example.com']);
    }

    public function test_login_accepts_valid_credentials(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $this->from('/entrar')
            ->post('/entrar', [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('perfil'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_invalid_password(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create([
            'email' => 'badlogin@example.com',
            'password' => 'password',
        ]);

        $this->from('/entrar')
            ->post('/entrar', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrors('email')
            ->assertRedirect('/entrar');

        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_entrar_when_already_authenticated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/entrar')->assertRedirect(route('home'));
    }

    public function test_logout_ends_session(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/sair')
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
