<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Textarea
 *
 * Define a simple textarea metabox
 *
 * type = textarea
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
namespace ExtraMetabox;
class Textarea extends AbstractField {

	public function the_admin() {
		?>
		<div class="extra-field-wrapper extra-textarea-wrapper <?php echo $this->css_class; ?><?php echo ( $this->wide ) ? ' extra-textarea-wide' : ''; ?><?php echo ( $this->small ) ? ' extra-textarea-small' : ''; ?>">
			<?php $this->mb->the_field( $this->get_single_field_name( 'text' ) ); ?>
			<?php echo ( $this->icon != null ) ? '<div class="dashicons ' . $this->icon . '"></div>' : ''; ?>
			<?php if ( ! empty( $this->label ) ): ?>
				<label for="<?php $this->mb->the_name(); ?>"><?php echo $this->label; ?><?php echo $this->required ? '*' : ''; ?></label><?php endif; ?>
			<div class="extra-input-wrapper">
				<textarea
					id="<?php $this->mb->the_name(); ?>"
					data-extra-field-name="<?php echo $this->name; ?>"
					name="<?php $this->mb->the_name(); ?>"
					<?php echo ( $this->maxlength != null ) ? ' maxlength="' . $this->maxlength . '"' : ''; ?>
					<?php echo $this->required ? ' required="true"' : ''; ?>><?php $this->mb->the_value(); ?></textarea>
				<?php if ( $this->description != null ) : ?>
					<div class="extra-input-description">
						<small><em><?php echo $this->description; ?></em></small>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );
		$this->wide      = isset( $properties['wide'] ) ? $properties['wide'] : false;
		$this->small     = isset( $properties['small'] ) ? $properties['small'] : false;
		$this->maxlength = isset( $properties['maxlength'] ) ? $properties['maxlength'] : null;
	}


	public function the_admin_column_value() {
		$meta = $this->mb->get_meta( $this->name, $this->mb->meta );
		echo $meta;
	}
} 