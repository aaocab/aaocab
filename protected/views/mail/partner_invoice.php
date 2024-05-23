<?php
if (in_array($data['bkg_status'], [5, 6, 7])) 
{
	// Completed partner booking
?>
	Dear Partner,
    <br>Please find the invoice having Booking ID <?=$data['booking_id'];?>
	<br>Invoice Link : <?= $data['invoiceLink'];?>
    <br><br>Your Refrence Id: <?= $data['agent_ref_code'];?>
<?php
}
else if($data['bkg_status']==9)
{
	// Cancelled partner booking
	if ($data['bkg_agent_id'] == 30228)
	{
		?>
		Dear <?= $data['bkg_user_fname']; ?>&nbsp;&nbsp;<?= $data['bkg_user_lname']; ?>,

		<br/><br/>Your Booking ID <?= Filter::formatBookingId($data['bkg_booking_id']); ?>. has been cancelled. No Worries!!!
		<br/>The cancellation request has been processed.
		<br/>Amount paid: ₹<?= $data['bkg_advance_amount']; ?>
		<br/>Cancellation Charges applicable: ₹<?= $data['cancellationCharge']; ?>
		<br/>Total Refund: ₹<?= $data['bkg_refund_amount']; ?>

		<br/><br/>You may please click on the following link to access the Invoice for your last trip with us <?= $data['invoiceLink'] ?>. We hope to see you travel with us again.

		<?php
	}
	else
	{
		?>
		<?= $data['agent_name']; ?>,
		<br/><br/><?= $data['agent_name']; ?> Booking ID <?= Filter::formatBookingId($data['bkg_booking_id']); ?> has been cancelled.
		<br/>The cancellation request has been processed.
		<br/>Amount paid: ₹<?= $data['bkg_advance_amount']; ?>
		<br/>Cancellation Charges applicable: ₹<?= $data['cancellationCharge']; ?>
		<br/>Total Refund: ₹<?= $data['bkg_refund_amount']; ?>

		<br/><br/>You may please click on the following link to access the Invoice for the above Booking <?= $data['invoiceLink'] ?>.
		<?php
	}	
}
?>
<br/><br/>Thanks,
<br>Regards, 
<br/>Team Gozo