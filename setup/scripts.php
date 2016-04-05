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
	// WEB FONT LOADER
//	wp_enqueue_script('webfontloader', EXTRA_URI.'/assets/js/lib/webfontloader.js', null, false, false);
	// REPLACE JQUERY
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'http://code.jquery.com/jquery-2.2.0.min.js', null, null, false);
	// TWEENMAX
	wp_enqueue_script('tweenmax', EXTRA_URI . '/assets/js/lib/TweenMax.min.js', array('jquery'), null, true);
	// SCROLLTO
	wp_enqueue_script('tweenmax-scrollto', EXTRA_URI . '/assets/js/lib/ScrollToPlugin.min.js', array('jquery'), null, true);
	// JQUERY GSAP
	wp_enqueue_script('jquery-gsap', EXTRA_URI . '/assets/js/lib/jquery.gsap.min.js', array('jquery'), null, true);
	// DAGGABLE
	wp_enqueue_script('draggable', EXTRA_URI . '/assets/js/lib/Draggable.min.js', array('jquery', 'tweenmax'), null, true);
	// THROWPROPS
	wp_enqueue_script('throwprops', EXTRA_URI . '/assets/js/lib/ThrowPropsPlugin.min.js', array('jquery', 'tweenmax'), null, true);
	// MOUSEWHEEL
	wp_enqueue_script('mousewheel', EXTRA_URI . '/assets/js/lib/jquery.mousewheel.pack.js', array('jquery'), null, true);
	// FANCYBOX
	wp_enqueue_script('fancybox', EXTRA_URI . '/assets/js/lib/jquery.fancybox.js', array('jquery'), null, true);
	// EXTRA
	wp_enqueue_script('extra', EXTRA_URI . '/assets/js/lib/extra.js', array('jquery', 'tweenmax', 'fancybox'), null, true);
	// RESPONSIVE SIZES
	$sizes = apply_filters('extra_responsive_sizes', array(
        'desktop' => 'only screen and (min-width: 961px)',
        'tablet' => 'only screen and (min-width: 691px) and (max-width: 960px)',
        'mobile' => 'only screen and (max-width: 690px)'
	));
	wp_localize_script('extra', 'extraResponsiveSizes', $sizes);
	// EXTRA
	wp_enqueue_script('extra-gallery', EXTRA_URI . '/assets/js/lib/extra.gallery.js', array('jquery', 'extra-slider', 'tweenmax'), null, true);
	// TO COPY IN THEME scripts.php IF NEEDED
	//wp_enqueue_script('jquery', EXTRA_URI . '/assets/js/lib/fix_web_jquery.min.js', null, null, true);
	// EXTRA FADE FROM BOTTOM
	//wp_enqueue_script('extra-scroll-animator', EXTRA_URI . '/assets/js/lib/extra.scrollanimator.js', array('jquery', 'tweenmax', 'extra'), null, true);
	// EXTRA FADE FROM BOTTOM
	//wp_enqueue_script('extra-smooth-fit', EXTRA_URI . '/assets/js/lib/extra.smoothfit.js', array('jquery', 'tweenmax', 'extra'), null, true);
	// EXTRA-SLIDER
	//wp_enqueue_script('extra-tooltip', EXTRA_URI . '/assets/js/lib/extra.tooltip.js', array('jquery', 'extra', 'tweenmax'), null, true);
	// FANCY SELECT
	//wp_enqueue_script('fancyselect', EXTRA_URI . '/assets/js/lib/fancyselect.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'extra_template_enqueue_scripts');