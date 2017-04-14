<?php
/**********************
 *
 *
 * LESS VARS
 *
 *
 *
 *********************/
function extra_less_vars( $vars, $handle ) {
	global $epb_full_width, $epb_half_width, $epb_one_third_width, $epb_two_third_width, $epb_gap, $content_width;
	$epb_full_width      = apply_filters( 'extra_page_builder_full_width', 940 );
	$epb_half_width      = apply_filters( 'extra_page_builder_half_width', 460 );
	$epb_one_third_width = apply_filters( 'extra_page_builder_one_third_width', 300 );
	$epb_two_third_width = apply_filters( 'extra_page_builder_two_third_width', 620 );
	$epb_gap             = apply_filters( 'extra_page_builder_gap', 20 );

	$vars['epb_full_width']      = $epb_full_width . 'px';
	$vars['epb_half_width']      = $epb_half_width . 'px';
	$vars['epb_one_third_width'] = $epb_one_third_width . 'px';
	$vars['epb_two_third_width'] = $epb_two_third_width . 'px';
	$vars['epb_gap']             = $epb_gap . 'px';
	$vars['content_width']       = $content_width . 'px';

	return $vars;
}

add_filter( 'less_vars', 'extra_less_vars', 10, 2 );
/**********************
 *
 *
 * LESS FUNCTIONS
 *
 *
 *
 *********************/
function extra_register_less_functions() {
	register_less_function( 'extra_urlencode', function ( $arg ) {
		list( $type, $value ) = $arg;

		return array( 'string', '', array( urlencode( $value ) ) );
	} );
}

add_action( 'init', 'extra_register_less_functions' );
/**********************
 *
 *
 *
 * MORE VARS FOR LESS
 *
 *
 *
 *********************/
function extra_framework_theme_less_vars( $vars, $handle ) {
	$vars['extra_uri']               = '~"' . EXTRA_URI . '"';
	$vars['extra_modules_uri']       = '~"' . EXTRA_MODULES_URI . '"';
	$vars['extra_includes_uri']      = '~"' . EXTRA_INCLUDES_URI . '"';
	$vars['extra_common_module_uri'] = '~"' . EXTRA_COMMON_MODULE_URI . '"';
	$vars['theme_uri']               = '~"' . THEME_URI . '"';
	$vars['theme_modules_uri']       = '~"' . THEME_MODULES_URI . '"';
	$vars['theme_includes_uri']      = '~"' . THEME_INCLUDES_URI . '"';
	$vars['theme_common_module_uri'] = '~"' . THEME_COMMON_MODULE_URI . '"';

	return $vars;
}

add_filter( 'less_vars', 'extra_framework_theme_less_vars', 10, 2 );