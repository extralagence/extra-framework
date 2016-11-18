/**
 * Created by vincent on 17/03/2014.
 */

jQuery(function ($) {
	'use strict';

	var $window = $(window);

	function init() {
		$('.extra-conditional-multiple-container').each(function () {
			var $checkboxes = $(this).find('.extra-conditional-multiple-input');

			$checkboxes.on('change', function () {
				$checkboxes.each(function () {
					var $checkbox = $(this);
					if ($checkbox.is(':checked')) {
						$checkbox.closest('.extra-conditional-multiple-input-container').addClass('open');
						$checkbox.next('.extra-conditional-multiple-field').slideDown(300);
					} else {
						$checkbox.closest('.extra-conditional-multiple-input-container').removeClass('open');
						$checkbox.next('.extra-conditional-multiple-field').slideUp(300);
					}
				});
				$window.trigger('extra:admin:tabs:refresh');
				$window.trigger('extra:admin:accordion:refresh');
			}).change();
		});
	}

	$window.on('extra:admin:tabs:newItem', init);
	$window.on('extra:admin:accordion:newItem', init);

	init();
});
