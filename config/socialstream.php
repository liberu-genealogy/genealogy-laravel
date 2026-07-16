<?php

declare(strict_types=1);

use JoelButcher\Socialstream\Features;
use JoelButcher\Socialstream\Providers;

return [
    'guard' => 'web',
    'middleware' => ['web'],
    'prompt' => 'Or continue with',
    'providers' => [
        Providers::bitbucket(),
        Providers::facebook(),
        Providers::github(),
        Providers::gitlab(),
        Providers::google(),
        // NOTE: linkedin() (legacy OAuth2) and linkedinOpenId() are both enabled
        // and both render a button labelled "LinkedIn" — the login page shows two
        // identical buttons. OpenID Connect supersedes the legacy provider, so
        // one of these should go, but SocialstreamRegistrationTest asserts both
        // are present, so the choice is a product decision rather than a cleanup.
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
    'home' => '/dashboard',
    'redirects' => [
        'login' => '/dashboard',
        'register' => '/dashboard',
        'login-failed' => '/login',
        'registration-failed' => '/register',
        'provider-linked' => '/user/profile',
        'provider-link-failed' => '/user/profile',
    ],
];
