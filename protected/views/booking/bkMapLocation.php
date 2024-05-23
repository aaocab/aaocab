<style>
	#map {
		height: 100%;
		width: 100%;
	}
	html, body {
		width:100%;
		height:550px;
	}

	.pac-card {
		margin: 10px 10px 0 0;
		border-radius: 2px 0 0 2px;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
		outline: none;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
		background-color: #fff;
		font-family: Roboto;
	}

	#pac-container {
		padding-bottom: 2px;
		margin-right: 12px;
	}

	.pac-controls {
		display: inline-block;
		padding: 5px 11px;
	}

	.pac-controls label {
		font-family: Roboto;
		font-size: 13px;
		font-weight: 300;
	}

	#pac-input {
		background-color: #fff;
		font-family: Roboto;
		font-size: 15px;
		font-weight: 300;
		margin: 12px;
		padding: 5px 10px;
		text-overflow: ellipsis;
		width: 350px;
		border: #e0e0e0 1px solid;
	}

	#pac-input:focus {
		outline: none;
	}

	#label {
		color: #fff;
		background-color: #4d90fe;
		font-size: 25px;
		font-weight: 500;
		padding: 6px 12px;
	}
	div#pac-card.pac-card{
		z-index: 2;
		position: absolute;
		left: 630px;
		top: 0px;
	}

	#location-error {
		display: inline-block;
		padding: 6px;
		background: #e4a7a7;
		border: #d49c9c 1px solid;
		font-size: 1.3em;
		color: #333;
		display:none;
		margin: 12px;
	}
	.bootbox{
		z-index: 5 !important;
	}
</style>

<div class="pac-card" id="pac-card">
	<div>
        <div id="label" style="font-size: 23px;">
			Enter approximate <?= $location ?> location and search 
        </div>       
	</div>
	<div id="pac-container">
        <input id="pac-input" type="text" placeholder="Enter a location" class="search"><div id="location-error"></div>
		<input id="pac-hidden" type="hidden"/>
		<button type="button" class="btn btn-success" id="pac-btn">Submit</button>
	</div>
</div>
<div id="map"></div>
<div id="infowindow-content" style="width: 180px;">
	<img src="" width="16" height="16" id="place-icon">
	<span id="place-name"  class="title"></span><br>
</div>
<script>
    var pacObject_marker;
    var pam_model = {};
    $(document).ready(function ()
    {
        pacObject_marker = new placeAutoComplete(document.getElementById("pac-input"), document.getElementById("pac-hidden"));
        pacObject_marker.onPlaceChange(function ()
        {
            pacObject_marker.geocode = new geocodeMarker(pacObject_marker.getValueObject(), document.getElementById("map"));
        });
        pacObject_marker.setValue(<?= $place ?>);
    });
	$('.pac-map-close').unbind("click").on('click',function(){
        $('.menu-box').removeClass('menu-box-active');
        $('#menu-hider').removeClass('menu-hider-active');
        return false;
    });
    function initMap(pacObject)
    {
        pam_model.callbackObject = pacObject;
        pacObject_marker.setValue(pacObject.getValueObject());
        pacObject_marker.initControl();
        pacObject_marker.geocode.initMap(function (placeObj)
        {
            pacObject_marker.setValue(placeObj);
        });

        $("#pac-btn").unbind("click").on("click", function ()
        {
            pam_model.callbackObject.setValue(pacObject_marker.getValueObject());
			$('#map-marker').find('.pac-map-close').click();
        });
    }


</script>
