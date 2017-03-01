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
		wrapper               : null,
		contentSelector       : '.extra-ajax-navigation-wrapper',
		listSelector          : '.extra-ajax-navigation-list',
		itemSelector          : '.extra-ajax-navigation-item',
		nextButtonSelector    : '.extra-ajax-navigation-next-button',
		previousButtonSelector: '.extra-ajax-navigation-previous-button',
		loadingClass          : 'extra-ajax-navigation-loading',
		nextCompleteClass     : 'extra-ajax-navigation-next-complete',
		previousCompleteClass : 'extra-ajax-navigation-previous-complete',
		noMoreLinkClass        : 'extra-ajax-navigation-no-more-link',
		pageMarkerSelector    : '.extra-ajax-navigation-page-marker',
		startPageAt           : 1
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
		nextIsComplete = false,
		previousIsComplete = false,
		wHeight = 0,
		scrollTop = 0,
		pages = [],
		firstPage = null,
		allowScrollUpdate = false;


	function clickHandler($button, isPrevious) {
		// Get url from button
		var url = $button.attr("href");
		loadNextPage(url, isPrevious);
	}

	function loadNextPage(url, isPrevious) {
		// CAN WE START LOADING ?
		if (isLoading || (nextIsComplete && !isPrevious) || (previousIsComplete && isPrevious)) {
			return;
		}

		// We are loading
		isLoading = true;
		self.options.wrapper.addClass(self.options.loadingClass);

		// Create a temporary div to store divs
		var $tmpDiv = $("<div></div>");

		// Load next page content into the temporary div
		$.ajax(url, {
			cache   : true,
			dataType: 'html',
			success : function (rawHtml, status, jqXHR) {

				$tmpDiv.append(rawHtml);
				$tmpDiv = $tmpDiv.find(self.options.contentSelector);

				// We are no longer loading
				isLoading = false;
				self.options.wrapper.removeClass(self.options.loadingClass);

				// Handles error
				if (status == "error") {
					nextIsComplete = true;
					previousIsComplete = true;
					self.options.wrapper.addClass(self.options.nextCompleteClass);
					self.options.wrapper.addClass(self.options.previousCompleteClass);
					self.options.wrapper.trigger("extra:ajaxNavigation:complete extra:ajaxNavigation:error");
				} else {
					self.options.wrapper.trigger("extra:ajaxNavigation:loaded", [rawHtml, status]);
				}

				// Get elements from our load
				var $items = $tmpDiv.find(self.options.itemSelector),
					$newNextButton = $tmpDiv.find(self.options.nextButtonSelector),
					$newPreviousButton = $tmpDiv.find(self.options.previousButtonSelector),
					$pageMarker = $tmpDiv.find(self.options.pageMarkerSelector);

				// Appends page marker
				if ($pageMarker.length) {
					if (isPrevious) {
						if (self.options.wrapper.find(self.options.pageMarkerSelector).length > 0) {
							$pageMarker.insertBefore(self.options.wrapper.find(self.options.pageMarkerSelector).first());
						} else {
							$pageMarker.appendTo(self.options.wrapper.find(self.options.listSelector));
						}
					} else {
						if (self.options.wrapper.find(self.options.itemSelector).length > 0) {
							$pageMarker.insertAfter(self.options.wrapper.find(self.options.itemSelector).last());
						} else {
							$pageMarker.appendTo(self.options.wrapper.find(self.options.listSelector));
						}
					}
				}

				// Update all the page markers
				$pageMarkers = self.options.wrapper.find(self.options.pageMarkerSelector);

				// Manage posts
				if ($items.length) {
					// Append new items
					self.options.wrapper.trigger("extra:ajaxNavigation:beforeAddItems", [isPrevious, $items, $pageMarker]);
					if (isPrevious) {
						$items.insertAfter(self.options.wrapper.find(self.options.itemSelector + ', ' + self.options.pageMarkerSelector).first());
					} else {
						$items.insertAfter(self.options.wrapper.find(self.options.itemSelector + ', ' + self.options.pageMarkerSelector).last());
					}
					self.options.wrapper.trigger("extra:ajaxNavigation:afterAddItems", [isPrevious, $items, $pageMarker]);
				}

				if (!isPrevious) {
					// Update next button
					if ($newNextButton.length > 0 && $nextButton.length > 0) {
						$nextButton.attr("href", $newNextButton.attr("href"));
					}
					if ($newNextButton.length == 0 || $newNextButton.hasClass(self.options.noMoreLinkClass)) {
						nextIsComplete = true;
						$nextButton.removeAttr("href");
						self.options.wrapper.addClass(self.options.nextCompleteClass);
						self.options.wrapper.trigger("extra:ajaxNavigation:nextComplete");
					}
				} else {
					// Update previous button
					if ($newPreviousButton.length > 0 && $previousButton.length > 0) {
						$previousButton.attr("href", $newPreviousButton.attr("href"));
					}
					if ($newPreviousButton.length == 0 || $newPreviousButton.hasClass(self.options.noMoreLinkClass)) {
						previousIsComplete = true;
						$previousButton.removeAttr("href");
						self.options.wrapper.addClass(self.options.previousCompleteClass);
						self.options.wrapper.trigger("extra:ajaxNavigation:previousComplete");
					}
				}

				$tmpDiv.empty().remove();

				// Recalculate position
				self.options.wrapper.trigger("extra:ajaxNavigation:update");
			}

		});
	}

	// CLICK ON THE NEXT PAGE BUTTON
	$nextButton.on("click", function (event) {
		event.preventDefault();
		if (!$(this).hasClass(self.options.noMoreLinkClass)) {
			clickHandler($(this), false);
		}
	});
	// CLICK ON THE PREVIOUS PAGE BUTTON
	$previousButton.on("click", function (event) {
		event.preventDefault();
		if (!$(this).hasClass(self.options.noMoreLinkClass)) {
			clickHandler($(this), true);
		}
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
	self.options.wrapper.on("extra:ajaxNavigation:update", resizeHandler);
	self.options.wrapper.on("extra:ajaxNavigation:load", function (event, url, isPrevious, reset) {
		if (reset) {
			nextIsComplete = false;
			previousIsComplete = false;
			self.options.wrapper.find(self.options.listSelector).html('');
		}
		loadNextPage(url, isPrevious);
	});

	/*********************************** INIT ***********************************/
	repaint();
	resizeHandler();


	return self;
}