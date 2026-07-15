---
name: Liberu Genealogy
description: A records office, rebuilt — precise, fast, evidence-first family history software.
colors:
  registry-green: "#047857"
  registry-green-deep: "#065f46"
  registry-tint: "#ecfdf5"
  ink: "#0f172a"
  ink-muted: "#475569"
  ink-faint: "#64748b"
  rule: "#e2e8f0"
  paper: "#ffffff"
  surface: "#f8fafc"
  surface-sunk: "#f1f5f9"
  flag-error: "#b91c1c"
  flag-warning: "#b45309"
  flag-info: "#1d4ed8"
typography:
  display:
    fontFamily: "Figtree, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(2.5rem, 5vw + 1rem, 3.75rem)"
    fontWeight: 700
    lineHeight: 1.1
    letterSpacing: "-0.02em"
  headline:
    fontFamily: "Figtree, ui-sans-serif, system-ui, sans-serif"
    fontSize: "2rem"
    fontWeight: 600
    lineHeight: 1.15
    letterSpacing: "-0.015em"
  title:
    fontFamily: "Figtree, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.375rem"
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: "-0.01em"
  body:
    fontFamily: "Figtree, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.0625rem"
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: "normal"
  label:
    fontFamily: "Figtree, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 500
    lineHeight: 1.4
    letterSpacing: "normal"
rounded:
  sm: "4px"
  md: "6px"
  lg: "10px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "40px"
  xxl: "64px"
components:
  button-primary:
    backgroundColor: "{colors.registry-green}"
    textColor: "{colors.paper}"
    typography: "{typography.label}"
    rounded: "{rounded.md}"
    padding: "12px 20px"
  button-primary-hover:
    backgroundColor: "{colors.registry-green-deep}"
    textColor: "{colors.paper}"
  button-secondary:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.registry-green}"
    typography: "{typography.label}"
    rounded: "{rounded.md}"
    padding: "12px 20px"
  button-secondary-hover:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.registry-green-deep}"
  input-field:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    typography: "{typography.body}"
    rounded: "{rounded.sm}"
    padding: "10px 12px"
    height: "44px"
  record-card:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    rounded: "{rounded.md}"
    padding: "20px"
  nav-item:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink-muted}"
    typography: "{typography.label}"
    rounded: "{rounded.sm}"
    padding: "8px 12px"
  nav-item-active:
    backgroundColor: "{colors.registry-tint}"
    textColor: "{colors.registry-green-deep}"
---

# Design System: Liberu Genealogy

## 1. Overview: The Records Office, Rebuilt

**Creative North Star: "The Records Office, Rebuilt"**

A records office earns trust through system, not decoration. Index cards in a known order. Registers with numbered columns. Finding aids that tell you exactly where a thing is and how sure anyone is that it's the right thing. Nothing in that room is trying to sell you a feeling — it is trying to answer a question, and its dignity comes from answering precisely. That is the room this interface is.

The second word is the one doing the work. **Rebuilt.** This is not the records office preserved under glass; it is the records office as it would be built now, by people who think 2009 software was an apology. Everything slow, ornamental, or bureaucratic about the original is gone. What stays is the discipline: the label under every specimen, the citation under every claim, the honest confidence score under every match. Speed is part of the rebuild — a researcher mid-question across nine tabs should never wait for the room to catch up.

This system explicitly rejects four things. **Ancestry/MyHeritage upsell UX** — no dangled hints, no blurred results, no manufactured discovery counts; a records office does not hide the ledger and charge for a peek. **Sepia and parchment heritage kitsch** — the records are old, the office is not; aged-paper texture is costume, and this system wears none. **The generic SaaS gradient template** — gradient headings, emoji eyebrow pills, and emerald-to-blue mesh heroes are the reflex this project is currently shipping and is now leaving behind. **Enterprise admin blandness** — the failure mode nearest to this North Star, and the one to guard hardest: gray-on-gray stock Filament is not neutrality, it is abdication. Civic does not mean characterless. The voice here is the crisp confidence of a well-run archive, not the resignation of a DMV.

**Key Characteristics:**
- Systematic before expressive: order, labels, and alignment carry the design.
- Evidence visible at rest — sources and confidence are never a click away.
- Flat, quiet surfaces; depth only when something is happening.
- One green, used sparingly, meaning "this is live/selected/verified."
- Legible under bad light and older eyes, by construction, not by settings.
- Dense where the data is dense; never cramped, never shrunk to fit.

## 2. Colors: The Registry Palette

A single green ramp against a cool slate neutral: the green marks what is live, selected, or verified, and the neutrals do everything else. The palette is **Restrained** — the accent touches roughly a tenth of any screen, and its rarity is what makes it legible as meaning.

### Primary
- **Registry Green** (`#047857`, `oklch(50.8% 0.105 165.6)`): The single interactive voice. Primary buttons, links, the current selection, the "verified" state. It clears 5.48:1 on paper, so it is safe as text *and* as a fill behind white labels. This is the green the Filament panels' `Color::Emerald` resolves toward; the identity is inherited, not invented.
- **Registry Green Deep** (`#065f46`, `oklch(43.2% 0.086 166.9)`): Hover and active deepening, and the color of headings that need to carry brand without shouting. 7.68:1 on paper.
- **Registry Tint** (`#ecfdf5`, `oklch(97.9% 0.021 166.1)`): The selected-row wash, the active nav-item bed. Ink on it reads 16.95:1.

`emerald-600` (`#059669`) is **not a token in this system.** It is 3.77:1 on paper — it fails AA as text and as a fill behind white labels — and a token whose entire definition is "never use this" is a lint rule wearing a token's clothes. It is named once, in the Don'ts, because it is the green on the landing page's primary CTA today. It does not get a slot in the palette.

### Neutral
- **Ink** (`#0f172a`, `oklch(20.8% 0.040 265.8)`): All body text, all headings by default. 17.85:1 on paper.
- **Ink Muted** (`#475569`, `oklch(44.6% 0.037 257.3)`): Secondary text, captions, column headers, dates. 7.58:1 — genuinely readable, not decorative gray.
- **Ink Faint** (`#64748b`, `oklch(55.4% 0.041 257.4)`): The floor, and it does two jobs. Placeholder text (4.76:1 — placeholders get no contrast exemption here) and **form-control borders**, where 1.4.11 demands ≥3:1 and this clears it. Nothing lighter may carry text or bound a control.
- **Rule** (`#e2e8f0`, `oklch(92.9% 0.013 255.5)`): Hairline dividers and table gridlines only. It is 1.23:1 — decorative, structural, never a control boundary.
- **Paper** (`#ffffff`): The reading surface. Records sit on paper.
- **Surface** (`#f8fafc`, `oklch(98.4% 0.003 247.9)`): The second neutral layer — sidebars, toolbars, panel chrome. Cooler than paper, so the content surface reads as the subject.
- **Surface Sunk** (`#f1f5f9`, `oklch(96.8% 0.007 247.9)`): Table zebra striping, inset wells.

### Tertiary
The status flags. They are signal, never decoration, and each is chosen at the step that survives white text on a fill.
- **Flag Error** (`#b91c1c`, `oklch(50.5% 0.190 27.5)`): Destructive confirmation, validation failure, import rejection. 6.47:1.
- **Flag Warning** (`#b45309`, `oklch(55.5% 0.146 49.0)`): Conflicting records, low-confidence merges, deprecated sources. 5.02:1. **Never `amber-600`** (3.19:1, fails).
- **Flag Info** (`#1d4ed8`, `oklch(48.8% 0.217 264.4)`): Neutral system notices. 6.7:1. This is the *only* sanctioned blue. It is not a brand color and never pairs with green as a gradient.

Success has no dedicated color: **success is Registry Green.** In a records product the good state is "verified," which is the same thing the accent already means. A separate success green would be a fifth green saying nothing new.

### Theme

**Light only, and that is a commitment, not an omission.** Records sit on paper; the reading surface is the metaphor's whole point. The scene is a researcher at a desk over a long session, cross-referencing records, often with older eyes — the case for a dark reading surface is weakest exactly there.

The codebase does not currently agree with itself. Fifty-six Blade files carry `dark:` variants inherited from Jetstream, and Tailwind 4's `dark:` resolves through `prefers-color-scheme` **by default** — no `@custom-variant` needed. Those variants are therefore *live*, not dead: a visitor whose OS is set to dark gets `bg-white dark:bg-gray-800` cards sitting inside a `site.css` footer and `.card` rule that have no dark variant at all. The result is a half-dark page nobody designed, tested, or chose. That is worse than having no dark mode, because it ships by accident to a real slice of users.

Two obligations follow, and both are concrete:
- Strip the stray `dark:` variants from the Blade surface. They are not a dark mode; they are sediment from a starter kit.
- Filament ships dark mode enabled with a user-facing toggle. Set **`->darkMode(false)`** on both panel providers, or the panels will offer a theme this system does not define and has not contrast-checked.

If dark mode is ever wanted, it is a designed piece of work with its own verified ramp — a second full contrast pass, not a `dark:` prefix sprinkled on the existing one.

### Named Rules

**The One Green Rule.** The codebase currently runs four unrelated greens — `emerald-600`, `green-800`, `green-900`, and a raw `rgba(0,128,0,0.75)` in `navbar.css`. Exactly one ramp survives: Registry Green. The other three are deleted on sight, not deprecated. Four greens is not a palette, it is sediment.

**The 4.5 Floor Rule.** No color carries text below 4.5:1 against its own background — placeholders and disabled labels included. If a token is close, it moves toward ink. "Light gray for elegance" is how interfaces become unreadable, and this product's users are the ones who feel it first.

**The Rarity Rule.** Registry Green appears on ≤10% of any screen. It means live, selected, or verified. A green thing the user cannot act on or trust is a lie about the color.

**The Never Hue-Alone Rule.** DNA confidence, match strength, and record status must never be encoded in color alone. Every such indicator carries a label, a value, or a shape alongside its hue. This is not a colorblind accommodation bolted on; it is the only honest way to state a confidence.

## 3. Typography

**Body Font:** Figtree (with `ui-sans-serif, system-ui, sans-serif`)
**Display Font:** Figtree — same family, heavier weight.
**Label/Mono Font:** none. There is no mono in this system yet.

**Character:** One humanist sans doing every job, separated by weight and size rather than by family. Figtree's open apertures and tall x-height are what make it survive at 17px on a tired screen; a geometric sans would look cleaner in a specimen and worse at 4pm. It was the project's original choice and it is a good one — it simply never rendered.

Figtree is **already configured and already downloaded, and has never once been applied.** `tailwind.config.js` maps `fontFamily.sans` to it, but Tailwind 4 (`@import "tailwindcss"`) ignores a JS config without an `@config` directive, so `font-sans` resolves to the stock system stack. The font link exists only in `guest.blade.php`, meaning `/login` fetches a typeface it then declines to use. Reviving it is a two-line fix: declare `--font-sans` in an `@theme` block, and load the family on every layout instead of one. Self-hosting it is the better end state — a third-party font request sits badly in an MIT, self-hostable product whose principle is that user data leaves whole.

### Hierarchy

**Inside the app, fixed rem, never fluid.** Users view product UI at a consistent DPI, and a clamped heading that shrinks inside a panel looks broken, not responsive. The ratio is ~1.2 — tight, because this system has many type elements and exaggerated contrast reads as noise.

**Display is the one exception, and only on the marketing hero.** That surface is brand register, where the page *is* the product and type is expected to scale with the viewport. Everything from Headline down is app type and stays fixed.

- **Display** (700, `clamp(2.5rem, 5vw + 1rem, 3.75rem)` → 40–60px, 1.1, -0.02em): Marketing hero only. Never inside the app. The ceiling matches the hero's current `md:text-6xl` (60px) so reviving Figtree doesn't quietly shrink the page; the floor keeps it from overflowing at 320px. Well under the 6rem ceiling — a hero should land, not shout.
- **Headline** (600, 2rem/32px, 1.15, -0.015em): Page titles, section heads on marketing.
- **Title** (600, 1.375rem/22px, 1.3, -0.01em): Panel headings, card titles, resource names.
- **Body** (400, 1.0625rem/17px, 1.6): All prose and record content. Prose caps at 65–75ch; data tables may run to 120ch+.
- **Label** (500, 0.9375rem/15px, 1.4): Buttons, form labels, column headers, nav items. Sentence case.

### Named Rules

**The Seventeen Floor Rule.** Body text is 17px. It does not shrink to win a density argument — density comes from spacing and layout, never from making a 62-year-old researcher lean in. 15px is the floor for labels and it is a floor, not a default.

**The Sentence Case Rule.** Labels are sentence case. The stock Jetstream button ships `uppercase tracking-widest text-xs` — 12px, all-caps, letter-spaced — which is three legibility failures stacked in one class string. All-caps destroys word shape, the exact cue struggling readers rely on most. Uppercase is permitted nowhere in this system.

**The No Display In UI Rule.** Display weight and size belong to the marketing hero. A display font in a button, a label, or a data cell is decoration where an affordance should be.

**The Tabular Figures Rule.** Every number that sits in a column or is meant to be compared gets `font-variant-numeric: tabular-nums` — dates, cM values, generation counts, record IDs, table cells. This product is mostly numbers in columns, and proportional figures make a column of dates ripple. Figtree carries tabular figures; the system does not need a mono to get aligned data, which is why it does not have one.

## 4. Elevation

**Flat at rest.** Surfaces are distinguished by tone and a hairline `rule`, not by shadow. Depth is a response to state, never an ambient property — a card that lifts while nothing is happening is a card claiming importance it hasn't got. The current codebase applies `shadow-md` to cards, the footer, the navbar, and buttons indiscriminately, which is why nothing on the page reads as more important than anything else: when everything lifts, nothing does.

The audit test: **if it looks like a 2014 app, the shadow is too dark and the blur is too small.** A shadow here is wide, soft, and nearly colorless, and it appears because the user did something.

### Shadow Vocabulary

- **Hover Lift** (`box-shadow: 0 1px 2px rgba(15,23,42,0.06), 0 2px 8px rgba(15,23,42,0.08)`): A row or card under the cursor. Paired with a 150ms transition. Nothing else.
- **Overlay** (`box-shadow: 0 8px 32px rgba(15,23,42,0.16)`): Modals, popovers, command palette. Real elevation, because the thing genuinely floats above the record.
- **Focus** — *not a shadow.* `outline: 2px solid #047857; outline-offset: 2px`. Focus is an outline so it survives forced-colors mode and never competes with the hover lift.

### Stacking Order

Elevation is also a z-axis, and right now that axis is decided by accident. The codebase puts the modal, the dropdown, the sticky navbar, and three checklist overlays all on `z-50` — which means what covers what is settled by DOM order, not intent. A dropdown inside a modal is a coin toss.

The scale is semantic and has six steps. Nothing outside it, and never a bare `9999`:

| Token | Value | Role |
|---|---|---|
| `z-dropdown` | 10 | Menus, popovers, select panels |
| `z-sticky` | 20 | Sticky table headers, the navbar |
| `z-modal-backdrop` | 30 | The scrim |
| `z-modal` | 40 | Dialogs, the command palette |
| `z-toast` | 50 | Notifications |
| `z-tooltip` | 60 | Always on top; never traps focus |

**Dropdowns clip.** A `position: absolute` menu inside an `overflow-hidden` or `overflow-x-auto` parent is cut off no matter how high its z-index — z-index cannot escape a clipping ancestor. Genealogy tables scroll horizontally, so this is live here, not theoretical. Use the native popover API, `position: fixed`, or a portal.

### Named Rules

**The Flat-At-Rest Rule.** Zero `box-shadow` at rest. If a surface needs separating from its neighbor, it gets a 1px `rule` border or a tone step to `surface` — those cost nothing and never smear.

**The Earned Elevation Rule.** Every shadow answers an event: hover, focus, or float-above-content. A shadow with no event behind it is deleted.

**The Named Layer Rule.** Every stacked element names its layer from the six above. `z-50` on four unrelated components is not a stacking order, it is four components hoping.

## 5. Components

The character across every control: **quiet, exact, and immediately legible.** A researcher fluent in good software should never pause to work out what a control does. Earned familiarity beats novelty — strangeness without purpose is this register's failure mode, not plainness.

Every interactive component ships all seven states: default, hover, focus, active, disabled, loading, error. Shipping four of seven is shipping a prototype.

### Buttons
- **Shape:** Lightly squared (6px, `{rounded.md}`) — a filing-cabinet corner, not a pill.
- **Primary:** Registry Green fill, paper label, 12px/20px padding, min-height 44px. White on `#047857` is 5.48:1.
- **Hover / Focus:** Hover deepens to Registry Green Deep over 150ms `cubic-bezier(0.22, 1, 0.36, 1)`. Focus is a 2px Registry Green outline at 2px offset. No lift, no scale.
- **Secondary:** Paper fill, Registry Green label, 1px `ink-faint` border. Hover fills to `surface`.
- **Disabled:** `surface-sunk` fill, `ink-faint` label. Never a faded primary — a translucent green button is an unreadable green button.

### Inputs / Fields
- **Style:** Paper fill, 1px `ink-faint` border (4.76:1 — clears the 3:1 that 1.4.11 requires for control boundaries), 4px radius, 44px min-height.
- **Focus:** Border shifts to Registry Green plus a 2px outline at 2px offset. No glow.
- **Placeholder:** `ink-faint` at 4.76:1. Placeholders are text and get no exemption.
- **Error:** `flag-error` border, with the message in `flag-error` text *below* the field — never a red border alone, which encodes meaning in hue.
- The shipped `<x-input>` uses `border-gray-300` at **1.48:1**, failing 1.4.11. It is replaced, not adjusted.

### Cards / Containers
- **Corner Style:** 6px (`{rounded.md}`).
- **Background:** Paper on `surface`. The record sits on paper; the chrome is cooler.
- **Shadow Strategy:** None at rest. Hover Lift only when the whole card is a link.
- **Border:** 1px `rule`.
- **Internal Padding:** 20px, or 16px in dense table contexts.
- Cards are the lazy answer to most layout questions. Use one only when the record genuinely is a discrete object. **Nested cards are always wrong.**

### Navigation
- **Style:** `surface` bed, Label typography, 6px radius, `ink-muted` at rest.
- **Active:** Registry Tint bed with Registry Green Deep text — the selection is stated by wash plus weight, never by hue alone.
- **Hover:** `surface-sunk`, 150ms.
- **Mobile:** The panel sidebar collapses structurally. Responsive behavior here is layout, not fluid type.
- The app panel currently labels all thirteen nav groups with emoji (`🏠 Dashboard`, `🧬 DNA & Genetics`). Emoji render inconsistently across platforms, are announced verbatim by screen readers, and are not a typographic system. Replace with the icon set already in the panel.

### Confidence Indicator (signature component)
The one component this product genuinely owns, and the one most able to betray it. A DNA match, a smart match, or a duplicate candidate always presents **three things together**: the value (`87.3 cM`), a named confidence (`High` / `Probable` / `Speculative`), and a shape or bar. Hue is the last carrier, never the only one.

This is where "evidence over hints" becomes visible: the number and its basis are on screen at rest. There is no blurred state, no "unlock to view," no count of discoveries the user may not inspect. If confidence is low, the interface says *Speculative*, in words.

## 6. Do's and Don'ts

### Do:
- **Do** use Registry Green (`#047857`) for every interactive and verified state, on ≤10% of the screen.
- **Do** keep body text at 17px / `ink` and secondary text at `ink-muted` (7.58:1). Never lighter.
- **Do** state DNA and match confidence with a value *and* a word *and* a shape.
- **Do** bound form controls with `ink-faint` (≥3:1) and focus them with a 2px outline at 2px offset.
- **Do** keep surfaces flat at rest; spend shadow only on hover, focus, and true overlays.
- **Do** use sentence case for every label.
- **Do** transition state in 150–250ms with `cubic-bezier(0.22, 1, 0.36, 1)`, and give every animation a `prefers-reduced-motion: reduce` alternative.
- **Do** hold hit targets at ≥44px.
- **Do** cap prose at 65–75ch; let tables run dense.
- **Do** set `font-variant-numeric: tabular-nums` on every number in a column.
- **Do** name a layer from the six-step z-scale on anything stacked.

### Don't:
- **Don't** ship **Ancestry/MyHeritage upsell UX**: no blurred results behind a paywall, no "47 new discoveries!" badges, no fake-urgency counts, no dangled leaf hints. If a pattern exists to manufacture FOMO, it does not ship here.
- **Don't** ship **sepia/parchment heritage kitsch**: no aged-paper texture, ornate scrollwork, faded photo borders, or literal tree/leaf iconography. No `--parchment`, `--sepia`, `--cream`, `--sand`, or `--linen` token ever enters this file.
- **Don't** ship the **generic SaaS gradient template**: no `bg-clip-text` gradient headings, no emoji eyebrow pills (`🌳 Discover Your Heritage`), no `from-emerald-50 via-white to-blue-50` mesh heroes, no rows of identical icon+heading+text cards, no hero-metric template.
- **Don't** ship **enterprise admin blandness**: stock untouched Filament gray-on-gray is abdication, not neutrality. Civic is not characterless.
- **Don't** use `emerald-600` (3.77:1) or `blue-500` (3.68:1) for text or as a fill behind white labels. Both fail AA. The landing CTA does this today.
- **Don't** use `amber-600` (3.19:1) for warnings. Use `flag-warning` (`#b45309`).
- **Don't** use `slate-400` (2.56:1) or `gray-300` (1.48:1) for text or control borders.
- **Don't** pair green with blue as a gradient. `flag-info` is the only blue and it is a status, not a brand color.
- **Don't** use `border-left`/`border-right` >1px as a colored accent stripe on cards, callouts, or alerts. Ever.
- **Don't** set body type in `uppercase tracking-widest`, or any type in all-caps.
- **Don't** nest cards.
- **Don't** add `dark:` variants to the Blade surface, or leave Filament's dark toggle enabled. This system is light only; the stray `dark:` classes are starter-kit sediment and ship a half-dark page to anyone whose OS prefers dark.
- **Don't** stack anything on a bare `z-50` / `z-999` / `z-9999`. Name a layer.
- **Don't** use glassmorphism or backdrop-blur decoratively.
- **Don't** add a tiny tracked eyebrow or an `01 / 02 / 03` marker above sections. Numbers are earned only by a genuine sequence.
- **Don't** let a heading overflow at any breakpoint. Test the real copy at 320px.
