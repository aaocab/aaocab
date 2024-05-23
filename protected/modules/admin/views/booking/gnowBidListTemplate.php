
<?php
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
$hours	 = round($minutes / 60) . 'Hrs' . ($minutes % 60) . 'min';
?>
<div class="col-xs-12 col-md-6 col-lg-3  ">
	<div class="panel panel-white bidBox" onClick="processClick(<?= $value['bvr_id'] ?>,<?= $value['bvr_vendor_id'] ?>)" id="bid_<?= $value['bvr_id'] ?>">
		<div class="panel-body">
			<div class="row mb10">
				<div class="col-xs-6">
					<div class="checkbox mt0">
						<input id="checkbox_<?php echo $value['bvr_vendor_id'] . '_' . $value['bvr_id']; ?>" value="<?php echo $value['bvr_id']; ?>" type="checkbox" name="cabsegmentation" class="checkbox-input chkbox ml0 checkbox-round">
						<label for="checkbox_<?php echo $value['bvr_vendor_id'] . '_' . $value['bvr_id']; ?>"><span class="color-gray font-14 weight400">cab arrives at</span><br><span class="font-18"><?php echo DateTimeFormat::DateTimeToLocale($value['reachingAtTime']); ?> </span></label>
					</div>
				</div>						 
				<div class="col-xs-6 text-right">
					<div class="heading-elements">Bid&nbsp;Amount<br>
						<span class="font-24">₹</span><span class="font-24"><b><?php echo $value['bidAmount']; ?></b></span>
					</div>
				</div>
			</div>

			<div class="row" style="position: relative;">
				<div class="col-xs-6 mb10">Operator</div>
				<div class="col-xs-6 mb10 text-right"><b><?php echo $value['vnd_code'] ?></b></div>
				<?
				if ($value['totalTrips'] > 0)
				{
					?>
					<div class="col-xs-4 mb10">Total Trips</div>
					<div class="col-xs-8 mb10 text-right"><?php echo $value['totalTrips'] ?> trips on this route</div>
				<? } ?>
				<div class="col-xs-6 mb10">Cab name</div>
				<div class="col-xs-6 mb10 text-right"><?php echo $value['vht_make'] ?> - <?php echo $value['vht_model'] ?></div>
				<div class="col-xs-6 mb10">Cab number</div>
				<div class="col-xs-6 mb10 text-right"><?php echo $value['vhc_number'] ?></div>

				<div class="col-xs-4 mb10">Driver</div>
				<div class="col-xs-8 mb10 text-right"><?php echo $value['driverName'] . ' (' . $value['driverMobile'] . ')' ?></div>
			</div>
		</div>
	</div>
</div>
