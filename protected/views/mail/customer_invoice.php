Dear <?= $data['bkg_user_fname']; ?>&nbsp;<?= $data['bkg_user_lname']; ?>,
<?php
if (in_array($data['bkg_status'], [5, 6, 7]))
{
	// Completed customer booking
	?>
		<br/><br/>I hope this email finds you well. We wanted to express our gratitude for choosing our services and hope you had a pleasant experience. We greatly value your feedback and kindly request you to share your thoughts through a quick review. 
		<br/><br/>To begin, we have included a link below for you to access the invoice related to your recent car rental reservation. Please click on the link to review the invoice and ensure that all the details, including rental duration, charges, and any additional services, accurately reflect your reservation: 
		<br><br>Invoice Link : <?= $data['invoiceLink']; ?> 
		<br/><br/>We strive for transparency and want to ensure that the invoice aligns with your expectations. If you have any questions or concerns regarding the invoice or any other aspect of your car rental experience, please feel free to reach out to our dedicated support team at info@aaocab.com. We are here to assist you promptly and address any queries you may have. 
		<br/><br/>Your feedback is incredibly valuable to us, as it helps us enhance our services and provide even better experiences to our customers. We kindly request you to take a few moments to share your feedback. 
		<p style="text-align:center;"><b style="text-align: center;">How likely are you to recommend Gozo to your friends and family?</b></p>
		<?//= $data['payURL']; ?>
		<table border="0" align="center" cellpadding="0" cellspacing="15" style="font-family:Arial, Helvetica, sans-serif;">
				<tr>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=1" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>1</b></a></td>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=2" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>2</b></a></td>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=3" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>3</b></a></td>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=4" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>4</b></a></td>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=5" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>5</b></a></td>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=6" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>6</b></a></td>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#f9be03" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=7" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>7</b></a></td>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#f9be03" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=8" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>8</b></a></td>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#77ce1e" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=9" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>9</b></a></td>
					<td width="48" height="48" align="center" valign="middle" bgcolor="#77ce1e" style="border-radius:8px;"><a href="<?= $data['reviewlink'] ?>&val=10" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 16px;"><b>10</b></a></td>
				</tr>
				<tr>
					<td colspan="3" align="left" valign="middle" style="border-radius:8px;">Not at all Likely</td>
					<td colspan="4" align="center" valign="middle" style="border-radius:8px;">&nbsp;</td>
					<td colspan="3" align="right" valign="middle" style="border-radius:8px;">Extremely Likely</td>
				</tr>
			</table>
		<br/><br/>By following the review link, you will be directed to a brief survey where you can rate your overall satisfaction with our car rental service and provide any additional comments or suggestions you may have. Your honest feedback enables us to make continuous improvements and serve you and our valued customers better. 
		<br/><br/>Should you encounter any difficulties accessing the invoice link or have any questions about the review process, please do not hesitate to inform us. We will be more than happy to assist you promptly and ensure a smooth experience. 
		<br/><br/>Thank you again for choosing our car rental services. We genuinely appreciate your business and the trust you have placed in us. We eagerly look forward to receiving your valuable feedback and hope to serve you again in the future. 
	<?php
	}
	else if ($data['bkg_status'] == 9 && $data['bkg_reconfirm_flag'] != 0)
	{
	// Cancelled customer booking
	if ($data['useUserWallet'] == 1 && !in_array($data['bkg_cancel_id'], $data['reasonIds']))
	{
		?>

		<br/><br/>I hope this email finds you well. I am writing to provide you with the details regarding the cancellation charges, invoice, and refunds for your recent reservation. 
		<br/><br/>We understand that circumstances may change, leading to the need for reservation cancellations. As per our cancellation policy, the following charges apply to your cancelled reservation: 
		<br><br>Cancellation Date: <?= $data['cancellationDate']; ?>
		<br/><br/>Reservation Details:
		
		<table align="center" width="100%" style="border:#DFE4EE 1px solid; padding: 4px 10px; font-size: 14px; font-family: 'Arial'; line-height: 18px;">
			<tr style="background-color: rgb(229, 238, 255); padding: 5px;">
				<td align="center"><b>Booking Id</b></td>
				<td align="center"><b>Pickup date</b></td>
				<td align="center"><b>Cab type</b></td>
			</tr>
			<tr>
				<td align="center" style="border:#ddf0ff 1px solid;"><?= Filter::formatBookingId($data['bkg_booking_id']); ?></td>
				<td align="center" style="border:#ddf0ff 1px solid;"><?= $data['pickupDate']; ?></td>
				<td align="center" style="border:#ddf0ff 1px solid;"><?= $data['cabType'] ?></td>
			</tr>
		</table>
		<br/>Cancellation Charges: ₹<?= $data['cancellationCharge']; ?>
				
		<br/>Invoice Link : <?= $data['invoiceLink']; ?>
		 <br/><br/>Please find attached the invoice link for the cancellation charges mentioned above. The invoice includes a breakdown of the charges and the total amount due. Kindly review the invoice to ensure its accuracy. If you have any questions or concerns regarding the cancellation charges or the invoice itself, please do not hesitate to contact our dedicated support team at info@aaocab.com. We are here to assist you and address any queries you may have. 
		
		<br/><br/>Now, regarding the refunds for your cancelled reservation, we want to assure you that we understand the importance of timely reimbursements. We have initiated the refund process, and the amount eligible for a refund is ₹<?= $data['bkg_refund_amount']; ?>. The refund will be credited back to the original payment method used for the reservation. 
		<br/><br/>Please note that the processing time for refunds may vary depending on your financial institution. Typically, it takes approximately 5-7 business days for the refund to reflect in your account. However, if you do not receive the refund within this time frame or have any concerns regarding the refund process, please let us know, and we will investigate the matter further. 
		<br/><br/>Once again, we apologize for any inconvenience caused by the cancellation charges. We appreciate your understanding of our cancellation policy, which allows us to manage our resources efficiently and provide the best possible service to all our customers. 
		<br/><br/>If you have any further questions or require additional assistance, please feel free to reach out to us. We are here to support you and ensure a smooth resolution of any concerns. 
		<br/><br/>Thank you for your understanding and cooperation. We value your business and hope to have the opportunity to serve you again in the future. 
		<?php
	}
	else if ($data['useUserWallet'] == 1 && in_array($data['bkg_cancel_id'], [21]))
	{
		?>
		<br/><br/>Our driver has been trying to locate you for sometime. He has now marked this booking as customer not reachable.
		Please contact the driver immediately as this booking is at risk of cancellation at your cost
		<?php
	}
	else
	{
		?>

		<BR><br> We were unable to allocate a car or driver that meets the requirements for the trip related to your booking <?= Filter::formatBookingId($data['bkg_booking_id']); ?>. 
		Please contact us so we can help make adjustments to your trip plan and allocate a car. Please consider this booking order as cancelled by Gozo.
		<BR>Our team will be getting in touch with you shortly however we wanted to inform you as quickly as we know that a vehicle or driver cannot be allocated.
		<br/><br/><B>What needs to happen next?</b>
		<BR>Our goal is to avoid & minimize any inconveniences to your planned trip. If you have any flexibility in your travel plans and are open to travel at a different time or in a different category of car. 
		Please get in touch with our customer service team so we can help me alternate travel arrangements for you.

		<br/><br/>Your Cancellation for your booking <?= Filter::formatBookingId($data['bkg_booking_id']); ?> has been processed on our end as follows:
		<?php
		if ($data['cabDriverShow'] != 1)
		{
			?>
			<br/>Amount you paid: ₹<?= $data['bkg_advance_amount']; ?>
			<br/>Total Refund: ₹<?= $data['bkg_refund_amount']; ?>
			<br/>As per terms and conditions and eligibility, refund for this booking shall be processed within 7 business days.
			<?php
		}
		?>
		<br/><br/>We thank you for for choosing GozoCabs as your travel partner. You may please click on the following link to access the Invoice for your last trip with us <?= $data['invoiceLink'] ?> . We hope to see you travel with us again. 
		<br/><br/>If the booking is eligible for a refund, you shall receive further communication about refund eligibility and transaction details (if eligible for refund) within 7 business days. Should you have any questions about your booking, you may email us at info@aaocab.communication
		<?php
	}
}
?>
<br/><br/>Thanks,
<br/>Team Gozo
<br/><br/><a href="http://www.aaocab.com/refer-friend/"><img src="http://www.aaocab.com/images/refer_friend_email.jpg"/></a>