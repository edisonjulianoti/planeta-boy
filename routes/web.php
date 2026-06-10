<?php

use App\Http\Controllers\Admin\AdminCityController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminSubscriberCategoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ExplorarController;
use App\Http\Controllers\DestaqueController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\AccountDeletionController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Sitemap XML (sem age gate)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Rotas públicas com validação de idade
Route::middleware('age.gate')->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Explorar
    Route::get('/explorar', [ExplorarController::class, 'index'])->name('explorar');

    // Página por cidade
    Route::get('/cidade/{slug}', [CityController::class, 'show'])->name('cidade.show');

    // Destaques
    Route::get('/destaques', [DestaqueController::class, 'index'])->name('destaques');

    // Perfil público
    Route::get('/perfis/{slug}', [PerfilController::class, 'ver'])->name('perfil.ver');

    // Planos (p\u00fablico)
    Route::get('/planos', [PlanoController::class, 'index'])->name('planos');

    // FAQ
    Route::get('/faq', [FaqController::class, 'index'])->name('faq');

    // Contato
    Route::get('/contato', [ContatoController::class, 'index'])->name('contato');
    Route::post('/contato', [ContatoController::class, 'store'])->name('contato.store');
});

// Perfil público (sem age gate para comentários/denúncias)
Route::post('/perfis/{slug}/comentar', [PerfilController::class, 'comentar'])->name('perfil.comentar')->middleware('auth');
Route::post('/perfis/{slug}/denunciar', [PerfilController::class, 'denunciar'])->name('perfil.denunciar')->middleware('auth');

// Páginas estáticas
Route::view('/sobre', 'sobre')->name('sobre');
Route::view('/termos', 'termos')->name('termos');
Route::view('/privacidade', 'privacidade')->name('privacidade');
Route::view('/seguranca', 'seguranca')->name('seguranca');
Route::view('/regras', 'regras')->name('regras');
Route::view('/como-anunciar', 'como-anunciar')->name('como-anunciar');

// Redirects de URLs legadas (inglês → português)
Route::redirect('/privacy', '/privacidade', 301);
Route::redirect('/terms', '/termos', 301);

// Validação de idade
Route::post('/age-gate/confirm', function () {
    return response()->json(['success' => true])->cookie('age_gate_confirmed', 'true', 43200);
})->name('age-gate.confirm');

// Auth (guest) - com rate limiting para proteção contra brute force
Route::middleware('guest')->group(function () {
    Route::get('/entrar', [LoginController::class, 'form'])->name('login');
    Route::get('/login', [LoginController::class, 'form']);
    Route::post('/entrar', [LoginController::class, 'store'])->middleware('throttle:5,1');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');

    Route::get('/cadastro', [RegistroController::class, 'form'])->name('registro');
    Route::get('/registro', [RegistroController::class, 'form']);
    Route::post('/cadastro', [RegistroController::class, 'store'])->middleware('throttle:3,30');
    Route::post('/registro', [RegistroController::class, 'store'])->middleware('throttle:3,30');

    Route::get('/esqueci-senha', fn() => view('auth.login'))->name('password.request');
});

// Auth (autenticado)
Route::middleware('auth')->group(function () {
    Route::post('/sair', [LoginController::class, 'destroy'])->name('logout');
    Route::post('/logout', [LoginController::class, 'destroy']);
    Route::get('/meu-perfil', [PerfilController::class, 'meu'])->name('perfil');
    Route::put('/meu-perfil', [PerfilController::class, 'atualizar'])->name('perfil.atualizar');
    Route::post('/localizacao/atualizar', [LocationController::class, 'update'])->name('localizacao.atualizar');

    // Criar/editar perfil de acompanhante
    Route::get('/meu-perfil/criar', [PerfilController::class, 'criar'])->name('perfil.criar');
    Route::get('/meu-perfil/editar/{id}', [PerfilController::class, 'editar'])->name('perfil.editar');
    Route::post('/meu-perfil/criar', [PerfilController::class, 'salvar'])->name('perfil.salvar');

    // Planos (requer login)
    Route::post('/planos/contratar', [PlanoController::class, 'contratar'])->name('planos.contratar');
    Route::get('/meu-plano', [PlanoController::class, 'meuPlano'])->name('meu.plano');
    Route::post('/meu-plano/cancelar', [PlanoController::class, 'cancelar'])->name('meu.plano.cancelar');

    // Exclusão de conta (LGPD - direito de exclusão)
    Route::get('/excluir-conta', [AccountDeletionController::class, 'form'])->name('conta.excluir.form');
    Route::delete('/excluir-conta', [AccountDeletionController::class, 'destroy'])->name('conta.excluir');

    // Favoritos
    Route::get('/meus-favoritos', [FavoritoController::class, 'index'])->name('favoritos.index');
    Route::post('/perfis/{profileId}/favoritar', [FavoritoController::class, 'toggle'])->name('favoritos.toggle');
});

// Admin
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/usuarios', [AdminController::class, 'users'])->name('users');
        Route::get('/usuarios/{user}', [AdminController::class, 'show'])->name('users.show');
        Route::get('/usuarios/{user}/edit', [AdminController::class, 'edit'])->name('users.edit');
        Route::put('/usuarios/{user}', [AdminController::class, 'update'])->name('users.update');
        Route::post('/usuarios/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::post('/usuarios/{user}/toggle-blocked', [AdminController::class, 'toggleBlocked'])->name('users.toggle-blocked');
        Route::delete('/usuarios/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
        Route::get('/perfis', [AdminController::class, 'profiles'])->name('profiles');
        Route::get('/perfis/{profile}/editar', [AdminController::class, 'editProfile'])->name('profiles.edit');
        Route::put('/perfis/{profile}', [AdminController::class, 'updateProfile'])->name('profiles.update');

        // Planos
        Route::get('/planos', [PlanController::class, 'index'])->name('plans');
        Route::get('/planos/{plan}/edit', [PlanController::class, 'edit'])->name('plans.edit');
        Route::put('/planos/{plan}', [PlanController::class, 'update'])->name('plans.update');

        // Assinaturas
        Route::get('/assinaturas', [SubscriptionController::class, 'index'])->name('subscriptions');
        Route::get('/assinaturas/{subscription}/editar', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
        Route::put('/assinaturas/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::post('/assinaturas/{subscription}/aprovar', [SubscriptionController::class, 'approve'])->name('subscriptions.approve');
        Route::post('/assinaturas/{subscription}/rejeitar', [SubscriptionController::class, 'reject'])->name('subscriptions.reject');
        Route::post('/usuarios/{user}/plano', [SubscriptionController::class, 'updateUserPlan'])->name('users.plan.update');

        // Cidades
        Route::get('/cidades', [AdminCityController::class, 'index'])->name('cities');
        Route::get('/cidades/criar', [AdminCityController::class, 'create'])->name('cities.create');
        Route::post('/cidades', [AdminCityController::class, 'store'])->name('cities.store');
        Route::get('/cidades/{city}/editar', [AdminCityController::class, 'edit'])->name('cities.edit');
        Route::put('/cidades/{city}', [AdminCityController::class, 'update'])->name('cities.update');
        Route::delete('/cidades/{city}', [AdminCityController::class, 'destroy'])->name('cities.destroy');

        // Servi\u00e7os
        Route::get('/servicos', [AdminServiceController::class, 'index'])->name('services');
        Route::get('/servicos/criar', [AdminServiceController::class, 'create'])->name('services.create');
        Route::post('/servicos', [AdminServiceController::class, 'store'])->name('services.store');
        Route::get('/servicos/{service}/editar', [AdminServiceController::class, 'edit'])->name('services.edit');
        Route::put('/servicos/{service}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::delete('/servicos/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

        // Categorias de Assinantes
        Route::get('/categorias-assinantes', [AdminSubscriberCategoryController::class, 'index'])->name('subscriber-categories');
        Route::get('/categorias-assinantes/criar', [AdminSubscriberCategoryController::class, 'create'])->name('subscriber-categories.create');
        Route::post('/categorias-assinantes', [AdminSubscriberCategoryController::class, 'store'])->name('subscriber-categories.store');
        Route::get('/categorias-assinantes/{category}/editar', [AdminSubscriberCategoryController::class, 'edit'])->name('subscriber-categories.edit');
        Route::put('/categorias-assinantes/{category}', [AdminSubscriberCategoryController::class, 'update'])->name('subscriber-categories.update');
        Route::delete('/categorias-assinantes/{category}', [AdminSubscriberCategoryController::class, 'destroy'])->name('subscriber-categories.destroy');

        // FAQs
        Route::get('/faqs', [AdminFaqController::class, 'index'])->name('faqs');
        Route::get('/faqs/criar', [AdminFaqController::class, 'create'])->name('faqs.create');
        Route::post('/faqs', [AdminFaqController::class, 'store'])->name('faqs.store');
        Route::get('/faqs/{faq}/editar', [AdminFaqController::class, 'edit'])->name('faqs.edit');
        Route::put('/faqs/{faq}', [AdminFaqController::class, 'update'])->name('faqs.update');
        Route::delete('/faqs/{faq}', [AdminFaqController::class, 'destroy'])->name('faqs.destroy');
    });
