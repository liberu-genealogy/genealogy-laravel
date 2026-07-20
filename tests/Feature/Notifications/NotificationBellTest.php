<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Models\Achievement;
use App\Models\User;
use App\Notifications\AchievementUnlockedNotification;
use App\Notifications\DnaMatchFoundNotification;
use App\Notifications\DuplicatePersonDetectedNotification;
use App\Notifications\RecordSuggestionNotification;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Storing a notification is not delivering it. The database channel writes rows
 * (see DatabaseNotificationChannelTest), but Filament's bell only renders a row
 * whose `data` is in Filament's own shape — a bare {type, count, message} payload
 * renders as an empty, untitled entry. Two things had to be true for a recipient
 * to actually see their notifications: the app panel enables the bell, and every
 * database-channel notification stores a Filament-renderable payload.
 */
class NotificationBellTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_app_panel_shows_the_notification_bell(): void
    {
        $this->assertTrue(Filament::getPanel('app')->hasDatabaseNotifications());
    }

    /**
     * @return array<string, array{0: \Closure}>
     */
    public static function databaseNotifications(): array
    {
        return [
            'dna match' => [fn () => new DnaMatchFoundNotification(3)],
            'duplicate person' => [fn () => new DuplicatePersonDetectedNotification(2)],
            'record suggestion' => [fn () => new RecordSuggestionNotification(4)],
            'achievement' => [fn () => new AchievementUnlockedNotification(Achievement::create([
                'key' => 'first_person', 'name' => 'First Person',
                'description' => 'Added your first person.', 'icon' => 'heroicon-o-user',
                'category' => 'tree', 'points' => 10, 'requirements' => [], 'badge_color' => 'green',
            ]))],
        ];
    }

    #[DataProvider('databaseNotifications')]
    public function test_a_stored_notification_is_renderable_by_the_bell(\Closure $make): void
    {
        $user = User::factory()->create();

        $user->notify($make());

        $data = DatabaseNotification::query()->firstOrFail()->data;

        // Filament reads these; without them the bell renders an empty entry.
        $this->assertSame('filament', $data['format']);
        $this->assertNotEmpty($data['title']);
    }
}
