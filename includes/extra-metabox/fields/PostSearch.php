<?php
/**
 * Class PostSearch
 *
 * Define a post search input metabox
 *
 * type = post_search
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
namespace ExtraMetabox;
class PostSearch extends AbstractField {

	protected $post_type;

	public static function init() {
		parent::init();
		wp_enqueue_style( 'extra-post-search-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-post-search.less' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'extra-accent-fold-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-accent-fold.js', array( 'jquery' ) );
		wp_enqueue_script( 'extra-post-search-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-post-search.js', array( 'jquery', 'jquery-ui-autocomplete', 'extra-accent-fold-metabox' ) );

		wp_localize_script( 'extra-post-search-metabox', 'ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );
		$this->post_type = isset( $properties['post_type'] ) ? $properties['post_type'] : '';
	}

	public function the_admin() {
		?>

		<div class="extra-post-search-container <?php echo $this->css_class; ?>">
			<?php if ( $this->title != null ) : ?>
				<h3><?php
					echo ( $this->icon != null ) ? '<div class="dashicons ' . $this->icon . '"></div>' : '';
					echo $this->title; ?>
				</h3>
			<?php endif; ?>

			<?php if ( $this->label != null ) : ?>
				<label><?php echo $this->label; ?></label>
			<?php endif; ?>


			<div class="extra-input-wrapper">
				<?php $this->mb->the_field( $this->get_prefixed_field_name( "content_search" ) ); ?>
				<input class="extra-post-search-autocomplete" type="text" name="<?php $this->mb->the_name(); ?>" value="<?php $this->mb->the_value(); ?>" data-post-type="<?php echo $this->post_type; ?>" />
				<?php $this->mb->the_field( $this->name ); ?>
				<?php $post_id = $this->mb->get_the_value(); ?>
				<input class="extra-post-search-autocomplete-hidden" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />

				<div class="extra-post-search-choice" <?php echo ( empty( $post_id ) ) ? 'style="display: none;"' : ''; ?>>
					<a href="<?php echo ( !empty( $post_id ) ) ? get_permalink( $this->mb->get_the_value() ) : ''; ?>" target="_blank"><?php echo ( !empty( $post_id ) ) ? get_permalink( $this->mb->get_the_value() ) : ''; ?></a>
				</div>

				<?php if ($this->description != null) : ?>
					<div class="extra-input-description"><small><em><?php echo $this->description; ?></em></small></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}


	static function extra_post_search_wp_ajax() {
		global $wpdb;

		$post_search_type = isset($_GET['post_search_type']) ? $_GET['post_search_type'] : '';

		if (!empty($post_search_type)) {
			$post_types = "'".$post_search_type."'";
		} else {
			$types = get_post_types( array(
				'public' => true
			) );
			if ( array_key_exists( 'attachment', $types ) ) {
				unset( $types['attachment'] );
			}
			$post_types = "'" . implode( "', '", $types ) . "'";
		}

		$keyword = '%' . $_GET['term'] . '%';

		$query = $wpdb->prepare( "
		SELECT DISTINCT p.ID as post_id, p.post_title, p.post_type
				FROM {$wpdb->posts} as p
				WHERE p.post_title LIKE '%s'
				AND (p.post_status = 'publish') 
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
}

add_action( 'wp_ajax_extra-post-search', array( '\\ExtraMetabox\\PostSearch', 'extra_post_search_wp_ajax' ) );