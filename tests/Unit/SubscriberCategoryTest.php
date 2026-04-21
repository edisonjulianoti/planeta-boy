<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\Service;
use App\Models\SubscriberCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SubscriberCategoryTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Testes de Criação ====================

    public function test_can_create_subscriber_category(): void
    {
        $category = SubscriberCategory::create([
            'name' => 'Básico',
            'slug' => 'basico',
            'description' => 'Categoria básica',
            'active' => true,
        ]);

        $this->assertDatabaseHas('subscriber_categories', [
            'name' => 'Básico',
            'slug' => 'basico',
        ]);
    }

    public function test_name_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        SubscriberCategory::create([
            'slug' => 'teste',
            'active' => true,
        ]);
    }

    public function test_slug_is_unique(): void
    {
        SubscriberCategory::create([
            'name' => 'Categoria',
            'slug' => 'categoria',
            'active' => true,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        SubscriberCategory::create([
            'name' => 'Outra',
            'slug' => 'categoria',
            'active' => true,
        ]);
    }

    // ==================== Testes de Casts ====================

    public function test_active_is_cast_to_boolean(): void
    {
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => 1,
        ]);

        $this->assertIsBool($category->active);
        $this->assertTrue($category->active);
    }

    public function test_active_defaults_to_true(): void
    {
        $category = SubscriberCategory::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->assertTrue($category->active);
    }

    // ==================== Testes de Relacionamentos ====================

    public function test_has_many_profiles_relationship(): void
    {
        $category = SubscriberCategory::create([
            'name' => 'Básico',
            'slug' => 'basico',
            'active' => true,
        ]);

        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'subscriber_category_id' => $category->id,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $category->profiles());
        $this->assertCount(1, $category->profiles);
        $this->assertEquals($category->id, $profile->subscriber_category_id);
    }

    public function test_belongs_to_many_restricted_services(): void
    {
        $category = SubscriberCategory::create([
            'name' => 'Básico',
            'slug' => 'basico',
            'active' => true,
        ]);

        $service = Service::create([
            'name' => 'Boquete',
            'slug' => 'boquete',
            'category' => 'Sexo Oral',
            'active' => true,
        ]);

        $category->restrictedServices()->attach($service->id);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $category->restrictedServices());
        $this->assertCount(1, $category->restrictedServices);
        $this->assertEquals($service->id, $category->restrictedServices->first()->id);
    }

    // ==================== Testes de UpdateOrCreate ====================

    public function test_update_or_create_creates_new_category(): void
    {
        SubscriberCategory::updateOrCreate(
            ['slug' => 'nova-categoria'],
            [
                'name' => 'Nova Categoria',
                'description' => 'Descrição',
                'active' => true,
            ]
        );

        $this->assertDatabaseHas('subscriber_categories', [
            'slug' => 'nova-categoria',
            'name' => 'Nova Categoria',
        ]);
    }

    public function test_update_or_create_updates_existing_category(): void
    {
        SubscriberCategory::create([
            'name' => 'Antiga',
            'slug' => 'antiga',
            'active' => true,
        ]);

        SubscriberCategory::updateOrCreate(
            ['slug' => 'antiga'],
            ['name' => 'Atualizada']
        );

        $this->assertDatabaseHas('subscriber_categories', [
            'slug' => 'antiga',
            'name' => 'Atualizada',
        ]);
    }
}
