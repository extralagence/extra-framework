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
 * Define a checkbox input metabox
 *
 * type = checkbox
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
namespace ExtraMetabox;
class Checkbox extends AbstractField {

	public function the_admin() {

		?>
        <?php if ($this->title != null) : ?>
            <h2><?php
                echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
                echo $this->title; ?>
            </h2>
        <?php endif; ?>
		<div class="<?php echo $this->css_class; ?> extra-checkbox-container">
			<?php $this->mb->the_field($this->get_single_field_name('checkbox')); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<?php if (!$this->label_right) : ?>
				<label class="extra-label-checkbox" for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<?php endif; ?>
			<input
				class="extra-checkbox-input"
				id="<?php $this->mb->the_name(); ?>"
				name="<?php $this->mb->the_name(); ?>"
				type="checkbox"
				value="1"
				<?php if ($this->mb->get_the_value()) echo ' checked="checked"'; ?>
				>
			<?php if ($this->label_right) : ?>
				<label class="extra-label-checkbox extra-label-right-checkbox" for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<?php endif; ?>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->label_right = isset($properties['label_right']) ? $properties['label_right'] : true;
	}

	public function the_admin_column_value() {
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		if ($meta) {
			_e("Oui", "extra-admin");
		} else {
			_e("Non", "extra-admin");
		}
	}
}