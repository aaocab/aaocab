
<style type="text/css">

	.selectize-dropdown{
		z-index: 100000 !important;
	}
	.selectize-input{
		width: 100%;
	}
	.place{
		display: none;
	}
	.autoAddress .selectize-control.single .selectize-input:not(.no-arrow):after {
  display: none;
}
		  
/*		  #map { 
		
		  }
@media (min-width: 768px) and (max-width: 3600px) {
#map{ height: 300px; width: 507px;}
}	  */
		</style>
<?php
$api	 = Config::getGoogleApiKey('browserapikey');
/* @var $cs CClientScript */
$cs		 = Yii::app()->getClientScript();
$jsVer	 = Yii::app()->params['siteJSVersion'];

//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $jsVer);

$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row" id="mapAutoComplete">
	<div class="col-12">
<!--<input type="text" class='addressline' id="jsonAddr_<?=$city?>" value="">-->
		<div>
			<div class="row">
				<div class="col-12 autoAddress">
					<?php
					$model				 = new AutocompleteMaster();
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'atc_city_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Type nearest landmark or town name for accurate pricing and options",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width'	 => '100%',
							
						),
						'defaultOptions'	 => $selectizeOptions + array(
							"maxItems" => 1,
					'dropdownParent' => null,
					'onInitialize'	 => "js:function(){
							let jsonVal = '$widgetTextValJson';
							\$jsRap.addOption(this,jsonVal,'$api','$city');
						
							}",
					'load'			 => "js:function(query, callback){

                         this.clearOptions();     
					      getValue(query, callback);

						}",
					'onChange'		 => "js:function(value) {
										\$jsRap.loadMapByPlaceId(value,'$api');
                                          
											}",
					'render'		 => "js:{
						option: function(item, escape){
					return '<div class=\"p5\" style=\"border-bottom: solid 1px #999\"><span>' + escape(item.text) +'</span></div>';
						},
						option_create: function(data, escape){
						return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
						}
						}",
						),
					));
					?> 

				</div>
	<div  style="display:block"  id="map" class="col-12 container">
	
<!--<iframe
  width="100%"
  style="border:0"
  loading="lazy"
  allowfullscreen
  referrerpolicy="no-referrer-when-downgrade"
  src="http://www.google.com/maps/embed/v1/place?key=AIzaSyDghPDCwW9R5cnl_Rb4Ys5JXUA4k3XP3sk&q=place_id:ChIJucT-mMC8kTkRlqHAsLoFz3A">
</iframe>-->
</div>
				<div class="col-12" id = "blankMsg"></div>
				
				
			</div></div>
		<!--===================== //autocomplete master by db end//===============-->
		<div class="row">
			<div class="col-12">
				<fieldset class="position-relative">
					<input type="text" class="form-control mt-1 addressline" id="txtAddress1" maxlength="100" placeholder="Provide additional address details for accurate pick up point" >
				</fieldset>
			</div>
			<div class="col-12">
				<fieldset class="position-relative">
					<input type="text" class="form-control mt-1 addressline" id="txtAddress2" maxlength="100" placeholder="Additional instructions (if any)">
				</fieldset>
			</div>
		</div>
		<button class="btn btn-sm btn-light btnBackToAddress mt-1 mb-1 text-uppercase mr-2">Back</button>
<!--		<input type="text" id="jsonValue" value="">-->
		<input type="hidden" class='addressline' id="placeId" value="">
        <input id="sessionId" type="hidden" value="<?= Filter::guidv4() ?>">

		
		<?php
		// print_r($_SERVER['HTTP_REFERER']);
		$uri				 = urldecode(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
		$var				 = explode('/', $uri);

		if ($var[1]=='book-taxi' || $var[1] == 'outstation-cabs' || $var[1] == 'car-rental')
		{
			?>
			<a id="pac-btn"  id="pac-btn" class="btn btn-sm btn-primary mt-1 mb-1 text-uppercase " >Confirm your location</a>
<?php }
else
{ ?>
			<button id="pac-btn" class="btn btn-sm btn-primary mt-1 mb-1 text-uppercase ee">Confirm your location</button>

<?php } ?>
	</div>
</div>
<div id="mapAddressForm" style="display: none">
	<?php //$this->renderPartial("//booking/addressForm", ["callback" => $callback,"city"=> $city]);  ?>
</div>
<script>
	var select = null;	
//	function addOption(control,val)
//	{
//		if(val!=="")
//		{
//			debugger;
//		var obj =  $.parseJSON(val);
//       control.addOption({
//			id: obj[0].placeId,
//			text: obj[0].addressMain
//  });
//  control.setValue([obj[0].placeId]);
//  $("#placeId").val(obj[0].placeId);
//		$("#txtAddress1").val(obj[0].address1);
//		$("#txtAddress2").val(obj[0].address2);
//		$jsRap.loadMapByPlaceId(obj[0].placeId,'<?php echo $api; ?>');
//		}
//	}

	$(document).ready(function()
	{
	$jsRap = new AutocompleteAddress();
	});
	function getValue(pval, callback)
	{
	    
		var maxSize = 250;
        var trimRes = pval.trim();
		trimRes = trimRes.substr(0,maxSize);
		var sessID =$("#sessionId").val();
		$jsRap.getValue(trimRes, callback, <?= $city ?>, sessID);
	}
	$("#pac-btn").click(function()
	{
		var rawText = $("#AutocompleteMaster_atc_city_id").text();
		var sessID =$("#sessionId").val();
	    var placeID =$("#placeId").val();
		createJSON();
		
		$jsRap.pacSubmit(sessID,placeID,<?= $callback ?>,rawText);
	});
        function createJSON() {
        jsonObj = [];
	    
        item = {}
        item ["placeId"] = $("#placeId").val();
		item ["address1"] = $("#txtAddress1").val();
		item ["address2"] = $("#txtAddress2").val();
		item ["addressMain"] = $("#AutocompleteMaster_atc_city_id").text();
		item["city"]= <?= $city; ?>;
       jsonObj.push(item);
	   $("#jsonAddr_"+<?=$city?>).val(JSON.stringify(jsonObj));

}



</script>

		

