<?php
/* @var $model BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/bookingRoute.js?v=$version");

$ptime					 = date('h:i A', strtotime('6am'));
//$model->bkg_pickup_date_time = $ptime;
$timeArr				 = Filter::getTimeDropArr($ptime);
$ptimePackage			 = Yii::app()->params['defaultPackagePickupTime'];
$timeArrPackage			 = Filter::getTimeDropArr($ptimePackage);
$selectizeOptions		 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
$cityRadius				 = Yii::app()->params['airportCityRadius'];
$emptyTransferDropdown	 = "Please check your transfer type.<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>


<div class="tab-content col-xs-12" style="height: 100%">
	<div class="tab-pane active home-search mt10 mb5" id="menu4">
		<?php
		/* @var $form TbActiveForm|CWidget */
		$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'bookingSform',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
		if(!hasError){
		var success = false;
			$.ajax({
				"type":"POST",
				"async":false,
				"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')) . '",
				"data":form.serialize(),
				"dataType": "json",
				"success":function(data1){
					if(data1.success)
					{
					success = true;
					}
					else{
					var errors = data1.errors;
					var content = "";
					for(var key in errors){
						$.each(errors[key], function (j, message) {
						content = content + message + \'\n\';
						});
					}
					alert(content);
					}
				},
				});
							return success;                                
				}
			}'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => Yii::app()->createUrl('book-cab/one-way'),
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form TbActiveForm */
		/** @var BookingTemp $model */
		$brtModel				 = $model->bookingRoutes[0];
		?>
		<div class="row main_time border-blueline">
			<div class="col-xs-12 col-sm-12 col-lg-8">
				<div class="row">
					<div class="col-xs-12 col-sm-6">

						<?//= $form->errorSummary($brtModel); ?>
						<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 1, 'id' => 'bkg_booking_type1']); ?>
						<?= $form->hiddenField($model, 'bktyp', ['value' => 1, 'id' => 'bktyp1']); ?>
						<input type="hidden" id="step11" name="step" value="1">
						<input type="hidden" name="step" value="5">
						<label> Source</label>
						<?php
						$widgetId			 = $ctr . "_" . random_int(99999, 10000000);
						$this->widget('application.widgets.BRCities', array(
							'type'				 => 1,
							'enable'			 => ($index == 0),
							'widgetId'			 => $widgetId,
							'model'				 => $brtModel,
							'attribute'			 => 'brt_from_city_id',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select City",
						));
						?>
						<span class="has-error"><? //echo $form->error($brtModel, 'brt_from_city_id'); ?></span>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label>Destination</label>
					<?php
					$this->widget('application.widgets.BRCities', array(
						'type'				 => 2,
						'widgetId'			 => $widgetId,
						'model'				 => $brtModel,
						'attribute'			 => 'brt_to_city_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select City",
					));
					?>
						<span class="has-error"><? echo $form->error($brtModel, ' brt_to_city_id'); ?></span>
						<span class="has-error"><? echo $form->error($brtModel, ' brt_pickup_date_date'); ?></span>
						<span class="has-error"><? echo $form->error($brtModel, ' brt_pickup_date_time'); ?></span>
					</div>
				</div>
            </div>
			<div class="col-6 col-md-6 col-lg-6 col-xl-2">
<!--				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">-->
						<label>Journey Date</label>
						<?php
							$minDate			 = ($brtModel->brt_min_date != '') ? $brtModel->brt_min_date : date('Y-m-d');
							$formattedMinDate	 = DateTimeFormat::DateToDatePicker($minDate);
							echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
								'model'			 => $brtModel,
								'attribute'		 => 'brt_pickup_date_date',
								'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
								'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
									'value'			 => $brtModel->brt_pickup_date_date, 'id'			 => 'brt_pickup_date_date_' . $widgetId,
									'class'			 => 'form-control datePickup border-radius')
							), true);
			        ?>
					</div>
					<div class="col-6 col-md-6 col-lg-6 col-xl-2">
						<label>Journey Time</label>
                            <?php
								$this->widget('ext.timepicker.TimePicker', array(
									'model'			 => $brtModel,
									'id'			 => 'brt_pickup_date_time_' . $widgetId,
									'attribute'		 => 'brt_pickup_date_time',
									'options'		 => ['widgetOptions' => array('options' => array())],
									'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
								));
			                ?>

					</div>

			<div class="col-12 mt-2 pb20 text-center">
				<button type="submit" class="btn btn-primary pl-5 pr-5">Next</button>
			</div>
		</div>
<?php $this->endWidget(); ?>
	</div>
	
	
	<?php
		Filter::createLog("Form Render Completed: " . Filter::getExecutionTime());
	?>
</div>

<script>
	$destCity = null;
	$sourceList = null;


	function loadSource(query, callback)
	{

		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			error: function ()
			{
				callback();
			},
			success: function (res)
			{
				callback(res);
			}
		});
	}
	
	function populateSource(obj, cityId)
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
						obj.setValue('<?= $model->bkg_from_city_id ?>');
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
				obj.setValue('<?= $model->bkg_from_city_id ?>');
			}
		});
	}

	function changeDestination(value, obj)
	{
		if (!value.length)
			return;
		var existingValue = obj.getValue();
		if (existingValue == '')
		{
			existingValue = '<?= $model->bkg_to_city_id ?>';
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

</script>