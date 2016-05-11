jQuery(document).ready(function ($) {

	$.wpalchemy.on('wpa_copy', function (e, elmt) {
		extra_process_map($(elmt));
	});

	function extra_process_map(elmt) {

		if (elmt === undefined) {
			elmt = $('.extra-map:not(".extra-map-processed")');
		}

		if (!elmt.length) {
			return;
		}

		elmt.not('.extra-map-processed').each(function () {

			if ($(this).closest('.wpa_group.tocopy').length) {
				return;
			}

			/***********************
			 *
			 *
			 * VARS
			 *
			 *
			 ***********************/
			var $element = $(this),
				geocoder = new google.maps.Geocoder(),
				$mapContainer = $element.find(".map-container"),
				$address = $element.find(".address"),
				$lat = $element.find(".lat"),
				$lon = $element.find(".lon"),
				lat = $lat.val() != '' ? $lat.val() : $lat.data("default"),
				lon = $lon.val() != '' ? $lon.val() : $lon.data("default");

			$element.addClass("extra-map-processed");
			/***********************
			 *
			 *
			 * MAP
			 *
			 *
			 ***********************/
			var mapOptions = {
				center   : new google.maps.LatLng(lat, lon),
				zoom     : 15,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			var map = new google.maps.Map($mapContainer[0], mapOptions);
			$mapContainer.data("map", map);
			/***********************
			 *
			 *
			 * MARKER
			 *
			 *
			 ***********************/
			var markerLatLng = new google.maps.LatLng(lat, lon);
			var marker = new google.maps.Marker({
				position : markerLatLng,
				map      : map,
				draggable: true
			});
			/***********************
			 *
			 *
			 * EVENTS POUR LE MARKER
			 *
			 *
			 ***********************/
			google.maps.event.addListener(map, "click", function (event) {
				marker.setPosition(event.latLng);
				update();
			});
			google.maps.event.addListener(marker, "dragend", function () {
				update();
			});
			/***********************
			 *
			 *
			 * UPDATE DU MARKER
			 *
			 *
			 ***********************/
			function update() {
				var latlng = marker.getPosition();
				$lat.val(latlng.lat());
				$lon.val(latlng.lng());
			}

			if ($address.val() == "") {
				update();
			}
			/***********************
			 *
			 *
			 * ADDRESS BUTTON
			 *
			 *
			 ***********************/
			var addressBtn = $("<a>Afficher sur la carte</a>").attr({
				"href" : "#",
				"class": "addressBtn button"
			}).insertAfter($address);
			var addressMsg = $("<span />").attr({
				"class": "addressMsg"
			}).insertAfter(addressBtn);
			/***********************
			 *
			 *
			 * UPDATE DU CHAMP DE TEXTE DE L'ADRESSE
			 *
			 *
			 ***********************/
			addressBtn.click(function () {
				addressUpdate();
				return false;
			});
			function addressUpdate() {
				geocoder.geocode({'address': $address.val()}, function (results, status) {
					if (status == "OK" && results && results[0]) {

						var lat = results[0].geometry.location.lat(),
							lon = results[0].geometry.location.lng(),
							latLng = new google.maps.LatLng(lat, lon);

						$lat.val(lat);
						$lon.val(lon);

						marker.setPosition(latLng);

						map.panTo(latLng);
						map.setZoom(15);

						addressMsg.text("");
					} else {
						addressMsg.text(status);
					}
				});
			}
		});
	}

	extra_process_map();


});