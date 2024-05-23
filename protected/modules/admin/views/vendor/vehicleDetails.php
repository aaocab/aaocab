
<div class="row">
	<div class="col-xs-12 table-style-panel">
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr class="bg-purple color-white">
					<td><b>Vehicle</b></td>
					<td><b>Vehicle ID</b></td>
					<td><b>Type</b></td>
					<td><b>Reg on</b></td>
					<td><b>Status</b></td>
				</tr>
				<?php
				foreach ($cabData as $cabDataView)
				{
				?>
				<tr>
					<td><b><?= $cabDataView['vhc_make'] ?> <?= $cabDataView['vhc_model'] ?></b></td>
					<td><a target="_blank"  href="/admpnl/vehicle/view?code=<?= $cabDataView['vhc_code'] ?>" target="_blank"><?= $cabDataView['vhc_number'] ?></a></td>
					<td><b><?= $cabDataView['vct_label']; ?></b></td>
					<td><?= date('d M Y', strtotime($cabDataView['vhc_created_date'])); ?></td>
					<td><?= $cabDataView['vht_active'] ?></td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>
	




