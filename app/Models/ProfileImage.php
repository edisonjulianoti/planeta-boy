<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['profile_id', 'url', 'thumb_path', 'order', 'is_main'])]
class ProfileImage extends Model
{
    protected function casts(): array
    {
        return [
            'is_main' => 'boolean',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function imageUrl(): string
    {
        return asset('storage/' . $this->url);
    }

    public function thumbUrl(): string
    {
        return $this->thumb_path
            ? asset('storage/' . $this->thumb_path)
            : $this->imageUrl();
    }
}
