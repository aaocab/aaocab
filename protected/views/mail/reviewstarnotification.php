<table style="width:98%;border: #d4d4d4 0px solid;border-collapse: collapse; font-family: 'Arial'; font-size: 13px; padding: 5px; color: #000;"  bgcolor="#fff" align="center" cellpadding="5" cellspacing="0">
    <tr>
        <td><p><strong style="font-size:16px;">
					Dear <?= $arr['user']; ?>,</strong></p>
            <p>Thanks for giving us feedback on your trip. We have awarded a small bonus to your driver <?= $arr['driver_name']; ?> for his good service.</p>
			<p>Gozo is a young company and WE DEPEND ON YOU to tell the world about our service. We are fighting fake reviews on social media and need real customers like you to share their opinion</p>
			<p>Please take less than 1 minute to review us on social media below. Use the one-click buttons below to quickly write your review…<br>
				You can directly copy the text below and paste into the review:
			</p>
			<div class="p15" style="border:1px solid #ddd; border-radius:10px; padding: 15px;">
				I just traveled with Gozo Cabs and I loved it. I just wrote my review on GozoCabs. <a href="<?= $arr['reviewLink']; ?>" target="_blank"><?= $arr['reviewLink']; ?></a> <br>
				<?= $arr['comment']; ?> <br><br>
				Join Gozo and you will get 10% off on your next trip. This link will also give you a discount for a future trip.<br>
				Join by clicking <a href="<?php echo $arr['inviteLink']; ?>" target="_blank"><?php echo $arr['inviteLink']; ?></a><br>
			</div>
			<p>&nbsp;</p>
			<table width="100%">
				<tr>
					<td><a href="<?= $arr['googleShareLink']; ?>" target="_blank"><img src="https://aaocab.com/images/review_google.png" alt="Review on Google" style="width:84px;"></a></td>
					<td><a href="<?= $arr['tripAdviserLink']; ?>" target="_blank"><img src="https://aaocab.com/images/review_trip.png" alt="Review on Tripadvisor" style="width:148px;"></a></td>
					<td><a href="<?= $arr['whatappShareLink']; ?>" target="_blank"><img src="https://aaocab.com/images/review_whatsapp.png" alt="Review on Whatsapp" style="width:111px;"></a></td>
					<td><a href='MAILTO:?subject=Gozo Referral&body=<?= $arr['mailBody']?>'><img src="https://aaocab.com/images/email-icon.png" alt="Send Mail" width="96px;"></a></td>
					<td><a href="<?= Yii::app()->createAbsoluteUrl('users/fbShareLink', ['refcode' => $arr['usr_refer_code'], 'hash' => $arr['hash'], 'id' => $arr['bkg_id']]); ?>" target="_blank" class="social-1" rel="nofollow">
							<img src="https://aaocab.com/images/facebook_share.png" width="116" alt="Share On Facebook" >
						</a></td>
				</tr>
			</table>
			<p>&nbsp;</p>
			<p>
				Thank you,<br>
				<b>Gozo family</b>
			</p>
        </td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>