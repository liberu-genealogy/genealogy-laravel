<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use JoelButcher\Socialstream\Providers;
use Tests\TestCase;

class SocialstreamRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_socialstream_providers_class_availability(): void
    {
        $this->assertTrue(class_exists(Providers::class));
    }

    /**
     * @dataProvider socialMediaProviders
     */
    public function test_socialstream_config_has_social_media_providers(string $provider): void
    {
        $this->assertContains($provider, config('socialstream.providers'));
    }

    public static function socialMediaProviders(): array
    {
        return [
            'bitbucket'       => [Providers::bitbucket()],
            'facebook'        => [Providers::facebook()],
            'github'          => [Providers::github()],
            'gitlab'          => [Providers::gitlab()],
            'google'          => [Providers::google()],
            'linkedin'        => [Providers::linkedin()],
            'linkedin-openid' => [Providers::linkedinOpenId()],
            'slack'           => [Providers::slack()],
            'twitter-oauth-2' => [Providers::twitterOAuth2()],
            // twitterOAuth1 excluded: OAuth 1.0 requires live API keys even for redirect
        ];
    }

    public function test_socialstream_config_excludes_twitter_oauth1(): void
    {
        $this->assertNotContains(Providers::twitterOAuth1(), config('socialstream.providers'));
    }
}
