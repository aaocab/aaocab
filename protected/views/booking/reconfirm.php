<style>
    .rating-cancel{
        display: none !important;
        visibility: hidden !important;
    }
    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;
    }
    .padded{
        padding-bottom: 5px;
        padding-top: 5px;
    }
    .fset{
        padding: 5px;margin:5px; border:1px solid #ddd;
    }
    .lgend{
        border-bottom: 0;font-size: 1em;width: 78px;padding-left: 2px
    }
    .review
    {
        margin-top: 20px;color: #f00;font-size: 13px;display: none;text-align: center;
    }
	.marginauto{
		margin-left: auto;
		margin-right: auto;
	}
</style>
<script src="https://apis.google.com/js/platform.js" async defer></script>

<?
$bkg_booking_id = Filter::formatBookingId($model->bkg_booking_id);
$link		 = 'http://www.gozocabs.com/invite/' . $refCode;
$mailBody	 = "Dear Friend,%0D%0DI traveled with Gozocabs and and loved it. Try Gozo with the URL below and both you and I will get Rs. 200 credit for our next trip.%0D$link %0DHere is my review from my trip "
		. $bkg_booking_id . ':%0D "'
		. $model->ratings[0]['rtg_customer_review'] . '"%0D%0DRegards%0D' . $model->bkgUserInfo->getUsername();
?>

<?
$cabmodel	 = $model->getBookingCabModel();
$vehicleModel = $cabmodel->bcbCab->vhcType->vht_model;
if($cabmodel->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($cabmodel->bcb_vendor_id, $cabmodel->bcb_cab_id);
}
$bookingInfo = '<div class="panel-body">
	<div class=" ">
		<fieldset class="fset">
			<legend class="mb0 lgend">Booking Info</legend>

			<div class="col-xs-6 padded">
				Picked up on : <span class="h5">' . DateTimeFormat::DateTimeToLocale($model->bkg_pickup_date) . '</span>
			</div>
			<div class="col-xs-6 padded">
				Booking Time : <span class="h5">' . DateTimeFormat::DateTimeToLocale($model->bkg_create_date) . '</span>
			</div>
			<div class="col-xs-6 padded">
				Route : <span class="h5">' . $model->bkgFromCity->cty_name . "-" . $model->bkgToCity->cty_name . '</span>
			</div>
			<div class="col-xs-6 padded">
				Driver : <span class="h5">' . $cabmodel->bcbDriver->drv_name . '</span>
			</div>
			<div class="col-xs-12 padded ">
				Cab : <span class="h5">' . $vehicleModel . " " . $cabmodel->bcbCab->vhc_number . '</span>
			</div>
		</fieldset>
	</div>
</div>';
$hash		 = Yii::app()->shortHash->hash($model->bkg_id);
?>
<section style="color:#555555">
    <div class="container">
        <div class="row">

            <div class="col-xs-12 col-md-6   marginauto   "  >
                <div class="panel border"  >
					<h3 class="text-uppercase text-center   mb10 weight400 mt10">Reconfirm Booking</h3>
                    <div class='panel-heading h3 text-danger text-center  '><?= $bookingcode ?></div>
					<?
					if ($model->bkgPref->bkg_is_gozonow == 1)
					{
						?>
						<div class="col-xs-12   text-center p10" style="color:#666666"><b><?= $messsage
						?>
							</b>
						</div> 
						<?
					}
					else
					{

						if (!$ifReviewExist)
						{
							echo $bookingInfo;
						}
						?>
						<?php
						$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'reconfrimForm', 'enableClientValidation' => true,
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
						<div class="panel-body  ">

							<?php
							if (($model->bkg_reconfirm_flag == 1 || $model->bkg_reconfirm_flag == 2) || $model->bkg_status == 9)
							{
								$message = "Thanks! We have already logged your response in our system";
								?> 
								<div class="col-xs-12 mb20 text-center"><b><?= $message; ?></b></div>
								<?php
							}
							else
							{
								?>    
								<div class="panel-scroll1">
									<?php
									/** @var \Booking $model */
									if ($messsage == "" && $model->bkgPref->bkg_is_gozonow == 0)
									{
										?>
										<div class=" p5">
											<div class="col-xs-12   text-center" style="color:#666666">
												<span class="mt5 btn btn-success" style="cursor:pointer;" ><a href="<?= Yii::app()->createUrl("booking/reconfirm?bookingId=" . $model->bkg_id . "&type=1") ?>" style="color:#FFF; text-decoration: none;">Yes, I'm going.  Reconfirm my booking</a></span>
												<span class="mt5 btn btn-danger" onclick="openReconfirmBox(2)" style="cursor:pointer;">No I'm not going</span>
												<span class="mt5 btn btn-warning" onclick="openReconfirmBox(3)" style="cursor:pointer;">Plans Delayed, Please Reschedule</span>
											</div>
										</div>
										<?php
									}
									else
									{
										?>
										<div class="col-xs-12 mb20 text-center" style="color:#666666"><b><?= $messsage ?></b></div> 
										<?php
									}
									?>
									<div class="row" id="notGoingReconfirmBlock">
										<div class="col-xs-10 marginauto float-none mb10" style="color:#666666">
											<?php
											$cancelList						 = VehicleTypes::model()->getJSON(CancelReasons::model()->getListbyUserType(1)[0]);
											$rDetail						 = CancelReasons::model()->getListbyUserType(1);
											$reasonPHList					 = $rDetail[1];
											$jsReasonPHList					 = json_encode($reasonPHList);
											$model->bkg_cancel_delete_reason = '';
											$this->widget('booster.widgets.TbSelect2', array(
												'model'			 => $model,
												'attribute'		 => 'bkg_cancel_id',
												'val'			 => $model->bkg_cancel_id,
												'asDropDownList' => FALSE,
												'options'		 => array('data' => new CJavaScriptExpression($cancelList)),
												'htmlOptions'	 => array('style' => 'width:100%; ', 'placeholder' => 'Cancel Reasons', 'title' => ""),
											));
											?>
										</div>
										<div class="col-xs-12" id="reasontext" style="display: none">
											<div class="col-xs-1 text-center">&nbsp;</div>
											<div class="col-xs-10 text-center"><?= $form->textFieldGroup($model, 'bkg_cancel_delete_reason', array('label' => '', 'widgetOptions' => array(), 'htmlOptions' => array('placeholder' => 'Enter Reason'))); ?></div>
											<div class="col-xs-1 text-center">&nbsp;</div>
										</div> 
										<div class="col-xs-12 mb20" style="color:#666666">
											<div class="col-xs-1 text-center">&nbsp;</div>
											<div class="col-xs-10 text-center"> <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
											<div class="col-xs-1 text-center">&nbsp;</div>
										</div> 
									</div>

									<div class="row" id="delayedReconfirmBlock">
										<div class="col-xs-12 marginauto" float-none mb10>
											<div class="col-xs-1"></div>
											<div class="col-xs-5 marginauto float-left mb10" style="color:#666666">
												<?=
												$form->datePickerGroup($model, 'bkg_pickup_date_date', array('label'			 => '',
													'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => date(),
															'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
															'value'			 => $pdate, 'id'			 => 'pickup_date',
															'class'			 => 'datepicker')),
													'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>
											</div> 
											<div class="col-xs-5 marginauto float-left mb10">
												<?=
												$form->timePickerGroup($model, 'bkg_pickup_date_time', array('label'			 => '',
													'widgetOptions'	 => array('options'		 => array('defaultTime' => false, 'autoclose' => true),
														'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Time',
															'value'			 => $ptime, 'id'			 => 'pickup_time',
															'class'			 => 'form-control pr0 border-radius text text-info'))));
												?> 
											</div>
											<div class="col-xs-1"></div>
										</div>
										<div class="col-xs-12" style="color:#666666">
											<div class="col-xs-1 text-center">&nbsp;</div>
											<div class="col-xs-10 text-center"> <?= $form->textAreaGroup($model, 'bkg_pickup_address', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Pickup Location', 'id' => 'pickup_address')))) ?></div>
											<div class="col-xs-1 text-center">&nbsp;</div>
										</div> 
										<div class="col-xs-12 mb20" style="color:#666666">
											<div class="col-xs-1 text-center">&nbsp;</div>
											<div class="col-xs-10 text-center"> <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
											<div class="col-xs-1 text-center">&nbsp;</div>
										</div>
									</div>
								</div>

							<?php }
							?>
						</div>
						<?= CHtml::hiddenField('type'); ?>
						<?php
						$this->endWidget();
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
	$(document).ready(function ()
	{
		$('#Booking_bkg_pickup_date_date').datepicker({'autoclose': true, 'startDate': new Date(), 'format': 'dd/mm/yyyy', 'language': 'en'});
		var rpList = [];
		rpList = <?= $jsReasonPHList ?>;
		hideBlocks();
		$("#Booking_bkg_cancel_id").change(function () {
			var reason = $("#Booking_bkg_cancel_id").val();
			if (reason != '') {
				$("#reasontext").show();
				$("#Booking_bkg_cancel_delete_reason").attr('placeholder', rpList[reason]);
				$("#Booking_bkg_cancel_delete_reason").attr('required', 'required');
			}
		});
	});

	function openReconfirmBox(type)
	{
		if (type == 1) {
			$('#goingReconfirmBlock').show();
			$('#notGoingReconfirmBlock').hide();
			$('#delayedReconfirmBlock').hide();
			$('#type').val('1');
		} else if (type == 2)
		{
			$('#goingReconfirmBlock').hide();
			$('#notGoingReconfirmBlock').show();
			$('#delayedReconfirmBlock').hide();
			$('#type').val('2');
		} else if (type == 3)
		{
			$('#goingReconfirmBlock').hide();
			$('#notGoingReconfirmBlock').hide();
			$('#delayedReconfirmBlock').show();
			$('#type').val('3');
		}
	}

	function hideBlocks()
	{
		$('#goingReconfirmBlock').hide();
		$('#notGoingReconfirmBlock').hide();
		$('#delayedReconfirmBlock').hide();
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

	$('#rating-form').submit(function (event) {
		var $error = 0;

		if ($('#Ratings_rtg_customer_review').val() == '') {
			//       $error += 1;
		} else {
			$error += 0;
		}
		if (!$('#Ratings_rtg_customer_recommend_0').hasClass('star-rating-on')) {
			$('#recommendErr').show();
			$error += 1;
		} else {
			$('#recommendErr').hide();
			$error += 0;
		}
		if (!$('#Ratings_rtg_customer_overall_0').hasClass('star-rating-on')) {
			$('#allErr').show();
			$error += 1;
		} else {
			$('#allErr').hide();
			$error += 0;
		}
		if (!$('#Ratings_rtg_customer_overall_3').hasClass('star-rating-on')) {
			if ($('#Ratings_rtg_customer_driver_0').hasClass('star-rating-on')) {
				$('#dvrErr').hide();
				$error += 0;
			} else {
				$('#dvrErr').show();
				$error += 1;
			}
			if ($('#Ratings_rtg_customer_csr_0').hasClass('star-rating-on')) {
				$('#csrErr').hide();
				$error += 0;
			} else {
				$('#csrErr').show();
				$error += 1;
			}
			if ($('#Ratings_rtg_customer_car_0').hasClass('star-rating-on')) {
				$('#carErr').hide();
				$error += 0;
			} else {
				$('#carErr').show();
				$error += 1;

			}
		} else {
			$('#dvrErr').hide();
			$('#carErr').hide();
			$('#csrErr').hide();
			$error += 0;
		}

		if ($error == 0) {
			$("#DivSubmitRate").hide();
		} else {
			$("#DivSubmitRate").show();
		}

		if ($error == 0) {

			$(function (event)
			{
				$.ajax({
					type: 'POST',
					"dataType": "json",
					"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rating/ajaxverify')) ?>",
					"data": $('#rating-form').serialize(),
					success: function (data)
					{
						if (data.result == 'true') {
							/*var returnUrl = <?= CJavaScript::encode(Yii::app()->createUrl('rating/bookingreview', ['uniqueid' => $uniqueid, 'status' => 'success'])) ?>;*/
							var returnUrl = <?= CJavaScript::encode(Yii::app()->createUrl('r/' . $uniqueid, ['status' => 'success'])) ?>;
							if (window.opener) {
								if (returnUrl) {
									window.opener.location.href = returnUrl;
								} else {
									window.opener.location.reload();
								}
								window.close();
							} else {
								window.location.href = returnUrl ? returnUrl : '/';
							}
						}

					}
				});
			});
		}
		event.preventDefault();
	});




	function checkrating(obj) {
		$rate = obj.val();
		if ($rate < 4) {
			$('#otherrating').show();
		} else {
			$('#otherrating').hide();
			$('#dvrErr').hide();
			$('#carErr').hide();
			$('#csrErr').hide();
		}
		if ($rate == '') {
			$('#allErr').show();
		} else {
			$('#allErr').hide();
		}
	}
	function checkRecRating(obj) {
		$rate = obj.val();
		if ($rate == '') {
			$('#recommendErr').show();
		} else {
			$('#recommendErr').hide();
		}
	}
	function checkcarrating(obj) {
		$rate = obj.val();
		if ($rate == '') {
			$('#carErr').show();
		} else {
			$('#carErr').hide();
		}
	}
	function checkcsrrating(obj) {
		$rate = obj.val();
		if ($rate == '') {
			$('#csrErr').show();
		} else {
			$('#csrErr').hide();
		}
	}

	function checkdvrrating(obj) {
		$rate = obj.val();
		if ($rate == '') {
			$('#dvrErr').show();
		} else {
			$('#dvrErr').hide();
		}
	}

	$('#Ratings_rtg_customer_review').keyup(function () {
		rev = $('#Ratings_rtg_customer_review').val();
		revlength = rev.length;
		if (revlength > 1000) {
			$('#overErr').show();
			$('#charleftcount').text('entered ' + (revlength - 1000) + ' characters  extra.');
		} else {
			$('#overErr').hide();
			$('#charleftcount').text((1000 - revlength) + ' characters  left.');
		}
	});

	$('#Ratings_rtg_customer_review').change(function () {
		rev = $('#Ratings_rtg_customer_review').val();

		if (rev.length > 1000) {
			$('#overErr').show();
		} else {
			$('#overErr').hide();
		}
	});


</script>