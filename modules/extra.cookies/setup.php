<?php
/**********************
 *
 *
 *
 * EXTRA COOKIES
 *
 *
 *
 *********************/
function extra_cookies_init () {
	$extra_enabled_extra_cookies = apply_filters('extra_enabled_extra_cookies', false);
	if(!$extra_enabled_extra_cookies) {
		return;
	}
	wp_enqueue_script('extra.cookies', EXTRA_MODULES_URI.'/extra.cookies/inc/js/extra.cookies.js', array('jquery', 'tweenmax'), EXTRA_VERSION, true);
	wp_enqueue_style('extra.cookies', EXTRA_MODULES_URI.'/extra.cookies/inc/css/extra.cookies.css', array(), EXTRA_VERSION, 'all');
}
add_action('init', 'extra_cookies_init');