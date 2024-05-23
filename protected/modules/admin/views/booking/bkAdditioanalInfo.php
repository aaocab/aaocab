<?php
$infosource = BookingAddInfo::model()->getInfosource('admin');
?>
<?php
//	$vehicletype = VehicleTypes::model()->findByPk($model->bkgAddInfo->baddInfoBkg->bkg_vehicle_type_id);
//	$capacity    = $vehicletype->vht_capacity;
//	$bagCapacity = $vehicletype->vht_bag_capacity;
//	$bigBagCapacity = $vehicletype->vht_big_bag_capacity;
//+ $.param({"YII_CSRF_TOKEN": "'// .  //Yii::app()->request->csrfToken .'"})
?>
<?php
/* @var $form TbActiveForm */
$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'additionalInfoForm', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
					if(!admBooking.validateAdditionalInfo())
					{
						return false;
					}
                    $.ajax({
                    "type":"POST",
                    "dataType":"HTML",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/additionalInfo')) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
						$("#bkErrors").addClass("hide");
						$(".btn-additionalInfo").removeClass("btn-info");
						$(".btn-additionalInfo").addClass("disabled");
						$("#additionalInfo").find("input,select").attr("disabled",true);
						$("#vendorIns").html(data1);
                        $("#vendorIns").removeClass("hide");
						$(".btn-editAdditionalInfo").removeClass("hide");
						$(document).scrollTop($("#vendorIns").offset().top);
                    },
                     error: function(xhr, status, error){
                      
                         }
                    });

                    }
                }'
	),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'onkeydown'	 => "return event.key != 'Enter';",
		'class'		 => '',
	),
		));
?>
<?= CHtml::hiddenField("jsonData_additionalInfo", $data, ['id' => 'jsonData_additionalInfo']) ?>
<div class="row">

	<div class="col-xs-12">
		<div class="panel panel-default panel-border">
			<span class="edit-block btn-editAdditionalInfo hide"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
			<h3 class="pl15">CUSTOMER PREFERENCES AND ADDITIONAL INFORMATION</h3>
			<div class="panel-body pt0">
				<div class="row hide">

					<div class="col-sm-12">
						<?= $form->checkboxGroup($prfModel, 'bkg_invoice', array('widgetOptions' => array('htmlOptions' => []))) ?>
						<?= $form->checkboxGroup($prfModel, 'bkg_trip_otp_required', array('widgetOptions' => array('htmlOptions' => []))) ?>
						<?= $form->checkboxGroup($prfModel, 'bkg_driver_app_required', array('widgetOptions' => array('htmlOptions' => ['checked' => 'checked']))) ?>
						<?= $form->checkboxGroup($prfModel, 'bkg_duty_slip_required', array('widgetOptions' => array('htmlOptions' => []))) ?>
						<?= $form->checkboxGroup($prfModel, 'bkg_water_bottles_required', array('widgetOptions' => array('htmlOptions' => []))) ?>
						<?= $form->checkboxGroup($prfModel, 'bkg_is_cash_required', array('widgetOptions' => array('htmlOptions' => []))) ?>
						<? //= $form->checkboxGroup($prfModel, 'bkg_pref_other', array('label' => 'Other instructions', 'widgetOptions' => array('htmlOptions' => []))) ?>

						<span id="othprefreq" style="display:none;">
							<?= $form->textAreaGroup($prfModel, 'bkg_pref_req_other', array('label' => 'Other instructions', 'widgetOptions' => array('htmlOptions' => ['placeholder' => '']))) ?>
						</span>
						</br>		
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 special_request">
						<h3>Customer Preferences</h3>
						<p class="mb0">(NOTE: Enter all customer special requests here. there will be shown to customer and also sent to vendor)</p>
						<div class="row">
							<div class="col-xs-4">
								<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_senior_citizen_trvl', []) ?>
							</div>
							<div class="col-xs-4">
								<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_kids_trvl', []) ?>
							</div>
							<div class="col-xs-4">
								<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_woman_trvl', []) ?>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-4">
								<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_carrier', []) ?>
							</div>
							<div class="col-xs-4">
								<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_driver_hindi_speaking', []) ?>
							</div>
							<div class="col-xs-4">
								<?= $form->checkboxGroup($addInfoModel, 'bkg_spl_req_driver_english_speaking', []) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 special_request">

						<?= $form->checkboxGroup($model, 'bkg_chk_others', ['label' => 'Other Requests']) ?>
					</div>
					<div class="col-xs-6 special_request">
						<div id="othreq">
							<?= $form->textFieldGroup($addInfoModel, 'bkg_spl_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests", "readonly" => "readonly"]), 'groupOptions' => ['class' => 'm0'])) ?>  
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-6 special_request">
						<?= $form->checkboxGroup($model, 'bkg_add_my_trip', ['label' => 'I Will Take Journey Break']) ?>
					</div>
					<div class="col-xs-6 special_request">
						<?= $form->dropDownListGroup($addInfoModel, 'bkg_spl_req_lunch_break_time', ['label' => '', 'widgetOptions' => ['data' => ['0' => '15 minutes (Included Free)', '30' => '30', '60' => '60', '90' => '90', '120' => '120', '150' => '150', '180' => '180'], 'htmlOptions' => []]]) ?>
					</div>
				</div>
				<h3>Additional Information</h3>
				<div class="row">
					<div class="col-xs-12"> 
						<div class="form-group"> 
							<label >Add Tags</label>
							<?php
							$SubgroupArray2	 = Tags::getListByType(Tags::TYPE_BOOKING);
							$this->widget('booster.widgets.TbSelect2', array(
								//'name'			 => 'bkg_tags',
								'attribute'		 => 'bkg_tags',
								'model'			 => $trailModel,
								'data'			 => $SubgroupArray2,
								// 'value' => explode(',', $model->bkg_tags),
								'htmlOptions'	 => array(
									'multiple'		 => 'multiple',
									'placeholder'	 => 'Add keywords that you may use to search for this booking later',
									'width'			 => '100%',
									'style'			 => 'width:100%',
								),
							));
							?>
						</div>
					</div>
					<div class="col-sm-6 mt15">
						<div class="form-group">
							<label class="control-label" style="text-align: left;" for="exampleInputName6"><nobr>How did you hear about Gozo cabs?</nobr></label>
							<?php
							$datainfo		 = VehicleTypes::model()->getJSON($infosource);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $addInfoModel,
								'attribute'		 => 'bkg_info_source',
								'val'			 => "'" . $addInfoModel->bkg_info_source . "'",
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($datainfo)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Infosource')
							));
							?>
						</div>
					</div>
					<div class="col-sm-6 mt15">
						<div class="form-group">
							<?= $form->textFieldGroup($addInfoModel, 'bkg_flight_no', array('label' => 'Flight Number', 'widgetOptions' => array('htmlOptions' => array()))) ?>
						</div>
					</div>
					<? $sourceDescShow	 = ($addInfoModel->bkg_info_source == 5 || $addInfoModel->bkg_info_source == 6) ? '' : 'hide'; ?>
					<div class="col-sm-6 <?= $sourceDescShow ?>" id="source_desc_show">
						<div class="form-group">
							<label class="control-label" for="type">&nbsp;</label>
							<?= $form->textFieldGroup($addInfoModel, 'bkg_info_source_desc', array('label' => "", 'widgetOptions' => array('htmlOptions' => array('placeholder' => '')))) ?>										
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<label class="control-label" for="exampleInputName6"></label>
						<?= $form->fileFieldGroup($model, 'fileImage', array('label' => 'Customer Other Information', 'widgetOptions' => array('htmlOptions' => []))) ?>
					</div>
					<div class="col-sm-6 hide">
						<label class="control-label" for="exampleInputName6"></label>
						<?= $form->checkboxGroup($prfModel, 'bkg_tentative_booking', array('widgetOptions' => array('htmlOptions' => []))) ?>
					</div>
				</div>

				<div class="row mt15">
					<label for="inputEmail" class="control-label col-xs-5">Customer Type</label>
					<div class="col-xs-7 pl0">
						<?=
						$form->radioButtonListGroup($addInfoModel, 'bkg_user_trip_type', array(
							'label'			 => '', 'widgetOptions'	 => array(
								'data' => Booking::model()->userTripList
							), 'inline'		 => true,)
						);
						?>
					</div>
				</div>
				<div class="row">
					<label for="inputEmail" class="control-label col-xs-5">Send me booking confirmations by</label>
					<div class="col-xs-7 pl0">
						<label class="checkbox-inline pt0">
							<?= $form->checkboxGroup($prfModel, 'bkg_send_email', ['label' => 'Email']) ?>
						</label>
						<label class="checkbox-inline pt0">
							<?= $form->checkboxGroup($prfModel, 'bkg_send_sms', ['label' => 'Phone']) ?>
						</label>
					</div>
				</div>

				<div class="row mb5">
					<label for="inputEmail" class="control-label col-xs-5">Number of Passengers</label>
					<div class="col-xs-7">
						<?= $form->numberFieldGroup($addInfoModel, 'bkg_no_person', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of Passengers"]), 'groupOptions' => ['class' => 'm0'])) ?>                      
					</div>
				</div>
				<div class="row mb5">
					<label for="inputEmail" class="control-label col-xs-5">Number of large suitcases</label>
					<div class="col-xs-7">
						<? //= $form->numberFieldGroup($addInfoModel, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of large suitcases"]), 'groupOptions' => ['class' => 'm0']))   ?>                      

						<?php
						$vehicleInfo	 = json_decode($data);
						$vct_Id			 = $vehicleInfo->bkg_vehicle_type_id;
						$scc_Id			 = $vehicleInfo->bkg_service_class;
						$sbagRecord		 = VehicleCatSvcClass::smallbagBycategoryClass($vct_Id, $scc_Id);
						$lbag			 = floor($sbagRecord['vcsc_small_bag'] / 2);
						?>
						<select class="form-control" id="BookingAddInfo_bkg_num_large_bag" name="BookingAddInfo[bkg_num_large_bag]" onchange="luggage_info(this.value,<?php echo $vct_Id ?>,<?php echo $scc_Id ?>,<?php echo $sbagRecord['vcsc_small_bag'] ?>);">
							<?php
							for ($i = 0; $i <= $lbag; $i++)
							{
								?>
								<option value="<?php echo $i ?>"><?php echo $i ?></option>
							<?php } ?>		
						</select>
					</div>
				</div>
				<div class="row mb5">
					<label for="inputEmail" class="control-label col-xs-5">Number of small bags</label>
					<div class="col-xs-7">
						<? //= $form->numberFieldGroup($addInfoModel, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Number of small bags"]), 'groupOptions' => ['class' => 'm0']))      ?>                      
						<select class="form-control" id="BookingAddInfo_bkg_num_small_bag" name="BookingAddInfo[bkg_num_small_bag]">
							<?php
							for ($i = 1; $i <= $sbagRecord['vcsc_small_bag']; $i++)
							{
								?>
								<option value="<?php echo $i ?>"><?php echo $i ?></option>
							<?php } ?>		
						</select>							

					</div>
				</div>

				<div class="row" id="<?= TbHtml::activeId($model, "bkgTrail") ?>" style="display: <? echo ($model->bkg_agent_id != '') ? 'none' : 'block' ?>">
					<div class="col-xs-12">Enter here the date and time by which customer will make payment: Remind customer that prices are rising and they need to make payment.</div>
					<div class="col-sm-6 mt15">

						<?=
						$form->datePickerGroup($trailModel, 'locale_followup_date', array('label'			 => 'Followup Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date('d/m/Y', strtotime($followUpDateTime)), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Followup Date', 'value' => DateTimeFormat::DateTimeToDatePicker($followUpDateTime))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>
					</div>
					<div class="col-sm-6 mt15">
						<div class="form-group">
							<label class="control-label" for="BookingTrail_locale_followup_time">Followup Time</label>
							<div class="bootstrap-timepicker input-group">
								<?
								$this->widget('ext.timepicker.TimePicker', array(
									'model'			 => $trailModel,
									'id'			 => CHtml::activeId($trailModel, "locale_followup_time"),
									'attribute'		 => 'locale_followup_time',
									'options'		 => ['widgetOptions' => array('options' => array())],
									'htmlOptions'	 => array('required' => true, "value" => date('h:i A', strtotime($followUpDateTime)), 'placeholder' => 'Reminder Time', 'class' => 'no-user-select input-group border-gray full-width form-control ct-form-control')
								));
								?> 
								<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
							</div>
							<div class="help-block error" id="BookingTrail_locale_followup_time_em_" style="display:none"></div>
						</div>
					</div>

					<!---price exp date-->
					<?php
					$trailModel->bkg_quote_expire_date		 = $quoteExpiry;
					$trailModel->bkg_quote_expire_max_date	 = $quoteExpiry;
					$currentDate							 = date(format, [timestamp]);
					$defaultDate							 = date('Y-m-d H:i:s', strtotime('+0 days'));
					$minDate								 = date('Y-m-d H:i:s', strtotime('+1 min'));
					$endDatemax								 = $trailModel->bkg_quote_expire_max_date;
					$endDate								 = DateTimeFormat::DateTimeToDatePicker($endDatemax);
					$endTime								 = DateTimeFormat::DateTimeToTimePicker($endDatemax);
					//$btrBkgId	 = $model->btr_bkg_id;
					$strrtedate								 = ($trailModel->bkg_quote_expire_date == '') ? date('Y-m-d H:i:s', strtotime('+15 min')) : $trailModel->bkg_quote_expire_date;
					$pdate									 = ($trailModel->bkg_quote_expire_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $trailModel->bkg_quote_expire_date;
					?>
					<div class="col-sm-6 mt15">
						<div class="form-group">
							<label for="type" class="control-label"><span style="float:left;font-weight: normal; font-size: 15px;">Price lock expires at: <b><span id="max_date"><?= $trailModel->bkg_quote_expire_max_date ?></span></b>.You may update the price lock time below if you like.</span></label>

							<!--						<label class="control-label"><b>Price lock expire at</b></label>
															
													<b><h3><? //= date('M d Y H:i A' , strtotime($quoteExpiry));       ?></h3></b>-->
							<div class="col-sm-6 mt15">

								<?=
								$form->datePickerGroup($trailModel, 'bkg_quote_expire_date', array('label'			 => 'Price lock expiry date',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => $minDate, 'endDate' => $endDate, 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => "expire date", 'value' => $endDate, 'id' => 'booking_quote_expiry_1', 'class' => 'input-group border-gray full-width')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
							</div>
							<div class="col-sm-6 mt15">
								<div class="form-group">
									<label class="control-label" for="BookingTrail_bkg_quote_expire_time">Price lock expiry time</label>
									<div class="bootstrap-timepicker input-group">
										<?
										$this->widget('ext.timepicker.TimePicker', array(
											'model'			 => $trailModel,
											'id'			 => 'bkg_quote_expire_time',
											'attribute'		 => 'bkg_quote_expire_time_1',
											'options'		 => ['widgetOptions' => array('options' => array())],
											'htmlOptions'	 => array('required' => true, 'placeholder' => 'price lock time', 'value' => date('h:i A', strtotime($strrtedate)), 'class' => 'form-control border-radius')
										));
										?>
										<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
									</div>
									<div class="help-block error" id="BookingTrail_bkg_quote_expire_time_em_" style="display:none"></div>
								</div>
							</div>	
						</div>
					</div>

					<!---price exp date-->

					<div class="col-xs-12 has-error">
						<?= $form->error($model, "bkgTrail") ?>
						<?= $form->error($model, "bkgAddInfo") ?>
					</div>

				</div>
                <div class="row">
					<input type="hidden" id="agentnotifydata" name="agentnotifydata" value='<?= json_encode($model->agentNotifyData); ?>'>
					<div class="col-xs-12 pb10 mt15" style="text-align:center;">
						<button type='button' class='btn btn-info btn-additionalInfo pl20 pr20'>Next</button>
					</div>
				</div>
				<input type="hidden" id="hourdiff" name="hourdiff" value="<?= $hourdiff ?>" >
<!--                                <input type="hidden" name="YII_CSRF_TOKEN"  >  -->
			</div>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
<script>
	var jsonData = JSON.parse($('#jsonData_additionalInfo').val());
	$("#BookingAddInfo_bkg_info_source").change(function ()
	{
		var infosource = $("#BookingAddInfo_bkg_info_source").val();
		admBooking.extraAdditionalInfo(infosource);
	});

	$('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function ()
	{
		if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
		{
			$("#othreq").find('input').attr('readonly', false);
		} else
		{
			$("#othreq").find('input').attr('readonly', true);
		}
	});

	$(".btn-additionalInfo").click(function () {

		var d = document.getElementById("booking_quote_expiry_1").value;
		var t = document.getElementById("bkg_quote_expire_time").value;
		var hourdiff = document.getElementById("hourdiff").value;
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
		var dateTime1 = new Date(datetime).getTime();

		if ((formatted_date1 > maxTime1 || formatted_date1 < dateTime1) && hourdiff > 4)
		{
			alert("You cannot extend the price lock time. You may only make it earlier.");
			return false;
		}
		function pad2(number)
		{
			return (number < 10 ? '0' : '') + number;
		}

		$('#bkg_quote_expire_time').timepicker({
			'step': '30'
		});

		if ($('#BookingAddInfo_bkg_spl_req_lunch_break_time').val() > 0 || $('#BookingAddInfo_bkg_spl_req_carrier').is(':checked') == true)
		{
			admBooking.calculateAmount(jsonData);
			var disabled = $('#paymentForm').find(':input:disabled');
			disabled.removeAttr('disabled');

			//   ,'YII_CSRF_TOKEN': "<? // Yii::app()->request->csrfToken  ?>"

			var serialized = $('#paymentForm').serialize() + '&' + $.param({'addInfoData': $('#jsonData_additionalInfo').val()});
			disabled.attr('disabled', 'disabled');
			jQuery.ajax({
				type: 'POST',
				url: '<?= Yii::app()->createUrl('admin/booking/payment', ['rec' => '1']) ?>',
				dataType: 'json',
				async: false,
				data: serialized,
				success: function (data)
				{
					$('#jsonData_additionalInfo').val(JSON.stringify(data));
				},
				error: function (x)
				{
					//   debugger;
					//  alert("x");
					//   alert(x);
					//      alert("x11");
					//      alert(JSON.stringify(x));
				}
			});
		}
		$("#additionalInfoForm").submit();
	});

	$(".btn-editAdditionalInfo").click(function () {
		$('#vendorIns').html('');
		$('#vendorIns').addClass('hide');
		$(".btn-additionalInfo").addClass("btn-info");
		$(".btn-additionalInfo").removeClass("disabled");
		$("#additionalInfo").find("input").attr("disabled", false);
		$(".btn-editAdditionalInfo").addClass("hide");
	});
	$("#BookingAddInfo_bkg_spl_req_lunch_break_time").change(function ()
	{
		var lunchTime = $(this).val();

		if (lunchTime != 0)
		{

			$("#Booking_bkg_add_my_trip").prop("checked", true);
		} else
		{
			$("#Booking_bkg_add_my_trip").prop("checked", false);
		}

	});


	function luggage_info(largebag, vcatid, sccid, smallbag)
	{
		var largebag = largebag;
		var vcatid = vcatid;
		var sccid = sccid;
		var smallbag = smallbag;
		var sbag = Math.floor(smallbag - (largebag * 2));
		$("#BookingAddInfo_bkg_num_small_bag").empty();
		for (var i = 0; i <= sbag; i++)
		{
			var id = i;
			var name = i;
			$("#BookingAddInfo_bkg_num_small_bag").append("<option value='" + id + "'>" + name + "</option>");
		}
	}

</script>
