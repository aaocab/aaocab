<?php
$msg = "";
$url = Yii::app()->getBaseUrl(true);
$msg = 'Dear ' . $data['userName'] .
        ',<br/><br/>Thanks for traveling with Gozocabs ( Booking ID : ' . $data['bookingId'] . '). We would love to hear your feedback on how we did.
        <br/><br/>Please tell us what you think about the car, driver, our customer service and any suggestion you have for us to do better. 
        <p style="text-align:center;"><b style="text-align: center;">How likely are you to recommend Gozo to your friends and family?</b></p>
		<table border="0" align="center" cellpadding="0" cellspacing="15" style="font-family:Arial, Helvetica, sans-serif;">
			<tr>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=1" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>1</b></a></td>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=2" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>2</b></a></td>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=3" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>3</b></a></td>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=4" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>4</b></a></td>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=5" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>5</b></a></td>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#ff7575" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=6" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>6</b></a></td>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#f9be03" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=7" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>7</b></a></td>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#f9be03" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=8" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>8</b></a></td>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#77ce1e" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=9" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 22px;"><b>9</b></a></td>
				<td width="48" height="48" align="center" valign="middle" bgcolor="#77ce1e" style="border-radius:8px;"><a href="'. $data['reviewlink'].'&val=10" style="color:#fff; font-size:18px; text-decoration:none; padding:15px 16px;"><b>10</b></a></td>
			</tr>
			<tr>
				<td colspan="3" align="left" valign="middle" style="border-radius:8px;">Not at all Likely</td>
				<td colspan="4" align="center" valign="middle" style="border-radius:8px;">&nbsp;</td>
				<td colspan="3" align="right" valign="middle" style="border-radius:8px;">Extremely Likely</td>
			</tr>
		</table>'.

        '<br/><br/><a href="https://www.gozocabs.com/refer-friend/"><img src="https://www.gozocabs.com/images/refer_friend_email.jpg"/></a>'.
        '<br/><br/>Regards,' .
        '<br/>Gozocabs Support' .
        '<br/>+91-90518-77-000';
return $msg;
