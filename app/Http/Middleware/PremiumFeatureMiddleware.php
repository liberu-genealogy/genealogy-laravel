<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class PremiumFeatureMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $feature = null): mixed
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has premium access
        if (!$user->isPremium()) {
            Notification::make()
                ->title('Premium Feature Required')
                ->body('This feature is only available to Premium subscribers. Upgrade now to unlock all features!')
                ->warning()
                ->persistent()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('upgrade')
                        ->label('Upgrade to Premium')
                        ->url(\Filament\Facades\Filament::getUrl() . '/subscription')
                        ->button()
                        ->color('primary'),
                ])
                ->send();

            return redirect(\Filament\Facades\Filament::getUrl() . '/subscription');
        }

        // Feature-specific checks
        if ($feature) {
            switch ($feature) {
                case 'dna_upload':
                    if (!$user->canUploadDna()) {
                        Notification::make()
                            ->title('DNA Upload Limit Reached')
                            ->body('Standard users can upload 1 DNA kit. Upgrade to Premium for unlimited uploads!')
                            ->warning()
                            ->send();

                        return redirect(\Filament\Facades\Filament::getUrl() . '/subscription');
                    }
                    break;

                case 'duplicate_checker':
                case 'smart_matching':
                    // These are premium-only features, already checked above
                    break;
            }
        }

        return $next($request);
    }
}
