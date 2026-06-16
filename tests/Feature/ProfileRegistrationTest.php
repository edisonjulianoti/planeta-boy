<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ProfileRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    private function validProfileData(array $overrides = []): array
    {
        return array_merge([
            'name'        => 'Joao Silva',
            'age'         => 28,
            'gender'      => 'masculino',
            'city'        => 'Sao Paulo',
            'state'       => 'SP',
            'description' => 'Um teste de perfil.',
            'services'    => [],
            'height'      => 175,
            'weight'      => 75,
            'hair_color'  => 'castanho',
            'eye_color'   => 'verde',
            'ethnicity'   => 'branco',
            'body_type'   => 'atletico',
        ], $overrides);
    }

    // --- Profile Creation ---

    public function test_unverified_user_cannot_access_create_profile(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)
            ->get(route('perfil.criar'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_access_create_profile_page(): void
    {
        $this->actingAs($this->user)
            ->get(route('perfil.criar'))
            ->assertOk()
            ->assertViewIs('perfil.criar');
    }

    public function test_user_can_create_profile(): void
    {
        $this->actingAs($this->user);

        $this->post(route('perfil.salvar'), $this->validProfileData())
            ->assertRedirect();

        $this->assertDatabaseHas('profiles', [
            'name'  => 'Joao Silva',
            'city'  => 'Sao Paulo',
            'state' => 'SP',
            'age'   => 28,
        ]);
    }

    public function test_user_cannot_create_profile_without_required_fields(): void
    {
        $this->actingAs($this->user);

        $this->from(route('perfil.criar'))
            ->post(route('perfil.salvar'), [
                'name'  => '',
                'age'   => '',
                'city'  => '',
                'state' => '',
            ])
            ->assertSessionHasErrors(['name', 'age', 'city', 'state']);
    }

    public function test_user_cannot_create_profile_with_underage_age(): void
    {
        $this->actingAs($this->user);

        $this->post(route('perfil.salvar'), $this->validProfileData([
            'age' => 17,
        ]))
            ->assertSessionHasErrors('age');
    }

    public function test_user_can_create_profile_with_image(): void
    {
        $this->actingAs($this->user);

        $image = UploadedFile::fake()->image('foto.jpg', 400, 400)->size(100);

        $this->post(route('perfil.salvar'), $this->validProfileData([
            'gallery' => [$image],
        ]))->assertRedirect();

        $profile = Profile::where('name', 'Joao Silva')->first();
        $this->assertNotNull($profile);
        $this->assertCount(1, $profile->images);
    }

    public function test_user_can_update_existing_profile(): void
    {
        $this->actingAs($this->user);

        $this->post(route('perfil.salvar'), $this->validProfileData([
            'name' => 'Nome Original',
        ]))->assertRedirect();

        $this->post(route('perfil.salvar'), $this->validProfileData([
            'name' => 'Nome Atualizado',
        ]))->assertRedirect();

        $this->assertDatabaseHas('profiles', [
            'name' => 'Nome Atualizado',
        ]);
    }

    public function test_user_can_only_have_one_profile(): void
    {
        $this->actingAs($this->user);

        $this->post(route('perfil.salvar'), $this->validProfileData([
            'name' => 'Primeiro',
        ]))->assertRedirect();

        $this->post(route('perfil.salvar'), $this->validProfileData([
            'name' => 'Segundo',
        ]))->assertRedirect();

        $this->assertDatabaseHas('profiles', [
            'user_id' => $this->user->id,
            'name'    => 'Segundo',
        ]);
    }

    // --- Physical Attributes ---

    public function test_profile_can_be_created_with_physical_attributes(): void
    {
        $this->actingAs($this->user);

        $this->post(route('perfil.salvar'), $this->validProfileData())
            ->assertRedirect();

        $profile = Profile::where('name', 'Joao Silva')->first();
        $this->assertNotNull($profile);

        $profile->load('physicalAttributes');
        $this->assertNotNull($profile->physicalAttributes);
        $this->assertSame(175, $profile->physicalAttributes->height);
        $this->assertSame('castanho', $profile->physicalAttributes->hair_color);
    }

    // --- User Profile Update (with CPF) ---

    public function test_user_can_update_cpf_in_profile(): void
    {
        $this->actingAs($this->user);

        $this->put(route('perfil.atualizar'), [
            'name'  => $this->user->name,
            'email' => $this->user->email,
            'cpf'   => '529.982.247-25',
        ])->assertRedirect(route('perfil'))
          ->assertSessionHas('success');

        $this->user->refresh();
        $this->assertNotNull($this->user->cpf);
    }

    public function test_user_cannot_update_with_invalid_cpf(): void
    {
        $this->actingAs($this->user);

        $this->from(route('perfil'))
            ->put(route('perfil.atualizar'), [
                'name'  => $this->user->name,
                'email' => $this->user->email,
                'cpf'   => '123.456.789-00',
            ])
            ->assertSessionHasErrors('cpf');
    }

    public function test_user_can_update_cpf_without_mask(): void
    {
        $this->actingAs($this->user);

        $this->put(route('perfil.atualizar'), [
            'name'  => $this->user->name,
            'email' => $this->user->email,
            'cpf'   => '52998224725',
        ])->assertRedirect(route('perfil'));

        $this->user->refresh();
        $this->assertNotNull($this->user->cpf);
    }
}
