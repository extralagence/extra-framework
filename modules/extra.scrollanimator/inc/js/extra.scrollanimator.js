function ExtraScrollAnimator(options) {
	var self = this,
		$window = $(window),
		scrollTop = $window.scrollTop(),
		isActive = true,
		wWidth = $window.width(),
		wHeight = $window.height(),
		isMin = null,
		isMax = null,
		isInside = null;

	/*********************************** FIRST INIT ***********************************/
	self.init = function (_options) {

		self.options = $.extend({
			target   : null,
			tween    : null,
			ease     : Linear.easeNone,
			min      : 0,
			max      : 1,
			minSize  : 0,
			speed    : 0.3,
			onMin    : null,
			onMax    : null,
			onOutside: null,
			onInside : null
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
		if (!isActive) {
			return;
		}
		var time = (fast === undefined || !fast) ? self.options.speed : 0,
			coords = self.options.target.data('coords'),
			percent = 1 - (scrollTop - coords.max) / (coords.min - coords.max);

		// Before the content
		if (percent < 0 && isMin !== true) {
			isMin = true;
			if (isFunction(self.options.onMin)) {
				self.options.onMin();
			}
			self.options.target.trigger("extra:scrollanimator:min");
		} else if (percent >= 0 && isMin !== false) {
			isMin = false;
		}

		// After the content
		if (percent > 1 && isMax !== true) {
			isMax = true;
			if (isFunction(self.options.onMax)) {
				self.options.onMax();
			}
			self.options.target.trigger("extra:scrollanimator:max");
		} else if (percent <= 1 && isMax !== false) {
			isMax = false;
		}

		// Check if we are outside
		if ((isMin === true || isMax === true) && isInside !== false) {
			isInside = false;
			if (isFunction(self.options.onOutside)) {
				self.options.onOutside();
			}
			self.options.target.trigger("extra:scrollanimator:outside");
		}

		// Check if we are inside
		else if (percent <= 1 && percent >= 0 && isInside !== true) {
			isInside = true;
			if (isFunction(self.options.onInside)) {
				self.options.onInside();
			}
			self.options.target.trigger("extra:scrollanimator:inside");
		}

		// Do we really need to update the tween ?
		if (isInside === false) {
			return;
		}

		// Update the tween
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
	$window.on('extra:resize', self.update);

	/*********************************** SCROLL ***********************************/
	function scrollHandler(event) {
		scrollTop = $window.scrollTop();
		self.allowScrollUpdate = true;
	}

	self.repaint = function () {
		if (!isActive) {
			return;
		}
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

	/*********************************** DESTROY ***********************************/
	self.destroy = function () {
		isActive = false;
		$window.off('scroll', scrollHandler);
		$window.off('extra:resize', self.update);
		if (self.options.tween) {
			self.options.tween.paused(true);
			self.options.tween.progress(1);
			self.options.tween.kill();
		}
		self.allowScrollUpdate = false;
	};

	/*********************************** UTILS ***********************************/
	function isFunction(functionToCheck) {
		var getType = {};
		return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
	}
}