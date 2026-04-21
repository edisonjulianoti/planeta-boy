---
trigger: always_on
---

# Estrutura do Projeto — Planeta Boy

## Stack & Ambiente

- **Framework:** Laravel 13 (PHP 8.3)
- **Frontend:** Blade puro + Tailwind CSS 4 + Vite 8
- **Servidor:** Laragon — `https://planeta-boy.test:8443/`
- **DB:** MySQL (tabelas em português)
- **Testes:** PHPUnit 12

## Models (8) — `app/Models/`

| Model | Tabela | Fillable | Casts | Relacionamentos |
|---|---|---|---|---|
| `User` | users | name, email, password, phone, bio, avatar, plan, plan_expires_at, is_admin | email_verified_at:datetime, plan_expires_at:datetime, password:hashed, is_admin:boolean | hasMany(Profile), hasMany(SubscriptionRequest) |
| `Profile` | perfis | user_id, name, age, city, state, description, verified, rating, views, last_active_at, active | verified:boolean, active:boolean, rating:decimal:2, last_active_at:datetime | belongsTo(User), hasOne(ProfilePhysicalAttribute), belongsToMany(Service), hasMany(ProfileImage), hasOne(ProfileAvailability) |
| `Plan` | planos | name, slug, price, description, features, active | price:decimal:2, features:array, active:boolean | — |
| `Service` | servicos | name, slug, category, active | active:boolean | belongsToMany(Profile) via profile_services |
| `ProfilePhysicalAttribute` | perfis_atributos_fisicos | profile_id, height, weight, hair_color, eye_color, ethnicity | — | belongsTo(Profile) |
| `ProfileImage` | perfis_imagens | profile_id, url, order, is_main | is_main:boolean | belongsTo(Profile) |
| `ProfileAvailability` | perfis_disponibilidade | profile_id, days, start_time, end_time | days:array | belongsTo(Profile) |
| `SubscriptionRequest` | subscription_requests | user_id, plan_slug, status, admin_notes, expires_at | expires_at:datetime | belongsTo(User) |

## Diagrama de Relacionamentos

```
User 1──N Profile 1──1 ProfilePhysicalAttribute
                   1──1 ProfileAvailability
                   1──N ProfileImage
                   N──N Service (pivot: profile_services + price)
User 1──N SubscriptionRequest
Plan (independente)
```

## Migrations (11) — `database/migrations/`

- `0001_01_01_000000` — users (Laravel default + phone, bio, avatar, plan, plan_expires_at, is_admin)
- `0001_01_01_000001` — cache
- `0001_01_01_000002` — jobs
- `2025_01_01_000010` — planos
- `2025_01_01_000011` — perfis
- `2025_01_01_000012` — perfis_atributos_fisicos
- `2025_01_01_000013` — servicos
- `2025_01_01_000014` — perfis_servicos (pivot)
- `2025_01_01_000015` — perfis_imagens
- `2025_01_01_000016` — perfis_disponibilidade
- `2025_01_01_000020` — subscription_requests

## Controllers — `app/Http/Controllers/`

| Controller | Métodos |
|---|---|
| `HomeController` | index |
| `ExplorarController` | index |
| `PerfilController` | ver, meu |
| `PlanoController` | index, contratar, meuPlano, cancelar |
| `ContatoController` | index, store |
| `Auth\LoginController` | form, store, destroy |
| `Auth\RegistroController` | form, store |
| `Admin\AdminController` | dashboard, users, profiles |
| `Admin\PlanController` | index, edit, update |
| `Admin\SubscriptionController` | index, approve, reject, updateUserPlan |

## Middleware — `app/Http/Middleware/`

- `EnsureUserIsAdmin` — verifica `is_admin` no user autenticado, aborta 403

## Actions (3) — `app/Actions/`

- `CreateContactAction` — loga contato (TODO: envio de e-mail)
- `FilterProfilesAction` — filtra perfis por cidade, featured e similar
- `RegisterUserAction` — cria user, faz login e regenera sessão

## Form Requests (3) — `app/Http/Requests/`

- `Auth\LoginRequest`
- `Auth\RegisterRequest`
- `ContactMessageRequest`

## Rotas — `routes/web.php`

**Públicas:**
- `GET /` → home
- `GET /explorar` → explorar
- `GET /perfis/{id}` → perfil.ver
- `GET /planos` → planos
- `GET /contato` → contato
- `POST /contato` → contato
- `GET /termos` → termos (view)
- `GET /privacidade` → privacidade (view)

**Guest (middleware: guest):**
- `GET/POST /login` → login
- `GET/POST /registro` → registro
- `GET /esqueci-senha` → password.request

**Auth (middleware: auth):**
- `POST /logout` → logout
- `GET /meu-perfil` → perfil
- `POST /planos/contratar` → planos.contratar
- `GET /meu-plano` → meu.plano
- `POST /meu-plano/cancelar` → meu.plano.cancelar

**Admin (middleware: auth + EnsureUserIsAdmin, prefix: admin):**
- `GET /admin` → admin.dashboard
- `GET /admin/usuarios` → admin.users
- `GET /admin/perfis` → admin.profiles
- `GET /admin/planos` → admin.plans
- `GET /admin/planos/{plan}/edit` → admin.plans.edit
- `PUT /admin/planos/{plan}` → admin.plans.update
- `GET /admin/assinaturas` → admin.subscriptions
- `POST /admin/assinaturas/{subscription}/aprovar` → admin.subscriptions.approve
- `POST /admin/assinaturas/{subscription}/rejeitar` → admin.subscriptions.reject
- `POST /admin/usuarios/{user}/plano` → admin.users.plan.update

## Views (19) — `resources/views/`

- `layouts/app.blade.php` (layout principal)
- `home.blade.php`, `explorar.blade.php`, `contato.blade.php`, `planos.blade.php`, `meu-plano.blade.php`
- `termos.blade.php`, `privacidade.blade.php`
- `auth/login.blade.php`, `auth/registro.blade.php`
- `perfil/ver.blade.php`, `perfil/meu.blade.php`
- `admin/layout.blade.php`, `admin/dashboard.blade.php`, `admin/users.blade.php`, `admin/profiles.blade.php`
- `admin/plans.blade.php`, `admin/plans-edit.blade.php`, `admin/subscriptions.blade.php`

## Seeders & Factories — `database/`

**Seeders:** DatabaseSeeder, PlanoSeeder, ServicoSeeder, UserSeeder
**Factories:** UserFactory, ProfileFactory

## Testes — `tests/`

- `Feature/AdminPanelTest`
- `Feature/Auth/LoginTest`
- `Feature/Auth/RegistroTest`
- `Feature/SiteRoutesTest`
- `Feature/SubscriptionTest`
- `Unit/ExampleTest`
