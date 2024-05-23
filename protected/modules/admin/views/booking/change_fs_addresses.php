<style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    .pac-item >.pac-icon-marker{
        display: none !important;
    }
    .pac-item-query{
        padding-left: 3px;
    }
    .booking-info{
        font-variant: all-petite-caps;
        font-size: initial;
        color: #d46767;
    }
</style>

<div class="panel">
	<div class="panel-body">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'create-trip', 'enableClientValidation' => FALSE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => Yii::app()->createUrl('admin/booking/changefsaddresses'),
			'htmlOptions'			 => array(
				'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
			),
		));
		/* @var $form TbActiveForm */
		?>
		<?
		$fbounds	 = $fpbooking->bkgFromCity->cty_bounds;
		$fboundArr	 = CJSON::decode($fbounds);
		?>
		<b>(FlEXXI Promoter is being picked up at: <?= DateTimeFormat::DateTimeToTimePicker($fpbooking->bkg_pickup_date) ?>) at (<?= $fpbooking->bkg_pickup_address ?>)</b><br>
		Set the address for the pickup point where the car will be picking-up all the riders for the FLEXXI Subscriber bookings related to this TRIP ID
		<div class="col-xs-12 pt20">
			<input type="hidden" id="booking_id" name="booking_id" class="" value="<?= $fpbooking->bkg_id ?>">			
			<input type="hidden" id="ctyLat0" class="" value="<?= $fpbooking->bkgFromCity->cty_lat ?>">
			<input type="hidden" id="ctyLon0" class="" value="<?= $fpbooking->bkgFromCity->cty_long ?> ">
			<input type="hidden" id="ctyELat0" class="" value="<?= round($fboundArr['northeast']['lat'], 6) ?>">
			<input type="hidden" id="ctyWLat0" class="" value="<?= round($fboundArr['southwest']['lat'], 6) ?>">
			<input type="hidden" id="ctyELng0" class="" value="<?= round($fboundArr['northeast']['lng'], 6) ?>">
			<input type="hidden" id="ctyWLng0" class="" value="<?= round($fboundArr['southwest']['lng'], 6) ?>">
			<input type="hidden" id="ctyRad0" class="hide" value="<?= $fpbooking->bkgFromCity->cty_radius ?>">
			<?= $form->hiddenField($brtRoute, "brt_from_latitude", ['id' => 'locLat0']); ?>
			<?= $form->hiddenField($brtRoute, "brt_from_longitude", ['id' => 'locLon0']); ?>
			<?=
			$form->timePickerGroup($brtRoute, 'brt_pickup_datetime', array('label'			 => 'Pick Up Time for all Flexxi Subscribers :',
				'widgetOptions'	 => array('options'		 => array('defaultTime' => false, 'autoclose' => true),
					'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
						//'id'=> CHtml::activeId($bmodel, "bkg_pickup_date_time"),
						'class'			 => 'bootstrap-timepicker input-group border-gray full-width'))));
			?>

		<?= $form->textAreaGroup($brtRoute, "brt_from_location", array('label' => 'Pickup Address(Applies to all FS bookings on this trip)', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_location0", 'class' => "form-control txtpl", 'required' => "required", 'placeholder' => "Pickup Address  (Required)"]))) ?>

		</div>
		<div class="col-xs-12 pull-right">
			<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>


<script type="text/javascript">
    booking_type = 6;

    $(document).ready(function () {

        $('.bootbox').removeAttr('tabindex');

        initializepl(1);
    });











    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
        $(this).on("keydown", function (event) {
            if (event.keyCode === 38 || event.keyCode === 40) {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });
</script>