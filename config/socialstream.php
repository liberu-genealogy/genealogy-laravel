<?php

return [
    'middleware' => ['web'],
    'prompt'     => 'Or continue with',
    'providers'  => [
        'bitbucket',
        'facebook',
        'github',
        'gitlab',
        'google',
        'linkedin',
        'linkedin-openid',
        'slack',
        // 'twitter' is OAuth 1.0 — excluded: requires live API keys even for redirects
        'twitter-oauth-2',
    ],
    'component' => 'socialstream::components.socialstream',
];
