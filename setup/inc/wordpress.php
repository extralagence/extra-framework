<?php
/**********************
*
*
*
* REMOVE API
*
*
*
*********************/
function remove_api() {
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}

add_action( 'after_setup_theme', 'remove_api' );
///////////////////////////////////////
//
//
// REMOVE ADMIN BAR MARGINS ON HTML
//
//
///////////////////////////////////////
function extra_remove_adminbar_margin() {
	remove_action( 'wp_head', '_admin_bar_bump_cb' );
}

add_action( 'admin_bar_init', 'extra_remove_adminbar_margin' );