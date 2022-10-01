<?php
/**
 * The main file of the Flat Preloader Settings
 *
 * @package flat-preloader
 */

defined( 'ABSPATH' ) || die;

add_action( 'admin_menu', 'flat_preloader_settings_menu' );

/**
 * Add the settings menu
 */
function flat_preloader_settings_menu() {
	$page_slug = 'flat-preloader';

	add_submenu_page(
		'options-general.php',
		esc_html__( 'Flat Preloader Settings', 'flat-preloader' ),
		esc_html__( 'Flat Preloader', 'flat-preloader' ),
		'manage_options',
		$page_slug,
		'flat_preloader_settings_page'
	);

	/* Save options */
	if ( isset( $_GET['page'] ) && $_GET['page'] === $page_slug ) {
		if ( isset( $_REQUEST['save-option'] ) && $_REQUEST['save-option'] !== '' ) {
			// Verify nonce.
			if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
				return false;
			}

			if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'flat_preloader_option_saving' ) ) {
				return false;
			}

			if ( isset( $_REQUEST['preloader-style'] ) ) {
				update_option( 'preloader-style', sanitize_text_field( $_REQUEST['preloader-style'] ) );
			}

			if ( isset( $_REQUEST['preloader-display'] ) ) {
				update_option( 'preloader-display', sanitize_text_field( $_REQUEST['preloader-display'] ) );
			}

			if ( isset( $_POST['preloader'] ) ) {
				$sanitized_opts = array();

				foreach ( $_POST['preloader'] as $key => $value ) {
					$value = apply_filters( 'flat_preloader_option_' . $key, $value );

					if ( $key === 'custom_image_url' ) {
						$sanitized_opts[ $key ] = esc_url_raw( $value );
						continue;
					}

					$sanitized_opts[ $key ] = sanitize_text_field( $value );
				}

				update_option( '_flat_preloader', $sanitized_opts );
			}
		}
	}
}

/**
 * Output the settings page
 */
function flat_preloader_settings_page() {
	$preloader_img = apply_filters(
		'flat_preloader_styles',
		array(
			array(
				'key_name'   => 'color-style',
				'title_name' => 'Color',
			),
			array(
				'key_name'   => 'ios-glyph',
				'title_name' => 'iOS Glyph',
			),
			array(
				'key_name'   => 'windows-10',
				'title_name' => 'Windows 10',
			),
			array(
				'key_name'   => 'office-style',
				'title_name' => 'Office',
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
		)
	);

	$style    = get_option( 'preloader-style' );
	$display  = get_option( 'preloader-display' );
	$settings = flat_preloader_get_settings();
	?>
	<h1 style="padding-top: 1.5rem"><?php esc_html_e( 'Flat Preloader Settings', 'flat-preloader' ); ?></h1>
	<div class="wp-preloading-wrapper metabox-holder">
		<div class="postbox">
			<div class="postbox-header">
				<h2 class="hndle"><?php esc_html_e( 'Settings', 'flat-preloader' ); ?></h2>
			</div>
			<div class="inside">
				<form method="post" id="flat-preloader-settings-form">
				<?php foreach ( $preloader_img as $preloader ) { ?>
					<div class="wp-preloading-section">
						<?php
						$icon_dir_path = FLAT_PRELOADER_PLUGIN_PATH . '/assets/img/' . $preloader['key_name'];
						$icon_dir_url  = FLAT_PRELOADER_PLUGIN_URL . '/assets/img/' . $preloader['key_name'];
						$files         = glob( $icon_dir_path . '/*.gif' );
						$total_files   = count( $files );
						$title_name    = $preloader['title_name'];
						?>

						<details id="<?php echo $preloader['key_name']; ?>" open>
							<summary><strong style="font-size: 1rem;"><?php echo esc_html( "$title_name ($total_files)" ); ?></strong></summary>
							<ul>
								<?php foreach ( $files as $file ) { ?>
									<?php
									$icon_name  = str_replace( $icon_dir_path . '/', '', $file );
									$icon_id    = sanitize_title( $preloader['key_name'] . '_' . $icon_name );
									$icon_url   = $icon_dir_url . '/' . $icon_name;
									$icon_value = $preloader['key_name'] . '/' . $icon_name;
									?>
									<li class="preloader-item">
										<label for="<?php echo esc_attr( $icon_id ); ?>">
											<input id="<?php echo esc_attr( $icon_id ); ?>" type="radio" name="preloader-style" value="<?php echo esc_attr( $icon_value ); ?>" <?php checked( $style, $icon_value ); ?>>
											<img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $icon_id ); ?>" />
										</label>
									</li>
								<?php } ?>
							</ul>
						</details>
					</div>
				<?php } ?>

				<div class="wp-preloading-section">
					<h3 class="mb-1"><?php esc_html_e( 'More display options', 'flat-preloader' ); ?></h3>
					<table class="form-table">
						<tbody>
							<tr>
								<th><label for="preloader-display"><?php esc_html_e( 'Show preloader on', 'flat-preloader' ); ?></label></th>
								<td>
								<select class="widefat" name="preloader-display" id="preloader-display">
									<option value="all" <?php selected( $display, 'all' ); ?>><?php esc_html_e( 'All pages', 'flat-preloader' ); ?></option>
									<option value="home" <?php selected( $display, 'home' ); ?>><?php esc_html_e( 'Only homepage', 'flat-preloader' ); ?></option>
									<option value="custom" <?php selected( $display, 'custom' ); ?>><?php esc_html_e( 'Custom', 'flat-preloader' ); ?></option>
								</select>
								</td>
							</tr>
							<tr id="fp-option-post-id" <?php echo $display !== 'custom' ? 'style="display: none;"' : ''; ?>>
								<th><label for="post_id"><?php esc_html_e( 'Post ID', 'flat-preloader' ); ?></label></th>
								<td>
									<input 
										type="text"
										id="post_id"
										name="preloader[post_id]"
										class="widefat"
										value="<?php echo $settings['post_id']; ?>" 
									>
									<p class="description"><?php esc_html_e( 'Enter the ID of the post where you want to show loading icon. Multiple ID separated by comma.', 'flat-preloader' ); ?></p>
									<p class="description"><?php esc_html_e( 'How to get post ID', 'flat-preloader' ); ?></p>
									<p>
										<a href="<?php echo FLAT_PRELOADER_PLUGIN_URL . 'admin/img/how-to-get-post-id.png'; ?>" target="_blank">
											<img src="<?php echo FLAT_PRELOADER_PLUGIN_URL . 'admin/img/how-to-get-post-id.png'; ?>" style="max-width: 100%; height: auto;" alt="How to get post ID?">
										</a>
									</p>
								</td>
							</tr>
							<tr>
								<th><label for="custom_image_url"><?php esc_html_e( 'Custom icon URL', 'flat-preloader' ); ?></label></th>
								<td>
									<input 
										type="url"
										id="custom_image_url" 
										name="preloader[custom_image_url]" 
										class="widefat"
										placeholder="https://" 
										value="<?php echo $settings['custom_image_url']; ?>"
									>
									<p class="description"><?php esc_html_e( 'If you don\'t like the icons above, you can add your own by entering the URL here. This value will override the selected icon above.', 'flat-preloader' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="text_under_icon"><?php esc_html_e( 'Text under icon', 'flat-preloader' ); ?></label></th>
								<td>
									<input 
										type="text" 
										id="text_under_icon" 
										name="preloader[text_under_icon]"
										class="widefat" 
										placeholder="<?php esc_html_e( 'E.g: Loading...', 'flat-preloader' ); ?>" 
										value="<?php echo $settings['text_under_icon']; ?>"
									>

									<?php echo do_action( 'flat_preloader_after_text_under_icon', $settings ); ?>
								</td>
							</tr>
							<tr>
								<th><label for="delay_time"><?php esc_html_e( 'Delay time (ms)', 'flat-preloader' ); ?></label></th>
								<td>
									<input 
										type="number" 
										id="delay_time" 
										name="preloader[delay_time]" 
										class="widefat" 
										placeholder="Default is 1000 ms (1 second)" 
										value="<?php echo esc_attr( $settings['delay_time'] ); ?>"
									>
									<p class="description"><?php esc_html_e( 'When your site is fully loaded, the preloader will fade out after the delay time. ', 'flat-preloader' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="alt"><?php esc_html_e( 'Alt text', 'flat-preloader' ); ?></label></th>
								<td>
									<input 
										type="text" 
										id="alt"
										name="preloader[alt]" 
										class="widefat" 
										placeholder="Eg: Loading icon" 
										value="<?php echo esc_attr( $settings['alt'] ); ?>"
									>
									<p class="description">
										<?php esc_html_e( 'Add alt text for icon to improve SEO score.', 'flat-preloader' ); ?>
										<?php /* translators: %s: a URL */ ?>
										<?php printf( __( '<a href="%s" target="_blank">Learn more</a>', 'flat-preloader' ), 'https://moz.com/learn/seo/alt-text' ); ?>
									</p>
								</td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Show preloader immediately', 'flat-preloader' ); ?></th>
								<td>
									<label for="show_preloader_instantly">
										<input 
											id="show_preloader_instantly"
											name="preloader[show_preloader_instantly]" 
											type="checkbox" 
											<?php echo checked( $settings['show_preloader_instantly'], '1' ); ?> 
											value="1"
										>
										<span><?php esc_html_e( 'Show preloader immediately when a link is clicked.', 'flat-preloader' ); ?></span>
									</label>
									<p class="description"><?php esc_html_e( 'This only works only if the "Show preloader on" option is "All pages"', 'flat-preloader' ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>

					<?php do_action( 'flat_preloader_after_settings', $settings ); ?>
				</div>

				<?php echo wp_nonce_field( 'flat_preloader_option_saving' ); ?>

				<input type="submit" id="flat-preloader-submit-form-button" class="button-primary" name="save-option" value="Save Changes">

				<div>
					<p><?php esc_html_e( 'Animated icons by', 'flat-preloader' ); ?>: <a href="https://icons8.com">icon8</a>, <a href="https://pixelbuddha.net/">PixelBuddha</a></p>
				</div>
			</form>
			</div>
		</div>
		<aside>
			<?php echo do_action( 'flat_preloader_before_aside', $settings ); ?>
			<div class="postbox">
				<div class="postbox-header">
					<h2 class="hndle"><?php echo esc_html__( 'Support me', 'flat-preloader' ); ?></h2>
				</div>
				<div class="inside">
					<p><?php esc_html_e( 'Thanks for using my plugin. Your support means a lot to me.', 'flat-preloader' ); ?></p>
					<div class="mb-4">
						<?php
							_e( 'Please rate <strong>Flat Preloader</strong> <a href="https://wordpress.org/support/plugin/flat-preloader/reviews/?filter=5#new-post" target="_blank">⭐⭐⭐⭐⭐</a> on <a href="https://wordpress.org/support/plugin/flat-preloader/reviews/?filter=5#new-post" target="_blank">WordPress.org</a> to help me spread the word.', 'flat-preloader' );
						?>
					</div>
					<div class="hide-on-pro">
						<a href="https://www.buymeacoffee.com/tatthien" class="btn-buy-me-a-coffee" target="_blank"><img src="https://img.buymeacoffee.com/button-api/?text=Buy me a coffee&emoji=☕&slug=tatthien&button_colour=FF5F5F&font_colour=ffffff&font_family=Inter&outline_colour=000000&coffee_colour=FFDD00"></a>
					</div>
				</div>
			</div>
			<div class="postbox hide-on-pro">
				<div class="postbox-header">
					<h2 class="hndle"><?php esc_html_e( 'Pro version', 'flat-preloader' ); ?></h2>
				</div>
				<div class="inside">
					<p><?php _e( 'You\'re using Flat Preloader free version. To unlock more features consider <a href="https://thisisthien.gumroad.com/l/flat-preloader-pro" target="_blank">upgrade to Pro</a>', 'flat-preloader' ); ?></p>
					<ul>
						<li>⚡️ <?php _e( '<strong>"Unlimited"</strong> CSS loading animations', 'flat-preloader' ); ?></li>
						<li>⚡️ <?php esc_html_e( 'Change background image, color or gradient', 'flat-preloader' ); ?></li>
						<li>⚡️ <?php esc_html_e( 'Change the size, and color of the text under preloader', 'flat-preloader' ); ?></li>
					</ul>
				</div>
			</div>
			<div class="postbox">
				<div class="postbox-header">
					<h2 class="hndle"><?php esc_html_e( 'Other plugins', 'flat-preloader' ); ?></h2>
				</div>
				<div class="inside">
					<div class="flat-preloader-ext-item">
						<a href="https://thisisthien.gumroad.com/l/wp-block-mindmap" target="_blank">
							<img src="<?php echo FLAT_PRELOADER_PLUGIN_URL . 'admin/img/wp-block-mindmap-200.png'; ?>"alt="WP Block Mindmap logo">
							<div>
								<h4>WP Block Mindmap</h4>
								<p>From markdown content to interactive mindmaps</p>
							</div>
						</a>
					</div>
				</div>
			</div>
			<?php echo do_action( 'flat_preloader_after_aside', $settings ); ?>
		</aside>
	</div>

	<script>
		const detailsEl = document.querySelectorAll('.wp-preloading-section details')
		detailsEl.forEach(el => {
			const key = `flat_preloader_details_${el.id}`
			const isOpen = JSON.parse(localStorage.getItem(key)) ?? true
			el.open = isOpen 

			el.addEventListener('toggle', event => {
				localStorage.setItem(key, event.target.open)
			})
		})

		// Toggle show/hide post-id option
		document.getElementById('preloader-display').addEventListener('change', event => {
			const value = event.target.value
			if (value === 'custom') {
				document.getElementById('fp-option-post-id').style.display = 'table-row'
			} else {
				document.getElementById('fp-option-post-id').style.display = 'none'
			}
		})
	</script>
	<?php
}
