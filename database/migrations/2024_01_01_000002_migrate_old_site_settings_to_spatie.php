<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate data from old site_settings table to new settings table
        $oldSettings = DB::table('site_settings')->first();

        if ($oldSettings) {
            $settingsData = [
                ['group' => 'general', 'name' => 'site_name', 'payload' => json_encode($oldSettings->name ?? config('app.name', 'Liberu Genealogy')), 'locked' => 0],
                ['group' => 'general', 'name' => 'site_email', 'payload' => json_encode($oldSettings->email ?? 'info@example.com'), 'locked' => 0],
                ['group' => 'general', 'name' => 'site_phone', 'payload' => json_encode($oldSettings->phone_01 ?? ''), 'locked' => 0],
                ['group' => 'general', 'name' => 'site_address', 'payload' => json_encode($oldSettings->address ?? ''), 'locked' => 0],
                ['group' => 'general', 'name' => 'site_country', 'payload' => json_encode($oldSettings->country ?? ''), 'locked' => 0],
                ['group' => 'general', 'name' => 'site_currency', 'payload' => json_encode($oldSettings->currency ?? '$'), 'locked' => 0],
                ['group' => 'general', 'name' => 'site_default_language', 'payload' => json_encode($oldSettings->default_language ?? 'en'), 'locked' => 0],
                ['group' => 'general', 'name' => 'facebook_url', 'payload' => json_encode($oldSettings->facebook), 'locked' => 0],
                ['group' => 'general', 'name' => 'twitter_url', 'payload' => json_encode($oldSettings->twitter), 'locked' => 0],
                ['group' => 'general', 'name' => 'github_url', 'payload' => json_encode($oldSettings->github ?? 'https://www.github.com/liberu-genealogy'), 'locked' => 0],
                ['group' => 'general', 'name' => 'youtube_url', 'payload' => json_encode($oldSettings->youtube), 'locked' => 0],
                ['group' => 'general', 'name' => 'footer_copyright', 'payload' => json_encode('© ' . date('Y') . ' ' . ($oldSettings->name ?? config('app.name', 'Liberu Genealogy')) . '. All rights reserved.'), 'locked' => 0],
            ];

            foreach ($settingsData as $setting) {
                DB::table('settings')->updateOrInsert(
                    ['group' => $setting['group'], 'name' => $setting['name']],
                    $setting
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the migrated settings
        DB::table('settings')->where('group', 'general')->delete();
    }
};