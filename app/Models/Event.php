<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use LaravelLiberu\Calendar\Models\Event as CoreEvent;

class Event extends CoreEvent
{
    use TenantConnectionResolver;
}
