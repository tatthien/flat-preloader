<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'admin_menu', 'th_preloader_settings_menu' );

function th_preloader_settings_menu() {
	$page_slug = 'flat-preloader';

	add_submenu_page(
		'options-general.php',
		'Flat Preloader Settings',
		'Flat Preloader',
		'manage_options',
		$page_slug,
		'th_preloader_settings_page'
	);

	/* Save options */
	if( isset( $_GET['page'] ) && $_GET['page'] == $page_slug ) {
		if( isset( $_REQUEST['save-option'] ) && $_REQUEST['save-option'] != "" ) {
			if( isset( $_REQUEST['preloader-style'] ) ) {
				update_option( 'preloader-style', $_REQUEST['preloader-style'] );
			}

			if( isset( $_REQUEST['preloader-display'] ) ) {
				update_option( 'preloader-display', $_REQUEST['preloader-display'] );
			}
		}
	}
}

function th_preloader_settings_page() {
	$preloader_img = array(
		array(
			'key_name' => 'flat',
			'title_name' => 'Flat',
			'count' => 17,
		),
		array(
			'key_name' => 'emoji',
			'title_name' => 'Emoji',
			'count' => 28,
		)
	);

	$style = get_option( 'preloader-style' );
	$display = get_option( 'preloader-display' );

	?>
	<div class="wp-preloading-wrapper">
		<h1>Choose your style</h1>

		<form method="post">
			<?php foreach( $preloader_img as $preloader ) { ?>
				<div class="wp-preloading-section">
					<h2><?php echo $preloader['title_name']; ?></h2>
					<ul>
						<?php for( $i = 1; $i <= $preloader['count']; $i++ ) { ?>
							<?php $preloader_name = $preloader['key_name'] .'_'. $i .'.gif'  ?>
							<li class="preloader-item">
								<img src="<?php echo PLUGIN_URL .'/assets/images/'. $preloader['key_name'] .'/'. $preloader_name ; ?>" />

								<?php if( $preloader_name == $style ) { ?>
									<input type="radio" name="preloader-style" value="<?php echo $preloader_name; ?>" checked>
								<?php } else { ?>
									<input type="radio" name="preloader-style" value="<?php echo $preloader_name; ?>">
								<?php }?>
							</li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>

			<div class="wp-preloading-section">
				<h2>More settings</h2>
				<label for="preloader-display">Preloader will be appeared on: </label>
				<select name="preloader-display" id="preloader-display">
					<?php if( $display == 'all' ) { ?>
						<option value="all" selected>All pages</option>
						<option value="home">Home</option>
					<?php } elseif( $display == 'home' ) { ?>
						<option value="home" selected>Home</option>
						<option value="all">All pages</option>
					<?php } else { ?>
						<option value="all">All pages</option>
						<option value="home">Home</option>
					<?php } ?>
				</select>
			</div>

			<input type="submit" class="button-primary" name="save-option" value="Save Changes">
		</form>

		<p class="external-lib">
			This plugin was inspired by the awesome preloaders from <a href="http://tympanus.net/codrops/2014/04/25/freebie-flat-style-squared-preloaders/">Codrops</a> and <a href="http://pixelbuddha.net/freebie/flat-preloaders">Pixelbuddha</a>
		</p>
	</div>
	<?php
}