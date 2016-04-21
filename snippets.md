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
