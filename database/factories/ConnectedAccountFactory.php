<?php

namespace Database\Factories;

use App\Models\ConnectedAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JoelButcher\Socialstream\Providers;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConnectedAccount>
 */
class ConnectedAccountFactory extends Factory
{
    protected $model = ConnectedAccount::class;

    public function definition(): array
    {
        return [
            'provider'      => fake()->randomElement(Providers::all()),
            'provider_id'   => fake()->numerify('########'),
            'name'          => fake()->name(),
            'email'         => fake()->safeEmail(),
            'token'         => Str::random(432),
            'refresh_token' => Str::random(432),
            'enable_family_matching' => false,
            'cached_profile_data' => null,
            'last_synced_at' => null,
        ];
    }

    /**
     * Indicate that family matching is enabled.
     */
    public function withFamilyMatching(): static
    {
        return $this->state(fn (array $attributes) => [
            'enable_family_matching' => true,
            'last_synced_at' => now(),
        ]);
    }
}
