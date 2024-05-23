
<table align="center" class="table table-striped  text-center table-bordered"   width="100%"  >
	<?
	if (!$data['success'])
	{
		?>
		<tr>
			<th class="text-center col-xs-12" >
				<?php echo $data['message'] ?></th>
		</tr>
		<?
	}
	else
	{
		?>
		<tr>
			<th class="col-lg-1">&nbsp;</th>   
			<th>Operator<br>Rating</th>
			<th>Cab Model</th>
			<th>Punctuality</th>
			<th>trips  completed </th>
			<th>Can pickup at</th>
			<th>Amount (all inclusive)</th> 
		</tr>
		<?
		foreach ($data['data'] as $key => $value)
		{
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
			?>
			<tr>
				<td class="text-center"><button type="button" class="btn btn-info Accept" id="gznowtrack" onclick="acceptBid(<?php echo $value['bvr_id'] ?>, '<?php echo $value['bvr_booking_id'] ?>')" title="Accept">Accept</button>
				</td>
				<td><?php echo $value['vnd_code'] ?><br><span class="stars text-warning"><?= $rateStar ?> </span> <span class="pull-right "><?= $rating ?></span></td>
				<td><?php echo $value['vhc_id'] ?></td>
				<td style="white-space: nowrap"><?php echo $punctuality ?></td>
				<td><?php echo $value['totalTrips'] ?></td>
				<td ><?php echo DateTimeFormat::DateTimeToLocale($value['reachingAtTime']) ?></td>
				<td>&#x20B9;<?php echo $value['totalCalculated'] ?></td> 
			</tr>
			<?
		}
	}
	?>
</table>
