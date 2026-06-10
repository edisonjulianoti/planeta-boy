<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'plan_slug', 'status', 'admin_notes', 'expires_at'])]
class SubscriptionRequest extends Model
{
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'status'     => SubscriptionStatus::class,
        ];
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopePending($query): void
    {
        $query->where('status', SubscriptionStatus::Pending);
    }

    public function scopeApproved($query): void
    {
        $query->where('status', SubscriptionStatus::Approved);
    }

    // ─── Relations ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === SubscriptionStatus::Pending;
    }

    public function isApproved(): bool
    {
        return $this->status === SubscriptionStatus::Approved;
    }
}
