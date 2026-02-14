<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialConnectionPrivacy extends Model
{
    use HasFactory;

    protected $table = 'social_connection_privacy';

    protected $fillable = [
        'user_id',
        'allow_family_discovery',
        'show_profile_to_matches',
        'share_tree_with_matches',
        'allow_contact_from_matches',
        'blocked_users',
    ];

    protected function casts(): array
    {
        return [
            'allow_family_discovery' => 'boolean',
            'show_profile_to_matches' => 'boolean',
            'share_tree_with_matches' => 'boolean',
            'allow_contact_from_matches' => 'boolean',
            'blocked_users' => 'array',
        ];
    }

    /**
     * Get the user that owns the privacy settings.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a user is blocked.
     */
    public function isUserBlocked(int $userId): bool
    {
        return in_array($userId, $this->blocked_users ?? []);
    }

    /**
     * Block a user.
     */
    public function blockUser(int $userId): void
    {
        $blockedUsers = $this->blocked_users ?? [];
        if (!in_array($userId, $blockedUsers)) {
            $blockedUsers[] = $userId;
            $this->blocked_users = $blockedUsers;
            $this->save();
        }
    }

    /**
     * Unblock a user.
     */
    public function unblockUser(int $userId): void
    {
        $blockedUsers = $this->blocked_users ?? [];
        $this->blocked_users = array_values(array_filter($blockedUsers, fn($id) => $id !== $userId));
        $this->save();
    }
}
