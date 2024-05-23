<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
$orderAmount = $model->bkgInvoice->calculateMinPayment();
if ($minPay > 0)
{
	$orderAmount = $minPay;
}
//$minPerc = (in_array($model->bkg_booking_type,[9,10,11])) ? '50%' : '20%';
$minPerc				 = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id,$model->bkgPref->bkg_is_gozonow);
$advance				 = $model->bkgInvoice->getAdvanceReceived();
$maxPaymentWithDiscount	 = round($model->bkgInvoice->bkg_total_amount) - $advance;
$countryList			 = Filter::getCountryList();
$freeCancellationEnd	 = CancellationPolicyRule::getCancelationTimeRange($model->bkg_id, 1);
$isPromoApplicable		 = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
?>

<style>
    .btn-card-outline.active,
    .btn-card-outline:focus,
    .btn-card-outline:hover{
		background-color: #eeeeee;
		color: #545454;
    }
    .btn-card-outline{ width: auto; display: inline-block;
            background: #0d4da7;
		border-radius: 4px;
            color: #fff;
		padding: 4px;
		cursor: pointer;
    }
    .btn-card-outline input[type="radio"] { opacity: 0.01; z-index: 100;}
	.noradio{
		border:1px solid #5e5e5e;
		border-radius: 4px;
		margin: auto;
		text-align: center
	}
	.noradio input[type="radio"] { opacity: 0.01; z-index: 100;}
	.noradio   img{display:  inline-block; height: 1.5em;  }
    .accordion a i:last-child{ right: 12px;}
</style>
<div class="p0 accordion-path">
	<?php
	if ($model->bkgInvoice->bkg_advance_amount == 0 && $model->bkg_booking_type != 7 && $isPromoApplicable)
	{
		?>
		<div class="accordion accordion-style-0">
			<div class="accordion-border">
				<a href="javascript:void(0)" class="font18" data-accordion="accordion-5">OFFERS & DISCOUNT<!--<i class="fa fa-plus"></i>--></a>
				<div>
					<div class="accordion-text">

						<div class="line-height18 radio bottom-0">
							<div class="display-ini">
								<div class="float-left"><img src="/images/discount.png"  alt="Promo Code | Gozo Coins" title="Promo Code | Gozo Coins" width="40"></div>
								<div class="disPromoShow color-gray pt15">
									<span class="distxtwallet <?= ($model->bkgInvoice->bkg_wallet_used > 0) ? 'color-highlight bolder' : '' ?>">Wallet</span> | <span class="distxtpromo <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? 'color-highlight bolder' : '' ?>">Promo Code</span> | <span class="distxtgozo <?= ($model->bkgInvoice->bkg_credits_used > 0) ? 'color-highlight bolder' : '' ?>">Gozo Coins</span></div>
								<a href="javascript:void(0);" data-menu="sidebar-right-over2" class="btn-submit-orange font-14 pull-right line-height18 bolder" style="height: 30px; margin-top: -18px;">Apply</a>
								<div class="clear"></div>
							</div>
						</div>

					</div>

				</div>
				<div class="clear"></div>

			</div>
		</div>
	<?php } ?>
	<div id="sidebar-right-over3" data-selected="menu-components" class="menu-box menu-bottom sidebar-widget-style p20" style="transition: all 300ms ease 0s;">
		<div class="menu-title p0">
			<h1 class="font-24">Fare Details</h1>
			<a href="javascript:void(0);" class="menu-hide pt0"><i class="fa fa-times"></i></a>
		</div>
		<div class="content p0 bottom-0  oldBasefareDiv hide">
			<div class="line-s p0">Base Fare (City Center to City Center)</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><span class="txtBaseFareOld"></span></div>
		</div>
		<div class="content p0 bottom-0 extrachargeDiv  hide">
			<div class="line-s p0" style="color: #FF1188">Additional km due to pickup/drop address changes(<span class="additionalKmVal"></span>km @₹<?= round($model->bkgInvoice->bkg_rate_per_km_extra, 2) ?>)<br>Extra charge </div>
			<div class="line-t p0 text-right"><br>&#x20b9<span class="extraChargeVal"></span></div>
		</div>
		<div class="content p0 bottom-0 vwBaseFare clsBilling">
			<div class="line-s p0">Base Fare:</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><span class="txtBaseFare"><?= $model->bkgInvoice->bkg_base_amount ?></span></div>
			<div class="clear"></div>
		</div>
		<div class="content discountFare p0 bottom-0 discounttd vwDiscount clsBilling <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide' ?>">
			<div class="line-s p0">Discounted Base Fare:</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><strong><span class="actualAmount txtDiscountedBaseAmount"><?= ($model->bkgInvoice->bkg_base_amount - $model->bkgInvoice->bkg_discount_amount) ?></span></strong></div>
		</div>
		<div class="content p0 bottom-0 vwAddOnCharge clsBilling <?= ($model->bkgInvoice->bkg_addon_charges > 0) ? '' : 'hide'; ?>">
			<div class="line-s p0">Add On Charge:(<strong><span  class="txtAddonLabel"></span> Applied</strong>)</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><span class="txtAddOnCharge"><?= $model->bkgInvoice->bkg_addon_charges ?></span></div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>	
		<div class="content p0 bottom-0 additionalcharge <?= ($model->bkgInvoice->bkg_additional_charge > 0) ? '' : 'hide' ?>">
			<div class="line-s p0 extrachargeremark">Additional Charges <?= $model->bkgInvoice->bkg_additional_charge_remark != '' ? '(' . $model->bkgInvoice->bkg_additional_charge_remark . ')' : ''; ?> :</div>
			<div class="line-t p0 text-right extracharge"><span>&#x20b9</span><?= $model->bkgInvoice->bkg_additional_charge ?></div>
		</div>
		<? $staxrate = $model->bkgInvoice->getServiceTaxRate(); ?>					
		<div class="content p0 bottom-0 <?= ($model->bkgInvoice->bkg_cgst > 0) ? '' : 'hide' ?>">
			<div class="line-s p0">CGST (@<?= Yii::app()->params['cgst'] ?>%):</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
		</div>	
		<div class="clear"></div>					
		<div class="content p0 bottom-0 <?= ($model->bkgInvoice->bkg_sgst > 0) ? '' : 'hide' ?>">
			<div class="line-s p0">SGST (@<?= Yii::app()->params['sgst'] ?>%):</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
		</div>
		<div class="clear"></div>					
		<div class="content p0 bottom-0  vwDriverAllowance clsBilling <?= ($model->bkgInvoice->bkg_driver_allowance_amount > 0) ? '' : 'hide' ?>">
			<div class="line-s p0">Driver Allowance:</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><span class="txtDriverAllowance"><?= $model->bkgInvoice->bkg_driver_allowance_amount; ?></span></div>
			<div class="clear"></div>
		</div>						
		<div class="content p0 bottom-0 vwTollTax clsBilling <?= ($model->bkgInvoice->bkg_toll_tax > 0) ? '' : 'hide' ?>">
			<div class="line-s p0">Toll Tax:</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><span class="tolltax txtTollTax"><?= $model->bkgInvoice->bkg_toll_tax ?></span></div>
			<div class="clear"></div>
		</div>
		<div class="content p0 bottom-0 vwAirportCharge clsBilling <?= ($model->bkgInvoice->bkg_airport_entry_fee > 0) ? '' : 'hide' ?>">
			<div class="line-s p0">Airport Entry Charge:</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><span class="txtAirportFee"><?php echo $model->bkgInvoice->bkg_airport_entry_fee ?></span></div>
			<div class="clear"></div>
		</div>
		<div class="content p0 bottom-0 vwStateTax clsBilling <?= ($model->bkgInvoice->bkg_state_tax > 0) ? '' : 'hide' ?>">
			<div class="line-s p0 pt5 pb5"><p class="bottom-0 line-height14 color-black">Other Taxes:</p><p class="bottom-0 line-height14"><i style="font-size:0.8em">(Including State Tax / Green Tax etc)</i></p></div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><span  class="txtStateTax"><?= $model->bkgInvoice->bkg_state_tax ?></span></div>
			<div class="clear"></div>
		</div>						
		<div class="content p0 bottom-0">
			<div class="line-s p0">GST (@<?= Yii::app()->params['igst'] ?>%):</div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><span class="taxAmount txtGstAmount"><?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></span></div>
		</div>
		<div class="clear"></div>	
		<div class="content p0 bottom-0 discounttd vwDiscount clsBilling  <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide' ?>">
			<div class="line-s p0">Discount <span class="disPromoType font-11">(Promo: <strong><span class="txtPromoCode"><?= $model->bkgInvoice['bkg_promo1_code'] ?></span> </strong> )</span></div>
			<div class="line-t p0 text-right">-<span>&#x20b9</span><span class="discountAmount txtDiscountAmount"><?= $model->bkgInvoice->bkg_discount_amount ?></span></div>
		</div>
		<div class="clear"></div>
		<div class="tdcredit vwGozoCoinsUsed clsBilling <?= ($model->bkgInvoice->bkg_credits_used > 0) ? '' : 'hide' ?>" >
			<div class="line-s p0">Gozo Coins Applied: </div>
			<div class="line-t text-right p0"><span>&#x20b9</span><span class="creditUsed txtGozoCoinsUsed"><?= $model->bkgInvoice->bkg_credits_used ?></span></div>
			<div class="clear"></div>
		</div>
		<div class="tdwallet vwWalletUsed clsBilling <?= ($model->bkgInvoice->bkg_wallet_used > 0) ? '' : 'hide' ?>">
			<div class="line-s p0">Wallet balance to be applied: </div>
			<div class="line-t p0 text-right"><span>&#x20b9</span><span class="walletUsed"></span></div>
			<div class="clear"></div>
		</div>
		<!--					<div class="content p0 bottom-0 m0 mb10 vwDueAmount">
								<div class="line-s pt5 font-14 pl0"><b> <? //= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Amount Due' : 'Amount  payable to driver'                    ?>:</b></div>
								<div class="line-t text-right font18 pt5 pr0"><span>&#x20b9</span><b><span class="dueAmount txtDueAmount"><? //= ($model->bkgInvoice->bkg_corporate_remunerator == 2 && $model->bkg_agent_id > 0) ? 0 : $model->bkgInvoice->bkg_due_amount                    ?></span></b></div>
								<div class="clear"></div>
							</div>-->
		<!--<div class="content p0 bottom-15">-->
		<?php
		if ($model['bkg_booking_type'] != 7)
		{
			?>

			<div class="border-gray-bottom mt10 mb10"></div>
			<div class="block-color mb10">
				<div class="one-half line-height16">
					<span class="font16"><b>Total Amount</b></span><br/> 

				</div>

				<div class="one-half last-column text-right font-16 pt5">
					&#x20B9<b><span class="payBoxTotalAmount"><?= round($model->bkgInvoice->bkg_total_amount) ?></span></b>
				</div>
				<div class="clear"></div>
				<div class="content m0 p0" style="position: relative; top: -11px;">
					<?php
					if ($model['bkg_booking_type'] != 7)
					{

						if (($model->quote->routeRates->isTollIncluded != null && $model->quote->routeRates->isTollIncluded > 0) || $model->bkgInvoice->bkg_is_toll_tax_included > 0)
						{
							?>
							<span class="font-11 color-gray">Toll Tax (Included)</span>
							<?php
							echo ', ';
						}
						?>
						<?php
						if (($model->quote->routeRates->isTollIncluded != null && $model->quote->routeRates->isTollIncluded <= 0) || $model->bkgInvoice->bkg_is_toll_tax_included <= 0)
						{
							?>
							<span class="font-11 color-gray">Toll Tax (Excluded)</span>
							<?php
							echo ', ';
						}
						?>
						<?php
						if (($model->quote->routeRates->isTollIncluded != null && $model->quote->routeRates->isStateTaxIncluded > 0) || $model->bkgInvoice->bkg_is_state_tax_included > 0)
						{
							?>
							<span class="font-11 color-gray">State Tax (Included)</span>
							<?php
							//echo ', ';
						}
						?>
						<?php
						if (($model->quote->routeRates->isTollIncluded != null && $model->quote->routeRates->isStateTaxIncluded <= 0) || $model->bkgInvoice->bkg_is_state_tax_included <= 0)
						{
							?>
							State Tax (Excluded)
							<?php
						}
					}
					?>
				</div>
				<div class="content p0 bottom-0 vwAdvancePaid clsBilling <?= ($model->bkgInvoice->bkg_advance_amount > 0) ? '' : 'hide' ?>">
					<div class="line-s p0"><?= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Advance Paid' : 'Advance received by partner' ?>:</div>
					<div class="line-t p0 text-right"><span>&#x20b9</span><span  class="txtAdvancePaid"><?= $model->bkgInvoice->bkg_advance_amount ?></span></div>
					<div class="clear"></div>
				</div>
				<div class="<?= ($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used > 0) ? '' : 'hide' ?>">
					<div class="border-gray-bottom mt10 mb10"></div>
					<div class="content p0 bottom-0 m0 mb10 vwDueAmount">
						<div class="line-s pt5 font-14 pl0"><b> <?= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Amount Due' : 'Amount  payable to driver' ?>:</b></div>
						<div class="line-t text-right font18 pt5 pr0"><span>&#x20b9</span><b><span class="dueAmount txtDueAmount"><?= ($model->bkgInvoice->bkg_corporate_remunerator == 2 && $model->bkg_agent_id > 0) ? 0 : $model->bkgInvoice->bkg_due_amount ?></span></b></div>
						<div class="clear"></div>
					</div>
					<!--								<div class="one-half line-height16">
														<span class="font16"><b>Due Amount</b></span><br/> 
					
													</div>
					
													<div class="one-half last-column text-right font-16 pt5">
														&#x20B9<b><span class=""><?= ($model->bkgInvoice->bkg_corporate_remunerator == 2 && $model->bkg_agent_id > 0) ? 0 : $model->bkgInvoice->bkg_due_amount ?></span></b>
													</div>-->
				</div>

			</div>
		<?php } ?>
	</div>
	<!--	<div class="accordion accordion-style-0 hide">
			<div class="accordion-border">
				<a href="javascript:void(0)" class="font18" data-accordion="accordion-1">PAYMENT<i class="fa fa-plus"></i></a>
				<div class="accordion-content" id="accordion-1" style="display: block;"> <div class="accordion-text">-->
	<div>

		<div><a href="#" data-menu="sidebar-right-over-card" class="header-icon header-icon-2 hide"></a></div>
		<input type="hidden" value="0" class="isCardInfo" name="isCardInfo">
		<div id="sidebar-right-over-card" data-selected="menu-components" data-width="300" data-height="550" class="menu-box menu-modal">			
			<div class="menu-title">
				<h1>Payment options</h1>
				<a href="#" class="menu-hide pt0"><i class="fa fa-times" style="left:10px;"></i></a>
			</div>
			<div class="content mb0">
				<?php
				if ($model['bkg_booking_type'] != 7)
				{
					?>
					<div class="pay-link line-height16 radio top-10" >
						<label class="radio-style">
							<input type="radio" name="payChk" id="minPayChk" value="0" checked="checked" class="mt5 payChk"> <span class="font-14 p5">Pay advance (<?= $minPerc ?>%)</span><span class="pull-right font-16 mt10">&#x20B9<span class="payBoxMinAmount"><b><?= $orderAmount ?></b></span></span><br><span class="color-gray pl20">rest pay to driver</span>
							<span class="checkmark"></span>
						</label>
					</div>


				<?php } ?>
				<div class="pay-link line-height16 radio" >
					<label class="radio-style pb5 pt5 line-height22">
						<input type="radio" name="payChk" id="fullPayChk" value="1" class="mt5 payChk"> <span class="font-14 p5">Pay full amount</span><span class="pull-right font-16">&#x20B9<span class="payBoxDueAmount"><b><?= $maxPaymentWithDiscount ?></b></span></span>
						<span class="checkmark"></span>
					</label>
				</div>

				<hr/>

				<!--   <div class="pay-panel mb20">
					 <span id="minpayval" class="clsMinPay">Pay advance<span>&#x20b9</span> <?php echo $orderAmount ?></span><a id="payBoxBtn" class="shadow-medium">Proceed to pay</a>
				 </div>-->
				<div class="border-gray-bottom mt10 mb10"></div>
				<div class="clear"></div>
				<?php
				foreach ($freeCancellationEnd as $key => $value)
				{
					if ($value['CancelCharge'] == 0)
					{
						?>
						<div class="payHeader">Free Cancellation applicable before<br> <span class="label-orange color-white" style="padding: 3px 7px;"><b><?= $freeCancellationEnd[0]['ToDate'] ?></b></span></div>
						<?php
					}
				}
				?>
			</div>
			<div class="clear"></div>
			<div class="menu-page p15 radio-style">
				<div class="radio">
					<div class="text-left font-16 mb5"><b>I will pay with</b></div>
					<div class="hide ">
						<label class="radio-style btn-card-outline hide">
							<input type="radio" name="intChk" id="isInd" value="0" checked="checked" class="mt5">
							<span class="font-14 pr5">Indian card</span>
						</label>
						<label class="radio-style btn-card-outline hide">
							<input type="radio" name="intChk" id="isInt" value="1" class="mt5"> 
							<span class="font-14 pr5">International card</span>
						</label>
					</div>
					<div class="radio-style   mt20 p5    noradio intPay payu">
						<input type="radio" name="intPay" id="payu" value="1" class="mr20 n  intPay"  checked="checked"> 
						<span class="font-13 p5 pt10">
							Credit/Debit Card | Net banking | Wallet | UPI
						</span>
					</div>

					<div class="radio-style mt20 p5 pt10 noradio intPay ptm">
						<input type="radio" name="intPay" id="ptm" value="0"  class="mr15 n  intPay">
						<span class="p5 pt10">
							<img src="/images/paytm.png">
						</span>
					</div>

					<label class="checkbox_style hide">
						<input type="checkbox" name="isInt" id="isInt" value="1" class="mt5"> 
						<span class="font-14 p5"><b>Card issuing bank outside India</b></span>							 
					</label>
				</div>
				<div class="billShow hide select-box select-box-1 top-0 bottom-10" style="background-color: #fff;display: none">	
					<select class="billShow hide success" id="bkg_bill_country"  name="BookingUser[bkg_bill_country]" placeholder="Select Country">
						<?
						foreach ($countryList as $id => $val)
						{
							echo '<option value="' . $id . '">' . $val['name'] . '</option>';
						}
						?>

					</select>

				</div>
				<div class="billShow hide input-simple-1 has-icon input-green success bottom-10">	
					<input type="text" id="bkg_bill_address" name="BookingUser[bkg_bill_address]" placeholder="Enter BillingAddress*">
				</div>
				<div class="billShow hide input-simple-1 has-icon input-green success bottom-10">	
					<input type="text" id="bkg_bill_city" name="BookingUser[bkg_bill_city]" placeholder="Enter City*">
				</div>
				<div class="billShow bottom-20 hide input-simple-1 has-icon input-green success bottom-10">	
					<input type="text" id="bkg_bill_postalcode" class="form-control "  name="BookingUser[bkg_bill_postalcode]" placeholder="Enter Postal Code*">
				</div>
				<div class="clear"></div>
				<div class="billShow hide"><button type="submit" class="uppercase btn-orange shadow-medium pt5 pb5 pl10 pr10" onclick="payCardInfo();">Submit</button></div>
			</div>

		</div>
	</div>

	<!--				</div>
					<div class="clear"></div>
	
				</div>
			</div>-->


</div>
<div class="content-padding p5 pt10 pb10 fixed-widget-content">
	<div class="one-half pl10 mr10"><span class="font-16 color-gray minpaytext">Total Amount</span><br><span class="font-18">&#x20b9;</span><span class="font-18 bolder clsMinPay" id="minpayval"><?= $maxPaymentWithDiscount ?></span><a href="javascript:void(0);" data-menu="sidebar-right-over3" class="inline-block"><i class="fas fa-info-circle font-16 pl5 pr5 color-gray"></i></a></div>
	<div class="one-half last-column"><a id="payBoxBtn" class="btn-2 mr5 font-14">Proceed to pay &#x20B9<span class="minDueAmount"><?php echo $orderAmount ?></span></a>
	</div>
</div>
<script>
	var minAmt;
	var dueAmt;
	$('.payChk').click(function ()
	{
		if (this.value == 0)
		{
			minAmt = $('.payBoxMinAmount').text();
			$("#minpayval").html(minAmt);
			$(".minpaytext").html('Pay advance');
			$("#BookingInvoice_partialPayment").val(minAmt);
			payFullUnChecked();
		} else
		{
			dueAmt = $('.payBoxDueAmount').text();
			$("#minpayval").html(dueAmt);
			$(".minpaytext").html('Pay full');
			$('#max_amount').val(dueAmt);
			$("#BookingInvoice_partialPayment").val(dueAmt);
			payFullChecked();
		}
	});
	$('#isInt').on('click', function () {
		if ($('#isInt').is(':checked')) {
			$('.billShow').show();
			$(this).parent().addClass('active');
			$(this).parent().prev('label').removeClass('active');
		} else {
			$('.billShow').hide();
			$(this).parent().removeClass('active');
			$(this).parent().prev('label').addClass('active');
		}
	});
	$('#isInd').on('click', function () {

		if ($('#isInd').is(':checked')) {
			$('.billShow').hide();
			payCardInfo();
		} else {
			$('.billShow').show();

		}
	});
	$('.intPay').on('click', function () {
		payCardInfo();
	});

	$(function () {
		var bookingType = <?= $model->bkg_booking_type ?>;
		if (bookingType == 7) {
			$('#fullPayChk').click();
		}
	});
	if (<?php echo ($model->bkgInvoice->bkg_wallet_used | 0) ?> > 0) {
		$('.tdwallet').removeClass('hide');
		$('.walletUsed').text(<?php echo $model->bkgInvoice->bkg_wallet_used ?>);
		$('#BookingInvoice_bkg_wallet_used').val(<?php echo $model->bkgInvoice->bkg_wallet_used ?>).change();
		updateRemaining();
	}

	$('#walletapply').on('click', function () {
		prmObj.applyPromo(5, $('#BookingInvoice_bkg_wallet_used').val());
		updateRemaining();

	});

	function updateRemaining() {

		$wBalance = parseInt(<?php echo $walletBalance ?> | 0);
		$remainingWallet = $('#BookingInvoice_bkg_wallet_used').val();
		$('#remainingWallet').text(Math.max($wBalance - $remainingWallet, 0));
	}
	$('#walletRemove').on('click', function () {

		prmObj.applyPromo(6);
		$wBalance = parseInt(<?php echo $walletBalance ?> | 0);
		$('#remainingWallet').text($wBalance);
	})
</script>