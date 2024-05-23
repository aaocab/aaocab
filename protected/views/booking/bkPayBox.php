<?php
//print_r();
$orderAmount = $model->bkgInvoice->calculateMinPayment();
if ($minPay > 0)
{
	$orderAmount = $minPay;
}
$minPerc = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id,$model->bkgPref->bkg_is_gozonow);
//$minPerc = ($model->bkg_booking_type == 7) ? '25%' : '20%';
//$minPerc = (in_array($model->bkg_booking_type,[9,10,11])) ? '50%' : '20%';
$advance				 = $model->bkgInvoice->getAdvanceReceived();
$maxPaymentWithDiscount	 = round($model->bkgInvoice->bkg_total_amount) - $advance;
?>
<div class="col-xs-12">
	<div class="payHeader mb10"><i class="fa fa-check-circle font18" aria-hidden="true"></i><span class="pl10">Free Cancellation applicable before <?= date('d M Y h:i A', strtotime('-6 hours', strtotime($model->bkg_pickup_date))) ?></span></div>
	<div class="main_time pb0 border-greenline mb20">
		<button type="button" class="btn btn-info gradient-green-blue border-none font20 m10" style="width: 95%; font-weight: bold;" id="payBoxBtn">PAY <?=$minPerc?>% (<span>&#x20B9</span><span class="payBoxBtnAmount"><?= $orderAmount ?></span>)</button>
			<?php
			if($model['bkg_booking_type']!=7)
            {
            ?>
			<div class="row p2 pay-link">
				<div class="col-xs-8 radio">
					<label class="radio-style">
						<input type="radio" name="payChk" id="minPayChk" value="0" checked="checked" class="mt5 clsPayChk"> <span style="font-size: 16px;font-weight: bold;padding: 5px">Pay <?=$minPerc?>% Now</span> rest pay to driver
						<span class="checkmark"></span>
					</label>
				</div>
				<div class="col-xs-4 text-right font18 mt10">
					<span>&#x20B9</span><span class="payBoxMinAmount" style="font-weight: bold;"><?= $orderAmount ?></span>
				</div>
			</div>
        <?php
			}
         ?>
		<div class="row p2">
			<div class="col-xs-8 radio">
				<label class="radio-style">
					<input type="radio" name="payChk" id="fullPayChk" value="1" class="mt5"> <span style="font-size: 16px;font-weight: bold;padding: 5px">Pay full online</span>
					<span class="checkmark"></span>
				</label>
			</div>
			<div class="col-xs-4 text-right font18 mt10">
				<span>&#x20B9</span><span class="payBoxDueAmount" style="font-weight: bold;"><?= $maxPaymentWithDiscount ?></span>
			</div>
		</div>

		<div class="row mb20 pl10 pr10 block-color mt10">
			<div class="col-xs-8">
				<span class="font18"><b>Total Amount</b></span>		
			</div>
			<div class="col-xs-4 text-right font20 pr0">
				<b><span>&#x20B9</span><span class="payBoxTotalAmount"><?= round($model->bkgInvoice->bkg_total_amount) ?></span></b>
			</div>
		</div>
	</div>
<!--	<div class="row"><div class="col-sm-12 mb20 text-center"><?php $this->renderPartial("bkBanner", ['model' => $model]); ?></div></div>-->
<?php
$dboApplicable = Filter::dboApplicable($model);
if ($dboApplicable)
{
?>
	<div class="row"><div class="col-sm-12 mb20 text-center"><a href="/terms/doubleback" target="_blank"><img src="/images/doubleback_fares2.jpg?v=0.1" alt="" class="img-responsive"></a></div></div>
<?php
}
?>
</div>
<script>
	var minAmt;
	var dueAmt;
	bktype = <?= $model->bkg_booking_type ?>;
	$('#minPayChk,#fullPayChk').click(function ()
	{ 
		$('#payBoxBtn').html('');
		if ($('#minPayChk').is(":checked") == true)
		{
			var minPerc = '<?=$minPerc?>';			 
			minAmt = $('.payBoxMinAmount').text(); 
			$('#payBoxBtn').html('PAY ' + minPerc + ' (<span>&#x20B9</span><span class="payBoxBtnAmount">' + minAmt + '</span>)');
			$('#fullPayChk').parent().parent().parent().removeClass("pay-link");
			$('#minPayChk').parent().parent().parent().addClass("pay-link");			
			if ($('#BookingInvoice_partialPayment').val() != minAmt)
			{
				$('#inlineCheckbox1').click();
			}
			
		} else
		{
			dueAmt = $('.payBoxDueAmount').text();
			$('#payBoxBtn').html('PAY Full (<span>&#x20B9</span><span class="payBoxBtnAmount">' + dueAmt + '</span>)');
			$('#fullPayChk').parent().parent().parent().addClass("pay-link");
			$('#minPayChk').parent().parent().parent().removeClass("pay-link");			
			$('#inlineCheckbox1').click();
		}
	});

	$('#payBoxBtn').click(function ()
	{
		$('#payment').removeClass('hide');
		$('html,body').animate({scrollTop: $("#payment").offset().top},'slow');
	 
//		var specialStatus = $("#request_status").val();
//		if(	bktype==7)
//		{
//			specialStatus = 1;
//		}
//		if(specialStatus==1)
//		{
			//alert("hibgj");
//			$('#payment').removeClass('hide');
//			//$(window).scrollTop($('.detailsWidjet').height()+$(window).height()/100*3);
//			$(window).scrollTop($('.detailsWidjet').height()-1800);
//		}
//		else
//		{
//			 alert("Please save your special requests preferences first. We need to know passenger + luggage information before you can proceed to pay.");
//			 $('#additiondetails').removeClass("hide");
//			$(window).scrollTop($('.detailsWidjet').height()-1800);
//			$(".additionalinfo").removeClass('blueline');
//			$(".additionalinfo").css("border", "1px solid red");
//		}
		
	});

</script>