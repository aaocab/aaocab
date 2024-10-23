

var ctLat = 0.0;
var ctLon = 0.0;
var ctELat = 0.0;
var ctELng = 0.0;
var ctWLat = 0.0;
var ctWLng = 0.0;

function initializepl(booking_type = 1, transfer_type = 0) {


	var acInputs = document.getElementsByClassName("txtpl");
	var len = acInputs.length;
	var i = 0;
	var j = 0;
	if (booking_type == '4') {
		len = 1;
	}

	var eastboundLat = 0.0;
	var eastboundLon = 0.0;
	var westboundLat = 0.0;
	var westboundLon = 0.0;
	latlongdiff = 0.00;
	var pluslatlongdiff = latlongdiff;
	var minuslatlongdiff = 0.0 - latlongdiff;

	for (i; i < len; i++) {
		if (booking_type == '4') {
			if (transfer_type == '1') {
				$('#locLat0').val($('#ctyLat0').val()).change();
				$('#locLon0').val($('#ctyLon0').val()).change();
				i = 1;
			} else
			if (transfer_type == '2') {
				$('#locLat1').val($('#ctyLat1').val()).change();
				$('#locLon1').val($('#ctyLon1').val()).change();

				j = i;
			}
		} else {
			if ($('#city_is_airport' + i).val() == 1) {
				$('#locLat' + i).val($('#ctyLat' + i).val()).change();
				$('#locLon' + i).val($('#ctyLon' + i).val()).change();
			}
			if ($('#city_is_poi' + i).val() == 1) {
				$('#locLat' + i).val($('#ctyLat' + i).val()).change();
				$('#locLon' + i).val($('#ctyLon' + i).val()).change();
			}
			j = i;
		}
		var cLatId = 'ctyLat' + i;
		var cLonId = 'ctyLon' + i;
		var cELatId = 'ctyELat' + i;
		var cELonId = 'ctyELng' + i;
		var cWLatId = 'ctyWLat' + i;
		var cWLonId = 'ctyWLng' + i;

//        var locLat = 'locLat' + i;
//        var locLon = 'locLon' + i;
		$(acInputs[j].id).attr("autocomplete", "disabled");


		if ($('#' + cELatId).val() > 0) {
			latLngType = 1;
		} else {
			latLngType = 2;
		}
		if (latLngType == 1) {
			westboundLat = $('#' + cELatId).val() - minuslatlongdiff;
			westboundLon = $('#' + cELonId).val() - minuslatlongdiff;
			eastboundLat = $('#' + cWLatId).val() - pluslatlongdiff;
			eastboundLon = $('#' + cWLonId).val() - pluslatlongdiff;

		} else if (latLngType == 2) {
			ctLat = $('#' + cLatId).val();
			ctLon = $('#' + cLonId).val();

			eastboundLat = ctLat - 0.05;
			eastboundLon = ctLon - 0.05;
			westboundLat = ctLat - 0.0 + 0.05;//parseFloat
			westboundLon = ctLon - 0.0 + 0.05;
//
			$('#' + cELatId).val(westboundLat);
			$('#' + cELonId).val(westboundLon);
			$('#' + cWLatId).val(eastboundLat);
			$('#' + cWLonId).val(eastboundLon);

		}
//         alert(eastboundLat + ' : ' + eastboundLon + ' : ' + westboundLat + ' : ' + westboundLon);
		var defaultBounds = new google.maps.LatLngBounds(
				new google.maps.LatLng(eastboundLat, eastboundLon),
				new google.maps.LatLng(westboundLat, westboundLon));
		 var options = {
                    types: [],
                    fields: ['address_components', 'geometry', 'place_id', 'formatted_address'],
                    bounds: defaultBounds,
                    strictBounds: 1,
                    componentRestrictions: {country: 'IN'}
                };

		if ($('#city_is_airport' + i).val() != 1 || $('#city_is_poi' + i).val() != 1) {
			autocomplete = new google.maps.places.Autocomplete(acInputs[j], options);
			$(".txtpl").attr("autocomplete", "disabled");
			autocomplete.inputId = acInputs[j].id;
			loadAddress(acInputs[j].id, autocomplete);
		}
}
}
function loadAddress(placeBoxId, autocomplete) {
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		var place = autocomplete.getPlace();
		placeTypes = place.types;

		placeTypes.forEach(function (element) {
//            alert(element);
		});

//            var placeid = place.place_id;
//            var address = place.formatted_address;
		var latitude = place.geometry.location.lat();
		var longitude = place.geometry.location.lng();
//        alert("formatted_address: "+ place.formatted_address);
//      alert("place_id: "+ place.place_id);

//            var mesg = "Address: " + address;
//            mesg += "\nLatitude: " + latitude;
//            mesg += "\nLongitude: " + longitude;
//            mesg += "\nplaceid: " + placeid;

//                      alert(locLat);


		textName1 = 'locLon';
		textBoxName = 'brt_location';
		preNumLen = textBoxName.length;

		plNumLen = placeBoxId.length;

		suffixNum = placeBoxId.substr(preNumLen, plNumLen - preNumLen);

		if ((($('#ctyELat' + suffixNum).val() - 0.0 + 0.05) >= latitude && latitude >= ($('#ctyWLat' + suffixNum).val() - 0.05)) &&
				(($('#ctyELng' + suffixNum).val() - 0.0 + 0.05) >= longitude && longitude >= ($('#ctyWLng' + suffixNum).val() - 0.05)))
		{

		} else {
			alert('The location is far off from the city. Please enter correct location address.');
			$('#' + placeBoxId).val('');
			$('#locLat' + suffixNum).val('').change();
			$('#locLon' + suffixNum).val('').change();
			return false;
		}

		if ($('#' + placeBoxId).val().length < 25 ||
				(placeTypes.indexOf("sublocality") == -1 && placeTypes.indexOf("political") > -1))
		{
			place = null;
			$('#' + placeBoxId).val('');
			alert('Enter proper address rather than city or region name.');
			$('#locLat' + suffixNum).val('').change();
			$('#locLon' + suffixNum).val('').change();
			return false;
		}

		if (latitude > 0 && longitude > 0) {
			$('#locLat' + suffixNum).val(latitude).change();
			$('#locLon' + suffixNum).val(longitude).change();
			$('#locFAdd' + suffixNum).val(place.formatted_address).change();
			$('#locPlaceid' + suffixNum).val(place.place_id).change();
		}
	});
}

$('.txtpl').change(function () {
	var placeBoxId = this.id;
	autocomplete.inputId = placeBoxId;
	loadAddress(placeBoxId);
});

var autocomplete;

