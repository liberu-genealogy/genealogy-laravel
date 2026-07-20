<?php

declare(strict_types=1);

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Support\Fabrication\AllowlistEntry;
use Tests\Support\Fabrication\FabricationScanner;

final class FabricationGateTest extends TestCase
{
    /**
     * The gate. Every generation of randomness in the application source must
     * be excused by an allowlist entry that says why it is not fabrication.
     * The allowlist lives here as data, one justified entry per site.
     *
     * @return list<AllowlistEntry>
     */
    private function allowlist(): array
    {
        return [
            new AllowlistEntry(
                'Services/FacialRecognition/Providers/MockProvider.php', '*', 'mock',
                'Demonstration provider. It fabricates faces and encodings by design, but is '
                .'only instantiated when FACIAL_RECOGNITION_PROVIDER=mock; the config default is '
                ."'none' and an unknown provider name yields no provider, so it is unreachable "
                .'unless deliberately opted in.',
            ),
            new AllowlistEntry(
                'Actions/Jetstream/InviteTeamMember.php', 'Str::random', 'token',
                'Team-member invitation token — a random identifier mailed to the invitee, not a value read as a finding.',
            ),
            new AllowlistEntry(
                'Models/VirtualEventAttendee.php', 'Str::random', 'token',
                'Attendee invitation token — a random identifier, not a domain value.',
            ),
            new AllowlistEntry(
                'Services/DnaImportService.php', 'Str::random', 'identifier',
                'Kit variable_name identifier, generated inside a collision-retry loop that re-rolls until unique.',
            ),
            new AllowlistEntry(
                'Services/VideoConferencing/GoogleMeetService.php', 'uniqid', 'api-request-id',
                'requestId for the Google Meet createRequest call — an external API request identifier.',
            ),
            new AllowlistEntry(
                'Services/VideoConferencing/ZoomService.php', 'Str::random', 'security',
                'Meeting password. Security-bearing randomness, not fabrication: it was deliberately '
                .'moved off str_shuffle to Str::random (CSPRNG-backed) for entropy. Classified as '
                .'security so it is never conflated with an invented domain value.',
            ),
            new AllowlistEntry(
                'Filament/App/Resources/GedcomResource/Pages/CreateGedcom.php', 'Str::uuid', 'identifier',
                'GEDCOM import slug — a unique identifier for the import, not a domain value.',
            ),
            new AllowlistEntry(
                'Jobs/ImportGedcom.php', 'Str::uuid', 'identifier',
                'GEDCOM import slug identifier.',
            ),
            new AllowlistEntry(
                'Jobs/ImportGrampsXml.php', 'Str::uuid', 'identifier',
                'GRAMPS import slug identifier.',
            ),
            new AllowlistEntry(
                'Services/GedcomService.php', 'Str::uuid', 'identifier',
                'GEDCOM import slug identifier.',
            ),
        ];
    }

    public function test_the_application_source_generates_no_unexcused_randomness(): void
    {
        $scanner = new FabricationScanner($this->allowlist());

        $violations = $scanner->scanDirectory(dirname(__DIR__, 2).'/app');

        $this->assertSame([], $violations, "\n".FabricationScanner::explain($violations));
    }

    public function test_it_flags_a_randomness_call_in_source(): void
    {
        $scanner = new FabricationScanner;

        $violations = $scanner->scanSource("<?php\n\$x = random_int(1, 3);\n", 'Bad.php');

        $this->assertCount(1, $violations);
        $this->assertSame(2, $violations[0]->line);
        $this->assertStringContainsString('random_int', $violations[0]->mechanism);
    }

    public function test_it_flags_a_static_randomness_call(): void
    {
        $scanner = new FabricationScanner;

        $violations = $scanner->scanSource("<?php\n\$t = Str::random(32);\n", 'Bad.php');

        $this->assertCount(1, $violations);
        $this->assertSame('Str::random', $violations[0]->mechanism);
    }

    public function test_it_ignores_randomness_names_that_only_appear_in_comments(): void
    {
        $scanner = new FabricationScanner;

        $source = "<?php\n// this used to call random_int(1, 3) to invent faces\n\$x = 1;\n";

        $this->assertSame([], $scanner->scanSource($source, 'Documented.php'));
    }

    public function test_an_allowlisted_site_is_excused(): void
    {
        $scanner = new FabricationScanner([
            new AllowlistEntry('Bad.php', 'random_int', 'identifier', 'kit identifier with collision retry'),
        ]);

        $this->assertSame([], $scanner->scanSource("<?php\n\$x = random_int(1, 3);\n", 'Bad.php'));
    }

    public function test_a_different_mechanism_in_an_allowlisted_file_is_still_flagged(): void
    {
        $scanner = new FabricationScanner([
            new AllowlistEntry('Bad.php', 'Str::random', 'token', 'attendee token'),
        ]);

        $violations = $scanner->scanSource("<?php\n\$x = random_int(1, 3);\n", 'Bad.php');

        $this->assertCount(1, $violations);
        $this->assertSame('random_int', $violations[0]->mechanism);
    }

    public function test_it_flags_a_certainty_coalesced_to_a_literal(): void
    {
        $scanner = new FabricationScanner;

        $violations = $scanner->scanSource("<?php\n\$c = \$face['confidence'] ?? 0;\n", 'Bad.php');

        $this->assertCount(1, $violations);
        $this->assertSame(2, $violations[0]->line);
        $this->assertStringContainsString('confidence', $violations[0]->mechanism);
    }

    public function test_a_certainty_coalesced_to_null_is_honest(): void
    {
        $scanner = new FabricationScanner;

        $this->assertSame([], $scanner->scanSource("<?php\n\$c = \$face['confidence'] ?? null;\n", 'Ok.php'));
    }

    public function test_a_count_coalesced_to_a_literal_is_not_a_certainty(): void
    {
        $scanner = new FabricationScanner;

        $this->assertSame([], $scanner->scanSource("<?php\n\$n = \$stats['people_count'] ?? 0;\n", 'Ok.php'));
    }

    public function test_an_allowlist_entry_without_a_justification_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new FabricationScanner([
            new AllowlistEntry('Bad.php', 'random_int', 'identifier', '   '),
        ]);
    }
}
