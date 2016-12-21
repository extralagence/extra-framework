<?php
/**********************
 *
 *
 *
 * EXTRA STICKY
 *
 *
 *
 *********************/
function extra_sticky__enqueue_assets() {
	$extra_enabled_extra_sticky = apply_filters( 'extra_enabled_extra_sticky', true );
	if ( ! $extra_enabled_extra_sticky ) {
		return;
	}
	// EXTRA STICKY
	wp_enqueue_script( 'extra.sticky', EXTRA_MODULES_URI . '/extra.sticky/inc/js/extra.sticky.js', array( 'extra' ), EXTRA_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'extra_sticky__enqueue_assets' );