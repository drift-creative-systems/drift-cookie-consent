# Changelog

All notable changes to this project are documented here. The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the project uses [Semantic Versioning](https://semver.org/).

## [1.0.0] - 2026-11-03

First release as **Drift: Cookie Consent**, a free WordPress.org plugin by Drift Creative Systems. Based on Bonsai Cookie Consent 2.5.2, which carries on separately for Bonsai client sites. There is no data migration between the two.

### Added
- Settings → Drift: Cookie Consent screen with the Drift monogram in caramel (`#d97706`), and the copyright and company details in the admin footer.
- "Settings" link on the plugin's row on the Plugins screen.
- Copyright and company details in the README, readme.txt and the main file header.
- `uninstall.php`: removes the settings on every site in a multisite network.
- `readme.txt` with an External services section (YouTube and Vimeo thumbnails before consent, players after), `LICENSE` (GPLv2), `phpcs.xml.dist`, WordPress.org icon and banners, release workflow with optional SVN deploy.
- Duplicate-install guard: a second copy of the plugin shows an admin notice instead of a fatal error.

### Changed
- Renamed to Drift: Cookie Consent: slug and text domain `drift-cookie-consent`, prefix `drift_cookie_consent_` / `DRIFT_COOKIE_CONSENT_`.
- Stored option renamed from `ccve_cookiescript_options` to `drift_cookie_consent_settings`.
- Filter renamed from `ccve_cookiescript_block_iframes` to `drift_cookie_consent_block_iframes`.
- Front-end classes renamed from `ccve-*` to `drift-cookie-consent-*`, the iframe marker from `data-ccve-provider` to `data-drift-cookie-consent-provider`, and the JS settings object from `ccveCookieScriptSettings` to `DRIFT_COOKIE_CONSENT`.
- Code split into `includes/settings.php`, `includes/admin.php` and `includes/frontend.php`; assets renamed to `assets/front.js`, `assets/front.css` and `assets/admin.css`. PHP indentation is now tabs, per WordPress Coding Standards.
- Default consent text and button label are now translatable.
- The iframe blocker only writes to the error log when `WP_DEBUG` is on.
- Overlay CSS: removed border declarations that were always overridden, and commented the remaining `!important`s.

### Removed
- Plugin Update Checker, Composer and `vendor/`. Updates come from WordPress.org.
- Bonsai admin design system header, stylesheet and logo.

---

Earlier history (Bonsai Cookie Consent 1.7 to 2.5.2) is in the [Bonsai Cookie Consent](https://github.com/Bonsai-Systems/bonsai-cookie-consent) repository.
