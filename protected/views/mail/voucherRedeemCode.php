<?php

$msg = "";

$msg .= '<p><b>Gozo Cabs Digital order confirmation & delivery</b></p>';
$msg .= 'Thank you for ordering '.$arr['voucherTitle'].' at aaocab.com.<br/> Your digital product is now delivered. If you ordered multiple items, each will be delivered in its own email'.

$msg .= '<br/><br/>Use the code below to apply this voucher to your Gozo account. Read the description clearly for additional terms & conditions of use that apply to your digital purchase';


$msg .= '<br/><br/><div><br/><table width="100%" border="0" cellpadding="0" cellspacing="0">';	
$msg.= '	<tr>
				<td align="center" valign="top"><b>Title</b><td>
				<td align="center" valign="top"><b>Description</b><td>
				<td align="center" valign="top"><b>Redemption Code</b></td>
			</tr>';
$msg .= '<tr>
				<td align="center" valign="top">'.$arr['voucherTitle'].'<td>
				<td align="center" valign="top">'.$arr['voucherDesc'].'<td>
				<td align="center" valign="top">'.$arr['redeemCode'].'</td>
			</tr>';
$msg .= '</table>';

$msg .= 'We hope to see you again soon!';
$msg .= '<br/><b>aaocab.com</b>';
echo $msg;
			