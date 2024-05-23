<?php
/* @var $model Booking */
$fcity					 = Cities::getName($model->bkg_from_city_id);
$tcity					 = Cities::getName($model->bkg_to_city_id);
//$infosource = Booking::model()->geactiontInfosource('user');
$infosource				 = BookingAddInfo::model()->getInfosource('user');
$routeCityList			 = $model->getTripCitiesListbyId();
$ct						 = implode(' &#10147 ', $routeCityList);
$action					 = Yii::app()->request->getParam('action');
$hash					 = Yii::app()->request->getParam('hash');
$otherExist				 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 'block' : 'none';
$model->bkg_chk_others	 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 1 : 0;
//echo $abc = ($showAdditional) ? 'True' : 'False';
$model->hash			 = Yii::app()->shortHash->hash($model->bkg_id);
$date					 = date_create($model->bkg_pickup_date);
$url					 = "https://" . $_SERVER['HTTP_HOST'] . "/bknw/$model->bkg_id/$model->hash";
$urlre1					 = "https://" . $_SERVER['HTTP_HOST'] . "/just1";
$text					 = 'I am going from ' . $fcity . ' to ' . $tcity . ' on ' . date_format($date, 'd/m/Y') . ' ' . date_format($date, 'g:i A') . ' and have a few empty seats in my taxi. Share the taxi with me and book your seat directly on ' . $url;
$textre1				 = 'I am going from ' . $fcity . ' to ' . $tcity . ' on ' . date_format($date, 'd/m/Y') . ' ' . date_format($date, 'g:i A') . ' for just ₹1/- . Share the taxi with me and book your seat on .  ' . $urlre1;
$scvVctId				 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
$staxrate	             = $model->bkgInvoice->getServiceTaxRate();
$totalduration  = 0;
foreach ($model->bookingRoutes as $v)
{			
	$totalduration	 += $v->brt_trip_duration;
}
?>
<div class="container mb-2">
	<div class="row">
		<div class="col-12 text-center">
			<h2 class="merriw weight600">Order Summary</h2>
			<div class="badge badge-pill badge-primary mr-1 mb-1"><?php echo Filter::formatBookingId($model->bkg_booking_id) ?></div>
            <!--<p><?php //echo ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')); ?></p>-->
		</div>
        
		<div class="col-12 col-lg-10 offset-lg-1 mt-3">
			<div class="row">
                 <div class="col-12">
        <?php if ($payment) {
           if ($succ == 'success') {  
         ?>						
		<div class="alert alert-success mb-2" role="alert">
			<strong>Transaction was successful. Thank you for your order. Your Transaction Id : <?= $transid ?></strong>
		</div>
        <?php  } 
         elseif ($succ == '') { 
        ?>
		<div class="alert alert-danger mb-2" role="alert">
			<strong>Oh snap!</strong> Something went wrong.  
		</div>
		<?php  }  
         else { 
        ?>  
         <div class="alert alert-danger mb-2" role="alert">
			<strong>Oh snap!</strong> Something went wrong. Transaction was not successful. 
		</div>
		<?php  }}   ?>
		</div>
				<div class="col-12 widget-liststyle">
					<ul class="list-unstyled">
						<li>Traveller Name: <span class="text-bold-500"><?php echo $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname ?></span></li>
						<?php
						if ($model->bkgUserInfo->bkg_user_email != '')
						{
						?>
							<li>Email: <span class="text-bold-500"><?php echo $model->bkgUserInfo->bkg_user_email ?></span></li>
						<?php } 
						if ($model->bkgUserInfo->bkg_contact_no != '')
						{
							?>
							<li>Phone: <span class="text-bold-500">+<?php echo $model->bkgUserInfo->bkg_country_code.$model->bkgUserInfo->bkg_contact_no ?></span></li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<div class="row card-style accordion-widget">
				<div class="col-12 mt-1" id="accordion-icon-wrapper1">
                    <div class="accordion collapse-icon accordion-icon-rotate" id="accordionWrapa21" data-toggle-hover="true">
                        <div class="card collapse-header">
                            <div id="heading121" class="card-header" data-toggle="collapse" data-target="#accordion121" aria-expanded="true" aria-controls="accordion121" role="tablist">
                                <span class="collapse-title">
                                    <span class="align-middle">Billing Details</span>
                                </span>
                            </div>
                            <div id="accordion121" role="tabpanel" data-parent="#accordionWrapa21" aria-labelledby="heading121" class="collapse show" style="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="text-right edit-icons mr-1">
                                            <a href="#" class="float-right">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </div>
                                        <div class="col-12 col-xl-8 p0">
                                            <div class="row">
                                                <div class="col-4 pl0">
                                                    <p class="mb0"><span class="font-22">₹</span><span class="font-24 weight600"><?php echo $model->bkgInvoice->bkg_total_amount; ?></span></p>
                                                    <p class="mb0 weight400 lineheight14">Total fare</p>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <p class="mb0"><span class="font-22">₹</span><span class="font-24 weight600"><?php echo round($model->bkgInvoice->bkg_advance_amount) ?></span></p>
                                                    <p class="mb0 weight400 lineheight14">Paid</p>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <p class="mb0"><span class="font-22">₹</span><span class="font-24 weight600"><?php echo round($model->bkgInvoice->bkg_due_amount); ?></span></p>
                                                    <p class="mb0 weight400 lineheight14">Pay to driver</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Distance quoted of the trip:</h6>
                                                                <small class="text-muted">(based on pickup and drop addresses provided)</small>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right"><?php echo $model->bkg_trip_distance; ?> Km<br><small class="text-muted">(Charges after <?php echo $model->bkg_trip_distance; ?> Km ₹<?php echo $model->bkgInvoice->bkg_rate_per_km; ?>/km)</small></h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Total days for the trip:</h6>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right"><?php echo BookingRoute::model()->formatTripduration($totalduration, 1); ?></h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Base Fare:</h6>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right">₹<?php echo $model->bkgInvoice->bkg_base_amount; ?></h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Toll Tax:</h6>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right">₹<?php echo $model->bkgInvoice->bkg_toll_tax; ?></h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">Other Taxes:</h6>
                                                                <small class="text-muted">(Including State Tax / Green Tax etc)</small>
                                                            </div>
                                                        </div>
                                                        <h6 class="mb-0 text-right">₹<?php echo ($model->bkgInvoice->bkg_additional_charge + $model->bkgInvoice->bkg_driver_allowance_amount); ?></h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between my-1">
                                                        <div class="sales-info d-flex align-items-center">
                                                            <div class="sales-info-content">
                                                                <h6 class="mb-0">GST (@<?php echo Yii::app()->params['igst']; ?>%):</h6>
                                                            </div>
                                                        </div>

                                                        <h6 class="mb-0 text-right">₹<?= ((Yii::app()->params['igst'] / $staxrate) * $model->bkgInvoice->bkg_service_tax)|0; ?></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="row mt-2">
				<div class="col-5">
					<p class="mb0 text-uppercase weight500 lineheight14">Total fare</p>
					<p class="mb0"><span class="font-20">₹</span><span class="font-24 weight600"><?php echo $model->bkgInvoice->bkg_total_amount; ?></span></p>
				</div>
				<div class="col-7 text-right"><a href="#" class="btn mt5 mb-1 btn-primary text-uppercase">Proceed to Pay</a></div>
			</div>
		</div>
	</div>

</div>