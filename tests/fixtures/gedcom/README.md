# GEDCOM test fixtures

Small, hand-written GEDCOM files driving `tests/Unit/Services/GedcomCorpusRoundTripTest.php`
(the import / export round-trip harness for issue #1619).

| File | Version | People | Families | Exercises |
|---|---|---|---|---|
| `minimal-5.5.1.ged` | 5.5.1 | 3 | 1 | HUSB/WIFE/CHIL links, `BIRT`/`DATE`, `NAME` slashes, SEX M/F |
| `lower-version-5.5.ged` | 5.5 | 2 | 1 | "versions 5.5.1 or lower" clause, `ANSEL` charset, no child |
| `notes-continuation-5.5.1.ged` | 5.5.1 | 1 | 0 | `CONC`/`CONT` continuation, `PLAC`, `ABT` date, SEX U |

Provenance: all authored for this repo (public-domain sample data — no real people).

The vendor **torture-test** files (`TGC551.ged`, `TGC55C.ged`) live at
`vendor/liberu-genealogy/php-gedcom/tests/stresstestfiles/`. They are large and exercise
deep edge cases; the harness does **not** import them in CI (slow, and several edge
structures trip the current parser). Point a manual/exploratory run at them once the
import/export bugs ticket 03 tracks are fixed.
