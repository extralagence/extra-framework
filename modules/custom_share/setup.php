<?php
///////////////////////////////////////
//
//
// PUBLIC FUNCTION
//
//
///////////////////////////////////////
global $extra_sharer_counter;
$extra_sharer_counter = 0;
function extra_custom_share( $custom_url, $custom_page_title ) {

	global $post,
	       $extra_options,
	       $extra_sharer_counter,
	       $extra_contact_form_printed;

	// Available tags
	// %url%
	// %sitetitle%
	// %pagetitle%

	// Setup all services
	$extra_share_services             = array();
	$extra_share_services['facebook'] = array(
		'tag' => '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=%s%" class="extra-social-button extra-social-facebook">
			<svg viewBox="0 0 20 20" class="icon"><use xlink:href="#extra-social-facebook"></use></svg>
			<span class="text">' . __( 'Partager sur Facebook', 'extra' ) . '</span><span class="counter"></span>
		</a>',
		'url' => '%url%'
	);
	$extra_share_services['twitter']  = array(
		'tag' => '<a target="_blank" href="https://twitter.com/home?status=%s%" class="extra-social-button extra-social-twitter">
			<svg viewBox="0 0 20 20" class="icon"><use xlink:href="#extra-social-twitter"></use></svg>
			<span class="text">' . __( 'Partager sur Twitter', 'extra' ) . '</span><span class="counter"></span>
		</a>',
		'url' => __( 'Lire l\'article intitulé %pagetitle% sur %sitetitle% : %url%', 'extra' )
	);

	// Add a hook on default services
	$extra_share_services = apply_filters( 'extra_custom_share__services', $extra_share_services );

	// URL
	$url = false;
	if ( empty( $custom_url ) ) {
		if ( ! empty( $post ) && ! empty( $post->ID ) ) {
			$url = get_permalink( $post->ID );
		}
	} else {
		$url = $custom_url;
	}
	$url = apply_filters( 'extra_custom_share__url', $url );

	if ( empty( $url ) ) {
		return;
	}

	// Page title
	if ( ! empty( $custom_page_title ) ) {
		$page_title = $custom_page_title;
	} else {
		$page_title = get_the_title();
	}
	$page_title = apply_filters( 'extra_custom_share__page_title', $page_title );

	// Site title
	$site_title = apply_filters( 'extra_custom_share__site_title', get_bloginfo( 'name' ) );


	// Start echoing
	echo '<div class="extra-social-wrapper">';


	foreach ( $extra_share_services as $service ) {
		$service_url = $service['url'];
		$service_url = str_replace( '%url%', $url, $service_url );
		$service_url = str_replace( '%pagetitle%', $page_title, $service_url );
		$service_url = str_replace( '%sitetitle%', $site_title, $service_url );

		$service_tag = $service['tag'];
		$service_tag = str_replace( '%url%', $url, $service_tag );
		$service_tag = str_replace( '%pagetitle%', $page_title, $service_tag );
		$service_tag = str_replace( '%sitetitle%', $site_title, $service_tag );
		$service_tag = str_replace( '%s%', urlencode( $service_url ), $service_tag );
		if ( ! empty( $service_tag ) ) {
			echo $service_tag;
		}
	}


	// Share form
	// Make this unique (for fancybox mainly)
	$extra_sharer_counter ++;
	if ( array_key_exists( 'share-form-id', $extra_options ) || array_key_exists( 'contact-form-select', $extra_options ) ) {

		$form_id = array_key_exists( 'share-form-id', $extra_options ) ? $extra_options['share-form-id'] : $extra_options['contact-form-select'];

		$email = '
			<a href="#extra-social-share-wrapper" class="extra-social-button extra-social-share">
				<svg viewBox="0 0 20 20" class="icon"><use xlink:href="#extra-social-mail"></use></svg>
				<span class="text">' . __( 'Partager par email', 'extra' ) . '</span>
			</a>
			';
		$email = apply_filters( 'extra_social_share_link', $email, $extra_sharer_counter );
		echo $email;

		if ( ! isset( $extra_contact_form_printed ) ) {

			$shortcode = '[contact-form-7 id="' . $form_id . '"]';
			$shortcode = apply_filters( 'extra_custom_share__share_shortcode', $shortcode, $form_id );

			$email_popup                = '<div class="js-custom-share-hidden">
					<div class="extra-form extra-social-share-wrapper" id="extra-social-share-wrapper">
					<h3>' . __( 'Partager par email', 'extra' ) . '</h3>
					' . do_shortcode( $shortcode ) . '
					</div>
				</div>';
			$email_popup                = apply_filters( 'extra_social_share_popup', $email_popup, $form_id );
			$extra_contact_form_printed = true;
			echo $email_popup;
		}
	}

	echo '</div>';
}

///////////////////////////////////////
//
//
// ASSETS
//
//
///////////////////////////////////////
function extra_custom_social_enqueue_assets() {
	$extra_enabled_custom_share = apply_filters( 'extra_enabled_custom_share', true );
	if ( ! $extra_enabled_custom_share ) {
		return;
	}
	wp_enqueue_style( 'extra-custom-share', EXTRA_MODULES_URI . '/custom_share/css/custom_share.less', array(), EXTRA_VERSION, 'all' );
	wp_enqueue_script( 'extra-custom-share', EXTRA_MODULES_URI . '/custom_share/js/custom_share.js', array( 'extra' ), EXTRA_VERSION, true );
	wp_localize_script( 'extra-custom-share', 'extra_custom_share_params', array(
		'assets_uri' => EXTRA_MODULES_URI . '/custom_share/'
	) );
}

add_action( 'wp_enqueue_scripts', 'extra_custom_social_enqueue_assets' );
///////////////////////////////////////
//
//
// REDUX OPTION
//
//
///////////////////////////////////////
function extra_custom_share_add_global_options_section( $sections ) {
	$extra_enabled_custom_share = apply_filters( 'extra_enabled_custom_share', true );
	if ( ! $extra_enabled_custom_share ) {
		return $sections;
	}
	// PAGES
	$sections[] = array(
		'icon'   => 'el-icon-share',
		'title'  => __( 'Formulaire de partage', 'extra-admin' ),
		'desc'   => null,
		'fields' => array(
			array(
				'id'    => 'share-form-id',
				'type'  => 'select',
				'title' => __( 'Formulaire de partage', 'extra-admin' ),
				'data'  => 'post',
				'args'  => array( 'post_type' => array( 'wpcf7_contact_form' ), 'posts_per_page' => - 1 ),
			)
		)
	);

	return $sections;
}

add_filter( 'extra_add_global_options_section', 'extra_custom_share_add_global_options_section' );
///////////////////////////////////////
//
//
// SEND MAIL OPTION
//
//
///////////////////////////////////////
function extra_share_contact_wpcf7_before_send_mail( $cf7 ) {
	global $extra_options;

	if ( ! array_key_exists( 'share-form-id', $extra_options ) && ! array_key_exists( 'contact-form-select', $extra_options ) ) {
		return;
	}

	$form_id = array_key_exists( 'share-form-id', $extra_options ) ? $extra_options['share-form-id'] : $extra_options['contact-form-select'];

	if ( intval( $cf7->id() ) !== $form_id || ! isset( $cf7->posted_data['sender'] ) || ! isset( $cf7->posted_data['username'] ) ) {
		return;
	}
	$sender       = $cf7->posted_data['sender'];
	$sender_array = explode( '@', $sender );

	$username       = $sender_array[0];
	$username       = preg_replace( '/[^a-zA-Z0-9]+/', ' ', $username );
	$username_array = explode( ' ', $username );
	$username_array = array_map( function ( $word ) {
		return ucfirst( $word );
	}, $username_array );
	$username       = implode( ' ', $username_array );

	$cf7->posted_data['username'] = $username;
}

add_action( 'wpcf7_before_send_mail', 'extra_share_contact_wpcf7_before_send_mail' );
///////////////////////////////////////
//
//
// DEFAULT MESSAGE
//
//
///////////////////////////////////////
function extra_share_contact_wpcf7_form_tag( $tags ) {

	if ( is_admin() ) {
		return $tags;
	}

	global $post;

	/*if ( $tags['name'] == 'share_message' ) {
		$post_title     = ( $post !== null ) ? $post->post_title : '';
		$post_title     = str_replace( '<br>', ' ', $post_title );
		$post_title     = str_replace( '&nbsp;', ' ', $post_title );
		$post_id        = ( $post !== null ) ? $post->ID : 0;
		$value          = __( "Bonjour,", 'extra' ) . "\n\n" . __( "Je vous invite à aller voir ", 'extra' ) . ( $post_title ) . "\n" . get_permalink( $post_id ) . "\n\n" . __( "Bien cordialement.", 'extra' );
		$tags['values'] = array( apply_filters( 'extra_custom_share__default_message', $value, $post_title, $post_id ) );
	}*/

	if ( $tags['name'] == 'sender' ) {
		if ( is_user_logged_in() ) {
			$logged_user    = wp_get_current_user();
			$tags['values'] = array( $logged_user->user_email );
		}
	}

	return $tags;
}

add_action( 'wpcf7_form_tag', 'extra_share_contact_wpcf7_form_tag' );
function extra_share_contact_filter_wordpress_name( $from_name ) {
	if ( 'WordPress' == $from_name ) {
		$from_name = get_bloginfo( 'name' );
	}

	return $from_name;
}

add_filter( 'wp_mail_from_name', 'extra_share_contact_filter_wordpress_name' );


add_action( 'init', function () {
	// Trigger deprecated for custom share
	global $extra_options;
	if ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) {
		if ( array_key_exists( 'contact-form-select', $extra_options ) ) {
			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since version %2$s! Use %3$s instead.' ), 'contact-form-select in $extra_options', '0.3.0', 'share-form-id' ) );
		}
	}
}, 99 );