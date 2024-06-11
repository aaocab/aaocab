<?php 
$totDays		 = floor(($model->bkg_trip_duration / 60) / 24) + 1;
$incArr			 = [0 => 'Excluded', 1 => 'Included'];
$tolltax_flag	 = $model->bkgInvoice->bkg_is_toll_tax_included;
$statetax_flag	 = $model->bkgInvoice->bkg_is_state_tax_included;
$tolltax_value	 = $model->bkgInvoice->bkg_toll_tax;
$statetax_value	 = $model->bkgInvoice->bkg_state_tax;
$taxStr			 = (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0)) ? '<i style="font-size:0.8em">(Toll Tax and State Tax not payable by customer)</i>' : '';
$extrKmCharge	 = '<br><i style="font-size:0.6em">(Charges after ' . $model->bkg_trip_distance . ' Km @ &nbsp;<i style="font-size:10px" class="fa">&#xf156;</i>' . round($model->bkgInvoice->bkg_rate_per_km_extra, 2) . '/km)</i>';
$scvVctId		 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
$estStr			 = '';
if ($model->bookingRoutes[0]->brt_from_location == '' || $model->bookingRoutes[(count($model->bookingRoutes) - 1)]->brt_to_location == '')
{
	$estStr = '<br><i style="font-size:0.8em">(city to city; final pickup and drop addresses not received)</i>';
}
else
{
	$estStr = '<br><i style="font-size:0.7em">(based on pickup and drop addresses provided)</i>';
}
//$staxrate	 = $model->bkgInvoice->getServiceTaxRate();
$serviceTaxRate				 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
$staxrate    = ($serviceTaxRate == 0)? 1 : $serviceTaxRate;
$taxLabel	 = ($serviceTaxRate == 5) ? 'GST' : 'Service Tax ';
?>
<div class="col-12 mb20">
    <div class="bg-white-box">
        <div class="row">
            <div class="col-12 col-md-9">
                <img src="<?= Yii::app()->createAbsoluteUrl('images/refer_friend.jpg?v1.2') ?>?v1.1" class="p0">
            </div>
            <div class="col-12 col-md-3">
                <a href="<?= Yii::app()->createAbsoluteUrl('users/FbShareTemplate', ['refcode' => $refcode]); ?>" target="_blank"><img src="<?= Yii::app()->createAbsoluteUrl('images/fbicon.jpg') ?>" ></a>

                <a href="https://web.whatsapp.com/send?text=<?= $whatappShareLink ?>" data-action="share/whatsapp/share" target="_blank"><img src="<?= Yii::app()->createAbsoluteUrl('images/watsappicon.jpg') ?>" class="p0"></a>
            </div>
        </div>
    </div>
</div>
<div class="col-12 mb20">
    <div class="bg-white-box pb0">
        <div class="font-20 mb10 text-uppercase"><b>Billing Details</b></div>
		<?php
		if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC && $model->bkg_flexxi_type == 1)
		{
			// FOR FLEXXI
		}
		else
		{
			?>
			<div class="row">
				<div class="col-md-12">
					<?
					if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC && $model->bkg_flexxi_type == 2)
					{
						?>
						<div class="row pt5 pb5">
							<div class="col-7 m0"></div>
							<div class="col-5 m0 red-color text-right"><b>Shared Fare</b></div>
						</div>
					<? } ?>
					<div class="row pt5 pb5">
						<div class="col-7 m0">Distance quoted of the trip: <span class="color-gray"><?= $estStr ?></span></div>
						<div class="col-5 m0 text-right"><?= $model->bkg_trip_distance ?> Km <span class="color-gray"><?= $extrKmCharge ?></span></div>
					</div>
					<div class="row pt5 pb5">
						<div class="col-7 m0">Total days for the trip: </div>
						<div class="col-5 m0 text-right"><?= $totDays ?> days</div>
					</div>
					<?php
					if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC && $model->bkg_flexxi_type == 2)
					{
						?>
						<div class="row pt5 pb5">
							<div class="col-7 m0">No of Seats <?= $taxStr ?>: </div>
							<div class="col-5 m0 red-color text-right"><b><?= $model->bkgAddInfo->bkg_no_person ?></b></div>
						</div>
					<?php }
					?>
					<div class="row pt5 pb5 oldBasefareDiv hide">
						<?php //echo json_encode($model->bkgInvoice);   ?>
						<div class="col-7 m0">Base Fare (City Center to City Center) <?= $taxStr ?>: </div>
						<div class="col-5 m0 text-right">&#x20B9;<span class="txtBaseFareOld"></span></div>
					</div>
					<div class="row pt5 pb5 extrachargeDiv hide">
						<div class="col-9 m0 text-danger">
							Additional km due to pickup/drop address changes (<span class="additionalKmVal"></span>km @₹<?= round($model->bkgInvoice->bkg_rate_per_km_extra, 2) ?>) 
	<!--							As pickup and drop address changed additional <span class="additionalKmVal"></span>Km added<br>
							Extra Km charge is @&#x20B9;<? //= round($model->bkgInvoice->bkg_rate_per_km_extra, 2)    ?>/km. -->
						</div>
						<div class="col-3 text-right">Extra charge <br>&#x20B9;<span class="extraChargeVal"></span></div>
					</div>
					<div class="row pt5 pb5">
						<?php //echo json_encode($model->bkgInvoice);   ?>
						<div class="col-8 m0">Base Fare <?= $taxStr ?>: </div>
						<div class="col-4 m0 text-right"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_base_amount ?></div>
					</div>
					<div class="row pt5 pb5 discounttd vwDiscount clsBilling text-danger <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide' ?>">
						<div class="col-7 m0">Discount Amount: 
							<span class="disPromoType"><strong> (<span  class="txtPromoCode"><?= $model->bkgInvoice['bkg_promo1_code'] ?></span> Applied)</strong></span>
						</div>
						<div class="col-5 m0 text-right">&#x20B9<span class="discountAmount txtDiscountAmount"><?= $model->bkgInvoice->bkg_discount_amount ?></span></div>
					</div>

					<div class="row pt5 pb5 clsBilling text-danger <?= ($model->bkgInvoice->bkg_extra_discount_amount > 0) ? '' : 'hide' ?>">
						<div class="col-7 m0">One-Time Price Adjustment:</div>
						<div class="col-5 m0 text-right">&#x20B9<span class="discountAmount txtDiscountAmount"><?= $model->bkgInvoice->bkg_extra_discount_amount ?></span></div>
					</div>

					<div class="row pt5 pb5 discounttd vwDiscount clsBilling <?= ($model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide' ?>">
						<div class="col-7 m0"><strong>Discounted Base Fare: </strong></div>
						<div class="col-5 m0 text-right">&#x20B9<strong><span class="actualAmount txtDiscountedBaseAmount"><?= ($model->bkgInvoice->bkg_base_amount - $model->bkgInvoice->bkg_net_discount_amount) ?></span></strong></div>
					</div>

					<div class="row pt5 pb5 discounttd vwAddonCharge clsBilling text-success <?= ($model->bkgInvoice->bkg_addon_charges > 0) ? '' : 'hide' ?>">
						<?php $addonModel =	Addons::model()->findByPk($model->bkgInvoice->bkg_addon_ids); ?>
						<div class="col-7 m0"><strong>Addon Charge:</strong> (<span  class="txtAddonCharge"><?= ($model->bkgInvoice->bkg_addon_ids > 0) ? $addonModel->adn_desc : ''; ?></span> Applied)</div>
						<div class="col-5 m0 text-right">&#x20B9<span class="addoncharge txtGozoAddonCharge"><?= $model->bkgInvoice->bkg_addon_charges ?></span></div>
					</div>

					<div class="row pt5 pb5 vwDriverAllowance clsBilling <?= ($model->bkgInvoice->bkg_driver_allowance_amount > 0) ? '' : 'hide' ?>">
						<div class="col-7 m0">Driver Allowance: </div>
						<div class="col-5 m0 text-right"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_driver_allowance_amount ?></div>
					</div>
					<div class="row pt5 pb5 additionalcharge <?= ($model->bkgInvoice->bkg_additional_charge > 0) ? '' : 'hide' ?>">
						<div class="col-7 m0 extrachargeremark">Additional Charges <?= $model->bkgInvoice->bkg_additional_charge_remark != '' ? '(' . $model->bkgInvoice->bkg_additional_charge_remark . ')' : ''; ?> :</div>
						<div class="col-5 m0 text-right extracharge"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_additional_charge ?></div>
					</div>
					<div class="row pt5 pb5 mb10 <?= ($model->bkgInvoice->bkg_cgst > 0) ? '' : 'hide' ?> ">
						<div class="col-7 m0">CGST (@<?= Yii::app()->params['cgst'] ?>%):</div>
						<div class="col-5 m0 text-right"><span>&#x20B9</span><?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
					</div>

					<div class="row pt5 pb5 <?= ($model->bkgInvoice->bkg_sgst > 0) ? '' : 'hide' ?>">
						<div class="col-7 m0">SGST (@<?= Yii::app()->params['sgst'] ?>%):</div>
						<div class="col-5 m0 text-right"><span>&#x20B9</span><?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></div>
					</div>
					<div class="row pt5 pb5 <?= ($serviceTaxRate != 5) ? '' : 'hide' ?>">
						<div class="col-7 summary-tr m0"><?= $taxLabel ?>: </div>
						<div class="col-5 summary-tr2 m0 text-right"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_service_tax ?></div>
					</div>

					<div class="row pt5 pb5 vwTollTax clsBilling <?= ($model->bkgInvoice->bkg_toll_tax > 0) ? '' : 'hide' ?>">
						<div class="col-7 m0">Toll Tax: </div>
						<div class="col-5 m0 text-right"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_toll_tax ?></div>
					</div>

                    <div class="row pt5 pb5 vwAirportCharge clsBilling <?= ($model->bkgInvoice->bkg_airport_entry_fee > 0) ? '' : 'hide' ?>">
						<div class="col-7 m0">Airport Entry Charges: </div>
						<div class="col-5 m0 text-right"><span>&#x20B9</span><?php echo $model->bkgInvoice->bkg_airport_entry_fee ?></div>
					</div>

					<div class="row pt5 pb5 vwStateTax clsBilling <?= ($model->bkgInvoice->bkg_state_tax > 0) ? '' : 'hide' ?>">
						<div class="col-7 m0 sum-height">Other Taxes: <br/><i style="font-size:0.8em">(Including State Tax / Green Tax etc)</i> </div>
						<div class="col-5 m0 text-right sum-height"><span>&#x20B9</span><?= $model->bkgInvoice->bkg_state_tax ?></div>
					</div>

					<div class="row pt5 pb5">
						<div class="col-7 m0">GST (@<?= Yii::app()->params['igst'] ?>%):</div>
						<div class="col-5 text-right">&#x20B9<span class="taxAmount txtGstAmount"><?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></span></div>
					</div>
					<div class="row gradient-green-blue radius-bottom-5 font-20 pt10 pb10">
						<div class="col-7 m0 sum-height"><b>Estimated Trip cost:</b></div>
						<div class="h4 col-5 m0 text-right">
							<b>&#x20B9<span class="etcAmount txtEstimatedAmount"><?= $model->bkgInvoice->bkg_total_amount ?></span></b><br>
							<span style="font-size:0.5em"><a href="http://www.aaocab.com/price-guarantee" target="_blank" style="color: white;">Best price guarantee</a></span>
						</div>
					</div>

					<div class="row pt5 pb5 tdadvance vwAdvancePaid clsBilling <?= ($model->bkgInvoice->bkg_advance_amount > 0 && $isredirct) ? '' : 'hide' ?>" >
						<div class="col-7 m0 sum-height"><?= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Advance Paid' : 'Advance received by partner' ?>: </div>
						<div class="col-5 m0 text-right">&#x20B9<span  class="txtAdvancePaid"><?= round($model->bkgInvoice->bkg_advance_amount) ?></span></div>
					</div>
					<div class="row pt5 pb5 ispromo vwGozoCoinsUsed clsBilling  <?= ($model->bkgInvoice->bkg_credits_used > 0 && $isredirct) ? '' : 'hide' ?>" >
						<div class="col-7 m0 sum-height">Gozo Coins Applied: </div>
						<div class="col-5 m0 text-right"><span>&#x20B9</span><span class="txtGozoCoinsUsed" id="gozoCoinsUsed"><?= $model->bkgInvoice->bkg_credits_used ?></span></div>
					</div>
					<div class="row pt5 pb5 ispromo  vwDueAmount clsBilling <?= (($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used > 0) && $isredirct) ? '' : 'hide' ?>">
						<div class="col-7 m0 sum-height"><b><?= ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0) ? 'Amount Due' : 'Amount  payable to driver' ?>: </b></div>
						<div class="col-5 m0 text-right"><b>&#x20B9<span class="txtDueAmount"><?= ($model->bkgInvoice->bkg_corporate_remunerator == 2 && $model->bkg_agent_id > 0) ? 0 : $model->bkgInvoice->bkg_due_amount ?></span></b></div>
					</div>


					<!--<div class="row pt5 pb5 tdcredit " style="display: none">
													<div class=" col-7 m0 sum-height">Gozo Coins Applied: </div>
													<div class=" col-5 m0 text-right"><span>&#x20B9</span><span class="creditUsed"><? //= $model->bkgInvoice->bkg_credits_used     ?></span></div>
											</div>-->
					<div class="row pt5 pb5 tdwallet vwWalletUsed clsBilling <?= ($model->bkgInvoice->bkg_wallet_used > 0) ? '' : 'hide' ?>">
						<div class=" col-7 m0 sum-height">Wallet balance to be applied: </div>
						<div class=" col-5 m0 text-right">&#x20B9<span class="walletUsed">0</span></div>
					</div>
					<!--<div class="row pt5 pb5  tdcredit tddue" style="display: none">
													<div class=" col-7 m0 sum-height"><b>Due Amount: </b></div>
													<div class=" col-5 m0 text-right"><b><span>&#x20B9</span><span class="bkgamtdetails111"><? //= $model->bkgInvoice->bkg_due_amount     ?></span></b></div>
											</div>-->
				</div>
			</div>
		<? } ?>
    </div>
</div>
<div class="col-12 mb20">
    <div class="bg-white-box">
		<? $hash = Yii::app()->shortHash->hash($model->bkg_id) ?>
		<b>Once the booking is confirmed & payment is received, CLICK <a href="<?= Yii::app()->createUrl('index/epass', array('bkgid' => $model->bkg_id, 'hash' => $hash)) ?>" target="_blank">HERE</a> to get Driver & Cab details for securing e-Pass</b>
	</div>
</div>

<script src="https://apis.google.com/js/platform.js" async defer></script>