<?php
/*
 * Extra Breadcrumb
 *
 * (c) 2013 Jean-Baptiste Janot / Extra l'agence
 *
 */
// GET POST GLOBAL
global $post;

$current_post = apply_filters('extra_arianne_current_post', $post);
?>
<div id="arianne">

	<div class="inner">

	<?php

	// if $current_post variable exists
	if(isset($current_post) && !empty($current_post)) {

		$home = array(
			'class' => 'home',
			'name'  => __("Accueil", "extra"),
			'link'  => home_url("/"),
		);

		$parents = array();

		$current_item = array(
			'class' => 'current',
			'name'  => '',
			'link'  => null,
		);


		// CATEGORY
		if (is_category()) {
			$current_category = get_category(get_query_var('cat'), false);
			if ($current_category->parent != 0) {
				$ancestors_ids = get_ancestors($current_category->ID, 'category');
				$ancestors_ids = array_reverse($ancestors_ids);

				foreach ($ancestors_ids as $ancestor_id) {
					$ancestor_category = get_category($ancestor_id, false);
					$parents[] = array(
						'class' => '',
						'name'  => $ancestor_category->name,
						'link'  => get_category_link($ancestor_category->term_id),
					);
				}
			}

			$homeID = get_option("page_for_posts");
			$home_post = get_post($homeID);
			$parents[] = array(
				'class' => '',
				'name'  => $home_post->post_title,
				'link'  => get_permalink($homeID),
			);

			$current_item['name'] = sprintf(__('Archive de la catégorie "%s"', 'extra'), single_cat_title('', false));
		}

		// SEARCH
		else if (is_search()) {
			$current_item['name'] = sprintf(__('Résultats pour la recherche "%s"', 'extra'), get_search_query());
		}

		// TIME - DAY
		else if (is_day()) {
			if (get_option("page_for_posts") != 0) {
				$homeID = get_option("page_for_posts");
				$home_post = get_post($homeID);
				$parents[] = array(
					'class' => '',
					'name'  => $home_post->post_title,
					'link'  => get_permalink($homeID),
				);
			}
			$parents[] = array(
				'class' => '',
				'name'  => get_the_time('Y'),
				'link'  => get_year_link(get_the_time('Y')),
			);
			$parents[] = array(
				'class' => '',
				'name'  => get_the_time('F'),
				'link'  => get_month_link(get_the_time('Y'), get_the_time('m')),
			);
			$current_item['name'] = get_the_time('d');
		}

		// TIME - MONTH
		else if (is_month()) {
			if (get_option("page_for_posts") != 0) {
				$homeID = get_option("page_for_posts");
				$home_post = get_post($homeID);
				$parents[] = array(
					'class' => '',
					'name'  => $home_post->post_title,
					'link'  => get_permalink($homeID),
				);
			}
			$parents[] = array(
				'class' => '',
				'name'  => get_the_time('Y'),
				'link'  => get_year_link(get_the_time('Y')),
			);
			$current_item['name'] = get_the_time('F');
		}

		// TIME - YEAR
		else if (is_year()) {
			if (get_option("page_for_posts") != 0) {
				$homeID = get_option("page_for_posts");
				$home_post = get_post($homeID);
				$parents[] = array(
					'class' => '',
					'name'  => $home_post->post_title,
					'link'  => get_permalink($homeID),
				);
			}
			$current_item['name'] = get_the_time('Y');
		}

		// NEWS HOME
		else if(is_home()) {
			$homeID = get_option("page_for_posts");
			$home_post = get_post($homeID);
			$current_item['name'] = ($home_post !== null) ? $home_post->post_title : '';
		}

		// SINGLE, NOT ATTACHMENT
		else if (is_single() && !is_attachment()) {

			// CUSTOM POST TYPE
			if (get_post_type() != 'post') {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				$parents[] = array(
					'class' => '',
					'name'  => $post_type->labels->singular_name,
					'link'  => home_url("/").'/'.$slug['slug'],
				);
				$current_item['name'] = get_the_title();
			}
			// POST
			else {
				if (get_option("page_for_posts") != 0) {
					$homeID = get_option("page_for_posts");
					$home_post = get_post($homeID);
					$parents[] = array(
						'class' => '',
						'name'  => $home_post->post_title,
						'link'  => get_permalink($homeID),
					);
				}
				$current_item['name'] = get_the_title();
			}
		}

		// CUSTOM POST TYPE
		else if (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			$current_item['name'] = $post_type->labels->singular_name;
		}

		// ATTACHMENT
		elseif (is_attachment()) {
			$parent = get_post($current_post->post_parent);
			$cat = get_the_category($parent->ID);
			$cat = $cat[0];

			$ancestors_ids = get_ancestors($cat, 'category');
			$ancestors_ids = array_reverse($ancestors_ids);
			$ancestors_ids[] = $cat;

			foreach ($ancestors_ids as $ancestor_id) {
				$ancestor_category = get_category($ancestor_id, false);
				$parents[] = array(
					'class' => '',
					'name'  => $ancestor_category->name,
					'link'  => get_category_link($ancestor_category->term_id),
				);
			}

			$parents[] = array(
				'class' => '',
				'name'  => $parent->post_title,
				'link'  => get_permalink($parent),
			);
			$current_item['name'] = get_the_title();
		}

		// TOP LEVEL PAGE
		elseif ( is_page() && !$current_post->post_parent ) {
			$current_item['name'] = get_the_title();
		}

		// PAGE WITH ANCESTOR
		else if(is_page()){
			$ancestors_ids = get_ancestors($current_post-> ID, 'page');
			$ancestors_ids = array_reverse($ancestors_ids);
			foreach ($ancestors_ids as $ancestor_id) {
				$parents[] = array(
					'class' => '',
					'name'  => get_the_title($ancestor_id),
					'link'  => get_permalink($ancestor_id),
				);
			}
			$current_item['name'] = get_the_title();
		}

		// TAG
		else if(is_tag()) {
			$current_item['name'] = sprintf(__('Actualités correspondant au tag %s', 'extra'), single_tag_title('', false));
		}


		// AUTHOR
		else if(is_author()) {
			global $author;
			$userdata = get_userdata($author);
			$current_item['name'] = sprintf(__('Actualités rédigées par %s', 'extra'), $userdata->display_name);
		}


		// 404
		else if (is_404()) {
			$current_item['name'] = __('Erreur 404', 'extra');
		}

		// PAGINATE
		if(get_query_var('paged')) {
			$name = ' ';
			if(is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				$name .= ' (';
			}
			$name .=  __('Page', 'extra').' '.get_query_var('paged');
			if(is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				$name .=  ')';
			}

			$current_item['name'] .= $name;
		}

		$home = apply_filters('extra_arianne_home', $home);

		$parents = apply_filters('extra_arianne_parents', $parents);

		$current_item = apply_filters('extra_arianne_current', $current_item);

		$delimiter = apply_filters('extra_arianne_delimiter', '');

		$breadcrumbs = array();
		$breadcrumbs[] = $home;
		$breadcrumbs = array_merge($breadcrumbs, $parents);
		$breadcrumbs[] = $current_item;

		$first = true;

		foreach ($breadcrumbs as $breadcrumb) {
			if (!empty($breadcrumb['name'])) {
				if ($first) {
					$first = false;
				} else {
					echo $delimiter;
				}
				$class = (!empty($breadcrumb['class']) ? ' class="'.$breadcrumb['class'].'"' : '');

				if (!empty($breadcrumb['link'])) {
					echo '<a '. $class .' href="'.$breadcrumb['link'].'">';
				} else {
					echo '<span'. $class .'>';
				}

				echo $breadcrumb['name'];

				if (!empty($breadcrumb['link'])) {
					echo '</a>';
				} else {
					echo '</span>';
				}
			}
		}
	}

	?>

	</div><!-- .inner -->

</div><!-- #arianne -->