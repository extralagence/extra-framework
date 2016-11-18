<?php
/**********************
 *
 *
 *
 * EXTRA MENU
 *
 *
 *
 *********************/
function extra_menu_init () {
	$extra_enabled_extra_menu = apply_filters('extra_enabled_extra_menu', false);
	if(!$extra_enabled_extra_menu) {
		return;
	}
	wp_enqueue_script('extra.menu', EXTRA_MODULES_URI.'/extra.menu/inc/js/extra.menu.js', array('jquery', 'tweenmax'), EXTRA_VERSION, true);
	wp_enqueue_style('extra.menu', EXTRA_MODULES_URI.'/extra.menu/inc/css/extra.menu.css', array(), EXTRA_VERSION, 'all');
}
add_action('init', 'extra_menu_init');