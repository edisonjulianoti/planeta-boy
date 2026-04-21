<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

#[Fillable(['name', 'email', 'password', 'phone', 'cpf', 'data_nascimento', 'bio', 'avatar', 'plan', 'plan_expires_at', 'is_admin', 'blocked'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'plan_expires_at'   => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
            'blocked'           => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isBlocked(): bool
    {
        return (bool) $this->blocked;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function subscriptionRequests(): HasMany
    {
        return $this->hasMany(SubscriptionRequest::class);
    }

    public function hasPlan(string $slug): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->plan === $slug;
    }

    public function planIsActive(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->plan === 'free') {
            return true;
        }

        return $this->plan_expires_at !== null
            && Carbon::parse($this->plan_expires_at)->isFuture();
    }

    public function hasPendingSubscriptionRequest(): bool
    {
        return $this->subscriptionRequests()
            ->where('status', 'pending')
            ->exists();
    }

    public function shouldHavePlan(): bool
    {
        return !$this->isAdmin();
    }
}
