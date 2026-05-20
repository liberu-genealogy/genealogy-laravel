<?php

namespace Database\Factories;

use App\Models\ConnectedAccount;
use App\Models\SocialFamilyConnection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialFamilyConnection>
 */
class SocialFamilyConnectionFactory extends Factory
{
    protected $model = SocialFamilyConnection::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'connected_account_id' => ConnectedAccount::factory(),
            'matched_social_id' => fake()->uuid(),
            'matched_name' => fake()->name(),
            'matched_email' => fake()->safeEmail(),
            'relationship_type' => 'potential_relative',
            'confidence_score' => fake()->numberBetween(20, 100),
            'matching_criteria' => [
                'common_surnames' => [fake()->lastName(), fake()->lastName()],
            ],
            'status' => 'pending',
        ];
    }

    /**
     * Indicate that the connection is accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    /**
     * Indicate that the connection is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}
