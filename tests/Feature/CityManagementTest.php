<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class CityManagementTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Testes de Autorização ====================

    public function test_guest_cannot_access_cities_list(): void
    {
        $this->get('/admin/cidades')
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_cities_list(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/cidades')
            ->assertForbidden();
    }

    public function test_admin_can_access_cities_list(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/cidades')
            ->assertOk();
    }

    public function test_guest_cannot_access_create_page(): void
    {
        $this->get('/admin/cidades/criar')
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_create_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/cidades/criar')
            ->assertForbidden();
    }

    public function test_guest_cannot_store_city(): void
    {
        $this->post('/admin/cidades')
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_store_city(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->post('/admin/cidades')
            ->assertForbidden();
    }

    public function test_guest_cannot_access_edit_page(): void
    {
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->get("/admin/cidades/{$city->id}/editar")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_edit_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->actingAs($user)
            ->get("/admin/cidades/{$city->id}/editar")
            ->assertForbidden();
    }

    public function test_guest_cannot_update_city(): void
    {
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->put("/admin/cidades/{$city->id}")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_update_city(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->actingAs($user)
            ->put("/admin/cidades/{$city->id}")
            ->assertForbidden();
    }

    public function test_guest_cannot_delete_city(): void
    {
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->delete("/admin/cidades/{$city->id}")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_delete_city(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->actingAs($user)
            ->delete("/admin/cidades/{$city->id}")
            ->assertForbidden();
    }

    // ==================== Testes de Listagem ====================

    public function test_cities_list_displays_cities(): void
    {
        $admin = User::factory()->admin()->create();
        City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/cidades')
            ->assertOk()
            ->assertViewHas('cities');
    }

    // ==================== Testes de Criação ====================

    public function test_admin_can_create_city_with_valid_data(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/cidades', [
                'name' => 'São Paulo',
                'state' => 'SP',
                'order' => 1,
                'active' => true,
            ])
            ->assertRedirect(route('admin.cities'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('cities', [
            'name' => 'São Paulo',
            'state' => 'SP',
        ]);
    }

    public function test_create_city_generates_slug_automatically(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/cidades', [
                'name' => 'Rio de Janeiro',
                'state' => 'RJ',
                'active' => true,
            ]);

        $this->assertDatabaseHas('cities', [
            'name' => 'Rio de Janeiro',
            'slug' => 'rio-de-janeiro-rj',
        ]);
    }

    public function test_create_city_requires_name(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/cidades', [
                'name' => '',
                'state' => 'SP',
                'active' => true,
            ])
            ->assertSessionHasErrors(['name']);
    }

    public function test_create_city_requires_state(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/cidades', [
                'name' => 'São Paulo',
                'state' => '',
                'active' => true,
            ])
            ->assertSessionHasErrors(['state']);
    }

    public function test_create_city_allows_optional_image(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/cidades', [
                'name' => 'São Paulo',
                'state' => 'SP',
                'active' => true,
            ])
            ->assertRedirect(route('admin.cities'));
    }

    // ==================== Testes de Edição ====================

    public function test_admin_can_access_edit_page(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->get("/admin/cidades/{$city->id}/editar")
            ->assertOk()
            ->assertViewHas('city');
    }

    public function test_admin_can_update_city(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->put("/admin/cidades/{$city->id}", [
                'name' => 'Rio de Janeiro',
                'state' => 'RJ',
                'order' => 2,
                'active' => true,
            ])
            ->assertRedirect(route('admin.cities'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('cities', [
            'id' => $city->id,
            'name' => 'Rio de Janeiro',
            'state' => 'RJ',
        ]);
    }

    public function test_update_city_requires_name(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->put("/admin/cidades/{$city->id}", [
                'name' => '',
                'state' => 'SP',
            ])
            ->assertSessionHasErrors(['name']);
    }

    public function test_update_city_requires_state(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->put("/admin/cidades/{$city->id}", [
                'name' => 'São Paulo',
                'state' => '',
            ])
            ->assertSessionHasErrors(['state']);
    }

    // ==================== Testes de Deleção ====================

    public function test_admin_can_delete_city(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'image' => 'cities/teste.jpg',
            'active' => true,
        ]);

        Storage::disk('public')->put('cities/teste.jpg', 'content');

        $this->actingAs($admin)
            ->delete("/admin/cidades/{$city->id}")
            ->assertRedirect(route('admin.cities'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('cities', [
            'id' => $city->id,
        ]);

        Storage::disk('public')->assertMissing('cities/teste.jpg');
    }

    public function test_delete_city_returns_success_message(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->delete("/admin/cidades/{$city->id}")
            ->assertSessionHas('success');
    }
}
