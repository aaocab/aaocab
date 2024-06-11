<div class="row">
	<div class="col-xs-12 table-style-panel">
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr class="bg-purple color-white">
					<td><b>Driver Name</b></td>
					<td><b>Code</b></td>
					<td><b>Tags</b></td>
					<td><b>Rel Vendor</b></td>
					<td><b>Phone No</b></td>
					<td><b>Status</b></td>
				</tr>
				<?php
				$statusLabelList = \Drivers::getApproveStatusList();
				foreach ($driverData as $driverDataView)
				{
					$drvApproved = $driverDataView['drv_approved'];
					?>
					<tr>
						<td><b><?= $driverDataView['ctt_name'] ?></b>
						</td>
						<td><a target="_blank"  href="/admpnl/driver/view?code=<?= $driverDataView['drv_code'] ?>" target="_blank"><?= $driverDataView['drv_code'] ?></a></td>

						<td>
							<?
							if ($driverDataView['ctt_is_name_dl_matched'] == 2)
							{
								echo ' <span class="label label-danger ">DL Mismatch</span>';
							}
							if ($driverDataView['ctt_is_name_pan_matched'] == 2)
							{
								echo ' <span class="label label-danger ">Pan Mismatch</span>';
							}
							if ($driverDataView['drv_approved'] == 1)
							{

								echo ' <span class="label label-info ">Approved</span>';
							}
							else
							{
								echo ' <span class="label label-danger ">' . $statusLabelList[$drvApproved] . '</span>';
							}

							if ($driverDataView['drv_is_freeze'] == 1)
							{
								echo ' <span class="label label-danger ">Blocked</span>';
							}
							?>

						</td>
						<td><a target="_blank"  href="/admpnl/vendor/view?code=<?= $driverDataView['vnd_code'] ?>" target="_blank"><?= $driverDataView['vnd_code'] ?></a></td>

						<td><?= $driverDataView['drv_phone']; ?></td>
						<td><?php echo ($driverDataView['ctt_active'] == 1 ? 'Active' : 'Inactive') ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>		
	</div>
</div>