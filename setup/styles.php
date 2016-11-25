<?php
///////////////////////////////////////
//
//
// CSS
//
//
///////////////////////////////////////
function extra_framework_enqueue_styles() {
	// EXTRA CONTENT
	wp_enqueue_style( 'extra-admin-bar', EXTRA_URI . '/setup/front/css/admin-bar.less', array(), false, 'all' );

}
add_action('wp_enqueue_scripts', 'extra_framework_enqueue_styles', 9);