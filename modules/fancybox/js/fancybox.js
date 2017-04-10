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
	var $metaTitle = $('meta[name="extra:fancybox_title"]'),
		extraFancyboxDefaultOptions = {
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

			errorTpl : '<div class="fancybox-error"><p>' + extra_fancybox_options.messages.error + '<p></div>',
			margin      : [140, 90, 120, 90],
			baseTpl     : 	'<div class="fancybox-container" role="dialog" tabindex="-1">' +
								'<div class="fancybox-bg"></div>' +
								'<div class="fancybox-controls">' +
									'<div class="fancybox-infobar__body">' +
										'<h2 class="extra-fancybox-title">' + $metaTitle.attr('content') + '</h2>' +
										'<div class="extra-fancybox-counter"><div class="inner"><div class="text">' + extra_fancybox_options.messages.count + '</div></div></div>' +
									'</div>' +
									'<div class="fancybox-buttons">' +
										'<button data-fancybox-previous class="extra-fancybox-nav extra-fancybox-prev"><span class="icon-arrow"></span></button>' +
										'<button data-fancybox-next class="extra-fancybox-nav extra-fancybox-next" ><span class="icon-arrow"></span></button>' +
										'<button data-fancybox-close class="extra-fancybox-nav close-button"><span class="icon-close"></span></button>' +
									'</div>' +
								'</div>' +
								'<div class="fancybox-slider-wrap">' +
									'<div class="fancybox-slider"></div>' +
								'</div>' +
								'<div class="fancybox-caption-wrap"><div class="fancybox-caption"></div></div>' +
							'</div>'
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

		var $caption = ($this.next().is('.wp-caption-text')) ? $this.next() : $this.find('.wp-caption-text');
		if($caption.length > 0) {
			$this.data('caption', $caption.html());
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