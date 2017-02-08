<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Text
 *
 * Define a taxonomy selector input metabox
 *
 * type = taxonomy_selector
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - placeholder (optional): label when the field is empty
 * - regex (optional): regex checked for each changes
 */
namespace ExtraMetabox;
class TaxonomySelector extends AbstractField {

	protected $taxonomy;
	protected $show_option_none;

	public static function init () {
		parent::init();
	}

	public function the_admin() {
		?>
		<?php $this->mb->the_field($this->get_single_field_name('taxonomy_selector')); ?>
		<p class="<?php echo $this->css_class; ?> extra-taxonomy-selector-container">
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?><?php echo $this->required ? '*' : ''; ?></label>
			<?php $this->generate_category_select(); ?>
		</p>
		<?php
	}

	private function generate_category_select() {
		wp_dropdown_categories(array(
			'show_option_none' => ($this->show_option_none) ? $this->show_option_none : __("Selectionnez une categorie", 'extra-admin'),
			'name' => $this->mb->get_the_name(),
			'selected' => $this->mb->get_the_value(),
			'taxonomy' => $this->taxonomy,
			'hierarchical' => true,
			'hide_empty' => false,
			'option_none_value' => '',
			'required' => ($this->required) ? 'true' : 'false'
		));
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->taxonomy = (isset ($properties['taxonomy'])) ? $properties['taxonomy'] : 'category';
		$this->show_option_none = (isset ($properties['show_option_none'])) ? $properties['show_option_none'] : null;
	}

	public function the_admin_column_value() {
		$term_name = '';
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		if (!empty($meta)) {
			$term = get_term_by('id', $meta, $this->taxonomy);
			if ($term) {
				$term_name = $term->name;
			}
		}
		echo $term_name;
	}
} 