<?php
$minamount = $model->bkgInvoice->calculateMinPayment();
if ($minPay > 0)
{
    $minamount = $minPay;
}
$minPerc		 = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id,$model->bkgPref->bkg_is_gozonow);
//$minPerc = ($model->bkg_booking_type == 7) ? '25%' : '20%';
//$minPerc = (in_array($model->bkg_booking_type,[9,10,11])) ? '50%' : '20%';
$advance		 = $model->bkgInvoice->getAdvanceReceived();
$maxPaymentWithDiscount	 = round($model->bkgInvoice->bkg_total_amount) - $advance;
$freeCancellationEnd =   CancellationPolicyRule::getCancelationTimeRange($model->bkg_id, 1);
?>
<div class="col-12 mb20">
    <div class="bg-white-box">
	<?php foreach ($freeCancellationEnd as $key => $value)
	{
		if($value['CancelCharge'] == 0)
		{ ?>
			<div class="alert alert-primary payHeader mb10 text-center"><i class="fa fa-check-circle font18" aria-hidden="true"></i><span class="pl10 font-18">Free Cancellation applicable before<br><?=date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[0]))?></span></div>
	<?php	}
	} ?>
	
	<?php if ($model['bkg_booking_type'] != 7)
	{ ?>
    	<div class="row m0 pay-link">
    	    <div class="col-9 radio">
    		<label class="radio2-style">
    		    <input type="radio" name="payChk" id="minPayChk" value="0" checked="checked" class="mt5 clsPayChk"> <span style="font-size: 16px;font-weight: bold;padding: 5px">Pay <?= $minPerc ?>% Now</span> rest pay to driver
    		    <span class="checkmark-2"></span>
    		</label>
    	    </div>
    	    <div class="col-3 text-right font-18 mt10">
    		<span>&#x20B9</span><span class="payBoxMinAmount"><b><?= $minamount ?></b></span>
    	    </div>
    	</div>
<?php } ?>
	<div class="row m0">
	    <div class="col-9 radio">
		<label class="radio2-style">
		    <input type="radio" name="payChk" id="fullPayChk" value="1" class="mt5"> <span style="font-size: 16px;font-weight: bold;padding: 5px">Pay full online</span>
		    <span class="checkmark-2"></span>
		</label>
	    </div>
	    <div class="col-3 text-right font-18 mt10">
		<span>&#x20B9</span><span class="payBoxDueAmount"><b><?= $maxPaymentWithDiscount ?></b></span>
	    </div>
	</div>

	<div class="row pl10 pr10 color-black font-20 pt15 border-top">
	    <div class="col-8 text-uppercase">
		<b>Total Amount</b>	
	    </div>
	    <div class="col-4 text-right pr15">
		<b><span>&#x20B9</span><span class="payBoxTotalAmount"><?= round($model->bkgInvoice->bkg_total_amount) ?></span></b>
	    </div>
	</div>
        <button type="button" class="btn gradient-green-blue border-none font-20 mb10 mt15" style="width: 100%; font-weight: bold;" id="payBoxBtn">PAY <?= $minPerc ?>% (<span>&#x20B9</span><span class="payBoxBtnAmount"><?= $minamount ?></span>)</button>
    </div>
</div>
	<?php
$dboApplicable = Filter::dboApplicable($model);
if ($dboApplicable)
{   
	if (!Yii::app()->user->isGuest)
	{
		?>
		<div class="col-12 mb20">
			<div class="bg-white-box">
				<div class="row">
					<div class="col-12 col-md-9">
						<a target="_blank" href="<?php echo Yii::app()->createUrl("/terms/doubleback");?>"><img src="/images/doubleback_fares2.jpg" alt="" width="350" class="img-responsive"></a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
?>
<script>
    var minAmt;
    var dueAmt;
    bktype = <?= $model->bkg_booking_type ?>;
    $('#minPayChk,#fullPayChk').click(function ()
    {
        $('#payBoxBtn').html('');
        if ($('#minPayChk').is(":checked") == true)
        {
            var minPerc = '<?= $minPerc ?>';

            minAmt = $('.payBoxMinAmount').text();
            $('#payBoxBtn').html('PAY ' + minPerc + '% (<span>&#x20B9</span><span class="payBoxBtnAmount">' + minAmt + '</span>)');
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

//	$('#payBoxBtn').click(function ()
//	{
//		
//		var specialStatus = $("#request_status").val();
//		if(	bktype==7)
//		{
//			specialStatus = 1;
//		}
//		if(specialStatus==1)
//		{
//			$('#payment').removeClass('hide');
//			$(window).scrollTop($('.detailsWidjet').height()+$(window).height()/100*3);
//		}
//		else
//		{
//			 alert("Please save your special requests preferences first. We need to know passenger + luggage information before you can proceed to pay.");
//			 $('#additiondetails').removeClass("hide");
//			$(window).scrollTop($('.detailsWidjet').height()-400);
//		}
//		
//	});

    $('#payBoxBtn').click(function ()
    {
        $('#payment').removeClass('hide');
        $('html,body').animate({scrollTop: $("#payment").offset().top}, 'slow');
    });

</script>