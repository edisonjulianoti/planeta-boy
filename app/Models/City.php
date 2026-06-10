<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'state', 'slug', 'image', 'active', 'order', 'featured'])]
class City extends Model
{
    protected function casts(): array
    {
        return [
            'active'    => 'boolean',
            'featured'  => 'boolean',
            'order'     => 'integer',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeActive($query): void
    {
        $query->where('active', true);
    }

    public function scopeFeatured($query): void
    {
        $query->where('featured', true);
    }

    public function scopeOrdered($query): void
    {
        $query->orderBy('order');
    }

    public function scopeWithProfileCounts($query): void
    {
        $query->withCount(['profiles']);
    }

    // ─── Relations ────────────────────────────────────────

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'city', 'name');
    }
}
