<?php
/**********************
 *
 *
 *
 * BODY CLASSES
 *
 *
 *
 *********************/
function extra_body_class($classes) {
	if(is_page()) {
		global $post;
		$classes[] = 'page-'.$post->post_name;
	}
	return $classes;
}
add_filter('body_class', 'extra_body_class');
/**********************
 *
 *
 *
 * CONTACT FORM 7
 *
 *
 *
 *********************/
function extra_wpcf7_ajax_loader () {
	return THEME_URI . '/assets/img/loading.gif';
}
add_filter('wpcf7_ajax_loader', 'extra_wpcf7_ajax_loader');
/**********************
 *
 *
 *
 * CUSTOM SEARCH
 *
 *
 *
 *********************/
if(!function_exists('extra_search_form')) {
    function extra_search_form($form) {
    	$form = '
    	<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    		<label for="s">'.__("Une recherche ?", "extra").'</label>
    		<input type="text" value="'.get_search_query().'" name="s" id="s" />
    		<button type="submit" id="searchsubmit"><span class="icon icon-search"></span><span class="text">'.__('Valider', 'extra').'</span></button>
    	</form>
    	';
    	return $form;
    }
}
add_filter( 'get_search_form', 'extra_search_form' );
/**********************
 *
 *
 *
 * EXCERPT
 *
 *
 *
 *********************/
// LENGTH
function extra_excerpt_length($length) {
	return 35;
}
add_filter('excerpt_length', 'extra_excerpt_length', 999);
// TEXT
function extra_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'extra_excerpt_more');
/**********************
 *
 *
 *
 * NO 10px MARGIN CAPTION
 *
 *
 *
 *********************/
function extra_img_caption_shortcode($x = null, $attr, $content){
	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));
	if ( 1 > (int) $width || empty($caption) )
		return $content;
	if ( $id ) $id = 'id="' . $id . '" ';
	return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . (0 + (int) $width) . 'px">' . do_shortcode( $content ) . '<div class="wp-caption-text">' . $caption . '</div></div>';
}
add_filter('img_caption_shortcode', 'extra_img_caption_shortcode', 10, 3);
/**********************
 *
 *
 *
 * DEFINE LANGUAGE CONSTANTS
 *
 *
 *
 *********************/
define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
define('ICL_DONT_LOAD_LANGUAGES_JS', true);
define('ICL_DONT_PROMOTE', true);
/**********************
 *
 *
 *
 * LANGUAGE SWITCHER
 *
 *
 *
 *********************/
function extra_language_switcher(){
	if(function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=0&orderby=KEY');
		if(1 < count($languages)){
			echo '<ul id="language-switcher">';
			foreach($languages as $l){
				if($l['active']) {
				    echo '<li><span class="'.$l['language_code'].' active">'.$l['language_code'].'</span></li>';
			    } else {
                    echo '<li><a class="'.$l['language_code'].'" href="'.$l['url'].'">'.$l['language_code'].'</a></li>';
                }
			}
			echo '</ul>';
		}
	}
}
///**********************
// *
// *
// *
// * HOOK MENU CLASSES
// *
// *
// *
// *********************/
//function extra_nav_menu_css_class_home($classes, $item){
//
//	global $extra_options;
//
//	if(get_option("page_on_front") == $item->object_id) {
//		$classes[] = "menu-item-home";
//	}
//
//	/*if((is_singular("post") || is_singular("event")) && $extra_options["page-agenda"] == $item->object_id) {
//		$classes[] = "current-page-ancestor current-menu-ancestor current_page_ancestor";
//	}*/
//
//	return $classes;
//}
//add_filter('nav_menu_css_class' , 'extra_nav_menu_css_class_home' , 10 , 2);
//
///**********************
// *
// *
// *
// * HOOK MENU TITLES
// *
// *
// *
// *********************/
//function extra_hook_nav_menu_footer ($items, $menu, $args) {
////	global $extra_options;
//
//	foreach ($items as $item) {
//		if ($item->object_id === get_option('page_on_front')) {
//			$item->title = '<span class="icon icon-home"></span><span class="text">'.$item->title.'</span>';
//		}
//	}
//	return $items;
//}
//add_filter('wp_get_nav_menu_items','extra_hook_nav_menu_footer', 10, 3);

function remove_parent_classes($class)
{
	// check for current page classes, return false if they exist.
	return ($class == 'current_page_item' || $class == 'current_page_parent' || $class == 'current_page_ancestor'  || $class == 'current-menu-item') ? FALSE : TRUE;
}
/**********************
 *
 *
 *
 * CONTACT FORM / CUSTOM SUBMIT
 *
 *
 *
 *********************/
add_action('init', 'extra_add_shortcode_submit', 10);
function extra_add_shortcode_submit() {
	if(function_exists('wpcf7_remove_shortcode')) {
		wpcf7_remove_shortcode( 'submit' );
		wpcf7_add_shortcode( 'submit', 'extra_submit_shortcode_handler' );
	}
}
if(!function_exists('extra_submit_shortcode_handler')) {
	function extra_submit_shortcode_handler( $tag ) {
		$tag = new WPCF7_Shortcode( $tag );

		$class = wpcf7_form_controls_class( $tag->type );

		$atts = array();

		$atts['class']    = $tag->get_class_option( $class );
		$atts['id']       = $tag->get_option( 'id', 'id', true );
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

		$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

		if ( empty( $value ) ) {
			$value = __( 'Send', 'wpcf7' );
		}

		$value = apply_filters('extra_cf7_submit_value', $value);

		$atts['type'] = 'submit';
		//$atts['value'] = $value;

		$atts = wpcf7_format_atts( $atts );

		$html = sprintf( '<button %1$s>%2$s</button>', $atts, $value );

		return $html;
	}
}


/**
 * echo reponsive image
 * @param $src $source
 * @param array $params $params['desktop'] $params['tablet'] $params['mobile'] required
 * @param string $class add custom classes
 * @param string $alt
 */
function extra_get_responsive_image($id = 0, $dimensions = 'thumbnail', $class = '', $alt = null, $img_itemprop = '', $caption = '') {

	// hook it to override available sizes
	$sizes = apply_filters('extra_responsive_sizes', array(
		'desktop' => 'only screen and (min-width: 961px)',
		'tablet' => 'only screen and (min-width: 691px) and (max-width: 960px)',
		'mobile' => 'only screen and (max-width: 690px)'
	));

	// SRC IS AN ID
	if(!is_numeric($id)) {
		throw new Exception(__("This must be an integer", 'extra'));
	}
	if (!isset($alt)) {
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		if (empty($alt)) {
			$attachment = get_post($id);
			$alt = $attachment->post_title;
		}
	}


	if (is_array($dimensions)) {
		$image_full_src = null;

		$real_dimensions = array();
		foreach ($dimensions as $dimension_name => $dimension) {
			// IF ONE DIMENSION IS NULL, CALCULATE IT FROM FULL DIMENSION RATIO
			if ($dimension[0] === null && $dimension[1] !== null) {
				if ($image_full_src == null) {
					$image_full_src = wp_get_attachment_image_src($id, 'full');
				}
				if (!empty($image_full_src)) {
					$dimension[0] = min(floor($dimension[1] * $image_full_src[1] / $image_full_src[2]), $image_full_src[1]);
				}
			} else if ($dimension[1] === null && $dimension[0] !== null) {
				if ($image_full_src == null) {
					$image_full_src = wp_get_attachment_image_src($id, 'full');
				}
				if (!empty($image_full_src)) {
					$dimension[1] = min(floor($dimension[0] * $image_full_src[2] / $image_full_src[1]), $image_full_src[2]);
				}
			}
			$real_dimensions[$dimension_name] = $dimension;
 		}
		$dimensions = $real_dimensions;
	}

	// START RENDERING
	ob_start();

	?>

	<figure class="responsiveImagePlaceholder<?php echo (!empty($class)) ? ' ' . $class : ''; ?><?php echo (!empty($caption)) ? ' wp-caption' : ''; ?>">
		<noscript
			<?php echo ($img_itemprop) ? 'data-img-itemprop="'.$img_itemprop.'"' : ''; ?>
			data-alt="<?php echo $alt; ?>"
			<?php foreach($sizes as $size => $value): ?>
			data-src-<?php echo $size; ?>="<?php
				$dimension = $dimensions[$size];
				$src = wp_get_attachment_image_src($id, $dimension);
				echo $src[0];
			?>"
			<?php endforeach; ?>>

			<img alt="<?php echo $alt; ?>"
				 <?php echo ($img_itemprop) ? 'itemprop="'.$img_itemprop.'"' : ''; ?>
				 src="<?php
					 $dimension = reset($dimensions);
					 $src = wp_get_attachment_image_src($id, $dimension);
					 echo $src[0];
				?>">
		</noscript>
		<img class="placeholder-image"
			 src="<?php echo EXTRA_URI ?>/assets/img/blank.png"
			 alt=""
			 style="<?php
			 $first_dimension = reset($dimensions);
			 echo (!empty($first_dimension[0])) ? 'width: ' . $first_dimension[0] . 'px;' : '';
			 echo (!empty($first_dimension[1])) ? ' height: ' . $first_dimension[1] . 'px;' : ''; ?>" />
		<?php if (!empty($caption)) : ?>
			<figcaption class="wp-caption-text">
				<?php echo $caption; ?>
			</figcaption>
		<?php endif; ?>
	</figure>
	<?php $return = ob_get_contents(); ?>

<?php
	ob_end_clean();
	return $return;
}
function extra_responsive_image($id = 0, $dimensions = 'thumbnail', $class = '', $alt = null, $img_itemprop= '', $caption = '') {
	echo extra_get_responsive_image($id, $dimensions, $class, $alt, $img_itemprop, $caption);
}

/**
 * echo reponsive image
 * @param $src $source
 * @param array $params $params['desktop'] $params['tablet'] $params['mobile'] required
 * @param string $class add custom classes
 * @param string $alt
 */
function extra_get_responsive_background_image($id = 0, $dimensions = 'thumbnail', $class = '') {

	// hook it to override available sizes
	$sizes = apply_filters('extra_responsive_sizes', array(
		'desktop' => 'only screen and (min-width: 961px)',
		'tablet' => 'only screen and (min-width: 691px) and (max-width: 960px)',
		'mobile' => 'only screen and (max-width: 690px)'
	));

	// SRC IS AN ID
	if(!is_numeric($id)) {
		throw new Exception(__("This must be an integer", 'extra'));
	}
	// START RENDERING
	ob_start();
	?>

	<div class="responsiveImagePlaceholder responsiveBackgroundImagePlaceholder<?php echo (!empty($class)) ? ' ' . $class : ''; ?>"
		style="background-image: url('<?php echo EXTRA_URI ?>/assets/img/blank.png');" >
		<noscript
			<?php foreach($sizes as $size => $value): ?>
				data-src-<?php echo $size; ?>="<?php
				$src = wp_get_attachment_image_src($id, $dimensions[$size]);
				echo $src[0];?>"
				<?php endforeach; ?>>
		</noscript>
	</div>
	<?php $return = ob_get_contents(); ?>

<?php
	ob_end_clean();
	return $return;
}
function extra_responsive_background_image($id = 0, $dimensions = 'thumbnail', $class = '') {
	echo extra_get_responsive_background_image($id, $dimensions, $class);
}
/**
 * Shortify a string with "..."
 *
 * @param $text
 * @param $max_length
 *
 * @return null|string
 */
function extra_shortify_text ($text, $max_length) {
	if (strlen($text) > $max_length) {
		$text_array = explode(' ', $text);
		$text = null;
		foreach ($text_array as $text_part) {
			if ($text == null) {
				$text = $text_part;
				if (strlen($text) > $max_length) {
					$text = substr($text, 0, $max_length-1).'...';
					break;
				}
			} else if (strlen($text.' '.$text_part) <= $max_length) {
				$text .= ' '.$text_part;
			} else {
				$text .= '...';
				break;
			}
		}
	}

	return $text;
}

function extra_get_archive_title ($id = 0) {
	global $post;
	$old_post = $post;

	if ($id != 0) {
		$post = get_post($id);
	}

	$title = null;
	if(isset($post) && !empty($post)) {
		// CATEGORY
		if (is_category()) {
			$title = sprintf(__('Archive de la catégorie "%s"', 'extra'), single_cat_title('', false));
		}

		// SEARCH
		else if (is_search()) {
			$title = sprintf(__('Résultats pour la recherche "%s"', 'extra'), get_search_query());
		}

		// TIME - DAY
		else if (is_day()) {
			$title = sprintf(__('Archive du %s', 'extra'), get_the_time('d F Y'));

		}

		// TIME - MONTH
		else if (is_month()) {
			$title = sprintf(__('Archive %s', 'extra'), get_the_time('F Y'));
		}

		// TIME - YEAR
		else if (is_year()) {
			$title = sprintf(__('Archive %s', 'extra'), get_the_time('Y'));
		}
	}

	$post = $old_post;

	return $title;
}

function extra_the_archive_title ($id = 0) {
	echo extra_get_archive_title($id);
}


/**********************
 *
 *
 *
 * REMOVE POST LIMIT FOR SEARCH
 *
 *
 *
 *********************/
if(!function_exists('extra_post_limits')) {
	add_filter('post_limits', 'extra_post_limits');
	function extra_post_limits ($limits) {
		if (is_search()) {
			global $wp_query;
			$wp_query->query_vars['posts_per_page'] = -1;
		}
		return $limits;
	}
}

/**********************
 *
 *
 *
 * SITE TITLE
 *
 *
 *
 *********************/
if(!function_exists('extra_wp_title')) {
	function extra_wp_title ($title, $sep) {
		global $paged, $page, $post;

		if (!is_feed() && !is_front_page()) {
			$title = get_bloginfo( 'name' );

			if (is_singular()) {
				if ($post != null) {
					$title .= ' '.$sep.' '.$post->post_title;
				}
			} else if (is_archive()) {
				$title .= ' '.$sep.' '.__("Archive", "extra-admin");
			}

			// Add a page number if necessary.
			if ( $paged >= 2 || $page >= 2 ) {
				$title = "$title $sep " . sprintf( __( 'Page %s', 'extra-admin' ), max( $paged, $page ) );
			}
		} else if(is_front_page()) {
		    $title = get_bloginfo('name');
		}

		return $title;
	}
}
add_filter('wp_title', 'extra_wp_title', 100, 3);
/**********************
 *
 *
 * LESS VARS
 *
 *
 *
 *********************/
function extra_less_vars($vars, $handle) {
	global $epb_full_width, $epb_half_width, $epb_one_third_width, $epb_two_third_width, $epb_gap, $content_width;
	$epb_full_width = apply_filters('extra_page_builder_full_width', 940);
	$epb_half_width = apply_filters('extra_page_builder_half_width', 460);
	$epb_one_third_width = apply_filters('extra_page_builder_one_third_width', 300);
	$epb_two_third_width = apply_filters('extra_page_builder_two_third_width', 620);
	$epb_gap = apply_filters('extra_page_builder_gap', 20);

	$vars['epb_full_width'] = $epb_full_width.'px';
	$vars['epb_half_width'] = $epb_half_width.'px';
	$vars['epb_one_third_width'] = $epb_one_third_width.'px';
	$vars['epb_two_third_width'] = $epb_two_third_width.'px';
    $vars['epb_gap'] = $epb_gap.'px';
    $vars['content_width'] = $content_width.'px';
	return $vars;
}
add_filter('less_vars', 'extra_less_vars', 10, 2);
/**********************
 *
 *
 * LESS FUNCTIONS
 *
 *
 *
 *********************/
function extra_register_less_functions() {
	register_less_function('extra_urlencode', function($arg) {
		list($type, $value) = $arg;
		return array('string', '', array (urlencode($value)));
	});
}
add_action('init', 'extra_register_less_functions');
/**********************
 *
 *
 * DATE FORMAT PHP TO JS
 *
 *
 *
 *********************/
function dateformat_to_js($php_format)
{
    $SYMBOLS_MATCHING = array(
        // Day
        'd' => 'dd',
        'D' => 'D',
        'j' => 'd',
        'l' => 'DD',
        'N' => '',
        'S' => '',
        'w' => '',
        'z' => 'o',
        // Week
        'W' => '',
        // Month
        'F' => 'MM',
        'm' => 'mm',
        'M' => 'M',
        'n' => 'm',
        't' => '',
        // Year
        'L' => '',
        'o' => '',
        'Y' => 'yy',
        'y' => 'y',
        // Time
        'a' => '',
        'A' => '',
        'B' => '',
        'g' => '',
        'G' => '',
        'h' => '',
        'H' => '',
        'i' => '',
        's' => '',
        'u' => ''
    );
    $jqueryui_format = "";
    $escaping = false;
    for($i = 0; $i < strlen($php_format); $i++)
    {
        $char = $php_format[$i];
        if($char === '\\') // PHP date format escaping character
        {
            $i++;
            if($escaping) $jqueryui_format .= $php_format[$i];
            else $jqueryui_format .= '\'' . $php_format[$i];
            $escaping = true;
        }
        else
        {
            if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
            if(isset($SYMBOLS_MATCHING[$char]))
                $jqueryui_format .= $SYMBOLS_MATCHING[$char];
            else
                $jqueryui_format .= $char;
        }
    }
    return $jqueryui_format;
}
/**********************
 *
 *
 *
 * STR TO LOWER UTF8
 *
 *
 *
 *********************/
function strtolower_utf8($inputString) {
	$outputString    = utf8_decode($inputString);
	$outputString    = strtolower($outputString);
	$outputString    = utf8_encode($outputString);
	return $outputString;
}

function _print_r($a) {
	echo "<pre>", htmlspecialchars(print_r($a, true)), "</pre>";
}/**********************
 *
 *
 *
 * NO P AROUND IMAGES
 *
 *
 *
 *********************/
function filter_ptags_on_images($content){
	$content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	$content = preg_replace('/<p>\s*(<iframe .*>)?\s*(<\/iframe>)?\s*<\/p>/iU', '\1\2', $content);
	return $content;
}
add_filter('the_content', 'filter_ptags_on_images', 10);

/**********************
 *
 *
 *
 * CORRECT AUTO EMBED FOR YOUTUBE LINK
 *
 *
 *
 *********************/
/**
 * Add a new line around paragraph links
 * @param string $content
 * @return string $content
 */
function my_autoembed_adjustments( $content ){

	$pattern = '|<p>\s*(https?://[^\s"]+)\s*</p>|im';    // your own pattern
	$to      = "<p>\n$1\n</p>";                          // your own pattern
	$content = preg_replace( $pattern, $to, $content );

	return $content;

}
add_filter( 'the_content', 'my_autoembed_adjustments', 7 );
/**********************
 *
 *
 *
 * ALLOW SVG
 *
 *
 *
 *********************/
//function extra_upload_mimes_svg($mimes) {
//	$mimes['svg'] = 'image/svg+xml';
//	return $mimes;
//}
//add_filter('upload_mimes', 'extra_upload_mimes_svg');
//function fix_svg_thumb_display() {
//	echo '
//    td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {
//      width: 100% !important;
//      height: auto !important;
//    }
//  ';
//}
//add_action('admin_head', 'fix_svg_thumb_display');