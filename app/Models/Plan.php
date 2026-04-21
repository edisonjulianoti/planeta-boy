<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'price', 'description', 'features', 'active'])]
class Plan extends Model
{
    protected function casts(): array
    {
        return [
            'price'   => 'decimal:2',
            'features' => 'array',
            'active'  => 'boolean',
        ];
    }
}
