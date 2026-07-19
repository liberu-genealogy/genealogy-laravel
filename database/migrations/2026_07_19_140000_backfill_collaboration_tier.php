<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Gives every existing membership an explicit collaboration tier.
 *
 * The tier column is nullable and, until now, nothing read it — so rows were
 * created without one and it did not matter. It matters from this release:
 * AppResource resolves authorisation through the tier, and a membership with
 * none is refused everything, reading included.
 *
 * That is the right runtime answer to "no tier" and the wrong thing to do to
 * people who already had access. Only a team owner can set a tier, so an
 * existing collaborator would be locked out with no way to ask for it back
 * except out of band.
 *
 * Viewer, not editor, because a null carries no evidence of what anyone
 * intended, and read-only is the reading that cannot grant something nobody
 * meant to grant. This does narrow existing access, deliberately: before this
 * release every member could delete anything, so any tier at all is a
 * reduction. Owners can raise individuals from the Members page.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('team_user') || ! Schema::hasColumn('team_user', 'role')) {
            return;
        }

        DB::table('team_user')->whereNull('role')->update(['role' => 'viewer']);
    }

    /**
     * Not reversed. Restoring the nulls would restore the lockout, and there is
     * no record of which rows were originally null once they are not.
     */
    public function down(): void {}
};
