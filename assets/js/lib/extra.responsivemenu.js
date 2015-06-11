/**************************
 *
 *
 * Responsive Navigation
 * @$menu : jquery object
 * @$site : jquery object
 * @$button : jquery object
 * @everySizes : boolean
 * @moveButton : boolean define if the button is moved to menu width
 * @moveSite : boolean define if the site wrapper is moved
 * @prependTimeline : function that take and return the timeline. This function is call before open animation
 * @appendTimeline : function that take and return the timeline. This function is call after open animation
 *
 *************************/
function ExtraResponsiveMenu(options) {

	var opt = $.extend({
			$menu: $("#mobile-menu-container"),
			$site: $("#wrapper"),
			$button: $("#switch-mobile-menu"),
			everySizes: false,
			moveButton: true,
			moveSite: true,
			prependTimeline: null,
			appendTimeline: null
		}, options),
		menuOpen = false,
		$html = $('html'),
		$wpadminbar = $("#wpadminbar"),
		timeline,
		$toMove;
	/**************************
	 *
	 *
	 * INIT
	 *
	 *
	 *************************/
	if (!opt.$menu.length || !opt.$site.length || !opt.$button.length) {
		console.log("Missing element to initialize the responsive menu");
		return;
	}

	/**************************
	 *
	 *
	 * CLICK
	 *
	 *
	 *************************/
	opt.$button.click(function () {
		if (small || opt.everySizes) {
			if (!menuOpen) {
				showMenu();
			} else {
				hideMenu();
			}
		}
		return false;
	});
	/**************************
	 *
	 *
	 * ON RESIZE
	 *
	 *
	 *************************/
	$window.on('extra.resize', function () {
		if (menuOpen) {
			showMenu(true);
		} else {
			hideMenu(true);
		}
	});
	/**************************
	 *
	 *
	 * INIT
	 *
	 *
	 *************************/
	opt.$menu.css('visibility', 'visible');
	update();
	/**************************
	 *
	 *
	 * SHOW
	 *
	 *
	 *************************/
	function showMenu(fast) {
		if (small || opt.everySizes) {
			opt.$menu.css('visibility', 'visible');
			menuOpen = true;
			$html.addClass('menu-open');
			$window.trigger('extra.showResponsiveMenu');
			if (fast) {
				timeline.totalProgress(1);
			} else {
				timeline.play();
			}
		} else {
			hideMenu(true);
		}
	}

	/**************************
	 *
	 *
	 * HIDE
	 *
	 *
	 *************************/
	function hideMenu(fast) {
		menuOpen = false;
		$html.removeClass('menu-open');
		if (!small && !opt.everySizes) {
			opt.$menu.removeAttr("style");
			opt.$site.removeAttr("style");
			opt.$menu.hide();
		}
		$window.trigger('extra.hideResponsiveMenu');
		if (fast) {
			timeline.totalProgress(0);
		} else {
			timeline.reverse();
		}
	}

	$(document).on('click', function (e) {
		var $target = $(e.target);
		if (menuOpen && $target != opt.$menu && !$target.closest(opt.$menu).length && $target != opt.$button && !$target.closest(opt.$button).length && $target != opt.$button && !$target.closest($wpadminbar).length) {
			hideMenu();
		}
	});

	$(document).on('extra.responsive-resize', function(){
		hideMenu(true);
		updateTimeline();
	});

	$window.on('extra.resize', function(){
		hideMenu(true);
		updateTimeline();
	});

	function update() {
		if(opt.moveButton && opt.moveSite) {
			$toMove = [opt.$site, opt.$button];
		} else if(opt.moveButton){
			$toMove = opt.$button;
		}else if(opt.moveSite) {
			$toMove = opt.$site;
		}else{
			$toMove = null;
		}
		if (!small && !opt.everySizes) {
			opt.$menu.removeAttr("style");
			opt.$site.removeAttr("style");
			opt.$menu.hide();
			$html.removeClass('responsive-menu').addClass('no-responsive-menu');
		} else {
			$html.removeClass('no-responsive-menu').addClass('responsive-menu');
		}
	}

	function updateTimeline() {

		if(timeline) {
			timeline.pause().totalProgress(0).kill();
		}

		timeline = new TimelineMax({
			paused: true,
			onReverseComplete: function () {
				$window.trigger('extra.hideResponsiveMenuComplete');
			}
		});
		/**************************
		 *
		 *
		 * CREATE TIMELINE
		 *
		 *
		 *************************/
		// START OF TIMELINE
		if (opt.prependTimeline) {
			timeline = opt.prependTimeline(timeline);
		}

		// SETTER
		TweenMax.set(opt.$menu, {
			x: -opt.$menu.outerWidth() + 'px',
			ease: Strong.EaseIn,
			onComplete: function () {
				$window.trigger('extra.hideResponsiveMenuComplete');
			}
		});
		if($toMove) {
			TweenMax.set($toMove, {
				x: 0,
				ease: Strong.EaseIn
			});
		}

		// TIMELINE
		timeline.to(opt.$menu.show(), 0.4, {
			x: 0,
			ease: Quad.EaseOut
		});
		if($toMove) {
			timeline.to($toMove, 0.5, {
				x: opt.$menu.outerWidth() + 'px',
				ease: Quad.EaseOut,
				onComplete: function () {
					$window.trigger('extra.showResponsiveMenuComplete');
				}
			}, '-=0.3');
		} else {
			timeline.addCallback(function(){
				$window.trigger('extra.showResponsiveMenuComplete');
			});
		}

		// END OF TIMELINE
		if (opt.appendTimeline) {
			timeline = opt.appendTimeline(timeline);
		}
	}

	$window.on('extra.triggerHideResponsiveMenu', function () {
		hideMenu(true);
	}).on('extra.triggerShowResponsiveMenu', function () {
		showMenu(true);
	});

	updateTimeline();
}