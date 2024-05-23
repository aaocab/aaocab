<?php
$hash  = Yii::app()->shortHash->hash($invoiceList['bkg_id']);
?>
<div style="font-size: 11px ;padding-top: 10px; line-height: 1.3em;width:100%;">
	<div style="width: 50%;float: left">
		<b>Traveller Information:</b><br/>
		<b>Name: </b><?php echo ucfirst($invoiceList['bkg_user_fname']) . ' ' . ucfirst($invoiceList['bkg_user_lname']); ?>,</b>
		<?php
		if ($invoiceList['bkg_contact_no'] != '')
		{
			?>
			<br/><b>Phone: </b><?php echo '+' . $invoiceList['bkg_alt_country_code'] . $invoiceList['bkg_contact_no']; ?>
			<?php
		}
		if ($invoiceList['bkg_user_email'] != '')
		{
			?>
			<br/><b>Email: </b><?php echo $invoiceList['bkg_user_email']; ?>
			<?php 
		}
		?>
	</div>
    <div class="text-right"  style="width:50%;float: right;">
		<b>Invoice #:</b><span style="text-transform: uppercase "><?php echo date('ym', strtotime($invoiceList['bkg_pickup_date'])) . '/' . $hash ?></span><br>
		<b>Generated on: </b> <?php echo date('M d, Y'); ?>
	</div>
</div>
<br/>
<div style="width:100%;float: left ;font-size: 11px;line-height: 1.3em">
	<div style="width: 75%;float: left">
	<b>Booked On:</b> <br/>
	<b>Company Name: </b><?php echo ucfirst($invoiceList['agt_cmpny']); ?><br/>
	<b>Address: </b><?php echo (trim($invoiceList['agt_address']) != '') ? trim($invoiceList['agt_address']) : "--" ?><br/>
	<b>GSTIN: </b><?php echo (trim($invoiceList['agt_gstin']) != '') ? $invoiceList['agt_gstin'] : "--"; ?><br/>
	<b>Your Reference ID: </b>
	<?php echo (trim($invoiceList['bkg_agent_ref_code']) != '') ? $invoiceList['bkg_agent_ref_code'] : "--";?>
	</div>
</div>
