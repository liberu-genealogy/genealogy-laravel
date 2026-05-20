<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoTag extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'photo_id',
        'person_id',
        'team_id',
        'confidence',
        'bounding_box',
        'status',
        'confirmed_by',
        'confirmed_at',
    ];

    protected $casts = [
        'confidence' => 'decimal:2',
        'bounding_box' => 'array',
        'confirmed_at' => 'datetime',
    ];

    public function photo(): BelongsTo
    {
        return $this->belongsTo(PersonPhoto::class, 'photo_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function confirm(int $userId): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_by' => $userId,
            'confirmed_at' => now(),
        ]);
    }

    public function reject(): void
    {
        $this->update([
            'status' => 'rejected',
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
