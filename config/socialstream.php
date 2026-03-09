<?php

return [
    'middleware' => ['web'],
    'prompt'     => 'Or Login Via',
    'providers'  => [
        'facebook',
        'google',
        'twitter',
        // 'github',
    ],
    'component' => 'socialstream::components.socialstream',
];
