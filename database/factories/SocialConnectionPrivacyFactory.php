<?php

namespace Database\Factories;

use App\Models\SocialConnectionPrivacy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialConnectionPrivacy>
 */
class SocialConnectionPrivacyFactory extends Factory
{
    protected $model = SocialConnectionPrivacy::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'allow_family_discovery' => true,
            'show_profile_to_matches' => true,
            'share_tree_with_matches' => false,
            'allow_contact_from_matches' => true,
            'blocked_users' => null,
        ];
    }

    /**
     * Indicate that family discovery is disabled.
     */
    public function discoveryDisabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'allow_family_discovery' => false,
        ]);
    }
}
