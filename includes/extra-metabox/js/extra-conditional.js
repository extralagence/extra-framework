/**
 * Created by vincent on 17/03/2014.
 */

jQuery(function ($) {
	'use strict';

	var $window = $(window);

	$(document).on('change', '.extra-conditional-input', function () {
		var $checkbox = $(this),
			$container = $checkbox.closest('.extra-conditional-container'),
			$containerFalse = $container.find('.extra-conditional-field-false').first(),
			$containerTrue = $container.find('.extra-conditional-field-true').first();

		if ($checkbox.is(':checked')) {
			$containerFalse.hide();
			$containerTrue.show();
		} else {
			$containerFalse.show();
			$containerTrue.hide();
		}
		$window.trigger('extra:admin:tabs:refresh');
		$window.trigger('extra:admin:accordion:refresh');
	}).change();
});
