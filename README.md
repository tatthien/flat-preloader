<p align="center"><a href="https://wordpress.org/plugins/flat-preloader/" target="_blank"><img src="https://ps.w.org/flat-preloader/assets/icon-128x128.png"></a></p>

<h1 align="center">Flat Preloader</h1>

<p align="center">💈 Create the loading page for WordPress site with many excited gif icons.</p>

<a href="https://wordpress.org/plugins/flat-preloader/"><img src="https://img.shields.io/wordpress/plugin/v/flat-preloader.svg" alt="Plugin version"/></a>
<a href="https://wordpress.org/plugins/flat-preloader/"><img src="https://img.shields.io/wordpress/plugin/tested/flat-preloader.svg" alt="WordPress tested version" /></a>

![Alt](https://repobeats.axiom.co/api/embed/2fd6b6b18c2f64c868954e45c6491dba27ccc610.svg "Repobeats analytics image")

### Description

Flat Preloader helps you create the loading page with many excited gif icons.

You can select where the loading page will be shown, such as home page or all pages.

### Installation
1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Use the **Settings -> Flat Preloader** screen to configure the plugin.
4. Choose your style and enjoy.

### Changelog

1.5.5

- Fix: XSS on front-end

1.5.4

- Improve: Securing the input & output of settings

1.5.3

- Improve: Securing the input & output of settings

1.5.2

- Chore: Update button "Buy me a coffee"

1.5.1

- Fix: Missing verify nonce which allows CSRF attack.

1.5

- Feat: You can add custom icon url if you don't like the avaible icons.
- Feat: Able to add icon alt text. This will help improve SEO score of your page.
- Improve: Show total icons count in each icon section.

1.4.1

- Fix: Change HTML event from `DOMContentLoaded` to `load`. I encounter an issue when some websites use optmization plugins to minify JavaScript and load JavaScript with defer. If the script is loaded with defer, it will execute before `DOMContenLoader`. Hence, the loading screen will be stuck and will not be hidden.

1.4

- Update: Remove text domain header.
- Update: Change text domain from 'flat_preloader' to 'flat-preloader'

1.3.1

- Fix: The overlay does not remove after faded out.

1.3

- Feat: Add more 60+ animated icons.
- Update: Get rid of jQuery. The script that shows the loading icon on the front-end is pure JavaScript.
- Update: Styles of hover, active animated incons in the settings page.

1.2

- Update: Re-arrange the settings page.
- Feat: Add text under loading icon.
- Feat: Add delay time. The loading icon will fade out after the delay time.

1.1.2

- Fix: cannot scroll the site when it was rendered in iframe.

1.1.1

- Fix: remove duplicate slash from asset URLs.

1.1

- Add Modern Flat icons.

1.0.1

- Add more icons.
- Add options to select where the preloading page will be appeared.
