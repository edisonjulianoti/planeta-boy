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

    public function embedUrl(): ?string
    {
        if ($this->type === 'local' && $this->path) {
            return asset('storage/' . $this->path);
        }

        if ($this->type === 'youtube' && $this->video_id) {
            return 'https://www.youtube.com/embed/' . $this->video_id;
        }

        return $this->url;
    }

    public function isLocal(): bool
    {
        return $this->type === 'local';
    }

    public function isYoutube(): bool
    {
        return $this->type === 'youtube';
    }
}
