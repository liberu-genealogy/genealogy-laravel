<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            // Beginner Achievements
            [
                'key' => 'first_person_added',
                'name' => 'First Steps',
                'description' => 'Add your first person to the family tree',
                'icon' => '👤',
                'category' => 'milestone',
                'points' => 50,
                'requirements' => ['count' => 1],
                'badge_color' => 'green',
                'sort_order' => 1,
            ],
            [
                'key' => 'first_family_created',
                'name' => 'Family Founder',
                'description' => 'Create your first family relationship',
                'icon' => '👨‍👩‍👧‍👦',
                'category' => 'milestone',
                'points' => 75,
                'requirements' => ['count' => 1],
                'badge_color' => 'blue',
                'sort_order' => 2,
            ],

            // Research Achievements
            [
                'key' => 'family_builder',
                'name' => 'Family Builder',
                'description' => 'Add 10 people to your family tree',
                'icon' => '🏗️',
                'category' => 'research',
                'points' => 200,
                'requirements' => ['count' => 10],
                'badge_color' => 'blue',
                'sort_order' => 10,
            ],
            [
                'key' => 'genealogy_researcher',
                'name' => 'Genealogy Researcher',
                'description' => 'Add 50 people to your family tree',
                'icon' => '🔍',
                'category' => 'research',
                'points' => 500,
                'requirements' => ['count' => 50],
                'badge_color' => 'purple',
                'sort_order' => 11,
            ],
            [
                'key' => 'family_historian',
                'name' => 'Family Historian',
                'description' => 'Add 100 people to your family tree',
                'icon' => '📚',
                'category' => 'research',
                'points' => 1000,
                'requirements' => ['count' => 100],
                'badge_color' => 'gold',
                'sort_order' => 12,
            ],

            // Relationship Achievements
            [
                'key' => 'family_connector',
                'name' => 'Family Connector',
                'description' => 'Create 5 family relationships',
                'icon' => '🔗',
                'category' => 'research',
                'points' => 150,
                'requirements' => ['count' => 5],
                'badge_color' => 'green',
                'sort_order' => 20,
            ],
            [
                'key' => 'relationship_expert',
                'name' => 'Relationship Expert',
                'description' => 'Create 20 family relationships',
                'icon' => '💞',
                'category' => 'research',
                'points' => 400,
                'requirements' => ['count' => 20],
                'badge_color' => 'purple',
                'sort_order' => 21,
            ],

            // Event Documentation Achievements
            [
                'key' => 'event_chronicler',
                'name' => 'Event Chronicler',
                'description' => 'Document 10 life events',
                'icon' => '📅',
                'category' => 'research',
                'points' => 200,
                'requirements' => ['count' => 10],
                'badge_color' => 'blue',
                'sort_order' => 30,
            ],
            [
                'key' => 'life_documenter',
                'name' => 'Life Documenter',
                'description' => 'Document 50 life events',
                'icon' => '📖',
                'category' => 'research',
                'points' => 600,
                'requirements' => ['count' => 50],
                'badge_color' => 'purple',
                'sort_order' => 31,
            ],

            // Photo/Media Achievements
            [
                'key' => 'photo_archivist',
                'name' => 'Photo Archivist',
                'description' => 'Upload 5 family photos',
                'icon' => '📸',
                'category' => 'general',
                'points' => 100,
                'requirements' => ['count' => 5],
                'badge_color' => 'green',
                'sort_order' => 40,
            ],
            [
                'key' => 'memory_keeper',
                'name' => 'Memory Keeper',
                'description' => 'Upload 25 family photos',
                'icon' => '🖼️',
                'category' => 'general',
                'points' => 300,
                'requirements' => ['count' => 25],
                'badge_color' => 'blue',
                'sort_order' => 41,
            ],

            // Point-based Achievements
            [
                'key' => 'point_collector',
                'name' => 'Point Collector',
                'description' => 'Earn 1,000 total points',
                'icon' => '⭐',
                'category' => 'milestone',
                'points' => 100,
                'requirements' => ['points' => 1000],
                'badge_color' => 'bronze',
                'sort_order' => 50,
            ],
            [
                'key' => 'high_achiever',
                'name' => 'High Achiever',
                'description' => 'Earn 5,000 total points',
                'icon' => '🌟',
                'category' => 'milestone',
                'points' => 500,
                'requirements' => ['points' => 5000],
                'badge_color' => 'silver',
                'sort_order' => 51,
            ],
            [
                'key' => 'legend',
                'name' => 'Legend',
                'description' => 'Earn 10,000 total points',
                'icon' => '👑',
                'category' => 'milestone',
                'points' => 1000,
                'requirements' => ['points' => 10000],
                'badge_color' => 'gold',
                'sort_order' => 52,
            ],

            // Level-based Achievements
            [
                'key' => 'level_up',
                'name' => 'Rising Star',
                'description' => 'Reach Level 5',
                'icon' => '🚀',
                'category' => 'milestone',
                'points' => 200,
                'requirements' => ['level' => 5],
                'badge_color' => 'blue',
                'sort_order' => 60,
            ],
            [
                'key' => 'experienced_researcher',
                'name' => 'Experienced Researcher',
                'description' => 'Reach Level 10',
                'icon' => '🎓',
                'category' => 'milestone',
                'points' => 500,
                'requirements' => ['level' => 10],
                'badge_color' => 'purple',
                'sort_order' => 61,
            ],

            // Activity-based Achievements
            [
                'key' => 'daily_researcher',
                'name' => 'Daily Researcher',
                'description' => 'Research for 7 consecutive days',
                'icon' => '📆',
                'category' => 'social',
                'points' => 300,
                'requirements' => ['days' => 7],
                'badge_color' => 'green',
                'sort_order' => 70,
            ],
            [
                'key' => 'dedicated_genealogist',
                'name' => 'Dedicated Genealogist',
                'description' => 'Research for 30 consecutive days',
                'icon' => '🔥',
                'category' => 'social',
                'points' => 1000,
                'requirements' => ['days' => 30],
                'badge_color' => 'red',
                'sort_order' => 71,
            ],

            // Meta Achievements
            [
                'key' => 'achievement_hunter',
                'name' => 'Achievement Hunter',
                'description' => 'Unlock 5 achievements',
                'icon' => '🏆',
                'category' => 'milestone',
                'points' => 250,
                'requirements' => ['count' => 5],
                'badge_color' => 'gold',
                'sort_order' => 80,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['key' => $achievement['key']],
                $achievement
            );
        }
    }
}