$(document).ready(function () {

	// AJAX SVG SPRITE
	$.get(extra_custom_share_params.assets_uri + "img/sprite.svg", function (data) {
		var div = document.createElement("div");
		div.innerHTML = new XMLSerializer().serializeToString(data.documentElement);
		document.body.insertBefore(div, document.body.childNodes[0]);
		$("html").addClass("extra-custom-share-svg-loaded");
	});

	extraInitShare($('body'));

});
function extraInitShare($wrapper) {
	$wrapper.find(".extra-social-button").not('.extra-social-share').each(function () {
		var $this = $(this),
			url = $this.data("url"),
			counter = $this.data("counter");

		if (counter) {
			// console.log(counter);
			$.getJSON(counter, function (data) {
				if (data['count']) {
					$this.find('.counter').text(data['count']);
				} else {
					$.each(data, function (key, val) {
						if (val['shares']) {
							$this.find('.counter').text(val['shares']);
						}
					});
				}
			});
		}
		$this.on('click', function (e) {
			e.preventDefault();
			window.open($this.attr('href'), "Partage", "menubar=no, status=no, scrollbars=no, menubar=no, width=600, height=500");
		});
	});

	var extraFancyboxCustomShareOptions = {
		margin: 0,
		padding: 0
	};
	if (window.extraFancyboxCustomShareOptions) {
		$.extend(extraFancyboxCustomShareOptions, window.extraFancyboxCustomShareOptions);
	}

	$wrapper.find('.extra-social-share').fancybox(extraFancyboxCustomShareOptions);

	// AJAX SVG SPRITE
	$.get(extra_custom_share_params.assets_uri + "/img/sprite.svg", function (data) {
		var div = document.createElement("div");
		div.innerHTML = new XMLSerializer().serializeToString(data.documentElement);
		document.body.insertBefore(div, document.body.childNodes[0]);
		$("html").addClass("extra-custom-share-svg-loaded");
	});


});