<?php

declare(strict_types=1);

use JoelButcher\Socialstream\Features;
use JoelButcher\Socialstream\Providers;

return [
    'guard'      => 'web',
    'middleware' => ['web'],
    'prompt'     => 'Or continue with',
    'providers'  => [
        Providers::bitbucket(),
        Providers::facebook(),
        Providers::github(),
        Providers::gitlab(),
        Providers::google(),
        Providers::linkedin(),
        Providers::linkedinOpenId(),
        Providers::slack(),
        // Providers::twitter() is OAuth 1.0 — excluded: requires live API keys even for redirects
        Providers::twitterOAuth2(),
    ],
    'features' => [
        Features::createAccountOnFirstLogin(),
        Features::rememberSession(),
        Features::providerAvatars(),
        Features::refreshOAuthTokens(),
    ],
    'component' => 'socialstream::components.socialstream',
    'home'       => '/dashboard',
    'redirects'  => [
        'login'                => '/dashboard',
        'register'             => '/dashboard',
        'login-failed'         => '/login',
        'registration-failed'  => '/register',
        'provider-linked'      => '/user/profile',
        'provider-link-failed' => '/user/profile',
    ],
];
