/**
 * @author Extra
 * @version 1.0
 * @date 11 Avril 2013
 */
(function ($) {
	$.fn.extraCheckbox = function () {

		return this.each(function () {

			var $this = $(this);
			if($this.parent().hasClass('moba') || $this.parent().hasClass('extra-radio')) {
				return;
			}

			var $wrapper = $this.closest($this.attr('type') == 'checkbox' ? '.extra-checkbox' : '.extra-radio');
			if ($wrapper.size() == 0) {
				var wrapTag = $this.attr('type') == 'checkbox' ? '<div class="extra-checkbox">' : '<div class="extra-radio">';
				$this.wrap(wrapTag);
			}

			if ($this.attr('type') == 'checkbox') {
				//
				// CHECKBOX
				//
				$this.on("change", function () {
					if ($(this).is(':checked')) {
						$(this).parent().addClass("checked");
					} else {
						$(this).parent().removeClass("checked");
					}
				}).on("focus", function(){
					$(this).parent().addClass("focus");
				}).on("blur", function(){
					$(this).parent().removeClass("focus");
				}).change();

			} else if ($this.attr('type') == 'radio') {
				//
				// RADIO
				//
				$this.on("change", function () {
					$('input[name="' + $(this).attr('name') + '"]').each(function () {
						if ($(this).is(':checked')) {
							$(this).parent().addClass("selected");
						} else {
							$(this).parent().removeClass("selected");
						}
					});
				}).on("focus", function(){
					$(this).parent().addClass("focus");
				}).on("blur", function(){
					$(this).parent().removeClass("focus");
				}).change();

			}
		});
	};
})(jQuery);