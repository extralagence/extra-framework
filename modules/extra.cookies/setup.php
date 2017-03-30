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
	wp_enqueue_style('extra.cookies', EXTRA_MODULES_URI.'/extra.cookies/inc/css/extra.cookies.less', array(), EXTRA_VERSION, 'all');
	wp_enqueue_script('js.cookie', EXTRA_MODULES_URI.'/extra.cookies/inc/js/js.cookie.js', array(), EXTRA_VERSION, true);
	wp_enqueue_script('extra.cookies', EXTRA_MODULES_URI.'/extra.cookies/inc/js/extra.cookies.js', array('jquery', 'js.cookie'), EXTRA_VERSION, true);
}
add_action('init', 'extra_cookies_init');