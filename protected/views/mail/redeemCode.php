<?php
$msg = "";
$msg .= '<p><b>Gozo Cabs Digital order confirmation & delivery</b></p><br>';
$msg .= 'Thank you for ordering '.$arr->vsbVch->vch_title.' at aaocab.com.<br>';
$msg .= 'Your digital product is now delivered. If you ordered multiple items, each will be delivered in its own email.';
$msg .= '<br><br>';	

$msg .= 'Use the code below to apply this voucher to your Gozo account. Read the description clearly for additional terms & conditions of use that apply to your digital purchase.';
$msg .= '<br><br>';

$msg .= '<table width="100%" border="1">
		<tr>
			<td align="left" valign="top">Title</td>
			<td align="left" valign="top">Description</td>
			<td align="center" valign="top">Redemption Code</td>
		</tr>';

$msg .= '<tr>
				<td align="left" valign="top">' . $arr->vsbVch->vch_title . '</td>
				<td align="left" valign="top">' . $arr->vsbVch->vch_desc . '</td>
				<td align="center" valign="top">' . $arr->vsb_redeem_code . '</td>
			</tr></table><br><br>';
$msg	 .= 'We hope to see you again soon!';
$msg	 .= '<br><b>aaocab.com</b>';
echo $msg;
