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
	wp_enqueue_script('extra.cookies', EXTRA_MODULES_URI.'/extra.cookies/inc/js/extra.cookies.js', array('jquery', 'tweenmax'), null, true);
	wp_enqueue_style('extra.cookies', EXTRA_MODULES_URI.'/extra.cookies/inc/css/extra.cookies.css', array(), false, 'all');
}
add_action('init', 'extra_cookies_init');