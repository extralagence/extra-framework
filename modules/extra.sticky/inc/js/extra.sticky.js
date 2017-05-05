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
				'shy'                : false,
				'shyOffset'          : -100,
				'shyClass'           : 'shy',
				'unshyClass'         : 'unshy',
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
						windowHeight = window.innerHeight,
						containerHeight,
						containerOuterHeight,
						outerHeight,
						offsetTop,
						allowStick = true,
						isFixed = false,
						isEnded = false,
						isActive = true,
						resizeTime = null,
						allowRepaint = false,
						isShy = false,
						scrollTop = 0,
						previousScrollTop = -1,
						diffStart = 0,
						diffStop = 0,
						diffStartShy = 0,
						diffStopShy = 0;

					/*********************************** FUNCTIONS ***********************************/
					function resize() {
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
						windowHeight = window.innerHeight;

						// Check minimum size requirement
						if (windowWidth < opt.minSize) {
							return;
						}

						offsetTop = $container.offset().top;
						containerHeight = $container.height();
						containerOuterHeight = $container.outerHeight();
						outerHeight = $this.outerHeight();

						// Element height must be less than window height and less than container height
						allowStick = outerHeight < windowHeight && (outerHeight < containerOuterHeight || !opt.limit);

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

					function scrollHandler() {
						var tmpScrollTop = $window.scrollTop();
						if (tmpScrollTop === previousScrollTop) {
							return;
						}
						previousScrollTop = scrollTop;
						scrollTop = tmpScrollTop;
						diffStart = offsetTop - scrollTop - opt.offset;
						diffStop = offsetTop + containerHeight - outerHeight - scrollTop - opt.offset;
						diffStartShy = offsetTop - scrollTop - opt.shyOffset;
						diffStopShy = offsetTop + containerHeight - outerHeight - scrollTop - opt.shyOffset;
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

							if (opt.shy === true) {
								if (diffStartShy < 0 && scrollTop > previousScrollTop && !isShy) {
									// we are going up
									isShy = true;
									$this.addClass(opt.shyClass);
									$this.removeClass(opt.unshyClass);
									$this.trigger("extra:sticky:shy");
								} else if (scrollTop < previousScrollTop && isShy) {
									// we are going up
									isShy = false;
									$this.removeClass(opt.shyClass);
									$this.addClass(opt.unshyClass);
									$this.trigger("extra:sticky:unshy");
								}
							}


							allowRepaint = false;
						}
						if (isActive) {
							window.requestAnimationFrame(repaint);
						}
					}

					function resizeHandler() {
						if (resizeTime) {
							clearTimeout(resizeTime);
						}
						resizeTime = setTimeout(resize, 300);
					}

					function destroyHandler() {
						isActive = false;
						$window.off('scroll', scrollHandler);
						$window.off('extra:sticky:resize', resize);
						$window.off('resize', resizeHandler);
						$window.off('extra:sticky:destroy', destroyHandler);
					}

					/********************************* LISTENERS ********************************/
					$window.on('extra:sticky:resize', resize);
					$window.on('resize', resizeHandler);
					$this.on('extra:sticky:destroy', destroyHandler);

					/*********************************** INIT ***********************************/
					resize();
					repaint();
				}
			);

			return this;

		}
		;
	}
	(jQuery)
)
;