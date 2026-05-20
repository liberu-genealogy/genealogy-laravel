<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message'   => fake()->words(),
            'sender_id' => fake()->randomElement([1, 2, 3, 4]),
            'chat_id'   => Chat::factory(),
            'reply_to'  => fake()->randomElement([1, 2, 3, 4]),
        ];
    }
}
