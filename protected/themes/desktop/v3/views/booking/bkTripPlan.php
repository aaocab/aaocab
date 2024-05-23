<div class="card-body timeline-panel">
	<?php 
	$last	 = 0;
	$tdays	 = 0;
	$cntBrt	 = count($model->bookingRoutes);
	?>
	<ul class="timeline ps ps--active-y timeline-widget mb0">
		<?php
		foreach ($model->bookingRoutes as $k => $brt)
		{
			if ($k == 0)
			{
				$tdays = 1;
			}
			else
			{
				$date1		 = new DateTime(date('Y-m-d', strtotime($model->bookingRoutes[0]->brt_pickup_datetime)));
				$date2		 = new DateTime(date('Y-m-d', strtotime($brt->brt_pickup_datetime)));
				$difference	 = $date1->diff($date2);
				$tdays		 = ($difference->d + 1);
			}
			if (in_array($model->bkg_booking_type, [9, 10, 11]))
			{
				$brt->brt_trip_distance	 = $model->bkg_trip_distance;
				$brt->brt_trip_duration	 = $model->bkg_trip_duration;
			}
			?>

			<li class="timeline-item timeline-icon-success active">
				<?
				$locAddress	 = ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location;
				$locAddress	 .= ($brt->brt_from_latitude > 0) ? '' : ', ' . $brt->brtFromCity->cty_name;
				?>
				<div class="p5 timeline-left">
					<?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?><br>
					<?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?>
					<br><br>Day <?= $tdays ?></div>
					<!--<div class="timeline-hori"><?= ($brt->brt_trip_distance < $model->bkg_trip_distance && ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 4 )) ? 'travel ' . $model->bkg_trip_distance : 'travel ' . $brt->brt_trip_distance ?> km</div>
					<div class="timeline-hori-2"><?= BookingRoute::model()->formatTripduration($brt->brt_trip_duration); ?></div>-->
				<div class="timeline-hori"><img src="/images/bx-tachometer.svg" alt="img" width="11" height="11" class="bx-rotate-90"> <?= ($brt->brt_trip_distance < $model->bkg_trip_distance && in_array($model->bkg_booking_type, [1, 4, 12])) ? $model->bkg_trip_distance : $brt->brt_trip_distance ?> km</div>
				<div class="timeline-hori-2"><img src="/images/bx-info-circle.svg" alt="img" width="11" height="11" class="bx-rotate-90"> <?= BookingRoute::model()->formatTripduration($brt->brt_trip_duration, 1); ?></div>
				<div class="timeline-hori-3"></div>

				<h6 class="timeline-title weight500 font-13"><?= $locAddress ?></h6>

				<div class="row timeline-content">
					<div class="info hide"><?= ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location ?></div>
					<div class="col-12 type"></div>
					<div class="col-12 number"><span class="black-color">
						</span> 
						<span class="timeing-box hide">arriving at <?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?> <?= DateTimeFormat::DateTimeToTimePicker($brt->arrival_time); ?><? //= BookingRoute::model()->formatTripduration($brt->brt_trip_duration);        ?></span></div>
				</div>
			</li>
			<?php
			if ($k == ($cntBrt - 1))
			{
				$expArrivedate	 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cntBrt - 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cntBrt - 1]->brt_trip_duration . ' MINUTE'));
				$hide			 = 'hide';

				$hide			 = '';
				//$expArrivedate = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$k]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$k]->brt_trip_duration . ' MINUTE'));
				$expArrivedate	 = $model->bookingRoutes[$k + 1]->brt_pickup_datetime;
				$brtTripDistance = $brt->brt_trip_distance;
				$brtTripDuration = $brt->brt_trip_duration;
				if ($model->bkg_booking_type == 3)
				{
					//$expArrivedate = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$k + 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$k + 1]->brt_trip_duration . ' MINUTE'));
					$expArrivedate	 = $model->bookingRoutes[$k + 1]->brt_pickup_datetime;
					$brtTripDistance = $model->bookingRoutes[$k + 1]->brt_trip_distance;
					$brtTripDuration = $model->bookingRoutes[$k + 1]->brt_trip_duration;
				}
				?>
				<li class="timeline-item timeline-icon-primary active">
					<?
					$locAddress	 = ($brt->brt_to_location == '') ? $brt->brtToCity->cty_name : $brt->brt_to_location;
					$locAddress	 .= ($brt->brt_to_latitude > 0) ? '' : ', ' . $brt->brtToCity->cty_name;
					?>

					<div class="timeline-time"><? //= $tdays       ?></div>
					<h6 class="timeline-title weight500"><?= $locAddress ?></h6>
					<p class="timeline-text"></a></p>
					<div class="timeline-content">
						<div class="info hide"><?= ($brt->brt_to_location == '' ) ? $brt->brtToCity->cty_name : $brt->brt_to_location ?></div>
						<div class="type <?= $hide ?>"></div>
						<span class="number "><span class="black-color "><? //= DateTimeFormat::DateTimeToDatePicker($brt->arrival_time);         ?><br><b class="gray-color bold-none"><? //= DateTimeFormat::DateTimeToTimePicker($brt->arrival_time);         ?></b></span> 
							<span class="timeing-box <?= $hide ?>"></span></span>
					</div>
				</li>
			<? } ?>

		<? } ?>
	</ul>
	<p><span class=".black-color" style="font-size:10px;"><strong>(+/- 30 mins for traffic)</strong></span></p>

</div>