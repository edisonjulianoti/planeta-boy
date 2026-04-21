<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Testes de Autorização ====================

    public function test_guest_cannot_access_users_list(): void
    {
        $this->get('/admin/usuarios')
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_users_list(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/usuarios')
            ->assertForbidden();
    }

    public function test_admin_can_access_users_list(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/usuarios')
            ->assertOk();
    }

    public function test_guest_cannot_access_user_show(): void
    {
        $user = User::factory()->create();

        $this->get("/admin/usuarios/{$user->id}")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_user_show(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $targetUser = User::factory()->create();

        $this->actingAs($user)
            ->get("/admin/usuarios/{$targetUser->id}")
            ->assertForbidden();
    }

    public function test_guest_cannot_access_user_edit(): void
    {
        $user = User::factory()->create();

        $this->get("/admin/usuarios/{$user->id}/edit")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_user_edit(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $targetUser = User::factory()->create();

        $this->actingAs($user)
            ->get("/admin/usuarios/{$targetUser->id}/edit")
            ->assertForbidden();
    }

    public function test_guest_cannot_toggle_admin(): void
    {
        $user = User::factory()->create();

        $this->post("/admin/usuarios/{$user->id}/toggle-admin")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_toggle_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $targetUser = User::factory()->create();

        $this->actingAs($user)
            ->post("/admin/usuarios/{$targetUser->id}/toggle-admin")
            ->assertForbidden();
    }

    public function test_guest_cannot_toggle_blocked(): void
    {
        $user = User::factory()->create();

        $this->post("/admin/usuarios/{$user->id}/toggle-blocked")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_toggle_blocked(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $targetUser = User::factory()->create();

        $this->actingAs($user)
            ->post("/admin/usuarios/{$targetUser->id}/toggle-blocked")
            ->assertForbidden();
    }

    public function test_guest_cannot_delete_user(): void
    {
        $user = User::factory()->create();

        $this->delete("/admin/usuarios/{$user->id}")
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_delete_user(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $targetUser = User::factory()->create();

        $this->actingAs($user)
            ->delete("/admin/usuarios/{$targetUser->id}")
            ->assertForbidden();
    }

    // ==================== Testes de Listagem com Filtros ====================

    public function test_users_list_displays_users(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(5)->create();

        $this->actingAs($admin)
            ->get('/admin/usuarios')
            ->assertOk()
            ->assertViewHas('users');
    }

    public function test_search_by_name(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['name' => 'João Silva']);
        User::factory()->create(['name' => 'Maria Santos']);

        $this->actingAs($admin)
            ->get('/admin/usuarios?search=João')
            ->assertOk()
            ->assertViewHas('users')
            ->assertSee('João Silva')
            ->assertDontSee('Maria Santos');
    }

    public function test_search_by_email(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['email' => 'joao@teste.com']);
        User::factory()->create(['email' => 'maria@teste.com']);

        $this->actingAs($admin)
            ->get('/admin/usuarios?search=joao@teste.com')
            ->assertOk()
            ->assertViewHas('users')
            ->assertSee('joao@teste.com')
            ->assertDontSee('maria@teste.com');
    }

    public function test_filter_by_plan(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->premium()->create();
        User::factory()->create(['plan' => 'free']);

        $this->actingAs($admin)
            ->get('/admin/usuarios?plan=premium')
            ->assertOk()
            ->assertViewHas('users');
    }

    public function test_filter_by_admin_status(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->admin()->create();
        User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin)
            ->get('/admin/usuarios?is_admin=1')
            ->assertOk()
            ->assertViewHas('users');
    }

    public function test_filter_by_blocked_status(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->blocked()->create();
        User::factory()->create(['blocked' => false]);

        $this->actingAs($admin)
            ->get('/admin/usuarios?blocked=1')
            ->assertOk()
            ->assertViewHas('users');
    }

    public function test_combined_filters(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->premium()->create(['name' => 'João Silva', 'blocked' => true]);
        User::factory()->create(['name' => 'Maria Santos', 'plan' => 'free']);

        $this->actingAs($admin)
            ->get('/admin/usuarios?search=João&plan=premium&blocked=1')
            ->assertOk()
            ->assertViewHas('users');
    }

    public function test_pagination_works(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(25)->create();

        $this->actingAs($admin)
            ->get('/admin/usuarios')
            ->assertOk()
            ->assertViewHas('users');
    }

    // ==================== Testes de Visualização e Edição ====================

    public function test_admin_can_view_user_details(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->get("/admin/usuarios/{$user->id}")
            ->assertOk()
            ->assertViewHas('user');
    }

    public function test_user_details_loads_relationships(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->get("/admin/usuarios/{$user->id}")
            ->assertOk();
    }

    public function test_admin_can_access_edit_form(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->get("/admin/usuarios/{$user->id}/edit")
            ->assertOk()
            ->assertViewHas('user');
    }

    public function test_update_user_with_valid_data(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->put("/admin/usuarios/{$user->id}", [
                'name' => 'Novo Nome',
                'email' => 'novo@email.com',
                'phone' => '11999999999',
                'bio' => 'Nova bio',
            ])
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Novo Nome',
            'email' => 'novo@email.com',
        ]);
    }

    public function test_update_user_requires_name(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->put("/admin/usuarios/{$user->id}", [
                'name' => '',
                'email' => 'novo@email.com',
            ])
            ->assertSessionHasErrors(['name']);
    }

    public function test_update_user_requires_email(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->put("/admin/usuarios/{$user->id}", [
                'name' => 'Nome',
                'email' => '',
            ])
            ->assertSessionHasErrors(['email']);
    }

    public function test_update_user_requires_unique_email(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create(['email' => 'existente@email.com']);

        $this->actingAs($admin)
            ->put("/admin/usuarios/{$user->id}", [
                'name' => 'Nome',
                'email' => 'existente@email.com',
            ])
            ->assertSessionHasErrors(['email']);
    }

    public function test_update_user_allows_optional_fields(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->put("/admin/usuarios/{$user->id}", [
                'name' => 'Nome',
                'email' => 'novo@email.com',
                'phone' => null,
                'bio' => null,
            ])
            ->assertRedirect(route('admin.users'));
    }

    // ==================== Testes de Toggle Admin ====================

    public function test_admin_can_promote_user_to_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$user->id}/toggle-admin")
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => true,
        ]);
    }

    public function test_admin_can_demote_admin_to_user(): void
    {
        $admin = User::factory()->admin()->create();
        $otherAdmin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$otherAdmin->id}/toggle-admin")
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $otherAdmin->id,
            'is_admin' => false,
        ]);
    }

    public function test_cannot_remove_last_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$admin->id}/toggle-admin")
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'is_admin' => true,
        ]);
    }

    public function test_toggle_admin_updates_database(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$user->id}/toggle-admin");

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => true,
        ]);
    }

    public function test_toggle_admin_returns_success_message(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$user->id}/toggle-admin")
            ->assertSessionHas('success');
    }

    // ==================== Testes de Toggle Blocked ====================

    public function test_admin_can_block_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['blocked' => false]);

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$user->id}/toggle-blocked")
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'blocked' => true,
        ]);
    }

    public function test_admin_can_unblock_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->blocked()->create();

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$user->id}/toggle-blocked")
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'blocked' => false,
        ]);
    }

    public function test_cannot_block_self(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$admin->id}/toggle-blocked")
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'blocked' => false,
        ]);
    }

    public function test_cannot_block_last_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$admin->id}/toggle-blocked")
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_toggle_blocked_updates_database(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['blocked' => false]);

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$user->id}/toggle-blocked");

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'blocked' => true,
        ]);
    }

    public function test_toggle_blocked_returns_success_message(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['blocked' => false]);

        $this->actingAs($admin)
            ->post("/admin/usuarios/{$user->id}/toggle-blocked")
            ->assertSessionHas('success');
    }

    // ==================== Testes de Deleção ====================

    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->delete("/admin/usuarios/{$user->id}")
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_cannot_delete_self(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete("/admin/usuarios/{$admin->id}")
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_cannot_delete_last_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete("/admin/usuarios/{$admin->id}")
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_delete_user_removes_from_database(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->delete("/admin/usuarios/{$user->id}");

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_delete_user_returns_success_message(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->delete("/admin/usuarios/{$user->id}")
            ->assertSessionHas('success');
    }
}
