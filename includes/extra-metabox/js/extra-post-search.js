jQuery(function ($) {

	function initializeExtraLink(elmt) {

		if (elmt === undefined) {
			elmt = $('.extra-post-search-container:not(".extra-post-search-processed")');
		}

		if (!elmt.hasClass('extra-post-search-container')) {
			elmt = elmt.find('.extra-post-search-container');
		}


		elmt.not('.extra-post-search-processed').each(function () {


			var $this = $(this),

				$autocompleteInput = $this.find('.extra-post-search-autocomplete'),
				$autocompleteInputHidden = $this.find('.extra-post-search-autocomplete-hidden'),
				$extraLinkChoice = $this.find('.extra-post-search-choice'),

				$extraLinkTitleInput = $this.find('.extra-post-search-title'),
				$extraLinkUrlInput = $this.find('.extra-post-search-url')
				;

			/*********************
			 *
			 *
			 * AUTOCOMPLETE FOR CONTENT
			 *
			 *
			 ********************/
			console.log($autocompleteInput.data('post-type'));

			$autocompleteInput.autocomplete({
				source   : function (req, callback) {
					$.ajax({
						url     : ajax.url,
						type    : 'get',
						dataType: 'json',
						data    : {
							'action': 'extra-post-search',
							'term'  : req.term,
							'post_search_type' : $autocompleteInput.data('post-type')
						},
						async   : true,
						cache   : true,
						success : function (data) {
							var suggestions = [];
							//process response
							$.each(data, function (i, val) {
								suggestions.push({
									"id"             : val.ID,
									"highlight_title": accent_folded_hilite(val.post_title, req.term),
									"title"          : val.post_title,
									"type"           : val.post_type,
									"url"            : val.url
								});
							});
							//pass array to callback
							callback(suggestions);
						}
					})
				},
				select   : function (event, ui) {
					$extraLinkChoice.show();
					$extraLinkChoice.html('<a href="'+ui.item.url+'" target="_blank">'+ui.item.url+'</a>');
					$extraLinkTitleInput.val(ui.item.title);
					$autocompleteInputHidden.val(ui.item.id);
					$autocompleteInput.val(ui.item.title);

					return false;
				},
				focus    : function (event, ui) {

					return false;
				},
				minLength: 2
			});

			if ($autocompleteInput.data("ui-autocomplete") != null) {
				$autocompleteInput.data("ui-autocomplete")._renderItem = function (ul, item) {
					return $("<li>")
						.append("<a class=\"extra-post-search-search-link\" href=\"#\"><span class=\"extra-post-search-search\">" + item.highlight_title + "</span> <span class=\"extra-post-search-search-type\"><strong>" + item.type + "</strong></span></a>")
						.appendTo(ul);
				};
			}

			$autocompleteInput.focus(function () {
				$autocompleteInput.autocomplete("search");
			});

			$(document).on('click', '.extra-post-search-search-link', function (event) {
				event.preventDefault();
			});
		});
	}


	initializeExtraLink($('.extra-post-search-container'));

	$.wpalchemy.on('wpa_copy', function (e, elmt) {
		initializeExtraLink($(elmt));
	});

});