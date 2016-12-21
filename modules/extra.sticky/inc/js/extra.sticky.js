/****************
 *
 *
 *
 * EXTRA STICKY 0.1
 *
 *
 *
 *****************/
(function ($) {
	'use strict';
	/*global console, jQuery, $, window, Quad */
	var $window = $(window);

	$.fn.extraSticky = function (options) {

		var opt = $.extend({
			'class'              : 'sticky',
			'container'          : null,
			'limit'              : true,
			'offset'             : 0,
			'keepContainerHeight': false,
			'keepChildWidth'     : false
		}, options);

		this.each(function () {

			/*********************************** SETUP VARS ***********************************/
			var $this = $(this),
				$window = $(window),
				$container = opt.container ? opt.container : $this.parent(),
				containerHeight,
				containerOuterHeight,
				innerHeight,
				offsetTop,
				allowStick = true,
				isFixed = false,
				isEnded = false,
				resizeTime = null;

			/*********************************** FUNCTIONS ***********************************/
			function resizeHandler() {
				$this.css({
					'position': '',
					'top'     : '',
					'width'   : ''
				});
				$container.css({
					'height': ''
				});
				isFixed = false;
				isEnded = false;
				$this.removeClass(opt.class);


				offsetTop = $container.offset().top;
				containerHeight = $container.height();
				containerOuterHeight = $container.outerHeight();
				innerHeight = $this.outerHeight();

				// Element height must be less than window height
				allowStick = innerHeight < wHeight;

				// Adjust container height if needed
				if (isFixed && opt.keepHeight && containerHeight == 0) {
					containerHeight = containerOuterHeight = innerHeight;
					$container.height(containerOuterHeight);
				}

				// Adjust element width if needed
				if (isFixed && opt.keepChildWidth) {
					$this.innerWidth($container.innerWidth());
				}
				scrollHandler();
			}

			function scrollHandler() {

				if (!allowStick) {
					return;
				}

				var scrollTop = $window.scrollTop(),
					diffStart = offsetTop - scrollTop - opt.offset,
					diffStop = offsetTop + containerHeight - innerHeight - scrollTop - opt.offset;

				if (isFixed) {
					if (diffStart >= 0) {
						isFixed = false;
						$this.removeClass(opt.class);
						TweenMax.set($container, {
							clearProps: 'height'
						});
						TweenMax.set($this, {
							clearProps: 'width'
						});
					}
				} else {
					if (diffStart < 0) {
						isFixed = true;
						$this.addClass(opt.class);
						if (opt.keepHeight) {
							$container.height(containerOuterHeight);
						}
						if (opt.keepChildWidth) {
							$this.innerWidth($container.innerWidth());
						}
					}
				}

				if (opt.limit) {
					if (isEnded) {
						if (diffStop > 0) {
							isEnded = false;
							$this.css({
								'position': '',
								'top'     : ''
							});
							if (!isFixed) {
								$container.height('');
								$this.width('');
							}
						}
					} else {
						if (diffStop <= 0) {
							isEnded = true;
							$this.css({
								'position': 'absolute',
								'top'     : (containerHeight - innerHeight) + 'px'
							});
							if (opt.keepHeight) {
								$container.height(containerOuterHeight);
							}
							if (opt.keepChildWidth) {
								$this.innerWidth($container.innerWidth());
							}
						}
					}
				}
			}

			/********************************* LISTENERS ********************************/
			$window.on('extra:sticky:resize', resizeHandler);
			$window.on('resize', function () {
				if (resizeTime) {
					clearTimeout(resizeTime);
				}
				resizeTime = setTimeout(resizeHandler, 300);
			});
			$window.on('scroll', scrollHandler);
			/*********************************** INIT ***********************************/
			resizeHandler();
		});

		return this;

	};
}(jQuery));