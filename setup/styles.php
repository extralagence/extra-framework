<?php
/**********************
 *
 *
 *
 * STYLEHSEETS
 *
 *
 *
 *********************/
function extra_template_enqueue_styles() {
	// REMOVE CSS FROM CONTACT FORM 7
	add_filter( 'wpcf7_load_css', '__return_false' );
	// FANCYBOX
	wp_enqueue_style( 'fancybox', EXTRA_URI . '/assets/css/jquery.fancybox.css', array(), false, 'all' );
	// FANCY SELECT
//	wp_enqueue_style( 'fancy-select', EXTRA_URI . '/assets/css/fancyselect.css', array(), false, 'all' );
	// EXTRA MOSAIC
	wp_enqueue_style( 'extra-gallery', EXTRA_URI . '/assets/css/extra.gallery.less', array(), false, 'all' );
}
add_action('wp_enqueue_scripts', 'extra_template_enqueue_styles', 9);