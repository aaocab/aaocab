<div class="panel-advancedoptions" >
    <div class="row">          
		<div class="panel" >
			<div class="">
				<?php if ($model->scr_log != '')
				{ ?>
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Modified By</th>
								<th scope="col">Date</th>
								<th scope="col">Markup Type</th>
								<th scope="col">Markup Amount</th>
								<th scope="col">Supported</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$decodedLogs = json_decode($model->scr_log);
							foreach ($decodedLogs as $log)
							{
								$adminData = Admins::findById($log[0]);
								?>
								<tr>
									<th scope="row"><?php echo $adminData->adm_fname . " " . $adminData->adm_lname ; ?></th>
									<td><?php echo date("d/M/Y h:i A", strtotime($log[1])); ?></td>
									<td><?php echo $log[2]; ?></td>
									<td><?php echo $log[3]; ?></td>
									<td><?php echo $log[4]; ?></td>

								</tr>
	<?php } ?>

						</tbody>
					</table>
				<?php
				}
				else
				{
					echo "No records found";
				}
				?>
			</div>
		</div>
    </div>
</div>
