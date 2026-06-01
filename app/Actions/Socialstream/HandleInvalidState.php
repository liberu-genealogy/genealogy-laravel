<?php

declare(strict_types=1);

namespace App\Actions\Socialstream;

use Illuminate\Http\Response;
use JoelButcher\Socialstream\Contracts\HandlesInvalidState;
use Laravel\Socialite\Two\InvalidStateException;

class HandleInvalidState implements HandlesInvalidState
{
    /**
     * Handle an invalid state exception from a Socialite provider.
     */
    public function handle(InvalidStateException $exception): Response
    {
        throw $exception;
    }
}
