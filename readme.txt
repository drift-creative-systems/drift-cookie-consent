=== Drift: Cookie Consent ===
Contributors: driftcreativesystems
Tags: cookie consent, gdpr, youtube, vimeo, privacy
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Blocks YouTube and Vimeo embeds until marketing consent is given through CookieScript or Cookiebot, with a thumbnail and consent message in their place.

== Description ==

A YouTube or Vimeo embed contacts Google or Vimeo as soon as the page loads, before the visitor has agreed to anything. Drift: Cookie Consent stops that. Videos are blocked in the page HTML before it reaches the browser, and your consent manager (CookieScript or Cookiebot) loads them once the visitor accepts marketing cookies.

Until then, visitors see the video's thumbnail with a dark overlay, your consent message and a button that opens the consent manager's preferences, or a link to your privacy policy.

Features:

* **Blocked on the server.** YouTube and Vimeo iframes are rewritten before the page is sent, so nothing loads before consent. That includes iframes printed directly by themes and page templates.
* **CookieScript or Cookiebot.** Pick the one on your site and the right blocking attributes are written for it.
* **Privacy-enhanced players.** YouTube embeds switch to youtube-nocookie.com, and Vimeo embeds get `dnt=1` (Vimeo's do-not-track mode).
* **Consent placeholder** with the video thumbnail, a dark overlay, your message and a button. The button opens the consent manager's preferences popup, or links to a page of your choice.
* **One image for every video**, if you'd rather not show YouTube or Vimeo thumbnails.
* **Fits theme video containers.** Inside a responsive video wrapper (`.embed-container`, `.videoWrapper`, FitVids, block editor embeds), the placeholder fills the existing box.
* **Works with lazy-loading.** Videos lazy-loaded by WP Rocket and similar plugins are blocked too.
* **Late videos are caught.** Videos added after the page loads (modals, load more) are blocked by the front-end script.
* Left alone in page builder editors (Elementor, Divi, Beaver Builder, Bricks, Oxygen, WPBakery) so you can still edit them.
* Settings are removed when you delete the plugin.

You need CookieScript or Cookiebot already installed on the site. Drift: Cookie Consent doesn't show a cookie banner itself.

= Privacy =

Drift: Cookie Consent doesn't track visitors, collect data or add credits or links to your site. It stores its settings in your WordPress database only. See **External services** for the requests visitors' browsers make to YouTube and Vimeo.

= For developers =

* `drift_cookie_consent_block_iframes` (filter, `bool`): return false to stop videos being blocked in the page HTML. They are then blocked only by the front-end script, after the page has loaded, so they may start loading before consent.

`add_filter( 'drift_cookie_consent_block_iframes', '__return_false' );`

== External services ==

This plugin doesn't send any data to Drift Creative Systems. It does work with two video services, through the visitor's browser:

**YouTube (Google)**

* Before consent, the placeholder shows the video's thumbnail from `i.ytimg.com`. The visitor's browser requests the image, which sends their IP address and browser details to Google. No cookies are set. To avoid this request, set a **Default video background image** in the settings.
* After consent, the consent manager loads the player from `youtube-nocookie.com`.
* [YouTube Terms of Service](https://www.youtube.com/t/terms), [Google Privacy Policy](https://policies.google.com/privacy)

**Vimeo**

* Before consent, the placeholder looks up the video's thumbnail through Vimeo's public oEmbed API (`vimeo.com/api/oembed.json`) and shows it from `i.vimeocdn.com`. The request sends the video's URL and the visitor's IP address and browser details, without cookies. To avoid this request, set a **Default video background image** in the settings.
* After consent, the consent manager loads the player from `player.vimeo.com` with `dnt=1`.
* [Vimeo Terms of Service](https://vimeo.com/terms), [Vimeo Privacy Policy](https://vimeo.com/privacy)

== Installation ==

1. Install and activate CookieScript or Cookiebot if you haven't already.
2. Upload the `drift-cookie-consent` folder to `/wp-content/plugins/`, or install it from Plugins → Add New.
3. Activate Drift: Cookie Consent.
4. Go to **Settings → Drift: Cookie Consent**, pick your consent manager and check the cookie category matches the one videos should be blocked under.

== Frequently Asked Questions ==

= Which videos are supported? =

YouTube (youtube.com, youtube-nocookie.com, youtu.be) and Vimeo (player.vimeo.com) iframe embeds.

= Which consent managers are supported? =

CookieScript and Cookiebot. Pick the one on your site under Settings → Drift: Cookie Consent. The setting must match the consent manager actually installed, or blocked videos will never load.

= Which cookie category should I use? =

The category your consent manager uses for marketing cookies. The default is `marketing`. On Cookiebot it must be one of `marketing`, `statistics` or `preferences`.

= What happens if I leave the consent link URL empty? =

The button opens the consent manager's preferences popup (`CookieScript.show()` or `Cookiebot.renew()`).

= Can I show the same image on every video? =

Yes. Set **Default video background image URL**. It also means no thumbnail is requested from YouTube or Vimeo before consent.

= Can I turn off server-side blocking? =

Yes, with the `drift_cookie_consent_block_iframes` filter (see For developers). Videos are then blocked by the front-end script only, which runs after the page has loaded, so they may start loading before consent.

== Screenshots ==

1. The consent placeholder over a blocked YouTube video.
2. Settings → Drift: Cookie Consent.

== Changelog ==

= 1.0.0 =
* First release as Drift: Cookie Consent, based on Bonsai Cookie Consent 2.5.2.
* Settings moved to Settings → Drift: Cookie Consent.
* Removed the GitHub updater. Updates come from WordPress.org.
