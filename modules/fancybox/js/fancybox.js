$(document).ready(function () {

	// SETUP FANCYBOX TITLE
	$(document).on('afterLoad', function () {
		if ($.fancybox.coming.element.next(".wp-caption-text").length) {
			$.fancybox.coming.title = $.fancybox.coming.element.next(".wp-caption-text").html();
		} else {
			$.fancybox.coming.title = '';
		}
	});

	extraInitFancybox($("body"));
});
//extra.initFancybox
$window.on('extra:fancybox:init', function (event, $parent) {

	if ($parent && $parent.length) {
		extraInitFancybox($parent);
	}
});
function extraInitFancybox($parent) {


	// DEFAULT OPTIONS
	var extraFacyboxDefaultOptions = {
			margin : 50,
			padding: 0,
			// type   : 'image',
			helpers: {
				title: {
					type: 'over'
				},
				media: {}
			}
		},


		// GET ALL ELEMENTS
		$toShow = $parent.find("a[href$='.jpg'], a[href$='.jpeg'], a[href$='.png'], a[href$='.gif'], a[href$='.svg'], .fancybox").not('.no-fancybox').not('.extra-fancybox-processed').filter(function () {
			return $(this).attr("target") != "_blank";
		}),

		// STORE UNIQUE VALUES
		uniques = {},
		duplicates = [];


	// REMOVE DUPLICATES
	$toShow.each(function () {
		var $this = $(this);
		if (uniques[$this.attr('href')]) {
			duplicates.push(this);
			$toShow = $toShow.not($this);
		}
		else {
			uniques[$this.attr('href')] = true;
		}
	});

	// OPTIONS EXTENDER
	if (window.extraFancyboxOverrideOptions) {
		$.extend(extraFacyboxDefaultOptions, window.extraFancyboxOverrideOptions);
	}

	// SETUP FANCYBOX
	$toShow.attr("data-fancybox-group", "gallery").addClass('extra-fancybox-processed').fancybox(extraFacyboxDefaultOptions);

	if (duplicates.length) {
		$(duplicates).each(function () {
			var $duplicate = $(this);
			$duplicate.on("click", function (event) {
				event.preventDefault();
				$toShow.filter(function (index, element) {
					return $duplicate.attr('href') == $(element).attr('href');
				}).trigger("click");
			});
		});
	}
}