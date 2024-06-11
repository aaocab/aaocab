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
                        <tr>
                            <td valign="top" width="165"><?= $record['vnd_address'] ?></td>
                        </tr>
                        <tr>
                            <td valign="top" width="78"><b>Phone</b></td>
                            <td valign="top" width="165"><?= $record['vnd_phone'] ?></td>
                        </tr>
                        <tr>
                            <td valign="top" width="78"><b>Email</b></td>
                            <td valign="top" width="165"><?= $record['vnd_email'] ?></td>
                        </tr>

                    </tbody>
                </table>


            </td>
            <td valign="top" class="rightHeader">
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
                For all account related queries write to accounts@aaocab.in
            </td></tr></tbody></table>
<table class="invoice_box">
    <tr class="blue2 white-color">
        <td align="center" style="width: 0.7in"><b>Booking Date</b></td>
        <td align="center" style="width: 0.7in"><b>Booking Id</b></td>
        <td align="center" style="width: 0.7in"><b>From</b></td>
        <td align="center" style="width: 0.7in"><b>To</b></td>
        <td align="center" style="width: 0.7in"><b>Booking Amount</b></td>
        <td align="center" style="width: 0.9in"><b>Vendor Amount</b></td>
        <td align="center" style="width: 0.5in"><b>Commission</b></td>
        <td align="center" style="width: 0.7in"><b>GST</b></td>
    </tr>
	<?php
	$ctr			 = 0;
	$sumCommission	 = 0;
	$sumServiceTax	 = 0;
	$sumVendorAmount = 0;
	if (count($dataList) > 0)
	{
		foreach ($dataList as $data)
		{
			$sumCommission	 = ($sumCommission + $data['bkg_gozo_amount']);
			$sumServiceTax	 = ($sumServiceTax + $data['bkg_service_tax']);
			$sumVendorAmount = ($sumVendorAmount + $data['bkg_vendor_amount']);
			?>
			<tr>
				<td align="center"><?php echo date('d/m/Y', strtotime($data['bkg_create_date'])); ?></td>
				<td align="center"><?php echo $data['bkg_booking_id']; ?></td>
				<td align="center"><?php echo $data['from_city_name']; ?></td>
				<td align="center"><?php echo $data['to_city_name']; ?></td>
				<td align="right"><i class="fa fa-inr"></i><?php echo $data['bkg_total_amount'] > 0 ? $data['bkg_total_amount'] : '0'; ?></td>
				<td align="right"><i class="fa fa-inr"></i><?php echo $data['bkg_vendor_amount'] > 0 ? $data['bkg_vendor_amount'] : '0'; ?></td>
				<td align="right"><i class="fa fa-inr"></i><?php echo $data['bkg_gozo_amount'] > 0 ? $data['bkg_gozo_amount'] : '0'; ?></td>
				<td align="right"><i class="fa fa-inr"></i><?php echo $data['bkg_service_tax'] > 0 ? $data['bkg_service_tax'] : '0'; ?></td>
			</tr>
			<?php
			$ctr			 = ($ctr + 1);
		}
		$tdsAmount	 = ($sumVendorAmount * 1 / 100);
		$payAmount	 = ($sumCommission + $sumServiceTax + $tdsAmount);
		?>
		<tr>
			<td colspan="6">&nbsp;</td>
			<td class="text-left"><b>Commission</b></td>
			<td class="text-right"><i class="fa fa-inr"></i><?php echo $sumCommission > 0 ? number_format($sumCommission, 2) : '0'; ?></td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
			<td class="text-left"><b>GST</b></td>
			<td class="text-right"><i class="fa fa-inr"></i><?php echo $sumServiceTax > 0 ? number_format($sumServiceTax, 2) : '0'; ?></td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
			<td class="text-left"><b>TDS @1%</b></td>
			<td class="text-right"><i class="fa fa-inr"></i><?php echo $tdsAmount > 0 ? $tdsAmount : '0'; ?></td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
			<td class="text-left"><b>Payable Amount</b></td>
			<td class="text-right"><i class="fa fa-inr"></i><?php echo $payAmount > 0 ? number_format($payAmount, 2) : '0'; ?></td>
		</tr>
		<?php
	}
	else
	{
		?>
		<tr><td colspan="8">No Records Yet Found.</td></tr>        
	<?php }
	?>
</table>