<?php 
$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode = $response->getData()->phone['ext'];
	$fname			 = $response->getData()->phone['firstName'];
	$lname			 = $response->getData()->phone['lastName'];
}
?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-xs-12 col-md-12 col-lg-12 new-booking-list">
                <div class="row p20">
                    <div class="col-xs-12 heading_box">Booking Details <br>(<span style="text-transform: capitalize;font-weight: bold">Booking ID: <?= Filter::formatBookingId($model->bkg_booking_id);?></span>)</div>
                    <div class="col-xs-12 main-tab1">
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Name</b></div>
                                    <div class="col-xs-7"><?= $fname . " " . $lname ?></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Email:</b></div>
				    <?php $response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
						if ($response->getStatus())
						{
							$email	 = $response->getData()->email['email'];
						}
					?>
                                    <div class="col-xs-7"><?= $email ?>   <? echo ($model->bkgUserInfo->bkg_email_verified==1)?'<i class="fa fa-check-square fa-lg" title="verified"></i>':'<i class="fa fa-close fa-lg" title="not verified"></i>'?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Contact:</b></div>
                                    <div class="col-xs-7"><?= $contactNo ?>  <? echo ($model->bkgUserInfo->bkg_phone_verified==1)?'<i class="fa fa-check-square fa-lg"  title="verified"></i>':'<i class="fa fa-close fa-lg"  title="not verified"></i>'?></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Alternate Contact:</b></div>
                                    <div class="col-xs-7"><?= $model->bkgUserInfo->bkg_alt_contact_no ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Booking Type:</b></div>
                                    <div class="col-xs-7"><?= $model->booking_type[$model->bkg_booking_type] ?></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Booking Status:</b></div>
                                    <div class="col-xs-7"><?= $model->getBookingStatus($model->bkg_status); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Route:</b></div>
                                    <div class="col-xs-7"><?= BookingRoute::model()->getRouteName($model->bkg_id) ?></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Cab Type:</b></div>
                                    <div class="col-xs-7"><?= $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label. ' ('.$model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label.')' ; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Trip Distance:</b></div>
                                    <div class="col-xs-7"><?= ($model->bkg_trip_distance != '') ? $model->bkg_trip_distance . " Km" : "&nbsp;" ?></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Trip Duration:</b></div>
                                    <div class="col-xs-7"><?= Filter::getDurationbyMinute($model->bkg_trip_duration) ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Pickup Date:</b></div>
                                    <div class="col-xs-7"><?= date('d/m/Y', strtotime($model->bkg_pickup_date)); ?></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Pickup Time:</b></div>
                                    <div class="col-xs-7"><?= date('h:i A', strtotime($model->bkg_pickup_date)); ?></div>
                                </div>
                            </div>

                        </div>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Create Date:</b></div>
                                    <div class="col-xs-7"><?= date('d/m/Y', strtotime($model->bkg_create_date)); ?></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>create Time:</b></div>
                                    <div class="col-xs-7"><?= date('h:i A', strtotime($model->bkg_create_date)); ?></div>
                                </div>
                            </div>
                        </div>
                        <? if ($model->bkg_return_date != '' && $model->bkg_booking_type == '2') { ?>
                            <div class="row new-tab-border-b">
                                <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                    <div class="row new-tab1">
                                        <div class="col-xs-5"><b>Return Date:</b></div>
                                        <div class="col-xs-7"><?= date('d/m/Y', strtotime($model->bkg_return_date)); ?></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="row new-tab1">
                                        <div class="col-xs-5"><b>Return Time:</b></div>
                                        <div class="col-xs-7"><?= date('h:i A', strtotime($model->bkg_return_date)); ?></div>
                                    </div>
                                </div>
                            </div>
                        <? } ?>
                        <div class="row new-tab-border-b">
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Info source:</b></div>
                                    <div class="col-xs-7"><?= ( $model->bkgAddInfo->bkg_info_source != '') ? $model->bkgAddInfo->bkg_info_source : "&nbsp;" ?></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>Trip Type:</b></div>
                                    <div class="col-xs-7"><?= ( $model->bkgAddInfo->bkg_user_trip_type != '') ? Booking::model()->getCustomerBookingType($model->bkgAddInfo->bkg_user_trip_type) : "" ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row new-tab-border-b">
                        </div>
                        <div class="row">
                            <div class="col-xs-12 p0">
                                <div class="hostory_leftdeep mt0">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="row p5">
                                            <div class="col-xs-6 col-sm-12"><b>Pickup Location</b></div>
                                            <div class="col-xs-6 col-sm-12"><?= $model->bkg_pickup_address; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="row p5">
                                            <div class="col-xs-6 col-sm-12"><b>Dropoff Location</b></div>
                                            <div class="col-xs-6 col-sm-12"><?= $model->bkg_drop_address; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php
            $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id' => 'manualverifyotp', 'enableClientValidation' => FALSE,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                    'errorCssClass' => 'has-error'
                ),
                'enableAjaxValidation' => false,
                'errorMessageCssClass' => 'help-block',
                'htmlOptions' => array(
                    'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'autocomplete' => "off",
                ),
            ));
            ?>
            <div class="col-xs-12"  style="text-align: center;min-height: 150px"><div class="col-xs-3"><b>TO VERIFY CONTACT INFO</b></div><div class="col-xs-4"><input class="form-control" type="text"  name="otpvalue" placeholder="Enter OTP here"><span style="color: #F25656"><?= ($error != "") ? $error : ""; ?></span></div><div class="col-xs-3"><input class="btn btn-info" type="submit" value="VERIFY" name="submitotp"></div></div>
                    <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>
<? if ($success) { ?>
        alert("Contact Info verified successfully");
        location.href = '<?= $url ?>';
<? } ?>
</script>
