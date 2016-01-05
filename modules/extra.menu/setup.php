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
	wp_enqueue_script('extra.menu', EXTRA_MODULES_URI.'/extra.menu/inc/js/extra.menu.js', array('jquery', 'tweenmax'), null, true);
	wp_enqueue_style('extra.menu', EXTRA_MODULES_URI.'/extra.menu/inc/css/extra.menu.css', array(), false, 'all');
}
add_action('init', 'extra_menu_init');