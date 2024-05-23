
<style type="text/css">
   .box_2{
        display:flex;
        flex-flow: column;
    } 
    .lowerslab{order:0;}
    .upperslab{order:-1;}
</style>
<?php
$ccmodel	 = new BraintreeCCForm('charge');
$orderAmount	 = $model->vor_total_price;
$msg			 = 'Pay online at least &#x20B9;' . $orderAmount . ' advance before trip starts to save &#x20B9;' . $saveAmount . ' more on your quoted fare.';
?>
<div class="col-12">
	<div >
		<?php
		
		if ($orderAmount > 0)
		{
			?>
			<div class="row" id="payment">
				<?php
				$form				 = $this->beginWidget('CActiveForm', array(
					'id'					 => 'payment-form1',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){


						if(!hasError){
								$.ajax({
						"type":"POST",
						"dataType":"json",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl("voucher/payment", ['hash' => Yii::app()->shortHash->hash($model->vor_id), 'id' => $model->vor_id, 'src' => 1, 'iscreditapplied' => ''])) . '"+$(\'#creditapplied\').val(),
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
				/* @var $form CActiveForm */
				?>
				<?= CHtml::errorSummary($model); ?>

				<?= $form->hiddenField($model, 'optPaymentOptions', ['value' => 1]) ?>
				<?= $form->hiddenField($model, 'paymentType', ['value' => 3]) ?>
				<?= $form->hiddenField($model, 'vor_id') ?>
				<?php echo $form->error($model, 'vor_id'); ?>
				<?= $form->hiddenField($model, 'ebsOpt', ['value' => 1]) ?>
				<?= $form->hiddenField($model, 'payubolt', ['value' => 0]) ?>
				<?php
//					$amountWithConvFee	 = round($model->bkgInvoice->bkg_due_amount);
//					$isAdvDiscount		 = 0;
//					if ($model->bkgInvoice->bkg_promo1_id != 0)
//					{
//						$promoModel = Promos::model()->getByCode($model->bkgInvoice->bkg_promo1_code);
//						if ($promoModel->prm_activate_on == 1 && !$isredirct)
//						{
//							$amountWithConvFee	 = round($mol->bkgInvoice->bkg_due_amount);
//							$isAdvDiscount		 = 1;
//						}
//					}
				?>
				<input type="hidden" name="iscreditapplied" id="iscreditapplied" value="0">
				<input type="hidden" name="creditapplied" id="creditapplied" value="">
				<input type="hidden" name="isAdvDiscount" id="isAdvDiscount" value=""> 
				<input type="hidden" id="step5" name="step" value="5">
				<input type="hidden" name="totAmount" id="totAmount" value="<?= $orderAmount; ?>">
				<input type="hidden" name="isPayNowCredits" id="isPayNowCredits" value="0">
				<input type="hidden" class="maxAmount" name="max_amount" id="max_amount" value="<?= $orderAmount; ?>">
				<input type="hidden" id="isWalletUsed" name="isWalletUsed" value="0"> 
				<input type="hidden" id="walletUsedAmt" name="walletUsedAmt" value="0" />
				<input type="hidden" id="refundCredits" value="0"/>
				<input type="hidden" id="bghash" name="bghash" value="<?= $hash ?>">
				<input type="hidden"  class="clsAdditionalParams" name="additionalParams" value='{"code":"","coins":0,"wallet":0}'/>


				<!-- payment widget start--->



					<div class="col-12">
		<div class="row m0 mt20 bg-white-box pay-widget">
		<div class="col-sm-3 pl0">
			<ul class="nav nav-tabs">
			<li class="" id="lblmenu2" >
				<a data-toggle="tab" href="#menu11" class="optType opt1 " id="mobwallet"> <i class="fas fa-check-circle"></i> Mobile Wallets
				</a>
			</li>

			<li  id="lbcredit" class="lbcredit active">
				<div id="indopt">
				<a class="opt6 optType active"  name="paymentTypeBtn" id="" data-toggle="tab" href="#home"> <i class="fas fa-check-circle"></i>  Credit Card / Debit Card
				</a>
				</div>
				<div id="intopt" style="display: none;">
				<a class="opt2 optType"  name="paymentTypeBtn" id="optl2" data-toggle="tab" href="#home"> <i class="fas fa-check-circle"></i>  Credit Card / Debit Card 

				</a> 
				</div>	
			</li>
			<li  id="lbllazy">
				<a data-toggle="tab" href="#menu12" name="paymentTypeBtn" class="optType opt14"> 
				<i class="fas fa-check-circle"></i>  Book Now. Pay Later 																			 
				</a>
			</li>




			<li id="lbl4">
				<a data-toggle="tab" href="#menu10" name="paymentTypeBtn" class="optType  opt4" id="optl4" > 
				<img class="hide" src="/images/netbanking-logo.png" style="height: 28px;margin-top: -5px" alt='Net Banking'>
				<i class="fas fa-check-circle"></i> Net Banking
				</a>
			</li>
			<?
			if (!$isredirct)
			//if ($model->bkg_booking_type == 1 && !$isredirct)
			{
				?>
				<li id="cashpay">
					<a data-toggle="tab" href="#payl" name="paymentTypeBtn" class="optType  opt0" id="optl0" > 
					<i class="fas fa-check-circle"></i> Cash On Delivery
					</a>
				</li>
			<? } ?>
			</ul>
		</div>
		<div class="tab-content col-sm-9 search-panel-3">
			<div id="home" class="tab-pane fade in active">
			<div class="row hide">
				<div class="col-12">
				<h4 class="mt0">
					Credit Card / Debit Card
				</h4>
				</div>
			</div>
			</div>
			<div id="menu10" class="tab-pane fade ">
			<div class="row hide active">
				<div class="col-12">
				<h4 class="mt0">
					Net Banking
				</h4>
				</div>
			</div>
			</div>
			<div id="menu11" class="tab-pane fade  ">
			<div class="row ">
				<div class="col-12 hide">
				<h4 class="mt0">
					Mobile Wallets
				</h4>
				</div>
				<div class="col-12 text-left mb10">
				<div class="row">
					<label class="col-md-3 opt1 optType" id="lbl1">
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
				<div class="col-12">
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

				<div class="col-12" id="lazypaytext">
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
				<div class="col-12 mt15">
				<div class="row" id="checkEleBlock">
					<div class="col-12  ">
					<button id="lpbtn" class="btn btn-primary mb5" type="button" onclick="checkeligiblity()">Click to get approved for LazyPay</button>
					</div>
					<div class="col-md-12 mb10">
					<span class="lazyPayEleText text-success h5" id="lazyPayEleTextSucc" ></span>
					<span class="lazyPayEleText text-danger h5" id="lazyPayEleTextErr"  ></span>
					</div>
					<input type="hidden" id="chkele" value="0"> 
				</div>
				</div>
				<div class="col-12">
				<div id="btree" style="display: none" >
					<?= CHtml::errorSummary($ccmodel); ?>
					<div class="col-12 p0">
					<h4>Credit Card Information</h4>
					<div class="row form-group">
						<div class="col-12 col-md-6">
						<label>Name on Card</label>
						<div class="">
							<?php echo $form->textField($ccmodel, 'creditCard_name', array('class' => 'form-control')); ?>
							<span class = "has-error"><?php echo $form->error($ccmodel, 'creditCard_name'); ?></span>
						</div>
						</div>
						<div class="col-12 col-md-6">
						<label>Card Number</label>
						<div class="controls">
							<?php echo $form->numberField($ccmodel, 'creditCard_number', array('class' => 'form-control')); ?>
							<span class = "has-error"><?php echo $form->error($ccmodel, 'creditCard_number'); ?></span>
						</div>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-12 col-md-5">
						<label>Security Code (CVV)</label>
						<div class="controls">
							<?php echo $form->passwordField($ccmodel, 'creditCard_cvv', array('class' => 'form-control', 'autocomplete' => 'off')); ?>
							<span class = "has-error"><?php echo $form->error($ccmodel, 'creditCard_cvv'); ?></span>
						</div>
						</div>
						<div class="col-12 col-md-7"><label>Expiration Date (MM/YYYY)</label>
						<div class="row">
							<div class="col-5">
                            <div class="form-group">
							<?= $form->dropDownList($ccmodel,'creditCard_month',array('data' => $ccMonth), array('style' => 'padding:6px','class' => 'form-control','placeholder' => 'Expiration Month (MM)'));?>
                            <?php echo $form->error($ccmodel,'creditCard_month',['class' => 'help-block error']);?> 
                            </div>
							</div>
							<div class="col-1 pl0 h3 mt5">/</div>
							<div class="col-6 pl0">
                            <div class="form-group">
							<?php echo $form->dropDownList($ccmodel, 'creditCard_year',array('data'=> $ccYear),array('class' => 'form-control','placeholder' => 'Expiration Year (YYYY)'));?>
                            <?php echo $form->error($ccmodel,'creditCard_year',['class' => 'help-block error']);?>
                            </div>
							</div>
						</div>
						</div>
					</div>
					</div>
				</div>
				<div class="showcash form-control col-sm-6" style="display: none"></div>
				<div id = "ppayment" style="display: block">


					<?php
//					    $readOnlyPartialPay	 = [];
//					    $readOnlyPayTrainman	 = false;
//					    if ($model->bkg_agent_id == 655)
//					    {
//						$hidePayinfull		 = "none";
//						$readOnlyPartialPay	 = ["readOnly" => "readOnly"];
//						$readOnlyPayTrainman	 = true;
//					    }
//					    else
//					    {
//						$hidePayinfull = "block";
//					    }
					?>  

					<span class="mt10" id="pwords">Amount to pay </span>
					<div class="row">
						<div class="col-sm-6">
                        <div class="form-group">
						<?= $form->numberField($model, 'partialPayment',['class' => 'form-control clsPartialPayment', 'placeholder' => "Partial Payment", 'min' => $orderAmount, 'max' => $orderAmount,'readonly'=>'readonly']) ?>
                        <?php echo $form->error($model,'partialPayment',['class' => 'help-block error']);?>
                        </div>
					</div>

					<div class="col-6">

						

					</div>
					<div class="col-12 col-md-6" style="display: none" id="divDollar">
						<b><span id="amtDollar"></span></b> will be deducted from your Card
					</div>
					</div>
					<span class="mt0" id="pmaxpay" style="display: none">Maximum amount payable through paytm (per day) is &#x20B9;10,000</span>
				</div>

				</div>
				<div class="col-12 mt10">
				<input type="hidden" name="confBtns" id="confPayNow1" value="p1">
                <div class="form-group">
                   <div class="checkbox"><input id="ytVoucherOrder_vor_tnc" type="hidden" value="0" name="VoucherOrder[vor_tnc]"><label><input name="VoucherOrder[vor_tnc]" id="VoucherOrder_vor_tnc" value="1" type="checkbox"> I agree to the Gozo <a href="javascript:void(0);" onclick="opentns()">terms and conditions</a></label></div>
                   <div class="help-block error" id="VoucherOrder_vor_tnc_em_" style="display:none"></div>
                </div>
                <div id="error_div1" style="display: none" class="alert alert-block alert-danger"></div>
				</div>

				<div class="col-12 text-right  pr20">
				<input type="submit" value="Proceed" class="btn-orange pl30 pr30" id="proceedPayNow">
				<input type="button" value="Proceed" class="btn-orange pl30 pr30" style="display: none"  id="payubtn" >
				</div>
			</div>
			</div>
		</div>
		</div>
	</div>




<!---- right one upper ------------>




				<!-- payment widget end--->

				<?php $this->endWidget(); ?>
			</div>
			<?php
		}
		else
		{
			$strmsg1 = 'Your Payment link is expired.';
		}
		
		if ($strmsg1 != '')
		{
			?>
			<div class="row">
				<div class="col-12">
					<div class="h4 text-center pt10">
						<?= $strmsg1 ?>
					</div>
				</div>
			</div><?php
		}
		?>
	</div>
</div>
<input id="minipay" class="miniPay" type="hidden" value="<?= $orderAmount; ?>">

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
	$netAmount = '<?= $orderAmount ?>' | 0;
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
//		if (IsPopupBlocker()) {
//			$enablePayuBolt = 0;
//		}


		$('.stepper-arrow').remove();
		$('#pmaxpay').hide();
		//  $("#inlineCheckbox1").attr('checked', 'checked');
		calculatePayable();
		$('#creditpoints').click(function ()
		{
			useCredit();
		});
		$('#VoucherOrder_partialPayment').change(function ()
		{
			$('#creditpoints').removeAttr('checked');
		});
		showCountryCard();
		setTimeout(function(){ cartInd();  }, 600);
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

		if ($('#VoucherOrder_paymentType').val() != 3 &&
				$('#VoucherOrder_paymentType').val() != 12 &&
				$('#VoucherOrder_paymentType').val() != 10
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
		if ('<?= $model->vor_bill_country ?>' == '' || '<?= $model->vor_bill_country ?>' == 'IN') {
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
		$('#VoucherOrder_paymentType').val('0');
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
		$('#VoucherOrder_paymentType').val('3');
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
		$('#VoucherOrder_paymentType').val('11');
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
		$('#VoucherOrder_paymentType').val('4');
		$('#VoucherOrder_ebsOpt').val('1');
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
		$('#VoucherOrder_paymentType').val('9');
		if ($('#<?= CHtml::activeId($ccmodel, "creditCard_name") ?>').val() == '')
		{
			$('#<?= CHtml::activeId($ccmodel, "creditCard_name") ?>').val('<?= $model->vor_name ?>');
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
		$('#VoucherOrder_paymentType').val('4');
		$('#VoucherOrder_ebsOpt').val('2');
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
		$('#VoucherOrder_paymentType').val('6');
		//$('#VoucherOrder_ebsOpt').val('3');
		$('.optType').removeClass('active');
		$('.opt4').addClass('active');
		$('#creditpoints').removeAttr('checked');
		$('#divDollar').hide();
		useCredit();
		calculatePayable();
		if ($enablePayuBolt == 1) {
			$("#VoucherOrder_payubolt").val(1);
			$('#payubtn').show();
			$('#proceedPayNow').hide();
		}
	}
	function opt6Click()
	{
		// $('#cardAsk').hide();

		$('#btree').hide();
		$('#VoucherOrder_paymentType').val('6');
		$('.optType').removeClass('active');
		$('.opt6').addClass('active');
		$('#creditpoints').removeAttr('checked');
		$('#divDollar').hide();
		useCredit();
		calculatePayable();
		if ($enablePayuBolt == 1) {
			$("#VoucherOrder_payubolt").val(1);
			$('#payubtn').show();
			$('#proceedPayNow').hide();
		}

	}

	$('.opt10').click(function ()
	{
		$('#cardAsk').hide();
		$('#btree').hide();
		$('#VoucherOrder_paymentType').val('10');
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
		$('#VoucherOrder_paymentType').val('14');
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
		$('#VoucherOrder_paymentType').val('15');
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
			$('#VoucherOrder_partialPayment').attr("readonly", "readonly");
			if (dueAmt == '')
			{
				$('#VoucherOrder_partialPayment').val($('#max_amount').val());
			} else {
				$('#VoucherOrder_partialPayment').val(dueAmt);
			}

			$('#VoucherOrder_optPaymentOptions').val('1');
		} else
		{
			$('#VoucherOrder_partialPayment').val($('#minipay').val());
			$("#pwords").css('visibility', 'visible');

			
			$('#VoucherOrder_optPaymentOptions').val('2');
			$('#VoucherOrder_partialPayment').val($("#VoucherOrder_partialPayment").attr('min'));
		}
		if ($('#optl9').is(':checked'))
		{
			showDollar();
		}

	});
	$('#VoucherOrder_partialPayment').on('change', function ()
	{
		showDollar();
	});

	function showDollar()
	{
		$('#divDollar').show();
		$pamount = $('#VoucherOrder_partialPayment').val();
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
	$('#Booking_vor_bill_country111').on('change', function ()
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
			$('#VoucherOrder_paymentType').val('4');
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
		$('#VoucherOrder_paymentType').val('9');


		$('#btree').show();

		showDollar();
		if ($('#<?= CHtml::activeId($ccmodel, "creditCard_name") ?>').val() == '')
		{
			$('#<?= CHtml::activeId($ccmodel, "creditCard_name") ?>').val('<?= $model->vor_name ?>');
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

	$('#VoucherOrder_partialPayment').on('change', function ()
	{
		if ($('.opt9').hasClass('active'))
		{
			showDollar();
		} else
		{
			$('#divDollar').hide();
		}
	});
	$('#VoucherOrder_partialPayment').on('blur', function ()
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
		$("#VoucherOrder_payubolt").val(0);
		$('#payubtn').hide();
		$('#proceedPayNow').show();
		var maxpay = parseInt(Math.round($netAmount * 1));
		var minpay = $("#VoucherOrder_partialPayment").attr('min'); //parseInt(Math.round($netAmount * 0.15));
		if ($("#VoucherOrder_paymentType").val() == 3 && maxpay > 10000)
		{
			maxpay = 10000;
			$('#pmaxpay').show();
		} else
		{
			$('#pmaxpay').hide();
		}

		$('#max_amount').val(maxpay);

		$("#VoucherOrder_partialPayment").attr('max', maxpay);
		$("#VoucherOrder_partialPayment").attr('min', minpay);

		$("#VoucherOrder_partialPayment").val(minpay);
		if ($("#inlineCheckbox1").is(':checked'))
		{
			$('#VoucherOrder_partialPayment').attr("readonly", "readonly");
			$('#VoucherOrder_partialPayment').val($('#max_amount').val()).change();
			$("#pwords").css('visibility', 'hidden');
			$('#VoucherOrder_optPaymentOptions').val('1');
		} else
		{
			$("#pwords").css('visibility', 'visible');

			
			$('#VoucherOrder_optPaymentOptions').val('2');
			$('#VoucherOrder_partialPayment').val(minpay).change();
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
	$('#VoucherOrder_bkg_bill_contact').change(function ()
	{
		$('.lazyPayEleText').hide();
		$('.lazyPayEleText').text('');
		$("#chkele").val("0");
		if ($("#VoucherOrder_paymentType").val() == "14")
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
		if ($('#VoucherOrder_bkg_bill_contact').val() == '')
		{
			$('#VoucherOrder_bkg_bill_contact_em_').show();
			$('#VoucherOrder_bkg_bill_contact_em_').html('Contact cannot be blank.');
			errno++;
		}
		if ($('#VoucherOrder_bkg_bill_email').val() == '')
		{
			$('#VoucherOrder_bkg_bill_email_em_').show();
			$('#VoucherOrder_bkg_bill_email_em_').html('Billing email cannot be blank.');
			errno++;
		}
		if ($('#VoucherOrder_partialPayment').val() == '')
		{
			$('#VoucherOrder_partialPayment_em_').show();
			$('#VoucherOrder_partialPayment_em_').html('Billing amount cannot be blank.');
			errno++;
		}
		if (errno == 0)
		{
			$.ajax({
				"type": "GET",
				"dataType": "json",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('lazypay/checkeligiblitycall')) ?>",
				"data": {'phone': $('#VoucherOrder_bkg_bill_contact').val(),
					'email': $('#VoucherOrder_bkg_bill_email').val(),
					'amount': $('#VoucherOrder_partialPayment').val()
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
			var id = '<?= $model->vor_id ?>';
			var hash = '<?= Yii::app()->shortHash->hash($model->vor_id) ?>';
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
					   if ($('#VoucherOrder_vor_tnc').is(':checked'))
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
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('voucher/payment',['id' => $model->vor_id, 'hash' => Yii::app()->shortHash->hash($model->vor_id)])); ?>",
			"data": $("#payment-form1").serialize(),
			"success": function (data2) {
				if (data2.success) {
					launchBOLT1(data2);
				} else {
					ul = '<ul style="list-style-type: none;">';
					for (const value of Object.values(data2.error)) 
					{
						ul += '<li>'+value[0]+'</li>';
					}
					ul += '</ul>';		
					$("#error_div1").show();			
					$("#error_div1").html(ul);
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
		if ($('#VoucherOrder_vor_tnc').is(':checked'))
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
		if ($('#VoucherOrder_vor_tnc').is(':checked'))
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
	conValue = '<?= $model->vor_bill_country ?>';
	$(document).ready(function ()
	{
		$('#VoucherOrder_vor_bill_country').selectize();
		if ('<?= $model->vor_bill_country ?>' == '' || '<?= $model->vor_bill_country ?>' == 'IN') {
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
		$('#VoucherOrder_vor_bill_country').selectize({"options": options});
		$('#VoucherOrder_vor_bill_country')[0].selectize.clearOptions();
		$('#VoucherOrder_vor_bill_country')[0].selectize.addOption(options);
		$('#VoucherOrder_vor_bill_country')[0].selectize.setValue('IN');
	}
	function cartInt() {
		var href = '<?= Yii::app()->createUrl('index/countrynamejson') ?>';
		jQuery.ajax({type: 'GET', "dataType": "json", "data": {}, url: href,
			success: function (data1)
			{
				$('#VoucherOrder_vor_bill_country').selectize();
				$('#VoucherOrder_vor_bill_country')[0].selectize.clearOptions();
				$('#VoucherOrder_vor_bill_country')[0].selectize.addOption(data1);
				$('#VoucherOrder_vor_bill_country')[0].selectize.refreshOptions(false);
				if (conValue != '') {
					$('#VoucherOrder_vor_bill_country')[0].selectize.setValue(conValue);
					$('#VoucherOrder_vor_bill_country').val(conValue).change();
				} else {
					$('#VoucherOrder_vor_bill_country')[0].selectize.setValue('US');
					$('#VoucherOrder_vor_bill_country').val('US').change();
				}
			}
		});
	}
	function cartAll() {

		var href = '<?= Yii::app()->createUrl('index/countrynamejson') ?>';
		jQuery.ajax({type: 'GET', "dataType": "json", "data": {"excludeIndia": 0}, url: href,
			success: function (data1)
			{
				$('#VoucherOrder_vor_bill_country').selectize();
				$('#VoucherOrder_vor_bill_country')[0].selectize.clearOptions();
				$('#VoucherOrder_vor_bill_country')[0].selectize.addOption(data1);
				$('#VoucherOrder_vor_bill_country')[0].selectize.refreshOptions(false);
				$('#VoucherOrder_vor_bill_country')[0].selectize.setValue(conValue);
				$('#VoucherOrder_vor_bill_country').val(conValue).change();
			}
		});
	}

	function otheradd()
	{
		$(".devClass").val("");
	}
	
	$('.optType').click(function(event){
		if($(this).hasClass("active")){
			hrefValue = $(this).attr("href");
			//alert(hrefValue);
			if (typeof hrefValue != "undefined") {
				isMenuActive = hrefValue.substring(1);
				if(isMenuActive){
					//$('.tab-pane').removeClass('active');
					$(".search-panel-3").find(".tab-pane").removeClass("active");
					$('#'+isMenuActive).addClass('active').removeClass('fade');	
				}
			}
		}
	});
</script>	