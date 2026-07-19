<?php

namespace App\Models;

use App\Events\UserLeveledUp;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use JoelButcher\Socialstream\HasConnectedAccounts;
use JoelButcher\Socialstream\SetsProfilePhotoFromUrl;
// use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Support\Config;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasDefaultTenant, HasTenants
{
    use Billable;
    use HasApiTokens;
    use HasConnectedAccounts;
    use HasFactory;
    use HasRoles, HasTeams {
        HasTeams::teams insteadof HasRoles;
        HasRoles::teams as roleTeams;
    }

    //  use HasProfilePhoto {
    //    HasProfilePhoto::profilePhotoUrl as getPhotoUrl;
    //    }
    use Notifiable;
    use SetsProfilePhotoFromUrl;
    use TwoFactorAuthenticatable;
    // use HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    #[\Override]
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
    #[\Override]
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
    #[\Override]
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
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
     * Delete the user's profile photo.
     */
    public function deleteProfilePhoto(): void
    {
        if (! is_null($this->profile_photo_path)) {
            Storage::disk($this->profilePhotoDisk())->delete($this->profile_photo_path);
            $this->forceFill(['profile_photo_path' => null])->save();
        }
    }

    /**
     * Get the disk used for storing profile photos.
     */
    protected function profilePhotoDisk(): string
    {
        return env('VAPOR_ARTIFACT_NAME') ? 's3' : config('jetstream.profile_photo_disk', 'public');
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function profilePhotoUrl(): Attribute
    {
        return filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)
            ? Attribute::get(fn () => $this->profile_photo_path)
            : $this->defaultPhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     */
    protected function defaultPhotoUrl(): Attribute
    {
        return Attribute::get(fn (): string => 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF');
    }

    /**
     * @return array<Model> | Collection
     */
    public function getTenants(Panel $panel): array|Collection
    {
        // Owned teams AND teams joined. Listing only owned teams left a member
        // of someone else's team with no way to reach it, which is the mirror
        // image of the access check below being too permissive.
        return $this->allTeams();
    }

    /**
     * Filament calls this to authorise the tenant in the URL.
     *
     * This returned an unconditional true, with the real condition commented
     * out on the same line, so any authenticated user could open any team by
     * typing its URL. Nothing in the interface led there, because the switcher
     * lists only reachable teams — it was reachable by editing the address bar.
     *
     * The commented-out condition tested ownership. Restoring it verbatim would
     * have locked every member out of teams they legitimately belong to but do
     * not own, so the test is membership: belongsToTeam() covers both.
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->belongsToTeam($tenant);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // always allow access to the `app` panel for any authenticated user;
        // prior implementation required a `panel_user` role which caused
        // 403 errors immediately after login/registration for new accounts.
        if ($panel->getId() === 'app') {
            return true;
        }

        if ($this->hasGlobalRole('super_admin')) {
            return true;
        }

        return match ($panel->getId()) {
            'admin' => $this->hasGlobalRole('admin'),
            default => true,
        };
    }

    /**
     * Roles held in the current team, plus every role defined without one.
     *
     * The permission library filters this relation by the current team on both
     * sides — the grant and the role — so a team-less role stopped applying the
     * moment its holder worked in a team other than the one the grant was
     * written in. That is right for a role scoped to a team and wrong for a
     * role that has none, and the admin panel is where it showed: gating the
     * door on a team-less role let an administrator in, and then all 53
     * policies behind it resolved through here, found nothing, and refused
     * every page. A panel that renders its navigation and 403s on all of it is
     * worse than one that turns you away.
     *
     * So a role with no team applies everywhere, which is the whole meaning of
     * defining one without a team. A role with a team applies in that team,
     * unchanged. Both sides are still checked for the scoped case, so a grant
     * cannot carry a role into a team the role does not belong to.
     *
     * This is a deliberate override of the vendor relation rather than a
     * mirror of it; if the package changes its team filtering, this needs
     * revisiting. It is tested in AdminPanelAuthorizationTest.
     */
    public function roles(): MorphToMany
    {
        $relation = $this->morphToMany(
            Config::roleModel(),
            'model',
            Config::modelHasRolesTable(),
            Config::morphKey(),
            app(PermissionRegistrar::class)->pivotRole,
        );

        if (! Config::teamsEnabled()) {
            return $relation;
        }

        $teamKey = Config::teamForeignKey();
        $roleTeam = Config::rolesTable().'.'.$teamKey;
        $grantTeam = Config::modelHasRolesTable().'.'.$teamKey;
        $currentTeam = getPermissionsTeamId();

        return $relation->withPivot($teamKey)->where(
            fn ($query) => $query
                ->whereNull($roleTeam)
                ->orWhere(
                    fn ($scoped) => $scoped
                        ->where($grantTeam, $currentTeam)
                        ->where($roleTeam, $currentTeam)
                )
        );
    }

    /**
     * Whether this user holds a role that is defined globally rather than
     * within a single team.
     *
     * Both checks above used hasRole(), which since roles became team-scoped
     * resolves against one team — the tenant in the URL, or the stored team
     * where there is none. The admin panel has no tenancy, so it would have
     * resolved against whichever team the user last worked in: a super admin
     * would keep or lose the admin panel depending on which family's tree they
     * had open. Nothing would have failed; the panel would just come and go.
     *
     * The safe reading of "global" here is a role whose *definition* has no
     * team, not an assignment held in any team. The permission library pins
     * every assignment to a team, so "holds admin somewhere" would let anyone
     * who can create a role inside their own team mint one named super_admin
     * and take the admin panel with it. A team member cannot create a
     * team-less role — anything created from inside a panel inherits that
     * panel's team — so only the seeder can produce one. That is the boundary
     * being relied on, and it is the reason this is not simply hasRole() with
     * the team filter removed.
     *
     * Shield's role management lives on the admin panel, so today there is no
     * route by which an app-panel user could create a role at all. This does
     * not depend on that staying true.
     */
    public function hasGlobalRole(string $name): bool
    {
        $roleModel = config('permission.models.role');

        return $roleModel::query()
            ->whereNull(config('permission.column_names.team_foreign_key', 'team_id'))
            ->where('name', $name)
            ->whereHas('users', fn ($query) => $query->whereKey($this->getKey()))
            ->exists();
    }

    /**
     * The team to land on when no tenant is in the URL.
     *
     * Two things this has to get right, both of which it previously did not.
     *
     * latestTeam is an unguarded belongsTo on current_team_id, so it can point
     * at a team the user no longer belongs to — removeUser() and a bare
     * teams()->detach() both leave the column behind. Returning that team sends
     * the user to a tenant the access check then refuses, which is a login
     * followed immediately by a 404 and no way out. That became reachable the
     * moment canAccessTenant stopped returning true unconditionally, so the
     * membership test here is part of that same change rather than a tidy-up.
     *
     * And the fallback only considered owned teams, so a member who owns
     * nothing — an invited account, or anyone whose own team was removed — got
     * null and was redirected to create a team despite having one they could
     * reach. LoginResponse calls this directly rather than going through
     * Filament, which falls back to the tenant list on its own, so the gap
     * showed up on the first screen after signing in.
     */
    public function getDefaultTenant(Panel $panel): ?Model
    {
        $current = $this->latestTeam;

        if ($current && $this->belongsToTeam($current)) {
            return $current;
        }

        return $this->allTeams()->first();
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
        // If premium features are globally enabled, treat all users as premium
        if (config('premium.enabled')) {
            return true;
        }

        // Active Stripe subscription (not cancelled / not expired)
        if ($this->subscribed('premium')) {
            return true;
        }

        // Local trial still running
        return $this->is_premium && $this->onTrial();
    }

    /**
     * Check whether the user started a trial that has since expired and they
     * have not yet set up a paid subscription.
     */
    public function hasExpiredTrial(): bool
    {
        if (config('premium.enabled')) {
            return false;
        }

        // They went through the trial flow (is_premium was set) but the trial
        // window has closed and there is no active Stripe subscription.
        return $this->is_premium
            && ! $this->onTrial()
            && ! $this->subscribed('premium');
    }

    /**
     * Check if user is on trial
     */
    public function onPremiumTrial(): bool
    {
        // Use generic trial (trial_ends_at on users table)
        return $this->onTrial();
    }

    /**
     * Get remaining trial days
     */
    public function trialDaysRemaining(): int
    {
        if (! $this->onTrial()) {
            return 0;
        }

        return max(0, $this->trial_ends_at->diffInDays(now()));
    }

    /**
     * Check if user can upload DNA kit
     */
    public function canUploadDna(): bool
    {
        // When premium features are enabled, allow unlimited uploads for all users
        if (config('premium.enabled')) {
            return true;
        }

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
        if (! $this->isPremium()) {
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
        $level = (int) ($this->level ?? 1);
        $totalPoints = (int) ($this->total_points ?? 0);

        $pointsForNextLevel = $this->getPointsRequiredForLevel($level + 1);
        $pointsForCurrentLevel = $this->getPointsRequiredForLevel($level);
        $progressToNextLevel = $totalPoints - $pointsForCurrentLevel;
        $pointsNeededForNextLevel = $pointsForNextLevel - $pointsForCurrentLevel;

        return [
            'current_level' => $level,
            'total_points' => $totalPoints,
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
        return ($level - 1) ** 2 * 100;
    }

    /**
     * Update user level based on total points
     */
    public function updateLevel(): void
    {
        $newLevel = $this->calculateLevelFromPoints($this->total_points);

        if ($newLevel > $this->level) {
            $oldLevel = (int) $this->level;
            $this->level = $newLevel;
            $this->save();

            // Dispatch level up event
            event(new UserLeveledUp($this, $oldLevel, $newLevel));
        }
    }

    /**
     * Calculate level from total points
     */
    private function calculateLevelFromPoints(?int $points): int
    {
        $points = $points ?? 0;
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
            ->whereHas('achievement', function ($query) use ($achievementKey): void {
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
            ->whereHas('achievement', function ($query) use ($achievementKey): void {
                $query->where('key', $achievementKey);
            })
            ->first();
    }

    /**
     * Get user's rank on leaderboard
     */
    public function getLeaderboardRank(): int
    {
        $totalPoints = (int) ($this->total_points ?? 0);

        return User::where('show_on_leaderboard', true)
            ->where('total_points', '>', $totalPoints)
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

    /**
     * Get the user's social connection privacy settings.
     */
    public function socialConnectionPrivacy(): HasOne
    {
        return $this->hasOne(SocialConnectionPrivacy::class);
    }

    /**
     * Get the user's connected accounts.
     */
    public function connectedAccounts(): HasMany
    {
        return $this->hasMany(ConnectedAccount::class);
    }

    /**
     * Get the user's social family connections.
     */
    public function socialFamilyConnections(): HasMany
    {
        return $this->hasMany(SocialFamilyConnection::class);
    }

    /**
     * Get pending social family connections.
     */
    public function pendingSocialConnections(): HasMany
    {
        return $this->hasMany(SocialFamilyConnection::class)
            ->where('status', 'pending');
    }
}
