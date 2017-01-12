<?php
/**********************
 *
 *
 *
 * SECOND TITLE METABOX
 *
 *
 *
 *********************/
function extra_redirection__metabox() {
	global $redirection_metabox;
	$redirection_metabox = new ExtraMetaBox( array(
		'id'               => '_redirection',
		'lock'             => WPALCHEMY_LOCK_AFTER_POST_TITLE,
		'title'            => __( "Redirection", "extra" ),
		'types'            => array( 'page' ),
		'include_template' => array( 'template-redirect.php' ),
		'hide_editor'      => true,
		'fields'           => array(
			array(
				'type'      => 'redirection',
				'name'      => 'redirection',
				'css_class' => 'bloc'
			),
		),
	) );
}

add_action( 'init', 'extra_redirection__metabox', 12 );


function extra_get_redirect($page_id) {
	global $redirection_metabox;
	$data = $redirection_metabox->the_meta($page_id);

	$redirection_type = ! empty( $data['redirection_type'] ) ? $data['redirection_type'] : 'auto';

	$response = array(
		'url' => null,
		'error' => false
	);

	switch ( $redirection_type ) {
		case 'auto' :
			$redirect_params = array(
				'child_of'    => $page_id,
				'sort_column' => 'menu_order'
			);
			$redirect_params = apply_filters( 'extra_redirection_get_pages_params', $redirect_params );
			$pagekids        = get_pages( $redirect_params );
			if ( ! empty( $pagekids ) ) {
				$firstchild = $pagekids[0];
				$response['url'] = get_permalink( $firstchild->ID );
			} else {
				$response['error'] = __( "Cette page n'a pas d'enfant", "extra" );
			}
			break;

		case 'manual' :

			if ( ! empty( $data['redirection_manual'] ) ) {
				$response['url'] = $data['redirection_manual'];
			} else {
				$response['error'] = __( "Cette page n'a pas d'url de destination", "extra" );
			}
			break;

		case 'content' :

			if ( ! empty( $data['redirection_content'] ) ) {
				$response['url'] = get_permalink( $data['redirection_content'] );
			} else {
				$response['error'] = __( "Cette page n'a pas d'url de destination", "extra" );
			}
			break;

		case 'post-type' :

			$redirection_value = $data['redirection_post-type'];
			if ( ! empty( $redirection_value ) ) {

				$targets = get_posts( array(
					'post_type'        => $redirection_value,
					'numberposts'      => 1,
					'orderby'          => 'menu_order',
					'order'            => 'ASC',
					'post_status'      => 'publish,private',
					'suppress_filters' => 0
				) );

				if ( count( $targets ) > 0 ) {
					if ( function_exists( 'icl_object_id' ) ) {
						$target_id = icl_object_id( $targets[0]->ID, $redirection_value, true );
					} else {
						$target_id = $targets[0]->ID;
					}

					$response['url'] = get_permalink( $target_id );
				} else {
					$response['error'] = __( "Cette page n'a pas d'url de destination", "extra" );
				}
			} else {
				$response['error'] = __( "Cette page n'a pas d'url de destination", "extra" );
			}
			break;
	}

	return $response;
}

add_action( 'template_redirect', function () {
	if ( is_page_template( 'template-redirect.php' ) ) {
		global $post, $redirection_metabox;
		the_post();
		$redirect = extra_get_redirect ($post->ID);

		if ($redirect['error'] != false) {
			echo $redirect['error'];
		} else {
			wp_redirect($redirect['url']);
		}
		die();
	}
} );


add_filter('wp_nav_menu_objects', function ($items) {
	foreach ($items as $item) {
		if ($item->object == 'page') {
			$slug = get_page_template_slug($item->object_id);
			if ($slug == 'template-redirect.php') {
				$redirect = extra_get_redirect ($item->object_id);
				if ($redirect['error'] == false) {
					$item->url = $redirect['url'];
				}
			}
		}
	}

	return $items;
});