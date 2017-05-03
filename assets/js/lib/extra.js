var styleLog1 = "padding: 0; color:#000000; line-height:30px; font-size: 16px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen-Sans', 'Ubuntu', 'Cantarell', 'Helvetica Neue', sans-serif;",
	styleLog2 = styleLog1 + " color: red;",
	styleLog3 = styleLog1 + " font-weight: 700;";
console.log("%c\n          Made with %c♥ %cby       \n    %cwww.extralagence.com    \n \n ", styleLog1, styleLog2, styleLog1, styleLog3);

/*********************
 *
 * WINDOW VARS
 *
 *********************/
var extra = {},
	$window = $(window),
	wWidth,
	wHeight,
	/*********************
	 *
	 * RESPONSIVE
	 *
	 *********************/
	extraResponsiveSizesTests = {},
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
	var canResize = true;
	wWidth = $window.width();
	wHeight = $window.height();
	small = extra_responsive_small_width_limit.value > wWidth;
	$window.on('resize', function () {
		if (canResize) {
			resizeHandler();
			canResize = false;
			setTimeout(function () {
				resizeHandler();
				canResize = true;
			}, 300);
		}
	});
	function resizeHandler() {
		wWidth = $window.width();
		wHeight = $window.height();
		small = extra_responsive_small_width_limit.value > wWidth;
		$window.trigger('extra:resize', [wWidth, wHeight]);
	}

	/*********************
	 *
	 * MOBILE OR NOT MOBILE
	 *
	 *********************/
	$window.on('extra:resize', function () {
		// IF STATE CHANGE, UPDATE
		var _tmpExtraResponsiveSizesTests = $.extend({}, extraResponsiveSizesTests);
		$.each(extraResponsiveSizes, function (index, value) {
			_tmpExtraResponsiveSizesTests[index] = matchMedia(value).matches;
		});
		/*if (extraResponsiveSizes['desktop'] !== undefined) {
		 small = !_tmpExtraResponsiveSizesTests['desktop'];
		 }*/
		if (JSON.stringify(_tmpExtraResponsiveSizesTests) !== JSON.stringify(extraResponsiveSizesTests)) {
			extraResponsiveSizesTests = $.extend({}, _tmpExtraResponsiveSizesTests);
			// $(document).trigger("extra.responsive-resize");
			$(document).trigger("extra:resize:responsive");
		}
	}).trigger('resize');
	/*********************
	 *
	 * EXTRA RESIZE
	 *
	 *********************/
	extra.resizeEvent = 'extra:resize';
});
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