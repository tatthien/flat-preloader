<?php
defined('ABSPATH') || die;

add_action('admin_menu', 'flat_preloader_settings_menu');

function flat_preloader_settings_menu()
{
    $page_slug = 'flat-preloader';

    add_submenu_page(
        'options-general.php',
        esc_html__('Flat Preloader Settings', 'flat-preloader'),
        esc_html__('Flat Preloader', 'flat-preloader'),
        'manage_options',
        $page_slug,
        'flat_preloader_settings_page'
    );

    /* Save options */
    if (isset($_GET['page']) && $_GET['page'] === $page_slug) {
        if (isset($_REQUEST['save-option']) && $_REQUEST['save-option'] !== '') {
            // Verify nonce
            if (!isset($_REQUEST['_wpnonce'])) {
                return false;
            }

            if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'flat_preloader_option_saving')) {
                return false;
            }

            if (isset($_REQUEST['preloader-style'])) {
                update_option('preloader-style', sanitize_text_field($_REQUEST['preloader-style']));
            }

            if (isset($_REQUEST['preloader-display'])) {
                update_option('preloader-display', sanitize_text_field($_REQUEST['preloader-display']));
            }

            if (isset($_POST['preloader'])) {
                $sanitized_opts = [];
                foreach($_POST['preloader'] as $key => $value) {
                    if ($key === 'custom_image_url') {
                        $sanitized_opts[$key] = esc_url_raw($value);
                        continue;
                    }
                    $sanitized_opts[$key] = sanitize_text_field($value);
                }
                update_option('_flat_preloader', $sanitized_opts);
            }
        }
    }
}

function flat_preloader_settings_page()
{
    $preloader_img = apply_filters('flat_preloader_styles', array(
        array(
            'key_name'   => 'color-style',
            'title_name' => 'Color style animated icons',
        ),
        array(
            'key_name'   => 'ios-glyph',
            'title_name' => 'iOS Glyph style animated icons',
        ),
        array(
            'key_name'   => 'windows-10',
            'title_name' => 'Windows 10 style animated icons',
        ),
        array(
            'key_name'   => 'office-style',
            'title_name' => 'Office style style animated icons',
        ),
        array(
            'key_name'   => 'modern-flat',
            'title_name' => 'Modern Flat',
        ),
        array(
            'key_name'   => 'flat',
            'title_name' => 'Flat',
        ),
        array(
            'key_name'   => 'emoji',
            'title_name' => 'Emoji',
        ),
    ));

    $style   = get_option('preloader-style');
    $display = get_option('preloader-display');

?>
    <h1 style="margin: 40px;"><?php esc_html_e('Flat Preloader Settings', 'flat-preloader'); ?></h1>
    <div class="wp-preloading-wrapper">
        <form method="post">
            <?php foreach ($preloader_img as $preloader) { ?>
                <div class="wp-preloading-section">
                    <?php
                    $icon_dir_path = FLAT_PRELOADER_PLUGIN_PATH . '/assets/images/' . $preloader['key_name'];
                    $icon_dir_url = FLAT_PRELOADER_PLUGIN_URL . '/assets/images/' . $preloader['key_name'];
                    $files = glob($icon_dir_path . '/*.gif');
                    $total_files = count($files);
                    $title_name = $preloader['title_name'];
                    ?>

                    <h2><?php echo esc_html("$title_name ($total_files)"); ?></h2>
                    <ul>
                        <?php foreach ($files as $file) { ?>
                            <?php
                            $icon_name = str_replace($icon_dir_path . '/', '', $file);
                            $icon_id  = sanitize_title($preloader['key_name'] . '_' . $icon_name);
                            $icon_url = $icon_dir_url . '/' . $icon_name;
                            $icon_value = $preloader['key_name'] . '/' . $icon_name;
                            ?>
                            <li class="preloader-item">
                                <label for="<?php echo esc_attr($icon_id); ?>">
                                    <input id="<?php echo esc_attr($icon_id); ?>" type="radio" name="preloader-style" value="<?php echo esc_attr($icon_value); ?>" <?php checked($style, $icon_value); ?>>
                                    <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($icon_id) ?>" />
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <div class="wp-preloading-section">
                <h2 style="margin-bottom: 1rem;"><?php esc_html_e('More settings', 'flat-preloader'); ?></h2>
                <div class="form-group">
                    <label for="preloader-display"><?php esc_html_e('Preloader will be appeared on', 'flat-preloader'); ?></label>
                    <select name="preloader-display" id="preloader-display">
                        <option value="all" <?php selected($display, 'all'); ?>><?php esc_html_e('All pages', 'flat-preloader'); ?></option>
                        <option value="home" <?php selected($display, 'home'); ?>><?php esc_html_e('Only homepage', 'flat-preloader'); ?></option>
                    </select>
                </div>

                <?php $settings = get_option('_flat_preloader'); ?>
                <div class="form-group" style="max-width: 500px;">
                    <label for="custom_image_url"><?php esc_html_e('Custom animated icon URL', 'flat-preloader'); ?></label>
                    <input 
                        type="url"
                        id="custom_image_url" 
                        name="preloader[custom_image_url]" 
                        class="regular-text"
                        placeholder="https://" 
                        value="<?php echo isset($settings['custom_image_url']) ? esc_url($settings['custom_image_url']) : ''; ?>"
                    >
                    <p class="description"><?php esc_html_e('If you don\'t like the icons above, you can add your own by entering the URL here. This value will override the selected icon above.', 'flat-preloader'); ?></p>
                </div>

                <div class="form-group">
                    <label for="text_under_icon"><?php esc_html_e('Text under loading icon', 'flat-preloader'); ?></label>
                    <input 
                        type="text" 
                        id="text_under_icon" 
                        name="preloader[text_under_icon]"
                        class="regular-text" 
                        placeholder="<?php esc_html_e('E.g: Loading...', 'flat-preloader'); ?>" 
                        value="<?php echo isset($settings['text_under_icon']) ? esc_attr($settings['text_under_icon']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="delay_time"><?php esc_html_e('Delay time (ms)', 'flat-preloader'); ?></label>
                    <input 
                        type="number" 
                        id="delay_time" 
                        name="preloader[delay_time]" 
                        class="regular-text" 
                        placeholder="Default is 1 second" 
                        value="<?php echo isset($settings['delay_time']) ? esc_attr($settings['delay_time']) : ''; ?>"
                    >
                    <p class="description"><?php esc_html_e('When your site is fully loaded, the preloader will fade out after the delay time. ', 'flat-preloader'); ?></p>
                </div>
                
                <div class="form-group">
                    <label for="alt"><?php esc_html_e('Alt text', 'flat-preloader'); ?></label>
                    <input 
                        type="text" 
                        id="alt"
                        name="preloader[alt]" 
                        class="regular-text" 
                        placeholder="" 
                        value="<?php echo isset($settings['alt']) ? esc_attr($settings['alt']) : ''; ?>"
                    >
                    <p class="description">
                        <?php esc_html_e('Add alt text for icon to improve SEO score.', 'flat-preloader'); ?>
                        <?php printf(__('<a href="%s" target="_blank">Learn more</a>', 'flat-preloader'), 'https://moz.com/learn/seo/alt-text'); ?>
                    </p>
                </div>
            </div>

            <?php echo wp_nonce_field('flat_preloader_option_saving'); ?>

            <input type="submit" class="button-primary" name="save-option" value="Save Changes">

            <div style="margin: 4rem 0 1rem 0;">
                <p><?php esc_html_e('Animated icons by', 'flat-preloader'); ?>: <a href="https://icons8.com">icon8</a>, <a href="https://pixelbuddha.net/">PixelBuddha</a></p>
            </div>
            <a href="https://www.buymeacoffee.com/tatthien" target="_blank"><img src="https://img.buymeacoffee.com/button-api/?text=Buy me a coffee&emoji=☕&slug=tatthien&button_colour=FF5F5F&font_colour=ffffff&font_family=Inter&outline_colour=000000&coffee_colour=FFDD00"></a>
        </form>
    </div>
<?php
}
