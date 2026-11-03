# MEMORY.md — Drift: Cookie Consent

Decisions, gotchas and open items that aren't obvious from the code. Keep entries short and dated.

## Key decisions

- **2026-11-03: Forked from Bonsai Cookie Consent 2.5.2 as a free WordPress.org plugin.** Bonsai Cookie Consent stays on Bonsai client sites. No migration: the option, filter, classes and data attributes all have new `drift_cookie_consent` / `drift-cookie-consent` names. Don't run both on one site; each would try to block the same iframes.
- **2026-11-03: Accent caramel `#d97706` / `#92400e`.** Fits "cookie"; the darker shade keeps it apart from Control's amber.
- **2026-11-03: Started at 1.0.0**, not 2.6.0. Earlier history stays in the Bonsai repo's CHANGELOG.
- **2026-11-03: Front-end JS stays vanilla.** It runs on every page; adding a jQuery dependency would force jQuery onto sites that don't otherwise load it.
- **Carried over from Bonsai Cookie Consent:**
  - Server-side blocking in a full-page output buffer (2.5.1), because JS-only blocking ran at `DOMContentLoaded`, after the browser had already started loading the iframe.
  - `WP_HTML_Tag_Processor`, not regex. Query uses `'IFRAME'` uppercase: WP < 6.5 only uppercases the HTML side.
  - Embed URL read from `src`, `data-lazy-src`, then `data-src` (2.5.2, WP Rocket puts `about:blank` in `src`).
  - Placeholder fills the parent when the iframe is `position: absolute` (theme ratio boxes), checked before wrapping.

## Gotchas

- The consent manager setting must match the CMP actually installed, or videos never unblock.
- Cookiebot only accepts `marketing`, `statistics` or `preferences` as the category.
- YouTube/Vimeo thumbnails are requested before consent (IP address goes to Google/Vimeo, no cookies). A default background image avoids it. Disclosed in readme.txt.
- Caching plugins cache the rewritten HTML. That's fine (rewrite doesn't depend on the visitor), but clear caches after changing the consent manager or category.
- Pages served without `template_redirect` (some page-cache drop-ins, custom endpoints) only get the JS fallback.

## Open items / tech debt

- [ ] Screenshots for WordPress.org (`.wordpress-org/screenshot-1.png` placeholder, `screenshot-2.png` settings).
- [ ] Run Plugin Check and PHPCS before submitting to WordPress.org.
- [ ] Submit to WordPress.org, then set `WPORG_DEPLOY` and the SVN secrets.
- [ ] No PHPUnit tests: `drift_cookie_consent_block_iframes_in_html()` (each consent manager, lazy-load attrs, already-marked iframes, non-video iframes), `drift_cookie_consent_to_privacy_url()`, `drift_cookie_consent_sanitize_options()`.
