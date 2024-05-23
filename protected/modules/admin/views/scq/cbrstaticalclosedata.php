<div class="h5 mt0" style="color: red">* This report is based on followup close date</div>
<div class="row">
    <div class="col-md-12 col-sm-10 col-xs-12">
        <div class="panel panel-white">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th style="text-align:center">Team Name</th>
							<th style="text-align:center">Total Closed Count</th>
							<th style="text-align:center">Unique Count Team Member Closed</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 0;
						foreach ($result as $row)
						{
							$i++;
							?>
							<tr>
								<td style="text-align:center"><?php
									if ($row['scq_follow_up_queue_type'] == 9 && $row['team_name'] != null)
									{
										echo $row['team_name'];
									}
									else if ($row['scq_follow_up_queue_type'] == 9 && $row['team_name'] == null)
									{
										$teamName = Teams::getByID($row['scq_to_be_followed_up_by_id']);
										echo $teamName;
									}
									else
									{
										$teamId		 = Teams::getTeamIdFromCached($row['scq_follow_up_queue_type']);
										$teamName	 = Teams::getByID($teamId);
										echo $teamName;
									}
									?></td>
								<td style="text-align:center"><?php echo $row['totalCloseCount'] ?></td>
								<td style="text-align:center"><?php echo $row['uniqueCsrCount'] ?></td>
							</tr>
							<?php
						}

						if ($i == 0)
						{
							?>
							<tr >
								<td colspan="3">No Record found</td>
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
