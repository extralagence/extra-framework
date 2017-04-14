<?php
///////////////////////////////////////
//
//
// Redux set default image
//
//
///////////////////////////////////////
if ( ! function_exists( 'extra_default_image_thumbnail_options' ) ) {
	function extra_default_image_thumbnails_options( $sections ) {
		$sections[] = array(
			'icon'   => 'el-icon-picture',
			'title'  => __( 'Images par défaut', 'extra-admin' ),
			'desc'   => null,
			'fields' => array(
				array(
					'id'    => 'default-thumbnail',
					'type'  => 'media',
					'title' => __( 'Image générique par défaut', 'extra-admin' ),
				),
				array(
					'id'    => 'default-thumbnail-small',
					'type'  => 'media',
					'title' => __( 'Image générique par défaut (petite taille)', 'extra-admin' ),
				)
			)
		);

		return $sections;
	}
}
add_filter( 'extra_default_global_options_section', 'extra_default_image_thumbnails_options' );
///////////////////////////////////////
//
//
// Get default image
//
//
///////////////////////////////////////
/**
 * @param $param 'default-thumbnail' || 'default-thumbnail-small'
 *
 * @return image id
 */
function extra_get_default_image_id( $param = 'default-thumbnail' ) {
	global $extra_options;
	$default_image    = ( isset( $extra_options[ $param ] ) ) ? $extra_options[ $param ] : null;
	$default_image_id = ( isset( $default_image['id'] ) ) ? $default_image['id'] : null;

	return $default_image_id;
}