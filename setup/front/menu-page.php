<?php
global $post;
///////////////////////////////////////
//
//
// CURRENTS
//
//
///////////////////////////////////////
$current_post = apply_filters( 'set_submenu_current', $post );
///////////////////////////////////////
//
//
//
//
//
///////////////////////////////////////
$has_no_children = false;
///////////////////////////////////////
//
//
// HAS PARENTS
//
//
///////////////////////////////////////
$current_ancestors = array();
if ( $current_post->post_parent !== 0 ) {
	$current_ancestors = get_post_ancestors( $current_post->ID );
	$top_parent        = get_post( $current_ancestors[count( $current_ancestors ) - 1] );
}
///////////////////////////////////////
//
//
// HAS NO PARENTS
//
//
///////////////////////////////////////
else {
	$top_parent      = $current_post;
	$children        = get_pages(
		array(
			'parent'      => $top_parent->ID,
			'sort_column' => 'menu_order',
			'sort_order'  => 'ASC'
		)
	);
	$has_no_children = empty( $children );
}
///////////////////////////////////////
//
//
// FILTERS
//
//
///////////////////////////////////////
$top_parent      = apply_filters( 'set_submenu_parent', $top_parent );
$current_post_id = $current_post->ID;
$current_post_id = apply_filters( 'set_submenu_selected', $current_post_id );
///////////////////////////////////////
//
//
// MENU PAGE FUNCTION
//
//
///////////////////////////////////////
function menu_page( $id, $parent, $level = 1, $ancestors = array() ) {
	// SET ARGS
	$args     = array(
		'parent'      => $parent->ID,
		'sort_column' => 'menu_order',
		'sort_order'  => 'ASC'
	);
	$children = get_pages( $args );

	if ( !empty( $children ) ) {
		echo '<ul class="menu level' . $level . '">';
		foreach ( $children as $child ) {

			if ( $child->post_parent == $parent->ID ) {
				$selected = '';
				$selected .= ( $id == $child->ID ) ? " current-page-item" : "";
				if ( in_array( $child->ID, $ancestors ) ) {
					$selected .= " parent-page-item";
				}
				$sub_children = get_pages( array( 'parent' => $child->ID ) );
				if ( !empty( $sub_children ) ) {
					$selected .= " menu-has-children";
				}
				$selected .= ( $id == $child->ID ) ? " current-page-item" : "";


				echo '<li class="page-item page-item-' . $child->ID . $selected . '">';
				if ( $id == $child->ID ) {
					echo '<a href="' . get_permalink( $child->ID ) . '">' . apply_filters( 'extra_submenu_current_title', $child->post_title, $child->ID ) . '</a>';
				} else {
					$child_title = apply_filters( 'extra_submenu_child_title', $child->post_title, $child->ID );
					echo '<a href="' . get_permalink( $child->ID ) . '">' . $child_title . '</a>';
				}

				menu_page( $id, $child, ( $level + 1 ), $ancestors );

				echo '</li>';
			}
		}
		echo '	</ul>';
	}
}

?>
<div class="menu-page<?php echo ( $has_no_children ) ? ' menu-page-empty' : ''; ?>">
	<?php
	$selected             = ( $current_post->ID == $top_parent->ID ) ? " current-page-item" : "";
	$parent_page_template = get_post_meta( $top_parent->ID, '_wp_page_template', true );
	$show_title           = apply_filters( 'extra_submenu_show_parent_title', true );
	if ( $show_title ) {
		echo '<div class="menu-title page-item-' . $top_parent->ID . $selected . '">';
		if ( $parent_page_template == 'template-redirect.php' ) {
			echo '<span>' . apply_filters( 'extra_submenu_parent_title', $top_parent->post_title, $top_parent->ID ) . '</span>';
		} else {
			echo '<a href="' . get_permalink( $top_parent->ID ) . '">' . apply_filters( 'extra_submenu_parent_title', $top_parent->post_title, $top_parent->ID ) . '</a>';
		}
		echo '</div>';
	}
	menu_page( $current_post_id, $top_parent, 0, $current_ancestors );
	?>
</div>