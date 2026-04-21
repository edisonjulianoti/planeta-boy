<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\SubscriberCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SubscriberCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Testes de Autorização ====================

    public function test_guest_cannot_access_subscriber_categories_list(): void
    {
        $this->get('/admin/categorias-assinantes')
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_subscriber_categories_list(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/categorias-assinantes')
            ->assertForbidden();
    }

    public function test_admin_can_access_subscriber_categories_list(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/categorias-assinantes')
            ->assertOk();
    }

    public function test_guest_cannot_access_create_page(): void
    {
        $this->get('/admin/categorias-assinantes/criar')
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_create_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/categorias-assinantes/criar')
            ->assertForbidden();
    }

    public function test_guest_cannot_store_category(): void
    {
        $this->post('/admin/categorias-assinantes')
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_store_category(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->post('/admin/categorias-assinantes')
            ->assertForbidden();
    }

    public function test_guest_cannot_access_edit_page(): void
    {
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->get("/admin/categorias-assinantes/{$category->id}/editar")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_edit_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->actingAs($user)
            ->get("/admin/categorias-assinantes/{$category->id}/editar")
            ->assertForbidden();
    }

    public function test_guest_cannot_update_category(): void
    {
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->put("/admin/categorias-assinantes/{$category->id}")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_update_category(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->actingAs($user)
            ->put("/admin/categorias-assinantes/{$category->id}")
            ->assertForbidden();
    }

    public function test_guest_cannot_delete_category(): void
    {
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->delete("/admin/categorias-assinantes/{$category->id}")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_delete_category(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->actingAs($user)
            ->delete("/admin/categorias-assinantes/{$category->id}")
            ->assertForbidden();
    }

    // ==================== Testes de Listagem ====================

    public function test_subscriber_categories_list_displays_categories(): void
    {
        $admin = User::factory()->admin()->create();
        SubscriberCategory::create([
            'name' => 'Básico',
            'slug' => 'basico',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/categorias-assinantes')
            ->assertOk()
            ->assertViewHas('categories');
    }

    // ==================== Testes de Criação ====================

    public function test_admin_can_create_category_with_valid_data(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/categorias-assinantes', [
                'name' => 'Nova Categoria',
                'description' => 'Descrição da categoria',
                'active' => true,
            ])
            ->assertRedirect(route('admin.subscriber-categories'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('subscriber_categories', [
            'name' => 'Nova Categoria',
            'slug' => 'nova-categoria',
        ]);
    }

    public function test_create_category_generates_slug_automatically(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/categorias-assinantes', [
                'name' => 'Categoria Teste',
                'active' => true,
            ]);

        $this->assertDatabaseHas('subscriber_categories', [
            'name' => 'Categoria Teste',
            'slug' => 'categoria-teste',
        ]);
    }

    public function test_create_category_requires_name(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/categorias-assinantes', [
                'name' => '',
                'active' => true,
            ])
            ->assertSessionHasErrors(['name']);
    }

    public function test_create_category_allows_optional_description(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/admin/categorias-assinantes', [
                'name' => 'Categoria',
                'active' => true,
            ])
            ->assertRedirect(route('admin.subscriber-categories'));
    }

    // ==================== Testes de Edição ====================

    public function test_admin_can_access_edit_page(): void
    {
        $admin = User::factory()->admin()->create();
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->get("/admin/categorias-assinantes/{$category->id}/editar")
            ->assertOk()
            ->assertViewHas('category');
    }

    public function test_admin_can_update_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = SubscriberCategory::create([
            'name' => 'Antiga',
            'slug' => 'antiga',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->put("/admin/categorias-assinantes/{$category->id}", [
                'name' => 'Atualizada',
                'description' => 'Nova descrição',
                'active' => true,
            ])
            ->assertRedirect(route('admin.subscriber-categories'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('subscriber_categories', [
            'id' => $category->id,
            'name' => 'Atualizada',
            'slug' => 'atualizada',
        ]);
    }

    public function test_update_category_requires_name(): void
    {
        $admin = User::factory()->admin()->create();
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->put("/admin/categorias-assinantes/{$category->id}", [
                'name' => '',
            ])
            ->assertSessionHasErrors(['name']);
    }

    // ==================== Testes de Deleção ====================

    public function test_admin_can_delete_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->delete("/admin/categorias-assinantes/{$category->id}")
            ->assertRedirect(route('admin.subscriber-categories'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('subscriber_categories', [
            'id' => $category->id,
        ]);
    }

    public function test_delete_category_returns_success_message(): void
    {
        $admin = User::factory()->admin()->create();
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->delete("/admin/categorias-assinantes/{$category->id}")
            ->assertSessionHas('success');
    }
}
