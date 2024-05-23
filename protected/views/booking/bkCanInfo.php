<style>
	.orange-bar{
		background-color: #f9ecd4;
		height: 15px;
		margin-top: 14px;
		border-right: 0.5px solid red;
		text-align: center;
		font-size: 12px;
		color: red;
	}
	.orange-text-color{
		color: #ffa500;
	}
	.common-box{
		width: 4%;
		height: 12px;
		float: left;
		margin-top: 4px;
		margin-right: 4px;
	}
	.green-box{
		background-color: #7bc7a1;
	}
	.orange-box{
		background-color: #f9ecd4;
	}
	.red-box{
		background-color: #f5d2cf;
	}
	.gray-box{
		background-color: #666;
	}
</style>

<?php
$totalAdvance	 = PaymentGateway::model()->getTotalAdvance($model->bkg_id);
$vehicalModel	 = new VehicleTypes();
//$carType		 = $vehicalModel->getVehicleTypeById($model->bkg_vehicle_type_id);
//$carType		 = $model->bkg_vehicle_type_id;
//$car_type		 = $vehicalModel->getCarType($carType);
//$cabModel		 = $vehicalModel->getModelDetailsbyId($model->bkg_vehicle_type_id);

$vctId			 = $model->bkgSvcClassVhcCat->scv_vct_id;
$car_type		 = SvcClassVhcCat::model()->getVctSvcList('string', '', $vctId);
$cabModel		 = VehicleCategory::model()->findByPk($vctId);

$model->bkgInvoice->calculateConvenienceFee(0);
$model->bkgInvoice->calculateTotal();
//$carType= VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
$priceRule = AreaPriceRule::model()->getValues($model->bkg_from_city_id,$model->bkg_vehicle_type_id,$model->bkg_booking_type);
$prr_day_driver_allowance =$priceRule['prr_day_driver_allowance'];
$prr_Night_driver_allowance =$priceRule['prr_night_driver_allowance'];

$freeAction = 0;
$paidAction = 0;
if(date('Y-m-d h:i:s') > date('Y-m-d h:i:s',strtotime('-24 hour', strtotime($model->bkg_pickup_date)))){
	$freeAction = 0;
}
if(date('Y-m-d h:i:s') > date('Y-m-d h:i:s',strtotime('-6 hour', strtotime($model->bkg_pickup_date)))){
	$paidAction =1;
}

?>
<div class="row">
	<div class="col-xs-12">
		<div class="main_time border-greenline mb20">
			<div class="heading-part mb10" style="font-size:16px; "><b>Read before you Book!</b></div>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<span class="heading-part mb10"><b>Cancellation information</b></span>
					<div style="margin: 15px 0px; padding-bottom: 75px;">
						<div class="col-xs-4 <?= $freeAction == 0 ?'green-bar':'gray-bar' ?> ">
							<span class="<?= $freeAction == 0 ? 'green-round':'gray-round' ?> "></span>
						</div>
						
						<div class="col-xs-4 <?= $paidAction == 0 ? 'green-bar':'gray-bar'/*Change green-bar to orange-bar after 31st Dec */ ?>">
							<?=($freeAction > 0) ? "Till": "24 hrs"?>
						</div>
						
						<div class="col-xs-4 red-bar" style="line-height:15px;">
							6 hrs
							<span class="red-round"></span>
						</div>
<!--						<div class="col-xs-4 bar-border-right"></div>
						<div class="col-xs-4 bar-border-right bar-border-left"></div>
						<div class="col-xs-4 bar-border-left"></div>-->
<!--						<div class="col-xs-3" style="padding: 0px 10px 0px 0px !important;font-size: 10px;">
							<span class="green-text-color"><?//= $freeAction == 0 ? 'Before 24 hours' : '' ?></span>
						</div>-->
<!--						<div class="col-xs-3" style="padding: 0px 10px 0px 0px !important;font-size: 10px;">
							<span><?//= $paidAction == 0 ? ($freeAction == 1) ? date('d M Y h:i A') : date('d M Y h:i A', strtotime('-24 hour', strtotime($model->bkg_pickup_date))): '' ?></span>
						</div>-->
						<div class="col-xs-5" style="padding: 0px 0px 0px 0px !important;font-size: 10px;">
							<span><?= date('d M Y h:i A', strtotime('-6 hour', strtotime($model->bkg_pickup_date))) ?></span>
						</div>
						<div class="col-xs-5" style="font-size: 10px;float: right;padding: 0px 0px 0px 20px !important;">
							<span><?= date('d M Y h:i A', strtotime($model->bkg_pickup_date)) ?></span>
						</div>
					</div>
					<br/>
					<br/>
					<?php 
						if($freeAction == 1 || $paidAction == 1)
						{
					?>
					<p><span class="common-box gray-box"></span> Not applicable</p>
					<?php 
						}
						if($freeAction == 0)
						{
					?>
					<p><span class="common-box green-box"></span> Free Cancellation applicable</p>
					<?php 
						}
						if($paidAction == 0)
						{ /*uncomment below line after 31st dec*/
					?>
					<!--<p><span class="common-box orange-box"></span> 15% Cancellation Charge applicable</p>-->
					<?php 
						}
					?>
					<p><span class="common-box red-box"></span> No Refund</p>
					<br/>
					<br/>
										
					<span class="heading-part mb10"><b>Night Charges</b></span>
					<p class="mb20">Post 9:00 PM to 6:00 AM, an Additional night charge (approx. Rs.250/night) will be applicable. These charges should be directly paid to the driver.</p>
					
					</div>
                <div class="col-xs-12 col-sm-6">
					<span class="heading-part mb10"><b>Driver Details</b></span>
					<p class="mb20">Driver details will be shared up to 2 hrs prior to departure.</p>
					 
					<span class="heading-part mb10"><b>Stops</b></span>
					<p class="mb20">This is a point to point booking and only one stop for meals is included.</p>
					 
					<span class="heading-part mb10"><b>Delays</b></span>
					<p class="mb20">Due to traffic or any other unavoidable reason, pickup may be delayed by 30 mins.</p>
					
					<span class="heading-part mb10"><b>Hilly Regions</b></span>
					<p class="mb20">AC will be switched off in hilly areas.</p>
					
					<!--<span class="heading-part mb10"><b>Receipts</b></span>
					<p class="mb20">You need to collect the receipts from driver for any toll tax, state tax, night charges or extra km paid directly to the driver during the trip. GOZO is not liable to provide invoices for such amount</p>
					-->
				</div>
               <div class="col-xs-12 col-sm-12">
                <span class="heading-part mb10"><b>Cab Category</b></span>
					<p class="mb20">The booking will be for cab type <?= $car_type ?> and we do not commit on providing the preferred cab model (<?= $cabModel['vct_desc'] ?>).</p>
                </div>
                <!--<div class="col-xs-12 col-sm-12">
					<span class="heading-part mb10"><b>Included with Quotation</b></span>
                     <p>Upto 251 Kms for the exact itinerary listed bellow.No route deviations allowed unless listed in itinerary:Toll and state taxes.</p>
                </div>
                
                <div class="col-xs-12 col-sm-12">
					<span class="heading-part mb10"><b>Customer to pay separately</b></span>
                     <p>Any and all airport entry and parking charges; Any parking charges night driving allowance of Rs 250 to be paid 
                      if pickup or drop off happens between (10pm and 6pm)</p>
                </div>-->
				<div class="col-xs-12 col-sm-12">
					<span class="heading-part mb10"><b>Included with Quotation</b></span>
					<p>
					NO route deviations allowed unless listed in itinerary 
					<?php 
						if ($model->bkgInvoice->bkg_is_toll_tax_included == 1 && $model->bkgInvoice->bkg_is_state_tax_included == 1)
						{
							echo '; Toll & state taxes';
						}
                        if ($model->bkgInvoice->bkg_is_airport_fee_included == 1)
						{
							echo ', Airport Entry Charges';
						}
						if($model->bkg_booking_type == 1)
						{
						echo "<br />".$isAllowencePickupText.$br.$isAllowenceDropOffText;
						}
						?>
						<?php
						if( $prr_day_driver_allowance >0 &&( $model->bkg_booking_type == 2 || $model->bkg_booking_type == 3))
						{
						echo "<br />"."Drivers daytime allowance of Rs. ".$prr_day_driver_allowance." per day is included in quotation";
						}
						?></p>
						<div class="heading-journeybreak hide">
						<span class="heading-part mb10"><b>Additional charges</b></span>
						 <p id="journeybreak">30 minutes break during journey (Rs. 150/-).</p>
						 </div>
						<span class="heading-part mb10"><b>Customer to pay separately</b></span>
						<p>Any and all parking charges; Any parking charges 
						<?php
						if ($model->bkgInvoice->bkg_is_toll_tax_included == 0 && $model->bkgInvoice->bkg_is_state_tax_included == 0)
						{
							echo '; Toll & state taxes';
						}
                        if ($model->bkgInvoice->bkg_is_airport_fee_included == 0)
						{
							echo ', Airport Entry Charges';
						}
						$night_driver_allowance_txt = ($prr_Night_driver_allowance > 0) ? "of Rs. " . $prr_Night_driver_allowance : '';
						if ($model->bkgInvoice->bkg_night_drop_included == 0 && $model->bkgInvoice->bkg_night_pickup_included == 1)
						{
							if ($model->bkg_booking_type == 1)
							{
								echo "<br />" . " Night drop allowance " . $night_driver_allowance_txt . " to be paid if drop off happens between (10pm and 6am).";
							}
							else
							{
								echo "<br />" . "Night drop allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am. ";
							}
						}
						if ($model->bkgInvoice->bkg_night_pickup_included == 0 && $model->bkgInvoice->bkg_night_drop_included==1)
						{
							if ($model->bkg_booking_type == 1)
							{
								echo "<br />" . " Night pickup allowance " . $night_driver_allowance_txt . " to be paid if drop off happens between (10pm and 6am).";
							}
							else
							{
								echo "<br />" . "Night pickup allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am. ";
							}
						}
						if ($model->bkgInvoice->bkg_night_pickup_included == 0 && $model->bkgInvoice->bkg_night_drop_included == 0)
						{
							if ($model->bkg_booking_type == 1)
							{
								echo "<br />" . " Night driving allowance " . $night_driver_allowance_txt . " to be paid if pickup or drop off happens between (10pm and 6am).";
							}
							else
							{
								echo "<br />" . "Night driving allowance " . $night_driver_allowance_txt . " to be paid to driver for each night when driving between the hours of 10pm and 6am. ";
							}
						}
						if ($model->bkgInvoice->bkg_night_pickup_included == 1 && $model->bkgInvoice->bkg_night_drop_included == 1)
						{
							echo" ";
						}
					?>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>