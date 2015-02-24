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
	// FANCYBOX
	wp_enqueue_style( 'fancybox', EXTRA_URI . '/assets/css/jquery.fancybox.css', array(), false, 'all' );
	// FANCY SELECT
	wp_enqueue_style( 'fancy-select', EXTRA_URI . '/assets/css/fancyselect.css', array(), false, 'all' );
    // EXTRA MOSAIC
    wp_enqueue_style( 'extra-gallery', EXTRA_URI . '/assets/css/extra.gallery.less', array(), false, 'all' );
}
add_action('wp_enqueue_scripts', 'extra_template_enqueue_styles');