<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Hidden
 *
 * Define a hidden field metabox
 *
 * type = hidden
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class Hidden extends AbstractField {

	protected $id;

	public function the_admin() {
		?>
		<?php $this->mb->the_field($this->get_single_field_name('hidden')); ?>
		<?php
		if(!isset($this->id)) {
			$this->id = $this->mb->get_the_name();
		}
		?>
		<input <?php echo (empty($this->css_class))? '' : 'class="'.$this->css_class.'"'; ?> id="<?php echo $this->id; ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" >
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->id = isset($properties['id']) ? $properties['id'] : null;
	}

	public function the_admin_column_value() {
		//TODO
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
} 