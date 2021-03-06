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
 * Define a select metabox
 *
 * type = select
 *
 * Options :
 * - name (required)
 * - values (required)
 * - label (optional)
 * - icon (optional)
 */
namespace ExtraMetabox;
class Select extends AbstractField {

	public function the_admin() {
		?>
        <?php if ($this->title != null) : ?>
            <h2><?php
                echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
                echo $this->title; ?>
            </h2>
        <?php endif; ?>
		<div class="<?php echo $this->css_class; ?> extra-select-container">
			<?php $this->mb->the_field($this->get_single_field_name('select')); ?>
            <label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?><?php echo $this->required ? '*' : ''; ?></label>
			<select
				class="extra-select-input"
				id="<?php $this->mb->the_name(); ?>"
				name="<?php $this->mb->the_name(); ?>"
				<?php echo $this->required ? ' required="true"' : ''; ?>
				>
				<?php
                    foreach($this->values as $value => $label) {
                        echo '<option';
                        if ($this->mb->get_the_value() == $value) echo ' selected="selected"';
                        echo ' value="'.$value.'">'.$label.'</value>';
                    }
				?>
			</select>
			<?php if ($this->description != null) : ?>
				<div class="extra-input-description"><small><em><?php echo $this->description; ?></em></small></div>
			<?php endif; ?>
		</div>
		<?php
	}

	public function the_admin_column_value() {
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);

		if (array_key_exists($meta, $this->values)) {
			$meta = $this->values[$meta];
		}

		if ($meta) {
			echo $meta;
		}
	}

    public function extract_properties($properties) {
        parent::extract_properties($properties);
        $this->values = (isset ($properties['values'])) ? $properties['values'] : null;
    }
}