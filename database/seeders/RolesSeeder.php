<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // super_admin is deliberately created with no team, which under the
        // permission library means it exists in every team rather than one.
        //
        // It used to be pinned to Team::firstOrFail() whenever tenancy was
        // enabled. That is wrong twice over. It scoped the one role that has to
        // be global to whichever team happened to hold the lowest id, so a
        // super admin stopped being one the moment they worked in any other
        // team. And it ran before TeamSeeder, so on a cold install there was no
        // team to find and firstOrFail() aborted the seed outright — which the
        // teams flag being off was all that hid.
        //
        // A role created without a team context is also the only kind a team
        // member cannot forge: anything created from inside a panel picks up
        // that panel's team. That property is what the admin panel's gate
        // relies on — see User::hasGlobalRole().
        $adminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::where('guard_name', 'web')->pluck('id')->toArray();
        $adminRole->syncPermissions($permissions);
    }
}
