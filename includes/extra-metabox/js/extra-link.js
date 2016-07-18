jQuery(function ($) {

	function initializeExtraLink(elmt) {

		if (elmt === undefined) {
			elmt = $('.extra-link-container:not(".extra-link-processed")');
		}

		if (!elmt.hasClass('extra-link-container')) {
			elmt = elmt.find('.extra-link-container');
		}


		elmt.not('.extra-link-processed').each(function () {


			var $this = $(this),
				$manualRadio = $this.find('.extra-link-manual .extra-link-radio'),
				$contentRadio = $this.find('.extra-link-content .extra-link-radio'),
				$taxonomyRadio = $this.find('.extra-link-taxonomy .extra-link-radio'),

				$autocompleteInput = $this.find('.extra-link-autocomplete'),
				$autocompleteInputHidden = $this.find('.extra-link-autocomplete-hidden'),
				$extraLinkChoice = $this.find('.extra-link-choice'),

				$autocompleteInputTaxonomy = $this.find('.extra-link-autocomplete-taxonomy'),
				$autocompleteInputTaxonomyHidden = $this.find('.extra-link-autocomplete-taxonomy-hidden'),
				$autocompleteInputTaxonomySlugHidden = $this.find('.extra-link-autocomplete-taxonomy-slug-hidden'),
				$extraLinkChoiceTaxonomy = $this.find('.extra-link-choice-taxonomy'),

				$extraLinkTitleInput = $this.find('.extra-link-title'),
				$extraLinkUrlInput = $this.find('.extra-link-url')
				;

			$manualRadio.on('click', function () {
				$autocompleteInput.prop('disabled', true);
				$autocompleteInputTaxonomy.prop('disabled', true);
				$extraLinkUrlInput.prop('disabled', false);
				$extraLinkChoice.hide();
				$extraLinkChoiceTaxonomy.hide();

				$extraLinkUrlInput.focus();
			});
			$contentRadio.on('click', function () {
				$extraLinkUrlInput.prop('disabled', true);
				$autocompleteInputTaxonomy.prop('disabled', true);
				$autocompleteInput.prop('disabled', false);
				$extraLinkChoiceTaxonomy.hide();
				$extraLinkChoice.show();

				$autocompleteInput.focus();
			});
			$taxonomyRadio.on('click', function () {
				$extraLinkUrlInput.prop('disabled', true);
				$autocompleteInput.prop('disabled', true);
				$autocompleteInputTaxonomy.prop('disabled', false);
				$extraLinkChoice.hide();
				$extraLinkChoiceTaxonomy.hide();

				$autocompleteInputTaxonomy.focus();
			});

			/*********************
			 *
			 *
			 * AUTOCOMPLETE FOR CONTENT
			 *
			 *
			 ********************/
			$autocompleteInput.autocomplete({
				source   : function (req, callback) {
					$.ajax({
						url     : ajax.url,
						type    : 'get',
						dataType: 'json',
						data    : {
							'action': 'extra-link',
							'term'  : req.term
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
					$extraLinkChoice.html(ui.item.url);
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
						.append("<a class=\"extra-link-search-link\" href=\"#\"><span class=\"extra-link-search\">" + item.highlight_title + "</span> <span class=\"extra-link-search-type\"><strong>" + item.type + "</strong></span></a>")
						.appendTo(ul);
				};
			}

			$autocompleteInput.focus(function () {
				$autocompleteInput.autocomplete("search");
			});


			/*********************
			 *
			 *
			 * AUTOCOMPLETE FOR TAXONOMY
			 *
			 *
			 ********************/
			$autocompleteInputTaxonomy.autocomplete({
				source   : function (req, callback) {
					$.ajax({
						url     : ajax.url,
						type    : 'get',
						dataType: 'json',
						data    : {
							'action': 'extra-link-taxonomy',
							'term'  : req.term
						},
						async   : true,
						cache   : true,
						success : function (data) {
							var suggestions = [];
							//process response
							$.each(data, function (i, val) {
								suggestions.push({
									"slug"           : val.slug,
									"taxonomy"       : val.taxonomy,
									"highlight_title": accent_folded_hilite(val.name, req.term),
									"title"          : val.name,
									"type"           : val.taxonomy_name,
									"url"            : val.url
								});
							});
							//pass array to callback
							callback(suggestions);
						}
					})
				},
				select   : function (event, ui) {
					$extraLinkChoiceTaxonomy.show();
					$extraLinkChoiceTaxonomy.html(ui.item.url);
					$extraLinkTitleInput.val(ui.item.title);
					$autocompleteInputTaxonomyHidden.val(ui.item.taxonomy);
					$autocompleteInputTaxonomySlugHidden.val(ui.item.slug);
					$autocompleteInputTaxonomy.val(ui.item.title);

					return false;
				},
				focus    : function (event, ui) {

					return false;
				},
				minLength: 2
			});

			if ($autocompleteInputTaxonomy.data("ui-autocomplete") != null) {
				$autocompleteInputTaxonomy.data("ui-autocomplete")._renderItem = function (ul, item) {
					return $("<li>")
						.append("<a class=\"extra-link-search-link\" href=\"#\"><span class=\"extra-link-search\">" + item.highlight_title + "</span> <span class=\"extra-link-search-type\"><strong>" + item.type + "</strong></span></a>")
						.appendTo(ul);
				};
			}

			$autocompleteInputTaxonomy.focus(function () {
				$autocompleteInputTaxonomy.autocomplete("search");
			});

			$(document).on('click', '.extra-link-search-link', function (event) {
				event.preventDefault();
			});
		});
	}


	initializeExtraLink($('.extra-link-container'));

	$.wpalchemy.on('wpa_copy', function (e, elmt) {
		initializeExtraLink($(elmt));
	});

});