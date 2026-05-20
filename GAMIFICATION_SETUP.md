# Gamification System Setup Guide

This guide will help you set up the comprehensive gamification system for your genealogy Laravel application.

## Features Implemented

✅ **Point System**: Users earn points for various genealogy activities
✅ **Achievement System**: Unlock achievements based on research milestones
✅ **Level System**: Progress through levels based on total points earned
✅ **Leaderboards**: Compare progress with other users (with privacy controls)
✅ **Progress Tracking**: Visual progress indicators for ongoing achievements
✅ **Real-time Notifications**: Live updates when achievements are unlocked
✅ **Activity Tracking**: Monitor daily research streaks and activity

## Quick Setup

Run the setup command to get started quickly:

```bash
php artisan gamification:setup
```

For a fresh installation with sample data:

```bash
php artisan gamification:setup --fresh
```

## Manual Setup Steps

### 1. Register the Service Provider

Add the GamificationServiceProvider to your `config/app.php` file in the providers array:

```php
'providers' => [
    // ... other providers
    App\Providers\GamificationServiceProvider::class,
],
```

### 2. Run Migrations

```bash
php artisan migrate
```

### 3. Seed Achievements

```bash
php artisan db:seed --class=AchievementSeeder
```

### 4. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

## Usage

### Accessing the Dashboard

Visit `/gamification` to view the gamification dashboard where users can:
- View their current level and points
- See unlocked achievements
- Track progress on ongoing achievements
- View leaderboards
- Monitor their activity streaks

### Point System

Users automatically earn points for:
- **Adding a person**: 25 points
- **Creating a family relationship**: 50 points
- **Adding life events**: 15-30 points (varies by event type)
- **Updating information**: 5-15 points
- **Unlocking achievements**: Variable bonus points
- **Leveling up**: 10 points × new level

### Achievement Categories

- **Milestones**: First steps, reaching levels, point thresholds
- **Research**: Adding people, creating relationships, documenting events
- **General**: Photo uploads, profile completion
- **Social**: Daily activity streaks, leaderboard participation

### Level System

Users progress through levels based on total points:
- Level 1: 0 points
- Level 2: 100 points
- Level 3: 400 points
- Level 4: 900 points
- And so on... (level² × 100 formula)

## Customization

### Adding New Achievements

1. Add achievement data to `database/seeders/AchievementSeeder.php`
2. Update the `GamificationService::checkAchievementRequirements()` method
3. Run the seeder: `php artisan db:seed --class=AchievementSeeder`

### Modifying Point Values

Edit the point values in the observer classes:
- `app/Observers/PersonObserver.php`
- `app/Observers/FamilyObserver.php`
- `app/Observers/PersonEventObserver.php`

### Customizing the Dashboard

The Livewire component and Blade view can be customized:
- Component: `app/Http/Livewire/GamificationDashboard.php`
- View: `resources/views/livewire/gamification-dashboard.blade.php`

## API Integration

The `GamificationService` provides methods for:

```php
// Award points manually
$gamificationService->awardPoints($user, 'custom_activity', 100, 'Custom description');

// Check achievements
$gamificationService->checkAchievements($user);

// Get user statistics
$stats = $gamificationService->getUserStats($user);

// Get leaderboard
$leaderboard = $gamificationService->getLeaderboard(10, 'all_time');
```

## Events and Listeners

The system dispatches events for:
- `AchievementUnlocked`: When a user unlocks an achievement
- `UserLeveledUp`: When a user reaches a new level

These events trigger:
- Email notifications
- Real-time browser notifications
- Logging
- Additional point bonuses

## Privacy Controls

Users can control their leaderboard visibility:
- Toggle visibility in the gamification dashboard
- Hidden users don't appear in public leaderboards
- Personal stats remain private

## Database Schema

The system adds these tables:
- `achievements`: Achievement definitions
- `user_achievements`: Unlocked achievements per user
- `user_points`: Point transaction history
- `user_progress`: Progress tracking for incomplete achievements
- Additional columns to `users` table for gamification data

## Troubleshooting

### Common Issues

1. **Achievements not unlocking**: Check that observers are registered in `GamificationServiceProvider`
2. **Points not awarded**: Ensure user is authenticated when performing actions
3. **Dashboard not loading**: Verify route is registered and Livewire is installed
4. **Events not firing**: Check `EventServiceProvider` registration

### Debug Commands

```bash
# Check if migrations ran
php artisan migrate:status

# Verify achievements exist
php artisan tinker
>>> App\Models\Achievement::count()

# Test point awarding
>>> $user = App\Models\User::first()
>>> app(App\Services\GamificationService::class)->awardPoints($user, 'test', 100)
```

## Performance Considerations

- Achievement checking is optimized to only run for relevant activities
- Leaderboard queries are indexed for performance
- Progress tracking uses efficient database queries
- Consider caching for high-traffic applications

## Future Enhancements

Potential additions:
- Team/family group achievements
- Seasonal challenges and events
- Achievement sharing on social media
- Advanced analytics and reporting
- Mobile app integration
- Gamification widgets for other pages

## Support

For issues or questions about the gamification system:
1. Check the troubleshooting section above
2. Review the code comments in the service classes
3. Test with the debug commands provided
4. Ensure all dependencies are properly installed

---

**Happy researching and may your family tree grow with every achievement unlocked! 🌳🏆**