<?php

use JoelButcher\Socialstream\Providers;

return [
    'middleware' => ['web'],
    'prompt'     => 'Or Login Via',
    'providers'  => [
        Providers::facebook(),
        Providers::google(),
        Providers::twitter(),
        // Providers::github(),
    ],
    'component' => 'socialstream::components.socialstream',
];
