# The application must never present invented data as fact

This is a hard rule, not a matter of taste. For a genealogy product it is the
most severe failure mode available: a user cannot tell an invented
great-grandmother — complete with a plausible source link — from a real
archival hit, and may record the invention as family history that outlives the
software.

## The rule

Where the platform cannot compute something, it says so plainly and says why.
Every figure on screen is either derived from the user's data or absent.

Concretely:

1. **Application source does not generate randomness** outside a small,
   justified allowlist (identifier and token generation). A random value is not
   a measurement.
2. **A certainty is never a hardcoded stand-in.** An unmeasured confidence,
   match quality or similarity is not `0` — that reads as "certainly wrong", a
   different claim. Coalesce to `null` so absence is representable, or let the
   failure propagate.
3. **An error-handling branch may not construct a domain value.** A `catch`
   block may log, translate the exception, or rethrow. It may not produce a
   centimorgan count, a confidence score, a detected face, a matched record, or
   a relationship estimate. An error is visible and recoverable; an invented
   finding is not.

## Why a fallback that invents a plausible value is worse than an error

It is the path of least resistance when a dependency is unavailable: it keeps
the demonstration working, keeps the tests green, and produces output that looks
like success. That is exactly why it is dangerous — it fails silently, in a way
review and CI both demonstrably missed across four services here. An error
surfaces and can be fixed; an invented ancestor gets recorded as fact.

When a provider-backed feature cannot run, return the typed
`App\Support\Unavailable` result carrying a human-readable reason, and render
that reason to the user ("not configured") rather than an empty or invented
result.

## The gate

`Tests\Unit\FabricationGateTest` enforces all three rules mechanically against
`app/` on every test run. If your change trips it, you have two routes forward:

- **Remove the randomness / literal / construction** — usually the right fix.
- **Add an allowlist entry** (randomness only) in the test, with a written
  justification a reviewer can evaluate. An entry without a reason fails. The
  allowlist is meant to be small and to shrink over time.
