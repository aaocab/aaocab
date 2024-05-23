<div class="row">
	<div class="col-xs-12 table-style-panel">
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr class="bg-purple color-white">
					<td><b>Driver Name</b></td>
					<td><b>Driver ID</b></td>
					<td><b>Phone No</b></td>
					<td><b>Status</b></td>
				</tr>
				<?php
				foreach ($driverData as $driverDataView)
				{
					?>
					<tr>
						<td><b><?= $driverDataView['ctt_name'] ?></b></td>
						<td><a target="_blank"  href="/admpnl/driver/view?code=<?= $driverDataView['drv_code'] ?>" target="_blank"><?= $driverDataView['drv_code'] ?></a></td>
						<td><?= $driverDataView['drv_phone']; ?></td>
						<td><?php echo ($driverDataView['ctt_active'] == 1 ? 'Active' : 'Inactive') ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>		
	</div>
</div>