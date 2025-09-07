<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JoelButcher\Socialstream\HasConnectedAccounts;
use JoelButcher\Socialstream\SetsProfilePhotoFromUrl;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasDefaultTenant, HasTenants, FilamentUser
{
    use HasApiTokens;
    // use HasConnectedAccounts;
    use HasRoles;
    use HasFactory;
    use HasProfilePhoto {
        HasProfilePhoto::profilePhotoUrl as getPhotoUrl;
    }
    use Notifiable;
    // use SetsProfilePhotoFromUrl;
    use TwoFactorAuthenticatable;
    use HasTeams;
    use Billable;
    // use HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_premium',
        'dna_uploads_count',
        'premium_started_at',
        'total_points',
        'level',
        'level_progress',
        'last_activity_at',
        'show_on_leaderboard',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'is_premium' => 'boolean',
            'premium_started_at' => 'datetime',
            'total_points' => 'integer',
            'level' => 'integer',
            'level_progress' => 'integer',
            'last_activity_at' => 'datetime',
            'show_on_leaderboard' => 'boolean',
        ];
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function profilePhotoUrl(): Attribute
    {
        return filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)
            ? Attribute::get(fn () => $this->profile_photo_path)
            : $this->getPhotoUrl();
    }

    /**
     * @return array<Model> | Collection
     */
    public function getTenants(Panel $panel): array|Collection
    {
        return $this->ownedTeams;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return true; //$this->ownedTeams->contains($tenant);
    }

    public function canAccessFilament(): bool
    {
        //        return $this->hasVerifiedEmail();
        return true;
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        if ($this->hasRole('super_admin')) {
            return true;
        }

        return match ($panel->getId()) {
            'admin' => $this->hasRole('super_admin'),
            'app' => $this->hasRole('panel_user'),
            default => false,
        };
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->latestTeam;
    }

    public function latestTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    /**
     * Check if user has premium subscription
     */
    public function isPremium(): bool
    {
        return $this->is_premium && ($this->subscribed('premium') || $this->onTrial('premium'));
    }

    /**
     * Check if user is on trial
     */
    public function onPremiumTrial(): bool
    {
        return $this->onTrial('premium');
    }

    /**
     * Get remaining trial days
     */
    public function trialDaysRemaining(): int
    {
        if (!$this->onTrial('premium')) {
            return 0;
        }

        return max(0, $this->trial_ends_at->diffInDays(now()));
    }

    /**
     * Check if user can upload DNA kit
     */
    public function canUploadDna(): bool
    {
        if ($this->isPremium()) {
            return true; // Unlimited for premium users
        }

        return $this->dna_uploads_count < 1; // Standard users get 1 upload
    }

    /**
     * Increment DNA upload count
     */
    public function incrementDnaUploads(): void
    {
        $this->increment('dna_uploads_count');
    }

    /**
     * Get premium badge HTML
     */
    public function getPremiumBadgeAttribute(): string
    {
        if (!$this->isPremium()) {
            return '';
        }

        $badgeText = $this->onPremiumTrial() ? 'Premium Trial' : 'Premium';
        $badgeColor = $this->onPremiumTrial() ? 'bg-yellow-100 text-yellow-800' : 'bg-gradient-to-r from-purple-500 to-pink-500 text-white';

        return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$badgeColor}\">
                    <svg class=\"w-3 h-3 mr-1\" fill=\"currentColor\" viewBox=\"0 0 20 20\">
                        <path d=\"M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z\"/>
                    </svg>
                    {$badgeText}
                </span>";
    }

    /**
     * Get user achievements
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * Get user points
     */
    public function points(): HasMany
    {
        return $this->hasMany(UserPoint::class);
    }

    /**
     * Get user progress
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get recent achievements
     */
    public function recentAchievements(int $days = 7)
    {
        return $this->achievements()
            ->with('achievement')
            ->recent($days)
            ->orderBy('unlocked_at', 'desc');
    }

    /**
     * Get recent points
     */
    public function recentPoints(int $days = 7)
    {
        return $this->points()
            ->recent($days)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get points for a specific activity type
     */
    public function getPointsForActivity(string $activityType): int
    {
        return $this->points()
            ->byActivity($activityType)
            ->sum('points');
    }

    /**
     * Get total points earned today
     */
    public function getTodaysPoints(): int
    {
        return $this->points()
            ->whereDate('created_at', today())
            ->sum('points');
    }

    /**
     * Get current level information
     */
    public function getLevelInfo(): array
    {
        $pointsForNextLevel = $this->getPointsRequiredForLevel($this->level + 1);
        $pointsForCurrentLevel = $this->getPointsRequiredForLevel($this->level);
        $progressToNextLevel = $this->total_points - $pointsForCurrentLevel;
        $pointsNeededForNextLevel = $pointsForNextLevel - $pointsForCurrentLevel;

        return [
            'current_level' => $this->level,
            'total_points' => $this->total_points,
            'points_for_current_level' => $pointsForCurrentLevel,
            'points_for_next_level' => $pointsForNextLevel,
            'progress_to_next_level' => $progressToNextLevel,
            'points_needed_for_next_level' => max(0, $pointsNeededForNextLevel - $progressToNextLevel),
            'progress_percentage' => $pointsNeededForNextLevel > 0 ? min(100, ($progressToNextLevel / $pointsNeededForNextLevel) * 100) : 100,
        ];
    }

    /**
     * Get points required for a specific level
     */
    public function getPointsRequiredForLevel(int $level): int
    {
        if ($level <= 1) {
            return 0;
        }

        // Exponential growth: level^2 * 100
        return pow($level - 1, 2) * 100;
    }

    /**
     * Update user level based on total points
     */
    public function updateLevel(): void
    {
        $newLevel = $this->calculateLevelFromPoints($this->total_points);

        if ($newLevel > $this->level) {
            $oldLevel = $this->level;
            $this->level = $newLevel;
            $this->save();

            // Dispatch level up event
            event(new \App\Events\UserLeveledUp($this, $oldLevel, $newLevel));
        }
    }

    /**
     * Calculate level from total points
     */
    private function calculateLevelFromPoints(int $points): int
    {
        $level = 1;
        while ($this->getPointsRequiredForLevel($level + 1) <= $points) {
            $level++;
        }
        return $level;
    }

    /**
     * Check if user has a specific achievement
     */
    public function hasAchievement(string $achievementKey): bool
    {
        return $this->achievements()
            ->whereHas('achievement', function ($query) use ($achievementKey) {
                $query->where('key', $achievementKey);
            })
            ->exists();
    }

    /**
     * Get achievement progress for a specific achievement
     */
    public function getAchievementProgress(string $achievementKey): ?UserProgress
    {
        return $this->progress()
            ->whereHas('achievement', function ($query) use ($achievementKey) {
                $query->where('key', $achievementKey);
            })
            ->first();
    }

    /**
     * Get user's rank on leaderboard
     */
    public function getLeaderboardRank(): int
    {
        return User::where('show_on_leaderboard', true)
            ->where('total_points', '>', $this->total_points)
            ->count() + 1;
    }

    /**
     * Get level badge HTML
     */
    public function getLevelBadgeAttribute(): string
    {
        $levelInfo = $this->getLevelInfo();
        $progressPercentage = $levelInfo['progress_percentage'];

        return "<div class=\"level-badge bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-2 text-center\">
                    <div class=\"text-lg font-bold\">Level {$this->level}</div>
                    <div class=\"text-xs\">{$this->total_points} points</div>
                    <div class=\"w-full bg-white/20 rounded-full h-1 mt-1\">
                        <div class=\"bg-white h-1 rounded-full\" style=\"width: {$progressPercentage}%\"></div>
                    </div>
                </div>";
    }
}
