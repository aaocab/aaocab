<?php
/* @var $model Booking  */
$fcity	    = Cities::getName($model->bkg_from_city_id);
$tcity	    = Cities::getName($model->bkg_to_city_id);
$response   = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
    $contactNo   = $response->getData()->phone['number'];
    $countryCode = $response->getData()->phone['ext'];
	$firstName   = $response->getData()->phone['firstName'];
	$lastName   = $response->getData()->phone['lastName'];
}
?>
<section id="section2">
    <div class="container">
        <div class="well peach border">
            Booking Successful! You will receive the cab details 3 hours before your scheduled pickup time.
        </div>
        <div class="col-xs-12 col-sm-8">
            <h4>One Way Drop - <?= $fcity ?> to  <?= $tcity ?></h4>
            <div class="row ">
                <label class="col-xs-4 ">Booking ID:</label>
                <label class="col-xs-6"><?= Filter::formatBookingId($model->bkg_booking_id); ?></label>
            </div>
            <div class="row ">
                <label class="col-xs-4 ">Cab:</label>
                <label class="col-xs-6"><?= $vmodel->vct_label . ' ' . $vmodel->vct_desc ?></label>
            </div>
            <div class="row ">
                <label class="col-xs-4 ">Date:</label>
                <label class="col-xs-6"><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?></label>
            </div>
            <div class="row ">
                <label class="col-xs-4 ">Time:</label>
                <label class="col-xs-6"><?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></label>
            </div>
            <div class="row ">
                <label class="col-xs-4 ">Pickup Point:</label>
                <label class="col-xs-6"><?= $model->bkg_pickup_address . ', ' . $fcity ?></label>
            </div>
            <div class="row ">
                <label class="col-xs-4 ">Drop Area:</label>
                <label class="col-xs-6"><?= $model->bkg_drop_address . ', ' . $tcity ?></label>
            </div>
            <div class="row ">
                <label class="col-xs-4 ">Name:</label>
                <label class="col-xs-6"><?= $firstName . ' ' . $lastName ?> </label>
            </div>
            <div class="row ">
                <label class="col-xs-4">Primary Phone:</label>
                <label class="col-xs-6">+<?=  $countryCode .  $contactNo ?></label>
            </div>
	    <?
	    if ($model->bkgUserInfo->bkg_alt_contact_no != '')
	    {
		?>
    	    <div class="row">
    		<label class="col-xs-4 ">Alternate Phone:</label>
    		<label class="col-xs-6">+<?= $model->bkgUserInfo->bkg_alt_country_code . $model->bkgUserInfo->bkg_alt_contact_no ?></label>
    	    </div>
		<?
	    } if ($model->bkgUserInfo->bkg_user_email != '')
	    {
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
		if ($response->getStatus())
		{
			$email	 = $response->getData()->email['email'];
		}
		?>

    	    <div class="row">
    		<label class="col-xs-4 ">Email:</label>
    		<label class="col-xs-6"><?= $email ?></label>
    	    </div>
	    <? } ?>
            <div class="row ">
                <label class="col-xs-4">Amount payable to driver:</label>
                <label class="col-xs-6"><i class=" fa fa-inr"></i> <?= $model->bkg_amount ?></label>
            </div>
            <div class="row ">
                <label class="col-xs-10">Thank you for choosing Gozocabs!</label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="well">
                <p>
                    - Extra pickup/drop points apart from the ones specified will be charged at Rs. 300 per point.
                </p>
                <p>
                    - <b><u>Cancellation</u></b> can be done by logging in to our Mobile App or Website. All bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in our Terms & Conditions page on our website.
                </p>
                <p>
                    - Incase of excessive luggage, contact us for a <b><u>carrier</u></b> on cab. No extra charges applicable.
                </p>
                <p>
                    - If Parking is requested (eg. airport), the charges will be borne by the customer.
                </p>
            </div>
        </div>
    </div>
</section>

