<?php
if ( class_exists( 'GFAPI' ) ) {
	global $extra_options;

	$forms = GFAPI::get_forms();

	$reservation_options  = array();
	$notification_options = array();
	foreach ( $forms as $form ) {
		$reservation_options[$form['id']] = $form['title'];

		$notifications = $form['notifications'];
		foreach ( $notifications as $notification ) {
			$notification_options[$notification['id']] = $form['title'] . ' - ' . $notification['name'];
		}
	}

	$sections[] = array(
		'icon'   => 'el-icon-briefcase',
		'title'  => __( 'Réservation', 'extra-admin' ),
		'desc'   => null,
		'fields' => array(
			array(
				'id'      => 'reservation_form',
				'type'    => 'select',
				'options' => $reservation_options,
				'title'   => __( 'Formulaire de reservation', 'extra-admin' ),
			),
			array(
				'id'      => 'reservation_notification',
				'type'    => 'select',
				'options' => $notification_options,
				'title'   => __( 'Notification cliente', 'extra-admin' ),
			),
			array(
				'id'    => 'reservation_debit_authorization',
				'type'  => 'media',
				'mode'  => 'application',
				'title' => __( 'Autorisation de prélèvement (pdf)', 'extra-admin' ),
			)
		)
	);
}

/**
 * Class GravityFormSelector
 *
 * Define a gravity form selector input metabox
 *
 * type = page_selector
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - option_none_value (optional)
 * - option_none_text (optional)
 * - placeholder (optional): label when the field is empty
 * - regex (optional): regex checked for each changes
 */
class GravityFormSelector extends AbstractField {

	protected $option_none_value;
	protected $option_none_text;

	public static function init() {
		if ( !class_exists( 'GFAPI' ) ) {
			return;
		}
		parent::init();
	}

	public function the_admin() {

		if ( !class_exists( 'GFAPI' ) ) {
			return;
		}
		if ( !empty( $this->title ) ) : ?>
			<h2>
				<?php echo ( $this->icon != null ) ? '<div class="dashicons ' . $this->icon . '"></div>' : ''; ?>
				<?php echo ( $this->title == null ) ? __( 'Image', 'extra-admin' ) : $this->title; ?>
			</h2>
		<?php endif;
		$this->mb->the_field( $this->get_single_field_name( 'page_selector' ) ); ?>
		<p class="<?php echo $this->css_class; ?> extra-page-selector-container">
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ( $this->label == null ) ? $this->name : $this->label; ?></label>
			<?php $this->generate_form_select( $this->mb->get_the_name(), $this->mb->get_the_value(), $this->option_none_text, $this->option_none_value ); ?>
		</p>
		<?php
	}

	private function generate_form_select( $select_id, $selected = 0, $option_none_text = null, $option_none_value = null ) {

		// Get forms
		$forms = GFAPI::get_forms();


		echo '<select name="' . $select_id . '" id="' . $select_id . '">';
		if ( $option_none_text || $option_none_value ) {
			echo '<option value="' . ( $option_none_value !== null ? $option_none_value : 0 ) . '">' . ( $option_none_text != null ? $option_none_text : __( 'Choisir dans la liste...', 'extra' ) ) . '</option>';
		}

		foreach ( $forms as $form ) {
			$select_options[$form['id']] = $form['title'];
			echo '<option value="', $form['id'], '"', $selected == $form['id'] ? ' selected="selected"' : '', '>', $form['title'], '</option>';
		}
		echo '</select>';
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );
		$this->option_none_text  = ( isset ( $properties['option_none_text'] ) ) ? $properties['option_none_text'] : null;
		$this->option_none_value = ( isset ( $properties['option_none_value'] ) ) ? $properties['option_none_value'] : null;
	}

	public function the_admin_column_value() {
		//TODO
		$meta = $this->mb->get_meta( $this->name, $this->mb->meta );
		echo $meta;
	}
}