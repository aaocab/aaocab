<div class="panel">  
    <div class="panel-body">
        <table class="table table-bordered">
            <tr class="blue2 white-color">
                <td><b>Zone</b></td>
                <td><b>Last Month</b></td>
                <td><b>Month to Date</b></td>
                <td><b>Last Week</b></td>
                <td><b>Week to Date</b></td>
            </tr>
			<?php
			if ($records > 0)
			{
				foreach ($records as $record)
				{
					?>       
					<tr>
						<td><?= strtoupper($record['zone_name']); ?></td>  
						<td><?= $record['last_month_count']; ?></td>
						<td><?= $record['month_to_date_count']; ?></td>
						<td><?= $record['last_week_count']; ?></td>
						<td><?= $record['week_to_date_count']; ?></td>
					</tr>
					<?php
				}
			}
			?>  
        </table>
    </div>
</div>





