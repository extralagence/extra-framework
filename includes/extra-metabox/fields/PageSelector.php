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
 * - placeholder (optional): label when the field is empty
 * - regex (optional): regex checked for each changes
 */
class PageSelector extends AbstractField {

	protected $post_type;

	public static function init () {
		parent::init();
	}

	public function the_admin() {
		if (!empty($this->title)) : ?>
		<h2>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<?php echo ($this->title == null) ? __('Image', 'extra-admin') : $this->title; ?>
		</h2>
		<?php endif;
		$this->mb->the_field($this->get_single_field_name('page_selector')); ?>
		<p class="<?php echo $this->css_class; ?> extra-page-selector-container">
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<?php $this->generate_post_select($this->mb->get_the_name(), $this->mb->get_the_value(), $this->post_type, $this->option_none_text, $this->option_none_value); ?>
		</p>
		<?php
	}

	private function generate_post_select($select_id, $selected = 0, $post_type = null, $option_none_text = null, $option_none_value = null) {
		if ($post_type === null) {
			wp_dropdown_pages(array(
				'name' => $this->mb->get_the_name(),
				'selected' => $this->mb->get_the_value(),
				'show_option_none' => ($option_none_text) ? $option_none_text : ($option_none_value) ? __('Choisir dans la liste...', 'extra') : '',
			));
		} else {
			$post_type_object = get_post_type_object($post_type);
			//$label = $post_type_object->label;
			$posts = get_posts(array('post_type'=> $post_type, 'post_status'=> 'publish', 'suppress_filters' => false, 'posts_per_page'=>-1));
			echo '<select name="'. $select_id .'" id="'.$select_id.'">';
			if($option_none_text || $option_none_value) {
				echo '<option value="'. ($option_none_value !== null ? $option_none_value : 0) . '">' . ($option_none_text != null ? $option_none_text : __('Choisir dans la liste...', 'extra')) . '</option>';
			}
			foreach ($posts as $post) {
				echo '<option value="', $post->ID, '"', $selected == $post->ID ? ' selected="selected"' : '', '>', $post->post_title, '</option>';
			}
			echo '</select>';
		}
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->post_type = (isset ($properties['post_type'])) ? $properties['post_type'] : null;
		$this->option_none_text = (isset ($properties['option_none_text'])) ? $properties['option_none_text'] : null;
		$this->option_none_value = (isset ($properties['option_none_value'])) ? $properties['option_none_value'] : null;
	}

	public function the_admin_column_value() {
		//TODO
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
}