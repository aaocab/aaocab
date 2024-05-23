<?php
//$cartype = VehicleTypes::model()->getParentVehicleTypes1();
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

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
</style>
<div class="row">
    <div class="" style="float: none; margin: auto">
		<?php
		$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'reschedulenew-form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
                    if(!hasError){				

								
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
		?>


        <div class="col-12">
            <div class="row">
			    <div class="col-12 text-center mt0 mb10 text-danger errorMessage">
                   
                </div>
				<?= $form->hiddenField($model, 'bkg_id') ?>
                 <input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">  
                <div class="col-12 text-center font-15 mt0 mb5">
                    <b>Booking Id: <?= $model->bkg_booking_id ?></b>
                </div>
				<div class="col-12 text-center font-15 mt0 mb10">
				<b>Current Pickup Time : </b><?php echo date('d/m/Y', strtotime($model->bkg_pickup_date)) . ', ' . date('h:i A', strtotime($model->bkg_pickup_date)); ?>
                </div>
                <div class="col-12">
                    <div class="row">
						<div class="col-12 col-sm-12" style="border-width: 2px;border-color: #00cc00">
							
						</div> 
					</div>
                </div>

<!--new changes-->
				<div class="col-xs-12 text-center">
	          <div class="col-xs-6">
						<?php
						$minDate			 = ($brtRoute->brt_min_date != '') ? $brtRoute->brt_min_date : date('Y-m-d');
						$formattedMinDate	 = DateTimeFormat::DateToDatePicker($minDate);
						echo $this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'model'			 => $brtRoute,
							'attribute'		 => 'brt_pickup_date_date',
							'options'		 => array('autoclose' => true, 'dateFormat' => 'dd/mm/yy', 'minDate' => $formattedMinDate),
							'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date', 'value'			 => $brtRoute->brt_pickup_date_date,
								'id'			 => 'brt_pickup_date_date_' . date('mdhis'), 'min'			 => $brtRoute->brt_min_date, 'class'			 => 'form-control datePickup border-radius','style'=>'z-index: 99999')
								), true);
						?>
						<span class="has-error"><?php echo $form->error($brtRoute, 'brt_pickup_date_date'); ?></span>
              </div>
              <div class="col-xs-6">
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
			   </div>
<!--new changes-->
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
			<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary text-uppercase gradient-green-blue font-14 border-none mt15', 'onclick' => 'savetime();')); ?>
        </div>

		<?php $this->endWidget(); ?>
    </div>
</div>

<script type="text/javascript">
     var isExtraPayCliked = false;
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
			rescheduleBooking(0);
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
	
	
	function rescheduleBooking(isCommit)
	{  
		var form = $("form#reschedulenew-form");
		$('.errorMessage').html('');
		let newPickupDate = $("input[name='BookingRoute[brt_pickup_date_date]']").val();
		let newPickupTime = $("input[name='BookingRoute[brt_pickup_date_time]']").val();
		if(newPickupDate == undefined || newPickupTime == undefined || newPickupDate == "" || newPickupTime == "")
		{
			alert("Please enter pickup date and time");
		}
		var href = '<?= Yii::app()->createUrl("admin/booking/reschedule"); ?>';
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
				debugger;
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
					acctbox = bootbox.dialog({
					message: data1,
					title: 'Review Details',
					size: 'medium',
					onEscape: function ()
					{

					}
				});
				acctbox.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
				}
				else
				{
					if (data.success)
					{
						if(data.data.payUrl!='' && data.data.payUrl!=undefined)
						{	
							//$('#paymentlinkbtn').hide();
							//$('.paymentLinkReschedule').html("Reschedule initiated, after successfull payment reschedule will complete.");
							bootbox.hideAll();
							acctbox = bootbox.dialog({
										message: "<div class='text-success'>Reschedule initiated, after successfull payment reschedule will complete. New Booking ID: "+data.data.newBkgId+"</div>",
										title: '',
										size: 'medium',
										onEscape: function ()
										{

										}
							});
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

							   //reqCMB(2,data.data.bkgID);
							   
							}
							return;
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


