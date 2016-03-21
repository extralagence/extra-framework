/*********************
 *
 * WINDOW LOAD
 *
 *********************/
$window.load(function () {
	var $responsiveImages = $(".responsiveImagePlaceholder"),
		totalResponsivesImages = $responsiveImages.length,
		currentResponsiveImagesLoaded = 0;
	/**************************
	 *
	 *
	 * RESPONSIVE IMAGE
	 *
	 *
	 *************************/
	window.imageCount = 0;
	$responsiveImages.each(function () {
		initResponsiveImage($(this).data("size", ""));
	});
	function initResponsiveImage(container) {

		var datas = container.find("noscript"),
			altTxt = datas.data("alt"),
			itemProp = datas.data("img-itemprop"),
			size = extra.getImageVersion(),
			addImage = function (size) {
				// SET NEW IMAGE
				if (datas && container.data("size") != size) {
					container.data("size", size);
					var imgSrc = datas.data("src-" + size);
					if (imgSrc) {
						var imgElement = $("<img />");
						imgElement.error(function () {
							currentResponsiveImagesLoaded++;
							container.trigger('complete.extra.responsiveImage', [currentResponsiveImagesLoaded, totalResponsivesImages]);
							if (currentResponsiveImagesLoaded === totalResponsivesImages) {
								container.trigger('complete.extra.responsiveImageTotal', [currentResponsiveImagesLoaded, totalResponsivesImages]);
							}
							initResponsiveImage(container);
						}).load(function () {
							// CORRECT IMAGE SIZE
							imgElement.attr({
								'width' : this.width,
								'height': this.height
							});
							if (itemProp) {
								imgElement.attr('itemprop', itemProp);
							}
							// APPEND
							if (container.hasClass('responsiveBackgroundImagePlaceholder')) {
								container.css('background-image', "url('" + imgSrc + "')");
							}
							else if (container.hasClass('responsiveSvgImagePlaceholder')) {
								container.find('>svg').find('image').attr({
									'xlink:href': imgSrc
								});
							}
							else {
								imgElement.appendTo(container);
							}

							// REMOVE EXISTING IMAGE
							container.find("img").not(imgElement).remove();
							currentResponsiveImagesLoaded++;
							container.trigger('complete.extra.responsiveImage', [currentResponsiveImagesLoaded, totalResponsivesImages]);
							if (currentResponsiveImagesLoaded === totalResponsivesImages) {
								container.trigger('complete.extra.responsiveImageTotal', [currentResponsiveImagesLoaded, totalResponsivesImages]);
							}

						}).attr({
							alt: altTxt,
							src: imgSrc
						});
					} else {
						totalResponsivesImages--;
					}
				}
			};

		$window.on("extra.responsive-resize", function () {
			size = extra.getImageVersion();
			addImage(size);
		});
		addImage(size);

		container.data('responsiveImageProcessed', true);

	}

	$window.on('extra.responsiveImage', function (event, obj) {
		obj.each(function () {
			var $elem = $(this);
			if ($elem.hasClass('responsiveImagePlaceholder')) {
				initResponsiveImage($elem.data("size", ""));
			} else {
				initResponsiveImage($elem.find('.responsiveImagePlaceholder').data("size", ""));
			}
		});
	});
});