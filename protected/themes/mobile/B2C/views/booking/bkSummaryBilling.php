<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="content-boxed-widget p0 accordion-path">
	<div class="accordion accordion-style-0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-1">PAYMENT<i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-1" style="display: block;">
                <div class="accordion-text">
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
					</div>
					<div class="clear"></div>
					
					<div class="content discountFare p0 bottom-0 discounttd vwDiscount clsBilling <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide' ?>">
						<div class="line-s p0">Discounted Base Fare:</div>
						<div class="line-t p0 text-right"><span>&#x20b9</span><strong><span class="actualAmount txtDiscountedBaseAmount"><?= ($model->bkgInvoice->bkg_base_amount - $model->bkgInvoice->bkg_discount_amount) ?></span></strong></div>
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
                                            <div class="line-s p0 pb5"><p class="bottom-0 line-height18 color-black">Other Taxes:</p><p class="bottom-0 line-height14"><i style="font-size:0.8em">(Including State Tax / Green Tax etc)</i></p></div>
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


					<div class="content p0 bottom-0 vwAdvancePaid clsBilling <?= ($model->bkgInvoice->bkg_advance_amount > 0) ? '' : 'hide' ?>">
						<div class="line-s p0"><?= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Advance Paid' : 'Advance received by partner' ?>:</div>
						<div class="line-t p0 text-right"><span>&#x20b9</span><span  class="txtAdvancePaid"><?= $model->bkgInvoice->bkg_advance_amount ?></span></div>
						<div class="clear"></div>
					</div>

                    <div class="tdcredit vwGozoCoinsUsed clsBilling <?= ($model->bkgInvoice->bkg_credits_used > 0) ? '' : 'hide' ?>" >
						<div class="line-s">Gozo Coins Applied: </div>
						<div class="line-t text-right"><span>&#x20b9</span><span class="creditUsed txtGozoCoinsUsed"><?= $model->bkgInvoice->bkg_credits_used ?></span></div>
						<div class="clear"></div>
					</div>
                    <div class="tdwallet vwWalletUsed clsBilling <?= ($model->bkgInvoice->bkg_wallet_used > 0) ? '' : 'hide' ?>">
						<div class="line-s p0">Wallet balance to be applied: </div>
						<div class="line-t p0 text-right"><span>&#x20b9</span><span class="walletUsed"></span></div>
						<div class="clear"></div>
					</div>
<!--					<div class="content p0 bottom-0 m0 mb10 vwDueAmount">
						<div class="line-s pt5 font-14 pl0"><b> <?//= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Amount Due' : 'Amount  payable to driver' ?>:</b></div>
						<div class="line-t text-right font18 pt5 pr0"><span>&#x20b9</span><b><span class="dueAmount txtDueAmount"><?//= ($model->bkgInvoice->bkg_corporate_remunerator == 2 && $model->bkg_agent_id > 0) ? 0 : $model->bkgInvoice->bkg_due_amount ?></span></b></div>
						<div class="clear"></div>
					</div>-->


				</div>
				<div class="clear"></div>

			</div>
		</div>


	</div>