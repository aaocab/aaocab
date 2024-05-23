<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<?
$statusList = Booking::model()->getActiveBookingStatus();
?>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
                            <table class="table table-bordered mb0"  >
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Name</th>
                                    <th>Route</th>
                                    <th>Booking Date</th>
                                    <th>Pickup Date</th>
                                    <th>Status</th>
                                </tr>
								<?
								foreach ($model as $key => $val)
								{
                                    //$bkgModel=Booking::model()->findByPk($val);
									?>
									<tr>
										<td><?= $val['bkg_booking_id']; ?></td> 
										<td><?= $val['bkg_user_fname'].'&nbsp;'.$val['bkg_user_lname']; ?></td>     
										<td><?= $val['from_city'] . '-' . $val['to_city']; ?></td>     
										<td><?= DateTimeFormat::DateTimeToLocale($val['bkg_create_date']); ?></td>     
										<td><?= DateTimeFormat::DateTimeToLocale($val['bkg_pickup_date']); ?></td>
										<td><?= $statusList[$val['bkg_status']]; ?></td>
									</tr>
									<?
								}
								?>
                            </table>
							<?
							$this->widget('CLinkPager', array('pages' => $usersList->pagination));
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

