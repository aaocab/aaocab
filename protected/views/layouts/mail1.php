<?php
if (!isset($email_receipient) && !isset($this->$email_receipient))
{
	$email_receipient = $data['email_receipient'];
}
if ($data['userId'] != '')
{
	$userId = $data['userId'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Gozo Cabs</title>

    </head>

    <body>
        <table width="640" align="center" cellpadding="15" cellspacing="0"  bgcolor="#DDF5FF" style="font-family: 'Arial'; font-size: 14px; word-wrap: break-word; min-width: 360px;width: 640px;max-width: 640px; border: rgb(221, 245, 255) 1px solid;">
			<tr>
				<td valign="middle">
					<table width="100%" align="center">
						<tr>
							<td align="left" valign="middle">
								<img src="http://aaocab.com/images/gozo-loog-mail.png" alt="Gozocabs"/>

							</td>
							<td align="right" valign="middle">
								<a href="http://www.aaocab.com/booking/list" style="color: #0279E8; line-height: 22px;">My Booking</a> | <a href="http://www.aaocab.com/users/view" style="color: #0279E8;">My Profile</a> | <a href="http://www.aaocab.com/" style="color: #0279E8;">www.aaocab.com</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#fff" style="background: rgb(255, 255, 255);">
					<?= $content ?>
				</td>
			</tr>
			<tr>
				<td>
					<p style="color: #515151; font-size:12px; font-weight:300; text-align:center; padding:0; margin:0;">
						This email is sent to <?= $email_receipient ?> you because you have used this email address to register with Gozocabs.
						<!--                        If you haven't done so, please ignore this email.-->
						If you received this email in error or do not wish to receive any further communications, please <a href="http://www.aaocab.com/index/unsubscribeemail/hash/<?= Yii::app()->shortHash->hash($userId) ?>/email/<?= $email_receipient ?>" target="_BLANK">unsubscribe here.</a>
						<a href="http://aaocab.com/" style="color: #3d4f99;">Gozocabs</a> and the Gozocabs logo is a copyright of <br/><b>Gozo Technologies Pvt. Ltd.</b>
					</p>
				</td>
			</tr>
		</table>
    </body>
</html>