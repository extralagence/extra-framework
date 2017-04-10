function ExtraScrollTrigger(options) {
	var self = this,
		$window = $(window),
		wWidth = $window.width(),
		wHeight = $window.height(),

		startLimit = 0,
		rewindLimit = 0;

	/*********************************** FIRST INIT ***********************************/
	self.init = function (_options) {

		self.options = $.extend({
			target       : null,
			timeline     : null,
			startOffset  : 0,
			rewindOffset : 0,
			minSize: 0
		}, _options);

		if (self.options.target === null || self.options.target.length < 1) {
			console.warn("Extra Scroll Trigger: no target specified");
			return false;
		}

		if (self.options.timeline === null) {
			console.warn("Extra Scroll Trigger: no timeline specified");
			return false;
		}

		self.allowScrollUpdate = true;
		self.update();
		self.repaint();
	};

	/*********************************** UPDATE POSITION ***********************************/
	self.checkTrigger = function () {
		var scrollTop = $window.scrollTop();

		if (wWidth < self.options.minSize) {
			self.options.timeline.paused(true);
			self.options.timeline.progress(1);
		} else {
			if (scrollTop > startLimit && self.options.timeline.paused()) {
				self.options.timeline.restart();
			}
			if (scrollTop < rewindLimit && !self.options.timeline.paused()) {
				self.options.timeline.paused(true);
				self.options.timeline.progress(0);
			}
		}
	};

	/*********************************** UPDATE COORDS ***********************************/
	self.update = function () {

		wWidth = $window.width();
		wHeight = $window.height();

		var offsetTop = self.options.target.offset().top - wHeight;

		if (Number.isInteger(self.options.startOffset)) {
			startLimit = offsetTop + self.options.startOffset;
		} else if (self.options.startOffset.endsWith('%')) {
			startLimit = offsetTop + (wHeight * parseInt(self.options.startOffset) / 100);
		}

		if (Number.isInteger(self.options.rewindOffset)) {
			rewindLimit = offsetTop + self.options.rewindOffset;
		} else if (self.options.startOffset.endsWith('%')) {
			rewindLimit = offsetTop + (wHeight * parseInt(self.options.rewindOffset) / 100);
		}
		rewindLimit = Math.min(rewindLimit, startLimit);

		self.options.timeline.paused(true);

		if (wWidth < self.options.minSize) {
			self.options.timeline.progress(1);
			$window.off('scroll', scrollHandler);
		} else {
			self.options.timeline.progress(0);
			self.checkTrigger();
			$window.on('scroll', scrollHandler);
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
		self.checkTrigger();
		self.allowScrollUpdate = false;
		window.requestAnimationFrame(self.repaint);
	};

	/*********************************** LAUCNH INIT ***********************************/
	self.init(options);


	/*********************************** EXTERNAL UPDATE TWEEN ***********************************/
	self.updateTimeline = function (timeline) {
		self.options.timeline = timeline;
		self.options.timeline.paused(true);
		self.options.timeline.progress(1);
		self.allowScrollUpdate = true;
		self.update();
		self.repaint();
	};
}