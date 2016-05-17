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
	wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js', null, null, false);
	// TWEENMAX
	wp_enqueue_script('tweenmax', EXTRA_URI . '/assets/js/lib/TweenMax.min.js', array('jquery'), null, true);
	// SCROLLTO
	wp_enqueue_script('tweenmax-scrollto', EXTRA_URI . '/assets/js/lib/ScrollToPlugin.min.js', array('jquery'), null, true);
	// DAGGABLE
	wp_enqueue_script('draggable', EXTRA_URI . '/assets/js/lib/Draggable.min.js', array('jquery', 'tweenmax'), null, true);
	// THROWPROPS
	wp_enqueue_script('throwprops', EXTRA_URI . '/assets/js/lib/ThrowPropsPlugin.min.js', array('jquery', 'tweenmax'), null, true);
	// EXTRA
	wp_enqueue_script('extra', EXTRA_URI . '/assets/js/lib/extra.js', array('jquery', 'tweenmax'), null, true);
	// RESPONSIVE SIZES
	$sizes = apply_filters('extra_responsive_sizes', array(
        'desktop' => 'only screen and (min-width: 961px)',
        'tablet' => 'only screen and (min-width: 691px) and (max-width: 960px)',
        'mobile' => 'only screen and (max-width: 690px)'
	));
	wp_localize_script('extra', 'extraResponsiveSizes', $sizes);
}

add_action('wp_enqueue_scripts', 'extra_template_enqueue_scripts');