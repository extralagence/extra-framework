<?php
/**********************
 *
 *
 *
 * EXTRA SLIDER
 *
 *
 *
 *********************/
function extra_slider_init () {
	$extra_enabled_extra_slider = apply_filters('extra_enabled_extra_slider', true);
	if(!$extra_enabled_extra_slider) {
		return;
	}
	wp_enqueue_script('extra.slider', EXTRA_MODULES_URI.'/extra.slider/inc/js/extra.slider.js', array('jquery', 'tweenmax'), null, true);
	wp_enqueue_style('extra.slider', EXTRA_MODULES_URI.'/extra.slider/inc/css/extra.slider.css', array(), false, 'all');
	wp_enqueue_script('extra-gallery', EXTRA_URI . '/assets/js/lib/extra.gallery.js', array('extra.slider'), null, true);
}
add_action('wp_enqueue_scripts', 'extra_slider_init');