<?
$fcity	 = Cities::getName($model->bkg_from_city_id);
$tcity	 = Cities::getName($model->bkg_to_city_id);
$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo   = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
}
?>

<div class="container">

    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8">
            <div class="row mb10">
                <div class="col-xs-12">
                    <p class="weight400"> Booking Successful! You will receive the cab details 3 hours before your scheduled pickup time.
                    </p>
                    <p class="weight400"> 
                        Please review the details of your booking request.
                    </p>
                </div> </div>
        </div> 
        <div class="col-xs-12 col-sm-8 col-md-8 book-panel">
            <div class="panel panel-default border-radius">
                <div class="panel-body pb0">
		    <?
		    if ($succ == 'TXN_SUCCESS')
		    {
			?> 
    		    <div role="alert" class="alert alert-success"> 
    			<strong>Transaction was successful. Thank you for your order. Your Transaction Id : <?= $transid ?></strong>
    		    </div>

			<?
		    }
		    else
		    {
			?>
    		    <div role="alert" class="alert alert-danger"> 
    			<strong>Oh snap!</strong> Something went wrong. Transaction was not successful. 
    		    </div>
			<?
		    }
		    ?>



                    <div class="row">
                        <div class="col-xs-12">
                            <h4 class="m0 mb10">One Way Drop - <?= $fcity ?> to  <?= $tcity ?> (Booking ID:<?= Filter::formatBookingId($model->bkg_booking_id) ?>) (<?= Booking::model()->getActiveBookingStatus($model->bkg_status) ?>)</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 summary-div">
                            <div class="col-xs-12 col-sm-5"><b>Full Name:</b></div>
                            <div class="col-xs-12 col-sm-7"><?= $model->bkg_user_fname . ' ' . $model->bkg_user_lname ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 summary-div">
                            <div class="col-xs-12 col-sm-5"><b>Cab:</b></div>
                            <div class="col-xs-12 col-sm-7"><?= '('.$model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label.') '.$model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . ' ' . $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 summary-div">
                            <div class="col-xs-12 col-sm-5"><b>Date:</b></div>
                            <div class="col-xs-12 col-sm-7"><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 summary-div">
                            <div class="col-xs-12 col-sm-5"><b>Time:</b></div>
                            <div class="col-xs-12 col-sm-7"><?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 summary-div">
                            <div class="col-xs-12 col-sm-5"><b>Pickup Point:</b></div>
                            <div class="col-xs-12 col-sm-7"><?= $model->bkg_pickup_address . ', ' . $fcity ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 summary-div">
                            <div class="col-xs-12 col-sm-5"><b>Drop Area:</b></div>
                            <div class="col-xs-12 col-sm-7"><?= $model->bkg_drop_address . ', ' . $tcity ?></div>
                        </div>
                    </div>
		    <?
		    if ($contactNo != '')
		    {
			?>
    		    <div class="row">
    			<div class="col-xs-12 summary-div">
    			    <div class="col-xs-12 col-sm-5"><b>Primary Phone:</b></div>
    			    <div class="col-xs-12 col-sm-7">+<?= $countryCode ?><?= $contactNo ?></div>
    			</div>
    		    </div>
		    <? } ?>
		    <?
		    if ($model->bkgUserInfo->bkg_alt_contact_no != '')
		    {
			?>
    		    <div class="row">
    			<div class="col-xs-12 summary-div">
    			    <div class="col-xs-12 col-sm-5"><b>Alternate Phone:</b></div>
    			    <div class="col-xs-12 col-sm-7">+<?= $model->bkg_alt_country_code ?><?= $model->bkgUserInfo->bkg_alt_contact_no ?></div>
    			</div>
    		    </div>
			<?
		    } if ($model->bkg_user_email != '')
		    {
			?>

    		    <div class="row">
    			<div class="col-xs-12 summary-div">
    			    <div class="col-xs-12 col-sm-5"><b>Email:</b></div>
    			    <div class="col-xs-12 col-sm-7"><?= $model->bkg_user_email ?></div>
    			</div>
    		    </div>
			<?
		    }
		    ?>
                    <div class="row">
                        <div class="col-xs-12 summary-div">
			    <?
			    if ($model->bkg_trip_type == 1)
			    {
				?>
    			    <div class="col-xs-12 col-sm-5"><b>Total Fare:</b></div>
    			    <div class="col-xs-12 col-sm-7"><h4 class="m0"><i class="fa fa-inr"></i> <?= $model->bkg_amount ?></h4></div>
				<?
			    }
			    else if ($model->bkg_trip_type == 2)
			    {
				?>
    			    <div class="col-xs-12 col-sm-5"><b>Rate:</b></div>
    			    <div class="col-xs-12 col-sm-7"><h4 class="m0"><i class="fa fa-inr"></i> <?= $model->bkg_rate_per_km . ' Per Km' ?></h4></div>
				<?
			    }
			    ?>
                        </div>
                    </div>
		    <?
		    if ($model->bkg_advance_amount > 0 || ($model->bkg_credits_used != '' && $model->bkg_credits_used > 0))
		    {
			if ($model->bkg_advance_amount > 0)
			{
			    ?>
			    <div class="row">
				<div class="col-xs-12 summary-div">
				    <div class="col-xs-12 col-sm-5"><b>Advance paid:</b></div>
				    <div class="col-xs-12 col-sm-7"><h4 class="m0"><i class="fa fa-inr"></i> <?= round($model->bkg_advance_amount) ?></h4></div>
				</div>
			    </div>
			<? } ?>
			<?
			if ($model->bkg_credits_used != '' && $model->bkg_credits_used > 0)
			{
			    ?>
			    <div class="row">
				<div class="col-xs-12 summary-div">
				    <div class="col-xs-12 col-sm-5"><b>Credits used:</b></div>
				    <div class="col-xs-12 col-sm-7"><h4 class="m0"><i class="fa fa-inr"></i> <?= $model->bkg_credits_used ?></h4></div>
				</div>
			    </div>
			<? } ?>
			<?
			if ($model->bkg_trip_type == 1)
			{
			    ?>
			    <div class="row blue2 white-color">
				<div class="col-xs-12 summary-div">
				    <div class="col-xs-12 col-sm-5"><b>Amount Due:</b></div>
				    <div class="col-xs-12 col-sm-7"><h4 class="m0"><i class="fa fa-inr"></i> <?= round($model->bkg_amount_due) ?></h4></div>
				</div>
			    </div>
			    <?
			}
		    }
		    ?>
                    <div class="row blue2 white-color">
                        <div class="col-xs-12 summary-div">
                            <div class="col-xs-12 col-sm-12"><b>Thank you for choosing aaocab!</b></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="thumbnail p10 border-radius">
                <div class="caption pl0 pr0">
                    <ul class="pl20">
                        <li>Amount inclusive of toll tax. </li>
                        <li>Extra pickup/drop points apart from the ones specified will be charged at Rs. 300 per point. </li>
                        <li><b>Cancellation:</b> You may cancel your Booking with us by  signin  or  login  to our Mobile App or Website. All bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in  <a href="http://www.aaocab.com/terms" target="_blank">Terms & Conditions</a> page on our website. </li>
                        <li>Incase of excessive luggage, contact us for a carrier on cab. No extra charges applicable. </li>
                        <li>If Parking is requested (eg. airport), the charges will be borne by the customer. </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?
if (APPLICATION_ENV == 'production')
{
    ?>
    <!-- Google Code for Confirm Lead Conversion Page -->
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 937550432;
        var google_conversion_language = "en";
        var google_conversion_format = "3";
        var google_conversion_color = "ffffff";
        var google_conversion_label = "J39FCNvt1WYQ4MSHvwM";
        var google_remarketing_only = false;
        /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/937550432/?label=J39FCNvt1WYQ4MSHvwM&amp;guid=ON&amp;script=0"/>
    </div>
    </noscript>
    <?
}
?>