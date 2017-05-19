function ExtraScrollAnimator(options) {
	var self = this,
		$window = $(window),
		scrollTop = $window.scrollTop(),
		isActive = true,
		wWidth = $window.width(),
		wHeight = $window.height(),
		isMin = null,
		isMax = null,
		isInside = null,
		isPaused = false;

	/*********************************** FIRST INIT ***********************************/
	self.init = function (_options) {

		self.options = $.extend({
			target         : null,
			tween          : null,
			ease           : Linear.easeNone,
			min            : 0,
			max            : 1,
			minSize        : 0,
			speed          : 0.3,
			defaultProgress: 1,
			debug          : false,
			onMin          : null,
			onMax          : null,
			onOutside      : null,
			onInside       : null,
			onUpdate       : null
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

		// not allowed to update
		if (!isActive) {
			return;
		}


		var
			// get tween speed
			time = (fast === undefined || !fast) ? self.options.speed : 0,

			//get coordinates from target (no recalculation)
			coords = self.options.target.data('coords'),

			// prepare percent variable
			percent;

		// if we have no coordinates,
		// we stop there
		if (!coords) {
			return;
		}

		// Get percent
		percent = 1 - (scrollTop - coords.max) / (coords.min - coords.max);

		// Before the content
		if (percent <= 0 && isMin !== true) {
			// TweenMax.to(self.options.tween, time, {progress: 0, ease: self.options.ease});
			isMin = true;
			if (isFunction(self.options.onMin)) {
				self.options.onMin();
			}
			self.options.target.trigger("extra:scrollanimator:min");
		}
		else if (percent > 0 && isMin !== false) {
			isMin = false;
		}

		// After the content
		if (percent >= 1 && isMax !== true) {
			// TweenMax.to(self.options.tween, time, {progress: 1, ease: self.options.ease});
			isMax = true;
			if (isFunction(self.options.onMax)) {
				self.options.onMax();
			}
			self.options.target.trigger("extra:scrollanimator:max");
		}
		else if (percent <= 1 && isMax !== false) {
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
			if (isMax) {
				// Force Max
				percent = 1;
			} else {
				// Force Min
				percent = 0;
			}
		}

		percent = Math.max(0, Math.min(percent));
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
		self.options.tween.progress(self.options.defaultProgress);

		if (wWidth < self.options.minSize) {
			$window.off('scroll', scrollHandler);
		}
		else {
			$window.on('scroll', scrollHandler);
			self.updatePosition(true);
		}

		if (isFunction(self.options.onUpdate)) {
			self.options.onUpdate(coords);
		}
		self.options.target.trigger("extra:scrollanimator:update", [coords]);

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
		if (self.options.tween) {
			self.options.tween.kill();
		}
		self.options.tween = tween;
		self.options.tween.paused(true);
		self.options.tween.progress(self.options.defaultProgress);
		self.allowScrollUpdate = true;
		self.update();
	};

	/*********************************** PAUSE / RESUME ***********************************/
	self.pause = function () {
		if (!isPaused) {
			$window.off('scroll', scrollHandler);
			$window.off('extra:resize', self.update);
			isActive = false;
			isPaused = true;
		}
	};

	self.resume = function () {
		if (isPaused) {
			if (wWidth < self.options.minSize) {
				$window.off('scroll', scrollHandler);
			}
			else {
				$window.on('scroll', scrollHandler);
			}
			$window.on('extra:resize', self.update);
			isActive = true;
			isPaused = false;
			self.repaint();
		}
	};

	/*********************************** DESTROY ***********************************/
	self.destroy = function () {
		isActive = false;
		$window.off('scroll', scrollHandler);
		$window.off('extra:resize', self.update);
		if (self.options.tween) {
			self.options.tween.paused(true);
			self.options.tween.progress(self.options.defaultProgress);
			self.options.tween.kill();
		}
		self.allowScrollUpdate = false;
	};

	function debug(string) {
		if (self.options.debug === true) {
			console.log(string);
		}
	}

	/*********************************** UTILS ***********************************/
	function isFunction(functionToCheck) {
		var getType = {};
		return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
	}
}