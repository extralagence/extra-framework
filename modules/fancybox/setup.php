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
add_action( 'wp_enqueue_scripts', function () {
	$extra_enabled_fancybox = apply_filters( 'extra_enabled_fancybox', true );
	if ( ! $extra_enabled_fancybox ) {
		return;
	}

	wp_enqueue_style( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/dist/jquery.fancybox.min.css', array(), EXTRA_VERSION, 'all' );
	wp_enqueue_style( 'extra.fancybox', EXTRA_MODULES_URI . '/fancybox/css/fancybox.less', array('fancybox'), EXTRA_VERSION, 'all' );

	wp_enqueue_script( 'fancybox', EXTRA_MODULES_URI . '/fancybox/inc/dist/jquery.fancybox.js', array( 'jquery' ), EXTRA_VERSION, true );
	wp_enqueue_script( 'extra.fancybox', EXTRA_MODULES_URI . '/fancybox/js/fancybox.js', apply_filters('extra_fancybox_script_dependencies', array( 'fancybox' )), EXTRA_VERSION, true );
	wp_localize_script('extra.fancybox', 'extra_fancybox_options', array(
		'messages' => array(
			'error' => __("Impossible de charger le contenu<br /> Veuillez réessayer ultérieurement.", 'extra'),
			'count' => sprintf(__("%s&nbsp;sur&nbsp;%s", 'extra'), '<span class="js-fancybox-index"></span>', '<span class="js-fancybox-count"></span>'),
		),
	));
}
);

add_action('wp_head', function () {

	$extra_enabled_fancybox = apply_filters( 'extra_enabled_fancybox', true );
	if ( ! $extra_enabled_fancybox ) {
		return;
	}

	$fancybox_title = apply_filters( 'extra_fancybox_title', '' );
	if (empty($fancybox_title)) {
		if (is_single()) {
			global $post;
			$fancybox_title = get_the_title($post);
		} else {
			$fancybox_title = get_bloginfo('name');
		}
	}

	echo '<meta name="extra:fancybox_title" content="' . $fancybox_title . '">';
});