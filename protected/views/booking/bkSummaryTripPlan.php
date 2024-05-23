<div class="col-xs-12 mb20">
	<div class="heading-part mb4">Your Trip Plan</div>
	<div class="main_time border-blueline p5">
		<div class="container-time pb15">
			<?php
			$last	 = 0;
			$tdays	 = 0;
			$cntBrt	 = count($model->bookingRoutes);
           
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
				if(in_array($model->bkg_booking_type, [9,10,11])){
				      $brt->brt_trip_distance = $model->bkg_trip_distance;
                      $brt->brt_trip_duration = $model->bkg_trip_duration;
                      $brt->est_date	 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[0]->brt_pickup_datetime . '+ ' . $brt->brt_trip_duration . ' minute'));
				}
				?>

				<ul>
					
					<li><span></span>
						<div>
							<?
							$locAddress	 = ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location;
							$locAddress	 .= ($brt->brt_from_latitude > 0) ? '' : ', ' . $brt->brtFromCity->cty_name;
							?>
							<div class="title black-color"><?= $locAddress ?>
								<span class="pull-right pr30">Day <?= $tdays ?></span></div>
							<div class="info hide"><?= ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location ?></div>
							<div class="type"><?= ($brt->brt_trip_distance < $model->bkg_trip_distance && ($model->bkg_booking_type == 1 ||$model->bkg_booking_type == 4 )) ? $model->bkg_trip_distance : $brt->brt_trip_distance ?><br>km</div>
						</div> <span class="number"><span class="black-color">
								<?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?><br><b class="gray-color bold-none">
									<?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></b></span> 								
								<span class="timeing-box" style="font-size:10px;"><?=BookingRoute::model()->formatTripduration($brt->brt_trip_duration); ?></span></span>
					</li>
					<?
				   
						if($k == ($cntBrt - 1))
						{
                         
							$expArrivedate	 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cntBrt - 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cntBrt - 1]->brt_trip_duration . ' MINUTE'));
							$hide = 'hide';	
						
							$hide = '';
							//$expArrivedate = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$k]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$k]->brt_trip_duration . ' MINUTE'));
							$expArrivedate = $model->bookingRoutes[$k+1]->brt_pickup_datetime;
							$brtTripDistance = $brt->brt_trip_distance;
							$brtTripDuration = $brt->brt_trip_duration;
							if($model->bkg_booking_type == 3)
							{
								//$expArrivedate = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$k + 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$k + 1]->brt_trip_duration . ' MINUTE'));
								$expArrivedate = $model->bookingRoutes[$k+1]->brt_pickup_datetime;
								$brtTripDistance = $model->bookingRoutes[$k+1]->brt_trip_distance;
								$brtTripDuration = $model->bookingRoutes[$k+1]->brt_trip_duration;
							}
							
                       
                       
						?>
						<li>
							<div><span></span>
								<?
								$locAddress		 = ($brt->brt_to_location == '') ? $brt->brtToCity->cty_name : $brt->brt_to_location;
								$locAddress		 .= ($brt->brt_to_latitude > 0) ? '' : ', ' . $brt->brtToCity->cty_name;
								?>

								<div class="title black-color"><?= $locAddress ?>
									<span class="pull-right pr30"><?//= $tdays ?></span></div>
								<div class="info hide"><?= ($brt->brt_to_location == '' ) ? $brt->brtToCity->cty_name : $brt->brt_to_location ?></div>
								<div class="type <?= $hide ?>"></div>
							</div> <span class="number "><span class="black-color "><?= DateTimeFormat::DateTimeToDatePicker($brt->est_date); ?><br><b class="gray-color bold-none"><?= DateTimeFormat::DateTimeToTimePicker($brt->est_date); ?></b></span> 
								<span class="timeing-box <?= $hide ?>"></span></span>
						</li>
                    <?php
						}
                     ?>
				</ul>				
			<? } ?>			
		</div>
		<p><span class=".black-color" style="font-size:10px;"><strong>(+/- 30 mins for traffic)</strong></span></p>
	</div>
</div>
