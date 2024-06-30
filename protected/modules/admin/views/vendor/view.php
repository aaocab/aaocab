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
	.bordered {
		border: 1px solid #ddd;
		min-height: 45px;
		line-height: 1.2em;
		margin-bottom: 10px;
		margin-left: 10px;
		margin-right: 10px;
		padding-bottom: 10px;
	}       
	.scroll {             
		height: 250px;
		overflow-x: hidden;
		overflow-y: auto;
		text-align:justify;
	}
</style>

<?php
$merged = '';
$mergedId = Yii::app()->request->getParam('id');
$mergedCode = Yii::app()->request->getParam('code');
if(($mergedId != '' && $mergedId != $model->vnd_id) || ($mergedCode != '' && strtoupper($mergedCode) != strtoupper($model->vnd_code)))
{
	$merged = ' <span class="label label-info font-10" title="Merged">Merged</span>';
}

$checkAccess=  Yii::app()->user->checkAccess('temporaryRatingBoost');
if ($model != null)
{
	$ynList		 = [1 => 'Yes', 0 => 'No'];
	$ynList1	 = [1 => 'Yes', 0 => 'No'];
	$accType	 = $model->accType;
	$firmType	 = $model->firm_type;
	$vndId		 = $data['vnd_id'];
	$ownerName	 = Contact::model()->getNameById($vndId);
	//$altContact	 = ContactPhone::model()->getAlternateContactById($vndId);
	
	/*  @var $model Vendors */
	$nextTripDetails = BookingCab::getNextTripByVndId($model->vnd_id);
	$calAmount		 = AccountTransDetails::model()->calAmountByVendorId($model->vnd_id);
	$batch			 = "";
	$state			 = "";
	
	if ($data['vnd_overall_rating'] > 4) //According to dipesh sir mantis Id 2522
	{
		$batch = '<img src="/images/icon/plan-gold.png"  style="cursor:pointer ;" title="Value" width="40">';
	}
	else
	{
		$batch = '<img src="/images/icon/plan-silver.png"  style="cursor:pointer ;" title="Value" width="40">';
	}

	if ($data['vnp_is_attached'] == 1)
	{
		$state = "Attached";
	}
	if ($data['vnd_is_freeze'] == 1)
	{
		$state = "Frozen";
	}
	if ($data['vnd_is_freeze'] == 2)
	{
		$state = "Adminstrative Frozen";
	}
	?>

	<div class="row widget-tab-content mb30">
		<div class="col-xs-12">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-4 col-lg-3">
						<!-- Nav tabs -->
						<div class="widget-tab-box mb30">
							<ul class="nav nav-tabs" role="tablist" id="viewId">
								<li role="presentation" class="p15 pl20 ml5"><b>Vendor's Information</b></li>
								<li role="presentation" class="active"><a href="#vendorDetails" aria-controls="vendorDetails" role="tab" data-toggle="tab">Dashboard</a></li>
								<li role="presentation" id="vendorTripDetailsli"><a  href="#vendorTripDetails" aria-controls="vendorTripDetails" role="tab" data-toggle="tab">Trip Details</a></li>
								<li role="presentation" id="vehicledetailsli"><a href="#vehicleDetails" aria-controls="vehicleDetails" role="tab" data-toggle="tab">Vehicle Details</a></li>
								<li role="presentation" id="driverdetailsli"><a href="#driverDetails" aria-controls="driverDetails" role="tab" data-toggle="tab">Driver Details</a></li>
								<li role="presentation" id="vendorRatingListli"><a  href="#vendorRatingList" aria-controls="vendorRatingList" role="tab" data-toggle="tab">Rating List</a></li>
								<li role="presentation" id="vendorCollectionli"><a href="#vendorCollection" aria-controls="vendorCollection" role="tab" data-toggle="tab">Accounting Summary</a></li>
								<li role="presentation" id="vendorZonesli"><a href="#zones" aria-controls="zones" role="tab" data-toggle="tab">Zones</a></li>
								<li role="presentation" id="vendorOnboardingli"><a href="#vendorOnboarding" aria-controls="vendorOnboarding" role="tab" data-toggle="tab">Documents</a></li>
								<li role="presentation" id="profileStrengthli"><a href="#profileStrength" aria-controls="profileStrength" role="tab" data-toggle="tab">Profile Strength</a></li>
								<li role="presentation" id="biddingLogli"><a href="#biddingLog" aria-controls="biddingLog" role="tab" data-toggle="tab">Bidding Log</a></li>
								<li role="presentation" id="penaltyli"><a href="#penalty" aria-controls="penalty" role="tab" data-toggle="tab">Penalty</a></li>
								<li role="presentation" id="appuse"><a href="#appusage" aria-controls="appusage" role="tab" data-toggle="tab">App Usage</a></li>
								<li role="presentation" id="viewLogli"><a href="#viewLog" aria-controls="viewLog" role="tab" data-toggle="tab">View Log</a></li>
								<li role="presentation" id="contactLogs"><a href="#contactLog" aria-controls="contactLog" role="tab" data-toggle="tab">Document Log</a></li>
								<li role="presentation" id="cbrdetailsli"><a href="#cbrdetails" aria-controls="cbrdetails" role="tab" data-toggle="tab">SCQ Details</a></li>
								<li role="presentation"><a href="<?php echo Yii::app()->createUrl("admin/vendor/add", array("agtid" => $data[vnd_id])); ?>" aria-controls="editVendor" target="_blank">Edit Vendor</a></li>
								<li role="presentation"id="notificationLogli"><a href="#notificationLog" aria-controls="notificationLog" role="tab" data-toggle="tab">Notification log</a></li>
								<li role="presentation"id="coinDetailsli"><a href="#coinDetails" aria-controls="coinDetails" role="tab" data-toggle="tab">Coin Details</a></li>
							</ul>
						</div>
						<?php
						if ($nextTripDetails)
						{
							$nextTripdate	 = date('jS M Y (l)', strtotime($nextTripDetails['bkg_pickup_date']));
							$formCityId		 = Cities::model()->getCityNameById($nextTripDetails['bkg_from_city_id']);
							$toCityId		 = Cities::model()->getCityNameById($nextTripDetails['bkg_to_city_id']);
							?>
							<div class="widget-tab-box mb30">
								<ul class="nav nav-tabs pb20" role="tablist">
									<center>
										<li role="presentation" class="p10"><b>Cabs next trip</b></li>
										<li role="presentation" class="p5"><?php echo $nextTripdate; ?></li>
										<li role="presentation" class="p5"><b><a href="/aaohome/booking/view?id=<?= $nextTripDetails['bkg_id'] ?>" target="_blank"><?php echo $nextTripDetails['bkg_booking_id']; ?></a></b></li>
										<li role="presentation" class="p5">(<?php echo $formCityId[0]['cty_name'] . ' to ' . $toCityId[0]['cty_name'] ?>)</li>
										<li role="presentation" class="p5"><?= Booking::model()->getBookingType($nextTripDetails['bkg_booking_type']); ?></li>
									</center>
								</ul>
							</div>
						<?php } ?>
					</div>
					<?php
					if ($data["ctt_first_name"] != "" && $data["ctt_last_name"] != "")
					{
						$vndName = ucfirst(strtolower($data["ctt_first_name"])) . ' ' . ucfirst(strtolower($data["ctt_last_name"]));
					}
					else if ($data["ctt_business_name"] != "")
					{
						$vndName = ucfirst(strtolower($data["ctt_business_name"]));
					}
					if (strpos($model->vnd_name, $data["ctt_first_name"]) !== false)
					{
						$vndName = "";
					}
					?>
					<div class="col-xs-12 col-sm-8 col-lg-9">
						<!-- Tab panes -->
						<div class="widget-tab-box">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane tabHide active" id="vendorDetails">
									<div class="panel">
										<div class="panel-heading p0 pt5">
											<?= $model->vnd_name . ((!empty($vndName)) ? ' [ ' . $vndName . ' ] ' : '') . ' ' . $batch . $merged; ?> 
										</div>
										<div class="panel-body p0 pt20">
											<div class="row">
												<div class="col-xs-12 mb30">
													<?php
													if ($data['vnd_is_freeze'] == 1)
													{
														//echo ' <span class="btn-4 mr15">Frozen</span>';
														
														if($data['vnp_credit_limit_freeze']==1)
														{
															$freezeType ="Credit limit Frozen";
														}
														if($data['vnp_low_rating_freeze']==1)
														{
															$freezeType ="Low rating Frozen";
														}	
														if($data['vnp_doc_pending_freeze']==1)
														{
															$freezeType ="Document pending Frozen";
														}
														if($data['vnp_manual_freeze']==1)
														{
															$freezeType ="Manual Frozen";
														}
														echo ' <span class="btn-4 mr15">'.$freezeType.'</span>';
													}
													if ($data['vnd_active'] == 1)
													{
														echo ' <span class="btn-5 mr15">Active</span>';
													}
													elseif ($data['vnd_active'] == 2) 
													{
													    echo '<span class="btn-5 mr15">Deactive</span>';
												    }
													elseif ($data['vnd_active'] == 3) 
													{
													    echo '<span class="btn-5 mr15">Pending Approval</span>';
												    }
													elseif ($data['vnd_active'] == 4) 
													{
													    echo '<span class="btn-5 mr15">Ready for Approval</span>';
												    }
													else
													{
														echo ' <span class="btn-4 mr15">Inactive</span>';
													}
													?>	
												</div>
												<div class="col-xs-12">
													<div class="row">
														<div class="col-xs-12 col-md-8 mb20">
															<div class="widget-tab-box2">
																<div class="row mb20">
																	<div class="col-xs-12 col-md-8">
																		<h1 class="mb5"><i class="fas fa-user"></i> Vendor Details</h1>
																		<?php
																		$date2		 = date_create(date("Y-M-d"));
																		$date1		 = date_create(date("Y-M-d", strtotime($data['vnd_create_date'])));
																		$diff2		 = date_diff($date1, $date2);
																		$datediff	 = $diff2->format("%R%a days");
																		$years		 = round($datediff / 365);

																		if ($data['vnd_total_trips'] != "")
																		{
																			$vndTotalTrips = $data['vnd_total_trips'];
																		}
																		else
																		{
																			$vndTotalTrips = 0;
																		}
//                                                                    
																		?>

																		<p class="color-gray">DOJ: <b><?= date('d M Y', strtotime($data['vnd_create_date'])); ?></b> | <b><?= $years ?>+</b> year old | <b><?= $vndTotalTrips ?>+</b> trip completed</p>
																	</div>  
																	<div class="col-xs-12 col-md-4 text-right">
																		<p class="mb0">

																			<i class="fas fa-star color-<?= (round($data['vnd_overall_rating']) >= 1) ? 'yellow' : 'gray'; ?>"></i>
																			<i class="fas fa-star color-<?= (round($data['vnd_overall_rating']) >= 2) ? 'yellow' : 'gray'; ?>"></i>
																			<i class="fas fa-star color-<?= (round($data['vnd_overall_rating']) >= 3) ? 'yellow' : 'gray'; ?>"></i>
																			<i class="fas fa-star color-<?= (round($data['vnd_overall_rating']) >= 4) ? 'yellow' : 'gray'; ?>"></i>
																			<i class="fas fa-star color-<?= (round($data['vnd_overall_rating']) >= 5) ? 'yellow' : 'gray'; ?>"></i>
																		</p>

																		<?php
																		if ($data['vnd_overall_rating']||$data['vnd_overall_rating']!=null)
																		{
																			$vndOverallRating = round($data['vnd_overall_rating'], 2);
																		}
																		else
																		{
																			$vndOverallRating = 0;
																		}
																		?>                                                                  
																		<p class="color-gray"><?= $vndOverallRating; ?>/5 Rating <?php // if ($data['countRating'] != ''){ echo '(' . $data['countRating'] . ' people)'; } else { echo ''; }                     ?></p>
																	</div>  
																</div> 
																<div class="row mb10">
																	<div class="col-xs-12 col-md-6">
																		<p class="mb0 color-gray">Vendor Code / IdNumber</p>
																		<p class="font-14"><b><?= $model->vnd_code ?> / <?= $model->vnd_id ?></b></p>
																	</div>
																	<div class="col-xs-12 col-md-6">
																		<p class="mb0 color-gray">Contact No</p>
																		<p class="font-14"><b><?= $data['vnd_contact_number']; ?></b></p>
																	</div>
																	<div class="col-xs-12 col-md-6">
																		<p class="mb0 color-gray">Contact Address</p>
																		<p class="font-14"><b><?= $data['vnd_city_name']; ?>, <?= $data['vnd_address']; ?></b></p>
																	</div>
																	<div class="col-xs-12 col-md-6">
																		<p class="mb0 color-gray">Email Id</p>
																		<p class="font-14"><b><?= $data['vnd_email']; ?></b></p>
																	</div>
																	<div class="col-xs-12 col-md-6">
																		<p class="mb0 color-gray">PAN number</p>
																		<p class="font-14"><b><?php
																				if (($model->vndContact->ctt_pan_no != '' && $model->vndContact->ctt_pan_no != NULL))
																				{
																					echo $model->vndContact->ctt_pan_no;
																				}
																				else
																				{
																					$askForPan = '<a href="/aaohome/document/view?ctt_id=' . $data['cr_contact_id'] . '" target="_blank">ask for PAN</a>';
																					echo '<span class="text-danger">PAN missing</span>' /* . $askForPan */;
																				}
																				?></b></p>
																	</div>
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Home Zone</p>
																		<p class="font-14"><b><?= $data['vnd_home_zone']; ?></b></p>
																	</div>
																</div>

																<div class="row mb10">
																	<div class="col-xs-12 mb30">
																		<h2>Tags</h2>
																		<span class="tags-btn" <?php
																		if ($data['vnp_boost_enabled'] > 0)
																		{
																			?> style="background:#48b9a7"> <i class="fas fa-check mr5"></i> <?php
																			  }
																			  else
																			  {
																				  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?> Boosted</span>
																	</div>	
																	<div class="col-xs-12 mb0">
																		<h2>Trip Type</h2> 
																		<!--                                                                    <span class="tags-btn2"  style="background:#48b9a7"> <i class="fas fa-check mr5"></i>Outstation</span>
																		<span class="tags-btn2"  style="background:#48b9a7"> <i class="fas fa-check mr5"></i>Local</span>
																		<span class="tags-btn2"  style="background:#48b9a7"> <i class="fas fa-check mr5"></i>Airport Transfer</span>-->
																		<span class="tags-btn2"  <?php
																		if ($data['vnp_oneway'] == 1)
																		{
																			?>style="background:#48b9a7"> <i class="fas fa-check mr5"></i><?php
																			  }
																			  else
																			  {
																				  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?>Oneway</span>
																		<span class="tags-btn2"  <?php
																		if ($data['vnp_round_trip'] == 1)
																		{
																			?>style="background:#48b9a7"> <i class="fas fa-check mr5"></i><?php
																			  }
																			  else
																			  {
																				  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?>Round Trip</span>
																		<span class="tags-btn2"  <?php
																		if ($data['vnp_airport'] == 1)
																		{
																			?>style="background:#48b9a7"> <i class="fas fa-check mr5"></i><?php
																			  }
																			  else
																			  {
																				  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?>Airport Transfer</span>
																		<span class="tags-btn2"  <?php
																		if ($data['vnp_daily_rental'] == 1)
																		{
																			?>style="background:#48b9a7"> <i class="fas fa-check mr5"></i><?php
																			  }
																			  else
																			  {
																				  ?> style="background:#ff4646"> <i class="fas fa-times mr5"></i> <?php } ?>Daily Rental</span>
																		<br>
																		<br>
																	</div>
																</div>

																<div class="row mb10">
																	<div class="col-xs-12 mb10"> 
																		<h2>Bank Details</h2>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Bank name : <b><?= $data['vnd_bank_name']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">IFSC Code : <b><?= $data['vnd_bank_ifsc']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Bank Branch : <b><?= $data['vnd_bank_branch']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Beneficiary name : <b><?= $data['vnd_beneficiary_name']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Account no. : <b><?= $data['vnd_bank_account_no']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Beneficiary ID : <b><?= $data['vnd_beneficiary_id']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Account type : <b><?= ($data['vnd_account_type'] == 0) ? 'Savings' : 'Current'; ?></b></p>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="row mb10">
																	<div class="col-xs-12 mb10"> 
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Firm Type : <b><?= $firmType[$data['vnd_firm_type']]; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">PAN : <b><?= $model->vnd_firm_pan; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">CCIN : <b><?= $model->vnd_firm_ccin; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-12 col-md-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">

																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-xs-12 col-md-4">
															<div class="row">
																<div class="col-xs-12 mb20">
																	<div class="widget-tab-box2 link-infos">
																		<h1 class="font-16">Actions</h1>
																		<ul class="pl0">
																			<li class="mb5"><a  href="<?php echo Yii::app()->createUrl("admin/booking/list", ['vndid' => $model->vnd_id]) ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Booking History</a></li>
																			<li class="mb5"><a  href="/aaohome/contact/view?ctt_id=<?= $data['cr_contact_id'] ?>&type=view" target="_blank"><i class="fas fa-plus mr5 font-11"></i>View Contact</a></li>
																			<?php
																			if (Yii::app()->user->checkAccess("vendorChangestatus"))
																			{
																				?>
																				<li class="mb5"><a  href="/aaohome/contact/form?ctt_id=<?= $data['cr_contact_id'] ?>&type=3" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Modify Contact Details</a></li>
																			<?php } ?>
																			<li class="mb5"><a  href="/aaohome/vendor/vendoraccount?vnd_id=<?= $model->vnd_id ?>&type=view" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Show Account Details</a></li>
																			<li class="mb5"><a href="<?php echo Yii::app()->createUrl("admin/vendor/agreementShowdoc", array('ctt_id' => $data['cr_contact_id'], 'vnd_id' => $model->vnd_id)) ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Approve Agreement</a></li>
																			<li class="mb5"><a onclick="reduceLvl();"><i class="fas fa-plus mr5 font-11"></i>Reduce Level</a></li>
																			<li class="mb5"><a onclick="updateVendorDetails();"><i class="fas fa-plus mr5 font-11"></i>Manually Update Statistical Data</a></li>
																			<li class="mb5"><a onclick="unlinkSocialLink();"><i class="fas fa-plus mr5 font-11"></i>Unlink Social Account</a></li>
																			<?php
																			if (Yii::app()->user->checkAccess("vendorChangestatus"))
																			{
																				if ($data['vnd_is_freeze'] == 0 && Yii::app()->user->checkAccess("vendorChangestatus"))
																				{
																					$objtitle = 'Freeze';
																				}
																				else if (($data['vnd_is_freeze'] == 1 || $data['vnd_is_freeze'] == 2) && Yii::app()->user->checkAccess("vendorChangestatus"))
																				{
																					$objtitle = "UnFreeze";
																				}
																				?>
																				<li class="mb5"><a onclick="administrativefreeze(this);return false;" data-title="<?= $objtitle ?>" href ="<?php echo Yii::app()->createUrl("aaohome/vendor/administrativefreeze", array("vnd_id" => $model->vnd_id, "vnp_is_freeze" => $data['vnd_is_freeze'], 'profileview' => true)); ?>"><i class="fas fa-plus mr5 font-11"></i>Administrative <?= $objtitle ?></a></li>
																				<?php
																				if ($data['vnd_cod_freeze'] == 0 && Yii::app()->user->checkAccess("vendorChangestatus"))
																				{
																					$objtitle = 'Freeze';
																				}
																				else if ($data['vnd_cod_freeze'] == 1 && Yii::app()->user->checkAccess("vendorChangestatus"))
																				{
																					$objtitle = "UnFreeze";
																				}
																				?>
																				<li class="mb5"><a onclick="codfreeze(this);return false;" data-title="<?= $objtitle ?>" href ="<?php echo Yii::app()->createUrl("aaohome/vendor/changecod", array("vnd_id" => $data['vnd_id'], "vnd_cod" => $data['vnd_cod_freeze'])); ?>"><i class="fas fa-plus mr5 font-11"></i>COD <?= $objtitle ?></a></li>
																				<?php
																				if ($data['vnd_active'] == 1 && Yii::app()->user->checkAccess("vendorChangestatus"))
																				{
																					$objtitle	 = 'Block';
																					$callurl	 = Yii::app()->createUrl("aaohome/vendor/block", array("vnd_id" => $data['vnd_id']));
																				}
																				else
																				{
																					$objtitle	 = "Unblock";
																					$callurl	 = Yii::app()->createUrl("aaohome/vendor/changestatus", array("vnd_id" => $data[vnd_id], "vnd_active" => 2));
																				}
																				?>
																				<li class="mb5"><a onclick="blockvendor(this);return false;" data-title="<?= $objtitle ?>" href ="<?php echo $callurl; ?>"><i class="fas fa-plus mr5 font-11"></i><?= $objtitle ?> Vendor</a></li>
																				<?php
																			}
																			?>
																			<li class="mb5"><a onclick="addremark(this);return false;" data-title="Add Remark" href ="<?php echo Yii::app()->createUrl("aaohome/vendor/addremark", array("vnd_id" => $data['vnd_id'])); ?>"><i class="fas fa-plus mr5 font-11"></i>Add Remark</a></li>
																			<li class="mb5"><a onclick="sendLink(this);return false;" data-title="Send Custom Message" href ="<?php echo Yii::app()->createUrl("aaohome/vendor/sendCustomMessage", array("vnd_id" => $data['vnd_id'])); ?>"><i class="fas fa-plus mr5 font-11"></i>Send Custom Message</a></li>
																			<?php if(Yii::app()->user->checkAccess("temporaryRatingBoost"))
																			{
																			?>
																			<li class="mb5"><a onclick="addTmpRating(this);return false;" data-title="Temporary Rating" href ="<?php echo Yii::app()->createUrl("aaohome/vendor/tmpRating", array("vnd_id" => $data['vnd_id'])); ?>"><i class="fas fa-plus mr5 font-11"></i>Temporary Ratings boost</a></li>
																			<?php
																			}
																			?>
																			<li class="mb5"><a  href="/aaohome/vendor/Getlockamount?vnd_id=<?= $model->vnd_id ?>&type=view" target="_blank"><i class="fas fa-plus mr5 font-11"></i>View Locked Amount</a></li>
                                                                           
																			<?php
																			
																			if($data['vrs_dependency']<0 && Yii::app()->user->checkAccess("vendorUnBlockStatus"))
																			{
																			?>
																			<li class="mb5"><a  onclick="addTmpDependency(this);return false;" href="/aaohome/vendor/boostDependency?vnd_id=<?= $model->vnd_id ?>&type=view" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Boost Dependency</a></li>
																			<?php
																			}
																			?>
																		</ul>    
																	</div>
																</div>
																<div class="col-xs-12 mb20">
																	<div class="widget-tab-box2">
																		<h1 class="font-16">On Duty Vehicle Details</h1>
																		<?php
																		$activeVhc = VendorVehicle::getCurrentlyActiveVehicleByVendor($vndId);
																		if (!$activeVhc)
																		{
																			?>
																			<div class="col-xs-9 pl5">Currently No Active Vehicle</div>
																			<br>
																			<?php
																		}
																		foreach ($activeVhc as $val)
																		{
																			?>
																			<div class="row mb20">
																				<div class="col-xs-3 pr0">
																					<div class="tags-btn3"><i class="fas fa-car-side"></i></div>
																				</div>
																				<div class="col-xs-9 pl5">
																					<h2 class="mt5 mb0"><?= $val['vhc_make'] ?> <?= $val['vhc_model'] ?></h2>
																					<p class="mb5"><a target="_blank"  href="/aaohome/vehicle/view?code=<?= $val['vhc_code'] ?>" target="_blank"><?= $val['vhc_number'] ?></a></p>
																					<p class="mb5"><?= date('d M Y', strtotime($val['vhc_created_date'])); ?></p>
																				</div>
																			</div>
																		<?php }
																		?>
																		<div class="text-right vehicledetailsli"><a href="#vehicleDetails" onclick="getVehicleDetails()" aria-controls="vehicleDetails" role="tab" data-toggle="tab">View All</a></div>
																	</div>
																</div>
																<div class="col-xs-12 mb20">
																	<div class="widget-tab-box2">
																		<h1 class="font-16">On Duty Driver Details</h1>
																		<?php
																		$driverDetails = VendorVehicle::getCurrentlyActiveDriverByVendor($vndId);
																		if (!$driverDetails)
																		{
																			?>
																			<div class="col-xs-9 pl5">Currently No Active Driver</div>
																			<br>
																			<?php
																		}
																		foreach ($driverDetails as $driverDataView)
																		{
																			?>
																			<div class="row mb20">
																				<div class="col-xs-3 pr0">
																					<div class="tags-btn3">
																						<?php
																						$nameData = explode("  ", $driverDataView['drv_name']);
																						echo VendorProfile::model()->getVendorNameInitials($nameData[0]);
																						?>
																					<!--<i class="fas fa-car-side"></i>-->
																					</div>
																				</div>
																				<div class="col-xs-9 pl5">
																					<h2 class="mt5 mb0"><?= $driverDataView['drv_name'] ?> </h2>
																					<p class="mb5"><a target="_blank"  href="/aaohome/driver/view?code=<?= $driverDataView['drv_code'] ?>" target="_blank"><?= $driverDataView['drv_code'] ?></a></p>
																					<p class="mb5"><?= $driverDataView['drv_phone']; ?></p>
																				</div>
																			</div>
																		<?php } ?><div class="text-right driverdetailsli"><a href="#driverDetails" onclick="getDriverDetails()" aria-controls="driverDetails" role="tab" data-toggle="tab">View All</a></div>
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


								<div role="tabpanel" class="tab-pane tabHide" id="vendorTripDetails">
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="p0 pt20"  >
											<div class="row">
												<div class="col-xs-12">
													<div class="row mb20">
														<div class="col-xs-12 widget-tab-box3 widget-tab-box5" id="showVendorTrip">
															<?php
															/* if ($mycall == 1)
															  {
															  $this->renderPartial('../vendor/tripDetails', ["dataProvider" => $vndTripDetails], false, false);
															  }
															  else
															  {
															  $this->renderPartial("tripDetails", ["dataProvider" => $vndTripDetails], false, false);
															  } */
															?>
														</div>
														<div class="row" style="display: flex; flex-wrap: wrap; ">

														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide" id="vehicleDetails">	
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="panel-body p0 pt20 vndVehicleDetails">
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide" id="driverDetails">
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="panel-body p0 pt20 vndDriverDetails">											
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide" id="vendorRatingList">
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="p0 pt20"  >
											<div class="row">
												<div class="col-xs-12">
													<div class="row mb20">
														<div class="col-xs-12 widget-tab-box3 widget-tab-box5" id="vndRatingList">
															
														</div>
														<div class="row" style="display: flex; flex-wrap: wrap; ">

														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide" id="vendorCollection">
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="panel-body p0 pt20 vndAccountDetails">											
										</div>
									</div>
								</div>



								<div role="tabpanel" class="tab-pane tabHide" id="zones">
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="panel-body p0 pt20 vndZoneDetails">											
										</div>
									</div>
								</div>


								<div role="tabpanel" class="tab-pane tabHide" id="vendorOnboarding"> 
									<div class="panel">
										<div class="panel-heading p0 pt5">
											<?= $model->vnd_name . ' ' . $batch . $merged ?> 
											<?php echo CHtml::link('Document Upload', Yii::app()->createUrl('admin/document/view', ['ctt_id' => $data['cr_contact_id'], 'viewType' => "vendor"]), ['class' => 'btn btn-primary mb10 pull-right', 'target' => '_blank']) ?>                                          
										</div>
										<div class="panel-body p0 pt20 vndvendorOnboarding">											
										</div>
									</div>
								</div>


								<div role="tabpanel" class="tab-pane tabHide" id="profileStrength"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="p0 pt20 vndprofileStrength">											
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide" id="biddingLog"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="p0 pt20 vndbiddingLog">											
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide" id="penalty"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="p0 pt20 vndpenalty">											
										</div>
									</div>
								</div>


								<div role="tabpanel" class="tab-pane tabHide" id="appusage"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="panel-body p0 pt20">
											<div class="row">
												<div class="col-xs-12">
													<div class="row mb20">
														<div class="col-xs-12 widget-tab-box3 widget-tab-box5 useapp">
														</div> 
													</div>
													<div class="row" style="display: flex; flex-wrap: wrap; ">

													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide" id="viewLog"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="panel-body p0 pt20 vndviewLog">											
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane tabHide" id="contactLog"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="panel-body p0 pt20 vndviewContactLog">											
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane tabHide" id="cbrdetails">
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="panel-body p0 pt20 vndcbrdetails">											
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane tabHide" id="notificationLog"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="p0 pt20 vndnotificationLog">											
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane tabHide" id="coinDetails"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->vnd_name . ' ' . $batch . $merged ?> </div>
										<div class="p0 pt20 vndcoinDetails">											
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


	<!--<div class="row booking-log">
	<div class="col-xs-12 ">
	<div class="col-xs-12 text-center">
	<label class = "control-label h3 ">Vendor Log</label>
	</div>-->
	<?php
//Yii::app()->runController('admin/vendor/showlog/agtid/' . $model->vnd_id . '/view/1');
}
else
{
	?>
	<div class="row">
		<div class="col-xs-12 text-center h3 mt0">        
			<h2 style='color:#ff0000;'>Vendor not found.</h2>
		</div>
	</div>
	<?php
}
?>
<!--</div>
</div>-->


<script  type="text/javascript">


    function updateVendorDocs(id, status)
    {
        var href = '<?= Yii::app()->createUrl("admin/vendor/updateDoc"); ?>';
        $.ajax({
            "url": href,
            "type": "GET",
            "dataType": "html",
            "data": {"vd_id": id, "vd_status": status},
            "success": function (data1)
            {
                var dataSet = data1.split("~");
                if (dataSet[1] == 1)
                {
                    var img = dataSet[0] + dataSet[1];
                    $(dataSet[0]).show();
                    $(dataSet[0]).css("display", "block");
                    $(dataSet[0]).removeClass('label-info');
                    $(dataSet[0]).addClass('label label-success');
                    $(dataSet[0]).html("Approved");
                    $(img).hide();
                }
                if (dataSet[1] == 2)
                {
                    $(dataSet[0]).show();
                    $(dataSet[0]).css("display", "block");
                    $(dataSet[0]).removeClass('label-info');
                    $(dataSet[0]).removeClass('label-success');
                    $(dataSet[0]).addClass('label label-danger');
                    $(dataSet[0]).html("Rejected");

                    var rejectImg = dataSet[0] + dataSet[1];
                    var approveImg = dataSet[0] + '1';
                    var reloadImg = dataSet[0] + '3';
                    var reloadRemarks = dataSet[0] + '33';
                    $(dataSet[0]).show();
                    $(rejectImg).hide();
                    $(approveImg).hide();
                    $(reloadImg).show();
                    $(reloadRemarks).hide();
                } else if (dataSet[1] == 3)
                {
                    var div = dataSet[0] + 'Div';
                    var img = dataSet[0] + dataSet[1];
                    $(dataSet[0]).hide();
                    $(div).show();
                    $(img).hide();
                }


            }
        });
        return false;
    }
	$("#vehicledetailsli").on("click", function () {
        getVehicleDetails();
    });

    function getVehicleDetails()
    {
		$('.nav li').removeClass('active');
        $('#vehicledetails').addClass('active');
		var vendorId = '<?= $model->vnd_id ?>';        
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorVehicleDetails"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndVehicleDetails').html(data);
			}
		});	
        
    }

	$("#driverdetailsli").on("click", function () {
        getDriverDetails();
    });
    function getDriverDetails()
    {
        $('.nav li').removeClass('active');
        $('#driverdetails').addClass('active');
		var vendorId = '<?= $model->vnd_id ?>';        
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorDriverDetails"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndDriverDetails').html(data);
			}
		});	
    }

    function reduceLvl()
    {

        var level = <?= $model->vnd_rel_tier; ?>;
        if (level == 0)
        {
            bootbox.alert("Vendor is aleady in low level");
            return false;
        }
        bootbox.confirm({
            message: "Are you sure want to reduce level?",
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
                    var vnd_id = <?= $model->vnd_id; ?>;
                    var href1 = '<?= Yii::app()->createUrl('admin/vendor/reduce') ?>';
                    jQuery.ajax({'type': 'GET', 'url': href1,
                        'data': {'vnd_id': vnd_id},
                        success: function (data)
                        {
                            alert(data);
                            bootbox.hideAll()
                            window.location.reload(true);

                        }
                    });
                }
            }
        });
    }

    function updateVendorDetails()
    {

        $href = "<?php echo Yii::app()->createUrl("admin/vendor/UpdateDetails") ?>";
        $id = <?php echo $model->vnd_id; ?>;

        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"vnd_id": $id},
            success: function (data)
            {
                data = JSON.parse(data);
                bootbox.alert(data.message);

            }
        });
    }
    $('#appuse').click(function () {
        userAppusage();
    });

    function userAppusage() {
        var userId = '<?= $model->vnd_id ?>';
        var type = '2';

        var href = '<?= Yii::app()->createUrl("aaohome/user/appUsage"); ?>';
        $.ajax
                ({
                    url: href,
                    data: {"userId": userId, 'userType': type},
                    type: 'get',
                    "dataType": "html",
                    success: function (data)
                    {
                        $('.useapp').html(data);
                    }
                });

    }
    $("#vendorTripDetailsli").on("click", function () {
        vendorTripDetails();
    });
    function vendorTripDetails() {
        var vendorId = '<?= $model->vnd_id ?>';
        var mycall = '<?= $mycall ?>';
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorTripDetails"); ?>';
        $.ajax
                ({
                    url: href,
                    data: {"vndId": vendorId, 'mycall': mycall},
                    type: 'get',
                    "dataType": "html",
                    success: function (data)
                    {
//console.log(data);
                        $('#showVendorTrip').html(data);
                    }
                });

    }
	
	 $("#vendorRatingListli").on("click", function () {
        vendorRatingList();
    });
    function vendorRatingList() {
        var vendorId = '<?= $model->vnd_id ?>';
        var mycall = '<?= $mycall ?>';
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorRatingList"); ?>';
        $.ajax
                ({
                    url: href,
                    data: {"vndId": vendorId, 'mycall': mycall, 'israting': 1},
                    type: 'get',
                    "dataType": "html",
                    success: function (data)
                    {
						//console.log(data);
                        $('#vndRatingList').html(data);
                    }
                });

    }

    function unlinkSocialLink()
    {
        bootbox.confirm({
            message: "Are you sure want to unlink social account?",
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
                    var href = '<?= Yii::app()->createUrl("admin/vendor/unlinksocialaccount"); ?>';
                    var vendorId = '<?= $model->vnd_id ?>';
                    var type = '1';
                    jQuery.ajax({type: 'GET',
                        url: href,
                        data: {"vnd_id": vendorId, "type": type},
                        success: function (data)
                        {
                            console.log(data);
                            data = JSON.parse(data);
                            bootbox.alert(data.message);

                        }
                    });
                }
            }
        });
    }
    function administrativefreeze(obj)
    {
        var objtitle = $(obj).data('title');
        var con = confirm("Are you sure you want to " + objtitle + " this vendor?");
//alert($(obj).data('title'));return false;
        if (con) {
            try
            {
                $href = $(obj).attr("href");
                jQuery.ajax({type: "GET", url: $href, success: function (data)
                    {
                        bootbox.dialog({
                            message: data,
                            className: "bootbox-sm",
                            title: objtitle + "  Vendor",
                            success: function (result) {
                                if (result.success) {

                                } else {
                                    alert('Sorry error occured');
                                }
                            },
                            error: function (xhr, status, error) {
                                alert('Sorry error occured');
                            }
                        });
                    }});
            } catch (e)
            {
                alert(e);
            }
        }

        return false;

    }
    function codfreeze(obj)
    {
        var objtitle = $(obj).data('title');
        var con = confirm("Are you sure you want to " + objtitle + " COD for this vendor?");
        if (con) {
            var $href = $(obj).attr('href');
            $.ajax({
                url: $href,
                dataType: "json",
                className: "bootbox-sm",
                title: objtitle + "  COD",
                success: function (result) {
                    if (result.success) {
                        refreshVendorGrid();
                    } else {
                        alert('Sorry error occured');
                    }
                },
                error: function (xhr, status, error) {
                    alert('Sorry error occured');
                }
            });
        }
        return false;
    }
    function blockvendor(obj)
    {
        var objtitle = $(obj).data('title');
        var con = confirm("Are you sure you want to " + objtitle + " this vendor?");
        if (con) {
            try
            {
                $href = $(obj).attr("href");
                if (objtitle == "Block") {
                    jQuery.ajax({type: "GET", url: $href, success: function (data)
                        {
                            bootbox.dialog({
                                message: data,

                                className: "bootbox-sm",
                                title: objtitle + " Vendor",
                                success: function (result) {
                                    if (result.success) {
                                        if (objtitle == "Unblock") {
                                            alert("here");
                                            refreshVendorGrid();
                                        }

                                    } else {
                                        alert('Sorry error occured');
                                    }
                                },
                                error: function (xhr, status, error) {
                                    alert('Sorry error occured');
                                }
                            });
                        }});
                } else {
                    $.ajax({
                        url: $href,
                        dataType: "json",
                        success: function (result) {
                            if (result.success) {
                                refreshVendorGrid();
                            } else {
                                alert('Sorry error occured');
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('Sorry error occured');
                        }
                    });
                }
            } catch (e)
            {
                alert(e);
            }
        }
        return false;
    }
    function refreshVendorGrid() {
        location.reload();
    }
    function addremark(obj) {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", url: $href, success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        className: "bootbox-sm",
                        title: "Add Remark",
                        success: function (result) {
                            if (result.success) {

                            } else {
                                alert('Sorry error occured');
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('Sorry error occured');
                        }
                    });
                }});
        } catch (e)
        {
            alert(e);
        }
        return false;
    }
	
	function sendLink(obj) {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", url: $href, success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        className: "bootbox-sm",
                        title: "Send Custom Message",
                        success: function (result) {
                            if (result.success) {

                            } else {
                                alert('Sorry error occured');
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('Sorry error occured');
                        }
                    });
                }});
        } catch (e)
        {
            alert(e);
        }
        return false;
    }
	
	
	function addTmpRating()
    {
        bootbox.confirm({
            message: "Are you sure want to give temporary rating?",
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
                    var href = '<?= Yii::app()->createUrl("admin/vendor/tmpRating"); ?>';
                    var vendorId = '<?= $model->vnd_id ?>';
                    jQuery.ajax({type: 'GET',
                        url: href,
                        data: {"vnd_id": vendorId},
                        success: function (data)
                        {
                            console.log(data);
                            data = JSON.parse(data);
                            bootbox.alert(data.message);

                        }
                    });
                }
            }
        });
    }
	
	function addTmpDependency()
	{
		bootbox.confirm({
            message: "Are you sure want to give temporary dependency?",
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
                    var href = '<?= Yii::app()->createUrl("admin/vendor/boostDependency"); ?>';
                    var vendorId = '<?= $model->vnd_id ?>';
                    jQuery.ajax({type: 'GET',
                        url: href,
                        data: {"vnd_id": vendorId},
                        success: function (data)
                        {
                            console.log(data);
                            data = JSON.parse(data);
                            bootbox.alert(data.message);

                        }
                    });
                }
            }
        });
	}
	
	$("#vendorCollectionli").on("click", function () {
        getAccountDetails();
    });
    function getAccountDetails()
    {       
		var vendorId = '<?= $model->vnd_id ?>';        
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorAccountDetails"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndAccountDetails').html(data);
			}
		});	
    }
	$("#vendorZonesli").on("click", function () {
        getZoneDetails();
    });
    function getZoneDetails()
    {       
		var vendorId = '<?= $model->vnd_id ?>';        
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorZoneDetails"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndZoneDetails').html(data);
			}
		});	
    }
	$("#profileStrengthli").on("click", function () {
        getProfileStrength();
    });
    function getProfileStrength()
    {        
		var vendorId = '<?= $model->vnd_id ?>';   
		var mycall = '<?= $mycall ?>';		     
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorProfileStrength"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId,'mycall': mycall},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndprofileStrength').html(data);
			}
		});	
    }
	$("#biddingLogli").on("click", function () {
        getBiddingLog();
    });
    function getBiddingLog()
    {        
		var vendorId = '<?= $model->vnd_id ?>';   
		var mycall = '<?= $mycall ?>';		     
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorBiddingLog"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId,'mycall': mycall},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndbiddingLog').html(data);
			}
		});	
    }
	$("#penaltyli").on("click", function () {
        getPenalty();
    });
    function getPenalty()
    {        
		var vendorId = '<?= $model->vnd_id ?>';   
		var mycall = '<?= $mycall ?>';		     
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorPenalty"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId,'mycall': mycall},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndpenalty').html(data);
			}
		});	
    }
	$("#viewLogli").on("click", function () {
        getViewLog();
    });
    function getViewLog()
    {        
		var vendorId = '<?= $model->vnd_id ?>';   
		var mycall = '<?= $mycall ?>';		     
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorViewLog"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId,'mycall': mycall},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndviewLog').html(data);
			}
		});	
    }
	$("#contactLogs").on("click", function () {
        getContactViewLog();
    });
	function getContactViewLog()
    {        
		var vendorId = '<?= $model->vnd_id ?>';   
		var mycall = '<?= $mycall ?>';		     
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorContactViewLog"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId,'mycall': mycall},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndviewContactLog').html(data);
			}
		});	
    }
	
	$("#cbrdetailsli").on("click", function () {
        getCbrdetails();
    });
    function getCbrdetails()
    {        
		var vendorId = '<?= $model->vnd_id ?>';   
		var mycall = '<?= $mycall ?>';		     
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorScqDetails"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId,'mycall': mycall},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndcbrdetails').html(data);
			}
		});	
    }
	$("#notificationLogli").on("click", function () {
        getNotificationLog();
    });
    function getNotificationLog()
    {        
		var vendorId = '<?= $model->vnd_id ?>';   
		var mycall = '<?= $mycall ?>';		     
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorNotificationLog"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId,'mycall': mycall},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndnotificationLog').html(data);
			}
		});	
    }
	$("#vendorOnboardingli").on("click", function () {
        getVendorOnboarding();
    });
    function getVendorOnboarding()
    {        
		var vendorId = '<?= $model->vnd_id ?>'; 	     
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/vendorDocuments"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndvendorOnboarding').html(data);
			}
		});	
    }
	$("#coinDetailsli").on("click", function () {
        getCoinDetails();
    });
    function getCoinDetails()
    {        
		var vendorId = '<?= $model->vnd_id ?>'; 	     
        var href = '<?= Yii::app()->createUrl("aaohome/vendor/getCoinDetails"); ?>';
        $.ajax
		({
			url: href,
			data: {"vndId": vendorId},
			type: 'get',
			"dataType": "html",
			success: function (data)
			{
				$('.vndcoinDetails').html(data);
			}
		});	
    }
</script>