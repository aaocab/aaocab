
<div class="row">
    <div class="col-md-12 col-sm-10 col-xs-12">
        <div class="panel panel-white">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th style="text-align:center">Team Name</th>
							<th style="text-align:center">Open count</th>
							<th style="text-align:center">Overdue count</th>
							<th style="text-align:center">Oldest due date</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($result)
						{
							foreach ($result as $row)
							{
								?>
								<tr>
									<td style="text-align:center"><?php echo $row['tea_name'] ?></td>
									<td style="text-align:center"><?php echo $row['openCount'] ?></td>
									<td style="text-align:center"><?php echo $row['overdueCount'] ?></td>
									<td style="text-align:center"><?php echo $row['oldestDueDate'] ?></td>
								</tr>
								<?php
							}
						}
						else
						{
							?>
							<tr >
								<td colspan="4">No Record found</td>
							</tr>
							<?php
						}
						?>


					</tbody>
				</table>
			</div>
        </div>
    </div>
</div>
