<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileVerificationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'document_type',
        'file_path',
        'status',
        'rejection_reason',
        'reviewed_by',
        'submitted_at',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at'  => 'datetime',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query): void
    {
        $query->where('status', 'pending');
    }

    public function scopeApproved($query): void
    {
        $query->where('status', 'approved');
    }

    public function scopeRejected($query): void
    {
        $query->where('status', 'rejected');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getDocumentTypeLabel(): string
    {
        return match ($this->document_type) {
            'rg'    => 'RG',
            'cnh'   => 'CNH',
            'selfie' => 'Selfie com Documento',
            'other' => 'Outro Documento',
            default => ucfirst($this->document_type),
        };
    }
}
