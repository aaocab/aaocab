<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<div class="text-center mb5 mt5 ">
	<?php
	if (!$data['success'])
	{
		echo $data['message'];
	}
	else
	{
		?>
		<div   class=" uppercase font-16 text-center p5 mt10">
			Bid List
		</div>
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
			$totalTrips = ($value['totalTrips'] > 0) ? $value['totalTrips'] : 'NA';
			?>
			<div class="tab-content p5 content-boxed-widget ">
				<div class="content p0 bottom-10   ">
					<div class="one-half">
						<span class="color-gray-dark">Operator</span><br>
						<span class="font-16 uppercase color-blue"><?php echo $value['vnd_code'] ?></span>
					</div>
					<div class="one-half last-column  ">
						<span class="color-gray-dark">Rating</span><br>
						<span class="stars color-orange"><?= $rateStar ?> </span><?= $rating ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="content p0 bottom-10   ">
					<div class="one-half">
						<span class="color-gray-dark">Punctuality</span><br>
						<?php echo $punctuality ?>
					</div>
					<div class="one-half last-column  ">
						<span class="color-gray-dark">Trips  completed</span><br>
						<?php echo $totalTrips ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="content p0 bottom-10   ">
					<div class="one-half">
						<span class="color-gray-dark">Can pickup at</span><br>
						<?php echo DateTimeFormat::DateTimeToLocale($value['reachingAtTime']) ?>
					</div>
					<div class="one-half last-column  ">
						<span class="color-gray-dark">Amount (all inc.)</span><br>
						&#x20B9;<?php echo $value['totalCalculated'] ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="content p0 bottom-10   ">
					<button type="button" class="btn btn-green Accept uppercase shadow-medium" id="gznowtrack" onclick="acceptBid(<?php echo $value['bvr_id'] ?>, '<?php echo $value['bvr_booking_id'] ?>')" title="Accept">Accept</button>
					<div class="clear"></div>
				</div>
			</div>
			<?php
		}
	}
	?>
</div>