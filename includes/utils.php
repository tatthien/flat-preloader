<?php

function flat_preloader_get_settings() {
	return wp_parse_args(
		get_option( '_flat_preloader' ),
		array(
			'delay_time'               => 1000,
			'show_preloader_instantly' => '',
			'custom_image_url'         => '',
			'alt'                      => 'Flat Preloader Icon',
			'post_id'                  => '',
			'text_under_icon'          => '',
		)
	);
}
