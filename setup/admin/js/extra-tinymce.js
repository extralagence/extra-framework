jQuery(document).ready(function ($) {
	// Too buggy to use in production. We'll try again later... maybe with gutenberg ?
	return;
	$('body').on('afterWpautop', function (event, obj) {
		var $div = $('<div></div>');
		$div.html(obj.data);
		$div.find('p > a:only-child > img').each(function () {
			var $img = $(this),
				$link = $img.parent('a');
			//link-image link-alignnone link-size-large extra-fancybox-processed
			// $link.removeClass('link-image link-size-thumbnail link-size-medium link-size-large link-size-full link-aligncenter link-alignright link-alignleft link-alignnone').addClass('link-image');
			$link.removeClass().addClass('link-image');
			var allclasses = $img[0].className.split(/\s+/);
			for (var classIndex in allclasses) {
				var cssclass = allclasses[classIndex];
				if (cssclass.indexOf('align') > -1) {
					$link.addClass('link-' + cssclass);
				}
				if (cssclass.indexOf('size') > -1) {
					$link.addClass('link-' + cssclass);
				}
			}
			// $link.parent('p').addClass('paragraph-link-image-wrapper');
		});
		$div.find('p > img').each(function () {
			if ($(this).parent().is('p')) {
				// $(this).unwrap('p');
				$(this).parent('p').addClass('paragraph-image-wrapper');
			}
		});
		obj.data = $div.html();
	});
});