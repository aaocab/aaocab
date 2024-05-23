<style type="text/css">
    .headding-1{ background: #333333!important; color: #fff; padding: 8px;}
    .headding-2{ background: #22baa0!important; color: #fff; padding: 8px;}
    .tr-panel{ border-top: #e9e9e9 1px solid; background: #f6f6f6;}
    .tr-panel2{ border-top: #e9e9e9 1px solid; background: #d5faff;}
    .payment-option{}
    .summary-box{ height: 297px; overflow: auto;} 

    /**************Booking panel**************/
    .payment-option{ font-size: 12px;}
    .payment-option .nav-tabs{
		background: #fcfcfc;
    }
    .payment-option ul{ display: block; padding: 0; border-right: #d7d7d7 1px solid;  }
    .payment-option .nav-tabs > li{ display: block!important;
									border-bottom: #f0f0f0 1px solid;
									margin-bottom: 0; padding: 0;}
    .payment-option .nav-tabs > li a{ display: block!important; border: 0; padding: 16px 10px;}

    .payment-option .nav-tabs > li a:hover{  background: #0D47A1!important; color: #FFFFFF!important;}
    .payment-option .nav-tabs > li a:focus{  color: #FFFFFF!important;}
    .payment-option .nav-tabs > li a:active{  background: #0D47A1!important; color: #FFFFFF!important;}

	.payment-option .nav>li a{
		line-height:  1.4em ; 
	}

    .payment-logos{ min-height: 60px;}
    .bg-gray
    {
		background-color: #EEEEFE;
		line-height: 16px;
    }
    .bg-compact
    {

		line-height: 16px;
    }
    .text-label{
		font-size: 0.8em;
		text-transform: uppercase;
		font-weight: bold;

    }

    /*                            */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    .pay_panel{ background: #deebfe;}
    .pay_panel .nav li a{ 
        display: block; border: #fff 1px solid; margin-right: 0; color: #000; text-transform: uppercase; font-size: 16px;
        background: #ededed;
        background: -moz-linear-gradient(top,  #ededed 0%, #ffffff 100%);
        background: -webkit-linear-gradient(top,  #ededed 0%,#ffffff 100%);
        background: linear-gradient(to bottom,  #ededed 0%,#ffffff 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#ffffff',GradientType=0 );}


    .bordered {
        border:1px solid #ddd;
        min-height: 45px;
    }
    .border-top-bottom {
        border-top:1px solid #666666;
        border-bottom: 1px solid #666666;
    }
    .trip_plan1 table { 
        width: 100%; 
        border-collapse: collapse; 
    }
    /* Zebra striping */
    .trip_plan1 tr:nth-of-type(odd) { 
        background: #f1f1f1; 
    }
    .trip_plan1 th { 
        background: #333; 
        color: white; 
        font-weight: bold; 
    }
    .trip_plan1 td { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
    .trip_plan1 th { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
	@media (min-width: 768px){
		.payment-option .nav-tabs > li{ width: 100%; padding: 0;}
		.payment-option .nav-tabs > li:hover,
		.payment-option .nav-tabs > li:focus,
		.payment-option .nav-tabs > li:active,
		.payment-option .nav-tabs > li.active,
		.payment-option .nav-tabs > li a.active
		{
			background: #fd7034!important;
			color: #FFFFFF!important; 
		}
		.payment-option .nav-tabs > li a{ margin: 0; color: #727272;}
		.payment-option .nav-tabs > li a:active{ font-weight: 700!important;}
		.payment-option .nav-tabs > li a:focus{ font-weight: bold!important;}
	}
    @media (max-width: 767px)
    {

        /* Force table to not be like tables anymore */
        .trip_plan1 table, thead, tbody, th, td, tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .trip_plan1 thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .trip_plan1 tr{ border: 1px solid #ccc; }

        .trip_plan1 td{ 
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #d5d5d5; 
            position: relative;
            padding-left: 50%; 
        }

        .trip_plan1 td:before { 
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            top: 6px;
            left: 6px;
            width: 45%; 
            padding-right: 10px; 
            white-space: nowrap;
        }

        /*
        Label the data
        */
        .trip_plan1 td:nth-of-type(1):before { content: "From"; }
        .trip_plan1 td:nth-of-type(2):before { content: "To"; }
        .trip_plan1 td:nth-of-type(3):before { content: "Departure Date"; }
        .trip_plan1 td:nth-of-type(4):before { content: "Time"; }
        .trip_plan1 td:nth-of-type(5):before { content: "Distance"; }
        .trip_plan1 td:nth-of-type(6):before { content: "Duration"; }
        .trip_plan1 td:nth-of-type(7):before { content: "Days"; }

        .payment-option ul{ min-height: 54px;}
		.payment-option .nav-tabs > li{ display: inline-block; border-bottom: none; font-size: 11px;}
		.nav-tabs > li > a{ line-height: initial;}
		.payment-option .nav-tabs > li{ padding: 0 2px;}
		.modal-dialog{ margin-left: auto; margin-right: auto;}
    }
    .yii-selectize.full-width,
    .yii-selectize.full-width .selectize-input,.full {
        width: 100% !important;

    }
    label.optType{
        min-height: 30px;
		cursor: pointer;
    }
    .optType  {
		text-decoration: none!important;
    }

	.box_2{
        display:flex;
        flex-flow: column;
    }  
    .lowerslab{order:0;}
    .upperslab{order:-1;}
</style>
<?php
$ccmodel	 = new BraintreeCCForm('charge');
$orderAmount	 = $model->bkgInvoice->calculateMinPayment();
if ($minPay > 0)
{
	$orderAmount = $minPay;
}

$total_tax_rate	 = ($model->bkgInvoice->bkg_convenience_charge * $model->bkgInvoice->getServiceTaxRate() * 0.01);
$saveAmount		 = round($model->bkgInvoice->bkg_convenience_charge + $total_tax_rate);
$msg			 = 'Pay online at least <i class="fa fa-inr"></i>' . $orderAmount . ' advance before trip starts to save <i class="fa fa-inr"></i>' . $saveAmount . ' more on your quoted fare.';
$creditsused	 = ($model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0;
$advance		 = $model->bkgInvoice->getAdvanceReceived();
$strCashBack	 = '';
if (count($promoArr) == 0 && $model->bkgInvoice->bkg_advance_amount == 0 && $model->bkg_status == 2)
{
	$strCashBack = 'Pay at least 15% of total amount and get 50% Cashback' . " *<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
}
$maxPaymentWithDiscount	 = round($model->bkgInvoice->bkg_total_amount) - $advance;
$payable				 = $model->bkgInvoice->bkg_total_amount;
$due					 = ($model->bkgInvoice->bkg_total_amount - $advance);
$conCharge				 = 0;
$minDiff				 = $model->getPaymentExpiryTimeinMinutes();

$model->bkgInvoice->bkg_due_amount	 = ($model->bkgInvoice->bkg_due_amount > 0) ? $model->bkgInvoice->bkg_due_amount : $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->bkg_advance_amount + round($model->bkgInvoice->bkg_refund_amount) - $model->bkgInvoice->bkg_credits_used - $model->bkgInvoice->bkg_vendor_collected;
$hash								 = Yii::app()->shortHash->hash($model->bkg_id);
$walletBalance						 = UserWallet::model()->getBalance(UserInfo::getUserId());
?>
<div class="col-xs-12">
	<div >
		<?
		if ($minDiff > 0)
		{
		if ($model->bkgInvoice->bkg_due_amount > 0)
		{
		?>
		<div class="row" id="payment">
			<?php
			$form								 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'payment-form1',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
								 
                    if($("#BookingInvoice_paymentType").val() == "0")
					{   
						processBooking();
                        return false;                         
					} 
														 
							if($("#chkele").val()=="0" && $("#BookingInvoice_paymentType").val() == "14"){
								alert("You need to check your elegiblity before pay through LazyPay");
								return false;
							}
							if($("#chkele").val()=="2" && $("#BookingInvoice_paymentType").val() == "14"){
								alert("Sorry you are not elegible to pay through LazyPay for now. Please try other payment method.");
								return false;
							}
							if(!hasError){
									$.ajax({
							"type":"POST",
							"dataType":"json",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl("booking/payment", ['hash' => Yii::app()->shortHash->hash($model->bkg_id), 'id' => $model->bkg_id, 'src' => 1, 'iscreditapplied' => ''])) . '"+$(\'#creditapplied\').val(),
							"data":form.serialize(),
							 "beforeSend": function(){
								ajaxindicatorstart("");
								},
								"complete": function(){
									ajaxindicatorstop();
								},
								"success":function(data1){
									if(data1.success){ 
										if(data1.url != "")
										{
											location.href=data1.url;
												return false;
											}
											if(data1.id > 0){                                   
												location.href="' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/summary')) . '?action=done&id="+data1.id+"&hash="+data1.hash;
											}
										}
										else{      
										if(data1.id > 0 && (data1.error === undefined || data1.error.length == 0)){   
										
											location.href="' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/paynow1')) . '/id/"+data1.id+"/hash/"+data1.hash+"/tinfo/fail";                                                                                                                
										}
											settings=form.data(\'settings\');
											data2 = data1.error;
											//console.log(data2);
											msg =JSON.stringify(data2);
											if(data2)
											{
												var x = window.matchMedia("(max-width: 700px)");
												if (x.matches) 
												{
													 var result = JSON.parse(msg);
													for (k in result) {
													   bootbox.alert({
															message: result[k],
															class: "",
															callback: function () {
															}
													   })
													   return false;
													}
												}
											}
											$.each (settings.attributes, function (i) {
											  $.fn.yiiactiveform.updateInput (settings.attributes[i], data2, form);
											});
											$.fn.yiiactiveform.updateSummary(form, data2);
										}
									},
								});
							}
						}'
				),
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class'		 => '', 'enctype'	 => 'multipart/form-data'
				),
			));
			/* @var $form TbActiveForm */
			?>
			<?= CHtml::errorSummary($model); ?>
			<?= CHtml::errorSummary($model->bkgInvoice); ?>

			<?= $form->hiddenField($model->bkgInvoice, 'optPaymentOptions', ['value' => 1]) ?>
			<?= $form->hiddenField($model->bkgInvoice, 'paymentType', ['value' => 3]) ?>
			<?= $form->hiddenField($model, 'hash', ['id' => 'hash5']); ?>
			<?= $form->hiddenField($model, 'bkg_id') ?>
			<?php echo $form->error($model, 'bkg_id'); ?>
			<?= $form->hiddenField($model->bkgInvoice, 'ebsOpt', ['value' => 1]) ?>
			<?= $form->hiddenField($model->bkgInvoice, 'payubolt', ['value' => 0]) ?>
			<? //= $form->hiddenField($model, 'bkg_status')  ?>
			<?= $form->hiddenField($model->bkgUserInfo, 'bkg_user_id', ['value' => $userid]) ?>
			<?= $form->hiddenField($model->bkgInvoice, 'isAdvPromoPaynow') ?>
			<?php
			$amountWithConvFee					 = round($model->bkgInvoice->bkg_due_amount);
			$isAdvDiscount						 = 0;
			if ($model->bkgInvoice->bkg_promo1_id != 0)
			{
				$promoModel = Promos::model()->getByCode($model->bkgInvoice->bkg_promo1_code);
				if ($promoModel->prm_activate_on == 1 && !$isredirct)
				{
					$amountWithConvFee	 = round($mol->bkgInvoice->bkg_due_amount);
					$isAdvDiscount		 = 1;
				}
			}
			?>
			<input type="hidden" name="iscreditapplied" id="iscreditapplied" value="0">
			<input type="hidden" name="creditapplied" id="creditapplied" value="<?= $model->bkgInvoice->bkg_credits_used ?>">
			<input type="hidden" name="isAdvDiscount" id="isAdvDiscount" value="<?= $isAdvDiscount ?>"> 
			<input type="hidden" id="step5" name="step" value="5">
			<input type="hidden" name="totAmount" id="totAmount" value="<?= $totAmount; ?>">
			<input type="hidden" name="isPayNowCredits" id="isPayNowCredits" value="0">
			<input type="hidden" class="maxAmount" name="max_amount" id="max_amount" value="<?= $maxPaymentWithDiscount; ?>">
			<input type="hidden" id="isWalletUsed" name="isWalletUsed" value="0"> 
			<input type="hidden" id="walletUsedAmt" name="walletUsedAmt" value="0" />
			<input type="hidden" id="refundCredits" value="0"/>
			<input type="hidden" id="bghash" name="bghash" value="<?= $hash ?>">
			<input type="hidden"  class="clsAdditionalParams" name="additionalParams" value='{"code":"","coins":0,"wallet":0}'/>


			<!-- payment widget start--->
			<div class="panel panel-default main_time border-greenline p0">
				<div class="panel-body p0 payment-option">

					<div class="col-xs-12 col-sm-3 p0 hidden-xs">
						<ul class="nav nav-tabs ">
							<li class="" id="lblmenu2" >
								<a data-toggle="tab" href="#menu11" class="optType opt1 " id="mobwallet"> Mobile Wallets
								</a>
							</li>

							<li  id="lbcredit" class="lbcredit active">
								<div id="indopt">
									<a class=" opt6 optType  active"  name="paymentTypeBtn" id="" data-toggle="tab" href="#home"> Credit Card / Debit Card 
									</a>
								</div>
								<div id="intopt" style="display: none;">
									<a class=" opt2 optType "  name="paymentTypeBtn" id="optl2" data-toggle="tab" href="#home"> Credit Card / Debit Card 

									</a> 
								</div>	
							</li>
							<li  id="lbllazy">
								<a data-toggle="tab" href="#menu12" name="paymentTypeBtn" class="optType opt14 "   > 
									Book Now. Pay Later 																			 
								</a>
							</li>




							<li id="lbl4">
								<a data-toggle="tab" href="#menu10" name="paymentTypeBtn" class="optType  opt4" id="optl4" > 
									<img class="hide" src="/images/netbanking-logo.png" style="height: 28px;margin-top: -5px" alt='Net Banking'>
									Net Banking
								</a>
							</li>
							<?
							if ($model->bkg_booking_type == 1 && $model->bkg_flexxi_type == 1 && !$isredirct)
							//if ($model->bkg_booking_type == 1 && !$isredirct)
							{
							?>
							<li id="cashpay">
								<a data-toggle="tab" href="#payl" name="paymentTypeBtn" class="optType  opt0" id="optl0" > 
									Cash On Delivery
								</a>
							</li>
							<? } ?>
						</ul>
					</div>
					<div class="hidden-lg hidden-md hidden-sm payment-panel">
						<ul class="nav nav-tabs ">
							<li id="lbcredit" class="active col-xs-6 lbcredit">
								<div id="indopt">
									<a class=" opt6 optType  "  name="paymentTypeBtn" id="" data-toggle="tab" href="#home"> Credit / Debit Card 
									</a>
								</div>
								<div id="intopt" style="display: none;">
									<a class=" opt2 optType "  name="paymentTypeBtn" id="optl2" data-toggle="tab" href="#home"> Credit / Debit Card 

									</a> 
								</div>	
							</li>
							<li class="col-xs-6" id="lblmenu2">
								<a data-toggle="tab" href="#menu11" class="optType opt1" id="mobwallet"> Mobile Wallets
								</a>
							</li>

							<li  id="lbllazy" class="col-xs-6">
								<a data-toggle="tab" href="#menu12" name="paymentTypeBtn" class="optType opt14 "   > 
									Book Now. Pay Later 																			 
								</a>
							</li>




							<li id="lbl4" class="col-xs-6">
								<a data-toggle="tab" href="#menu10" name="paymentTypeBtn" class="optType  opt4" id="optl4" > 
									<img class="hide" src="/images/netbanking-logo.png" style="height: 28px;margin-top: -5px" alt='Net Banking'>
									Net Banking
								</a>
							</li>
							<?
							if ($model->bkg_booking_type == 1 && $model->bkg_flexxi_type == 1 && !$isredirct)
							//if ($model->bkg_booking_type == 1 && !$isredirct)
							{
							?>
							<li id="cashpay" class="col-xs-6">
								<a data-toggle="tab" href="#payl" name="paymentTypeBtn" class="optType  opt0" id="optl0" > 
									Cash On Delivery
								</a>
							</li>
							<? } ?>
						</ul>
					</div>
					<div class="tab-content col-xs-12 col-sm-9 p20">
						<div id="home" class="tab-pane fade in active">
							<div class="row hide">
								<div class="col-xs-12">
									<h4 class="mt0">
										Credit Card / Debit Card
									</h4>
								</div>
							</div>
						</div>
						<div id="menu10" class="tab-pane fade ">
							<div class="row hide active">
								<div class="col-xs-12">
									<h4 class="mt0">
										Net Banking
									</h4>
								</div>
							</div>
						</div>
						<div id="menu11" class="tab-pane fade  ">
							<div class="row ">
								<div class="col-xs-12 hide">
									<h4 class="mt0">
										Mobile Wallets
									</h4>
								</div>
								<div class="col-xs-12 text-left mb10">
									<div class="row">
										<label class="col-md-3 opt1 optType"  id="lbl1">
											<input type="radio" name="paymentTypeBtn" class="optType opt1" id="optl1"> 
											<img src="/images/paytm_logo.png" style="height: 20px;margin-top: -5px" alt='Paytm'>
										</label>
										<? /* ?><label class="col-md-4 opt12 optType" id="lbl12">
											<input type="radio" name="paymentTypeBtn" class="optType opt12" id="optl12">
											<img src="/images/frc-logo.png" style="height: 23px;" alt='FreeCharge'>
										</label><? */ ?>
										<label class="col-md-4 opt6 optType " id="lbl6">
											<input type="radio" name="paymentTypeBtn" class="optType opt6 optl6" id="">
											<span class="hide"> 
												<img src="/images/pay-visa.png" class='' style="height: 20px;margin-top: -5px" alt='VISA'> <img class='hide' src="/images/pay-mastercard.png" style="height: 20px;margin-top: -5px" alt='MASTER CARD'>
											</span>  
											<img src="/images/payumoney-logo.png" style="height: 23px;margin-top: -8px" alt='PayUMoney'>
										</label>
										<label class="col-md-5 opt10 optType" id="lbl10">
											<input type="radio" name="paymentTypeBtn" class="optType opt10" id="optl10"> 
											<img src="/images/mobikwik-logo-new.png" style="height: 23px;margin-top: -8px" alt='Mobikwik'><br><span > (Get 10% SuperCash <b><a href="javascript:void(0);" onclick="openmtns()" >T&C apply</a>*</b>)</span>
										</label>


									</div>
								</div>
							</div>
						</div>
						<div id="menu12" class="tab-pane fade ">
							<div class="row">
								<div class="col-xs-12">
									<div class="row">
										<div class="col-md-6">
											<label class="opt14 optType active" id="lbl14">
												<input type="radio" name="paymentTypeBtn" class="optType opt14 rad active" id="optl14"> 
												<img src="/images/lazypay-logo.png"  style="height: 20px;margin-top: -5px" alt='LAZYPAY'>
												<b class="h4">Credit</b> 
											</label>
										</div>
										<div class="col-md-6">
											<label class="opt15 optType" id="lbl15">
												<input type="radio" name="paymentTypeBtn" class="optType opt15 rad" id="optl15" > 
												<img src="/images/epaylogo.png"  style="height: 30px;margin-top: -10px" alt='EPayLater'>
												<b class="h4">Credit</b> 
											</label>
										</div>
									</div>
								</div>

								<div class="col-xs-12" id="lazypaytext">
									<b>Use LazyPay to Book Now & Pay Later</b><br>																	 
									<i class="fa fa-angle-double-right text-danger pl10"></i> Zero Cost Credit (0% interest)<br>	
									<i class="fa fa-angle-double-right text-danger pl10"></i> No sign-up needed<br>	
									<i class="fa fa-angle-double-right text-danger pl10"></i> Place your order with just an OTP<br>	
									<i class="fa fa-angle-double-right text-danger pl10"></i> Pay LazyPay within due date. Lazypay will remind you 
								</div>
							</div>
						</div>
						<div id="payl" class="tab-pane fade ">

						</div>
						<!-- credit card section-->
						<div>
							<?
							$this->renderPartial('billingdetails', ['model' => $model, 'form' => $form,]);
							?>
							<div class="row">
								<div class="col-xs-12 mt15">
									<div class="row" id="checkEleBlock">
										<div class="col-xs-12  ">
											<button id="lpbtn" class="btn btn-primary mb5" type="button" onclick="checkeligiblity()">Click to get approved for LazyPay</button>
										</div>
										<div class="col-md-12 mb10">
											<span class="lazyPayEleText text-success h5" id="lazyPayEleTextSucc" ></span>
											<span class="lazyPayEleText text-danger h5" id="lazyPayEleTextErr"  ></span>
										</div>
										<input type="hidden" id="chkele" value="0"> 
									</div>
								</div>
								<div class="col-xs-12">
									<div id="btree" style="display: none" >
										<?= CHtml::errorSummary($ccmodel); ?>
										<div class="col-xs-12 p0">
											<h4>Credit Card Information</h4>
											<div class="row form-group">
												<div class="col-xs-12 col-md-6">
													<label>Name on Card</label>
													<div class="">
														<?php echo $form->textField($ccmodel, 'creditCard_name', array('class' => 'form-control')); ?>
														<span class = "has-error"><?php echo $form->error($ccmodel, 'creditCard_name'); ?></span>
													</div>
												</div>
												<div class="col-xs-12 col-md-6">
													<label>Card Number</label>
													<div class="controls">
														<?php echo $form->numberField($ccmodel, 'creditCard_number', array('class' => 'form-control')); ?>
														<span class = "has-error"><?php echo $form->error($ccmodel, 'creditCard_number'); ?></span>
													</div>
												</div>
											</div>
											<div class="row form-group">
												<div class="col-xs-12 col-md-5">
													<label>Security Code (CVV)</label>
													<div class="controls">
														<?php echo $form->passwordField($ccmodel, 'creditCard_cvv', array('class' => 'form-control', 'autocomplete' => 'off')); ?>
														<span class = "has-error"><?php echo $form->error($ccmodel, 'creditCard_cvv'); ?></span>
													</div>
												</div>
												<div class="col-xs-12 col-md-7"><label>Expiration Date (MM/YYYY)</label>
													<div class="row">
														<div class="col-xs-5">
															<?=
															$form->dropDownListGroup(
																	$ccmodel, 'creditCard_month', array('label'				 => '', 'wrapperHtmlOptions' => array('class' => '',),
																'widgetOptions'		 => array('data'			 => $ccMonth, 'htmlOptions'	 => array('style' => 'padding:6px',),
															)));
															?>
														</div>
														<div class="col-xs-1 pl0 h3 mt5">/</div>
														<div class="col-xs-6 pl0">
															<?php
															echo $form->dropDownListGroup(
																	$ccmodel, 'creditCard_year', array('label'				 => '',
																'wrapperHtmlOptions' => array('class' => '',),
																'widgetOptions'		 => array('data'			 => $ccYear, 'htmlOptions'	 => array(),
																))
															);
															?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="showcash form-control col-sm-6" style="display: none"><?= $model->bkgInvoice->bkg_due_amount ?></div>
									<div id = "ppayment" style="display: block">


										<?php
										$readOnlyPartialPay	 = [];
										$readOnlyPayTrainman = false;
										if ($model->bkg_agent_id == 655)
										{
											$hidePayinfull		 = "none";
											$readOnlyPartialPay	 = ["readOnly" => "readOnly"];
											$readOnlyPayTrainman = true;
										}
										else
										{
											$hidePayinfull = "block";
										}
										?>  

										<span class="mt10" id="pwords">Please enter amount you want to pay</span>
										<div class="row">
											<div class="col-sm-6">
														<?= $form->numberFieldGroup($model->bkgInvoice, 'partialPayment', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control clsPartialPayment', 'placeholder' => "Partial Payment", 'min' => $orderAmount, 'max' => $maxPaymentWithDiscount] + $readOnlyPartialPay))) ?>
											</div>
											<div class="col-xs-6">

												<span style="display: <?= $hidePayinfull ?>">  
													<div class="form-group hide"><div class="checkbox">
															<label class="mb0" for="inlineCheckbox1"><input class="" type="checkbox" id="inlineCheckbox1" value="option1">Pay in full </label>
														</div></div>
												</span>

											</div>
											<div class="col-xs-12 col-md-6" style="display: none" id="divDollar">
												<b><span id="amtDollar"></span></b> will be deducted from your Card
											</div>
										</div>
										<span class="mt0" id="pmaxpay" style="display: none">Maximum amount payable through paytm (per day) is <i class="fa fa-inr"></i>10,000</span>
									</div>

								</div>

								<div class="col-xs-12" >
									<input type="hidden" name="confBtns" id="confPayNow1" value="p1">
									<label class="">
										<?
										if (in_array($model->bkg_flexxi_type, [1, 2]))
										{
										?>
										<?= $form->checkboxGroup($model->bkgTrail, 'bkg_tnc', ['label' => 'I agree to the Gozo <a href="javascript:void(0);" onclick="opentns()" >terms and conditions</a> & <a href="javascript:void(0);" onclick="openflexxiterms()" >Flexxi terms and conditions</a>']) ?>
										<?
										}
										else
										{
										?>
										<?= $form->checkboxGroup($model->bkgTrail, 'bkg_tnc', ['class' => 'test', 'label' => 'I agree to the Gozo <a href="javascript:void(0);" onclick="opentns()" >terms and conditions</a>']) ?>
										<? } ?>   
									</label> 
									<div id="error_div1" style="display: none" class="alert alert-block alert-danger"></div>
								</div>

								<div class="col-xs-12 text-right  pr20">
									<input type="submit" value="Proceed" class="btn proceed-new-btn text-uppercase white-color" id="proceedPayNow">
									<input type="button" value="Proceed" class="btn proceed-new-btn text-uppercase white-color" style="display: none" id="payubtn" >
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- payment widget end--->

			<?php $this->endWidget(); ?>
		</div>
		<?
		}
		else
		if ($model->bkgInvoice->bkg_due_amount == 0 && $model->bkgInvoice->bkg_advance_amount > 0)
		{
		$strmsg1 = 'Full fare is paid in advance. No due amount left to be paid.';
		}
		}
		else
		{
		$strmsg1 = 'Your Payment link is expired.';
		}
		if ($strmsg1 != '')
		{
		?>
		<div class="row">
			<div class="col-xs-12">
				<div class="h4 text-center pt10">
					<?= $strmsg1 ?>
				</div>
			</div>
		</div><?
		}
		?>
	</div>
</div>
<input id="minipay" class="miniPay" type="hidden" value="<?= $model->bkgInvoice->calculateMinPayment() ?>">
<input id="dueAmountWithoutCOD" type="hidden" value="<?= $model->bkgInvoice->bkg_due_amount; ?>">
<!--<input id="minipay" type="text" value="<?= $model->bkgInvoice->calculateMinPayment() ?>">
<input id="dueAmountWithoutCOD" type="text" value="<?= $model->bkgInvoice->bkg_due_amount; ?>">-->
<div id="mobikwikTnC" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header p10">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;  </button>
				<h6 class="modal-title" id="myModalLabel"><b>Mobikwik Cashback</b></h6>
			</div>
			<div class="modal-body" style="font-size: 0.95em;">
				<ul style="line-height: 1.2em">   
					<li class="pb5">
						Get 10% SuperCash on the amount paid using Mobikwik. </li>
					<li  class="pb5">
						Maximum SuperCash that can be availed shall be Rs.200. </li>
					<li class="pb5">
						Customer can avail this offer once during the offer period. </li>
					<li class="pb5">This offer is governed by terms and conditions laid out by Mobikwik. Gozocabs shall not be responsible for applicability of such Cashback. Any dispute or claims, pertaining to such Cashback shall have to be taken up with Mobikwik only.
					</li>
					<li class="pb5">This offer is over and above any offer issued by Gozocabs. Any Offer, Discount or Benefit offered by Gozocabs are independent of any offers made by Mobikwik.
					</li>
					<li>
						Offer period: 5th June, 2018 to 30th June, 2018
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    $netAmount = '<?= $maxPaymentWithDiscount ?>' | 0;
    var agent_id = '<?= $model->bkg_agent_id ?>';
    $readOnlyPayTrainman = (agent_id == '655');
    var bid = '<?= $model->bkg_id ?>';
    function IsPopupBlocker() {
        var oWin = window.open("", "testpopupblocker", "width=100,height=50,top=5000,left=5000");
        if (oWin == null || typeof (oWin) == "undefined") {
            return true;
        } else {
            oWin.close();
            return false;
        }
    }

    $(document).ready(function ()
    {
        $defPayuBolt = '<?= Yii::app()->params['enablePayuBolt'] ?>';
        $enablePayuBolt = $defPayuBolt;
        if (IsPopupBlocker()) {
            $enablePayuBolt = 0;
        }


        $('.stepper-arrow').remove();
        $('#pmaxpay').hide();
        //  $("#inlineCheckbox1").attr('checked', 'checked');
        calculatePayable();
        $('#creditpoints').click(function ()
        {
            useCredit();
        });
        $('#BookingInvoice_partialPayment').change(function ()
        {
            $('#creditpoints').removeAttr('checked');
        });
        showCountryCard();
    });
    $('#cardChk1').change(function ()
    {
        if ($("#cardChk1").is(':checked'))
        {
            optIndCCClick();
        }
    });
    $('#cardChk2').change(function ()
    {
        if ($("#cardChk2").is(':checked'))
        {
            // $('#optl9').prop("checked", true);
            showIntOptions();
            // opt9Click();



        }
    });


    $('#lblmenu2').click(function ()
    {
        $('#cardAsk').hide();
        $('.optType').removeClass('active');

        if ($('#BookingInvoice_paymentType').val() != 3 &&
                $('#BookingInvoice_paymentType').val() != 12 &&
                $('#BookingInvoice_paymentType').val() != 10
                // && $('#<? //= CHtml::activeId($model, "paymentType")                                                                                                  ?>').val() != 6
                )
        {
            $('#optl1').prop("checked", true);
            opt1Click();

        }

        cartInd();
        $('#mobwallet').addClass('active');

    });

    $('.lbcredit').click(function ()
    {
        showCountryCard();
    });

    function showCountryCard() {

        $('#cardAsk').show();
        checkDefaultCountry();
        if ($("#cardChk1").is(':checked'))
        {
            optIndCCClick();
            cartInd();
        }

        if ($("#cardChk2").is(':checked'))
        {
            showIntOptions();
            cartInt();
        }
        $('#menu11').removeClass('active');
    }

    function checkDefaultCountry() {

        if ('<?= $model->bkgUserInfo->bkg_bill_country ?>' == '' || '<?= $model->bkgUserInfo->bkg_bill_country ?>' == 'IN') {
            $('#cardChk1').prop('checked', true);
            cartInd();
        } else {
            $('#cardChk2').prop('checked', true);
            cartInt();
            showIntOptions();
        }
    }
    function optIndCCClick()
    {
        $('.optl6').prop("checked", true);
        opt6Click();
        showIndOptions();
        $('#indopt').show();
        $('#intopt').hide();
    }
    $('.opt1').click(function ()
    {
        opt1Click();
    });
    $('.opt2').click(function ()
    {
        opt2Click();
    });
    $('.opt9').click(function ()
    {
        opt9Click();
    });
    $('.opt11').click(function ()
    {
        opt11Click();
    });

    $('.opt3').click(function ()
    {
        opt3Click();
    });
    $('.opt4').click(function ()
    {
        opt4Click();
    });
    $('.opt6').click(function ()
    {
        opt6Click();
    });
    $('.opt14').click(function ()
    {
        opt14Click();
    });
    $('.opt15').click(function ()
    {
        opt15Click();
    });

    $('.opt0').click(function ()
    {
        opt0Click();
    });
    function opt0Click()
    {

        $('#cardAsk').hide();
        $('#btree').hide();
        $('#divDollar').hide();
        $('#BookingInvoice_paymentType').val('0');
        $('.optType').removeClass('active');
        $('.opt1').addClass('active');
        $('#creditpoints').removeAttr('checked');
        useCredit();
        calculatePayable();
        $('.billinfo').hide();
        $('#ppayment').hide();
        $('.showcash').show();
    }

    function opt1Click()
    {
        $('#optl1').prop("checked", "checked");
        $('#cardAsk').hide();
        $('#btree').hide();
        $('#divDollar').hide();
        $('#BookingInvoice_paymentType').val('3');
        $('.optType').removeClass('active');
        $('.opt1').addClass('active');
        $('#creditpoints').removeAttr('checked');
        useCredit();
        calculatePayable();

    }
    function opt11Click()
    {
        $('#cardAsk').show();
        $('#btree').hide();
        $('#divDollar').hide();
        $('#BookingInvoice_paymentType').val('11');
        $('.optType').removeClass('active');
        $('.opt11').addClass('active');
        $('#creditpoints').removeAttr('checked');
        useCredit();
        calculatePayable();
    }

    function opt2Click()
    {
        $('#cardAsk').show();
        $('#btree').hide();
        $('#BookingInvoice_paymentType').val('4');
        $('#BookingInvoice_ebsOpt').val('1');
        $('.optType').removeClass('active');
        $('.opt2').addClass('active');
        $('#creditpoints').removeAttr('checked');
        $('#divDollar').hide();
        useCredit();
        calculatePayable();
    }
    function opt9Click()
    {
        $('#cardAsk').show();
        $('#btree').show();
        $('#BookingInvoice_paymentType').val('9');
        if ($('#<?= CHtml::activeId($ccmodel, "creditCard_name") ?>').val() == '')
        {
            $('#<?= CHtml::activeId($ccmodel, "creditCard_name") ?>').val('<?= $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?>');
        }
        showDollar();
        $('.optType').removeClass('active');
        $('.opt9').addClass('active');
        $('#creditpoints').removeAttr('checked');
        useCredit();
        calculatePayable();
    }
    function opt3Click()
    {

        $('#cardAsk').hide();
        $('#btree').hide();
        $('#BookingInvoice_paymentType').val('4');
        $('#BookingInvoice_ebsOpt').val('2');
        $('.optType').removeClass('active');
        $('.opt3').addClass('active');
        $('#creditpoints').removeAttr('checked');
        $('#divDollar').hide();
        useCredit();
        calculatePayable();
    }

    function opt4Click()
    {
        $('#cardAsk').hide();
        $('#btree').hide();
        $('#BookingInvoice_paymentType').val('6');
        //$('#BookingInvoice_ebsOpt').val('3');
        $('.optType').removeClass('active');
        $('.opt4').addClass('active');
        $('#creditpoints').removeAttr('checked');
        $('#divDollar').hide();
        useCredit();
        calculatePayable();
        if ($enablePayuBolt == 1) {
            $("#BookingInvoice_payubolt").val(1);
            $('#payubtn').show();
            $('#proceedPayNow').hide();
        }
    }
    function opt6Click()
    {
        // $('#cardAsk').hide();

        $('#btree').hide();
        $('#BookingInvoice_paymentType').val('6');
        $('.optType').removeClass('active');
        $('.opt6').addClass('active');
        $('#creditpoints').removeAttr('checked');
        $('#divDollar').hide();
        useCredit();
        calculatePayable();
        if ($enablePayuBolt == 1) {
            $("#BookingInvoice_payubolt").val(1);
            $('#payubtn').show();
            $('#proceedPayNow').hide();
        }

    }

    $('.opt10').click(function ()
    {
        $('#cardAsk').hide();
        $('#btree').hide();
        $('#BookingInvoice_paymentType').val('10');
        $('#divDollar').hide();
        $('.optType').removeClass('active');
        $('.opt10').addClass('active');
        $('#creditpoints').removeAttr('checked');
        useCredit();
        calculatePayable();
    });



    function opt14Click()
    {
        $('#optl14').prop("checked", "checked");
        $('#cardAsk').hide();
        $('#btree').hide();
        $('#BookingInvoice_paymentType').val('14');
        $('#divDollar').hide();
        $('.optType').removeClass('active');
        $('.opt14').addClass('active');
        $('#creditpoints').removeAttr('checked');
        useCredit();
        calculatePayable();
        $("#checkEleBlock").show();
        $('.billinfo').hide();
        $('#contact_info').show();
        $('#proceedPayNow').addClass('disabled');
        $('#lazypaytext').show();
    }

    function opt15Click()
    {
        $('#optl15').prop("checked", "checked");
        $('#cardAsk').hide();
        $('#btree').hide();
        $('#BookingInvoice_paymentType').val('15');
        $('#divDollar').hide();
        $('.optType').removeClass('active');
        $('.opt15').addClass('active');

        $('#creditpoints').removeAttr('checked');
        useCredit();
        calculatePayable();
        $('.billinfo').hide();
        $('#contact_info').show();
    }



    $("#inlineCheckbox1").change(function ()
    {

        if ($("#inlineCheckbox1").is(':checked'))
        {
            //var dueAmt = $('.bkgamtdetails111').html();
            var dueAmt = $('.payBoxDueAmount').text();
            $('#BookingInvoice_partialPayment').attr("readonly", "readonly");
            if (dueAmt == '')
            {
                $('#BookingInvoice_partialPayment').val($('#max_amount').val());
            } else {
                $('#BookingInvoice_partialPayment').val(dueAmt);
            }

            $('#BookingInvoice_optPaymentOptions').val('1');
        } else
        {
            $('#BookingInvoice_partialPayment').val($('#minipay').val());
            $("#pwords").css('visibility', 'visible');

            if (!$readOnlyPayTrainman)
            {

                $('#BookingInvoice_partialPayment').removeAttr("readonly");
            }
            $('#BookingInvoice_optPaymentOptions').val('2');
            $('#BookingInvoice_partialPayment').val($("#BookingInvoice_partialPayment").attr('min'));
        }
        if ($('#optl9').is(':checked'))
        {
            showDollar();
        }

    });
    $('#BookingInvoice_partialPayment').on('change', function ()
    {
        showDollar();
    });

    function showDollar()
    {
        $('#divDollar').show();
        $pamount = $('#BookingInvoice_partialPayment').val();
        $damount = ($pamount / <?= Yii::app()->params['dollarToRupeeRate'] ?>).toFixed(2);
        $('#amtDollar').html('$' + $damount);
    }

    function useCredit()
    {

    }

    function changeAmountWord()
    {
        var amount = $('#totAmount').val();
        $href = '<?= Yii::app()->createUrl('booking/amountinwords') ?>';
        jQuery.ajax({type: 'GET', url: $href, "data": {"amount": amount},
            success: function (data)
            {
                $('#amtinword').text(data);
            }
        });
    }
    $('#Booking_bkg_bill_country111').on('change', function ()
    {

        if (this.value == 'IN')
        {
            showIndOptions();
        } else
        {
            showIntOptions();
        }
        $('.lbcredit').addClass('active');
        $('#cardAsk').show();
    });
    function showIndOptions()
    {
        $('.optl6').prop("checked", true);
        opt6Click();
    }
    function showIndOptions1()
    {

        $('.optType').show();
        if ($('.opt9').hasClass('active'))
        {
            if ('<?= $creditCardType ?>' == '1')
            {
                $('.opt2').addClass('active');
            }
            if ('<?= $creditCardType ?>' == '2')
            {
                $('.opt11').addClass('active');
            }
            $('.opt9').removeClass('active');
            $('#BookingInvoice_paymentType').val('4');
            $('#btree').hide();
        }
        $('#<?= CHtml::activeId($ccmodel, "creditCard_name") ?>').val('');
        $('#divDollar').hide();
        $('.opt9').hide();
        $('.lbcredit').addClass('active');
        $('#indopt').show();
        $('#intopt').hide();
    }
    function showIntOptions1()
    {

        // $('.optType').hide();
        $('.opt9').show();
        $('.opt9').addClass('active');
        //$('.opt1').removeClass('active');
        if ('<?= $creditCardType ?>' == '1')
        {
            $('.opt2').removeClass('active');
        }
        if ('<?= $creditCardType ?>' == '2')
        {
            $('.opt11').removeClass('active');
        }
        // $('.opt3').removeClass('active');
        // $('.opt4').removeClass('active');
        $('#BookingInvoice_paymentType').val('9');


        $('#btree').show();

        showDollar();
        if ($('#<?= CHtml::activeId($ccmodel, "creditCard_name") ?>').val() == '')
        {
            $('#<?= CHtml::activeId($ccmodel, "creditCard_name") ?>').val('<?= $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?>');
        }
        $('.opt9').show();
        calculatePayable();
        $('.lbcredit').addClass('active');
        $('#intopt').show();
        $('#indopt').hide();
    }
    function showIntOptions()
    {
        $('#optl2').prop("checked", true);
        opt2Click();

        $('#intopt').show();
        $('#indopt').hide();
    }

    $('#BookingInvoice_partialPayment').on('change', function ()
    {
        if ($('.opt9').hasClass('active'))
        {
            showDollar();
        } else
        {
            $('#divDollar').hide();
        }
    });
    $('#BookingInvoice_partialPayment').on('blur', function ()
    {
        if ($('.opt9').hasClass('active'))
        {
            showDollar();
        } else
        {
            $('#divDollar').hide();
        }
    });

    function calculatePayable()
    {
        // var maxpay = parseInt(Math.round($netAmount * 0.95));

        $("#checkEleBlock").hide();
        $('.billinfo').show();
        $('#contact_info').show();
        $('#proceedPayNow').removeClass('disabled');
        $('#ppayment').show();
        $('.showcash').hide();
        $('#lazypaytext').hide();
        $("#BookingInvoice_payubolt").val(0);
        $('#payubtn').hide();
        $('#proceedPayNow').show();
        var maxpay = parseInt(Math.round($netAmount * 1));
        var minpay = $("#BookingInvoice_partialPayment").attr('min'); //parseInt(Math.round($netAmount * 0.15));
        if ($("#BookingInvoice_paymentType").val() == 3 && maxpay > 10000)
        {
            maxpay = 10000;
            $('#pmaxpay').show();
        } else
        {
            $('#pmaxpay').hide();
        }

        $('#max_amount').val(maxpay);

        $("#BookingInvoice_partialPayment").attr('max', maxpay);
        $("#BookingInvoice_partialPayment").attr('min', minpay);

        $("#BookingInvoice_partialPayment").val(minpay);
        if ($("#inlineCheckbox1").is(':checked'))
        {
            $('#BookingInvoice_partialPayment').attr("readonly", "readonly");
            $('#BookingInvoice_partialPayment').val($('#max_amount').val()).change();
            $("#pwords").css('visibility', 'hidden');
            $('#BookingInvoice_optPaymentOptions').val('1');
        } else
        {
            $("#pwords").css('visibility', 'visible');

            if (!$readOnlyPayTrainman)
            {

                $('#BookingInvoice_partialPayment').removeAttr("readonly");
            }
            $('#BookingInvoice_optPaymentOptions').val('2');
            $('#BookingInvoice_partialPayment').val(minpay).change();
        }
        $("#max_amount_paytm_discount").text(maxpay);
    }
    $('form').on('focus', 'input[type=number]', function (e)
    {
        $(this).on('mousewheel.disableScroll', function (e)
        {
            e.preventDefault();
        });
        $(this).on("keydown", function (event)
        {
            if (event.keyCode === 38 || event.keyCode === 40)
            {
                event.preventDefault();
            }
        });
    });
    $('form').on('blur', 'input[type=number]', function (e)
    {
        $(this).off('mousewheel.disableScroll');
        $(this).off('keydown');
    });
    $('#BookingUser_bkg_bill_contact').change(function ()
    {
        $('.lazyPayEleText').hide();
        $('.lazyPayEleText').text('');
        $("#chkele").val("0");
        if ($("#BookingInvoice_paymentType").val() == "14")
        {
            $('#proceedPayNow').addClass('disabled');
        } else
        {
            $('#proceedPayNow').removeClass('disabled');
        }
    });
    function checkeligiblity()
    {

        var errno = 0;
        if ($('#BookingUser_bkg_bill_contact').val() == '')
        {
            $('#BookingUser_bkg_bill_contact_em_').show();
            $('#BookingUser_bkg_bill_contact_em_').html('Contact cannot be blank.');
            errno++;
        }
        if ($('#BookingUser_bkg_bill_email').val() == '')
        {
            $('#BookingUser_bkg_bill_email_em_').show();
            $('#BookingUser_bkg_bill_email_em_').html('Billing email cannot be blank.');
            errno++;
        }
        if ($('#BookingInvoice_partialPayment').val() == '')
        {
            $('#BookingInvoice_partialPayment_em_').show();
            $('#BookingInvoice_partialPayment_em_').html('Billing amount cannot be blank.');
            errno++;
        }
        if (errno == 0)
        {
            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('lazypay/checkeligiblitycall')) ?>",
                "data": {'phone': $('#BookingUser_bkg_bill_contact').val(),
                    'email': $('#BookingUser_bkg_bill_email').val(),
                    'amount': $('#BookingInvoice_partialPayment').val(),
                    'bkgid': "" + $('#Booking_bkg_id').val()
                },
                "beforeSend": function ()
                {
                    ajaxindicatorstart("");
                },
                "complete": function ()
                {
                    ajaxindicatorstop();
                },
                "success": function (data2)
                {
                    $('.lazyPayEleText').hide();
                    $('.lazyPayEleText').text('');

                    if (data2.eligibility)
                    {
                        $('#lazyPayEleTextSucc').show();
                        $('#lazyPayEleTextSucc').text("You are now approved for LazyPay.");
                        $('#chkele').val('1').change();
                        $('#proceedPayNow').removeClass('disabled');
                    } else
                    {
                        $('#lazyPayEleTextErr').show();
                        $('#lazyPayEleTextErr').text("Sorry you were not approved for LazyPay. You can try ePayLater or any other payment method.");
                        $('#chkele').val('2').change();
                        $('#proceedPayNow').addClass('disabled');
                        //                        $('#lbl14').hide();
                    }
                }
            });
        }
    }

    function getAdvPromo(id, hash, prm_id)
    {
        ajaxAdvPromo('apply', id, hash, prm_id);
    }

    $("input[name='AdvPromoRadio']").on('click', function ()
    {
        calculatePayable();
    });


    function ajaxPayNow(url)
    {

        if (!$isRunningAjax)
        {
            var id = '<?= $model->bkg_id ?>';
            var hash = '<?= Yii::app()->shortHash->hash($model->bkg_id) ?>';
            var creditsused = $('#creditapplied').val();
            $.ajax({
                "type": "GET",
                "url": url,
                "dataType": "html",
                data: {'src': 1, 'id': id, 'hash': hash, 'iscreditapplied': creditsused},
                "beforeSend": function ()
                {
                    ajaxindicatorstart("");
                    $isRunningAjax = true;
                },
                "complete": function ()
                {
                    ajaxindicatorstop();
                    $isRunningAjax = false;
                },
                success: function (data)
                {
                    $isRunningAjax = false;
                    $('#paymentdiv').html(data);
                    //   $('#bookingDetPayNow').hide();
                    var creditsApplied = $('#creditapplied').val();
                    if (creditsApplied > 0)
                    {
                        $('#isPayNowCredits').val(creditsApplied);
                    }
                    $("#proceedPayNow").on("click", function (event)
                    {
                        if ($('.isPickupAdrsCls').val() != null && $('.isPickupAdrsCls').val() != undefined) {
                            if ($('.isPickupAdrsCls').val() != '1') {
                                alert("Pickup and drop address is mandatory. If already selected than please press save address button.");
                                event.preventDefault();
                            }
                        }

                        if ($('#BookingTrail_bkg_tnc').is(':checked'))
                        {
                            $('#error_div1').hide();
                            $('#error_div1').html('');
                        } else
                        {
                            $('#error_div1').show();
                            $('#error_div1').html('Please check Terms and Conditions before proceed.');
                            event.preventDefault();
                        }

                    });
                },
                "error": function (error)
                {
                    alert(error);
                    $isRunningAjax = false;
                }
            });
        }
    }

    function processBooking()
    {
        var checkedBtns = "c1";

        $.ajax({
            "type": "GET",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/finalbook')) ?>/ctype/" + checkedBtns,
            "data": $("#payment-form1").serialize(),
            "beforeSend": function ()
            {
                ajaxindicatorstart("");
            },
            "complete": function ()
            {
                ajaxindicatorstop();
            },
            "success": function (data2)
            {
                if (data2.success)
                {
                    location.href = data2.url;
                } else
                {
                    var errors = data2.errors;

                    var txt = "<h5>Please fix the following input errors:</h5><ul style='list-style:none'>";
                    $.each(errors, function (key, value)
                    {
                        txt += "<li>" + value + "</li>";
                    });
                    txt += "</li>";
                    $("#error_div1").show();
                    $("#error_div1").html(txt);
                }
            }
        });
    }
</script>
<?
if (Yii::app()->params['enablePayuBolt'] == 1)
{
?>
<script id="bolt" src="<?= Yii::app()->payu->boltjsSrc ?>" bolt-color="1a4ea2" 
		bolt-logo="http://gozocabs.com/images/1024_1024_new.png">
</script>
<? } ?>
<script type="text/javascript">
    function launchBOLT()
    {
        $.ajax({
            "type": "POST",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/payment')); ?>",
            "data": $("#payment-form1").serialize(),
            "success": function (data2) {

                if (data2.success) {
                    if (data2.onlywallet) {
                     
                        location.href = data2.url;
                    } else {

                        launchBOLT1(data2);
                    }
                }
            }
        });
    }
    function launchBOLT1(data3)
    {
        $atxnid = data3.txnid;
        bolt.launch({
            key: data3.key,
            txnid: $atxnid,
            hash: data3.hash,
            amount: data3.amount,
            firstname: data3.firstname,
            email: data3.email,
            phone: data3.phone,
            productinfo: data3.productinfo,
            surl: data3.surl,
            furl: data3.furl,
            mode: 'dropout'
        }, {responseHandler: function (BOLT) {
                if (BOLT.response.txnStatus == 'CANCEL')
                {
                    BOLT.response.txnid = $atxnid;
                }
                $.ajax({
                    "type": "POST",
                    "dataType": "json",
                    "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('payment/response')); ?>",
                    "data": {'response': BOLT.response, 'ptpid': 6, 'bolt': 1},
                    "success": function (data4) {
                        alert(data4.message);
                    }
                });

            },
            catchException: function (BOLT) {
                alert(BOLT.message);
            }
        });
    }
    $("#proceedPayNow").on("click", function (event)
    {

        if ($('.isPickupAdrsCls').val() != null && $('.isPickupAdrsCls').val() != undefined) {
            if ($('.isPickupAdrsCls').val() != '1') {
                alert("Pickup and drop address is mandatory. If already selected than please press save address button.");
			$( ".brt_location_0" ).focus();
                event.preventDefault();
            }
        }

        if ($('#BookingTrail_bkg_tnc').is(':checked'))
        {
            $('#error_div1').hide();
            $('#error_div1').html('');
        } else
        {
            $('#error_div1').show();
            $('#error_div1').html('Please check Terms and Conditions before proceed.');
            event.preventDefault();
        }
    });
    $("#payubtn").on("click", function (event)
    {
		if($('.isPickupAdrsCls').val()!=null && $('.isPickupAdrsCls').val()!=undefined){
		    if($('.isPickupAdrsCls').val() != '1'){
			alert("Pickup and drop address is mandatory. If already selected than please press save address button.");
			$( ".brt_location_0" ).focus();
			event.preventDefault(); 
			return false;
		    }
		}
        if ($('#BookingTrail_bkg_tnc').is(':checked'))
        {
            $('#error_div1').hide();
            $('#error_div1').html('');
            launchBOLT();
        } else
        {
            $('#error_div1').show();
            $('#error_div1').html('Please check Terms and Conditions before proceed.');
            event.preventDefault();
        }
    });
</script>	
<script type="text/javascript">
    $pkpAddress = $drpAddress = $pkpPin = $drpPin = '';
    conValue = '<?= $model->bkgUserInfo->bkg_bill_country ?>';
    $(document).ready(function ()
    {
        $('#BookingUser_bkg_bill_country').selectize();
        if ('<?= $model->bkgUserInfo->bkg_bill_country ?>' == '' || '<?= $model->bkgUserInfo->bkg_bill_country ?>' == 'IN') {
            $('#cardChk1').prop('checked', true);
        } else {
            $('#cardChk2').prop('checked', true);
        }
    });
    if (conValue == 'IN') {
        cartInd()
    } else {
        cartInt();
    }
    //cartAll();

    function cardCountry(obj) {
        var country = obj.value;
        if (country == 'ind') {
            cartInd()
        }
        if (country == 'int') {
            cartInt();
        }
        //cartAll();
    }
    function cartInd() {

        var options = [{value: "IN", text: "India (IN)"}];
        $('#BookingUser_bkg_bill_country').selectize({"options": options});
        $('#BookingUser_bkg_bill_country')[0].selectize.clearOptions();
        $('#BookingUser_bkg_bill_country')[0].selectize.addOption(options);
        $('#BookingUser_bkg_bill_country')[0].selectize.setValue('IN');
    }
    function cartInt() {
        var href = '<?= Yii::app()->createUrl('index/countrynamejson') ?>';
        jQuery.ajax({type: 'GET', "dataType": "json", "data": {}, url: href,
            success: function (data1)
            {
                $('#BookingUser_bkg_bill_country').selectize();
                $('#BookingUser_bkg_bill_country')[0].selectize.clearOptions();
                $('#BookingUser_bkg_bill_country')[0].selectize.addOption(data1);
                $('#BookingUser_bkg_bill_country')[0].selectize.refreshOptions(false);
                if (conValue != '') {
                    $('#BookingUser_bkg_bill_country')[0].selectize.setValue(conValue);
                    $('#BookingUser_bkg_bill_country').val(conValue).change();
                } else {
                    $('#BookingUser_bkg_bill_country')[0].selectize.setValue('US');
                    $('#BookingUser_bkg_bill_country').val('US').change();
                }
            }
        });
    }
    function cartAll() {

        var href = '<?= Yii::app()->createUrl('index/countrynamejson') ?>';
        jQuery.ajax({type: 'GET', "dataType": "json", "data": {"excludeIndia": 0}, url: href,
            success: function (data1)
            {
                $('#BookingUser_bkg_bill_country').selectize();
                $('#BookingUser_bkg_bill_country')[0].selectize.clearOptions();
                $('#BookingUser_bkg_bill_country')[0].selectize.addOption(data1);
                $('#BookingUser_bkg_bill_country')[0].selectize.refreshOptions(false);
                $('#BookingUser_bkg_bill_country')[0].selectize.setValue(conValue);
                $('#BookingUser_bkg_bill_country').val(conValue).change();
            }
        });
    }

    $addressval = '';
    function getAddress($opt = 0) {

        if ($opt == 1) {
            $addressval = "<?= str_replace(array("\r\n", "\n", "\r"), ' ', $model->bkg_pickup_address); ?>";
        }
        if ($opt == 2) {
            $addressval = "<?= str_replace(array("\r\n", "\n", "\r"), ' ', $model->bkg_drop_address); ?>";
        }
        var $prename = '<?= $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?>';
        $fullname = $('#BookingUser_bkg_user_fname').val() + ' ' + $('#BookingUser_bkg_user_lname').val();
        $name = ($prename.trim() == '') ? $fullname : $prename;
        $('#BookingUser_bkg_bill_fullname').val($name);
        var $bkgid = $('#Booking_bkg_id').val();
        var $hash = '<?= $model->hash ?>';
        var $bkhash = $('#bkghash').val();
        $bkghash = ($hash == '') ? $bkhash : $hash;
        $href = '<?= Yii::app()->createUrl('booking/getaddressdata') ?>';
        jQuery.ajax({
            type: 'GET',
            "dataType": "json",
            async: false,
            url: $href,
            "data": {"bkgid": $bkgid, 'bkghash': $bkghash, 'opt': $opt},
            success: function (data1)
            {
                $('#BookingUser_bkg_bill_city').val(data1.city);
                $('#BookingUser_bkg_bill_state').val(data1.state);
                if ($addressval == '') {
                    $addressval = data1.city + ', ' + data1.state;
                }

            }
        });
        $('#BookingUser_bkg_bill_address').val($addressval);

        $("#chkdrpaddLt").removeAttr('checked');
        $("#chkdrpaddRt").removeAttr('checked');
    }
    function copypickupadd() {

        getAddress(1);
    }
    function copydropadd() {
        getAddress(2);
    }
    function otheradd()
    {
        $(".devClass").val("");
    }
//    $('input[name="inputGozoWallet"]').click(function ()
//    {
//        if ($('input[name="inputGozoWallet"]').is(':checked'))
//		{
//           useWallet(true);
//		}
//        else
//		{
//			var isWalletUsed = $('#isWalletUsed').val();
//			if(isWalletUsed == 1)
//			{
//                useWallet(false);
//			}
//		}
//    });
    function useWallet(flagUseWallet)
    {
        var walletAmount = $('#BookingInvoice_bkg_wallet_used').val();
        $('#inlineCheckbox1').prop('checked', false);
        var totWalletAmt = '<?= $walletBalance | 0 ?>';
        if (walletAmount > 0)
        {
            if (parseInt(walletAmount) <= parseInt(totWalletAmt))
            {
                var creditapplied = $('#creditapplied').val();
                $('#errMsgWallet').html("");
                var model = {};
                var promo = new Promo();
                model.url = "/users/usewallet";
                model.bkg_id = bid;
                model.bkghash = '<?= $hash ?>';
                promo.model = model;
                model.amount = walletAmount;
                model.flagUseWallet = flagUseWallet;
                model.credit_amount = creditapplied;
                promo.PromoCreditAjax();
            } else
            {
                $('#errMsgWallet').html("Amount cann't be greater than wallet balance.");
            }
        } else
        {
            $('#errMsgWallet').html("Amount must greater than 0.");
        }
    }
</script>	