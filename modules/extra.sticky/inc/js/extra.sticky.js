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
			'keepChildWidth'     : false,
			'minSize'            : 0
		}, options);

		this.each(function () {

			/*********************************** SETUP VARS ***********************************/
			var $this = $(this),
				$window = $(window),
				$container = opt.container ? opt.container : $this.parent(),
				windowWidth = window.innerWidth,
				containerHeight,
				containerOuterHeight,
				outerHeight,
				offsetTop,
				allowStick = true,
				isFixed = false,
				isEnded = false,
				resizeTime = null,
				allowRepaint = false;

			/*********************************** FUNCTIONS ***********************************/
			function resizeHandler() {
				$window.off('scroll', scrollHandler);
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
				windowWidth = window.innerWidth;

				// Check minimum size requirement
				if (windowWidth < opt.minSize) {
					return;
				}


				offsetTop = $container.offset().top;
				containerHeight = $container.height();
				containerOuterHeight = $container.outerHeight();
				outerHeight = $this.outerHeight();

				// Element height must be less than window height and less than container height
				allowStick = outerHeight < wHeight && (outerHeight < containerOuterHeight || !opt.limit) ;

				// Adjust container height if needed
				if (isFixed && opt.keepContainerHeight && containerHeight === 0) {
					containerHeight = containerOuterHeight = outerHeight;
					$container.height(containerOuterHeight);
				}

				// Adjust element width if needed
				if (isFixed && opt.keepChildWidth) {
					$this.innerWidth($container.innerWidth());
				}
				$window.on('scroll', scrollHandler);
				scrollHandler();
			}


			var scrollTop,
				diffStart,
				diffStop;

			function scrollHandler() {
				scrollTop = $window.scrollTop();
				diffStart = offsetTop - scrollTop - opt.offset;
				diffStop = offsetTop + containerHeight - outerHeight - scrollTop - opt.offset;

				allowRepaint = true;
			}

			function repaint() {
				if (allowRepaint) {
					if (allowStick) {
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
								$this.trigger("extra:sticky:unstick");
							}
						} else {
							if (diffStart < 0) {
								isFixed = true;
								$this.addClass(opt.class);
								if (opt.keepContainerHeight) {
									$container.height(containerOuterHeight);
								}
								if (opt.keepChildWidth) {
									$this.innerWidth($container.innerWidth());
								}
								$this.trigger("extra:sticky:stick");
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
										'top'     : (containerHeight - outerHeight) + 'px'
									});
									if (opt.keepContainerHeight) {
										$container.height(containerOuterHeight);
									}
									if (opt.keepChildWidth) {
										$this.innerWidth($container.innerWidth());
									}
								}
							}
						}
					}
				}
				allowRepaint = false;
				window.requestAnimationFrame(repaint);
			}

			/********************************* LISTENERS ********************************/
			$window.on('extra:sticky:resize', resizeHandler);
			$window.on('resize', function () {
				if (resizeTime) {
					clearTimeout(resizeTime);
				}
				resizeTime = setTimeout(resizeHandler, 300);
			});
			/*********************************** INIT ***********************************/
			resizeHandler();
			repaint();
		});

		return this;

	};
}(jQuery));