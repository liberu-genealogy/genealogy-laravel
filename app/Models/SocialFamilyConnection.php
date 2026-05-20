<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialFamilyConnection extends Model
{
    use HasFactory;

    protected $table = 'social_family_connections';

    protected $fillable = [
        'user_id',
        'connected_account_id',
        'matched_social_id',
        'matched_name',
        'matched_email',
        'relationship_type',
        'confidence_score',
        'matching_criteria',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'confidence_score' => 'integer',
            'matching_criteria' => 'array',
        ];
    }

    /**
     * Get the user that owns the connection.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the connected account.
     */
    public function connectedAccount(): BelongsTo
    {
        return $this->belongsTo(ConnectedAccount::class);
    }

    /**
     * Accept the connection.
     */
    public function accept(): void
    {
        $this->status = 'accepted';
        $this->save();
    }

    /**
     * Reject the connection.
     */
    public function reject(): void
    {
        $this->status = 'rejected';
        $this->save();
    }

    /**
     * Check if connection is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if connection is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }
}
