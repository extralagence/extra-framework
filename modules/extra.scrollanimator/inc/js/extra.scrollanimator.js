function ExtraScrollAnimator(options) {
	var self = this,
		$window = $(window),
		wWidth = $window.width(),
		wHeight = $window.height();

	/*********************************** FIRST INIT ***********************************/
	self.init = function (_options) {

		self.options = $.extend({
			target : null,
			tween  : null,
			ease   : Linear.easeNone,
			min    : 0,
			max    : 1,
			minSize: 0,
			speed  : 0.3
		}, _options);

		if (self.options.target === null || self.options.target.length < 1) {
			console.warn("Extra Scroll Animator: no target specified");
			return false;
		}

		if (self.options.tween === null) {
			console.warn("Extra Scroll Animator: no tween specified");
			return false;
		}

		self.allowScrollUpdate = true;
		self.update();
		self.repaint();
	};

	/*********************************** UPDATE POSITION ***********************************/
	self.updatePosition = function (fast) {
		var time = (fast === undefined || !fast) ? self.options.speed : 0,
			scrollTop = $window.scrollTop(),
			coords = self.options.target.data('coords'),
			percent = 1 - Math.max(0, Math.min(1, (scrollTop - coords.max) / (coords.min - coords.max)));

		if (wWidth < self.options.minSize) {
			percent = self.options.max;
		}

		TweenMax.to(self.options.tween, time, {progress: percent, ease: self.options.ease});
	};

	/*********************************** UPDATE COORDS ***********************************/
	self.update = function () {

		wWidth = $window.width();
		wHeight = $window.height();

		var coords = {},
			offsetTop = self.options.target.offset().top,
			height = self.options.target.height(),
			min = self.options.min,
			max = self.options.max;

		coords.min = offsetTop - wHeight + (wHeight * min);
		coords.max = (offsetTop - wHeight + height) + (wHeight * max);
		self.options.target.data("coords", coords);

		self.options.tween.paused(true);
		self.options.tween.progress(1);

		if (wWidth < self.options.minSize) {
			$window.off('scroll', scrollHandler);
		} else {
			$window.on('scroll', scrollHandler);
			self.updatePosition(true);
		}
	};

	/*********************************** RESIZE ***********************************/
	$window.on('extra:resize', function () {
		self.update();
	});

	/*********************************** SCROLL ***********************************/
	function scrollHandler(event) {
		self.allowScrollUpdate = true;
	}

	self.repaint = function () {
		if (!self.allowScrollUpdate) {
			window.requestAnimationFrame(self.repaint);
			return;
		}
		self.updatePosition();
		self.allowScrollUpdate = false;
		window.requestAnimationFrame(self.repaint);
	};

	/*********************************** LAUCNH INIT ***********************************/
	self.init(options);


	/*********************************** EXTERNAL UPDATE TWEEN ***********************************/
	self.updateTween = function (tween) {
		self.options.tween = tween;
		self.options.tween.paused(true);
		self.options.tween.progress(1);
		self.allowScrollUpdate = true;
		self.update();
		self.repaint();
	};
}