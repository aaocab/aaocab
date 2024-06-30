
<div class="row">
	<div class="col-xs-12 table-style-panel">
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr class="bg-purple color-white">
					<td><b>Vehicle Number</b></td>
					<td><b>Make Model</b></td>
					<td><b>Type</b></td>
					<td><b>Tags</b></td>
					<td><b>Rel Vendor</b></td>
					<td><b>Reg on</b></td>
					<td><b>Status</b></td>
				</tr>
				<?php
				foreach ($cabData as $cabDataView)
				{
					?>
					<tr>
						<td><a target="_blank"  href="/aaohome/vehicle/view?code=<?= $cabDataView['vhc_code'] ?>" target="_blank"><?= $cabDataView['vhc_number'] ?></a></td>

						<td><b><?= $cabDataView['vhc_make'] ?> <?= $cabDataView['vhc_model'] ?></b>
						</td>
						<td><?= $cabDataView['vct_label']; ?></td>
						<td>
							<?php
							if ($cabDataView['vhc_approved'] == 1)
							{
								echo ' <span class="label label-success ">Approved</span>';
							}
							if ($cabDataView['vhc_approved'] == 0)
							{
								echo ' <span class="label label-default ">Not Verified</span>';
							}
							if ($cabDataView['vhc_approved'] == 2)
							{
								echo ' <span class="label label-primary ">Pending approval (verified)</span>';
							}
							if ($cabDataView['vhc_approved'] == 4)
							{
								echo ' <span class="label label-warning ">Approved but papers expired</span>';
							}
							if ($cabDataView['vhc_approved'] == 3)
							{
								echo ' <span class="label label-danger ">Rejected</span>';
							}
							if ($cabDataView['vhc_is_freeze'] == 1)
							{
								echo ' <span class="label label-danger ">Frozen</span>';
							}
							if ($cabDataView['vhs_boost_enabled'] == 1)
							{
								echo ' <span class="label label-success ">Boost Enabled</span>';
							}
							?>
						</td>
						<td><a target="_blank"  href="/aaohome/vendor/view?code=<?= $cabDataView['vnd_code'] ?>" target="_blank"><?= $cabDataView['vnd_code'] ?></a></td>

						<td><?= date('d M Y', strtotime($cabDataView['vhc_created_date'])); ?></td>
						<td><?= $cabDataView['vht_active'] ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>





