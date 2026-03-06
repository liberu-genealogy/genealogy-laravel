<?php

namespace App\Actions\Socialstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use JoelButcher\Socialstream\Contracts\CreatesConnectedAccounts;
use JoelButcher\Socialstream\Contracts\CreatesUserFromProvider;
use JoelButcher\Socialstream\Socialstream;
use Laravel\Socialite\Contracts\User as ProviderUserContract;

class CreateUserWithTeamsFromProvider extends CreateUserFromProvider
{
    // Intentionally empty – the base class already implements the full logic.
    // Keeping this subclass allows the configuration that expects this
    // class name to continue functioning without change.
}
