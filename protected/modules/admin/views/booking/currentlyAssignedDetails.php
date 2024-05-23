<?php
/* @var $model Booking */
//$cityList = CHtml::listData(Cities::model()->findAll(array('order' => 'cty_name', 'condition' => 'cty_active=:act', 'params' => array(':act' => '1'))), 'cty_id', 'cty_name');
$status				 = Booking::model()->getBookingStatus();
//$adminlist = Admins::model()->findNameList();
//$statuslist = Booking::model()->getActiveBookingStatus();
$reconfirmStatus	 = Booking::model()->getReconfirmStatus();
$cancelDetail		 = CancelReasons::model()->findByPk($model->bkg_cancel_id);
$rutInfo			 = [];
$cntRut				 = count($bookingRouteModel);
$spclInstruction	 = $model->getFullInstructions();
$vencabdriver		 = $model->getBookingCabModel();
$infosource			 = BookingAddInfo::model()->getInfosource('admin');
$isDboMaster		 = Yii::app()->params['dboMaster'];
$criticalityFactor	 = $maxout				 = $cng				 = $escalate			 = $dutySlip			 = $drvAppRequired		 = $selfAssignedTrue	 = $dboflag			 = $assignmentBadges	 = $demSupBadge		 = $assignMode			 = $needSupply			 = $followup			 = $teamBatch			 = $accountFlag		 = $cancelFlag;
$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
if($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
{
	$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
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
    .rounded-margin{ margin: 0 15px;}
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
    .box-design1{ background: #8DCF8A; color: #000; padding: 10px;}
    .box-design1a{ background: #ccffcc; color: #000;}
    .box-design2{ background: #F8A6AC; color: #000;  padding: 10px;}
    .box-design2a{ background: #ffcccc; color: #000; }
    .label-tab label{ margin:0 17%!important}
    .label-tab .form-group{ margin-bottom: 0;}



	.border-01{border: 1px #EF5350 solid; color: #EF5350; background: #fff;}
	.border-02{border: 1px #EC407A solid; color: #EC407A;background: #fff;}
	.border-03{border: 1px #AB47BC solid; color: #AB47BC;background: #fff;}
	.border-04{border: 1px #7E57C2 solid; color: #7E57C2;background: #fff;}
	.border-05{border: 1px #5C6BC0 solid; color: #5C6BC0;background: #fff;}
	.border-06{border: 1px #42A5F5 solid; color: #42A5F5;background: #fff;}
	.border-07{border: 1px #388E3C solid; color: #388E3C;background: #fff;}
	.border-08{border: 1px #689F38 solid; color: #689F38;background: #fff;}
	.border-09{border: 1px #FFAA00 solid; color: #FFAA00;background: #fff;}



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
</style>
<?php $name				 = Admins::model()->findById($userInfo->userId); ?>
<div id="view">
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
            <div class="row p20">
                <div class="col-xs-12 heading_box">Booking Information</div>
                <div class="col-xs-12 main-tab1">
                    <div class="row new-tab-border-b">
                        <div class="col-xs-12 col-sm-6 new-tab-border-r">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Traveller Name</b></div>
                                <div class="col-xs-7"><?php
									$isQrAgent = ($model->bkgUserInfo->bkg_user_id)? Agents::checkQrAgentByUser($model->bkgUserInfo->bkg_user_id) : false;
									$urlUser = ($model->bkg_agent_id != NULL && $isQrAgent == false) ? "javascript:void(0)" : Yii::app()->createUrl('admin/user/view', array("id" => $model->bkgUserInfo->bkg_user_id));
									echo $model->bkgUserInfo->bkg_user_fname . ' ' . $model->bkgUserInfo->bkg_user_lname;
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
                                <div class="col-xs-7"><?= $model->bkgFromCity->cty_name . ' to ' . $model->bkgToCity->cty_name; ?></div>
                            </div>
                        </div>
						<?php
						$cabmodel	 = $model->getBookingCabModel();
						$scvId		 = $model->bkgSvcClassVhcCat->scv_scc_id;
						if ($scvId == 4 || $scvId == 5)
						{
							$vhcTypeModel	 = VehicleTypes::model()->findByPk($model->bkg_vht_id);
							$vhcModel		 = ' - ' . $vhcTypeModel->vht_make . ' ' . $vhcTypeModel->vht_model;
						}
						?>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row new-tab1">
                                <div class="col-xs-5"><b>Cab Type:</b></div>
                                <div class="col-xs-7"><?=
									$model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(<strong>' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . $vhcModel . '</strong>)<br>';
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
					<?
					if ($model->bkg_return_date != '' && $model->bkg_booking_type == '2')
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
                                <div class="col-xs-5"><b>Info source:</b></div>
                                <div class="col-xs-7"><?= ( $model->bkgAddInfo->bkg_info_source != '') ? $infosource[$model->bkgAddInfo->bkg_info_source] : "&nbsp;" ?></div>
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
						<?
						if ($model->bkg_agent_id > 0)
						{
							?>
							<div class="col-xs-12 col-sm-12 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5  text-danger"><b>BOOKING TYPE:</b></div>
									<div class="col-xs-7"><?php
										$agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
										if ($agentsModel->agt_type == 1)
										{
											echo "<span class='text-danger'>CORPORATE (" . ($agentsModel->agt_company) . ")<br></span>";
											echo "CORPORATE BOOKING. CLEAN CAR. WELL BEHAVED DRIVER.<br>";
											echo "Customer Due <i class='fa fa-inr'></i>" . $model->bkgInvoice->bkg_due_amount;
										}
										else
										{
											$owner = ($agentsModel->agt_owner_name != '') ? $agentsModel->agt_owner_name : ($agentsModel->agt_fname . " " . $agentsModel->agt_lname);
											echo "PARTNER (" . ($agentsModel->agt_company . "-" . $owner) . ")<br>";
											echo "<b>Booking Referral ID: <span class='text-info'>" . $model->bkg_agent_ref_code . "</span></b>";
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
												foreach ($arrEvents as $key => $value)
												{
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
						<? }
						    else{
								?>
							<div class="col-xs-12 col-sm-12 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5  text-danger"><b>BOOKING TYPE:</b></div>
									<div class="col-xs-7">
										<?php	
											$contactId					 = ContactProfile::getByEntityId($model->bkgUserInfo->bkg_user_id);
											$contactModel				 = Contact::model()->findByPk($contactId);
											$firstname					 = $contactModel->ctt_first_name;
											$lastname					 = $contactModel->ctt_last_name;

											$isQrAgent = ($model->bkgUserInfo->bkg_user_id)? Agents::checkQrAgentByUser($model->bkgUserInfo->bkg_user_id) : false;
											$urlUser = ($model->bkg_agent_id != NULL && $isQrAgent == false) ? "javascript:void(0)" : Yii::app()->createUrl('admin/user/view', array("id" => $model->bkgUserInfo->bkg_user_id));
											
											if ($model->bkgUserInfo->bkg_user_id > 0)
											{
												echo "B2C (<a target='_blank' href='$urlUser'>" . ($firstname . " " . $lastname) . ")</a><br>";
											}
											else
											{
												echo $firstname . ' ' . $lastname;
											}
										?>
									</div>
								</div>
							</div>	
						<?
							}
						if ($model->bkgAddInfo->bkg_file_path != '')
						{
							?>
							<div class="col-xs-12 col-sm-6 new-tab-border-r">
								<div class="row new-tab1">
									<div class="col-xs-5"><b>File Path:</b></div>
									<div class="col-xs-7"><a href="<?= $model->bkgAddInfo->bkg_file_path ?>" target="_blank">File</a></div>
								</div>
							</div>
						<? } ?>
						<?
						if (($model->bkg_status == 8 || $model->bkg_status == 9) && $model->bkg_cancel_delete_reason != '')
						{
							?>
							<?
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
	if ($cntRut > 0)
	{
		$diffdays = 0;
		foreach ($bookingRouteModel as $key => $bookingRoute)
		{
			$rutName		 = $bookingRoute->brtFromCity->cty_name . ' to ' . $bookingRoute->brtToCity->cty_name;
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
								if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time != '0')
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
									<?php echo CHtml::link($model->bkg_bcb_id, Yii::app()->createUrl("admin/booking/triprelatedbooking", ["tid" => $model->bkg_bcb_id]), ["class" => "viewRelatedBooking", "onclick" => "return viewList(this)"]); ?>
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
								}
								?>
                                Name: <b><?= CHtml::link($modelMergedVendor->vnd_name, Yii::app()->createUrl("admin/vendor/view", ["id" => $modelMergedVendor->vnd_id]), ["target" => "_blank"]); ?></b></br>
                                Phone: <?= $number; //$vencabdriver->bcbVendor->vnd_phone    ?><br>
                                Rating:  <?php
								if ($modelMergedVendor->vendorStats->vrs_vnd_overall_rating > 0)
								{
									$ratingType = ($modelMergedVendor->vendorStats->vrs_vnd_overall_rating >= 4) ? 'success' : 'danger';
									echo '<span class="label label-' . $ratingType . '">' . $vencabdriver->bcbVendor->vendorStats->vrs_vnd_overall_rating . '</span>';
								}
								?><br>
                                Lifetime Trips: <?= $vencabdriver->bcb_vendor_trips ?><br>
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
								<?
								$modelDriver = Drivers::model()->mergedDriverId($vencabdriver->bcb_driver_id);
								$driverName	 = $modelDriver->drvContact->ctt_name;
								$numberAlt	 = $model->bkgBcb->bcb_driver_phone;
								$drvCttId	 = $modelDriver->drv_contact_id;
								if ($drvCttId != '')
								{
									$number = ContactPhone::model()->getContactPhoneById($drvCttId);
								}
								?>	
                                Name: <b><?= CHtml::link($driverName, Yii::app()->createUrl("admin/driver/list", ["code" => $modelDriver->drv_code]), ["target" => "_blank"]); ?></b></br>
                                Phone: <?= $number; ?> <?= ($numberAlt != '') ? "/ $numberAlt(Booking No.)" : " " ?><br>
                                Rating: <?php
								if ($modelDriver->drv_overall_rating != '')
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
								?><br>
                                Lifetime Trips: <?= $vencabdriver->bcb_driver_trips ?><br>
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
                            </td>
                            <td   style="vertical-align: middle;" rowspan="<?= $cntRut ?>">
                                Vehicle name: <b><?= CHtml::link($vehicleModel, Yii::app()->createUrl("admin/vehicle/list", ["code" => $bookData['vhc_code']]), ["target" => "_blank"]); ?></b><br>
                                Car number: <?= $vencabdriver->bcbCab->vhc_number ?><br>
                                Rating: <?php
								if ($vencabdriver->bcbCab->vhc_overall_rating != '')
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
								?><br>
                                Lifetime Trips: <?= $vencabdriver->bcb_cab_trips ?><br>
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
                                                                            <?php if ($note[$i]['dnt_area_type'] == 1) { ?>
                                                                                        <?= ($note[$i]['dnt_zone_name']) ?>
                                                                            <?php }?>
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


							if (($ratingModel->rtg_customer_driver <> NULL && $ratingModel->rtg_customer_driver < 4) && $ratingModel->rtg_customer_overall < 5)
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
							if (($ratingModel->rtg_customer_csr <> NULL && $ratingModel->rtg_customer_csr < 4) && $ratingModel->rtg_customer_overall < 5)
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
						if (($ratingModel->rtg_customer_car <> NULL && $ratingModel->rtg_customer_car < 4) && $ratingModel->rtg_customer_overall < 5)
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




	<div class="row booking-log">
        <div class="col-xs-12 text-center">
            <label class = "control-label h3">Booking Progress Tracker </label>
			<?php
			Yii::app()->runController('admin/booking/track/booking_id/' . $model->bkg_id);
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

    var acctbox;
    $(document).ready(function ()
    {
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
    function editAccount()
    {
        //   $('#view').hide();
        //    $('#edit').show();

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