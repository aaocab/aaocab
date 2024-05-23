<style type="text/css">
	.form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<?
$version			 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false];
?>
<div class="container  mt50">
	<div class="  spot-panel">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'shuttlebookform', 'enableClientValidation' => FALSE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',			 
			'htmlOptions'			 => array(
				'class' => 'form-horizontal'
			),
		));
		/* @var $form TbActiveForm */
		echo $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]);
		echo $form->hiddenField($model, 'bkg_booking_type');
		echo $form->hiddenField($model, 'bkg_from_city_id');
		echo $form->hiddenField($model, 'bkg_to_city_id');
		echo $form->hiddenField($model, 'bkg_shuttle_id');
		?>
		<?= $form->errorSummary($model); ?>
		<?= CHtml::errorSummary($model); ?>
		<input type="hidden" name="step" value="15">

		<div class="row">
			<div class="col-xs-12 col-sm-4 mt30 h3">
				Select Shuttle
			</div>	
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-4">
				<div class="form-group">
					<label class="control-label">Select Date</label>
					<?
					$defaultDate		 = date('Y-m-d H:i:s', strtotime('+7 days'));
					$minDate			 = date('Y-m-d H:i:s', strtotime('+4 hour'));
					$pdate				 = ($model->bkg_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->bkg_pickup_date_date;
					?>
					<?=
					$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '',
						'widgetOptions'	 => array(
							'options'		 => array('autoclose' => true, 'startDate' => $minDate, 'format' => 'dd/mm/yyyy'),
							'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
								'value'			 => $pdate,
								'class'			 => 'border-radius font-16')),
						'groupOptions'	 => ['class' => ''],
						'prepend'		 => '<i class = "fa fa-calendar pr10 font-16 tx-gra-green"></i>'));
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-4 ">
				<div class="form-group">
					<label class=" control-label"> From City </label>
					<div  >
						<select class="form-control  " name="Booking[bkg_from_city_id]"  
								id="Booking_bkg_from_city_id1" onchange="">
						</select>
						<span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 ">
				<div class="form-group">
					<label class=" control-label"> Pickup Address </label>
					<div  >
						<select class="form-control  " name="Booking[bkg_pickup_address]"  
								id="Booking_bkg_pickup_address" onchange="populateDropCity()">
						</select>
						<span class="has-error"><? echo $form->error($model, 'bkg_pickup_address'); ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-4  "> 
				<div class="form-group">
					<label class=" control-label ">To City</label>
					<div class=" ">
						<select class="form-control  " name="Booking[bkg_to_city_id]"  
								id="Booking_bkg_to_city_id1" onchange="populateDropLocation()">
						</select>
						<span class="has-error"><? echo $form->error($model, 'bkg_to_city_id'); ?></span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4  ">
				<div class="form-group">
					<label class=" control-label ">Drop Address</label>
					<div>	
						<select class="form-control  " name="Booking[bkg_drop_address]"  
								id="Booking_bkg_drop_address" onchange="populateShuttleList()">
						</select>
						<span class="has-error"><? echo $form->error($model, 'bkg_drop_address'); ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6  ">
				<div class="form-group">
					<label class=" control-label ">Shuttle List</label>
					<div>	
						<select class="form-control  " name="Booking[bkg_shuttle_id]"   
								id="Booking_bkg_shuttle_id1" >
						</select>
						<span class="has-error"><? echo $form->error($model, 'bkg_shuttle_id'); ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="btndiv" style="display: none;">
			<div class="col-xs-12 text-right mt30">
				<button type="submit" class="btn btn-primary btn-lg pl50 pr50 pt30 pb30" name="step15submit"><b>Next <i class="fa fa-arrow-right"></i></b></button>
			</div>
		</div>



		<?php $this->endWidget(); ?>
	</div></div>
<script type="text/javascript">
	history.pushState(null, null, location.href);
	window.onpopstate = function () {
		history.go(1);
	};
	$(document).ready(function ()
	{
		$('#btndiv').hide();
		populateShuttleSource();
	});
	$sourceList = null;

	$('#Booking_bkg_pickup_date_date').change(function () {
		populateShuttleSource();

	});
	$('#Booking_bkg_from_city_id1').change(function () {
		populatePickupLocation();
//		populateDropCity();

	});

	function populateShuttleSource() {
		dateVal = $('#Booking_bkg_pickup_date_date').val();

		$('#Booking_bkg_pickup_address').html('');
		$('#Booking_bkg_to_city_id1').html('');

		$('#Booking_bkg_drop_address').html('');
		$('#Booking_bkg_shuttle_id1').html('');
		$('#Booking_bkg_from_city_id').val('');
		$('#Booking_bkg_to_city_id').val('');
		$('#Booking_bkg_shuttle_id').val('');
		$('#btndiv').hide();

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
				$('#btndiv').hide();
				$('#Booking_bkg_from_city_id1').children('option').remove();
				$("#Booking_bkg_from_city_id1").append('<option value="">Select Pickup City</option>');
				$.each(data1, function (key, value) {
					$('#Booking_bkg_from_city_id1').append($("<option></option>").attr("value", key).text(value));
				});
			}
		});
	}



	function populatePickupLocation() {

		dateVal = $('#Booking_bkg_pickup_date_date').val();
		fcityVal = $('#Booking_bkg_from_city_id1').val();



		$('#btndiv').hide();
		$('#Booking_bkg_pickup_address').html('');
		$('#Booking_bkg_to_city_id1').html('');
		$('#Booking_bkg_drop_address').html('');
		$('#Booking_bkg_shuttle_id1').html('');			 
		$('#Booking_bkg_to_city_id').val('');
		$('#Booking_bkg_shuttle_id').val('');



		$.ajax({
			"type": "POST",
			dataType: 'json',
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getpickuploc')) ?>",
			data: {
				'dateVal': dateVal, 'fcityVal': fcityVal
			},
			"async": false,
			"success": function (data1)
			{
				$('#btndiv').hide();
				$('#Booking_bkg_pickup_address').children('option').remove();
				$("#Booking_bkg_pickup_address").append('<option value="">Select Pickup Address</option>');
				$.each(data1, function (key, value) {
					$('#Booking_bkg_pickup_address').append($("<option></option>").attr("value", key).text(value));
				});
			}
		});
	}

	function populateDropCity() {

		$('#Booking_bkg_to_city_id1').html('');
		$('#Booking_bkg_drop_address').html('');
		$('#Booking_bkg_shuttle_id1').html('');				 
		$('#Booking_bkg_to_city_id').val('');
		$('#Booking_bkg_shuttle_id').val('');
		$('#btndiv').hide();
		dateVal = $('#Booking_bkg_pickup_date_date').val();
		fcityVal = $('#Booking_bkg_from_city_id1').val();
		fcityLoc = $('#Booking_bkg_pickup_address').val();
		$.ajax({
			"type": "POST",
			dataType: 'json',
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getdropcitylist')) ?>",
			data: {
				'dateVal': dateVal, 'fcityVal': fcityVal, 'fcityLoc': fcityLoc
			},
			"async": false,
			"success": function (data1)
			{
				$('#btndiv').hide();
				$('#Booking_bkg_to_city_id1').children('option').remove();
				$("#Booking_bkg_to_city_id1").append('<option value="">Select Drop City</option>');
				$.each(data1, function (key, value) {
					$('#Booking_bkg_to_city_id1').append($("<option></option>").attr("value", key).text(value));
				});
			}
		});
	}

	function populateDropLocation() {

		$('#Booking_bkg_drop_address').html('');
		$('#Booking_bkg_shuttle_id1').html('');		 
		$('#Booking_bkg_shuttle_id').val('');
		$('#btndiv').hide();
		dateVal = $('#Booking_bkg_pickup_date_date').val();
		fcityVal = $('#Booking_bkg_from_city_id1').val();
		fcityLoc = $('#Booking_bkg_pickup_address').val();
		tcityVal = $('#Booking_bkg_to_city_id1').val();

		$.ajax({
			"type": "POST",
			dataType: 'json',
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getdroploc')) ?>",
			data: {
				'dateVal': dateVal, 'fcityVal': fcityVal, 'fcityLoc': fcityLoc, 'tcityVal': tcityVal
			},
			"async": false,
			"success": function (data1)
			{
				$('#btndiv').hide();
				$('#Booking_bkg_drop_address').children('option').remove();
				$("#Booking_bkg_drop_address").append('<option value="">Select Drop Address</option>');
				$.each(data1, function (key, value) {
					$('#Booking_bkg_drop_address').append($("<option></option>").attr("value", key).text(value));
				});
			}
		});
	}

	function populateShuttleList() {



		$('#Booking_bkg_shuttle_id1').html('');
		$('#Booking_bkg_shuttle_id').val('');
		$('#btndiv').hide();
		dateVal = $('#Booking_bkg_pickup_date_date').val();
		fcityVal = $('#Booking_bkg_from_city_id1').val();
		fcityLoc = $('#Booking_bkg_pickup_address').val();
		tcityVal = $('#Booking_bkg_to_city_id1').val();
		tcityLoc = $('#Booking_bkg_drop_address').val();

		$.ajax({
			"type": "POST",
			dataType: 'json',
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getavailable')) ?>",
			data: {
				'dateVal': dateVal, 'fcityVal': fcityVal, 'fcityLoc': fcityLoc, 'tcityVal': tcityVal, 'tcityLoc': tcityLoc
			},
			"async": false,
			"success": function (data1)
			{
				$('#btndiv').show();
				$('#Booking_bkg_shuttle_id1').children('option').remove();
				$("#Booking_bkg_shuttle_id1").append('<option value="">Select Shuttle</option>');
				$.each(data1, function (key, value) {
					$('#Booking_bkg_shuttle_id1').append($("<option></option>").attr("value", key).text(value));
				});
			}
		});
	}


	$('#Booking_bkg_from_city_id1').change(function () {
		$('#Booking_bkg_from_city_id').val($('#Booking_bkg_from_city_id1').val()).change();

	});
	$('#Booking_bkg_to_city_id1').change(function () {
		$('#Booking_bkg_to_city_id').val($('#Booking_bkg_to_city_id1').val()).change();
	});

	$('#Booking_bkg_shuttle_id1').change(function () {
		$('#Booking_bkg_shuttle_id').val($('#Booking_bkg_shuttle_id1').val()).change();
	});


</script>