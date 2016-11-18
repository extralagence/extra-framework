# SNIPPETS
### Link button
Append svg element in link-button links :
```
add_filter('the_content', function ($content) {
	$content = preg_replace('/<a(.*?)class="(.*?link-button.*?)"(.*?)>(.*?)<\/a>/','<a$1class="$2"$3><svg class="icon"><use xlink:href="#icon-arrow"></use></svg>$4</a>',$content);

	return $content;
});
```


### Redux
Classic options panel (pages, text, editor). Placed first.
```
add_filter( 'extra_default_global_options_section', function ( $sections ) {
	array_unshift( $sections, array(
		'icon'   => 'el-icon-file',
		'title'  => "Redux examples",
		'desc'   => null,
		'fields' => array(
			array(
				'id'    => 'page-example',
				'type'  => 'select',
				'title' => "Example page",
				'data'  => 'page'
			),
			array(
				'id' => 'text-example',
				'type' => 'text',
				'title' => "Text example"
			),
			array(
				'id' => 'editor-example',
				'type' => 'editor',
				'title' => "Editor example"
			)
		)
	) );

	return $sections;
}, 50 );
```

### Dedicated Redux panel
Redux panel with simplest possible configuration.

Can be placed under a custom post type menu for example.
```
<?php

if ( ! class_exists( "ReduxFramework" ) ) {
	return;
}

function extra_example__init_redux() {
	$args     = array(
		'opt_name'            => 'extra_example_options',
		'display_name'        => 'Paramètres',
		'menu_type'           => 'submenu',
		'allow_sub_menu'      => false,
		'menu_title'          => 'Paramètres',
		'page'                => 'Paramètres',
		'dev_mode'            => false,
		'forced_dev_mode_off' => false,
		'customizer'          => false,
		'admin_bar'           => false,
		'open_expanded'       => true,

		'page_priority'    => 10,
		'page_parent'      => 'edit.php?post_type=extra_example',
		'page_permissions' => 'publish_pages',
		'menu_icon'        => 'dashicons-admin-tools',
		'page_icon'        => 'icon-themes',
		'page_slug'        => '_extra_example_options',
		'save_defaults'    => true,
		'default_show'     => false,
		'default_mark'     => '',
		
		'transient_time' => 60 * MINUTE_IN_SECONDS,
		'output'         => true,
		'output_tab'     => true,
		'domain'         => 'extra',
		'footer_credit'  => '&nbsp;',

		'show_import_export' => false,
		'system_info'        => false,

		'help_tabs'    => null,
		'help_sidebar' => null
	);
	$sections = array(
		array(
			'icon'   => 'el-icon-file',
			'title'  => "Paramétres",
			'desc'   => null,
			'fields' => array(
				array(
					'id'    => 'page-example-archive',
					'type'  => 'select',
					'title' => "Page d'exemple",
					'data'  => 'page'
				)
			)
		)
	);

	new ReduxFramework( $sections, $args );
}

add_action( 'init', 'extra_example__init_redux' );
```
