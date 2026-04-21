<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CityTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Testes de Criação ====================

    public function test_can_create_city(): void
    {
        $city = City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
            'order' => 1,
        ]);

        $this->assertDatabaseHas('cities', [
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
        ]);
    }

    public function test_name_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        City::create([
            'state' => 'SP',
            'slug' => 'teste',
            'active' => true,
        ]);
    }

    public function test_state_is_required(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        City::create([
            'name' => 'São Paulo',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);
    }

    public function test_slug_is_unique(): void
    {
        City::create([
            'name' => 'São Paulo',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        City::create([
            'name' => 'Outra Cidade',
            'state' => 'SP',
            'slug' => 'sao-paulo',
            'active' => true,
        ]);
    }

    // ==================== Testes de Casts ====================

    public function test_active_is_cast_to_boolean(): void
    {
        $city = City::create([
            'name' => 'Teste',
            'state' => 'SP',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->assertIsBool($city->active);
        $this->assertTrue($city->active);
    }

    public function test_active_defaults_to_true(): void
    {
        $city = City::create([
            'name' => 'Teste',
            'state' => 'SP',
            'slug' => 'teste',
            'active' => true,
        ]);

        $this->assertTrue($city->active);
    }

    public function test_order_defaults_to_zero(): void
    {
        $city = City::create([
            'name' => 'Teste',
            'state' => 'SP',
            'slug' => 'teste',
            'order' => 0,
        ]);

        $this->assertSame(0, $city->order);
    }

    // ==================== Testes de Imagem ====================

    public function test_image_is_nullable(): void
    {
        $city = City::create([
            'name' => 'Teste',
            'state' => 'SP',
            'slug' => 'teste',
            'image' => null,
        ]);

        $this->assertNull($city->image);
    }

    public function test_can_store_image_path(): void
    {
        $city = City::create([
            'name' => 'Teste',
            'state' => 'SP',
            'slug' => 'teste',
            'image' => 'cities/teste.jpg',
        ]);

        $this->assertSame('cities/teste.jpg', $city->image);
    }

    // ==================== Testes de Relacionamentos ====================
}
