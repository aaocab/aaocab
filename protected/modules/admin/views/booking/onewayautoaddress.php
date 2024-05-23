<?php
$data		 = CJSON::decode($data);
$locLatval	 = '';
$locLonval	 = '';
$pickupLoc	 = '';
if ($data['pickup_cty_is_airport'] == 1 || $data['pickup_cty_is_poi'] == 1)
{
	$locLatval	 = $data['pickup_cty_lat'];
	$locLonval	 = $data['pickup_cty_long'];
	$pickupLoc	 = $data['pickup_cty_loc'];
}
$locLatvald	 = "";
$locLonvald	 = "";
$dropLoc	 = '';
if ($data['drop_cty_is_airport'] == 1 || $data['drop_cty_is_poi'] == 1)
{
	$locLatvald	 = $data['drop_cty_lat'];
	$locLonvald	 = $data['drop_cty_long'];
	$dropLoc	 = $data['drop_cty_loc'];
}

$ctyLat[0]			 = $data['pickup_cty_lat'];
$ctyLat[1]			 = $data['drop_cty_lat'];
$ctyLon[0]			 = $data['pickup_cty_long'];
$ctyLon[1]			 = $data['drop_cty_long'];
$bound[0]			 = $data['pickup_cty_bounds'];
$bound[1]			 = $data['drop_cty_bounds'];
$isCtyAirport[0]	 = $data['pickup_cty_is_airport'];
$isCtyAirport[1]	 = $data['drop_cty_is_airport'];
$boundisCtyPoi[0]	 = $data['pickup_cty_is_poi'];
$boundisCtyPoi[1]	 = $data['drop_cty_is_poi'];

$mandatoryField = "Optional";
if ($hyperInitialize != 'route')
{
	$mandatoryField = "Required";
}
$initialClass = 'txtpl' . $hyperInitialize
?>
<div class="col-sm-6 pt10 pb10 ">
	<div class="row">
		<div class="col-xs-12">
			<?php
			if ($hyperInitialize != 'route')
			{
				?>
				<label for="pickup_address0" class="control-label text-left">Pickup Address for <?= $data['pickup_city_name'] ?>:</label>
			<?php } ?>
            <input type="hidden" id="ctyLat0" value="<?= $data['pickup_cty_lat'] ?>">
			<input type="hidden" id="ctyLon0" value="<?= $data['pickup_cty_long'] ?>">
			<input type="hidden" id="ctyELat0" value="<?= $data['pickup_cty_ne_lat'] ?>">
			<input type="hidden" id="ctyWLat0" value="<?= $data['pickup_cty_sw_lat'] ?>">
			<input type="hidden" id="ctyELng0" value="<?= $data['pickup_cty_ne_long'] ?>">
			<input type="hidden" id="ctyWLng0" value="<?= $data['pickup_cty_sw_long'] ?>">
			<input type="hidden" id="ctyRad0" value="<?= $data['pickup_cty_radius'] ?>">
			<input name="BookingRoute[0][brt_from_latitude]"  class="locLatVal locLat_0" type="hidden" value="<?= $locLatval ?>">
			<input name="BookingRoute[0][brt_from_longitude]"  class="locLonVal locLon_0" type="hidden" value="<?= $locLonval ?>">
			<input type="hidden" class="locPlaceid_0" name="BookingRoute[0][brt_from_place_id]" value="">
			<input type="hidden" class="locFAdd_0" name="BookingRoute[0][brt_from_formatted_address]" value="">
			<input id="city_is_airport0" name="BookingRoute[0][brt_from_city_is_airport]" type="hidden"  value="<?= $data['pickup_cty_is_airport'] ?>">
			<input id="city_is_poi0" name="BookingRoute[0][brt_from_city_is_poi]" type="hidden"  value="<?= $data['pickup_cty_is_poi'] ?>">
			<input class="brt_location_0 cpy_loc_0" type="hidden"  value="">
			<input type="hidden" class="mapBound_0">
		</div>
		<?php
		if ($hyperInitialize != 'route')
		{
			?>
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-10">
						<div class="form-group">
							<textarea id="loc<?= $hyperInitialize ?>_0" class="form-control brt_location_0 <?= $initialClass ?> form-control route-focus" placeholder="Pickup Address  (<?= $mandatoryField; ?>)" name="BookingRoute[0][brt_from_location]" autocomplete="off" onblur="hyperModel.clearAddress(this)"><?= $pickupLoc ?></textarea>
							<div class="help-block error" id="BookingRoute_0_brt_from_location_em_" style="display:none"></div>
						</div>
					</div>
					<div class="col-xs-2">
						<span class="autoMarkerLoc" data-lockey="0" data-toggle="tooltip" title="Select source location on map" onclick="showMap(this, 'source')"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
<div class="col-sm-6 pb10  pt10">
	<div class="row ">
		<div class="col-xs-12">
			<?php
			if ($hyperInitialize != 'route')
			{
				?>
				<label for="drop_address1" class="control-label text-left">Drop Address for <?= $data['drop_city_name'] ?>:</label>
			<?php } ?>
            <input type="hidden" id="ctyLat1" value="<?= $data['drop_cty_lat'] ?>">
			<input type="hidden" id="ctyLon1" value="<?= $data['drop_cty_long'] ?>">
			<input type="hidden" id="ctyELat1" value="<?= $data['drop_cty_ne_lat'] ?>">
			<input type="hidden" id="ctyWLat1" value="<?= $data['drop_cty_sw_lat'] ?>">
			<input type="hidden" id="ctyELng1" value="<?= $data['drop_cty_ne_long'] ?>">
			<input type="hidden" id="ctyWLng1" value="<?= $data['drop_cty_sw_long'] ?>">
			<input type="hidden" id="ctyRad1" value="<?= $data['drop_cty_radius'] ?>">
			<input name="BookingRoute[1][brt_to_latitude]"  class="locLatVal locLat_1" type="hidden" value="<?= $locLatvald ?>">
			<input name="BookingRoute[1][brt_to_longitude]"  class="locLonVal locLon_1" type="hidden" value="<?= $locLonvald ?>">
			<input type="hidden" class="locPlaceid_1" name="BookingRoute[1][brt_to_place_id]" value="">
			<input type="hidden" class="locFAdd_1" name="BookingRoute[1][brt_to_formatted_address]" value="">
			<input id="city_is_airport1" name="BookingRoute[1][brt_to_city_is_airport]" type="hidden"  value="<?= $data['drop_cty_is_airport'] ?>">
			<input id="city_is_poi1" name="BookingRoute[1][brt_to_city_is_poi]" type="hidden"  value="<?= $data['drop_cty_is_poi'] ?>">
			<input class="brt_location_1 cpy_loc_1" type="hidden"  value="<?=$data['drop_cty_loc']?>">
			<input type="hidden" class="mapBound_1">
		</div>
		<?php
		if ($hyperInitialize != 'route')
		{
			?>
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-10">
						<div class="form-group">
							<textarea value="<?=$data['drop_cty_loc']?>" id="loc<?= $hyperInitialize ?>_1" class="form-control brt_location_1 <?= $initialClass ?> form-control route-focus" placeholder="Drop Address  (<?= $data['booking_type'] == 1 ? $mandatoryField : 'Optional'; ?>)" name="BookingRoute[1][brt_to_location]" autocomplete="off" onblur="hyperModel.clearAddress(this)"><?= $dropLoc ?></textarea>
							<div class="help-block error" id="BookingRoute_1_brt_to_location_em_" style="display:none"></div>
						</div>
					</div>
					<div class="col-xs-2">
						<span class="autoMarkerLoc" data-lockey="1" data-toggle="tooltip" title="Select destination location on map" onclick="showMap(this, 'destination')"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<script>
	var hyperModel = new HyperLocation();
	var isGozonow = '<?php echo $data['isGozonow'] ?>'
	$(document).ready(function () {

		if ($('#bkg_agent_id').val() != '' && $('#bkg_agent_id').val() != undefined)
		{
			$('.brt_location_0').attr('readonly', false);
			$('.brt_location_1').attr('readonly', false);
			$('.autoMarkerLoc').show();
			$('.custInfo').hide();
			setHyperLocationData();
		} else {
			if (isGozonow == 0) {
				$('.brt_location_0').attr('readonly', true);
				$('.brt_location_1').attr('readonly', true);
			}
			if (isGozonow == 1) {
				setHyperLocationData();
			}
			$('.autoMarkerLoc').hide();
			$('.custInfo').show();
		}
		var ctyLat = <?= json_encode($ctyLat) ?>;
		var ctyLon = <?= json_encode($ctyLon) ?>;
		var bound = <?= json_encode($bound) ?>;
		var isAirport = <?= json_encode($isCtyAirport) ?>;
		var isCtyPoi = <?= json_encode($boundisCtyPoi) ?>;
		$('.mapBound_0').val(JSON.stringify({"ctyLat": ctyLat[0], "ctyLon": ctyLon[0], "bound": bound[0], "isAirport": isAirport[0], "isCtyPoi": isCtyPoi[0]}));
		$('.mapBound_1').val(JSON.stringify({"ctyLat": ctyLat[1], "ctyLon": ctyLon[1], "bound": bound[1], "isAirport": isAirport[1], "isCtyPoi": isCtyPoi[1]}));
	});

	function setHyperLocationData()
	{
		var model = {};
		model.booking_type = '1';
		model.transfer_type = '0';
		model.ctyLat = <?= json_encode($ctyLat) ?>;
		model.ctyLon = <?= json_encode($ctyLon) ?>;
		model.bound = <?= json_encode($bound) ?>;
		model.isCtyAirport = <?= json_encode($isCtyAirport) ?>;
		model.isCtyPoi = <?= json_encode($boundisCtyPoi) ?>;
		model.hyperLocationClass = '<?= $initialClass ?>';
		hyperModel.model = model;
		hyperModel.initializepl();
		
		
		if(model.booking_type==1)
		{
			if(model.isCtyAirport[1]==1)
			{
				var airportAddress = '<?=$dropLoc?>';
				if(airportAddress!='')
				{
				  $('.brt_location_1').val(airportAddress);
				}
			}
			if(model.isCtyAirport[0]==1)
			{
				var airportAddress = '<?=$pickupLoc?>';
				if(airportAddress!='')
				{
				  $('.brt_location_0').val(airportAddress);
				}
			}
		}
	}

</script>