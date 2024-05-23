<?php
/* @var $model Booking */
//$cityList = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
$status = Booking::model()->getBookingStatus();
//$adminlist = Admins::model()->findNameList();
//$statuslist = Booking::model()->getActiveBookingStatus();
$reconfirmStatus = Booking::model()->getReconfirmStatus();
$cancelDetail = CancelReasons::model()->findByPk($model->bkg_cancel_id);
$rutInfo = [];
$cntRut = count($bookingRouteModel);
$spclInstruction = $model->getFullInstructions();
$vencabdriver = $model->getBookingCabModel();
$vehicleModel = $vencabdriver->bcbCab->vhcType->vht_model;
if($vencabdriver->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($vencabdriver->bcb_vendor_id, $vencabdriver->bcb_cab_id);
}
?>
<style type="text/css">
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .control-label{
        font-weight: bold
    }   
    .rating-cancel{
        display: none !important;
        visibility: hidden !important;
    }
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }

    div .comments .comment {
        padding:3px;max-width:100%
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }   

    .remarkbox{
        width: 100%; 
        padding: 3px;  
        overflow: auto; 
        line-height: 14px; 
        font: normal arial; 
        border-radius: 5px; 
        -moz-border-radius: 5px; 
        border: 1px #aaa solid;
    }

</style>

<div class="row">
    <div class="col-xs-12 text-center h2 mt0">
        <label for="type" class="control-label"><span style="font-weight: normal; font-size: 30px;">Booking Id:</span> </label>
        <b><?= $model->bkg_booking_id ?></b><label><?
            if ($model->bkg_agent_id > 0) {
                $agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
                if ($agentsModel->agt_type == 1) {
                    echo "(Corporate)";
                } else {
                    echo "(Partner)";
                }
            }
            ?></label>
    </div>
</div>

<!--<div class="row">
    <div class="col-xs-12 mb20">
        <div style="text-align: center" class="below-buttons">
            <? $button_type = 'label'; ?>
            <?= $model->getActionButton([], $button_type); ?>
            <? $ratingModel = Ratings::model()->getRatingbyBookingId($model->bkg_id); ?>
            <?
            if ($model->bkg_status > 4 && $ratingModel->rtg_customer_overall == '') {
                ?>
                <a class="btn btn-info mt5" id="review" onclick="addCustRating(<?= $model->bkg_id ?>)" title="Add Customer Review"><i class="fa fa-star-o"></i> Add Customer Review</a>
            <? } ?>
            <?
            if ($model->bkg_status > 4 && $ratingModel->rtg_csr_customer == '') {
                ?>
                <a class="btn btn-info mt5 ml5" id="review" onclick="addCSRRating(<?= $model->bkg_id ?>)" title="Add CSR Review"><i class="fa fa-star-o"></i> Add CSR Review</a>
            <? } ?>
        </div>
    </div>
</div>-->
<div id="view">
<!--    <div class="row" >
        <div class="col-xs-6 text-left"><?if($model->bkg_status==1 && ($model->bkg_agent_id==null || $model->bkg_agent_id=='' || $model->bkg_agent_id==0)){?><a class="btn btn-info btn-sm" title="Confirm Booking" onclick="confbooking();">Confirm Booking</a><?}?></div>
        <div class="col-xs-6 text-right">
            <a class="btn btn-primary btn-sm" id="setFlag" style="display: none;" onclick="addDesc('0')" title="Set accounting flag" >Set accounting flag</a>    
            <a class="btn btn-success btn-sm" id="clearFlag" style="display: none;" onclick="accountFlag(<?=$model->bkg_id?>,'1')" title="Clear accounting flag" >Clear accounting flag</a>
            <a class="btn btn-info btn-sm" id="bkg_acct" onclick="editAccount()" title="Edit Account Details">Edit Account Details</a>
        </div>  
    </div> -->

    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
            <div class="row p20">
                <div class="col-xs-12 heading_box">Booking Information</div>
                <div class="col-xs-12 main-tab1">
                    <div class="row new-tab-border-b">
                        <div class="col-xs-12 col-sm-6 new-tab-border-r">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Name</b></div>
                                <div class="col-xs-7"><a href="#" onclick="showLinkedUser(<?= $model->bkg_user_id ?>, '<?= Yii::app()->createUrl('admin/user/details') ?>')"><?= $model->bkg_user_name . ' ' . $model->bkg_user_lname; ?></a></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Email:</b></div>
                                <div class="col-xs-7"><span id="userEmail"></span><br><span class="bg-success mt5"><?=($model->bkg_email_verified==1)?" Verified ":""?></span> <button class="btn btn-default btn-xs" id="showContactDetails" onclick="showContact()">Show Email/Contact</button> </div>
                            </div>
                        </div>
                    </div>
                    <div class="row new-tab-border-b">
                        <div class="col-xs-12 col-sm-6 new-tab-border-r">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Contact:</b></div>
                                <div class="col-xs-7"><span id="userPhone"></span><br><span class="bg-success mt5"><?=($model->bkg_phone_verified==1)?" Verified ":""?></span></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Alternate Contact:</b></div>
                                <div class="col-xs-7"><span id="userAltPhone"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="row new-tab-border-b">
                        <div class="col-xs-12 col-sm-6 new-tab-border-r">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Booking Type:</b></div>
                                <div class="col-xs-7"><?= Booking::model()->getBookingType($model->bkg_booking_type); ?></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Booking Status:</b></div>
                                <div class="col-xs-7"><?= $status[$model->bkg_status] ?> <?php
                                    if ($model->bkg_status != '9' || $model->bkg_status != '8') {
                                        echo '(' . $reconfirmStatus[$model->bkg_reconfirm_flag] . ')';
                                    }
                                    if($bkgTrack->bkg_is_trip_verified==1){
                                      echo '(' ."<span class='bg-success p5'>Trip verified</span>". ')';   
                                    }else{
                                      echo '(' ."Trip not verified". ')';      
                                    }
                                    ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row new-tab-border-b">
                        <div class="col-xs-12 col-sm-6 new-tab-border-r">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Route:</b></div>
                                <div class="col-xs-7"><?= $model->bkgFromCity->cty_name . ' to ' . $model->bkgToCity->cty_name; ?></div>
                            </div>
                        </div>
                        <?php
                        $cabmodel = $model->getBookingCabModel();
                        ?>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Cab Type:</b></div>
                                <div class="col-xs-7"><?=
                                    $model->bkgVehicleType->getCabType();
                                    ?></div>
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
                                <div class="col-xs-7"><?= ( $model->bkg_user_trip_type != '') ? Booking::model()->getCustomerBookingType($model->bkg_user_trip_type) : "" ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row new-tab-border-b">
                        <?
                        if ($model->bkg_agent_id > 0) {
                            ?>
                            <div class="col-xs-12 col-sm-12 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5  text-danger"><b>BOOKING TYPE:</b></div>
                                    <div class="col-xs-7"><?php
                                        $agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
                                        if ($agentsModel->agt_type == 1) {
                                            echo "<span class='text-danger'>CORPORATE (" . ($agentsModel->agt_company) . ")<br></span>";
                                            echo "CORPORATE BOOKING. CLEAN CAR. WELL BEHAVED DRIVER.<br>";
                                            echo "Customer Due <i class='fa fa-inr'></i>" . $model->bkg_due_amount;
                                        } else {
                                            $owner = ($agentsModel->agt_owner_name!='')?$agentsModel->agt_owner_name:($agentsModel->agt_fname." ".$agentsModel->agt_lname);
                                            echo "PARTNER (" . ($agentsModel->agt_company."-".$owner) . ")<br>";
                                            echo "<b>Booking Referral ID: <span class='text-info'>".$model->bkg_agent_ref_code."</span></b>";    
                                        }
                                        ?>
                                    </div>
                                    <div class="col-xs-12">
                                        <button class="btn btn-default btn-small" onclick="showAgentNotifyDefault()">Partner Notification Defaults</button> 
                                        <div id="showagentnotifydefaults" class="hide mt10">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th></th>
                                                    <th colspan="3">Partner</th>
                                                    <th colspan="3">Traveller</th>    
                                                    <th colspan="3">Relationship Manager</th>    
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Email</td><td>SMS</td><td>App</td>
                                                    <td>Email</td><td>SMS</td><td>App </td>
                                                    <td>Email</td><td>SMS</td><td>App </td>
                                                </tr>
                                                <?
                                                $arrEvents = AgentMessages::getEvents();
                                                foreach ($arrEvents as $key => $value) {
                                                    $bkgMessagesModel = BookingMessages::model()->getByEventAndBookingId($model->bkg_id, $key);
                                                    ?>  
                                                    <tr>
                                                        <th><?= $arrEvents[$key] ?></th>
                                                        <td><?= ($bkgMessagesModel->bkg_agent_email == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
                                                        <td><?= ($bkgMessagesModel->bkg_agent_sms == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
                                                        <td><?= ($bkgMessagesModel->bkg_agent_app == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>

                                                        <td><?= ($bkgMessagesModel->bkg_trvl_email == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
                                                        <td><?= ($bkgMessagesModel->bkg_trvl_sms == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
                                                        <td><?= ($bkgMessagesModel->bkg_trvl_app == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>

                                                        <td><?= ($bkgMessagesModel->bkg_rm_email == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
                                                        <td><?= ($bkgMessagesModel->bkg_rm_sms == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
                                                        <td><?= ($bkgMessagesModel->bkg_rm_app == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
                                                    </tr>
                                                    <?
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? } ?>
                        <? if ($model->bkg_file_path != '') { ?>
                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b>File Path:</b></div>
                                    <div class="col-xs-7"><a href="<?= $model->bkg_file_path ?>" target="_blank">File</a></div>
                                </div>
                            </div>
                        <? } ?>
                        <? if (($model->bkg_status == 8 || $model->bkg_status == 9) && $model->bkg_cancel_delete_reason != '') { ?>
                            <?
                            $reason = '';
                            if ($model->bkg_status == 8) {
                                $reason = 'Delete';
                            }
                            if ($model->bkg_status == 9) {
                                $reason = 'Cancel';
                            }
                            ?>

                            <div class="col-xs-12 col-sm-6 new-tab-border-r">
                                <div class="row new-tab1">
                                    <div class="col-xs-5"><b><?= $reason ?> Reason:</b></div>
                                    <div class="col-xs-7"><?= $model->bkg_cancel_delete_reason . "$cancelDetail->cnr_reason" ?></div>
                                </div>
                            </div>



                        <? } ?>
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
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="row p5 pl0 pr0">
                                        <div class="col-xs-6 col-sm-12"><b>Additional Information</b></div>
                                        <div class="col-xs-6 col-sm-12"><?= ($spclInstruction != "") ? $spclInstruction : "&nbsp;" ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
            <div class="row p20">
                <div class="col-xs-12 heading_box">Billing Information</div>
                <?
                $this->renderPartial('accountsdetail', ['model' => $model, 'cabmodel' => $vencabdriver]);
                ?>
            </div>
        </div>

    </div>
    <?
    if ($cntRut > 0) {
        $diffdays = 0;
        foreach ($bookingRouteModel as $key => $bookingRoute) {
            $rutName = $bookingRoute->brtFromCity->cty_name . ' to ' . $bookingRoute->brtToCity->cty_name;
            $pickLoc = $bookingRoute->brt_from_location;
            $pickDateTime = DateTimeFormat::DateTimeToDatePicker($bookingRoute->brt_pickup_datetime) . " " . DateTimeFormat::DateTimeToTimePicker($bookingRoute->brt_pickup_datetime);
            $dist = $bookingRoute->brt_trip_distance . 'Km';
            $dura = Filter::getDurationbyMinute($bookingRoute->brt_trip_duration);

            if ($key == 0) {
                $diffdays = 1;
            } else {

                $date1 = new DateTime(date('Y-m-d', strtotime($bookingRouteModel[0]->brt_pickup_datetime)));
                $date2 = new DateTime(date('Y-m-d', strtotime($bookingRoute->brt_pickup_datetime)));
                $difference = $date1->diff($date2);
                $diffdays = ($difference->d + 1);
            }

            $last_date = date('Y-m-d H:i:s', strtotime($bookingRoute->brt_pickup_datetime . '+ ' . $bookingRoute->brt_trip_duration . ' minute'));
            $rutInfo[] = ['rutName' => $rutName, 'pickLoc' => $pickLoc, 'pickDateTime' => $pickDateTime,
                'dist' => $dist, 'dura' => $dura, 'diffdays' => $diffdays, 'last_date' => $last_date];
        }
    }
    ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive11">
                <table class="table table-responsive table-bordered mb0" style="background: #fff!important;">
                    <tbody><tr class="all_detailss">
                            <td class="col-xs-4 text-center"><b>Route</b></td>
                            <td class="col-xs-3 text-center"><b>Vendor</b></td>
                            <td class="col-xs-2 text-center"><b>Driver</b></td>
                            <td class="col-xs-3 text-center"><b>Cab</b></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row"><div class="col-xs-12 font11x"><b><?= $rutInfo[0]['rutName'] ?></b> </div></div>
                                <div class="row"><div class="col-xs-12"><b>Pickup Location:</b> <?= $rutInfo[0]['pickLoc'] ?></div></div>
                                <div class="row"><div class="col-xs-12"><b>Pickup Time:</b> <?= $rutInfo[0]['pickDateTime'] ?></div></div>
                                <div class="row"><div class="col-xs-5"><b>Duration:</b> <nobr><?= $rutInfo[0]['dura'] ?> </nobr></div><div class="col-xs-4"> <b>Distance:</b> <nobr><?= $rutInfo[0]['dist'] ?></nobr></div><div class="col-xs-3"> <b>Day:</b> <?= $rutInfo[0]['diffdays'] ?></div></div>
                                <? if($model->bkg_spl_req_lunch_break_time != '0'){?>
                                    <div class="row"><div class="col-xs-12"><b>Extra Time Added:</b> <?= $model->bkg_spl_req_lunch_break_time ?> Minutes For Journey Break</div></div>
                                <? } ?>
                            </td>

                            <td  style="vertical-align: middle;" rowspan="<?= $cntRut ?>">
                                Trip ID: <b>
                                    <?php echo CHtml::link($model->bkg_bcb_id, Yii::app()->createUrl("rcsr/booking/triprelatedbooking", ["tid" => $model->bkg_bcb_id]), ["class" => "viewRelatedBooking", "onclick" => "return viewList(this)"]); ?>
                                </b> <?php if ($vencabdriver->bcb_trip_type != 0) { ?>
                                    <span class="label label-primary">
                                        <?php
                                        if ($vencabdriver->bcb_trip_type == 1) {
                                            echo "Matched";
                                        }
                                        ?> </span>
                                <?php } ?> </br>
                                Name: <b><?= CHtml::link($vencabdriver->bcbVendor->vnd_name, Yii::app()->createUrl("admin/vendor/view", ["id" => $vencabdriver->bcbVendor->vnd_id]), ["target" => "_blank"]); ?></b></br>
                                Phone: <?= $vencabdriver->bcbVendor->vnd_phone ?><br>
                                Rating:  <?php
                                if ($vencabdriver->bcbVendor->vnd_overall_rating != '') {
                                    if ($vencabdriver->bcbVendor->vnd_overall_rating >= 4) {
                                        echo '<span class="label label-success">' . $vencabdriver->bcbVendor->vnd_overall_rating . '</span>';
                                    } else if ($vencabdriver->bcbVendor->vnd_overall_rating <= 3) {
                                        echo '<span class="label label-danger">' . $vencabdriver->bcbVendor->vnd_overall_rating . '</span>';
                                    }
                                }
                                ?><br>
                                Lifetime Trips: <?= $vencabdriver->bcb_vendor_trips ?><br>
                                <?php
                                if ($vencabdriver->bcbVendor) {
                                    if ($vencabdriver->bcbVendor->vnd_is_freeze == 1) {
                                        echo '<span class="label label-danger">Frozen</span><br>';
                                    } else if ($vencabdriver->bcbVendor->vnd_is_freeze == 0) {
                                        echo '<span class="label label-success">Unfreezed</span><br>';
                                    }
                                    if ($vencabdriver->bcbVendor->vnd_active == 0) {
                                        echo '<span class="label label-danger">Inactive</span><br>';
                                    }
                                    if ($vencabdriver->bcbVendor->vnd_active == 2) {
                                        echo '<span class="label label-danger">Blocked</span><br>';
                                    }
                                    if ($vencabdriver->bcbVendor->vnd_mark_vendor_count >= 1) {
                                        if ($vencabdriver->bcbVendor->vnd_mark_vendor_count == 1) {
                                            echo '<span class="label label-warning">Bad Count : ' . $vencabdriver->bcbVendor->vnd_mark_vendor_count . '</span><br>';
                                        } else if ($vencabdriver->bcbVendor->vnd_mark_vendor_count > 1) {
                                            echo '<span class="label label-danger">Bad Count : ' . $vencabdriver->bcbVendor->vnd_mark_vendor_count . '</span><br>';
                                        }
                                    }
                                }
                                ?>   
                            </td>
                            <td   style="vertical-align: middle;" rowspan="<?= $cntRut ?>">
                                <?
                                $driverName = ($vencabdriver->bcb_driver_name == '') ? $vencabdriver->bcbDriver->drv_name : $vencabdriver->bcb_driver_name;
                                ?>
                                Name: <b><?= CHtml::link($driverName, Yii::app()->createUrl("rcsr/driver/view", ["id" => $vencabdriver->bcb_driver_id]), ["target" => "_blank"]); ?></b></br>
                                Phone: <?= $vencabdriver->bcbDriver->drv_phone; ?><br>
                                Rating: <?php
                                if ($vencabdriver->bcbDriver->drv_overall_rating != '') {
                                    if ($vencabdriver->bcbDriver->drv_overall_rating >= 4) {
                                        echo '<span class="label label-success">' . $vencabdriver->bcbDriver->drv_overall_rating . '</span>';
                                    } else if ($vencabdriver->bcbDriver->drv_overall_rating <= 3) {
                                        echo '<span class="label label-danger">' . $vencabdriver->bcbDriver->drv_overall_rating . '</span>';
                                    }
                                }
                                ?><br>
                                Lifetime Trips: <?= $vencabdriver->bcb_driver_trips ?><br>
                                <?php
                                if ($vencabdriver->bcbDriver) {
                                    if ($vencabdriver->bcbDriver->drv_is_freeze == 1) {
                                        echo '<span class="label label-success">Blocked</span><br>';
                                    }

                                    if ($vencabdriver->bcbDriver->drv_active == 0) {
                                        echo '<span class="label label-danger">Inactive</span><br>';
                                    }

                                    if ($vencabdriver->bcbDriver->drv_mark_driver_count >= 1) {
                                        if ($vencabdriver->bcbDriver->drv_mark_driver_count == 1) {
                                            echo '<span class="label label-warning">Bad Count : ' . $vencabdriver->bcbDriver->drv_mark_driver_count . '</span><br>';
                                        } else if ($vencabdriver->bcbDriver->drv_mark_driver_count > 1) {
                                            echo '<span class="label label-danger">Bad Count : ' . $vencabdriver->bcbDriver->drv_mark_driver_count . '</span><br>';
                                        }
                                    }

                                    if ($vencabdriver->bcbDriver->drv_approved == 1) {
                                        echo '<span class="label label-success">Approved</span>';
                                    } else if ($vencabdriver->bcbDriver->drv_approved == 0) {
                                        echo '<span class="label label-danger">Not Approved</span>';
                                    } else if ($vencabdriver->bcbDriver->drv_approved == 2) {
                                        echo '<span class="label label-warning">Pending Approval</span>';
                                    } else if ($vencabdriver->bcbDriver->drv_approved == 3) {
                                        echo '<span class="label label-warning">Rejected</span>';
                                    }
                                }
                                ?>


                            </td>
                            <td   style="vertical-align: middle;" rowspan="<?= $cntRut ?>">
                                Vehicle name: <b><?= CHtml::link($vehicleModel, Yii::app()->createUrl("admin/vehicle/view", ["id" => $vencabdriver->bcbCab->vhc_id]), ["target" => "_blank"]); ?></b><br>
                                Car number: <?= $vencabdriver->bcbCab->vhc_number ?><br>
                                Rating: <?php
                                if ($vencabdriver->bcbCab->vhc_overall_rating != '') {
                                    if ($vencabdriver->bcbCab->vhc_overall_rating >= 4) {
                                        echo '<span class="label label-success">' . $vencabdriver->bcbCab->vhc_overall_rating . '</span>';
                                    } else if ($vencabdriver->bcbCab->vhc_overall_rating <= 3) {
                                        echo '<span class="label label-danger">' . $vencabdriver->bcbCab->vhc_overall_rating . '</span>';
                                    }
                                }
                                ?><br>
                                Lifetime Trips: <?= $vencabdriver->bcb_cab_trips ?><br>
                                <?php
                                if ($vencabdriver->bcbCab) {
                                    if ($vencabdriver->bcbCab->vhc_is_freeze == 1) {
                                        echo '<span class="label label-success">Blocked</span><br/>';
                                    }

                                    if ($vencabdriver->bcbCab->vhc_active == 0) {
                                        echo '<span class="label label-danger">Inactive</span><br/>';
                                    }

                                    if ($vencabdriver->bcbCab->vhc_is_commercial == 1) {
                                        echo '<span class="label label-primary">Commercial</span><br/>';
                                    }

                                    if ($vencabdriver->bcbCab->vhc_approved == 1) {
                                        echo '<span class="label label-success">Approved</span><br/>';
                                    } else if ($vencabdriver->bcbCab->vhc_approved == 0) {
                                        echo '<span class="label label-danger">Not Approved</span><br/>';
                                    } else if ($vencabdriver->bcbCab->vhc_approved == 2) {
                                        echo '<span class="label label-warning">Pending Approval</span><br/>';
                                    } else if ($vencabdriver->bcbCab->vhc_approved == 3) {
                                        echo '<span class="label label-danger">Rejected</span><br/>';
                                    }


                                    if ($vencabdriver->bcbCab->vhc_mark_car_count >= 1) {
                                        if ($vencabdriver->bcbCab->vhc_mark_car_count == 1) {
                                            echo '<span class="label label-warning">Bad Mark : ' . $vencabdriver->bcbCab->vhc_mark_car_count . '</span><br/>';
                                        } else if ($vencabdriver->bcbCab->vhc_mark_car_count > 1) {
                                            echo '<span class="label label-danger">Bad Mark : ' . $vencabdriver->bcbCab->vhc_mark_car_count . '</span><br/>';
                                        }
                                    }
                                }
                                ?> 
                            </td>
                        </tr>
                        <?
                        if ($cntRut > 1) {
                            for ($i = 1; $i < $cntRut; $i++) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="row"><div class="col-xs-12 font11x"><b><?= $rutInfo[$i]['rutName'] ?></b></div></div>
                                        <div class="row"><div class="col-xs-12"><b>Pickup Location:</b> <?= $rutInfo[$i]['pickLoc'] ?></div></div>
                                        <div class="row"><div class="col-xs-12"><b>Pickup Time:</b> <?= $rutInfo[$i]['pickDateTime'] ?></div></div>
                                        <div class="row"><div class="col-xs-5"><b>Duration:</b> <?= $rutInfo[$i]['dura'] ?> </div><div class="col-xs-4"> <b>Distance:</b> <?= $rutInfo[$i]['dist'] ?> </div><div class="col-xs-3"> <b>Day:</b> <?= $rutInfo[$i]['diffdays'] ?></div></div>

                                    </td>
                                </tr>
                                <?
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    if ($model->bkg_status > 4) {
        ?>
        <div class="row">
            <div class="col-xs-12 mt20">
                <?php
                if ($ratingModel->rtg_customer_overall) {
                    ?> 
                    <label class="mt10 control-label">Customer Rating</label>
                    <div class="col-xs-12 rounded pb10">
                        <div class="row">
                            <?php
                            if ($ratingModel->rtg_customer_recommend) {
                                ?> <div class='col-xs-12 mt10'>
                                    <?= $ratingModel->getAttributeLabel('rtg_customer_recommend') ?><br>
                                    <?
                                    $this->widget('CStarRating', array(
                                        'model' => $ratingModel,
                                        'attribute' => 'rtg_customer_recommend',
                                        'minRating' => 1,
                                        'maxRating' => 10,
                                        'starCount' => 10,
                                        'value' => $ratingModel->rtg_customer_recommend,
                                        'readOnly' => true,
                                    ));
                                    ?>
                                </div>
                                <?php
                            }
                            if ($ratingModel->rtg_customer_overall) {
                                ?> 
                                <div class='col-xs-12 mt10'>
                                    <?= $ratingModel->getAttributeLabel('rtg_customer_overall') ?><br>
                                    <?
                                    $this->widget('CStarRating', array(
                                        'model' => $ratingModel,
                                        'attribute' => 'rtg_customer_overall',
                                        'minRating' => 1,
                                        'maxRating' => 5,
                                        'starCount' => 5,
                                        'value' => $ratingModel->rtg_customer_overall,
                                        'readOnly' => true,
                                    ));
                                    ?>
                                </div>
                                <?php
                            }
                            if ($ratingModel->rtg_customer_review) {
                                ?> 
                                <div class='col-xs-12  mt10'>
                                    <?= $ratingModel->getAttributeLabel('rtg_customer_review') ?> 
                                </div>
                                <div class="col-xs-12 p15 rounded mt5 mb10">
                                    <?= $ratingModel->rtg_customer_review;
                                    ?>
                                </div>
                                <?php
                            }
                            if ($ratingModel->rtg_customer_driver) {
                                ?> <div class='col-xs-12 mt10'>
                                    <?= $ratingModel->getAttributeLabel('rtg_customer_driver') ?><br>
                                    <?
                                    $this->widget('CStarRating', array(
                                        'model' => $ratingModel,
                                        'attribute' => 'rtg_customer_driver',
                                        'minRating' => 1,
                                        'maxRating' => 5,
                                        'starCount' => 5,
                                        'value' => $ratingModel->rtg_customer_driver,
                                        'readOnly' => true,
                                    ));
                                    ?></div>
                                <?php
                            }
                            if (($ratingModel->rtg_customer_driver <> NULL && $ratingModel->rtg_customer_driver < 4) && $ratingModel->rtg_customer_overall < 5) {
                                ?>
                                <div class="row col-xs-12 mt10">
                                    <div class="col-xs-3">On-time? <?= ($ratingModel->rtg_driver_ontime > 0) ? 'Yes' : 'No'; ?></div>
                                    <div class="col-xs-3">Soft Spoken? <?= ($ratingModel->rtg_driver_softspokon > 0) ? 'Yes' : 'No'; ?></div>
                                    <div class="col-xs-6">Respectfully dressed? <?= ($ratingModel->rtg_driver_respectfully > 0) ? 'Yes' : 'No'; ?></div>
                                </div>
                                <div class="row col-xs-12 mt10">
                                    <div class="col-xs-3">Helpful? <?= ($ratingModel->rtg_driver_helpful > 0) ? 'Yes' : 'No'; ?></div>
                                    <div class="col-xs-3">Drove Safely? <?= ($ratingModel->rtg_driver_safely > 0) ? 'Yes' : 'No'; ?></div>
                                    <div class="col-xs-6">Driver info matched the info provided by Gozo? <?= ($ratingModel->rtg_driver_vendor_mismatch > 0) ? 'No' : 'Yes'; ?></div>
                                </div>

                                <div class='col-xs-12 mt10'>Driver Comment</div>
                                <div class="col-xs-12 p15 rounded mt5 ">
                                    <?= $ratingModel->rtg_driver_cmt; ?>
                                </div>
                                <?php
                            }
                            if ($ratingModel->rtg_customer_csr) {
                                ?> <div class='col-xs-12 mt10'>
                                    <?= $ratingModel->getAttributeLabel('rtg_customer_csr') ?><br>
                                    <?
                                    $this->widget('CStarRating', array(
                                        'model' => $ratingModel,
                                        'attribute' => 'rtg_customer_csr',
                                        'minRating' => 1,
                                        'maxRating' => 5,
                                        'starCount' => 5,
                                        'value' => $ratingModel->rtg_customer_csr,
                                        'readOnly' => true,
                                    ));
                                    ?></div>
                                <?php
                            }
                            if (($ratingModel->rtg_customer_csr <> NULL && $ratingModel->rtg_customer_csr < 4) && $ratingModel->rtg_customer_overall < 5) {
                                if ($ratingModel->rtg_csr_polite == 1) {
                                    $csrPolite = "Yes";
                                } else if ($ratingModel->rtg_csr_polite == 0) {
                                    $csrPolite = "No";
                                } else if ($ratingModel->rtg_csr_polite == 2) {
                                    $csrPolite = "Didn't use";
                                }

                                if ($ratingModel->rtg_csr_professional == 1) {
                                    $csrProfessional = "Yes";
                                } else if ($ratingModel->rtg_csr_professional == 0) {
                                    $csrProfessional = "No";
                                } else if ($ratingModel->rtg_csr_professional == 2) {
                                    $csrProfessional = "Didn't use";
                                }

                                if ($ratingModel->rtg_csr_well_communicate == 1) {
                                    $csrCommunicate = "Yes";
                                } else if ($ratingModel->rtg_csr_well_communicate == 0) {
                                    $csrCommunicate = "No";
                                } else if ($ratingModel->rtg_csr_well_communicate == 2) {
                                    $csrCommunicate = "Didn't use";
                                }
                                ?>
                                <div class="row col-xs-12 mt10">
                                    <div class="col-xs-4">Polite & Helpful? <?= $csrPolite; ?></div>
                                    <div class="col-xs-4">Communication was clear? <?= $csrCommunicate; ?></div>
                                    <div class="col-xs-4">Professional? <?= $csrProfessional; ?></div>
                                </div>
                                <div class='col-xs-12 mt10'>CSR Comment</div>
                                <div class="col-xs-12 p15 rounded mt5 mb10">
                                    <?= $ratingModel->rtg_csr_cmt; ?>
                                </div>
                                <?php
                            }
                            if ($ratingModel->rtg_customer_car) {
                                ?>  <div class='col-xs-12 mt10'>
                                    <?= $ratingModel->getAttributeLabel('rtg_customer_car') ?><br>
                                    <?
                                    $this->widget('CStarRating', array(
                                        'model' => $ratingModel,
                                        'attribute' => 'rtg_customer_car',
                                        'minRating' => 1,
                                        'maxRating' => 5,
                                        'starCount' => 5,
                                        'value' => $ratingModel->rtg_customer_car,
                                        'readOnly' => true,
                                    ));
                                    ?>
                                </div>
                            <?php }
                            ?>
                        </div>

                        <?php
                        if (($ratingModel->rtg_customer_car <> NULL && $ratingModel->rtg_customer_car < 4) && $ratingModel->rtg_customer_overall < 5) {
                            ?>
                            <div class="row mt10">
                                <div class="col-xs-2">Clean? <?= ($ratingModel->rtg_car_clean > 0) ? 'Yes' : 'No'; ?></div>
                                <div class="col-xs-2">In good condition? <?= ($ratingModel->rtg_car_good_cond > 0) ? 'Yes' : 'No'; ?></div>
                                <div class="col-xs-2">Commercial? <?= ($ratingModel->rtg_car_commercial > 0) ? 'Yes' : 'No'; ?></div>
                                <div class="col-xs-6">Car license plate matched the info provided by Gozo? <?= ($ratingModel->rtg_car_vendor_mismatch > 0) ? 'No' : 'Yes'; ?></div>
                            </div>
                            <div class='mt10'>Car Comment</div>
                            <div class="col-xs-12 p15 rounded mt5 mb10">
                                <?= $ratingModel->rtg_car_cmt; ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            if ($ratingModel->rtg_csr_customer) {
                ?>
                <div class="col-xs-12">
                    <label class="mt10 control-label">CSR Rating</label>
                    <div class="col-xs-12 rounded pb10 pt10">
                        <div class="row">
                            <?php
                            if ($ratingModel->rtg_csr_customer) {
                                ?> <div class='col-xs-6'>
                                    <?= $ratingModel->getAttributeLabel('rtg_csr_customer') ?><br>
                                    <?
                                    $this->widget('CStarRating', array(
                                        'model' => $ratingModel,
                                        'attribute' => 'rtg_csr_customer',
                                        'minRating' => 1,
                                        'maxRating' => 5,
                                        'starCount' => 5,
                                        'value' => $ratingModel->rtg_csr_customer,
                                        'readOnly' => true,
                                    ));
                                    ?></div>
                                <?php
                            }
                            if ($ratingModel->rtg_csr_vendor) {
                                ?> <div class='col-xs-6'>
                                    <?= $ratingModel->getAttributeLabel('rtg_csr_vendor') ?><br>
                                    <?
                                    $this->widget('CStarRating', array(
                                        'model' => $ratingModel,
                                        'attribute' => 'rtg_csr_vendor',
                                        'minRating' => 1,
                                        'maxRating' => 5,
                                        'starCount' => 5,
                                        'value' => $ratingModel->rtg_csr_vendor,
                                        'readOnly' => true,
                                    ));
                                    ?></div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                        if ($ratingModel->rtg_csr_review) {
                            ?> 
                            <div class='mt20'>
                                <?= $ratingModel->getAttributeLabel('rtg_csr_review') ?> </div>
                            <div class="col-xs-12 p15 rounded mt10 mb10">
                                <?= $ratingModel->rtg_csr_review; ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    <?php }
    ?>
    <div class="row booking-log">
        <div class="col-xs-12 text-center">
            <label class = "control-label h3">Booking Log</label>
            <?
            Yii::app()->runController('rcsr/booking/showlog/booking_id/' . $model->bkg_id);
            ?>
        </div>
    </div>
</div>
    <script>

        var acctbox;
        $(document).ready(function () {
            $('#view').show();
            $('#edit').hide();
            var flag = '<?= $model->bkg_account_flag ?>';
            if (flag > '0') {
                $("#clearFlag").show();
                $("#setFlag").hide();
            } else {
                $("#setFlag").show();
                $("#clearFlag").hide();
            }

         
         

        });
        
        function confbooking(){
//          
//                        bootbox.confirm({
//                           message: "Confirm this booking by verifying customer phone",
//                           buttons: {
//                               confirm: {
//                                   label: 'OK',
//                                   className: 'btn-info'
//                               },
//                               cancel: {
//                                   label: 'CANCEL',
//                                   className: 'btn-danger'
//                               }
//                           },
//                           callback: function (result) {
//                               if(result){
                                           var bkg_id = <?=$model->bkg_id;?>;
                                           var href1 = '<?= Yii::app()->createUrl('rcsr/booking/confirmmobile') ?>';
                                           jQuery.ajax({'type': 'GET', 'url': href1,
                                               'data': {'bid':bkg_id},
                                               success: function (data) {
                                                   box = bootbox.dialog({
                                                       message: data,
                                                       title: '',
                                                       size: 'medium',
                                                       onEscape: function () {
                                                       }
                                                   });
                                               }
                                           }); 
//                               }
//                           }
//                       });    
                   
        }
        function showLog(booking_id) {
            $href = "<?= Yii::app()->createUrl('rcsr/booking/showlog') ?>";
            var $booking_id = booking_id;
            jQuery.ajax({type: 'GET',
                url: $href,
                data: {"booking_id": $booking_id},
                success: function (data)
                {
                    var box = bootbox.dialog({
                        message: data,
                        title: 'Booking Log',
                        onEscape: function () {
                        }, });
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }
            });
        }

        function addCSRRating(booking_id) {
            $href = "<?= Yii::app()->createUrl('rcsr/rating/addcsrreview') ?>";
            var $booking_id = booking_id;
            jQuery.ajax({type: 'GET',
                url: $href,
                data: {"bkg_id": $booking_id},
                success: function (data)
                {
                    var box = bootbox.dialog({
                        message: data,
                        title: 'Add CSR Review',
                        onEscape: function () {
                        }});
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }
            });
        }

        function addCustRating(booking_id) {
            $href = "<?= Yii::app()->createUrl('rcsr/rating/addcustreview') ?>";
            jQuery.ajax({type: 'GET',
                url: $href,
                data: {"bkg_id": booking_id},
                success: function (data)
                {
                    var box = bootbox.dialog({
                        message: data,
                        title: 'Add Customer Review',
                        onEscape: function () {
                        }
                    });
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }
            });
        }
        function save() {


            $('#edit').hide();
            $('#view').show();
        }

        //    function refreshAccountDetails() {
        //        jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/view')) ?>',
        //            success: function (data)
        //            {
        //                $('#acctdata').html(data);
        //            }
        //        });
        //    }

        var refreshAccountDetails = function () {
            jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('rcsr/booking/view', ['view' => 'accountsdetail', 'id' => $model->bkg_id])) ?>',
                success: function (data)
                {
                    $('#acctdata').html(data);
                    $('.bootbox').removeAttr('tabindex');
                }
            });
        };
        function editAccount() {
            //   $('#view').hide();
            //    $('#edit').show();

            booking_id = '<?= $model->bkg_id ?>';
            $href = "<?= Yii::app()->createUrl('rcsr/booking/editaccounts') ?>";
            jQuery.ajax({type: 'GET',
                url: $href,
                data: {"bkg_id": booking_id},
                success: function (data)
                {
                    acctbox = bootbox.dialog({
                        message: data,
                        title: 'Edit Accounts Details',
                        size: 'large',
                        onEscape: function () {

                        }
                    });
                    acctbox.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }
            });
        }
        function addDesc(opt) {            
            booking_id = '<?= $model->bkg_id ?>';
            $href = "<?= Yii::app()->createUrl('rcsr/booking/addaccountingremark') ?>";
            jQuery.ajax({type: 'GET',
                url: $href,
                data: {"bkg_id": booking_id},
                success: function (data)
                {
                    acctbox1 = bootbox.dialog({
                        message: data,
                        title: 'Add remarks for Accounting Flag',
                        size: 'xs',
                        onEscape: function () {

                        }
                    });
                    acctbox1.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });

                    return true;
                }
            });
        }


   


        function showFlightStatus(bkgId) {
            jQuery.ajax({type: 'GET',
                url: '<?= Yii::app()->createUrl('rcsr/booking/flightstatus'); ?>',
                dataType: 'json',
                data: {'bkgId': bkgId},
                success: function (data) {
                    if (data.success) {
                        var actualdepart = (data.actualDepartTime == null || data.actualDepartTime == 'null' || data.actualDepartTime == undefined) ? '&nbsp;' : data.actualDepartTime;
                        var actualarrive = (data.actualArriveTime == null || data.actualArriveTime == 'null' || data.actualArriveTime == undefined) ? '&nbsp;' : data.actualArriveTime;
                        var delayarrive = (data.delayArrive == null || data.delayArrive == 'null' || data.delayArrive == undefined) ? '&nbsp;' : data.delayArrive + " minutes";
                        var arriveTerminal = (data.arriveTerminal == null || data.arriveTerminal == 'null' || data.arriveTerminal == undefined) ? '&nbsp;' : data.arriveTerminal;
                        var html = "<div><b>Status :</b>" + data.status +
                                "<br><br><b>From :</b>" + data.from + "<br><br><b>To :</b>" + data.to +
                                "<br><br><b>Scheduled Departure :</b>" + data.scheduledDepartTime + "<br><br><b>Scheduled Arrival :</b>" + data.scheduledArriveTime +
                                "<br><br><b>Actual Departure :</b>" + actualdepart + "<br><br><b>Actual Arrival :</b>" + actualarrive +
                                "<br><br><b>Delay Arrival :</b>" + delayarrive + "<br><br><b>Arrival Terminal :</b>" + arriveTerminal +
                                "</div>";
                        var flightbootbox = bootbox.dialog({
                            message: html,
                            title: 'Track Flight',
                            onEscape: function () {
                            }
                        });
                        flightbootbox.on('hidden.bs.modal', function (e) {
                            $('body').addClass('modal-open');
                        });
                    } else {
                        alert(data.msg);
                    }



                },
                error: function (x) {
                    alert(x);
                }
            });
        }

        function showLinkedUser(user, url) {
            if (user > 0)
            {
                jQuery.ajax({type: 'GET',
                    url: url,
                    dataType: 'html',
                    data: {"user": user},
                    success: function (data)
                    {
                        showuser = bootbox.dialog({
                            message: data,
                            title: 'User Details',
                            size: 'large',
                            onEscape: function () {
                            }
                        });
                        showuser.on('hidden.bs.modal', function (e) {
                            $('body').addClass('modal-open');
                        });
                        return true;
                    },
                    error: function (x) {
                        alert(x);
                    }
                });
            }
        }
        function viewList(obj) {
            var href2 = $(obj).attr("href");

            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "html",
                "success": function (data) {
                    var box = bootbox.dialog({
                        message: data,
                        title: 'Booking Details',
                        size: 'large',
                        onEscape: function () {
                            // user pressed escape
                        },
                    });
                }
            });
            return false;
        }

        function showContact() {
            bkgId = '<?= $model->bkg_id ?>';
            if (bkgId > 0) {

                $href = "<?= Yii::app()->createUrl('rcsr/booking/getcontacts') ?>";


                jQuery.ajax({type: 'GET',
                    url: $href,
                    dataType: 'json',
                    data: {"bkgId": bkgId},
                    success: function (data) {
                        if (data.success) {
                            $("#userEmail").text(data.email);
                            $("#userPhone").text(data.phone);
                            $("#userAltPhone").text(data.altPhone);
                            $("#showContactDetails").hide();
                        } else {
                            alert("Sorry error occured");
                        }
                    },
                    error: function (x) {
                        alert(x);
                    }
                });
            }
        }
        function showAgentNotifyDefault() {
            $('#showagentnotifydefaults').toggleClass('hide');
        }

    </script>
    <?php
    $version = Yii::app()->params['customJsVersion'];
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
    ?>