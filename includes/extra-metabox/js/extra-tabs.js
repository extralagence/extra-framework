jQuery(document).ready(function ($) {
	var $window = $(window);

	function initialize() {
		var $parents = $('.extra-tabs');
		$parents.each(function (index, element) {
			var $parent = $(this);
			$parent.find(">.wpa_loop").each(function (index, element) {
				initTabs($(element), index, $parent);
			});
		});
	}

	function initTabs($wrapper, index, $parent) {

		if ($wrapper.closest(".tocopy").length || $wrapper.data('extra-tabs-processed') == 'processed') {
			return;
		}
		$wrapper.attr("id", $wrapper.attr("id") + '-' + index).data("extra-tabs-processed", "processed");

		var $nav = $('<ul class="extra-tab-navigation"></ul>').prependTo($wrapper),
			idBase = $wrapper.attr("id"),
			first = true,
			wpautop = true,
			textareaID, selectedEd;


		$.wpalchemy.bind('wpa_copy', function (event, $clone) {
			if ($clone.parent()[0] !== $wrapper[0]) {
				return;
			}

			updateMenu();
			if ($nav.children().size()) {
				$nav.find(" > li:last a").click();
			}
			if ($clone.find(".extra-tabs > .wpa_loop")) {
				$clone.find(".extra-tabs > .wpa_loop").not('.tocopy').each(function (index, element) {
					var $item = $(element);
					if (!$item.data("extra-tabs-processed") || $item.data("extra-tabs-processed") != "processed") {
						initTabs($item);
					}
				});
			}
		});
		$.wpalchemy.bind('wpa_delete', function (event) {
			updateMenu();
		});

		function refresh() {
			$wrapper.tabs('refresh');
		}

		function updateMenu() {

			// IS IT FIRST INIT
			if (!first) {
				$wrapper.tabs("destroy");
				$nav.sortable("destroy");
			}

			// EMPTY THE LIST
			$nav.empty();


			// LOOP THROUGH THE ITEMS 
			$wrapper.find(" > .wpa_group").not('.tocopy').each(function (i) {

				var $item = $(this);
				var title = $item.find("h2").first(),
					titleTextField = $parent.data('title_text_field');
				$item.attr("id", (idBase + '' + i));

				if (!$item.data("extra-tabs-item-processed") || $item.data("extra-tabs-item-processed") != "processed") {
					title.text(title.text() + " " + (i + 1));
					$item.data("extra-tabs-item-processed", "processed");
				}

				var link = $("<a></a>", {
					"href": "#" + (idBase + '' + i),
					"text": title.text()
				});

				if (titleTextField !== '') {
					var $inputText = $item.find('.extra-text-input[data-name="' + titleTextField + '"]').first();

					$inputText.on('keyup', function () {
						link.text((($inputText.val() != '') ? $inputText.val() : link.data('default-text')));
					});
					link.data('default-text', link.text());
					link.text((($inputText.val() != '') ? $inputText.val() : link.data('default-text')));

				}

				$nav.append(link);
				link.wrap("<li />");

				$window.trigger('extra:admin:tabs:newItem');
			});


			// SET WRAPPER 
			$wrapper.tabs({
				activate: function (event, ui) {
					$window.trigger('extra:admin:tabs:refresh');
				}
			});

			// MAKE IT SORTABLE
			$nav.sortable({
				containment: "parent",
				forcePlaceholderSize: true,
				opacity: 1,
				placeholder: "extra-tabs-placeholder",
				start: function (event, ui) {
					$nav.children().each(function (index, element) {
						var item = $(element),
							$target = $("#" + item.attr("aria-controls")),
							$editors = $target.find('.extra-editor-processed');
						// shut down the editors
						if ($editors.length) {
							$editors.each(function (index, element) {
								var textarea = $(this).find('textarea.extra-custom-editor'),
									textareaId = textarea.attr('id'),
									editor = tinymce.EditorManager.get(textareaId);
								textarea.data('tinymceSettings', editor.settings);
								tinymce.settings.wpautop = false;
								tinymce.execCommand('mceRemoveEditor', false, textareaId);
							});
						}
					});
				},
				stop: function (event, ui) {

					$nav.children().each(function (index, element) {
						var item = $(element),
							$target = $("#" + item.attr("aria-controls")),
							$editors = $target.find('.extra-editor-processed');
						$wrapper.append($target);

						// reset the editors
						if ($editors.length) {
							$editors.each(function () {
								var textarea = $(this).find('textarea.extra-custom-editor'),
									textareaId = textarea.attr('id');
								tinymce.settings = textarea.data('tinymceSettings');
								tinymce.execCommand('mceAddEditor', false, textareaId);
							});
						}
					});
					// refresh the tabs
					$wrapper.tabs("refresh");
				}
			}).disableSelection();

			// NO MORE FIRST
			if (first) {
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