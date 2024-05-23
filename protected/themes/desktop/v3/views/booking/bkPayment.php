<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.min.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/v3/promo.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/v3/promotion.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/v3/handleUI.js?v=' . $version);

$minamount	 = $bkgMinAmount;
$minPerc	 = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $model->bkgPref->bkg_is_gozonow);
$advance	 = $model->bkgInvoice->getAdvanceReceived();
if($advance == 0)
{
	BookingPref::updateMinAdvanceParams($model->bkg_id, $minPerc, $model->bkgInvoice->bkg_total_amount);
}

$maxPaymentWithDiscount	 = round($model->bkgInvoice->bkg_total_amount) - $advance;
$maxPaymentWithDiscount	 = ($bkgDueAmount == '') ? $maxPaymentWithDiscount : $bkgDueAmount;
$defaultAmount			 = ($model->bkgInvoice->bkg_advance_amount > 0) ? $maxPaymentWithDiscount : $minamount;

if($model->bkg_cav_id != NULL && $model->bkg_cav_id > 0)
{
	$minPerc = 50;
}
$arrPartPayPercent = array_unique([$minPerc, 50, 100]);

$paymentOptions		 = Config::get('payment.setting');
$arrPaymentOptions	 = json_decode($paymentOptions, true);
$showPayu			 = false;
$showEaseBuzz		 = false;

if($model->bkgUserInfo->bkg_bill_contact != '' && $model->bkgUserInfo->bkg_bill_email != '')
{
	$showEaseBuzz = true;
}

//$getPaymentEntry = PaymentGateway::fetchTransactionsByBooking($model->bkg_id);
//if($getPaymentEntry->getRowCount() > 0)
//{
//	$showPayu = true;
//}
?>
<div class="container-fluid mb-2">
	<div class="row justify-center">
		<div class="col-12 text-center">
			<h2 class="merriw heading-line">Make a Payment</h2>
		</div>
		<div class="col-12 text-center">
			<div class="alert alert-danger mb-2 text-center hide alertpayment" role="alert"></div>
		</div>

		<div class="col-12 mt-2">
			<div class="row justify-center">

				<?php
				foreach($arrPartPayPercent as $paykey => $value)
				{
					$key		 = array_search($value, array_column($arrPaymentOptions, 'percentage'));
					$payOption	 = $arrPaymentOptions[$key];
					$checked	 = ($payOption['id'] == 'minPayChk') ? 'checked="checked"' : '';
					if($payOption['id'] == 'partPayChk' && (in_array($model->bkg_booking_type, [9, 10, 11]) || $model->bkg_cav_id != NULL))
					{
						$checked = 'checked="checked"';
					}

					$partPayment = round($maxPaymentWithDiscount * $payOption['percentage'] / 100);

					$hide		 = '';
					$percentDesc = '(' . $value . ' % advance)';
					if($minPayExtra > 0 && $key == 0)
					{
						$partPayment		 = round($minPayExtra);
						$hide				 = "hide";
						$percentDesc		 = "";
						$payOption['label']	 = "Part Payment";
					}
					else
					{
						$partPayment = $partPayment - $prevAdvance;
					}
					?>
					<div class="col-12 col-xl-7 col-lg-7 mt-1 part-widget <?php echo ($paykey > 0 ? 'otherPaymentOpt hide' : ''); ?>">
						<div class="radio-style4">
							<div class="float-right">
								<span class="font-18"></span><span class="<?php echo 'spanPrice' . $key; ?> font-18 weight600 <?= $payOption['class'] ?>"><?php echo Filter::moneyFormatter($partPayment); ?></span>
							</div>
							<div class="radio"> 
								<input type="radio" name="payChk" id="<?php echo $payOption['id'] ?>" value="<?php echo $payOption['value'] ?>" <?= $checked ?> class="bkg_user_trip_type payChk">
								<label  for="<?php echo $payOption['id'] ?>"><?php echo $payOption['label'] ?> <br><?php echo $percentDesc; ?></label>
							</div>
						</div>
					</div>
				<?php }
				?>
				<?php
				$isAllowedCash = BookingPref::isFullCashAllowed($model->bkg_id);
				if($isAllowedCash)
				{
					?>
					<div class="col-12 col-xl-7 col-lg-7 mt-1 part-widget mb-1"> 
						<div class="float-right">
							<span class="font-18"></span><span  class="font-18 weight600">₹0</span>
						</div>
						<div class="radio-style4">
							<div class="radio"> 
								<input type="radio" name="payChk" id="payChkcash" value="-1" class="bkg_user_trip_type payChk">
								<label for="payChkcash">Pay in cash</label>
							</div>
						</div>

					</div>
					<?php
				}
				?>
				<div class="col-12 col-xl-7 col-lg-7 mt-1 part-widget btnPayMore text-right"><a href="Javascript:void(0)" onclick="showPayMoreOptions()">More Option</a></div>
				<?php
				/** @var Booking $model */
				if($walletBalance > 0 && ($model->bkgUserInfo->bkg_user_id == UserInfo::getUserId()) && $model->bkgInvoice->bkg_advance_amount == 0)
				{
					?>
					<div class="col-12  col-xl-7 col-lg-7  mt-1 check-style part-widget  walletRemoveDiv" style="display:none;">
						<div class="list-tree-ui">
							<h4 class="font-14">Wallet balance of ₹<span class="walletUsed"></span><b> <span class="color-green">is applied.</span></b><br/> ₹<span id="remainingWallet" class="text-uppercase  remainingWallet"><?php echo $walletBalance ?></span> remaining in your gozo wallet <a href="javascript:void(0);" onclick="prmObj.applyWallet(6);" class="btn btn-danger btn-sm float-right mt15 n pl10 pr10">Remove</a></h4>

						</div>			            
					</div>

					<div class="col-12  col-xl-7 col-lg-7  mt-2 check-style2 part-widget walletDiv">
						<div class="">
							<div class="walletbox">
								<div class="list-tree-ui">Use wallet balance(<?php echo Filter::moneyFormatter($walletBalance); ?>) 
									<a href="javascript:void(0);" onclick="walletApply()" class="btn btn-primary btn-sm text-uppercase ml-2 float-right">Apply</a>
								</div>
								<input type="hidden" id="BookingInvoice_bkg_wallet_used" name="BookingInvoice_bkg_wallet_used" value="<?= $walletBalance ?>" class="form-control" placeholder="Enter Amount">
							</div>
						</div>
						<input id="walletbalance" class="walletbalance" type="hidden" value="<?= $walletBalance; ?>">

					</div>

				<?php } ?>

				<?php
				if($maxPaymentWithDiscount > 40000)
				{
					?>
					<div class="col-12">
						<p class="merriw font-14 mt10 color-gray">(Note: Maximum ₹40,000 allowed at a time. please make partial payment)</p>
					</div>
				<?php } ?>
				<input id="isWalletSelected" class="isWalletSelected" type="hidden" value="0">
				<div class="col-12 hide">
					<a href="javascript:void(0);" onclick="confirmBooking();" class="btn btn-primary btn-sm text-uppercase ml-2 float-right">Confirm Booking</a>
				</div>
				<div class="col-12 mt-3 txtpaymentgateway">
					<p class="text-center merriw font-18 weight600">Proceed with the payment gateway below</p>
				</div>

			</div>
		</div>

		<div class="col-12 col-md-12 col-xl-12 mb-2 paymentgateway">
			<div class="row m0 justify-center">
				<?php
				if($showEaseBuzz)
				{
					?>
					<div class="col-12 col-md-6 col-xl-6 p5 radio-style5 easebuzzdivPG ">
						<div class="card mb10">
							<div class="card-body p10 pl5 pr5">
								<div class="radio noradio payu">
									<input type="radio" name="intPay" id="easebuzz" value="1" class="mr20 n intPay"> 
									<label for="easebuzz" class="p5 font-16">
										<span class="one-tb font-13">Credit/ Debit Card | Net Banking | Wallet | UPI | EMI</span><br>
										<span class="two-tb font-12 weight600">EaseBuzz</span>
									</label>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<div class="col-12 col-md-6 col-xl-6 p5 radio-style5 razorpaydivPG">
					<div class="card mb10">
						<div class="card-body p10 pl5 pr5">
							<div class="radio noradio payu">
								<input type="radio" name="intPay" id="razorpay" value="1" class="mr20 n  intPay"> 
								<label for="razorpay" class="p5 font-16">
									<span class="one-tb font-13">Credit/ Debit Card | Net Banking | Wallet | UPI | EMI</span><br>
									<span class="two-tb pt10 font-12 weight600">Razorpay</span>
								</label>
							</div>
						</div>
					</div>
				</div>

				<?
				if($showPayu)
				{
					?>
					<div class="col-12 col-md-6 col-xl-6 p5 radio-style5 payudivPG ">
						<div class="card mb10">
							<div class="card-body p10 pl5 pr5">
								<div class="radio noradio payu">
									<input type="radio" name="intPay" id="payucard" value="1" class="mr20 n  intPay"> 
									<label for="payucard" class="p5 font-16">
										<span class="one-tb font-13"><span class="weight600">Net Banking</span> (SBI | HDFC | AXIS)</span>
										<span class="two-tb font-12 weight600">PayU Money</span>
									</label>
								</div>
							</div>
						</div>
					</div>
					<?
				}
				?>


				<?php
				if($isAllowedCash)
				{
					?>
					<div class="col-12 col-md-6 col-xl-4 text-center mt-2 cashbookingDiv hide">
						<button href="javascript:void(0);" onclick="confirmBooking()" class="btn btn-primary btn-sm text-uppercase">Confirm as cash</button>
					</div>		
				<?php } ?>

				<!--							<div class="col-12 col-md-4 col-xl-4 p5 radio-style5 hide">
												<div class="card mb10">
													<div class="card-body p10 pl5 pr5">
														<div class="radio noradio  ptm">
															<input type="radio" name="intPay" id="ptm" value="0"  class="mr15 n  intPay">
															<label for="ptm" class="font-16 p5">
																<span class="one-tb">PayTM</span>
																<span class="two-tb">UPI</span>
															</label>
														</div>
													</div>
												</div>
											</div>-->
			</div>
		</div>
	</div>

</div>
<?php
$maxPaymentWithDiscount = $maxPaymentWithDiscount - $prevAdvance;
$this->renderPartial("paywidget", ["model" => $model, 'bkgMinAmount' => $minamount, 'bkgDueAmount' => $maxPaymentWithDiscount], false);
?>
<script type="text/javascript">
	var pageInitialized = false;
	var huiObj = null;
	var prmObj = null;
	var additionalParams = '<?php echo $additionalParams; ?>';
	huiObj = new HandleUI();
	$(document).ready(function ()
	{

		//	checkPayAmmount();
		$(".walletRemoveDiv").hide();
		if (pageInitialized)
			return;
		pageInitialized = true;


		huiObj.bkgId = '<?= $model->bkg_id ?>';
		prmObj = new Promotion(huiObj);

		// $(".clsAdditionalParams").val(additionalParams);
		var walletUsedDb = '<?php echo $model->bkgInvoice->bkg_wallet_used ?>';
		var advanceAmt = '<?php echo $model->bkgInvoice->bkg_advance_amount ?>';
		if (walletUsedDb > 0 && advanceAmt == 0)
		{
			walletApply();
		}

		var agentId = '<?= $model->bkg_agent_id ?>';
		var configAgentId = '<?= Config::get('Kayak.partner.id') ?>';
		if (agentId == configAgentId)
		{
			$('.requestcall').addClass('hide');
		} else {
			$('.requestcall').removeClass('hide');
		}

	});

	var actualMinAmt = '<?php echo $minamount ?>';
	var dueAmt = '<?php echo $maxPaymentWithDiscount; ?>';
	//alert(dueAmt);
	//$('#minpay').val(actualMinAmt);
//	$("#minpay").val(huiObj.moneyFormatter(actualMinAmt, 1));

	$('.intPay').on('click', function () {

		// debugger;
		var payCheck = $("input[name='payChk']:checked").val();

		var isWalletSelected = $("#isWalletSelected").val();
		var custMinPaying = dueAmt;
		if (payCheck == 0)
		{

			custMinPaying = huiObj.removeCommas($('.payBoxMinAmount').text().substring(1));
		}
		if (payCheck == 1)
		{
			custMinPaying = huiObj.removeCommas($('.payBoxPartAmount').text().substring(1));
		}
		if (payCheck == 2)
		{
			custMinPaying = huiObj.removeCommas($('.payBoxDueAmount').text().substring(1));
		}
		if (!($(".walletbox").hasClass('hide')))
		{

			if (payCheck == 0 || payCheck == 1)
			{

				$("#BookingInvoice_partialPayment").val(custMinPaying);
				$("#BookingInvoice_partialPayment").attr('min', custMinPaying);
			} else
			{


				if (isWalletSelected == 0)
				{
					$("#BookingInvoice_partialPayment").val(dueAmt);
					$("#BookingInvoice_partialPayment").attr('max', dueAmt);
				}

				$(".alertpayment").html('');
				$(".alertpayment").addClass('hide');
				$(".alertpayment").hide();
			}
		}
		payCardInfo();
	});


	function checkMinAmount()
	{
		var custMinPaying = parseInt($('#minpay').val() | 0);
		var payCheck = $("input[name='payChk']:checked").val();
		if (!($(".walletbox").hasClass('hide')))
		{
			$(".alertpayment").hide();
			$('#minpay').val(custMinPaying);
//			$('#minpay').val(huiObj.moneyFormatter(custMinPaying, 1));
			if (custMinPaying <= 0 || custMinPaying == '')
			{
				$(".alertpayment").html('Minimum Payable amount should be more than 0');
				$(".alertpayment").removeClass('hide');
				$(".alertpayment").show();
				return false;
			}
			if (parseInt(actualMinAmt) > custMinPaying)
			{
				$(".alertpayment").html('Minimum Payable amount is ₹' + huiObj.moneyFormatter(actualMinAmt, 1));
				$(".payBoxMinAmount").val(huiObj.moneyFormatter(actualMinAmt, 1));
				$('#minpay').val(actualMinAmt);
				$(".alertpayment").removeClass('hide');
				$(".alertpayment").show();
				return false;
			}
			if (custMinPaying > parseInt(dueAmt))
			{
				$(".alertpayment").html('Maximum Payable amount is ₹' + huiObj.moneyFormatter(dueAmt, 1));
				$('#minpay').val(dueAmt);
				$(".alertpayment").removeClass('hide');
				$(".alertpayment").show();
				return false;
			}
			if (payCheck == 0)
			{
				$("#BookingInvoice_partialPayment").val(custMinPaying);
				$("#BookingInvoice_partialPayment").attr('min', custMinPaying);
			} else
			{
				$("#BookingInvoice_partialPayment").val(dueAmt);
				$("#BookingInvoice_partialPayment").attr('max', dueAmt);
				$(".alertpayment").html('');
				$(".alertpayment").addClass('hide');
				$(".alertpayment").hide();
			}

		}
	}
	function walletApplyOLD() {
		//debugger;
		$(".alertpayment").hide();
		var walletAmount = '<?= $walletBalance ?>';
		//var custMinPaying = parseInt($('#minpay').val() | 0);
		var payCheck = $("input[name='payChk']:checked").val();

		var custMinPaying = huiObj.removeCommas($('.payBoxMinAmount').text().substring(1));

		if (payCheck == 1)
		{
			custMinPaying = huiObj.removeCommas($('.payBoxPartAmount').text().substring(1));
		}
		if (payCheck == 2)
		{
			custMinPaying = huiObj.removeCommas($('.payBoxDueAmount').text().substring(1));
		}
		if (parseInt(actualMinAmt) > parseInt(walletAmount) || custMinPaying < parseInt(actualMinAmt))
		{
			$(".alertpayment").html('Wallet balance cannot be applied. Minimum payable amount ₹' + huiObj.moneyFormatter(actualMinAmt, 1));
			$(".alertpayment").removeClass('hide');
			$(".alertpayment").show();
		} else {
			prmObj.applyWallet(5, custMinPaying);
		}
	}

	$('.payBoxMinAmount').on('keydown', function (event) {
		let charCode = (event.which) ? event.which : event.keyCode;
		if (event.shiftKey == 1) {
			return false;
		}
		if ((charCode >= 48 && charCode <= 57) || charCode == 8 || charCode == 46 || (charCode >= 96 && charCode <= 105))
		{
		} else
		{
			event.preventDefault();
		}
	});

	function confirmBooking()
	{	//debugger;
		var dueAmt = $('.payBoxDueAmount').text().substring(1);
		var payCheck = $("input[name='payChk']:checked").val();
		var href = '<?= $this->getURL(['booking/confirmbooking', "id" => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)]) ?>';
		if (payCheck != "-1")
		{
			if (payCheck == 0)
			{
				var custMinPaying = huiObj.removeCommas($('.payBoxMinAmount').text().substring(1));
			}
			if (payCheck == 1)
			{
				var custMinPaying = huiObj.removeCommas($('.payBoxPartAmount').text().substring(1));
			}
			if (payCheck == 0 || payCheck == 1)
			{
				$("#BookingInvoice_partialPayment").val(custMinPaying);
				$("#BookingInvoice_partialPayment").attr('min', custMinPaying);
			} else
			{
				$("#BookingInvoice_partialPayment").val(dueAmt);
				$("#BookingInvoice_partialPayment").attr('max', dueAmt);
			}
		} else
		{
			href = '<?= $this->getURL(['booking/confirmbooking', "id" => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id), 'cash' => 1]) ?>';
		}

		$.ajax({
			"type": "POST",
			"dataType": "json",
			"url": href,
			"data": $("#payment-form1").serialize(),
			"beforeSend": function () {
				ajaxindicatorstart("");
			},
			"complete": function () {
				ajaxindicatorstop();
			},
			"success": function (data1) {
				//debugger;
				if (data1.success) {
					if (data1.url != "" && data1.url != undefined)
					{
						location.href = data1.url;
						return false;
					}
					if (data1.data.url != "" && data1.data.url != undefined)
					{
						location.href = data1.data.url;
						return false;
					}

				} else
				{
					var errors = data1.error;
					var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
					$.each(errors, function (key, value)
					{
						txt += "<li>" + value + "</li>";
					});
					txt += "</li>";
					$(".alertpayment").html(txt);
					$(".alertpayment").removeClass('hide');
					$(".alertpayment").show();
				}
			}
		});
	}


	function walletApply() {
		//  debugger;
		$(".alertpayment").hide();
		var walletAmount = '<?= $walletBalance ?>';
		//alert(<?= $walletBalance ?>);
		$("#isWalletSelected").val(1);
		var payCheck = $("input[name='payChk']:checked").val();

		var custMinPaying = huiObj.removeCommas($('.payBoxMinAmount').text().substring(1));

		if (payCheck == 1)
		{
			custMinPaying = huiObj.removeCommas($('.payBoxPartAmount').text().substring(1));
			$('input[id=minPayChk]').attr("disabled", true);
			$('input[id=fullPayChk]').attr("disabled", true);
		}
		if (payCheck == 2)
		{
			custMinPaying = huiObj.removeCommas($('.payBoxDueAmount').text().substring(1));
			$('input[id=minPayChk]').attr("disabled", true);
			$('input[id=partPayChk]').attr("disabled", true);
		}

		if (parseInt(walletAmount) > 0 && custMinPaying > parseInt(walletAmount))
		{
			prmObj.applyWallet(7, custMinPaying);

		} else {
			prmObj.applyWallet(5, custMinPaying);
		}

	}
	function checkPayAmmount()
	{

		var href = '<?= $this->getURL(['booking/checkPayAmmount', "id" => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)]) ?>';
		$.ajax({
			"type": "POST",
			"dataType": "json",
			"url": href,
			//"data": $("#payment-form1").serialize(),
			data: {'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
			"beforeSend": function () {
				ajaxindicatorstart("");
			},
			"complete": function () {
				ajaxindicatorstop();
			},
			"success": function (data1) {
				//debugger;
				if (data1.success) {
					if (data1.url != "")
					{
						location.href = data1.url;
						return false;
					}

				}
			}
		});
	}

	$('.payChk').on('click', function ()
	{
		if ($(this).val() == "-1")
		{
			$('.txtpaymentgateway').addClass('hide');
			$('.razorpaydivPG').addClass('hide');
			$('.payudivPG').addClass('hide');
			$('.walletbox').addClass('hide');
			$('.cashbookingDiv').removeClass('hide');
			$('.easebuzzdivPG').addClass('hide');
		} else
		{
			$('.txtpaymentgateway').removeClass('hide');
			$('.razorpaydivPG').removeClass('hide');
			$('.payudivPG').removeClass('hide');
			$('.walletbox').removeClass('hide');
			if (!$('.cashbookingDiv').hasClass('hide')) {
				$('.cashbookingDiv').addClass('hide');
			}
			$('.easebuzzdivPG').removeClass('hide');
		}
	});

	function showPayMoreOptions()
	{
		$('div.otherPaymentOpt.hide').removeClass('hide');
		$('.btnPayMore').hide();
	}
</script>	