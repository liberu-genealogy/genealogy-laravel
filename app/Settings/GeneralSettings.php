<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public string $site_email;
    public string $site_phone;
    public string $site_address;
    public string $site_country;
    public string $site_currency;
    public string $site_default_language;

    // Social Media Links
    public ?string $facebook_url;
    public ?string $twitter_url;
    public ?string $github_url;
    public ?string $youtube_url;

    // Footer Copyright
    public string $footer_copyright;

    public static function group(): string
    {
        return 'general';
    }
}