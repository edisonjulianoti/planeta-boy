<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\Service;
use App\Models\SubscriberCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ServiceTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Testes de Criação ====================

    public function test_can_create_service(): void
    {
        $service = Service::create([
            'name' => 'Massagem Erótica',
            'slug' => 'massagem-erotica',
            'category' => 'Massagem',
            'active' => true,
        ]);

        $this->assertDatabaseHas('services', [
            'name' => 'Massagem Erótica',
            'slug' => 'massagem-erotica',
            'category' => 'Massagem',
        ]);
    }

    public function test_name_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Service::create([
            'slug' => 'teste',
            'category' => 'Teste',
            'active' => true,
        ]);
    }

    public function test_slug_is_unique(): void
    {
        Service::create([
            'name' => 'Massagem',
            'slug' => 'massagem',
            'category' => 'Massagem',
            'active' => true,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Service::create([
            'name' => 'Outro',
            'slug' => 'massagem',
            'category' => 'Massagem',
            'active' => true,
        ]);
    }

    // ==================== Testes de Casts ====================

    public function test_active_is_cast_to_boolean(): void
    {
        $service = Service::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'category' => 'Teste',
            'active' => 1,
        ]);

        $this->assertIsBool($service->active);
        $this->assertTrue($service->active);
    }

    public function test_active_defaults_to_true(): void
    {
        $service = Service::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'category' => 'Teste',
            'active' => true,
        ]);

        $this->assertTrue($service->active);
    }

    // ==================== Testes de Relacionamentos ====================

    public function test_belongs_to_many_profiles(): void
    {
        $service = Service::create([
            'name' => 'Massagem',
            'slug' => 'massagem',
            'category' => 'Massagem',
            'active' => true,
        ]);

        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);
        $service->profiles()->attach($profile->id, ['price' => 150.00]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $service->profiles());
        $this->assertCount(1, $service->profiles);
        $this->assertEquals($profile->id, $service->profiles->first()->id);
    }

    public function test_belongs_to_many_subscriber_categories(): void
    {
        $service = Service::create([
            'name' => 'Boquete',
            'slug' => 'boquete',
            'category' => 'Sexo Oral',
            'active' => true,
        ]);

        $category = SubscriberCategory::create([
            'name' => 'Básico',
            'slug' => 'basico',
            'active' => true,
        ]);

        $service->subscriberCategories()->attach($category->id);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $service->subscriberCategories());
        $this->assertCount(1, $service->subscriberCategories);
        $this->assertEquals($category->id, $service->subscriberCategories->first()->id);
    }

    public function test_relationship_with_subscriber_categories_uses_pivot_table(): void
    {
        $service = Service::create([
            'name' => 'Boquete',
            'slug' => 'boquete',
            'category' => 'Sexo Oral',
            'active' => true,
        ]);

        $category = SubscriberCategory::create([
            'name' => 'Básico',
            'slug' => 'basico',
            'active' => true,
        ]);

        $service->subscriberCategories()->attach($category->id);

        $this->assertDatabaseHas('subscriber_category_restricted_services', [
            'service_id' => $service->id,
            'subscriber_category_id' => $category->id,
        ]);
    }

    // ==================== Testes de UpdateOrCreate ====================

    public function test_update_or_create_creates_new_service(): void
    {
        Service::updateOrCreate(
            ['slug' => 'novo-servico'],
            [
                'name' => 'Novo Serviço',
                'category' => 'Categoria',
                'active' => true,
            ]
        );

        $this->assertDatabaseHas('services', [
            'slug' => 'novo-servico',
            'name' => 'Novo Serviço',
        ]);
    }

    public function test_update_or_create_updates_existing_service(): void
    {
        Service::create([
            'name' => 'Antigo',
            'slug' => 'antigo',
            'category' => 'Categoria',
            'active' => true,
        ]);

        Service::updateOrCreate(
            ['slug' => 'antigo'],
            ['name' => 'Atualizado']
        );

        $this->assertDatabaseHas('services', [
            'slug' => 'antigo',
            'name' => 'Atualizado',
        ]);
    }
}
