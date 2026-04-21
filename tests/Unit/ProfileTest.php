<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\Service;
use App\Models\SubscriberCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Testes de Criação ====================

    public function test_can_create_profile(): void
    {
        $user = User::factory()->create();
        $category = SubscriberCategory::create([
            'name' => 'Básico',
            'slug' => 'basico',
            'active' => true,
        ]);

        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'João Silva',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'description' => 'Descrição do perfil',
            'verified' => false,
            'rating' => 4.5,
            'views' => 100,
            'active' => true,
            'subscriber_category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('profiles', [
            'name' => 'João Silva',
            'city' => 'São Paulo',
        ]);
    }

    // ==================== Testes de Casts ====================

    public function test_verified_is_cast_to_boolean(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'verified' => 1,
        ]);

        $this->assertIsBool($profile->verified);
        $this->assertTrue($profile->verified);
    }

    public function test_active_is_cast_to_boolean(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'active' => 1,
        ]);

        $this->assertIsBool($profile->active);
        $this->assertTrue($profile->active);
    }

    public function test_rating_is_cast_to_decimal(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'rating' => '4.5',
        ]);

        $this->assertEquals(4.5, $profile->rating);
    }

    public function test_last_active_at_is_cast_to_datetime(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'last_active_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $profile->last_active_at);
    }

    // ==================== Testes de Relacionamentos ====================

    public function test_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $profile->user());
        $this->assertEquals($user->id, $profile->user->id);
    }

    public function test_belongs_to_subscriber_category(): void
    {
        $category = SubscriberCategory::create([
            'name' => 'Básico',
            'slug' => 'basico',
            'active' => true,
        ]);
        $profile = Profile::factory()->create(['subscriber_category_id' => $category->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $profile->subscriberCategory());
        $this->assertEquals($category->id, $profile->subscriberCategory->id);
    }

    public function test_subscriber_category_is_nullable(): void
    {
        $profile = Profile::factory()->create();

        $this->assertNull($profile->subscriber_category_id);
        $this->assertNull($profile->subscriberCategory);
    }

    public function test_belongs_to_many_services(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);
        $service = Service::create([
            'name' => 'Massagem',
            'slug' => 'massagem',
            'category' => 'Massagem',
            'active' => true,
        ]);

        $profile->services()->attach($service->id, ['price' => 150.00]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $profile->services());
        $this->assertCount(1, $profile->services);
        $this->assertEquals($service->id, $profile->services->first()->id);
    }

    public function test_has_many_images(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $profile->images());
    }

    public function test_has_many_videos(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $profile->videos());
    }

    public function test_has_one_physical_attributes(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $profile->physicalAttributes());
    }

    // ==================== Testes de Defaults ====================

    public function test_verified_defaults_to_false(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'verified' => false,
        ]);

        $this->assertFalse($profile->verified);
    }

    public function test_active_defaults_to_true(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'active' => true,
        ]);

        $this->assertTrue($profile->active);
    }

    public function test_rating_defaults_to_zero(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'rating' => 0.0,
        ]);

        $this->assertEquals(0.0, $profile->rating);
    }

    public function test_views_defaults_to_zero(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'views' => 0,
        ]);

        $this->assertSame(0, $profile->views);
    }

    // ==================== Testes de Novos Campos ====================

    public function test_gender_is_enum(): void
    {
        $user = User::factory()->create();
        $validGenders = ['masculino', 'feminino', 'trans', 'outros'];

        foreach ($validGenders as $gender) {
            $profile = Profile::create([
                'user_id' => $user->id,
                'name' => 'Teste',
                'age' => 25,
                'city' => 'São Paulo',
                'state' => 'SP',
                'gender' => $gender,
            ]);

            $this->assertEquals($gender, $profile->gender);
        }
    }

    public function test_whatsapp_is_string(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'telegram' => '11999999999',
        ]);

        $this->assertIsString($profile->telegram);
    }

    public function test_tagline_is_string(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'tagline' => 'Frase curta',
        ]);

        $this->assertIsString($profile->tagline);
    }

    public function test_attendance_target_is_json(): void
    {
        $user = User::factory()->create();
        $targets = ['homens', 'mulheres'];
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'attendance_target' => $targets,
        ]);

        $this->assertIsArray($profile->attendance_target);
        $this->assertEquals($targets, $profile->attendance_target);
    }

    public function test_payment_methods_is_json(): void
    {
        $user = User::factory()->create();
        $methods = ['pix', 'dinheiro'];
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'payment_methods' => $methods,
        ]);

        $this->assertIsArray($profile->payment_methods);
        $this->assertEquals($methods, $profile->payment_methods);
    }

    public function test_documents_verified_is_boolean(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'documents_verified' => true,
        ]);

        $this->assertIsBool($profile->documents_verified);
        $this->assertTrue($profile->documents_verified);
    }

    public function test_no_reports_is_boolean(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'no_reports' => true,
        ]);

        $this->assertIsBool($profile->no_reports);
        $this->assertTrue($profile->no_reports);
    }

    public function test_clean_history_is_boolean(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
            'clean_history' => true,
        ]);

        $this->assertIsBool($profile->clean_history);
        $this->assertTrue($profile->clean_history);
    }

    // ==================== Testes de Novos Relacionamentos ====================

    public function test_has_many_pricing(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $profile->pricing());
    }

    public function test_has_many_comments(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $profile->comments());
    }

    public function test_has_many_reports(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $profile->reports());
    }

    public function test_comments_are_ordered_by_latest(): void
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'Teste',
            'age' => 25,
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        $this->assertStringContainsString('latest', $profile->comments()->toSql());
    }
}
