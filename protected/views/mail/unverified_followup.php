<?php
if($arr['type']=='lead')
{
	$emailStartingText  = 'Hey! You left your reservation incomplete.';
	$emailPromoImg		= ($arr['confirm'] == 0)? '<a href="https://www.gozocabs.com" target="_black"><img src="http://gozocabs.com/images/email/save20.jpg" alt="Use SAVE20 Get 10% instant discount & 10% cashback as Gozo Coins" title="Use SAVE20 Get 10% instant discount & 10% cashback as Gozo Coins"></a>':'';
}
else if($arr['type']=='unverified')
{
	$emailStartingText  = 'We noticed you have not paid for your booking yet. Prices will be going up again soon.';
	$emailPromoImg = '';
}

$msg = '<table align="center" width="100%" bgcolor="#fff">
			<tr>
				<td>
					<table width="800" bgcolor="#fff" align="center" style="font-family:"Arial"; font-size: 13px; color: #000; padding: 20px;" cellpadding="0" cellspacing="0">
						<tr>
							<td align="left" valign="top">'.$emailStartingText.$emailPromoText.'<br><br>We want your business and would love to hear from you what we need to do better.</td>
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td align="left" valign="top">Please click one of the buttons below to tell us why?</td>
						</tr>
						<tr>
							<td align="left" valign="top" style="padding: 10px 0;" margin-left:15px;>
								<a href="' . $arr['url'] . '/r/1" target="_blank" style="background: #f96408; color: #fff; font-weight: bold; text-decoration: none; padding: 10px 10px; line-height: 40px;">The quoted price was too high</a>										
								<a href="' . $arr['url'] . '/r/4" target="_blank" style="background: #f96408; color: #fff; font-weight: bold; text-decoration: none; padding: 10px 10px; line-height: 40px;">I have special requirements and need a specialist to call me</a>
								<a href="' . $arr['url'] . '/r/2" target="_blank" style="background: #f96408; color: #fff; font-weight: bold; text-decoration: none; padding: 10px 10px; line-height: 40px; margin: 0 5px;">I was having technical issues with the gozo platform</a>
								<a href="' . $arr['url'] . '/r/3" target="_blank" style="background: #f96408; color: #fff; font-weight: bold; text-decoration: none; padding: 10px 10px; line-height: 40px; margin: 0 5px;">Other</a>
							</td>
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td>
								Thank you,<br>
								<span style="font-size: 18px; font-weight: bold;">Team Gozo</span>
							</td>
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>'.$emailPromoImg.'</td></tr>

					</table>
				</td>
			</tr>
		</table>';
 echo $msg;