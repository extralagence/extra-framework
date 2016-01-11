$(document).ready(function() {
	$(".extra-social-button").not('.extra-social-share').each(function() {
		var $this = $(this),
			url = $this.data("url"),
			counter = $this.data("counter");
		$.getJSON($(this).data('counter'), function(data) {
			if(data['count']) {
				$this.find('.counter').text(data['count']);
			} else {
				$.each(data, function (key, val) {
					if (val['shares']) {
						$this.find('.counter').text(val['shares']);
					}
				});
			}
		});
		$this.on('click', function(e) {
			e.preventDefault();
			window.open($this.attr('href'),"Partage","menubar=no, status=no, scrollbars=no, menubar=no, width=600, height=500");
		});
	});

	$('.extra-social-share').fancybox({
		margin: 0,
		padding: 0
	});


});