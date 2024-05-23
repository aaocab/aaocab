<div class="panel">  
    <div class="panel-body">

        <table class="table table-bordered">
            <thead>
                <tr>
					<th>Date</th> 
                    <th>Total Sent</th>
                    <th>Total Delivered</th>
					<th>Total Read</th>
                    <th>Link Opened</th>
                    <th>Download Attempted</th> 
					<th>Logged In</th> 
                </tr>
            </thead>
            <tbody>
				<?php
				foreach ($dataSet as $row)
				{
					?>
					<tr>
						<th><?php echo $row['date'] ?>  </th> 
						<td><?php echo $row['sentCount'] ?> </td> 
						<td><?php echo $row['deliveredCount'] ?> </td> 
						<td><?php echo $row['readCount'] ?> </td> 
						<td><?php echo $row['clickedCount'] ?> </td> 
						<td><?php echo $row['downloadCount'] ?> </td> 
						<td><?php echo $row['loginCount'] ?> </td>  
					</tr> <?
				}
				?>

            </tbody>
        </table>
    </div>
</div>

