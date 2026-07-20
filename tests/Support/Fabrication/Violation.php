<?php

declare(strict_types=1);

namespace Tests\Support\Fabrication;

/**
 * One place in the application source where the fabrication gate found either
 * the generation of randomness or a hardcoded stand-in for a measured value,
 * and no allowlist entry excused it.
 */
final class Violation
{
    public function __construct(
        public readonly string $file,
        public readonly int $line,
        public readonly string $mechanism,
        public readonly string $reason,
    ) {}

    public function describe(): string
    {
        return "{$this->file}:{$this->line} — {$this->mechanism}\n    {$this->reason}";
    }
}
