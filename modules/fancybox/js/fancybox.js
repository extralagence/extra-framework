$(document).ready(function () {
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
	var extraFancyboxDefaultOptions = {
			margin : 50,
			opacity : 'auto',

			// Should display toolbars
			infobar : true,
			buttons : true,
			smallBtn: false,

			// What buttons should appear in the toolbar
			slideShow  : false,
			fullScreen : false,
			thumbs     : false,
			closeBtn   : true,

			errorTpl : '<div class="fancybox-error"><p>Impossible de charger le contenu<br /> Veuillez réessayer ultérieurement.<p></div>'
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

		if($this.next().is('.wp-caption-text')) {
			$this.data('caption', $this.next().html());
		}
	});

	// OPTIONS EXTENDER
	if (window.extraFancyboxOverrideOptions) {
		$.extend(extraFancyboxDefaultOptions, window.extraFancyboxOverrideOptions);
	}

	// SETUP FANCYBOX
	$toShow.attr("data-fancybox", "gallery").addClass('extra-fancybox-processed').fancybox(extraFancyboxDefaultOptions);

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