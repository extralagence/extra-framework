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
			'limitClass'         : 'limited',
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
					isLimited = false,
					isActive = true,
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
						'bottom'     : ''
					});
					if (opt.keepContainerHeight) {
						TweenMax.set($container, {
							clearProps: 'height'
						});
					}
					if (opt.keepChildWidth) {
						TweenMax.set($this, {
							clearProps: 'width'
						});
					}
					isFixed = false;
					isLimited = false;
					$this.removeClass(opt.class);
					$this.removeClass(opt.limitClass);
					windowWidth = window.innerWidth;
					windowHeight = window.innerHeight;

					// Check minimum size requirement
					if (windowWidth <= opt.minSize) {
						return;
					}

					offsetTop = $container.offset().top;
					containerHeight = $container.height();
					containerOuterHeight = $container.outerHeight();
					outerHeight = $this.outerHeight();

					// Element height must be less than window height and less than container height
					allowStick = outerHeight <= windowHeight && (outerHeight <= containerOuterHeight || !opt.limit);

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
									if (opt.keepContainerHeight) {
										TweenMax.set($container, {
											clearProps: 'height'
										});
									}
									if (opt.keepChildWidth) {
										TweenMax.set($this, {
											clearProps: 'width'
										});
									}
									$this.trigger("extra:sticky:unstick");
								}
							}
							else {
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
								if (isLimited) {
									if (diffStop > 0) {
										isLimited = false;
										$this.css({
											'position': '',
											'bottom'  : ''
										});
										$this.removeClass(opt.limitClass);
										if (!isFixed) {
											if (opt.keepContainerHeight) {
												TweenMax.set($container, {
													clearProps: 'height'
												});
											}
											if (opt.keepChildWidth) {
												TweenMax.set($this, {
													clearProps: 'width'
												});
											}
										}
									}
								}
								else {
									if (diffStop <= 0) {
										isLimited = true;
										$this.css({
											'position': 'absolute',
											'bottom'  : 0
										});
										$this.addClass(opt.limitClass);
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
							}
							else if (scrollTop < previousScrollTop && isShy) {
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

				function destroyHandler() {
					isActive = false;
					$window.off('extra:sticky:resize', resize);
					$window.off('extra:resize', resize);
					$window.off('extra:sticky:destroy', destroyHandler);
				}

				/********************************* LISTENERS ********************************/
				$window.on('extra:sticky:resize', resize);
				$window.on('extra:resize', resize);
				$this.on("extra:sticky:destroy", destroyHandler);

				/*********************************** INIT ***********************************/
				resize();
				repaint();
			}
		);

		return this;

	};
}(jQuery));