<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/city.js?v=$version");
/* @var $brtRoute BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$ctr = rand(0, 99) . date('mdhis');
 if (Yii::app()->request->cookies->contains('itineraryCookie'))
            {
     
                $var     = Yii::app()->request->cookies['itineraryCookie']->value;
                $dateVar = explode(" ", Filter::getDateFormatted($var->pickupTime));

                $cookieSourceCity      = $var->source->city->id;
                $cookieDestinationCity = $var->destination->city->id;

               
            }

if ($transfertype == 1)
{
	$heading = "Pickup from airport";
	$pickup	 = "Which airport do you need the pickup from";
	$toCity	 = "Going to";
	$date	 = "departure";
    if ($cookieSourceCity)
    {
        $cookieSourceCityModel = Cities::model()->getLatLngByCity($cookieSourceCity);
        $placeObj              = \Stub\common\Place::init($cookieSourceCityModel['lat'], $cookieSourceCityModel['lng']);
        $res                   = Cities::getNearestAirports($placeObj);
        $pickupcity            = [];
        foreach ($res as $key => $value)
        {
            if ($key == 0)
            {
                $pickupcity = $value["cty_id"];
            }
        }
        $destinationcity = $cookieDestinationCity;
    }
}
else
{
	$heading = "Drop to airport";
	$pickup	 = "Pickup city";
	$toCity	 = "Which airport do you need the dropping at?";
	$date	 = "pickup";
    if($cookieDestinationCity)
    {
        $cookieDestinationCityModel = Cities::model()->getLatLngByCity($cookieDestinationCity);
        $placeObj              = \Stub\common\Place::init($cookieDestinationCityModel['lat'], $cookieDestinationCityModel['lng']);
        $res                   = Cities::getNearestAirports($placeObj);
        $destinationcity            = [];
        foreach ($res as $key => $value)
        {
            if ($key == 0)
            {
                $destinationcity = $value["cty_id"];
            }
        }
        $pickupcity = $cookieSourceCity;
    }
}
?>
<input type="hidden" name="ctr" value="<?= $ctr ?>">
<div class="container mt15 clsRoute">
	<div class="bg-white-box">
		<div class="row mb-2">
			<div class="col-12 text-center"><p class="merriw heading-line"><?php echo $heading; ?></p></div>
			<div class="col-12 col-md-6 col-lg-6 offset-lg-3">
				<label for="iconLeft"><?php echo $pickup; ?></label>
				<?php
				$widgetId			 = $ctr . "_" . random_int(99999, 10000000);
				$this->widget('application.widgets.BRCities', array(
					'type'				 => 1,
					'enable'			 => ($index == 0),
					'widgetId'			 => $widgetId,
					'model'				 => $brtRoute,
					'airportOnly'		 => ($transfertype == 1),
					'attribute'			 => 'brt_from_city_id',
					'useWithBootstrap'	 => true,
                     'isCookieActive'              =>     true,
                         'cookieSource'              =>     $pickupcity,
					"placeholder"		 => "Select City",
				));
				?>
				<span class="has-error"><?php echo $form->error($brtRoute, 'airport'); ?></span>

				<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
			</div>

            <div class="col-12 col-md-6 col-lg-6 offset-lg-3">
				<label for="iconLeft"><?php echo $toCity; ?></label>
				<?php
				$this->widget('application.widgets.BRCities', array(
					'type'				 => 2,
					'widgetId'			 => $widgetId,
					'model'				 => $brtRoute,
					'airportOnly'		 => ($transfertype == 2),
					'attribute'			 => 'brt_to_city_id',
					'useWithBootstrap'	 => true,
                     'isCookieActive'              =>     true,
                    'cookieDestination'              =>     $destinationcity,
					"placeholder"		 => "Select City",
				));
				?>
            </div>
			<div class="col-6 col-md-6 col-lg-3 offset-lg-3 mb-2">
				<label for="iconLeft">Date of <?php echo $date; ?></label>
				<?php
				$minDate			 = ($model->brt_min_date != '') ? $model->brt_min_date : date('Y-m-d');
				$formattedMinDate	 = DateTimeFormat::DateToDatePicker($minDate);
				echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'			 => $brtRoute,
					'attribute'		 => 'brt_pickup_date_date',
					'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
					'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date', 'value'			 => $brtRoute->brt_pickup_date_date,
						'id'			 => 'brt_pickup_date_date_' . date('mdhis'), 'min'			 => $brtRoute->brt_min_date, 'class'			 => 'form-control datePickup border-radius')
						), true);
				?>
				<span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_date'); ?></span>
			</div>
			<div class="col-6 col-md-6 col-lg-3 mb-2">
				<label for="iconLeft">Time of <?php echo $date; ?></label>
				<?php
				$this->widget('ext.timepicker.TimePicker', array(
					'model'			 => $brtRoute,
					'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
					'attribute'		 => 'brt_pickup_date_time',
					'options'		 => ['widgetOptions' => array('options' => array())],
					'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
				));
				?>
				<span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_time'); ?></span>
			</div>
			<div class="col-12 col-lg-6 offset-lg-3">
				<div class="row">
					<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
					<div class="col-10 col-lg-10 d-lg-none d-xl-none"><span class="cabcontent"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></span></div>
					<div class="col-10 col-lg-10 cabcontent d-none d-lg-block"><p class="typing"></p><div class="hiders"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></div></div>
				</div>
			</div>
		</div>

	</div>
	<div class="row m0 cc-2">
		<div class="col-12">
			<div class="row m0 justify-center cc-2 pb10">
				<div class="col-12 text-center">
					<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
					<?= CHtml::submitButton('NEXT', array('class' => 'btn btn-primary pl-5 pr-5', 'id' => 'btnAirTransfer')); ?>
				</div>
			</div>
		</div>
	</div>
<!--	<div class="row mb30">
<div class="col-12 text-center"><a href="https://www.gozocabs.com/terms/doubleback" target="_blank"><img src="/images/double-back-banner.webp?v=0.3" alt="Img" class="img-fluid"></a></div>
</div>-->
	
</div>

<script>

	$(document).ready(function()
	{
		var val = '<?= $transfertype ?>';
		if (val == 1)
		{
			var defaultVal = 99;
		}
		else
		{
			var defaultVal = 100;
		}
		var tncval = JSON.parse('<?= $tncArr ?>');
		//$('.cabcontent').html(tncval[defaultVal]);
        $('.typing').html(tncval[defaultVal]);
        
		$('.roundimage').removeClass('hide');
	});

	function changeDestination(value, obj)
	{	
		if (!value.length)
			return;
		var existingValue = obj.getValue();
		obj.disable();
		obj.clearOptions();
		obj.load(function(callback)
		{
			//  xhr && xhr.abort();
			xhr = $.ajax({
				url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
				dataType: 'json',
				success: function(results)
				{
					obj.enable();
					callback(results);
					obj.setValue(existingValue);
				},
				error: function()
				{
					callback();
				}
			});
		});
	}
</script>