<?php
//echo "<pre>";
//print_r($value);
$rating			 = $value['bvr_vendor_rating'];
$lateArrive		 = $value['lateArrive'] | 0;
$onTimeArrive	 = $value['onTimeArrive'] | 0;
$totArriveData	 = $lateArrive + $onTimeArrive;
$punctuality	 = ($totArriveData > 0) ? round($onTimeArrive * 100 / $totArriveData) . '% on time' : 'NA';
$rateStar		 = '';
for ($i = 1; $i <= $rating; $i++)
{
	$rateStar .= "<i class= 'fa fa-star'></i>";
	if ($rating - $i < 0.7 && $rating - $i >= 0.3)
	{
		$rateStar .= "<i class= 'fa fa-star-half'></i>";
	}
}
$minutes = round((strtotime($value["reachingAtTime"]) - time()) / 60);
$bidExpireTimeLeft = $value['bidexpiretimeLeft'];
$minutesBid = floor(($bidExpireTimeLeft / 60) % 60);
$secondsBid = $bidExpireTimeLeft % 60;
$vhcRating = ($value['vhc_overall_rating']== NULL ||$value['vhc_overall_rating'] < 1) ? "4.8" : $value['vhc_overall_rating'];
$drvRating=  ($value['drs_drv_overall_rating']== NULL ||$value['drs_drv_overall_rating'] < 1) ? "4.8" : $value['drs_drv_overall_rating'];

?>
<input type="hidden" id="isTimerStarted_<?= $value['bvr_id']?>" value="0">
<input type="hidden" id="bidtimeleft_<?= $value['bvr_id']?>" value="<?=$bidExpireTimeLeft?>">
<div class="col-12 col-md-6 col-xl-4 bidCards bid_<?= $value['bvr_id'] ?>" id="bid_<?= $value['bvr_id'].'_'.$value['bvr_bid_amount'] ?>">
	<div class="card widget-user-details mb-1">
	<div class="row m0"><div class="col-12"><span class="time-widget-2">Bid Expires in <span id="times_<?= $value['bvr_id'] ?>"><b><?=$minutesBid.":".$secondsBid?></b></span></span></div></div>
 		<div class="card-header pl15 pr15 pb10 pt10">
			<div class="card-title-details d-flex">
				<div class="lineheight14">
					<label><span class="color-gray font-13 weight400">cab arrives in</span><br><span class="font-16"><?php echo $minutes; ?> mins</span></label>
				</div>						 
			</div>
			<div class="heading-elements">
				<span class="font-18">₹</span><span class="font-18 weight600"><?php echo $value['totalCalculated']; ?></span>
			</div>
		</div>
		<div class="card-body d-flex pl15 pr15 pb5 d-flex justify-content-between">
			<div class="mr-xl-2" style="position: relative;">
				<div class="profit-content">
					<small class="color-gray font-13">Operator</small>
					<h5 class="mb-0 weight500 font-18"><?php echo $value['vnd_code']; ?></h5>
					<p class="mb-0 lineheight18"><?php echo $value['totalTrips']; ?> trips on this route</p>
                    
				</div>
                <div class="profit-content mt20">
					<small class="color-gray font-13">Driver</small>
					<h5 class="mb-0 weight500 font-18"><?php echo $value['drv_name']; ?></h5>
					<p class="mb-0 lineheight18">Driver rating - <?php echo ($drvRating < 3) ? 3 : ($drvRating); ?></p>
                    <p class="mb-0 lineheight18">No of trip - <?php echo $value['total_trip']; ?></p>
				</div>
                <div class="resize-triggers">
                    <div class="expand-trigger">
                        <div style="width: 91px; height: 65px;">
                        </div>
                    </div>
                    <div class="contract-trigger"></div>
                </div>
			</div>
			<div style="position: relative;">
				<div class="profit-content mt-3">
					<p class="mb-0 lineheight18"><?php echo $value['vht_make'] ?> - <?php echo $value['vht_model']; ?> </p>
                    <p class="mb-0 lineheight18">Cab rating - <?php echo ($vhcRating < 3) ? 3 : ($vhcRating); ?> </p>
                    <p class="mb-0 lineheight18">No of trip - <?php echo $value['vhs_total_trips']; ?> </p>
				</div>
				<div class="resize-triggers"><div class="expand-trigger"><div style="width: 95px; height: 65px;"></div></div><div class="contract-trigger"></div></div>
			</div>
		</div>
		<div class="row ml0 mr0">
			<div class="col-6 mb-1 text-left ">
				<button type="button" class="btn btn-sm btn-success round pl-2 pr-2 btnAccpt " id="acceptBidBtn_<?php echo $value['bvr_vendor_id'] . '_' . $value['bvr_id']; ?>"  value="<?php echo $value['bvr_id']; ?>">Accept</button>
			</div>	
			<div class="col-6 mb-1 text-right">	
				<button type="button" class="btn btn-sm btn-danger round pl-2 pr-2 btnDeny" id="denyBidBtn_<?php echo $value['bvr_vendor_id'] . '_' . $value['bvr_id']; ?>"  value="bid_<?php echo $value['bvr_id']. '_' . $value['bvr_bid_amount']; ?>">Decline</button>
			</div>
		</div>
	</div>
</div>
