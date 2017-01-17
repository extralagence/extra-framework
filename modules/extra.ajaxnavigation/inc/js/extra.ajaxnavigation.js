///////////////////////////////////////
//
//
// AJAX NAVIGATION (v1.0)
//
//
///////////////////////////////////////
function ExtraAjaxNavigation(options) {
	var self = this;

	/*********************************** OPTIONS ***********************************/
	self.options = $.extend({
		wrapper                : null,
		contentSelector        : '.extra-ajax-navigation-wrapper',
		itemSelector           : '.extra-ajax-navigation-item',
		nextButtonSelector     : '.extra-ajax-navigation-more-button',
		previousButtonSelector : '.extra-ajax-navigation-previous-button',
		loadingClass           : 'extra-ajax-navigation-loading',
		completeClass          : 'extra-ajax-navigation-complete',
		pageMarkerSelector     : '.extra-ajax-navigation-page-marker',
		startPageAt            : 1
	}, options);


	/*********************************** OPTIONS CHECKER ***********************************/
	if (self.options.wrapper === null || self.options.wrapper.length < 1) {
		console.warn("Extra Ajax Navigation: no wrapper specified");
		return {};
	}

	/*********************************** VARIABLES ***********************************/
	var $window = $(window),
		$nextButton = self.options.wrapper.find(self.options.nextButtonSelector),
		$previousButton = self.options.wrapper.find(self.options.previousButtonSelector),
		$pageMarkers = self.options.wrapper.find(self.options.pageMarkerSelector),
		currentPageNum = self.options.startPageAt,
		isLoading = false,
		isComplete = false,
		wHeight = 0,
		scrollTop = 0,
		pages = [],
		firstPage = null,
		allowScrollUpdate = false;


	function clickHandler($button, isPrevious) {
		// CAN WE START LOADING ?
		if (isLoading || isComplete) {
			return;
		}

		// We are loading
		isLoading = true;
		$button.addClass(self.options.loadingClass);

		// Create a temporary div to store divs
		var $tmpDiv = $("<div></div>"),

		// Get url from button
		url = $button.attr("href");

		// Load next page content into the temporary div
		$.ajax(url, {
			cache   : true,
			dataType: 'html',
			success : function (data, textStatus, jqXHR) {

				$tmpDiv.append(data);
				$tmpDiv = $tmpDiv.find(self.options.contentSelector);

				// We are no longer loading
				isLoading = false;
				$button.removeClass(self.options.loadingClass);

				// Handles error
				if (status == "error") {
					isComplete = true;
					self.options.wrapper.addClass(self.options.completeClass);
					self.options.wrapper.trigger("extra:ajaxNavigation:complete extra:ajaxNavigation:error");
				}

				// Get elements from our load
				var $items = $tmpDiv.find(self.options.itemSelector),
					$newButton = (isPrevious) ? $tmpDiv.find(self.options.previousButtonSelector) : $tmpDiv.find(self.options.nextButtonSelector),
					$pageMarker = $tmpDiv.find(self.options.pageMarkerSelector);

				// Appends page marker
				if ($pageMarker.length) {
					if (isPrevious) {
						console.log(self.options.wrapper.find(self.options.itemSelector).first());
						$pageMarker.insertBefore(self.options.wrapper.find(self.options.pageMarkerSelector).first());
					} else {
						$pageMarker.insertAfter(self.options.wrapper.find(self.options.itemSelector).last());
					}
				}

				// Update all the page markers
				$pageMarkers = self.options.wrapper.find(self.options.pageMarkerSelector);

				// Recalculate position
				calculatePagePositions();

				// Manage posts
				if ($items.length) {
					// Append new items
					self.options.wrapper.trigger("extra:ajaxNavigation:beforeAddItems", [isPrevious, $items]);
					if (isPrevious) {
						$items.insertAfter(self.options.wrapper.find(self.options.itemSelector + ', ' + self.options.pageMarkerSelector).first());
					} else {
						$items.insertAfter(self.options.wrapper.find(self.options.itemSelector + ', ' + self.options.pageMarkerSelector).last());
					}
					self.options.wrapper.trigger("extra:ajaxNavigation:afterAddItems", [isPrevious, $items]);
				}

				// Is there a next button ?
				if ($newButton.length > 0) {
					$button.attr("href", $newButton.attr("href"));
				} else {
					isComplete = true;
					if (isPrevious) {
						$button.remove();
					} else {
						$button.removeAttr("href");
					}
					self.options.wrapper.addClass(self.options.completeClass);
					self.options.wrapper.trigger("extra:ajaxNavigation:complete");
				}

				$tmpDiv.empty().remove();
			}

		});
	}

	// CLICK ON THE NEXT PAGE BUTTON
	$nextButton.on("click", function (event) {
		event.preventDefault();
		clickHandler($(this), false);
	});
	// CLICK ON THE PREVIOUS PAGE BUTTON
	$previousButton.on("click", function (event) {
		event.preventDefault();
		clickHandler($(this), true);
	});


	/*********************************** PAGE POSITION ***********************************/
	function calculatePagePositions() {
		pages = [];
		firstPage = null;
		$pageMarkers.each(function () {
			var $marker = $(this),
				pageNum = $marker.data('page-num'),
				pagePermalink = $marker.data('page-permalink'),
				pageTitle = $marker.data('page-title'),
				top = $marker.offset().top,
				page = {top: top, num: pageNum, permalink: pagePermalink, title: pageTitle};

			pages.push(page);

			if (firstPage == null || firstPage.top > top) {
				firstPage = page;
			}
		});
	}

	/*********************************** CURRENT PAGE IN THE SCROLL ***********************************/
	function getScrolledPage(scrollTop) {
		var i = 0,
			length = pages.length,
			currentPage = null,
			goodPage = null;

		while (i < length) {
			currentPage = pages[i];
			if (currentPage.top < scrollTop) {
				if (goodPage == null || goodPage.top < currentPage.top) {
					goodPage = currentPage;
				}
			}
			i++;
		}

		if (goodPage == null) {
			goodPage = firstPage;
		}

		return goodPage;
	}

	/*********************************** CHANGE PAGE IN HISTORY ***********************************/
	function changeCurrentPage(pageNum, pageUrl, pageTitle) {
		if (currentPageNum != pageNum) {
			currentPageNum = pageNum;
			if (window.history && window.history.pushState) {
				window.history.pushState("", "", pageUrl);
				document.title = pageTitle;
			}
		}
	}


	/*********************************** MANAGE WINDOW RESIZE ***********************************/
	function resizeHandler() {
		wHeight = window.innerHeight;
		calculatePagePositions();
		scrollUpdate();
	}

	$window.on("extra:resize", resizeHandler);

	/*********************************** SCROLL ***********************************/
	function scrollUpdate() {
		scrollTop = $window.scrollTop();
		var scrolledPage = getScrolledPage(scrollTop);
		if (scrolledPage) {
			changeCurrentPage(scrolledPage.num, scrolledPage.permalink, scrolledPage.title);
		}
	}

	function repaint() {
		if (allowScrollUpdate) {
			allowScrollUpdate = false;
			scrollUpdate();
		}
		window.requestAnimationFrame(repaint);
	}

	function scrollHandler() {
		allowScrollUpdate = true;
	}

	$window.on("scroll", scrollHandler);

	/*********************************** INIT ***********************************/
	repaint();
	resizeHandler();


	return self;
}