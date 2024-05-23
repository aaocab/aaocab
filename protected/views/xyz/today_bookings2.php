<div class="panel panel-white"><div class="panel-body">

		<title>Today's Bookings</title>
		<?php
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
		<?php } ?>
		<?php
		if ($error == 2)
		{
			?>
			<div class="row m0 mt20" id="wrongPassword" style="">
				<div class="col-xs-offset-4 col-xs-4">
					<h3>Wrong Password</h3>
					<img src="http://static.commentcamarche.net/es.ccm.net/pictures/Ud6krzOUaQiVrbx4IWkuzUrMD8vWr4qbG1wMtmWKQ94r7Doi6fybXXnACJoLFtKR-lol.png">
				</div>
			</div>
		<?php } ?>
		<?php
		if ($error == 0)
		{
			?>
			<div style="font-size: 9px;">Last refresh at <?= $bookings['lastRefeshDate']; ?></div>
			<div class="row"> 
				<?php
				$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'booking-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => '',
					),
				));
				/* @var $form TbActiveForm */
				?>
				<div class="col-xs-12 col-sm-4 col-md-3">
					<?=
					$form->datePickerGroup($booksub, 'date', array('label'			 => 'Select Date',
						'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Select Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
					?>  
				</div>
				<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
					<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
				<?php $this->endWidget(); ?>
			</div>
			<table class="table table-bordered">
				<thead>
					<tr style="color: blue;background: whitesmoke">
						<th colspan="8" class="text-center"><u>TODAY'S COUNT BY BOOKING DATE</u></th>
					</tr>
					<tr style="color: black;background: whitesmoke">
						<th class="text-center"><u>BOOKING TYPE</u></th>
						<th class="text-center"><u>COUNT</u></th>
						<th class="text-center"><u>ADVANCE COUNT</u></th>
						<th class="text-center"><u>CANCELLED COUNT</u></th>
						<th class="text-center"><u>GOZO CANCELLED COUNT</u></th>
						<th class="text-center"><u>TOTAL AMOUNT</u></th>
						<th class="text-center"><u>GOZO AMOUNT</u></th>
						<th class="text-center"><u>GROSS MARGIN (%)</u></th>

					</tr>
				</thead>
				<tbody id="count_booking_row">
					<?php
					$bkgCount		 = 0;
					$canCount		 = 0;
					$gozocanCount	 = 0;
					$bkgAmount		 = 0;
					$gozoAmount		 = 0;
					$advcanCount	 = 0;
					foreach ($bkgmodel as $row)
					{
						$bkgCount		 += $row['ry_booking_count'];
						$canCount		 += $row['ry_cancelled_booking_count'];
						$gozocanCount	 += $row['ry_gozo_cancelled_booking_count'];
						$advcanCount	 += $row['ry_adv_booking_count'];
						$bkgAmount		 += $row['ry_booking_amount'];
						$gozoAmount		 += $row['ry_gozo_amount'];
						?>
						<tr>
							<td class=""><?= ($row['seq'] == '0000') ? $row['name'] : 'ALL' ?></td>
							<td class="text-center"><?= $row['ry_booking_count'] ?></td>
							<td class="text-center"><?= number_format($row['ry_adv_booking_count']) ?></td>
							<td class="text-center"><?= $row['ry_cancelled_booking_count'] ?></td>
							<td class="text-center"><?= $row['ry_gozo_cancelled_booking_count'] ?></td>
							<td class="text-center"><?= number_format($row['ry_booking_amount']) ?></td>
							<td class="text-center"><?= number_format($row['ry_gozo_amount']) ?></td>
							<td class="text-center"><?= round((($row['ry_gozo_amount'] / $row['ry_booking_amount']) * 100), 2) ?></td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td class=""><?= 'ALL'; ?></td>
						<td class="text-center"><?= $bkgCount ?></td>
						<td class="text-center"><?= number_format($advcanCount) ?></td>
						<td class="text-center"><?= $canCount ?></td>
						<td class="text-center"><?= $gozocanCount ?></td>
						<td class="text-center"><?= number_format($bkgAmount) ?></td>
						<td class="text-center"><?= number_format($gozoAmount) ?></td>
						<td class="text-center"><?= round((($gozoAmount / $bkgAmount) * 100), 2) ?></td>
					</tr>
				</tbody>
			</table>
			<div class="row" id="routewiseDiv" style="margin-top: 10px;">  
				<div class="col-xs-12 col-sm-6">       
					<table class="table table-bordered">
						<thead>
							<tr style="color: black;background: whitesmoke">
								<th class="text-center"><u>Total Booking</u></th>
								<th class="text-center"><u>MMT</u></th>
								<th class="text-center"><u>IBIBO</u></th>
								<th class="text-center"><u>B2B Other</u></th>
								<th class="text-center"><u>Gozo SHUTTLE</u></th>	
								<th class="text-center"><u>B2C</u></th>	
								<th class="text-center"><u>B2C Self</u></th>
								<th class="text-center"><u>B2C Team</u></th>	
								<th class="text-center"><u>B2C Unverified</u></th>
								<th class="text-center"><u>B2C Quotes Created</u></th>
								<th class="text-center"><u>B2C Quoted</u></th>
							</tr>
						</thead>
						<tbody id="count_booking_row">                         
							<tr>
								<td class="text-center"><?= $bookings['total_book']  ?></td>
								<td class="text-center"><?= $bookings['total_mmtb2b']."<br> ". round((($bookings['total_mmtb2b'] * 100) / $bookings['total_book']), 2) . "%" ?></td>
								<td class="text-center"><?= $bookings['total_ibibob2b']."<br> ". round((($bookings['total_ibibob2b'] * 100) / $bookings['total_book']), 2) . "%" ?></td>
								<td class="text-center"><?= $bookings['total_b2b']."<br> ". round((($bookings['total_b2b'] * 100) / $bookings['total_book']), 2) . "%" ?></td>
								<td class="text-center"><?= $bookings['total_gozoSuttleb2c']."<br> ". round((($bookings['total_gozoSuttleb2c'] * 100) / $bookings['total_book']), 2) . "%" ?></td>
								<td class="text-center"><?= $bookings['total_b2c']."<br> ". round((($bookings['total_b2c'] * 100) / $bookings['total_book']), 2) . "%" ?></td>
								<td class="text-center"><?= $bookings['total_b2c_user']."<br> ". round((($bookings['total_b2c_user'] * 100) / $bookings['total_b2c']), 2) . "%" ?></td>
								<td class="text-center"><?= $bookings['total_b2c_admin']."<br> ". round((($bookings['total_b2c_admin'] * 100) / $bookings['total_b2c']), 2) . "%" ?></td>
								<td class="text-center"><?= $bookings['total_b2c_unv'] ?></td>									
								<td class="text-center"><?= $bookings['total_b2c_quot_crt'] ?></td>
								<td class="text-center"><?= $bookings['total_b2c_quot'] ?></td>
							</tr>
						</tbody>
					</table>

					<table class="table table-bordered">
						<thead>
							<tr style="color: black;background: whitesmoke">
								<th class="text-center"><u>Auto-Assigned</u><?= "(" . round(((($bkgassigned[0]['total_auto_assigned_b2c'] + $bkgassigned[0]['total_auto_assigned_mmt'] + $bkgassigned[0]['total_auto_assigned_ibibo']) / ($bkgassigned[0]['total_assigned_b2c'] + $bkgassigned[0]['total_assigned_mmt'] + $bkgassigned[0]['total_assigned_ibibo'])) * 100), 1) . "%)" ?></th>
								<th class="text-center"><u>Manual-Assigned</u><?= "(" . round(((($bkgassigned[0]['total_manual_assigned_b2c'] + $bkgassigned[0]['total_manual_assigned_mmt'] + $bkgassigned[0]['total_manual_assigned_ibibo']) / ($bkgassigned[0]['total_assigned_b2c'] + $bkgassigned[0]['total_assigned_mmt'] + $bkgassigned[0]['total_assigned_ibibo'])) * 100), 1) . "%)" ?></th>
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
									<?= "B2B IBIBO: " . $bkgassigned[0]['total_auto_assigned_ibibo'] ?><br>	
									<?= "B2B OTHERS: " . $bkgassigned[0]['total_auto_assigned_b2bothers'] ?>
								</td>
								<td class="text-center"><?= $bkgassigned[0]['total_manual_assigned'] ?><br>
									<?= "P: {$bkgassigned[0]['manualAssignProfit']}={$bkgassigned[0]['manualAssignProfitCount']}" ?><br>
									<?= "L: {$bkgassigned[0]['manualAssignLoss']}={$bkgassigned[0]['manualAssignLossCount']}" ?><br>
									<?= "B2C: " . $bkgassigned[0]['total_manual_assigned_b2c'] ?><br>
									<?= "B2B MMT: " . $bkgassigned[0]['total_manual_assigned_mmt'] ?><br>
									<?= "B2B IBIBO: " . $bkgassigned[0]['total_manual_assigned_ibibo'] ?><br>	
									<?= "B2B OTHERS: " . $bkgassigned[0]['total_manual_assigned_b2bothers'] ?>
								</td>
								<td class="text-center"><?= $bkgassigned[0]['total_assigned'] ?><br>
									<?= "P: " . ($bkgassigned[0]['manualAssignProfit'] + $bkgassigned[0]['autoAssignProfit']) . "=" . ($bkgassigned[0]['manualAssignProfitCount'] + $bkgassigned[0]['autoAssignProfitCount']) . " (" . round((($bkgassigned[0]['totalProfit'] * 100) / $bkgassigned[0]['totalAmount']), 2) . "%)" ?><br>
									<?= "L: " . ($bkgassigned[0]['manualAssignLoss'] + $bkgassigned[0]['autoAssignLoss']) . "=" . ($bkgassigned[0]['manualAssignLossCount'] + $bkgassigned[0]['autoAssignLossCount']) . "" ?><br>
									<?= "B2C: " . $bkgassigned[0]['total_assigned_b2c'] ?><br>
									<?= "B2B MMT: " . $bkgassigned[0]['total_assigned_mmt'] ?><br>
									<?= "B2B IBIBO: " . $bkgassigned[0]['total_assigned_ibibo'] ?><br>
									<?= "B2B OTHERS: " . $bkgassigned[0]['total_assigned_b2bothers'] ?>										
								</td>

							</tr>
						</tbody>
					</table>

					<table class="table table-bordered mt10">
						<thead>
							<tr style="color: blue;background: whitesmoke">
								<th colspan="8" class="text-center"><u>Today's Region-wise Count</u></th>
							</tr>
							<tr style="color: black;background: whitesmoke">
								<th class="text-center"><u>Region</u></th>
								<th class="text-center"><u>B2B MMT</u></th>
								<th class="text-center"><u>B2B IBIBO</u></th>
								<th class="text-center"><u>B2B OTHERS</u></th>
								<th class="text-center"><u>B2C</u></th>
								<th class="text-center"><u>Total</u></th>
							</tr>
						</thead>
						<tbody id="count_booking_row">                         
							<?php
							$cntregionWise	 = 0;
							$cnt			 = 0;
							foreach ($regionWiseData as $data)
							{
								$cntregionWise += $data['countBook'];
							}
							foreach ($regionWiseData as $data)
							{
								$cntmmt		 += $data['cntb2bmmt'];
								$cntibibo	 += $data['cntb2bibibo'];
								$cntb2bother += $data['cntb2bothers'];
								$cntb2c		 += $data['countB2C'];
								$cnt		 += $data['countBook'];
								?>
								<tr>
									<td class="text-center"><?= $data['region'] ?></td>
									<td class="text-center"><?= $data['cntb2bmmt'] ?></td>
									<td class="text-center"><?= $data['cntb2bibibo'] ?></td>
									<td class="text-center"><?= $data['cntb2bothers'] ?></td>
									<td class="text-center"><?= $data['countB2C'] ?></td>
									<td class="text-center"><?= $data['countBook'] . " (" . round((($data['countBook'] * 100) / $cntregionWise), 2) . "%)" ?></td>
								</tr>

								<?php
							}
							?>
							<tr>
								<td colspan="1" class="text-center" style="border-top : 1px solid grey;font-style: italic;">Total Bookings Count</td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntmmt ?></td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntibibo ?></td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntb2bother ?></td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntb2c ?></td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cnt ?></td>
							</tr>
						</tbody>
					</table>


					<?php
					if (!empty($dataProvider4))
					{
						?>
						<div class="panel panel-primary" id="mbkg-grid1">
							<table class="table table-bordered mt10">
								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Today's Service Type-wise Count</u></th>
									</tr>
								</tbody></table>
							<div class="panel-body"><table class="table table-striped table-bordered mb0 table">
									<thead>
										<tr>
											<th class="col-xs-1" id="mbkg-grid1_c0">Service Type</th><th class="col-xs-1" id="mbkg-grid1_c1">B2B MMT</th><th class="col-xs-1" id="mbkg-grid1_c5">B2B IBIBO</th><th class="col-xs-1" id="mbkg-grid1_c2">B2B OTHERS</th><th class="col-xs-1" id="mbkg-grid1_c3">B2C</th><th class="col-xs-1" id="mbkg-grid1_c4">Count</th></tr>
									</thead>
									<tbody>
										<?php
										$i					 = 0;
										$serviceTypeCountAll = 0;
										$serviceTypeCount	 = 0;
										foreach ($dataProvider4 as $serviceType)
										{
											$serviceTypeCountAll = $serviceTypeCountAll + $serviceType['cntServiceType'];
										}
										foreach ($dataProvider4 as $serviceType)
										{
											?>
											<tr class="<?php if ($i == 0 ? 'even' : 'odd')  ?>">
												<td><?php echo $serviceType['serviceType'] ?></td>
												<td><?php echo $serviceType['total_mmtb2b'] ?></td>
												<td><?php echo $serviceType['total_ibibob2b'] ?></td>
												<td><?php echo $serviceType['total_b2bothers'] ?></td>
												<td><?php echo $serviceType['total_b2c'] ?></td>
												<td><?php echo $serviceType['cntServiceType'] . " (" . round((($serviceType['cntServiceType'] * 100) / $serviceTypeCountAll), 2) . "%)" ?></td>
											</tr>
											<?php
											$i++;
											$serviceTypeCount = $serviceTypeCount + $serviceType['cntServiceType'];
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0">Total Bookings Count: <?php echo $serviceTypeCount; ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span><span></span><span></span><span></span><span></span></div>
						</div>

					<?php }
					?> 
					<?php
					if (!empty($dataProvider5))
					{
						?>
						<div class="panel panel-primary" id="mbkg-grid2">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Today's Service Type-wise Profits</u></th>
									</tr>

								</tbody></table>
							<div class="panel-body"><table class="table table-striped table-bordered mb0 table">
									<thead>
										<tr>
											<th class="col-xs-1" id="mbkg-grid2_c0">Service Type</th>
											<th class="col-xs-1" id="mbkg-grid2_c1">B2B MMT(₹)</th>
											<th class="col-xs-1" id="mbkg-grid2_c5">B2B IBIBO(₹)</th>
											<th class="col-xs-1" id="mbkg-grid2_c2">B2B OTHERS(₹)</th>
											<th class="col-xs-1" id="mbkg-grid2_c3">B2C(₹)</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Total Profit(₹)</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i						 = 0;
										$serviceTypeProfit		 = 0;
										$serviceTypeProfitAll	 = 0;
										foreach ($dataProvider5 as $serviceProfit)
										{

											$serviceTypeProfitAll = $serviceTypeProfitAll + $serviceProfit['totalmargin'];
										}
										foreach ($dataProvider5 as $serviceProfit)
										{
											?>
											<tr class="<?php if ($i == 0 ? 'even' : 'odd')  ?>">
												<td><?php echo $serviceProfit['serviceType'] ?></td>
												<td><?php echo $serviceProfit['total_mmtb2b'] ?></td>
												<td><?php echo $serviceProfit['total_ibibob2b'] ?></td>
												<td><?php echo $serviceProfit['total_b2bothers'] ?></td>
												<td><?php echo $serviceProfit['total_b2c'] ?></td>
												<td><?php echo $serviceProfit['totalmargin'] . " (" . round((($serviceProfit['totalmargin'] * 100) / $serviceTypeProfitAll), 2) . "%)" ?></td>
											</tr>
											<?php
											$i++;
											$serviceTypeProfit = $serviceTypeProfit + $serviceProfit['totalmargin'];
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0">Total Profit(₹):<?php echo $serviceTypeProfit; ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span><span></span><span></span><span></span><span></span></div>
						</div>
					<?php } ?> 	

					</br></br>
					<a href="<?php echo Yii::app()->createUrl('admpnl/report/zonewise-count'); ?>" target="blank">>> Today's Zone-wise Count Report</a>
					</br></br>
					<a href="<?php echo Yii::app()->createUrl('admpnl/generalReport/partnerWiseCountBooking'); ?>" target="blank">>> Partner Booking Report( B2B Other )</a>


				</div>

				<div class="col-xs-12 col-sm-6">  
					<?php
					if (!empty($dataProvider3))
					{
						?>
						<div class="panel panel-primary" id="mbkg-grid">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Today's Service Tier-wise Count</u></th>
									</tr>

								</tbody></table>
							<div class="panel-body"><table class="table table-striped table-bordered mb0 table">
									<thead>
										<tr>
											<th class="col-xs-1" id="mbkg-grid_c0">Service Tier</th>
											<th class="col-xs-1" id="mbkg-grid_c1">Count</th>
											<th class="col-xs-1" id="mbkg-grid_c2">Total Amount</th>
											<th class="col-xs-1" id="mbkg-grid_c3">Gozo Amount</th>
											<th class="col-xs-1" id="mbkg-grid_c4">Gross Margin (%)</th>
										</tr>

									</thead>
									<tbody>
										<?php
										$i			 = 0;
										$tierCount	 = 0;
										foreach ($dataProvider3 as $tier)
										{
											?>
											<tr class="<?php if ($i == 0 ? 'even' : 'odd')  ?>">
												<td><?php echo $tier['tierName'] ?></td>
												<td><?php echo $tier['cntServiceTier'] ?></td>
												<td><?php echo $tier['booking_amount'] ?></td>
												<td><?php echo $tier['gozo_amount'] ?></td>
												<td><?= round((($tier['gozo_amount'] / $tier['booking_amount']) * 100), 2) . " (₹" . (round((($tier['gozo_amount'] / $tier['cntServiceTier'])), 2)) . ")" ?></td>

											</tr>
											<?php
											$i++;
											$tierCount = $tierCount + $tier['cntServiceTier'];
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> result.</div></div><div class="col-xs-12 col-sm-6 pr0">Total Bookings Count: <?php echo $tierCount; ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span></div>
						</div>
						<?php
					}
					?> 
					<?php
					if (!empty($dataProvider2))
					{
						?>
						<div class="panel panel-primary" id="mbkg-grid">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Today's Car Category-wise Count</u></th>
									</tr>

								</tbody></table>
							<div class="panel-body"><table class="table table-striped table-bordered mb0 table">
									<thead>
										<tr>
											<th class="col-xs-1" id="mbkg-grid_c01">Service Tier</th>
											<th class="col-xs-1" id="mbkg-grid_c0">Car Category</th>
											<th class="col-xs-1" id="mbkg-grid_c1">B2B MMT</th>
											<th class="col-xs-1" id="mbkg-grid_c1">B2B IBIBO</th>
											<th class="col-xs-1" id="mbkg-grid_c2">B2B OTHERS</th>
											<th class="col-xs-1" id="mbkg-grid_c3">B2C</th>
											<th class="col-xs-1" id="mbkg-grid_c4">Count</th>
										</tr>
									</thead>
									<tbody>

										<?php
										$i			 = 0;
										$carCount	 = 0;
										foreach ($dataProvider2 as $car)
										{
											?>
											<tr class="<?php if ($i == 0 ? 'even' : 'odd')  ?>">
												<td><?php echo $car['tierName'] ?></td>
												<td><?php echo $car['catName'] ?></td>
												<td><?php echo $car['total_mmtb2b'] ?></td>
												<td><?php echo $car['total_ibibob2b'] ?></td>
												<td><?php echo $car['total_b2bothers'] ?></td>
												<td><?php echo $car['total_b2c'] ?></td>
												<td><?php echo $car['cntCarCat'] ?></td>
											</tr>
											<?php
											$i++;
											$carCount = $carCount + $car['cntCarCat'];
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0">Total Bookings Count: <?php echo $carCount; ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span><span></span><span></span><span></span></div>
						</div>
					<?php }
					?> 

					<?php
					if (!empty($dataProvider7))
					{
						?>
						<div class="panel panel-primary" id="mbkg-grid2">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Today's Cancellations</u></th>
									</tr>

								</tbody></table>
							<div class="panel-body"><table class="table table-striped table-bordered mb0 table">
									<thead>
										<tr>
											<th class="col-xs-1" id="mbkg-grid2_c0">Service Type</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled by Gozo</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled(not by Gozo)</th>												
										</tr>
									</thead>
									<tbody>
										<?php
										$i					 = 0;
										$serviceCancelType	 = 0;
										foreach ($dataProvider7 as $serviceCancel)
										{
											?>
											<tr class="<?php if ($i == 0 ? 'even' : 'odd')  ?>">
												<td><?php echo $serviceCancel['tierName'] ?></td>
												<td><?php echo $serviceCancel['cancelled'] ?></td>
												<td><?php echo $serviceCancel['cancelledbygozo'] ?></td>
												<td><?php echo ($serviceCancel['notcancelledbygozo']) ?></td>													
											</tr>
											<?php
											$i++;
											$serviceCancelType = $serviceCancelType + $serviceCancel['cancelled'];
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0">Today's Cancellations:<?php echo $serviceCancelType; ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span><span></span><span></span><span></span><span></span></div>
						</div>
					<?php }
					?> 

					<?php
					if (!empty($dataProvider6))
					{
						?>
						<div class="panel panel-primary" id="mbkg-grid2">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Today's Scheduled Pickups</u></th>
									</tr>

								</tbody></table>
							<div class="panel-body"><table class="table table-striped table-bordered mb0 table">
									<thead>
										<tr>
											<th class="col-xs-1" id="mbkg-grid2_c0">Service Type</th>
											<th class="col-xs-1" id="mbkg-grid2_c1">New</th>
											<th class="col-xs-1" id="mbkg-grid2_c5">Assigned</th>
											<th class="col-xs-1" id="mbkg-grid2_c2">On the  Way</th>
											<th class="col-xs-1" id="mbkg-grid2_c3">Completed</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled by Gozo</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled(not by Gozo)</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Count</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i			 = 0;
										$serviceType = 0;
										foreach ($dataProvider6 as $service)
										{
											?>
											<tr class="<?php if ($i == 0 ? 'even' : 'odd')  ?>">
												<td><?php echo $service['tierName'] ?></td>
												<td><?php echo $service['new'] ?></td>
												<td><?php echo $service['assigned'] ?></td>
												<td><?php echo $service['ontheway'] ?></td>
												<td><?php echo $service['completed'] ?></td>
												<td><?php echo $service['cancelled'] ?></td>
												<td><?php echo $service['cancelledbygozo'] ?></td>
												<td><?php echo ($service['notcancelledbygozo']) ?></td>
												<td><?php echo $service['cnt'] ?></td>
											</tr>
											<?php
											$i++;
											$serviceType = $serviceType + $service['cnt'];
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0">Today's Scheduled Pickups:<?php echo $serviceType; ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span><span></span><span></span><span></span><span></span></div>
						</div>
					<?php } ?> 

				</div>

			<?php } ?>
		</div></div></div>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>