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
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js', null, EXTRA_VERSION, apply_filters(' ', true));
	// TWEENMAX
	wp_enqueue_script('tweenmax', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js', array('jquery'), EXTRA_VERSION, true);
	// SCROLLTO
	wp_enqueue_script('tweenmax-scrollto', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/plugins/ScrollToPlugin.min.js', array('jquery'), EXTRA_VERSION, true);
	// EXTRA
	wp_enqueue_script('extra', EXTRA_URI . '/assets/js/lib/extra.js', array('jquery', 'tweenmax'), EXTRA_VERSION, true);
	// RESPONSIVE SIZES
	$small_size = apply_filters('extra_responsive_small_width_limit', 1200);
	wp_localize_script('extra', 'extra_responsive_small_width_limit', array(
		'value' => $small_size
	));
	// All sizes
	$sizes = apply_filters('extra_responsive_sizes', array());
	wp_localize_script('extra', 'extraResponsiveSizes', $sizes);
}

add_action('wp_enqueue_scripts', 'extra_template_enqueue_scripts');