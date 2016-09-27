$(document).ready(function () {
	extraInitFancybox($("body"));
});
//extra.initFancybox
$window.on('extra:fancybox:init', function (event, $parent) {
	if ($parent && $parent.length) {
		extraInitFancybox($parent);
	}
});
function extraInitFancybox($parent) {
	$parent.find("a[href$='.jpg'], a[href$='.jpeg'], a[href$='.png'], a[href$='.gif'], a[href$='.svg'], .fancybox").not('.no-fancybox').filter(function () {
		return $(this).attr("target") != "_blank";
	}).attr("data-fancybox-group", "gallery").each(function () {
		var $this = $(this),
			$img = $this.find(" > img").first();
		if ($img.length) {
			$(this).addClass("zoom");
		}
		if ($this.next(".wp-caption-text").length) {
			console.log(defaultOptions);
			defaultOptions['fancyboxOptions']['beforeShow'] = function () {
				this.title = $this.next(".wp-caption-text").html();
			}
		}
		/*if ($img.hasClass("alignleft")) {
		 $this.addClass("alignleft");
		 }
		 if ($img.hasClass("alignright")) {
		 $this.addClass("alignright");
		 }*/
	}).fancybox(defaultOptions.fancyboxOptions);
}
/*********************
 *
 * ALL LINKS TO IMAGES
 *
 *********************/
var defaultOptions = {
	fancyboxOptions: {
		margin : 50,
		padding: 0,
		type   : 'image',
		helpers: {
			title: {
				type: 'over'
			}
		}
	}
};
$.extend(defaultOptions, extraOptions);