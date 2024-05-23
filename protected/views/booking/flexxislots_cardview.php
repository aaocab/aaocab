
<div class="col-xs-12 mb20">
	<?
	$data = BookingSub::model()->getAvailableFlexxiSlots($date,$fromCity,$toCity);
	if (count($data) > 0)
	{

		foreach ($data as $value)
		{
			if ($value['slot'] != "At other times")
			{
				$time	 = explode('-', $value['slot']);
				$time1	 = date('H:s:i', strtotime($time[0]));
				$time2	 = date('H:s:i', strtotime($time[1]));
				?>
	                  <div class="btn next3-btn" style="background: #f36c31;width:200px;margin-right: 20px;" pickupdate="<?= date('d/m/Y', strtotime($value['bkg_pickup_date'])) ?>" time1="<?= $time1 ?>" time2="<?= $time2 ?>" onclick="flexxiShare_subQuick(this)"><? echo date('M d', strtotime($value['bkg_pickup_date'])) . " | " . $value['slot'] . '<br>' . $value['totseats'] . " seats available" ?></div>
			<?}?>
			
			<?
		}
	}
	?>
</div>
