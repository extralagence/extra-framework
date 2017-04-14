<?php
/**********************
 *
 *
 *
 * REMOVE POST LIMIT FOR SEARCH
 *
 *
 *
 *********************/
if ( ! function_exists( 'extra_post_limits' ) ) {
	add_filter( 'post_limits', 'extra_post_limits' );
	function extra_post_limits( $limits ) {
		if ( is_search() ) {
			global $wp_query;
			$wp_query->query_vars['posts_per_page'] = - 1;
		}

		return $limits;
	}
}
///////////////////////////////////////
//
//
// DEFAULT SEARCH FORM
//
//
///////////////////////////////////////
if ( ! function_exists( 'extra_search_form' ) ) {
	function extra_search_form( $form ) {
		$form = '
    	<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    		<label for="s">' . __( "Une recherche ?", "extra" ) . '</label>
    		<input type="text" value="' . get_search_query() . '" name="s" id="s" />
    		<button type="submit" id="searchsubmit"><span class="icon icon-search"></span><span class="text">' . __( 'Valider', 'extra' ) . '</span></button>
    	</form>
    	';

		return $form;
	}
}
add_filter( 'get_search_form', 'extra_search_form' );