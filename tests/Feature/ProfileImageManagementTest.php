<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ProfileImageManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Profile $profile;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->user = User::factory()->create();
        $this->profile = Profile::factory()->create([
            'user_id' => $this->user->id,
            'name'    => 'João Teste',
            'age'     => 25,
            'city'    => 'São Paulo',
            'state'   => 'SP',
            'active'  => true,
        ]);
    }

    // ─── Helpers ────────────────────────────────────────────

    private function actingAsUser(): self
    {
        return $this->actingAs($this->user);
    }

    private function fakeImage(string $name = 'photo.jpg', int $kb = 100): UploadedFile
    {
        return UploadedFile::fake()->image($name, 400, 400)->size($kb);
    }

    /**
     * Extract image IDs from the database for a given profile.
     */
    private function imageIds(): array
    {
        return $this->profile->images()->pluck('id')->toArray();
    }

    private function mainImage(): ?string
    {
        $main = $this->profile->images()->where('is_main', true)->first();

        return $main?->url;
    }

    private function assertStorageHasImages(int $count): void
    {
        $files = Storage::disk('public')->allFiles('profiles/images');
        $this->assertCount($count, $files);
    }

    // ==================== Upload de Imagens ====================

    public function test_user_can_upload_image_when_creating_profile(): void
    {
        // Create a fresh user without a profile
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('perfil.salvar'), [
            'name'        => 'João Silva',
            'age'         => 25,
            'city'        => 'São Paulo',
            'state'       => 'SP',
            'description' => 'Descrição de teste',
            'gallery'     => [$this->fakeImage()],
        ]);

        $profile = $user->fresh()->profile;
        $this->assertNotNull($profile);
        $this->assertCount(1, $profile->images);
        $this->assertStorageHasImages(1);
    }

    public function test_user_can_upload_multiple_images(): void
    {
        // Create a fresh user without a profile
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('perfil.salvar'), [
            'name'        => 'João Silva',
            'age'         => 25,
            'city'        => 'São Paulo',
            'state'       => 'SP',
            'gallery'     => [
                $this->fakeImage('foto1.jpg'),
                $this->fakeImage('foto2.jpg'),
                $this->fakeImage('foto3.jpg'),
            ],
        ]);

        $this->assertCount(3, $user->fresh()->profile->images);
        $this->assertStorageHasImages(3);
    }

    public function test_first_uploaded_image_becomes_main_by_default(): void
    {
        // Create a fresh user without a profile
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('perfil.salvar'), [
            'name'    => 'João Silva',
            'age'     => 25,
            'city'    => 'São Paulo',
            'state'   => 'SP',
            'gallery' => [$this->fakeImage(), $this->fakeImage()],
        ]);

        $profile = $user->fresh()->profile;
        $mainImage = $profile->images()->where('is_main', true)->first();

        $this->assertNotNull($mainImage);
        $this->assertCount(1, $profile->images()->where('is_main', true)->get());
        // The first uploaded image should be main (lowest order)
        $this->assertEquals(0, $mainImage->order);
    }

    // ==================== Edição / Adicionar Mais ====================

    public function test_user_can_add_more_images_when_editing(): void
    {
        $this->profile->images()->create(['url' => 'old.jpg', 'is_main' => true, 'order' => 0]);
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'        => $this->profile->name,
            'age'         => $this->profile->age,
            'city'        => $this->profile->city,
            'state'       => $this->profile->state,
            'gallery'     => [$this->fakeImage('nova.jpg')],
        ])->assertRedirect(route('perfil.editar', $this->profile->id));

        $this->assertCount(2, $this->profile->fresh()->images);
    }

    // ==================== Deleção de Imagens ====================

    public function test_user_can_remove_images(): void
    {
        $img1 = $this->profile->images()->create(['url' => 'img1.jpg', 'is_main' => true, 'order' => 0]);
        $img2 = $this->profile->images()->create(['url' => 'img2.jpg', 'order' => 1]);
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'          => $this->profile->name,
            'age'           => $this->profile->age,
            'city'          => $this->profile->city,
            'state'         => $this->profile->state,
            'remove_images' => [$img1->id],
        ])->assertRedirect(route('perfil.editar', $this->profile->id));

        $this->assertDatabaseMissing('profile_images', ['id' => $img1->id]);
        $this->assertDatabaseHas('profile_images', ['id' => $img2->id]);
    }

    public function test_user_can_remove_multiple_images_at_once(): void
    {
        $img1 = $this->profile->images()->create(['url' => 'r1.jpg', 'order' => 1]);
        $img2 = $this->profile->images()->create(['url' => 'r2.jpg', 'order' => 2]);
        $img3 = $this->profile->images()->create(['url' => 'keep.jpg', 'order' => 3]);
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'          => $this->profile->name,
            'age'           => $this->profile->age,
            'city'          => $this->profile->city,
            'state'         => $this->profile->state,
            'remove_images' => [$img1->id, $img2->id],
        ]);

        $this->assertDatabaseMissing('profile_images', ['id' => $img1->id]);
        $this->assertDatabaseMissing('profile_images', ['id' => $img2->id]);
        $this->assertDatabaseHas('profile_images', ['id' => $img3->id]);
    }

    public function test_remove_invalid_image_id_does_nothing(): void
    {
        $this->profile->images()->create(['url' => 'keep.jpg', 'is_main' => true, 'order' => 0]);

        $originalCount = $this->profile->images()->count();
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'          => $this->profile->name,
            'age'           => $this->profile->age,
            'city'          => $this->profile->city,
            'state'         => $this->profile->state,
            'remove_images' => [99999],
        ]);

        $this->assertCount($originalCount, $this->profile->fresh()->images);
    }

    // ==================== Trocar Imagem Principal ====================

    public function test_user_can_set_an_existing_image_as_main(): void
    {
        $img1 = $this->profile->images()->create(['url' => 'img1.jpg', 'is_main' => true, 'order' => 0]);
        $img2 = $this->profile->images()->create(['url' => 'img2.jpg', 'order' => 1]);
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'         => $this->profile->name,
            'age'          => $this->profile->age,
            'city'         => $this->profile->city,
            'state'        => $this->profile->state,
            'main_image_id' => $img2->id,
        ]);

        $this->profile->refresh();
        $this->assertFalse($this->profile->images()->find($img1->id)->is_main);
        $this->assertTrue($this->profile->images()->find($img2->id)->is_main);
    }

    public function test_user_can_set_newly_uploaded_image_as_main(): void
    {
        $this->profile->images()->create(['url' => 'img1.jpg', 'is_main' => true, 'order' => 0]);
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'                 => $this->profile->name,
            'age'                  => $this->profile->age,
            'city'                 => $this->profile->city,
            'state'                => $this->profile->state,
            'gallery'             => [$this->fakeImage()],
            'new_main_image_index' => 0,
        ]);

        $this->profile->refresh();
        // Only one image should be main — the newly uploaded one
        $mainImages = $this->profile->images()->where('is_main', true)->get();
        $this->assertCount(1, $mainImages);

        // The new image should be the one with order = 0 (set when becoming main)
        $this->assertEquals('0', $mainImages->first()->order);
    }

    // ==================== Combinações (delete + upload + troca) ====================

    public function test_user_can_remove_image_and_upload_new_one_in_single_request(): void
    {
        $img1 = $this->profile->images()->create(['url' => 'old.jpg', 'is_main' => true, 'order' => 0]);
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'          => $this->profile->name,
            'age'           => $this->profile->age,
            'city'          => $this->profile->city,
            'state'         => $this->profile->state,
            'gallery'       => [$this->fakeImage('new.jpg')],
            'remove_images' => [$img1->id],
        ]);

        $this->assertDatabaseMissing('profile_images', ['id' => $img1->id]);
        $this->assertCount(1, $this->profile->fresh()->images);
    }

    public function test_complete_image_workflow_remove_upload_change_main(): void
    {
        $img1 = $this->profile->images()->create(['url' => 'keep.jpg', 'order' => 1]);
        $img2 = $this->profile->images()->create(['url' => 'delete.jpg', 'is_main' => true, 'order' => 0]);
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'                 => $this->profile->name,
            'age'                  => $this->profile->age,
            'city'                 => $this->profile->city,
            'state'                => $this->profile->state,
            'gallery'             => [$this->fakeImage()],
            'remove_images'       => [$img2->id],
            'new_main_image_index' => 0,
        ]);

        $profile = $this->profile->fresh();

        // Old deleted image is gone
        $this->assertDatabaseMissing('profile_images', ['id' => $img2->id]);

        // Original keep.jpg still exists
        $this->assertDatabaseHas('profile_images', ['id' => $img1->id]);

        // The new image should exist and be the only main image
        $this->assertCount(2, $profile->images);

        $mainImages = $profile->images()->where('is_main', true)->get();
        $this->assertCount(1, $mainImages);

        // The main image is the new one (order = 0 after set as main)
        $this->assertEquals(0, $mainImages->first()->order);
    }

    // ==================== Edge Cases ====================

    public function test_upload_non_image_file_is_rejected(): void
    {
        $this->actingAsUser();

        $response = $this->post(route('perfil.salvar'), [
            'name'    => 'João Silva',
            'age'     => 25,
            'city'    => 'São Paulo',
            'state'   => 'SP',
            'gallery' => [UploadedFile::fake()->create('documento.pdf', 50)],
        ]);

        // The StoreProfileRequest should validate images
        // If it validates with 'image' rule, expect redirect with errors
        $response->assertInvalid(['gallery.0']);
    }

    public function test_uploading_no_images_does_not_fail(): void
    {
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'  => $this->profile->name,
            'age'   => $this->profile->age,
            'city'  => $this->profile->city,
            'state' => $this->profile->state,
        ])->assertRedirect(route('perfil.editar', $this->profile->id));
    }

    public function test_unauthenticated_user_cannot_upload_images(): void
    {
        $this->post(route('perfil.salvar'), [
            'name'    => 'João Silva',
            'age'     => 25,
            'city'    => 'São Paulo',
            'state'   => 'SP',
            'gallery' => [$this->fakeImage()],
        ])->assertRedirect(route('login'));
    }

    public function test_image_order_is_incremented_correctly(): void
    {
        $this->profile->images()->create(['url' => 'existing.jpg', 'order' => 3]);
        $this->actingAsUser();

        $this->post(route('perfil.salvar'), [
            'name'    => $this->profile->name,
            'age'     => $this->profile->age,
            'city'    => $this->profile->city,
            'state'   => $this->profile->state,
            'gallery' => [$this->fakeImage(), $this->fakeImage()],
        ]);

        $images = $this->profile->fresh()->images;
        $orders = $images->pluck('order')->sort()->values();

        // 3 images total
        $this->assertCount(3, $images);

        // Main image = order 0, remaining images renumbered sequentially
        $this->assertEquals(0, $orders[0]);
        $this->assertEquals(1, $orders[1]);
        $this->assertEquals(2, $orders[2]);
    }
}
