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
class Link extends AbstractField {

	protected $url_label;
	protected $title_label;
	protected $target_label;
	protected $search_label;
	protected $select_label;

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-link-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-link.less');
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('extra-accent-fold-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-accent-fold.js', array('jquery'));
		wp_enqueue_script('extra-link-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-link.js', array('jquery', 'jquery-ui-autocomplete', 'extra-accent-fold-metabox'));

		wp_localize_script('extra-link-metabox', 'ajax', array( 'url' => admin_url( 'admin-ajax.php' )));
	}

	public function the_admin() {
		?>
		<div class="extra-link-container <?php echo $this->css_class; ?>">
			<?php if ($this->title != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->title; ?>
				</h2>
			<?php endif; ?>
			<div class="extra-link-manual">
				<?php
				$this->mb->the_field($this->get_prefixed_field_name("type"));
				$this->mb->is_value('content');
				?>
				<label class="extra-link-radio-label" for="<?php $this->mb->the_name(); ?>_manual"><?php _e("Adresse web", "extra-admin"); ?></label>
				<input class="extra-link-radio" id="<?php $this->mb->the_name(); ?>_manual" type="radio" name="<?php $this->mb->the_name(); ?>" value="manual" <?php echo ($this->mb->is_selected('manual'))?' checked="checked"':''; ?>>
				<?php $this->mb->the_field($this->get_prefixed_field_name("url")); ?>
				<input class="extra-link-url" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="text" value="<?php $this->mb->the_value(); ?>"<?php echo ($this->mb->is_selected('manual'))?' checked="checked"':''; ?>/><br>
			</div>

			<div class="extra-link-content">
				<?php
				$this->mb->the_field($this->get_prefixed_field_name("type"));
				$show_content = $this->mb->is_value('content');
				?>

				<label class="extra-link-radio-label" for="<?php $this->mb->the_name(); ?>_content"><?php _e("Lien vers un contenu", "extra-admin"); ?></label>
				<input class="extra-link-radio" id="<?php $this->mb->the_name(); ?>_content" type="radio" name="<?php $this->mb->the_name(); ?>" value="content" <?php echo ($show_content)?' checked="checked"':''; ?>>

				<?php $this->mb->the_field($this->get_prefixed_field_name("content_search")); ?>
				<input class="extra-link-autocomplete" type="text" name="<?php $this->mb->the_name(); ?>" value="<?php $this->mb->the_value(); ?>"<?php echo ($show_content) ? '' : ' disabled' ?> />
				<?php $this->mb->the_field($this->get_prefixed_field_name("content")); ?>
				<?php $post_id = $this->mb->get_the_value(); ?>
				<input class="extra-link-autocomplete-hidden" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>"/><br>

				<div class="extra-link-choice" <?php echo (empty($post_id) || !$show_content) ? 'style="display: none;"' : ''; ?>>
					<?php echo (!empty($post_id)) ? get_permalink($this->mb->get_the_value()) : ''; ?>
				</div>
			</div>

			<div class="extra-link-taxonomy">
				<?php
				$this->mb->the_field($this->get_prefixed_field_name("type"));
				$show_content = $this->mb->is_value('taxonomy');
				?>

				<label class="extra-link-radio-label" for="<?php $this->mb->the_name(); ?>_taxonomy"><?php _e("Vers une taxonomie", "extra-admin"); ?></label>
				<input class="extra-link-radio" id="<?php $this->mb->the_name(); ?>_taxonomy" type="radio" name="<?php $this->mb->the_name(); ?>" value="taxonomy" <?php echo ($show_content)?' checked="checked"':''; ?>>

				<?php $this->mb->the_field($this->get_prefixed_field_name("taxonomy_search")); ?>
				<input class="extra-link-autocomplete-taxonomy" type="text" name="<?php $this->mb->the_name(); ?>" value="<?php $this->mb->the_value(); ?>"<?php echo ($show_content) ? '' : ' disabled' ?> />
				<?php $this->mb->the_field($this->get_prefixed_field_name("taxonomy")); ?>
				<?php $taxonomy = $this->mb->get_the_value(); ?>
				<input class="extra-link-autocomplete-taxonomy-hidden" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>"/><br>
				<?php $this->mb->the_field($this->get_prefixed_field_name("taxonomy-slug")); ?>
				<?php $taxonomy_slug = $this->mb->get_the_value(); ?>
				<input class="extra-link-autocomplete-taxonomy-slug-hidden" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>"/><br>

				<div class="extra-link-choice-taxonomy" <?php echo (empty($post_id) || !$show_content) ? 'style="display: none;"' : ''; ?>>
					<?php echo (!empty($taxonomy_slug) && !empty($taxonomy)) ? get_term_link($taxonomy_slug, $taxonomy) : ''; ?>
				</div>
			</div>

			<?php $this->mb->the_field($this->get_prefixed_field_name("title")); ?>
			<label class="extra-link-title-label" for="<?php $this->mb->the_name(); ?>_manual"><?php _e("Titre", "extra-admin"); ?></label>
			<input class="extra-link-title" id="<?php $this->mb->the_name(); ?>_manual" name="<?php $this->mb->the_name(); ?>" type="text" value="<?php $this->mb->the_value(); ?>" /><br>

			<?php $this->mb->the_field($this->get_prefixed_field_name("target")); ?>
			<input class="extra-link-target" id="<?php $this->mb->the_name(); ?>_manual" type="checkbox" name="<?php $this->mb->the_name(); ?>" value="1"<?php if ($this->mb->get_the_value()) echo ' checked="checked"'; ?>/>
			<label for="<?php $this->mb->the_name(); ?>_manual"><?php _e("Ouvrir le lien dans un nouvel onglet", "extra-admin"); ?></label>
		</div>
		<?php
	}

	static function extra_link_taxonomy_wp_ajax() {
		global $wpdb;

		$taxonomies = apply_filters('extra_metabox_link_available_taxonomies', get_taxonomies());
		$taxonomies = "'".implode("', '", $taxonomies)."'";

		$keyword = '%'.$_GET['term'].'%';

		$query = $wpdb->prepare("
			SELECT DISTINCT t.term_id, t.name, t.slug, tt.taxonomy
			FROM {$wpdb->terms} as t
			INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id
			WHERE t.name LIKE '%s'
			AND tt.count > 0
			AND tt.taxonomy IN (".$taxonomies.")
					ORDER BY t.term_id
			LIMIT 10
		", $keyword);

		$results = $wpdb->get_results($query);

		$data = array();
		foreach ($results as $result) {
			$taxonomy = get_taxonomy($result->taxonomy);

			$data[] = array(
				'term_id' => $result->term_id,
				'slug' => $result->slug,
				'name' => $result->name,
				'taxonomy' => $result->taxonomy,
				'taxonomy_name' => $taxonomy->labels->name,
				'url' => get_term_link($result->slug, $result->taxonomy)
			);
		}

		echo json_encode($data);
		die();
	}

	static function extra_link_wp_ajax() {
		global $wpdb;

		$types = get_post_types(array(
			'public' => true
		));
		if (array_key_exists('attachment', $types)) {
			unset($types['attachment']);
		}
		$post_types = "'".implode("', '", $types)."'";

		$keyword = '%'.$_GET['term'].'%';

		$query = $wpdb->prepare("
		SELECT DISTINCT p.ID as post_id, p.post_title, p.post_type
				FROM {$wpdb->posts} as p
				WHERE p.post_title LIKE '%s'
				AND p.post_status = 'publish'
				AND p.post_type IN (".$post_types.")
				ORDER BY p.ID
		LIMIT 10
	", $keyword);

		$results = $wpdb->get_results($query);

		$data = array();
		foreach ($results as $result) {
			$type = get_post_type_object($result->post_type);

			$data[] = array(
				'ID' => $result->post_id,
				'post_title' => html_entity_decode($result->post_title),
				'post_type' => $type->labels->singular_name,
				'url' => get_permalink($result->ID)
			);
		}

		echo json_encode($data);
		die();
	}

	public function the_admin_column_value() {
		//TODO
		echo '-';
	}

	public static function get_permalink($name, ExtraMetaBox $mb) {
		$type = $mb->get_the_value(AbstractField::get_field_name($name, 'type', '_'));
		$url = $mb->get_the_value(AbstractField::get_field_name($name, 'url', '_'));
		$content = $mb->get_the_value(AbstractField::get_field_name($name, 'content', '_'));

		if ($type == 'content') {
			return get_permalink($content);
		} if ($type == 'taxonomy') {
			return '#/coucou';
		} else {
			return $url;
		}

		//TODO MANAGE TAXONOMY PERMALINK
	}

	public static function get_title($name, ExtraMetaBox $mb) {
		return $mb->get_the_value(AbstractField::get_field_name($name, 'title', '_'));
	}

	public static function get_target($name, ExtraMetaBox $mb) {
		if($mb->get_the_value(AbstractField::get_field_name($name, 'target', '_'))) {
			return '_blank';
		} else {
			return '_self';
		}
	}

	public static function get_permalink_from_meta($meta, $name, $separator) {
		$type = isset($meta[$name.$separator.'type']) ? $meta[$name.$separator.'type'] : '';
		$url = isset($meta[$name.$separator.'url']) ? $meta[$name.$separator.'url'] : '';
		$content = isset($meta[$name.$separator.'content']) ? $meta[$name.$separator.'content'] : '';
		$taxonomy = isset($meta[$name.$separator.'taxonomy']) ? $meta[$name.$separator.'taxonomy'] : '';
		$taxonomy_slug = isset($meta[$name.$separator.'taxonomy-slug']) ? $meta[$name.$separator.'taxonomy-slug'] : '';

		if ($type == 'content') {
			return get_permalink($content);
		} else if ($type == 'taxonomy') {
			return get_term_link($taxonomy_slug, $taxonomy);
		} else {
			return $url;
		}
	}
	public static function get_target_from_meta($meta, $name, $separator) {
		if (isset($meta[$name.$separator.'target']) && $meta[$name.$separator.'target']) {
			return '_blank';
		} else {
			return '_self';
		}
	}
}

add_action('wp_ajax_extra-link', array('Link', 'extra_link_wp_ajax'));
add_action('wp_ajax_extra-link-taxonomy', array('Link', 'extra_link_taxonomy_wp_ajax'));