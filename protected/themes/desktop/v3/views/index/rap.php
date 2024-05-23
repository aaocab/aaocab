
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<?php
$api		 = Config::getGoogleApiKey('browserapikey');
//var_dump($_REQUEST);
$city		 = 30366;
$ctyModel	 = Cities::model()->findByPk($city);
$expandBound = 0.1;

if ($isAirport == 1)
{
	$expandBound = 0.25;
	if ($ctyModel->cty_radius > 0)
	{
		$expandBound = $ctyModel->cty_radius / 100;
	}
}

/* @var $cs CClientScript */
$cs			 = Yii::app()->getClientScript();
$jsVer		 = Yii::app()->params['siteJSVersion'];
$cs->registerScriptFile("/js/gozo/city.js?v=$jsVer");
$cs->registerScriptFile('/js/gozo/geocodeMarker.js?v=' . $jsVer);
$cs->registerScriptFile('/js/gozo/placeAutoComplete.js?v=' . $jsVer);
$widgetId	 = "AMA" . mt_rand(999, 9999999);

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.js?v=' . $jsVer);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$jsVer");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>


<div>

<?php
$model = new AutocompleteMaster();
$this->widget('ext.yii-selectize.YiiSelectize', array(
	'model'				 => $model,
	'attribute'			 => 'atc_city_id',
	'useWithBootstrap'	 => true,
	"placeholder"		 => "select location",
	'fullWidth'			 => false,
	'htmlOptions'	 => array('width'	 => '100%',
		'id'	 => 'atc_city_id',
	),
	'defaultOptions' => $selectizeOptions + array(
'dropdownParent' => null,
 'onInitialize'	 => "js:function(){
						
							}",
 'load'	 => "js:function(query, callback){
                         this.clearOptions();     
					      getValue(query, callback);
						}",
 'render' => "js:{
						option: function(item, escape){
						return '<div><span class=\"place\" class=\"fa fa-map-marker mr5\">' + escape(item.placeID) +'</span><span class=\"\">' + escape(item.text) +'</span></div>';
						},
						option_create: function(data, escape){
						return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
						}
						}",
	),
	'callbacks'		 => array(
		'onChange' => 'myOnChangeTest',
	),
));
?> 

</div>

<div id="placehtml">
</div>
<div id = "map1"></div>

<input type="hidden" id="sessiontoken" value="<?php echo Filter::guidv4(); ?>">
<script>
	function myOnChangeTest(value)
	{
		$('#placehtml').show();
		var id = "place" + value;
		var placeID = $('#' + id).val();
		if (placeID)
		{

			$.ajax({
				"type": "GET",
				"url": '/lookup/getLatlngByPlaceId',
				"data": {placeID: placeID},
				"dataType": "json",
				global: false,
				error: function()
				{
					//callback();
				},
				"success": function(res)
				{

					var lat = res.coordinates.lat;
					var lng = res.coordinates.lng;
					$("#map1").html(res.html);


				}
			});
		}
	}


	function getValue(pval, callback)
	{


		if (pval.length > 2)
		{
			$.ajax({
				"type": "GET",
				"url": '/lookup/getPredictions',
				"data": {pval: pval, city: <?= $city ?>, sessiontoken: $("#sessiontoken").val()},
				"dataType": "json",
				global: false,
				error: function()
				{
					callback();
				},
				"success": function(res)
				{
					callback(res);
					$("#placehtml").html("");
					var span = "";
					$.each(res, function(key, value)
					{
						span += '<input  type="hidden" id="place' + value.id + '" value="' + value.placeID + '">';
					});
					$("#placehtml").html(span);

				}
			});
		}
	}

</script>


<style type="text/css">
	.place{
		display: none;
	}
</style>
