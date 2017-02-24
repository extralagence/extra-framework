<?php
/**********************
 *
 *
 *
 * CHANGE GALLERY PARAMETERS
 *
 *
 *
 *********************/
add_action('print_media_templates', function () {
	if (!apply_filters( 'extra_enabled_extra_gallery', true )) {
		return;
	}
// define your backbone template;
// the "tmpl-" prefix is required, and your input field should have a data-setting attribute matching the shortcode name
	?>
	<script type="text/html" id="tmpl-extra-gallery-type">
		<h3><?php _e('Gallery Settings'); ?></h3>
		<label class="setting" for="extra_gallery_type"><span><?php _e('Type de galerie :', 'extra'); ?></span></label>
		<select id="extra_gallery_type" data-setting="extra_gallery_type">
			<option value="mosaic"> <?php _e('Mosaïque', 'extra-admin'); ?> </option>
			<?php if (apply_filters('extra_gallery_type_slider_enabled', true)) : ?>
				<option value="slider"> <?php _e('Carousel', 'extra-admin'); ?> </option>
			<?php endif; ?>
		</select>
	</script>

	<script>
		jQuery(document).ready(function () {

			// add your shortcode attribute and its default value to the
			// gallery settings list; $.extend should work as well...
			_.extend(wp.media.gallery.defaults, {
				extra_gallery_type: 'mosaic'
			});

			// merge default gallery settings template with yours
			//wp.media.template('gallery-settings')(view);
			wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
				template: function (view) {
					return wp.media.template('extra-gallery-type')(view);
				}
			});
		});
	</script>
	<?php
});

/**********************
 *
 *
 *
 * GALLERY SHORTCODE
 *
 *
 *
 *********************/
add_action('init', function () {
	if (!apply_filters( 'extra_enabled_extra_gallery', true )) {
		return;
	}
	if (!is_admin()) {
		remove_shortcode('gallery', 'gallery_shortcode');
		add_shortcode('gallery', 'extra_gallery_handler');
	}
});

// GALLERY HANDLER
function extra_gallery_handler($atts, $content = null) {
	global $content_width;

	$ids = explode(',', $atts['ids']);
	$type = (!empty($atts['extra_gallery_type']) && $atts['extra_gallery_type'] == 'slider') ? 'slider' : 'mosaic';

	if(empty($ids)) {
		return '';
	}

	switch($type) {
		default:
		case 'mosaic':

			$return = '<ul class="extra-mosaic">';
			foreach ($ids as $id):

				$attachment = get_post( $id );
				$src = wp_get_attachment_image_src($id, apply_filters('extra_gallery_item_zoom_media_size', 'large', 'mosaic'));
				$item_classes = apply_filters('extra_gallery_item_classes', '', 'mosaic');
				if ($item_classes != '') {
					$item_classes = ' '.$item_classes;
				}
				$return .= '    <li><a href="'.$src[0].'" class="zoom' . $item_classes . '">';
				$return .= apply_filters('extra_gallery_before_item', '', 'mosaic');
				$sizes = apply_filters('extra_responsive_sizes', array());
				$params = array();
				foreach($sizes as $size => $value) {
					$params[$size] = array(
						apply_filters('extra_gallery_width', $content_width/3, 'mosaic', $size),
						apply_filters('extra_gallery_height', $content_width/3, 'mosaic', $size));
				}
				$return .= extra_get_responsive_image($id, $params, '', '', '', $attachment->post_excerpt);
				$return .= apply_filters('extra_gallery_after_item', '', 'mosaic');
				$return .= '    </a></li>';
			endforeach;
			$return .= '</ul>';
			break;
		case 'slider':
			$return =  '<div class="extra-slider extra-editor-slider' .(isset($atts['extra_gallery_class']) ? ' '  .$atts['extra_gallery_class'] : '') . '">';
			$return .= ' <div class="wrapper">';
			$return .= '     <ul>';
			foreach ($ids as $id):
				$src = wp_get_attachment_image_src($id, apply_filters('extra_gallery_item_zoom_media_size', 'large', 'slider'));
				$item_classes = apply_filters('extra_gallery_item_classes', '', 'slider');
				if (!empty($item_classes)) {
					$item_classes = ' class="'.$item_classes.'"';
				}
				$return .= '        <li><a'.$item_classes.' href="'.$src[0].'">';
				$sizes = apply_filters('extra_responsive_sizes', array());
				$params = array();
				foreach($sizes as $size => $value) {
					$params[$size] = array(
						apply_filters('extra_gallery_width', $content_width/3, 'slider', $size),
						apply_filters('extra_gallery_height', $content_width/3, 'slider', $size),
					);
				}
				$return .= extra_get_responsive_image($id, $params);
				$return .= '</a></li>';
			endforeach;
			$return .= '     </ul>';
			$return .= ' </div>';
			$return .= apply_filters('extra_gallery_slider_navigation', '<div class="pagination"></div><div class="navigation"><a class="prev" href="#">' . __('Précédent', 'extra') . '</a><a class="next" href="#">' . __('Suivant', 'extra') . '</a></div>');
			$return .= '</div>';
			break;
	}
	$return = apply_filters('extra_after_gallery', $return, $type);

	return $return;
}