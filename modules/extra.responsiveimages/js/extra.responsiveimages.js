///////////////////////////////////////
//
//
// RESPONSIVE IMAGES
//
//
///////////////////////////////////////
$window.on("load", function () {
	var $responsiveImages = $(".responsiveImagePlaceholder:not(.responsiveImageLazy)"),
		$responsiveLazyImages = $('.responsiveImagePlaceholder.responsiveImageLazy'),
		$responsiveCustomLoadedImages = $('.responsiveImagePlaceholder.extra-custom-loading'),
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
		var $responsiveImage = $(this);
		$responsiveImage.data("size", "");
		initPlaceholder($responsiveImage);
		initResponsiveImage($responsiveImage);
	});

	function initLazyAndCustomLoaded() {
		var $responsiveImage = $(this);
		$responsiveImage.data("size", "");
		initPlaceholder($responsiveImage);
	}

	$responsiveLazyImages.each(initLazyAndCustomLoaded);
	$responsiveCustomLoadedImages.each(initLazyAndCustomLoaded);


	///////////////////////////////////////
	//
	//
	// INIT
	//
	//
	///////////////////////////////////////
	function initPlaceholder($container) {
		var $placeholderImage = $container.find('.placeholder-image'),
			$placeholderCanvas = $container.find('.placeholder-canvas');
		if ($placeholderImage.size() > 0 && $placeholderCanvas.size() > 0) {
			if (typeof stackBlurImage == 'function') {
				stackBlurImage($placeholderImage[0], $placeholderCanvas[0], 20);
			}
		}
	}


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
				// currentResponsiveImagesLoaded++;
				totalResponsivesImages--;
				$container.trigger('extra:responsiveImage:error', [currentResponsiveImagesLoaded, totalResponsivesImages]);
				if (currentResponsiveImagesLoaded === totalResponsivesImages) {
					$container.trigger('extra:responsiveImage:complete', [currentResponsiveImagesLoaded, totalResponsivesImages]);
				}
				return;
			}

			// Create temporary image
			var imgElement = $("<img />");

			// EVENTS
			imgElement

			// ERROR
				.on("error", function () {
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
				.on("load", function () {
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
						$container.find('.placeholder-image').after(imgElement);
					}

					setTimeout(function () {
						$container.find('.placeholder-image').remove();
						$container.find('.placeholder-canvas').remove();
					}, 500);

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

		$container.data('extra-responsiveImageProcessed', true);

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

	$window.on('extra:responsiveImage:startFollowScroll', startFollowScroll);
	function startFollowScroll(event, $container) {
		$container.fracs(function (fracs, previousFracs) {
			if(fracs.visible > 0) {
				var $elem = $(this);

				if ($elem.hasClass('responsiveImagePlaceholder')) {
					initResponsiveImage($elem.data("size", ""));
				} else {
					var $responsiveImages = $elem.find('.responsiveImagePlaceholder');
					$responsiveImages.each(function () {
						initResponsiveImage($(this).data("size", ""));
					});
				}
				$elem.fracs('unbind');
			}
		});
		$container.fracs('check');
	}

	var $lazyImages = $('.responsiveImageLazy:not(.extra-custom-loading)');
	$window.trigger('extra:responsiveImage:startFollowScroll', [$lazyImages]);
});
