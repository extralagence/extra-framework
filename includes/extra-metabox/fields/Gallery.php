<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Gallery
 *
 * Define a gallery metabox
 *
 * type = gallery
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
namespace ExtraMetabox;
class Gallery extends AbstractField {

	public static function init () {
		parent::init();
		add_action('admin_enqueue_scripts', function() {
			wp_enqueue_style('extra-image-gallery-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-image-gallery.less');
			wp_enqueue_script('extra-gallery-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-gallery.js', array('jquery'), null, true);
		});
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?>">
			<?php if ($this->title != null) : ?>
				<h3><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->title; ?>
				</h3>
			<?php endif; ?>
			<div class="extra-custom-gallery">
				<?php $this->mb->the_field($this->get_single_field_name("gallery_shortcode")); ?>
				<div class="extra-input-wrapper">
					<a href="#" class="button choose-button"><?php _e("Éditer la galerie d'images", "extra"); ?></a>
					<?php if ($this->description != null) : ?>
						<div class="extra-input-description"><small><em><?php echo $this->description; ?></em></small></div>
					<?php endif; ?>
				</div>

				<div class="thumbs"><?php
					$ids = $this->mb->get_the_value();
					if(isset($ids) && !empty($ids) && sizeof($ids) > 0) {
						$ids = explode(",", $ids);
						foreach($ids as $id) {
							$src = wp_get_attachment_image_src($id, 'thumbnail');
							echo '<span class="image"><img data-id="'.$id.'" src="'.$src[0].'" width="150" /><a class="close" href="#close"><span class="dashicons dashicons-no"></span></a></span>';
						}
					}
					?>
				</div>
				<input class="gallery-input" type="hidden" value="<?php $this->mb->the_value(); ?>" name="<?php $this->mb->the_name(); ?>" />
			</div>
		</div>
		<?php
	}

	/**
	 * @deprecated
	 */
	public static function explodeGalleryShortCode($ids) {

		return \ExtraMetabox\Gallery::get_gallery_ids($ids);
	}

	public static function get_gallery_ids($ids) {
		$data = array();
		if (!empty($ids)) {
			$data = explode(',', $ids);
		}

		return $data;
	}

	public function the_admin_column_value() {
		//TODO
		echo '-';
	}
}