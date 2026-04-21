<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
}
