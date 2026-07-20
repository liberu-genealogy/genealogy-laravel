<?php

declare(strict_types=1);

namespace App\Support;

/**
 * The typed result meaning "a provider-backed feature could not run", carrying a
 * human-readable reason the presentation layer renders to the user.
 *
 * It is a distinct type — not a null, false, or empty array — so "we could not
 * look" is never mistaken for "we looked and found nothing". Callers branch on
 * `instanceof Unavailable` and show the reason ("not configured") rather than an
 * empty result.
 */
final class Unavailable
{
    public function __construct(public readonly string $reason) {}
}
