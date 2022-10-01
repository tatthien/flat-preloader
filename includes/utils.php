<?php

function flat_preloader_get_value( $value, $default = null ) {
	if (isset( $value ) && ! empty( $value ) ) {
		 return $value;
	}

	return $default;
}
