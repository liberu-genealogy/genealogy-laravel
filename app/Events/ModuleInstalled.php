<?php

declare(strict_types=1);

namespace App\Events;

use App\Modules\Contracts\ModuleInterface;
use Illuminate\Foundation\Events\Dispatchable;

class ModuleInstalled
{
    use Dispatchable;

    public function __construct(public ModuleInterface $module) {}
}
