<?php
$widgetId = "AMA" . mt_rand(999,9999999);
?>
<div id="pac-card" class="">
	<a href="#" class="pac-map-close" style="padding: 0px;"><i class="fas fa-arrow-left color-gray-dark"></i></a>
	<input id="pac-input" type="text" placeholder="Enter a location" class="search"><div id="location-error"></div>
	<input id="pac-hidden" type="hidden" class="hdn<?=$widgetId?>"/>
<span id="pac-btn"><i class="fas fa-check-circle color-green2-light font-25"></i></span>
</div>
<div id="map"></div>
<div id="infowindow-content" style="width: 180px;">
	<img src="" width="16" height="16" id="place-icon">
	<span id="place-name"  class="title"></span><br>
</div>
<script>
    var pacObject_marker;
    var pam_model = {};

    function initMap(pacObject)
    {
        pacObject_marker = new placeAutoComplete("pac-input", "pac-hidden","<?=$widgetId?>");
        pacObject_marker.onPlaceChange(function ()
        {
			var check = pacObject_marker.validateAddress();
			if(!check)
			{
				return;
			}
            pacObject_marker.geocode = new geocodeMarker(pacObject_marker.getValueObject(), null, document.getElementById("map"));
            pacObject_marker.geocode.initMap();
        });
        pam_model.callbackObject = pacObject;
        pacObject_marker.setValue(pacObject.getValueObject());
        pacObject_marker.initControl(pacObject.model.options);
        var bounds = null;
        if (pacObject.model.options !== undefined && pacObject.model.options !== null)
        {
            bounds = pacObject.model.options.bounds;
        }
        pacObject_marker.geocode = new geocodeMarker(pacObject.getValueObject(), bounds, document.getElementById("map"));

        pacObject_marker.geocode.initMap();
        pacObject_marker.geocode.onPositionChange(function (event, obj)
        {
			pacObject_marker.model.googlePlaceObject = obj.model.googlePlaceObject;
            pacObject_marker.setValue(obj.model.placeObj);
        });
        $("#pac-btn").unbind("click").on("click", function ()
        {
			var check = pacObject_marker.validateAddress();
			if(!check)
			{
				return;
			}
			$('#map-marker').find('.pac-map-close').click();
            pam_model.callbackObject.setValue(pacObject_marker.getValueObject());
		});
    }

	$('.pac-map-close').unbind("click").on('click',function(){
        $('.menu-box').removeClass('menu-box-active');
        $('#menu-hider').removeClass('menu-hider-active');
        return false;
    });
</script>
