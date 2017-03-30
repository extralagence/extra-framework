<?php
/**********************
 *
 *
 *
 * FANCYBOX
 *
 *
 *
 *********************/
function extra_fancybox_init() {
	global $post;
	$extra_enabled_fancybox = apply_filters( 'extra_enabled_fancybox', true );
	if ( ! $extra_enabled_fancybox ) {
		return;
	}

	wp_enqueue_style( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/dist/jquery.fancybox.min.css', array(), EXTRA_VERSION, 'all' );
	wp_enqueue_style( 'extra.fancybox', EXTRA_MODULES_URI . '/fancybox/css/fancybox.less', array('fancybox'), EXTRA_VERSION, 'all' );

	wp_enqueue_script( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/dist/jquery.fancybox.min.js', array( 'jquery' ), EXTRA_VERSION, true );
	wp_enqueue_script( 'extra.fancybox', EXTRA_MODULES_URI . '/fancybox/js/fancybox.js', array( 'fancybox' ), EXTRA_VERSION, true );
	wp_localize_script('extra.fancybox', 'extra_fancybox_options', array(
		'messages' => array(
			'error' => __("Impossible de charger le contenu<br /> Veuillez réessayer ultérieurement.", 'extra'),
			'count' => sprintf(__("%s&nbsp;sur&nbsp;%s", 'extra'), '<span class="js-fancybox-index"></span>', '<span class="js-fancybox-count"></span>'),
			'title' => (!empty($post)) ? get_the_title($post) : '',
		),
	));
}

add_action( 'wp_enqueue_scripts', 'extra_fancybox_init' );