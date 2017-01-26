///////////////////////////////////////
//
//
// EXTRA RESPONSIVE IMAGES
//
//
///////////////////////////////////////
$(document).ready(function ($) {
	var totalResponsivesImages = 0,
		currentResponsiveImagesLoaded = 0,
		$initialImages = $(document.body).find(".extra-responsive-image-wrapper").not('.extra-responsive-custom-loading');

	///////////////////////////////////////
	//
	//
	// INITIALIZATION
	//
	//
	///////////////////////////////////////
	function init($container) {
		var isLazy = $container.hasClass('.extra-responsive-image-lazy'),
			isCustom = $container.hasClass('.extra-responsive-image-custom-loading'),
			$placeholderImage = $container.find('.extra-responsive-image-placeholder'),
			$placeholderCanvas = $container.find('.extra-responsive-image-placeholder-canvas');

		// BLURRED CANVAS
		if ($placeholderImage.length > 0 && $placeholderCanvas.length > 0 && typeof stackBlurImage == 'function') {
			stackBlurImage($placeholderImage[0], $placeholderCanvas[0], 20);
		}

		// Lazy but not custom = follow scroll
		if (isLazy && !isCustom) {
			startFollowScroll(null, $container);
		}
		// Classic responsive image
		else if (!isLazy) {
			$window.on("extra:resize:responsive", function () {
				load($container);
			});
			load($container);
		}
	}

	///////////////////////////////////////
	//
	//
	// LOAD
	//
	//
	///////////////////////////////////////
	function load($imageWrapper) {

		var datas = $imageWrapper.find("noscript"),
			altTxt = datas.data("alt"),
			itemProp = datas.data("img-itemprop"),
			size = extra.getImageVersion();

		// If image is the same, return
		if ($imageWrapper.data("size") == size) {
			return;
		}

		// Keep track of the current size
		$imageWrapper.data("size", size);

		// Get new src
		var imgSrc = datas.data("src-" + size);

		// Do we have a src ?
		if (!imgSrc || imgSrc == '') {
			currentResponsiveImagesLoaded++;
			// totalResponsivesImages--;
			$imageWrapper.trigger('extra:responsiveImage:error', [currentResponsiveImagesLoaded, totalResponsivesImages]);
			checkCompleteState($imageWrapper);
			return;
		}

		// Create temporary image
		var imgElement = $("<img />");

		// EVENTS
		imgElement

		// ERROR
			.on("error", function () {
				currentResponsiveImagesLoaded++;
				$imageWrapper.trigger('extra:responsiveImage:load', [currentResponsiveImagesLoaded, totalResponsivesImages]);
				checkCompleteState($imageWrapper);
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
				if ($imageWrapper.hasClass('extra-responsive-image-background')) {
					$imageWrapper.css('background-image', "url('" + imgSrc + "')");
				}
				else if ($imageWrapper.hasClass('extra-responsive-image-svg')) {
					$imageWrapper.find('>svg').find('image').attr({
						'xlink:href': imgSrc
					});
				}
				else {
					$imageWrapper.append(imgElement);
					$imageWrapper.find('img').not(imgElement).remove();
				}

				setTimeout(function () {
					$imageWrapper.find('.extra-responsive-image-placeholder-canvas').remove();
				}, 500);

				currentResponsiveImagesLoaded++;
				// complete.extra.responsiveImage
				$imageWrapper.trigger('extra:responsiveImage:load', [currentResponsiveImagesLoaded, totalResponsivesImages]);
				checkCompleteState($imageWrapper);

				$imageWrapper.addClass("extra-responsive-image-loaded");

			}).attr({
			alt: altTxt,
			src: imgSrc
		});
	}

	///////////////////////////////////////
	//
	//
	// SCROLL EVENT
	//
	//
	///////////////////////////////////////
	function startFollowScroll(event, $container) {

		// Find the responsive image(s)
		var $image = $container.hasClass('extra-responsive-image-wrapper') ? $container : $container.find('.extra-responsive-image-wrapper');

		// Make sure we have an image
		if (!$image.length) {
			console.warn("Nothing to follow");
			return false;
		}

		// Add to fracs
		$container.fracs(function (fracs, previousFracs) {
			if (fracs.visible > 0) {
				$image.each(function () {
					load($(this));
				});
				$container.fracs('unbind');
			}
		});

		// Checks
		$container.fracs('check');
	}

	$window.on('extra:responsiveImage:startFollowScroll', startFollowScroll);

	///////////////////////////////////////
	//
	//
	// IS IT COMPLETE ?
	//
	//
	///////////////////////////////////////
	function checkCompleteState($imageWrapper) {
		if (currentResponsiveImagesLoaded === totalResponsivesImages) {
			$imageWrapper.trigger('extra:responsiveImage:complete', [currentResponsiveImagesLoaded, totalResponsivesImages]);
		}
	}

	///////////////////////////////////////
	//
	//
	// INIT LISTENER
	//
	//
	///////////////////////////////////////
	$window.on('extra:responsiveImage:init', function (event, $container) {

		// Make sure we have a container
		if (!$container || !$container.length) {
			console.warn("Nothing to init");
			return false;
		}

		$container.each(function () {
			var $elem = $(this),
				$images = $elem.hasClass('extra-responsive-image-wrapper') ? $elem : $elem.find('.extra-responsive-image-wrapper');

			if (!$images.length) {
				$elem.trigger("extra:responsiveImage:error");
				console.warn('No responsive images to process');
			}

			totalResponsivesImages += $images.length;

			$images.each(function (index, element) {
				var $image = $(element);
				$image.data("size", "");
				init($image);
			});
		});
	});

	///////////////////////////////////////
	//
	//
	// FIRST LAUNCH
	//
	//
	///////////////////////////////////////
	if ($initialImages.length) {
		totalResponsivesImages = $initialImages.length;
		$initialImages.each(function () {
			init($(this));
		});
	}
});