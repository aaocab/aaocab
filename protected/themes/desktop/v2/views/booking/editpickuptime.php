<?php
//$cartype = VehicleTypes::model()->getParentVehicleTypes1();
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
if (Yii::app()->request->isAjaxRequest)
{
	$cls = "";
}
else
{
	$cls = "col-lg-4 col-md-6 col-sm-8 pb10";
}
?>
<style>
    .form-horizontal .form-group{
        margin: 0;
    }
    .datepicker.datepicker-dropdown.dropdown-menu,
    .bootstrap-timepicker-widget.dropdown-menu,
    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}
    .selectize-input {
        min-width: 0px !important; 
        width: 100% !important;
    }
    .modal-body{
        padding-bottom: 0
    }
    .modal-header{
        display:block;
    }
    .modal-dialog{ width: 68%;}

    @media (min-width: 768px) and (max-width: 1200px) {
        .modal-dialog{ width: 68%;}
    }
    @media (min-width: 320px) and (max-width: 767px) {
        .modal-dialog{ width: 90%; margin: 0 auto;}
    }
</style>
<div class="row">
    <div class="<?= $cls ?>" style="float: none; margin: auto">
		<?php
		$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'pickuptime-form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
                    if(!hasError){				
								if(!checkValidation())
								{
								   return false;
								}
								
							}
                    }'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal'
			),
		));
		/* @var $form TbActiveForm */
		?>


        <div class="col-12">
            <div class="row">
				<?= $form->hiddenField($model, 'bkg_id') ?>
                <div class="col-12 text-center font-18 mt0 mb20">
                    <b>Booking Id: <?= $model->bkg_booking_id ?></b>
                </div>
				<div class="col-12 text-center font-18 mt0 mb20">
                </div>
                <div class="col-12">
                    <div class="row">
						<div class="col-12 col-sm-12">
							<b>Current Pickup Time:</b><?php echo date('d/m/Y', strtotime($model->bkg_pickup_date)) . ', ' . date('h:i A', strtotime($model->bkg_pickup_date)); ?>
						</div> 
					</div>
                </div>
                <div class="col-12 mt10 mb20">
					<div class="row">
						<label class="control-label col-3">Post pone by</label>
						<div class="form-group col-9">   
							<?php
							$timeSchedule	 = Filter::scheduleTimeInterval();
							$jsonData		 = Filter::getJSON($timeSchedule);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'timeSchedule',
								'val'			 => $model->timeSchedule,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($jsonData)),
								'htmlOptions'	 => array('placeholder' => 'Select Time')
							));
							?>
						</div>
					</div>
                </div>
				<div class="col-12">
                    <div class="row">
						<div class="col-12 col-sm-12" style="display:none" id="resBkg">
							<b>Reschedule Pickup Time:</b> <span id="rescheduleBkg"></span>
						</div> 
					</div>
                </div>

            </div>
        </div>

        <div class="panel-footer text-center mb20">
			<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary text-uppercase gradient-green-blue font-14 border-none mt15', 'onclick' => 'return savetime();')); ?>
        </div>

		<?php $this->endWidget(); ?>
    </div>
</div>

<script type="text/javascript">
	$("#Booking_timeSchedule").change(function () {
		var pickup = "<?= $model->bkg_pickup_date; ?>";
		var pickupDate = new Date(pickup);
		var rescheduleTime = parseInt(this.value);
		var newDateTime = pickupDate.setMinutes(pickupDate.getMinutes() + rescheduleTime);
		var nowDate = new Date(newDateTime);
		var rescheduleDate = nowDate.getDate();
		var rescheduleMonth = nowDate.getMonth() + 1;
		var rescheduleYear = nowDate.getFullYear();
		var rescheduleHour = nowDate.toLocaleString('en-US', {hour: 'numeric', minute: 'numeric', hour12: true});
		var finalRescheduleDateTime = rescheduleDate + "/" + rescheduleMonth + "/" + rescheduleYear + ", " + rescheduleHour;
		if (finalRescheduleDateTime != '') {
			$('#resBkg').show();
			$("#rescheduleBkg").text(finalRescheduleDateTime);
		}
	});
	function savetime()
	{
		var rescheduleTime = $('#Booking_timeSchedule').val();
		if (rescheduleTime == '') {
			alert("Please select time");
			return false;
		}
		if (confirm("Pickup time can be re-schedule only once. \n Do you want to reschedule pickup time?")) {
			saveReschedulePickupTime();
		} else {
			return false;
		}
	}

	function saveReschedulePickupTime()
	{
		var href = '<?= Yii::app()->createUrl("booking/savepickuptime"); ?>';
		$.ajax({
			"url": href,
			"type": "GET",
			"dataType": "json",
			"data": {"bkg_id": $('#Booking_bkg_id').val(), "timePrePost": 1, "timeSchedule": $('#Booking_timeSchedule').val()},
			"success": function (data1)
			{
				if (data1.success)
				{
					alert(data1.message);
					location.reload();
				} else
				{
					alert(data1.message);
					location.reload();
				}

			}
		});
		return false;
	}
</script>


