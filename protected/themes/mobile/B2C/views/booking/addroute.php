<?php
/* @var $model BookingRoute */
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/bookingRoute.js?v=$version");
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');

$ptime				 = date('h:i A', strtotime('6am'));
//$model->bkg_pickup_date_time = $ptime;
$timeArr			 = Filter::getTimeDropArr($ptime);
$ptimePackage		 = Yii::app()->params['defaultPackagePickupTime'];
$timeArrPackage		 = Filter::getTimeDropArr($ptimePackage);
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
if ($sourceCity == "")
{
	$cityList	 = Cities::model()->getJSONAirportCitiesAll();
	$pcityList	 = $cityList;
}
else
{
	$model->brt_from_city_id = $sourceCity;
	$cmodel					 = Cities::model()->getDetails($sourceCity);
	$sourceCityName			 = $cmodel->cty_name . ', ' . $cmodel->ctyState->stt_name;
	$pcityList				 = Cities::model()->getJSONNearestAll($previousCity);
}
if ($model->brt_from_city_id != '')
{
	$cityList = Cities::model()->getJSONNearestAll($model->brt_from_city_id);
}
$rcitiesDiv	 = '';
$rtimeDiv	 = "  col-md-4";
if ($btype == 2)
{
	$rcitiesDiv	 = "  col-md-offset-2";
	$rtimeDiv	 = "  col-md-12";
}
if ($btype == 3)
{
	$mcitiesDiv = "  col-md-4";
}

$ctr = rand(0, 99) . date('mdhis');
?>
<?php
if ($btype == 7)
{

	$minDate = ($model->brt_min_date != '') ? $model->brt_min_date : date('Y-m-d');
	?>
	<div class="content-boxed-widget mobile-type clsRoute">
		<div class="one-half input-simple-1 has-icon input-blue bottom-40 input-box">
			<em class="color-highlight">Depart date</em><i class="fa fa-calendar pr10 font-16 tx-gra-green"></i>
			<i class = "fa fa-calendar pr10 font-16 tx-gra-green"></i>
			<?php
			$this->widget('booster.widgets.TbDatePicker', array(
				'model'			 => $model,
				'attribute'		 => '[' . $ctr . ']brt_pickup_date_date',
				'value'			 => $pdate,
				'options'		 => array('showAnim' => 'slide', 'autoclose' => true, 'startDate' => $minDate, 'format' => 'dd/mm/yyyy', 'minDate' => 0, 'maxDate' => "+6M"),
				'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
					'class'			 => 'border-radius font-16', 'id'			 => 'brt_pickup_date_date_shuttle', 'min'			 => $brtModel->brt_min_date, 'style'			 => 'z-index:100 !important', 'readonly'		 => 'readonly')
			));
			?>

		</div>
		<input type='hidden' id="<?= 'brt_pickup_date_time_' . date('mdhis') ?>" name="BookingRoute[<?= $ctr ?>][brt_pickup_date_time]"  value="<?= $model->brt_pickup_date_time ?>" >
		<div class="clear"></div>
		<div class="select-box-1 select-panel">
			<em  id='trslabel' class="color-highlight">Depart from City</em>
			<select class="form-control ctyPickup ctySelect2 yii-selectize full-width selectized inputSource " name="BookingRoute[<?= $ctr ?>][brt_from_city_id]"  
					id="<?= 'brt_from_city_id_' . $ctr ?>" onchange="populateDropCity()" style="background-color: #ffffff;">
			</select>
			<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
		</div>
		<div class="clear"></div>   
		<div class="select-box-1 select-panel" id="arriveCity">
			<em id='trdlabel' class="color-highlight">Arrive at City</em> 
			<select class="form-control ctyPickup ctySelect2 yii-selectize full-width selectized destSource " name="BookingRoute[<?= $ctr ?>][brt_to_city_id]"  
					id="<?= 'brt_to_city_id_' . $ctr ?>"  style="background-color: #ffffff;">
			</select>

		</div>
		<div class="clear"></div>
	</div>
	<?php
}
else if ($btype != 4 && $btype != 7)
{
	?>

	<div class="content-boxed-widget mobile-type clsRoute">

		<?php
		if ($btype == 9 || $btype == 10 || $btype == 11)
		{
			?>
			<?= $form->hiddenField($model, "[" . $ctr . "]brt_to_city_id", ['id' => 'brt_to_city_id_' . $ctr]); ?>

			<div class="select-box select-box-1 select-panel">
				<em  id='trslabel' class="color-gray">Going From</em>
				<?php
				$widgetId		 = $ctr . "_" . random_int(99999, 10000000);
				$this->widget('application.widgets.BRCities', array(
					'type'				 => 1,
					'enable'			 => ($index == 0),
					'widgetId'			 => $widgetId,
					'model'				 => $model,
					'attribute'			 => '[' . $ctr . ']brt_from_city_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select City",
					'defaultOptions'	 => [
						'onFocus' => "js:function() {
									$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
							}",
					]
				));
				?>
				<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
			</div>

			<div class="select-box select-box-1 select-panel">
				<em id='trdlabel' class="color-gray">Select Rental Type</em> 
				<?php
				$rentalType		 = Booking::model()->rental_types;
				$model->tripType = (in_array($rentalType, ['9', '10', '11'])) ? $rentalType : $btype;
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'useWithBootstrap'	 => false,
					'fullWidth'			 => true,
					'attribute'			 => '[' . $ctr . ']tripType',
					'data'				 => $rentalType,
					'htmlOptions'		 => array('style' => 'width:100%', 'placeholder' => 'Hr - Km', 'id' => 'BookingTemp_bkg_booking_type_rental', 'onChange' => 'setDayRentalType(this)', 'class' => 'form-control ctySelect2')
				));
				?>
			</div>

			<?php
		}
		else
		{
			?>

			<div class="select-box select-box-1 select-panel mb10 mt10">
				<em  id='trslabel' class="color-gray mt30 n">Going From</em>
				<?php
				$widgetId		 = $ctr . "_" . random_int(99999, 10000000);
				$this->widget('application.widgets.BRCities', array(
					'type'				 => 1,
					'enable'			 => ($index == 0),
					'widgetId'			 => $widgetId,
					'model'				 => $model,
					'attribute'			 => '[' . $ctr . ']brt_from_city_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select City",
					'defaultOptions'	 => [
						'onFocus' => "js:function() {
										$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
                                    }",
					]
				));
				?>
				<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
			</div>


			<div class="select-box select-box-1 select-panel mt20 mb10" id="arriveCity">
				<em id='trdlabel' class="color-gray mt30 n">Going To</em> 
				<?php
				$this->widget('application.widgets.BRCities', array(
					'type'				 => 2,
					'widgetId'			 => $widgetId,
					'model'				 => $model,
					'attribute'			 => '[' . $ctr . ']brt_to_city_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select City",
					'defaultOptions'	 => [
						'onFocus' => "js:function() {
									$('html, body').animate({scrollTop: $(this.\$control[0]).offset().top - 80},200);
                                }",
					]
				));
				?>
			</div>

		<?php } ?>

		<div class="input-simple-1 has-icon input-blue bottom-10 input-box">
			<em class="color-gray mb10 n">Start Date</em>
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'			 => 'BookingRoute[' . $ctr . '][brt_pickup_date_date]',
				'value'			 => $model->brt_pickup_date_date,
				'options'		 => array('showAnim' => 'slide', 'autoclose' => true, 'startDate' => date('Y-m-d H:i:s ', strtotime('+4 hour')), 'dateFormat' => 'dd/mm/yy', 'minDate' => 0, 'maxDate' => "+6M"),
				'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date', 'readonly'		 => 'readonly',
					'class'			 => 'border-radius font-16 datePickup', 'id'			 => 'dateAddroute_' . date('mdhis'), 'style'			 => 'z-index:100 !important')
			));
			?>

		</div>

		<div class="input-simple-1 has-icon input-blue bottom-10 input-box">
			<em class="color-gray mb10 n">Start Time</em>
			<?php
			$this->widget('ext.timepicker.TimePicker', array(
				'model'			 => $model,
				'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
				'attribute'		 => '[' . $ctr . ']brt_pickup_date_time',
				'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
				'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'timePickup font-16')
			));
			?> 
		</div>
		<?php $arvlcnt = ($arvlcnt == '' || $arvlcnt == 0)?1:$arvlcnt; ?>
		<input type="hidden" name="arvlcnt" id="arvlcnt"  value="<?php echo $arvlcnt;?>">
        <?php //if($estArrTime != ''){ ?>
		<div class="input-simple-1 has-icon input-blue bottom-10 input-box estarvtime hide">
			<em class="color-gray mb10 n">Arrival Time</em>
			<span class="arvlcntcls<?php echo $arvlcnt;?>"><?php echo date('h:i a',strtotime($estArrTime));?></span>
		</div>
		<?php //}?>
		<div class="clear"></div>

		<?php
		if ($btype == 3)
		{
			echo CHtml::activeHiddenField($model, '[' . $ctr . ']estArrTime', array('id' => 'estArrTime_' . $index));
			?>
			<div class="">
				<div><!--<span>Est Arrival Time</span>-->&nbsp;<span id="lab_estArrTime_<?= $index ?>"><!--to be calculated--></span>
					<input  type='hidden' id="hidden_estArrTime_<?= $index ?>"  value="<?= ($model->estArrTime == '') ? 'to be calculated' : $estArrTime ?>" >
				</div>  
			</div>
		<?php } ?>

		<?php
		if ($btype == 2)
		{
			?>
			<div class="col">
				<label class="control-label ml10 n mb10"><b>Trip End information</b></label>
				<div class="input-simple-1 has-icon input-blue bottom-30 top-10">
					<em class="color-highlight">Return Date</em><i class="fa fa-calendar pr10 font-16 tx-gra-green"></i>
					<i class = "fa fa-calendar pr10 font-16 tx-gra-green"></i>
					<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'name'			 => 'BookingRoute[' . $ctr . '][brt_return_date_date]',
						'value'			 => $model->brt_return_date_date,
						'options'		 => array('showAnim' => 'slide', 'autoclose' => true, 'startDate' => date('Y-m-d H:i:s ', strtotime('+4 hour')), 'dateFormat' => 'dd/mm/yy', 'minDate' => 0, 'maxDate' => "+6M"),
						'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date', 'readonly'		 => 'readonly',
							'class'			 => 'border-radius font-16', 'id'			 => 'dateAddRway', 'style'			 => 'z-index:100 !important')
					));
					?>

				</div>
				<div class="input-simple-1 has-icon input-blue bottom-30 top-10">
					<em class="color-highlight">Return Time</em><i class="fa fa-clock  tx-gra-green"></i>
					<?php
					$this->widget('ext.timepicker.TimePicker', array(
						'model'			 => $model,
						'id'			 => 'brt_return_date_time' . date('mdhis'),
						'attribute'		 => '[' . $ctr . ']brt_return_date_time',
						'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
						'htmlOptions'	 => array('required' => true, 'placeholder' => 'Return Time', 'class' => 'timeReturn font-16', 'readonly' => 'readonly')
					));
					?> 
				</div>
			</div>
		<?php } ?>

	</div>

	<?php
}
else
{
	$this->renderPartial('bkTypeAirportTransfer', ['brtRoute' => $model, 'form' => $form, 'timeArr' => $timeArr, 'pcityList' => $pcityList, 'cityList' => $cityList, 'btype' => $btype, 'index' => 0, 'bkgTempModel' => $bkgTempModel, 'selectizeOptions' => $selectizeOptions], false, false);
	?>

	<? } ?>

	<div id="new_data22"></div>					   
	<script>
		$sourceList = null;
		$loadCityId = 0;
		var booknow = new BookNow();
		$('#lab_estArrTime_' + (<?= $index - 1 ?>)).html($('#hidden_estArrTime_' + <?= $index ?>).val());

		function populateSource1(obj, cityId)
		{
			obj.load(function (callback)
			{
				var obj = this;
				if ($sourceList == null)
				{
					xhr = $.ajax({
						url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>',
						dataType: 'json',
						data: {
							city: cityId
						},
						//  async: false,
						success: function (results)
						{
							$sourceList = results;
							obj.enable();
							callback($sourceList);
							obj.setValue('<?= $model->brt_from_city_id ?>');
						},
						error: function ()
						{
							callback();
						}
					});
				}
				else
				{
					obj.enable();
					callback($sourceList);
					obj.setValue('<?= $model->brt_from_city_id ?>');
				}
			});
		}

		function changeDestination1(value, obj)
		{
			if (!value.length)
				return;
			var existingValue = obj.getValue();
			if (existingValue == '')
			{
				existingValue = '<?= $model->brt_to_city_id ?>';
			}
			obj.disable();
			obj.clearOptions();
			obj.load(function (callback)
			{
				//  xhr && xhr.abort();
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
					dataType: 'json',
					success: function (results)
					{
						obj.enable();
						callback(results);
						obj.setValue(existingValue);
					},
					error: function ()
					{
						callback();
					}
				});
			});
		}

		function setDayRentalType(obj)
		{
			if ($(obj).val() == 9)
			{
				$('#topRouteDesc').html('Day Rental 4hr-40km');
			}
			if ($(obj).val() == 10)
			{
				$('#topRouteDesc').html('Day Rental 8hr-80km');
			}
			if ($(obj).val() == 11)
			{
				$('#topRouteDesc').html('Day Rental 12hr-120km');
			}
			$('#BookingTemp_bkg_booking_type').val($(obj).val());
		}


		$(document).ready(function ()
		{
			populateShuttleSource();
			
			var estarvtime = '<?php echo date('h:i a',strtotime($estArrTime));?>';
			var arvlcnt = '<?php echo ($arvlcnt - 1);?>';
			$('.arvlcntcls'+arvlcnt).html(estarvtime);
		});

		$('#brt_pickup_date_date_shuttle').change(function ()
		{
			$('.destSource').val('');
			populateShuttleSource();

		});

		function populateShuttleSource()
		{
			fromCity = '<?= $model->brt_from_city_id ?>';
			dateVal = $('#brt_pickup_date_date_shuttle').val();
			$('.inputSource').val('');
			$('.destSource').html('');

			$.ajax({
				"type": "POST",
				dataType: 'json',
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getpickupcitylist')) ?>",
				data: {
					'dateVal': dateVal
				},
				"async": false,
				"success": function (data1)
				{

					$('.inputSource').children('option').remove();
					$(".inputSource").append('<option value="">Select Pickup City</option>');
					$.each(data1, function (key, value)
					{
						if (fromCity != '' && fromCity == key)
						{
							$('.inputSource').append($("<option></option>").attr("value", key).attr("selected", "selected").text(value));
						}
						else
						{
							$('.inputSource').append($("<option></option>").attr("value", key).text(value));
						}

					});
				}

			});
			if (fromCity != '')
			{
				populateDropCity();
			}
		}

		function populateDropCity()
		{
			toCity = '<?= $model->brt_to_city_id ?>';
			dateVal = $('#brt_pickup_date_date_shuttle').val();
			fcityVal = $('.inputSource').val();
			$('.destSource').val('');
			if ($('#BookingTemp_bkg_booking_type').val() == 7 && fcityVal > 0)
			{
				$.ajax({
					"type": "POST",
					dataType: 'json',
					"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getdropcitylist')) ?>",
				data: {
					'dateVal': dateVal, 'fcityVal': fcityVal
				},
				"async": false,
				"success": function (data1)
				{

					$('.destSource').children('option').remove();
					$(".destSource").append('<option value="">Select Drop City</option>');
					$.each(data1, function (key, value)
					{
						if (toCity != '' && toCity == key)
						{
							$('.destSource').append($("<option></option>").attr("value", key).attr("selected", "selected").text(value));
						}
						else
						{

							$('.destSource').append($("<option></option>").attr("value", key).text(value));
						}
					});
				}
			});
		}
	}
</script>

