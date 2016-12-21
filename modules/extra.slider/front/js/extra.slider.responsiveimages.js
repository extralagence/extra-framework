$(document).ready(function () {
	///////////////////////////////////////
	//
	//
	// CLONE RESPONSIVE IMAGE MANAGEMENT
	//
	//
	///////////////////////////////////////
	$window.on('extra:slider:updateClones', function (event, currentItem, currentIndex) {

		var $slider = $(event.target),
			$clones = $slider.find('.extra-slider-clone');

		// console.log($clones.length);

		$window.trigger('extra:responsiveImage:init', [$clones]);
	});
});