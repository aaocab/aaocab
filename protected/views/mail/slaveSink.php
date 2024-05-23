Our Slave Database having problem from: <?php echo $time; ?><br>
Database Connection String: <?php echo $connectionString; ?><br>
Is Slave Down: <?php echo $isSlaverRunning ? "Yes" : "No"; ?><br>
Database sync is delayed by:  <?php echo $delayedByTime; ?> Second
<br/><br/>

<table class="table" border="2">
	<thead class="thead-light"><tr>
			<th scope="col" data-column="Id" style=""><span>Id</span></th>
			<th scope="col" data-column="User" style=""><span>User</span></th>
			<th scope="col" data-column="Host" style=""><span>Host</span></th>
			<th scope="col" data-column="db" style=""><span>db</span></th>
			<th scope="col" data-column="Command" style=""><span>Command</span></th>
			<th scope="col" data-column="Time" style=""><span>Time</span></th>
			<th scope="col" data-column="State" style=""><span>State</span></th>
			<th scope="col" data-column="Info" style=""><span>Info</span></th>
			<th scope="col" data-column="Progress" style=""><span>Progress</span></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($processList as $value)
		{
			?>
			<tr>  
				<td scope="row"><?php echo $value['Id']; ?></td>
				<td><?php echo $value['User']; ?></td>
				<td><?php echo $value['Host']; ?></td>
				<td><?php echo $value['db']; ?></td>
				<td><?php echo $value['Command']; ?></td>
				<td><?php echo $value['Time']; ?></td>
				<td><?php echo $value['State']; ?></td>
				<td><?php echo $value['Info']; ?></td>
				<td><?php echo $value['Progress']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>