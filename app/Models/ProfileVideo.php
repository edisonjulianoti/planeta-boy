<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['profile_id', 'url', 'path', 'type', 'order', 'is_main', 'video_id', 'platform'])]
class ProfileVideo extends Model
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

    /**
     * Get the URL for embedding/displaying the video.
     */
    public function embedUrl(): ?string
    {
        if ($this->path) {
            return asset('storage/' . $this->path);
        }

        return $this->url;
    }

    public function isLocal(): bool
    {
        return true;
    }
}
