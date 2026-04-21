<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['profile_id', 'duration', 'price', 'description'])]
class ProfilePricing extends Model
{
    protected $table = 'profile_pricing';

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
