jQuery(document).ready(function($){

	$('.extra-accordion > .wpa_loop').each(function(index, element) {

		initAccordion($(element), index);

	});

	function initAccordion($wrapper, index) {

		if($wrapper.closest(".tocopy").length) {
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
			updateMenu();

			//$wrapper.find('> .wpa_group:last-child > .extra-accordion-handle').trigger('click');
			var last = $wrapper.find('> .wpa_group:not(.tocopy)').size() - 1;
			$wrapper.accordion('option', 'active', last);
		});
		$.wpalchemy.bind('wpa_delete', function(event) {
			updateMenu();
		});

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
				header: '> .wpa_group > .extra-accordion-handle'
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
			console.log('sortable');

			// MAKE IT SORTABLE
			//$nav.sortable({
			//	containment: "parent",
			//	forcePlaceholderSize: true,
			//	opacity: 1,
			//	placeholder: "extra-accordion-placeholder",
			//	start: function(event, ui) {
			//		$nav.children().each(function(index, element) {
			//			var item = $(element),
			//				$target = $("#"+item.attr("aria-controls")),
			//				$editors =  $target.find('.extra-editor-processed');
			//			// shut down the editors
			//			if($editors.length) {
			//				$editors.each(function(index, element) {
			//					var textarea = $(this).find('textarea.extra-custom-editor'),
			//						textareaId = textarea.attr('id'),
			//						editor = tinymce.EditorManager.get(textareaId);
			//					textarea.data('tinymceSettings', editor.settings);
			//					tinymce.settings.wpautop = false;
			//					tinymce.execCommand('mceRemoveEditor', false, textareaId);
			//				});
			//			}
			//		});
			//	},
			//	stop: function(event, ui) {
			//
			//		$nav.children().each(function(index, element) {
			//			var item = $(element),
			//				$target = $("#"+item.attr("aria-controls")),
			//				$editors =  $target.find('.extra-editor-processed');
			//			$wrapper.append($target);
			//
			//			// reset the editors
			//			if($editors.length) {
			//				$editors.each(function() {
			//					var textarea = $(this).find('textarea.extra-custom-editor'),
			//						textareaId = textarea.attr('id');
			//					tinymce.settings = textarea.data('tinymceSettings');
			//					tinymce.execCommand('mceAddEditor', false, textareaId);
			//				});
			//			}
			//		});
			 //
			 //       // refresh the accordion
			//		$wrapper.accordion( "refresh" );
			//	}
			//}).disableSelection();

			// NO MORE FIRST
			if(first) {
				first = false;
			}

		}

		updateMenu();
	}

});
