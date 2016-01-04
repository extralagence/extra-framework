$(document).ready(function(){
	/*********************
	 *
	 * ACCORDEON
	 *
	 *********************/
	$(".accordeon-element").each(function(){
		var wrapper = $(this);
		var title = wrapper.find(" > .tab-title a");
		var content = wrapper.find(" > .tab-content");
		var height = content.innerHeight();
		content.height(0);
		title.bind("click", function(event){
			event.preventDefault();
			if(content.height() > 0) {
				wrapper.removeClass("open");
				TweenMax.to(content, 0.3, {css:{height:0}});
			} else {
				wrapper.addClass("open");
				TweenMax.to(content, 0.3, {css:{height:height}});
				window.location.hash = '/'+$(this).attr('id');
			}
		});
	});

	$window.on('load', function () {
		var hash = window.location.hash;

		if (hash != null && hash != '') {
			hash = hash.substr(2);
			var $trigger = $('#'+hash);

			if ($trigger.size() > 0) {
				$trigger.trigger('click');
				TweenMax.to($window, 0, {
					scrollTo: {
						y: $trigger.offset().top - 150
					}
				});
			}
		}
	});
});
