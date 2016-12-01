<?php
/**********************
 *
 *
 *
 * JAVASCRIPTS
 *
 *
 *
 *********************/
function extra_template_enqueue_scripts() {
	// REPLACE JQUERY
	wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', apply_filters( 'extra_script__jquery_dependencies', array() ), EXTRA_VERSION, apply_filters( 'extra_jquery_in_footer', true ) );
	// TWEENMAX
	wp_enqueue_script( 'tweenmax', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js', array( 'jquery' ), EXTRA_VERSION, true );
	// SCROLLTO
	wp_enqueue_script( 'tweenmax-scrollto', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/plugins/ScrollToPlugin.min.js', array( 'jquery' ), EXTRA_VERSION, true );
	// EXTRA
	wp_enqueue_script( 'extra', EXTRA_URI . '/assets/js/lib/extra.js', apply_filters( 'extra_script__dependencies', array(
		'jquery',
		'tweenmax'
	) ), EXTRA_VERSION, true );
	// RESPONSIVE SIZES
	$small_size = apply_filters( 'extra_responsive_small_width_limit', 1200 );
	wp_localize_script( 'extra', 'extra_responsive_small_width_limit', array(
		'value' => $small_size
	) );
	// All sizes
	$sizes = apply_filters( 'extra_responsive_sizes', array() );
	wp_localize_script( 'extra', 'extraResponsiveSizes', $sizes );
}

add_action( 'wp_enqueue_scripts', 'extra_template_enqueue_scripts' );