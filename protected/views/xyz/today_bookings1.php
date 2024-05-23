<div class="container-fluid p0"><div class="panel panel-white"><div class="panel-body">

            <title>Today's Bookings</title>
			<?
			if ($error == 1)
			{
				?>
				<div class="row m0 mt20" id="passwordDiv">
					<form name="tbkg" method="POST" action="<?= Yii::app()->request->url ?>">
						<div class="col-xs-offset-4 col-xs-4">   
							<div class="form-group row text-center">
								<input class="form-control" type="password" id="psw" name="psw" value="" placeholder="Password" required/>
							</div>
							<div class="Submit-button row text-center">
								<button type="submit" class="btn btn-primary">SUBMIT</button>
							</div>
						</div>
					</form>
				</div>
			<? } ?>
			<?
			if ($error == 2)
			{
				?>
				<div class="row m0 mt20" id="wrongPassword" style="">
					<div class="col-xs-offset-4 col-xs-4">
						<h3>Wrong Password</h3>
						<img src="http://static.commentcamarche.net/es.ccm.net/pictures/Ud6krzOUaQiVrbx4IWkuzUrMD8vWr4qbG1wMtmWKQ94r7Doi6fybXXnACJoLFtKR-lol.png">
					</div>
				</div>
			<? } ?>
			<?
			if ($error == 0)
			{
				?>
				<div style="font-size: 9px;">Last refresh at <?= $bookings['lastRefeshDate']; ?></div>
				<table class="table table-bordered">
					<thead>
						<tr style="color: blue;background: whitesmoke">
							<th colspan="6" class="text-center"><u>TODAY'S COUNT BY BOOKING DATE</u></th>
						</tr>
						<tr style="color: black;background: whitesmoke">
							<th class="text-center"><u>BOOKING TYPE</u></th>
							<th class="text-center"><u>COUNT</u></th>
							<th class="text-center"><u>ADVANCE COUNT</u></th>
							<th class="text-center"><u>CANCELLED COUNT</u></th>
							<th class="text-center"><u>TOTAL AMOUNT</u></th>
							<th class="text-center"><u>GOZO AMOUNT</u></th>
						</tr>
					</thead>
					<tbody id="count_booking_row">
						<?php
						foreach ($bkgmodel as $row)
						{
							$bkgCount		 += $row['ry_booking_count'];
							$canCount		 += $row['ry_cancelled_booking_count'];
							$bkgAmount		 += $row['ry_booking_amount'];
							$gozoAmount		 += $row['ry_gozo_amount'];
							$canGozoAmount	 += $row['ry_cancelled_gozo_amount'];
							?>
							<tr>
								<td class=""><?= ($row['seq'] == '0000') ? $row['name'] : 'ALL' ?></td>
								<td class="text-center"><?= $row['ry_booking_count'] ?></td>
								<td class="text-center"><?= number_format($row['ry_adv_booking_count']) ?></td>
								<td class="text-center"><?= $row['ry_cancelled_booking_count'] ?></td>
								<td class="text-center"><?= number_format($row['ry_booking_amount']) ?></td>
								<td class="text-center"><?= number_format($row['ry_gozo_amount']) ?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				<div class="row" id="routewiseDiv" style="margin-top: 10px;">  
					<div class="col-xs-12 col-sm-5">       
						<table class="table table-bordered">
							<thead>
								<tr style="color: black;background: whitesmoke">
									<th class="text-center"><u>Total Booking</u></th>
									<th class="text-center"><u>MMT</u></th>
									<th class="text-center"><u>B2B Other</u></th>
									<th class="text-center"><u>GozoSHUTTLE</u></th>	
									<th class="text-center"><u>B2C</u></th>									
									<th class="text-center"><u>B2C Unverified</u></th>
									<th class="text-center"><u>B2C Quotes Created</u></th>
									<th class="text-center"><u>B2C Quoted</u></th>
								</tr>
							</thead>
							<tbody id="count_booking_row">                         
								<tr>
									<td class="text-center"><?= $bookings['total_book'] ?></td>
									<td class="text-center"><?= $bookings['total_mmtb2b'] ?></td>
									<td class="text-center"><?= $bookings['total_b2b'] ?></td>
									<td class="text-center"><?= $bookings['total_gozoSuttleb2c'] ?></td>
									<td class="text-center"><?= $bookings['total_b2c'] ?></td>
									<td class="text-center"><?= $bookings['total_b2c_unv'] ?></td>									
									<td class="text-center"><?= $bookings['total_b2c_quot_crt'] ?></td>
									<td class="text-center"><?= $bookings['total_b2c_quot'] ?></td>
								</tr>
							</tbody>
						</table>

						<table class="table table-bordered">
							<thead>
								<tr style="color: black;background: whitesmoke">
									<th class="text-center"><u>Auto-Assigned</u></th>
									<th class="text-center"><u>Manual-Assigned</u></th>
									<th class="text-center"><u>Total Assigned Today</u></th>									

								</tr>
							</thead>
							<tbody id="count_booking_row">                         
								<tr>
									<td class="text-center">
										<?= $bkgassigned[0]['total_auto_assigned'] ?><br>
										<?= "P: {$bkgassigned[0]['autoAssignProfit']}={$bkgassigned[0]['autoAssignProfitCount']}" ?><br>
										<?= "L: {$bkgassigned[0]['autoAssignLoss']}={$bkgassigned[0]['autoAssignLossCount']}" ?><br>
										<?= "B2C: " . $bkgassigned[0]['total_auto_assigned_b2c'] ?><br>
										<?= "B2B MMT: " . $bkgassigned[0]['total_auto_assigned_mmt'] ?><br>	
										<?= "B2B OTHERS: " . $bkgassigned[0]['total_auto_assigned_b2bothers'] ?>
									</td>
									<td class="text-center"><?= $bkgassigned[0]['total_manual_assigned'] ?><br>
										<?= "P: {$bkgassigned[0]['manualAssignProfit']}={$bkgassigned[0]['manualAssignProfitCount']}" ?><br>
										<?= "L: {$bkgassigned[0]['manualAssignLoss']}={$bkgassigned[0]['manualAssignLossCount']}" ?><br>
										<?= "B2C: " . $bkgassigned[0]['total_manual_assigned_b2c'] ?><br>
										<?= "B2B MMT: " . $bkgassigned[0]['total_manual_assigned_mmt'] ?><br>	
										<?= "B2B OTHERS: " . $bkgassigned[0]['total_manual_assigned_b2bothers'] ?>
									</td>
									<td class="text-center"><?= $bkgassigned[0]['total_assigned'] ?><br>
										<?= "P: " . ($bkgassigned[0]['manualAssignProfit'] + $bkgassigned[0]['autoAssignProfit']) . "=" . ($bkgassigned[0]['manualAssignProfitCount'] + $bkgassigned[0]['autoAssignProfitCount']) . "" ?><br>
										<?= "L: " . ($bkgassigned[0]['manualAssignLoss'] + $bkgassigned[0]['autoAssignLoss']) . "=" . ($bkgassigned[0]['manualAssignLossCount'] + $bkgassigned[0]['autoAssignLossCount']) . "" ?><br>
										<?= "B2C: " . $bkgassigned[0]['total_assigned_b2c'] ?><br>
										<?= "B2B MMT: " . $bkgassigned[0]['total_assigned_mmt'] ?><br>	
										<?= "B2B OTHERS: " . $bkgassigned[0]['total_assigned_b2bothers'] ?>										
									</td>

								</tr>
							</tbody>
						</table>

						<table class="table table-bordered mt10">
							<thead>
								<tr style="color: blue;background: whitesmoke">
									<th colspan="5" class="text-center"><u>Today's Region-wise Count</u></th>
								</tr>
								<tr style="color: black;background: whitesmoke">
									<th class="text-center"><u>Region</u></th>
									<th class="text-center"><u>B2B MMT</u></th>
									<th class="text-center"><u>B2B OTHERS</u></th>
									<th class="text-center"><u>B2C</u></th>
									<th class="text-center"><u>Total</u></th>
								</tr>
							</thead>
							<tbody id="count_booking_row">                         
								<?
								$cnt = 0;
								foreach ($regionWiseData as $data)
								{
									$cntmmt		 += $data['cntb2bmmt'];
									$cntb2bother += $data['cntb2bothers'];
									$cntb2c		 += $data['countB2C'];
									$cnt		 += $data['countBook'];
									?>
									<tr>
										<td class="text-center"><?= $data['region'] ?></td>
										<td class="text-center"><?= $data['cntb2bmmt'] ?></td>
										<td class="text-center"><?= $data['cntb2bothers'] ?></td>
										<td class="text-center"><?= $data['countB2C'] ?></td>
										<td class="text-center"><?= $data['countBook'] ?></td>
									</tr>

									<?
								}
								?>
								<tr>
									<td colspan="1" class="text-center" style="border-top : 1px solid grey;font-style: italic;">Total Bookings Count</td>
									<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntmmt ?></td>
	                                <td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntb2bother ?></td>
	                                <td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntb2c ?></td>
									<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cnt ?></td>
								</tr>
							</tbody>
						</table>

						<table class="table table-bordered mt10">
							<thead>
								<tr style="color: blue;background: whitesmoke">
									<th colspan="4" class="text-center"><u>Today's Route-wise Count</u></th>
								</tr>
								<tr style="color: black;background: whitesmoke">
									<th class="text-center"><u>From</u></th>
									<th class="text-center"><u>To</u></th>
									<th class="text-center"><u>Count</u></th>
									<th class="text-center"><u>Amount</u></th>
								</tr>
							</thead>
							<tbody id="count_booking_row">                         
								<?
								$cnt	 = 0;
								$amount	 = 0;
								foreach ($model1 as $data)
								{
									$cnt	 += $data['count'];
									$amount	 += $data['amount'];
									?>
									<tr>
										<td class="text-center"><?= $data['fromc'] ?></td>
										<td class="text-center"><?= $data['toc'] ?></td>
										<td class="text-center"><?= $data['count'] ?></td>
										<td class="text-center"><?= $data['amount'] ?></td>
									</tr>

									<?
								}
								?>
								<tr><td colspan="2" class="text-center" style="border-top : 1px solid grey;font-style: italic;">Total Bookings Count and Amount</td><td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cnt ?></td><td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $amount ?></td></tr>
							</tbody>
						</table>
					</div>
					<div class="col-xs-12 col-sm-7">       
						<table class="table table-bordered">
							<thead>
								<tr style="color: blue;background: whitesmoke">
									<th colspan="8" class="text-center"><u>Today's Bookings</u></th>
								</tr>
								<tr style="color: black;background: whitesmoke">
									<th class="text-center"><u>Booking ID</u></th>
									<th class="text-center"><u>Booking Date/Time</u></th>
									<th class="text-center" style="min-width: 150px"><u>Routes</u></th>
									<th class="text-center"><u>Pickup Date/Time</u></th>
									<th class="text-center"><u>Amount</u></th>
									<th class="text-center"><u>Cab Type</u></th>
								</tr>
							</thead>
							<tbody id="booking_row">                         
								<?
								foreach ($model as $data)
								{
									?>
									<tr>
										<td class="text-center"><?= $data['bkg_booking_id'] ?></td>
										<td class="text-center"><?= date("d/m/Y H:i:s", strtotime($data['bkg_create_date'])) ?></td>
										<td class="text-center"><?= $data['routes'] ?></td>
										<td class="text-center"><?= date("d/m/Y H:i:s", strtotime($data['bkg_pickup_date'])) ?></td>
										<td class="text-center"><?= $data['bkg_total_amount'] ?></td>
										<td class="text-center"><?= $data['cab_type'] ?></td>
									</tr>

									<?
								}
								?>
							</tbody>
						</table>
						<div class="col-xs-12 well text-right">
							<?php
							$this->widget('CLinkPager', array('pages' => $usersList->pagination));
							?>
						</div>
					</div></div>
			<? } ?>
		</div></div></div>