<?php
/**********************
 *
 *
 *
 * NO MORE JS AND CSS LOADING FOR CONTACT FORM 7
 *
 *
 *
 *********************/
// REMOVE JS FROM CONTACT FORM 7
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
///////////////////////////////////////
//
//
// Better submit button
//
//
///////////////////////////////////////
add_action( 'init', 'extra_add_shortcode_submit', 10 );
function extra_add_shortcode_submit() {
	if ( function_exists( 'wpcf7_remove_shortcode' ) ) {
		wpcf7_remove_form_tag( 'submit' );
		wpcf7_add_form_tag( 'submit', 'extra_submit_shortcode_handler' );
	}
}

if ( ! function_exists( 'extra_submit_shortcode_handler' ) ) {
	function extra_submit_shortcode_handler( $tag ) {
		$tag = new WPCF7_FormTag( $tag );

		$class = wpcf7_form_controls_class( $tag->type );

		$atts = array();

		$atts['class']    = $tag->get_class_option( $class );
		$atts['id']       = $tag->get_option( 'id', 'id', true );
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

		$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

		if ( empty( $value ) ) {
			$value = __( 'Send', 'wpcf7' );
		}

		$value = apply_filters( 'extra_cf7_submit_value', $value, $tag );

		$atts['type'] = 'submit';
		//$atts['value'] = $value;
		$atts = apply_filters( 'extra_cf7_submit_atts', $atts );

		$atts = wpcf7_format_atts( $atts );

		$html = sprintf( '<button %1$s>%2$s</button>', $atts, $value );

		return $html;
	}
}
///////////////////////////////////////
//
//
// No size attributes on contact form 7 inputs
//
//
///////////////////////////////////////
add_filter( 'wpcf7_form_elements', function ( $obj ) {
	$obj = preg_replace( '/(<[^>]+) size=".*?"/i', '$1', $obj );
	$obj = preg_replace( '/(<[^>]+) rows=".*?"/i', '$1', $obj );
	$obj = preg_replace( '/(<[^>]+) cols=".*?"/i', '$1', $obj );

	return $obj;
} );