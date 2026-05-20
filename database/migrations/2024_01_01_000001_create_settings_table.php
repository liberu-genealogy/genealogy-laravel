<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', config('app.name', 'Liberu Genealogy'));
        $this->migrator->add('general.site_email', 'info@example.com');
        $this->migrator->add('general.site_phone', '');
        $this->migrator->add('general.site_address', '');
        $this->migrator->add('general.site_country', '');
        $this->migrator->add('general.site_currency', '$');
        $this->migrator->add('general.site_default_language', 'en');
        $this->migrator->add('general.facebook_url', null);
        $this->migrator->add('general.twitter_url', null);
        $this->migrator->add('general.github_url', 'https://www.github.com/liberu-genealogy');
        $this->migrator->add('general.youtube_url', null);
        $this->migrator->add('general.footer_copyright', '© ' . date('Y') . ' ' . config('app.name', 'Liberu Genealogy') . '. All rights reserved.');
    }
};