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
 * Define a simple text input metabox
 *
 * type = text
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - placeholder (optional): label when the field is empty
 * - regex (optional): regex checked for each changes
 * - description (optional)
 */
class Text extends AbstractField {

	protected $suffix;
	protected $regex;
	protected $placeholder;

	public static function init () {
		parent::init();
		wp_enqueue_script('extra-text-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-text.js', array('jquery'), null, true);
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-text-container">
			<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
			<?php if ($this->title != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->title; ?>
				</h2>
			<?php endif; ?>
			<?php echo ($this->title == null && $this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<div class="extra-input-wrapper">
				<input
					class="extra-text-input"
					id="<?php $this->mb->the_name(); ?>"
					name="<?php $this->mb->the_name(); ?>"
					type="text"
					data-name="<?php echo $this->name; ?>"
					value="<?php
					$value = $this->mb->get_the_value();
					if(empty($value)) {
						$value = $this->default;
					}
					echo $value; ?>"
					<?php echo ($this->regex != null) ? 'data-regex="'.$this->regex.'"' : ''; ?>
					<?php echo ($this->placeholder != null) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>>
				<?php echo ($this->suffix == null) ? '' : $this->suffix; ?>
				<?php if ($this->description != null) : ?>
					<div><small><em><?php echo $this->description; ?></em></small></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->suffix = isset($properties['suffix']) ? $properties['suffix'] : null;
		$this->regex = isset($properties['regex']) ? $properties['regex'] : null;
        $this->placeholder = isset($properties['placeholder']) ? $properties['placeholder'] : null;
        $this->default = isset($properties['default']) ? $properties['default'] : '';
        $this->description = isset($properties['description']) ? $properties['description'] : '';
	}

	public function the_admin_column_value() {
		//TODO
		$meta = isset($this->mb->meta[$this->name]) ? $this->mb->meta[$this->name] : '-';
		echo $meta;
	}
}