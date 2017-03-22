<?php
/**********************
 *
 *
 *
 * FANCYBOX
 *
 *
 *
 *********************/
function extra_fancybox_init() {
	$extra_enabled_fancybox = apply_filters( 'extra_enabled_fancybox', true );
	if ( ! $extra_enabled_fancybox ) {
		return;
	}

	wp_enqueue_style( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/dist/jquery.fancybox.min.css', array(), EXTRA_VERSION, 'all' );
	wp_enqueue_script( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/dist/jquery.fancybox.min.js', array( 'jquery' ), EXTRA_VERSION, true );
	wp_enqueue_script( 'extra.fancybox', EXTRA_MODULES_URI . '/fancybox/js/fancybox.js', array( 'fancybox' ), EXTRA_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'extra_fancybox_init' );