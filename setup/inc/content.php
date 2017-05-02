<?php
/**********************
 *
 *
 *
 * NO P AROUND IMAGES
 *
 *
 *
 *********************/
function extra_template_no_tags_around_medias( $content ) {
	$content = preg_replace( '/<p.*>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
	$content = preg_replace( '/<p.*>\s*(<iframe .*>)\s*(<\/iframe>)\s*<\/p>/iU', '\1\2', $content );

	return $content;
}

add_filter( 'the_content', 'extra_template_no_tags_around_medias', 10 );

/**********************
 *
 *
 *
 * NO 10px MARGIN CAPTION
 *
 *
 *
 *********************/
if ( ! function_exists( 'extra_img_caption_shortcode' ) ) {
	function extra_img_caption_shortcode( $x = null, $attr, $content ) {
		extract( shortcode_atts( array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => ''
		), $attr ) );
		if ( 1 > (int) $width || empty( $caption ) ) {
			return $content;
		}
		if ( $id ) {
			$id = 'id="' . $id . '" ';
		}

		return '<figure ' . $id . 'class="wp-caption ' . $align . '">' . do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $caption . '</figcaption></figure>';
	}
}
add_filter( 'img_caption_shortcode', 'extra_img_caption_shortcode', 10, 3 );