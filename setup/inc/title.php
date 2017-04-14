<?php
/**********************
 *
 *
 *
 * SITE TITLE
 *
 *
 *
 *********************/
if ( ! function_exists( 'extra_wp_title' ) ) {
	function extra_wp_title( $title, $sep ) {
		global $paged, $page, $post;

		$title = '';

		if ( ! is_feed() && ! is_front_page() ) {

			if ( is_singular() ) {
				if ( $post != null ) {
					$title .= $post->post_title . ' ' . $sep . ' ';
				}
			} else {
				if ( is_archive() ) {
					$title .= ' ' . $sep . ' ' . __( "Archive", "extra-admin" );
				}
			}

			// Add a page number if necessary.
			if ( $paged >= 2 || $page >= 2 ) {
				$title .= "$title $sep " . sprintf( __( 'Page %s', 'extra-admin' ), max( $paged, $page ) );
			}
			$title .= get_bloginfo( 'name' );
		} else {
			if ( is_front_page() ) {
				$title = get_bloginfo( 'name' );
			}
		}

		return $title;
	}
}
add_filter( 'wp_title', 'extra_wp_title', 100, 3 );