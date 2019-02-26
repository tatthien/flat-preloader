<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: Flat Preloader
Plugin URI:  http://tatthien.com
Description: Create preloading page with many various styles
Version:     1.0.1
Author:      Tat Thien
Author URI:  http://tatthien.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: harmonyone
*/

define( 'PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'PLUGIN_URL', plugin_dir_url(__FILE__) );

require_once PLUGIN_PATH . '/flat-preloader-settings.php';

/**
 * Add scripts and styles for plugin settings
 * @param void
 * @return void
 */
function th_add_admin_scripts() {
	wp_enqueue_style( 'th-preloader-admin', PLUGIN_URL . '/assets/css/th-flat-preloader.css', array(), '1.0', 'all' );
}

add_action( 'admin_enqueue_scripts', 'th_add_admin_scripts' );

/**
 * Add scripts and styles for front page
 * @param void
 * @return void
 */
function th_add_public_scripts() {
	wp_enqueue_style('th-preloader', PLUGIN_URL . '/assets/css/th-flat-preloader-public.css');
	wp_enqueue_script( 'th-preloader-js', PLUGIN_URL . '/assets/js/th-flat-preloader.js', array('jquery'), '1.0', true );
}

add_action( 'wp_enqueue_scripts', 'th_add_public_scripts' );

/**
 * Preloading shortcode
 * @param void
 * @return string $output
 */
function th_preloader_output() {
	$style = get_option('preloader-style');
	$display = get_option('preloader-display');

	if($display == 'home' && (is_home() || is_front_page())) {
		$key_name = explode('_', $style);
		echo '<div id="th_preloader"><img src="'. PLUGIN_URL .'/assets/images/'. $key_name[0] .'/'. $style .'"/></div>';
	} elseif( $display == 'all' ) {
		$key_name = explode( '_', $style );
		echo '<div id="th_preloader"><img src="'. PLUGIN_URL .'/assets/images/'. $key_name[0] .'/'. $style .'"/></div>';
	}


}

add_action('wp_head', 'th_preloader_output', 1000);