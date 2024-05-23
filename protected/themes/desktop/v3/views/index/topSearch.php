<style>
.modal-backdrop.show{ z-index: 9!important;}
</style>
<?php

$version = Yii::app()->params['siteJSVersion'];
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/v3/home.js?v=' . $version);
Yii::app()->clientScript->registerPackage("webV3End");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/rap.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(APP_ASSETS . '/js/luxon.min.js?v=' . $version);

if (Yii::app()->user->isGuest)
{
	$uname		 = '';
	$isLoggedin	 = false;
	?>

	<?php
}
else
{
	$isLoggedin	 = true;
	$uname		 = Yii::app()->user->loadUser()->usr_name;
	?>

<?php }
?>

<?php
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
//$api					 = Yii::app()->params['googleBrowserApiKey'];
$api					 = Config::getGoogleApiKey('browserapikey');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$version");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/city.js?v=$version");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/bookingRoute.js?v=$version");

$discovid	 = (isset($_COOKIE['gzcovid'])) ? 'none' : 'block';


$dbDate				 = Filter::getDBDateTime();
$fifteenMin			 = 15 * 60;
$timeStr			 = (ceil(strtotime($dbDate . '+1 hour') / $fifteenMin)) * $fifteenMin;
$defaultNewDate		 = date('Y-m-d H:i:s', $timeStr);

$dateonload = DateTimeFormat::DateTimeToDatePicker($defaultNewDate);
$timeonload =  DateTimeFormat::DateTimeToTimePicker($defaultNewDate);
//Yii::app()->clientScript->registerScriptFile("https://maps.googleapis.com/maps/api/js?key={$api}&libraries=places", CClientScript::POS_END);
?>
<script>var hyperModel = new HyperLocation();</script>
<!--<div id='covid' class="covid-panel" style="display:<?= $discovid ?>">
    <div class="alert alert-warning alert-dismissible pt5 pb5" role="alert" style="background: #fcc521; border-radius: 0;">
        <button type="button" class="close p0 pr10" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 36px;">&times;</span></button>
        GozoCabs' Response to COVID-19 <a class="btn btn-primary p0" href="https://www.gozocabs.com/blog/sanitized-cars-ensuring-safe-ride/" target="_blank" role="button">Know More</a>
    </div>
</div>-->
<div class="container mt20 d-none d-lg-block">
	<div class="card">
		<div class="card-body">
<div class="row">
		<div class="col-12 text-center mb20 tab-view">
			<ul class="nav nav-tabs text-uppercase">
				<li class="otrip nav-item mr0 flex-fill"><a href="#menu4" class="full-width nav-link active" data-toggle="tab">One-way</a></li>
<!--<li class="p0 text-center ostrip"><a href="#menu4" class="full-width  " data-toggle="tab">One-way<br><span class="search-sub-text">SHARED CAB</span></a></li>-->
				<!--					<li class="rtrip"><a href="#menu5" class="search-pad  " data-toggle="tab">Round Trip </a></li>-->
				<li class="nav-item mr0 flex-fill mtrip"><a href="#menu6" class="search-pad nav-link" data-toggle="tab">Round Trip OR Multi Way </a></li>
				<li class="nav-item mr0 flex-fill ttrip" style="white-space: nowrap"><a href="#menu7" class="search-pad nav-link" data-toggle="tab">Airport Transfer</a></li>
				<li class="nav-item mr0 flex-fill ptrip" style="white-space: nowrap"><a href="#menu8" class="search-pad nav-link" data-toggle="tab">Tour Packages</a></li>
				<li class="nav-item mr0 flex-fill drtrip" style="white-space: nowrap"><a href="#menu10" class="search-pad nav-link" data-toggle="tab">Day Rental</a></li>
			</ul>

		</div>

		<div class="tab-content col-12">
			<div class="tab-pane active" id="menu4">
				<?php
				/* @var $form CActiveForm|CWidget */
				$form		 = $this->beginWidget('CActiveForm', array(
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
				/* @var $form CActiveForm */
				/** @var BookingTemp $model */
				$brtModel	 = $model->bookingRoutes[0];
				//print_r($model);exit;
				if (trim($brtModel->brt_from_city_id) == "")
				{
					$brtModel->brt_from_city_id = $model->bkg_from_city_id;
				}
				if (trim($brtModel->brt_to_city_id) == "")
				{
					$brtModel->brt_to_city_id = $model->bkg_to_city_id;
				}
				?>
				<div class="row">
					<div class="col-sm-12 col-lg-6">
						<div class="row">
							<div class="col-sm-6">

								<?php //= $form->errorSummary($brtModel);     ?>
								<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 1, 'id' => 'bkg_booking_type1']); ?>
								<?= $form->hiddenField($model, 'bktyp', ['value' => 1, 'id' => 'bktyp1']); ?>
								<input type="hidden" id="step11" name="step" value="6">
								<label class="text-uppercase font-12"> Going From</label>
								<?php
								$widgetId		 = "bookingSform_" . random_int(99999, 10000000);
								$this->widget('application.widgets.BRCities', array(
									'type'				 => 1,
									'widgetId'			 => $widgetId,
									'model'				 => $brtModel,
									'attribute'			 => 'brt_from_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select City",
								));
								?>
								<span class="has-error"><?php //echo $form->error($brtModel, 'brt_from_city_id');                             ?></span>
							</div>
							<div class="col-sm-6 col-md-6">
								<label class="text-uppercase font-12">Going To</label>
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
								<span class="has-error"><?php echo $form->error($brtModel, ' brt_to_city_id'); ?></span>
								<span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_date'); ?></span>
								<span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_time'); ?></span>
							</div>
						</div>

					</div>
					<div class="col-sm-12 col-lg-4">
						<div class="row">
							<div class="col-sm-6 col-md-6">
								<label class="text-uppercase mb0 font-12">Journey Date</label>
								<?php
								$defaultDate	 = date('Y-m-d H:i:s', strtotime('+2 days'));
								$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+3 days'));
//										$minDate		 = date('Y-m-d H:i:s ', strtotime('+4 hour'));
								$pdate			 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $brtModel->brt_pickup_date_date;
								$minDate			 = ($brtModel->brt_min_date != '') ? $brtModel->brt_min_date : date('Y-m-d');
								$formattedMinDate	 = DateTimeFormat::DateToDatePicker($minDate);

								echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
								  'model'			 => $brtModel,
								  'attribute'		 => 'brt_pickup_date_date',
								  'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
								  'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
									  'value'			 => $brtModel->brt_pickup_date_date, 'id'			 => 'brt_pickup_date_date_' . $widgetId,
									  'class'			 => 'form-control input-style datepicker')
								), true);
								?>

							</div>
							<div class="col-sm-6 col-md-6">
								<label class="text-uppercase mb0 font-12">Journey Time</label>
								<div class="input-group timer-control">

									<?php
									$this->widget('ext.timepicker.TimePicker', array(
										'model'			 => $brtModel,
										'id'			 => 'brt_pickup_date_time_' . $widgetId,
										'attribute'		 => 'brt_pickup_date_time',
										'options'		 => ['widgetOptions' => array('options' => array())],
										'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control timePickup input-style')
									));
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-2 mt15 pb20 mt20 text-right">
						<input type="hidden" name="step" value="5"/>
						<button type="submit" class="btn btn-primary hvr-push text-uppercase"><span>Proceed </span></button>
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>

			<div class="tab-pane" id="menu5">
				<div id='returnform'>
					<?php
					$form1			 = $this->beginWidget('CActiveForm', array(
						'id'					 => 'bookingRform',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form, data, hasError){
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
				alert(content); }  },
				});
				return success;
				}
				}'
						),
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'action'				 => Yii::app()->createUrl('book-cab/round-trip'),
						'htmlOptions'			 => array(
							'class' => 'form-horizontal',
						),
					));
					/* @var $form1 CActiveForm */
					?>
					<div class="row">
						<div class="col-12 col-md-12 col-lg-5 pl0">
							<div class="row">
								<div class="col-12 col-sm-6 col-md-6 col-lg-6">
									<?php //= $form1->errorSummary($model);     ?>
									<div id='bkt'>
										<?= $form1->hiddenField($model, 'bkg_booking_type', ['value' => 2, 'id' => 'bkg_booking_type2']); ?>
										<?= $form1->hiddenField($model, 'bktyp', ['value' => 2, 'id' => 'bktyp2']); ?>
										<?= $form1->hiddenField($brtModel, 'brt_return_date_time', ['value' => '10:00 PM']); ?>
										<input type="hidden" id="step11" name="step" value="6">
										<input type="hidden" id="step22" name="step2" value="2">
									</div>

									<label class="text-uppercase font-12">Going From</label>
									<?php
									$widgetId		 = "bookingRform_" . random_int(99999, 10000000);
									$this->widget('application.widgets.BRCities', array(
										'type'				 => 1,
										'widgetId'			 => $widgetId,
										'model'				 => $brtModel,
										'attribute'			 => 'brt_from_city_id',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select City",
									));
									?>
									<span class="has-error"><?php echo $form->error($brtModel, 'brt_from_city_id'); ?></span>

								</div>
								<div class="col-12 col-sm-6 col-md-6 col-lg-6">
									<div class="input-group">
										<label class="text-uppercase font-12">Going To</label>
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
										<span class="has-error"><?php echo $form1->error($brtModel, 'brt_to_city_id1'); ?></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-12 col-lg-7">
							<div class="row">
								<div class="col-12 col-sm-4 col-lg-3 pr0">

									<label class="text-uppercase mb0 font-12">Start Date</label>
									<?=
									$form->widget('zii.widgets.jui.CJuiDatePicker', array(
										'model'			 => $brtModel,
										'attribute'		 => 'brt_pickup_date_date',
										'options'		 => array('autoclose' => true, 'minDate' => new CJavaScriptExpression('0'), 'maxDate' => '+3M', 'format' => 'dd/mm/yyyy'),
										'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
											'id'			 => 'Booking_bkg_pickup_date_date1',
											'class'			 => 'form-control input-style', 'onchange' => "setDateFormat()",  
											'onload' => "loadDefaultDate()")
											), true);
									?>
									<span class="has-error"><?php echo $form1->error($model, 'bkg_pickup_date_date1'); ?></span>
								</div>
								<div class="col-12 col-sm-4 col-lg-3">
									<label class="text-uppercase mb0 font-12">Start Time</label>
									<?php
									$this->widget('ext.timepicker.TimePicker', array(
										'model'			 => $brtModel,
										'id'			 => 'brt_pickup_date_time_2' . date('mdhis'),
										'attribute'		 => 'brt_pickup_date_time',
										'options'		 => ['widgetOptions' => array('options' => array())],
										'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control input-style')
									));
									?>
								</div>
								<span class="has-error"><?php echo $form1->error($model, 'brt_pickup_date_date1'); ?></span>
								<span class="has-error"><?php echo $form1->error($model, 'brt_pickup_date_time1'); ?></span>

								<div class="col-12 col-sm-4 col-lg-3">

									<label class="text-uppercase mb0">Return Date</label>
									<?php
									echo $form1->widget('zii.widgets.jui.CJuiDatePicker', array(
										'model'			 => $brtModel,
										'attribute'		 => 'brt_return_date_date',
										'options'		 => array('autoclose' => true, 'startDate' => $minDate, 'format' => 'dd/mm/yyyy'),
										'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Return Date',
											'value'			 => DateTimeFormat::DateTimeToDatePicker($defaultRDate), 'id'			 => 'Booking_bkg_return_date_date',
											'class'			 => 'form-control input-style')
											), true);
									?>
								</div>
								<div class="col-sm-4 col-md-6 hide col-lg-3" style="display: none;">
									<label>Return Time</label>

								</div>

								<span class="has-error"><?php echo $form1->error($brtModel, 'brt_pickup_date_date1'); ?></span>
								<span class="has-error"><?php echo $form1->error($model, 'brt_pickup_date_time1'); ?></span>
								<div class="col-sm-12 col-lg-3 pb20 text-right">
									<input type="hidden" name="step" value="6"/>
									<button type="submit" class="btn btn-primary gradient-yellow-orange border-none text-uppercase pt10 pb10 pl20 pr20"><b>Proceed</b></button>
								</div>
							</div>
						</div>
						<?php $this->endWidget(); ?>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="menu6">
				<div id='multiform'>
					<?php
					$form2			 = $this->beginWidget('CActiveForm', array(
						'id'					 => 'bookingMform',
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
						'action'				 => Yii::app()->createUrl('book-cab/multi-city'),
						'htmlOptions'			 => array(
							'class' => 'form-horizontal',
						),
					));
					/* @var $form1 CActiveForm */
					?>
					<div class="row">
						<div class="col-12 col-sm-6 col-md-3">
							<?= $form2->errorSummary($model); ?>
							<div id='bkt'>
								<?= $form2->hiddenField($model, 'bkg_booking_type', ['value' => 3, 'id' => 'bkg_booking_type3']); ?>
								<?= $form2->hiddenField($model, 'bktyp', ['value' => 3, 'id' => 'bktyp3']); ?>
								<input type="hidden" id="step23" name="step2" value="2">
								<input type="hidden" name="step" value="6"/>

							</div>

							<label class="text-uppercase font-12">Going From</label>
							<?php
							$widgetId		 = "bookingMform_" . random_int(99999, 10000000);
							$this->widget('application.widgets.BRCities', array(
								'type'				 => 1,
								'id'				 => 'bkg_from_city_id_1',
								'widgetId'			 => $widgetId,
								'model'				 => $brtModel,
								'attribute'			 => 'brt_from_city_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select City",
								"htmlOptions"		 => ['id' => 'bkg_from_city_id_1']
							));
							?>
							<span class="has-error"><?php echo $form->error($model, 'bkg_from_city_id'); ?></span>

						</div>
						<div class="col-12 col-sm-6 col-md-3">

							<label class="text-uppercase font-12">Going To</label>
							<?php
							$this->widget('application.widgets.BRCities', array(
								'type'				 => 2,
								'id'				 => 'bkg_to_city_id_1',
								'widgetId'			 => $widgetId,
								'model'				 => $brtModel,
								'attribute'			 => 'brt_to_city_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select City",
								"htmlOptions"		 => ['id' => 'bkg_to_city_id_1']
							));
							?>
							<span class="has-error"><?php echo $form1->error($model, 'bkg_to_city_id1'); ?></span>

						</div>
						<div class="col-12 col-md-6">
							<div class="row">
								<div class="col-sm-6 col-md-4">
									<label class="text-uppercase mb0 font-12">Start Date</label>
									<?=
									$form2->widget('zii.widgets.jui.CJuiDatePicker', array(
										'model'			 => $brtModel,
										'attribute'		 => 'brt_pickup_date_date',
										'options'		 => array('autoclose' => true, 'minDate' => new CJavaScriptExpression('0'), 'maxDate' => '+3M', 'format' => 'dd/mm/yyyy'),
										'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
											'value'			 => "", 'id'			 => 'Booking_bkg_pickup_date_date_1',
											'onchange' => "setDateFormat()",
											'class'			 => 'form-control input-style')
											), true);
									?>
								</div>
								<div class="col-sm-6 col-md-3 pr0">
									<label class="text-uppercase mb0 font-12">Start Time</label>

									<?php
									$this->widget('ext.timepicker.TimePicker', array(
										'model'			 => $brtModel,
										'id'			 => 'brt_pickup_date_time_3' . date('mdhis'),
										'attribute'		 => 'brt_pickup_date_time',
										'options'		 => ['widgetOptions' => array('options' => array())],
										'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control input-style')
									));
									?>
								</div>
								<span class="has-error"><?php echo $form2->error($model, 'brt_pickup_date_date_1'); ?></span>
								<span class="has-error"><?php echo $form2->error($model, 'brt_pickup_date_time_1'); ?></span>
								<div class="col-sm-12 col-md-5 mt20 text-right">
									<button type="submit" class="btn btn-primary hvr-push text-uppercase">Add more city</button>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			</div>
			<div class="tab-pane" id="menu7">
				<?php
				/* @var $form CActiveForm|CWidget */
				$form3			 = $this->beginWidget('CActiveForm', array(
					'id'					 => 'bookingTrform',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){

			if(!hasError){
				return true;
			}
			}'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'action'				 => Yii::app()->createUrl('booking/itinerary'),
					'htmlOptions'			 => array(
						'class' => 'form-horizontal',
					),
				));
				/* @var $form CActiveForm */


				$brtModel = $model->bookingRoutes[0];
//print_r($model);exit;
if (trim($brtModel->brt_from_city_id) == "")
{
$brtModel->brt_from_city_id = $model->bkg_from_city_id;
}
if (trim($brtModel->brt_to_city_id) == "")
{
$brtModel->brt_to_city_id = $model->bkg_to_city_id;
}
?>

				<?= $form3->errorSummary($model); ?>
				<?= $form3->hiddenField($model, 'bkg_booking_type', ['value' => 4, 'id' => 'bkg_booking_type4']); ?>
				<?= $form3->hiddenField($model, 'bktyp', ['value' => 4, 'id' => 'bktyp4']); ?>
				<?= $form3->hiddenField($brtModel, 'brt_from_city_id', ['id' => 'ctyIdAir0']); ?>
				<?= $form3->hiddenField($brtModel, 'brt_to_city_id', ['id' => 'ctyIdAir1']); ?>






				<input type="hidden" id="step14" name="step" value="5">
				<div class="row">
					<div class="col-12 col-sm-12 col-lg-8" id="ttype" >
						<div class="row">
							<div class="col-12 col-sm-4 col-lg-4">
								<label class="text-uppercase font-12">Pickup Type</label>
								<div class="btn-group" data-toggle="buttons">
									<?php
									echo $form->dropDownList($model, 'bkg_transfer_type', array("1" => "From the Airport", "2" => "To the Airport"), array('class' => "form-control selectize-input items not-full select-font"));
									?>
								</div>
							</div>
							<div class="col-12 col-sm-4 col-lg-4" id="s122">
								<label id="slabel" class="text-uppercase font-12">From the Airport</label>
<?php
//										$options		 = [];
//										$acWidgetId		 = CHtml::activeId($brtModel, "place");
//										$this->widget('ext.yii-selectize.YiiSelectize', array(
//											'model'				 => $brtModel,
//											'attribute'			 => 'airport',
//											'useWithBootstrap'	 => true,
//											"placeholder"		 => "Select Airport",
//											'fullWidth'			 => false,
//											'htmlOptions'		 => array('width' => '50%'
//											),
//											'defaultOptions'	 => $selectizeOptions + array(
//										'onInitialize'	 => "js:function(){
//													populateAirportList(this, '{$model->bkgAirport}');
//												}",
//										'load'			 => "js:function(query, callback){
//													loadAirportSource(query, callback);
//												}",
//										'onChange'		 => "js:function(value) {
//												PACObject.getObject('{$acWidgetId}').initAirportBounds(value);
//											}",
//										'render'		 => "js:{
//														option: function(item, escape){
//														return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
//														},
//														option_create: function(data, escape){
//														return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
//													   }
//													}",
//											),
//										));
								?>
								<?php
								$options		 = [];
								//$acWidgetId		 = CHtml::activeId($brtModel, "place");
								$acWidgetId		 = CHtml::activeId($brtModel, 'place') . "_" . rand(100000, 9999999);
								$acWidgetIdTo	 = CHtml::activeId($brtModel, 'place') . "_" . rand(100000, 9999999);
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $brtModel,
									'attribute'			 => 'airport',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Airport",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width' => '50%'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
											populateAirportList(this, '{$model->bkgAirport}');
										}",
								'load'			 => "js:function(query, callback){
											loadAirportSource(query, callback);
										}",
								'onChange'		 => "js:function(value) {

								setAddressCity('{$acWidgetId}',value);

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
								<span class="has-error"><?php echo $form->error($brtModel, 'brt_from_city_id'); ?></span>
							</div>
							<div class="col-12 col-sm-4 col-lg-4 form-group-1" >
								<div class="row">
									<label  id="dlabel" class="text-uppercase col-12 font-12">To Address</label>
									<div class="col-12">										
										<?php
										$this->widget('application.widgets.SelectAddress', array(
											'model'			 => $brtModel,
											"htmlOptions"	 => ["class" => "form-control border rounded p10 text-left SelectAddress item", "id" => $acWidgetId] + ['platform' => 0],
											'attribute'		 => "place",
											'widgetId'		 => $acWidgetId,
											"city"			 => "{$brtModel->brt_from_city_id}",
											"modalId"		 => "myAddressModal1"
										));
										?>

									</div>
								</div>
							</div>


<div class="modal full-screen" id="myAddressModal1">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header" style="display: inline-block; padding: 5px 10px 0; border-bottom: 0px">
			<button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
		</div>
		<div class="modal-body p-1">
		</div>
		<div class="modal-body1 p15">

		</div>
	</div>
</div>
</div>
						</div>
					</div>
					<div class="col-12 col-sm-8 col-lg-4">
						<div class="row">
							<div class="col-12 col-sm-6 col-md-4 pr0 pl0 input-style-2">
								<label class="text-uppercase mb0 font-12">Journey Date</label>
								<?php
								$defaultDate	 = date('Y-m-d H:i:s', strtotime('+7 days'));
								$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+8 days'));
								$minDate		 = date('Y-m-d H:i:s', strtotime('+4 hour'));
								$pdate			 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->bkg_pickup_date_date;
								?>
								<?=
								$form->widget('zii.widgets.jui.CJuiDatePicker', array(
									'model'			 => $brtModel,
									'attribute'		 => 'brt_pickup_date_date',
									'options'		 => array('autoclose' => true, 'startDate' => $minDate, 'format' => 'dd/mm/yyyy'),
									'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
										'value'			 => $pdate, 'id'			 => 'Booking_brt_pickup_date_date_11',
										'class'			 => 'form-control input-style', 'onchange' => "setDateFormat()")
										), true);
								?>
							</div>
							<div class="col-12 col-sm-6 col-md-4 pr0 pl5">
								<label class="text-uppercase mb0 font-12">Journey Time</label>
								<div class="input-group full-width">
									<?php
									$this->widget('ext.timepicker.TimePicker', array(
										'model'			 => $brtModel,
										'id'			 => 'brt_pickup_date_time_4' . date('mdhis'),
										'attribute'		 => 'brt_pickup_date_time',
										'options'		 => ['widgetOptions' => array('options' => array())],
										'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control input-style')
									));
									?>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-md-4 pl0 mt20 text-right">
								<button type="button" id="btnTransfer" class="btn btn-primary hvr-push pl15 pr15 text-uppercase">Proceed</button>
							</div>
						</div>
					</div>
				</div>
				<script>

					$('#btnTransfer').click(function()
					{
						$.ajax({
							"type": "POST",
							"async": false,
							"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateAirport')) ?>',
							"data": $('#bookingTrform').serialize(),
							"dataType": "json",
							"success": function(data1)
							{
							//	alert("going itenary");
								if (data1.success)
								{
									if (data1.hasOwnProperty("errors"))
									{
										$("#bkg_booking_type4").val(1);
									}
									$('#bookingTrform').submit();
								}
								else
								{
									var errors = data1.errors;
									var content = "";
									for (var key in errors)
									{
										$.each(errors[key], function(j, message)
										{
											content = content + message + '\n';
										});
									}
									alert(content);
								}
							}

						});
					});
				</script>
				<?php $this->endWidget(); ?>
			</div>

			<div class="tab-pane" id="menu8">
				<div class="col-12">
					<!--<a href="/packages" class="btn btn-primary">Go to Packages</a> -->
					<?php
					$form			 = $this->beginWidget('CActiveForm', array(
						'id'					 => 'driver-register-form', 'enableClientValidation' => FALSE,
						'action'				 => array('/packages'),
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
						),
					));
					/* @var $form CActiveForm */

					$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
						'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
						'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
						'openOnFocus'		 => true, 'preload'			 => false,
						'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
						'addPrecedence'		 => false,];
					?>
					<div class="row">
						<div class="col-5 pl0"><label class="text-uppercase font-12">Going From</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'from_city',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select City",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
							$('.selectize-control INPUT').attr('autocomplete','new-password');
	  populateSourceCityPackage(this, '{$model->from_city}');
					}",
							'load'			 => "js:function(query, callback){
	loadSourceCityPackage(query, callback);
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
						</div>
						<div class="col-5">
							<div class="row">
								<?php
								$model->min_nights	 = 0;
								$model->max_nights	 = 10;
								?>
								<div class="col-6 style-widget-1">
									<label class="text-uppercase font-12">Min No. of Nights</label>
									<?php echo $form->numberField($model, 'min_nights', ['min' => 0, 'class' => 'form-control']); ?>
								</div>

								<div class="col-6 style-widget-1">
									<label class="text-uppercase font-12">Max No. of Nights</label>
									<?php echo $form->numberField($model, 'max_nights', ['min' => 0, 'class' => 'form-control']); ?>
								</div>
							</div>
						</div>
						<div class="col-2 text-right mt20 pr0"><input type="submit" class="btn btn-primary hvr-push" value="PROCEED"></div>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			</div>

			<div class="tab-pane" id="menu10">
				<?php
				$form1				 = $this->beginWidget('CActiveForm', array(
					'id'					 => 'bookingRentalform',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form, data, hasError){
				if(!hasError){
				var success = false;
				$.ajax({
				"type":"POST",
				"async":false,
				"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateDayRentalSearch')) . '",
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
					'action'				 => Yii::app()->createUrl('booking/itinerary'),
					'htmlOptions'			 => array(
						'class' => 'form-horizontal',
					),
				));
				/* @var $form CActiveForm */

				$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
					'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
					'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
					'openOnFocus'		 => true, 'preload'			 => false,
					'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
					'addPrecedence'		 => false,];
				?>
				<div class="row">
					<div class="col-sm-12 col-lg-6">
						<div class="row">
							<div class="col-lg-6">
								<label class="text-uppercase font-12"> Going From</label>
								<?php
								$widgetId			 = "bookingDRform_" . random_int(99999, 10000000);
								$this->widget('application.widgets.BRCities', array(
									'type'				 => 1,
									'id'				 => 'bkg_from_city_id',
									'widgetId'			 => $widgetId,
									'model'				 => $brtModel,
									'attribute'			 => 'brt_from_city_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select City",
									"htmlOptions"		 => ['id' => 'bkg_from_city_id']
								));

								echo $form->hiddenField($brtModel, "brt_from_location", ['id' => 'Onelocation0']);
								echo $form->hiddenField($brtModel, "brt_from_latitude", ['id' => 'OnelocLat0']);
								echo $form->hiddenField($brtModel, "brt_from_longitude", ['id' => 'OnelocLon0']);
								echo $form->hiddenField($brtModel, "brt_from_formatted_address", ['id' => 'OnelocFAdd0']);
								echo $form->hiddenField($brtModel, 'brt_from_is_airport', ['id' => 'OneisAirport0']);
								echo $form->hiddenField($brtModel, 'brt_to_city_id', ['id' => 'bkg_to_city_id']);
								?>
								<input  id="bkg_booking_type_rental" name="BookingTemp[bkg_booking_type]" type="hidden">
								<input  id="bktyp_rental" name="BookingTemp[bktyp]" type="hidden">
								<input type="hidden" id="step11" name="step" value="6">
								<span class="has-error"><? //echo $form->error($brtModel, 'brt_from_city_id');                ?></span>
							</div>
							<div class="col-sm-6 col-md-6">
								<label class="text-uppercase font-12">Rental Type</label>
								<?php
								$rentalTypeArr	 = Booking::model()->rental_types;
								$rentalTypeArr	 = ['' => 'Rental Types'] + $rentalTypeArr;
								echo $form->dropDownList($model, "bkg_booking_type", $rentalTypeArr, ['id' => 'BookingTemp_bkg_booking_type_rental', 'class' => 'form-control', 'placeholder' => 'Rental Types']);
								?>
							</div>
						</div>

					</div>
					<div class="col-sm-12 col-lg-4">
						<div class="row">
							<div class="col-sm-6 col-md-6">
								<label class="text-uppercase mb0 font-12">Journey Date</label>
								<?php
								$defaultDate	 = date('Y-m-d H:i:s', strtotime('+2 days'));
								$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+3 days'));
								$minDate		 = date('Y-m-d H:i:s ', strtotime('+4 hour'));
								$pdate			 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $brtModel->brt_pickup_date_date;
								?>
								<?=
								$form->widget('zii.widgets.jui.CJuiDatePicker', array(
									'model'			 => $brtModel,
									'attribute'		 => 'brt_pickup_date_date',
									'options'		 => array('autoclose' => true, 'startDate' => $minDate, 'format' => 'dd/mm/yyyy'),
									'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
										'value'			 => $pdate, 'id'			 => 'Booking_bkg_pickup_date_date_rental',
										'class'			 => 'form-control input-style', 'onchange' => "setDateFormat()")
										), true);
								?>
								<input type='hidden' id="<?= 'brt_pickup_date_time_' . date('mdhis') ?>" name="BookingRoute[brt_pickup_date_time]"  value="<?= $brtModel->brt_pickup_date_time ?>" >
							</div>
							<div class="col-sm-6 col-md-6">
								<label class="text-uppercase mb0 font-12">Journey Time</label>
								<div class="input-group timer-control">

									<?php
									$this->widget('ext.timepicker.TimePicker', array(
										'model'			 => $brtModel,
										'id'			 => 'brt_pickup_date_time_rental' . date('mdhis'),
										'attribute'		 => 'brt_pickup_date_time',
										'options'		 => ['widgetOptions' => array('options' => array())],
										'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control input-style')
									));
									?>
								</div>
							</div>
							<span class="has-error"><? echo $form->error($brtModel, ' brt_to_city_id'); ?></span>
							<span class="has-error"><? echo $form->error($brtModel, ' brt_pickup_date_date'); ?></span>
							<span class="has-error"><? echo $form->error($brtModel, ' brt_pickup_date_time'); ?></span>
						</div>
					</div>
					<div class="col-12 col-lg-2 mt20  pb20 text-right">
						<input type="hidden" name="step" value="5"/>
						<button type="button" class="btn btn-primary hvr-push text-uppercase" id="dayrentalbtn"><span>Proceed </span></button>
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>

			<?php
			Logger::create("Form Render Completed: " . Filter::getExecutionTime());
			?>
		</div>
	</div>
</div>
</div>
</div>

		<script>
			$fromCity = '<?= $datacity ?>';
			var toCity = [];
			var toCity1 = [];
			var toCity2 = [];
			var toCity4 = [];
			var airportList = [];
			var trlocList = [];
			$destCity = null;
			$(function()
			{
				$(window).on("scroll", function()
				{
					if ($(window).scrollTop() > 50)
					{
						$(".top-menu").addClass("white-header");
					}
					else
					{
						$(".top-menu").removeClass("white-header");
					}
				});
				
				$('.datepicker').datepicker({
					minDate: 0,
					dateFormat: 'dd/mm/yy',
				 });
				
				$('.timePickup').timepicker({
					timeFormat: 'hh:mm p',
					interval: '15',
					minTime: '24',
					startTime: '24:00',
					dynamic: false,
					dropdown: true,
					scrollbar: true
				});
				
				$('#style2c').css('z-index','0');
				
				var DateTime = luxon.DateTime;
				var dafaultdate = DateTime.now().plus({days: 1}).setZone("Asia/kolkata").toFormat('dd/MM/yyyy');
				var dafaulttime = "06:00 PM";
				
				$("input[name='BookingRoute[brt_pickup_date_date]']").val(dafaultdate);
				$("input[name='BookingRoute[brt_pickup_date_time]']").val(dafaulttime);
			});
			$(document).ready(function()
			{

				$("#bkg_pickup_date_time1").selectize();
				$("#bkg_pickup_date_time2").selectize();
				$("#bkg_pickup_date_time3").selectize();
				$("#bkg_pickup_date_time4").selectize();
				$("#bkg_pickup_date_time5").selectize();
				//        populateData();
				//        populateDataR();
				//        populateDataM();
				if (window.location.hash == '#airport-transfer')
				{
					$('.otrip').removeClass('active');
					$('.home-search').removeClass('active');
					$('.home-search1').removeClass('active');
					$('#ttrip').addClass('active');
					$('#menu7').addClass('active');
				}

		<?php
		if (strtoupper($tripType) == 'DAY-RENTAL')
		{
			?>
					$('.drtrip a').click();
				<? } ?>

			<?php
			if (strtoupper($tripType) == 'SHUTTLE')
			{
				?>
					$('.strip a').click();
				<?php
			}
			if (strtoupper($tripType) == 'AIRPORT-TRANSFERS')
			{
				?>
						$('.ttrip a').click();
			<?php } ?>

					populateShuttleSource('<?= $brtModel->brt_from_city_id ?>');
					});
					$sourceList = null;

					function loadAirportSource(query, callback)

					{
						//debugger;

									$.ajax({
									url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>?q=' + encodeURIComponent(query),
											type: 'GET',
											dataType: 'json',
											error: function ()
											{
											callback();
										},
										success: function (res)
										{
											//debugger;
										callback(res);
										}
								});
				}
				function loadTime(query, callback)
					{

								//	if (!query.length) return callback();
								$.ajax({
								url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/timedrop')) ?>?q=' + encodeURIComponent(query),
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

								function populatePackage(obj, pckid)
								{
								obj.load(function (callback)
								{
								var obj = this;
										if ($sourceList == null)
								{
								xhr = $.ajax({
								url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/package')) ?>',
										dataType: 'json',
										data: {
										pckid: pckid
										},
										//  async: false,
										success: function (results)
										{
										$sourceList = results;
												obj.enable();
												callback($sourceList);
												obj.setValue('<?= $model->bkg_package_id ?>');
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
										obj.setValue('<?= $model->bkg_package_id ?>');
								}
								});
								}
								function loadPackage(query, callback)
									{

								$.ajax({
								url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/package')) ?>?q=' + encodeURIComponent(query),
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

							function populateAirportList(obj, cityId)
							{
								//alert("hjghjg");
								obj.load(function (callback)
								{
								var obj = this;
										if ($sourceList == null)
								{
								xhr = $.ajax({
								url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>',
										dataType: 'json',
										data: {
										city: cityId
										},
										//  async: false,
										success: function (results)
										{
											//alert(callback);
										$sourceList = results;
												obj.enable();
												callback($sourceList);
												obj.setValue('<?= $model->bkgAirport ?>');
										},
										error: function ()
										{
										callback();
										}
								});
								}
								else
								{
									alert("ghfghfh");
								obj.enable();
										callback($sourceList);
										obj.setValue('<?= $model->bkgAirport ?>');
								}
								});
									}

									function changeTrDestination(value, obj)
									{
								if (!value.length)
								return;
								var existingValue = obj.getValue();
								if (existingValue == '')
						{
						existingValue = '<?= $model->bkgTransferLoc ?>';
						}
						obj.disable();
								obj.clearOptions();
								obj.load(function (callback)
								{
								//  xhr && xhr.abort();
								xhr = $.ajax({
								url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportnearest')) ?>/source/' + value,
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


									$('#bookingtimform1').submit(function (event)
									{

								fcity = $('#Booking_bkg_from_city_id').val();
								tcity = $('#Booking_bkg_to_city_id').val();
								// alert(tcity);
								});

							$('#rtrip').click(function ()
							{
								$('#bkt #bkg_booking_type2').val(2);
								$('#bkt #bktyp2').val(2);
								});
								$('#mtrip').click(function ()
									{
								$('#bkt #bkg_booking_type3').val(3);
								$('#bkt #bktyp3').val('3');
									});
										$('#ptrip').click(function ()
										{
								$('#bkt #bkg_booking_type5').val(5);
								$('#bkt #bktyp5').val('5');
											});


											function viewList(obj)
											{
								var href2 = $(obj).attr("href");
								$.ajax({
								"url": href2,
										"type": "GET",
										"dataType": "html",
										"success": function (data)
										{
										var box = bootbox.dialog({
										message: data,
												title: 'Booking Details',
												size: 'large',
												onEscape: function ()
												{
												// user pressed escape
												},
										});
										}
								});
								return false;
								}

								$sourceList22 = null;
								function populateSourceCityPackage(obj, cityId)
								{

								obj.load(function (callback)
								{
								var obj = this;
										if ($sourceList22 == null)
								{
								xhr = $.ajax({
								url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
										dataType: 'json',
										data: {
										// city: cityId
										},
										//  async: false,
										success: function (results)
										{
										$sourceList22 = results;
												obj.enable();
												callback($sourceList22);
												obj.setValue(cityId);
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
										callback($sourceList22);
										obj.setValue(cityId);
								}
								});
									}
									function loadSourceCityPackage(query, callback)
										{
								$.ajax({
								url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1')) ?>?apshow=1&q=' + encodeURIComponent(query),
										type: 'GET',
										dataType: 'json',
										global: false,
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

												function populateShuttleSource(fromCityId) {
								dateVal = $('#brt_pickup_date_date_shuttle').val();
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
										$('.inputSource').html('');
												$('.inputSource').children('option').remove();
												$(".inputSource").append('<option value="">Select City</option>');
												$.each(data1, function (key, value) {
												$('.inputSource').append($("<option></option>").attr("value", key).text(value));
												});
												if (fromCityId > 0)
										{
										$('.inputSource').val(fromCityId).change();
										}
										}
								});
														}

														function populateRentalSource(obj, cityId) {
								obj.load(function (callback)
								{
								var obj = this;
										if ($sourceList == null)
								{
								xhr = $.ajax({
								url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/dayrentalcitylist')) ?>',
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

														$('#dayrentalbtn').click(function () {
								$.ajax({
								"type": "GET",
										"async": false,
										"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateDayRental')) ?>',
										"data": {'fromCityId': $('#bkg_from_city_id').val(), 'bkType': $('#BookingTemp_bkg_booking_type_rental').val()},
										"dataType": "json",
										"success": function (data1)
										{
										if (data1.success == true)
										{
										$('#bkg_booking_type_rental').val(data1.bkType);
												$('#bktyp_rental').val(data1.bkType);
												$('#OnelocLat0').val(data1.from.cty_lat);
												$('#OnelocLon0').val(data1.from.cty_long);
												$('#OnelocFAdd0').val(data1.from.cty_garage_address);
												$('#Onelocation0').val(data1.from.cty_garage_address);
												$('#OneisAirport0').val(data1.from.cty_is_airport);
												$('#bookingRentalform').submit();
										}
										else
										{
										$('#bkg_booking_type_rental').val(data1.bkType);
												$('#bktyp_rental').val(data1.bkType);
												$('#OnelocLat0').val('');
												$('#OnelocLon0').val('');
												$('#OnelocFAdd0').val('');
												$('#Onelocation0').val('');
												$('#OneisAirport0').val('');
												if (($("#BookingTemp_bkg_booking_type_rental").val() == '' || $("#BookingTemp_bkg_booking_type_rental").val() == null || $("#BookingTemp_bkg_booking_type_rental").val() == undefined) || ($("#bkg_from_city_id").val() == '' || $("#bkg_from_city_id").val() == null || $("#bkg_from_city_id").val() == undefined)) {
										var content = "You Should Enter City/Rental Trip Type.";
												if (data1.errorMsg != '' || data1.errorMsg != undefined)
										{
										content = data1.errorMsg;
										}
										alert(content);
										}
										}
										}
								});
															});

															function populateDropCity(toCityId) {

								dateVal = $('#brt_pickup_date_date_shuttle').val();
								fcityVal = $('.inputSource').val();
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
										$('.destSource').html('');
												$('.destSource').children('option').remove();
												$(".destSource").append('<option value="">Select Drop City</option>');
												$.each(data1, function (key, value) {
												$('.destSource').append($("<option></option>").attr("value", key).text(value));
												});
												if (toCityId > 0)
										{
										$('.destSource').val(toCityId).change();
										}
										}
								});
														}


													$('#brt_pickup_date_date_shuttle').change(function () {
								$('.destSource').val('');
								populateShuttleSource();
										});

										$('select[name="BookingTemp[bkg_transfer_type]"]').change(function (event) {
								var radVal = $(event.currentTarget).val();
								var dlabel = (radVal == 2) ? 'From Address' : 'To Address';
								var slabel = (radVal == 1) ? 'From the Airport' : 'To the Airport';
								$('#slabel').text(slabel);
								$('#dlabel').text(dlabel);
								$('#trslabel').text(slabel);
								$('#trdlabel').text(dlabel);
								if (radVal == 2)
						{
						$('.autoMarkerLoc').attr('data-original-title', 'Select source location on map');
						}
						else
						{
						$('.autoMarkerLoc').attr('data-original-title', 'Select destination locationon map');
						}
											});

											$('.autoMarkerLoc').click(function (event) {
								var locKey = $(event.currentTarget).data('lockey');
								var lat = $('#locLat1').val();
								var long = $('#locLon1').val();
								var isAirport = $('#isAirport1').val();
								if (lat == '' || long == '')
						{
						lat = $('#locLat0').val();
								long = $('#locLon0').val();
						}
						if (lat == '' || long == '')
						{
						alert("Please select airport first");
						}
						else
						{
						var transferType = $('#BookingTemp_bkg_transfer_type').val();
								var Loclabel = (transferType == 1) ? "Enter approximate destination location and then move pin to exact location" : "Enter approximate source location and then move pin to exact location";
								var locSearch = (transferType == 1) ? "destination" : "source";
								$('#mapModalLabel').html(Loclabel);
								$.ajax({
								"type": "POST",
										"url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/autoMarkerAddress')) ?>',
										"data": {"ctyLat": lat, "ctyLon": long, "bound": '', "isCtyAirport": isAirport, "isCtyPoi": 0, "locKey": locKey, "location": locSearch, "airport": 1, "YII_CSRF_TOKEN": $("input[name='YII_CSRF_TOKEN']").val()},
										"dataType": "HTML",
										"success": function (data1)
										{
										$('#mapModal').removeClass('fade');
												$('#mapModal').css('display', 'block');
												$('#mapModelContent').html(data1);
												$('#mapModal').modal('show');
										}

								});
						}
													});

														$('.search-pad').click(function (event) {
								$('.full-width').removeClass('active');
														});

															$('#contactus').click(function () {
								var href2 = "<?= Yii::app()->createUrl('scq/callback') ?>";
					$.ajax({
					"url": href2,
							"data": {"desktheme": 1},
							"type": "GET",
							"dataType": "html",
							"success": function (data) {
							$('#callmeback').removeClass('fade');
									$('#callmeback').css("display", "block");
									$('#callmebackBody').html(data);
									$('#callmeback').modal('show');
							}
					});
					return false;
												});

											$('.close').click(function(){
					var cookieName = 'gzcovid';
					var cookieValue = 1;
					var date = new Date();
					date.setTime(date.getTime() + (6 * 60 * 60 * 1000));
					var expires = "; expires=" + date.toUTCString();
					document.cookie = cookieName + "=" + cookieValue + expires + "; path=/";
					$('#covid').hide();
												});
												
			function setDateFormat() {
				$( "#BookingRoute_brt_pickup_date_date" ).datepicker( "option", "dateFormat", 'dd/mm/yy' );
				$("#Booking_bkg_pickup_date_date_1").datepicker( "option", "dateFormat", 'dd/mm/yy' );
				$('#Booking_brt_pickup_date_date_11').datepicker( "option", "dateFormat", 'dd/mm/yy' );
				$( "#brt_pickup_date_date_shuttle" ).datepicker( "option", "dateFormat", 'dd/mm/yy' );
				$( "#Booking_bkg_pickup_date_date_rental").datepicker( "option", "dateFormat", 'dd/mm/yy' );
			}	
</script>


