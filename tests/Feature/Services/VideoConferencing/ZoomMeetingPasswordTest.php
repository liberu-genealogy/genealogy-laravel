<?php

declare(strict_types=1);

namespace Tests\Feature\Services\VideoConferencing;

use App\Services\VideoConferencing\ZoomService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Meeting passwords gate access to real meetings and are sent to Zoom.
 *
 * They were produced by str_shuffle() on a fixed alphabet, taking the first
 * eight characters. The defect that matters is predictability — str_shuffle
 * draws from Mt19937, whose state is recoverable from observed output.
 *
 * Be clear about what this file does and does not pin. It asserts that
 * characters are sampled with replacement, which fails deterministically
 * against a permutation. It does NOT assert the source is cryptographically
 * secure, and no reasonable unit test can: a regression to
 * `$alphabet[mt_rand(0, 61)]` in a loop samples with replacement and would
 * pass everything here while restoring the actual vulnerability.
 *
 * That property is held by static analysis instead — the fabrication gate
 * (issue 05) classifies non-cryptographic generators separately from ordinary
 * allowlisted randomness precisely so this cannot quietly come back.
 *
 * These tests capture the password actually sent to Zoom rather than reaching
 * into the generator, so they describe what an attacker would observe.
 */
class ZoomMeetingPasswordTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.zoom.base_url' => 'https://api.zoom.us/v2',
            'services.zoom.key' => 'test-key',
            'services.zoom.secret' => 'test-secret',
            'services.zoom.account_id' => 'test-account',
        ]);
    }

    public function test_the_password_sent_to_zoom_is_eight_alphanumeric_characters(): void
    {
        $password = $this->capturePassword();

        $this->assertSame(8, strlen($password));
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{8}$/', $password);
    }

    /**
     * The load-bearing assertion. Under str_shuffle a repeated character is
     * impossible by construction, so this fails deterministically against the
     * old implementation.
     *
     * Sampling 8 characters with replacement from 62 leaves all eight distinct
     * 62.44% of the time, so a repeat appears in ~37.6% of draws. Over 60 draws
     * a false negative has probability 0.6244^60, about 5e-13 — negligible,
     * and 60 is cheap enough that this does not dominate the suite.
     */
    public function test_passwords_are_sampled_with_replacement_so_characters_can_repeat(): void
    {
        $sawRepeat = false;

        for ($i = 0; $i < 60; $i++) {
            $password = $this->capturePassword();

            if (count(array_unique(str_split($password))) < 8) {
                $sawRepeat = true;
                break;
            }
        }

        $this->assertTrue(
            $sawRepeat,
            'No password in 60 draws contained a repeated character, which means '
            .'the alphabet is being permuted rather than sampled. That shrinks the '
            .'keyspace and is what str_shuffle() did.'
        );
    }

    public function test_passwords_differ_between_meetings(): void
    {
        $passwords = [];

        for ($i = 0; $i < 50; $i++) {
            $passwords[] = $this->capturePassword();
        }

        $this->assertCount(50, array_unique($passwords));
    }

    public function test_no_password_is_sent_when_one_is_not_required(): void
    {
        $payload = $this->capturePayload(['require_password' => false]);

        $this->assertArrayNotHasKey('password', $payload);
    }

    private function capturePassword(): string
    {
        return $this->capturePayload()['password'];
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function capturePayload(array $overrides = []): array
    {
        Http::fake([
            '*/oauth/token' => Http::response(['access_token' => 'test-token', 'expires_in' => 3600]),
            '*/users/me/meetings' => Http::response([
                'id' => 123456789,
                'password' => 'echoed',
                'start_url' => 'https://zoom.us/s/123',
                'join_url' => 'https://zoom.us/j/123',
                // The service dereferences all of these without a null guard,
                // so a real Zoom response omitting any one of them would fatal.
                'uuid' => 'abc==',
                'host_id' => 'host-1',
                'topic' => 'Family research call',
                'status' => 'waiting',
                'created_at' => '2026-07-19T10:00:00Z',
            ]),
        ]);

        (new ZoomService)->createMeeting([
            'title' => 'Family research call',
            'start_time' => '2026-08-01 10:00:00',
            'end_time' => '2026-08-01 11:00:00',
            'timezone' => 'UTC',
        ] + $overrides);

        // Http::recorded()'s argument is a filter predicate, not an iterator, and
        // it returns early without calling it when nothing was recorded — using
        // it for side effects yields a confusing TypeError rather than a failed
        // assertion. Filter the collection instead.
        $request = Http::recorded()
            ->map(fn (array $pair) => $pair[0])
            ->first(fn ($recorded) => str_contains($recorded->url(), '/users/me/meetings'));

        $this->assertNotNull($request, 'No meeting-creation request reached Zoom.');

        return $request->data();
    }
}
