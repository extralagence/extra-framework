///////////////////////////////////////
//
//
// RESPONSIVE IMAGES
//
//
///////////////////////////////////////
$window.load(function () {
	var $responsiveImages = $(".responsiveImagePlaceholder"),
		totalResponsivesImages = $responsiveImages.length,
		currentResponsiveImagesLoaded = 0;


	///////////////////////////////////////
	//
	//
	// LOOP THROUGH IMAGES
	//
	//
	///////////////////////////////////////
	$responsiveImages.each(function () {
		initResponsiveImage($(this).data("size", ""));
	});

	///////////////////////////////////////
	//
	//
	// INIT
	//
	//
	///////////////////////////////////////
	function initResponsiveImage($container) {

		var datas = $container.find("noscript"),
			altTxt = datas.data("alt"),
			itemProp = datas.data("img-itemprop"),
			size = extra.getImageVersion();

		function addImage(size) {
			// If image is the same, return
			if ($container.data("size") == size) {
				return;
			}

			// Keep track of the current size
			$container.data("size", size);

			// Get new src
			var imgSrc = datas.data("src-" + size);

			// Do we have a src ?
			if (!imgSrc || imgSrc == '') {
				console.warn("Image src is empty");
				console.warn($container);
				currentResponsiveImagesLoaded--;
				return;
			}

			// Create temporary image
			var imgElement = $("<img />");

			// EVENTS
			imgElement

			// ERROR
				.error(function () {
					currentResponsiveImagesLoaded++;
					// complete.extra.responsiveImage
					$container.trigger('extra:responsiveImage:load', [currentResponsiveImagesLoaded, totalResponsivesImages]);
					if (currentResponsiveImagesLoaded === totalResponsivesImages) {
						// complete.extra.responsiveImageTotal
						$container.trigger('extra:responsiveImage:complete', [currentResponsiveImagesLoaded, totalResponsivesImages]);
					}
					initResponsiveImage($container);
				})

				// LOAD
				.load(function () {
					// CORRECT IMAGE SIZE
					imgElement.attr({
						'width' : this.width,
						'height': this.height
					});
					if (itemProp) {
						imgElement.attr('itemprop', itemProp);
					}
					// APPEND
					if ($container.hasClass('responsiveBackgroundImagePlaceholder')) {
						$container.css('background-image', "url('" + imgSrc + "')");
					}
					else if ($container.hasClass('responsiveSvgImagePlaceholder')) {
						$container.find('>svg').find('image').attr({
							'xlink:href': imgSrc
						});
					}
					else {
						imgElement.appendTo($container);
					}

					// REMOVE EXISTING IMAGE
					$container.find("img").not(imgElement).remove();
					currentResponsiveImagesLoaded++;
					// complete.extra.responsiveImage
					$container.trigger('extra:responsiveImage:load', [currentResponsiveImagesLoaded, totalResponsivesImages]);
					if (currentResponsiveImagesLoaded === totalResponsivesImages) {
						// complete.extra.responsiveImageTotal
						$container.trigger('extra:responsiveImage:complete', [currentResponsiveImagesLoaded, totalResponsivesImages]);
					}

					$container.addClass("extra-responsive-image-loaded");

				}).attr({
				alt: altTxt,
				src: imgSrc
			});

		}

		//extra.responsive-resize
		$window.on("extra:resize:responsive", function () {
			size = extra.getImageVersion();
			addImage(size);
		});
		addImage(size);

		$container.data('responsiveImageProcessed', true);

	}

	$window.on('extra:responsiveImage:init', function (event, $container) {
		$container.each(function () {
			var $elem = $(this);
			if ($elem.hasClass('responsiveImagePlaceholder')) {
				initResponsiveImage($elem.data("size", ""));
			} else {
				initResponsiveImage($elem.find('.responsiveImagePlaceholder').data("size", ""));
			}
		});
	});

	/*********************
	 *
	 * EXTRA SLIDERS
	 *
	 *********************/
	$window.on('extra:slider:updateClones', function (event, currentItem, total) {
		$(event.target).find('.cloned .responsiveImagePlaceholder').each(function () {
			$window.trigger('extra:responsiveImage:init', [$(this).data("size", "")]);
		});
	});
});