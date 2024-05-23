<style>
	.modal-open .modal{ z-index: 1000!important;}
	.modal.in .modal-dialog{
		-webkit-box-shadow: -1px 6px 20px 2px rgba(0,0,0,0.31)!important;
		-moz-box-shadow: -1px 6px 20px 2px rgba(0,0,0,0.31)!important;
		box-shadow: -1px 6px 20px 2px rgba(0,0,0,0.31)!important;
	}
  #map {
    height: 600px;
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
    left: 800px;
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

</style>

<div class="pac-card" id="pac-card">
	<div>
        <div id="label" style="font-size: 19px;">
			Enter approximate <?= $location?> location and search 
        </div>       
	</div>
	<div id="pac-container">
        <input id="pac-input" type="text"
			   placeholder="Enter a location" class="search"><div id="location-error"></div>
		<button type="button" class="btn btn-success" id="pac-btn">Submit</button>
	</div>
</div>
<div id="map"></div>
<div id="infowindow-content" style="width: 180px;">
	<img src="" width="16" height="16" id="place-icon">
	<span id="place-name"  class="title"></span><br>
</div>
<input type="hidden" id="plc-add">
<input type="hidden" id="plc-lat">
<input type="hidden" id="plc-long">
<input type="hidden" id="plc-plcid">
<input type="hidden" id="plc-isAirport">
<input type="hidden" id="plc-types">
<script>
	var hyperMarkerModel = new HyperMarkerLocation('<?= Config::getGoogleApiKey('browserapikey') ?>');
	var model = {};
	$(document).ready(function ()
	{
		setHyperMarkerLocationData();
		setTimeout(function(){document.getElementById('pac-input').select();}, 300);
	});
	function setHyperMarkerLocationData()
	{
		model.ctyLat = <?= $ctyLat ?>;
		model.ctyLon = <?= $ctyLon ?>;
		<?php if($bound != ''){ ?>
			model.bound = <?= $bound ?>;
		<?php } ?>
		model.isCtyAirport = <?= $isCtyAirport ?>;
		model.isCtyPoi = <?= $isCtyPoi ?>;
		model.airport  = <?= $airport ?>;
		model.hyperLocationClass = 'search';
		hyperMarkerModel.model = model;
		hyperMarkerModel.initializepl();
	}
	
	$("#pac-btn").click(function(){
		var key = <?= $locKey?>;
		var plcAdd =  $("#plc-add").val();
		var pacInput = $("#pac-input").val();
	    var result = plcAdd.localeCompare(pacInput);
		if(result == 1 || result == -1){
			$("#pac-input").val(plcAdd);
		}
		<?php if($airport == 1){?>
			getCityIdByLatLong(key);
			$('#locLat' + key).val($("#plc-lat").val());
			$('#locLon' + key).val($("#plc-long").val());
			$('#locFAdd' + key).val($("#plc-add").val());
			$('#locPlaceid' + key).val($("#plc-plcid").val());
			$('#brt_location' + key).val($("#pac-input").val());
			$('#isAirport' + key).val($("#plc-isAirport").val());
		<?php }else{ ?>
			$('.locLat_' + key).val($("#plc-lat").val());
			$('.locLon_' + key).val($("#plc-long").val());
			$('.locFAdd_' + key).val($("#plc-add").val());
			$('.locPlaceid_' + key).val($("#plc-plcid").val());
			$('.brt_location_' + key).val($("#pac-input").val());
		<?php } ?>
			$(".cpy_loc_"+key).val($("#plc-add").val());
			$('.modal').modal('hide');
	});
	
	function getCityIdByLatLong(key)
	{
		var types = $("#plc-types").val();
		types     = (types == '' || types == "undefined" || types == undefined)? '':JSON.parse(types);
		$.ajax({
			type: "GET",
			dataType: "json",
			url: $baseUrl + "/city/cityId",
			data: {'cLat':$("#plc-lat").val(),'cLong':$("#plc-long").val(),'placeId':$("#plc-plcid").val(),'formattedAddress':$("#plc-add").val(),'types':types,'isAirport':0,'ctyId':''},
			success: function (data1)
			{
				data = data1;
				if(data.ctyId > 0)
				{
					$('#ctyIdAir'+key).val(data.ctyId)
					if(key == 0)
					{
						$('#Booking_bkg_from_city_id').val(data.ctyId);
					}
					else
					{
						$('#Booking_bkg_to_city_id').val(data.ctyId);
					}
				}

			},
			error: function (error)
			{
				console.log(error);
			}
		});
	}
</script>
