$(document).ready(function () {
	$('.extra-cookies-default-popup').extraCookies({
		cookieName        : 'extra_cookies_default_accepted',
		cookieValue       : 'true',
		position          : extraCookiesParams.position,
		expirationDays    : extraCookiesParams.expiration,
		waitingBeforeCheck: extraCookiesParams.delay
	});
});