<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
//$vehicletype = VehicleTypes::model()->findByPk($model->bkgAddInfo->baddInfoBkg->bkg_vehicle_type_id);
$vehicletype	 = VehicleCategory::model()->findByPk($model->bkgSvcClassVhcCat->scv_vct_id);
$capacity		 = $vehicletype->vct_capacity;
$bagCapacity	 = $vehicletype->vct_small_bag_capacity;
$bigBagCapacity	 = $vehicletype->vct_big_bag_capacity;
?>
<div class="content-boxed-widget p0 accordion-path">
	<div class="accordion accordion-style-0">
		<div class="accordion-border">
			<a href="javascript:void(0)" class="font18" data-accordion="accordion-8"><span class="uppercase">Your Trip Summary</span><i class="fa fa-plus"></i></a>
			<div class="accordion-content" id="accordion-8" style="display: none;">
				<div class="content-padding box-text-3 mb10 pt0">
					<ul class="timeline">
						<?
						$last			 = 0;
						$tdays			 = 0;
						$cntBrt			 = count($model->bookingRoutes);
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
							?>
							<li>
								<?
								$locAddress	 = ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location;
								$locAddress	 .= ($brt->brt_from_latitude > 0) ? '' : ', ' . $brt->brtFromCity->cty_name;
								?>
								<div class="direction-r">
									<div class="flag-wrapper">
										<span class="flag"><?= $brt->brtFromCity->cty_name ?></span><br>
										<span class="time-wrapper"><span class="time"><?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?>, <?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></span></span>
									</div>
									<div class="desc">	
										<?php
										if (in_array($model->bkg_booking_type, [9, 10, 11]))
										{
											$brt->brt_trip_duration	 = $model->bkg_trip_duration;
											$brt->est_date			 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[0]->brt_pickup_datetime . '+ ' . $brt->brt_trip_duration . ' minute'));
										}
										?>
										travel <?= ($brt->brt_trip_distance < $model->bkg_trip_distance && in_array($model->bkg_booking_type, [9, 10, 11, 1])) ? $model->bkg_trip_distance : $brt->brt_trip_distance ?> km in <span class="time"><?= BookingRoute::model()->formatTripduration($brt->brt_trip_duration, 1); ?></span>, Day <?= $tdays ?> <br>
										<span class="txbox"><?= $locAddress ?></span></div>
								</div>
								<div class="clear"></div>
								<?php
								if ($brt->arrival_time != '')
								{
									?>
									<div class="time">arriving at <?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?> <?= DateTimeFormat::DateTimeToTimePicker($brt->arrival_time); ?></div>
								<?php } ?>
							</li>

							<?
							if ($k == ($cntBrt - 1))
							{
								$expArrivedate	 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cntBrt - 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cntBrt - 1]->brt_trip_duration . ' MINUTE'));
								?>
								<li>
									<?
									$locAddress		 = ($brt->brt_to_location == '') ? $brt->brtToCity->cty_name : $brt->brt_to_location;
									$locAddress		 .= ($brt->brt_to_latitude > 0) ? '' : ', ' . $brt->brtToCity->cty_name;
									?>
									<div class="direction-r">
										<div class="flag-wrapper">
											<span class="flag"><?= $brt->brtToCity->cty_name ?></span><br>
											<span class="time-wrapper">
												<?php
												if (trim($brt->est_date) != "")
												{
													?>
													<span class="time">
			<!--													<? //= DateTimeFormat::DateTimeToDatePicker($brt->est_date);         ?>, <? //= DateTimeFormat::DateTimeToTimePicker($brt->est_date);         ?><br>-->
														<b>Drop Address:</b> <?= $locAddress ?>
													</span>
													<?php
												}
												else
												{
													$est_date = date('Y-m-d H:i:s', strtotime($brt->brt_pickup_datetime . '+ ' . $brt->brt_trip_duration . ' minute'));
													?>
													<span class="time">
			<!--													<? //= DateTimeFormat::DateTimeToDatePicker($est_date);          ?>, <? //= DateTimeFormat::DateTimeToTimePicker($est_date);          ?><br>-->
														<b>Drop Address:</b> <?= $locAddress ?>
													</span>
												<?php } ?>
											</span>
										</div>
									</div>
									<div class="clear"></div>
								</li>
								<?
							}
						}
						?>
					</ul>
                    <strong><span class=".black-color" style="font-size:10px;">(+/- 30 mins for traffic)</span></strong>
                    <div class="heading-part text-uppercase mb5 mt10"><b>You have booked a <?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label ?> <img src="/images/icon/<?= $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label ?>.png" class="inline-block"> car</b></div>
					<div>
						<ol class="mb0">
							<?php
							$bkgVehicleTypeId	 = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_desc;
							$arrServiceDesc		 = json_decode($bkgVehicleTypeId);
							foreach ($arrServiceDesc as $key => $value)
							{
								?>	
								<li><span class="black-color"><?= $value; ?></span></li>
									<?php
								}
								?>
						</ol>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>