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
	wp_enqueue_script( 'jquery', apply_filters( 'extra_script__jquery_url', '//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'), apply_filters( 'extra_script__jquery_dependencies', array() ), null, apply_filters( 'extra_jquery_in_footer', true ) );
	// TWEENMAX
	wp_enqueue_script( 'tweenmax', apply_filters( 'extra_script__tweenmax_url', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js'), false, null, true );
	// SCROLLTO
	wp_enqueue_script( 'tweenmax-scrollto', apply_filters( 'extra_script__scrollto_url', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/plugins/ScrollToPlugin.min.js'), array('tweenmax'), null, true );
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



/**********************
 *
 *
 *
 * TELL TO WP ROCKET TO NOT MINIFY THESES EXTERNAL JS
 *
 *
 *
 *********************/
add_filter('rocket_minify_excluded_external_js', function ($external_js) {
	$external_js[] = 'cdnjs.cloudflare.com';
	$external_js[] = 'ajax.googleapis.com';
	$external_js[] = 'maps.googleapis.com';

	return $external_js;
});