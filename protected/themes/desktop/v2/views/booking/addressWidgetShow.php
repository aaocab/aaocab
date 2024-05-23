<?php
if ( ($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == ''  ) && $model->bkg_booking_type != 4)
{
	?>
	<div class="container mt5 addressAdd">
		<div class="row">
			<div class="col-12 col-lg-10 offset-lg-1 mb30">
				<div class="bg-white-box">
					<div class="row">
						<div class="col-9 heading-part mb10"><b>UPDATE YOUR PICKUP & DROP ADDRESSES</b></div>
					</div>
					<?php $this->renderPartial('pickupLocationWidgetGNow', ['model' => $model], false, false); ?>
					<div class="row">
						<div class="col-12 text-right mt20 n">
							<button type="button" id="saveNewAddreses" class="btn btn-effect-ripple btn-success p5 mt10" name="saveNewAddreses" onclick="saveAddressesByRoutes();">Save Addresses</button>
						</div>
					</div>
				</div>
			</div></div></div>
	<input type="hidden" value="<?php echo ($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') ? '0' : '1' ?>" class="isPickupAdrsCls" name="isPickupAdrsCls">
	<?php
}
else
{
	?>
	<div class=" container container-time pb15 addressRes">
		<div class="row">
			<div class="col-12 col-lg-10 offset-lg-1 mb30">
				<div class="bg-white-box">
					<div class="row pb15">
						<ul>
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
								?>
								<li><span></span>
									<div>
										<?php
										$locAddress	 = ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location;
										$locAddress	 .= ($brt->brt_from_latitude > 0) ? '' : ', ' . $brt->brtFromCity->cty_name;
										?>
										<div class="title black-color"><?= $locAddress ?>
											<span class="pull-right pr30 pl20">Day <?= $tdays ?></span></div>
										<div class="info hide"><?= ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location ?></div>
										<div class="type"><?= ($brt->brt_trip_distance < $model1->bkg_trip_distance && $model1->bkg_booking_type == 1) ? $model1->bkg_trip_distance : $brt->brt_trip_distance ?><br>km</div>
									</div> <span class="number"><span class="black-color">
											<?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?><br><b class="gray-color bold-none">
												<?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></b></span> 
										<span class="text-success mt10 text-left"><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></span></span>
								</li>
								<?php
								if ($k == ($cntBrt - 1))
								{
									?>
									<?php
									$expArrivedate	 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cntBrt - 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cntBrt - 1]->brt_trip_duration . ' MINUTE'));
									?>
									<li>
										<div><span></span>
											<?php
											$locAddress		 = ($brt->brt_to_location == '') ? $brt->brtToCity->cty_name : $brt->brt_to_location;
											$locAddress		 .= ($brt->brt_to_latitude > 0) ? '' : ', ' . $brt->brtToCity->cty_name;
											?>

											<div class="title black-color"><?= $locAddress ?>
												<span class="pull-right pr30 pl30">Day <?= $tdays ?></span></div>
											<div class="info hide"><?= ($brt->brt_to_location == '' ) ? $brt->brtToCity->cty_name : $brt->brt_to_location ?></div>
											<div class="type hide"><?= ($brt->brt_trip_distance < $model1->bkg_trip_distance && $model1->bkg_booking_type == 1) ? $model1->bkg_trip_distance : $brt->brt_trip_distance ?><br>km</div>
										</div> <span class="number "><span class="black-color "><?= DateTimeFormat::DateTimeToDatePicker($expArrivedate); ?><br><b class="gray-color bold-none"><?= DateTimeFormat::DateTimeToTimePicker($expArrivedate); ?></b></span> 
											<span class="timeing-box hide"><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></span></span>
									</li><?php
								}
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>	
	</div>
<? }
?>