<?php
/**********************
 *
 *
 *
 * EXTRA SCROLL TRIGGER
 *
 *
 *
 *********************/
add_action('wp_enqueue_scripts', function () {
	$extra_enabled_extra_scroll_trigger = apply_filters('extra_enabled_extra_scroll_trigger', false);
	if(!$extra_enabled_extra_scroll_trigger) {
		return;
	}
	wp_enqueue_script('extra.scrolltrigger', EXTRA_MODULES_URI.'/extra.scrolltrigger/inc/js/extra.scrolltrigger.js', array('jquery', 'tweenmax', 'extra'), EXTRA_VERSION, true);
});