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
	wp_enqueue_script('extra.slider', EXTRA_MODULES_URI.'/extra.slider/inc/js/extra.slider.js', array('jquery', 'tweenmax'), null, true);
	wp_enqueue_style('extra.slider', EXTRA_MODULES_URI.'/extra.slider/inc/css/extra.slider.css', array(), false, 'all');
}
add_action('wp_enqueue_scripts', 'extra_slider_init');