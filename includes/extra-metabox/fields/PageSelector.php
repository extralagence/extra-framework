<?php

/**
 * Class Text
 *
 * Define a page selector input metabox
 *
 * type = page_selector
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - post_type (optional)
 * - option_none_value (optional)
 * - placeholder (optional): label when the field is empty
 * - regex (optional): regex checked for each changes
 */
namespace ExtraMetabox;
class PageSelector extends AbstractField {

	protected $post_type;
	protected $option_none_value;

	public static function init() {
		parent::init();
	}

	public function the_admin() {
		if ( ! empty( $this->title ) ) : ?>
			<h2>
				<?php echo ( $this->icon != null ) ? '<div class="dashicons ' . $this->icon . '"></div>' : ''; ?>
				<?php echo ( $this->title == null ) ? __( 'Image', 'extra-admin' ) : $this->title; ?>
			</h2>
		<?php endif;
		$this->mb->the_field( $this->get_single_field_name( 'page_selector' ) ); ?>
		<p class="<?php echo $this->css_class; ?> extra-page-selector-container">
			<?php if ( ! empty( $this->label ) ): ?>
				<label for="<?php $this->mb->the_name(); ?>"><?php echo $this->label; ?></label><?php endif; ?>
			<?php $this->generate_post_select( $this->mb->get_the_name(), $this->mb->get_the_value(), $this->post_type, $this->option_none_value ); ?>
		</p>
		<?php
	}

	private function generate_post_select( $select_id, $selected = 0, $post_type = null, $option_none_value = true ) {
		if ( $post_type === null ) {
			wp_dropdown_pages( array(
				'name'             => $this->mb->get_the_name(),
				'selected'         => $this->mb->get_the_value(),
				'show_option_none' => $option_none_value === true ? __( 'Choisir dans la liste...', 'extra' ) : $option_none_value
			) );
		} else {
			//$post_type_object = get_post_type_object( $post_type );
			//$label = $post_type_object->label;
			$posts = get_posts( array( 'post_type'        => $post_type,
			                           'post_status'      => 'publish',
			                           'suppress_filters' => false,
			                           'posts_per_page'   => - 1
			) );
			echo '<select name="' . $select_id . '" id="' . $select_id . '">';
			if ( ! empty( $option_none_value ) ) {
				echo '<option value="">' . ( $option_none_value === true ? __( 'Choisir dans la liste...', 'extra' ) : $option_none_value ) . '</option>';
			}
			foreach ( $posts as $post ) {
				echo '<option value="', $post->ID, '"', $selected == $post->ID ? ' selected="selected"' : '', '>', $post->post_title, '</option>';
			}
			echo '</select>';
		}
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );
		$this->post_type         = ( isset ( $properties['post_type'] ) ) ? $properties['post_type'] : null;
		$this->option_none_value = ( isset ( $properties['option_none_value'] ) ) ? $properties['option_none_value'] : true;
	}


	public function the_admin_column_value() {
		$value = '';
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		if (!empty($meta)) {
			$post = get_post($meta);
			if ($post) {
				$value = $post->post_title;
			}
		}
		echo $value;
	}
}