<?php
add_filter( "mce_external_plugins", "extra_mce_external_plugins" );
function extra_mce_external_plugins( $plugin_array ) {
	if ( is_admin() ) {
		$extra_typekit_id = apply_filters( 'extra_admin_typekit_id', '' );
		if ( ! empty( $extra_typekit_id ) ) {
			$plugin_array['typekit'] = EXTRA_URI . '/setup/admin/js/typekit.tinymce.js';
		}
	}

	return $plugin_array;
}


add_action( 'before_wp_tiny_mce', 'extra_before_wp_tiny_mce' );
function extra_before_wp_tiny_mce() {
	if ( is_admin() ) {
		$extra_typekit_id = apply_filters( 'extra_admin_typekit_id', '' );
		if ( ! empty( $extra_typekit_id ) ) {
			echo "<script>var extra_admin_typekit_id = '" . $extra_typekit_id . "';</script>";
		}
	}
}