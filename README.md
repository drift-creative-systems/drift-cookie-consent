![Drift Creative Systems](.github/banner.jpg)

# Drift: Cookie Consent

Free WordPress plugin by **Drift Creative Systems**. Blocks YouTube and Vimeo embeds until marketing consent is given through **CookieScript** or **Cookiebot**, and shows the video's thumbnail with a consent message in their place. Settings are under **Settings → Drift: Cookie Consent**.

- WordPress.org slug: `drift-cookie-consent` (submission pending)
- Licence: GPL-2.0-or-later
- Requires WordPress 6.4+, PHP 7.4+, and CookieScript or Cookiebot on the site

The user-facing documentation is [readme.txt](readme.txt), which WordPress.org displays. This file is for developers.

## Structure

```
drift-cookie-consent.php     Bootstrap: duplicate-install guard, constants, requires
includes/
  settings.php               Defaults, option read + sanitise, Settings API fields
  admin.php                  Settings → Drift: Cookie Consent screen, admin CSS, footer, Plugins-row link
  iframe-blocker.php         Full-page output buffer that rewrites YouTube/Vimeo iframes before the page is sent
  frontend.php               Enqueues front.css / front.js, passes settings as DRIFT_COOKIE_CONSENT
assets/
  front.js                   Builds the placeholder; blocks iframes added after page load; reopens the consent popup
  front.css                  Placeholder, overlay and ratio-container styles
  admin.css                  Settings screen
uninstall.php                Removes the option, on every site in a network
```

## How blocking works

1. `drift_cookie_consent_start_output_buffer()` starts a buffer on `template_redirect` (priority 0). It's skipped in wp-admin, AJAX, REST, cron, feeds, robots, trackbacks and page builder previews (`elementor-preview`, `et_fb`, `fl_builder`, `bricks`, `ct_builder`, `vc_editable`).
2. On output, `WP_HTML_Tag_Processor` walks every `<iframe>`. The first of `src`, `data-lazy-src`, `data-src` holding a YouTube or Vimeo URL is used, so lazy-load placeholders like `src="about:blank"` are skipped.
3. The iframe loses `src` and any lazy-loader attributes (`data-lazy-src`, `data-rocket-lazyload`, `.rocket-lazyload`), and gets the privacy URL plus the active consent manager's attributes:

   | Consent manager | Source attribute | Category attribute |
   |---|---|---|
   | CookieScript | `data-src` | `data-cookiecategory` |
   | Cookiebot | `data-cookieblock-src` | `data-cookieconsent` |

   It also gets a `data-drift-cookie-consent-provider` marker (`youtube` / `vimeo`).
4. Non-HTML responses are left alone, and any exception returns the original page untouched (logged when `WP_DEBUG` is on).
5. `front.js` wraps each marked iframe in a placeholder and watches its `src`. When the consent manager writes `src`, the iframe is shown. Unmarked iframes found on `DOMContentLoaded` go through the same blocking in JS as a fallback.

YouTube URLs switch to `youtube-nocookie.com`; Vimeo URLs get `dnt=1`, keeping other params such as the `h=` hash unlisted videos need. The PHP and JS helpers mirror each other (`drift_cookie_consent_get_provider()` / `getProvider()`, etc.). Change both together.

Inside a theme ratio box (the iframe is `position: absolute`), the wrapper gets `drift-cookie-consent-video-wrapper--fill` and fills the parent instead of adding its own 16:9 box.

## Stored data

| Key | Where | What |
|---|---|---|
| `drift_cookie_consent_settings` | option (autoloaded, read on every page) | `consent_manager`, `cookie_category`, `default_bg_image`, `consent_text`, `consent_link_url`, `consent_link_label` |

Removed in `uninstall.php`. Never rename it once released.

## Hooks

| Hook | Type | Purpose |
|---|---|---|
| `drift_cookie_consent_block_iframes` | filter | `bool`, default `true`. Return false to skip server-side blocking and rely on `front.js` only |

```php
// Leave server-side blocking off on this site.
add_filter( 'drift_cookie_consent_block_iframes', '__return_false' );
```

## Front-end classes

The wrapper is `.drift-cookie-consent-video-wrapper`, with `.drift-cookie-consent-provider-youtube` / `-vimeo`, and `.drift-cookie-consent-consent-granted` once the video has loaded. Inside it are `.drift-cookie-consent-placeholder` (`-image`, `-shade`), `.drift-cookie-consent-overlay-content`, `-overlay-text`, `-overlay-link` and the iframe, `.drift-cookie-consent-managed-iframe`.

## Development

- No build step. Edit `assets/*.js` / `*.css` directly.
- Coding standard: `phpcs.xml.dist` (WordPress, prefixes `drift_cookie_consent` / `DRIFT_COOKIE_CONSENT`).
- Run **Plugin Check** before every release, against a copy built from `.distignore`:
  ```sh
  wp plugin install plugin-check --activate
  wp plugin check drift-cookie-consent --include-low-severity-errors --include-low-severity-warnings \
    --require=./wp-content/plugins/plugin-check/cli.php
  ```
- Test any change to blocking with both consent managers, with and without consent, on a YouTube and a Vimeo embed, inside and outside a theme ratio container, and with WP Rocket's LazyLoad for iframes on.
- Directory guidelines review: the `wp-plugin-directory-guidelines` Claude Code skill.

## Releasing

1. Bump the version in **three** places: the `Version:` header, `DRIFT_COOKIE_CONSENT_VERSION` and `Stable tag:` in `readme.txt`. Add a changelog entry to both `readme.txt` and `CHANGELOG.md`.
2. Merge `develop` → `main`, then publish a GitHub release tagged `vX.Y.Z`.
3. `.github/workflows/release.yml` checks the three versions match, builds `drift-cookie-consent.zip` from `.distignore` and attaches it to the release.
4. Once approved on WordPress.org, set the repo variable `WPORG_DEPLOY=true` and the secrets `SVN_USERNAME` / `SVN_PASSWORD`. Releases then deploy to SVN automatically, and readme/asset changes on `main` sync on their own (`wporg-assets.yml`).

WordPress.org assets (icon, banners, screenshots) live in `.wordpress-org/`. Screenshots still need adding as `screenshot-1.png` (placeholder over a video) and `screenshot-2.png` (settings) to match `readme.txt`.

## Licence

GPL-2.0-or-later. See [LICENSE](LICENSE).

Copyright © 2026 Drift Creative Systems Ltd

Drift Creative Systems Ltd. Registered in England and Wales, company number 17548220. Registered office: 167-169 Great Portland Street, 5th Floor, London W1W 5PF
