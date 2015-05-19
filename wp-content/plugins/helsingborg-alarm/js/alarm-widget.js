jQuery(document).ready(function() {
	var municipalities = jQuery("select#municipality_multiselect");

	if (municipalities.val()) {
		municipalities.zmultiselect({
			live: "#selectedMunicipality",
			filter: true,
			filterResult: true,
			selectedText: ['Valt', 'av'],
			selectAll: true,
			addButton: onUpdateClick,
			selectAllText: ['Markera alla', 'Avmarkera alla']
		});

		jQuery(document).on('click', '.modalLinkAlarm', function(event) {
			event.preventDefault();

			var _title = jQuery('.main-title');
			var _date = jQuery('.modalDate');
			var _event = jQuery('.modalEvent');
			var _station = jQuery('.modalStation');
			var _id = jQuery('.modalID');
			var _state = jQuery('.modalState');
			var _address = jQuery('.modalAddress');
			var _location = jQuery('.modalLocation');
			var _area = jQuery('.modalArea');
			var _municipality = jQuery('.modalMunicipality');
			var _moreinfo = jQuery('.modalMoreInfo');
			var result;

			for (var i = 0; i < _alarms.length; i++) {
				if (_alarms[i].ID === this.id) {
					result = _alarms[i];
				}
			}

			moreInfoText = '-';
			if (result.MoreInfo != '') {
				moreInfoText = result.MoreInfo;
			}

			jQuery(_title).html(result.HtText);
			jQuery(_date).html(result.SentTime);
			jQuery(_event).html(result.HtText);
			jQuery(_station).html(result.Station);
			jQuery(_id).html(result.ID);
			jQuery(_state).html(result.PresGrp);
			jQuery(_address).html(result.Address);
			jQuery(_location).html(result.Place);
			jQuery(_area).html(result.Zone);
			jQuery(_municipality).html(result.Zone);
			jQuery(_moreinfo).html(moreInfoText);
		});
	}

	function onUpdateClick() {
		setupMarkers();
		jQuery("select#municipality_multiselect").zmultiselect('close')
		var selectedValues = jQuery("select#municipality_multiselect").zmultiselect(
			'getValue');
		if (selectedValues == '') {
			selectedValues = 'Helsingborg';
			jQuery("select#municipality_multiselect").zmultiselect('set',
				'Helsingborg', true);
		}

		jQuery.ajax({
			type: 'GET',
			url: ajaxalarm.url,
			data: {
				action: 'get_alarm_for_cities',
				options: selectedValues.join(';')
			},
			success: function(result) {
				if (result) {
					var data = jQuery.parseJSON(result);
					_alarms = data.GetAlarmsForCitiesResult;
					updateList(data.GetAlarmsForCitiesResult);
				}
			}
		});
	}

	function updateList(items) {
		jQuery('.alarm-list').empty();
		jQuery.each(items, function(i, item) {
			var alarm = '<li>';
			alarm += '<span class="date">' + item.SentTime + '</span>';
			alarm += '<a href="#" class="modalLinkAlarm" id="' + item.ID +
				'" data-reveal-id="alarmModal">' + item.HtText + '</a>';
			alarm += '</li>';
			jQuery(alarm).appendTo(jQuery('.alarm-list'));
			return i < (_amount - 1);
		});
	}

	var mapCanvas = document.getElementById('map-canvas');

	if (mapCanvas) {
		var mapOptions = {
			zoom: 9,
			center: new google.maps.LatLng(56.100769, 12.854576)
		};
		var map = mapCanvas ? new google.maps.Map(mapCanvas, mapOptions) : null;
		var infowindow = new google.maps.InfoWindow({
			content: ""
		});
		var bounds = new google.maps.LatLngBounds();
		var markers = [];
		var infoArray = [];
		var counter = 0;
		var options;

		function setupMarkers() {
			if (map) {
				var select = jQuery("select#municipality_multiselect");
				if (select.val()) {
					var selectedValues = select.zmultiselect('getValue');
					if (selectedValues) {
						options = selectedValues.join(",");
					}
				}
				loadMarkers(options);
			}
		}

		function loadMarkers(options)  {
			jQuery.ajax({
				type: 'GET',
				url: ajaxalarm.url,
				data: {
					action: 'get_markers',
					options: options
				},
				success: function(result) {
					if (result)
						setMarkers(map, jQuery.parseJSON(result));
				}
			});
		}

		function removeMarkers() {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(null);
			}
			markers = [];
		}

		function setMarkers(map, locations) {
			removeMarkers();
			bounds = new google.maps.LatLngBounds();
			for (var i = 0; i < locations.length; i++) {
				var alarm = locations[i];
				var myLatLng = new google.maps.LatLng(alarm.Latitude, alarm.Longitude);
				bounds.extend(myLatLng);
				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					html: '<div id="infowindow" style="height:50px;"><div><b>' + alarm.Time +
						'</b></div><div>' + alarm.Information + '</div></div>',
					title: alarm.Information,
					icon: alarm.Icon
				});

				google.maps.event.addListener(marker, 'click', function() {
					infowindow.setContent(this.html);
					infowindow.open(map, this);
				});

				markers.push(marker);
			}
			map.fitBounds(bounds);
			google.maps.event.trigger(map, "rezise");
		}

		google.maps.event.addDomListener(window, 'load', setupMarkers);
	}
});
