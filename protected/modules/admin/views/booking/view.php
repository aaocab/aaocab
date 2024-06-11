<?php
/* @var $model Booking */
//$cityList = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
$status			 = Booking::model()->getBookingStatus();
//$adminlist = Admins::model()->findNameList();
//$statuslist = Booking::model()->getActiveBookingStatus();
$reconfirmStatus = Booking::model()->getReconfirmStatus();
$cancelDetail	 = CancelReasons::model()->findByPk($model->bkg_cancel_id);
$rutInfo		 = [];

$cntRut			 = count($bookingRouteModel);
$spclInstruction = $model->getFullInstructions();
$vencabdriver	 = $model->getBookingCabModel();

$vehicleModel = $vencabdriver->bcbCab->vhcType->vht_model;
if ($vencabdriver->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($vencabdriver->bcb_vendor_id, $vencabdriver->bcb_cab_id);
}
$infosource			 = BookingAddInfo::model()->getInfosource('admin');
$isDboMaster		 = Yii::app()->params['dboMaster'];
$criticalityFactor	 = $maxout				 = $cng				 = $escalate			 = $dutySlip			 = $drvAppRequired		 = $selfAssignedTrue	 = $dboflag			 = $assignmentBadges	 = $demSupBadge		 = $assignMode			 = $needSupply			 = $followup			 = $teamBatch			 = $accountFlag		 = $cancelFlag			 = $gnowFlag;
//$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
//if ($response->getStatus())
//{
$fname				 = $model->bkgUserInfo->bkg_user_fname;
$lname				 = $model->bkgUserInfo->bkg_user_lname;
//}

if ($model->bkgBcb->bcb_is_max_out == 1)
{
	$maxout = " <button type='button' title='Manually Trigger Assignment' class='label  border-01'>Manually Trigger Assignment SET</button>";
}
if ($model->bkgPref->bkg_cng_allowed == 1 && $model->bkgAddInfo->bkg_num_large_bag < 2)
{
	$cng = " <button type='button' title='CNG ALLOWED' class='label  border-01'>CNG ALLOWED</button>";
}
if ($model->bkgPref->bkg_cng_allowed == 1 && $model->bkgAddInfo->bkg_num_large_bag > 1)
{
	$cng = " <button type='button' title='CNG ALLOWED WITH CARRIER' class='label  border-01'>CNG ALLOWED WITH CARRIER</button>";
}
if ($model->bkgPref->bkg_cng_allowed == 0)
{
	$cng = " <button type='button' title='CNG NOT ALLOWED' class='label  border-05'>CNG NOT ALLOWED</button>";
}

if ($model->bkgTrack->btk_drv_details_viewed == 1)
{
	$drvDetailsViewed = " <button type='button' title='Driver and Car details have been viewed by Customer. Cancellation charges now apply.' class='label  border-05'>DRIVER DETAILS VIEWED</button>";
}
if ($model->bkgTrack->btk_cust_details_viewed == 1)
{
	$custDetailsViewed = " <button type='button' title='Customer details have been viewed by driver.' class='label  border-05'>CUSTOMER DETAILS VIEWED</button>";
}
if ($model->bkgTrail->bkg_escalation_status == 1)
{
	$escalatedLbl	 = $model->bkgTrail->btr_escalation_level;
	$lbl			 = BookingTrail::model()->escalation[$escalatedLbl]['color'];
	$escalate		 = " <button type='button' title='Escalated booking' onclick='adminAction(21," . $model->bkg_id . ",2)' class='label  border-01' >ESCALATED ( " . strtoupper($lbl) . " ) </button>";
	if ($model->bkgTrail->btr_escalation_assigned_team != NULL)
	{
		$assignedTeams	 = explode(',', $model->bkgTrail->btr_escalation_assigned_team);
		$teamBatch		 = "";
		foreach ($assignedTeams as $value)
		{
			$team		 = Teams::getByID($value);
			$teamBatch	 .= " <button type='button' title='Escalated booking' class='label  border-01'>E: $team </button>";
		}
	}
}
$tagBtnList = '';
if ($model->bkgTrail->bkg_tags != '')
{
	$tagList = Tags::getListByids($model->bkgTrail->bkg_tags);
	foreach ($tagList as $tag)
	{
		if($tag['tag_color']!='')
		{
			$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10' style='background:".$tag['tag_color']."'>" . $tag['tag_name'] . "</span>";
		}
		else
		{
			$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10' >" . $tag['tag_name'] . "</span>";
		}
	}
	//$custDetailsViewed = " <button type='button' title='Customer details have been viewed by driver.' class='label  border-05'>CUSTOMER DETAILS VIEWED</button>";
}
if ($model->bkgPref->bkg_duty_slip_required == 1)
{
	$dutySlip = " <button type='button' title='Duty Slip Required' class='label  border-03'>DUTY-SLIP REQUIRED </button>";
}
if ($model->bkgPref->bkg_driver_app_required == 1)
{
	$drvAppRequired = " <button type='button' title='Driver App Required' class='label  border-01'>DRIVER APP REQUIRED</button>";
}
if ($model->bkgPref->bpr_vnd_recmnd == 1)
{
	$selfAssignedTrue = " <button type='button' title='Self Assigned ' class='label  border-01'>SELF ASSIGNED</button>";
}
if ($model->bkgPref->bkg_critical_score > 0)
{
	$criticalityFactor = " <button type='button' title='Criticality Factor' class='label  border-04'>CF : " . round($model->bkgPref->bkg_critical_score, 2) . "</button>";
}
if ($model->bkgPref->bkg_account_flag == 1)
{
	$accountFlag = " <button type='button' title='Accounts Review Needed' class='label  border-01'>ACCOUNTING FLAG SET</button>";
}
if ($isDboMaster == 1 && $model->bkgTrail->btr_is_dbo_applicable == 1)
{
	$dboflag = " <button type='button' title='DBO Applicable' class='label  border-06'>DBO</button>";
}
if ($model->bkgPref->bkg_critical_assignment == 1)
{
	$assignmentBadges = " <button type='button' title='Critical Assignment' class='label  border-03'>CRITICAL</button>";
}

if ($model->bkgTrail->btr_is_dem_sup_misfire == 1)
{
	$demSupBadge = " <button type='button' title='Demand Supply Miss Fire' class='label  border-09'>DEM-SUP-MISFIRE</button>";
}

if ($model->bkgBcb->bcb_assign_mode == 0 && in_array($model->bkg_status, [3, 5, 6]))
{
	$assignMode = " <button type='button' title='Auto Assigned' class='label  border-06'>AUTO ASSIGNED</button>";
}
if ($model->bkgBcb->bcb_assign_mode == 1 && in_array($model->bkg_status, [3, 5, 6]))
{
	$assignMode = " <button type='button' title='Manual Assigned' class='label  border-06'>MANUAL ASSIGNED</button>";
}
if ($model->bkgBcb->bcb_assign_mode == 2 && in_array($model->bkg_status, [3, 5, 6]))
{
	$assignMode = " <button type='button' title='Manual Assigned' class='label  border-06'>Direct Accept</button>";
}
if ($isSupply > 0)
{
	$needSupply = " <button type='button' title='Need More Supply' class='label  border-06'>NEED MORE SUPPLY</button>";
}
if ($model->bkgTrail->bkg_followup_active == 1)
{
	$followup = " <button type='button' title='Followup Pending' class='label  btn-danger' onclick='adminAction(41,$model->bkg_id,2)'>FOLLOWUP PENDING</button>";
}
if ($model->bkgPref->bpr_uncommon_route == 1)
{
	$assignMode = " <button type='button' title='Uncommon Route' class='label btn-success'>UNCOMMON ROUTE</button>";
}
if ($model->bkgTrail->btr_auto_cancel_value == 1)
{
	$cancelFlag = " <button type='button' title='Auto cancel Flag ON' class='label  border-01'>AUTO CANCEL FLAG ON</button>";
}
if ($model->bkgPref->bkg_is_gozonow == 1)
{
	$gnowFlag = " <button type='button' title='GOZONOW' class='label  border-06'>GOZONOW</button>";
}
if (!is_null($model->bkgTrail->bkg_quote_expire_date) && $model->bkgTrail->bkg_quote_expire_date != '' && in_array($model->bkg_status, array(1, 15)))
{
	$qtExpDate			 = Filter::getDateFormatted($model->bkgTrail->bkg_quote_expire_date);
	$tagQuoteExpireDate	 = " <button type='button' title='Quote Expire: {$qtExpDate}' class='label border-01'>Quote Expire: {$qtExpDate}</button>";
}
if (!is_null($model->bkgTrail->bkg_payment_expiry_time) && $model->bkgTrail->bkg_payment_expiry_time != '' && in_array($model->bkg_status, array(1, 15)))
{
	$payExpDate			 = Filter::getDateFormatted($model->bkgTrail->bkg_payment_expiry_time);
	$tagPayExpireDate	 = " <button type='button' title='Payment Expire: {$payExpDate}' class='label border-01'>Payment Expire: {$payExpDate}</button>";
}

$policyType = CancellationPolicyDetails::model()->findByPk($bkgPref->bkg_cancel_rule_id)->cnp_code; //CancellationPolicy::getPolicyType($bkgPref->bkg_cancel_rule_id, $model->bkg_agent_id);
if ($policyType != '')
{
	$policyTypeabel = " <button type='button' title='Cancellation Policy Type' class='label border-06'>CP : $policyType</button>";
}
if ($model->bkgPref->bpr_rescheduled_from > 0)
{
	$prevBookingId		 = Booking::model()->getCodeById($model->bkgPref->bpr_rescheduled_from);
	$rescheduledLabel	 = " <button type='button' title='Rescheduled Booking' class='label border-06'>Rescheduled Previuos Booking ID : {$prevBookingId}</button>";
}

if ($model->bkg_cav_id > 0 && $model->bkgPref->bpr_is_flash == 1)
{
	$flashsale = " <button type='button' title='FLASH SALE' class='label  border-01'>FLASH SALE</button>";
}
?>

<?php
$cityCategory		 = \CitiesStats::getCategory($model->bkg_from_city_id);
$cancellationRule	 = \CancellationPolicy::getPolicy($cityCategory, $model->bkgSvcClassVhcCat->scv_scc_id);
$cancellationType	 = ($model->bkg_agent_id == 18190) ? "( " . $cancellationRule['rule'] . " )" : '';
if ($cntRut > 0)
{
	$diffdays = 0;
	foreach ($bookingRouteModel as $key => $bookingRoute)
	{
		$rutName		 = $bookingRoute->brtFromCity->cty_full_name . ' to ' . $bookingRoute->brtToCity->cty_full_name;
		$pickLoc		 = $bookingRoute->brt_from_location;
		$pickDateTime	 = DateTimeFormat::DateTimeToDatePicker($bookingRoute->brt_pickup_datetime) . " " . DateTimeFormat::DateTimeToTimePicker($bookingRoute->brt_pickup_datetime);
		$dist			 = $bookingRoute->brt_trip_distance . 'Km';
		$dura			 = Filter::getDurationbyMinute($bookingRoute->brt_trip_duration);

		if ($key == 0)
		{
			$diffdays = 1;
		}
		else
		{

			$date1		 = new DateTime(date('Y-m-d', strtotime($bookingRouteModel[0]->brt_pickup_datetime)));
			$date2		 = new DateTime(date('Y-m-d', strtotime($bookingRoute->brt_pickup_datetime)));
			$difference	 = $date1->diff($date2);
			$diffdays	 = ($difference->d + 1);
		}

		$last_date	 = date('Y-m-d H:i:s', strtotime($bookingRoute->brt_pickup_datetime . '+ ' . $bookingRoute->brt_trip_duration . ' minute'));
		$rutInfo[]	 = ['rutName'		 => $rutName, 'pickLoc'		 => $pickLoc, 'pickDateTime'	 => $pickDateTime,
			'dist'			 => $dist, 'dura'			 => $dura, 'diffdays'		 => $diffdays, 'last_date'		 => $last_date];
	}
}
?>

<style type="text/css">
    .flex {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
    }
    .rounded-margin{
		margin: 0 15px;
	}
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
        padding:3px;
		max-width:100%
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
    .box-design1{
		background: #8DCF8A;
		color: #000;
		padding: 10px;
	}
    .box-design1a{
		background: #ccffcc;
		color: #000;
	}

    .box-design2{
		background: #F8A6AC;
		color: #000;
		padding: 10px;
	}
    .box-design2a{
		background: #ffcccc;
		color: #000;
	}
    .label-tab label{
		margin:0 17%!important
	}
    .label-tab .form-group{
		margin-bottom: 0;
	}
	table>tbody>tr>th{
		vertical-align:middle !important;
		font-weight:bold
	}
    .border-01{
		border: 1px #EF5350 solid;
		color: #EF5350;
		background: #fff;
	}
    .border-02{
		border: 1px #EC407A solid;
		color: #EC407A;
		background: #fff;
	}
    .border-03{
		border: 1px #AB47BC solid;
		color: #AB47BC;
		background: #fff;
	}
    .border-04{
		border: 1px #7E57C2 solid;
		color: #7E57C2;
		background: #fff;
	}
    .border-05{
		border: 1px #5C6BC0 solid;
		color: #5C6BC0;
		background: #fff;
	}
    .border-06{
		border: 1px #42A5F5 solid;
		color: #42A5F5;
		background: #fff;
	}
    .border-07{
		border: 1px #388E3C solid;
		color: #388E3C;
		background: #fff;
	}
    .border-08{
		border: 1px #689F38 solid;
		color: #689F38;
		background: #fff;
	}
    .border-09{
		border: 1px #FFAA00 solid;
		color: #FFAA00;
		background: #fff;
	}



    ol {
        list-style: none; /* Remove default bullets */
    }

    ol li::before {
        content: "\2022";  /* Add content: \2022 is the CSS Code/unicode for a bullet */
        color: #000; /* Change the color */
        font-weight: bold; /* If you want it to be bold */
        display: inline-block; /* Needed to add space between the bullet and the text */
        width: 1em; /* Also needed for space (tweak if needed) */
        margin-left: -1em; /* Also needed for space (tweak if needed) */
    }

	.bg-green3 {
		background: #00a388;
	}
	.mb-1, .my-1 {
		margin-bottom: 0.25rem!important;
	}
	.bg-orange {
		background: #f36c31;
	}
	.bg-red {
		background: #ef2b2b;
	}
	.float-right {
		float: right!important;
	}
	.float-left {
		float: left!important;
	}
</style>
<?php $name = Admins::model()->findById($userInfo->userId); ?>
<div id="test" >
    <div class="row" style="background: #f9f109f2; color: #000;">
        <div class="col-xs-12 text-center mt10" style="background: #f9f109f2;" >
            <a id="test" class="" style="color:#000;font-size:15px"><?= date("Y/m/d g:i A"); ?>   Booking opened by : <?= $name['adm_fname']; ?> <?= $name['adm_lname'] ?>
                <span id="demo"></span>
                <span class="btn btn-info btn-sm mb5 mr5" onclick="adminAction(21,<?= $model->bkg_id ?>, 2, '')">ADD REMARKS</span>
            </a>
            <input type="hidden" id="seconedb" name="seconedb">
            <input type="hidden" id="isAddRemark" name="isAddRemark" value="">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-center mt0">
		<span class=" h2 text-center">
            <label for="type" class="control-label"><span style="font-weight: normal; font-size: 30px;">Booking Id:</span> </label>
            <b><?= $model->bkg_booking_id ?></b>
            <label>
				<?php
				if ($model->bkg_agent_id > 0)
				{
					$agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
					if ($agentsModel->agt_type == 1)
					{
						echo "<button type='button' title='Corporate Booking' class='label  border-08'>Corporate</button>";
					}
					else
					{
						echo "<button type='button' title='Partner Booking' class='label  border-08'>Partner</button>";
					}
				}
				?></label>

        </span>

        <br /><?= $maxout . $needSupply . $cng . $escalate . $teamBatch . $dutySlip . $drvAppRequired . $selfAssignedTrue . $criticalityFactor . $dboflag . $assignmentBadges . $demSupBadge . $assignMode . $followup . $accountFlag . $cancelFlag . $drvDetailsViewed . $custDetailsViewed . $policyTypeabel . $gnowFlag . $rescheduledLabel . $tagQuoteExpireDate . $flashsale; ?></div>
</div>
<br />
<div class="row">
    <div class="col-xs-12 mb20">
        <div style="text-align: center" class="below-buttons">
			<?php $button_type = 'label'; ?>
			<?= $model->getActionButton([], $button_type); ?>
			<?php $ratingModel = Ratings::model()->getRatingbyBookingId($model->bkg_id); ?>
			<?php
			if (($model->bkg_status > 4 && $model->bkg_status != 15) && $ratingModel->rtg_customer_overall == '')
			{
				?>
				<a class="btn btn-info mt5" id="review" onclick="addCustRating(<?= $model->bkg_id ?>)" title="Add Customer Review"><i class="fa fa-star-o"></i> Add Customer Review</a>
			<?php } ?>
			<?php
			if (($model->bkg_status > 4 && $model->bkg_status != 15) && $ratingModel->rtg_csr_customer == '')
			{
				?>
				<a class="btn btn-info mt5 ml5" id="review" onclick="addCSRRating(<?= $model->bkg_id ?>)" title="Add CSR Review"><i class="fa fa-star-o"></i> Add CSR Review</a>
			<?php } ?>
			<?php
			if (($model->bkg_status > 4 && $model->bkg_status != 15) && !empty(sizeof($bkgDriverAppinfo)))
			{
				?>
				<a class="btn btn-info mt5 ml5" id="review" onclick="showTripStatus(<?= $model->bkg_id ?>)" title="View Trip Status"><i class="fa fa-star-o"></i> View Trip Status </a>
			<?php } ?>

			<?php
			if ($model->bkg_status == '6' || $model->bkg_status == '7')
			{
				$url = Yii::app()->createUrl('admin/account/accountlist', ['bkgid' => $model->bkg_id, 'bcbid' => $model->bkg_bcb_id]);
				?>
				<a target="_blank" href="<?= $url ?>"><button class="btn btn-info">Show Account</button></a>
			<?php } ?>
			<?php
			if (in_array($model->bkg_status, [2, 3, 4, 5, 6, 10]))
			{
				?>
				<a class="btn btn-info mt5" id="rating" onclick="showVendorRating(<?= $model->bkg_id ?>)" title="Show Vendor Bid Rank"><i class="fa fa-star-o"></i> View Bid Rank</a>
			<?php } ?>
			<?= $model->getActionButton([], $button_type, '1'); ?>   
			<?php
			if ($model->bkg_status == 9 && Yii::app()->user->checkAccess('changeCancelReason'))
			{
				?>
				<a class="btn btn-danger mt5 ml5" id="review" onclick="changecancelreason(<?= $model->bkg_id ?>)" title="Change Cancel Reason"><i class="fa fa-star-o"></i> Change Cancel Reason</a>
			<?php } ?>
        </div>
    </div>
</div>
<?php
$pgmodel = PaymentGateway::model()->getImmediateEmptystatus($model->bkg_id);
if ($pgmodel)
{
	?>
	<div class="text-right mb20">

		<button type='button' title='Corporate Booking' onclick="getPaymentStatus()" class='btn btn-warning mt20 n'>Track Payment Status</button>
	</div>
	<?php
}
?>
<div id="view">
    <div class="row" >
        <div class="col-xs-6 text-left">
			<?php
//if (($model->bkg_status == 15 && $isConfirmCash==0 && ($model->bkg_agent_id == null || $model->bkg_agent_id == '' || $model->bkg_agent_id == 0) && $model->bkg_vehicle_type_id != 114 && $userInfo->userType == 4) || ($model->bkg_status == 1 && $model->bkg_vehicle_type_id != 114 && $userInfo->userType == 4 && ($userInfo->userId == $model->bkgTrail->bkg_assign_csr || Yii::app()->user->checkAccess('7 - Admin')) && ($model->bkg_agent_id == null || $model->bkg_agent_id == '' || $model->bkg_agent_id == 0)))
			$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
			if (($model->bkg_status == 15 && $isConfirmCash == 0 && ($model->bkg_agent_id == null || $model->bkg_agent_id == '' || $model->bkg_agent_id == 0) && $scvVctId != VehicleCategory::SHARED_SEDAN_ECONOMIC && $userInfo->userType == 4) && Yii::app()->user->checkAccess('confirmAsCashBooking'))
			{
				if ($model->bkg_reconfirm_flag == 1 && ($model->bkgUserInfo->bkg_email_verified == 1 || $model->bkgUserInfo->bkg_phone_verified == 1))
				{
					?>
					<a class="btn btn-info btn-sm" title="Confirm as cash booking" onclick="confbooking();">Confirm as cash booking</a>
					<?php
				}
				else
				{
					?>
					<a class="btn btn-info btn-sm" title="Confirm as cash booking" onclick="verifyAlert();">Confirm as cash booking</a>
					<?php
				}
			}
			if($model->bkg_agent_id > 0 && $model->bkg_status == 15)
			{ ?>
				<a class="btn btn-info btn-sm" title="Confirm booking" onclick="confirmPartnerBooking('<?=$model->bkg_id?>');">Confirm Booking</a>
			<?php }
			
			if ($model->bkg_status == 1)
			{
				if ($model->bkgPref->bkg_critical_score < 0.7)
				{
					?>
					<a class="btn btn-info btn-sm" title="Convert to quote" onclick="convertToquote();">Convert to quote</a>
					<?
				}
				else
				{
					?>
					<a class="btn btn-info btn-sm" title="Convert to quote" onclick="convertAlert();">Convert to quote</a>
					<?php
				}
			}
//if ($model->bkgTrail->bkg_quote_expire_max_date != '')
//{
			?>
			<!--				<a class="btn btn-info btn-sm" onclick="return priceLockSubmit();" title="price lock edit">Edit Price Lock</a>-->
			<?php
			//}
			?>

		</div>
		<div class="col-xs-12 text-right">

			<?php
			$checkAccess	 = Yii::app()->user->checkAccess('bookingComplete');
			$pickupDiff		 = $model->getPickupDiffinMinutes();
			$duration		 = $model->bkg_trip_duration;
			$completeTime	 = $pickupDiff + $duration;
			if ($model->bkg_status == 5 && $completeTime < 0 && $checkAccess)
			{
				?>
				<button type="button" class="btn btn-success" onclick="completeBooking(<?= $model->bkg_id ?>);" >Mark Complete</button>
				<?php
			}


			$nextthreehr	 = date('Y-m-d H:i:s', strtotime('+180 min'));
			$pickupDateTime	 = date('Y-m-d H:i:s', strtotime($model->bkg_pickup_date));
			if ($pickupDateTime < $nextthreehr && (in_array($model->bkg_status, [3, 5])))
			{
				?>
				<?php
				$driverForBooking	 = $model->bkgBcb->bcb_driver_id;
				$drvStat			 = DriverStats::model()->getLastLocation($driverForBooking);
				if (!empty($driverForBooking) && !empty($drvStat) && is_array($drvStat) && !empty($drvStat['drv_last_loc_date']))
				{
					$buttontext = "Driver Location @ " . date("d/m/Y h:i A", strtotime($drvStat['drv_last_loc_date']));
				}
				else
				{
					$buttontext = "Driver Location";
				}
				?>
				<a class="btn btn-success btn-sm"  onclick="drvLocation(<?= $model->bkg_id ?>)" title="Driver Location" > <?php echo $buttontext; ?></a>
				<?php
			}
			if (in_array($model->bkg_status, array(6, 7, 9)))
			{
				?>
				<button type="button" class="btn btn-info btn-sm" onclick="viewVendorCompensation(<?= $model->bkg_id ?>);" >Vendor Compensation</button>
				<?php
			}
			if (Yii::app()->user->checkAccess('penaltyRemove') && in_array($model->bkg_status, array(2, 3, 5, 6, 7, 9)))
			{
				?>
				<button type="button" class="btn btn-primary" onclick="viewPenalty(<?= $model->bkg_id ?>);" >View Penalty</button>
				<?php
			}
			if (Yii::app()->user->checkAccess('changeVendorAmount') && in_array($model->bkg_status, array(2, 15)))
			{
				?>
				<button type="button" class="btn btn-warning" onclick="updateVendorAmount(<?= $model->bkg_id ?>);" >Update Vendor Amount</button>
				<?php
			}
			if (Yii::app()->user->checkAccess('OneTimePriceAdjustment') && in_array($model->bkg_status, array(2, 3, 4, 5, 15)))
			{
				?>
				<button type="button" class="btn btn-primary" onclick="extraDiscount(<?= $model->bkg_id ?>);" >One-Time Price Adjustment</button>
			<?php } ?>
			<button type="button" class="btn btn-primary" onclick="viewDutySlip(<?= $model->bkg_id ?>);" >View Attachments</button>
			<?php
			if ($model->bkgTrail->btr_is_datadiscrepancy > 0)
			{
				?>
				<button type="button" class="btn btn-danger" onclick="showTripStatus(<?= $model->bkg_id ?>)" >Data Discrepancy (<?php echo $model->bkgTrail->btr_is_datadiscrepancy ?>)</button>
			<?php 
			} 
			$accessClearAccountingFlag = Yii::app()->user->checkAccess('clearAccountingFlag');
			if($accessClearAccountingFlag)
			{
			?>
			<!--            <a class="btn btn-primary btn-sm" id="setFlag" style="display: none;" onclick="addDesc('0')" title="Set accounting flag" >Set accounting flag</a> -->
			<a class="btn btn-success btn-sm" id="clearFlag" style="display: none;" onclick="accountFlag(<?= $model->bkg_id ?>, '1')" title="Clear accounting flag" >Clear accounting flag</a>
			<?php
			}
			if ($model->bkgBcb->bcb_lock_vendor_payment == 1 && ($model->bkg_status == '6' || $model->bkg_status == '5' || $model->bkg_status == '7') && Yii::app()->user->checkAccess('releaseVendorPayment'))
			{
				?>
				<a class="btn btn-success btn-sm" id="releaseAmt" style="display: none;" onclick="modifiedPaymentStatus('<?= $model->bkg_id ?>', '<?= $model->bkg_bcb_id ?>', '2');" title="Release vendor payment." >Release Vendor Payment</a>
				<?php
			}
			if ($model->bkgBcb->bcb_lock_vendor_payment == 2 || $model->bkgBcb->bcb_lock_vendor_payment == 0 && ($model->bkg_status == '6' || $model->bkg_status == '5') && Yii::app()->user->checkAccess('accountEdit'))
			{
				?>

				<a class="btn btn-danger btn-sm" id="lockAmt" style="display: none;" onclick="modifiedPaymentStatus('<?= $model->bkg_id ?>', '<?= $model->bkg_bcb_id ?>', '1');" title="DO NOT release vendor payment until current issues are resolved" >Stop Vendor Payment</a>
			<?php } ?>
			<?php
			if ($model->bkg_status != '6' && $model->bkg_status != '7')
			{
				?>
				<a class="btn btn-info btn-sm" id="bkg_acct" onclick="editAccount('<?php echo $isRestricted ?>')" title="Edit Account Details">Edit Account Details</a>
				<?php
			}
			$checkaccess = Yii::app()->user->checkAccess('updateDrvAppUsage');
			$diffMinutes = (DBUtil::getTimeDiff($model->bkg_pickup_date, DBUtil::getCurrentTime()));
			if ($checkaccess && in_array($model->bkg_status, array(2, 3, 5)) && $diffMinutes <= 15)
			{
				?>
				<a class="btn btn-info btn-sm" id="refresh" onclick="toggleDrvAppUsage()" title="Change Driver App Requirement Usage"><?php echo ($model->bkgPref->bkg_driver_app_required == 1) ? "Turn Off Driver App Requirement" : "Turn On Driver App Requirement" ?></a>
				<?php
			}
			$checkaccess				 = Yii::app()->user->checkAccess('reschedulePickupTime');
			$checkRescheduleDriverAssign = Yii::app()->user->checkAccess('reschedulePickupTimeDriverAssigned');
			if (($checkaccess && in_array($model->bkg_status, array(1, 2, 3, 15))) || ($checkRescheduleDriverAssign && in_array($model->bkg_status, array(1, 2, 3, 5, 15))))
			{
				?>
				<a class="btn btn-success btn-sm" id="bkg_reschedule_pickup" onclick="editPickupTime()" title="Reschedule Pickup Time">Reschedule Pickup Time (Hr)</a>
				<?php
			}
			$getBookingLogInfo	 = BookingLog::model()->getRescheduleTimeLog($model->bkg_id);
			$bkgPrefModel		 = BookingPref::model()->findBySql("SELECT 1 FROM booking_pref WHERE bpr_rescheduled_from = {$model->bkg_id}");
			if ($checkaccess && in_array($model->bkg_status, array(2, 3, 5)) && $model->bkgPref->bkg_is_gozonow != 1 && $model->bkgPref->bpr_rescheduled_from == 0)
			{
				$isRescheduled = ($bkgPrefModel != '' || $getBookingLogInfo != '') ? 1 : 0;
				?>
				<a class="btn btn-success btn-sm" id="bkg_reschedule_pickup" onclick="reschedule(<?= $isRescheduled ?>)" title="Reschedule Booking">Reschedule Booking</a>
				<?php
			}
			$checkaccess = Yii::app()->user->checkAccess('stopIncreasingVendorAmount');
			if ($model->bkg_status == 2 && ($checkaccess || $model->bkgPref->bkg_manual_assignment == 1))
			{
				$btnIncVndAmt = 'Stop Increasing Vendor Amount';
				if ($model->bkgTrail->btr_stop_increasing_vendor_amount == 1)
				{
					$btnIncVndAmt = 'Stopped Increasing Vendor Amount';
				}
				?>
				<a class="btn btn-success btn-sm" id="refresh" onclick="stopSystemMaxAllowableVndAmount()" title="<?php echo $btnIncVndAmt; ?>"><?php echo $btnIncVndAmt; ?></a>
				<?php
			}
			if ($model->bkgTrail->btr_drv_api_sync_error)
			{
				?>
				<a class="btn btn-success btn-sm" target="_blank" href="/admpnl/driver/syncfail?bookingId=<?php echo $model->bkg_booking_id; ?>" title="Show Sync Fail Log">Show Sync Fail Log</a>
			<?php } ?>
		</div>  
	</div> 

	<div class="row">
		<div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
			<div class="row p20">
				<div class="col-xs-12 heading_box">Booking Information</div>
				<div class="col-xs-12 main-tab1">
					<div class="row new-tab-border-b">
						<div class="col-xs-12 col-sm-6 new-tab-border-r">
							<div class="row new-tab1">
								<div class="col-xs-5"><b>Traveller Name</b></div>
								<div class="col-xs-7">
									<?php
									$isQrAgent	 = ($model->bkgUserInfo->bkg_user_id) ? Agents::checkQrAgentByUser($model->bkgUserInfo->bkg_user_id) : false;
									$urlUser	 = ($model->bkg_agent_id != NULL && $isQrAgent == false) ? "javascript:void(0)" : Yii::app()->createUrl('admin/user/view', array("id" => $model->bkgUserInfo->bkg_user_id));
									echo $fname . ' ' . $lname;
									?>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="row new-tab1">
								<div class="col-xs-5"><b>Traveller Email:</b></div>
								<div class="col-xs-7"><span id="trvEmail"><button class="btn btn-default btn-xs" id="showTrvDetails" onclick="showTravellerInfo()">Show Email/Contact</button></span></div>
							</div>
						</div>
					</div>
					<div class="row new-tab-border-b">
						<div class="col-xs-12 col-sm-6 new-tab-border-r">
							<div class="row new-tab1">
								<div class="col-xs-5"><b>Traveller Phone:</b></div>
								<div class="col-xs-7"><span id="trvPhone"></span></div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="row new-tab1">
								<div class="col-xs-5"><b>Traveller Alternate Phone:</b></div>
								<div class="col-xs-7"><span id="trvAltPhone"></span></div>
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
									if ($model->bkg_status != '9' || $model->bkg_status != '8')
									{
										echo '(' . $reconfirmStatus[$model->bkg_reconfirm_flag] . ')';
									}
									if ($bkgTrack->bkg_is_trip_verified == 1)
									{
										echo '(' . "<span class='bg-success p5'>Trip verified</span>" . ')';
									}
									else
									{
										echo '(' . "Trip not verified" . ')';
									}
									?></div>
							</div>
						</div>
					</div>
					<div class="row new-tab-border-b">
						<div class="col-xs-12 col-sm-6 new-tab-border-r">
							<div class="row new-tab1">
								<div class="col-xs-5"><b>Route:</b></div>
								<div class="col-xs-7"><?php echo $model->bkgFromCity->cty_full_name . ' to ' . $model->bkgToCity->cty_full_name; ?></div>
							</div>
						</div>
						<?php
						$cabmodel	 = $model->getBookingCabModel();
						$scvId		 = $model->bkgSvcClassVhcCat->scv_scc_id;
						$vhcModel	 = $model->bkgSvcClassVhcCat->scv_label;
						?>
						<div class="col-xs-12 col-sm-6">
							<div class="row new-tab1">
								<div class="col-xs-5"><b>Cab Type:</b></div>
								<div class="col-xs-7"><?=
									$model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(<strong>' . $vhcModel . '</strong>)<br>';
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
								<div class="col-xs-7"><?= Filter::getTimeDurationbyMinute($model->bkg_trip_duration) ?> (<?= $rutInfo[$cntRut - 1]['diffdays'] ?>days)</div>
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
								<div class="col-xs-5"><b>Confirm Date:</b></div>
								<div class="col-xs-7"><?= ($bkgTrail->bkg_confirm_datetime != '') ? date('d/m/Y', strtotime($bkgTrail->bkg_confirm_datetime)) : ''; ?></div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="row new-tab1">
								<div class="col-xs-5"><b>Confirm Time:</b></div>
								<div class="col-xs-7"><?= ($bkgTrail->bkg_confirm_datetime != '') ? date('h:i A', strtotime($bkgTrail->bkg_confirm_datetime)) : ''; ?></div>
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
					<?
					if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC)
					{
						?>
						<div class="row new-tab-border-b">
							<div class="col-xs-12 col-sm-6 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>No.of Seats:</b></div>
									<div class="col-xs-7"><b><?= $model->bkgAddInfo->bkg_no_person; ?></b></div>
								</div>
							</div>
						</div>
					<? } ?>
					<?php
					if ($model->bkg_return_date != '' && in_array($model->bkg_booking_type, [2, 3]))
					{
						?>
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
								<?php
								$arrPlatform = Booking::model()->booking_platform;
								$platform	 = $arrPlatform[$model->bkgTrail->bkg_platform];
								if ($model->bkgTrail->bkg_platform == 2)
								{
									$adminLists	 = Admins::getAdminList();
									$adminName	 = ' (' . $adminLists[$model->bkgTrail->bkg_create_user_id] . ")";
								}
								?>
								<div class="col-xs-5"><b>Platform:</b></div>
								<div class="col-xs-7"><?= ($platform != '') ? $platform . $adminName : "&nbsp;" ?></div>
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
						<div class="col-xs-12 col-sm-6  new-tab-border-r">
							<div class="row new-tab1 new-tab-border-b">
								<?
								if ($model->bkg_agent_id > 0)
								{
									?>

									<div class="col-xs-5  text-danger"><b>BOOKING TYPE:</b></div>
									<div class="col-xs-7"><?php
										$agentsModel = Agents::model()->findByPk($model->bkg_agent_id);

										$referenceCode = $model->bkg_agent_ref_code;

										if ($model->bkg_agent_id == Config::get('Kayak.partner.id') && $model->bkg_partner_ref_id != '')
										{
											$referenceCode = $model->bkg_partner_ref_id;
										}
										if ($model->bkg_agent_id == Config::get('transferz.partner.id') && is_numeric($model->bkg_agent_ref_code))
										{
											$partnerCode	 = TransferzOffers::getOffer($model->bkg_agent_ref_code);
											$referenceCode	 = ($partnerCode && isset($partnerCode['trb_trz_journey_code'])) ? $partnerCode['trb_trz_journey_code'] : $referenceCode;
										}

										if ($agentsModel->agt_type == 1)
										{
											echo "<span class='text-danger'>CORPORATE (<a target='_blank' href='/admpnl/agent/view?agent=$model->bkg_agent_id'>" . ($agentsModel->agt_company) . ")</a><br></span>";
											echo "CORPORATE BOOKING. CLEAN CAR. WELL BEHAVED DRIVER.<br>";
											echo "Customer Due <i class='fa fa-inr'></i>" . $model->bkgInvoice->bkg_due_amount;
										}
										else
										{
											$owner = ($agentsModel->agt_owner_name != '') ? $agentsModel->agt_owner_name : ($agentsModel->agt_fname . " " . $agentsModel->agt_lname);
											echo "PARTNER (<a target='_blank' href='/admpnl/agent/view?agent=$model->bkg_agent_id'>" . ($agentsModel->agt_company . "-" . $owner) . ")</a><br>";
											echo "<b>Booking Referral ID: <span class='text-info'>" . $referenceCode . "</span></b>";
										}
										if ($model->bkgPref->bkg_is_corporate == 1)
										{
											echo "<br/>";
											echo "<div class='text-danger text-left font-weight-bold'><b>CORPORATE</b></div>";
										}
										?>
									</div>
									<div class="col-xs-12">
										<button class="btn btn-default btn-small" onclick="showAgentNotifyDefault()">Partner Notification Defaults</button> 
										<div id="showagentnotifydefaults" class="hide mt10">
											<table class="table table-bordered">
												<tr>
													<th class="text-center" rowspan="2" >Events</th>
													<th colspan="4" class="text-center">Partner</th>
													<th colspan="4" class="text-center">Traveller</th>    
													<th colspan="4" class="text-center">Relationship Manager</th>    
												</tr>
												<tr>

													<td>Email</td><td>SMS</td><td>App</td><td>WA</td>
													<td>Email</td><td>SMS</td><td>App</td><td>WA</td>
													<td>Email</td><td>SMS</td><td>App</td><td>WA</td>
												</tr>
												<?
												$arrEvents = AgentMessages::getEvents();
												foreach ($arrEvents as $key => $value)
												{
													$bkgMessagesModel = BookingMessages::model()->getByEventAndBookingId($model->bkg_id, $key);
													?>  
													<tr>
														<td><?= $arrEvents[$key] ?></td>
														<td><?= ($bkgMessagesModel->bkg_agent_email == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
														<td><?= ($bkgMessagesModel->bkg_agent_sms == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
														<td><?= ($bkgMessagesModel->bkg_agent_app == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
														<td><?= ($bkgMessagesModel->bkg_agent_whatsapp == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>

														<td><?= ($bkgMessagesModel->bkg_trvl_email == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
														<td><?= ($bkgMessagesModel->bkg_trvl_sms == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
														<td><?= ($bkgMessagesModel->bkg_trvl_app == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
														<td><?= ($bkgMessagesModel->bkg_trvl_whatsapp == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>

														<td><?= ($bkgMessagesModel->bkg_rm_email == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
														<td><?= ($bkgMessagesModel->bkg_rm_sms == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
														<td><?= ($bkgMessagesModel->bkg_rm_app == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
														<td><?= ($bkgMessagesModel->bkg_rm_whatsapp == 1) ? "<span class='text-success'>Yes</span>" : "<span class='text-danger'>No</span>"; ?></td>
													</tr>
													<?
												}
												?>
											</table>
										</div>
									</div>
									<?
								}
								else
								{
									?>

									<div class="col-xs-5  text-danger" style="display: flex; align-items: center; vertical-align: middle; min-height: 35px;"><b>BOOKING TYPE:</b></div>
									<div class="col-xs-7" style="display: flex; align-items: center;">
										<?php
										$contactId		 = ContactProfile::getByEntityId($model->bkgUserInfo->bkg_user_id);
										$contactModel	 = Contact::model()->findByPk($contactId);
										$categoryLabel = "";
										$catCss = "";
										if($contactModel!='')
										{
											$categoryId = ContactPref::model()->find("cpr_ctt_id=:cId", ['cId' => $contactId])->cpr_category;
											if($categoryId > 0)
											{
												$categoryLabel = UserCategoryMaster::model()->findByPk($categoryId)->ucm_label;
												$catCss = UserCategoryMaster::getColorByid($categoryId);
											}
										}
                                        $fullName =  "user";
										$firstname		 = $contactModel->ctt_first_name;
										$lastname		 = $contactModel->ctt_last_name;
                                        if($firstname!='' || $lastname!='')
                                        {
                                        $fullName  =  $firstname . ' ' . $lastname;
                                        }

										$isQrAgent	 = ($model->bkgUserInfo->bkg_user_id) ? Agents::checkQrAgentByUser($model->bkgUserInfo->bkg_user_id) : false;
										$urlUser	 = ($model->bkg_agent_id != NULL && $isQrAgent == false) ? "javascript:void(0)" : Yii::app()->createUrl('admin/user/view', array("id" => $model->bkgUserInfo->bkg_user_id));

										if ($model->bkgUserInfo->bkg_user_id > 0 && ($model->bkg_status != 15 || Yii::app()->user->checkAccess('bookingContactAccess') || $model->bkgTrail->bkg_assign_csr == $userInfo->userId))
										{
											echo "B2C (<a target='_blank' href='{$urlUser}'>" . ($fullName) ."</a>)<img src='/images/{$catCss}' alt='' width='20' class='pull-right ml5'  title='{$categoryLabel}'><br>";
										}
										else
										{
                                           
											echo $fullName;
										}
										?>

									</div>		
									<?
								}
								?>
							</div> 						
							<?
							if (($model->bkg_status == 8 || $model->bkg_status == 9) && $model->bkg_cancel_delete_reason != '')
							{
								$reason = '';
								if ($model->bkg_status == 8)
								{
									$reason = 'Delete';
								}
								if ($model->bkg_status == 9)
								{
									$reason = 'Cancel';
								}
								?>							 
								<?php
								if ($model->bkg_status == 9 && !empty($model->bkgTrail->btr_cancel_date))
								{
									?>
									<div class="row new-tab1 new-tab-border-b">
										<div class="col-xs-5"><b>Cancellation Date:</b></div>
										<div class="col-xs-7"><?php echo DateTimeFormat::DateTimeToLocale($model->bkgTrail->btr_cancel_date); ?></div>
									</div>
								<?php } ?>
								<div class="row new-tab1">
									<div class="col-xs-5"><b><?= $reason ?> Reason:</b></div>
									<div class="col-xs-7"><?= $model->bkg_cancel_delete_reason . "$cancelDetail->cnr_reason" ?></div>
								</div>				 
							<? } ?>
						</div>
						<div class="col-xs-12 col-sm-6 new-tab-border-r">
							<?
							if ($model->bkgAddInfo->bkg_file_path != '')
							{
								?>
								<div class="row new-tab1 new-tab-border-b">
									<div class="col-xs-5"><b>File Path:</b></div>
									<div class="col-xs-7"><a href="<?= $model->bkgAddInfo->bkg_file_path ?>" target="_blank">File</a></div>
								</div>
							<?
							}
							if ($model->bkgTrail->bkg_tags != '')
							{
								?>
								<div class="row new-tab1 new-tab-border-b new-tab-border-l">
									<div class="col-xs-12"  ><b>Tags: </b><?= $tagBtnList ?></div>
								</div>
							<? } ?>
						</div>
					</div>
					<?php
					$btrCount = count($model->bookingRoutes) - 1;
					$pickupLatLong = $model->bookingRoutes[0]->brt_from_latitude . "," . $model->bookingRoutes[0]->brt_from_longitude;
					$dropLatLong = $model->bookingRoutes[$btrCount]->brt_to_latitude . "," . $model->bookingRoutes[0]->brt_to_longitude;
					?>
					<div class="row">
						<div class="col-xs-12 p0">
							<div class="hostory_leftdeep mt0">
								<div class="col-xs-12 col-sm-6 col-md-4">
									<div class="row p5">
										<div class="col-xs-6 col-sm-12"><b>Pickup Location</b></div>
										<div class="col-xs-6 col-sm-12">
											<a href="https://maps.google.com/?q=<?php echo $model->bookingRoutes[0]->brt_from_latitude . "," . $model->bookingRoutes[0]->brt_from_longitude; ?>" target="_blank"><?= $model->bkg_pickup_address; ?></a>

										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4">
									<div class="row p5">
										<div class="col-xs-6 col-sm-12"><b>Dropoff Location</b> <a href="http://www.google.com/maps/dir/<?php echo $pickupLatLong; ?>/<?php echo $dropLatLong; ?>" target="_blank"><img src="/images/icon/google-maps.svg" alt="" width="16" ></a></div>
										<div class="col-xs-6 col-sm-12">
											<a href="https://maps.google.com/?q=<?php echo $model->bookingRoutes[$btrCount]->brt_to_latitude . "," . $model->bookingRoutes[0]->brt_to_longitude; ?>" target="_blank"><?= $model->bkg_drop_address; ?></a>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4">
									<div class="row p5 pl0 pr0">
										<div class="col-xs-6 col-sm-12"><b>Additional Information</b></div>
										<div class="col-xs-6 col-sm-12 text-left">

											<ol class="pl10"><?= ($spclInstruction != "") ? $spclInstruction : "&nbsp;" ?></ol>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="row p20 pt0">
				<!--				<div class="col-xs-12 heading_box">Billing Details</div>-->
				<?
				$this->renderPartial('billingDetails', ['model' => $model, 'cabmodel' => $vencabdriver]);
				?>
			</div>
			<div class="row" id="csr_feedback">
				<div class="col-xs-12">
					<div class="panel panel-default panel-border" style="background: #D3EADA;">
						<div class="panel-body text-center">
							<h2 class="mt0"><b>Did you talk to the customer or driver??</b></h2>
							<div class="row"><div class="col-xs-4 col-xs-offset-3"><h3 class="mt0" ><b>Tell us what you know?</b></h3></div></div>
							<?php
							if ($days > 30)
							{
								?>
								<div class = "row mb15">
									<div class = "col-xs-3 pt10 text-right"><b>Customer was feeling</b></div>
									<div class="col-xs-3 text-center">
										<label class="form-control" <?php echo CsrFeedback::getColorCodeForRating(($csrFeedback['customer_to_driver_rating'] >= 0 ? $csrFeedback['customer_to_driver_rating'] : 0)); ?>>
	<?php echo CsrFeedback::getFeedbackRating(($csrFeedback['customer_to_driver_rating'] >= 0 ? $csrFeedback['customer_to_driver_rating'] : 0)); ?>
										</label>

									</div>
									<div class="col-xs-5 pl0 pt10 text-left"><b>about Driver</b></div>
								</div>
								<div class="row mb15">
									<div class="col-xs-3 pt10 text-right"><b>Driver was feeling</b></div>
									<div class="col-xs-3 text-center">										
										<label class="form-control" <?php echo CsrFeedback::getColorCodeForRating(($csrFeedback['driver_to_cust_rating'] >= 0 ? $csrFeedback['driver_to_cust_rating'] : 0)); ?>>
	<?php echo CsrFeedback::getFeedbackRating(($csrFeedback['driver_to_cust_rating'] >= 0 ? $csrFeedback['driver_to_cust_rating'] : 0)); ?>
										</label>
									</div>
									<div class="col-xs-5 pl0 pt10  text-left"><b>about Customer</b></div>
								</div>
								<div class="row mb15">
									<div class="col-xs-3 pt10 text-right"><b>Customer was feeling</b></div>
									<div class="col-xs-3 text-center">
										<label class="form-control" <?php echo CsrFeedback::getColorCodeForRating(($csrFeedback['cust_to_car_rating'] >= 0 ? $csrFeedback['cust_to_car_rating'] : 0)); ?>>
	<?php echo CsrFeedback::getFeedbackRating(($csrFeedback['cust_to_car_rating'] >= 0 ? $csrFeedback['cust_to_car_rating'] : 0)); ?>
										</label>
									</div>
									<div class="col-xs-5 pl0 pt10  text-left"><b>about Car</b></div>
								</div>
								<div class="row mb15">
									<div class="col-xs-3 pt10 text-right"><b>CSR was feeling</b></div>
									<div class="col-xs-3 text-center">
										<label class="form-control" <?php echo CsrFeedback::getColorCodeForRating(($csrFeedback['csr_to_customer_rating'] >= 0 ? $csrFeedback['csr_to_customer_rating'] : 0)); ?>>
	<?php echo CsrFeedback::getFeedbackRating(($csrFeedback['csr_to_customer_rating'] >= 0 ? $csrFeedback['csr_to_customer_rating'] : 0)); ?>
										</label>
									</div>
									<div class="col-xs-5 pl0 pt10  text-left"><b>about Customer</b></div>
								</div>
								<div class="row mb15">
									<div class="col-xs-3 pt10 text-right"><b>CSR was feeling</b></div>
									<div class="col-xs-3 text-center">

										<label class="form-control" <?php echo CsrFeedback::getColorCodeForRating(($csrFeedback['csr_to_driver_rating'] >= 0 ? $csrFeedback['csr_to_driver_rating'] : 0)); ?>>
	<?php echo CsrFeedback::getFeedbackRating(($csrFeedback['csr_to_driver_rating'] >= 0 ? $csrFeedback['csr_to_driver_rating'] : 0)); ?>
										</label>
									</div>
									<div class="col-xs-5 pl0 pt10  text-left"><b>about Driver</b></div>
								</div>

								<?php
							}
							else
							{
								?>
								<div class = "row mb15">
									<div class = "col-xs-3 pt10 text-right"><b>Customer is feeling</b></div>
									<div class="col-xs-3 text-center">
										<select class="form-control" name="customer_to_driver_rating" id="customer_to_driver_rating">
											<option value="">?? - don't know</option>
											<option value="1" style="background: #ff0000; color: #fff;" >1 - very upset</option>
											<option value="2" style="color: #ff0000;">2 - upset</option>
											<option value="3" style="color: #1077d5;">3 - ok</option>
											<option value="4" style="background: #e2efda; color: #70ad47;">4 - happy</option>
											<option value="5" style="background: #70ad47; color: #fff;">5 - very happy</option>
										</select>

									</div>
									<div class="col-xs-5 pl0 pt10 text-left"><b>about Driver</b> | <i style="color:blue;">Last status = <?php
											$type	 = $csrFeedback->customer_to_driver_rating >= 0 ? $csrFeedback['customer_to_driver_rating'] : 0;
											echo CsrFeedback::getFeedbackRating($type);
											?></i></div>
								</div>
								<div class="row mb15">
									<div class="col-xs-3 pt10 text-right"><b>Driver is feeling</b></div>
									<div class="col-xs-3 text-center">
										<select class="form-control" name="driver_to_cust_rating" id="driver_to_cust_rating">
											<option value="">?? - don't know</option>
											<option value="1" style="background: #ff0000; color: #fff;" >1 - very upset</option>
											<option value="2" style="color: #ff0000;">2 - upset</option>
											<option value="3" style="color: #1077d5;">3 - ok</option>
											<option value="4" style="background: #e2efda; color: #70ad47;">4 - happy</option>
											<option value="5" style="background: #70ad47; color: #fff;">5 - very happy</option>
										</select>
									</div>
									<div class="col-xs-5 pl0 pt10  text-left"><b>about Customer</b> | <i style="color:blue;">Last status = <?php
											$type	 = $csrFeedback->driver_to_cust_rating >= 0 ? $csrFeedback['driver_to_cust_rating'] : 0;
											echo CsrFeedback::getFeedbackRating($type);
											?></i></div>
								</div>
								<div class="row mb15">
									<div class="col-xs-3 pt10 text-right"><b>Customer is feeling</b></div>
									<div class="col-xs-3 text-center">
										<select class="form-control" name="cust_to_car_rating" id="cust_to_car_rating">
											<option value="">?? - don't know</option>
											<option value="1" style="background: #ff0000; color: #fff;" >1 - very upset</option>
											<option value="2" style="color: #ff0000;">2 - upset</option>
											<option value="3" style="color: #1077d5;">3 - ok</option>
											<option value="4" style="background: #e2efda; color: #70ad47;">4 - happy</option>
											<option value="5" style="background: #70ad47; color: #fff;">5 - very happy</option>
										</select>
									</div>
									<div class="col-xs-5 pl0 pt10  text-left"><b>about Car</b> | <i style="color:blue;">Last status = <?php
											$type	 = $csrFeedback->cust_to_car_rating >= 0 ? $csrFeedback['cust_to_car_rating'] : 0;
											echo CsrFeedback::getFeedbackRating($type);
											?></i></div>
								</div>
								<div class="row mb15">
									<div class="col-xs-3 pt10 text-right"><b>CSR is feeling</b></div>
									<div class="col-xs-3 text-center">
										<select class="form-control" name="csr_to_customer_rating" id="csr_to_customer_rating">
											<option value="">?? - don't know</option>
											<option value="1" style="background: #ff0000; color: #fff;" >1 - very upset</option>
											<option value="2" style="color: #ff0000;">2 - upset</option>
											<option value="3" style="color: #1077d5;">3 - ok</option>
											<option value="4" style="background: #e2efda; color: #70ad47;">4 - happy</option>
											<option value="5" style="background: #70ad47; color: #fff;">5 - very happy</option>
										</select>
									</div>
									<div class="col-xs-5 pl0 pt10  text-left"><b>about Customer</b> | <i style="color:blue;">Last status =  <?php
											$type	 = $csrFeedback->csr_to_customer_rating >= 0 ? $csrFeedback['csr_to_customer_rating'] : 0;
											echo CsrFeedback::getFeedbackRating($type);
											?></i></div>
								</div>
								<div class="row mb15">
									<div class="col-xs-3 pt10 text-right"><b>CSR is feeling</b></div>
									<div class="col-xs-3 text-center">
										<select class="form-control" name="csr_to_driver_rating" id="csr_to_driver_rating">
											<option value="">?? - don't know</option>
											<option value="1" style="background: #ff0000; color: #fff;" >1 - very upset</option>
											<option value="2" style="color: #ff0000;">2 - upset</option>
											<option value="3" style="color: #1077d5;">3 - ok</option>
											<option value="4" style="background: #e2efda; color: #70ad47;">4 - happy</option>
											<option value="5" style="background: #70ad47; color: #fff;">5 - very happy</option>
										</select>
									</div>
									<div class="col-xs-5 pl0 pt10  text-left"><b>about Driver</b> | <i style="color:blue;">Last status =  <?php
											$type	 = $csrFeedback->csr_to_driver_rating >= 0 ? $csrFeedback['csr_to_driver_rating'] : 0;
											echo CsrFeedback::getFeedbackRating($type);
											?></i></div>
								</div>
								<div class="row mb15" >								
									<div class="col-xs-3 col-xs-offset-3 pt10"><button class="btn btn-primary full-width mb0 text-uppercase" type="button" id="saveCsrFeedBack">Save my input</button><b class="pt10 hidebtn" style="color:green; display: none;">Feedback saved successfully.</b></div>
								</div>
<?php }
?>


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
			<div class="row p20 pt0">
				<div class="col-xs-12 heading_box">Other Information</div>
				<?
				$this->renderPartial('specialinformation', ['model' => $model, 'cabmodel' => $vencabdriver]);
				?>
			</div>

		</div>


	</div>

	<div class="row">
		<div class="col-xs-12">
			<div id="scq" class="p10 mt20 mb20" style="color: #4E5E6A; background: #fff; border: 1px solid #ddd;">
				<h3 class="mb0 mt0"><b>Follow Up</b></h3>
				<div class="row pl15 pr15 pt15" id="followupSecP1" style="display:none">
					<div class="col-xs-12 pl15 pr15">

					</div>
				</div>

			</div>

		</div>
	</div>

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
								<?
								if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time != '0' && $model->bkgAddInfo->bkg_spl_req_lunch_break_time != null)
								{
									?>
									<div class="row"><div class="col-xs-12"><b>Extra Time Added:</b> <?= $model->bkgAddInfo->bkg_spl_req_lunch_break_time ?> Minutes For Journey Break</div></div>
<? } ?>
							</td>

							<td  style="vertical-align: middle;" rowspan="<?= $cntRut ?>">

								<?php
								$modelMergedVendor = Vendors::model()->mergedVendorId($vencabdriver->bcb_vendor_id);
								?>
								Trip ID: <b>
								<?php echo CHtml::link($model->bkg_bcb_id, Yii::app()->createUrl("admin/booking/triprelatedbooking", ["tid" => $model->bkg_bcb_id, "bid" => $model->bkg_id]), ["class" => "viewRelatedBooking", "onclick" => "return viewList(this)"]); ?>
								</b> <?php
								if ($vencabdriver->bcb_trip_type != 0)
								{
									?>
									<span class="label label-primary">
										<?php
										if ($vencabdriver->bcb_trip_type == 1)
										{
											echo "Matched";
										}
										?> </span>
								<?php } ?> </br>
								<?php
								$cttId = $modelMergedVendor->vnd_contact_id;
								if ($cttId != '')
								{
									$number = ContactPhone::model()->getContactPhoneById($cttId);
									if (empty($number))
									{
										$vndCtn	 = ContactPhone::model()->findByContactID($cttId);
										$number	 = $vndCtn[0]->phn_phone_no;
									}
								}
								?>
								Name: <b><?= CHtml::link($modelMergedVendor->vnd_name, Yii::app()->createUrl("admin/vendor/profile", ["code" => $modelMergedVendor->vnd_code]), ["target" => "_blank"]); ?></b></br>
								Phone: <?= $number; //$vencabdriver->bcbVendor->vnd_phone                                                                                                         ?><br>
								Rating:  <?php
								if ($modelMergedVendor->vendorStats->vrs_vnd_overall_rating > 0)
								{
									$ratingType = ($modelMergedVendor->vendorStats->vrs_vnd_overall_rating >= 4) ? 'success' : 'danger';
									echo '<span class="label label-' . $ratingType . '">' . $vencabdriver->bcbVendor->vendorStats->vrs_vnd_overall_rating . '</span>';
								}
								else
								{
									echo '<span class="label label-danger">0</span>';
								}
								?><br>
								Dependency Score:  <?php
								if ($modelMergedVendor->vendorStats->vrs_dependency >= 0)
								{
									$scoreType = ($modelMergedVendor->vendorStats->vrs_dependency <= 50) ? 'warning' : 'success';
									echo '<span class="label label-' . $scoreType . '">' . $vencabdriver->bcbVendor->vendorStats->vrs_dependency . '</span>';
								}
								else
								{
									echo '<span class="label label-danger">' . $vencabdriver->bcbVendor->vendorStats->vrs_dependency . '</span>';
								}
								?><br>
								#Trips a/o bkg: <?= $vencabdriver->bcb_vendor_trips ?><br>
								<?php
								if ($vencabdriver->bcbVendor)
								{
									if ($modelMergedVendor->vendorPrefs->vnp_is_freeze == 1)
									{
										echo '<span class="label label-danger">Frozen</span><br>';
									}
									else if ($modelMergedVendor->vendorPrefs->vnp_is_freeze == 0)
									{
										echo '<span class="label label-success">Unfreezed</span><br>';
									}
									if ($vencabdriver->bcbVendor->vnd_active == 0)
									{
										echo '<span class="label label-danger">Inactive</span><br>';
									}
									if ($modelMergedVendor->vnd_active == 2)
									{
										echo '<span class="label label-danger">Blocked</span><br>';
									}
									if ($modelMergedVendor->vendorStats->vrs_mark_vend_count >= 1)
									{
										if ($modelMergedVendor->vendorStats->vrs_mark_vend_count == 1)
										{
											echo '<span class="label label-warning">Bad Count : ' . $modelMergedVendor->vendorStats->vrs_mark_vend_count . '</span><br>';
										}
										else if ($modelMergedVendor->vendorStats->vrs_mark_vend_count > 1)
										{
											echo '<span class="label label-danger">Bad Count : ' . $modelMergedVendor->vendorStats->vrs_mark_vend_count . '</span><br>';
										}
									}
								}
								?> 
								<?php
								$noOfBid		 = BookingVendorRequest::model()->getBidCountByBcb($vencabdriver->bcb_id);
								$floated		 = $noOfBid['bidCountFloated'];
								$floatedLoggedIn = $noOfBid['bidCountFloatedLoggedIn'];
								$acceptCnt		 = $noOfBid['totBidReceived'];
								$deniedCnt		 = $noOfBid['totBidDenied'];
								?>
								<?
								if ($vencabdriver->bcb_first_request_sent != '' || $vencabdriver->bcb_first_request_sent != NULL)
								{
									?>
									Bid started at : <?= date('d/m/Y', strtotime($vencabdriver->bcb_first_request_sent)); ?><br>
<? }
?>

								Bid Floated : <br>
								<span class="ml30">A. Eligible: <?= ($floated > 0) ? $floated : 0; ?></span><br>
								<span class="ml30">B. Logged In: <?= ($floatedLoggedIn > 0) ? $floatedLoggedIn : 0; ?></span><br>
								Bid Received : <?= ($acceptCnt > 0) ? $acceptCnt : 0; ?><br>
								Bid Denied : <?= ($deniedCnt > 0) ? $deniedCnt : 0; ?><br>

							</td>
							<td   style="vertical-align: middle;" rowspan="<?= $cntRut ?>">
								<?php
								$modelDriver = Drivers::model()->mergedDriverId($vencabdriver->bcb_driver_id);
								$driverName	 = $modelDriver->drvContact->ctt_name;
								$numberAlt	 = $model->bkgBcb->bcb_driver_phone;
								$drvCttId	 = $modelDriver->drv_contact_id;
								if ($drvCttId != '')
								{
									$number = ContactPhone::model()->getContactPhoneById($drvCttId);
									if (empty($number))
									{
										$drvCnt	 = ContactPhone::model()->findByContactID($drvCttId);
										$number	 = $drvCnt[0]->phn_phone_no;
									}
								}
								?>	
								Name: <b><?= CHtml::link($driverName, Yii::app()->createUrl("admin/driver/view", ["code" => $modelDriver->drv_code]), ["target" => "_blank"]); ?></b></br>
								Phone: <?= $number; ?> <?= ($numberAlt != '') ? "/ $numberAlt(Booking No.)" : " " ?><br>
								Rating: <?php
								if ($modelDriver->drv_overall_rating != '' && !empty($modelDriver->drv_overall_rating))
								{
									if ($modelDriver->drv_overall_rating >= 4)
									{
										echo '<span class="label label-success">' . $modelDriver->drv_overall_rating . '</span>';
									}
									else if ($modelDriver->drv_overall_rating <= 3)
									{
										echo '<span class="label label-danger">' . $modelDriver->drv_overall_rating . '</span>';
									}
								}
								else
								{
									echo '<span class="label label-danger">0</span>';
								}
								if ($vencabdriver->bcb_driver_trips != "")
								{
									$drvTotalTrips = $vencabdriver->bcb_driver_trips;
								}
								else
								{
									if ($modelDriver->drv_total_trips != "")
									{
										$drvTotalTrips = $modelDriver->drv_total_trips;
									}
									else
									{
										$drvTotalTrips = 0;
									}
								}
								?><br>
								#Trips a/o bkg: <?= $drvTotalTrips ?><br>
								<?php
								if ($modelDriver)
								{
									if ($modelDriver->drv_is_freeze == 1)
									{
										echo '<span class="label label-success">Blocked</span><br>';
									}

									if ($modelDriver->drv_active == 0)
									{
										echo '<span class="label label-danger">Inactive</span><br>';
									}
									if ($modelDriver->drv_mark_driver_count >= 1)
									{
										if ($modelDriver->drv_mark_driver_count == 1)
										{
											echo '<span class="label label-warning">Bad Count : ' . $modelDriver->drv_mark_driver_count . '</span><br>';
										}
										else if ($vencabdriver->bcbDriver->drv_mark_driver_count > 1)
										{
											echo '<span class="label label-danger">Bad Count : ' . $modelDriver->drv_mark_driver_count . '</span><br>';
										}
									}
									if ($modelDriver->drv_approved == 1)
									{
										echo '<span class="label label-success">Approved</span>';
									}
									else if ($modelDriver->drv_approved == 0)
									{
										echo '<span class="label label-danger">Not Approved</span>';
									}
									else if ($modelDriver->drv_approved == 2)
									{
										echo '<span class="label label-warning">Pending Approval</span>';
									}
									else if ($modelDriver->drv_approved == 3)
									{
										echo '<span class="label label-warning">Rejected</span>';
									}
								}
								?>
								<?php
								//DRIVER BLOCK rATING tAGS
								$ratingtags = RatingAttributes::getDriverRatingTags($modelDriver->drv_id);
								if (count($ratingtags) > 0)
								{
									?>
									<p>Tags :
										<?php
										foreach ($ratingtags as $rtag)
										{
											if ($rtag['rating_type'] == "GOOD")
											{
												$color		 = 'background:#48b9a7';
												$checkBtn	 = 'fas fa-check mr5';
												$cls		 = "label label-success";
											}
											if ($rtag['rating_type'] == "BAD")
											{
												$color		 = 'background:#ff4646';
												$checkBtn	 = 'fas fa-times mr5';
												$cls		 = "label label-danger";
											}
											?>
											<span class="<?= $cls ?>" style="<?= $color ?>"> <i class="<?= $checkBtn; ?>"></i> <?php echo $rtag['rating_tag']; ?></span>
											<?php
										}
										?>
									</p>
<?php } ?><Br>                                
							</td>
							<td   style="vertical-align: middle;" rowspan="<?= $cntRut ?>">
								Cab model: <b><?= CHtml::link($vehicleModel, Yii::app()->createUrl("admin/vehicle/view", ["code" => $bookData['vhc_code']]), ["target" => "_blank"]); ?></b><br>
								License Plate: <?= $vencabdriver->bcbCab->vhc_number ?><br>
								Rating: <?php
								$vehmodel = Vehicles::model()->findByPk($vencabdriver->bcb_cab_id);
								if (!empty($vencabdriver->bcbCab->vhc_overall_rating) && $vencabdriver->bcbCab->vhc_overall_rating != '')
								{
									if ($vencabdriver->bcbCab->vhc_overall_rating >= 4)
									{
										echo '<span class="label label-success">' . $vencabdriver->bcbCab->vhc_overall_rating . '</span>';
									}
									else if ($vencabdriver->bcbCab->vhc_overall_rating <= 3)
									{
										echo '<span class="label label-danger">' . $vencabdriver->bcbCab->vhc_overall_rating . '</span>';
									}
								}
								else if (!empty($vehmodel->vhc_overall_rating) && $vehmodel->vhc_overall_rating != '')
								{
									$vencabdriver->bcbCab->vhc_overall_rating = $vehmodel->vhc_overall_rating;
								}
								else
								{
									echo '<span class="label label-danger">0</span>';
								}
								?><br>
								<?php
								if (!empty($vencabdriver->bcbCab->bcb_cab_trips) && $vencabdriver->bcbCab->bcb_cab_trips != '')
								{
									$vhc_total_trips = $vencabdriver->bcbCab->bcb_cab_trips;
								}
								else
								{
									if ($vehmodel->vhc_total_trips != "")
									{
										$vhc_total_trips = $vehmodel->vhc_total_trips;
									}
									else
									{
										$vhc_total_trips = 0;
									}
								}
								?>
								#Trips a/o bkg: <?= $vhc_total_trips ?><? //= $vencabdriver->bcb_cab_trips                                                                      ?><br>
								<?php
								if ($vencabdriver->bcbCab)
								{
									if ($vencabdriver->bcbCab->vhc_is_freeze == 1)
									{
										echo '<span class="label label-success">Blocked</span><br/>';
									}

									if ($vencabdriver->bcbCab->vhc_active == 0)
									{
										echo '<span class="label label-danger">Inactive</span><br/>';
									}

									if ($vencabdriver->bcbCab->vhc_is_commercial == 1)
									{
										echo '<span class="label label-primary">Commercial</span><br/>';
									}


									if ($vencabdriver->bcbCab->vhc_approved == 1)
									{
										echo '<span class="label label-success">Approved</span><br/>';
									}
									else if ($vencabdriver->bcbCab->vhc_approved == 0)
									{
										echo '<span class="label label-danger">Not Approved</span><br/>';
									}
									else if ($vencabdriver->bcbCab->vhc_approved == 2)
									{
										echo '<span class="label label-warning">Pending Approval</span><br/>';
									}
									else if ($vencabdriver->bcbCab->vhc_approved == 3)
									{
										echo '<span class="label label-danger">Rejected</span><br/>';
									}


									if ($vencabdriver->bcbCab->vhc_mark_car_count >= 1)
									{
										if ($vencabdriver->bcbCab->vhc_mark_car_count == 1)
										{
											echo '<span class="label label-warning">Bad Mark : ' . $vencabdriver->bcbCab->vhc_mark_car_count . '</span><br/>';
										}
										else if ($vencabdriver->bcbCab->vhc_mark_car_count > 1)
										{
											echo '<span class="label label-danger">Bad Mark : ' . $vencabdriver->bcbCab->vhc_mark_car_count . '</span><br/>';
										}
									}
								}
								?> 
								Tags :
								<?php
								if (!empty($vencabdriver->bcb_cab_id))
								{
									if ($vehmodel->vhc_has_rooftop_carrier > 0)
									{
										echo '<span class="label label-success" > <i class="fas fa-check mr5"></i> Rooftop Carriers</span>';
									}
									else
									{
										echo '<span class="label label-danger" > <i class="fas fa-times mr5"></i> Rooftop Carriers</span>';
									}
									echo '  ';
									if ($vehmodel->vhc_has_cng > 0)
									{
										echo '<span class="label label-success" > <i class="fas fa-check mr5"></i> CNG</span>';
									}
									else
									{
										echo '<span class="label label-danger" > <i class="fas fa-times mr5"></i> CNG</span>';
									}echo '  ';
									if ($vehmodel->vhc_is_commercial > 0)
									{
										echo '<span class="label label-success" > <i class="fas fa-check mr5"></i> Commercial</span>';
									}
									else
									{
										echo '<span class="label label-danger" > <i class="fas fa-times mr5"></i> Commercial</span>';
									}echo '  ';
									if ($vehmodel->vhc_is_uber_approved > 0)
									{
										echo '<span class="label label-success" > <i class="fas fa-check mr5"></i> UBER Approved</span>';
									}
									else
									{
										echo '<span class="label label-danger" > <i class="fas fa-times mr5"></i> UBER Approved</span>';
									}echo '  ';

									if ($vencabdriver->bcbCab->vhcStat->vhs_is_partition > 0)
									{
										echo '<span class="label label-success" > <i class="fas fa-check mr5"></i>Partitioned</span>';
									}
									else
									{
										echo '<span class="label label-danger" > <i class="fas fa-times mr5"></i> Partitioned</span>';
									}echo '  ';
								}
								?>
							</td>
						</tr>
						<?
						if ($cntRut > 1)
						{
							for ($i = 1; $i < $cntRut; $i++)
							{
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



	<div class="mt20">
		<?php
// print_r($note);exit;
		if (!empty($note))
		{
			?>
			<div class="row">
				<div class="col-xs-12" id="linkedusers"><div class="panel panel-primary panel-border compact">
						<div class="panel-heading heading_box" style="min-height:0">SPECIAL INSTRUCTIONS & ADVISORIES THAT MAY AFFECT YOUR PLANNED TRAVEL</div>
						<div aria-describedby="caption" class="table" role="grid">
							<div class="tr" role="row">
								<div class="th smallCol" role="columnheader">
									Place
								</div>
								<div class="th bigCol" role="columnheader">
									Note
								</div>
								<div class="th smallCol" role="columnheader">
									Valid From
								</div>
								<div class="th smallCol" role="columnheader">
									Valid To
								</div>
								<div class="th smallCol" role="columnheader">
									Applicable For
								</div>
							</div>
							<?php
							for ($i = 0; $i < count($note); $i++)
							{
								?>  
								<div class="tr" role="row">
									<div class="th smallCol" role="rowheader">
										<?php
										if ($note[$i]['dnt_area_type'] == 1)
										{
											?>
											<?= ($note[$i]['dnt_zone_name']) ?>
										<?php } ?>
										<?php
										if ($note[$i]['dnt_area_type'] == 3)
										{
											?>
											<?= ($note[$i]['cty_name']) ?>
											<?php
										}
										else if ($note[$i]['dnt_area_type'] == 2)
										{
											?>
											<?= ($note[$i]['dnt_state_name']) ?>
											<?php
										}
										else if ($note[$i]['dnt_area_type'] == 0)
										{
											?>
											<?= "Applicable to all" ?>
											<?php
										}
										else if ($note[$i]['dnt_area_type'] == 4)
										{
											?>
											<?= Promos::$region[$note[$i]["dnt_area_id"]] ?>
											<?php
										}
										?>

									</div>
									<div class="td bigCol" role="gridcell">
										<?= ($note[$i]['dnt_note']) ?>
									</div>
									<div class="td smallCol" role="gridcell">
										<?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?>
									</div>
									<div class="td smallCol" role="gridcell">
										<?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?>
									</div>
									<div class="td smallCol" role="gridcell">
										<?php
										$dataArr = explode(",", ($note[$i]['dnt_show_note_to']));
										foreach ($dataArr as $showNoteTo)
										{

											if ($showNoteTo == 1)
											{
												echo "Consumer" . ", ";
											}
											else if ($showNoteTo == 2)
											{
												echo "Vendor" . ", ";
											}
											else if ($showNoteTo == 3)
											{
												echo "Driver" . ", ";
											}
											//destination notes by Rituparana
											//else if ($showNoteTo == 5)
											//{
											//echo "Agent" . ", ";
											//}
											else
											{
												echo "";
											}
										}
										?>

									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div></div>
			</div>
			<?php
		}
		?>
	</div>




	<?php
	if ($model->bkg_status > 4)
	{
		?>
		<div class="row">
			<div class="col-xs-12 mt20">
				<?php
				if ($ratingModel->rtg_customer_overall)
				{
					?> 
					<label class="mt10 control-label">Customer Rating</label>
					<div class="col-xs-12 rounded pb10">
						<div class="row">
							<?php
							if ($ratingModel->rtg_customer_recommend)
							{
								?> <div class='col-xs-12 mt10'>
									<?= $ratingModel->getAttributeLabel('rtg_customer_recommend') ?><br>
									<?
									$this->widget('CStarRating', array(
										'model'		 => $ratingModel,
										'attribute'	 => 'rtg_customer_recommend',
										'minRating'	 => 1,
										'maxRating'	 => 10,
										'starCount'	 => 10,
										'value'		 => $ratingModel->rtg_customer_recommend,
										'readOnly'	 => true,
									));
									?>
								</div>
								<?php
							}
							if ($ratingModel->rtg_customer_overall)
							{
								?> 
								<div class='col-xs-12 mt10'>
									<?= $ratingModel->getAttributeLabel('rtg_customer_overall') ?><br>
									<?
									$this->widget('CStarRating', array(
										'model'		 => $ratingModel,
										'attribute'	 => 'rtg_customer_overall',
										'minRating'	 => 1,
										'maxRating'	 => 5,
										'starCount'	 => 5,
										'value'		 => $ratingModel->rtg_customer_overall,
										'readOnly'	 => true,
									));
									?>
								</div>
								<?php
							}
							if ($ratingModel->rtg_customer_review)
							{
								?> 
								<div class='col-xs-12 mt10'><?= $ratingModel->getAttributeLabel('rtg_customer_review') ?> 
								</div>
								<div class="col-xs-12 p15 rounded rounded-margin mt5 mb10" style="width:97%;">
									<div class="row">
										<div class="col-xs-12">
			<?= $ratingModel->rtg_customer_review; ?>
										</div>
									</div>
								</div>
								<?php
							}
							if ($ratingModel->rtg_customer_driver)
							{
								?> <div class='col-xs-12 mt10'>
									<?= $ratingModel->getAttributeLabel('rtg_customer_driver') ?><br>
									<?
									$this->widget('CStarRating', array(
										'model'		 => $ratingModel,
										'attribute'	 => 'rtg_customer_driver',
										'minRating'	 => 1,
										'maxRating'	 => 5,
										'starCount'	 => 5,
										'value'		 => $ratingModel->rtg_customer_driver,
										'readOnly'	 => true,
									));
									?></div>
								<?php
							}


							if (($ratingModel->rtg_customer_driver <> NULL && $ratingModel->rtg_customer_driver <= 4) && $ratingModel->rtg_customer_overall <= 5)
							{
								?>


								<!-------------------------------------- -->
								<div class="col-xs-12">
									<div class="panel panel-default">
										<div class="panel-body p0">
											<div class="container">
												<div class="row">
													&nbsp;
												</div>
											</div>   
											<div class="row m0 flex">
												<div class="col-xs-6 col-sm-6  box-design1a2 pt0">
													<div class="row">
														<div class="col-xs-12 box-design1 border-new-b text-center"><b>What was good?</b></div>
														<div class="col-xs-12">                                                                                   
															<ul class="mt10">
																<?php
																if (!empty($ratingModel->rtg_driver_good_attr))
																{
																	$data = explode(',', $ratingModel->rtg_driver_good_attr);
																	foreach ($data as $vb)
																	{
																		?>
																		<li style="color: #009900;"><i class="fa fa-check"></i>&nbsp; <?= $data_array[$vb]['ratt_name'] ?></li>                                                                                                
																		<?php
																	}
																}
																?>
															</ul>
														</div>
													</div>
												</div>


												<div class="col-xs-6 col-sm-6 box-design2a2 pt0">
													<div class="row">
														<div class="col-xs-12 box-design2 border-new-b text-center"><b>What was not?</b></div>
														<div class="col-xs-12">
															<ul class="mt10">	
																<?php
																if (!empty($ratingModel->rtg_driver_bad_attr))
																{
																	$data = explode(',', $ratingModel->rtg_driver_bad_attr);
																	foreach ($data as $vb)
																	{
																		?>
																		<li style="color:#DC143C;"><i class="fa fa-times"></i>&nbsp;  <?= $data_array[$vb]['ratt_name_bad'] ?></li>                                                                                                
																		<?php
																	}
																}
																?>		
															</ul>																																			
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>
								</div>
								<!-------------------------------------- -->
								<div class='col-xs-12 mt10'>Driver Comment</div>

								<div class="col-xs-12 p15 rounded rounded-margin mt5 mb10" style="width:97%;">
									<div class="row">
										<div class="col-xs-12">
			<?= $ratingModel->rtg_driver_cmt; ?>
										</div>
									</div>
								</div>



								<?php
							}
							if ($ratingModel->rtg_customer_csr)
							{
								?> <div class='col-xs-12 mt10'>
									<?= $ratingModel->getAttributeLabel('rtg_customer_csr') ?><br>
									<?
									$this->widget('CStarRating', array(
										'model'		 => $ratingModel,
										'attribute'	 => 'rtg_customer_csr',
										'minRating'	 => 1,
										'maxRating'	 => 5,
										'starCount'	 => 5,
										'value'		 => $ratingModel->rtg_customer_csr,
										'readOnly'	 => true,
									));
									?></div>
								<?php
							}
							if (($ratingModel->rtg_customer_csr <> NULL && $ratingModel->rtg_customer_csr <= 4) && $ratingModel->rtg_customer_overall <= 5)
							{
								?>

								<!-------------------------------------- -->
								<div class="col-xs-12">
									<div class="panel panel-default">
										<div class="panel-body p0">
											<div class="row m0">
												<div class="col-xs-6 col-sm-6">
													&nbsp;
												</div>
											</div>    
											<div class="row m0 flex">
												<div class="col-xs-6 col-sm-6 box-design1a2 pt0">
													<div class="row">
														<div class="col-xs-12 box-design1 border-new-b text-center"><b>What was good?</b></div>
														<div class="col-xs-12">
															<ul>
																<?php
																if (!empty($ratingModel->rtg_csr_good_attr))
																{
																	$data = explode(',', $ratingModel->rtg_csr_good_attr);
																	foreach ($data as $vb)
																	{
																		?>
																		<li style="color: #009900;"><i class="fa fa-check"></i>&nbsp; <?= $data_array[$vb]['ratt_name'] ?></li>                                                                                                
																		<?php
																	}
																}
																?>
															</ul>
														</div>
													</div>
												</div>
												<div class="col-xs-6 col-sm-6 box-design2a2 pt0">
													<div class="row">
														<div class="col-xs-12 box-design2 border-new-b text-center"><b>What was not?</b></div>
														<div class="col-xs-12">
															<ul>	
																<?php
																if (!empty($ratingModel->rtg_csr_bad_attr))
																{
																	$data = explode(',', $ratingModel->rtg_csr_bad_attr);
																	foreach ($data as $vb)
																	{
																		?>
																		<li style="color:#DC143C;"><i class="fa fa-times"></i>&nbsp;  <?= $data_array[$vb]['ratt_name_bad'] ?></li>                                                                                                
																		<?php
																	}
																}
																?>		
															</ul>																																			
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>
								</div>
								<!-------------------------------------- -->
								<div class='col-xs-12 mt10'>CSR Comment</div>

								<div class="col-xs-12 p15 rounded rounded-margin mt5 mb10" style="width:97%;">
									<div class="row">
										<div class="col-xs-12">
			<?= $ratingModel->rtg_csr_cmt; ?>
										</div>
									</div>
								</div>
								<?php
							}
							if ($ratingModel->rtg_customer_car)
							{
								?>  <div class='col-xs-12 mt10'>
									<?= $ratingModel->getAttributeLabel('rtg_customer_car') ?><br>
									<?
									$this->widget('CStarRating', array(
										'model'		 => $ratingModel,
										'attribute'	 => 'rtg_customer_car',
										'minRating'	 => 1,
										'maxRating'	 => 5,
										'starCount'	 => 5,
										'value'		 => $ratingModel->rtg_customer_car,
										'readOnly'	 => true,
									));
									?>
								</div>
		<?php }
		?>
						</div>

						<?php
						if (($ratingModel->rtg_customer_car <> NULL && $ratingModel->rtg_customer_car <= 4) && $ratingModel->rtg_customer_overall <= 5)
						{
							?>

							<!-------------------------------------- -->
							<div class="col-xs-12">
								<div class="panel panel-default">
									<div class="panel-body p0">
										<div class="row m0">
											<div class="col-xs-6 col-sm-6">
												&nbsp;
											</div>
										</div> 
										<div class="row flex">
											<div class="col-xs-6 col-sm-6 box-design1a2 pt0">
												<div class="row">
													<div class="col-xs-12 box-design1 border-new-b text-center"><b>What was good?</b></div>
													<div class="col-xs-12">
														<ul>
															<?php
															if (!empty($ratingModel->rtg_car_good_attr))
															{
																$data = explode(',', $ratingModel->rtg_car_good_attr);
																foreach ($data as $vb)
																{
																	?>
																	<li style="color: #009900;"><i class="fa fa-check"></i>&nbsp; <?= $data_array[$vb]['ratt_name'] ?></li>                                                                                                
																	<?php
																}
															}
															?>		
														</ul>
													</div>
												</div>
											</div>
											<div class="col-xs-6 col-sm-6 box-design2a2 pt0">
												<div class="row">
													<div class="col-xs-12 box-design2 border-new-b text-center"><b>What was not?</b></div>
													<div class="col-xs-12">
														<ul>	
															<?php
															if (!empty($ratingModel->rtg_car_bad_attr))
															{
																$data = explode(',', $ratingModel->rtg_car_bad_attr);
																foreach ($data as $vb)
																{
																	?>
																	<li style="color:#DC143C;"><i class="fa fa-times"></i>&nbsp;  <?= $data_array[$vb]['ratt_name_bad'] ?></li>                                                                                                
																	<?php
																}
															}
															?>					
														</ul>																																			
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>
							<!-------------------------------------- -->
							<div class='mt10'>Car Comment</div>

							<div class="col-xs-12 p15 rounded  mt5 mb10" style="width:99%;">
								<div class="row">
									<div class="col-xs-12">
			<?= $ratingModel->rtg_car_cmt; ?>
									</div>
								</div>
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
			if ($ratingModel->rtg_csr_customer)
			{
				?>
				<div class="col-xs-12">
					<label class="mt10 control-label">CSR Rating</label>
					<div class="col-xs-12 rounded pb10 pt10">
						<div class="row">
							<?php
							if ($ratingModel->rtg_csr_customer)
							{
								?> <div class='col-xs-6'>
									<?= $ratingModel->getAttributeLabel('rtg_csr_customer') ?><br>
									<?
									$this->widget('CStarRating', array(
										'model'		 => $ratingModel,
										'attribute'	 => 'rtg_csr_customer',
										'minRating'	 => 1,
										'maxRating'	 => 5,
										'starCount'	 => 5,
										'value'		 => $ratingModel->rtg_csr_customer,
										'readOnly'	 => true,
									));
									?></div>
								<?php
							}
							if ($ratingModel->rtg_csr_vendor)
							{
								?> <div class='col-xs-6'>
									<?= $ratingModel->getAttributeLabel('rtg_csr_vendor') ?><br>
									<?
									$this->widget('CStarRating', array(
										'model'		 => $ratingModel,
										'attribute'	 => 'rtg_csr_vendor',
										'minRating'	 => 1,
										'maxRating'	 => 5,
										'starCount'	 => 5,
										'value'		 => $ratingModel->rtg_csr_vendor,
										'readOnly'	 => true,
									));
									?></div>
								<?php
							}
							?>
						</div>
						<?php
						if ($ratingModel->rtg_csr_review)
						{
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




	<div class="row booking-log col-12">
		<div class="col-xs-12 col-lg-6 text-center">
            <label class = "control-label h3">Booking Progress Tracker</label>
            <?php
			$gpxLink = '';
			$gpxFile = $bkgTrack->btk_gpx_file;
			$gpxFileS3 = $bkgTrack->btk_gpx_s3_data;
			if($gpxFile != NULL || $gpxFileS3 != NULL)
			{
				$gpxFileUrl = Yii::app()->createUrl('admin/booking/gpx', ['bkgId' => $model->bkg_id]);
            ?>
			<b>( <a target="_blank" href="<?= $gpxFileUrl ?>">GPX</a> )</b>
			<?php }?>
            
			<?php
			Yii::app()->runController('admin/booking/track/booking_id/' . $model->bkg_id);
			?>
		</div>

		<?php
		if ($model->bkgPref->bkg_cancel_rule_id != NULL)
		{
			?>
			<div class="col-xs-12 col-lg-3 text-center">
				<label class = "control-label h3">Cancellation Policy  </label> <b> <?php echo '(' . $policyType . ')'; ?></b>
				<?php
				$cancelTimes_new = CancellationPolicy::initiateRequest($model);
				?>
				<div class="row m0">
					<div class="col-12 pl10 pr10">
						<div class="row font-11">
							<div class="col-12 mb5">
								<div class="bg-green3 p5 mb-1 color-white">
									<p class="text-center mb5 font-14">
										<b>Free Cancellation Period</b>
									</p>
									<div class="mb5 font-14 p10"><span class="float-left"><?= date('d M Y h:i a', strtotime(($bkgTrail->bkg_confirm_datetime != '') ? $bkgTrail->bkg_confirm_datetime : $model->bkg_create_date)); ?></span> <span class="float-right"><?= date('d M Y h:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?></span></div>
								</div>

								<div class="bg-orange p5 mb-1 color-white">
									<p class="text-center mb10 font-14">
										<b>Cancellation Charge: &#x20B9;<?= array_values($cancelTimes_new->slabs)[1]; ?></b>
									</p>
									<div class="mb5 font-14 p10"><span class="float-left"><?= date('d M Y h:i a', strtotime(array_keys($cancelTimes_new->slabs)[0])) ?></span> <span class="float-right"><?= date('d M Y h:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])) ?></span></div>
								</div>

								<div class="bg-red p5 mb-1 color-white">
			<!--                            <p class="text-center mb10 font-14"><b>No Refund</b></p>-->
									<p class="text-center mb10 font-14"><b>Cancellation Charge: &#x20B9;<?= $cancelTimes_new->slabs[-1] ?> <br>after <?= date('d M Y h:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])); ?></b></p>
			<!--                            <div class="mb0 font-10 p10"><span class="float-left"><?= date('d M Y H:i a', strtotime(array_keys($cancelTimes_new->slabs)[1])); ?> </span><span class="float-right">After this</span></div>-->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		if ($model->bkg_agent_id == 18190 && in_array($model->bkg_status, [2, 3, 5, 9]))
		{

			$penaltyArray = GoMmt::getPenalty($model);
			?>
			<div class="col-xs-3">
				<label class = "control-label h3 text-center">Partner Penalty Policy </label>
				<div class="row m0">
					<div class="col-12 pl10 pr10">
						<div class="row font-11">
							<div class="col-12 mb5">
								<div class="bg-green3 p5 mb-1 color-white">
									<p class="text-center mb5 font-14">
										<b>No Penalty Period</b>
									</p>
									<div class="mb5 font-12 p10"><span class="float-left"><?= date('d M Y h:i a', strtotime($penaltyArray[0]['startDate'])); ?></span> <span class="float-right"><?= date('d M Y h:i a', strtotime($penaltyArray[0]['endDate'])) ?></span></div>
								</div>

								<div class="bg-red p5 mb-1 color-white" style="background: #bf3636">
									<p class="text-center mb10 font-14">
										<b>Penalty Charges: &#x20B9;<?= $penaltyArray[1]['amount']; ?></b>
									</p>
									<div class="mb5 font-12 p10"><span class="float-left"><?= date('d M Y h:i a', strtotime($penaltyArray[1]['startDate'])) ?></span> <span class="float-right"><?= date('d M Y h:i a', strtotime($penaltyArray[1]['endDate'])) ?></span></div>
								</div>
								<?php
								if (!empty($penaltyArray[2]))
								{
									?>
									<div class="bg-red p5 mb-1 color-white">
										<p class="text-center mb10 font-12"><b>Post Pickup Penalty Charges: &#x20B9;<?= $penaltyArray[2]['amount'] ?> <br>after <?= date('d M Y h:i a', strtotime($penaltyArray[2]['startDate'])); ?></b></p>
									</div>
	<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php
		if ($model->bkg_agent_id == null && $model->bkgTrail->btr_is_dbo_applicable == 1 && in_array($model->bkg_status, [2, 3, 5, 9]))
		{
			$getDBOCompensation = $model->bkgTrail->btr_dbo_amount;
			?>
			<div class="col-xs-3">
				<label class = "control-label h3 text-center">Customer Compensation Policy (DBO) </label>
				<div class="row m0">
					<div class="col-12 pl10 pr10">
						<div class="row font-11">
							<div class="col-12 mb5">
								<div class="bg-red p5 mb-1 color-white">
									<p class="text-center mb10 font-14">
										<b>Compensation Amount: &#x20B9;<?= $getDBOCompensation; ?><br>after <?= date('d M Y h:i a', strtotime($model->bkgTrail->bkg_confirm_datetime)) ?></b>
									</p>
	<!--									<div class="mb5 font-12 p10"><span class="float-left"><? //= date('d M Y h:i a', strtotime($model->bkgTrail->bkg_confirm_datetime))          ?></span> <span class="float-right"><? //= date('d M Y h:i a', strtotime($model->bkg_pickup_date))          ?></span></div>-->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>  
	</div>




	<div class="row booking-log">
		<div class="col-xs-12 text-center">
			<label class = "control-label h3">Booking Log </label>( <?php echo CHtml::link('<b>Sms Log</b>', array('message/list', 'bookingId' => $model->bkg_booking_id), array('target' => '_blank', 'class' => 'control-label', 'title' => "Sms Log")); ?> 
			/ <?php echo CHtml::link('<b>Email Log</b>', array('email/list', 'bookingId' => $model->bkg_booking_id), array('target' => '_blank', 'class' => 'control-label', 'title' => "Email Log")); ?> 
			/ <?php echo CHtml::link('<b>Whatsapp Log</b>', array('/report/notification/whatsapplog', 'bookingId' => $model->bkg_id), array('target' => '_blank', 'class' => 'control-label', 'title' => "Whatsapp Log")); ?> )
			<?php
			Yii::app()->runController('admin/booking/showlog/booking_id/' . $model->bkg_id);
			?>
		</div>
	</div>
</div>
<script>
//    $('#seconedb').change(function ()
//    {
//        checkOneminuteLog();
//       
//    });



	$lgID = 0;
	function setTimer() {
		setTimeout(function () {
			checkOneminuteLog();
		}, 60000);
	}
	function checkOneminuteLog()
	{
		var isAddRemark = $("#isAddRemark").val();
		var timeval = $('#seconedb').val();
		if (isAddRemark != 1)
		{
			//  if (timeval % 10 == 0)
			//{
			$href = "<?= Yii::app()->createUrl('admin/booking/oneminutelog') ?>";
			var $booking_id = <?= $model->bkg_id ?>;
			jQuery.ajax({
				global: false,
				type: 'GET', dataType: 'json',
				url: $href,
				data: {"booking_id": $booking_id, "second": timeval, "bkglogID": $lgID},
				success: function (data)
				{
					//alert(data.bkgLogID);
					$lgID = data.bkgLogID | 0;
					setTimer();
				}


			});
			// }
		}
	}

	function verifyAlert()
	{
		bootbox.alert("Customer contact not verified. Ask customer to reconfirm first using pay-now link that was sent in booking confirmation email.");
		return false;
	}
	function convertAlert()
	{
		bootbox.alert("Create a new quote as price for this route has changed");
		return false;
	}
	
	function confirmPartnerBooking(bkg_id)
	{
		$href = "<?= Yii::app()->createUrl('admin/booking/confirmpartnerbooking') ?>";

		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkgid": bkg_id},
			success: function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'Confirm Booking',
					onEscape: function ()
					{
					}, });
				box.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}

	var acctbox;
	$(document).ready(function ()
	{
<?php
$spiceId	 = Config::get('spicejet.partner.id');
$sugerboxId	 = Config::get('sugerbox.partner.id');
if ($model->bkg_agent_id == $spiceId || $model->bkg_agent_id == $sugerboxId)
{
	?>
			showContact();
<?php } ?>
		countdown();
		setTimer();
		$('#view').show();
		$('#edit').hide();
		var flag = '<?= $model->bkgPref->bkg_account_flag ?>';
		if (flag > '0')
		{
			$("#clearFlag").show();
			$("#setFlag").hide();
		} else {
			$("#setFlag").show();
			$("#clearFlag").hide();
		}
		var lock_vendor_payment = '<?= $model->bkgBcb->bcb_lock_vendor_payment ?>';
		if (lock_vendor_payment == '1')
		{
			$("#releaseAmt").show();
			$("#lockAmt").hide();
		}
		if (lock_vendor_payment == '0') {
			$("#lockAmt").show();
			$("#releaseAmt").hide();
		}
		if (lock_vendor_payment == '2') {
			$("#lockAmt").show();
			$("#releaseAmt").hide();
		}

	});

	function confbooking()
	{

		bootbox.confirm({
			message: "Are you sure want to confirm this booking ?",
			buttons: {
				confirm: {
					label: 'OK',
					className: 'btn-info'
				},
				cancel: {
					label: 'CANCEL',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if (result) {
					var bkg_id = <?= $model->bkg_id; ?>;
					//  var href1 = '<?= Yii::app()->createUrl('admin/booking/confirmmobile') ?>';
					var href1 = '<?= Yii::app()->createUrl('admin/booking/bkgChangeStatus') ?>';
					jQuery.ajax({'type': 'GET', 'url': href1,
						'data': {'bid': bkg_id},
						success: function (data)
						{

							bootbox.hideAll()
							window.location.reload(true);

						}
					});
				}
			}
		});

	}

	function convertToquote()
	{

		bootbox.confirm({
			message: "Are you sure want to convert this booking to quote?",
			buttons: {
				confirm: {
					label: 'OK',
					className: 'btn-info'
				},
				cancel: {
					label: 'CANCEL',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if (result) {
					var bkg_id = <?= $model->bkg_id; ?>;
					var href1 = '<?= Yii::app()->createUrl('admin/booking/unverifiedToquote') ?>';
					jQuery.ajax({'type': 'GET', 'url': href1,
						'data': {'bkgid': bkg_id},
						success: function (data)
						{

							bootbox.hideAll()
							window.location.reload(true);

						}
					});
				}
			}
		});

	}
	function showLog(booking_id)
	{
		$href = "<?= Yii::app()->createUrl('admin/booking/showlog') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id},
			success: function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'Booking Log',
					onEscape: function ()
					{
					}, });
				box.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}

	function addCSRRating(booking_id)
	{
		$href = "<?= Yii::app()->createUrl('admin/rating/addcsrreview') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'Add CSR Review',
					onEscape: function ()
					{
					}});
				box.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}

	function showTripStatus(booking_id)
	{
		$href = "<?= Yii::app()->createUrl('admin/booking/showtripstatus') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'View Trip Status',
					onEscape: function ()
					{
					}});
				box.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}

	function addCustRating(booking_id)
	{
		$href = "<?= Yii::app()->createUrl('admin/rating/addcustreview') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": booking_id},
			success: function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'Add Customer Review',
					onEscape: function ()
					{
					}
				});
				box.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}
	function save()
	{


		$('#edit').hide();
		$('#view').show();
	}

	//    function refreshAccountDetails() {
	//        jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/view')) ?>',
	//            success: function (data)
	//            {
	//                $('#acctdata').html(data);
	//            }
	//        });
	//    }

	var refreshAccountDetails = function ()
	{
		jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/view', ['view' => 'accountsdetail', 'id' => $model->bkg_id])) ?>',
			success: function (data)
			{
				$('#acctdata').html(data);
				$('.bootbox').removeAttr('tabindex');
			}
		});
	};
	function editAccount(isRestricted)
	{
		//   $('#view').hide();
		//    $('#edit').show();
		var isRestricted = isRestricted;
		//alert(isRestricted);
		if (isRestricted == true)
		{
			booking_id = '<?= $model->bkg_id ?>';
			$href = "<?= Yii::app()->createUrl('admin/booking/editaccounts') ?>";
			jQuery.ajax({type: 'GET',
				url: $href,
				data: {"bkg_id": booking_id},
				success: function (data)
				{
					acctbox = bootbox.dialog({
						message: data,
						title: 'Edit Accounts Details',
						size: 'large',
						onEscape: function ()
						{

						}
					});
					acctbox.on('hidden.bs.modal', function (e)
					{
						$('body').addClass('modal-open');
					});
				}
			});
		} else {
			alert("Can not edit account.");
		}
	}

	function toggleDrvAppUsage()
	{
		bootbox.confirm({
			message: "Are you sure? You want to <?php echo ($model->bkgPref->bkg_driver_app_required == 1) ? "Turn OFF" : "Turn ON" ?> the driver app requirement usage",
			buttons: {
				confirm: {
					label: 'OK',
					className: 'btn-info'
				},
				cancel: {
					label: 'CANCEL',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if (result) {
					var booking_id = '<?php echo $model->bkg_id ?>';
					$.ajax({
						type: 'POST',
						url: '<?php echo Yii::app()->createUrl('admin/booking/changeDriverAppRequirementStatus'); ?>',
						data: {"bkgId": booking_id, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
						dataType: 'json',
						success: function (data)
						{
							alert(data);
							window.location.reload(true);
						}
					});
				}
			}
		});
	}

	function modifiedPaymentStatus(booking_id, bcb_id, status_type)
	{
		$href = "<?= Yii::app()->createUrl('admin/booking/modifiedPaymentStatus') ?>";

		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": booking_id, "bcb_id": bcb_id, "status_type": status_type},
			success: function (data)
			{
				//return true; 
				window.location.reload(true);
			}
		});
	}
	function addDesc(opt)
	{
		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/addaccountingremark') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": booking_id},
			success: function (data)
			{
				acctbox1 = bootbox.dialog({
					message: data,
					title: 'Add remarks for Accounting Flag',
					size: 'xs',
					onEscape: function ()
					{

					}
				});
				acctbox1.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});

				return true;
			}
		});
	}





	function showFlightStatus(bkgId)
	{
		jQuery.ajax({type: 'GET',
			url: '<?= Yii::app()->createUrl('admin/booking/flightstatus'); ?>',
			dataType: 'json',
			data: {'bkgId': bkgId},
			success: function (data)
			{
				if (data.success)
				{
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
						onEscape: function ()
						{
						}
					});
					flightbootbox.on('hidden.bs.modal', function (e)
					{
						$('body').addClass('modal-open');
					});
				} else {
					alert(data.msg);
				}



			},
			error: function (x)
			{
				alert(x);
			}
		});
	}

	function showLinkedUser(user, url)
	{
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
						onEscape: function ()
						{
						}
					});
					showuser.on('hidden.bs.modal', function (e)
					{
						$('body').addClass('modal-open');
					});
					return true;
				},
				error: function (x)
				{
					alert(x);
				}
			});
		}
	}
	function viewList(obj)
	{
		var href2 = $(obj).attr("href");

		$.ajax({
			"url": href2,
			"type": "GET",
			"dataType": "html",
			"success": function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'Booking Details',
					size: 'large',
					onEscape: function ()
					{
						// user pressed escape
					},
				});
			}
		});
		return false;
	}

	function showContact()
	{
		bkgId = '<?= $model->bkg_id ?>';
		if (bkgId > 0)
		{

			$href = "<?= Yii::app()->createUrl('admin/booking/getcontacts') ?>";
			jQuery.ajax({type: 'GET',
				url: $href,
				dataType: 'json',
				data: {"bkgId": bkgId},
				success: function (data)
				{

					if (data.success)
					{
						$("#userEmail").text(data.email);
						if (data.isShowPh == 1)
						{
							$("#userPhone").text(data.phone);
						} else {
							$("#userPhone").html("<span  class='label label-success'>Call Now</span>");
						}
						$("#userAltPhone").text(data.altPhone);
						$("#showContactDetails").hide();
						if (data.emailVerified == 1)
						{
							$('.emailverified').text("Verified");
						}
						if (data.phoneVerified == 1)
						{
							$('.phoneverified').text("Verified");
						}
					} else {
						alert("Sorry error occured");
					}
				},
				error: function (x)
				{
					alert(x);
				}
			});
		}
	}

	function showTravellerInfo()
	{
		bkgId = '<?= $model->bkg_id ?>';
		if (bkgId > 0)
		{

			$href = "<?= Yii::app()->createUrl('admin/booking/gettravellerinfo') ?>";
			jQuery.ajax({type: 'GET',
				url: $href,
				dataType: 'json',
				data: {"bkgId": bkgId},
				success: function (data)
				{

					if (data.success)
					{
						$("#trvEmail").text(data.email);
						if (data.isShowPh == 1)
						{
							$("#trvPhone").text(data.phone);
						} else {
							$("#trvPhone").html("<span  class='label label-success'>Call Now</span>");
						}
						$("#trvAltPhone").text(data.altPhone);
						$("#showTrvDetails").hide();
					} else {
						alert("Sorry error occured");
					}
				},
				error: function (x)
				{
					alert(x);
				}
			});
		}
	}

	function showAgentNotifyDefault()
	{
		$('#showagentnotifydefaults').toggleClass('hide');
	}

	function addZeros(n) {
		return (n < 10) ? '0' + n : '' + n;
	}
	function countdown()
	{
		var countDownDate = new Date().getTime();
		var x = setInterval(function () {
			var now = new Date().getTime();
			var duration = now - countDownDate;
			var seconds_show = Math.floor((duration % (1000 * 60)) / 1000);//Math.floor(duration / 1000);
			var seconds = Math.floor(duration / 1000);
			var minutes = Math.floor(seconds / 60);
			var hours = Math.floor(minutes / 60);
			if (minutes == 0)
			{
				minutes = "00";
			}
			$("#demo").text(minutes + ':' + addZeros(seconds_show));
			$("#seconedb").val(seconds).change();
		}, 1000);
	}

	function delOneminlog()
	{
		$("#isAddRemark").val("1");
		$href = "<?= Yii::app()->createUrl('admin/booking/deloneminutelog') ?>";
		var $booking_id = <?= $model->bkg_id ?>;
		jQuery.ajax({type: 'GET',
			dataType: 'json',
			url: $href,
			data: {"booking_id": $booking_id, "logID": $lgID},
			success: function (data)
			{
				//  alert(data);
			}
		});
	}

	function editPickupTime()
	{
		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/editpickuptime') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			dataType: 'html',
			data: {"bkg_id": booking_id},
			success: function (data)
			{
				acctbox = bootbox.dialog({
					message: data,
					title: 'Reschedule Pickup Time',
					size: 'medium',
					onEscape: function ()
					{

					}
				});
				acctbox.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}

	function reschedule(rescheduleFrom = 0)
	{
		if (rescheduleFrom > 0) {
			alert("Reschedule already initiated/Reschedule is allowed only once.");
			return;
		}
		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/reschedule') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			dataType: 'html',
			data: {"bkg_id": booking_id},
			success: function (data)
			{
				reschedulebox = bootbox.dialog({
					message: data,
					title: 'Reschedule Pickup Time',
					size: 'medium',
					onEscape: function ()
					{

					}
				});
				reschedulebox.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}

	function extraDiscount()
	{
		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/extraDiscountAmount') ?>";

		jQuery.ajax({type: 'GET',
			url: $href,
			dataType: 'html',
			data: {"bkgid": booking_id},
			success: function (data)
			{
				acctbox = bootbox.dialog({
					message: data,
					title: 'One-Time Price Adjustment',
					size: 'medium',
					onEscape: function ()
					{

					}
				});
				acctbox.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}

	function updateVendorAmount()
	{
		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/updateVenorAmount') ?>";

		jQuery.ajax({type: 'GET',
			url: $href,
			dataType: 'html',
			data: {"bkgid": booking_id},
			success: function (data)
			{
				acctbox = bootbox.dialog({
					message: data,
					title: 'Update Vendor Amount',
					size: 'medium',
					onEscape: function ()
					{

					}
				});
				acctbox.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}

	function priceLockSubmit() {
		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/pricelock') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			dataType: 'html',
			data: {"bkg_id": booking_id},
			success: function (data)
			{
				priceLockBox = bootbox.dialog({
					message: data,
					title: 'price lock edit',
					size: 'medium',
					onEscape: function ()
					{

					}
				});
				priceLockBox.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
		return false;
	}
	function getPaymentStatus()
	{
		booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/getPaymentStatus') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			dataType: 'html',
			data: {"bkg_id": booking_id},
			success: function (data)
			{
				pmtStsbox = bootbox.dialog({
					message: data,
					title: 'Payment Status',
					size: 'large',
					onEscape: function ()
					{
						pmtStsbox.hide();
						pmtStsbox.remove();
					}
				});
				pmtStsbox.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}

	function drvLocation(bookingId) {

		$booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('admin/booking/getDrvCurrentLocation') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			"dataType": "json",
			data: {"booking_id": $booking_id},
			success: function (data)
			{
				if (data.success)
				{

					window.open(data.destUrl, '_blank');
				}
			}
		});
	}
	function showVendorRating(booking_id)
	{
		$href = "<?= Yii::app()->createUrl('admin/booking/ShowAllBidRank') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data)
			{
				var box = bootbox.dialog({
					message: data,
					title: 'View all vendor bid rank',
					onEscape: function ()
					{
					}});
				box.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});

	}

	function changecancelreason(booking_id)
	{
		$href = "<?= Yii::app()->createUrl('admin/booking/changecancelreason') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": booking_id},
			success: function (data)
			{
				var box = bootbox.dialog({
					message: data,
					//title: 'Change Cancel Reason',
					onEscape: function ()
					{
					}
				});
				box.on('hidden.bs.modal', function (e)
				{
					$('body').addClass('modal-open');
				});
			}
		});
	}
</script>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<style>
	.tr {
		display: flex;
	}
	.th, .td {
		border-top: 1px solid #ccc;
		border-right: 1px solid #ccc;
		padding: 4px 8px;
		flex: 1;
		font-size:14px;
		overflow-wrap: break-word;
		word-wrap: break-word;
		overflow: auto;
	}
	.smallCol
	{
		max-width:15%;
	}
	.bigCol
	{
		max-width:40%;
	}
	.th {
		font-weight: bold;
	}
	.th[role="rowheader"] {
		background-color: #fff;
	}
	.th[role="columnheader"] {
		background-color: #fff;
	}
</style>

<script>
	$(document).ready(function () {

		showFollowListCall();

	});
	function cnlSubmit()
	{
		$("#scqForm").hide("slow");
	}
	function createScq()
	{
		var bkgId = <?= $model->bkg_id ?>;
		$href = "<?= Yii::app()->createUrl('admin/scq/CtrScq') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": bkgId},
			success: function (data)
			{
				$("#scq").html(data);
				$("#checkBkg").show("slow");
				$("#ServiceCallQueue_scq_related_bkg_id").val(bkgId);
				$("#isBooking").show("slow");
				showFollowListCall();
			}
		});

	}

	function showFollowListCall()
	{
		//alert("sss");
		var bkgId = <?= $model->bkg_id ?>;
		$href = "<?= Yii::app()->createUrl('admin/scq/followUps') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": bkgId},
			success: function (data)
			{
				//	alert("sss");
				//	alert(data);
				var obj = $.parseJSON(data);
				var arrList = obj.list;

				showFollowList(arrList);

			}
		});
	}

	function showFollowList(arrList)
	{
		//alert("pp");
		var html = "";
		$("#scq").show("slow");
		$("#followupSecP1").show("slow");
		var cntArr = arrList.length;
		//alert(cntArr);
		var btnHtml = "<thead><tr><td colspan='7'></td><td width='5%'><a onclick='createScq()' class='btn btn-primary full-width mb0'style='background: #0c4ba8;'> Create Followup</a></td></tr></thead>";
		$.each(arrList, function (key, value)
		{
			//alert(value["scq_to_be_followed_up_with_value"]);
			var disabledStat = "";
			var selectedDrp = "";
			var style = "";
			var remark = "";
			var reScheduleOption = "";
			if (value["scq_disposition_comments"] != null)
			{
				remark = value["scq_disposition_comments"];
			}

			if (value["scq_status"] != 1 || value["scq_assigned_uid"] != null)
			{
				disabledStat = "disabled";
				selectedDrp = "selected";
				style = "style='color:#d3d3d3'";
				$("#tr_" + value["scq_id"]).css("font-style", "italic");
			}
			// var wrap_open_comment = (value["scq_creation_comments"].length > 25)?'<span data-toggle="tooltip" data-placement="top" title="' + value["scq_creation_comments"]  + '">'+(value["scq_creation_comments"].substring(0, 25))+'...</span>':value["scq_creation_comments"];
			// var wrap_close_comment = (remark.length > 25)?(remark.substring(0, 25))+'...':remark;

			var wrap_open_comment = (value["scq_creation_comments"] != null && value["scq_creation_comments"].length > 25) ? '<span data-toggle="tooltip" data-placement="top" title="' + value["scq_creation_comments"] + '">' + (value["scq_creation_comments"].substring(0, 25)) + '...</span>' : value["scq_creation_comments"];
			var wrap_close_comment = (remark != null && remark.length > 25) ? (remark.substring(0, 25)) + '...' : remark;

			reScheduleOption = "<option value='3'>FollowUp Reschedule</option></select>";
			html = html + '<tr id ="tr_' + value["scq_id"] + '" ' + style + ' >\n\
													  <td>' + value["scq_id"] + '</td>\n\
													   <td>' + value["tea_name"] + ' / ' + value["fwpPrefdt"] + " " + value["fwpPreftm"] + '</td>\n\
													   <td>' + value["adm_fname"] + " " + value["adm_lname"] + ' / ' + value["created_adm_id"] + '</td>\n\
													   <td>' + value["followupWith"] + '</td>\n\
													   <td>' + wrap_open_comment + ' </td>\n\
													   <td>\n\
                                                            <div class="">\n\
                                                                <span data-toggle="tooltip" data-placement="top" title="' + remark + '"><input ' + disabledStat + '  value ="' + wrap_close_comment + '" plceholder="Remarks" class="form-control"  name="ServiceCallQueue[scq_creation_comments]" id="ServiceCallQueue_scq_creation_comments_' + value["scq_id"] + '" type="text" placeholder=""remarks></span>\n\
                                                                 <div class="help-block error" id="ServiceCallQueue_scq_creation_comments" style="display:none"></div></div></td>\n\
													   <td>' + value["closed_by"] + ((value["scq_disposition_date"] != null) ? ' / ' + value["scq_disposition_date"] : '') + '</td>\n\
                                                       <td style="width: 14%;"><select ' + disabledStat + ' class="form-control"  name="ServiceCallQueue_event_id" id="ServiceCallQueue_event_id_' + value["scq_id"] + '" onchange="actionFollow(' + value["scq_id"] + ');"><option value="0">Select</option><option ' + selectedDrp + ' value="2">FollowUp Completed</option>' + reScheduleOption + '</select></td>\n\
                                                       </tr>';
		});
		//alert(html);
		if (cntArr > 0)
		{
			//	alert("jjj");
			$('#followupSecP1').html('<table width="90%" class="table table-bordered">' + btnHtml + '<tr><th>Id</th><th>Team / Created At</th><th>Created by / EmpID</th><th>FollowUp With</th><th>Opening comment</th><th>Closing comment</th><th>Closed By / Closed At</th><th width="5%">Status</th></tr>' + html + '</table>');
		} else {
			// alert("kkk");
			$('#followupSecP1').html('<table width="90%"  class="table table-bordered">' + btnHtml + '</table>');
		}
	}


	function actionFollow(followId)
	{
		var remarks = $("#ServiceCallQueue_scq_creation_comments_" + followId).val();
		var eventId = $("#ServiceCallQueue_event_id_" + followId).val();
		if (remarks == "")
		{
			bootbox.alert("Please enter remark.");
			$("#ServiceCallQueue_event_id_" + followId).prop('selectedIndex', 0);
			return;
		}

		var isReSchedule = (eventId == 3) ? 1 : 0;
		if (isReSchedule == 1)
		{
			bootbox.confirm("Are you sure you want to reschedule this followup to a later time?", function (result) {
				if (result)
				{
					var bkgId = <?= $model->bkg_id ?>;
					$href = "<?= Yii::app()->createUrl('admin/scq/CtrScq') ?>";
					jQuery.ajax({type: 'GET',
						url: $href,
						contentType: "application/json",
						dataType: "text",
						data: {"bkg_id": bkgId, "scqId": followId, "isReschedule": isReSchedule},

						success: function (data)
						{
							$("#scq").html(data)
							$("#ServiceCallQueue_isBooking_0").prop('checked', true);
							$("#isBooking").show("slow");
							$("#checkBkg").show("slow");
							$("#ServiceCallQueue_scq_related_bkg_id").val(<?= $model->bkg_id ?>);
							showFollowListCall();
						}
					});
				} else
				{
					$("#ServiceCallQueue_event_id_" + followId).prop('selectedIndex', 0);
					$("#ServiceCallQueue_scq_creation_comments_" + followId).val("");
					return;
				}
			});

		} else
		{
			$href = "<?= Yii::app()->createUrl('admin/scq/registerlog') ?>";
			jQuery.ajax({type: 'GET',
				url: $href,
				data: {"refId": followId,
					"remarks": $("#ServiceCallQueue_scq_creation_comments_" + followId).val(),
					"eventId": $("#ServiceCallQueue_event_id_" + followId).val(),
					"flag": isReSchedule, },
				success: function (data)
				{
					var obj = $.parseJSON(data);
					if (obj.result == 1)
					{
						$("#ServiceCallQueue_scq_creation_comments_" + followId).attr("disabled", "disabled");
						$("#ServiceCallQueue_event_id_" + followId).attr("disabled", "disabled");
						$('#followupSecP2').show("slow");
						$('#followupSecP2').text('Followup done');
						if (eventId == 3)
						{
							$('#heading').text('Reschedule');
							showReScheduleForm(followId);
						}
						showFollowListCall();
					}
				}
			});
		}






	}
	function showReScheduleForm(fwpID)
	{
		$href = "<?= Yii::app()->createUrl('admin/scq/details') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"refId": fwpID},
			success: function (data)
			{
				var obj = $.parseJSON(data);
				var details = obj.detail;
				$('#heading1').text('Reschedule');
				$("#ServiceCallQueue_scq_disposition_comments").val(details.scq_disposition_comments);
				$("#ServiceCallQueue_locale_followup_date").val("");
				$("#ServiceCallQueue_locale_followup_time").val("");
				$("#ServiceCallQueue_scq_prev_or_originating_followup").val(details.scq_prev_or_originating_followup != null ? details.scq_prev_or_originating_followup : details.scq_id);
				$('#ServiceCallQueue_followupWith').select2("val", details.scq_to_be_followed_up_with_entity_type);
				$('#ServiceCallQueue_scq_to_be_followed_up_by_id').select2("val", details.scq_to_be_followed_up_by_id);
			}
		});
	}

	function showIncExc(text)
	{

		if (text == "toll")
		{
			$('#tollDesc').toggleClass("hide");
		}
		if (text == "state")
		{
			$('#stateDesc').toggleClass("hide");
		}
		if (text == "airport")
		{
			$('#airportDesc').toggleClass("hide");
		}
		if (text == "parking")
		{
			$('#parkingDesc').toggleClass("hide");
		}
		if (text == "nightPickup")
		{
			$('#nightPickupDesc').toggleClass("hide");
		}
		if (text == "nightDrop")
		{
			$('#nightDropDesc').toggleClass("hide");
		}
		if (text == "addon")
		{
			$('#addonDesc').toggleClass("hide");
		}

	}
	function stopSystemMaxAllowableVndAmount()
	{
		bootbox.confirm({
			message: "Are you sure? You want to stop increasing vendor amount",
			buttons: {
				confirm: {
					label: 'OK',
					className: 'btn-info'
				},
				cancel: {
					label: 'CANCEL',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if (result) {
					var bkgid = '<?php echo $model->bkg_id ?>';
					$.ajax({
						type: 'POST',
						url: '<?php echo Yii::app()->createUrl('admin/booking/stopSystemMaxAllowableVndAmount'); ?>',
						data: {"bkgid": bkgid, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
						dataType: 'json',
						success: function (data)
						{
							alert(data);
							window.location.reload(true);
						}
					});
				}
			}
		});
	}

	$("#saveCsrFeedBack").on("click", function () {
		var bkgid = '<?php echo $model->bkg_id ?>';
		var bkgstatus = '<?php echo $model->bkg_status ?>';
		var customer_to_driver_rating = $("#customer_to_driver_rating").val();
		var driver_to_cust_rating = $("#driver_to_cust_rating").val();
		var cust_to_car_rating = $("#cust_to_car_rating").val();
		var csr_to_customer_rating = $("#csr_to_customer_rating").val();
		var csr_to_driver_rating = $("#csr_to_driver_rating").val();
		if (customer_to_driver_rating != '' || driver_to_cust_rating != '' || cust_to_car_rating != '' || csr_to_customer_rating != '' || csr_to_driver_rating != '')
		{
			const arrayStatus = [2, 3, 4, 5, 6, 7];
			if (arrayStatus.includes(parseInt(bkgstatus)))
			{
				$.ajax({
					type: 'POST',
					url: '<?php echo Yii::app()->createUrl('admin/booking/CsrFeedBack'); ?>',
					data: {"bkgid": bkgid, "bkgstatus": bkgstatus, "customer_to_driver_rating": customer_to_driver_rating, "driver_to_cust_rating": driver_to_cust_rating, "cust_to_car_rating": cust_to_car_rating, "csr_to_customer_rating": csr_to_customer_rating, "csr_to_driver_rating": csr_to_driver_rating, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
					dataType: 'json',
					success: function (data)
					{
						if (data.success)
						{
							$(".hidebtn").show();
//                            window.location.reload(true);
						} else
						{
							bootbox.alert("Some error occured");
						}
					}
				});
			} else
			{
				bootbox.alert("Booking need to in booking status from (2, 3, 4, 5, 6, 7)");
			}

		} else
		{
			bootbox.alert("Please select a least one option from dropdown");
		}
	});
</script>

