<?php 
	$selectizeOptions1	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="col-sm-12">
	<div class="row">
		<div class="col-sm-3">
			<label class="checkbox-inline">
				<input placeholder="Transfer Type" id="BookingTemp_bkg_transfer_type_0" value="1" type="radio" name="Booking[bkg_transfer_type]"  checked autocomplete="off"> Pickup
			</label>
		</div>
		<div class="col-sm-3">
			<label class="checkbox-inline">
				<input placeholder="Transfer Type" id="BookingTemp_bkg_transfer_type_1" value="2" type="radio" name="Booking[bkg_transfer_type]" autocomplete="off"> Drop
			</label>
		</div>
	</div>
</div>
<br/>
<div class="col-sm-6 ">
	<div class="form-group cityinput">
		<label class="control-label" for="exampleInputName6">Railway or Bus Terminal</label>
		<?php
		$this->widget('ext.yii-selectize.YiiSelectize', array(
			'model'				 => $model,
			'attribute'			 => 'bkgPoi',
			'useWithBootstrap'	 => true,
			"placeholder"		 => "Select Railway or Bus",
			'fullWidth'			 => false,
			'htmlOptions'		 => array(
				'class' => 'route-focus'
			),
			'defaultOptions'	 => $selectizeOptions1 + array(
		'onInitialize'	 => "js:function(){
											booking.populateRailwayBusList(this, '{$model->bkgPoi}');
										}",
		'load'			 => "js:function(query, callback){
											booking.loadeRailwayBusSource(query, callback);
										}",
		'onChange'		 => "js:function(value) {
											hyperModel.changeTrDestination(value);
										}",
		'render'		 => "js:{
											option: function(item, escape){                      
											return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
											},
											option_create: function(data, escape){
											return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
										   }
										}",
			),
		));
		?>
		<input id="brt_location0" name="BookingRoute[0][brt_from_location]"  type="hidden" value="">
		<input id="locLat0" name="BookingRoute[0][brt_from_latitude]"  class="locLatVal" type="hidden" value="">
		<input id="locLon0" name="BookingRoute[0][brt_from_longitude]"  class="locLonVal" type="hidden" value="">
		<input type="hidden" id="locPlaceid0" name="BookingRoute[0][brt_from_place_id]" value="">
		<input type="hidden" id="locFAdd0" name="BookingRoute[0][brt_from_formatted_address]" value="">
		<input id="isPoiType0" name="BookingRoute[0][brt_from_city_is_poitype]" type="hidden"  value="">
		<input id="ctyIdAir0" name="BookingRoute[0][brt_from_city_id]" type="hidden"  value="">	</div>			
</div>
<div class="col-sm-6">
	<div class="form-group cityinput">
		<label class="control-label" for="exampleInputCompany6" id="dlabel">Drop</label>
		<div class="row">
			<div class="col-sm-10">
				<input id="brt_location1" name="BookingRoute[1][brt_to_location]"  class="autoComLoc form-control route-focus" type="text" value="" autocomplete = "section-new" placeholder = "Address" onblur="hyperModel.clearAddress(this,'airport')">
			</div>
			<div class="col-sm-2">
				<span class="autoMarkerLoc" data-lockey="1" data-toggle="tooltip" title="Select drop location on map" onclick="showAirportMap(this)"><img src="/images/locator_icon4.png" alt="Precise location" width="30" height="30"></span>
			</div>
		</div>
		<input id="locLat1" name="BookingRoute[1][brt_to_latitude]"  class="locLatVal" type="hidden" value="">
		<input id="locLon1" name="BookingRoute[1][brt_to_longitude]"  class="locLonVal" type="hidden" value="">
		<input type="hidden" id="locPlaceid1" name="BookingRoute[1][brt_to_place_id]" value="">
		<input type="hidden" id="locFAdd1" name="BookingRoute[1][brt_to_formatted_address]" value="">
		<input id="isPoiType1" name="BookingRoute[1][brt_to_city_is_poitype]" type="hidden"  value="">
		<input id="ctyIdAir1" name="BookingRoute[1][brt_to_city_id]" type="hidden"  value="">
		<input class="cpy_loc_1" type="hidden"  value="">
	</div>
</div>
<script>
	$("input[name='Booking[bkg_transfer_type]']").change(function(){
		var radVal = $(event.currentTarget).val();
		var label = (radVal == 2) ?  'Pickup' : 'Drop';
		$('#dlabel').text(label);
		if(radVal == 2)
		{
			$('.autoMarkerLoc').attr('title', 'Select Pickup Address on map');
		}
		else
		{
			$('.autoMarkerLoc').attr('title', 'Select Drop Address on map');
		}
	});
</script>