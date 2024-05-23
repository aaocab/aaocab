
<?php
$csr			 = " ";
$secondVar		 = "--";
$tripInformer	 = "--";
$csr1			 = " ";
?>


<style>
    .tracker-div{
		display: inline-block;
		margin-right: 2px;
		background: #fff;
	}
    .tracker-top{
		background: #3464ae;
		color: #fff;
		padding: 2px;
	}
    .tracker-footer{
		border: #f3f3f3 1px solid;
		padding: 2px;
	}
</style>
<div class="panel-advancedoptions" >

	<div class="row">

        <div class="col-lg-12 tracker-table">

            <div style="overflow-x: scroll; padding-bottom: 15px;">
				<div class="tracker-div">
                    <div class="tracker-top"><b>New</b></div>
                    <div class="tracker-footer"><?php echo date("d/m/Y h:i A", strtotime($bookingModel->bkg_create_date)); ?></div>
					<?php $arrPlatfrom	 = BookingTrail::model()->booking_platform; ?>
					<div class="tracker-footer"><?php
						if ($bookingModel->bkgTrail->bkg_platform == 2)
						{
							$admin	 = Admins::model()->findByPk($bookingModel['bkg_admin_id']);
							$csr	 = " ( " . $admin->adm_fname . " " . $admin->adm_lname . " )";
						}

						echo $arrPlatfrom[$bookingModel->bkgTrail->bkg_platform] . $csr;
						?></div>
                </div>

				<?php
				foreach ($event as $key => $value)
				{

					$dt		 = BookingLog::model()->getDetailByEvent($key, $bookingModel->bkg_id);
					$admin	 = Admins::model()->findByPk($dt['blg_user_id']);
					if ($dt > 0)
					{
						?>

						<div class="tracker-div">
							<div class="tracker-top"><b><?php echo $event[$dt['blg_event_id']]; ?></b></div>
							<div class="tracker-footer"><?php echo date("d/m/Y h:i A", strtotime($dt['blg_created'])); ?></div>
							<div class="tracker-footer">
								<?php
								if ($dt['blg_user_type'] == 4)
								{
									$csr1 = " ( " . $admin->adm_fname . " " . $admin->adm_lname . " )";
								}
								else
								{
									$csr1 = " ";
								}
								if ($dt['blg_event_id'] == 7)
								{
									//$assignType	 = ($bookingModel->bkgBcb->bcb_assign_mode == 0) ? "Auto Assign" : "Manual Assign";
									if ($bookingModel->bkgBcb->bcb_assign_mode == 0)
									{
										$assignType = "Auto Assign";
									}
									elseif ($bookingModel->bkgBcb->bcb_assign_mode == 1)
									{
										$assignType = "Manual Assign";
									}
									else
									{
										$assignType = "Direct Accept";
									}
									$secondVar = " " . $assignType . $csr1;
								}
								if (in_array($dt['blg_event_id'], [44, 46]))
								{


									$secondVar = $csr1;
								}

								echo $dt['user_type'] . $secondVar;
								?>
							</div>
						</div>
						<?php
					}
				}

				$rowDetails = ServiceCallQueue::getDispatchDetails($bookingModel->bkg_id);
				if ($rowDetails)
				{
					?>
					<div class="tracker-div">
						<div class="tracker-top"><b>Dispatch</b></div>
						<div class="tracker-footer"><?php echo $rowDetails['DATE'] != null ? date("d/m/Y h:i A", strtotime($rowDetails['DATE'])) : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; ?></div>
						<div class="tracker-footer"><?php echo $rowDetails['Platform']; ?></div>
					</div>
					<?php
				}
				echo "</br >";
				foreach ($trackLogmodel as $value)
				{

					$coordinates = (explode(",", $value['btl_coordinates']));
					$latLong	 = round($coordinates[0], 4) . ',' . round($coordinates[1], 4);
					?> 
					<div class="tracker-div">
						<div class="tracker-top"><b><?php echo $trackevent[$value['btl_event_type_id']]; ?></b></div>
						<div class="tracker-footer">
							<a href="https://maps.google.com/?q=<?php echo $value['btl_coordinates']; ?>" target="_blank"><?= $latLong ?></a>
						</div>
						<div class="tracker-footer"><?php echo date("d/m/Y h:i A", strtotime($value['btl_sync_time'])) ?></div>

						<div class="tracker-footer"><?php
							if ($value['user_type'] != 0)
							{
								$tripInformer = $value['user_type'];
							}
							if ($value['btl_user_type_id'] == 3)
							{
								$value['btl_user_id'];
								$cttId		 = ContactProfile::getByEntityId($value['btl_user_id'], UserInfo::TYPE_CONSUMER);
								$cttModel	 = Contact::model()->find("ctt_id=:cId AND ctt_active=1", ['cId' => $cttId]);

								$name			 = " (" . $cttModel->ctt_name . ") ";
								$tripInformer	 = $value['user_type'] . " " . $name;
							}


							if ($value['countEvent'] > 1)
							{
								$time = "---" . $value['countEvent'] . "times";
							}
							else
							{
								$time = "";
							}

							echo $tripInformer . $time;
							?>
						</div>
					</div>
				<?php }
				?>
				<?php
				$drvId			 = $bookingModel->bkgBcb->bcb_driver_id;
				$drvStat		 = DriverStats::model()->getLastLocation($drvId);
				$nextthreehr	 = date('Y-m-d H:i:s', strtotime('+180 min'));
				$pickupDateTime	 = date('Y-m-d H:i:s', strtotime($bookingModel->bkg_pickup_date));
				$cttId			 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
				$cttModel		 = Contact::model()->find("ctt_id=:cId AND ctt_active=1", ['cId' => $cttId]);
                 $lastLocation =  $bookingModel->bkgTrack->btk_last_coordinates;
				if ($pickupDateTime < $nextthreehr && (in_array($bookingModel->bkg_status, [3, 5])))
				{
					if (!empty($drvId) && !empty($drvStat) && is_array($drvStat) && !empty($drvStat['drv_last_loc_date']))
					{
						?>
						<div class="tracker-div">
							<div class="tracker-top"><b>Driver Last Location</b></div>
							<div class="tracker-footer">
<!--								<a href="https://maps.google.com/?q=<?php echo $drvStat['drv_last_loc_lat'] . "," . $drvStat['drv_last_loc_long'] ?>" target="_blank" class="color-black"><?php echo ($drvStat['drv_last_loc_lat'] != '' && $drvStat['drv_last_loc_long'] != '') ? round($drvStat['drv_last_loc_lat'], 4) . "," . round($drvStat['drv_last_loc_long'], 4) : '-'; ?></a>-->
						
                            
                            <a href="https://maps.google.com/?q=<?php echo $lastLocation;?>" target="_blank" class="color-black"><?php echo $lastLocation; ?></a>
                            
                            </div>
							<div class="tracker-footer"><?php echo date("d/m/Y h:i A", strtotime($drvStat['drv_last_loc_date'])) ?></div>
							<div class="tracker-footer"><?php echo "Driver (" . $cttModel->ctt_name . ") " ?></div>
						</div>	
						<?php
					}
				}
				$dtCancel = BookingLog::model()->getDetailByEvent(10, $bookingModel->bkg_id);
				if ($dtCancel > 0)
				{
					$admin = Admins::model()->findByPk($dtCancel['blg_user_id']);
					?>
					<div class="tracker-div">
						<div class="tracker-top"><b>Cancel</b></div>
						<div class="tracker-footer"><?php echo date("d/m/Y h:iA", strtotime($dtCancel['blg_created'])); ?></div>
						<div class="tracker-footer">
							<?php
							if ($dtCancel['blg_user_type'] == 4)
							{
								$csr = "--by " . $admin->adm_fname . " " . $admin->adm_lname;
							}
							$secondVar = "<span title ='" . $dtCancel['blg_desc'] . "'>" . $dtCancel['user_type'] . $csr . "</span>";

							echo $secondVar;
							?>
						</div>
					</div>
				<?php } ?>
            </div>

        </div>
    </div>
</div>

