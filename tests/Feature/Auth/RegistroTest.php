<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RegistroTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_page_is_accessible_to_guests(): void
    {
        $this->get('/registro')
            ->assertOk()
            ->assertViewIs('auth.registro');
    }

    public function test_authenticated_user_is_redirected_from_registro_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/registro')
            ->assertRedirect(route('home'));
    }

    public function test_user_can_register_and_is_redirected_to_profile(): void
    {

        $this->post('/registro', [
            'nome'                  => 'Novo Usuário',
            'email'                 => 'novo@example.com',
            'senha'                 => 'senha123',
            'senha_confirmation'    => 'senha123',
            'cpf'                   => '529.982.247-25',
            'data_nascimento'       => '15/05/1990',
            'lgpd_consent'          => '1',
        ])
            ->assertRedirect(route('perfil'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name'  => 'Novo Usuário',
            'email' => 'novo@example.com',
            'plan'  => 'free',
        ]);
    }

    public function test_registro_requires_nome(): void
    {

        $this->from(route('registro'))
            ->post('/registro', [
                'nome'               => '',
                'email'              => 'novo@example.com',
                'senha'              => 'senha123',
                'senha_confirmation' => 'senha123',
                'cpf'                => '529.982.247-25',
                'data_nascimento'    => '15/05/1990',
                'lgpd_consent'       => '1',
            ])
            ->assertSessionHasErrors('nome')
            ->assertRedirect(route('registro'));

        $this->assertGuest();
    }

    public function test_registro_requires_valid_email(): void
    {

        $this->from(route('registro'))
            ->post('/registro', [
                'nome'               => 'Teste',
                'email'              => 'email-invalido',
                'senha'              => 'senha123',
                'senha_confirmation' => 'senha123',
                'cpf'                => '529.982.247-25',
                'data_nascimento'    => '15/05/1990',
                'lgpd_consent'       => '1',
            ])
            ->assertSessionHasErrors('email')
            ->assertRedirect(route('registro'));

        $this->assertGuest();
    }

    public function test_registro_rejects_duplicate_email(): void
    {

        User::factory()->create(['email' => 'existente@example.com']);

        $this->from(route('registro'))
            ->post('/registro', [
                'nome'               => 'Outro',
                'email'              => 'existente@example.com',
                'senha'              => 'senha123',
                'senha_confirmation' => 'senha123',
                'cpf'                => '529.982.247-25',
                'data_nascimento'    => '15/05/1990',
                'lgpd_consent'       => '1',
            ])
            ->assertSessionHasErrors('email')
            ->assertRedirect(route('registro'));
    }

    public function test_registro_requires_password_confirmation(): void
    {

        $this->from(route('registro'))
            ->post('/registro', [
                'nome'               => 'Teste',
                'email'              => 'novo@example.com',
                'senha'              => 'senha123',
                'senha_confirmation' => 'diferente',
                'cpf'                => '529.982.247-25',
                'data_nascimento'    => '15/05/1990',
                'lgpd_consent'       => '1',
            ])
            ->assertSessionHasErrors('senha')
            ->assertRedirect(route('registro'));

        $this->assertGuest();
    }

    public function test_registro_requires_minimum_password_length(): void
    {

        $this->from(route('registro'))
            ->post('/registro', [
                'nome'               => 'Teste',
                'email'              => 'novo@example.com',
                'senha'              => '123',
                'senha_confirmation' => '123',
                'cpf'                => '529.982.247-25',
                'data_nascimento'    => '15/05/1990',
                'lgpd_consent'       => '1',
            ])
            ->assertSessionHasErrors('senha')
            ->assertRedirect(route('registro'));

        $this->assertGuest();
    }

    public function test_new_user_has_free_plan_and_no_admin(): void
    {

        $this->post('/registro', [
            'nome'               => 'Usuário Comum',
            'email'              => 'comum@example.com',
            'senha'              => 'senha123',
            'senha_confirmation' => 'senha123',
            'cpf'                => '529.982.247-25',
            'data_nascimento'    => '15/05/1990',
            'lgpd_consent'       => '1',
        ]);

        $user = User::where('email', 'comum@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('free', $user->plan);
        $this->assertFalse($user->is_admin);
    }
}
