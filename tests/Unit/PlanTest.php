<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PlanTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Testes de Criação ====================

    public function test_can_create_plan(): void
    {
        $plan = Plan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'price' => 99.90,
            'description' => 'Plano premium com todos os recursos',
            'features' => ['3 perfis', 'ilimitado fotos', 'destaque'],
            'active' => true,
        ]);

        $this->assertDatabaseHas('plans', [
            'name' => 'Premium',
            'slug' => 'premium',
            'price' => 99.90,
        ]);
    }

    public function test_name_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Plan::create([
            'slug' => 'teste',
            'price' => 0,
            'active' => true,
        ]);
    }

    public function test_slug_is_unique(): void
    {
        Plan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'price' => 99.90,
            'active' => true,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Plan::create([
            'name' => 'Outro',
            'slug' => 'premium',
            'price' => 49.90,
            'active' => true,
        ]);
    }

    // ==================== Testes de Casts ====================

    public function test_active_is_cast_to_boolean(): void
    {
        $plan = Plan::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'price' => 0,
            'active' => 1,
        ]);

        $this->assertIsBool($plan->active);
        $this->assertTrue($plan->active);
    }

    public function test_active_defaults_to_true(): void
    {
        $plan = Plan::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'price' => 0,
            'active' => true,
        ]);

        $this->assertTrue($plan->active);
    }

    public function test_features_is_cast_to_array(): void
    {
        $plan = Plan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'price' => 99.90,
            'features' => ['3 perfis', 'ilimitado fotos'],
            'active' => true,
        ]);

        $this->assertIsArray($plan->features);
        $this->assertCount(2, $plan->features);
    }

    // ==================== Testes de Price ====================

    public function test_price_can_be_zero(): void
    {
        $plan = Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'active' => true,
        ]);

        $this->assertEquals(0.0, $plan->price);
    }

    public function test_price_can_be_decimal(): void
    {
        $plan = Plan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'price' => 99.90,
            'active' => true,
        ]);

        $this->assertEquals(99.90, $plan->price);
    }

    // ==================== Testes de UpdateOrCreate ====================

    public function test_update_or_create_creates_new_plan(): void
    {
        Plan::updateOrCreate(
            ['slug' => 'novo-plano'],
            [
                'name' => 'Novo Plano',
                'price' => 49.90,
                'description' => 'Descrição',
                'active' => true,
            ]
        );

        $this->assertDatabaseHas('plans', [
            'slug' => 'novo-plano',
            'name' => 'Novo Plano',
        ]);
    }

    public function test_update_or_create_updates_existing_plan(): void
    {
        Plan::create([
            'name' => 'Antigo',
            'slug' => 'antigo',
            'price' => 29.90,
            'active' => true,
        ]);

        Plan::updateOrCreate(
            ['slug' => 'antigo'],
            ['name' => 'Atualizado', 'price' => 39.90]
        );

        $this->assertDatabaseHas('plans', [
            'slug' => 'antigo',
            'name' => 'Atualizado',
            'price' => 39.90,
        ]);
    }

    // ==================== Testes de Defaults ====================

    public function test_features_defaults_to_empty_array(): void
    {
        $plan = Plan::create([
            'name' => 'Teste',
            'slug' => 'teste',
            'price' => 0,
            'features' => [],
        ]);

        $this->assertIsArray($plan->features);
        $this->assertEmpty($plan->features);
    }
}
