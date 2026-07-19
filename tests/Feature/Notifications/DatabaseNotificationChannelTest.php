<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Models\Achievement;
use App\Models\User;
use App\Notifications\AchievementUnlockedNotification;
use App\Notifications\DnaMatchFoundNotification;
use App\Notifications\DuplicatePersonDetectedNotification;
use App\Notifications\RecordSuggestionNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The database notification channel must actually store notifications.
 *
 * The notifications table declared its primary key as an auto-incrementing
 * integer, while Laravel writes a UUID string. Every insert was rejected, so
 * no in-app notification had ever been stored since the table was created in
 * 2017 — the classes existed, were dispatched, and were thrown away by the
 * database.
 *
 * These tests dispatch for real (no Notification::fake) precisely because the
 * failure was in the write itself; faking the facade is what hid it.
 */
class DatabaseNotificationChannelTest extends TestCase
{
    use RefreshDatabase;

    // Mail is deliberately NOT faked. phpunit.xml already uses the array
    // mailer, so nothing is sent — and letting the mail channel render means
    // a broken toMail() template surfaces here too. Mail is currently the only
    // channel that reaches a user at all (see the class docblock), so hiding it
    // would be the wrong trade.

    public function test_a_database_notification_is_stored_and_readable_by_its_recipient(): void
    {
        $user = User::factory()->create();

        $user->notify(new DnaMatchFoundNotification(3));

        $stored = $user->fresh()->notifications;

        $this->assertCount(1, $stored);
        $this->assertSame(DnaMatchFoundNotification::class, $stored->first()->type);
        $this->assertSame(3, $stored->first()->data['count']);
    }

    public function test_the_primary_key_is_the_uuid_laravel_writes(): void
    {
        $user = User::factory()->create();

        $user->notify(new DnaMatchFoundNotification(1));

        $id = $user->fresh()->notifications->first()->id;

        // A 36-character UUID, not an integer. Guards against the column being
        // "fixed" to a string type that silently truncates or re-numbers.
        $this->assertIsString($id);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            $id
        );
    }

    public function test_every_database_channel_notification_stores(): void
    {
        $user = User::factory()->create();

        $user->notify(new DnaMatchFoundNotification(1));
        $user->notify(new RecordSuggestionNotification(2));
        $user->notify(new DuplicatePersonDetectedNotification(4));
        $user->notify(new AchievementUnlockedNotification($this->achievement()));

        $this->assertCount(4, $user->fresh()->notifications);
    }

    /**
     * The achievement notification is the one whose failure was genuinely
     * invisible: its listener wraps dispatch in a try/catch that logs a warning
     * and swallows the exception. It also builds the richest payload of the
     * four, dereferencing the achievement across eight keys.
     */
    public function test_the_achievement_notification_stores_its_full_payload(): void
    {
        $user = User::factory()->create();
        $achievement = $this->achievement();

        $user->notify(new AchievementUnlockedNotification($achievement));

        $data = $user->fresh()->notifications->first()->data;

        $this->assertSame('achievement_unlocked', $data['type']);
        $this->assertSame($achievement->id, $data['achievement_id']);
        $this->assertSame($achievement->key, $data['achievement_key']);
        $this->assertSame($achievement->points, $data['points_awarded']);
    }

    private function achievement(): Achievement
    {
        return Achievement::create([
            'key' => 'first_person',
            'name' => 'First Person',
            'description' => 'Added your first person.',
            'icon' => 'heroicon-o-user',
            'category' => 'tree',
            'points' => 10,
            'requirements' => [],
            'badge_color' => 'green',
        ]);
    }

    public function test_notifications_can_be_marked_read(): void
    {
        $user = User::factory()->create();

        $user->notify(new DnaMatchFoundNotification(1));

        $this->assertCount(1, $user->fresh()->unreadNotifications);

        $user->fresh()->unreadNotifications->markAsRead();

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
