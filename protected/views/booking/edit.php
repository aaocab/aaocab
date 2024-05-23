<style>
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .bootstrap-timepicker-widget input  {
        border: 1px #555555 solid;color: #555555;
    }
    .navbar-nav > li > a {
        padding: 6px 30px;
    }
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }
    div .comments .comment {
        padding:3px;max-width:100%
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
    .selectize-input {
        min-width: 0px !important; 
        width: 30% !important; 
    }
    .remarkbox{
        width: 100%; 
        padding: 3px;  
        overflow: auto; 
        line-height: 10px; 
        font: normal arial; 
        border-radius: 5px; 
        -moz-border-radius: 5px; 
        border: 1px #aaa solid;
    }
    .border-none{
        border: 0!important;
    }
    .datepicker.datepicker-dropdown.dropdown-menu ,
    .bootstrap-timepicker-widget.dropdown-menu,
    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}
</style>
<div class="row">
    <div class="col-xs-12 text-center h5 mt0">
        <label for="type" class="control-label"><span style="font-weight: normal; font-size: 15px;">Booking Id:</span> </label>
        <b><?= Filter::formatBookingId($model->bkg_booking_id); ?></b>
    </div>
</div>
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'booking-edit-form', 'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'errorCssClass' => 'has-error',
        'afterValidate' => 'js:function(form,data,hasError){
			if(!hasError){
				$.ajax({
				"type":"POST",
				"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/edit', ['bkg_id' => $model->bkg_id])) . '",
				"data":form.serialize(),
				"dataType": "json",
				"success":function(data1){
						if(data1.success)
						{
								bootbox.hideAll();
								location.reload();
						}
						else{
							var errors = data1.errors;
							settings=form.data(\'settings\');
							$.each (settings.attributes, function (i) {
							$.fn.yiiactiveform.updateInput (settings.attributes[i], errors, form);
							});
							$.fn.yiiactiveform.updateSummary(form, errors);
						}
					},
				});
				}
			}'
    ),
    'enableAjaxValidation' => false,
    'errorMessageCssClass' => 'help-block',
    'htmlOptions' => array(
        'class' => 'form-horizontal'
    ),
        ));
?>
<div class="row">
    <div class="col-xs-12">
        <?php echo CHtml::errorSummary($model); ?>
        <div class="row">
            <div class="col-xs-6">
                <?=
                $form->datePickerGroup($model, 'bkg_pickup_date_date', array('label' => 'Pickup Date',
                    'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Pickup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date))), 'prepend' => '<i class="fa fa-calendar"></i>'));
                ?>
            </div>
            <div class="col-xs-6">
                <?=
                $form->timePickerGroup($model, 'bkg_pickup_date_time', array('label' => 'Pickup Time',
                    'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_pickup_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Pickup Time', 'value' => date('h:i A', strtotime($model->bkg_pickup_date))))));
                ?>
            </div>
        </div>
        <div class="row <?= ($model->bkg_booking_type == 2) ? '' : 'hide' ?>" id="return_div">
            <div class="col-xs-6">
                <? $strrtedate = ($model->bkg_return_date == '') ? '' : DateTimeFormat::DateTimeToDatePicker($model->bkg_return_date); ?>
                <?=
                $form->datePickerGroup($model, 'bkg_return_date_date', array('label' => 'Return Date',
                    'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Return Date', 'value' => $strrtedate)), 'prepend' => '<i class="fa fa-calendar"></i>'));
                ?>
            </div>
            <div class="col-xs-6">
                <?=
                $form->timePickerGroup($model, 'bkg_return_date_time', array('label' => 'Return Time',
                    'widgetOptions' => array('id' => CHtml::activeId($model, "bkg_return_date_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Return Time', 'value' => date('h:i A', strtotime($model->bkg_return_date))))));
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->textAreaGroup($model, 'bkg_instruction_to_driver_vendor', array('label' => 'Additional Instruction to Vendor/Driver', 'widgetOptions' => array('htmlOptions' => array()))) ?>
            </div>
        </div>     
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-center p10">
        <?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
        $('.glyphicon').addClass('fa').removeClass('glyphicon');
        $('.glyphicon-time').addClass('fa-clock-o').removeClass('glyphicon-time');
    });
    $('#Booking_bkg_pickup_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#Booking_bkg_return_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });
</script>