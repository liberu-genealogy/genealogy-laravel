<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Settings\GeneralSettings;

class SiteSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = app(GeneralSettings::class);

        $settings->site_name = config('app.name', 'Liberu Genealogy');
        $settings->site_email = 'info@liberugenealogy.com';
        $settings->site_phone = '+44 208 050 5865';
        $settings->site_address = '123 Genealogy St, London, UK';
        $settings->site_country = 'United Kingdom';
        $settings->site_currency = '£';
        $settings->site_default_language = 'en';
        $settings->facebook_url = 'https://www.facebook.com/familytree365';
        $settings->twitter_url = null;
        $settings->github_url = 'https://www.github.com/liberu-genealogy';
        $settings->youtube_url = null;
        $settings->footer_copyright = '© ' . date('Y') . ' ' . config('app.name', 'Liberu Genealogy') . '. All rights reserved.';

        $settings->save();
    }
}
