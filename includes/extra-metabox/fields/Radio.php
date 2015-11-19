<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Checkbox
 *
 * Define a radio button group input metabox
 *
 * type = radio
 *
 * Options :
 *  - radis (required) array of arrays with label and value :
 *
'radios' => array(
	array(
		'value' => 'header',
		'label' => 'En-tête'
	),
	array(
		'value' => 'footer',
		'label' => 'Pied de page'
	)
),
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class Radio extends AbstractField {

	protected $radios = array();

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-radio-group-container">
			<?php $this->mb->the_field($this->get_single_field_name('radio')); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>

			<?php foreach ($this->radios as $radio) {
				$label = '<label class="label-position-'.$this->label_position.'" for="' . $this->mb->get_the_name() . '-' . $radio['value'] . '">' . $radio['label'] . '</label>';
				$input = '<input
				class="extra-radio-group-input label-position-'.$this->label_position.'"
				id="'.$this->mb->get_the_name().'-'.$radio['value'].'"
				type="radio"
				name="'.$this->mb->get_the_name().'"
				value="'.$radio['value'].'"'.($this->mb->is_value($radio['value'])?' checked="checked"':'').'>';
				if($this->label_position == 'right') {
					echo $label . $input  .'<br>';
				} else {
					echo $input . $label  .'<br>';
				}
			}
			?>
		</div>
	<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->radios = isset($properties['radios']) ? $properties['radios'] : array();
		$this->label_position = isset($properties['label_position']) ? $properties['label_position'] : 'left';
	}

	public function the_admin_column_value() {
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		if (!empty($meta)) {
			echo $meta;
		} else {
			echo '-';
		}
	}
}