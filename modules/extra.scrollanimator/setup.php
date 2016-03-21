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
function extra_scroll_animator_init () {
	$extra_enabled_extra_scroll_animator = apply_filters('extra_enabled_extra_scroll_animator', false);
	if(!$extra_enabled_extra_scroll_animator) {
		return;
	}
	wp_enqueue_script('extra.scrollanimator', EXTRA_MODULES_URI.'/extra.scrollanimator/inc/js/extra.scrollanimator.js', array('jquery', 'tweenmax', 'extra'), null, true);
}
add_action('wp_enqueue_scripts', 'extra_scroll_animator_init');