$(document).ready(function () {
	$('.extra-cookies-popup').extraCookies({
		cookieName        : 'extra_cookies_default_accepted',
		cookieValue       : 'true',
		position          : extraCookiesParams.position,
		expirationDays    : extraCookiesParams.expiration,
		waitingBeforeCheck: extraCookiesParams.delay
	});
});