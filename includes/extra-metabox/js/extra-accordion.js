jQuery(document).ready(function($){
	var $window = $(window);

	function initialize() {
		$('.extra-accordion > .wpa_loop').each(function(index, element) {
			initAccordion($(element), index);
		});
	}

	function initAccordion($wrapper, index) {

		console.log('initAccordion : '+$wrapper.data('extra-accordion-processed'));

		if($wrapper.closest(".tocopy").length || $wrapper.data('extra-accordion-processed') == 'processed') {
			return;
		}
		$wrapper.attr("id", $wrapper.attr("id") + '-' + index).data("extra-accordion-processed", "processed");

		var idBase = $wrapper.attr("id"),
			first = true,
			wpautop = true,
			textareaID, selectedEd;


		$.wpalchemy.bind('wpa_copy', function(event, $clone){
			if($clone.parent()[0] !== $wrapper[0]) {
				return;
			}
			console.log('accordion wpa_copy');

			updateMenu();

			//$wrapper.find('> .wpa_group:last-child > .extra-accordion-handle').trigger('click');
			var last = $wrapper.find('> .wpa_group:not(.tocopy)').size() - 1;
			$wrapper.accordion('option', 'active', last);
			$window.trigger('extra:admin:accordion:newItem');
		});
		$.wpalchemy.bind('wpa_delete', function(event) {
			updateMenu();
		});

		function refresh() {
			console.log('refresh accordion');
			$wrapper.accordion('refresh');
		}

		function updateMenu() {

			// IS IT FIRST INIT
			if(!first) {
				$wrapper.accordion("destroy");
				//$nav.sortable("destroy");
			}

			var $accordion = $wrapper.closest('.extra-accordion'),
				titleTextField = $accordion.data('title_text_field');

			// LOOP THROUGH THE ITEMS
			$wrapper.find(" > .wpa_group").not('.tocopy').each(function(i){

				var $item = $(this),
					title = $item.find(" > .extra-accordion-handle").first();
				$item.attr("id", (idBase + '' + i));

				if(!$item.data("extra-accordion-item-processed") || $item.data("extra-accordion-item-processed") != "processed") {
					if (titleTextField !== '') {
						var $inputText = $item.find('.extra-text-input[data-name="'+titleTextField+'"]').first();

						$inputText.on('keyup', function () {
							title.text(title.data('default-text') + (($inputText.val() != '') ? ' - ' + $inputText.val() : ' ' + (i + 1)) );
						});
						title.data('default-text', title.text());
						title.text(title.data('default-text') + (($inputText.val() != '') ? ' - ' + $inputText.val() : ' ' + (i + 1)) );

					} else {
						title.text(title.text() + " " + (i + 1));
					}
					$item.data("extra-accordion-item-processed", "processed");
				}
			});

			// SET WRAPPER
			$wrapper.accordion({
				header: '> .wpa_group > .extra-accordion-handle',
				activate: function (event, ui) {
					console.log('accordion activate');
					$window.trigger('extra:admin:accordion:refresh');
				}
			}).sortable({
				axis: "y",
				handle: ".extra-accordion-handle",
				stop: function( event, ui ) {
					// IE doesn't register the blur when sorting
					// so trigger focusout handlers to remove .ui-state-focus
					ui.item.children( ".extra-accordion-handle" ).triggerHandler( "focusout" );

					// Refresh accordion to handle new order
					$( this ).accordion( "refresh" );
				}
			});

			// NO MORE FIRST
			if(first) {
				first = false;

				$window.on('extra:admin:tabs:refresh', refresh);
				$window.on('extra:admin:accordion:refresh', refresh);
			}

		}

		updateMenu();
	}


	initialize();
	$window.on('extra:admin:tabs:newItem', initialize);
	$window.on('extra:admin:accordion:newItem', initialize);
});
