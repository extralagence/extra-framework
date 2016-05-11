<?php
/**********************
 *
 *
 *
 * EXTRA RESPONSIVE IMAGES
 *
 *
 *
 *********************/
function extra_responsive_images_init() {
	$extra_enabled_extra_responsive_images = apply_filters( 'extra_enabled_extra_responsive_images', true );
	if ( !$extra_enabled_extra_responsive_images ) {
		return;
	}
	wp_enqueue_script( 'extra.responsiveimages', EXTRA_MODULES_URI . '/extra.responsiveimages/js/extra.responsiveimages.js', array( 'jquery', 'tweenmax', 'extra' ), null, true );
}

add_action( 'init', 'extra_responsive_images_init' );
/**********************
 *
 *
 *
 * PHP FUNCTIONS
 *
 *
 *
 *********************/
/**
 * echo reponsive image
 *
 * @param        $src    $source
 * @param array  $params $params['desktop'] $params['tablet'] $params['mobile'] required
 * @param string $class  add custom classes
 * @param string $alt
 */
function extra_get_responsive_image( $id = 0, $dimensions = 'thumbnail', $class = '', $alt = null, $img_itemprop = '', $caption = '' ) {

// hook it to override available sizes
	$sizes = apply_filters( 'extra_responsive_sizes', array(
		'desktop' => 'only screen and (min-width: 961px)',
		'tablet'  => 'only screen and (min-width: 691px) and (max-width: 960px)',
		'mobile'  => 'only screen and (max-width: 690px)'
	) );

// SRC IS AN ID
	if ( empty( $id ) || !is_numeric( $id ) ) {
		//throw new Exception( __( "This must be an integer", 'extra' ) );
		ob_start();
		?>
		<img
			class="placeholder-image<?php echo !empty( $class ) ? ' ' . $class : ''; ?>"
			src="<?php echo EXTRA_URI; ?>/assets/img/blank.png">
		<?php
		$return = ob_get_contents();
		ob_end_clean();

		return $return;
	}
	if ( !empty( $alt ) ) {
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		if ( empty( $alt ) ) {
			$attachment = get_post( $id );
			$alt        = $attachment->post_title;
		}
	}


	if ( is_array( $dimensions ) ) {
		$image_full_src = null;

		$real_dimensions = array();
		foreach ( $dimensions as $dimension_name => $dimension ) {
// IF ONE DIMENSION IS NULL, CALCULATE IT FROM FULL DIMENSION RATIO
			if ( $dimension[0] === null && $dimension[1] !== null ) {
				if ( $image_full_src == null ) {
					$image_full_src = wp_get_attachment_image_src( $id, 'full' );
				}
				if ( !empty( $image_full_src ) ) {
					$dimension[0] = min( floor( $dimension[1] * $image_full_src[1] / $image_full_src[2] ), $image_full_src[1] );
				}
			} else {
				if ( $dimension[1] === null && $dimension[0] !== null ) {
					if ( $image_full_src == null ) {
						$image_full_src = wp_get_attachment_image_src( $id, 'full' );
					}
					if ( !empty( $image_full_src ) ) {
						$dimension[1] = min( floor( $dimension[0] * $image_full_src[2] / $image_full_src[1] ), $image_full_src[2] );
					}
				}
			}
			$real_dimensions[$dimension_name] = $dimension;
		}
		$dimensions = $real_dimensions;
	}

// START RENDERING
	ob_start();

	?>

	<figure class="responsiveImagePlaceholder<?php echo ( !empty( $class ) ) ? ' ' . $class : ''; ?><?php echo ( !empty( $caption ) ) ? ' wp-caption' : ''; ?>">
		<noscript
			<?php echo ( $img_itemprop ) ? 'data-img-itemprop="' . $img_itemprop . '"' : ''; ?>
			data-alt="<?php echo $alt; ?>"
			<?php foreach ( $sizes as $size => $value ): ?>
				data-src-<?php echo $size; ?>="<?php
				$dimension = $dimensions[$size];
				$src       = wp_get_attachment_image_src( $id, $dimension );
				echo $src[0];
				?>"
			<?php endforeach; ?>>

			<img alt="<?php echo $alt; ?>"
				<?php echo ( $img_itemprop ) ? 'itemprop="' . $img_itemprop . '"' : ''; ?>
				 src="<?php
				 $dimension = reset( $dimensions );
				 $src       = wp_get_attachment_image_src( $id, $dimension );
				 echo $src[0];
				 ?>">
		</noscript>
		<img class="placeholder-image"
			 src="<?php echo EXTRA_URI ?>/assets/img/blank.png"
			 alt=""
			 style="<?php
			 $first_dimension = reset( $dimensions );
			 echo ( !empty( $first_dimension[0] ) ) ? 'width: ' . $first_dimension[0] . 'px;' : '';
			 echo ( !empty( $first_dimension[1] ) ) ? ' height: ' . $first_dimension[1] . 'px;' : ''; ?>" />
		<?php if ( !empty( $caption ) ) : ?>
			<figcaption class="wp-caption-text">
				<?php echo $caption; ?>
			</figcaption>
		<?php endif; ?>
	</figure>

	<?php
	$return = ob_get_contents();
	ob_end_clean();

	return $return;
}

function extra_responsive_image( $id = 0, $dimensions = 'thumbnail', $class = '', $alt = null, $img_itemprop = '', $caption = '' ) {
	echo extra_get_responsive_image( $id, $dimensions, $class, $alt, $img_itemprop, $caption );
}

/**
 * echo reponsive image
 *
 * @param        $src    $source
 * @param array  $params $params['desktop'] $params['tablet'] $params['mobile'] required
 * @param string $class  add custom classes
 * @param string $alt
 */
function extra_get_responsive_background_image( $id = 0, $dimensions = 'thumbnail', $class = '', $tag = 'div') {

	// hook it to override available sizes
	$sizes = apply_filters( 'extra_responsive_sizes', array(
		'desktop' => 'only screen and (min-width: 961px)',
		'tablet'  => 'only screen and (min-width: 691px) and (max-width: 960px)',
		'mobile'  => 'only screen and (max-width: 690px)'
	) );

	// SRC IS AN ID
	if ( !is_numeric( $id ) ) {
		throw new Exception( __( "This must be an integer", 'extra' ) );
	}
	// START RENDERING
	ob_start();
	?>

	<<?php echo $tag; ?> class="responsiveImagePlaceholder responsiveBackgroundImagePlaceholder<?php echo ( !empty( $class ) ) ? ' ' . $class : ''; ?>"
		 style="background-image: url('<?php echo EXTRA_URI ?>/assets/img/blank.png');">
		<noscript
			<?php foreach ( $sizes as $size => $value ): ?>
				data-src-<?php echo $size; ?>="<?php
				$src = wp_get_attachment_image_src( $id, $dimensions[$size] );
				echo $src[0]; ?>"
			<?php endforeach; ?>>
		</noscript>
	</<?php echo $tag; ?>>
	<?php $return = ob_get_contents(); ?>

	<?php
	ob_end_clean();

	return $return;
}

function extra_responsive_background_image( $id = 0, $dimensions = 'thumbnail', $class = '', $tag = 'div' ) {
	echo extra_get_responsive_background_image( $id, $dimensions, $class, $tag );
}

/**
 * get svg responsive image
 *
 * @param        $src    $source
 * @param array  $params $params['desktop'] $params['tablet'] $params['mobile'] required
 * @param string $class  add custom classes
 * @param string $alt
 */
function extra_get_responsive_svg_image( $id = 0, $dimensions = 'thumbnail', $class = '' ) {

	// hook it to override available sizes
	$sizes = apply_filters( 'extra_responsive_sizes', array(
		'desktop' => 'only screen and (min-width: 961px)',
		'tablet'  => 'only screen and (min-width: 691px) and (max-width: 960px)',
		'mobile'  => 'only screen and (max-width: 690px)'
	) );

	// SRC IS AN ID
	if ( !is_numeric( $id ) ) {
		throw new Exception( __( "This must be an integer", 'extra' ) );
	}
	// START RENDERING
	ob_start();
	?>

	<div class="responsiveImagePlaceholder responsiveSvgImagePlaceholder<?php echo ( !empty( $class ) ) ? ' ' . $class : ''; ?>">
		<svg width="100%" height="100%"
			 preserveAspectRatio="none"
			 xmlns="http://www.w3.org/2000/svg"
			 xmlns:xlink="http://www.w3.org/1999/xlink">
			<image width="100%" height="100%" preserveAspectRatio="xMidYMid slice" xlink:href="<?php echo EXTRA_URI ?>/assets/img/blank.png"></image>
		</svg>
		<noscript
			<?php foreach ( $sizes as $size => $value ): ?>
				data-src-<?php echo $size; ?>="<?php
				$src = wp_get_attachment_image_src( $id, $dimensions[$size] );
				echo $src[0]; ?>"
			<?php endforeach; ?>>
		</noscript>
	</div>
	<?php $return = ob_get_contents(); ?>

	<?php
	ob_end_clean();

	return $return;
}

function extra_responsive_svg_image( $id = 0, $dimensions = 'thumbnail', $class = '' ) {
	echo extra_get_responsive_svg_image( $id, $dimensions, $class );
}