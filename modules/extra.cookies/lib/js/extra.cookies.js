/****************
 *
 *
 *
 * EXTRA COOKIES 1.0
 * Documentation and examples on http://cookies.extralagence.com
 *
 *
 *
 ****************/
(function ($) {
	'use strict';
	/*global console, jQuery, $, window, Cookies */

	$.fn.extraCookies = function (options) {
		var opt = $.extend({
			$button           : null,
			cookieName        : 'extra_cookies_accepted',
			cookieValue       : 'accepted',
			expirationDays    : 60,
			position          : 'bottom', // bottom || top
			waitingBeforeCheck: 1000 // In ms
		}, options);

		$(this).each(function () {

			/*********************************** SETUP VARS ***********************************/
			var $this = $(this),
				$button = opt.$button || $this.find('.extra-cookies-button'),
				shown = false;

			$this.addClass('extra-cookies-popup-positioned extra-cookies-popup-' + opt.position);


			function checkCookies() {
				if (!hasAcceptCookies()) {
					show();
				} else {
					$this.hide();
				}
			}

			function hasAcceptCookies() {
				return Cookies.get(opt.cookieName) === opt.cookieValue;
			}

			function acceptCookies() {
				Cookies.set(opt.cookieName, opt.cookieValue, {expires: parseFloat(opt.expirationDays)});
				hide();
			}


			function show() {
				if (!shown) {
					shown = true;
					$this.toggleClass('extra-cookies-popup-show', true);
				}
			}

			function hide() {
				if (shown) {
					shown = false;
					$this.toggleClass('extra-cookies-popup-show', false);
				}
			}

			$button.on('click', function (event) {
				event.preventDefault();
				acceptCookies();
			});

			/*********************************** INITIALIZE ***********************************/
			setTimeout(function () {
				$this.addClass('extra-cookies-popup-initialised');
				setTimeout(checkCookies, opt.waitingBeforeCheck)
			}, 0);
		});

		return this;
	};
}(jQuery));