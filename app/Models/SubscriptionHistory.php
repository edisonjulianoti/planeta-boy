<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['subscription_id', 'event', 'old_plan_slug', 'new_plan_slug', 'description'])]
class SubscriptionHistory extends Model
{
    use HasFactory;

    protected $table = 'subscription_histories';

    // ─── Relations ────────────────────────────────────────

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
