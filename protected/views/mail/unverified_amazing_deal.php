<?php
$msg = '<table align="center" width="100%" bgcolor="#fff">
			<tr>
				<td>
					<table width="800" bgcolor="#fff" align="center" style="font-family:"Arial"; font-size: 13px; color: #000; padding: 20px;" cellpadding="0" cellspacing="0">
						<tr>
							<td align="left" valign="top">We noted that you have still not confirmed your plans for travel from ' . $arr['from_city'] . ' to ' . $arr['to_city'] . ' on ' . $arr['deal_pickup_date'] . '. Prices have gone up a lot since we gave you your original quote.</td>
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td align="left" valign="top">Today, We found an amazing and highly discounted price for your trip.<br>
								You can travel from ' . $arr['from_city'] . ' to ' . $arr['to_city'] . ' for a base fare of ₹' . $arr['deal_base_fare'] . ' IF YOU ACT NOW and make payment before '.LeadFollowup::showTravelDate($arr['bkg_id']).'.<br>
								Click one of the buttons below and let us know how you want to proced.
							</td>
						</tr>
						<tr>
							<td align="left" valign="top" style="padding: 10px 0;" margin-left:15px;>
								<a href="' . $arr['url'] . '" target="_blank" style="background: #f96408; color: #fff; font-weight: bold; text-decoration: none; padding: 10px 10px; line-height: 40px;">Yes, Im interested in booking now</a>
								<a href="' . $arr['url'] . '" target="_blank" style="background: #f96408; color: #fff; font-weight: bold; text-decoration: none; padding: 10px 10px; line-height: 40px;">NO, Im not interested</a>
							</td>
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td>
								Thank you,<br>
								<span style="font-size: 18px; font-weight: bold;">Team Gozo</span>
							</td>
						</tr>

					</table>
				</td>
			</tr>
		</table>';
 echo $msg;