<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Subscription;

#[Fillable(['user_id', 'name', 'age', 'gender', 'city', 'state', 'description', 'telegram', 'tagline', 'attendance_target', 'payment_methods', 'documents_verified', 'no_reports', 'clean_history', 'verified', 'rating', 'views', 'last_active_at', 'active', 'subscriber_category_id', 'latitude', 'longitude', 'location_enabled'])]
class Profile extends Model
{
    use HasFactory;
    protected function casts(): array
    {
        return [
            'verified'           => 'boolean',
            'active'             => 'boolean',
            'rating'             => 'decimal:2',
            'last_active_at'     => 'datetime',
            'attendance_target'  => 'array',
            'payment_methods'    => 'array',
            'documents_verified' => 'boolean',
            'no_reports'         => 'boolean',
            'clean_history'      => 'boolean',
            'location_enabled'   => 'boolean',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeActive($query): void
    {
        $query->where('active', true);
    }

    public function scopeVerified($query): void
    {
        $query->where('verified', true);
    }

    public function scopeByCity($query, string $city): void
    {
        $query->where('city', $city);
    }

    public function scopeByState($query, string $state): void
    {
        $query->where('state', $state);
    }

    public function scopeFeatured($query): void
    {
        $query->where('verified', true)->orderBy('rating', 'desc');
    }

    public function scopeSimilarTo($query, self $profile, int $limit = 4): void
    {
        $query->active()
            ->where('id', '!=', $profile->id)
            ->where('city', $profile->city)
            ->where('state', $profile->state)
            ->inRandomOrder()
            ->limit($limit);
    }

    public function scopeWithEagerLoads($query): void
    {
        $query->with([
            'images', 'videos', 'user', 'physicalAttributes',
            'services', 'availability', 'pricing', 'comments.user',
        ]);
    }

    // ─── Relations ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriberCategory(): BelongsTo
    {
        return $this->belongsTo(SubscriberCategory::class);
    }

    public function physicalAttributes(): HasOne
    {
        return $this->hasOne(ProfilePhysicalAttribute::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'profile_services')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProfileImage::class)->orderBy('order');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ProfileVideo::class)->orderBy('order');
    }

    public function availability(): HasOne
    {
        return $this->hasOne(ProfileAvailability::class);
    }

    public function pricing(): HasMany
    {
        return $this->hasMany(ProfilePricing::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ProfileComment::class)->latest();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ProfileReport::class);
    }

    // ─── Favoritos ───────────────────────────────────────

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'profile_favorites')
            ->withTimestamps();
    }

    public function scopeFavoritedBy($query, User $user): void
    {
        $query->whereHas('favoritedByUsers', fn($q) => $q->where('user_id', $user->id));
    }

    // ─── Assinaturas ─────────────────────────────────────

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscribersCount(): int
    {
        return $this->subscriptions()->active()->count();
    }

    public function firstSubscriptionDate(): ?string
    {
        $first = $this->subscriptions()->orderBy('start_date')->first();
        return $first?->start_date?->format('d/m/Y');
    }

    // ─── Mídia ────────────────────────────────────────────

    public function primaryImage(): ?ProfileImage
    {
        return $this->images()->where('is_main', true)->first() ?? $this->images()->first();
    }

    public function primaryImageUrl(): ?string
    {
        $image = $this->primaryImage();

        return $image?->imageUrl();
    }

    public function primaryVideo(): ?ProfileVideo
    {
        return $this->videos()->where('is_main', true)->first() ?? $this->videos()->first();
    }
}
