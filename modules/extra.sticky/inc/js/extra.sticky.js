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
			'offset'             : 0,
			'limit'              : true,
			'limitOffset'        : 0,
			'limitClass'         : 'limited',
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

					// remove scroll listener by default
					// we may have it back later
					$window.off('scroll', scrollHandler);

					// Clean item
					TweenMax.set($this, {clearProps: 'position,bottom'});
					$this.removeClass(opt.class);
					$this.removeClass(opt.limitClass);
					if (opt.keepContainerHeight) {
						TweenMax.set($container, {clearProps: 'height'});
					}

					// If needed, clean with item
					if (opt.keepChildWidth) {
						TweenMax.set($this, {clearProps: 'width'});
					}

					// Set vars to their default values
					// to allow them to be updated
					isFixed = false;
					isLimited = false;
					previousScrollTop = -1;

					// Window dimensions
					windowWidth = window.innerWidth;
					windowHeight = window.innerHeight;

					// Check minimum size requirement
					if (windowWidth <= opt.minSize) {
						return;
					}

					// Update dimensions
					offsetTop = $container.offset().top;
					containerHeight = $container.height();
					containerOuterHeight = $container.outerHeight();
					outerHeight = $this.outerHeight();

					// Element height must be less than window height and less than container height
					allowStick = outerHeight <= windowHeight && (outerHeight <= containerOuterHeight || !opt.limit);

					// Set listener back
					$window.on('scroll', scrollHandler);

					// Update position
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
					diffStop = offsetTop + containerHeight - outerHeight - scrollTop - opt.offset - windowHeight;
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
									if (diffStop > opt.limitOffset) {
										isLimited = false;
										TweenMax.set($this, {clearProps: 'position,bottom'});
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
									if (diffStop <= opt.limitOffset) {
										isLimited = true;
										$this.css({
											'position': 'absolute',
											'bottom'  : opt.limitOffset
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

				function update(event, options) {
					opt = $.extend(opt, options);
					resize();
				}

				function destroyHandler() {
					isActive = false;
					$window.off('extra:sticky:resize', resize);
					$window.off('extra:resize', resize);
					$this.off("extra:sticky:update", update);
					$this.off('extra:sticky:destroy', destroyHandler);
				}

				/********************************* LISTENERS ********************************/
				$window.on('extra:sticky:resize', resize);
				$window.on('extra:resize', resize);
				$this.on("extra:sticky:update", update);
				$this.on("extra:sticky:destroy", destroyHandler);

				/*********************************** INIT ***********************************/
				resize();
				repaint();
			}
		);

		return this;

	};
}(jQuery));