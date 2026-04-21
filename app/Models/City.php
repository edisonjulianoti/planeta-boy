<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

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
}
