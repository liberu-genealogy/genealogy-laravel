<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Contact form recipient
    |--------------------------------------------------------------------------
    |
    | Where /contact/send delivers. This must be read through config() and not
    | env(): once `php artisan config:cache` runs — which it does in
    | production — env() returns null outside of config files, and the form
    | silently mails nowhere.
    |
    */
    'to' => env('CONTACT_EMAIL'),
];
