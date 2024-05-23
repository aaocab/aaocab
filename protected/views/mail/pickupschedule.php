<?

$style = 'style="padding: 5px"';
/* @var $model Booking */
$body1 = ' 
<h3>Pickup Details for Next' . $hours . ' Hours</h3>
<table border="1" style="border-collapse: collapse">
	<thead>
		<tr>
			<th ' . $style . ' >		Booking ID			</th>
			<th ' . $style . '>		Customer Name		</th>
			<th ' . $style . '>		Customer Phone		</th>
			<th ' . $style . '>		Pickup City			</th>
			<th' . $style . '>		Drop City			</th>
			<th' . $style . '>		Pickup Date			</th>
			<th' . $style . '>		Drop Date</th>
			<th' . $style . '>		Vendor Name<br>/ Phone			</th>
			<th' . $style . '>		Driver Name<br>/ Phone			</th>
			<th' . $style . '>		Cab Number<br>/ Type			</th>
		</tr>
	</thead><tbody>';

$body2 = '';
foreach ($bData as $model) {

	$ucontact = ($model->bkg_contact_no != "") ? '+' . $model->bkg_country_code . "-" . $model->bkg_contact_no : '';
	$pdate = date('d/m/Y h:i A', strtotime($model->bkg_pickup_date));
	$ddate = ($model->bkg_trip_type == 2) ? date('d/m/Y h:i A', strtotime($model->bkg_return_date)) : '';
	$vinfo = ($model->bkg_agent_id != "") ? $model->bkgAgent->agt_name . "<br>" . $model->bkgAgent->agt_contact_number : '';
	$dinfo = ($model->bkg_driver_id != '') ? $model->bkgDriver->drv_name . "<br>" . $model->bkg_extdriver_contact : '';
	$cinfo = ($model->bkg_vehicle_id != '') ? $model->bkgVehicle->vhcType->vht_make_model . "<br>" . $model->bkgVehicle->vhc_number : '';

	$bookingId = Filter::formatBookingId($model->bkg_booking_id);
	$body2.= '<tr>				
	<td' . $style . '>' . $bookingId  . '</td>
	<td' . $style . '>' . $model->getUsername() . '</td>
	<td' . $style . '>' . $ucontact . '</td>
	<td' . $style . '>' . $model->bkgFromCity->cty_name . '				</td>
	<td' . $style . '>' . $model->bkgToCity->cty_name . '				</td>
	<td' . $style . '>' . $pdate . '		</td>
	<td' . $style . '> ' . $ddate . '			</td>	
	<td' . $style . '>	' . $vinfo . '			</td>
	<td' . $style . '>	' . $dinfo . '			</td>
	<td' . $style . '>		' . $cinfo . '			</td></tr>';
}
$body3 = '</tbody></table>';
echo $body1 . $body2 . $body3;
