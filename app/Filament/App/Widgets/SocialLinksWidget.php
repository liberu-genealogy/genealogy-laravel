<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\Widget;

class SocialLinksWidget extends Widget
{
    protected static string $view = 'filament.app.widgets.social-links-widget';

    public function render()
    {
        return view(static::$view, [
            'links' => [
                'GitHub' => 'https://www.github.com/liberu-genealogy',
                'Facebook Page' => 'https://www.facebook.com/familytree365',
                'Facebook Groups' => [
                    'Family Tree 365' => 'https://www.facebook.com/groups/familytree365',
                    'Genealogy Chat' => 'https://www.facebook.com/groups/genealogychat',
                    'DNA 365' => 'https://www.facebook.com/groups/dna365',
                ],
            ],
        ]);
    }
}