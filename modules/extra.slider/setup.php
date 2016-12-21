<?php
/**********************
 *
 *
 *
 * EXTRA SLIDER
 *
 *
 *
 *********************/
function extra_slider_init() {
	$extra_enabled_extra_slider = apply_filters( 'extra_enabled_extra_slider', true );
	if ( ! $extra_enabled_extra_slider ) {
		return;
	}
	// DRAGGABLE
	wp_enqueue_script( 'draggable', apply_filters( 'extra_script__draggable_url', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/utils/Draggable.min.js'), array(
		'jquery',
		'tweenmax'
	), EXTRA_VERSION, true );
	// EXTRA SLIDER
	wp_enqueue_script( 'extra.slider', EXTRA_MODULES_URI . '/extra.slider/inc/js/extra.slider.js', array(
		'tweenmax',
		'draggable',
		'extra.responsiveimages'
	), EXTRA_VERSION, true );
	wp_enqueue_script( 'extra.slider.responsiveimages', EXTRA_MODULES_URI . '/extra.slider/front/js/extra.slider.responsiveimages.js', array(
		'tweenmax',
		'draggable',
		'extra.responsiveimages'
	), EXTRA_VERSION, true );
	wp_enqueue_style( 'extra.slider', EXTRA_MODULES_URI . '/extra.slider/inc/css/extra.slider.css', array(), EXTRA_VERSION, 'all' );
	// EXTRA GALLERY
	wp_enqueue_style( 'extra-gallery', EXTRA_URI . '/assets/css/extra.gallery.less', array(), EXTRA_VERSION, 'all' );
	wp_enqueue_script( 'extra-gallery', EXTRA_URI . '/assets/js/lib/extra.gallery.js', array( 'extra.slider' ), EXTRA_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'extra_slider_init' );