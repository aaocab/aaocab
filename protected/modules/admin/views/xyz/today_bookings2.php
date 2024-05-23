<div class="panel panel-white"><div class="panel-body">

        <title>Day's Bookings</title>
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
				$form						 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
				<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-4 col-md-2 text-center mt20 p5">   
					<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
				<?php $this->endWidget(); ?>
			</div>
			<div class="row">
				<div class="col-xs-12" style="max-width: 100%; overflow: auto">
					<table class="table table-bordered responsive">
						<thead>
							<tr style="color: blue;background: whitesmoke">
								<th colspan="9" class="text-center"><u>DAY'S CREATED BOOKINGS</br>[LOCAL/OUTSTATION]</u></th>
							</tr>
							<tr style="color: black;background: whitesmoke">
								<th class="text-center"><u>CHANNEL TYPE</u></th>
								<th class="text-center"><u>#</u></th>
								<th class="text-center"><u>ADVANCE #</u></th>
								<th class="text-center"><u>CXL #</u></th>
								<th class="text-center"><u>GOZO CXL #</u></th>
								<th class="text-center"><u>NET BASE ₹ </br>[Post Discounts]</u></th>
								<th class="text-center"><u>GOZO ₹ </br>[Post Comm] </u></th>
								<th class="text-center"><u>ASSIGNED MARGIN (%) </u></th>
								<th class="text-center"><u>QUOTED MARGIN (%) </u></th>
							</tr>
						</thead>
						<tbody id="count_booking_row">
							<?php
							$bkgCount					 = 0;
							$bkgCountLocal				 = 0;
							$bkgCountOutStation			 = 0;
							$canCount					 = 0;
							$canCountLocal				 = 0;
							$canCountOutStation			 = 0;
							$gozocanCount				 = 0;
							$gozocanCountLocal			 = 0;
							$gozocanCountOutStation		 = 0;
							$bkgAmount					 = 0;
							$bkgAmountLocal				 = 0;
							$bkgAmountOutStation		 = 0;
							$gozoAmount					 = 0;
							$gozoAmountLocal			 = 0;
							$gozoAmountOutStation		 = 0;
							$advcanCount				 = 0;
							$advcanCountLocal			 = 0;
							$advcanCountOutStation		 = 0;
							$quoteVendorAmount			 = 0;
							$quoteVendorAmountLocal		 = 0;
							$quoteVendorAmountOutStation = 0;
							foreach ($bkgmodel as $row)
							{
								$bkgCount					 += $row['ry_booking_count'];
								$bkgCountLocal				 += $row['ry_booking_count_local'];
								$bkgCountOutStation			 += $row['ry_booking_count_outstation'];
								$canCount					 += $row['ry_cancelled_booking_count'];
								$canCountLocal				 += $row['ry_cancelled_booking_count_local'];
								$canCountOutStation			 += $row['ry_cancelled_booking_count_outstation'];
								$gozocanCount				 += $row['ry_gozo_cancelled_booking_count'];
								$gozocanCountLocal			 += $row['ry_gozo_cancelled_booking_count_local'];
								$gozocanCountOutStation		 += $row['ry_gozo_cancelled_booking_count_outstation'];
								$advcanCount				 += $row['ry_adv_booking_count'];
								$advcanCountLocal			 += $row['ry_adv_booking_count_local'];
								$advcanCountOutStation		 += $row['ry_adv_booking_count_outstation'];
								$bkgAmount					 += $row['ry_booking_amount'];
								$bkgAmountLocal				 += $row['ry_booking_amount_local'];
								$bkgAmountOutStation		 += $row['ry_booking_amount_outstation'];
								$gozoAmount					 += $row['ry_gozo_amount'];
								$gozoAmountLocal			 += $row['ry_gozo_amount_local'];
								$gozoAmountOutStation		 += $row['ry_gozo_amount_outstation'];
								$quoteVendorAmount			 += $row['ry_quote_vendor_amount'];
								$quoteVendorAmountLocal		 += $row['ry_quote_vendor_amount_local'];
								$quoteVendorAmountOutStation += $row['ry_quote_vendor_amount_outstation'];
								?>
								<tr>
									<td class=""><?= ($row['seq'] == '0000') ? $row['name'] : 'ALL' ?></td>
									<td class="text-center"><?= $row['ry_booking_count'] . "(" . $row['ry_booking_count_local'] . "/" . $row['ry_booking_count_outstation'] . ")" ?></td>
									<td class="text-center"><?= number_format($row['ry_adv_booking_count']) . "(" . number_format($row['ry_adv_booking_count_local']) . "/" . number_format($row['ry_adv_booking_count_outstation']) . ")" ?></td>
									<td class="text-center"><?= $row['ry_cancelled_booking_count'] . "(" . $row['ry_cancelled_booking_count_local'] . "/" . $row['ry_cancelled_booking_count_outstation'] . ")" ?></td>
									<td class="text-center"><?= $row['ry_gozo_cancelled_booking_count'] . "(" . $row['ry_gozo_cancelled_booking_count_local'] . "/" . $row['ry_gozo_cancelled_booking_count_outstation'] . ")" ?></td>
									<td class="text-center"><?= number_format($row['ry_booking_amount']) . "(" . number_format($row['ry_booking_amount_local']) . "/" . number_format($row['ry_booking_amount_outstation']) . ")" ?></td>
									<td class="text-center"><?= number_format($row['ry_gozo_amount']) . "(" . number_format($row['ry_gozo_amount_local']) . "/" . number_format($row['ry_gozo_amount_outstation']) . ")" ?></td>
									<td class="text-center"><?= round((($row['ry_gozo_amount'] / $row['ry_booking_amount']) * 100), 2) . "(" . round((($row['ry_gozo_amount_local'] / $row['ry_booking_amount_local']) * 100), 2) . "/" . round((($row['ry_gozo_amount_outstation'] / $row['ry_booking_amount_outstation']) * 100), 2) . ")" ?></td>
									<td class="text-center"><?= round((($row['ry_quote_vendor_amount'] / $row['ry_booking_amount']) * 100), 2) . "(" . round((($row['ry_quote_vendor_amount_local'] / $row['ry_booking_amount_local']) * 100), 2) . "/" . round((($row['ry_quote_vendor_amount_outstation'] / $row['ry_booking_amount_outstation']) * 100), 2) . ")" ?></td>
								</tr>
								<?php
							}
							?>
							<tr>
								<td class=""><?= 'ALL'; ?></td>
								<td class="text-center"><?= $bkgCount . "(" . $bkgCountLocal . "/" . $bkgCountOutStation . ")" ?></td>
								<td class="text-center"><?= number_format($advcanCount) . "(" . number_format($advcanCountLocal) . "/" . number_format($advcanCountOutStation) . ")" ?></td>
								<td class="text-center"><?= $canCount . "(" . $canCountLocal . "/" . $canCountOutStation . ")" ?></td>
								<td class="text-center"><?= $gozocanCount . "(" . $gozocanCountLocal . "/" . $gozocanCountOutStation . ")" ?></td>
								<td class="text-center"><?= number_format($bkgAmount) . "(" . number_format($bkgAmountLocal) . "/" . number_format($bkgAmountOutStation) . ")" ?></td>
								<td class="text-center"><?= number_format($gozoAmount) . "(" . number_format($gozoAmountLocal) . "/" . number_format($gozoAmountOutStation) . ")" ?></td>
								<td class="text-center"><?= round((($gozoAmount / $bkgAmount) * 100), 2) . "(" . round((($gozoAmountLocal / $bkgAmountLocal) * 100), 2) . "/" . round((($gozoAmountLocal / $bkgAmountLocal) * 100), 2) . ")" ?></td>
								<td class="text-center"><?= round((($quoteVendorAmount / $bkgAmount) * 100), 2) . "(" . round((($quoteVendorAmountLocal / $bkgAmountLocal) * 100), 2) . "/" . round((($quoteVendorAmountOutStation / $bkgAmountOutStation) * 100), 2) . ")" ?></td>
							</tr>
						</tbody>
					</table>
				</div></div>

			<div class="row">
				<div class="col-xs-12" style="max-width: 100%; overflow: auto">
					<table class="table table-bordered responsive">
						<thead>
							<tr style="color: blue;background: whitesmoke">
								<th colspan="12" class="text-center"><u>MONTH WISE DAILY METRIC</br>[BY PICKUP DATE AND  STATUS (6,7)]</br>[LOCAL/OUTSTATION]</u></th>
							</tr>
							<tr style="color: black;background: whitesmoke">
								<th class="text-center"><u>MONTH</u></th>
								<th class="text-center"><u>B2C_PER_DAY_CNT</u></th>
								<th class="text-center"><u>B2C_GN_PER_DAY_CNT</u></th>
								<th class="text-center"><u>B2C_NON_GN_PER_DAY_CNT</u></th>
								<th class="text-center"><u>MMT_PER_DAY_CNT</u></th>
								<th class="text-center"><u>OTHER_PARTNER_PER_DAY_CNT </u></th>
								<th class="text-center"><u>B2C_TOTAL</u></th>
								<th class="text-center"><u>B2C_GN_TOTAL</u></th>
								<th class="text-center"><u>B2C_NON_GN_TOTAL</u></th>
								<th class="text-center"><u>MMT_TOTAL</u></th>
								<th class="text-center"><u>OTHER_PARTNER_TOTAL </u></th>
							</tr>
						</thead>
						<tbody id="count_booking_row_month">
							<?php
							foreach ($monthWiseDaily as $row)
							{
								?>
								<tr>
									<td class="text-center"><?= $row['MONTH'] ?></td>
									<td class="text-center"><?= $row['B2C_PER_DAY_CNT'] . "(" . $row['B2C_PER_DAY_CNT_LOCAL'] . "/" . $row['B2C_PER_DAY_CNT_OUTSTATION'] . ")" ?></td>
									<td class="text-center"><?= $row['B2C_GN_PER_DAY_CNT'] . "(" . $row['B2C_GN_PER_DAY_CNT_LOCAL'] . "/" . $row['B2C_GN_PER_DAY_CNT_OUTSTATION'] . ")" ?></td>
									<td class="text-center"><?= $row['B2C_NON_GN_PER_DAY_CNT'] . "(" . $row['B2C_NON_GN_PER_DAY_CNT_LOCAL'] . "/" . $row['B2C_NON_GN_PER_DAY_CNT_OUTSTATION'] . ")" ?></td>
									<td class="text-center"><?= $row['MMT_PER_DAY_CNT'] . "(" . $row['MMT_PER_DAY_CNT_LOCAL'] . "/" . $row['MMT_PER_DAY_CNT_OUTSTATION'] . ")" ?></td>
									<td class="text-center"><?= $row['OTHER_PARTNER_PER_DAY_CNT'] . "(" . $row['OTHER_PARTNER_PER_DAY_CNT_LOCAL'] . "/" . $row['OTHER_PARTNER_PER_DAY_CNT_OUTSTATION'] . ")" ?></td>
									<td class="text-center"><?= $row['B2C_TOTAL'] . "(" . $row['B2C_TOTAL_LOCAL'] . "/" . $row['B2C_TOTAL_OUTSTATION'] . ")" ?></td>
									<td class="text-center"><?= $row['B2C_GN_TOTAL'] . "(" . $row['B2C_GN_TOTAL_LOCAL'] . "/" . $row['B2C_GN_TOTAL_OUTSTATION'] . ")" ?></td>
									<td class="text-center"><?= $row['B2C_NON_GN_TOTAL'] . "(" . $row['B2C_NON_GN_TOTAL_LOCAL'] . "/" . $row['B2C_NON_GN_TOTAL_OUTSTATION'] . ")" ?></td>
									<td class="text-center"><?= $row['MMT_TOTAL'] . "(" . $row['MMT_TOTAL_LOCAL'] . "/" . $row['MMT_TOTAL_OUTSTATION'] . ")" ?></td>
									<td class="text-center"><?= $row['OTHER_PARTNER_TOTAL'] . "(" . $row['OTHER_PARTNER_TOTAL_LOCAL'] . "/" . $row['OTHER_PARTNER_TOTAL_OUTSTATION'] . ")" ?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row" id="routewiseDiv" style="margin-top: 10px;">  
				<div class="col-xs-12 col-md-6" style="max-width: 100%; overflow: auto">       
					<table class="table table-bordered">
						<thead>
							<tr style="color: black;background: whitesmoke">
								<th class="text-center"><u>Date</u></th>
								<th class="text-center"><u>Total Booking </u></th>
								<th class="text-center"><u>IBIBO </u></th>
								<th class="text-center"><u>B2B Other </u></th>
								<th class="text-center"><u>B2C </u></th>	
								<th class="text-center"><u>B2C Self | +Assist | +GN (*in progress)</u></th>
								<th class="text-center"><u>B2C Concierge | +Cash | +GN </u></th>	
								<th class="text-center"><u>B2C Unverified</u></th>
								<th class="text-center"><u>B2C Quotes Created</u></th>
								<th class="text-center"><u>B2C Quoted</u></th>
							</tr>
						</thead>
						<tbody id="count_booking_row"> 
							<?php
							$i = 0;
							foreach ($bookingWiseArr as $bookingsArr)
							{
								?>
								<tr <?php echo $i == 0 ? 'style="color: black;background: whitesmoke"' : "" ?>>
									<td class="text-center"><?= $bookingsArr['lastRefeshDate'] ?></td>
									<td class="text-center"><?= $bookingsArr['total_book']."(".$bookingsArr['total_book_local']."/".$bookingsArr['total_book_outstation'].")" ?></td>
									<td class="text-center"><?= $bookingsArr['total_ibibob2b']."(".$bookingsArr['total_ibibob2b_local']."/".$bookingsArr['total_ibibob2b_outstation'].")" . "<br> " . round((($bookingsArr['total_ibibob2b'] * 100) / $bookingsArr['total_book']), 2) . "%" ?></td>
									<td class="text-center"><?= $bookingsArr['total_b2b']."(".$bookingsArr['total_b2b_local']."/".$bookingsArr['total_b2b_outstation'].")"  . "<br> " . round((($bookingsArr['total_b2b'] * 100) / $bookingsArr['total_book']), 2) . "%" ?></td>
									<td class="text-center"><?= $bookingsArr['total_b2c']."(".$bookingsArr['total_b2c_local']."/".$bookingsArr['total_b2c_outstation'].")"  . "<br> " . round((($bookingsArr['total_b2c'] * 100) / $bookingsArr['total_book']), 2) . "%" ?></td>
									<td class="text-center"><?= $bookingsArr['total_b2c_user'] . "|" . $bookingsArr['total_b2c_other'] . "|" . $bookingsArr['total_b2c_gn'] . "<br> " . round((($bookingsArr['total_b2c_user'] * 100) / $bookingsArr['total_b2c']), 2) . "%" . " | " . round((($bookingsArr['total_b2c_other'] * 100) / $bookingsArr['total_b2c']), 2) . "%" . " | " . round((($bookingsArr['total_b2c_gn'] * 100) / $bookingsArr['total_b2c']), 2) . "%" ?></td>
									<td class="text-center"><?= $bookingsArr['total_b2c_adminAssisted'] . "|" . $bookingsArr['total_b2c_admin'] . "|" . $bookingsArr['total_b2c_admin_gn'] . "<br> " . round((($bookingsArr['total_b2c_adminAssisted'] * 100) / $bookingsArr['total_b2c']), 2) . "%" . " | " . round((($bookingsArr['total_b2c_admin'] * 100) / $bookingsArr['total_b2c']), 2) . "%" . " | " . round((($bookingsArr['total_b2c_admin_gn'] * 100) / $bookingsArr['total_b2c']), 2) . "%" ?></td>
									<td class="text-center"><?= $bookingsArr['total_b2c_unv'] ?></td>									
									<td class="text-center"><?= $bookingsArr['total_b2c_quot_crt'] ?></td>
									<td class="text-center"><?= $bookingsArr['total_b2c_quot'] ?></td>
								</tr>
								<?php
								$i++;
							}
							?>
						</tbody>
					</table>
				</div>
				<div class="col-xs-12 col-md-6" style="max-width: 100%; overflow: auto">
					<table class="table table-bordered">
						<thead>
							<tr style="color: black;background: whitesmoke">

								<th class="text-center">
									<u>Auto assigned</u><br>
									all: <span style="white-space: nowrap;"><?= " {$bkgassigned[0]['total_auto_assigned']} [" . round(((($bkgassigned[0]['total_auto_assigned'] ) / ( $bkgassigned[0]['total_assigned'])) * 100), 1) . "%]" ?></span><br>
									excl AT: <span style="white-space: nowrap;"><?= " {$bkgassigned[0]['total_auto_assigned_non_AT']} [" . round(((($bkgassigned[0]['total_auto_assigned_non_AT'] ) / ( $bkgassigned[0]['total_assigned_non_AT'])) * 100), 1) . "%]" ?></span>
									<span style="white-space: nowrap;"><?= "MaxOut trigger: " . $bkgassigned[0]['manual_triggered_auto_assigned'] . " [" . round((($bkgassigned[0]['manual_triggered_auto_assigned'] * 100) / $bkgassigned[0]['total_auto_assigned']), 2) . "%]" ?></span><br>
									<span style="white-space: nowrap;"><?= "Non trigger: " . $bkgassigned[0]['non_triggered_auto_assigned'] . " [" . round((($bkgassigned[0]['non_triggered_auto_assigned'] * 100) / $bkgassigned[0]['total_auto_assigned']), 2) . "%]" ?></span><br><br>
									<span style="white-space: nowrap;"><?= "GN attempted: " . $bkgassigned[0]['gn_auto_assigned'] . " [" . round((($bkgassigned[0]['gn_auto_assigned'] * 100) / $bkgassigned[0]['total_auto_assigned']), 2) . "%]" ?></span><br>								
								</th>
								<th class="text-center">
									<u>Manual assigned</u><br>
									all: <span style="white-space: nowrap;"><?= " {$bkgassigned[0]['total_manual_assigned']} [" . round(((($bkgassigned[0]['total_manual_assigned']) / ($bkgassigned[0]['total_assigned'])) * 100), 1) . "%]"; ?></span><br>
									excl AT: <span style="white-space: nowrap;"><?= " {$bkgassigned[0]['total_manual_assigned_non_AT']} [" . round(((($bkgassigned[0]['total_manual_assigned_non_AT']) / ($bkgassigned[0]['total_assigned_non_AT'])) * 100), 1) . "%]"; ?></span><br>
									<span style="white-space: nowrap;"><?= "MaxOut trigger: " . $bkgassigned[0]['manual_triggered_assignment'] . " [" . round((($bkgassigned[0]['manual_triggered_assignment'] * 100) / $bkgassigned[0]['total_manual_assigned']), 2) . "%]" ?></span><br>
									<span style="white-space: nowrap;"><?= "Non trigger: " . $bkgassigned[0]['manual_triggered_assignment_smt_not_used'] . " [" . round((($bkgassigned[0]['manual_triggered_assignment_smt_not_used'] * 100) / $bkgassigned[0]['total_manual_assigned']), 2) . "%]" ?></span><br><br>
									<span style="white-space: nowrap;"><?= "GN attempted: " . $bkgassigned[0]['gn_manual_assigned'] . " [" . round((($bkgassigned[0]['gn_manual_assigned'] * 100) / $bkgassigned[0]['total_manual_assigned']), 2) . "%]" ?></span><br>

								</th>
								<th class="text-center">
									<u>Direct accept</u><br>
									all: <span style="white-space: nowrap;"><?= " {$bkgassigned[0]['total_direct_assigned']} [" . round(((($bkgassigned[0]['total_direct_assigned']) / ( $bkgassigned[0]['total_assigned'])) * 100), 1) . "%]" ?></span><br>
									excl AT: <span style="white-space: nowrap;"><?= " {$bkgassigned[0]['total_direct_assigned_non_AT']} [" . round(((($bkgassigned[0]['total_direct_assigned_non_AT'] ) / ( $bkgassigned[0]['total_assigned_non_AT'])) * 100), 1) . "%]" ?></span>
									<br>
									<br>
									<br>
									<br>
								</th>


								<th class="text-center">
									<u>GozoNow accept</u><br>
									all: <span style="white-space: nowrap;"><?= " {$bkgassigned[0]['total_gozoNow_assigned']} [" . round(((($bkgassigned[0]['total_gozoNow_assigned']) / ( $bkgassigned[0]['total_assigned'])) * 100), 1) . "%]" ?></span><br>
									excl AT: <span style="white-space: nowrap;"><?= " {$bkgassigned[0]['total_gozoNow_assigned_non_AT']} [" . round(((($bkgassigned[0]['total_gozoNow_assigned_non_AT'] ) / ( $bkgassigned[0]['total_gozoNow_assigned_non_AT'])) * 100), 1) . "%]" ?></span>
									<br>
									<br>
									<br>
									<br>
								</th>
								<th class="text-center">
									<u>Total assigned</u><br>
									all: <?= " {$bkgassigned[0]['total_assigned']} " ?><br>
									excl AT: <?= " {$bkgassigned[0]['total_assigned_non_AT']} " ?>
									<br>
									<br>
									<br>
									<br>
								</th>									

							</tr>
						</thead>
						<tbody id="count_booking_row">                         
							<tr>
								<td class="text-center">
									<?= $bkgassigned[0]['total_auto_assigned'] ?><br>
									<?= "P = {$bkgassigned[0]['autoAssignProfit']} / {$bkgassigned[0]['autoAssignProfitCount']}" ?><br>
									<?= "L = {$bkgassigned[0]['autoAssignLoss']} / {$bkgassigned[0]['autoAssignLossCount']} = ₹" . round(($bkgassigned[0]['autoAssignLoss'] / $bkgassigned[0]['autoAssignLossCount']), 1) . "pu" ?><br><br>
									<?= "B2C[P:L] = " . $bkgassigned[0]['total_auto_assigned_b2c'] . "[" . $bkgassigned[0]['autoAssignB2CProfitCount'] . " : " . $bkgassigned[0]['autoAssignB2CLossCount'] . "]"; ?><br>
									<?= "B2B MMT[P:L] = " . $bkgassigned[0]['total_auto_assigned_mmt'] . "[" . $bkgassigned[0]['autoAssignB2BMMTProfitCount'] . " : " . $bkgassigned[0]['autoAssignB2BMMTLossCount'] . "]"; ?><br>	
									<?= "B2B IBIBO[P:L] = " . $bkgassigned[0]['total_auto_assigned_ibibo'] . "[" . $bkgassigned[0]['autoAssignB2BIBIBOProfitCount'] . " : " . $bkgassigned[0]['autoAssignB2BIBIBOLossCount'] . "]"; ?><br>	
									<?= "B2B OTHERS[P:L] = " . $bkgassigned[0]['total_auto_assigned_b2bothers'] . "[" . $bkgassigned[0]['autoAssignB2BOTHERSProfitCount'] . " : " . $bkgassigned[0]['autoAssignB2BOTHERSLossCount'] . "]"; ?>
								</td>
								<td class="text-center">
									<?= $bkgassigned[0]['total_manual_assigned'] ?><br>
									<?= "P = {$bkgassigned[0]['manualAssignProfit']} / {$bkgassigned[0]['manualAssignProfitCount']}" ?><br>
									<?= "L = {$bkgassigned[0]['manualAssignLoss']} / {$bkgassigned[0]['manualAssignLossCount']} = ₹" . round(($bkgassigned[0]['manualAssignLoss'] / $bkgassigned[0]['manualAssignLossCount']), 1) . "pu" ?><br><br>
									<?= "B2C[P:L] = " . $bkgassigned[0]['total_manual_assigned_b2c'] . "[" . $bkgassigned[0]['manualB2CProfitCount'] . " : " . $bkgassigned[0]['manualB2CLossCount'] . "]"; ?><br>
									<?= "B2B MMT[P:L] = " . $bkgassigned[0]['total_manual_assigned_mmt'] . "[" . $bkgassigned[0]['manualB2BMMTProfitCount'] . " : " . $bkgassigned[0]['manualB2BMMTLossCount'] . "]"; ?><br>
									<?= "B2B IBIBO[P:L] = " . $bkgassigned[0]['total_manual_assigned_ibibo'] . "[" . $bkgassigned[0]['manualB2BIBIBOProfitCount'] . " : " . $bkgassigned[0]['manualB2BIBIBOLossCount'] . "]"; ?><br>	
									<?= "B2B OTHERS[P:L] = " . $bkgassigned[0]['total_manual_assigned_b2bothers'] . "[" . $bkgassigned[0]['manualB2BOTHERSProfitCount'] . " : " . $bkgassigned[0]['manualB2BOTHERSLossCount'] . "]"; ?>
								</td>
								<td class="text-center">
									<?= $bkgassigned[0]['total_direct_assigned'] ?><br>
									<?= "P = {$bkgassigned[0]['directAssignProfit']} / {$bkgassigned[0]['directAssignProfitCount']}" ?><br>
									<?= "L = {$bkgassigned[0]['directAssignLoss']} / {$bkgassigned[0]['directAssignLossCount']} = ₹" . round(($bkgassigned[0]['directAssignLoss'] / $bkgassigned[0]['directAssignLossCount']), 1) . "pu" ?><br><br>
									<?= "B2C[P:L] = " . $bkgassigned[0]['total_direct_accept_b2c'] . "[" . $bkgassigned[0]['directB2CProfitCount'] . " : " . $bkgassigned[0]['directB2CLossCount'] . "]"; ?><br>
									<?= "B2B MMT[P:L] = " . $bkgassigned[0]['total_direct_accept_mmt'] . "[" . $bkgassigned[0]['directB2BMMTProfitCount'] . " : " . $bkgassigned[0]['directB2BMMTLossCount'] . "]"; ?><br>
									<?= "B2B IBIBO[P:L] = " . $bkgassigned[0]['total_direct_accept_ibibo'] . "[" . $bkgassigned[0]['directB2BIBIBOProfitCount'] . " : " . $bkgassigned[0]['directB2BIBIBOLossCount'] . "]"; ?><br>	
									<?= "B2B OTHERS[P:L] = " . $bkgassigned[0]['total_direct_accept_b2bothers'] . "[" . $bkgassigned[0]['directB2BOTHERSProfitCount'] . " : " . $bkgassigned[0]['directB2BOTHERSLossCount'] . "]"; ?>
								</td>


								<td class="text-center">
									<?= $bkgassigned[0]['total_gozoNow_assigned'] ?><br>
									<?= "P = {$bkgassigned[0]['gozoNowAssignProfit']} / {$bkgassigned[0]['gozoNowAssignProfitCount']}" ?><br>
									<?= "L = {$bkgassigned[0]['gozoNowAssignLoss']} / {$bkgassigned[0]['gozoNowAssignLossCount']} = ₹" . round(($bkgassigned[0]['gozoNowAssignLoss'] / $bkgassigned[0]['gozoNowAssignLossCount']), 1) . "pu" ?><br><br>
									<?= "B2C[P:L] = " . $bkgassigned[0]['total_gozoNow_accept_b2c'] . "[" . $bkgassigned[0]['gozoNowB2CProfitCount'] . " : " . $bkgassigned[0]['gozoNowB2CLossCount'] . "]"; ?><br>
									<?= "B2B MMT[P:L] = " . $bkgassigned[0]['total_gozoNow_accept_mmt'] . "[" . $bkgassigned[0]['gozoNowB2BMMTProfitCount'] . " : " . $bkgassigned[0]['gozoNowB2BMMTLossCount'] . "]"; ?><br>
									<?= "B2B IBIBO[P:L] = " . $bkgassigned[0]['total_gozoNow_accept_ibibo'] . "[" . $bkgassigned[0]['gozoNowB2BIBIBOProfitCount'] . " : " . $bkgassigned[0]['gozoNowB2BIBIBOLossCount'] . "]"; ?><br>	
									<?= "B2B OTHERS[P:L] = " . $bkgassigned[0]['total_gozoNow_accept_b2bothers'] . "[" . $bkgassigned[0]['gozoNowB2BOTHERSProfitCount'] . " : " . $bkgassigned[0]['gozoNowB2BOTHERSLossCount'] . "]"; ?>
								</td>

								<td class="text-center"><?= $bkgassigned[0]['total_assigned'] ?><br>
									<?= "P = " . ($bkgassigned[0]['manualAssignProfit'] + $bkgassigned[0]['autoAssignProfit'] + $bkgassigned[0]['directAssignProfit']) . " | " . ($bkgassigned[0]['manualAssignProfitCount'] + $bkgassigned[0]['autoAssignProfitCount'] + $bkgassigned[0]['directAssignProfitCount']) . " [" . round((($bkgassigned[0]['totalProfit'] * 100) / $bkgassigned[0]['totalAmount']), 2) . "%]" ?><br>
									<?= "L = " . ($bkgassigned[0]['manualAssignLoss'] + $bkgassigned[0]['autoAssignLoss'] + $bkgassigned[0]['directAssignLoss']) . " | " . ($bkgassigned[0]['manualAssignLossCount'] + $bkgassigned[0]['autoAssignLossCount'] + $bkgassigned[0]['directAssignLossCount']) . " = ₹" . round(( ($bkgassigned[0]['manualAssignLoss'] + $bkgassigned[0]['autoAssignLoss'] + $bkgassigned[0]['directAssignLoss']) / ($bkgassigned[0]['manualAssignLossCount'] + $bkgassigned[0]['autoAssignLossCount'] + $bkgassigned[0]['directAssignLossCount'])), 1) . "pu" ?><br><br>
									<?= "B2C[P:L] = " . $bkgassigned[0]['total_assigned_b2c'] . "[" . $bkgassigned[0]['allB2CProfitCount'] . " : " . $bkgassigned[0]['allB2CLossCount'] . "]"; ?><br>
									<?= "B2B MMT[P:L] = " . $bkgassigned[0]['total_assigned_mmt'] . "[" . $bkgassigned[0]['allB2BMMTProfitCount'] . " : " . $bkgassigned[0]['allB2BMMTLossCount'] . "]"; ?><br>
									<?= "B2B IBIBO[P:L] = " . $bkgassigned[0]['total_assigned_ibibo'] . "[" . $bkgassigned[0]['allB2BIBIBOProfitCount'] . " : " . $bkgassigned[0]['allB2BIBIBOLossCount'] . "]"; ?><br>
									<?= "B2B OTHERS[P:L] = " . $bkgassigned[0]['total_assigned_b2bothers'] . "[" . $bkgassigned[0]['allB2BOTHERSProfitCount'] . " : " . $bkgassigned[0]['allB2BOTHERSLossCount'] . "]"; ?>										
								</td>

							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-xs-12 col-md-6" style="max-width: 100%; overflow: auto">
					<table class="table table-bordered mt10">
						<thead>
							<tr style="color: blue;background: whitesmoke">
								<th colspan="8" class="text-center"><u>Day's Region-wise Count</u></th>
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
									<td class="text-center"><?= $data['countBook'] . " [" . round((($data['countBook'] * 100) / $cntregionWise), 2) . "%]" ?></td>
								</tr>

								<?php
							}
							?>
							<tr>
								<td colspan="1" class="text-center" style="border-top : 1px solid grey;font-style: italic;">Total bookings</td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntmmt ?></td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntibibo ?></td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntb2bother ?></td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cntb2c ?></td>
								<td colspan="1" style="border-top : 1px solid grey;"  class="text-center"><?= $cnt ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php
				if (!empty($dataProvider4))
				{
					?>
					<div class="col-xs-12 col-md-6" style="max-width: 100%; overflow: auto">

						<div class="panel panel-primary" id="mbkg-grid1">
							<table class="table table-bordered mt10">
								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Day's Service Type-wise Count</u></th>
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
												<td><?php echo $serviceType['cntServiceType'] . " [" . round((($serviceType['cntServiceType'] * 100) / $serviceTypeCountAll), 2) . "%]" ?></td>
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
					</div>
				<?php }
				?>
				<?php
				if (!empty($dataProvider5))
				{
					?>
					<div class="col-xs-12 col-md-6" style="max-width: 100%; overflow: auto">

						<div class="panel panel-primary" id="mbkg-grid2">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Day's Service Type-wise Profits (All ₹ are post-commission) </u></th>
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
											<th class="col-xs-1" id="mbkg-grid2_c4">Gozo Profit(₹)</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i						 = 0;
										$serviceTypeProfit		 = 0;
										$serviceTypeProfitAll	 = 0;
										$serviceCount			 = 0;
										foreach ($dataProvider5 as $serviceProfit)
										{

											$serviceTypeProfitAll	 = $serviceTypeProfitAll + $serviceProfit['totalmargin'];
											$serviceCount			 = $serviceCount + $serviceProfit['cnt'];
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
												<td><?php echo $serviceProfit['totalmargin'] . " [" . round((($serviceProfit['totalmargin'] * 100) / $serviceTypeProfitAll), 2) . "%]" ?></td>
											</tr>
											<?php
											$i++;
											$serviceTypeProfit = $serviceTypeProfit + $serviceProfit['totalmargin'];
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> results.</div></div><div class="col-xs-12 col-sm-4 pr0">Total Profit: ₹<?php echo $serviceTypeProfit; ?></div><div class="col-xs-12 col-sm-4 pr0">Per booking Margin: ₹<?php echo round(($serviceTypeProfitAll / $serviceCount), 2) ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span><span></span><span></span><span></span><span></span></div>
						</div>
					</div>
				<?php } ?> 	


				<?php
				if (!empty($dataProvider3))
				{
					?>
					<div class="col-xs-12 col-md-6" style="max-width: 100%; overflow: auto">  
						<div class="panel panel-primary" id="mbkg-grid">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="3" class="text-center"><u>Day's Service Tier-wise Count (₹ is post commission)</u></th>
									</tr>

								</tbody></table>
							<div class="panel-body"><table class="table table-striped table-bordered mb0 table">
									<thead>
										<tr>
											<th class="col-xs-1" id="mbkg-grid_c0">Service Tier</th>
											<th class="col-xs-1" id="mbkg-grid_c1">Count</th>
											<th class="col-xs-1" id="mbkg-grid_c2">Total Amount</th>
											<th class="col-xs-1" id="mbkg-grid_c3">Gozo ₹ (post comm)</th>
											<th class="col-xs-1" id="mbkg-grid_c4">Assigned Margin (%)</th>
											<th class="col-xs-1" id="mbkg-grid_c4">Quoted Margin (%)</th>
										</tr>

									</thead>
									<tbody>
										<?php
										$i					 = 0;
										$tierCount			 = 0;
										$totalcount			 = 0;
										$totalamount		 = 0;
										$totalgozoamount	 = 0;
										$quotevendoramount	 = 0;

										foreach ($dataProvider3 as $val)
										{
											$totalcount			 = $totalcount + $val['cntServiceTier'];
											$totalamount		 = $totalamount + $val['booking_amount'];
											$totalgozoamount	 = $totalgozoamount + $val['gozo_amount'];
											$quotevendoramount	 = $quotevendoramount + $val['quote_vendor_amount'];
										}
										foreach ($dataProvider3 as $tier)
										{
											?>
											<tr class="<?php if ($i == 0 ? 'even' : 'odd')  ?>">
												<td><?php echo $tier['tierName']; ?></td>
												<td><?php echo $tier['cntServiceTier'] . " [ " . round((($tier['cntServiceTier'] / $totalcount) * 100), 2) . "% ]" ?></td>
												<td><?php echo $tier['booking_amount'] . " [ " . round((($tier['booking_amount'] / $totalamount) * 100), 2) . "% ]" ?></td>
												<td><?php echo $tier['gozo_amount'] . " [ " . round((($tier['gozo_amount'] / $totalgozoamount) * 100), 2) . "% ]" ?></td>
												<td><?= round((($tier['gozo_amount'] / $tier['booking_amount']) * 100), 2) . " [ ₹" . (round((($tier['gozo_amount'] / $tier['cntServiceTier'])), 2)) . " ]" ?></td>												
												<td><?= round((($tier['quote_vendor_amount'] / $tier['booking_amount']) * 100), 2) . " [ ₹" . (round((($tier['quote_vendor_amount'] / $tier['cntServiceTier'])), 2)) . " ]" ?></td>												
											</tr>
											<?php
											$i++;
											$tierCount = $tierCount + $tier['cntServiceTier'];
										}
										?>
									</tbody>
								</table></div>
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> result.</div></div><div class="col-xs-12 col-sm-6 pr0">Total bookings count: <?php echo $tierCount; ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span></div>
						</div>
					</div>
					<?php
				}
				if (!empty($dataProvider2))
				{
					?>
					<div class="col-xs-12 col-md-6" style="max-width: 100%; overflow: auto">  

						<div class="panel panel-primary" id="mbkg-grid">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Day's Car Category-wise Count</u></th>
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
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0">Total bookings: <?php echo $carCount; ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span><span></span><span></span><span></span></div>
						</div>
					</div>                   
					<?php
				}
				if (!empty($dataProvider7))
				{
					?>
					<div class="col-xs-12 col-sm-6" style="max-width: 100%; overflow: auto">  

						<div class="panel panel-primary" id="mbkg-grid2">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Day's cancellations</u></th>
									</tr>

								</tbody></table>
							<div class="panel-body"><table class="table table-striped table-bordered mb0 table">
									<thead>
										<tr>
											<th class="col-xs-1" id="mbkg-grid2_c0">Service Tier</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled [total]</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled [Gozo]</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled [non Gozo]</th>												
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
							<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Total <?php echo $i; ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0">Day's Cancellations:<?php echo $serviceCancelType; ?></div></div></div><div class="keys" style="display:none" title="/xyz/mbkg"><span></span><span></span><span></span><span></span><span></span></div>
						</div>
					</div>                 
				<?php }
				?> 

				<?php
				if (!empty($dataProvider6))
				{
					?>
					<div class="col-xs-12 col-sm-6" style="max-width: 100%; overflow: auto">  

						<div class="panel panel-primary" id="mbkg-grid2">

							<table class="table table-bordered mt10">

								<tbody><tr style="color: blue;background: whitesmoke">
										<th colspan="2" class="text-center"><u>Day's scheduled pickups</u></th>
									</tr>

								</tbody></table>
							<div class="panel-body"><table class="table table-striped table-bordered mb0 table">
									<thead>
										<tr>
											<th class="col-xs-1" id="mbkg-grid2_c0">Service Tier</th>
											<th class="col-xs-1" id="mbkg-grid2_c1">New</th>
											<th class="col-xs-1" id="mbkg-grid2_c5">Assigned</th>
											<th class="col-xs-1" id="mbkg-grid2_c2">Allocated</th>
											<th class="col-xs-1" id="mbkg-grid2_c3">Completed (margin%)</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled [total]</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled [Gozo]</th>
											<th class="col-xs-1" id="mbkg-grid2_c4">Cancelled [non Gozo]</th>
											<th class="col-xs-1" id="mbkg-grid2_c4"># [completed | cancel] </th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i						 = 0;
										$serviceType			 = 0;
										$totalNew				 = 0;
										$totalAssigned			 = 0;
										$totalOntheway			 = 0;
										$totalCompleted			 = 0;
										$totalCancelled			 = 0;
										$totalCancelledbygozo	 = 0;
										$totalCancelledbygozo	 = 0;
										$totalNotcancelledbygozo = 0;
										$bkgAmount				 = 0;
										$gozoAmount				 = 0;
										$bkgAmountall			 = 0;
										$gozoAmountall			 = 0;

										foreach ($dataProvider6 as $service)
										{
											$sum1					 = $service['new'] + $service['assigned'] + $service['ontheway'] + $service['completed'];
											?>
											<tr class="<?php if ($i % 2 == 0 ? 'even' : 'odd')  ?>">
												<td><?php echo $service['tierName'] ?></td>
												<td><?php echo $service['new'] ?></td>
												<td><?php echo $service['assigned'] ?></td>
												<td><?php echo $service['ontheway'] ?></td>
												<td><?php echo $service['completed'] ?>(<?= round((($service['ry_gozo_amount'] / $service['ry_booking_amount']) * 100), 2) ?>)</td>
												<td><?php echo $service['cancelled'] ?></td>
												<td><?php echo $service['cancelledbygozo'] ?></td>
												<td><?php echo ($service['notcancelledbygozo']) ?></td>
												<td><?php echo $service['cnt'] . "[ " . $sum1 . " | " . $service['cancelled'] . " ]"; ?></td>
											</tr>
											<?php
											$i++;
											$serviceType			 = $serviceType + $service['cnt'];
											$totalNew				 = $totalNew + $service['new'];
											$totalAssigned			 = $totalAssigned + $service['assigned'];
											$totalOntheway			 = $totalOntheway + $service['ontheway'];
											$totalCompleted			 = $totalCompleted + $service['completed'];
											$totalCancelled			 = $totalCancelled + $service['cancelled'];
											$totalCancelledbygozo	 = $totalCancelledbygozo + $service['cancelledbygozo'];
											$totalNotcancelledbygozo = $totalNotcancelledbygozo + $service['notcancelledbygozo'];
											$bkgAmount				 += $service['ry_booking_amount'];
											$gozoAmount				 += $service['ry_gozo_amount'];
											$bkgAmountall			 += $service['ry_booking_amount_all'];
											$gozoAmountall			 += $service['ry_gozo_amount_all'];
										}
										?>
										<tr class="<?php if ($i % 2 == 0 ? 'even' : 'odd')  ?>">
											<td><?php echo "All"; ?></td>
											<td><?php echo $totalNew; ?></td>
											<td><?php echo $totalAssigned; ?></td>
											<td><?php echo $totalOntheway; ?></td>
											<td><?php echo $totalCompleted ?>(<?= round((($gozoAmount / $bkgAmount) * 100), 2) ?>)</td>
											<td><?php echo $totalCancelled ?></td>
											<td><?php echo $totalCancelledbygozo ?></td>
											<td><?php echo $totalNotcancelledbygozo ?></td>
											<td><?php echo $serviceType . "[ " . ($totalNew + $totalAssigned + $totalOntheway + $totalCompleted) . " | " . $totalCancelled . " ]"; ?></td>

									</tbody>
								</table></div>
							<div class="panel-footer">
								<div class="row m0">
									<div class="col-xs-12 col-sm-4 p5">
										<div class="summary">Total <?php echo $i; ?> results.</div>
									</div>
									<div class="col-xs-12 col-sm-6 pr0">Today's scheduled pickups:<?php echo ($totalNew + $totalAssigned + $totalOntheway + $totalCompleted); ?><br>
										Today's completed pickups: <?php echo $totalCompleted; ?><br>
										Total Gozo amount (completed pickups): <?= $gozoAmount ?><br>
										Realized unit margin: ₹ <?= round(($gozoAmount / $totalCompleted), 2) ?>
									</div>

								</div></div>

						</div>
					</div>   
				<?php } ?> 

			</div>
			<a href="<?php echo Yii::app()->createUrl('admpnl/report/zonewise-count'); ?>" target="blank">>> Day's Zone-wise Count Report</a>
			</br></br>
			<a href="<?php echo Yii::app()->createUrl('admpnl/generalReport/partnerWiseCountBooking'); ?>" target="blank">>> Partner Booking Report (B2B Other)</a>
			</br></br>
			<a href="<?php echo Yii::app()->createUrl('admpnl/report/runningtotal'); ?>" target="blank">>> Running Total Report</a>
			</br></br>
			<a href="<?php echo Yii::app()->createUrl('admpnl/report/booking'); ?>" target="blank">>> Booking Report</a>
			</br></br>
			<a href="<?php echo Yii::app()->createUrl('admpnl/report/pickup'); ?>" target="blank">>> Pickup Report</a>
			</br></br>


			<a href="https://bi.gozo.cab/public/dashboard/1f186a95-c2d7-4532-b0f3-9f207bc40e10?must_pick_a_date=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>&weekscounttrend=4" target="blank">>>>> BI | Booking flow today</a></br>
			<a href="https://bi.gozo.cab/public/question/8f1fe263-63bb-4753-8eba-1afa7dac61e9?create_date=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>" target="blank">>>>> BI | Today's creates</a></br>
			<a href="https://bi.gozo.cab/public/dashboard/cd5b8728-7bd5-4aa2-a8c7-0220a9f8d053" target="blank">>>>> BI | Daily Margin</a></br></br></br>
			<a href="https://bi.gozo.cab/public/dashboard/3c7c1065-dffb-47fc-bf48-162bb1cc5eaa" target="blank">>>>> BI | Conversions Dashboard</a></br>
			<a href="https://bi.gozo.cab/public/dashboard/49248c16-7a4c-477d-b216-5d13b702b9f3?single_date=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>" target="blank">>>>> BI | Bookings distribution deshboard</a></br>
			<a href="https://bi.gozo.cab/public/question/d9863c50-a2c6-4479-bbd1-13eed545c833?date_of_pickup=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>" target="blank">>>>> BI | Pickup details (Master Regions)</a></br>
			<a href="https://bi.gozo.cab/public/question/e137586a-eb80-45eb-a337-01ca0c0c02f9" target="blank">>>>> BI | Dynamic pricing report</a></br>
			<a href="https://bi.gozo.cab/public/question/d29042ee-81ac-43e2-8dcb-651cc72003df?assign_date=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>&create_date=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>" target="blank">>>>> BI | Assignments analysis report</a></br>
			<a href="https://bi.gozo.cab/public/question/13da846d-5de5-4bde-b780-916bea94a30d" target="blank">>>>> BI | Manual Assignments (Last 7 days) report</a></br>
			<a href="https://bi.gozo.cab/public/question/88b0c4c7-c359-4103-a2ed-edea80c6b475?bookingCreateDate=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>~<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>" target="blank">>>>> BI | Loss bookings analysis</a></br>
			<a href="https://bi.gozo.cab/public/question/9fb77e22-457c-48e0-a733-df734cf4650e" target="blank">>>>> BI | Future bookings outlook (by status) report</a></br>
			<a href="https://bi.gozo.cab/public/question/cb87db4a-9810-45b7-8376-f6c1b9b87b18" target="blank">>>>> BI | Pickup revenue ₹ (upcoming daily #s) report</a></br></br>
			<a href="https://bi.gozo.cab/public/question/8d2ecede-d529-4508-bead-2ba5f8631a99?date_of_pickup=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>" target="blank">>>>> BI | Pickup distribution by master regions pie chart (daily)</a></br>
			<a href="https://bi.gozo.cab/public/question/d9863c50-a2c6-4479-bbd1-13eed545c833?date_of_pickup=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>" target="blank">>>>> BI | Pickup distribution by master regions detail (daily)</a></br>
			<a href="https://bi.gozo.cab/public/question/b9c45924-b56a-4a2f-932c-5a78a371b41c?date_of_pickup=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>" target="blank">>>>> BI | Pickup by Assign mode report (by day)</a></br>
			<a href="https://bi.gozo.cab/public/question/9028634a-6aa4-45ec-b5b6-246c42fb4f3a?date_of_pickup=<?php echo date('Y-m-d', strtotime($bookings['lastRefeshDate'])); ?>" target="blank">>>>> BI | Pickup distribution by Zone Type (by day)</a></br>






		<?php } ?>
	</div></div>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>