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

	$extra_fancybox_version = apply_filters( 'extra_fancybox_version', '3' );
	if ($extra_fancybox_version == '2') {

		wp_enqueue_style( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/v2/source/jquery.fancybox.css', array(), EXTRA_VERSION, 'all' );
		wp_enqueue_script( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/v2/source/jquery.fancybox.pack.js', array( 'jquery' ), EXTRA_VERSION, true );
		wp_enqueue_script( 'mousewheel', EXTRA_MODULES_URI . '/fancybox/inc/v2/lib/jquery.mousewheel.pack.js', array( 'fancybox' ), EXTRA_VERSION, true );
		wp_enqueue_script( 'fancybox.media', EXTRA_MODULES_URI . '/fancybox/inc/v2/source/helpers/jquery.fancybox-media.js', array( 'fancybox' ), EXTRA_VERSION, true );
		wp_enqueue_script( 'extra.fancybox', EXTRA_MODULES_URI . '/fancybox/js/fancybox.js', array( 'fancybox' ), EXTRA_VERSION, true );

	} else if ($extra_fancybox_version == '3') {
		wp_enqueue_style( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/jquery.fancybox.min.css', array(), EXTRA_VERSION, 'all' );
		wp_enqueue_script( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/jquery.fancybox.min.js', array( 'jquery' ), EXTRA_VERSION, true );
		wp_enqueue_script( 'extra.fancybox', EXTRA_MODULES_URI . '/fancybox/js/fancybox.js', array( 'fancybox' ), EXTRA_VERSION, true );
	}
}

add_action( 'wp_enqueue_scripts', 'extra_fancybox_init' );