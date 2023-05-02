<?php
/**
 * The main file of the Flat Preloader
 *
 * @package flat-preloader
 * @version 1.16.0
 *
 * Plugin Name: Flat Preloader
 * Plugin URI:  https://wordpress.org/plugins/flat-preloader/
 * Description: Create preloading page with many various styles
 * Version:     1.16.0
 * Author:      Thien Nguyen
 * Author URI:  https://thien.dev
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || die;

define( 'FLAT_PRELOADER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'FLAT_PRELOADER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FLAT_PRELOADER_VERSION', '1.16.0' );

require_once dirname( __FILE__ ) . '/includes/utils.php';
require_once dirname( __FILE__ ) . '/flat-preloader-settings.php';

/**
 * Add scripts and styles for plugin settings
 */
function flat_preloader_add_admin_scripts() {
	wp_enqueue_style( 'flat-preloader-admin', untrailingslashit( FLAT_PRELOADER_PLUGIN_URL ) . '/assets/css/flat-preloader.css', array(), FLAT_PRELOADER_VERSION, 'all' );
}

add_action( 'admin_enqueue_scripts', 'flat_preloader_add_admin_scripts' );

/**
 * Add scripts and styles for front page
 */
function flat_preloader_add_public_scripts() {
	$settings = flat_preloader_get_settings();
  $display  = get_option( 'preloader-display' );
  $ignoreLinks = apply_filters('flat_preloader_ignore_links', array(
				'^https?:\/\/[^\/]+' . preg_quote( wp_unslash( $_SERVER['REQUEST_URI'] ), '/' ) . '(#.*)?$',
				'^' . preg_quote( admin_url(), '/' ),
				'^' . preg_quote( site_url(), '/' ) . '[^?#]+\.php',
				preg_quote( wp_parse_url( content_url(), PHP_URL_PATH ), '/' ),
                '.*\?.+',
                '^' . preg_quote( wp_unslash( $_SERVER['REQUEST_URI'] ), '/' ) . '(#.*)?$',
                '^#.*',
                '^mailto:.*',
                '^tel:.*',
            ),
  );
  $showPreloaderInstantly = apply_filters('flat_preloader_show_preloader_instantly', $settings['show_preloader_instantly'] === '1' && $display === 'all' ? true : false);

	wp_enqueue_style( 'flat-preloader', untrailingslashit( FLAT_PRELOADER_PLUGIN_URL ) . '/assets/css/flat-preloader-public.css', array(), FLAT_PRELOADER_VERSION, 'all' );
	wp_enqueue_script( 'flat-preloader-js', untrailingslashit( FLAT_PRELOADER_PLUGIN_URL ) . '/assets/js/flat-preloader.js', array( 'jquery' ), FLAT_PRELOADER_VERSION, true );
	wp_localize_script(
		'flat-preloader-js',
		'flatPreloader',
		array(
			'delayTime'              => $settings['delay_time'] ? $settings['delay_time'] : 1000,
			'showPreloaderInstantly' => $showPreloaderInstantly,
			'host'                   => $_SERVER['HTTP_HOST'],
			'ignores'                => $ignoreLinks,
			'display'                => $display,
		)
	);
}

add_action( 'wp_enqueue_scripts', 'flat_preloader_add_public_scripts' );

/**
 * Preloader output
 */
function flat_preloader_output() {
	global $post;

	$style = get_option( 'preloader-style' );

	if ( ! $style ) {
		$style = 'flat/flat_8.gif';
	}

	$display              = get_option( 'preloader-display' );
	$settings             = flat_preloader_get_settings();
	$custom_image_url     = $settings['custom_image_url'];
	$has_custom_image_url = ! empty( $custom_image_url );
	$image_url            = $has_custom_image_url ? $custom_image_url : untrailingslashit( FLAT_PRELOADER_PLUGIN_URL ) . '/assets/img/' . $style;
	$text                 = $settings['text_under_icon'];
	$alt                  = esc_attr( $settings['alt'] );
	$post_id              = esc_attr( $settings['post_id'] );
	$overlay_class        = $has_custom_image_url ? 'fpo-custom' : 'fpo-default';

	ob_start();
	?>
	<div id="flat-preloader-overlay" class="<?php echo $overlay_class; ?>">
		<?php do_action( 'flat_preloader_output_before_overlay', $settings ); ?>

		<img src="<?php echo $image_url; ?>" alt="<?php echo $alt; ?>">
		<small><?php echo $text; ?></small>

		<?php do_action( 'flat_preloader_output_after_overlay', $settings ); ?>
	</div>
	<?php
	$content = ob_get_clean();

	$ids = explode( ',', $post_id );
	$ids = array_map(
		function ( $id) {
			return (int) trim( $id );
		},
		$ids
	);

	if ( $display === 'home' && ( is_home() || is_front_page() ) ) {
		echo $content;
	} elseif ( $display === 'all' ) {
		echo $content;
	} elseif ( $display === 'custom' && in_array( $post->ID, $ids, true ) && is_singular() ) {
		echo $content;
	}
}

add_action( 'wp_head', 'flat_preloader_output', 1000 );

/**
 * Add custom classes to body
 *
 * @since 1.1.2
 *
 * @param array $classes The body classes.
 *
 * @return array $classes The body classes
 */
function flat_preloader_body_classes( $classes ) {
	$display = get_option( 'preloader-display' );

	if ( $display === 'home' && ( is_home() || is_front_page() ) ) {
		$classes[] = 'flat-preloader-active';
	} elseif ( $display === 'all' ) {
		$classes[] = 'flat-preloader-active';
	}

	return $classes;
}

add_filter( 'body_class', 'flat_preloader_body_classes' );

/**
 * Add plugin action links
 *
 * @param array $links The plugin action links.
 *
 * @return array $links
 */
function flat_preloader_plugin_action_links( $links ) {
	$links[] = '<a href="' . esc_url( get_admin_url( null, 'options-general.php?page=flat-preloader' ) ) . '">' . esc_html__( 'Settings', 'flat-preloader' ) . '</a>';
	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'flat_preloader_plugin_action_links', 100 );

