<?php
/**********************
 *
 *
 *
 * EXTRA SCROLL ANIMATOR
 *
 *
 *
 *********************/
function extra_ajax_navigation_init() {
	$extra_enabled_extra_ajax_navigation = apply_filters( 'extra_enabled_extra_ajax_navigation', false );
	if ( ! $extra_enabled_extra_ajax_navigation ) {
		return;
	}
	wp_enqueue_script( 'extra-ajax-navigation', EXTRA_MODULES_URI . '/extra.ajaxnavigation/inc/js/extra.ajaxnavigation.js', array( 'jquery', 'tweenmax', 'extra' ), EXTRA_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'extra_ajax_navigation_init' );