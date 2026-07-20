<?php

declare(strict_types=1);

namespace Tests\Support\Fabrication;

/**
 * One deliberately-excused use of randomness in the application source.
 *
 * Every entry names the exact site and the exact mechanism, and carries a
 * written reason a reviewer can evaluate — an entry without one is rejected,
 * so adding to the allowlist is a conscious act rather than a way to silence
 * the gate. The category separates ordinary identifier/token generation from
 * security-bearing randomness, so the two concerns are never conflated.
 */
final class AllowlistEntry
{
    public const CATEGORIES = ['identifier', 'token', 'api-request-id', 'mock', 'security'];

    /**
     * @param  string  $path  path relative to app/ (or the label passed to scanSource)
     * @param  string  $mechanism  the exact mechanism excused, or '*' for every mechanism in the file
     * @param  string  $category  one of self::CATEGORIES
     */
    public function __construct(
        public readonly string $path,
        public readonly string $mechanism,
        public readonly string $category,
        public readonly string $reason,
    ) {}

    public function excuses(Violation $violation): bool
    {
        return $this->path === $violation->file
            && ($this->mechanism === '*' || $this->mechanism === $violation->mechanism);
    }
}
