=== Plugin Name ===
Contributors: tatthiennguyen
Donate link: https://www.buymeacoffee.com/tatthien
Tags: wordpress, preloader, flat, gif, icon, loading
Requires at least: 4.6
Tested up to: 5.5.1
Stable tag: 5.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Flat Preloader helps you create the loading page with many excited gif icons.

You can select where the loading page will be shown, such as home page or all pages.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings -> Flat Preloader screen to configure the plugin.
4. Choose your style and enjoy.

== Screenshots ==

1. Settings
2. Demo 1
3. Demo 2

== Changelog ==

= 1.4.1 =

* Fix: Change HTML event from `DOMContentLoaded` to `load`. I encounter an issue when some websites use optmization plugins to minify JavaScript and load JavaScript with defer. If the script is loaded with defer, it will execute before `DOMContenLoader`. Hence, the loading screen will be stuck and will not be hidden.

= 1.4 =

* Update: Remove text domain header.
* Update: Change text domain from 'flat_preloader' to 'flat-preloader'

= 1.3.1 =

* Fix: The overlay does not remove after faded out.

= 1.3 =
* Feat: Add more 60+ animated icons.
* Update: Get rid of jQuery. The script that shows the loading icon on the front-end is pure JavaScript.
* Update: Styles of hover, active animated incons in the settings page.

= 1.2 =
* Update: Re-arrange the settings page.
* Feat: Add text under loading icon.
* Feat: Add delay time. The loading icon will fade out after the delay time.

= 1.1.2 =
* Fix: cannot scroll the site when it was rendered in iframe.

= 1.1.1 =
* Fix: remove duplicate slash from asset URLs

= 1.1 =
* Add Modern Flat icons

= 1.0.1 =
* Add more icons
* Add options to select where the preloading page will be appeared
