
<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
    <tbody>
        <tr>
            <td colspan="2" style="text-align: center;background-color: #e5e5e5; font-size: 14pt; width: 100% !important; padding: 5x; border: solid 1px #000;">INVOICE</td>
        </tr>
        <tr><td colspan="2" >&nbsp;</td>
        <tr>
            <td valign="top" width="275" class="leftHeader">
                <table border="0" cellpadding="3" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td valign="top" rowspan="2" class="label" style="white-space: nowrap" width="78">Addressed to</td>
                            <td valign="top" width="165"><?= $record['vnd_company'] ?></td>
                        </tr>
<!--                                        <tr>
                                <td valign="top" class="label" width="78">Owner </td>
                                <td valign="top" width="165"><?= ($record['vnd_owner'] == '') ? 'Not Available' : $record['vnd_owner'] ?></td>
                        </tr>-->
                        <tr>
<!--                                            <td valign="top" class="label" width="78">Phone no. </td>-->
                            <td valign="top" width="165"><?= $record['vnd_address'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px">
                    <tbody>
                        <tr>
                            <td valign="top" class="label" width="153">Opening balance as on <?php echo $fromDate; ?></td>
                            <td valign="top" width="85" align="right"><?= round($openingAmount['vendor_amount']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" class="label" width="153">Accounts <?= ($vendorAmount['vendor_amount'] > 0) ? "Receivable" : "Payable" ?> as on <?php echo $toDate; ?> </td>
                            <td valign="top" width="85" align="right">
								<?php $a		 = ($vendorAmount['vendor_amount'] >= 0) ? 1 : -1; ?>
								<?= $a * trim($vendorAmount['vendor_amount']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" class="label" width="153">Minimum Amount Payable </td>
                            <td valign="top" width="85" align="right">
								<?php
								$min	 = 0;
								$credit	 = ($record['vnd_credit_limit'] == 0) ? 10000 : $record['vnd_credit_limit'];
								if ($vendorAmount['vendor_amount'] > 0)
								{
									$min = $vendorAmount['vendor_amount'] - round($credit / 2);
									$min = ($min > 0) ? $min : round($vendorAmount['vendor_amount'] / 2);
								}
								?>
								<?= $min ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </td>
            <td valign="top" class="rightHeader">
<!--								<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 5px; width: 100%">
                                                            <tbody>
                                                                    <tr>
                                                                            <td style="width: 46%"><strong>PAN No.:</strong>  AASCG0222J</td>
                                                                            <td style="text-align: right"><strong>Service Tax No.:</strong> AAFCG0222JSD001</td>
                                                                    </tr></tbody></table>-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="borderless" style="margin-bottom: 0px;">
                    <tbody>
                        <tr>
                            <td valign="top" class="" width="120"><strong>From Date :</strong> <?php echo $fromDate; ?>
                            </td>
                            <td valign="top" class="" width="153" style="text-align: right"><strong>To Date :</strong> <?php echo $toDate; ?> 
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
<!--                                        <tr>
						<? $overall_rating	 = ($record['vnd_overall_rating'] == '') ? 'Not Available' : $record['vnd_overall_rating'] ?>
                                    <td width="101" class="label">Current rating </td>
                                    <td width="327"><?= $overall_rating ?></td>
                            </tr>-->
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
<!--                                        <tr>
                                <td width="101" class="label"># of active drivers </td>
                                <td width="327"><?= ($record['vnd_total_drivers'] == '') ? 0 : $record['vnd_total_drivers'] ?></td>
                        </tr>
                        <tr>
                                <td width="101" class="label"># of active vehicles </td>
                                <td width="327"><?= ($record['vnd_total_vehicles'] == '') ? 0 : $record['vnd_total_vehicles'] ?></td>
                        </tr>-->
                        <tr>
							<? $zones			 = str_replace(",", ", ", $record['vnd_zones']); ?>
							<? $zones			 = str_replace("Z-", "", $zones); ?>
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
												<?= ($record['vnd_total_trips'] == '') ? 0 : $record['vnd_total_trips'] ?>
                                                <br>(lifetime)
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="no-border" style="margin-top: 10px">
    <tbody>
        <tr>
            <td colspan="" style="text-align: center; font-size: 9pt; width: 100% !important; padding: 5x; border: solid 1px #000;">
                For all account related queries write to accounts@gozocabs.in
            </td></tr></tbody></table>
<table class="invoice_box">
    <tr class="blue2 white-color">
        <td align="center" style="width: 0.7in"><b>Date</b></td>
        <td align="center" style="width: 0.7in"><b>Pickup Date</b></td>
        <td align="center" style="width: 0.7in"><b>Customer</b></td>
        <td align="center" style="width: 0.7in"><b>Booking ID</b></td>
        <td style="width: 0.9in"><b>Route</b></td>
        <td style="width: 0.9in"><b>Cab/Driver</b></td>
        <td align="center" style="width: 0.7in"><b>Amount</b></td>
        <td align="center" style="width: 0.7in"><b>Vendor Amount</b></td>
        <td><b>Notes</b></td>
    </tr>
	<?php
	$ctr			 = 0;
	if (count($vendorList) > 0)
	{
		foreach ($vendorList as $vendor)
		{
			?>
			<tr>
				<td align="center"><?php echo trim($vendor['ven_trans_date']); ?></td>
				<td align="center"><?php echo trim($vendor['bkg_pickup_date']); ?></td>
				<td align="center"><?php echo trim($vendor['bkg_user_name'] . ' ' . $vendor['bkg_user_lname']); ?></td>
				<td align="center"><?php
					if ($vendor['bkg_booking_id'] == NULL)
					{
						echo "none";
					}
					else
					{
						echo trim($vendor['bkg_booking_id']);
					}
					?></td>
				<td><?php
					if ($vendor['from_city'] == NULL)
					{
						echo "none";
					}
					else
					{
						echo trim($vendor['from_city']) . ' - ';
					}
					if ($vendor['to_city'] == NULL)
					{
						echo "";
					}
					else
					{
						echo trim($vendor['to_city']);
					}
					?></td>
				<td><?php
					if ($vendor['bcb_cab_number'] != '' && $vendor['drv_name'] != '')
					{
						echo $vendor['bcb_cab_number'] . " / " . $vendor['drv_name'];
					}
					?></td>
				<td align="right"><i class="fa fa-inr"></i><?php echo round(trim($vendor['ven_trans_amount'])); ?></td>
				<td align="right"><i class="fa fa-inr"></i><?php echo round(trim($vendor['bkg_vendor_amount'])); ?></td>
				<td><?php echo trim($vendor['ven_trans_remarks']); ?></td>
			</tr>
			<?php
			$ctr = ($ctr + 1);
		}
	}
	else
	{
		?>
		<tr><td colspan="6">No Records Yet Found.</td></tr>        
	<?php }
	?>
</table>

