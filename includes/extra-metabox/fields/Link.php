<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Link
 *
 * Define a link input metabox
 *
 * type = link
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
namespace ExtraMetabox;
class Link extends AbstractField {

	protected $url_label;
	protected $title_label;
	protected $target_label;
	protected $search_label;
	protected $select_label;
	protected $has_title;

	public static function init() {
		parent::init();
		wp_enqueue_style( 'extra-link-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-link.less' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'extra-accent-fold-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-accent-fold.js', array( 'jquery' ) );
		wp_enqueue_script( 'extra-link-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-link.js', array(
			'jquery',
			'jquery-ui-autocomplete',
			'extra-accent-fold-metabox'
		) );

		wp_localize_script( 'extra-link-metabox', 'ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );
		$this->has_title = isset( $properties['has_title'] ) ? $properties['has_title'] : true;
	}

	public function the_admin() {
		?>

		<div class="extra-link-container <?php echo $this->css_class; ?>">
			<?php if ( $this->title != null ) : ?>
				<h3><?php
					echo ( $this->icon != null ) ? '<div class="dashicons ' . $this->icon . '"></div>' : '';
					echo $this->title; ?>
				</h3>
			<?php endif; ?>

			<?php
			$this->mb->the_field( $this->get_single_field_name( 'link' ) );
			$value                    = $this->mb->get_the_value();
			$content_type             = '';
			$is_content_type_manual   = false;
			$is_content_type_content  = false;
			$is_content_type_taxonomy = false;
			if ( ! empty( $value['type'] ) ) {
				$content_type             = $value['type'];
				$is_content_type_manual   = $content_type == 'manual';
				$is_content_type_content  = $content_type == 'content';
				$is_content_type_taxonomy = $content_type == 'taxonomy';
			}
			$url             = ! empty( $value['url'] ) ? $value['url'] : '';
			$content_search  = ! empty( $value['content_search'] ) ? $value['content_search'] : '';
			$post_id         = ! empty( $value['post_id'] ) ? $value['post_id'] : '';
			$taxonomy_search = ! empty( $value['taxonomy_search'] ) ? $value['taxonomy_search'] : '';
			$taxonomy        = ! empty( $value['taxonomy'] ) ? $value['taxonomy'] : '';
			$term_slug       = ! empty( $value['term_slug'] ) ? $value['term_slug'] : '';
			$title           = ! empty( $value['title'] ) ? $value['title'] : '';
			$target          = ! empty( $value['target'] ) ? $value['target'] : '';
			?>



			<?php if ( $this->label != null ) : ?>
				<label><?php echo $this->label; ?></label>
			<?php endif; ?>

			<!--

			MANUAL TYPE

			-->
			<div class="extra-link-manual extra-link-wrapper">
				<div class="extra-link-checkbox-wrapper">
					<input class="extra-link-radio" id="<?php $this->mb->the_name(); ?>_type_manual" type="radio" name="<?php $this->mb->the_name(); ?>[type]" value="manual" <?php echo $is_content_type_manual ? ' checked="checked"' : ''; ?>>
					<label class="extra-link-radio-label extra-link-label" for="<?php $this->mb->the_name(); ?>_type_manual"><?php _e( "Adresse web", "extra-admin" ); ?></label>
				</div>
				<input class="extra-link-url" id="<?php $this->mb->the_name(); ?>_url" name="<?php $this->mb->the_name(); ?>[url]" type="text" value="<?php echo $url; ?>"<?php echo $is_content_type_manual ? '' : ' disabled' ?> />
			</div>


			<!--

			CONTENT TYPE

			-->
			<div class="extra-link-content extra-link-wrapper">

				<div class="extra-link-checkbox-wrapper">
					<input class="extra-link-radio" id="<?php $this->mb->the_name(); ?>_type_content" type="radio" name="<?php $this->mb->the_name(); ?>[type]" value="content" <?php echo $is_content_type_content ? ' checked="checked"' : ''; ?>>
					<label class="extra-link-radio-label extra-link-label" for="<?php $this->mb->the_name(); ?>_type_content"><?php _e( "Lien vers un contenu", "extra-admin" ); ?></label>
				</div>

				<input class="extra-link-autocomplete" type="text" name="<?php $this->mb->the_name(); ?>[content_search]" value="<?php echo $content_search; ?>"<?php echo $is_content_type_content ? '' : ' disabled' ?> />
				<input class="extra-link-autocomplete-hidden" id="<?php $this->mb->the_name(); ?>_post_id" name="<?php $this->mb->the_name(); ?>[post_id]" type="hidden" value="<?php echo $post_id; ?>" />


				<div class="extra-link-choice" <?php echo ( empty( $post_id ) || ! $is_content_type_content ) ? 'style="display: none;"' : ''; ?>>
					<?php echo ( ! empty( $post_id ) ) ? get_permalink( $post_id ) : ''; ?>
				</div>
			</div>


			<!--

			TAXONOMY TYPE

			-->
			<div class="extra-link-taxonomy extra-link-wrapper">

				<div class="extra-link-checkbox-wrapper">
					<input class="extra-link-radio" id="<?php $this->mb->the_name(); ?>_type_taxonomy" type="radio" name="<?php $this->mb->the_name(); ?>[type]" value="taxonomy" <?php echo $is_content_type_taxonomy ? ' checked="checked"' : ''; ?>>
					<label class="extra-link-radio-label extra-link-label" for="<?php $this->mb->the_name(); ?>_type_taxonomy"><?php _e( "Vers une taxonomie", "extra-admin" ); ?></label>
				</div>

				<input class="extra-link-autocomplete-taxonomy" type="text" name="<?php $this->mb->the_name(); ?>[taxonomy_search]" value="<?php echo $taxonomy_search; ?>"<?php echo $is_content_type_taxonomy ? '' : ' disabled' ?> />


				<input class="extra-link-autocomplete-taxonomy-hidden" id="<?php $this->mb->the_name(); ?>_taxonomy" name="<?php $this->mb->the_name(); ?>[taxonomy]" type="hidden" value="<?php echo $taxonomy; ?>" />
				<input class="extra-link-autocomplete-taxonomy-slug-hidden" id="<?php $this->mb->the_name(); ?>_term_slug" name="<?php $this->mb->the_name(); ?>[term_slug]" type="hidden" value="<?php echo $term_slug; ?>" />

				<div class="extra-link-choice-taxonomy" <?php echo ( empty( $term_slug ) || ! $is_content_type_taxonomy ) ? 'style="display: none;"' : ''; ?>>
					<?php echo ( ! empty( $term_slug ) && ! empty( $taxonomy ) ) ? get_term_link( $term_slug, $taxonomy ) : ''; ?>
				</div>
			</div>


			<!--

			TITLE

			-->
			<?php if ( $this->has_title ) : ?>
				<div class="extra-link-title-container extra-link-wrapper">
					<label class="extra-link-title-label" for="<?php $this->mb->the_name(); ?>_title"><?php _e( "Titre", "extra-admin" ); ?></label>
					<input class="extra-link-title" id="<?php $this->mb->the_name(); ?>_title" name="<?php $this->mb->the_name(); ?>[title]" type="text" value="<?php echo $title; ?>" />
				</div>
			<?php endif; ?>


			<!--

			TARGET

			-->
			<div class="extra-link-target-container extra-link-wrapper">
				<input class="extra-link-target-input" id="<?php $this->mb->the_name(); ?>_target" type="checkbox" name="<?php $this->mb->the_name(); ?>[target]" value="1"<?php echo ! empty( $target ) ? ' checked="checked"' : ''; ?>/>
				<label class="extra-link-target-label" for="<?php $this->mb->the_name(); ?>_target"><?php _e( "Ouvrir le lien dans un nouvel onglet", "extra-admin" ); ?></label>
			</div>
		</div>
		<?php
	}

	static function extra_link_taxonomy_wp_ajax() {
		global $wpdb;

		$taxonomies = apply_filters( 'extra_metabox_link_available_taxonomies', get_taxonomies() );
		$taxonomies = "'" . implode( "', '", $taxonomies ) . "'";

		$keyword = '%' . $_GET['term'] . '%';

		$query = $wpdb->prepare( "
			SELECT DISTINCT t.term_id, t.name, t.slug, tt.taxonomy
			FROM {$wpdb->terms} as t
			INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id
			WHERE t.name LIKE '%s'
			AND tt.count > 0
			AND tt.taxonomy IN (" . $taxonomies . ")
					ORDER BY t.term_id
			LIMIT 10
		", $keyword );

		$results = $wpdb->get_results( $query );

		$data = array();
		foreach ( $results as $result ) {
			$taxonomy = get_taxonomy( $result->taxonomy );

			$data[] = array(
				'term_id'       => $result->term_id,
				'slug'          => $result->slug,
				'name'          => $result->name,
				'taxonomy'      => $result->taxonomy,
				'taxonomy_name' => $taxonomy->labels->name,
				'url'           => get_term_link( $result->slug, $result->taxonomy )
			);
		}

		echo json_encode( $data );
		die();
	}

	static function extra_link_wp_ajax() {
		global $wpdb;

		$types = get_post_types( array(
			'public' => true
		) );
		if ( array_key_exists( 'attachment', $types ) ) {
			unset( $types['attachment'] );
		}
		$post_types = "'" . implode( "', '", $types ) . "'";

		$keyword = '%' . $_GET['term'] . '%';

		$query = $wpdb->prepare( "
		SELECT DISTINCT p.ID as post_id, p.post_title, p.post_type
				FROM {$wpdb->posts} as p
				WHERE p.post_title LIKE '%s'
				AND p.post_status = 'publish'
				AND p.post_type IN (" . $post_types . ")
				ORDER BY p.ID
		LIMIT 10
	", $keyword );

		$results = $wpdb->get_results( $query );

		$data = array();
		foreach ( $results as $result ) {
			$type = get_post_type_object( $result->post_type );

			$data[] = array(
				'ID'         => $result->post_id,
				'post_title' => html_entity_decode( $result->post_title ),
				'post_type'  => $type->labels->singular_name,
				'url'        => get_permalink( $result->post_id )
			);
		}

		echo json_encode( $data );
		die();
	}

	public function the_admin_column_value() {
		//TODO
		echo '-';
	}

	public static function get_permalink_from_meta( $meta, $name ) {
		$type      = isset( $meta[ $name ]['type'] ) ? $meta[ $name ]['type'] : '';
		$url       = isset( $meta[ $name ]['url'] ) ? $meta[ $name ]['url'] : '';
		$post_id   = isset( $meta[ $name ]['post_id'] ) ? $meta[ $name ]['post_id'] : '';
		$taxonomy  = isset( $meta[ $name ]['taxonomy'] ) ? $meta[ $name ]['taxonomy'] : '';
		$term_slug = isset( $meta[ $name ]['term_slug'] ) ? $meta[ $name ]['term_slug'] : '';

		if ( $type == 'content' ) {
			return get_permalink( $post_id );
		} else {
			if ( $type == 'taxonomy' ) {
				return get_term_link( $term_slug, $taxonomy );
			} else {
				return $url;
			}
		}
	}

	public static function get_target_from_meta( $meta, $name ) {
		if ( ! empty( $meta[ $name ]['target'] ) && $meta[ $name ]['target'] ) {
			return '_blank';
		} else {
			return '_self';
		}
	}

	public static function get_title_from_meta( $meta, $name ) {
		$title = ! empty( $meta[ $name ]['title'] ) ? $meta[ $name ]['title'] : '';

		return $title;
	}
}

add_action( 'wp_ajax_extra-link', array( '\\ExtraMetabox\\Link', 'extra_link_wp_ajax' ) );
add_action( 'wp_ajax_extra-link-taxonomy', array( '\\ExtraMetabox\\Link', 'extra_link_taxonomy_wp_ajax' ) );