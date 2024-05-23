<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
    <tbody>
        <tr>
            <td colspan="2" style="text-align: center;background-color: #e5e5e5; font-size: 14pt; width: 100% !important; padding: 5x; border: solid 1px #000;">ACCOUNT STATEMENT</td>
        </tr>
        <tr><td colspan="2" >&nbsp;</td>
        <tr>
            <td valign="top" width="275" class="leftHeader">
                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td valign="top" rowspan="2" class="label" style="white-space: nowrap" width="78">Addressed to</td>
                            <td valign="top" width="165"><?= ($record['ctt_user_type']==1)?$record['ctt_first_name'].' '.$record['ctt_last_name']:$record['ctt_business_name']; //$record['vnd_company'] ?></td>
                        </tr>
                        <tr>
                            <td valign="top" width="165"><?= $record['ctt_address'] ?></td>
                        </tr>
                        <tr>
                            <td valign="top" width="78"><b>Phone</b></td>
                            <td valign="top" width="165"><?= $record['phn_phone_no'] ?></td>
                        </tr>
                        <tr>
                            <td valign="top" width="78"><b>Email</b></td>
                            <td valign="top" width="165"><?= $record['eml_email_address'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px">


                    <tbody>
                        <tr>
                            <td valign="top" class="label" width="153">Opening balance as of <?php echo $fromDate; ?></td>
                            <td valign="top" width="85">
                                <table>
                                    <tr>
                                        <td align="left" style="text-transform: uppercase; font-size: 10px; font-weight: bold; width: 60%;">
											<?php
											$textOpeningBalance	 = ($openingAmount['vendor_amount']) > 0 ? "PAY GOZO" : "GOZO TO PAY";
											echo $textOpeningBalance;
											?>
                                        </td>
                                        <td align="right" style="width: 40%;">
											<?php
											$a					 = ($openingAmount['vendor_amount'] >= 0) ? 1 : -1;
											echo round($openingAmount['vendor_amount'] * $a);
											?>
                                        </td>
                                    </tr>
                                </table> 
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" class="label" width="153">Accounts <?= ($vendorAmount['vendor_amount'] > 0) ? "Receivable" : "Payable" ?> as of <?php echo $toDate; ?> </td>
                            <td valign="top" width="85" align="right">
                                <table>
                                    <tr>
                                        <td align="left"  style="text-transform: uppercase; font-size: 10px; font-weight: bold; width: 60%;">
											<?php
											$textAccounts		 = ($vendorAmount['vendor_amount']) > 0 ? "PAY GOZO" : "GOZO TO PAY";
											echo $textAccounts;
											?>
                                        </td>
                                        <td align="right" style="width: 40%;">
											<?php
											$a					 = ($vendorAmount['vendor_amount'] >= 0) ? 1 : -1;
											echo round($vendorAmount['vendor_amount'] * $a);
											?>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                        <tr>
                            <td valign="top" class="label" width="153"><?php
								$payDateText		 = round($vendorAmount['vendor_amount']) > 0 ? "PLEASE PAY GOZO by " : "GOZO will pay you by ";
								$deadLineDays		 = date("d/m/Y", strtotime(date('Y-m-d', strtotime("+7 days"))));
								echo $payDateText . $deadLineDays;
								?>
                            </td>
                            <td valign="top" width="85" align="right">
								<?php
								$min				 = 0;
								$credit				 = ($record['vrs_credit_limit'] == 0) ? 10000 : $record['vrs_credit_limit'];
								if ($vendorAmount['vendor_amount'] > 0)
								{
									$min = $vendorAmount['vendor_amount'] - round($credit / 2);
									$min = ($min > 0) ? $min : round($vendorAmount['vendor_amount'] / 2);
								}
								else
								{
									$min = $vendorAmount['vendor_amount'] * -1;
								}
								?>
<?= $min ?>
                            </td>
                        </tr>

						<tr>
							<td valign="top" class="label" width="153">Security Deposit</td>
                            <td valign="top" width="85" align="right"><?= ($vendorAmount['vnd_security_amount'] > 0) ? round($vendorAmount['vnd_security_amount']) : 0; ?></td>
                        </tr>

                    </tbody>
                </table>
            </td>
            <td valign="top" class="rightHeader">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="borderless" style="margin-bottom: 0px;">
                    <tbody>
                        <tr>
                            <td valign="top" class="" width="120"><strong>From Date :</strong> <?php echo $fromDate; ?></td>
                            <td valign="top" class="" width="153" style="text-align: right"><strong>To Date :</strong> <?php echo $toDate; ?></td>
                        </tr>
                    </tbody>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>

                        <tr>
                            <td width="101" class="label">Rating trend </td>
                            <td width="327">
                                <table border="0" cellpadding="0" cellspacing="0" class="borderless">
                                    <tbody>
                                        <tr>
                                            <td valign="top" width="86" align="center"><?= ($record['vnd_last_three_month_rating'] == '') ? $overall_rating : $record['vnd_last_three_month_rating'] ?>
                                                <br>(3 m)
                                            </td>
                                            <td valign="top" width="86" align="center">
<?= ($record['vnd_last_six_month_rating'] == '') ? $overall_rating : $record['vnd_last_six_month_rating'] ?>
                                                <br>(6 m)
                                            </td>
                                            <td valign="top" width="86" align="center">
<?= ($record['vnd_last_twelve_month_rating'] == '') ? $overall_rating : $record['vnd_last_twelve_month_rating'] ?>
                                                <br>(12 m)
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <tr>
<? $zones	 = str_replace(",", ", ", $record['vnd_zones']); ?>
<? $zones	 = str_replace("Z-", "", $zones); ?>
                            <td width="101" class="label">Zones operating in </td>
                            <td width="327"><?= ($zones == '') ? 'Not Available' : $zones ?></td>
                        </tr>
                        <tr>
                            <td width="101" class="label">Home City </td>
                            <td width="327"><?= ($record['vnd_home_city'] == '') ? 'Not Available' : $record['vnd_home_city'] ?></td>
                        </tr>
                        <tr>
                            <td width="101" class="label"># of Trips </td>
                            <td width="327">
                                <table border="0" cellpadding="0" cellspacing="0" class="borderless">
                                    <tbody>
                                        <tr>
                                            <td valign="top" width="78" align="center">
<?= $record['vnd_last_ten_day_trips'] ?>
                                                <br>(Last 10 d)
                                            </td>
                                            <td valign="top" width="48" align="center">
<?= $record['vnd_last_one_month_trips'] ?>
                                                <br>(1 m)
                                            </td>
                                            <td valign="top" width="48" align="center">
<?= $record['vnd_last_three_month_trips'] ?>
                                                <br>(3 m)
                                            </td>
                                            <td valign="top" width="48" align="center">
<?= $record['vnd_last_six_month_trips'] ?>
                                                <br>(6 m)
                                            </td>
                                            <td valign="top" width="53" align="center">
<?= $record['vnd_last_twelve_month_trips'] ?>
                                                <br>(12 m)
                                            </td>
                                            <td valign="top" width="62" align="center">
<?= ($record['vrs_total_trips'] == '') ? 0 : $record['vrs_total_trips'] ?>
                                                <br>(lifetime)
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <br><b>SEND PAYMENT TO - </b>
                <br><b>Beneficiary Name:</b> GOZO TECHNOLOGIES PRIVATE LIMITED;&nbsp;
                <br><b>Bank Name:</b> HDFC BANK LTD;&nbsp;
                <br><b>Branch Name:</b> Badshahpur, Gurgaon;&nbsp;
                <br><b>IFSC Code:</b> HDFC0001098;&nbsp;
                <br><b>Account Number:</b> 50200020818192
				<?php
				if ($vendorAmount['vnd_security_amount'] == 0)
				{
					?> 
					<br><br><b>Please send security deposit of Rs. 2000/-
						<br>Vendors who have a security deposit continue to receive bookings continuously
						<br>even when they have some payment pending to Gozo</b>         
<?php }
?>
            </td>
        </tr>
    </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="no-border" style="margin-top: 30px">
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
</table>
<table class="invoice_box">
    <tr class="blue2 white-color">
        <th align="center" style="width: 0.7in">Date</th>
        <th align="center" style="width: 0.7in">Pickup Date</th>
        <th align="center" style="width: 1.4in">Description</th>       
        <th style="width: 0.9in">Route</th>      
        <th align="center" style="width: 0.7in">Amount</th>
        <th align="center" style="width: 0.7in">Balance<br>(+=You pay gozo,-=Gozo pays you)</th>

    </tr>
	<?php
	$ctr = 0;
	if (count($vendorList) > 0)
	{
		foreach ($vendorList as $vendor)
		{
			$bkgcode		 = ($vendor['ledgerNames'] == NULL) ? "none" : trim($vendor['ledgerNames']);
			$bkgId			 = ($vendor['bkg_id'] == NULL) ? "none" : trim($vendor['bkg_id']);
			$drvCabDetails	 = ($vendor['bcb_cab_number'] != '' && $vendor['drv_name'] != '') ? $vendor['bcb_cab_number'] . " / " . $vendor['drv_name'] : "";
			$notes			 = (trim($vendor['ven_trans_remarks']) == '') ? '' : '(' . trim($vendor['ven_trans_remarks']) . ')';
			$advance		 = ($vendor['bkg_advance_amount'] == '') ? '0' : round($vendor['bkg_advance_amount']);
			$gozoAmount		 = ($vendor['gozo_amount'] == '') ? '0' : round($vendor['gozo_amount']);
			$netAmount		 = ($gozoAmount - $advance);
			$pickupDate		 = ($vendor['bkg_pickup_date'] == '' || date('d-m-Y', strtotime($vendor['bkg_pickup_date'])) == '01-01-1970') ? '' : date('d-m-Y h:iA', strtotime($vendor['bkg_pickup_date']));
			$vendorCollected = ($vendor['bkg_vendor_collected'] == '') ? '0' : round($vendor['bkg_vendor_collected']);
			$transactionDate = ($vendor['act_date'] != '') ? date('d-m-Y', strtotime($vendor['act_date'])) : '';
			$toCities		 = ($vendor['from_city'] != '') ? $vendor['from_city'] : '';
			?>
			<tr>
				<td align="center"><?php echo $transactionDate; ?></td>
				<td align="left"><?php echo $pickupDate ?></td>
				<td align="left"><?php
					echo $bkgcode . '<br>'
					. '<br>' . $notes;
					?></td>
				<td align="left"><?php echo $toCities; ?></td>
				<td align="right"><i class="fa fa-inr"></i><?php echo round(trim($vendor['ven_trans_amount'])); ?></td>
				<td align="right"><i class="fa fa-inr"></i><?php echo round(trim($vendor['runningBalance'])); ?> </td>

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


