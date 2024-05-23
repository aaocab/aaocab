

<div class="panel">
    <div class="panel-body table-responsive">
        <table class="table table-bordered">
            <thead>
            <th></th>
            <th class="col-xs-2">Details</th>
            <th class="col-xs-3">Guest Details</th>
            <th class="col-xs-3">Address</th>
            <th class="col-xs-2">Date Time</th>
            <th class="col-xs-2">Driver and Car details</th>
            </thead>
            <tbody>
				<?
				foreach ($models as $key => $model)
				{
					if (empty($model["bkg_agent_ref_code"]))
					{
						$displayText = "Ref Code not available";
					}
					else
					{
						$displayText = $model["bkg_agent_ref_code"];
					}
					$response = Contact::referenceUserData($model["bui_id"], 3);
					if ($response->getStatus())
					{
						$contactNo	 = $response->getData()->phone['number'];
						$countryCode = $response->getData()->phone['ext'];
						$firstName	 = $response->getData()->email['firstName'];
						$lastName	 = $response->getData()->email['lastName'];
						$email		 = $response->getData()->email['email'];
					}
					?>
					<tr>
						<td><?= $key + 1 ?></td>
						<td>
							<span><b>Booking ID : </b><?= $model["bkg_booking_id"] . "<br>(" . $displayText . ")" ?></span><br>
							<span><b>City : </b><?= $model["fromcity"] . "-" . $model["tocity"] ?></span><br>
							<span><b>Booking Type : </b><?= Booking::model()->getBookingType($model["bkg_booking_type"]) ?></span><br>
							<span><b>Car Booked : </b><?= $model["cabType"] ?></span>
						</td>
						<td><span><b>Name :</b><?= $firstName . " " . $lastName ?></span><br>
							<span><b>Phone :</b><?= $contactNo ?></span><br>
							<span><b>Email :</b><?= $email ?></span>
						</td>
						<td>
							<span><b>Pick Up :</b><?= $model["bkg_pickup_address"] ?></span><br>
							<span><b>Drop Off :</b><?= $model["bkg_drop_address"] ?></span> 
						</td>
						<td>
							<span><b>Day :</b><?= date('D', strtotime($model["bkg_pickup_date"])); ?></span><br>
							<span><b>Date :</b><?= date('d/m/Y', strtotime($model["bkg_pickup_date"])); ?></span><br>
							<span><b>Time :</b><?= date('h:i A', strtotime($model["bkg_pickup_date"])); ?></span><br>
						</td>
						<td>
							<span><b>Driver :</b><?= $model["drv_name"] ?></span><br>
							<span><b>Phone :</b><?= $model["drv_phone"]; ?></span><br>
							<span><b>Cab :</b><?= $model["cab"]; ?></span><br>
						</td>
					</tr>
				<? } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
<?
if ($_REQUEST['login'] == 1)
{
	?>

	        setTimeout(
	                function ()
	                {
	                    alert("Your account is still pending approval. Please upload required papers in the partner profile section. You may create bookings temporarily but this may be blocked unless papers are submitted soon.");
	                }, 1200);

<? } ?>
    });
</script>

