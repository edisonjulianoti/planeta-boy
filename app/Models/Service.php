<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'slug', 'category', 'active'])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'profile_services')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function subscriberCategories(): BelongsToMany
    {
        return $this->belongsToMany(SubscriberCategory::class, 'subscriber_category_restricted_services')
            ->withTimestamps();
    }
}
