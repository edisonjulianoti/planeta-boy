<?php

namespace App\Models;

use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['profile_id', 'user_id', 'reason', 'description', 'status'])]
class ProfileReport extends Model
{
    protected $attributes = [
        'status' => ReportStatus::Pending,
    ];

    protected function casts(): array
    {
        return [
            'status' => ReportStatus::class,
        ];
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopePending($query): void
    {
        $query->where('status', ReportStatus::Pending);
    }

    // ─── Relations ────────────────────────────────────────

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
