<?php
/**********************
 *
 *
 * PUBLIC FUNCTION
 *
 *
 *********************/
global $extra_sharer_counter;
$extra_sharer_counter = 0;
function extra_custom_share($id = 0) {

	global  $post,
			$extra_options,
			$extra_sharer_counter;

	if ( $id == 0 && isset($post->ID)) {
		$id = $post->ID;
	}

	if(!isset($id)) {
		return;
	}

	$extra_sharer_counter++;

	$title = get_the_title();
	$blog_title = get_bloginfo('name');
	$link = get_permalink( $id );

	// IF LINK, ECHO SHARE
	if ( ! empty( $link ) ) {
		$return = file_get_contents(EXTRA_MODULES_PATH.'/custom_share/img/sprite.svg');
		$return .= '
		<div class="extra-social-wrapper">
			<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $link . '" class="extra-social-button extra-social-facebook" data-url="' . $link . '" data-counter="https://graph.facebook.com/?ids=' . urlencode($link) . '">
				<svg viewBox="0 0 20 20" class="icon"><use xlink:href="#extra-social-facebook"></use></svg>
				<span class="text">' . __( 'Partager sur Facebook', 'extra' ) . '</span><span class="counter"></span>
			</a>
			<a target="_blank" href="https://twitter.com/home?status=' . utf8_uri_encode(sprintf(__('Lire l\'article intitulé %s sur %s : %s', 'extra'), $title, $blog_title, $link)) . '" class="extra-social-button extra-social-twitter" data-url="' . $link . '" data-counter="http://cdn.api.twitter.com/1/urls/count.json?url=' . urlencode($link) . '&amp;callback=?">
				<svg viewBox="0 0 20 20" class="icon"><use xlink:href="#extra-social-twitter"></use></svg>
				<span class="text">' . __( 'Partager sur Twitter', 'extra' ) . '</span><span class="counter"></span>
			</a>
			<a target="_blank" href="https://plus.google.com/share?url=' . $link . '" class="extra-social-button extra-social-gplus" data-url="' . $link . '" data-counter="' . EXTRA_MODULES_URI . '/custom_share/gplus.php?url= ' . urlencode($link) . '">
				<svg viewBox="0 0 20 20" class="icon"><use xlink:href="#extra-social-google"></use></svg>
				<span class="text">' . __( 'Partager sur Google+', 'extra' ) . '</span><span class="counter"></span>
			</a>';
		if(array_key_exists('contact-form-select', $extra_options)) {
			$return .= '
			<a href="#extra-social-share-' . $extra_sharer_counter . '-wrapper" class="extra-social-button extra-social-share">
				<svg viewBox="0 0 20 20" class="icon"><use xlink:href="#extra-social-mail"></use></svg>
				<span class="text">' . __( 'Partager par email', 'extra' ) . '</span>
			</a>
			<div class="js-custom-share-hidden">
				<div class="extra-social-share-wrapper" id="extra-social-share-' . $extra_sharer_counter . '-wrapper">
				<h3>' . __( 'Partager par email', 'extra' ) . '</h3>
				' . do_shortcode( '[contact-form-7 id="' . $extra_options['contact-form-select'] . '"]' ) . '
				</div>
			</div>';
		}
		$return .= '</div>';
		echo $return;
	}
}
/**********************
 *
 *
 * ENQUEUE ASSETS
 *
 *
 *********************/
function extra_custom_social_enqueue_assets() {
	wp_enqueue_style('extra-custom-share', EXTRA_MODULES_URI.'/custom_share/css/custom_share.less');
	wp_enqueue_script('extra-custom-share', EXTRA_MODULES_URI.'/custom_share/js/custom_share.js', array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'extra_custom_social_enqueue_assets');
/**********************
 *
 *
 *
 * SOCIAL  SECTION
 *
 *
 *
 *********************/
function extra_custom_share_add_global_options_section($sections) {
	// PAGES
	$sections[] = array(
		'icon'   => 'el-icon-share',
		'title'  => __( 'Gestion du formulaire de contact', 'extra-admin' ),
		'desc'   => null,
		'fields' => array(
			array(
				'id'    => 'contact-form-select',
				'type'  => 'select',
				'title' => __( 'Formulaire de contact', 'extra-admin' ),
				'data' => 'post',
				'args'  => array('post_type' => array('wpcf7_contact_form')),
			)
		)
	);
	return $sections;
}
add_filter('extra_add_global_options_section', 'extra_custom_share_add_global_options_section');
/**********************
 *
 *
 *
 * SHARE CONTACT FORM
 *
 *
 *
 *********************/
function extra_share_contact_wpcf7_before_send_mail($cf7){
	$sender = $cf7->posted_data['sender'];
	$sender_array = explode('@', $sender);

	$username = $sender_array[0];
	$username = preg_replace('/[^a-zA-Z0-9]+/', ' ', $username);
	$username_array = explode(' ', $username);
	$username_array = array_map(ucfirst, $username_array);
	$username = implode(' ', $username_array);

	$cf7->posted_data['username'] = $username;
}
add_action('wpcf7_before_send_mail', 'extra_share_contact_wpcf7_before_send_mail');
/**********************
 *
 *
 *
 * DEFAULT MESSAGE
 *
 *
 *
 *********************/
function extra_share_contact_wpcf7_form_tag($tags) {

	if(is_admin()) {
		return $tags;
	}

	global $post;

	if($tags['name'] == 'share_message') {
		$tags['values'] = array(
			__('Bonjour,', 'extra').'

		'.
			__('Je vous invite à aller voir ', 'extra').$post->post_title.'
		'
			.get_permalink($post->ID).'
		'.

			__('Bien cordialement.', 'extra')
		);
	}
	return $tags;
}
add_action('wpcf7_form_tag', 'extra_share_contact_wpcf7_form_tag');
function extra_share_contact_filter_wordpress_name ($from_name) {
	if ('WordPress' == $from_name) {
		$from_name = get_bloginfo('name');
	}

	return $from_name;
}
add_filter('wp_mail_from_name', 'extra_share_contact_filter_wordpress_name');

