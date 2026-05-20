<?php

namespace Database\Factories;

use App\Models\Event;
use LaravelLiberu\Calendar\Database\Factories\EventFactory as CoreEventFactory;

class EventFactory extends CoreEventFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;
}
