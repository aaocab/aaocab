<?php
$baseAmount					 = $invoiceList['bkg_base_amount'];
$sTax						 = $invoiceList['bkg_service_tax'];
$sTax2						 = round(($sTax * 0.2) / 6);
$sTax1						 = $sTax - (2 * $sTax2);
$dANightPickupStr			 = ( $invoiceList['bkg_night_pickup_included'] == 1) ? "Included" : "Excluded";
$dANightDropStr				 = ( $invoiceList['bkg_night_drop_included'] == 1) ? "Included" : "Excluded";
$totalCancellationCharges	 = $invoiceList['bkg_cancel_charge'] + $invoiceList['bkg_cancel_gst'];

if ($totalCancellationCharges > 0)
{
	$numberToWords = "Rupees " . ucwords(Filter::convertNumberToWord($totalCancellationCharges)) . " only";
}
else
{
	$numberToWords = "Rupees zero only";
}
?>
<div style="width:100%;float: left ;padding-top: 10px; font-size: 11px;line-height: 1.3em">
<div class="col-xs-12 p0 pt10">
		<table class="table table-bordered mb0" width="100%">
			<tr>
				<td colspan="2"><b>Journey Details</b></td>
			</tr>
			<tr>
				<td>Customer Name</td>
				<td class="text-left"><?= ucfirst($invoiceList['bkg_user_fname']) . ' ' . ucfirst($invoiceList['bkg_user_lname']); ?></td>
			</tr>
			<?php if($invoiceList['agt_cmpny']){ ?>
			<tr>
				<td>Company Name</td>
				<td class="text-left"><?= ucfirst($invoiceList['agt_cmpny']); ?></td>
			</tr>
			<?php } 
			if($invoiceList['agt_address']){
			?>
			<tr>
				<td>Address</td>
				<td class="text-left"><?= (trim($invoiceList['agt_address']) != '') ? trim($invoiceList['agt_address']) : "--" ?></td>
			</tr>
			<?php } 
			 if($invoiceList['agt_gstin']){ ?>
			<tr>
				<td>GSTIN</td>
				<td class="text-left"><?= (trim($invoiceList['agt_gstin']) != '') ? $invoiceList['agt_gstin'] : "--"; ?></td>
			</tr>
			<?php } ?>
			<tr>
				<td>Route</td>
				<td class="text-left"><?php echo $invoiceList['route']; ?></td>
			</tr>
			<tr>
				<td>Order Number</td>
				<td class="text-left"><?php echo Filter::formatBookingId($invoiceList['bkg_booking_id']); ?></td>
			</tr>
			<tr>
				<td>Trip Status</td>
				<td class="text-left"><?php echo Booking::model()->getBookingStatus($invoiceList['bkg_status']); ?></td>
			</tr>
			<tr>
				<td>Scheduled date and time of journey </td>
				<td class="text-left"><?php echo date('jS M Y (D) g:i a', strtotime($invoiceList['bkg_pickup_date'])); ?></td>
			</tr>
            <tr>
				<td>Date and time of Cancellation</td>
				<td class="text-left"><?php echo date('jS M Y (D) g:i a', strtotime($invoiceList['btr_cancel_date'])); ?></td>
			</tr>
		</table>
	</div>
</div>

<div class="row mt10" style="margin-right: 0; margin-left: 0;">
	<div style=" <?= $pdfCss1 ?>">
		<table class="table table-bordered mb10" width="100%">
			<tr>
				<td class="text-right" width="30%"><b>Particulars</b></td>
				<td class="text-right"><b>Amount (Rs.)</b></td>
			</tr>
			<tr>
				<td class="text-right">Advance received </td> 
				<td class="text-right"><i class="fa fa-inr"></i>  <?php echo number_format($invoiceList['bkg_advance_amount']) ?></td>
			</tr>
			<tr>
				<td class="text-right">Refund amount  </td> 
				<td class="text-right"><i class="fa fa-inr"></i>  <?php echo number_format($invoiceList['bkg_refund_amount']); ?></td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="text-right">Cancellation Charge  </td> 
				<td class="text-right"><i class="fa fa-inr"></i>  <?php echo number_format($invoiceList['bkg_cancel_charge']); ?></td>
			</tr>
			<?php
			if ($invoiceList['bkg_sgst'] > 0)
			{
			?>
				<tr>
					<td class="text-right">SGST @ <?= Yii::app()->params['sgst']; ?>%</td>
					<td class="text-right"><i class="fa fa-inr"></i> <?php echo ((Yii::app()->params['sgst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
				</tr>
			<?php 
			}
			if ($invoiceList['bkg_cgst'] > 0)
			{
				?>
				<tr>
					<td class="text-right">CGST @ <?= Yii::app()->params['cgst']; ?>%</td>
					<td class="text-right"><i class="fa fa-inr"></i> <?php echo ((Yii::app()->params['cgst'] / $invoiceList['bkg_service_tax_rate']) * $sTax) ?></td>
				</tr>
			<?php 
			}  
			if ($invoiceList['bkg_igst'] > 0)
			{
			?>
			<tr>
				<td class="text-right">IGST  @ <?php echo Yii::app()->params['igst']; ?>%</td> 
				<td class="text-right"><?php echo number_format($invoiceList['bkg_cancel_gst']); ?></td>
			</tr>
			<?php 
			}?>
			<tr>
				<td class="text-right">Total Cancellation Charges </td> 
				<td class="text-right"><i class="fa fa-inr"></i> <?php echo number_format($totalCancellationCharges) ?></td>
			</tr>
			<tr>
				<td class="text-left">&nbsp;Total Amount (In Words) : &nbsp;</td>
				<td><?php echo $numberToWords ?></td>
			</tr>
		</table>
	</div>
</div>