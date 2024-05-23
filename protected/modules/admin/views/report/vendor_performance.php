<div class="panel">  
    <div class="panel-body">
        <div class="row mb20">
            <div class="col-xs-12">
				<?php
				$this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
				?>
            </div>
        </div>
        <table class="table table-bordered">
            <tr class="blue2 white-color">
                <td><b>Vendor Name</b></td>
                <td><b>Rating as of End of Last Month</b></td>
                <td><b>Current Rating</b></td>
                <td><b>Bookings Completed</b></td>
                <td><b>Reviews Received</b></td>
                <td><b>5 star</b></td>
                <td><b>4 star</b></td>
                <td><b>3 star</b></td>
                <td><b>2 star</b></td>
                <td><b>1 star</b></td>
            </tr>
			<?php
			if ($records > 0)
			{
				foreach ($records as $record)
				{
					?>       
					<tr>
						<td><?= strtoupper($record['vnd_name']); ?></td>  
						<td><?= $record['last_month_overall_rating']; ?></td>
						<td><?= $record['current_rating']; ?></td>
						<td><?= $record['booking_completed']; ?></td>
						<td><?= $record['reviews']; ?></td>
						<td><?= $record['five_rating']; ?></td>
						<td><?= $record['four_rating']; ?></td>
						<td><?= $record['three_rating']; ?></td>
						<td><?= $record['two_rating']; ?></td>
						<td><?= $record['one_rating']; ?></td>
					</tr>
					<?php
				}
			}
			?>  
        </table>
        <div class="row">
            <div class="col-xs-12">
				<?php
				$this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
				?>
            </div>
        </div>
    </div>
</div>
