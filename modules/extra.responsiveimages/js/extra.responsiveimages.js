(function () {
	///////////////////////////////////////
	//
	//
	// RESPONSIVE IMAGES
	//
	//
	///////////////////////////////////////
	var $responsiveImagesNotLazy,
		totalResponsivesImages,
		currentResponsiveImagesLoaded;


	function initPlaceholder($container) {
		var $placeholderImage = $container.find('.placeholder-image'),
			$placeholderCanvas = $container.find('.placeholder-canvas');
		if ($placeholderImage.length > 0 && $placeholderCanvas.length > 0) {
			if (typeof stackBlurImage == 'function') {
				if ($placeholderImage.length > 0 && $placeholderCanvas.length > 0) {
					stackBlurImage($placeholderImage[0], $placeholderCanvas[0], 20);
				}
			}
		}
	}

	function loadResponsiveImage($container) {

		if ($container.data("extraResponsiveImageProcessed") === true) {
			return;
		}
		//$container.data('extraResponsiveImageProcessed', true);

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
					loadResponsiveImage($container);
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
						$container.find('img').last().after(imgElement);
						$container.find('img').not(imgElement).remove();
					}

					setTimeout(function () {
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

		$container.data('extraResponsiveImageProcessed', true);
	}


	/*********************
	 *
	 * ON SCROLL
	 *
	 *********************/
	function startFollowScroll(event, $container) {
		console.log('listen startFollowScroll');
		if (typeof $container != 'undefined' && $container.length > 0) {
			$container.fracs(function (fracs, previousFracs) {
				if (fracs.visible > 0) {
					var $elem = $(this);

					if ($elem.hasClass('responsiveImagePlaceholder')) {
						loadResponsiveImage($elem.data("size", ""));
					} else {
						var $responsiveImages = $elem.find('.responsiveImagePlaceholder');
						$responsiveImages.each(function () {
							loadResponsiveImage($(this).data("size", ""));
						});
					}
					$elem.fracs('unbind');
				}
			});
			$container.fracs('check');
		} else {
			console.warn('nothing to follow');
		}
	}


	///////////////////////////////////////
	//
	//
	// INIT
	//
	//
	///////////////////////////////////////
	jQuery(function($) {
		$responsiveImagesNotLazy = $(".responsiveImagePlaceholder:not(.responsiveImageLazy)");
		totalResponsivesImages = $responsiveImagesNotLazy.length;
		currentResponsiveImagesLoaded = 0;


		// INIT PLACEHOLDER FOR ALL RESPONSIVE IMAGE
		$('.responsiveImagePlaceholder').each(function () {
			var $responsiveImage = $(this);
			$responsiveImage.data("size", "");
			initPlaceholder($responsiveImage);
		});

		$window.on('extra:responsiveImage:init', function (event, $container) {
			$container.each(function () {
				var $elem = $(this),
					$responsiveImage = $elem;
				if ($responsiveImage.hasClass('responsiveImagePlaceholder')) {
					$responsiveImage.data("size", "");
					initPlaceholder($responsiveImage);
					loadResponsiveImage($responsiveImage);
				} else {
					$responsiveImage = $elem.find('.responsiveImagePlaceholder');
					if ($responsiveImage.length > 0) {
						$responsiveImage.data("size", "");
						initPlaceholder($responsiveImage);
						loadResponsiveImage($responsiveImage);
					} else {
						$elem.trigger("extra:responsiveImage:error");
					}
				}
				totalResponsivesImages++;
			});
		});

		/*********************
		 *
		 * EXTRA SLIDERS
		 *
		 *********************/
		$window.on('extra:slider:updateClones', function (event, currentItem, currentIndex) {

			var $slider = $(event.target),
				$clones = $slider.find('.extra-slider-clone');

			$window.trigger('extra:responsiveImage:init', [$clones]);
		});


		/*********************
		 *
		 * ON SCROLL
		 *
		 *********************/
		$window.on('extra:responsiveImage:startFollowScroll', startFollowScroll);

		///////////////////////////////////////
		//
		//
		// LOOP THROUGH IMAGES AND START LOADING
		//
		//
		///////////////////////////////////////
		$responsiveImagesNotLazy.each(function () {
			var $responsiveImage = $(this);
			$responsiveImage.data("size", "");
			loadResponsiveImage($responsiveImage);
		});

		var $lazyImages = $('.responsiveImageLazy:not(.extra-custom-loading)');
		if ($lazyImages.length > 0) {
			startFollowScroll(null, $lazyImages);
		}
	});
})();
