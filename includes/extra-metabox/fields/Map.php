<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Map
 *
 * Define a map metabox
 *
 * type = map
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class Map extends AbstractField {

	protected $show_address = true;

	public static function init() {
		parent::init();
		wp_enqueue_style( 'extra-map-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-map.less' );
		wp_enqueue_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBpFeTSnmCMi1Vb3LuLoAivc4D4CeA2YJs&sensor=false', array( 'jquery' ), null, true );
		wp_enqueue_script( 'extra-map-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-map.js', array( 'jquery' ), null, true );
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );

		$this->show_address = ( isset( $properties['show_address'] ) ) ? $properties['show_address'] : true;
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?>">
			<?php if ( isset( $this->title ) ): ?>
				<h2>
					<?php echo ( $this->icon != null ) ? '<div class="dashicons ' . $this->icon . '"></div>' : ''; ?>
					<?php echo $this->title; ?>
				</h2>
			<?php endif; ?>

			<div class="extra-map">

				<!-- ADDRESS -->
				<?php if ( $this->show_address ) : ?>
					<?php $this->mb->the_field( $this->get_prefixed_field_name( 'address' ) ); ?>
					<p>
						<label for="<?php $this->mb->the_name(); ?>"><?php _e( "Adresse à afficher", "extra-admin" ); ?></label>
						<textarea id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>"><?php $this->mb->the_value(); ?></textarea>
					</p>
				<?php endif; ?>

				<!-- LATITUDE -->
				<?php
				$this->mb->the_field( $this->get_prefixed_field_name( "lat" ) );
				$field = $this->mb->get_the_value();
				?>
				<input
					class="lat"
					type="hidden"
					id="<?php $this->mb->the_name(); ?>"
					name="<?php $this->mb->the_name(); ?>"
					value="<?php echo ( !empty( $field ) ) ? $field : ''; ?>"
					data-default="45.7609889"
				/>

				<!-- LONGITUDE -->
				<?php $this->mb->the_field( $this->get_prefixed_field_name( "lon" ) );
				$field = $this->mb->get_the_value(); ?>
				<input
					class="lon"
					type="hidden"
					id="<?php $this->mb->the_name(); ?>"
					name="<?php $this->mb->the_name(); ?>"
					value="<?php echo ( !empty( $field ) ) ? $field : ''; ?>"
					data-default="4.8340262"
				/>

				<!-- ZOOM -->
				<?php $this->mb->the_field( $this->get_prefixed_field_name( "zoom" ) );
				$field = $this->mb->get_the_value(); ?>
				<input
					class="zoom"
					type="hidden"
					id="<?php $this->mb->the_name(); ?>"
					name="<?php $this->mb->the_name(); ?>"
					value="<?php echo ( !empty( $field ) ) ? $field : ''; ?>"
					data-default="15"
				/>

				<!-- ADDRESS -->
				<?php
				$this->mb->the_field( $this->get_prefixed_field_name( "address_map" ) );
				$field = $this->mb->get_the_value();
				?>
				<p>
					<label for="<?php $this->mb->the_name(); ?>"><?php _e( "Adresse pour la carte", "extra-admin" ); ?></label>
					<input
						class="address"
						type="text"
						id="<?php $this->mb->the_name(); ?>"
						name="<?php $this->mb->the_name(); ?>"
						value="<?php echo $field; ?>"
					/>
				</p>

				<!-- MAP -->
				<div class="map-container"></div>
			</div>
		</div>
		<?php
	}

	public function the_admin_column_value() {
		//TODO
	}
}