<?php

use App\Models\Profile;
use App\Models\ProfileVerificationDocument;
use App\Models\User;
use App\Notifications\VerificationNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    Notification::fake();

    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->user = User::factory()->create(['email_verified_at' => now()]);
    $this->profile = Profile::factory()->create([
        'user_id'             => $this->user->id,
        'verification_status' => 'none',
    ]);
});

// ─── Página de verificação ─────────────────────────────────

test('user can view verification page', function () {
    $this->actingAs($this->user)
        ->get(route('perfil.verificacao'))
        ->assertOk()
        ->assertSee('Verificação de Perfil');
});

test('unauthenticated user cannot access verification page', function () {
    $this->get(route('perfil.verificacao'))
        ->assertRedirect(route('login'));
});

test('user without profile gets 404 on verification page', function () {
    $userNoProfile = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($userNoProfile)
        ->get(route('perfil.verificacao'))
        ->assertNotFound();
});

// ─── Upload de documento ────────────────────────────────────

test('user can upload verification document', function () {
    $file = UploadedFile::fake()->image('rg.jpg', 800, 600);

    $this->actingAs($this->user)
        ->post(route('perfil.verificacao.upload'), [
            'document_type' => 'rg',
            'document'      => $file,
        ])
        ->assertRedirect(route('perfil.verificacao'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('profile_verification_documents', [
        'profile_id'    => $this->profile->id,
        'document_type' => 'rg',
        'status'        => 'pending',
    ]);

    $this->profile->refresh();
    expect($this->profile->verification_status)->toBe('pending');
});

test('user can upload selfie document', function () {
    $file = UploadedFile::fake()->image('selfie.jpg');

    $this->actingAs($this->user)
        ->post(route('perfil.verificacao.upload'), [
            'document_type' => 'selfie',
            'document'      => $file,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('profile_verification_documents', [
        'profile_id'    => $this->profile->id,
        'document_type' => 'selfie',
        'status'        => 'pending',
    ]);
});

test('document upload requires valid document_type', function () {
    $file = UploadedFile::fake()->image('doc.jpg');

    $this->actingAs($this->user)
        ->post(route('perfil.verificacao.upload'), [
            'document_type' => 'passaporte',
            'document'      => $file,
        ])
        ->assertSessionHasErrors('document_type');
});

test('document upload requires file', function () {
    $this->actingAs($this->user)
        ->post(route('perfil.verificacao.upload'), [
            'document_type' => 'rg',
        ])
        ->assertSessionHasErrors('document');
});

test('document upload validates file type', function () {
    $file = UploadedFile::fake()->create('doc.txt', 100);

    $this->actingAs($this->user)
        ->post(route('perfil.verificacao.upload'), [
            'document_type' => 'rg',
            'document'      => $file,
        ])
        ->assertSessionHasErrors('document');
});

test('document upload validates max file size', function () {
    $file = UploadedFile::fake()->create('doc.jpg', 11240); // > 10MB

    $this->actingAs($this->user)
        ->post(route('perfil.verificacao.upload'), [
            'document_type' => 'rg',
            'document'      => $file,
        ])
        ->assertSessionHasErrors('document');
});

test('user cannot upload document if already pending', function () {
    $this->profile->update(['verification_status' => 'pending']);

    $file = UploadedFile::fake()->image('rg.jpg');

    $this->actingAs($this->user)
        ->post(route('perfil.verificacao.upload'), [
            'document_type' => 'rg',
            'document'      => $file,
        ])
        ->assertRedirect()
        ->assertSessionHas('error');
});

test('user can upload document again after rejection', function () {
    $this->profile->update(['verification_status' => 'rejected']);

    $file = UploadedFile::fake()->image('cnh.jpg');

    $this->actingAs($this->user)
        ->post(route('perfil.verificacao.upload'), [
            'document_type' => 'cnh',
            'document'      => $file,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->profile->refresh();
    expect($this->profile->verification_status)->toBe('pending');
});

// ─── Remover documento ──────────────────────────────────────

test('user can remove pending document', function () {
    $doc = ProfileVerificationDocument::create([
        'profile_id'    => $this->profile->id,
        'document_type' => 'rg',
        'file_path'     => 'verificacao/test.jpg',
        'status'        => 'pending',
        'submitted_at'  => now(),
    ]);

    $this->actingAs($this->user)
        ->delete(route('perfil.verificacao.documento.destroy', $doc))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('profile_verification_documents', ['id' => $doc->id]);
});

test('user cannot remove approved document', function () {
    $doc = ProfileVerificationDocument::create([
        'profile_id'    => $this->profile->id,
        'document_type' => 'rg',
        'file_path'     => 'verificacao/test.jpg',
        'status'        => 'approved',
        'submitted_at'  => now(),
    ]);

    $this->actingAs($this->user)
        ->delete(route('perfil.verificacao.documento.destroy', $doc))
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->assertDatabaseHas('profile_verification_documents', ['id' => $doc->id]);
});

// ─── Admin: fila de verificação ─────────────────────────────

test('admin can view verification queue', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.verificacoes'))
        ->assertOk();
});

test('non-admin cannot view verification queue', function () {
    $this->actingAs($this->user)
        ->get(route('admin.verificacoes'))
        ->assertForbidden();
});

test('admin can view pending documents in queue', function () {
    ProfileVerificationDocument::create([
        'profile_id'    => $this->profile->id,
        'document_type' => 'rg',
        'file_path'     => 'test.jpg',
        'status'        => 'pending',
        'submitted_at'  => now(),
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.verificacoes', ['status' => 'pending']))
        ->assertOk()
        ->assertSee($this->profile->name);
});

test('admin can view review page', function () {
    $doc = ProfileVerificationDocument::create([
        'profile_id'    => $this->profile->id,
        'document_type' => 'rg',
        'file_path'     => 'test.jpg',
        'status'        => 'pending',
        'submitted_at'  => now(),
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.verificacoes.show', $doc))
        ->assertOk()
        ->assertSee('Revisar Documento');
});

// ─── Admin: aprovar ─────────────────────────────────────────

test('admin can approve document', function () {
    Notification::fake();

    $doc = ProfileVerificationDocument::create([
        'profile_id'    => $this->profile->id,
        'document_type' => 'rg',
        'file_path'     => 'test.jpg',
        'status'        => 'pending',
        'submitted_at'  => now(),
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.verificacoes.approve', $doc))
        ->assertRedirect()
        ->assertSessionHas('success');

    $doc->refresh();
    expect($doc->status)->toBe('approved');
    expect($doc->reviewed_by)->toBe($this->admin->id);

    $this->profile->refresh();
    expect($this->profile->verification_status)->toBe('approved');
    expect($this->profile->documents_verified)->toBeTrue();

    Notification::assertSentTo($this->user, VerificationNotification::class);
});

test('admin cannot approve already processed document', function () {
    $doc = ProfileVerificationDocument::create([
        'profile_id'    => $this->profile->id,
        'document_type' => 'rg',
        'file_path'     => 'test.jpg',
        'status'        => 'approved',
        'submitted_at'  => now(),
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.verificacoes.approve', $doc))
        ->assertRedirect()
        ->assertSessionHas('error');
});

// ─── Admin: rejeitar ────────────────────────────────────────

test('admin can reject document with reason', function () {
    Notification::fake();

    $doc = ProfileVerificationDocument::create([
        'profile_id'    => $this->profile->id,
        'document_type' => 'rg',
        'file_path'     => 'test.jpg',
        'status'        => 'pending',
        'submitted_at'  => now(),
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.verificacoes.reject', $doc), [
            'rejection_reason' => 'Documento ilegível, por favor envie uma foto com melhor qualidade.',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $doc->refresh();
    expect($doc->status)->toBe('rejected');
    expect($doc->rejection_reason)->toContain('ilegível');
    expect($doc->reviewed_by)->toBe($this->admin->id);

    $this->profile->refresh();
    expect($this->profile->verification_status)->toBe('rejected');

    Notification::assertSentTo($this->user, VerificationNotification::class);
});

test('rejection requires reason with minimum length', function () {
    $doc = ProfileVerificationDocument::create([
        'profile_id'    => $this->profile->id,
        'document_type' => 'rg',
        'file_path'     => 'test.jpg',
        'status'        => 'pending',
        'submitted_at'  => now(),
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.verificacoes.reject', $doc), [
            'rejection_reason' => 'Ruim',
        ])
        ->assertSessionHasErrors('rejection_reason');
});

// ─── Admin: perfis de verificação ───────────────────────────

test('admin can view verification profiles list', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.verificacao-perfis'))
        ->assertOk();
});

test('profile shows pending status in admin profiles list', function () {
    $this->profile->update(['verification_status' => 'pending']);

    $this->actingAs($this->admin)
        ->get(route('admin.verificacao-perfis'))
        ->assertOk()
        ->assertSee($this->profile->name);
});
