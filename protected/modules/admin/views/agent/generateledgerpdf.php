<!--<table border="0" width="100%"><tr><td style="text-align: right;font-size: 8pt;"></td></tr></table>-->
<table border="0" cellpadding="0" cellspacing="0"   class="no-border header" style="width: 100%;">
    <tbody>
        <tr>
            <td valign="top"   class="leftHeader" style="width: 50%!important">
                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td valign="top" width="78"><b>Addressed to</b></td>
                            <td valign="top" width="165"><?= $companyName ?></td>
                        </tr>
<!--                        <tr>
                            <td valign="top" width="165"><? //= $record['ctt_address']     ?></td>
                        </tr>-->
                        <tr>
                            <td valign="top" width="78"><b>Phone</b></td>
                            <td valign="top" width="165"><?= $phone ?></td>
                        </tr>
                        <tr>
                            <td valign="top" width="78"><b>Email</b></td>
                            <td valign="top" width="165"><?= $email ?></td>
                        </tr>
                    </tbody>
                </table>
				<table border="1" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px">


					<tbody>
						<tr>
							<td valign="top" class="label" width="153">Opening balance as of <?php echo date('d-m-Y', strtotime($fromDate)); ?></td>
							<td valign="top" width="85">
								<table>
									<tr>
										<td align="left" style="text-transform: uppercase; font-size: 10px; font-weight: bold; width: 60%;">
											<?php
											$textOpeningBalance	 = ($openingAmount['totAmount']) > 0 ? "PAY GOZO" : "GOZO TO PAY";
											echo $textOpeningBalance;
											?>
										</td>
										<td align="right" style="width: 40%;">
											<?php
											$a					 = ($openingAmount['totAmount'] >= 0) ? 1 : -1;
											echo round($openingAmount['totAmount'] * $a);
											?>
										</td>
									</tr>
								</table> 
							</td>
						</tr>
						<tr>
							<td valign="top" class="label" width="153">Accounts <?= ($agentAmount['totAmount'] > 0) ? "Receivable" : "Payable" ?> as of <?php echo date('d-m-Y', strtotime($toDate)); ?> </td>
							<td valign="top" width="85"  >
								<table>
									<tr>
										<td align="left"  style="text-transform: uppercase; font-size: 10px; font-weight: bold; width: 60%;">
											<?php
											$textAccounts		 = ($agentAmount['totAmount']) > 0 ? "PAY GOZO" : "GOZO TO PAY";
											echo $textAccounts;
											?>
										</td>
										<td align="right" style="width: 40%;">
											<?php
											$a					 = ($agentAmount['totAmount'] >= 0) ? 1 : -1;
											echo round($agentAmount['totAmount'] * $a);
											?>
										</td>
									</tr>
								</table>

							</td>
						</tr>
						<tr>
							<td valign="top" class="label" width="153"><?php
								$payDateText		 = round($agentAmount['totAmount']) > 0 ? "PLEASE PAY GOZO by " : "GOZO will pay you by ";
								$deadLineDays		 = date("d-m-Y", strtotime(date('Y-m-d', strtotime("+7 days"))));
								echo $payDateText . $deadLineDays;
								?>
							</td>
							<td valign="top" width="85" align="right">
								<?php
//								$min				 = 0;
//								$credit				 = ($record['vrs_credit_limit'] == 0) ? 10000 : $record['vrs_credit_limit'];
//								if ($agentAmount['agent_amount'] > 0)
//								{
//									$min = $agentAmount['agent_amount'] - round($credit / 2);
//									$min = ($min > 0) ? $min : round($agentAmount['agent_amount'] / 2);
//								}
//								else
//								{
//									$min = $agentAmount['agent_amount'] * -1;
//								}
								?>
								<? //= $min ?>
							</td>
						</tr>

<!--						<tr>
							<td valign="top" class="label" width="153">Credit Limit</td>
							<td valign="top" width="85" align="right"><?//= ($agentAmount['creditLimit'] > 0) ? round($agentAmount['creditLimit']) : 0; ?></td>
						</tr>-->
						
						<tr>
							<td colspan="2" valign="top" width="153"><strong>Last payment received:</strong><?php if($lastPaymentReceived['paymentReceived']!='' && $lastPaymentReceived['paymentReceived'] > 0){ ?> Rs.<?= $lastPaymentReceived['paymentReceived'] ?> on <?= date('d-m-Y', strtotime($lastPaymentReceived['ReceivedDate'])) ?><?php }else { echo " nil";}?> </td>
						</tr>
						
					</tbody>
				</table>
			</td>
			<td valign="top" class="rightHeader"  style="width: 50%!important">
				<table border="0" cellpadding="0" cellspacing="0"   class="borderless" style="margin-bottom: 0px;width: 100%">
					<tbody>
						<tr>
							<td colspan="2" style="text-align: right;text-transform: uppercase;"><b>Invoice #:</b><?= $invoiceNo ?></td>
						</tr>
						<tr>
							<td valign="top" class="" width="120"><strong>From Date :</strong> <?php echo date('d-m-Y', strtotime($fromDate)); ?></td>
							<td valign="top" class="" width="153" style="text-align: right"><strong>To Date :</strong> <?php echo date('d-m-Y', strtotime($toDate)); ?></td>
						</tr>
					</tbody>
				</table>
				<br><br>We declare that this statement shows the actual value 
				<br>of all transactions and that all particulars are true and correct.
				<br>All payments may please be made in favour of <b>"Gozo Technologies pvt. Ltd."</b> 
				<br>
				<br>The Bank details are as follows:
				<br><b>Beneficiary Name:</b> GOZO TECHNOLOGIES PRIVATE LIMITED;&nbsp;
				<br><b>Bank Name:</b> HDFC BANK LTD;&nbsp;
				<br><b>Branch Name:</b> Badshahpur, Gurgaon;&nbsp;
				<br><b>IFSC Code:</b> HDFC0001098;&nbsp;
				<br><b>Account Number:</b> 50200020818192
				<?php
//	if ($agentAmount['securitydepo'] == 0)
//	{
//		
				?> 
				<!--		<br><br><b>Please send security deposit of Rs. 2000/-
				<br>Vendors who have a security deposit continue to receive bookings continuously
				<br>even when they have some payment pending to Gozo</b>         -->
				<?php //}
				?>
			</td>
		</tr>
	</tbody>
</table>
<!--<table border="0" cellpadding="0" cellspacing="0" width="100%" class="no-border" style="margin-top: 30px">
    <tbody>
        <tr>
            <td colspan="" style="font-size: 9pt; width: 100% !important; padding: 5x; border: solid 1px #000;">
                <table style="font-size: 9px;">
					<tr><td style="text-align: left;"><h3>IMPORTANT NOTICE</h3></td></tr>
					<tr>
						<td style="text-align:left;">
							For all account related inquiries please write ONLY to accounts@gozocabs.in<br>
							When contacting Gozo...
							<ul>
								<li>for booking related issues call our operator support line at +91 033-66283910 only.</li>
								<li>for operator account or documentation related issues call +91 033-663283905.</li>
								<li>Please DO NOT CALL the standard customer service line.<br><br>
									Please check this account statement for accuracy and report all errors within 7 days of receiving this statement. Our accounts department will not be able to help with any issues being raised after 7days of statement being generated.<br>
								</li>
							</ul>
						</td>
					</tr>
				</table>  
			</td></tr>
	</tbody>
</table>-->
<table class="invoice_box" >
    <tr class="blue2 white-color">
        <th align="center" style="width: 0.7in">Transaction Date</th>
		<th align="center" style="width: 0.7in">Booking ID</th>
        <th align="center" style="width: 0.7in">Pickup Date</th>
        <th align="center" style="width: 1.4in">Booking Info</th>       
        <td align="center" style="width: 0.7in"><strong>Amount</strong><br><span style="font-size:10px;">(+=credit to gozo,<br>-=credit to agent)</span></td>
		<th style="width: 1.2in">Notes</th> 
        <th align="center" style="width: 0.6in">Running Balance</th>

    </tr>
	<?php
	$address	 = Config::getGozoAddress();
	$ctr		 = 0;
	if (count($agentList) > 0)
	{
		foreach ($agentList as $agent)
		{
			$bkgcode		 = ($agent['ledgerNames'] == NULL) ? "none" : trim($agent['ledgerNames']);
			$bkgId			 = ($agent['bookingId'] == NULL) ? "none" : trim($agent['bookingId']);
			$drvCabDetails	 = ($agent['bcb_cab_number'] != '' && $agent['bcb_driver_id'] != '') ? ' - ' . $agent['bcb_cab_number'] : '';
			$notes			 = (trim($agent['act_remarks']) == '') ? '' : '(' . trim($agent['act_remarks']) . ')';
			$advance		 = ($agent['bkg_advance_amount'] == '') ? '0' : round($agent['bkg_advance_amount']);
			$gozoAmount		 = ($agent['gozo_amount'] == '') ? '0' : round($agent['gozo_amount']);
			$netAmount		 = ($gozoAmount - $advance);
			$pickupDate		 = ($agent['bkg_pickup_date'] == '' || date('d-m-Y', strtotime($agent['bkg_pickup_date'])) == '01-01-1970') ? '' : date('d-m-Y h:iA', strtotime($agent['bkg_pickup_date']));
			$vendorCollected = ($agent['bkg_vendor_collected'] == '') ? '0' : round($agent['bkg_vendor_collected']);
			$transactionDate = ($agent['act_date'] != '') ? date('d-m-Y', strtotime($agent['act_date'])) : '';
			$toCities		 = ($agent['bookingInfo'] != '') ? $agent['bookingInfo'] : '';
			//$vhtType         = ($agent['bcb_cab_number'] != '' && $agent['bcb_driver_id'] != '') ? $agent['vht_make'] : 'NA';
			$agentName		 = '<br>Traveler: ' . $agent['fname'] . ' ' . $agent['lname'];
			$travelerName	 = ($agent['fname'] != '') ? $agentName : ' ';
			?>
			<tr>
				<td align="center"><?php echo $transactionDate; ?></td>
				<td align="left"><?php echo $bkgId ?></td>
				<td align="left"><?php echo $pickupDate ?></td>
				<td align="left"><?php
					echo $toCities
					. $travelerName
					. '<br>' . $agent['vht_make'] . $drvCabDetails;
					?></td>	
				<td align="right"><i class="fa fa-inr"></i><?php echo round(trim($agent['adt_amount'])); ?></td>
				<td align="left"><?php echo $notes; ?></td>
				<td align="right"><i class="fa fa-inr"></i><?php echo round(trim($agent['runningBalance'])); ?> </td>

			</tr>
			<?php
			$ctr			 = ($ctr + 1);
		}
	}
	else
	{
		?>
		<tr><td colspan="7">No Records Yet Found.</td></tr>        
		<?php
	}
	?>
</table>
<div class="row ">
	<div class="col-xs-12"  style="font-size: 8px;line-height: 1.4em;text-align: justify">
		<p>We declare that this Invoice shows the actual value of all transactions and that all particulars are true and correct. 
			This bill is issued by the cab driver and not by Gozocabs. Gozocabs acts only as an intermediary for arranging the cab services. 
			GST is collected and remitted by Gozo Technologies Pvt. Ltd. [GSTIN: 06AAFCG0222J1Z0]; in the capacity of Aggregator as per the Finance Budget, 2015 read with GST Notification No. 5/2015
			In case of any queries/complaints, write to us on info@aaocab.com <br>
			This is an electronically generated invoice and does not require signature. All applicable terms and conditions are available at http://www.aaocab.com/terms 
		</p></div>
</div>
<div class="row pt20">
	<div class="col-xs-12">
		<hr style="margin-top: 10px!important;margin-bottom: 5px">
		<p style="font-size: 8px; text-align: center; margin-bottom: 2px; line-height: 1.4em"><b>Corporate Office:</b> <?= $address ?> &nbsp;|&nbsp; Email: info@aaocab.com &nbsp;|&nbsp; Phone: (+91) 90518-77-000 (24x7), International (+1) 650-741-GOZO (24x7) &nbsp;|&nbsp; www.aaocab.com </p>
	</div>
</div>



