<?php

namespace App\Filament\App\Widgets;

use Override;
use Illuminate\Contracts\View\View;
use Filament\Widgets\Widget;
use App\Settings\GeneralSettings;

class SocialLinksWidget extends Widget
{
    protected string $view = 'filament.app.widgets.social-links-widget';

    #[Override]
    public function render(): View
    {
        $settings = app(GeneralSettings::class);

        $links = [];

        if ($settings->github_url) {
            $links['GitHub'] = $settings->github_url;
        }

        if ($settings->facebook_url) {
            $links['Facebook'] = $settings->facebook_url;
        }

        if ($settings->twitter_url) {
            $links['Twitter'] = $settings->twitter_url;
        }

        if ($settings->youtube_url) {
            $links['YouTube'] = $settings->youtube_url;
        }

        // Keep the Facebook Groups as fallback
        if (empty($links)) {
            $links = [
                'GitHub' => 'https://www.github.com/liberu-genealogy',
                'Facebook Page' => 'https://www.facebook.com/familytree365',
                'Facebook Groups' => [
                    'Family Tree 365' => 'https://www.facebook.com/groups/familytree365',
                    'Genealogy Chat' => 'https://www.facebook.com/groups/genealogychat',
                    'DNA 365' => 'https://www.facebook.com/groups/dna365',
                ],
            ];
        }

        return view($this->view, [
            'links' => $links,
        ]);
    }
}
