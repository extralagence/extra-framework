/**
 * Created by vincent on 17/03/2014.
 */

jQuery(function ($) {
	'use strict';

	$(document).on('change', '.extra-conditional-input', function () {
		var $checkbox = $(this),
			$container = $checkbox.closest('.extra-conditional-container'),
			$containerFalse = $container.find('.extra-conditional-field-false').first(),
			$containerTrue = $container.find('.extra-conditional-field-true').first();

		if ($checkbox.is(':checked')) {
			$containerFalse.slideUp(300, function() {
				$container.closest('.ui-accordion').accordion('refresh');
			});
			$containerTrue.slideDown(300, function() {
				$container.closest('.ui-accordion').accordion('refresh');
			});
		} else {
			$containerFalse.slideDown(300, function() {
				$container.closest('.ui-accordion').accordion('refresh');
			});
			$containerTrue.slideUp(300, function() {
				$container.closest('.ui-accordion').accordion('refresh');
			});
		}
		$container.closest('.ui-accordion').accordion('refresh');
	});
});
