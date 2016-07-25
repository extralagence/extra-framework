<?php
global $post;


$current = apply_filters('set_submenu_current', $post);

/**********************
 *
 *
 *
 * SUBMENU
 *
 *
 *
 *********************/

$has_no_children = false;
// SET PARENT
if ($current->post_parent !== 0){
	$ancestors = get_post_ancestors($current->ID);
	$parent = get_post($ancestors[count($ancestors)-1]);
} else {
	$parent = $current;

	$children = get_pages(
		array(
			'parent'     	=> $parent->ID,
			'sort_column'  	=> 'menu_order',
			'sort_order'	=> 'ASC'
		)
	);
	$has_no_children = empty($children);

}
$parent = apply_filters('set_submenu_parent', $parent);

// SELECTED
$selectedID = $current->ID;
$selectedID = apply_filters('set_submenu_selected', $selectedID);



function menu_page($id, $current_parent, $level = 1) {
	// SET ARGS
	$args = array(
		'parent'     	=> $current_parent->ID,
		'sort_column'  	=> 'menu_order',
		'sort_order'	=> 'ASC'
	);
	$children = get_pages($args);

	if (!empty($children)) {
		echo '<ul class="menu level'.$level.'">';
		foreach($children as $child) {

			if($child->post_parent == $current_parent->ID) {

				$selected = ($id == $child->ID) ? " current-page-item" : "";
				echo '<li class="page-item page-item-'.$child->ID.$selected.'">';
				if($id == $child->ID) {
					echo '<a href="'.get_permalink($child->ID).'">'.apply_filters('extra_submenu_current_title', $child->post_title, $child->ID).'</a>';
				} else {
					$child_title = apply_filters('extra_submenu_child_title', $child->post_title, $child->ID);
					echo '<a href="'.get_permalink($child->ID).'">'.$child_title.'</a>';
				}

				menu_page($id, $child, ($level+1));

				echo '</li>';
			}
		}
		echo '	</ul>';
	}
}
?>
<div class="menu-page<?php echo ($has_no_children) ? ' menu-page-empty' : ''; ?>">
	<?php
	$selected = ($current->ID == $parent->ID) ? " current-page-item" : "";
	$parent_page_template = get_post_meta($parent->ID, '_wp_page_template', true);
	$show_title = apply_filters('extra_submenu_show_parent_title', true);
	if ($show_title) {
		echo '<div class="menu-title page-item-'.$parent->ID.$selected.'">';
		if($parent_page_template == 'template-redirect.php') {
			echo '<span>'.apply_filters('extra_submenu_parent_title', $parent->post_title, $child->ID).'</span>';
		} else {
			echo '<a href="'.get_permalink($parent->ID).'">'.apply_filters('extra_submenu_parent_title', $parent->post_title, $child->ID).'</a>';
		}
		echo '</div>';
	}
	menu_page($selectedID, $parent);
	?>
</div>