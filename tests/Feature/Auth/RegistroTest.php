<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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

    public function test_user_can_register_and_redirects_to_verification(): void
    {
        Notification::fake();

        $this->post('/registro', [
            'nome'                  => 'Novo Usuario',
            'email'                 => 'novo@example.com',
            'senha'                 => 'senha123',
            'senha_confirmation'    => 'senha123',
            'cpf'                   => '529.982.247-25',
            'data_nascimento'       => '15/05/1990',
            'lgpd_consent'          => '1',
        ])
            ->assertRedirect(route('verification.notice'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name'  => 'Novo Usuario',
            'email' => 'novo@example.com',
            'plan'  => 'free',
        ]);

        // Assert verification notification was sent
        $user = User::where('email', 'novo@example.com')->first();
        Notification::assertSentTo($user, VerifyEmailNotification::class);
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

    public function test_registro_rejects_invalid_cpf(): void
    {
        $this->from(route('registro'))
            ->post('/registro', [
                'nome'               => 'Teste CPF',
                'email'              => 'cpf@example.com',
                'senha'              => 'senha123',
                'senha_confirmation' => 'senha123',
                'cpf'                => '123.456.789-00',
                'data_nascimento'    => '15/05/1990',
                'lgpd_consent'       => '1',
            ])
            ->assertSessionHasErrors('cpf')
            ->assertRedirect(route('registro'));

        $this->assertGuest();
    }

    public function test_registro_rejects_repeated_cpf_sequence(): void
    {
        $this->from(route('registro'))
            ->post('/registro', [
                'nome'               => 'Teste CPF',
                'email'              => 'cpf@example.com',
                'senha'              => 'senha123',
                'senha_confirmation' => 'senha123',
                'cpf'                => '111.111.111-11',
                'data_nascimento'    => '15/05/1990',
                'lgpd_consent'       => '1',
            ])
            ->assertSessionHasErrors('cpf')
            ->assertRedirect(route('registro'));

        $this->assertGuest();
    }

    public function test_new_user_has_free_plan_and_not_admin(): void
    {
        $this->post('/registro', [
            'nome'               => 'Usuario Comum',
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
        $this->assertNull($user->email_verified_at);
    }

    public function test_registration_sends_verification_email(): void
    {
        Notification::fake();

        $this->post('/registro', [
            'nome'                  => 'Email Verify',
            'email'                 => 'verify@example.com',
            'senha'                 => 'senha123',
            'senha_confirmation'    => 'senha123',
            'cpf'                   => '529.982.247-25',
            'data_nascimento'       => '15/05/1990',
            'lgpd_consent'          => '1',
        ]);

        $user = User::where('email', 'verify@example.com')->first();

        Notification::assertSentTo(
            $user,
            VerifyEmailNotification::class,
            function ($notification, $channels) use ($user) {
                $mail = $notification->toMail($user);
                $this->assertStringContainsString('Confirme seu e-mail', $mail->subject);
                $this->assertStringContainsString($user->name, $mail->greeting ?? '');

                return true;
            }
        );
    }
}
