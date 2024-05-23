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
	.ui-timepicker-container
	{
        z-index: 10000 !important;
    }
.modal-dialog {
    width: 100%;
    margin: 0 auto;
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
//								if(!checkValidation())
//								{
//								   return false;
//								}
								
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

		echo $form->errorSummary($model, NULL, NULL, ['class' => 'mt10 errorSummary alert alert-danger mb-2']);

		if($model->bkgInvoice->bkg_advance_amount > 0)
		{
			$cancelObj							 = CancellationPolicy::initiateRequest($model);
			$rescheduleCharge = $model->bkgInvoice->calculateRescheduleCharge($cancelObj->charges,$model->bkg_pickup_date);
		}
		?>


        <div class="col-12">
            <div class="row">
			    <div class="col-12 text-center mt0 text-danger errorMessage">
                   
                </div>
				<?= $form->hiddenField($model, 'bkg_id') ?>
                 <input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">  
                <div class="col-12 text-center font-14 mt0 mb10">
                    Booking Id<br> <b><?= $model->bkg_booking_id ?></b>
                </div>
				<div class="col-12 text-center font-14 mt0 mb10">
					Current Pickup Time<br> <b><?php DateTimeFormat::parseDateTime($model->bkg_pickup_date, $date, $time);
													 echo $date. ', ' .$time; ?></b>
                </div>
                <div class="col-12">
                    <div class="row">
						<div class="col-12 col-sm-12" style="border-width: 2px;border-color: #00cc00">
							
						</div> 
					</div>
                </div>

	          <div class="col-6 mt10 mb0 pr5">
						<?php
						$minDate			 = ($brtRoute->brt_min_date != '') ? $brtRoute->brt_min_date : date('Y-m-d');
						$formattedMinDate	 = DateTimeFormat::DateToDatePicker($minDate);
						echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'			 => $brtRoute,
							'attribute'		 => 'brt_pickup_date_date',
							'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
							'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date (dd/mm/yyyy)', 'value'			 => $brtRoute->brt_pickup_date_date,
								'id'			 => 'brt_pickup_date_date_' . date('mdhis'), 'min'			 => $brtRoute->brt_min_date, 'class'			 => 'form-control datePickup border-radius','style'=>'z-index: 99999')

								), true);
						?>
						<span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_date'); ?></span>
              </div>
              <div class="col-6 mt10 mb0 pl5">
					<?php
					  $this->widget('ext.timepicker.TimePicker', array(
						  'model'			 => $brtRoute,
						  'id'			 => 'brt_pickup_date_time_' . date('mdhis'),
						  'attribute'		 => 'brt_pickup_date_time',
						  'options'		 => ['widgetOptions' => array('options' => array())],
						  'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius timePickup text text-info col-xs-12')
					  ));
					  ?>
					  <span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_time'); ?></span>
               </div>

				<div class="col-12">
                    <div class="row">
						<div class="col-12 col-sm-12" style="display:none" id="resBkg">
							<b>Reschedule Booking:</b> <span id="rescheduleBkg"></span>
						</div> 
					</div>
                </div>

            </div>
        </div>

        <div class="panel-footer text-center mb20">
			<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary text-uppercase gradient-green-blue font-14 border-none mt15', 'onclick' => 'savetime();')); ?>
        </div>
		<div class="col-12 font-12 pb-1"><?php if($rescheduleCharge>0){ echo "*reschedule/cancellation charge of ".Filter::moneyFormatter($rescheduleCharge)." will be applied in this booking and rest of the advance will be transferred to the new booking.";}?></div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<script type="text/javascript">
     var isExtraPayCliked = false;

	function savetime()
	{ 
		$('.errorMessage').html('');
		let newPickupDate = $("input[name='BookingRoute[brt_pickup_date_date]']").val();
		let newPickupTime = $("input[name='BookingRoute[brt_pickup_date_time]']").val();
		if(newPickupDate == undefined || newPickupDate == "") 
		{
			$('.errorMessage').html("Pickup date is mandatory");
			return false;
		}
		if(newPickupTime == undefined || newPickupTime == "")
		{
			$('.errorMessage').html("Pickup time is mandatory");
			return false;
		}
		//saveReschedulePickupTime(); // old reschedule method 
		rescheduleBooking(0);
		
	}

	function rescheduleBooking(isCommit)
	{  
		$('.errorMessage').html('');
		let newPickupDate = $("input[name='BookingRoute[brt_pickup_date_date]']").val();
		let newPickupTime = $("input[name='BookingRoute[brt_pickup_date_time]']").val();
		var form = $("form#pickuptime-form");
		var href = '<?= Yii::app()->createUrl("booking/reschedulebooking"); ?>';
		$.ajax({
			"url": href,
			"type": "POST",
			"dataType": "html",
			"data": {"bkg_id": $('#Booking_bkg_id').val(), "newPickupDate": newPickupDate, "newPickupTime": newPickupTime,'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val(),'isCommit':isCommit},
			"beforeSend": function ()
			  {
				  ajaxindicatorstart("");
			  },
			  "complete": function ()
			  {
				  ajaxindicatorstop();
			  },
			"success": function (data1)
			{ 
				var isJSON = false;
				try
				{
					data = JSON.parse(data1);
					isJSON = true;
				} catch (e)
				{

				}
				if (!isJSON)
				{
						$('#rescheduleBooking').removeClass('fade');
						$('#rescheduleBooking').css('display', 'block');
						$('#rescheduleBookingDeatils').html(data1);
						$('#rescheduleBookingDeatils').removeClass("hide");
						$('#rescheduleBookingContent').addClass("hide");
						$('#rescheduleBookingLabel').html("Review Details");
						$('#rescheduleBooking').modal('show');
				}
				else
				{
					if (data.success)
					{
						if(data.data.payUrl!='' && data.data.payUrl!=undefined)
						{
							window.location.href = data.data.payUrl;
						}
						else
						{
							alert("Booking rescheduled successfully. New Booking ID is "+data.data.newBkgId);
							window.location.reload();	
						}
					} 
					else
					{
						if(data.data!=undefined)
						{
							if(data.data.errMessage!='')
							{	
								$('.errorMessage').html(data.data.errMessage);
							}
							if(data.data.isGozoNow == 1)
							{
							  $('#rescheduleBookingContent').addClass("hide"); 
							  $('#rescheduleBookingLabel').html("&nbsp;");
							  $('#rescheduleBookingDeatils').html('<div class="font-16">'+data.data.errMessage+'</div>\n\
                                                                   <div class="col-12 text-center mt10" style="color: #ffffff;margin-bottom: 15px;">\n\
                                                                   <button class="btn btn-primary btn-sm" style="margin-right: 5px;" onclick="reqCMB(2,data.data.bkgID,data.data.msg)">Yes</button>\n\
                                                                   <button class="btn btn-primary btn-sm" onclick="showRescheduleForm();">No</button></div>');
							  $('#rescheduleBookingDeatils').removeClass("hide");
							  $('.errorMessage').html('');
							  return false;
							}
						}
						else
						{
							//$('.errorMessage').html("Unable to process your request.Please try after some time");
						}
						if(data.errors!='')
						{
							var errors = data.errors;
							msg = JSON.stringify(errors);
							settings = form.data('settings');
							$.each(settings.attributes, function (i)
							{
								$.fn.yiiactiveform.updateInput(settings.attributes[i], errors, form);
							});
							$.fn.yiiactiveform.updateSummary(form, errors);
							messages = errors;
							content = '';
							var summaryAttributes = [];
							for (var i in settings.attributes)
							{
								if (settings.attributes[i].summary)
								{
									summaryAttributes.push(settings.attributes[i].id);
								}
							}
							displayFormError(form, messages);																									
						}
						
					}
				}
			}
		});
		return false;
	}
	
	function showRescheduleForm()
	{
				$('#rescheduleBooking').removeClass('fade');
				$('#rescheduleBooking').css('display', 'block');
				if(!$('#rescheduleBookingDeatils').hasClass('hide'))
				{
				  $('#rescheduleBookingDeatils').addClass("hide");
				}
				$('#rescheduleBookingContent').removeClass("hide");
				$('#rescheduleBookingLabel').html("Reschedule Booking");
	}
	
	function displayFormError(form, messages)
	{ 
		settings = form.data('settings');
		content = "";
		let msgs = [];
		for (var key in messages)
		{
			if ($.type(messages[key]) === 'string')
			{
				content = content + '<li>' + messages[key] + '</li>';
				continue;
			}
			$.each(messages[key], function(j, message)
			{
				if ($.type(message) === 'array')
				{
					$.each(messages[key], function(k, v)
					{
						if ($.type(v) == "array")
						{
							$.each(v, function(k1, v1)
							{
								if ($.type(v1) == "array")
								{
									$.each(v1, function(j, message)
									{
										if (msgs.indexOf(message) > -1)
										{
											return;
										}
										msgs.push(message);
										content = content + '<li>' + message + '</li>';
									});
								}
								else
								{
									if (msgs.indexOf(v1) > -1)
									{
										return;
									}
									msgs.push(v1);
									content = content + '<li>' + v1 + '</li>';
								}
							});
						}
						else
						{
							$.each(v, function(j, message)
							{
								if (msgs.indexOf(message) > -1)
								{
									return;
								}
								msgs.push(message);
								content = content + '<li>' + message + '</li>';
							});
						}
					});
				}
				else
				{
					if (msgs.indexOf(message) > -1)
					{
						return;
					}
					msgs.push(message);
					content = content + '<li>' + message + '</li>';
				}
			});
		}
		$('#' + settings.summaryID).toggle(content !== '').find('ul').html(content);
		return (content == "");
	}

	
</script>


