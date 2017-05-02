<?php
/**********************
 *
 *
 *
 * EXTRA RESPONSIVE SIZES
 *
 *
 *
 *********************/
function extra_responsive_images__default_responsive_size_rules( $rules ) {
	return array(
		'desktop' => null,
		'mobile'  => 1200,
	);
}

add_filter( 'extra_responsive_size_rules', 'extra_responsive_images__default_responsive_size_rules', 0 );

// By default the content size match window sizes;
add_filter( 'extra_responsive_content_size_rules', 'extra_responsive_images__default_responsive_size_rules', 0 );

function extra_responsive_images__default_responsive_sizes( $sizes ) {
	$rules = apply_filters( 'extra_responsive_size_rules', array() );

	$sizes = array();
	$rules = array_reverse( $rules );

	$previous_max = null;
	foreach ( $rules as $rule_name => $max_width ) {
		$previous = '';
		if ( $previous_max != null ) {
			$previous = ' and (min-width: ' . ( $previous_max + 1 ) . 'px)';
		}
		$current = '';
		if ( $max_width !== null ) {
			$current = ' and (max-width: ' . $max_width . 'px)';
		}
		$sizes[ $rule_name ] = 'only screen' . $previous . $current;
		$previous_max        = $max_width;
	}

	$sizes = array_reverse( $sizes );

	return $sizes;
}

add_filter( 'extra_responsive_sizes', 'extra_responsive_images__default_responsive_sizes', 0 );


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
	if ( ! $extra_enabled_extra_responsive_images ) {
		return;
	}
	wp_enqueue_style( 'extra-responsiveimages', EXTRA_MODULES_URI . '/extra.responsiveimages/css/extra.responsiveimages.less', null, EXTRA_VERSION, 'all' );

	wp_enqueue_script( 'extra.jfracs', EXTRA_MODULES_URI . '/extra.responsiveimages/js/lib/jquery.fracs.js', array( 'jquery' ), EXTRA_VERSION, true );
	wp_enqueue_script( 'extra.blur', EXTRA_MODULES_URI . '/extra.responsiveimages/js/lib/blur.js', null, EXTRA_VERSION, true );
	wp_enqueue_script( 'extra.responsiveimages', EXTRA_MODULES_URI . '/extra.responsiveimages/js/extra.responsiveimages.js', array(
		'jquery',
		'tweenmax',
		'extra',
		'extra.blur',
		'extra.jfracs'
	), EXTRA_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'extra_responsive_images_init' );
/**********************
 *
 *
 *
 * PHP FUNCTIONS
 *
 *
 *
 *********************/

function extra_get_placeholder( $id, $width, $height ) {

	$use_placeholder = apply_filters( 'extra_responsive_images_use_placeholder', false );
	if ( $use_placeholder ) {
		$placeholder_size = apply_filters( 'extra_responsive_images_placeholder_size', 30 );
		if ( $width >= $height ) {
			$placeholder_size = array( $placeholder_size, round( $placeholder_size * $height / $width ) );
		} else {
			$placeholder_size = array( round( $placeholder_size * $width / $height ), $placeholder_size );
		}

		$placeholder_src = wp_get_attachment_image_src( $id, $placeholder_size );

	} else {
		$placeholder_src    = array();
		$placeholder_src[0] = EXTRA_URI . '/assets/img/blank.png';
	}
	$placeholder_src[1] = $width;
	$placeholder_src[2] = $height;

	return $placeholder_src;
}

/**
 * echo reponsive image
 *
 * @param        $src            $source
 * @param array  $params         $params['desktop'] $params['tablet'] $params['mobile'] required
 * @param string $class          add custom classes
 * @param string $alt
 * @param bool   $img_itemprop   true if you want to use itemprop
 * @param string $caption        html for the caption
 * @param string $tag            used to wrap the image (figure, span, etc.)
 * @param bool   $lazy_loading   true if loading start only when element is in viewport
 * @param bool   $custom_loading true if you want to overide the loading mechanic (lazy or not)
 */
function extra_get_responsive_image( $id = 0, $dimensions = 'thumbnail', $class = '', $alt = null, $img_itemprop = true, $caption = '', $tag = 'figure', $lazy_loading = false, $custom_loading = false ) {

	// hook it to override available sizes
	$sizes = apply_filters( 'extra_responsive_sizes', array() );

	$class .= ( $lazy_loading ) ? ' extra-responsive-image-lazy' : '';
	$class .= ( $custom_loading ) ? ' extra-responsive-image-custom-loading' : '';
	$use_placeholder = apply_filters( 'extra_responsive_images_use_placeholder', false );

// SRC IS AN ID
	if ( empty( $id ) || ! is_numeric( $id ) ) {
		//throw new Exception( __( "This must be an integer", 'extra' ) );
		ob_start();
		?>
		<img
			class="extra-responsive-image-placeholder<?php echo ! empty( $class ) ? ' ' . $class : ''; ?>"
			src="<?php echo EXTRA_URI; ?>/assets/img/blank.png">
		<?php
		$return = ob_get_contents();
		ob_end_clean();

		return $return;
	}
	if ( empty( $alt ) ) {
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		if ( empty( $alt ) ) {
			$attachment = get_post( $id );
			$alt        = $attachment->post_title;
		} else {
			if ( is_array( $alt ) ) {
				$alt = reset( $alt );
			}
		}
		$alt = str_replace( '"', '', $alt );
	}

	// ADJUST DIMENSIONS
	$dimensions = extra_responsive_image__adjust_dimensions( $id, $dimensions );

	// START RENDERING
	ob_start();
	?>

	<<?php echo $tag; ?> class="extra-responsive-image-wrapper<?php echo ( ! empty( $class ) ) ? ' ' . $class : ''; ?><?php echo ( ! empty( $caption ) ) ? ' wp-caption' : ''; ?>"<?php echo ( $img_itemprop ) ? ' itemprop="image" itemscope itemtype="http://schema.org/ImageObject"' : ''; ?>>
	<?php if ( $img_itemprop ) :
		$dimension = is_array( $dimensions ) ? reset( $dimensions ) : $dimensions;
		$src    = wp_get_attachment_image_src( $id, $dimension );
		?>
		<meta itemprop="url" content="<?php echo $src[0]; ?>">
		<meta itemprop="width" content="<?php echo $src[1]; ?>">
		<meta itemprop="height" content="<?php echo $src[2]; ?>">
	<?php endif; ?>
	<noscript
		data-alt="<?php echo $alt; ?>"
		<?php foreach ( $sizes as $size => $value ): ?>
			data-src-<?php echo $size; ?>="<?php
			$src = wp_get_attachment_image_src( $id, is_array( $dimensions ) ? $dimensions[ $size ] : $dimensions );
			echo $src[0];
			?>"
		<?php endforeach; ?>>

		<img alt="<?php echo $alt; ?>" src="<?php
		$dimension = is_array( $dimensions ) ? reset( $dimensions ) : $dimensions;
		$src       = wp_get_attachment_image_src( $id, $dimension );
		echo $src[0];
		?>"
			 width="<?php echo $src[1]; ?>"
			 height="<?php echo $src[2]; ?>">
	</noscript>
	<?php $placeholder_src = extra_get_placeholder( $id, $src[1], $src[2] ); ?>
	<img class="extra-responsive-image-placeholder-thumb"
		 src="<?php echo $placeholder_src[0]; ?>"
		 alt=""
		 width="<?php echo ( ! empty( $placeholder_src[1] ) ) ? $placeholder_src[1] : ''; ?>"
		 height="<?php echo ( ! empty( $placeholder_src[2] ) ) ? $placeholder_src[2] : ''; ?>"
		 style="height: <?php echo ( ! empty( $placeholder_src[2] ) ) ? $placeholder_src[2] : ''; ?>px;"
	/>
	<?php if ( $use_placeholder ) : ?>
		<canvas class="extra-responsive-image-placeholder-canvas"></canvas>
	<?php endif; ?>
	<?php if ( ! empty( $caption ) ) : ?>
		<figcaption class="wp-caption-text">
			<?php echo $caption; ?>
		</figcaption>
	<?php endif; ?>
	<?php do_action('extra_responsive_image_content_after'); ?>
	</<?php echo $tag; ?>>

	<?php
	$return = ob_get_contents();
	ob_end_clean();

	return $return;
}

function extra_responsive_image( $id = 0, $dimensions = 'thumbnail', $class = '', $alt = null, $img_itemprop = true, $caption = '', $tag = 'figure', $lazy_loading = false, $custom_loading = false ) {
	echo extra_get_responsive_image( $id, $dimensions, $class, $alt, $img_itemprop, $caption, $tag, $lazy_loading, $custom_loading );
}

/**
 * echo reponsive image
 *
 * @param        $src            $source
 * @param array  $params         $params['desktop'] $params['tablet'] $params['mobile'] required
 * @param string $class          add custom classes
 * @param string $alt
 * @param string $tag            used to be carry the background image
 * @param bool   $lazy_loading   true if loading start only when element is in viewport
 * @param bool   $custom_loading true if you want to overide the loading mechanic (lazy or not)
 */
function extra_get_responsive_background_image( $id = 0, $dimensions = 'thumbnail', $class = '', $tag = 'div', $lazy_loading = false, $custom_loading = false ) {

	// hook it to override available sizes
	$sizes = apply_filters( 'extra_responsive_sizes', array() );

	$class .= ( $lazy_loading ) ? ' extra-responsive-image-lazy' : '';
	$class .= ( $custom_loading ) ? ' extra-responsive-custom-loading' : '';

	// SRC IS AN ID
	if ( ! is_numeric( $id ) ) {
		throw new Exception( __( "This must be an integer", 'extra' ) );
	}

	// ADJUST DIMENSIONS
	$dimensions = extra_responsive_image__adjust_dimensions( $id, $dimensions );

	// START RENDERING
	ob_start();

	?>

	<<?php echo $tag; ?> class="extra-responsive-image-wrapper extra-responsive-image-background<?php echo ( ! empty( $class ) ) ? ' ' . $class : ''; ?>"
	style="background-image: url('<?php echo EXTRA_URI . '/assets/img/blank.png'; ?>');">
	<noscript
		<?php foreach ( $sizes as $size => $value ): ?>
			data-src-<?php echo $size; ?>="<?php
			$src = wp_get_attachment_image_src( $id, is_array( $dimensions ) ? $dimensions[ $size ] : $dimensions );
			echo $src[0]; ?>"
		<?php endforeach; ?>>
	</noscript>
	</<?php echo $tag; ?>>
	<?php $return = ob_get_contents(); ?>

	<?php
	ob_end_clean();

	return $return;
}

function extra_responsive_background_image( $id = 0, $dimensions = 'thumbnail', $class = '', $tag = 'div', $lazy_loading = false, $custom_loading = false ) {
	echo extra_get_responsive_background_image( $id, $dimensions, $class, $tag, $lazy_loading, $custom_loading );
}

/**
 * get svg responsive image
 *
 * @param        $src    $source
 * @param array  $params $params['desktop'] $params['tablet'] $params['mobile'] required
 * @param string $class  add custom classes
 * @param string $alt
 */
function extra_get_responsive_svg_image( $id = 0, $dimensions = 'thumbnail', $class = '', $lazy_loading = false, $custom_loading = false ) {

	// hook it to override available sizes
	$sizes = apply_filters( 'extra_responsive_sizes', array() );

	$class .= ( $lazy_loading ) ? ' extra-responsive-image-lazy' : '';
	$class .= ( $custom_loading ) ? ' extra-responsive-image-custom-loading' : '';

	// SRC IS AN ID
	if ( ! is_numeric( $id ) ) {
		throw new Exception( __( "This must be an integer", 'extra' ) );
	}

	$dimensions = extra_responsive_image__adjust_dimensions( $id, $dimensions );

	// START RENDERING
	ob_start();
	?>

	<div
		class="extra-responsive-image-wrapper extra-responsive-image-svg<?php echo ( ! empty( $class ) ) ? ' ' . $class : ''; ?>">
		<svg width="100%" height="100%"
			 preserveAspectRatio="none"
			 version="1.1"
			 xmlns="http://www.w3.org/2000/svg"
			 xmlns:xlink="http://www.w3.org/1999/xlink">
			<image width="100%" height="100%" preserveAspectRatio="xMidYMid slice"
				   xlink:href="<?php echo EXTRA_URI ?>/assets/img/blank.png"></image>
		</svg>
		<noscript
			<?php foreach ( $sizes as $size => $value ): ?>
				data-src-<?php echo $size; ?>="<?php
				$src = wp_get_attachment_image_src( $id, is_array( $dimensions ) ? $dimensions[ $size ] : $dimensions );
				echo $src[0]; ?>"
			<?php endforeach; ?>>
		</noscript>
	</div>
	<?php $return = ob_get_contents(); ?>

	<?php
	ob_end_clean();

	return $return;
}

function extra_responsive_svg_image( $id = 0, $dimensions = 'thumbnail', $class = '', $lazy_loading = false, $custom_loading = false ) {
	echo extra_get_responsive_svg_image( $id, $dimensions, $class, $lazy_loading, $custom_loading );
}


///////////////////////////////////////
//
//
// REPLACE IMG CONTENT WITH RESPONSIVE IMG
//
//
///////////////////////////////////////
function extra_responsive_images__the_content_replace( $matches, $tag ) {

	$alt = '';

	$img = $matches[4];

	// Extract ID
	$current_matches = array();
	preg_match( '/wp-image-([0-9]*)/', $img, $id_matches );
	$id = $id_matches[1];

	// Extract Height
	$current_matches = array();
	preg_match( '/height="([0-9]*)"/', $img, $current_matches );
	$height = intval( $current_matches[1] );

	// Extract Width
	$current_matches = array();
	preg_match( '/width="([0-9]*)"/', $img, $current_matches );
	$width = intval( $current_matches[1] );

	// Extract Alt
	$current_matches = array();
	preg_match( '/alt="(.*?)"/', $img, $current_matches );
	if ( ! empty( $current_matches[1] ) ) {
		$alt = $current_matches[1];
	}

	// Extract Class
	$current_matches = array();
	preg_match( '/class="(.*?)"/', $img, $current_matches );
	$class = $current_matches[1];

	// GET SPECIFIC CLASSES
	preg_match( '/(align[a-zA-Z]*)/', $class, $align_class );
	preg_match( '/(size\-[a-zA-Z]*)/', $class, $size_class );

	$responsive_sizes = array();
	$rules            = apply_filters( 'extra_responsive_content_size_rules', array() );

	foreach ( $rules as $rule_name => $max_width ) {
		if ( $max_width !== null ) {
			$current_width = min( $max_width, $width );
		} else {
			$current_width = $width;
		}
		$current_height                 = floor( $height * $current_width / $width );
		$responsive_sizes[ $rule_name ] = array( $current_width, $current_height );
	}

	$responsive_sizes = apply_filters( 'extra_responsive_images_sizes', $responsive_sizes, $width, $height );
	$lazy_loading     = apply_filters( 'extra_responsive_content_lazy_loading', true );

	$html = "";


	// IF IS WRAP WITH LINK
	if ( ! empty( $matches[1] ) || ! empty( $matches[2] ) || ! empty( $matches[3] ) ) {
		$html .= '<a ' . $matches[1] . 'class="link-image ';
		if ( ! empty( $align_class ) ) {
			$html .= 'link-' . $align_class[1] . ' ';
		}
		if ( ! empty( $size_class ) ) {
			$html .= 'link-' . $size_class[1] . ' ';
		}
		$html .= $matches[2] . '"' . $matches[3] . '>';

		$html .= apply_filters( 'extra_responsive_images__the_content_replace__before_link_image', '' );
	}

	// RESPONSIVE IMAGE
	$html .= extra_get_responsive_image(
		$id,
		$responsive_sizes,
		$class,
		$alt,
		'',
		'',
		$tag,
		$lazy_loading
	);

	// IF IS WRAP WITH LINK
	if ( ! empty( $matches[1] ) || ! empty( $matches[2] ) || ! empty( $matches[3] ) ) {
		$html .= apply_filters( 'extra_responsive_images__the_content_replace__after_link_image', '' );
		$html .= '</a>';
	}

	return $html;
}

function extra_responsive_images__the_content_replace_with_span( $matches ) {
	return extra_responsive_images__the_content_replace( $matches, 'span' );
}

function extra_responsive_images__the_content( $content ) {
//	return $content;

	$content = preg_replace_callback(
		'/(?><a(.*?)?(?:class="(.*?)")?(.*?)?>)?(<img.*?class=".*?(?:wp-image).*?".*?>)(?><\/a>)?/',
		'extra_responsive_images__the_content_replace_with_span',
		$content );

	return $content;
}

add_filter( 'the_content', 'extra_responsive_images__the_content', 99 );


// DISABLED SRCSET FOR IMG
function extra_responsive_images__wp_calculate_image_srcset( $sources ) {
	return false;
}

add_filter( 'wp_calculate_image_srcset', 'extra_responsive_images__wp_calculate_image_srcset' );


///////////////////////////////////////
//
//
// ADJUST DIMENSIONS
//
//
///////////////////////////////////////
function extra_responsive_image__adjust_dimensions( $attachment_id, $dimensions ) {

	// Assuming we have key values of sizes (desktop, tablet, ...)
	if ( is_array( $dimensions ) ) {

		if ( ! empty( $dimensions[0] ) && is_int( $dimensions[0] ) ) {
			$width      = $dimensions[0];
			$height     = ! empty( $dimensions[1] ) ? $dimensions[1] : 0;
			$sizes      = apply_filters( 'extra_responsive_sizes', array() );
			$dimensions = array();
			foreach ( $sizes as $size_name => $size_value ) {
				$dimensions[ $size_name ] = array( $width, $height );
			}
		}

		// Get full size
		$image_full_src = wp_get_attachment_image_src( $attachment_id, 'full' );

		if ( ! $image_full_src ) {
			return $dimensions;
		}

		$filetype = wp_check_filetype( $image_full_src[0] );
		if ( ! empty( $filetype ) && $filetype['ext'] === 'svg' ) {
			return $dimensions;
		}

		$full_dimension = array( $image_full_src[1], $image_full_src[2] );

		// Real dimensions returned
		$real_dimensions = array();

		// Loop thgrough screen dimensions
		foreach ( $dimensions as $dimension_name => $dimension ) {

			// Dimensions is array of int [width, height]
			if ( is_array( $dimension ) ) {

				// We need a width
				if ( empty( $dimension[0] ) ) {
					wp_die( "Image Responsive error" );
				}

				// If desired width > max width available
				if ( $dimension[0] > $full_dimension[0] ) {

					// We have height, adjust it
					if ( ! empty( $dimension[1] ) ) {
						$dimension[1] = ( $full_dimension[0] * $dimension[1] ) / $dimension[0];
					}

					// Set width
					$dimension[0] = $full_dimension[0];
				}

				// If desired height > max height available
				if ( ! empty( $dimension[1] ) && $dimension[1] > $full_dimension[1] ) {

					// Adjust width
					$dimension[0] = ( $full_dimension[1] * $dimension[0] ) / $dimension[1];

					// Set height
					$dimension[1] = $full_dimension[1];
				}


				if ( empty( $dimension[1] ) ) {
					$dimension[1] = min( floor( ( $dimension[0] * $full_dimension[1] ) / $full_dimension[0] ), $full_dimension[1] );
				}

				$real_dimensions[ $dimension_name ] = $dimension;
			}
		}

		$dimensions = $real_dimensions;
	}

	return $dimensions;
}