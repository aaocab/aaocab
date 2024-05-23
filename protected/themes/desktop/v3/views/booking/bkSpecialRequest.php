<?php
$fcity					 = Cities::getName($model->bkg_from_city_id);
$tcity					 = Cities::getName($model->bkg_to_city_id);
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$action					 = Yii::app()->request->getParam('action');
$hash					 = Yii::app()->shortHash->hash($model->bkg_id);
$otherExist				 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 'block' : 'none';
$model->bkg_chk_others	 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 1 : 0;
$scvVctId				 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
$form					 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingsplrequest', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => ''
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
/* @var $form CActiveForm */
$currentPage			 = Yii::app()->controller->getAction();
$show					 = "";
$hide					 = "";
if ($currentPage->id == "bkgconfirmation")
{
	$show	 = "pointer-events: none;";
	$hide	 = "hide";
}
$disable			 = false;
$dropdowndisabled	 = "";
if (!in_array($model->bkg_status, [15, 2]))
{
	$disable			 = true;
	$dropdowndisabled	 = "disabled";
}
?>


<div class="row" style="<?php echo $show; ?>">
	<?php
	echo $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']);
	echo $form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
	?>
<div class="col-12">
	<p>Please provide additional information to help us to serve you better.</p>
	<ul class="list-unstyled mb-0">
		<li class="d-inline-block mr-2 mb-1">
			<fieldset>
				<div class="checkbox">
					<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl', ['onclick' => 'splRequest()', 'disabled' => $disable]); ?>
					<label for="BookingAddInfo_bkg_spl_req_senior_citizen_trvl">Senior citizen traveling</label>
				</div>
			</fieldset>
		</li>
		<li class="mr-2 mb-1">
			<fieldset>
				<div class="checkbox">
					<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_woman_trvl', ['onclick' => 'splRequest()', 'disabled' => $disable]); ?>
					<label for="BookingAddInfo_bkg_spl_req_woman_trvl">Women traveling</label>
				</div>
			</fieldset>
		</li>
		<li class="d-inline-block mr-2 mb-1">
			<fieldset>
				<div class="checkbox">
					<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_kids_trvl', ['onclick' => 'splRequest()', 'disabled' => $disable]); ?>
					<label for="BookingAddInfo_bkg_spl_req_kids_trvl">Kids on board</label>
				</div>
			</fieldset>
		</li>
		<li class="d-inline-block mr-2 mb-1">
			<fieldset>
				<div class="checkbox">
					<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking', ['onclick' => 'splRequest()', 'disabled' => $disable]); ?>
					<label for="BookingAddInfo_bkg_spl_req_driver_english_speaking">English-speaking driver preferred</label>
				</div>
			</fieldset>
		</li>
		<li class="mr-2 mb-1">
			<fieldset>
				<div class="checkbox">
					<?php echo $form->checkBox($model, 'bkg_chk_others', ['onclick' => 'splRequest()', 'disabled' => $disable]); ?>
					<label for="Booking_bkg_chk_others">Other</label>
				</div>
			</fieldset>
		</li>
<li class="mr-2 mb-1">
			<div class="">
				<div class="" id="othreq" style="display: <?php echo $otherExist ?>">
					<?php echo $form->textArea($model->bkgAddInfo, 'bkg_spl_req_other', ['placeholder' => "Other Requests", 'class' => 'form-control']) ?>
				</div>
			</div>
		</li>
		<?php
		//if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
		//{
			?>
<!--			<li class="d-inline-block mr-2 mb-1">
				<fieldset>
					<div class="checkbox">
						<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_carrier', ['onclick' => 'splRequest()', 'disabled' => $disable]); ?>
						<label for="BookingAddInfo_bkg_spl_req_carrier">Require vehicle with carrier (₹150)</label>
					</div>
				</fieldset>
			</li>-->
			<?
		//}
		?>
	</ul>
</div>
<!--		<div class="col-12 mb-1">
			<fieldset>
				<label for="Booking_bkg_add_my_trip">Select your journey break</label>
				<?php echo $form->dropDownList($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['' => 'No journey break required', '0' => '15 Minutes | Free', '30' => '30 Minutes | ₹150', '60' => '60 Minutes | ₹300', '90' => '90 Minutes | ₹450', '120' => '120 Minutes | ₹600', '150' => '150 Minutes | ₹750', '180' => '180 Minutes | ₹900'], ['class' => 'form-control', 'placeholder' => 'Journey Break', 'disabled' => $dropdowndisabled]) ?>
				<?php echo $form->error($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['class' => 'help-block error']); ?>
			</fieldset>

		</div>-->
		

	
</div>
<div class="clear"></div>

<div class="row">
	<div class="col-12 text-center heading-part mb10">
		<span id="msg" class="hide text-success font-20" style="font-weight: bold;color: #FF6700;font-size: 12px">SPECIAL REQUESTS SAVED.</span>
	</div>
</div>
<div class="clear"></div>
<?php
$capacity		 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_capacity;
$bagCapacity	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_small_bag_capacity;
$bigBagCapacity	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_big_bag_capacity;
$this->endWidget();
?>
<script>
	$jsHandleUI = new HandleUI();
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
	$(document).ready(function ()
	{
		$("#BookingAddInfo_bkg_info_source").change(function ()
		{
			var infosource = $("#BookingAddInfo_bkg_info_source").val();
			extraAdditionalInfo(infosource);
		});
		var additionalCharge = '<?= $model->bkgInvoice->bkg_additional_charge ?>';
		if ($('#Booking_bkg_add_my_trip').is(':checked'))
		{
			$("#addmytrip").show();
		}
		if (additionalCharge != 0)
		{
			$('.additionalcharge').removeClass('hide');
		}

		if (additionalCharge == 0)
		{
			$("#BookingAddInfo_bkg_spl_req_lunch_break_time").prop("selectedIndex", '');
		}

	});

	var seniorCitizen = false;
	$(window).ready(function ()
	{

	});

	function extraAdditionalInfo(infosource)
	{
		$("#source_desc_show").addClass('hide');
		if (infosource == '5')
		{
			$("#source_desc_show").removeClass('hide');
			$("#agent_show").addClass('hide');
			$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
		} else if (infosource == '6')
		{
			$("#source_desc_show").removeClass('hide');
			$("#agent_show").addClass('hide');
			$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
		}
	}
	$('#Booking_bkg_chk_others').change(function ()
	{
		if ($('#Booking_bkg_chk_others').is(':checked'))
		{
			$("#othreq").show();
		} else
		{
			$("#othreq").hide();
		}
	});
	$('#Booking_bkg_add_my_trip').change(function ()
	{
		if ($('#Booking_bkg_add_my_trip').is(':checked'))
		{
			$('#BookingAddInfo_bkg_spl_req_lunch_break_time').attr('disabled', false);
		} else
		{
			$('#BookingAddInfo_bkg_spl_req_lunch_break_time').attr('disabled', true);
		}
	});

	$('select[name="BookingAddInfo[bkg_spl_req_lunch_break_time]"]').change(function (event)
	{
		evalCharges();
	});

	$('#BookingAddInfo_bkg_spl_req_senior_citizen_trvl,#BookingAddInfo_bkg_spl_req_kids_trvl,#BookingAddInfo_bkg_spl_req_woman_trvl,#BookingAddInfo_bkg_spl_req_driver_english_speaking,#Booking_bkg_chk_others,#Booking_bkg_add_my_trip,#BookingAddInfo_bkg_user_trip_type_0,#BookingAddInfo_bkg_user_trip_type_1,#BookingAddInfo_bkg_info_source').click(function ()
	{
		$('#additiondetails').removeClass("hide");
	});

	$('#BookingAddInfo_bkg_no_person,#BookingAddInfo_bkg_num_large_bag,#BookingAddInfo_bkg_num_small_bag').focus(function ()
	{
		$('#additiondetails').removeClass("hide");
	});

	function splRequest()
	{
		var addrequest = "";
		var seniorCitizen = $('#BookingAddInfo_bkg_spl_req_senior_citizen_trvl').is(":checked");
		var kidsTravel = $('#BookingAddInfo_bkg_spl_req_kids_trvl').is(":checked");
		var womanTravel = $('#BookingAddInfo_bkg_spl_req_woman_trvl').is(":checked");
		var carrierReq = $('#BookingAddInfo_bkg_spl_req_carrier').is(":checked");
		var engSepeakingDriver = $('#BookingAddInfo_bkg_spl_req_driver_english_speaking').is(":checked");

		(seniorCitizen > 0) ? $(".srcitizentrvl").show() : $(".srcitizentrvl").hide();
		(kidsTravel > 0) ? $(".kidstrvl").show() : $(".kidstrvl").hide();
		(womanTravel > 0) ? $(".womantrvl").show() : $(".womantrvl").hide();
		(womanTravel > 0) ? $(".womantrvl").show() : $(".womantrvl").hide();
		(engSepeakingDriver > 0) ? $(".drvenglishspeaking").show() : $(".drvenglishspeaking").hide();
	
		if (carrierReq == true)
		{
			$(".carrierReq").removeClass('hide');
		} else
		{
			$(".carrierReq").addClass('hide');
		}

		if (seniorCitizen == true || kidsTravel == true || womanTravel == true || engSepeakingDriver == true || carrierReq == true)
		{
			$(".splRequest").text("Some special services requested");
		} else
		{
			$(".splRequest").text("No special services requested");
		}
	}

	$("#BookingAddInfo_bkg_spl_req_lunch_break_time").change(function ()
	{
		var lunchBrk = $('option:selected', $(this)).val();
		var amt = 0;
		if (lunchBrk == 30)
		{
			amt = 150;
		}
		if (lunchBrk == 60)
		{
			amt = 300;
		}
		if (lunchBrk == 90)
		{
			amt = 450;
		}
		if (lunchBrk == 120)
		{
			amt = 600;
		}
		if (lunchBrk == 150)
		{
			amt = 750;
		}
		if (lunchBrk == 180)
		{
			amt = 900;
		}
		calculateSplRequest(amt);
	});

	$("#BookingAddInfo_bkg_spl_req_carrier").change(function ()
	{
		var amt = 0;
		if ($('#BookingAddInfo_bkg_spl_req_carrier').is(':checked'))
		{
			amt = 150;
		}
		evalCharges();
	});

	function calculateSplRequest(additionalAmt)
	{
		var totAmount = '<?= $model->bkgInvoice->bkg_total_amount ?>';
		var extracharge = $('.extracharge').text();
		var discountAmt = $('.txtDiscountAmount').text();
		var disamt = parseInt(discountAmt.replace(/([-,.₹;'<>])+/g, ''));
		var txtDisBaseAmt = $('.txtDiscountedBaseAmount').text();
		var baseFare = '<?= $model->bkgInvoice->bkg_base_amount ?>';
		var disamount = parseInt(txtDisBaseAmt.replace(/([-,.₹;'<>])+/g, ''));
		var tolTax = '<?= $model->bkgInvoice->bkg_toll_tax ?>';
		var extraStateTax = '<?= $model->bkgInvoice->bkg_extra_state_tax ?>';
		var extraStateTax = (extraStateTax != '') ? extraStateTax : 0;
		var otherTax = '<?= $model->bkgInvoice->bkg_state_tax ?>';
		var drvallownce = '<?= $model->bkgInvoice->bkg_driver_allowance_amount ?>';
		var parkingCharge = '<?= $model->bkgInvoice->bkg_parking_charge ?>';
		//var extraCharge	    = '<? //= $model->bkgInvoice->bkg_extra_charge  ?>';
		var airportEntryCharge = '<?= $model->bkgInvoice->bkg_airport_entry_fee ?>';
		var convienceCharge = '<?= $model->bkgInvoice->bkg_convenience_charge ?>';
		var carrier = $('#BookingAddInfo_bkg_spl_req_carrier').is(':checked');
		var sevicetaxRate = '<?= $model->bkgInvoice->bkg_service_tax_rate ?>';
		var serviceTax = '<?= $model->bkgInvoice->bkg_service_tax ?>';
		var extraToltax = '<?= $model->bkgInvoice->bkg_extra_toll_tax ?>';
		var txtDueAmount = parseInt(($('.txtDueAmount').text()).replace(/([-,.₹;'<>])+/g, ''));
		var txtAdvancePaid = parseInt(($('.txtAdvancePaid').text()).replace(/([-,.₹;'<>])+/g, ''));
		//var tax			= '<? //= $model->bkgInvoice->bkg_service_tax  ?>';
		if (extracharge != '' && carrier == 1)
		{
			var additionalAmt = parseInt(additionalAmt) + parseInt(extracharge);
		}
		var grossAmount = parseInt(parseInt(baseFare) + parseInt(additionalAmt)) - parseInt(disamt);
		//var grossAmount	= parseInt(baseFare) + parseInt(additionalAmt);
		if (convienceCharge != '')
		{
			grossAmount = grossAmount + parseInt(convienceCharge);
		}
		//grossAmount	 = parseInt(grossAmount) + parseInt(drvallownce);
		//var serviceTax	 = Math.round(grossAmount * parseInt(sevicetaxRate) * 0.01);
		if (additionalAmt != '')
		{
			$('.additionalcharge').removeClass('hide');
		}
		$('.extracharge').text(additionalAmt);

		//var totalAmnt = (parseInt(grossAmount)+parseInt(tolTax)+parseInt(otherTax)+parseInt(serviceTax));
		var grossAmount = parseInt(grossAmount) + parseInt(tolTax) + parseInt(otherTax) + parseInt(extraToltax) + parseInt(extraStateTax) + parseInt(drvallownce) + parseInt(parkingCharge) + parseInt(airportEntryCharge);
		var serviceTax = Math.round(grossAmount * parseInt(sevicetaxRate) * 0.01);
		var totalAmnt = parseInt(grossAmount) + parseInt(serviceTax);

		var dueAmt = $jsHandleUI.moneyFormatter(totalAmnt - txtAdvancePaid);
		var totalAmnt = $jsHandleUI.moneyFormatter(totalAmnt)

		$('.txtEstimatedAmount').text(dueAmt);
		$('.payBoxTotalAmount').text(totalAmnt);
		$('.txtDueAmount').text(dueAmt);
		$('.extracharge').text(additionalAmt);
		$(".txtGstAmount").text(serviceTax);
	}
</script>