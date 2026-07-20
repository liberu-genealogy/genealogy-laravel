<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use Filament\Facades\Filament;
use Tests\TestCase;

/**
 * Pins the app-panel navigation taxonomy so a stray $navigationGroup string
 * cannot silently create an eleventh group, and a rename in the central list
 * cannot orphan the resources that name the old string.
 *
 * The agreed group set and the reasoning behind it are documented above
 * AppPanelProvider::navigationGroups().
 */
final class AppNavigationGroupsTest extends TestCase
{
    /**
     * The one true list, in sidebar order. Editing the central list without
     * editing this array (or vice versa) fails the build on purpose.
     *
     * @var list<string>
     */
    private const GROUPS = [
        '🏠 Dashboard',
        '👥 Family Tree',
        '🗂️ GEDCOM Detail',
        '📚 Sources & Citations',
        '🧬 DNA & Matching',
        '📊 Charts & Reports',
        '📋 Research Workspace',
        '🛠️ Data & Import',
        '🎉 Community',
        '👤 Account & Settings',
    ];

    public function test_the_app_panel_declares_exactly_the_agreed_groups_in_order(): void
    {
        $labels = array_values(array_map(
            fn ($group): ?string => $group->getLabel(),
            Filament::getPanel('app')->getNavigationGroups(),
        ));

        $this->assertSame(self::GROUPS, $labels);
    }

    public function test_no_resource_or_page_names_a_group_outside_the_list(): void
    {
        $dir = app_path('Filament/App');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));

        $offenders = [];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $source = (string) file_get_contents($file->getPathname());

            if (! preg_match_all('/\$navigationGroup\s*=\s*[\'"]([^\'"]+)[\'"]/', $source, $matches)) {
                continue;
            }

            foreach ($matches[1] as $group) {
                if (! in_array($group, self::GROUPS, true)) {
                    $offenders[] = str_replace($dir.'/', '', $file->getPathname()).": {$group}";
                }
            }
        }

        $this->assertSame([], $offenders, "\nGroups not in the central list:\n".implode("\n", $offenders));
    }
}
