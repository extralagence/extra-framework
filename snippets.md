# SNIPPETS
### Link button
Append svg element in link-button links :
```
add_filter('the_content', function ($content) {
	$content = preg_replace('/<a(.*?)class="(.*?link-button.*?)"(.*?)>(.*?)<\/a>/','<a$1class="$2"$3><svg class="icon"><use xlink:href="#icon-arrow"></use></svg>$4</a>',$content);

	return $content;
});
```
