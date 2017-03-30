<?php
///////////////////////////////////////
//
//
// COOKIES ASSETS
//
//
///////////////////////////////////////
function extra_cookies_assets() {

	// Make sure the theme allows the cookie module
	if ( ! apply_filters( 'extra_enabled_extra_cookies', false ) ) {
		return;
	}
	wp_enqueue_style( 'extra.cookies', EXTRA_MODULES_URI . '/extra.cookies/lib/css/extra.cookies.less', array(), EXTRA_VERSION, 'all' );
	wp_enqueue_script( 'js.cookie', EXTRA_MODULES_URI . '/extra.cookies/lib/js/js.cookie.js', array(), EXTRA_VERSION, true );
	wp_enqueue_script( 'extra.cookies', EXTRA_MODULES_URI . '/extra.cookies/lib/js/extra.cookies.js', array(
		'jquery',
		'js.cookie'
	), EXTRA_VERSION, true );


	////////////////// DEFAULT USAGE //////////////////

	// Make sure the theme allows the default cookie behaviour (strip to accept cookie usage)
	if ( ! apply_filters( 'extra_enabled_extra_cookies_default', true ) ) {
		return;
	}

	global $extra_options;

	wp_enqueue_script( 'extra.cookies.default', EXTRA_MODULES_URI . '/extra.cookies/front/js/extra.cookies.default.js', array(
		'jquery',
		'js.cookie',
		'extra.cookies'
	), EXTRA_VERSION, true );
	wp_localize_script( 'extra.cookies.default', 'extraCookiesParams', array(
		'delay'      => $extra_options['cookies-default-delay'] * 1000,
		'expiration' => $extra_options['cookies-default-expiration'],
		'position'   => $extra_options['cookies-default-position']
	) );
}

add_action( 'wp_enqueue_scripts', 'extra_cookies_assets' );
///////////////////////////////////////
//
//
// COOKIES INSERT IN FOOTER
//
//
///////////////////////////////////////
add_action( 'wp_footer', function () {

	// Make sure the theme allows the cookie module, AND it allows the default cookie behaviour (strip to accept cookie usage)
	if ( ! apply_filters( 'extra_enabled_extra_cookies', false ) && ! apply_filters( 'extra_enabled_extra_cookies_default', true ) ) {
		return;
	}

	global $extra_options;

	echo '<div class="extra-cookies-popup"><div class="extra-cookies-popup-inner">';
	echo '<div class="extra-cookies-text">' . nl2br( $extra_options['cookies-default-content'] ) . '</div>';
	echo '<button type="button" class="extra-cookies-button">' . $extra_options['cookies-default-button-text'] . '</button>';
	echo ' </div ></div > ';

} );
///////////////////////////////////////
//
//
// COOKIES OPTIONS PANEL
//
//
///////////////////////////////////////
add_filter( 'extra_default_global_options_section', function ( $sections ) {

	// Make sure the theme allows the cookie module, AND it allows the default cookie behaviour (strip to accept cookie usage)
	if ( ! apply_filters( 'extra_enabled_extra_cookies', false ) && ! apply_filters( 'extra_enabled_extra_cookies_default', true ) ) {
		return $sections;
	}
	$sections[] = array(
		'icon'   => 'el-icon-certificate',
		'title'  => "Bandeau de cookies",
		'desc'   => null,
		'fields' => array(
			array(
				'id'      => 'cookies-default-content',
				'type'    => 'editor',
				'title'   => "Contenu du bandeau de cookies",
				'default' => __( "En poursuivant votre navigation sur ce site, vous acceptez l’utilisation de cookies.", 'extra' )
			),
			array(
				'id'      => 'cookies-default-button-text',
				'type'    => 'text',
				'title'   => "Texte du bouton",
				'default' => __( "Valider", 'extra' )
			),
			array(
				'id'      => 'cookies-default-position',
				'type'    => 'select',
				'title'   => "Position",
				'default' => 'bottom',
				'options' => array(
					'top'    => 'Haut de page',
					'bottom' => 'Bas de page'
				),
			),
			array(
				'id'       => 'cookies-default-expiration',
				'type'     => 'text',
				'validate' => 'numeric',
				'title'    => "Durée de validité",
				'default'  => 60
			),
			array(
				'id'       => 'cookies-default-delay',
				'type'     => 'text',
				'validate' => 'numeric',
				'title'    => "Délai d'apparition en secondes",
				'default'  => 1
			)
		)
	);

	return $sections;
}, 50 );