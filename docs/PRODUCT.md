# Product

## Register

product

## Users

Family-history researchers, from weekend hobbyists to professional genealogists. They arrive with a specific question — who was this person, is this record the same man, why does this DNA segment match — and they stay for long sessions, cross-referencing records, sources, and matches across many tabs.

The demographic skews older, and the work skews evidence-heavy: a serious user is building a citation trail they expect to hand to someone else, or defend. They are frequently comparing two near-identical records and deciding whether they are one person. That judgement is the job. The software's role is to put the evidence in front of them and get out of the way.

The primary surface is the authenticated app (`/app`, `/admin`) — Filament panels covering people, families, sources, citations, DNA, media, and research checklists. The marketing site (`/`, `/about`, `/subscription`) is a secondary surface; when working on it, override the register to `brand` for that task.

## Product Purpose

Liberu Genealogy is a free, MIT-licensed, self-hostable platform for building, researching, and preserving family trees. It imports and exports GEDCOM (the portability guarantee — your data leaves as easily as it arrives), analyses raw DNA for matches and triangulated segments, assists photo tagging with facial recognition, and searches external services (MyHeritage, Ancestry, FamilySearch, FindMyPast) for record hints.

Success is a researcher trusting the tool with a decade of work, and being able to walk away with all of it.

## Brand Personality

**Precise, modern, fast.**

- **Precise** — exact matches, honest confidence scores, no fuzzy upsell. When the software isn't sure, it says so and shows why.
- **Modern** — 2026 software. The subject matter is old; the tool is not.
- **Fast** — dense data, no waiting, no ceremony between a question and its answer.

Voice: plain, specific, quantified. "Import 12,000 people in 4 seconds," not "powerful genealogy tools." Name the thing — a person, a record, a segment — rather than the abstraction. Never manufacture excitement the user didn't bring.

## Anti-references

All four are active constraints, not preferences.

- **Ancestry / MyHeritage upsell UX.** No leaf-hint dopamine bait, no "47 new discoveries!" badges, no results blurred behind a paywall, no fake urgency. The category's dominant look is downstream of a business model this project explicitly rejects. If a pattern exists to manufacture FOMO, it does not ship here.
- **Sepia / parchment heritage kitsch.** No aged-paper texture, ornate scrollwork, faded photo borders, or literal tree/leaf/branch decoration. No `--parchment` / `--sepia` / `--cream` tokens. This is the first-order category reflex *and* the saturated AI default; it is banned on both counts. Heritage is carried by the content, never by costume.
- **Generic SaaS gradient template.** No gradient text (`bg-clip-text`), no emoji eyebrow pills, no emerald→blue mesh heroes, no rows of identical icon+heading+text cards, no hero-metric template. The current landing page is the reference for what to stop doing.
- **Enterprise admin blandness.** Stock, untouched Filament gray-on-gray is not neutrality, it's abdication. Density is not an excuse for having no voice.

## Design Principles

1. **Evidence over hints.** Show the record, its source, and an honest confidence. Never dangle, never blur, never tease. A match the user cannot inspect is not a feature.
2. **The tool disappears into the research.** Earned familiarity beats novelty. A researcher fluent in good software should sit down and trust every control immediately. Strangeness without purpose is the failure mode — not plainness.
3. **Heritage without the costume.** The subject is a century old; the interface is not. Respect for the material shows up as rigor and legibility, never as decoration cosplaying age.
4. **Speed is a feature, not an optimization.** Users are mid-question across many tabs. Density, no ceremony, no orchestrated loads.
5. **Legible at seventy.** Accessibility is a design input here, not a compliance pass. If it needs young eyes, it's broken.
6. **Your data leaves whole.** GEDCOM export, self-hosting, and MIT licensing are product promises. Never design a flow that quietly makes leaving harder.

## Accessibility & Inclusion

**WCAG 2.2 AA, with deliberate older-user care.**

- Body text ≥4.5:1, large text ≥3:1. Placeholders included — no muted-gray exemption.
- Base font size ≥17px with comfortable line-height. Do not shrink UI text for density.
- Hit targets ≥44px.
- **Never encode meaning in hue alone.** DNA confidence, match strength, and record status must carry a label, shape, or value alongside color — colorblind-safe by construction.
- `prefers-reduced-motion` honored on every transition.
- Full keyboard navigation; semantic landmarks throughout.
