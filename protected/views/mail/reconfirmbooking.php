<?php
/* @var $model Booking */
$splRequest	 = $model->bkgAddInfo->getSpecialRequests();
$source		 = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other') ? ($model->bkgAddInfo->bkg_info_source . ' - ' . $model->bkg_info_source_desc) : $model->bkgAddInfo->bkg_info_source;
$passengers	 = ($model->bkgAddInfo->bkg_no_person > 0) ? $model->bkgAddInfo->bkg_no_person : '0';
$bookingType = '';
$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
	$firstName	 = $response->getData()->phone['firstName'];
	$lastName	 = $response->getData()->phone['lastName'];
}
switch ($model->bkg_booking_type)
{
	case 1;
		$bookingType = 'One Way Drop';
		break;
	case 2;
		$bookingType = 'Return Trip';
		break;
	case 3;
		$bookingType = 'Multi Way Trip';
		break;
}
?>
<table style="width:98%;border: #d4d4d4 0px solid;border-collapse: collapse; font-family: 'Arial'; font-size: 12px; padding: 5px; color: #000;" border="0" align="center">
    <tr>
        <td><table width="100%" bgcolor="#fff" align="center" style="border: #d4d4d4 1px solid; font-family: 'Arial'; font-size: 12px; padding: 5px; color: #000;" cellpadding="5" cellspacing="0">
                <tr>
                    <td align="center"><p><strong style="font-size:16px; text-decoration: underline;">RECONFIRMATION</strong></p></td>
                </tr>
                <tr>
                    <td align="left" style="padding-left: 10px;">
                        Dear <?= $model->bkgUserInfo->getUsername(); ?>,<br><br>
                        We need reconfirmation for your trip from <?= $model->bkgFromCity->cty_name; ?> to <?= $model->bkgToCity->cty_name; ?> on <?= date("d/m/Y", strtotime($model->bkg_pickup_date)); ?> with a scheduled pickup for <?= date('g:i A', strtotime($model->bkg_pickup_date)); ?> <br>
                        <br>
                        <ul>
                            <li style="padding-bottom: 8px;"><a href="<?= $reconfirm_url; ?>" target="_blank" style="color: #2196f3; background: #283593; padding: 4px 12px; color: #fff; font-weight: bold; text-decoration: none; line-height: 30px; border-radius: 10px; -moz-border-radius: 10px; -webkit-border-radius: 10px;">RECONFIRM or CANCEL NOW</a> Cancellation charges apply to all bookings if cancelled less than 24 hours before the pickup time. See our Cancellation & Refund Policy section in <a href="https://www.gozocabs.com/terms" target="_blank">Terms & Conditions</a>. Please RECONFIRM or CANCEL now.</li>
                            <li style="padding-bottom: 8px;">Make sure your booking has an exact pickup address. Without a clear pickup or drop address we could encounter delays. </li>
                            <li style="padding-bottom: 8px;">Use this <a href="<?= $pay_url; ?>" target="_blank" style="color: #2196f3; background: #e65100; padding: 4px 12px; color: #fff; font-weight: bold; text-decoration: none; line-height: 30px; border-radius: 10px; -moz-border-radius: 10px; -webkit-border-radius: 10px;">Payment Link</a> to make your advance payment</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><strong style="font-size:14px;">We have your itinerary details as below:</strong></td>
                </tr>
                <tr>
                    <td><table width="100%" border="1" style="border-collapse: collapse;" cellpadding="4" bordercolor="#CCCCCC">
                            <tr>
                                <td width="30%" align="left"><span style="color:#7a7a7a"><strong>Booking ID</strong></span></td>
                                <td align="left"><?= Filter::formatBookingId($model->bkg_booking_id); ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Type</strong></td>
                                <td align="left"><?= $bookingType; ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Going from city</strong></td>
                                <td align="left"><?= $model->bkgFromCity->cty_name; ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Going to city</strong></td>
                                <td align="left"><?= $model->bkgToCity->cty_name; ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Pickup Address</strong></td>
                                <td align="left"><?= $model->bkg_pickup_address; ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Requested pickup time</strong></td>
                                <td align="left"><?= date("d/m/Y", strtotime($model->bkg_pickup_date)) . ' ' . date('g:i A', strtotime($model->bkg_pickup_date)); ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Drop Address</strong></td>
                                <td align="left"><?= $model->bkg_drop_address; ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Est. trip cost</strong></td>
                                <td align="left"><b style="font-size: 18px; color: #c62828;">Rs. <?= number_format($model->bkgInvoice->bkg_total_amount, 2); ?></b></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Payment Due (15% advance required) </strong></td>
                                <td align="left"><b style="font-size: 18px;">Rs. <?= number_format($model->bkgInvoice->bkg_due_amount, 2); ?> </b></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Primary Phone</strong></td>
                                <td align="left"><?= $contactNo; ?> </td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Traveller name</strong></td>
                                <td align="left"><?= $firstName; ?> <?= $lastName; ?> </td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Special Requests</strong></td>
                                <td align="left"><?= ($splRequest != '') ? $splRequest : 'none'; ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Number of Passengers</strong></td>
                                <td align="left"><?= $passengers; ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Number of large bags</strong></td>
                                <td align="left"><?= $model->bkgAddInfo->bkg_num_large_bag; ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">Number of small bags</strong></td>
                                <td align="left"><?= $model->bkgAddInfo->bkg_num_small_bag; ?></td>
                            </tr>
                            <tr>
                                <td align="left"><strong style="color:#7a7a7a">How did you hear about Gozo</strong></td>
                                <td align="left"><?= $source; ?></td>
                            </tr>

                        </table></td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>Thank you,<br>
						<b>Team Gozo</b></td>
                </tr>

            </table></td>
    </tr>
</table>