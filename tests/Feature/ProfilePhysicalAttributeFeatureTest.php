<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProfilePhysicalAttributeFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    private function actingAsUser(): self
    {
        return $this->actingAs($this->user);
    }

    // ==================== Criação de Perfil com Características ====================

    public function test_physical_attributes_are_saved_when_creating_profile(): void
    {
        $this->actingAsUser();

        $response = $this->post(route('perfil.salvar'), [
            'name'       => 'João Silva',
            'age'        => 25,
            'city'       => 'São Paulo',
            'state'      => 'SP',
            'height'     => 180,
            'weight'     => 75,
            'hair_color' => 'castanho',
            'eye_color'  => 'verdes',
            'ethnicity'  => 'branca',
            'body_type'  => 'atlético',
        ]);

        $profile = $this->user->fresh()->profile;
        $response->assertRedirect(route('perfil.editar', $profile->id));

        $this->assertNotNull($profile);
        $this->assertNotNull($profile->physicalAttributes);
        $this->assertEquals(180, $profile->physicalAttributes->height);
        $this->assertEquals(75, $profile->physicalAttributes->weight);
        $this->assertEquals('castanho', $profile->physicalAttributes->hair_color);
        $this->assertEquals('verdes', $profile->physicalAttributes->eye_color);
        $this->assertEquals('branca', $profile->physicalAttributes->ethnicity);
        $this->assertEquals('atlético', $profile->physicalAttributes->body_type);
    }

    public function test_partial_physical_attributes_are_saved(): void
    {
        $this->actingAsUser();

        $response = $this->post(route('perfil.salvar'), [
            'name'      => 'João Silva',
            'age'       => 25,
            'city'      => 'São Paulo',
            'state'     => 'SP',
            'height'    => 175,
            'body_type' => 'musculoso',
        ]);

        $profile = $this->user->fresh()->profile;
        $response->assertRedirect(route('perfil.editar', $profile->id));

        $this->assertNotNull($profile->physicalAttributes);
        $this->assertEquals(175, $profile->physicalAttributes->height);
        $this->assertEquals('musculoso', $profile->physicalAttributes->body_type);
        $this->assertNull($profile->physicalAttributes->weight);
        $this->assertNull($profile->physicalAttributes->hair_color);
        $this->assertNull($profile->physicalAttributes->eye_color);
        $this->assertNull($profile->physicalAttributes->ethnicity);
    }

    public function test_creating_profile_without_physical_attributes(): void
    {
        $this->actingAsUser();

        $response = $this->post(route('perfil.salvar'), [
            'name'  => 'João Silva',
            'age'   => 25,
            'city'  => 'São Paulo',
            'state' => 'SP',
        ]);

        $profile = $this->user->fresh()->profile;
        $response->assertRedirect(route('perfil.editar', $profile->id));

        $this->assertNotNull($profile);
        $this->assertNull($profile->physicalAttributes);
    }

    // ==================== Edição de Perfil com Características ====================

    public function test_physical_attributes_are_updated_when_editing_profile(): void
    {
        $profile = Profile::factory()->create([
            'user_id' => $this->user->id,
            'name'    => 'João Silva',
            'age'     => 25,
            'city'    => 'São Paulo',
            'state'   => 'SP',
        ]);

        // Create initial physical attributes
        $profile->physicalAttributes()->create([
            'height' => 170,
            'weight' => 70,
        ]);

        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'       => 'João Silva Atualizado',
            'age'        => 26,
            'city'       => 'São Paulo',
            'state'      => 'SP',
            'height'     => 185,
            'weight'     => 80,
            'hair_color' => 'loiro',
            'body_type'  => 'sarado',
        ])->assertRedirect(route('perfil.editar', $profile->id));

        $profile->refresh();
        $profile->load('physicalAttributes');

        $this->assertEquals(185, $profile->physicalAttributes->height);
        $this->assertEquals(80, $profile->physicalAttributes->weight);
        $this->assertEquals('loiro', $profile->physicalAttributes->hair_color);
        $this->assertEquals('sarado', $profile->physicalAttributes->body_type);
    }

    public function test_physical_attributes_are_removed_when_cleared(): void
    {
        $profile = Profile::factory()->create([
            'user_id' => $this->user->id,
            'name'    => 'João Silva',
            'age'     => 25,
            'city'    => 'São Paulo',
            'state'   => 'SP',
        ]);

        $profile->physicalAttributes()->create([
            'height' => 170,
            'weight' => 70,
        ]);

        $this->assertNotNull($profile->physicalAttributes);

        $this->actingAsUser();

        // Update without any physical attributes — should delete existing ones
        $this->post(route('perfil.salvar'), [
            'name'  => 'João Silva',
            'age'   => 25,
            'city'  => 'São Paulo',
            'state' => 'SP',
        ])->assertRedirect(route('perfil.editar', $profile->id));

        $profile->refresh();

        $this->assertNull($profile->physicalAttributes);
        $this->assertDatabaseMissing('profile_physical_attributes', ['profile_id' => $profile->id]);
    }

    // ==================== Validação ====================

    public function test_height_must_be_integer(): void
    {
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'   => 'João Silva',
            'age'    => 25,
            'city'   => 'São Paulo',
            'state'  => 'SP',
            'height' => 'abc',
        ])->assertInvalid(['height']);
    }

    public function test_height_must_be_between_100_and_250(): void
    {
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'   => 'João Silva',
            'age'    => 25,
            'city'   => 'São Paulo',
            'state'  => 'SP',
            'height' => 50,
        ])->assertInvalid(['height']);
    }

    public function test_weight_must_be_integer(): void
    {
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'   => 'João Silva',
            'age'    => 25,
            'city'   => 'São Paulo',
            'state'  => 'SP',
            'weight' => 'abc',
        ])->assertInvalid(['weight']);
    }

    public function test_weight_must_be_between_30_and_300(): void
    {
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'   => 'João Silva',
            'age'    => 25,
            'city'   => 'São Paulo',
            'state'  => 'SP',
            'weight' => 400,
        ])->assertInvalid(['weight']);
    }

    // ==================== Admin ====================

    public function test_admin_can_update_physical_attributes(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $profile = Profile::factory()->create([
            'user_id' => $this->user->id,
            'name'    => 'João Silva',
            'age'     => 25,
            'city'    => 'São Paulo',
            'state'   => 'SP',
        ]);

        $this->actingAs($admin);

        $this->put(route('admin.profiles.update', $profile), [
            'name'       => 'João Silva',
            'age'        => 25,
            'city'       => 'São Paulo',
            'state'      => 'SP',
            'height'     => 190,
            'weight'     => 85,
            'hair_color' => 'preto',
            'eye_color'  => 'castanho',
            'ethnicity'  => 'parda',
            'body_type'  => 'forte',
        ])->assertRedirect(route('admin.profiles'));

        $profile->refresh();
        $profile->load('physicalAttributes');

        $this->assertNotNull($profile->physicalAttributes);
        $this->assertEquals(190, $profile->physicalAttributes->height);
        $this->assertEquals(85, $profile->physicalAttributes->weight);
        $this->assertEquals('preto', $profile->physicalAttributes->hair_color);
        $this->assertEquals('castanho', $profile->physicalAttributes->eye_color);
        $this->assertEquals('parda', $profile->physicalAttributes->ethnicity);
        $this->assertEquals('forte', $profile->physicalAttributes->body_type);
    }
}
