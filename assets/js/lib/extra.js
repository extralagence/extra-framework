/*********************
 *
 * WINDOW VARS
 *
 *********************/
var extra = {},
	$window = $(window),
	wWidth,
	wHeight,
	resizeTimer;
/*********************
 *
 * RESPONSIVE
 *
 *********************/
var extraResponsiveSizesTests = {},
	small = null;
/*********************
 *
 * GLOBAL OPTIONS
 *
 *********************/
var extraOptions = {};
/*********************
 *
 * JQUERY START
 *
 *********************/
$(document).ready(function () {
	/**************************
	 *
	 *
	 * REAL RESIZE
	 *
	 *
	 *************************/
	wWidth = $window.width();
	wHeight = $window.height();
	$window.on('resize', function () {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(resizeHandler, 300);
	});
	function resizeHandler() {
		if ($window.width() !== wWidth || $window.height() !== wHeight) {
			wWidth = $window.width();
			wHeight = $window.height();
			// $window.trigger('extra.resize');
			$window.trigger('extra:resize');
		}
	}

	/*********************
	 *
	 * MOBILE OR NOT MOBILE
	 *
	 *********************/
	$(window).on('extra:resize', function () {
		// IF STATE CHANGE, UPDATE
		var _tmpExtraResponsiveSizesTests = $.extend({}, extraResponsiveSizesTests);
		$.each(extraResponsiveSizes, function (index, value) {
			_tmpExtraResponsiveSizesTests[index] = matchMedia(value).matches;
		});
		if (extraResponsiveSizes['desktop'] !== undefined) {
			small = !_tmpExtraResponsiveSizesTests['desktop'];
		}
		if (JSON.stringify(_tmpExtraResponsiveSizesTests) !== JSON.stringify(extraResponsiveSizesTests)) {
			extraResponsiveSizesTests = $.extend({}, _tmpExtraResponsiveSizesTests);
			// $(document).trigger("extra.responsive-resize");
			$(document).trigger("extra:resize:responsive");
		}
	}).trigger('extra:resize');
	/*********************
	 *
	 * EXTRA RESIZE
	 *
	 *********************/
	extra.resizeEvent = 'extra:resize';
	/**************************
	 *
	 *
	 * GET SCREEN SIZE
	 *
	 *
	 *************************/
	extra.getImageVersion = function () {
		// default value
		var toReturn = 'desktop';
		$.each(extraResponsiveSizesTests, function (index, value) {
			if (value === true) {
				toReturn = index;
			}
		});
		return toReturn;
	};
});