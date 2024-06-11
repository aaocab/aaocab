<?php
if ($openingAmount['vendor_amount'] > 0)
{
	$openingBalance = $openingAmount['vendor_amount'];
}
else if ($openingAmount['vendor_amount'] < 0)
{
	$openingBalance = $openingAmount['vendor_amount'];
}
else
{
	$openingBalance = 0;
}
$bookingAmount	 = ($dataList['total_amount'] > 0) ? $dataList['total_amount'] : '0';
$baseAmount		 = ($dataList['base_amount'] > 0) ? $dataList['base_amount'] : '0';
$vendorAmount	 = ($dataList['vendor_amount'] > 0) ? $dataList['vendor_amount'] : '0';


if ($dataList['service_charge_amount'] > 0)
{
	$serviceChargeAmount = $dataList['service_charge_amount'];
}
else if ($dataList['service_charge_amount'] < 0)
{
	$serviceChargeAmount = $dataList['service_charge_amount'];
}
else
{
	$serviceChargeAmount = '0';
}
$serviceTaxAmount	 = ($dataList['service_tax_amount'] > 0) ? $dataList['service_tax_amount'] : '0';
//$tdsAmount = ($dataList['total_tds_amount'] > 0) ? $dataList['total_tds_amount'] : '0';
$totalAmount		 = ($serviceChargeAmount + $serviceTaxAmount);
$paymentAdjustment	 = $adjustAmount['adjust_amount'];
$amountDue			 = ($openingBalance + $totalAmount + $paymentAdjustment);
?>
<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
    <tbody>
        <tr>
            <td colspan="2" style="text-align: center;background-color: #e5e5e5; font-size: 14pt; width: 100% !important; padding: 5x; border: solid 1px #000;">INVOICE</td>
        </tr>
        <tr>
            <td colspan="2" >&nbsp;</td>
        <tr>
            <td valign="top" width="275" class="leftHeader"><table border="0" cellpadding="3" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td valign="top" rowspan="2" class="label" style="white-space: nowrap" width="78">Addressed to</td>
                            <td valign="top" width="165"><?= ($record['ctt_user_type']==1)?$record['ctt_first_name'].' '.$record['ctt_last_name']:$record['ctt_business_name']; ?></td>
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
                </table></td>
            <td valign="top" class="rightHeader"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 0px;">
                    <tbody>
                        <tr>
                            <td valign="top" class="" width="120"><strong>Invoice No :</strong> </td>
                            <td valign="top" class="" width="153" style="text-align: left"></td>
                        </tr>
                        <tr>
                            <td valign="top" class="" width="120"><b>Date :</b></td>
                            <td valign="top" class="" width="153" style="text-align: left;"><b><?php echo date('d/m/Y', strtotime(date('Y-m-d'))); ?></b></td>
                        </tr>
                        <tr>
                            <td valign="top" class="" width="120"><b>PAN No :</b></td>
                            <td valign="top" class="" width="153" style="text-align: left;"><b>AAFCG0222J</b></td>
                        </tr>
                        <tr>
                            <td valign="top" class="" width="120"><b>GSTIN :</b></td>
                            <td valign="top" class="" width="153" style="text-align: left;"><b>AAFCG0222JSD001</b></td>
                        </tr>
                    </tbody>
                </table></td>
        </tr>

    </tbody>


</table>


<table border="1" cellpadding="5" cellspacing="0" width="702" style="margin-bottom: 0px; margin-top: 15px;" class="no-border header">
    <tbody>
        <tr>
            <td valign="top" class="" width="25%"><strong>Outstanding Balance as on <?= $fromDate; ?></strong> </td>
            <td valign="top" class="" width="25%" style="text-align: left"><strong>Amount for the period</strong></td>
            <td valign="top" class="" width="25%" style="text-align: left"><strong>Payments adjustment during the period</strong> </td>
            <td valign="top" class="" width="25%" style="text-align: left"><strong>Outstanding balance as on <?= $toDate; ?></strong></td>
        </tr>
        <tr>
            <td style="text-align: right;"><?= number_format($openingBalance, 2) ?></td>
            <td style="text-align: right;"><?= number_format($totalAmount, 2) ?></td>
            <td style="text-align: right;"><?= number_format($paymentAdjustment, 2); ?></td>
            <td style="text-align: right;"><?= number_format($amountDue, 2); ?></td>
        </tr>
    </tbody>
</table>

<table class="invoice_box">
    <tr class="blue2 white-color">
        <td colspan="3"><b>Particulars</b></td>
    </tr>  
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Period</b></td>

        <td style="width: 60%"><?php echo $fromDate . " - " . $toDate ?></td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Opening Balance as on <?= $fromDate; ?> (Rs.)</b></td>
        <td style="width: 60%"><i class="fa fa-inr"><?= number_format($openingBalance, 2); ?></td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Number Of Trips</b></td>
        <td style="width: 60%"><?= $dataList['total_booking']; ?></td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Booking Amount (Rs.)</b></td>
        <td style="width: 60%"><i class="fa fa-inr"><?= number_format($bookingAmount, 2); ?></i></td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Base Amount (Rs.)</b></td>
        <td style="width: 60%"><i class="fa fa-inr"><?= number_format($baseAmount, 2); ?></i></td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Vendor Amount (Rs.)</b></td>
        <td style="width: 60%"><i class="fa fa-inr"><?= number_format($vendorAmount, 2); ?></i></td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Gozo Service Charge (Rs.) (A)</b></td>
        <td style="width: 60%"><i class="fa fa-inr"></i><?= number_format($serviceChargeAmount, 2); ?> 
        </td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>GST (  On Base Amount) (Rs.) (B)</b></td>
        <td style="width: 60%"><i class="fa fa-inr"></i><?= number_format($serviceTaxAmount, 2); ?></td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Total Amount (Rs.) (A+B+C)</b></td>
        <td style="width: 60%"><i class="fa fa-inr"></i><?= number_format($totalAmount, 2); ?></td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Total Amount In Words </b></td>
        <td style="width: 60%"><?php
			$objNum				 = new NumbersToWords();
			if ($totalAmount > 0)
			{
				echo "Rupees ";
				echo $objNum->convertToWords($totalAmount);
			}
			else
			{
				echo "NIL";
			}
			?></td>
    </tr>
    <tr class="blue2 white-color">
        <td style="width: 40%"><b>Amount Due (Rs.)</b></td>
        <td style="width: 60%"><i class="fa fa-inr"></i><?= number_format($amountDue, 2); ?></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="no-border" style="margin-top: 10px">
    <tbody>
        <tr>
            <td colspan="" style="text-align: center; font-size: 9pt; width: 100% !important; padding: 5x; border: solid 1px #000;"> For all account related queries write to accounts@aaocab.in<br>
                This is a system generated invoice and requires no signature. </td>
        </tr>
    </tbody>
</table>
