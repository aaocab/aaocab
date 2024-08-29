<style>
#map {
  height: 100%;
}
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
}
.full-width{ width: initial!important; }
</style>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<div class="container">
	<div class="row justify-center">
		<div class="col-12 col-xl-10 mb20 mt30 lst-1">
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

$autoAddressJSVer = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/aao/v3/bookingRoute.js?v=$autoAddressJSVer");

/** @var BookingTemp $model */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'map1',
	'enableClientValidation' => true,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => $this->getURL(['index/map']),
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
));
?>
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-md-4">
					<label for="iconLeft">City</label>
					<?php
					$ctr		 = rand(0, 99) . date('mdhis');
					$widgetId	 = $ctr . "_" . random_int(99999, 10000000);
					$this->widget('application.widgets.BRCities', array(
						'type'				 => 1,
						'enable'			 => true,
						'widgetId'			 => $widgetId,
						'model'				 => $model,
						'attribute'			 => 'bkg_from_city_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select City",
					));
					?>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-6">
					<label for="iconLeft">Pointer</label>
					<div class="row">
						<div class="col-md-6">
						<?php echo CHtml::activeTextField($model, 'pickupLat', array('placeholder'=>'Latitude', 'class'=>'form-control')); ?>
					</div>
						<div class="col-md-6">
						<?php echo CHtml::activeTextField($model, 'pickupLon', array('placeholder'=>'Longitude', 'class'=>'form-control')); ?>
					</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-2" style="margin-top: 26px;">
					<?= CHtml::submitButton('Submit', array('class' => 'btn btn-md btn-primary pl-2 pr-2')); ?>
				</div>
			</div>
		</div>
			<div class="col-12 col-xl-10" style="height:500px;" >
					<div id="map"></div>
			</div>
			<?php $this->endWidget(); ?>
	</div>
</div>
<?php if(count($arrCtyBounds) > 0) { ?>
<script type="text/javascript">
var north = "<?=$arrCtyBounds['northeast']['lat']; ?>";
var east = "<?=$arrCtyBounds['northeast']['lng']; ?>";
var south = "<?=$arrCtyBounds['southwest']['lat']; ?>";
var west = "<?=$arrCtyBounds['southwest']['lng']; ?>";
var pLat = "<?=$model->pickupLat?>";
var pLng = "<?=$model->pickupLon?>";
if(pLat == '') {
	var pLat = south;
	var pLng = east;
}

// This example adds a red rectangle to a map.
function initMap() {
	
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 13,
    center: { lat: Number(south), lng: Number(east) },
    mapTypeId: "roadmap",
  });
  if(pLat != '') {
	const myLatLng = { lat: Number(pLat), lng: Number(pLng) };
	new google.maps.Marker({
	  position: myLatLng,
	  map
	});
  }
  const rectangle = new google.maps.Rectangle({
    strokeColor: "#FF0000",
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: "#FF0000",
    fillOpacity: 0.1,
    map,
    bounds: {
      north: Number(north),
      south: Number(south),
      east: Number(east),
      west: Number(west),
    },
  });
}

//window.initMap = initMap;
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=Config::getGoogleApiKey('browserapikey') ?>&callback=initMap&v=weekly" defer ></script>
<?php } ?>