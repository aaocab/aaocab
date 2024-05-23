<style>
	.text-color-red{
		color: red !important;
	}
</style>
<?
/* @var $model Booking */
$gozoAmount			 = ($model->bkgInvoice->bkg_gozo_amount != '') ? $model->bkgInvoice->bkg_gozo_amount : $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->bkg_vendor_amount;
$dueAmount			 = ($model->bkgInvoice->bkg_due_amount != '') ? $model->bkgInvoice->bkg_due_amount : $model->bkgInvoice->bkg_total_amount - $model->bkgInvoice->getTotalPayment();
$grossAmount		 = $model->bkgInvoice->calculateGrossAmount();
$tabclass			 = ($minheight) ? "main-tab2-$minheight" : 'main-tab2';
$checkAccess		 = Yii::app()->user->checkAccess('ConfidentialBookingDetails');
//$bcRow			 = BookingCab::model()->getTripGozoAmountByBkgID($model->bkg_id);
//$tripGozoAmount	 = $bcRow['gozoAmount'];
//$noOfBidByBkgId  = BookingVendorRequest::model()->getBidCountByBkgID($model->bkg_id);
?>
<?php
$bookingRouteModel	 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $model->bkg_id]);
?>
<div class="<?= $tabclass ?>">
    <div class="col-xs-12 col-sm-6 p0">
        <div class="<?= $tabclass ?>">
            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Base Fare:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_base_amount ?></div>
            </div>

			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Additional Charge:</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_additional_charge ?></div>
			</div>


			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>COD Charge: </b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_convenience_charge ?></div>
			</div>

			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Extra charges(<?= $model->bkgInvoice->bkg_extra_km; ?> kms ) : </b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_extra_km_charge ?></div>
			</div>
			<?
			if (in_array($model->bkg_booking_type, [9, 10, 11]))
			{
				?>
				<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>Extra Minutes(<?= $model->bkgInvoice->bkg_extra_min; ?> Min):</b></div>
					<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_extra_total_min_charge; ?></div>
				</div>
			<? }
			?>

			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Discount <?php
						if ($model->bkgInvoice->bkg_promo1_code != null && (($model->bkgInvoice->bkg_discount_amount >= $model->bkgInvoice->bkg_promo1_amt && $model->bkgInvoice->bkg_promo1_amt > 0) || $model->bkgInvoice->bkg_promo1_amt == 0))
						{
							?>(Promo: <span class="color-green"><?= $model->bkgInvoice->bkg_promo1_code ?></span>)<?php } ?>:</b></div>
				<div class="col-xs-6 text-right text-color-red">(-)<i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_discount_amount ?> </div>
			</div>

			<div class="row p5 new-tab2 <?= ($model->bkgInvoice->bkg_extra_discount_amount > 0) ? "" : "hide" ?>">
				<div class="col-xs-6"><b>One-Time Adjustment:</b></div>
				<div class="col-xs-6 text-right text-color-red">(-)<i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_extra_discount_amount ?> </div>
			</div>

			<div class="row p5 new-tab2">
				<?php
				if ($model->bkgInvoice->bkg_addon_charges <> 0)
				{
					?>
					<div class="col-xs-6" onclick="showIncExc('addon')"> <b>[+]Addon Charge  :</b></div>
					<?
				}
				else
				{
					?><div class="col-xs-6"><b> Addon Charge :</b></div><?
				}
				?>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_addon_charges != '') ? round($model->bkgInvoice->bkg_addon_charges) : 0 ?></div>
				<div id="addonDesc" class="col-xs-12 pl15 hide">
					<?php
					$shOpt			 = 0;
					$addonDetailsArr = $model->getAppliedAddonList();
					foreach ($addonDetailsArr as $addonDetails)
					{
						echo ($shOpt > 0) ? "<br>" : '';
						$value		 = $addonDetails['value'];
						$showvalue	 = ($addonDetails['isNegative']) ? "<span class='text-color-red'>" . $value . "</span>" : $value;
						echo $addonDetails['label'] . ": " . $showvalue;
						$shOpt++;
					}
//
//					$addonDetails = json_decode($model->bkgInvoice->bkg_addon_details, true);
//					//$addonCharge	 = (preg_match('/-/', $model->bkgInvoice->bkg_addon_charges)) ? str_replace('-', '', $model->bkgInvoice->bkg_addon_charges) : $model->bkgInvoice->bkg_addon_charges;
//					//	$minusSymbol	 = (preg_match('/-/', $model->bkgInvoice->bkg_addon_charges)) ? '(-)' : '';
//
//					$addnkey = array_search(1, array_column($addonDetails, 'adn_type'));
//					if ($addonDetails[$addnkey]['adn_type'] == 1)
//					{
//						$cpDetails		 = CancellationPolicyDetails::model()->findByPk($model->bkgPref->bkg_cancel_rule_id);
//						$cpAddonCharge	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? str_replace('-', '', $addonDetails[$addnkey]['adn_value']) : $addonDetails[$addnkey]['adn_value'];
//						$cpMinusSymbol	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? '(-)' : '';
//					}
//					$shOpt = 0;
//					if ($addonDetails[$addnkey]['adn_type'] == 1)
//					{
//						$shOpt = 1;
//						echo $cpDetails->cnp_label . ": <span class='text-color-red'>" . $cpMinusSymbol . "</span> " . Filter::moneyFormatter($cpAddonCharge);
//					}
//
//					$addnkey = array_search(2, array_column($addonDetails, 'adn_type'));
//					if ($addonDetails[$addnkey]['adn_type'] == 2)
//					{
//						$cmLebel		 = SvcClassVhcCat::model()->findByPk($model->bkg_vehicle_type_id)->scv_label;
//						$cmAddonCharge	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? str_replace('-', '', $addonDetails[$addnkey]['adn_value']) : $addonDetails[$addnkey]['adn_value'];
//						$cmMinusSymbol	 = (preg_match('/-/', $addonDetails[$addnkey]['adn_value'])) ? '(-)' : '';
//					}
//					if (($addonDetails[$addnkey]['adn_type'] == 2))
//					{
//						if ($shOpt == 1)
//						{
//							echo "<br>";
//						}
//						echo $cmLebel . ": <span class='text-color-red'>" . $cmMinusSymbol . "</span> " . Filter::moneyFormatter($cmAddonCharge);
//					}
					?>
				</div>
			</div>

			<div class="row p5 new-tab4">
				<div class="col-xs-6"><b>Amount (Excl Tax):</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $grossAmount ?></div>
			</div>

			<?
//$staxrate	 = $model->bkgInvoice->getServiceTaxRate();
			$serviceTaxRate	 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
			$staxrate		 = ($serviceTaxRate == 0) ? 1 : $serviceTaxRate;
			$taxLabel		 = ($serviceTaxRate == 5) ? 'GST' : 'Service Tax ';
			?>
			<?
			if ($model->bkgInvoice->bkg_cgst > 0)
			{
				?>
				<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>CGST (@<?= Yii::app()->params['cgst'] ?>%):</b></div>
					<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ((Yii::app()->params['cgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></div>
				</div>
			<? } ?>
			<?
			if ($model->bkgInvoice->bkg_sgst > 0)
			{
				?>
				<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>SGST (@<?= Yii::app()->params['sgst'] ?>%):</b></div>
					<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ((Yii::app()->params['sgst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></div>
				</div>
			<? }
			?>
			<div class="row p5 new-tab2">
				<div class="col-xs-6">
					<b>Driver Allowance:</b><br />
					<!--						<b>Night Pickup :</b><br />
											<b>Night Drop :</b>-->
				</div>
				<div class="col-xs-6 text-right">
					<i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_driver_allowance_amount != '') ? $model->bkgInvoice->bkg_driver_allowance_amount : '0'; ?>
				</div></div>
			<?php
			$extraStateTax	 = ($model->bkgInvoice->bkg_extra_state_tax > 0) ? $model->bkgInvoice->bkg_extra_state_tax : 0;
			$extraTollTax	 = ($model->bkgInvoice->bkg_extra_toll_tax > 0) ? $model->bkgInvoice->bkg_extra_toll_tax : 0;
			?>
			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Other charges (State) :</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= round($extraStateTax) ?></div>
			</div>

			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Other charges (Toll) :</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= round($extraTollTax) ?></div>
			</div>

            <div class="row p5 new-tab2">
				<div class="col-xs-12" onclick="showIncExc('toll')"> <b>[+]</b> <b>Toll Tax <?= ($model->bkgInvoice->bkg_is_toll_tax_included == 1) ? "(Not payable by customer)" : "(Payable on actuals)" ?>:</b>
					<div id="tollDesc" class="col-xs-12 hide">
						<?php
						if ($model->bkgInvoice->bkg_is_toll_tax_included == 1)
						{
							?>   
							Our estimate of toll charges for travel on this route are ₹<?= ($model->bkgInvoice->bkg_toll_tax != '') ? $model->bkgInvoice->bkg_toll_tax : 0; ?>. 
							Toll taxes (even if amount is different) is already included in the trip cost<?php
						}
						else
						{
							?>
							Our estimate of toll charges  on this route are ₹<b><?= $model->bkgInvoice->bkg_toll_tax ?></b>. Any charges incurred is payable by customer.
							<?php
						}
						?>
					</div>
				</div>
					   <!-- <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_toll_tax != '') ? $model->bkgInvoice->bkg_toll_tax : 0; ?></div>-->
            </div>

            <div class="row p5 new-tab2">
				<div class="col-xs-12" onclick="showIncExc('state')"> <b >[+]</b> <b>State Tax <?= ($model->bkgInvoice->bkg_is_state_tax_included == 1) ? "(Not payable by customer)" : "(Payable on actuals)" ?>: </b>
					<div id="stateDesc" class="col-xs-12 hide">
						<?php
						if ($model->bkgInvoice->bkg_is_state_tax_included == 1)
						{
							?>   
							Our estimate of State Tax for travel on this route are ₹<?= ($model->bkgInvoice->bkg_state_tax != '') ? $model->bkgInvoice->bkg_state_tax : 0; ?>. 
							State Taxes (even if amount is different) is already included in the trip cost<?php
						}
						else
						{
							?>
							Our estimate of State Tax on this route are ₹<b><?= $model->bkgInvoice->bkg_state_tax ?></b>. Any charges incurred is payable by customer.
							<?php
						}
						?>
					</div>
				</div>
						<!-- <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_state_tax != '') ? $model->bkgInvoice->bkg_state_tax : 0; ?></div>-->
            </div>
            <div class="row p5 new-tab2">
				<div class="col-xs-12" onclick="showIncExc('airport')">   <b>[+]</b> <b>Airport Entry Charge <?= ($model->bkgInvoice->bkg_is_airport_fee_included == 1) ? "(Not payable by customer)" : "(Payable on actuals)" ?>: </b>
					<div id="airportDesc" class="col-xs-12 hide">
						<?php
						if ($model->bkgInvoice->bkg_is_airport_fee_included != 1)
						{
							?>   
							Our estimate of airport entry charges on this route are ₹ <?= $model->bkgInvoice->bkg_airport_entry_fee ?> . Any charges incurred is payable by customer. <?php
						}
						else
						{
							?>

							Our estimate of airport entry charges on this route are ₹<?= ($model->bkgInvoice->bkg_airport_entry_fee != '') ? $model->bkgInvoice->bkg_airport_entry_fee : 0; ?>. 
							airport entry charges (even if amount is different) is already included in the trip cost 
							<?php
						}
						?>
					</div>
				</div>
						<!--<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_airport_entry_fee != '') ? $model->bkgInvoice->bkg_airport_entry_fee : 0; ?></div>-->
            </div>
			<div class="row p5 new-tab2" onclick="showIncExc('nightPickup')">
                <div class="col-xs-6">   <b>[+]</b><b>Night Pickup Allowance <?= ($model->bkgInvoice->bkg_night_pickup_included == 1) ? '(Prepaid)' : '' ?>:</b></div>
				<div class="col-xs-6 text-right"><b><?= ($model->bkgInvoice->bkg_night_pickup_included == 1) ? "Not payable by customer" : "Payable on actuals" ?></b></div>
                <div id="nightPickupDesc" class="col-xs-12 hide">
					<?php
					if ($model->bkgInvoice->bkg_night_pickup_included == 1)
					{
						?>
						Night pickup charges of ₹250 will be payable if journey start between the hours of 10pm to 6am. Currently , night pickup charges of ₹<?= ($model->bkgInvoice->bkg_night_pickup_included == 1) ? $model->bkgInvoice->bkg_driver_allowance_amount : 0 ?> have been applied for this booking.
						<?php
					}
					else
					{
						?>
						Based on the schedule of this trip, the journey is expected to start between the hours of 10pm to 6am. As a result, night pickup charges of ₹<?= ($model->bkgInvoice->bkg_night_pickup_included == 1) ? $model->bkgInvoice->bkg_driver_allowance_amount : 0 ?> have been applied for this booking. 
						<?php
					}
					?>
				</div>
			</div>
			<div class="row p5 new-tab2" onclick="showIncExc('nightDrop')">
				<div class="col-xs-6">   <b>[+]</b><b>Night Drop Allowance <?= ($model->bkgInvoice->bkg_night_drop_included == 1) ? '(Prepaid)' : '' ?>:</b></div>
				<div class="col-xs-6 text-right"><b><?= ($model->bkgInvoice->bkg_night_drop_included == 1) ? "Not payable by customer" : "Payable on actuals" ?></b></div>
                <div id="nightDropDesc" class="col-xs-12 hide">
					<?php
					if ($model->bkgInvoice->bkg_night_drop_included == 1)
					{
						?>
						Night drop charges of ₹250 will be payable if journey ends between the hours of 10pm to 6am. Currently , night drop charges of ₹<?= ($model->bkgInvoice->bkg_night_drop_included == 1) ? $model->bkgInvoice->bkg_driver_allowance_amount : 0 ?> have been applied for this booking.
						<?php
					}
					else
					{
						?>
						Based on the schedule of this trip, the journey is expected to end between the hours of 10pm to 6am. As a result, night drop charges of ₹<?= ($model->bkgInvoice->bkg_night_pickup_included != 1 && $model->bkgInvoice->bkg_night_drop_included == 1) ? $model->bkgInvoice->bkg_driver_allowance_amount : 0 ?> have been applied for this booking.						<?php
					}
					?>
				</div>
			</div>

			<div class="row p5 new-tab2">
				<div class="col-xs-12" onclick="showIncExc('parking')"><b> [+]</b> <b>Parking charges   :</b>
					<div id="parkingDesc" class="col-xs-12 hide">
						<?php
						if ($model->bkgInvoice->bkg_parking_charge > 0)
						{
							?>< Parking charges are prepaid upto ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>.<?php } ?> Customer will directly pay for parking charges after the total parking cost for the trip exceeds ₹<?= round($model->bkgInvoice->bkg_parking_charge) ?>. Driver must upload all parking receipts for payments made by drive 
					</div>
				</div>
				<!--<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_parking_charge > 0 || $model->bkgInvoice->bkg_is_parking_included == 1) ? round($model->bkgInvoice->bkg_parking_charge) : 0 ?></div>-->
			</div>
			<?
			if ($model->bkgInvoice->bkg_igst > 0 && $model->bkg_from_city_id == 30706)
			{
				?>
				<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>IGST (@<?= Yii::app()->params['igst'] ?>%):</b></div>
					<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax) | 0; ?></div>
				</div>
			<? }
			elseif($model->bkg_agent_id == 18190 && $model->bkgInvoice->bkg_igst == 5){
			 ?>
			
			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>GST (@<?= Yii::app()->params['gst'] ?> % ):</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_service_tax; ?></div>
			</div>

			<? }else{?>
			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>GST (@<?= $serviceTaxRate ?> % ):</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_service_tax; ?></div>
			</div>
			<? }?>
			
            <div class="row p5 new-tab3">
                <div class="col-xs-6"><b>TOTAL AMOUNT</b></div>
                <div class="col-xs-6  text-right amount_size"><span><b><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_total_amount ?></b></span></div>
            </div>
			<?php
			if ($tripGozoAmount < 0)
			{
				?>
				<div class="row p5 new-tab3">
					<div class="col-xs-12 text-right"><b><font style="color:red">Trip Not Profitable</font></b></div>
				</div>
				<?php
			}
			if ($model->bkgTrail->bkg_first_request_sent != '' || $model->bkgTrail->bkg_first_request_sent != NULL)
			{
				?>
				<div class="row p5 new-tab2">
					<div class="col-xs-12"><span class="label label-info la-3x" style="font-size:1em;font-weight: normal;">Bid started at : <?= date('d/m/Y', strtotime($model->bkgTrail->bkg_first_request_sent)); ?></span></div>	
				</div>
			<?php } ?>

			<? //if($noOfBidByBkgId>0){            ?>
			<!--			   <div class="row p5 new-tab2">
							<div class="col-xs-12"><span class="label label-info la-3x" style="font-size:1em;font-weight: normal;">No of bid floated : <?= $noOfBidByBkgId ?></span></div>
						</div>-->
			<?php //}            ?> 




        </div>
    </div>
    <div class="col-xs-12 col-sm-6 p0">
        <div class="<?= $tabclass ?>">
			<?
			$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
			if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
			{
				?>
				<div class="row p5 new-tab2 hidden-xs">
					<div class="col-xs-6"><b>Flexxi Base Amount:</b></div>
					<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_flexxi_base_amount ?></div>
				</div>
			<? } ?>
            <div class="row p5 new-tab2 hidden-xs">
                <div class="col-xs-6"><b>Total Amount:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_total_amount ?></div>
            </div>
            <div class="row p5 new-tab2">
                <div class="col-sm-8 col-xs-6"><b>Charges (per km) after <?= $model->bkg_trip_distance ?> km:<br>(Payable seperately on actuals)</b></div>
                <div class="col-xs-6 col-sm-4 text-right"><?= ($model->bkg_booking_type == 7) ? 'NA' : '<i class="fa fa-inr"></i>' . $model->bkgInvoice->bkg_rate_per_km_extra ?></div>
            </div>
			<?
			if (in_array($model->bkg_booking_type, [9, 10, 11]))
			{
				$scvId			 = $model->bkgSvcClassVhcCat->scv_scc_id;
				$time_cap		 = json_decode(\Config::get('dayRental.timeSlot'));
				$defaultClass	 = \Yii::app()->params['defaultClass'];
				$svcClassId		 = ($defaultClass == 1 ? 0 : $scvId);
				$total			 = $time_cap->$svcClassId * $model->bkgInvoice->bkg_extra_per_min_charge;
				?>
				<div class="row p5 new-tab2">
					<div class="col-sm-8 col-xs-6"><b>For every extra <?= $time_cap->$svcClassId; ?> min after <?= Filter::getTimeDurationbyMinute($model->bkg_trip_duration); ?> :</b></div>
					<div class="col-xs-6 col-sm-4 text-right"><i class="fa fa-inr"></i><?= $total ?></div>
				</div>
			<? } ?>
			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b><?= ($model->bkgInvoice->bkg_advance_amount > 0) ? "Customer Advance Received" : "Customer Advance" ?>:</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_advance_amount != '') ? round($model->bkgInvoice->bkg_advance_amount) : 0 ?></div>
			</div>


			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Customer Refund:</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_refund_amount != '') ? round($model->bkgInvoice->bkg_refund_amount) : 0; ?></div>
			</div>
			 <div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Customer Compensation:</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_cust_compensation_amount != '') ? round($model->bkgInvoice->bkg_cust_compensation_amount) : 0; ?></div>
			</div>
            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b><?= (round($dueAmount) >= 0) ? "Customer Pays" : "Customer Receives"; ?>:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= round($dueAmount) ?></div>
            </div>

			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Gozo Coins Used: </b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_credits_used != '' && $model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0 ?></div>
			</div>




            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Vendor Amount:</b></div>
                <div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $model->bkgInvoice->bkg_vendor_amount ?></div>
            </div>
			<?php
			if ($checkAccess)
			{
				?>
				<div class="row p5 new-tab2">
					<div class="col-xs-8"><b>Quoted Vendor Amount:</b></div>
					<div class="col-xs-4 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_quoted_vendor_amount > 0) ? $model->bkgInvoice->bkg_quoted_vendor_amount : 0 ?></div>
				</div>
				<?
			}
			?>
			<?php
			if ($checkAccess)
			{
				?>
				<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>Trip Vendor Amount:</b></div>
					<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($cabmodel->bcb_vendor_amount > 0) ? $cabmodel->bcb_vendor_amount : 0 ?></div>
				</div>
				<?php
			}
			?>
			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Driver Has To Collect:</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_vendor_collected != '') ? $model->bkgInvoice->bkg_vendor_collected : 0; ?></div>
			</div>
			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Driver Actual Collected:</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_vendor_actual_collected > 0) ? $model->bkgInvoice->bkg_vendor_actual_collected : 0; ?></div>
			</div>

			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b><?= (($model->bkgInvoice->bkg_vendor_amount - $model->bkgInvoice->bkg_vendor_collected) >= 0) ? "Vendor Receives" : "Vendor Pays" ?> : </b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_vendor_amount - $model->bkgInvoice->bkg_vendor_collected) ?></div>
			</div>
			<?php
			if ($checkAccess)
			{
				?>
				<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>Gozo Amount:</b></div>
					<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= $gozoAmount ?></div>
				</div>
			<?php } ?>
			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b><?= (($gozoAmount - $model->bkgInvoice->getAdvanceReceived()) >= 0) ? "Gozo Receives" : "Gozo Pays" ?> :</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($gozoAmount - $model->bkgInvoice->getAdvanceReceived()) ?></div>
			</div>

			<div class="row p5 new-tab2">
				<div class="col-xs-6"><b>Partner Commission:</b></div>
				<div class="col-xs-6 text-right"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_partner_commission > 0) ? ($model->bkgInvoice->bkg_partner_commission + $model->bkgInvoice->bkg_partner_extra_commission) : 0; ?></div>
			</div>

			<?php
			if ($checkAccess)
			{
				if ($model->bkgTrail->bkg_non_profit_flag == 1)
				{
					?>
					<div class="row p5 new-tab2">
						<div class="col-xs-8"><b><font style="color:red">NOT PROFITABLE!! Loss = </font></b></div>
						<div class="col-xs-4 text-right"><b><font style="color:red"><i class="fa fa-inr"></i><?= ( (($model->bkgInvoice->bkg_gozo_amount) * -1) - (($model->bkgInvoice->bkg_credits_used != '' && $model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0 ) ) ?></font></b></div>
					</div>
					<?php
				}
				else
				{
					?>
					<div class="row p5 new-tab2">
						<div class="col-xs-8"><b><font style="color:green">Profit (Trip) Amount = </font></b></div>
						<div class="col-xs-4 text-right"><b><font style="color:green"><i class="fa fa-inr"></i><?= ($model->bkgInvoice->bkg_gozo_amount - (($model->bkgInvoice->bkg_credits_used != '' && $model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_credits_used : 0 )) ?></font></b></div>
					</div>
					<?php
				}
			}
			?>
        </div>
    </div>
</div>
