<?php

declare(strict_types=1);

namespace Tests\Feature\Teams;

use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class PermissionTiersTest extends TestCase
{
    /**
     * The four collaboration tiers (SCOPE §10), most -> least privileged,
     * mapped to their exact permission sets.
     *
     * @return array<string, array<int, string>>
     */
    private const TIERS = [
        'admin' => ['create', 'read', 'update', 'delete', 'manage-team'],
        'editor' => ['create', 'read', 'update', 'delete'],
        'contributor' => ['create', 'read', 'update'],
        'viewer' => ['read'],
    ];

    public function test_all_four_tiers_resolve_with_expected_permissions(): void
    {
        foreach (self::TIERS as $key => $expected) {
            $role = Jetstream::findRole($key);

            $this->assertNotNull($role, "Role [{$key}] is not registered.");
            $this->assertEqualsCanonicalizing(
                $expected,
                $role->permissions,
                "Role [{$key}] has unexpected permissions."
            );
        }
    }

    public function test_tiers_form_a_strict_privilege_ladder(): void
    {
        // Each tier must be a strict superset of the tier below it.
        $keys = array_keys(self::TIERS);

        for ($i = 0; $i < count($keys) - 1; $i++) {
            $higher = Jetstream::findRole($keys[$i])->permissions;
            $lower = Jetstream::findRole($keys[$i + 1])->permissions;

            $this->assertEmpty(
                array_diff($lower, $higher),
                "[{$keys[$i]}] must grant everything [{$keys[$i + 1]}] does."
            );
            $this->assertNotEmpty(
                array_diff($higher, $lower),
                "[{$keys[$i]}] must grant strictly more than [{$keys[$i + 1]}]."
            );
        }
    }

    public function test_viewer_is_read_only(): void
    {
        $viewer = Jetstream::findRole('viewer');

        $this->assertContains('read', $viewer->permissions);
        $this->assertNotContains('create', $viewer->permissions);
        $this->assertNotContains('update', $viewer->permissions);
        $this->assertNotContains('delete', $viewer->permissions);
    }

    public function test_contributor_can_create_but_not_delete_or_manage_team(): void
    {
        $contributor = Jetstream::findRole('contributor');

        $this->assertContains('create', $contributor->permissions);
        $this->assertContains('update', $contributor->permissions);
        $this->assertNotContains('delete', $contributor->permissions);
        $this->assertNotContains('manage-team', $contributor->permissions);
    }

    public function test_permissions_registry_covers_every_referenced_permission(): void
    {
        $registered = Jetstream::$permissions;

        foreach (self::TIERS as $key => $permissions) {
            foreach ($permissions as $permission) {
                $this->assertContains(
                    $permission,
                    $registered,
                    "Permission [{$permission}] used by [{$key}] is not in Jetstream::permissions()."
                );
            }
        }
    }
}
