<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(fn () => tap(User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]), function (User $user): void {
            $team = $this->createTeam($user);
            $user->switchTeam($team);
            setPermissionsTeamId($team->id);
            // No assignRole('panel_user'): RolesSeeder seeds only super_admin, so
            // that call threw RoleDoesNotExist and 500'd every registration that
            // reached this action. It is vestigial too — User::canAccessPanel()
            // grants the app panel to any authenticated user precisely because
            // requiring panel_user "caused 403 errors immediately after
            // login/registration for new accounts". Nothing checks for it.
        }));
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user)
    {
        return $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
