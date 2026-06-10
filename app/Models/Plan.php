<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'slug', 'price', 'description', 'features', 'active', 'image'])]
class Plan extends Model
{
    protected function casts(): array
    {
        return [
            'price'    => 'decimal:2',
            'features' => 'array',
            'active'   => 'boolean',
        ];
    }

    public function imageUrl(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return asset('storage/' . $this->image);
    }
}
