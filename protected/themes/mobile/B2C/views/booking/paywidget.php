<style type="text/css">

    /*                            */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }

    label.optType{
        min-height: 30px;
		cursor: pointer;
		/*border: 1px solid #5555ee;*/
		/*padding: 5px 10px;*/
		/*width:100%;*/
    }
    .optType  {
		text-decoration: none!important;
    }
	.optType img{
		display: inline;
    }
	div{
		position: static;
		display: block
	}
	.error,.alert-danger,.text-danger{color: rgb(212, 103, 103);}
	.text-success{color: rgb(103,212,103);}
	a.menu-hide{z-index: 10;}
	.payimg  {

		min-height:40px!important;

	}
	.payimg img{
		width:auto!important;
		height:30px!important;
		border-radius:0!important;
		margin-top: -30px!important;
		box-shadow: none;
	}

</style>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<?
$advance				 = $model->bkgInvoice->getAdvanceReceived();
$maxPaymentWithDiscount	 = round($model->bkgInvoice->bkg_total_amount) - $advance;
$orderAmount			 = $model->bkgInvoice->calculateMinPayment();
$defaultPaymentGateway = Config::model()->getAccess("paymentgateway.default");
?>


<div id="menu-list-bottom2" data-selected="menu-components" data-height="480" class="menu-box menu-bottom" style="display:none; transition: all 300ms ease 0s;">
	<div class="  pay-header text-right">
		<a href="#" class="menu-hide mt15 n ml5"><i class="fa fa-times"></i></a>
		<a href="#" class="pt15 mr50 backarrow hide" style="transition: all 300ms ease 0s;"><i class="fas fa-arrow-circle-left"></i> BACK</a>		

	</div>
	<div class="clear"></div>

	<?php
	$ccmodel				 = new BraintreeCCForm('charge');

	if ($minPay > 0)
	{
		$orderAmount = $minPay;
	}

	$total_tax_rate	 = ($model->bkgInvoice->bkg_convenience_charge * $model->bkgInvoice->getServiceTaxRate() * 0.01);
	$saveAmount		 = round($model->bkgInvoice->bkg_convenience_charge + $total_tax_rate);
	$msg			 = 'Pay online at least <i class="fa fa-inr"></i>' . $orderAmount . ' advance before trip starts to save <i class="fa fa-inr"></i>' . $saveAmount . ' more on your quoted fare.';
	$creditsused	 = ($model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0;
	//$advance		 = $model->bkgInvoice->getAdvanceReceived();
	$strCashBack	 = '';
	if (count($promoArr) == 0 && $model->bkgInvoice->bkg_advance_amount == 0 && $model->bkg_status == 2)
	{
		$strCashBack = 'Pay at least 15% of total amount and get 50% Cashback' . " *<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
	}
	//$maxPaymentWithDiscount				 = round($model->bkgInvoice->bkg_total_amount) - $advance;
	$payable							 = $model->bkgInvoice->bkg_total_amount;
	$due								 = ($model->bkgInvoice->bkg_total_amount - $advance);
	$conCharge							 = 0;
	$minDiff							 = $model->getPaymentExpiryTimeinMinutes();
	$hash								 = Yii::app()->shortHash->hash($model->bkg_id);
	$model->bkgInvoice->bkg_due_amount	 = ($model->bkgInvoice->bkg_due_amount > 0) ? $model->bkgInvoice->bkg_due_amount : $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->bkg_advance_amount + round($model->bkgInvoice->bkg_refund_amount) - $model->bkgInvoice->bkg_credits_used - $model->bkgInvoice->bkg_vendor_collected;
	?>
	<div class="content p0 bottom-0">

		<?
		if ($minDiff > 0)
		{
			if ($model->bkgInvoice->bkg_due_amount > 0)
			{
				?>
				<div class="content p0 bottom-0" id="payment">
					<?php
					$form				 = $this->beginWidget('CActiveForm', array(
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
								booknow.showErrorMsg("You need to check your elegiblity before pay through LazyPay");
								return false;
							}
							if($("#chkele").val()=="2" && $("#BookingInvoice_paymentType").val() == "14"){
								booknow.showErrorMsg("Sorry you are not elegible to pay through LazyPay for now. Please try other payment method.");
								return false;
							}
							if(!hasError){
									$.ajax({
							"type":"POST",
							"dataType":"json",
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl("booking/payment", ['id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id), 'src' => 1, 'iscreditapplied' => ''])) . '"+$(\'#creditapplied\').val(),
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
											var data2 = data1.error;
											settings=form.data(\'settings\');
											//console.log(data2);
											msg =JSON.stringify(data2);
											if(data2)
											{
												var x = window.matchMedia("(max-width: 700px)");
												if (x.matches) 
												{
													 var result = JSON.parse(msg);
													for (k in result) {
													$("#"+k+"_em_").text(result[k]);
													$("#"+k+"_em_").show();
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
					$model->bkgInvoice->paymentType;
					/* @var $form CActiveForm */
					?>
					<?php echo CHtml::errorSummary($model); ?>
					<?php echo CHtml::errorSummary($model->bkgInvoice); ?>
					<? ///////// ?>
					<?php echo $form->hiddenField($model->bkgInvoice, 'optPaymentOptions', ['value' => 1]) ?>
					<?php echo $form->hiddenField($model->bkgInvoice, 'paymentType', ['value' => 3]) ?>
					<?php echo $form->hiddenField($model, 'hash', ['id' => 'hash5', 'value' => "$hash"]); ?>
					<?php echo $form->hiddenField($model, 'bkg_id') ?>
					<?php echo $form->error($model, 'bkg_id'); ?>
					<?php echo $form->hiddenField($model->bkgInvoice, 'ebsOpt', ['value' => 1]) ?>
					<?php echo $form->hiddenField($model->bkgInvoice, 'payubolt', ['value' => 0]) ?>
					<? //= $form->hiddenField($model, 'bkg_status')  ?>
					<?php echo $form->hiddenField($model->bkgUserInfo, 'bkg_user_id', ['value' => $userid]) ?>
					<?php echo $form->hiddenField($model->bkgInvoice, 'isAdvPromoPaynow') ?>
					<?php
					$amountWithConvFee	 = round($model->bkgInvoice->bkg_due_amount);
					$isAdvDiscount		 = 0;
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
					<input type="hidden" name="creditapplied" id="creditapplied" value="<?php echo $model->bkgInvoice->bkg_credits_used ?>">
					<input type="hidden" name="isAdvDiscount" id="isAdvDiscount" value="<?php echo $isAdvDiscount ?>"> 
					<input type="hidden" id="step5" name="step" value="5">
					<input type="hidden" name="totAmount" id="totAmount" value="<?php echo $totAmount; ?>">
					<input type="hidden" name="isPayNowCredits" id="isPayNowCredits" value="0">
					<input type="hidden" class="maxAmount" name="max_amount" id="max_amount" value="<?php echo $maxPaymentWithDiscount; ?>">

					<input type="hidden" id="bkghash" name="bkghash"  value="<?php echo $hash ?>">
					<input type="hidden" id="isWalletUsed" name="isWalletUsed" value="0"> 
					<input type="hidden" id="walletUsedAmt" name="walletUsedAmt" value="0" />
					<input type="hidden"  class="clsAdditionalParams" name="additionalParams" value='{"code":"","coins":0,"wallet":0}'/>
					<!-- payment widget start--->

					<div id="menu-search-list">

						<div class=" menu-search-trending  top-20">
							<b class="uppercase">Payment Method</b>
							<ul class="link-list link-panel">
								<li   id="lblmenu2"  >
									<div class="mb5">Mobile Wallets</div>
									<div class="fac one-half mr5" reftext="paytm"><span></span>
										<input type="radio" name="paymentTypeBtn" class="optType opt1" id="optl1"> 
										<label class="opt1 optType pl0 pb10 "  id="lbl1" for="optl1">
											<img src="/images/paytm_logo.png" style="height:30px"  alt="Paytm">
										</label>
									</div>

									<div class="fac one-half last-column" reftext="mbk"><span></span>												 
										<input type="radio" name="paymentTypeBtn" class="optType opt10"  id="mbk"> 	 
										<label class="opt10 optType pl0 pb10" id="lbl10" for="mbk">
											<img src="/images/mobikwik-logo-new.png" style="height:26px" alt='Mobikwik'> 
										</label>
									</div>
									<div class="fac one-half"  reftext="payu" style="display: none;"><span></span>
										<input type="radio" name="paymentTypeBtn" class="optType opt6 optl6"  >
										<label class="opt6 optType " id="lbl6" for="opt6">
											<img src="/images/payumoney-logo.png" style="margin-top: 0;" alt='PayUMoney'>
										</label>
									</div>
									<div class="clear"></div>
									<? /* ?><label class="opt12 optType" id="lbl12">
									  <input type="radio" name="paymentTypeBtn" class="optType opt12" id="optl12">
									  <img src="/images/frc-logo.png" style="height: 23px;" alt='FreeCharge'>
									  </label><? */
									?>

								</li>

								<li  id="lbcredit">
									<div class="mb5">Credit Card / Debit Card</div>
									<div class="fac content  mb10 pl5" reftext="indcredit"><span></span>
										<input type="radio" name="paymentTypeBtn" class="opt6 optType" id="copt6"> 
										<label class="opt6 optType  pl0" id="clbl6" for="copt6" >
											Option 1: Card Issued in India (PayU)<br> 
											<img class=' ' src="/images/pay-rupay.png?v"   alt='RUPAY'>
										</label>
									</div>
									<div class="fac content mt5 mb10  pl5" reftext="intcredit"><span></span>
										<input type="radio" name="paymentTypeBtn" class="opt2 optType" id="optl2"> 
										<label class="opt2 optType  pl0" id="optl2"   for="optl2">
											Option 2: International Card<br>
											<img src="/images/pay-international-card.png" style="max-width: 210px"   alt='Intaernational Cards'>
										</label>
									</div>


								</li>
								<li  id="lbllazy"  >
									<div class="mb5">Book Now. Pay Later</div>
									<div class="fac content mt5 mb5 pl5" reftext="lazy"><span></span>
										<input type="radio" name="paymentTypeBtn" class="optType opt14 rad  " id="optl14"> 
										<label class="opt14 optType  pl0 " id="lbl14" for="optl14">
											<img src="/images/lazypay-logo.png"    style="height: 20px  "  alt='LAZYPAY'>
										</label>
									</div>

									<div class="fac  content mb10  pl5" reftext="epay"><span></span>
										<input type="radio" name="paymentTypeBtn" class="optType opt15 rad" id="optl15" > 
										<label class="opt15 optType   pl0  " id="lbl15" for="optl15">
											<img src="/images/epaylogo.png"    style="height: 30px  " alt='EPayLater'> </label>
									</div>
								</li>

								<li id="lbl4">
									<div class="mb5">Net Banking</div>
									<div class="fac  one-half pl5" reftext="banking"><span></span>
										<input type="radio" name="paymentTypeBtn" class="optType  opt4" id="optl4"> 
										<label class="optType  opt4 pl0 pb10" id="lbl4" for="optl4">
											<img src="/images/netbanking-logo.png" class="pull-left" style="height: 35px; " alt='Net Banking'>
										</label>
									</div>
									<div class="clear"></div>
								</li>							 
							</ul>
						</div>


						<div class="search-results  ">	

							<label data-filter-item=""  data-filter-name="paytm" 
								   class="search-result-list disabled-search-item     payimg">
								<img src="/images/paytm_logo.png"  alt="Paytm">
							</label>
							<label data-filter-item=""  data-filter-name="mbk" 
								   class="search-result-list disabled-search-item      payimg">
								<img src="/images/mobikwik-logo-new.png"  alt='Mobikwik'> 
							</label>
							<label  data-filter-item=""  
									data-filter-name="indcredit" 
									class="search-result-list disabled-search-item   payimg">
								<img src="/images/pay-rupay.png"  alt='RUPAY'>

							</label>
							<label  data-filter-item=""  
									data-filter-name="intcredit" 
									class="search-result-list disabled-search-item   payimg">
								<img src="/images/pay-international-card.png" style="max-width: 210px"   alt='International Cards'>

							</label>

							<label  data-filter-item=""  
									data-filter-name="lazy" 
									class="search-result-list disabled-search-item   payimg">
								<img src="/images/lazypay-logo.png"  class="pull-left" style="height: 20px"  alt='LAZYPAY'></label>
							<label   data-filter-item=""  
									 data-filter-name="epay" 
									 class="search-result-list disabled-search-item    payimg">
								<img src="/images/epaylogo.png" class="pull-left "  alt='EPayLater'></label>
							<label   data-filter-item=""  
									 data-filter-name="banking" 
									 class="search-result-list disabled-search-item payimg">
								<img src="/images/netbanking-logo.png" class="pull-left  "   alt='Net Banking'>
							</label>



							<div data-filter-item=""  data-filter-name="lazy" class="search-result-list disabled-search-item content p0 bottom-0 bottom-5" id="lazypaytext">
								<span>LazyPay Credit</span>
								<b>Use LazyPay to Book Now & Pay Later</b><br>																	 
								<i class="fa fa-angle-double-right text-danger pl10"></i> Zero Cost Credit (0% interest)<br>	
								<i class="fa fa-angle-double-right text-danger pl10"></i> No sign-up needed<br>	
								<i class="fa fa-angle-double-right text-danger pl10"></i> Place your order with just an OTP<br>	
								<i class="fa fa-angle-double-right text-danger pl10"></i> Pay LazyPay within due date. Lazypay will remind you 
							</div>

							<!-- credit card section-->
							<div data-filter-item="" data-filter-name="intcredit indcredit banking lazy paytm mbk epay"  class="disabled-search-item disabled-search-item" >
								<div class="content p0 bottom-0 mt20 n" >
									<?php
									$this->renderPartial('billingdetails', ['model' => $model, 'form' => $form,]);
									?>
								</div>
								<div class="content p0 bottom-0" id="checkEleBlock">
									<div class="col-xs-12  ">
										<button id="lpbtn" class="  btn-orange shadow-medium" type="button" onclick="checkeligiblity()">Click to get approved for LazyPay</button>
									</div>
									<div class="col-md-12 mb10">
										<span class="lazyPayEleText text-success h5" id="lazyPayEleTextSucc" ></span>
										<span class="lazyPayEleText text-danger h5" id="lazyPayEleTextErr"  ></span>
									</div>
									<input type="hidden" id="chkele" value="0"> 
								</div>

								<div class="content p0 bottom-0" >
									<div id="btree" style="display: none" class="content p0 bottom-0">
										<?php echo CHtml::errorSummary($ccmodel); ?>
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
															<?php
															echo
															$form->dropDownList(
																	$ccmodel, 'creditCard_month', $ccMonth,
																	array('style' => 'padding:6px',)
															);
															?>
														</div>
														<div class="col-xs-1 pl0 h3 mt5">/</div>
														<div class="col-xs-6 pl0">
															<?php
															echo $form->dropDownList(
																	$ccmodel, 'creditCard_year', $ccYear
															);
															?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="showcash form-control " style="display: none"><?php echo $model->bkgInvoice->bkg_due_amount ?></div>
									<div id = "ppayment"  class="content p0 bottom-0 mt15">

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
										<div class="content p0 bottom-0">
											<div class="one-half input-simple-1 has-icon input-green bottom-15">
												<?php echo $form->numberField($model->bkgInvoice, 'partialPayment', ['class' => 'clsPartialPayment pl10 ', 'placeholder' => "Partial Payment", 'min' => $orderAmount, 'max' => $maxPaymentWithDiscount] + $readOnlyPartialPay) ?>
											</div>
											<div class="one-half last-column"  style="display: <?php echo $hidePayinfull ?>">  
												<div class="  mt5"  style="display:none;"> 													
													<label   for="inlineCheckbox1">
														<input  type="checkbox" id="inlineCheckbox1" value="option1"  >	Pay in full 
													</label>
												</div>
											</div>
											<div class="clear"></div>



											<div class="content p0 bottom-0" style="display: none" id="divDollar">
												<b><span id="amtDollar"></span></b> will be deducted from your Card
											</div>
										</div>
										<span class="content p0 bottom-0" id="pmaxpay" style="display: none">Maximum amount payable through paytm (per day) is <i class="fa fa-inr"></i>10,000</span>
									</div>

								</div>

								<div class="content p0 bottom-0 top-10">
									<input type="hidden" name="confBtns" id="confPayNow1" value="p1">
									<label>
										<?
										if (in_array($model->bkg_flexxi_type, [1, 2]))
										{
											?>
											<?php echo $form->checkbox($model->bkgTrail, 'bkg_tnc') ?>
											<?php echo 'I agree to the Gozo <a href="javascript:void(0);" class="termscls" >terms and conditions</a> & <a href="javascript:void(0);" onclick="openflexxiterms()" >Flexxi terms and conditions</a>' ?>
											<?
										}
										else
										{
											?>
											<?php echo $form->checkbox($model->bkgTrail, 'bkg_tnc', ['class' => 'test']) ?>
											<?php echo 'I agree to the Gozo <a href="javascript:void(0);" class="termscls" >terms and conditions</a>' ?>
										<? } ?>   
									</label> 
									<div id="error_div1" style="display: none" class="alert alert-block alert-danger error"></div>
								</div>

								<div class="content p0 bottom-20 top-20 text-center">
									<input type="submit" value="Proceed" class="uppercase btn-orange  " id="proceedPayNow">
									<input type="button" value="Proceed" class="uppercase btn-orange  " style="display: none"  id="payubtn" >
								</div>
							</div>



						</div>

						<!-- payment widget end--->
					</div>
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
						<?php echo $strmsg1 ?>
					</div>
				</div>
			</div><?
		}
		?>
	</div> 
	<input id="minipay" class="minipay"  type="hidden" value="<?php echo $orderAmount ?>">
	<input id="dueAmountWithoutCOD" type="hidden" value="<?php echo $model->bkgInvoice->bkg_due_amount; ?>">

	<script type="text/javascript">
		var booknow = new BookNow();
		$netAmount = <?php echo $maxPaymentWithDiscount ?>;
		var bid = '<?php echo $model->bkg_id ?>';
		var hsh = '<?php echo Yii::app()->shortHash->hash($model->bkg_id) ?>';
		var dollarToRupeeRate =<?php echo Yii::app()->params['dollarToRupeeRate'] ?>;
		$readOnlyPayTrainman = '<?php echo $readOnlyPayTrainman ?>';
		$defaultPaymentGateway = '<?php echo $defaultPaymentGateway;?>';
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
			$defPayuBolt = '<?php echo Yii::app()->params['enablePayuBolt'] ?>';
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
//			opt1Click();
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
					// && $('#<? //= CHtml::activeId($model, "paymentType")       ?>').val() != 6
					)
			{
				$('#optl1').prop("checked", true);
				opt1Click();
			}

			cartInd();
			$('#mobwallet').addClass('active');
		});
		$('#lbcredit').click(function ()
		{
			$('#cardAsk').hide();
			//$('#cardAsk').show();
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


		});
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
//			$('#cardAsk').show();
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
//			$('#cardAsk').show();
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
//			$('#cardAsk').show();
			$('#btree').show();
			$('#BookingInvoice_paymentType').val('9');
			if ($('#<?php echo CHtml::activeId($ccmodel, "creditCard_name") ?>').val() == '')
			{
				$('#<?php echo CHtml::activeId($ccmodel, "creditCard_name") ?>').val('<?php echo $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?>');
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
				payFullChecked();
			} else
			{
				payFullUnChecked();
			}
			if ($('#optl9').is(':checked'))
			{
				showDollar();
			}

		});

		function payFullChecked()
		{
			$('#BookingInvoice_partialPayment').attr("readonly", "readonly");
			$('#BookingInvoice_partialPayment').val($('#max_amount').val());
			$('#BookingInvoice_optPaymentOptions').val('1');
			$("#pwords").css('visibility', 'hidden');
		}

		function payFullUnChecked()
		{
			$("#pwords").css('visibility', 'visible');
			if (!$readOnlyPayTrainman)
			{
				$('#BookingInvoice_partialPayment').removeAttr("readonly");
			}
			$('#BookingInvoice_optPaymentOptions').val('2');
			var minpay = $("#BookingInvoice_partialPayment").attr('min');
			$('#BookingInvoice_partialPayment').val(minpay).change();
		}


		$('#BookingInvoice_partialPayment').on('change', function ()
		{
			showDollar();
		});
		function showDollar()
		{
			$('#divDollar').show();
			$pamount = $('#BookingInvoice_partialPayment').val();
			$damount = ($pamount / dollarToRupeeRate).toFixed(2);
			$('#amtDollar').html('$' + $damount);
		}

		function useCredit()
		{

		}

		function changeAmountWord()
		{
			var amount = $('#totAmount').val();
			$href = '<?php echo Yii::app()->createUrl('booking/amountinwords') ?>';
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
			$('#lbcredit').addClass('active');
//			$('#cardAsk').show();
		});
		function showIndOptions()
		{

			$('#optl2').prop("checked", true);
			opt2Click();
//            $('.optl6').prop("checked", true);
//            opt6Click();
		}
		function showIndOptions1()
		{

			$('.optType').show();
			if ($('.opt9').hasClass('active'))
			{
				if ('<?php echo $creditCardType ?>' == '1')
				{
					$('.opt2').addClass('active');
				}
				if ('<?php echo $creditCardType ?>' == '2')
				{
					$('.opt11').addClass('active');
				}
				$('.opt9').removeClass('active');
				$('#BookingInvoice_paymentType').val('4');
				$('#btree').hide();
			}
			$('#<?php echo CHtml::activeId($ccmodel, "creditCard_name") ?>').val('');
			$('#divDollar').hide();
			$('.opt9').hide();
			$('#lbcredit').addClass('active');
			$('#indopt').show();
			$('#intopt').hide();
		}
		function showIntOptions1()
		{

			// $('.optType').hide();
			$('.opt9').show();
			$('.opt9').addClass('active');
			//$('.opt1').removeClass('active');
			if ('<?php echo $creditCardType ?>' == '1')
			{
				$('.opt2').removeClass('active');
			}
			if ('<?php echo $creditCardType ?>' == '2')
			{
				$('.opt11').removeClass('active');
			}
			// $('.opt3').removeClass('active');
			// $('.opt4').removeClass('active');
			$('#BookingInvoice_paymentType').val('9');
			$('#btree').show();
			showDollar();
			if ($('#<?php echo CHtml::activeId($ccmodel, "creditCard_name") ?>').val() == '')
			{
				$('#<?php echo CHtml::activeId($ccmodel, "creditCard_name") ?>').val('<?php echo $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?>');
			}
			$('.opt9').show();
			calculatePayable();
			$('#lbcredit').addClass('active');
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

			if ($(".payChk:checked").val() == 0) {
				$("#BookingInvoice_partialPayment").val(minpay);
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
					"url": "<?php echo CHtml::normalizeUrl(Yii::app()->createUrl('lazypay/checkeligiblitycall')) ?>",
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
				var id = '<?php echo $model->bkg_id ?>';
				var hash = '<?php echo Yii::app()->shortHash->hash($model->bkg_id) ?>';
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
						booknow.showErrorMsg(error);
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
				"url": "<?php echo CHtml::normalizeUrl(Yii::app()->createUrl('booking/finalbook')) ?>/ctype/" + checkedBtns,
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
	<?PHP
	if (Yii::app()->params['enablePayuBolt'] == 1)
	{
		?>
		<script id="bolt" src="<?php echo Yii::app()->payu->boltjsSrc ?>" bolt-color="1a4ea2" 
				bolt-logo="http://aaocab.com/images/1024_1024_new.png">
		</script>
	<?php } ?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
	<script type="text/javascript">

	function launchRazorpay()
	{
		//debugger;
		$.ajax({
			"type": "POST",
			"dataType": "json",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/payment')); ?>",
			"data": $("#payment-form1").serialize(),
			"success": function (data2) {
				//debugger;
				if (data2.success) {
					if (data2.onlywallet) {

						location.href = data2.url;
					} else {

						launchRazorpay1(data2);
					}
				}
			}
		});
	}
	function launchRazorpay1(data3)
	{
		//debugger;
		var options = {
			"key": data3.key, // Enter the Key ID generated from the Dashboard
			"amount": data3.amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
			"currency": data3.currency,
			"name": "Gozocabs",
			"description": "Make Payment",
			"image": "http://www.aaocab.com/images/gozo_svg_logo.svg?v0.1",
			"order_id": data3.order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
			"handler": function (response){
			//debugger;
			onResponseRecieved(response,data3.callbackUrl);
			},
			"prefill": {
				"name": data3.name,
				"email": data3.email,
				"contact":data3.contact
			},
			"notes": {
				"trnsCode": data3.trnsCode
			},
			"theme": {
				"color": "#F37254"
			}
		};
		var rzp1 = new Razorpay(options);
		rzp1.on('payment.failed', function (response){
			//debugger;
			onResponseRecieved(response,data3.callbackUrl);

		});
		$('.razorpay-payment-button').click();
		$('.razorpay-payment-button').hide();
		rzp1.open();
	}
	
	function onResponseRecieved(response,url)
	{
		//	debugger;
			var stringResponse = JSON.stringify(response);
			$.ajax({
					"type": "POST",
					"dataType": "json",
					"url": url,
					"data": {"response":stringResponse},
					"success": function (data) {
						//debugger;
					   window.location.href = data.url;
					}
				});
	}
		function launchBOLT()
		{
			$.ajax({
				"type": "POST",
				"dataType": "json",
				"url": "<?php echo CHtml::normalizeUrl(Yii::app()->createUrl('booking/payment')); ?>",
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
						"url": "<?php echo CHtml::normalizeUrl(Yii::app()->createUrl('payment/response')); ?>",
						"data": {'response': BOLT.response, 'ptpid': 6, 'bolt': 1},
						"success": function (data4) {
							booknow.showErrorMsg(data4.message);
						}
					});
				},
				catchException: function (BOLT) {
					booknow.showErrorMsg(BOLT.message);
				}
			});
		}
		$("#proceedPayNow").on("click", function (event)
		{
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

		$('#BookingTrail_bkg_tnc').click(function () {
			$('#tncBox').addClass('hide');
		});
	</script>	
	<script type="text/javascript">
		$pkpAddress = $drpAddress = $pkpPin = $drpPin = '';
		conValue = '<?php echo $model->bkgUserInfo->bkg_bill_country ?>';
		$(document).ready(function ()
		{
			$('#BookingUser_bkg_bill_country').selectize();
			if ('<?php echo $model->bkgUserInfo->bkg_bill_country ?>' == '' || '<?php echo $model->bkgUserInfo->bkg_bill_country ?>' == 'IN') {
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
			var href = '<?php echo Yii::app()->createUrl('index/countrynamejson') ?>';
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

			var href = '<?php echo Yii::app()->createUrl('index/countrynamejson') ?>';
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
				$addressval = "<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', $model->bkg_pickup_address); ?>";
			}
			if ($opt == 2) {
				$addressval = "<?php echo str_replace(array("\r\n", "\n", "\r"), ' ', $model->bkg_drop_address); ?>";
			}
			var $prename = '<?php echo $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?>';
			$fullname = $('#BookingUser_bkg_user_fname').val() + ' ' + $('#BookingUser_bkg_user_lname').val();
			$name = ($prename.trim() == '') ? $fullname : $prename;
			$('#BookingUser_bkg_bill_fullname').val($name);
			var $bkgid = $('#Booking_bkg_id').val();
			var $hash = '<?php echo $model->hash ?>';
			var $bkhash = $('#bkghash').val();
			$bkghash = ($hash == '') ? $bkhash : $hash;
			$href = '<?php echo Yii::app()->createUrl('booking/getaddressdata') ?>';
			jQuery.ajax({
				type: 'GET',
				"dataType": "json",
				async: false,
				url: $href,
				"data": {"bkgid": $bkgid, 'bkghash': $bkghash, 'opt': $opt},
				success: function (data1)
				{
					$('.bill_citydata').val(data1.city);
					$('.bill_statedata').val(data1.state);
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
			$('.cardDtls').val('');
		}

		$('#BookingUser_bkg_bill_country').keypress(function (e) {
			if (e.which == 27) {
				$('#BookingUser_bkg_bill_country').val('');
			}
		});

	</script>
	<script type="text/javascript">
		//Preload Image
		$(function () {
			$(".preload-search-image").lazyload({threshold: 0});
		});
		//Search Menu Functions


		//Menu Search Values//
		$('.menu-search-trending div').on('click', function () {
			var e = jQuery.Event("keydown");
			e.which = 32;
			var search_value = $(this).attr('reftext');

			$('.search-results').removeClass('disabled-search-list');
			$('[data-filter-item]').addClass('disabled-search-item');
			$('[data-filter-item][data-filter-name*="' + search_value.toLowerCase() + '"]').removeClass('disabled-search-item');
			$('#search-page').addClass('move-search-list-up');
			$('.pay-header a').addClass('search-close-active');
			$('.backarrow').show();
			$('.menu-search-trending').addClass('disabled-search-item');
			return false;
		});
		$('#menu-hider, .close-menu, .menu-hide').on('click', function () {

			$('.menu-box').removeClass('menu-box-active');
			$('#menu-hider').removeClass('menu-hider-active');
			setTimeout(function () {
				$('#search-page').removeClass('move-search-list-up');
			}, 100);
			$('[data-filter-item]').addClass('disabled-search-item');

			$('.menu-search-trending').removeClass('disabled-search-item');
			$('.pay-header a').removeClass('search-close-active');
			$('.backarrow').hide();
			$('#search-page').removeClass('move-search-list-up');
			return false;
		});
		$('#menu-search-list input').on('focus', function () {
			$('#search-page').addClass('move-search-list-up');
			$('.pay-header a').addClass('search-close-active');
			$('.backarrow').show();
			return false;
		})
		$('.pay-header a').on('click', function () {
			$('.menu-search-trending').removeClass('disabled-search-item');
			$('#menu-search-list .search-results').addClass('disabled-search-list');
			$('#search-page').removeClass('move-search-list-up');
			$('.pay-header a').removeClass('search-close-active');
			$('.backarrow').hide();
			return false;
		});

		function payCardInfo() {
			//debugger;
			if ($('#isInt').is(':checked')) {
				if ($('#bkg_bill_address').val() == '') {
					booknow.showErrorMsg("Please provide billing address for the Internation card");
					return false;
				}

				if ($('#bkg_bill_city').val() == '') {
					booknow.showErrorMsg("Please provide City name");
					return false;
				}

				if ($('#bkg_bill_postalcode').val() == '') {
					booknow.showErrorMsg("Please provide Postal Code");
					return false;
				}
			}
			$('.isCardInfo').val(1);
			$('#payBoxBtn').click();
		}


		$('#payBoxBtn').click(function ()
		{	//debugger; 
			if ($('.isPickupAdrsCls').val() != null && $('.isPickupAdrsCls').val() != undefined) {
				if ($('.isPickupAdrsCls').val() != '1') {
					booknow.showErrorMsg("Pickup and drop address is mandatory. If already selected then please press save address button.");
					$("#accordion-60").css("display", "block");
					$("#form_cty_id_0").focus();
					event.preventDefault();
					return false;
				}
			}
			if ($('.isCardInfo').val() == 0) {
				$('a[data-menu="sidebar-right-over-card"]').click();
				return false;
			}
			bktype = <?php echo $model->bkg_booking_type ?>;
			var specialStatus = $("#request_status").val();
			$(".additionalinfo").css("border", "0");
			if (bktype == 7)
			{
				specialStatus = 1;
			}
//			if ($('#isInt').is(':checked')) {
//				processEBS();
//			} else {
//				processPayu();
//			}
			if ($('#ptm').is(':checked')) {
				processPaytm();
			}
			if ($('#payu').is(':checked')) {
				//debugger;
				if($defaultPaymentGateway=='21'){
				processRazorpay();
				}else{
				processPayu();
				}
			}

			return false;
		});
		$('.ptm').click(function () {
			processPaytm();
		});
	//	$('.payu').click(function () {
	//		processPayu();
	//	});

		function processEBS() {
			if ($('#bkg_bill_address').val() == '') {
				booknow.showErrorMsg("Please provide billing address for the Internation card");
				return false;
			}

			if ($('#bkg_bill_city').val() == '') {

				booknow.showErrorMsg("Please provide City name");
				return false;
			}

			if ($('#bkg_bill_postalcode').val() == '') {

				booknow.showErrorMsg("Please provide Postal Code");
				return false;
			}
			$('#BookingInvoice_paymentType').val('4');
			$('#BookingInvoice_optPaymentOptions').val('2');
			$('#BookingInvoice_ebsOpt').val('3');
			$('#BookingUser_bkg_bill_postalcode').val($('#bkg_bill_postalcode').val());
			$('#BookingUser_bkg_bill_city').val($('#bkg_bill_city').val());
			$('#BookingUser_bkg_bill_address').val($('#bkg_bill_address').val());
			$('#BookingUser_bkg_bill_country').val($('#bkg_bill_country').val());
			processOther();
		}
		function processPayu() {
			$('#BookingInvoice_paymentType').val('6');
			useCredit();
			calculatePayable();
			$('#BookingTrail_bkg_tnc').prop('checked', true);
			if ($enablePayuBolt == 1) {
				$("#BookingInvoice_payubolt").val(1);
				launchBOLT();
			} else {
				processOther();
			}
		}
		function processRazorpay() {
			//debugger;
			$('#BookingInvoice_paymentType').val('21');
			useCredit();
			calculatePayable();
			$('#BookingTrail_bkg_tnc').prop('checked', true);
			$("#BookingInvoice_payubolt").val(1);
			launchRazorpay();
			
		}
		function processPaytm() {
			$('#BookingInvoice_paymentType').val('3');
			useCredit();
			calculatePayable();
			$('#BookingTrail_bkg_tnc').prop('checked', true);

			processOther();

		}
		function processOther() {
			$.ajax({
				"type": "POST",
				"dataType": "json",
				"url": "<?php echo CHtml::normalizeUrl(Yii::app()->createUrl('booking/payment')); ?>",
				"data": $("#payment-form1").serialize(),
				"beforeSend": function () {
					ajaxindicatorstart("");
				},
				"complete": function () {
					ajaxindicatorstop();
				},
				"success": function (data1) {
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
    </script>
</div>