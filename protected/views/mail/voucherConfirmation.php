<?php
$msg		 .= 'Dear ' . $arr->vor_name . ',<br/><br/>Thank you for shopping with us. Your Digital purchase Order Number is ' . $arr->vor_number;
$msg		 .= '<table width="100%" border="1">
		<tr>
			<td align="left" valign="top"><b>Item</b></td>
			<td align="left" valign="top"><b>Description</b></td>
			<td align="center" valign="top"><b>Quantity</b></td>
			<td align="center" valign="top"><b>Price/Qty</b></td>
			<td align="center" valign="top"><b>Total</b></td>
		</tr>';
$totalAmount = 0;
foreach ($arr->voucherOrderDetails as $orderDetails)
{
	$msg	.= '<tr>
				<td align="left" valign="top">' . $orderDetails->vodVch->vch_code . ' - ' . $orderDetails->vodVch->vch_title . '</td>
				<td align="left" valign="top">' . $orderDetails->vodVch->vch_desc . '</td>
				<td align="center" valign="top">' . $orderDetails->vod_vch_qty . '</td>
				<td align="center" valign="top"> <span class="font-22">&#x20B9;' . $orderDetails->vodVch->vch_selling_price . '</span></td>
				<td align="center" valign="top"> <span class="font-22">&#x20B9;' . $orderDetails->vod_vch_price . '</span></td>
			</tr>';
	$totalAmount = $totalAmount + $orderDetails->vod_vch_price;
}
$msg .= '</table><br/><br/>';
$msg .= 'Grand Total : <span class="font-22">&#x20B9;' . $totalAmount. '</span>';
$msg .= '<br/><br/>Payment Received : <span class="font-22">&#x20B9;' . $arr->vor_total_price. '</span>';

$msg .= '<div>
			<div><p><b>Billing Information</b>:</p></div>
			<div>
				<div><b>Full name</b> : &nbsp; ' . $arr->vor_bill_fullname . ' </div>					
			</div>
			<div>
				<div><b>Email</b> : &nbsp; ' . $arr->vor_bill_email . '</div>					
			</div>		
			<div>
				<div><b>Phone</b> : &nbsp; ' . $arr->vor_bill_contact . '</div>					
			</div>	
			<div>
				<div><b>State</b> : &nbsp; ' . $arr->vor_bill_state . '</div>					
			</div>	
			<div>
				<div><b>City</b> : &nbsp; ' . $arr->vor_bill_city . '</div>					
			</div>	
			<div>
				<div><b>Postal Code</b> : &nbsp; ' . $arr->vor_bill_postalcode . '</div>					
			</div>	
		</div>';


$msg .= '<br/><br/>All digital purchases are delivered via individual emails for each item purchased.<br/>' .
		'We shall be sending your delivery confirmations soon.' .
		'<br/><br/>We hope to see you again soon.<br/>' .
		'<b>aaocab.com</b>';

echo $msg;
