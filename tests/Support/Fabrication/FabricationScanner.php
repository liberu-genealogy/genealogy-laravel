<?php

declare(strict_types=1);

namespace Tests\Support\Fabrication;

/**
 * The executable definition of "this application does not invent data".
 *
 * It tokenises the application source and reports every generation of
 * randomness (and, later, every hardcoded stand-in for a measured value)
 * that no allowlist entry excuses. Tokenising rather than pattern-matching is
 * deliberate: the grep-based sweep this replaces used word boundaries and so
 * could not see `random_int` embedded in a larger identifier, and it matched
 * the same call names inside comments. token_get_all resolves each call to its
 * exact name and drops comments, closing both gaps.
 */
final class FabricationScanner
{
    /** Global functions whose only purpose is to produce randomness. */
    private const RANDOM_FUNCTIONS = [
        'rand', 'mt_rand', 'random_int', 'random_bytes', 'uniqid',
        'shuffle', 'str_shuffle', 'array_rand', 'srand', 'mt_srand',
        'openssl_random_pseudo_bytes', 'lcg_value', 'fake',
    ];

    /** Static methods (on the short class name) that produce a random value. */
    private const STATIC_RANDOM = [
        'Str' => ['random', 'password', 'uuid', 'orderedUuid'],
        'Arr' => ['random'],
    ];

    /**
     * Identifier fragments that name a measurement of certainty or match
     * quality. A value you did not measure has no such figure — substituting a
     * literal for it invents one, unlike a count, where an absent count of zero
     * is a true statement.
     */
    private const CERTAINTY_VOCAB = [
        'confidence', 'match_quality', 'quality_score', 'probability',
        'similarity_score', 'certainty',
    ];

    /**
     * @param  list<AllowlistEntry>  $allowlist
     */
    public function __construct(private readonly array $allowlist = [])
    {
        foreach ($allowlist as $entry) {
            if (trim($entry->reason) === '') {
                throw new \InvalidArgumentException(
                    "Allowlist entry for {$entry->path} ({$entry->mechanism}) has no justification. "
                    .'Every allowlist entry must carry a reason a reviewer can evaluate.'
                );
            }

            if (! in_array($entry->category, AllowlistEntry::CATEGORIES, true)) {
                throw new \InvalidArgumentException(
                    "Allowlist entry for {$entry->path} has unknown category '{$entry->category}'."
                );
            }
        }
    }

    /**
     * Scan every .php file under $dir, reporting paths relative to it.
     *
     * @return list<Violation>
     */
    public function scanDirectory(string $dir): array
    {
        $dir = rtrim($dir, '/');
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        $violations = [];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relative = substr($file->getPathname(), strlen($dir) + 1);
            $source = file_get_contents($file->getPathname());

            foreach ($this->scanSource($source, $relative) as $violation) {
                $violations[] = $violation;
            }
        }

        return $violations;
    }

    /**
     * @param  list<Violation>  $violations
     */
    public static function explain(array $violations): string
    {
        if ($violations === []) {
            return '';
        }

        $lines = ['The application source invents data at:'];

        foreach ($violations as $violation) {
            $lines[] = $violation->describe();
        }

        return implode("\n", $lines);
    }

    /** @return list<Violation> */
    public function scanSource(string $source, string $relativePath): array
    {
        $tokens = token_get_all($source);
        $significant = $this->significantTokens($tokens);
        $catchRanges = $this->catchBlockRanges($significant);

        $violations = [];

        foreach ($significant as $i => $token) {
            $violation = $this->randomnessViolation($significant, $i, $relativePath)
                ?? $this->certaintyStandInViolation($significant, $i, $relativePath)
                ?? $this->domainValueInCatchViolation($significant, $i, $relativePath, $catchRanges);

            if ($violation !== null && ! $this->isAllowed($violation)) {
                $violations[] = $violation;
            }
        }

        return $violations;
    }

    /**
     * Index ranges (into the significant-token list) that lie inside a catch
     * block body, so error-handling branches can be checked for the
     * construction of a domain value.
     *
     * @param  list<array{0:int,1:string,2:int}>  $tokens
     * @return list<array{0:int,1:int}>
     */
    private function catchBlockRanges(array $tokens): array
    {
        $ranges = [];
        $n = count($tokens);

        for ($i = 0; $i < $n; $i++) {
            if ($tokens[$i][0] !== T_CATCH) {
                continue;
            }

            $open = $i + 1;
            while ($open < $n && $tokens[$open][1] !== '{') {
                $open++;
            }

            if ($open >= $n) {
                continue;
            }

            $depth = 0;
            $close = $open;
            for (; $close < $n; $close++) {
                if ($tokens[$close][1] === '{') {
                    $depth++;
                } elseif ($tokens[$close][1] === '}') {
                    $depth--;
                    if ($depth === 0) {
                        break;
                    }
                }
            }

            $ranges[] = [$open + 1, $close - 1];
            $i = $close;
        }

        return $ranges;
    }

    /**
     * A catch block may log, translate the exception, or rethrow. It may not
     * assign a certainty/finding vocabulary key or variable to a hardcoded
     * literal — that constructs a value the user reads as a finding, on the very
     * path where nothing was measured.
     *
     * @param  list<array{0:int,1:string,2:int}>  $tokens
     * @param  list<array{0:int,1:int}>  $catchRanges
     */
    private function domainValueInCatchViolation(array $tokens, int $i, string $file, array $catchRanges): ?Violation
    {
        $isArrow = $tokens[$i][0] === T_DOUBLE_ARROW;
        $isAssign = $tokens[$i][0] === -1 && $tokens[$i][1] === '=';

        if (! $isArrow && ! $isAssign) {
            return null;
        }

        if (! $this->indexInRanges($i, $catchRanges)) {
            return null;
        }

        $next = $tokens[$i + 1] ?? null;
        if ($next !== null && ($next[1] === '-' || $next[1] === '+')) {
            $next = $tokens[$i + 2] ?? null;
        }
        if ($next === null || ($next[0] !== T_LNUMBER && $next[0] !== T_DNUMBER)) {
            return null;
        }

        $prev = $tokens[$i - 1] ?? null;
        $name = match ($prev[0] ?? null) {
            T_VARIABLE => strtolower(ltrim($prev[1], '$')),
            T_STRING => strtolower($prev[1]),
            T_CONSTANT_ENCAPSED_STRING => strtolower(trim($prev[1], "'\"")),
            default => null,
        };

        if ($name === null) {
            return null;
        }

        foreach (self::CERTAINTY_VOCAB as $vocab) {
            if (str_contains($name, $vocab)) {
                return new Violation(
                    file: $file,
                    line: $tokens[$i][2],
                    mechanism: "domain value '{$vocab}' constructed in an error-handling branch",
                    reason: "A catch block assigns '{$vocab}' a hardcoded literal. An error-handling "
                        .'branch may log, translate the exception, or rethrow — it may not produce a '
                        .'value the user reads as a finding, because an error is visible and recoverable '
                        .'while an invented finding may be recorded as family history that outlives the '
                        .'software. Propagate the failure or return a typed Unavailable instead.',
                );
            }
        }

        return null;
    }

    /**
     * @param  list<array{0:int,1:int}>  $ranges
     */
    private function indexInRanges(int $i, array $ranges): bool
    {
        foreach ($ranges as [$start, $end]) {
            if ($i >= $start && $i <= $end) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<array{0:int,1:string,2:int}>  $tokens
     */
    private function randomnessViolation(array $tokens, int $i, string $file): ?Violation
    {
        $mechanism = $this->randomnessAt($tokens, $i);

        if ($mechanism === null) {
            return null;
        }

        return new Violation(
            file: $file,
            line: $tokens[$i][2],
            mechanism: $mechanism,
            reason: "Generates randomness ({$mechanism}). Application source must not invent "
                .'data. Either remove the randomness, or add an allowlist entry with a written '
                .'justification a reviewer can evaluate.',
        );
    }

    /**
     * A certainty measurement coalesced (?? / ?:) to a hardcoded literal: an
     * invented figure where none was measured.
     *
     * @param  list<array{0:int,1:string,2:int}>  $tokens
     */
    private function certaintyStandInViolation(array $tokens, int $i, string $file): ?Violation
    {
        if ($tokens[$i][0] !== T_COALESCE) {
            return null;
        }

        if (! $this->rightOperandIsNumericLiteral($tokens, $i)) {
            return null;
        }

        $word = $this->certaintyWordInLeftOperand($tokens, $i);

        if ($word === null) {
            return null;
        }

        return new Violation(
            file: $file,
            line: $tokens[$i][2],
            mechanism: "certainty '{$word}' defaulted to a literal",
            reason: "Substitutes a hardcoded value for an absent '{$word}' measurement. A "
                ."certainty you did not measure is not 0 — that reads as 'certainly wrong', a "
                .'different claim. Coalesce to null so absence is representable, or remove the '
                .'fallback so it fails loudly.',
        );
    }

    /**
     * @param  list<array{0:int,1:string,2:int}>  $tokens
     */
    private function rightOperandIsNumericLiteral(array $tokens, int $coalesce): bool
    {
        $next = $tokens[$coalesce + 1] ?? null;

        if ($next !== null && ($next[1] === '-' || $next[1] === '+')) {
            $next = $tokens[$coalesce + 2] ?? null;
        }

        return $next !== null && ($next[0] === T_LNUMBER || $next[0] === T_DNUMBER);
    }

    /**
     * Walk back over the left operand (to the nearest statement/argument
     * boundary) looking for a certainty-vocabulary identifier, array key, or
     * variable name.
     *
     * @param  list<array{0:int,1:string,2:int}>  $tokens
     */
    private function certaintyWordInLeftOperand(array $tokens, int $coalesce): ?string
    {
        $boundaries = [';', '(', ',', '{', '}'];

        for ($j = $coalesce - 1; $j >= 0; $j--) {
            [$id, $text] = $tokens[$j];

            if ($id === T_DOUBLE_ARROW || in_array($text, $boundaries, true)) {
                break;
            }

            $name = match ($id) {
                T_VARIABLE => strtolower(ltrim($text, '$')),
                T_STRING => strtolower($text),
                T_CONSTANT_ENCAPSED_STRING => strtolower(trim($text, "'\"")),
                default => null,
            };

            if ($name === null) {
                continue;
            }

            foreach (self::CERTAINTY_VOCAB as $vocab) {
                if (str_contains($name, $vocab)) {
                    return $vocab;
                }
            }
        }

        return null;
    }

    private function isAllowed(Violation $violation): bool
    {
        foreach ($this->allowlist as $entry) {
            if ($entry->excuses($violation)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Reduce the token stream to the array-form tokens that carry meaning,
     * dropping whitespace and comments but keeping single-character tokens
     * (like "(" or "::") re-encoded as [id, text, line] so lookahead is uniform.
     *
     * @param  array<int, array{0:int,1:string,2:int}|string>  $tokens
     * @return list<array{0:int,1:string,2:int}>
     */
    private function significantTokens(array $tokens): array
    {
        $out = [];
        $line = 1;

        foreach ($tokens as $token) {
            if (is_array($token)) {
                $line = $token[2];

                if ($token[0] === T_WHITESPACE || $token[0] === T_COMMENT || $token[0] === T_DOC_COMMENT) {
                    continue;
                }

                $out[] = [$token[0], $token[1], $token[2]];

                continue;
            }

            // Single-character token ("(", ")", "::" is T_DOUBLE_COLON so is array).
            $out[] = [-1, $token, $line];
        }

        return $out;
    }

    /**
     * @param  list<array{0:int,1:string,2:int}>  $tokens
     */
    private function randomnessAt(array $tokens, int $i): ?string
    {
        [$id, $text] = $tokens[$i];

        if ($id !== T_STRING) {
            return null;
        }

        $name = strtolower($text);
        $next = $tokens[$i + 1][1] ?? null;
        $prev = $tokens[$i - 1][1] ?? null;

        if (in_array($name, self::RANDOM_FUNCTIONS, true) && $next === '(' && $prev !== '->' && $prev !== '::') {
            return $text;
        }

        // Static call: <Class>::<method>(  — the class token sits two before the
        // method regardless of any namespace prefix, since it is the token that
        // immediately precedes "::".
        if ($next === '(' && $prev === '::') {
            $class = $tokens[$i - 2][1] ?? '';

            foreach (self::STATIC_RANDOM as $shortClass => $methods) {
                if ($class === $shortClass && in_array($text, $methods, true)) {
                    return "{$shortClass}::{$text}";
                }
            }
        }

        return null;
    }
}
