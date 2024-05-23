<style>

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
	.modal{
		z-index: 400;
	}
	.modal-content{
		padding:20px;
	}
</style>
<?php
//$duration = ['' => '< Select a time >'] + Filter::getQuoteExpiryTime();
?>
<div class="panel-advancedoptions" >
    <div class="row" >
		<div class="col-xs-12 text-center h3 mt0">
			<label for="type" class="control-label"><span style="font-weight: normal; font-size: 15px;">Your final quote expires in: <b><span id="max_date"><?= $model->bkg_quote_expire_max_date ?></span></b></span></label>
		</div>
		<div class="col-xs-12">            
            <div class="panel" >

                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll1">

						<? //= CHtml::beginForm(Yii::app()->createUrl('admin/Booking/PriceLockTest'), "post", ['accept-charset' => "UTF-8", 'id' => "deleteForm", 'onsubmit' => 'return submitDelForm(this);', 'class' => "form", 'data-replace' => ".error-message p"]); ?>
						<?php // echo $form->hiddenField($model, 'btr_bkg_id'); ?>
						<?php // echo $form->hiddenField($model, 'bkg_quote_expire_max_date'); ?>
						<?php
						$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'add-followup-form', 'enableClientValidation' => true,
							'clientOptions'			 => array(
								'validateOnSubmit'	 => true,
								'errorCssClass'		 => 'has-error',
							),
							// Please note: When you enable ajax validation, make sure the corresponding
							// controller action is handling ajax validation correctly.
							// See class documentation of CActiveForm for details on this,
							// you need to use the performAjaxValidation()-method described there.
							'enableAjaxValidation'	 => false,
							'errorMessageCssClass'	 => 'help-block',
							'htmlOptions'			 => array(
								'class' => 'form-horizontal'
							),
						));
						/* @var $form TbActiveForm */
						?>
						<div class="form-group">
							<div class="col-xs-6">
								<?php echo $form->hiddenField($model, 'btr_bkg_id'); ?>
								<?php
								//$qtexprdate = ($model->bkg_quote_expire_date == '') ? $model->bkg_quote_expire_max_date : $model->bkg_quote_expire_date; 
								$currentDate = date(format, [timestamp]);
								$defaultDate = date('Y-m-d H:i:s', strtotime('+0 days'));
								$minDate	 = date('Y-m-d H:i:s', strtotime('+1 min'));
								$endDatemax	 = $model->bkg_quote_expire_max_date;
								$endDate	 = DateTimeFormat::DateTimeToDatePicker($endDatemax);
								$endTime	 = DateTimeFormat::DateTimeToTimePicker($endDatemax);
								//$btrBkgId	 = $model->btr_bkg_id;
								$strrtedate	 = ($model->bkg_quote_expire_date == '') ? date('Y-m-d H:i:s', strtotime('+15 min')) : $model->bkg_quote_expire_date;
								$pdate		 = ($model->bkg_quote_expire_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->bkg_quote_expire_date;
								?>
								<?=
								$form->datePickerGroup($model, 'bkg_quote_expire_date', array('label'			 => 'Quote Expiry Date',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => $minDate, 'endDate' => $endDate, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => "expire date", 'value' => $endDate, 'id' => 'booking_quote_expiry_1', 'class' => 'input-group border-gray full-width')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
							</div>

							<div class="col-xs-6">
								<label><b>Journey Time</b></label>
								<?
								$this->widget('ext.timepicker.TimePicker', array(
									'model'			 => $model,
									'id'			 => 'bkg_quote_expire_time',
									'attribute'		 => 'bkg_quote_expire_time_1',
									'options'		 => ['widgetOptions' => array('options' => array())],
									'htmlOptions'	 => array('required' => true, 'placeholder' => 'price lock time', 'value' => date('h:i A', strtotime($strrtedate)), 'class' => 'form-control border-radius')
								));
								?>
							</div>

						</div>
						<div class="Submit-button text-center" >
							<button type="submit" class="btn btn-primary mt10" onclick="return submitDelForm();" >SUBMIT</button>
						</div>

						<?php $this->endWidget(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script>
//    $(document).ready(function ()
//    {
//        jQuery('#Booking_bkg_quote_expire_date').datepicker({'autoclose': true, 'startDate': new Date(), 'format': 'dd/mm/yyyy', 'language': 'en'});
//        jQuery('#Booking_bkg_quote_expire_date').datepicker({'autoclose': true, 'startDate': new Date(), 'format': 'dd/mm/yyyy', 'language': 'en'});
//    });





    function submitDelForm() {
        var d = document.getElementById("booking_quote_expiry_1").value;
        var t = document.getElementById("bkg_quote_expire_time").value;
        var initial = d.split(/\//).reverse().join('/');
        const dateTime = initial + ' ' + t;
        let current_datetime = new Date(dateTime);
        let formatted_date = current_datetime.getFullYear() + "-" + pad2(current_datetime.getMonth() + 1) + "-" + pad2(current_datetime.getDate()) + " " + current_datetime.getHours() + ":" + pad2(current_datetime.getMinutes()) + ":" + current_datetime.getSeconds();
        var currentdate = new Date();

        var datetime = "Last Sync: " + currentdate.getFullYear() + "-" + pad2(currentdate.getMonth() + 1)
                + "-" + pad2(currentdate.getDate()) + " "
                + currentdate.getHours() + ":"
                + pad2(currentdate.getMinutes()) + ":" + currentdate.getSeconds();
        var maxTime = document.getElementById("max_date").innerHTML;
        var formatted_date1 = new Date(formatted_date).getTime();
        var maxTime1 = new Date(maxTime).getTime();
		var dateTime1=new Date(datetime).getTime();
        var btrBkgId = document.getElementById("BookingTrail_btr_bkg_id").value;
//var compare=dates.compare(formatted_date,maxTime);
        $href = "<?= Yii::app()->createUrl('admin/booking/pricelocktest') ?>";
        if (formatted_date1 > maxTime1 || formatted_date1 < dateTime1) {
            alert("Please select proper time!");
            return false;
        } else
        {
            $.ajax({
                "type": "GET",
                "url": $href,
                'dataType': "json",

                "data": {maxDate: formatted_date, bkg_id: btrBkgId},

                "success": function (data1) {

                    if (data1.success)
                    {
                        alert("Price lock changed successfully!");
                    } else
                    {
                        alert("Error! ");
                    }
                    bootbox.hideAll();
                    return false;
                },
            });

            return false;
        }

    }
    function pad2(number) {

        return (number < 10 ? '0' : '') + number;

    }

$('#bkg_quote_expire_time').timepicker({
     'step': '30'
});

</script>

