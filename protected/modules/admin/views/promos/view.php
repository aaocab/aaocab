

<div class="col-xs-12 text-center h2 mt0">
	<label for="type" class="control-label"><span style="font-weight: normal; font-size: 30px;">PROMO CODE:</span> </label>
	<?= $promoModel->prm_code ?>
</div>


<div class="col-xs-11 p40">
	<div class="col-xs-12">
		<div class="col-xs-6">
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<div class="col-xs-12 mb10"><b>DETAILS</b></div>
					<div class="col-xs-12"><b>Description:</b> <?= $promoModel->prm_desc ?></div>
					<div class="col-xs-6 mt5"><b>Valid Form:</b> <?= date('d/m/Y h:i A', strtotime($promoModel->prm_valid_from)); ?></div>
					<div class="col-xs-6 mt5"><b>Valid Upto:</b> <?= date('d/m/Y h:i A', strtotime($promoModel->prm_valid_upto)); ?></div>
					<div class="col-xs-6 mt5"><b>Pickupdate From:</b> <?= ($promoModel->prm_pickupdate_from != '') ? date('d/m/Y h:i A', strtotime($promoModel->prm_pickupdate_from)) : "&nbsp;" ?></div>
					<div class="col-xs-6 mt5"><b>Pickupdate To:</b> <?= ($promoModel->prm_pickupdate_to != '') ? date('d/m/Y h:i A', strtotime($promoModel->prm_pickupdate_to)) : "&nbsp;" ?></div>
					<div class="col-xs-12 mt5"><b>Platform:</b> <?php
						$platformArr = explode(',', $promoModel->prm_applicable_platform);
						foreach ($platformArr as $key => $value)
						{
							?>
							<?= Promos::$source_type[$value] . ","; ?>
						<?php } ?></div>
					<div class="col-xs-6 mt5"><b>Createdate From:</b> <?= ($promoModel->prm_createdate_from != '') ? date('d/m/Y h:i A', strtotime($promoModel->prm_createdate_from)) : "&nbsp;" ?></div>
					<div class="col-xs-6 mt5"><b>Createdate To:</b> <?= ($promoModel->prm_createdate_to != '') ? date('d/m/Y h:i A', strtotime($promoModel->prm_createdate_to)) : "&nbsp;" ?></div>
					<div class="col-xs-6 mt5"><b>Use max:</b> <?= $promoModel->prm_use_max == 0 ? Promos::$useMax[$promoModel->prm_use_max] : $promoModel->prm_use_max; ?></div>
					<div class="col-xs-6 mt5"><b>Promo Used:</b> <?= $promoModel->prm_used_counter == 0 ? '' : $promoModel->prm_used_counter; ?></div>
					<div class="col-xs-6 mt5"><b>Applicable Type:</b> <?= Promos::$applicableType[$promoModel->prm_applicable_type]; ?></div>
					<div class="col-xs-6 mt5"><b>Activate On:</b> <?= Promos::$activateOn[$promoModel->prm_activate_on]; ?></div>
					<div class="col-xs-6 mt5"><b>Applicable User:</b> <?= Promos::$applicableUserType[$promoModel->prm_applicable_user]; ?></div>
					<div class="col-xs-6 mt5"><b>Next Trip Applicable:</b> <?= $promoModel->prm_applicable_nexttrip == 0 ? 'No' : 'Yes'; ?></div>
					<div class="col-xs-6 mt5"><b>Minimum Base Amount:</b> <?= $promoModel->prm_min_base_amount != '' ? $promoModel->prm_min_base_amount : ''; ?></div>
					<div class="col-xs-6 mt5"><b>User Logged In:</b> <?= $promoModel->prm_logged_in == 0 ? 'No' : 'Yes'; ?></div>
					<div class="col-xs-6 mt5"><b>Minimum Booking Created:</b> <?= $promoModel->prm_booked_min != '' ? $promoModel->prm_booked_min : ''; ?></div>
					<div class="col-xs-6 mt5"><b>Maximum Booking Created:</b> <?= $promoModel->prm_booked_max != '' ? $promoModel->prm_booked_max : ''; ?></div>
					<div class="col-xs-6 mt5"><b>Minimum Booking Completed:</b> <?= $promoModel->prm_complete_min != '' ? $promoModel->prm_complete_min : ''; ?></div>
					<div class="col-xs-6 mt5"><b>Maximum Booking Completed:</b> <?= $promoModel->prm_complete_max != '' ? $promoModel->prm_complete_max : ''; ?></div>
					<div class="col-xs-6 mt5"><b>Not Travelled(in Days):</b> <?= $promoModel->prm_not_travelled != '' ? $promoModel->prm_not_travelled : ''; ?></div>

				</div>
			</div>
		</div>

		<div class="col-xs-6">
			<div class="panel panel-default panel-border">
				<div class="panel-body">

					<label class="mb10"><b>CALCULATION</b></label>

					<?
					if ($promoModel->prmCal->pcn_type == 1 || $promoModel->prmCal->pcn_type == 3)
					{
						?>
						<div class="col-xs-12" style="border: 1px solid #7e8691">
							<div class="col-xs-6"><b>Discount Type:</b> <?= Promos::$promoType[$promoModel->prmCal->pcn_type]; ?></div><br>
							<div class="col-xs-6"><b>Discount Value Type:</b> 
								<?= Promos::$valueType[$promoModel->prmCal->pcn_value_type_cash]; ?></div>
							<div class="col-xs-6"><b>Discount Value:</b> 
								<?= $promoModel->prmCal->pcn_value_cash ?></div>
							<div class="col-xs-6"><b>Discount cannot exceeded:</b> 
								<?= $promoModel->prmCal->pcn_max_cash ?></div>
							<div class="col-xs-6"><b>Minimum discount given:</b> 
								<?= $promoModel->prmCal->pcn_min_cash ?></div>
						</div>
					<? } ?>
					<?
					if ($promoModel->prmCal->pcn_type == 2 || $promoModel->prmCal->pcn_type == 3)
					{
						?>
						<div class="col-xs-12 mt5"  style="border: 1px solid #7e8691">
							<div class="col-xs-6"><b>Discount Type:</b> 
								<?= Promos::$promoType[$promoModel->prmCal->pcn_type]; ?></div><br>
							<div class="col-xs-6"><b>Discount Value Type : </b>
								<?= Promos::$valueType[$promoModel->prmCal->pcn_value_type_coins]; ?></div>
							<div class="col-xs-6"><b>Discount Value: </b>
								<?= $promoModel->prmCal->pcn_value_coins ?></div>
							<div class="col-xs-6"><b>Discount cannot exceeded :</b> 
								<?= $promoModel->prmCal->pcn_max_coins ?></div>
							<div class="col-xs-6"><b>Minimum discount given: </b>
								<?= $promoModel->prmCal->pcn_min_coins ?></div>
						</div>
					<? } 
					if ($promoModel->prmCal->pcn_type == 4)
					{
						?>
						<div class="col-xs-12 mt5"  style="border: 1px solid #7e8691">
							<div class="col-xs-6"><b>Discount Type:</b> 
								<?= Promos::$promoType[$promoModel->prmCal->pcn_type]; ?></div><br>
							<div class="col-xs-6"><b>Fixed Amount </b>
								<?= $promoModel->prmCal->pcn_fixed_price ?></div>
						</div>
					<? } ?>

				</div>
			</div>
		</div>
	</div>

	<div class="col-xs-12">
		<div class="col-xs-6">
			<div class="panel panel-border">
				<div class="panel-body">
					<label class="mb10"><b>DATE FILTER</b></label>
					<br>
					<div class="col-xs-12 mb5"  style="border: 1px solid #7e8691">
						<label>By Create Date</label><br>
						<div class="col-xs-6"><b>Weekdays:</b> <?php
							if ($dateModel->pcd_weekdays_create != '')
							{
								$list = explode(',', $dateModel->pcd_weekdays_create);
								foreach ($list as $key => $value)
								{
									echo PromoDateFilter::getWeekDaysList($value);
									echo "<br>";
								}
							}
							?>
						</div>
						<div class="col-xs-6"><b>Weeks:</b> <?php
							if ($dateModel->pcd_weeks_create != '')
							{
								$list = explode(',', $dateModel->pcd_weeks_create);
								foreach ($list as $key => $value)
								{
									echo PromoDateFilter::getWeekList($value);
									echo "<br>";
								}
							}
							?>
						</div>
						<div class="col-xs-6"><b>Monthdays:</b> <?php
							if ($dateModel->pcd_monthdays_create != '')
							{
								$list = explode(',', $dateModel->pcd_monthdays_create);
								foreach ($list as $key => $value)
								{
									echo PromoDateFilter::getMonthDaysList($value);
									echo "<br>";
								}
							}
							?>
						</div>
						<div class="col-xs-6"><b>Months:</b> <?php
							if ($dateModel->pcd_months_create != '')
							{
								$list = explode(',', $dateModel->pcd_months_create);
								foreach ($list as $key => $value)
								{
									echo PromoDateFilter::getMonthList($value);
									echo "<br>";
								}
							}
							?>
						</div>
					</div>


					<div class="col-xs-12 mb5"  style="border: 1px solid #7e8691">
						<label class="mt10">By Pickup Date</label><br>
						<div class="col-xs-6"><b>weekdays:</b> <?php
							if ($dateModel->pcd_weekdays_pickup != '')
							{
								$list = explode(',', $dateModel->pcd_weekdays_pickup);
								foreach ($list as $key => $value)
								{
									echo PromoDateFilter::getWeekDaysList($value);
									echo "<br>";
								}
							}
							?>
						</div>
						<div class="col-xs-6"><b>Weeks:</b> <?php
							if ($dateModel->pcd_weeks_pickup != '')
							{
								$list = explode(',', $dateModel->pcd_weeks_pickup);
								foreach ($list as $key => $value)
								{
									echo PromoDateFilter::getWeekList($value);
									echo "<br>";
								}
							}
							?>
						</div>
						<div class="col-xs-6"><b>Monthdays:</b> <?php
							if ($dateModel->pcd_monthdays_pickup != '')
							{
								$list = explode(',', $dateModel->pcd_monthdays_pickup);
								foreach ($list as $key => $value)
								{
									echo PromoDateFilter::getMonthDaysList($value);
									echo "<br>";
								}
							}
							?>
						</div>
						<div class="col-xs-6"><b>Months:</b> <?php
							if ($dateModel->pcd_months_pickup != '')
							{
								$list = explode(',', $dateModel->pcd_months_pickup);
								foreach ($list as $key => $value)
								{
									echo PromoDateFilter::getMonthList($value);
									echo "<br>";
								}
							}
							?>
						</div>
					</div>


				</div>
			</div>
		</div>

		<div class="col-xs-6">
			<div class="panel panel-border">
				<div class="panel-body">
					<label class="mb10"><b>AREA FILTER</b></label>
					<div class="row mb5">
						<div class="col-xs-6"><b>From Type:</b> <?= Promos::$areaType[$entityModel->pef_area_type_from] ?></div>
						<div class="col-xs-6">
							<label><b>From Area:</b></label><br> <?php
							if ($entityModel->pef_area_type_from == 1)
							{
								$list = explode(',', $entityModel->pef_area_from_id);
								foreach ($list as $key => $value)
								{
									echo Zones::model()->getZoneById($value);
									echo "<br>";
								}
							}
							else if ($entityModel->pef_area_type_from == 2)
							{
								$list = explode(',', $entityModel->pef_area_from_id);
								foreach ($list as $key => $value)
								{
									echo States::model()->getNameById($value);
									echo "<br>";
								}
							}
							else if ($entityModel->pef_area_type_from == 3)
							{
								$list = explode(',', $entityModel->pef_area_from_id);
								foreach ($list as $key => $value)
								{
									echo Cities::getName($value);
									echo "<br>";
								}
							}
							else if ($entityModel->pef_area_type_from == 4)
							{
								$list = explode(',', $entityModel->pef_area_from_id);
								foreach ($list as $key => $value)
								{
									echo Promos::$region[$value];
									echo "<br>";
								}
							}
							else
							{
								echo " ";
							}
							?>
						</div>
					</div>

					<div class="row mb5">
						<div class="col-xs-6"><b>To Type:</b> <?= Promos::$areaType[$entityModel->pef_area_type_to] ?></div>
						<div class="col-xs-6">
							<label><b>To Area:</b></label><br> <?php
							if ($entityModel->pef_area_type_to == 1)
							{
								$list = explode(',', $entityModel->pef_area_to_id);
								foreach ($list as $key => $value)
								{
									echo Zones::model()->getZoneById($value);
									echo "<br>";
								}
							}
							else if ($entityModel->pef_area_type_to == 2)
							{
								$list = explode(',', $entityModel->pef_area_to_id);
								foreach ($list as $key => $value)
								{
									echo States::model()->getNameById($value);
									echo "<br>";
								}
							}
							else if ($entityModel->pef_area_type_to == 3)
							{
								$list = explode(',', $entityModel->pef_area_to_id);
								foreach ($list as $key => $value)
								{
									echo Cities::getName($value);
									echo "<br>";
								}
							}
							else if ($entityModel->pef_area_type_to == 4)
							{
								$list = explode(',', $entityModel->pef_area_to_id);
								foreach ($list as $key => $value)
								{
									echo Promos::$region[$value];
									echo "<br>";
								}
							}
							else
							{
								echo " ";
							}
							?>
						</div>
					</div>
					<div class="row mb5">
						<div class="col-xs-6"><b>Type (Source/Destination):</b> <?= Promos::$areaType[$entityModel->pef_area_type] ?></div>
						<div class="col-xs-6">
							<label><b>Area (Source/Destination):</b></label><br> <?php
							if ($entityModel->pef_area_type == 1)
							{
								$list = explode(',', $entityModel->pef_area_id);
								foreach ($list as $key => $value)
								{
									echo Zones::model()->getZoneById($value);
									echo "<br>";
								}
							}
							else if ($entityModel->pef_area_type == 2)
							{
								$list = explode(',', $entityModel->pef_area_id);
								foreach ($list as $key => $value)
								{
									echo States::model()->getNameById($value);
									echo "<br>";
								}
							}
							else if ($entityModel->pef_area_type == 3)
							{
								$list = explode(',', $entityModel->pef_area_id);
								foreach ($list as $key => $value)
								{
									echo Cities::getName($value);
									echo "<br>";
								}
							}
							else if ($entityModel->pef_area_type == 4)
							{
								$list = explode(',', $entityModel->pef_area_id);
								foreach ($list as $key => $value)
								{
									echo Promos::$region[$value];
									echo "<br>";
								}
							}
							else
							{
								echo " ";
							}
							?>
						</div>
					</div>
					<div class="row mb5">
						<div class="col-xs-6"><label><b>Booking Type:</b></label> <?php
							if ($entityModel->pef_booking_type != '')
							{
								$list = explode(',', $entityModel->pef_booking_type);
								foreach ($list as $key => $value)
								{
									echo Booking::model()->booking_type[$value];
									echo "<br>";
								}
							}
							?>
						</div>

					</div>
					<div class="row">
						<div class="col-xs-6"><label><b>Car Type:</b></label><?php
									if ($entityModel->pef_cab_type != '')
									{
										$list = explode(',', $entityModel->pef_cab_type);
										foreach ($list as $key => $value)
										{
											echo VehicleCategory::model()->findByPk($value)->vct_label;
											echo "<br>";
										}
									}
									?>
						</div>			
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


