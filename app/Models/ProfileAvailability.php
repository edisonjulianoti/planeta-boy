<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['profile_id', 'days', 'start_time', 'end_time'])]
class ProfileAvailability extends Model
{
    protected $table = 'profile_availability';

    protected function casts(): array
    {
        return [
            'days' => 'array',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
