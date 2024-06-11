<?php

$msg = '<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
                <table width="640" align="center" cellpadding="15" cellspacing="0" bgcolor="#fff" style="font-family: arial; font-size: 14px; word-wrap: break-word; min-width: 360px;width: 640px;max-width: 640px; border: rgb(221, 245, 255) 1px solid;">
	<tr>
	<td align="center">
	<span style="background-color: rgb(76, 205, 116); color: #fff; font-weight: bold; border-radius: 50px; font-size: 14px; padding: 5px 10px; margin: 0 auto;">
	Quotation is about to expire
	</span>
	</td>
	</tr>
<tr>
<td>Hi '. $arr['user_name'] .',</td>
</tr>
<tr>
<td align="left" valign="middle">We wanted to remind you that the quotation for the cab service you requested is about to expire on <b>'. $arr['expire_on'] .'</b>. If you are still interested in booking the cab, please find the details below:</td>
</tr>

	<tr><td style="padding-bottom:0;"><span><b>Quotation Details:</b></span></td></tr>           
	<tr>
	<td>
	<table width="100%" style="border: #DFE4EE 1px solid; margin-bottom: 30px; padding: 15px; margin: 0; margin-bottom: 5px; color: #000; font-size: 14px; font-family: arial; line-height: 18px;">
	<tr>
	<td align="left" width="50%" valign="middle"><span style="color: #858585; padding-right: 5px;">Quote Id: </span><p style="margin-top:0;"><b>'. $arr['booking_id'] .'</b></p></td>
	<td align="left" valign="middle"><span style="color: #858585; padding-right: 5px;">Trip Type: </span><p style="margin-top:0;"><b>'. $arr['trip_type'] .'</b></p></td>
	</tr>
	<tr><td>&nbsp;</td></tr>  
	<tr>
	<td align="left" width="50%" valign="middle"><span style="color: #858585; padding-right: 5px;">Cab Type: </span><p style="margin-top:0;"><b>'. $arr['cab_type'] .'</b></p></td>
	<td align="left" valign="middle"><span style="color: #858585; padding-right: 5px;">Pickup Time: </span><p style="margin-top:0;"><b>'. $arr['pickup_time'] .'</b></p></td>
	</tr>
	<tr><td>&nbsp;</td></tr>  
	<tr>
	<td align="left" width="50%" valign="middle"><span style="color: #858585; padding-right: 5px;">Pickup Location: </span><p style="margin-top:0;">'. $arr['pickup_location'] .'</p></td>
	<td align="left" valign="middle"><span style="color: #858585; padding-right: 5px;">Drop-off Location: </span><p style="margin-top:0;">'. $arr['dropoff_location'] .'</p></td>
	</tr>
	<tr><td>&nbsp;</td></tr>  
	<tr>
	<td align="left" width="50%" valign="middle"><span style="color: #858585; padding-right: 5px;">Total Fare: </span><p style="margin-top:0;"><b>&#x20b9;'. $arr['total_fare'] .'</b></p></td>
	<td align="left" valign="middle"><span style="color: #858585; padding-right: 5px;">Min Payment: </span><p style="margin-top:0;"><b>&#x20b9;'. $arr['min_payment'] .'</b></p></td>
	</tr>
</table>
</td>
</tr>
        
	<tr>
	<td>
	<p>To confirm your booking and secure the quoted rate, you can make the minimum payment of &#x20b9;'. $arr['min_payment'] .' by clicking on the following secure payment link: <a href="'.$arr['url'].'" target="_blank"> pay now </a></p>
	<p>Please note that this payment link is valid until '. $arr['expire_on'] .'. After that, the quoted rate and availability may change.</p>
	<p>If you have any questions or need further assistance, please do not hesitate to contact our customer support at <a href="tel:'.$arr['contact_us'].'">'. $arr['contact_us'] .'.</a></p>
	<p>We look forward to serving you and providing a comfortable cab service for your journey. Thank you for considering us!</p>
	<p>Team Gozocabs</b></p>
	</td>
	</tr>           
<tr>
<td align="center" valign="middle">
<a href="http://www.aaocab.com/refer-friend/"><img src="http://www.aaocab.com/images/refer-a-friend.jpg"/></a>
</td>
</tr>         

        </table>
</div>';
echo $msg;
