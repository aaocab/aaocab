<style>
    .rating-cancel {
        display: none !important;
        visibility: hidden !important;
    }
    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;
    }
    .padded {
        padding-bottom: 5px;
        padding-top: 5px;
    }
    .fset {
        padding: 5px;
        margin:5px;
        border:1px solid #ddd;
    }
    .lgend {
        border-bottom: 0;
        font-size: 1em;
        width: 78px;
        padding-left: 2px
    }
    .review {
        margin-top: 20px;
        color: #f00;
        font-size: 13px;
        display: none;
        text-align: center;
    }
</style>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<?php
$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
$styleSubmit = 'style="display:none"';
if (isset($type) && $type == 1)
{
	$stylePriceHigh	 = 'style="display:block"';
	$classPriceHigh	 = 'btn btn-primary font-12 mt5 pl10 pr10 hvr-push';
	$styleSubmit	 = 'style="display:block"';
}
else
{
	$stylePriceHigh	 = 'style="display:none"';
	$classPriceHigh	 = 'btn btn-primary font-12 mt5 pl10 pr10 hvr-push';
}
if (isset($type) && $type == 2)
{
	$styleLooking	 = 'style="display:block"';
	$classLooking	 = 'btn btn-primary font-12 mt5 pl10 pr10';
	$styleSubmit	 = 'style="display:block"';
}
else
{
	$styleLooking	 = 'style="display:none"';
	$classLooking	 = 'btn btn-primary font-12 mt5 pl10 pr10 hvr-push';
}
if (isset($type) && $type == 3)
{
	$styleOther	 = 'style="display:block"';
	$classOther	 = 'btn btn-primary font-12 mt5 pl10 pr10';
	$styleSubmit = 'style="display:block"';
}
else
{
	$styleOther	 = 'style="display:none"';
	$classOther	 = 'btn btn-primary font-12 mt5 pl10 pr10 hvr-push';
}
if (isset($type) && $type == 4)
{
	$styleWasConfused	 = 'style="display:block"';
	$classWasConfused	 = 'btn btn-primary font-12 mt5 pl10 pr10 hvr-push';
	$styleSubmit		 = 'style="display:block"';
}
else
{
	$styleWasConfused	 = 'style="display:none"';
	$classWasConfused	 = 'btn btn-primary font-12 mt5 pl10 pr10 hvr-push';
}
?>
<section>
    <div class="container">
        <div class="row justify-center">
			<div class="col-12 col-xl-9 mt-1">
                <div class="card">
					<?php
					$bookingId = $model->bkg_booking_id;
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'unverifiedForm', 'enableClientValidation' => true,
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
                    <div class="card-body p15 radio-style6">
						<h3 class="text-center heading-inner pb10 mb20 border-bottom">Your booking ID: <?= Filter::formatBookingId($model->bkg_booking_id); ?></h3>
                        <div class="">
							<p class="text-center"><b>Tell us why you were not able to complete your gozo booking</b></p>
							<?php
							if ($model3->lfu_id != "")
							{
								$message = 'Thanks for telling us how we can do better. We will be in touch';
							}
							else
							{
								$message = "Our teams work hard to bring you the best quality cabs at the lowest prices possible. But we know, we're not perfect. Tell us what happened, so we can learn and do even better";
							}
							?>
                            <div class="col-12 mb20 text-center"><?= $message ?></div>
							<?php
							if ($model3->lfu_id > 0)
							{
								
							}
							else
							{
								?>

								<div class="row">
									<div class="col-12 col-md-4 col-lg-3">
										<div class="widget-content-box">
											<div class="radio mt-1">
												<input type="radio" name="btnPriceHigh" id="btnPriceHigh" value="0" class="bkg_user_trip_type" onClick="openReconfirmBox(1)" <?php if($_POST['type']==1){ echo 'checked="true"'; }?>>
												<label for="btnPriceHigh"></label>
												<input type="hidden" id="contenttype" value="61">
												<div class="mb-0 label-text weight500">The quoted price was too high</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-md-4 col-lg-3">
										<div class="widget-content-box">
											<div class="radio mt-1">
												<input type="radio" name="btnWasConfused" id="btnWasConfused" value="0" class="bkg_user_trip_type" onClick="openReconfirmBox(4)" <?php if($_POST['type']==4){ echo 'checked="true"'; }?>>

												<label for="btnWasConfused"></label>
												<input type="hidden" id="contenttype" value="61">
												<div class="mb-0 label-text weight500">I have special requirements and need a specialist to call me</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-md-4 col-lg-4">
										<div class="widget-content-box">
											<div class="radio mt-1">
												<input type="radio" name="btnLooking" id="btnLooking" value="0" class="bkg_user_trip_type" onClick="openReconfirmBox(2)" <?php if($_POST['type']==2){ echo 'checked="true"'; }?>>

												<label for="btnLooking"></label>
												<input type="hidden" id="contenttype" value="61">
												<div class="mb-0 label-text weight500">I was having technical issues with the gozo platform</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-md-4 col-lg-2">
										<div class="widget-content-box">
											<div class="radio mt-1">
												<input type="radio" name="btnOther" id="btnOther" value="0" class="bkg_user_trip_type" onClick="openReconfirmBox(3)" <?php if($_POST['type']==3){ echo 'checked="true"'; }?>>

												<label for="btnOther"></label>
												<input type="hidden" id="contenttype" value="61">
												<div class="mb-0 label-text weight500">Other</div>
											</div>
										</div>
									</div>
								</div>

								
								
								<?php
							}
							?>



							<div class="row">
                                <div class="col-12">
                                    <div class="row justify-center m0"  id="priceHighBlock"<?= $stylePriceHigh; ?>>
                                        <div class="col-12" style="margin: auto;">
											<div class="row justify-center">
												<div class="col-12 col-md-6 col-xl-4">
													<div class="row">
														<div class="col-xl-12"><label class="control-label">I got it cheaper by Rs.</label></div>
														<div class="col-xl-12"><?= $form->textFieldGroup($model2, 'lfu_amount', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '', 'id' => 'lfu_comment', 'class' => '', 'style' => '')))) ?></div>
													</div>
												</div>
												<div class="col-12 col-md-6 col-xl-4">
													<div class="row">
														<div class="col-xl-12"><label class="control-label">from</label></div>
														<div class="col-xl-12"><?= $form->textFieldGroup($model2, 'lfu_cmt', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '', 'class' => '', 'id' => 'lfu_cmt', 'style' => '')))) ?></div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="row justify-center m0"  id="bookLaterBlock"<?= $styleLooking; ?>>
										<div class="col-12 col-md-9 col-xl-5" style="margin: auto;">
											<div class="row justify-center">
												<div class="col-12 text-center"><label>I was just looking at Gozo. I plan to travel on</label></div>
												<div class="col-12 col-xl-12">

													<?=
													$form->datePickerGroup($model2, 'locale_lfu_date', array('label'			 => '',
														'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => date(),
																'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('placeholder'	 => '',
																'value'			 => '', 'id'			 => 'lfu_date',
																'class'			 => 'datepicker')),
														'prepend'		 => ''));
													?>    
												</div> 
											</div>
										</div>
									</div>

									<div class="row justify-center m0"  id="otherBlock"<?= $styleOther; ?>>
										<div class="col-12 col-xl-8" style="margin: auto;">
											<div class="row justify-center">
												<div class="col-12 text-center"><label>Tell us more about your experience</label></div>
												<div class="col-12 col-xl-9">
													<?= $form->textAreaGroup($model2, 'lfu_tellus', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => '']))) ?> 
												</div>
											</div>
										</div>
									</div>	
									<div class="row justify-center m0"  id="otherContactBlock"<?= $styleOther; ?>>
										<div class="col-12 col-xl-8" style="margin: auto;">
											<div class="row justify-center">
												<div class="col-12 text-center"><label>What phone number should we call you at</label></div>
												<div class="col-12 col-xl-9">
													<?= $form->textFieldGroup($model2, 'lfu_phone_no', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '', 'id' => 'lfu_contactno')))) ?>
												</div>
											</div>
										</div>
									</div>
									
									<?php
									$pickupDate = ($model->bkg_pickup_date == '') ? date('Y-m-d H:i:s', strtotime('+4 hour')) : $model->bkg_pickup_date;
									$time			 = strtotime(date('H:i', strtotime($pickupDate)));
									$round			 = 30 * 60;
									$rounded		 = round($time / $round) * $round;

									$returnDate = ($model->bkg_pickup_date == '') ? date('Y-m-d H:i:s', strtotime('+4 hour')) : $model->bkg_pickup_date;
									$time2			 = strtotime(date('H:i', strtotime($returnDate)));
									$round2			 = 30 * 60;
									$rounded2		 = round($time2 / $round2) * $round2;
									?>
													
									<div class="row justify-center m0"  id="otherTimeslotBlock"<?= $styleOther; ?>>
										<div class="col-12 col-xl-8" style="margin: auto;">
											<div class="row justify-center">
												<div class="col-12 text-center"><label>Select a preferred timeslot for us to call you 
													<?php
													$this->widget('ext.timepicker.TimePicker', array(
													'model'			 => $model2,
													'id'			 => CHtml::activeId($model2, "lfu_pickup_date_time"),
													'attribute'		 => 'lfu_pickup_date_time',
													'options'		 => ['widgetOptions' => array('options' => array())],
													'htmlOptions'	 => array('required' => true, "value" => date('h:i A',$rounded), 'placeholder' => 'Pickup Time', 'class' => 'no-user-select input-group border-gray full-width route-focus form-control ct-form-control')
													));
													?>   to 
													<?php
													$this->widget('ext.timepicker.TimePicker', array(
													'model'			 => $model2,
													'id'			 => CHtml::activeId($model2, "lfu_return_date_time"),
													'attribute'		 => 'lfu_return_date_time',
													'options'		 => ['widgetOptions' => array('options' => array())],
													'htmlOptions'	 => array('required' => true, "value" => date('h:i A',$rounded2), 'placeholder' => 'Return Time', 'class' => 'no-user-select input-group border-gray full-width route-focus form-control ct-form-control')
													));
													?> 
												</label></div>

											</div>
										</div>
									</div>

									<div class="row justify-center m0" id="wasConfusedBlock"<?= $styleWasConfused; ?>>
										<div class="col-12 col-xl-9" style="margin: auto;">
											<div class="row justify-center">
												<div class="col-12 text-center"><label>Please tell us the reason for the call so we can get the correct team to call you</label></div>
												<div class="col-12 col-xl-9 text-center">
													<?= $form->textAreaGroup($model2, 'lfu_followup', array('label' => 'Tell Us', 'widgetOptions' => array('htmlOptions' => ['placeholder' => '']))) ?> 
												</div>
											</div>
										</div>
									</div>

									<div class="col-12 mb20 mt-1" id="isSubmit" <?= $styleSubmit; ?>>
										<div class="text-center"> <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
									</div>
								</div>
							</div>
						</div>
						
						<?= CHtml::hiddenField('type', $type); ?>
						<?php $this->endWidget(); ?>
					</div>
				</div>
			</div>
		</div>

</section>
<script>
    $(document).ready(function ()
    {


        $("#btnPriceHigh").click(function ()
        {
            $("#btnPriceHigh").removeClass('mt5 btn btn-warning');
            $("#btnPriceHigh").addClass('mt5 btn btn-primary');
            $("#btnLooking").removeClass('mt5 btn btn-primary');
            $("#btnLooking").addClass('mt5 btn btn-warning');
            $("#btnOther").removeClass('mt5 btn btn-primary');
            $("#btnOther").addClass('mt5 btn btn-warning');
            $("#btnWasConfused").removeClass('mt5 btn btn-primary');
            $("#btnWasConfused").addClass('mt5 btn btn-warning');
        });

        $("#btnLooking").click(function ()
        {
            $("#btnLooking").removeClass('mt5 btn btn-warning');
            $("#btnLooking").addClass('mt5 btn btn-primary');
            $("#btnPriceHigh").removeClass('mt5 btn btn-primary');
            $("#btnPriceHigh").addClass('mt5 btn btn-warning');
            $("#btnOther").removeClass('mt5 btn btn-primary');
            $("#btnOther").addClass('mt5 btn btn-warning');
            $("#btnWasConfused").removeClass('mt5 btn btn-primary');
            $("#btnWasConfused").addClass('mt5 btn btn-warning');
        });


        $("#btnOther").click(function ()
        {
            $("#btnOther").removeClass('mt5 btn btn-warning');
            $("#btnOther").addClass('mt5 btn btn-primary');
            $("#btnLooking").removeClass('mt5 btn btn-primary');
            $("#btnLooking").addClass('mt5 btn btn-warning');
            $("#btnPriceHigh").removeClass('mt5 btn btn-primary');
            $("#btnPriceHigh").addClass('mt5 btn btn-warning');
            $("#btnWasConfused").removeClass('mt5 btn btn-primary');
            $("#btnWasConfused").addClass('mt5 btn btn-warning');
        });

        $("#btnWasConfused").click(function ()
        {
            $("#btnWasConfused").removeClass('mt5 btn btn-warning');
            $("#btnWasConfused").addClass('mt5 btn btn-primary');
            $("#btnLooking").removeClass('mt5 btn btn-primary');
            $("#btnLooking").addClass('mt5 btn btn-warning');
            $("#btnPriceHigh").removeClass('mt5 btn btn-primary');
            $("#btnPriceHigh").addClass('mt5 btn btn-warning');
            $("#btnOther").removeClass('mt5 btn btn-primary');
            $("#btnOther").addClass('mt5 btn btn-warning');
        });


    });

    function openReconfirmBox(type)
    {
        hideBlocks();
        switch (type)
        {
            case 1:
                $('#priceHighBlock').show();
                $('#bookLaterBlock').hide();
                $('#otherBlock').hide();
                $('#otherContactBlock').hide();
				$('#otherTimeslotBlock').hide();
                $('#wasConfusedBlock').hide();
                $('#isSubmit').show();
                $('#lfu_date').val('');
                $('#lfu_comment').val('');
                $('#lfu_bkg_tentative_booking').val('');
                $('#type').val('1');
				
				$('#btnPriceHigh').prop('checked', true);
				$('#btnWasConfused').prop('checked', false);
				$('#btnLooking').prop('checked', false);
				$('#btnOther').prop('checked', false); 
                break;
            case 2:
                $('#priceHighBlock').hide();
                $('#bookLaterBlock').show();
                $('#otherBlock').hide();
                $('#otherContactBlock').hide();
				$('#otherTimeslotBlock').hide();
                $('#wasConfusedBlock').hide();
                $('#isSubmit').show();
                //alert($('#lfu_comment').val());
                $('#lfu_amount').val('');
                $('#lfu_cmt').val('');
                $('#lfu_tellus').val('');
                $('#type').val('2');
				
				$('#btnPriceHigh').prop('checked', false);
				$('#btnWasConfused').prop('checked', false);
				$('#btnLooking').prop('checked', true);
				$('#btnOther').prop('checked', false); 
                break;
            case 3:
                $('#priceHighBlock').hide();
                $('#bookLaterBlock').hide();
                $('#otherBlock').show();
                $('#otherContactBlock').show();
				$('#otherTimeslotBlock').show();
                $('#wasConfusedBlock').hide();
                $('#isSubmit').show();
                $('#lfu_date').val('');
                $('#lfu_bkg_tentative_booking').val('');
                $('#lfu_amount').val('');
                $('#lfu_cmt').val('');
                $('#type').val('3');
				
				$('#btnPriceHigh').prop('checked', false);
				$('#btnWasConfused').prop('checked', false);
				$('#btnLooking').prop('checked', false);
				$('#btnOther').prop('checked', true); 
                break;
            case 4:
                $('#priceHighBlock').hide();
                $('#bookLaterBlock').hide();
                $('#otherBlock').hide();
                $('#otherContactBlock').hide();
				$('#otherTimeslotBlock').hide();
                $('#wasConfusedBlock').show();
                $('#isSubmit').show();
                $('#lfu_date').val('');
                $('#lfu_bkg_tentative_booking').val('');
                $('#lfu_amount').val('');
                $('#lfu_cmt').val('');
                $('#lfu_tellus').val('');
                $('#type').val('4');
				
				$('#btnPriceHigh').prop('checked', false);
				$('#btnWasConfused').prop('checked', true);
				$('#btnLooking').prop('checked', false);
				$('#btnOther').prop('checked', false); 
                break;
        }

    }

    function hideBlocks()
    {
        $('#priceHighBlock').hide();
        $('#bookLaterBlock').hide();
        $('#wasConfusedBlock').hide();
        $('#otherBlock').hide();
        $('#otherContactBlock').hide();
		$('#otherTimeslotBlock').hide();
    }

    function reconfirmBooking(bkgId)
    {
        var href = '<?= Yii::app()->createUrl("admin/booking/reconfirmsubmit"); ?>';
        var type = '1';
        $.ajax({
            url: href,
            type: 'GET',
            dataType: "json",
            data: {"bkgId": bkgId, "type": type},
            "success": function (data) {
                //var driverBad = data.drvMarkBad;
                //var carBad = data.carMarkBad;
                //checkRemarkBox(driverBad, carBad, data.logOutput);
            }
        });

    }








</script>
