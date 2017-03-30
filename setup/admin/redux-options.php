<?php

if ( ! class_exists( "ReduxFramework" ) ) {
	return;
}

if ( ! class_exists( "Extra_Redux_Framework" ) ) {

	class Extra_Redux_Framework {

		public $args = array();
		public $sections = array();

		public function __construct() {

			// Set the default arguments
			$this->setArguments();

			// Create the sections and fields
			$this->setSections();

			new ReduxFramework( $this->sections, $this->args );

		}

		public function setSections() {

			$this->sections = array();

			// PREPENDED SECTIONS
			$this->sections = apply_filters( 'extra_default_global_options_section', $this->sections );

			// APPENDED SECTIONS
			$sections = apply_filters( 'extra_add_global_options_section', array() );

			if ( ! empty( $sections ) ) {
				foreach ( $sections as $section ) {
					$this->sections[] = $section;
				}
			}
		}


		// Create the sections and fields
		public function setArguments() {

			$this->args = array(

				'opt_name'            => 'extra_options',
				'display_name'        => 'Paramètres du site internet',
				'menu_type'           => 'menu',
				'allow_sub_menu'      => true,
				'menu_title'          => apply_filters('extra_global_options_name', 'Paramètres'),
				'page'                => apply_filters('extra_global_options_name', 'Paramètres'),
				'dev_mode'            => false,
				'forced_dev_mode_off' => false,
				'customizer'          => false,
				'admin_bar'           => false,

				'page_priority'    => 58,
				'page_parent'      => 'themes.php',
				'page_permissions' => 'publish_pages',
				'menu_icon'        => 'dashicons-admin-tools',
				'page_icon'        => 'icon-themes',
				'page_slug'        => '_options',
				'save_defaults'    => true,
				'default_show'     => false,
				'default_mark'     => '',

				'transient_time' => 60 * MINUTE_IN_SECONDS,
				'output'         => true,
				'output_tab'     => true,
				'domain'         => 'extra-admin',
				'footer_credit'  => '&nbsp;',

				'show_import_export' => false,
				'system_info'        => false,

				'help_tabs'    => null,
				'help_sidebar' => null
			);

		}

	}

	function init_global_options() {
		new Extra_Redux_Framework();
	}

	add_action( 'init', 'init_global_options' );

}