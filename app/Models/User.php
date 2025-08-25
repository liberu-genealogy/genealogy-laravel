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
}
