 <style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12"> 
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
                            <table class="table table-bordered mb0"  >
                                <tr>
                                    <th>Trip status</th>
                                    <th>Date-Time</th>
									<th>Latitude & Longitude</th>
									<th>Device</th>
									<th>Discrepancy</th>
									<th>Remarks</th>
								</tr>
								<?php
								foreach ($bkgDriverAppinfo as $val)
								{
									?>
									<tr <?php
									if ($val['btl_is_discrepancy'] > 0)
									{
										?>class="f-red"<?php } ?>>
										<td><?php
											$eventType	 = BookingLog::model()->driverEventList($val['btl_event_type_id'], $view		 = 1);
											if ($val['btl_event_type_id'] != 205)
											{
												echo $eventType['event_type'];
											}
											else
											{
												echo $eventType['event_type'] . '(' . $val['btl_trip_late'] . ' Min)';
											}
											?>
										</td>
										<td><?= $val['btl_sync_time'] ?></td>
										<td>
                                            <?php
                                            $bookingModel	 = Booking::model()->findByPk($val['btl_bkg_id']);
                                            if($val['btl_event_type_id']==104)
                                            {
                                                $routeCoordinate = $bookingModel->bookingRoutes[0]->brt_to_latitude . ',' . $bookingModel->bookingRoutes[0]->brt_to_longitude;
                                            }
                                            else
                                            {
                                                $routeCoordinate = $bookingModel->bookingRoutes[0]->brt_from_latitude . ',' . $bookingModel->bookingRoutes[0]->brt_from_longitude;
                                            }
                                            $link			 = "https://google.com/maps/dir/?api=1&origin=" . $val['btl_coordinates'] . "&destination=" . $routeCoordinate . "";
                                            ?>
                                            <a href="<?=$link?>" target="_blank"><?= $val['btl_coordinates'] ?></a>
                                            <!--<a  href="https://maps.google.com/?q=<?= $val['btl_coordinates'] ?>" target="_blank"><?= $val['btl_coordinates'] ?></a></td>-->
										<td><?php echo CJSON::decode($val['btl_device_info'])['deviceName']." (".CJSON::decode($val['btl_device_info'])['uniqueId'].")";?> </td>
										<td><?php echo $val['btl_is_discrepancy']; ?></td>
										<td><?php
											if ($val['btl_is_discrepancy'] > 0)
											{
												$bookingModel	 = Booking::model()->findByPk($val['btl_bkg_id']);
												$routeCoordinate = $bookingModel->bookingRoutes[0]->brt_from_latitude . ',' . $bookingModel->bookingRoutes[0]->brt_from_longitude;
												$link			 = "https://google.com/maps/dir/?api=1&origin=" . $val['btl_coordinates'] . "&destination=" . $routeCoordinate . "";

												$arr = CJSON::decode($val['btl_discrepancy_remarks']);

												foreach ($arr as $value)
												{
													echo "<a href='$link' target='_blank' class='f-red'>" . $value['Location'] . "</a>";
													echo $value['deviceID'];
													echo $value['checksum'];
												}
											}
											?></td>
									</tr> 
									<?php
								}
								?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



