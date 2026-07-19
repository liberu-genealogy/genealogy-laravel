<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $adminPassword = Str::random(12);
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make($adminPassword),
            'email_verified_at' => now(),
        ]);

        $team = Team::firstOrFail();
        $adminUser->teams()->syncWithoutDetaching([$team->id]);
        $adminUser->forceFill(['current_team_id' => $team->id])->save();

        // Role assignments carry a team now, and seeding has no request to take
        // one from, so it is named explicitly. Without this the assignment
        // writes a null team into a column that is part of the primary key and
        // the seed aborts — which is a cold install, not an edge case.
        //
        // The role itself stays team-less (see RolesSeeder); only this grant of
        // it is scoped. The admin panel gate reads the role rather than the
        // grant, so this admin keeps the panel from whichever team they are in.
        app(PermissionRegistrar::class)->setPermissionsTeamId($team->id);

        $role = Role::where('name', 'super_admin')->firstOrFail();
        $adminUser->assignRole($role);

        // Print passwords to console
        echo "Admin password: {$adminPassword}\n";
    }
}
