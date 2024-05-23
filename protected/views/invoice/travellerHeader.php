<?php
$hash  = Yii::app()->shortHash->hash($invoiceList['bkg_id']);
?>
<div style="width:100%;float: left ;padding-top: 10px; font-size: 11px;line-height: 1.3em">
	<div style="width: 75%;float: left">
		<b>Billing Information:</b><br/>
		<b>Name: </b><?= ucfirst($invoiceList['bkg_user_fname']) . ' ' . ucfirst($invoiceList['bkg_user_lname']); ?>,</b>
		<?php
		if ($invoiceList['bkg_contact_no'] != '')
		{
			?>
			<br/><b>Phone: </b><?= '+' . $invoiceList['bkg_alt_country_code'] . $invoiceList['bkg_contact_no']; ?>
		<? } ?>
		<?php
		if ($invoiceList['bkg_user_email'] != '')
		{
			?>
			<br/><b>Email: </b><?= $invoiceList['bkg_user_email']; ?>
		<? } ?>
		<br/><b>Booking made through : </b>&nbsp;<?php echo $invoiceList['agt_cmpny'];?>

	</div>
	<div class="text-right "  style="width:25%;float: left">
		<b>Invoice #:</b><span style="text-transform: uppercase "><?= date('ym', strtotime($invoiceList['bkg_pickup_date'])) . '/' . $hash ?></span><br>
		<b>Generated on: </b> <?= date('M d, Y'); ?>
	</div>
	
</div>