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
$contactId	 = ContactProfile::getByEntityId($model->drv_id, UserInfo::TYPE_DRIVER);
$contactData = Contact::getContactDetails($contactId);
if ($model != null)
{
	$ynList		 = [1 => 'Yes', 0 => 'No'];
	$ynList1	 = [1 => 'Yes', 0 => 'No'];
	$accType	 = $model->accType;
	$allTrip	 = [1, 2, 3];
	$ownerName	 = Contact::model()->getNameById($model->drv_id);
	if ($model->drv_trip_type <> NULL)
	{
		$tripType = explode(',', $model->drv_trip_type);
	}
	/*  @var $model Drivers */

	$batch			 = "";
	$state			 = "";
	
	if ($data['drv_overall_rating'] > 4) //According to dipesh sir mantis Id 2522
	{
		$batch = '<img src="/images/icon/plan-gold.png"  style="cursor:pointer ;" title="Value" width="40">';
	}
	else
	{
		$batch = '<img src="/images/icon/plan-silver.png"  style="cursor:pointer ;" title="Value" width="40">';
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
								<li role="presentation" class="p15 pl20 ml5"><b>Driver's Information</b></li>
								<li role="presentation" class="active"><a href="#driverDetails" aria-controls="driverDetails" role="tab" data-toggle="tab">Dashboard</a></li>
								<li role="presentation" id="tripdetailsli"><a href="#tripdetails" aria-controls="tripdetails" role="tab" data-toggle="tab">Past Trip Details</a></li>
								<li role="presentation" id="vendordetailsli"><a href="#vendorDetails" aria-controls="vendorDetails" role="tab" data-toggle="tab">Vendor Details</a></li>
								<li role="presentation" id="logs"><a href="#viewLogs" aria-controls="viewLogs" role="tab" data-toggle="tab">View Log</a></li>
								<li role="presentation" id="appuse"><a href="#appUsage" aria-controls="appUsage" role="tab" data-toggle="tab">App Usage</a></li>
								<li role="presentation" id="documentsli"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Documents</a></li>
								<li role="presentation" id="cbrdetailsli"><a href="#cbrdetails" aria-controls="cbrdetails" role="tab" data-toggle="tab">SCQ Details</a></li>
								<li role="presentation"><a href="<?php echo Yii::app()->createUrl("admin/driver/add", array('drvid' => $model->drv_id)); ?>" aria-controls="editDriver" target="_blank">Edit Driver</a></li>
								<li role="presentation"id="coinDetailsli"><a href="#coinDetails" aria-controls="coinDetails" role="tab" data-toggle="tab">Coin Details</a></li>
								<li role="presentation" id="documentLogs"><a href="#docLogs" aria-controls="docLogs" role="tab" data-toggle="tab">Document Log</a></li>
								
							</ul>
						</div>
					</div>
					<?php
					if ($contactData["ctt_first_name"] != "")
					{
						$drvName = ucfirst(strtolower($contactData["ctt_first_name"])) . ' ' . ucfirst(strtolower($contactData["ctt_last_name"]));
					}
					else if ($contactData["ctt_business_name"] != "")
					{
						$drvName = ucfirst(strtolower($contactData["ctt_business_name"]));
					}
					if (str_contains(strtolower($model->drv_name), strtolower($contactData["ctt_first_name"])))
					{
						$drvName = "";
					}
					?>
					<div class="col-xs-12 col-sm-8 col-lg-9">
						<!-- Tab panes -->
						<div class="widget-tab-box">
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane tabHide active" id="driverDetails">
									<div class="panel">
										<div class="panel-heading p0 pt5">
											<?= $model->drv_name . ((!empty($drvName) && $model->drv_name != $drvName) ? ' [ ' . $drvName . ' ] ' : '') . ' ' . $batch ?> 
										</div>
										<div class="panel-body p0 pt20">
											<div class="row">
												<div class="col-xs-12 mb30">
													<?php
													if ($model->drv_is_freeze == 1)
													{
														echo ' <span class="btn-4 mr15">Frozen</span>';
													}
													if ($model->drv_active == 1)
													{
														echo ' <span class="btn-5 mr15">Active</span>';
													}
													else
													{
														echo ' <span class="btn-4 mr15">Inactive</span>';
													}
													$isApprove = 'btn-4 mr15';
													if ($data['drv_approved'] == 1)
													{
														$isApprove = 'btn-5 mr15';
														?>
														<span class="<?= $isApprove ?>"><?= $data['approve_status']; ?></span>	
														<?php
													}
													?>	

													<?php
													if ($data['ctt_vaccine_status'] == 2)
													{
														echo ' <span class="btn-5 mr15">Fully Vaccinated</span>';
													}
													else if ($data['ctt_vaccine_status'] == 1)
													{
														echo ' <span class="btn-5 mr15">Partially Vaccinated</span>';
													}
													else
													{
														echo ' <span class="btn-4 mr15">Not Vaccinated</span>';
													}
													if ($data['drv_id'] != $model->drv_ref_code)
													{
														echo' <span class="btn-4 mr15">Merged</span>';
													}
													?>
												</div>
												<div class="col-xs-12">
													<div class="row">
														<div class="col-xs-12 col-md-8 mb20">
															<div class="widget-tab-box2">
																<div class="row mb20">
																	<div class="col-xs-12 col-md-9 pr5">
																		<h1 class="mb5"><i class="fas fa-user"></i> Driver Details</h1>
																		<?php
																		$date2		 = date_create(date("Y-M-d"));
																		$date1		 = date_create(date("Y-M-d", strtotime($model->drv_created)));
																		$diff2		 = date_diff($date1, $date2);
																		$datediff	 = $diff2->format("%R%a days");
																		$years		 = round($datediff / 365);
																		/* if ($model->drv_total_trips != "")
																		  {
																		  $drvTotalTrips = $model->drv_total_trips;
																		  }
																		  else
																		  {
																		  $drvTotalTrips = 0;
																		  } */
																		//	echo $drvstatModel->drs_total_trip."=======".$drvstatModel->drs_total_trips."=========".$model->drv_total_trips."<BR>";
																		if ($drvstatModel->drs_total_trips != "")
																		{
																			$drvTotalTrips = $drvstatModel->drs_total_trips;
																		}
																		else
																		{
																			$drvTotalTrips = 0;
																		}
																		$drvOverallRating	 = !empty($drvstatModel->drs_drv_overall_rating) ? round($drvstatModel->drs_drv_overall_rating, 2) : 0;
																		//echo $drvstatModel->drs_drv_overall_rating . "===".$drvOverallRating;
																		?>
																		<p class="color-gray">DOJ: <b><?= date('d M Y', strtotime($model->drv_created)); ?></b> | 
																			<b><?= $years ?>+</b> year old | <b><?= $drvTotalTrips ?>+</b> trip completed</p>
																	</div>  
																	<div class="col-xs-12 col-md-3 pl5 text-right">
																		<p class="mb0">
																			<i class="fas fa-star color-<?= ($drvOverallRating >= 1) ? 'yellow' : 'gray'; ?>"></i>
																			<i class="fas fa-star color-<?= ($drvOverallRating >= 2) ? 'yellow' : 'gray'; ?>"></i>
																			<i class="fas fa-star color-<?= ($drvOverallRating >= 3) ? 'yellow' : 'gray'; ?>"></i>
																			<i class="fas fa-star color-<?= ($drvOverallRating >= 4) ? 'yellow' : 'gray'; ?>"></i>
																			<i class="fas fa-star color-<?= ($drvOverallRating >= 5) ? 'yellow' : 'gray'; ?>"></i>
																		</p>
																		<?php
																		/* if ($model->drv_overall_rating)
																		  {
																		  $drvOverallRating = round($model->drv_overall_rating, 2);
																		  }
																		  else
																		  {
																		  $drvOverallRating = 0;
																		  } */
																		?>                                                                  
																		<p class="color-gray"><?= $drvOverallRating; ?>/5 Rating <?php // if ($data['countRating'] != ''){ echo '(' . $data['countRating'] . ' people)'; } else { echo ''; }                          ?></p>
																	</div>  
																</div> 
																<div class="row mb10">
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Driver Code / ID</p>
																		<p class="font-14"><b><?= $model->drv_code ?> / <?= $model->drv_id ?></b></p>
																	</div>
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Contact No</p>
																		<p class="font-14"><b><?= $data['drv_phone']; ?><br><?= $data['drv_alt_phone']; ?></b>(<?= ($data['drv_alt_phone'] != '') ? 'alternate' : ''; ?>)</p>
																	</div>
																	<div class="col-xs-6" style="word-wrap: break-word;">
																		<p class="mb0 color-gray">Address</p>
																		<p class="font-14"><b><?= $data['city_name']; ?>, <?= $data['drv_address']; ?></b></p>
																	</div>
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Email Id</p>
																		<p class="font-14"><b><?= $data['drv_email']; ?></b></p>
																	</div>
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">License number</p>
																		<p class="font-14"><b>
																				<?= ($model->drvContact->ctt_license_no != '' && $model->drvContact->ctt_license_no != NULL) ? $model->drvContact->ctt_license_no : ''; ?>
																			</b></p>
																	</div>
																</div>

																<div class="row mb10">
																	<div class="col-xs-6">
																		<p class="mb0 color-gray">License Expiry Date</p>
																		<p class="font-14">
																			<B><?= ($model->drvContact->ctt_license_exp_date != '' && $model->drvContact->ctt_license_exp_date != NULL) ? date('d/m/Y h:i A', strtotime($model->drvContact->ctt_license_exp_date)) : ''; ?> </b>
																		</p>
																	</div>

																	<div class="col-xs-6">
																		<p class="mb0 color-gray">Approved By</p>
																		<p class="font-14">
																			<B><?= $data['approve_by_name']; ?></B>
																		</p>
																	</div>

																	<!--<div class="col-xs-6">
																					<p class="mb0 color-gray">On Time</p>
																					<p class="font-14">
																	<? //= ($data['rtg_driver_ontime'] > 0) ? $data['rtg_driver_ontime'] : '0';    ?>
																					</p>
																	</div>

																	<div class="col-xs-6">
																					<p class="mb0 color-gray">Soft Spoken</p>
																					<p class="font-14">
																	<? //= ($data['rtg_driver_softspokon'] > 0) ? $data['rtg_driver_softspokon'] : '0';   ?>
																					</p>
																	</div>

																	<div class="col-xs-6">
																					<p class="mb0 color-gray">Respectfully Dressed</p>
																					<p class="font-14">
																	<? //= ($data['rtg_driver_respectfully'] > 0) ? $data['rtg_driver_respectfully'] : '0';   ?>
																					</p>
																	</div>

																	<div class="col-xs-6">
																					<p class="mb0 color-gray">Safe Driver</p>
																					<p class="font-14">
																	<? //= ($data['rtg_driver_safely'] > 0) ? $data['rtg_driver_safely'] : '0';   ?>
																					</p>
																	</div> -->
																	<?php
																	$ratingtags			 = RatingAttributes::getDriverRatingTags($model->drv_id);
																	if (count($ratingtags) > 0)
																	{
																		?>
																		<div class="col-xs-12">
																			<h2>Tags</h2>
																			<p class="font-14">
																				<?php
																				foreach ($ratingtags as $rtag)
																				{
																					if ($rtag['rating_type'] == "GOOD")
																					{
																						$color		 = 'background:#48b9a7';
																						$checkBtn	 = 'fas fa-check mr5';
																					}
																					if ($rtag['rating_type'] == "BAD")
																					{
																						$color		 = 'background:#ff4646';
																						$checkBtn	 = 'fas fa-times mr5';
																					}
																					?>
																					<span class="tags-btn2" style="<?= $color ?>"> <i class="<?= $checkBtn; ?>"></i> <?php echo $rtag['rating_tag']; ?></span>
																					<?php
																				}
																				?>
																			</p>
																		</div>
																	<?php } ?>
																</div>

																<div class="row mb10">
																	<div class="col-xs-12 mb0"> 
																		<h2>Trip Type</h2> 
																		<?php
																		foreach ($allTrip as $trpType)
																		{
																			$type		 = Drivers::getSingleTripType($trpType);
																			$color		 = 'background:#ff4646';
																			$checkBtn	 = 'fas fa-times mr5';
																			if (in_array($trpType, $tripType))
																			{
																				$color		 = 'background:#48b9a7';
																				$checkBtn	 = 'fas fa-check mr5';
																			}
																			?>
																			<span class="tags-btn2" style="<?= $color ?>"> <i class="<?= $checkBtn; ?>"></i> <?php echo $type; ?></span>
																			<?php
																		}
																		?>
																		<br>
																		<br>
																	</div>

																</div>

																<div class="row mb10">
																	<div class="col-xs-12 mb30"> 
																		<h2>Bank Details</h2>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Bank name : <b><?= $data['ctt_bank_name']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">IFSC Code : <b><?= $data['ctt_bank_ifsc']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Bank Branch : <b><?= $data['ctt_bank_branch']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Beneficiary name : <b><?= $data['ctt_beneficiary_name']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Account no. : <b><?= $data['ctt_bank_account_no']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Beneficiary ID : <b><?= $data['ctt_beneficiary_id']; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Account type : <b><?= $accType[$data['ctt_account_type']]; ?></b></p>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="col-xs-12 mb30">
																		<h2>App / Login Information</h2>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Username : <b><?= $model->drvUser->usr_name; ?></b></p>
																				</div>
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="row">
																				<div class="col-xs-12 pl0">
																					<p class="mb0 color-gray lineheight20">Last Login Date  : <b><?= (!empty($drvstatModel->drs_last_logged_in) ? date('d M Y H:i:s', strtotime($drvstatModel->drs_last_logged_in)) : "-"); ?></b></p>
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
																			<li class="mb5"><a href="<?php echo Yii::app()->createUrl("admin/booking/list", ['drvid' => $model->drv_id]) ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Booking History</a></li>
																		</ul>
																		<ul class="pl0">
																			<li class="mb5"><a  href="/aaohome/contact/view?ctt_id=<?= $data['cr_contact_id'] ?>&viewType=driver" target="_blank"><i class="fas fa-plus mr5 font-11"></i>View Contact</a></li>
																		</ul>  
																		<ul class="pl0">
																			<li class="mb5"><a  href="/aaohome/driver/deviceHistory?drvId=<?= $model->drv_id ?>&viewType=driver" target="_blank"><i class="fas fa-plus mr5 font-11"></i>Device History</a></li>
																		</ul>
																		<ul class="pl0">
																			<li class="mb5"><a onclick="drvLocation(<?= $model->drv_id ?>)" title="Driver Location" target="_blank" ><i class="fas fa-plus mr5 font-11"></i>Driver Location</a></li>
																		</ul>
																		<ul class="pl0">
																			<li class="mb5"><a onclick="updateDriverDetails()" title="Update Statistical Data" ><i class="fas fa-plus mr5 font-11"></i>Manually Update Statistical Data</a></li>
																		</ul>
																		<ul class="pl0">
																			<li class="mb5"><a href="<?php echo Yii::app()->createUrl("admin/driver/viewtransaction", ['id' => $model->drv_id]) ?>" target="_blank"><i class="fas fa-plus mr5 font-11"></i>View Transaction</a></li>
																		</ul>
																		<ul class="pl0">
																			<li class="mb5"><a onclick="addremark(this);return false;" data-title="Add Remark" href ="<?php echo Yii::app()->createUrl("aaohome/driver/addremark", array("drv_id" => $model->drv_id)); ?>"><i class="fas fa-plus mr5 font-11"></i>Add Remark</a></li>
																		</ul>
																		<?php
																		if ($model->drv_user_id != NULL)
																		{
																			?>
																			<ul class="pl0">
																				<li class="mb5"><a onclick="unlinkSocialLink();"><i class="fas fa-plus mr5 font-11"></i>Unlink Social Account</a></li>
																			</ul>
	<?php } ?>
																	</div>
																</div>
																<div class="col-xs-12 mb20">
																	<div class="widget-tab-box2">
																		<h1 class="font-16">Current Vendor details</h1>
																		<?php
																		$vnd_name	 = explode(",", $data['vnd_name']);
																		$vnd_code	 = explode(",", $data['vnd_code']);
																		$vndName	 = array_slice($vnd_name, 0, 2);
																		for ($i = 0; $i < count($vndName); $i++)
																		{
																			?>
																			<div class="row mb20">
																				<div class="col-xs-3 pr0">
																					<div class="tags-btn3"><?php
																						$nameData = explode("_", $vnd_name[$i]);
																						echo VendorProfile::model()->getVendorNameInitials($nameData[0]);
																						?></div>
																				</div>
																				<div class="col-xs-9 pl5">
																					<h2 class="mt5 mb0"><?= $vnd_name[$i] ?></h2>
																					<p class="mb5"><a target="_blank"  href="<?= Yii::app()->createUrl('admin/vendor/view', ['code' => $vnd_code[$i]]) ?>" target="_blank"><?= $vnd_code[$i] ?></a></p>

																				</div>
																			</div>
	<?php } ?>
																		<div class="text-right"><a href="#vendorDetails" onclick="getVendorDetails()" aria-controls="vendorDetails" role="tab" data-toggle="tab" id="vendordetailsli">View All</a></div>
																	</div>
																</div>
																<div class="col-xs-12 mb20">
																	<div class="widget-tab-box2">
																		<h1 class="font-16">Current Driver details</h1>
																		<p class="mb0 color-gray"> Currently logged into app</p>
																		<p class="font-14">
																			<b><?php echo ($logedInCount > 0) ? 'Yes' : 'No'; ?></b>
																		</p>
																		<p class="mb0 color-gray">last location lat/long</p>
																		<p class="font-14">
																			<b><a href="https://maps.google.com/?q=<?php echo $drvStat['drv_last_loc_lat'] . "," . $drvStat['drv_last_loc_long'] ?>" target="_blank" class="color-black"><?php echo ($drvStat['drv_last_loc_lat'] != '' && $drvStat['drv_last_loc_long'] != '') ? round($drvStat['drv_last_loc_lat'], 4) . "," . round($drvStat['drv_last_loc_long'], 4) : '-'; ?></a></b>
																		</p>
																		<p class="mb0 color-gray">last location date/time</p>
																		<p class="font-14">
																			<b><?php echo ($drvStat['drv_last_loc_date'] != '') ? date('d/m/Y h:i A', strtotime($drvStat['drv_last_loc_date'])) : '-'; ?></b>
																		</p>
																		<p class="mb0 color-gray">last location device id</p>
																		<p class="font-14">
																			<b><?php echo ($drvStat['drv_last_loc_device_id'] != '') ? $drvStat['drv_last_loc_device_id'] : '-'; ?></b>
																		</p>
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

								<div role="tabpanel" class="tab-pane tabHide " id="tripdetails">
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->drv_name ?>  </div>
										<div class="panel-body p0 pt20 drvtripdetails">                                           
										</div>
									</div>
								</div>


								<div role="tabpanel" class="tab-pane tabHide " id="vendorDetails">
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->drv_name ?> </div>
										<div class="panel-body p0 pt20 drvvendorDetails">                                            
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide " id="viewLogs"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->drv_name ?>  </div>
										<div class="panel-body p0 pt20">
											<div class="row">
												<div class="col-xs-12">	
													<div class="col-xs-12 widget-tab-box3 widget-tab-box5 viewlog">
	<?php //$this->renderPartial("showlog", ["dataProvider" => $showLog], false, false);         ?>
													</div> 
													<div class="row" style="display: flex; flex-wrap: wrap; ">

													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide " id="appUsage"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->drv_name ?>  </div>
										<div class="panel-body p0 pt20">
											<div class="row">
												<div class="col-xs-12">	
													<div class="col-xs-12 widget-tab-box3 widget-tab-box5 useapp">
	<?php //$this->renderPartial("showlog", ["dataProvider" => $showLog], false, false);          ?>
													</div> 
													<div class="row" style="display: flex; flex-wrap: wrap; ">

													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div role="tabpanel" class="tab-pane tabHide " id="documents"> 
									<div class="panel">
										<div class="panel-heading p0 pt5">
											<?= $model->drv_name ?> 
	<?php echo CHtml::link('Document Upload', Yii::app()->createUrl('admin/document/view', ['ctt_id' => $data['cr_contact_id'], 'viewType' => "driver"]), ['class' => 'btn btn-primary mb10 pull-right', 'target' => '_blank']) ?> 
										</div>
										<div class="panel-body p0 pt20 drvdocuments">                                           
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane tabHide" id="cbrdetails">
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->drv_name . ' ' . $batch ?> </div>
										<div class="panel-body p0 pt20 drvcbrdetails">                                           
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane tabHide" id="coinDetails"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->drv_name . ' ' . $batch ?> </div>
										<div class="p0 pt20 drvCoinDetails">											
										</div>
									</div>
								</div>
                               <div role="tabpanel" class="tab-pane tabHide " id="docLogs"> 
									<div class="panel">
										<div class="panel-heading p0 pt5"><?= $model->drv_name ?>  </div>
										<div class="panel-body p0 pt20">
											<div class="row">
												<div class="col-xs-12">	
													<div class="col-xs-12 widget-tab-box3 widget-tab-box5 viewdoclog">
	<?php //$this->renderPartial("showlog", ["dataProvider" => $showLog], false, false);         ?>
													</div> 
													<div class="row" style="display: flex; flex-wrap: wrap; ">

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
			<h2 style='color:#ff0000;'>Driver not found.</h2>
		</div>
	</div>
	<?php
}
?>
<!--</div>
</div>-->


<script  type="text/javascript">

	$('#logs').click(function () {
		var driverId = '<?= $model->drv_id ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/driver/Showlog"); ?>';
		$.ajax
				({
					url: href,
					data: {"drvId": driverId},
					type: 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.viewlog').html(data);
					}
				});
	});

	$('#documentLogs').click(function () {
		var driverId = '<?= $model->drv_id ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/driver/documentlog"); ?>';
		$.ajax
				({
					url: href,
					data: {"drvId": driverId},
					type: 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.viewdoclog').html(data);
					}
				});
	});

	function drvLocation(drvId)
	{
		href = "<?= Yii::app()->createUrl('admin/booking/getDrvCurrentLocation') ?>";
		jQuery.ajax({type: 'GET',
			url: href,
			"dataType": "json",
			data: {"drvId": drvId},
			success: function (data)
			{
				if (data.success)
				{
					window.open(data.destUrl, '_blank');
				}
			}
		});
	}

	function updateDriverDetails()
	{
		$href = "<?php echo Yii::app()->createUrl("admin/driver/UpdateDetails") ?>";
		$id = "<?php echo $model->drv_id; ?>";

		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"drv_id": $id},
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
		var userId = '<?= $model->drv_id ?>';
		console.log(userId);
		var type = '3';
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
					var href = '<?= Yii::app()->createUrl("admin/driver/unlinksocialaccount"); ?>';
					var drvid = '<?= $model->drv_id ?>';
					var type = '1';
					jQuery.ajax({type: 'GET',
						url: href,
						data: {"drvid": drvid, "type": type},
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
	$("#tripdetailsli").on("click", function () {
		getTripDetails();
	});
	function getTripDetails()
	{
		var driverId = '<?= $model->drv_id ?>';
		var mycall = '<?= $mycall ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/driver/pastTripDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"drvId": driverId, 'mycall': mycall},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.drvtripdetails').html(data);
					}
				});
	}
	$("#vendordetailsli").on("click", function () {
		getVendorDetails();
	});
	function getVendorDetails()
	{
		$('.nav li').removeClass('active');
		$('#vendordetails').addClass('active');
		var driverId = '<?= $model->drv_id ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/driver/vndDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"drvId": driverId},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.drvvendorDetails').html(data);
					}
				});
	}
	$("#cbrdetailsli").on("click", function () {
		getCbrDetails();
	});
	function getCbrDetails()
	{
		var driverId = '<?= $model->drv_id ?>';
		var mycall = '<?= $mycall ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/driver/scqDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"drvId": driverId, 'mycall': mycall},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.drvcbrdetails').html(data);
					}
				});
	}
	$("#documentsli").on("click", function () {
		getDocumentsDetails();
	});
	function getDocumentsDetails()
	{
		var driverId = '<?= $model->drv_id ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/driver/documentDetails"); ?>';
		$.ajax
				({
					"url": href,
					"data": {"drvId": driverId},
					"type": 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.drvdocuments').html(data);
					}
				});
	}
	$("#coinDetailsli").on("click", function () {
		getCoinDetails();
	});
	function getCoinDetails()
	{
		var driverId = '<?= $model->drv_id ?>';
		var href = '<?= Yii::app()->createUrl("aaohome/driver/getCoinDetails"); ?>';
		$.ajax
				({
					url: href,
					data: {"drvId": driverId},
					type: 'get',
					"dataType": "html",
					success: function (data)
					{
						$('.drvCoinDetails').html(data);
					}
				});
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
</script>