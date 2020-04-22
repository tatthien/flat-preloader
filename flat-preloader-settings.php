<?php
defined( 'ABSPATH' ) || die;

add_action( 'admin_menu', 'flat_preloader_settings_menu' );

function flat_preloader_settings_menu() {
	$page_slug = 'flat-preloader';

	add_submenu_page(
		'options-general.php',
		esc_html__( 'Flat Preloader Settings', 'flat_preloader' ),
		esc_html__( 'Flat Preloader', 'flat_preloader' ),
		'manage_options',
		$page_slug,
		'flat_preloader_settings_page'
	);

	/* Save options */
	if ( isset( $_GET['page'] ) && $_GET['page'] == $page_slug ) {
		if ( isset( $_REQUEST['save-option'] ) && $_REQUEST['save-option'] != "" ) {
			if ( isset( $_REQUEST['preloader-style'] ) ) {
				update_option( 'preloader-style', $_REQUEST['preloader-style'] );
			}

			if ( isset( $_REQUEST['preloader-display'] ) ) {
				update_option( 'preloader-display', $_REQUEST['preloader-display'] );
			}
		}
	}
}

function flat_preloader_settings_page() {
	$preloader_img = apply_filters( 'flat_preloader_styles', array(
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
		)
    ) );

	$style   = get_option( 'preloader-style' );
	$display = get_option( 'preloader-display' );

	?>
    <h1 style="margin: 40px;"><?php esc_html_e( 'Flat Preloader Settings', 'flat_preloader' ); ?></h1>
    <div class="wp-preloading-wrapper">
        <form method="post">
			<?php foreach ( $preloader_img as $preloader ) { ?>
                <div class="wp-preloading-section">
                    <h2><?php echo $preloader['title_name']; ?></h2>
                    <ul>
	                    <?php $icon_dir_path = FLAT_PRELOADER_PLUGIN_PATH . '/assets/images/' . $preloader['key_name']; ?>
	                    <?php $icon_dir_url = FLAT_PRELOADER_PLUGIN_URL . '/assets/images/' . $preloader['key_name']; ?>
	                    <?php foreach ( glob( $icon_dir_path . '/*.gif' ) as $file ) { ?>
		                    <?php
		                    $icon_name  = str_replace( $icon_dir_path . '/', '', $file );
		                    $icon_id    = sanitize_title( $icon_name );
		                    $icon_url   = $icon_dir_url . '/' . $icon_name;
		                    $icon_value = $preloader['key_name'] . '/' . $icon_name;
		                    ?>
                            <li class="preloader-item">
                                <label for="<?php echo $icon_id; ?>">
                                    <input id="<?php echo $icon_id; ?>" type="radio" name="preloader-style" value="<?php echo $icon_value; ?>" <?php checked( $style, $icon_value ); ?>>
                                    <img src="<?php echo $icon_url; ?>" alt="<?php echo $icon_id ?>"/>
                                </label>
                            </li>
						<?php } ?>
                    </ul>
                </div>
			<?php } ?>

            <div class="wp-preloading-section">
                <h2><?php esc_html_e( 'More settings', 'flat_preloader' ); ?></h2>
                <label for="preloader-display"><?php esc_html_e( 'Preloader will be appeared on', 'flat_preloader' ); ?></label>
                <select name="preloader-display" id="preloader-display">
                    <option value="all" <?php selected( $display, 'all' ); ?>><?php esc_html_e( 'All pages', 'flat_preloader' ); ?></option>
                    <option value="home" <?php selected( $display, 'home' ); ?>><?php esc_html_e( 'Home', 'flat_preloader' ); ?></option>
                </select>
            </div>

            <input type="submit" class="button-primary" name="save-option" value="Save Changes">
        </form>
    </div>
	<?php
}
