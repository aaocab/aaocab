<script>
	$('#collapseExample').on('hidden.bs.collapse', function () {
		// do something…
	})
</script>
<style>
    .sidebar{ background: none;}
    .sidenav{ background: #0f264d; margin-bottom: 5px; border-radius: 5px;}
    .sidebar{ padding-top: 10px; padding-bottom: 10px;}
    .dropdown-container{ background: #f7f7f7; border: #e7e7e7 1px solid; padding: 15px;}
    .sidenav .active{ background: #0f264d;}
    .sidenav a, .dropdown-btn{ padding: 12px 8px 12px 16px; font-size: 18px; color: #fff; font-weight: 500; border-bottom: none;}
</style>

<?php
$checkDate		 = '2020-03-31 23:59:59';
$minPayPercent	 = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id,$model->bkgPref->bkg_is_gozonow);
if ($model->bkg_pickup_date > $checkDate)
{
	?>
	<?
	//$api				 = Yii::app()->params['googleBrowserApiKey'];
	$api				 = Config::getGoogleApiKey('browserapikey');
	?>
	<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
	<div class="row title-widget">
		<div class="col-12">
			<div class="container">
				<?php echo $this->pageTitle; ?>
			</div>
		</div>
	</div>
	<?php
	$isPromoApplicable = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
	if ($model->bkgInvoice->bkg_discount_amount == 0 && $model->bkg_status == 15 && $isPromoApplicable)
	{
		$isPromoApplicable = true;
	}
	else
	{
		$isPromoApplicable = false;
	}
	if (($model->bkg_booking_type == 1 && $model->bkg_flexxi_type == 2) || $model->bkg_cav_id > 0)
	{
		$isPromoApplicable = false;
	}
	if ($model->bkgInvoice->bkg_promo1_id > 0)
	{
		$pModel = Promos::model()->findByPk($model->bkgInvoice->bkg_promo1_id);
	}
	$vehicleModel = $model->bkgBcb->bcbCab->vhcType->vht_model;
	if($model->bkgBcb->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
	{
		$vehicleModel = OperatorVehicle::getCabModelName($model->bkgBcb->bcb_vendor_id, $model->bkgBcb->bcb_cab_id);
	}
	?>


	<script>
	var huiObj = null;
	var prmObj = null;
	var pageInitialized = false;
	$(document).ready(function ()
	{
		$('#payment').removeClass('hide');
		if (pageInitialized)
			return;
		pageInitialized = true;
		huiObj = new HandleUI();
		huiObj.bkgId = '<?= $model->bkg_id ?>';
	<?php
	if ($pModel->prm_code != '')
	{
		?>
			huiObj.additionalParams.code = '<?= $pModel->prm_code ?>';
		<?php
	}
	if ($model->bkgInvoice->bkg_temp_credits > 0)
	{
		?>
			huiObj.additionalParams.coins = '<?= $model->bkgInvoice->bkg_temp_credits ?>';
	<?php }
	?>
		prmObj = new Promotion(huiObj);
	<?php
//if ($isredirct != 1 && $model->bkg_flexxi_type != 2 && $promoId >0 && $model->bkgInvoice->bkg_advance_amount == 0)

	if ($isPromoApplicable)
	{
		?>
			ajaxindicatorstart("");
		<?php
		if ($model->bkgInvoice->bkg_promo1_id > 0)
		{
			?>
				prmObj.applyPromo(1, '<?= $pModel->prm_code; ?>');
			<?php
		}
		if ($model->bkgInvoice->bkg_temp_credits > 0)
		{
			?>
				prmObj.applyPromo(3, '<?= $model->bkgInvoice->bkg_temp_credits; ?>');
			<?php
		}
	}
	else
	{
		?>
			$(".disPromoType").addClass('hide');
		<?php
	}
	?>
		bid = '<?= $model->bkg_id ?>';
		hsh = '<?= $model->hash ?>';
		$isRunningAjax = false;
		var promo = new Promo();
		promo.payNowLater(bid, hsh);
	<?php
	if ($isPromoApplicable)
	{
		?>
			ajaxindicatorstop();
		<?php
	}
	if ($isredirct)
	{
		?>
			//			PromoCreditApplyRemove('promoAuto', 175, '1');
	<?php } ?>
	});
	</script>
	<?php
	/* @var $model Booking */
	$detect = Yii::app()->mobileDetect;

// call methods
	$isMobile	 = $detect->isMobile() && $detect->is("AndroidOS");
	$hide		 = (($model1->bkgInvoice->bkg_promo1_id != 0 && $model1->bkgInvoice->bkg_discount_amount > 0) || $model->bkgInvoice->bkg_credits_used > 0) ? '' : 'hide';

	$unverifiedImg	 = Yii::app()->baseUrl . "/images/unverified.png";
	$verifiedImg	 = Yii::app()->baseUrl . "/images/verified.png";
	$isPhoneVerified = ($model->bkgUserInfo->bkg_phone_verified == 1) ? $verifiedImg : $unverifiedImg;
	$isEmailVerified = ($model->bkgUserInfo->bkg_email_verified == 1) ? $verifiedImg : $unverifiedImg;

	$hidepromo	 = ($model->bkgInvoice->bkg_promo1_id != 0 || $model->bkgInvoice->bkg_discount_amount > 0) ? '' : 'hide';
	$hide1		 = ($model->bkgInvoice->bkg_promo1_id != 0 || $model->bkgInvoice->bkg_discount_amount > 0) ? 'hide' : '';
	$enableCOD	 = $model->enableCOD();
	$row		 = 'row';
	$version	 = Yii::app()->params['siteJSVersion'];
	$scvVctId	 = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promotion.js?v=' . $version);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/handleUI.js?v=' . $version);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/addon.js?v=' . $version);

	if ($isredirct)
	{
		if ($model->bkg_agent_id != '')
		{
			$row = '';
		}
		if ($model != null)
		{
			$routeCityList	 = $model->getTripCitiesListbyId();
			$ct				 = implode(' &#10147; ', $routeCityList);
		}
		?>
		<div class="<?= $row ?>">
			<div class="col-12">
				<?php
				if ($model->bkg_agent_id != '')
				{
					/* @var $agentModel Agents */
					$agentModel		 = Agents::model()->findByPk($model->bkg_agent_id);
					$acceptPayment	 = $agentModel->agt_use_gateway;
					?>
					<div class="row">
						<div class="col-6 text-left"><h1><?= $agentModel->agt_company ?></h1></div>
						<div class="col-6 text-right mt5"><img src="<?= Yii::app()->baseUrl ?>/images/logo4.png"/></div>
					</div>
					<h4 class=" text-center mb20"><?= $this->pageTitle ?></h4>
				<?php } ?>
				<?php
				if ($model->bkgInvoice->bkg_advance_amount < 1 && ($model->bkg_agent_id != NULL || $model->bkg_agent_id != ''))
				{
					?>
					<div class=" text-center mb20 alert alert-danger"><?php
						if (in_array($model->bkg_booking_type, [9, 10, 11]))
						{
							?>RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast  <?php echo $minPayPercent; ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed by <?php
						}
						else
						{
							?>RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast  <?php echo $minPayPercent; ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed by <?php } ?><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)); ?> , <?= date('h:i A', strtotime($model->bkg_pickup_date)); ?></div>
					<div class=" text-center mb20">
						<?= CHtml::radioButtonList('bkg_reconfirm_flag', $model->bkg_reconfirm_flag, array(1 => 'Yes, confirm this booking as soon as payment is received', 3 => 'No, I didnt request this booking. Please cancel it'), array('id' => "reconfirm", 'separator' => "    ", 'onclick' => "reconfirmUpdate()")); ?>
					</div>
					<?php
				}
				else if ($model->bkgInvoice->bkg_advance_amount < 1 && $model->bkgTrail->bkg_platform == 2)
				{
					?>
					<div class=" text-center mb20 alert alert-danger"><?php
						if (in_array($model->bkg_booking_type, [9, 10, 11]))
						{
							?>RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast  <?php echo $minPayPercent; ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed <?php
						}
						else
						{
							?> RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast <?php echo $minPayPercent; ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed<?php } ?></div>
					<div class=" text-center mb20">
						<?= CHtml::radioButtonList('bkg_reconfirm_flag', $model->bkg_reconfirm_flag, array(1 => 'Yes, confirm this booking as soon as payment is received', 3 => 'No, I didnt request this booking.Please cancel it'), array('id' => "reconfirm", 'separator' => "    ", 'onclick' => "reconfirmUpdate()")); ?>
					</div>
					<?php
				}

				$platform	 = Yii::app()->request->getParam('platform');
				$src		 = Yii::app()->request->getParam('src', 2);
				if ($src != 1 && $platform != 3)
				{
					$statusStr = '';
					if ($model->bkg_status > 5 && $model->bkg_status < 15)
					{
						$statusStr = '(' . Booking::model()->getActiveBookingStatus($model->bkg_status) . ')';
					}
					?>
					<div class="" id="bookingDetPayNow">
						<?php
						if ($paymentdone)
						{
							if ($succ == 'success')
							{
								?>
								<div role="alert" class="alert alert-success">
									<strong>Transaction was successful. Thank you for your order. Your Transaction Id : <?= $transid ?></strong>
								</div>
								<?php
							}
							else
							{
								?>
								<div role="alert" class="alert alert-danger">
									<strong>Oh snap!</strong> Something went wrong. Transaction was not successful.
								</div>
								<?php
							}
						}
						?>
					</div>
				<?php } ?>
			</div>
		</div>

		<?php
	}

// print_r($note);exit;
	/* if (!empty($note))
	  {
	  ?>
	  <div class="row">
	  <div class="container mt30">
	  <div class="row m0">
	  <div class="col-12 p0" id="linkedusers">
	  <div class="sidebar">
	  <div class="sidenav">
	  <button class="dropdown-btn">Special instructions & advisories that may affect your planned travel <i class="fa fa-caret-down"></i>
	  </button>
	  <div class="dropdown-container" style="display: none;">
	  <div class="compact">
	  <div class="text-uppercase font-weight-bold text-white text-center p-2 rounded-top" style="background:#0d47a1">Special instructions & advisories that may affect your planned travel</div>
	  <div class="row" style="padding: 0px 15px;">
	  <div class="col-sm-2" style="border-right:1px solid #ccc;border-left:1px solid #ccc;border-bottom:1px solid #ccc;">
	  <div class="p5" style="font-size: 1.2em"><strong>Place</strong></div></div>
	  <div class="col-sm-6" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
	  <div class="p5" style="font-size: 1.2em"><span class="m5"><strong>Note</strong></span></div></div>
	  <div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
	  <div class="p5" style="font-size: 1.2em"><span class="m5"><strong>Valid From</strong></span></div></div>
	  <div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
	  <div class="p5" style="font-size: 1.2em"><span class="m5"><strong> Valid To</strong></span></div></div>
	  </div>
	  <?php
	  for ($i = 0; $i < count($note); $i++) {
	  ?>
	  <div class="row" style="padding: 0px 15px;">
	  <div class="col-sm-2" style="border-right:1px solid #ccc;border-left:1px solid #ccc;border-bottom:1px solid #ccc">
	  <div class="p5">
	  <?php if ($note[$i]['dnt_area_type'] == 1) {
	  ?>
	  <?= ($note[$i]['dnt_zone_name']) ?>
	  <?php } ?>
	  <?php
	  if ($note[$i]['dnt_area_type'] == 3) {
	  ?>
	  <?= ($note[$i]['cty_name']) ?>
	  <?php
	  } else if ($note[$i]['dnt_area_type'] == 2) {
	  ?>
	  <?= ($note[$i]['dnt_state_name']) ?>
	  <?php
	  } else if ($note[$i]['dnt_area_type'] == 0) {
	  ?>
	  <?= "Applicable to all" ?>
	  <?php
	  } else if ($note[$i]['dnt_area_type'] == 4) {
	  ?>
	  <?= Promos::$region[$note[$i]["dnt_area_id"]] ?>
	  <?php
	  }
	  ?>
	  </div></div>
	  <div class="col-sm-6" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
	  <div class="p5"><span><?= ($note[$i]['dnt_note']) ?></span></div></div>
	  <div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
	  <div class="p5"><span>  <?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?></span></div></div>
	  <div class="col-sm-2" style="border-right:1px solid #ccc;border-bottom:1px solid #ccc">
	  <div class="p5"><span> <?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?></span></div></div>
	  </div><?php
	  }
	  ?>


	  </div>
	  </div>

	  </div>

	  </div>
	  </div>
	  </div>
	  </div>
	  </div>
	  <?php
	  } */
	?>
	<!----Start new design------->
	<div class="container mt30">
		<div class="row">
			<div class="col-lg-7">
				<div class="row">
					<div class="col-12 mb20">
						<div class="bg-white-box">
							<div class="heading-part mb10 text-uppercase font-weight-bold text-bold"><b>Traveller Information</b></div>

							<div class="row">
								<div class="col-12 col-sm-6">
									Passenger Name: <span class="black-color"><?= $model->bkgUserInfo->getUsername() ?></span><br>
									Email: <span class="black-color">
										<?= (count(Yii::app()->user->loadUser()) > 0 && Yii::app()->user->loadUser()->user_id == $model->bkgUserInfo->bkg_user_id) ? $model->bkgUserInfo->bkg_user_email : Filter::maskEmalAddress($model->bkgUserInfo->bkg_user_email); ?>
										<img src="<?= $isEmailVerified; ?>">
									</span><br>
									Phone: <span class="black-color">+<?= $model->bkgUserInfo->bkg_country_code ?><?= (count(Yii::app()->user->loadUser()) > 0 && Yii::app()->user->loadUser()->user_id == $model->bkgUserInfo->bkg_user_id) ? $model->bkgUserInfo->bkg_contact_no : Filter::maskPhoneNumber($model->bkgUserInfo->bkg_contact_no) ?>
										<img src="<?= $isPhoneVerified; ?>">
									</span><br>
								</div>
								<div class="col-12 col-sm-6">
									Cab Type: <span class="black-color"><?= $model1->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . " (" . $model1->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ")-" . $model->bkgVehicleType->vht_make . ' ' . $model->bkgVehicleType->vht_model; ?></span><br>
									Trip Type: <span class="black-color"><?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?></span><br>
									Pickup Time: <span class="black-color"><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?>, <?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></span><br>
									<?php
									$cnt			 = count($model->bookingRoutes) - 1;
									$dropDateTime	 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cnt]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cnt]->brt_trip_duration . ' MINUTE'));
									?>
									Drop Time: <span class="black-color"><?= date('jS M Y (l)', strtotime($dropDateTime)) ?>, <?= date('h:i A', strtotime($dropDateTime)) ?></span><br>
								</div>

							</div>
						</div>
					</div>

					<?php
					if ($model->bkg_booking_type == 5)
					{
						$packName = Package::getNamebyId($model->bkg_package_id);
						?>
						<div class="col-12 mb20">
							<div class="heading-part ">Package: <strong><?= $packName ?></strong></div>
						</div>
					<?php } ?>
					<div class="col-12 mb20 w-100">
						<div class="bg-white-box">
							<div class="heading-part mb10 text-uppercase font-weight-bold text-bold"><b>Your Trip Plan</b></div>
							<div class="container-time pb15"><ul>
									<?php
									$last	 = 0;
									$tdays	 = 0;
									$cntBrt	 = count($model->bookingRoutes);
									foreach ($model->bookingRoutes as $k => $brt)
									{
										if ($k == 0)
										{
											$tdays = 1;
										}
										else
										{
											$date1		 = new DateTime(date('Y-m-d', strtotime($model->bookingRoutes[0]->brt_pickup_datetime)));
											$date2		 = new DateTime(date('Y-m-d', strtotime($brt->brt_pickup_datetime)));
											$difference	 = $date1->diff($date2);
											$tdays		 = ($difference->d + 1);
										}
										?>
										<li><span></span>
											<div>
												<?php
												$locAddress	 = ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location;
												$locAddress	 .= ($brt->brt_from_latitude > 0) ? '' : ', ' . $brt->brtFromCity->cty_name;
												?>
												<div class="title black-color"><?= $locAddress ?>
													<span class="pull-right pr30">Day <?= $tdays ?></span></div>
												<div class="info hide"><?= ($brt->brt_from_location == '') ? $brt->brtFromCity->cty_name : $brt->brt_from_location ?></div>
												<div class="type"><?= ($brt->brt_trip_distance < $model1->bkg_trip_distance && $model1->bkg_booking_type == 1) ? $model1->bkg_trip_distance : $brt->brt_trip_distance ?><br>km</div>
											</div> <span class="number"><span class="black-color">
													<?= DateTimeFormat::DateTimeToDatePicker($brt->brt_pickup_datetime); ?><br><b class="gray-color bold-none">
														<?= DateTimeFormat::DateTimeToTimePicker($brt->brt_pickup_datetime); ?></b></span> 
												<span class="text-success mt10 text-left"><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></span></span>
										</li>
										<?php
										if ($k == ($cntBrt - 1))
										{
											?>
											<?php
											$expArrivedate	 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cntBrt - 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cntBrt - 1]->brt_trip_duration . ' MINUTE'));
											?>
											<li>
												<div><span></span>
													<?php
													$locAddress		 = ($brt->brt_to_location == '') ? $brt->brtToCity->cty_name : $brt->brt_to_location;
													$locAddress		 .= ($brt->brt_to_latitude > 0) ? '' : ', ' . $brt->brtToCity->cty_name;
													?>

													<div class="title black-color"><?= $locAddress ?>
														<span class="pull-right pr30">Day <?= $tdays ?></span></div>
													<div class="info hide"><?= ($brt->brt_to_location == '' ) ? $brt->brtToCity->cty_name : $brt->brt_to_location ?></div>
													<div class="type hide"><?= ($brt->brt_trip_distance < $model1->bkg_trip_distance && $model1->bkg_booking_type == 1) ? $model1->bkg_trip_distance : $brt->brt_trip_distance ?><br>km</div>
												</div> <span class="number "><span class="black-color "><?= DateTimeFormat::DateTimeToDatePicker($expArrivedate); ?><br><b class="gray-color bold-none"><?= DateTimeFormat::DateTimeToTimePicker($expArrivedate); ?></b></span> 
													<span class="timeing-box hide"><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></span></span>
											</li>
										<?php } ?>



									<?php } ?></ul>
							</div>
							<div class="heading-part text-uppercase mb5 mt30"><b>You have booked a <?= $model1->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label ?> <img src="/images/icon/sccid<?= $model1->bkgSvcClassVhcCat->scc_ServiceClass->scc_id ?>.png"> car</b></div>
							<div class="row">
								<div class="col-12">
									<ol>
										<?php
										$bkgVehicleTypeId	 = $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_desc;
										$arrServiceDesc		 = json_decode($bkgVehicleTypeId);
										foreach ($arrServiceDesc as $key => $value)
										{
											?>	
											<li><span class="black-color"><?= $value; ?></span></li>
											<?php
										}
										?>
									</ol>
								</div>	
							</div>
						</div>
					</div>
					<?php
					if (($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') && $model->bkg_booking_type != 4)
					{
						?>
						<div class="col-12 mb30">
							<div class="bg-white-box">
								<div class="row">
									<div class="col-9 heading-part mb10"><b>UPDATE YOUR PICKUP & DROP ADDRESSES</b></div>
								</div>
								<?php $this->renderPartial('pickupLocationWidget', ['model' => $model], false, false); ?>
								<div class="row">
									<div class="col-9 heading-part mb10"></div>
									<div class="col-3 text-center">
										<button type="button" id="saveNewAddreses" class="btn btn-effect-ripple btn-success p5 mt10" name="saveNewAddreses" onclick="saveAddressesByRoutes();">Save Addresses</button>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" value="<?php echo ($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') ? '0' : '1' ?>" class="isPickupAdrsCls" name="isPickupAdrsCls">
					<?php } ?>

					<div class="col-12 mb30">
						<div class="bg-white-box">
							<div class="heading-part mb10"><b>ADDITIONAL INFORMATION</b></div>  
							<?php
							$fcity					 = Cities::getName($model->bkg_from_city_id);
							$tcity					 = Cities::getName($model->bkg_to_city_id);
							$infosource				 = BookingAddInfo::model()->getInfosource('user');
							$action					 = Yii::app()->request->getParam('action');
							$hash					 = Yii::app()->shortHash->hash($model->bkg_id);
							$otherExist				 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 'block' : 'none';
							$model->bkg_chk_others	 = ($model->bkgAddInfo->bkg_spl_req_other != '') ? 1 : 0;
							?>
							<?php
							$form					 = $this->beginWidget('CActiveForm', array(
								'id'					 => 'bookingadditionalinfo', 'enableClientValidation' => true,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error',
									'afterValidate'		 => ''
								),
								'errorMessageCssClass'	 => 'help-block',
								'htmlOptions'			 => array(
									'class'		 => '', 'enctype'	 => 'multipart/form-data'
								),
							));
							/* @var $form CActiveForm */
							?>
							<div class="row">
								<div class="col-12">
									<div class="row">
										<div class="heading-part mb10 col-12 col-lg-7 text-uppercase">Special Requests</div>
										<div class="heading-part mb10 col-12 col-lg-5" align="right"></div>
									</div>
									<!--		<div class="main_time border-blueline">-->
									<div class="mb0 col-12">  
										<p><b>Please provide additional information to help us to serve you better.</b></p>
									</div>
									<?=
									$form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']);
									$form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
									?>
									<div id="error_div" style="display: none" class="alert alert-block alert-danger"></div>
									<div class="row ml5">
										<div class="col-12">
											<div class="row">
												<div class="col-4">

													<label class="checkbox-inline check-box">Senior citizen traveling
														<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl'); ?>
														<span class="checkmark-box"></span>
													</label>
												</div>
												<div class="col-4 pl15 pr15">
													<label class="checkbox-inline check-box">Kids on board
														<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_kids_trvl'); ?>
														<span class="checkmark-box"></span>
													</label>
												</div>
												<div class="col-4">
													<label class="checkbox-inline check-box">Women traveling
														<?php echo $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_woman_trvl'); ?>
														<span class="checkmark-box"></span>
													</label>
												</div>

											</div>
											<div class="row">
												<div class="col-4">
													<label class="checkbox-inline check-box">English-speaking driver required
														<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking'); ?>
														<span class="checkmark-box"></span>
													</label>
												</div>
												<div class="col-4 pl15 pr15">
													<label class="checkbox-inline check-box">Others
														<?= $form->checkBox($model, 'bkg_chk_others'); ?>
														<span class="checkmark-box"></span>
													</label>
												</div>
												<div class="col-4">
													<?php
													if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
													{
														?>
														<label class="checkbox-inline check-box">Require vehicle with Carrier
															<?= $form->checkBox($model->bkgAddInfo, 'bkg_spl_req_carrier'); ?>
															<span class="checkmark-box"></span>
														</label>
														<?php
													}
													?>
												</div>
											</div>
											<div class="row">
												<div class="col-6"></div>
												<div class="col-6"></div>
											</div>
											<div class="row">
												<div class="col-12" id="othreq" style="display: <?= $otherExist ?>">
													<?= $form->textArea($model->bkgAddInfo, 'bkg_spl_req_other', ['placeholder' => "Other Requests", 'class' => "form-control mb10"]) ?>  
												</div>
											</div>
											<div class="row">
												<div class="col-5 ">
													<label class="checkbox-inline pt0 pr30 check-box">Add a journey break (₹150/30mins)
														<?= $form->checkBox($model, 'bkg_add_my_trip'); ?>
														<span class="checkmark-box"></span>
													</label>
												</div>
												<div class="col-7 col-lg-7">
													<?= $form->dropDownList($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['0' => 'Minutes', '30' => '30', '60' => '60', '90' => '90', '120' => '120', '150' => '150', '180' => '180'], ['id' => 'bkg_spl_req_lunch_break_time', 'class' => 'form-control', 'placeholder' => 'Journey Break']) ?>
													<?php echo $form->error($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['class' => 'help-block error']); ?>
													<div id="addmytrip" class="font11">First 15min free. Unplanned journey breaks are not allowed for one-way trips</div>
												</div>
											</div> 
										</div>
									</div>
									<!--		</div>
											</div>
											<div class="  main_time border-blueline additionalinfo  mb20">-->
									<div class="heading-part mb10 text-uppercase">Additional Details</div>
									<div class="special_request">
										<div class="row mb10">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12"><label for="inputEmail" class="control-label font-weight-bold">Personal Or Business Trip?</label></div>
													<div class="col-6 col-lg-3">
														<input type="hidden" id="request_status" value="">
														<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id']); ?>

														<label class="radio2-style mb0">
															<input id="BookingAddInfo_bkg_user_trip_type_0" value="1" type="radio" name="BookingAddInfo[bkg_user_trip_type]" class="bkg_user_trip_type" <?php
															if ($model->bkgAddInfo['bkg_user_trip_type'] == 1)
															{
																?>checked="checked"<?php } ?>>Personal	
															<span class="checkmark-2"></span>
														</label>
													</div>
													<div class="col-6 col-lg-3">
														<label class="radio2-style mb0">
															<input id="BookingAddInfo_bkg_user_trip_type_1" value="2" type="radio" name="BookingAddInfo[bkg_user_trip_type]" class="bkg_user_trip_type" <?php
															if ($model->bkgAddInfo['bkg_user_trip_type'] == 2)
															{
																?>checked="checked"<?php } ?>>Business	
															<span class="checkmark-2"></span>
														</label>
													</div>

												</div>
											</div>
											<?php
											$readOnly = [];
											if (in_array($model->bkg_flexxi_type, [1, 2]))
											{
												$readOnly = ['readOnly' => 'readOnly'];
											}
											?>
											<div class="col-md-4">
												<div class="row">
													<label for="inputEmail" class="control-label col-12 font-weight-bold">Number of Passengers <span style="color:red;"> * </span></label>
													<div class="col-6 w-50">
														<?= $form->numberField($model->bkgAddInfo, 'bkg_no_person', ['placeholder' => "0", 'class' => 'form-control', 'min' => 1, 'max' => $bdata['vct_capacity']] + $readOnly) ?>                      
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="row">
													<label for="inputEmail" class="control-label col-12 font-weight-bold"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_large_bag') ?></label>
													<div class="col-6 w-50">
														<?php //= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "large suitcases", 'min' => 0, 'max' => $bdata['vct_big_bag_capacity']] + $readOnly), 'groupOptions' => []))   ?>                    
														<?php
														$vct_Id		 = $model->bkgSvcClassVhcCat->scv_vct_id;
														$scc_Id		 = $model->bkgSvcClassVhcCat->scv_scc_id;
														$sbagRecord	 = VehicleCatSvcClass::smallbagBycategoryClass($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id);
														$lbag		 = floor($sbagRecord['vcsc_small_bag'] / 2);
														?>
														<select class="form-control" id="BookingAddInfo_bkg_num_large_bag" name="BookingAddInfo[bkg_num_large_bag]" onchange="luggage_info(this.value,<?php echo $vct_Id ?>,<?php echo $scc_Id ?>,<?php echo $sbagRecord['vcsc_small_bag'] ?>);">
															<?php
															for ($i = 0; $i <= $lbag; $i++)
															{
																?>
																<option value="<?php echo $i ?>"><?php echo $i ?></option>
															<?php } ?>		
														</select>
													</div>
												</div>
											</div>


											<div class="col-md-4">
												<div class="row">
													<label for="inputEmail" class="control-label col-12 font-weight-bold"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_small_bag') ?></label>
													<div class="col-6 w-50">
														<?php //= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "small bags", 'min' => 0, 'max' => $bdata['vct_small_bag_capacity']] + $readOnly), 'groupOptions' => []))        ?>            
														<select class="form-control" id="BookingAddInfo_bkg_num_small_bag" name="BookingAddInfo[bkg_num_small_bag]">
															<?php
															for ($i = 1; $i <= $sbagRecord['vcsc_small_bag']; $i++)
															{
																?>
																<option value="<?php echo $i ?>"><?php echo $i ?></option>
															<?php } ?>		
														</select>       
													</div>
												</div>
											</div>
										</div>
										<div class="row">

											<!--                                        <div class="col-md-4">
																																	<div class="row">
																																			<div class="form-group">
																																					<label for="inputEmail" class="control-label col-12 ml10">How did you hear about Gozo cabs? </label>
											
																																					<div class="col-12">
											<?php
//                                                        $datainfo = VehicleTypes::model()->getJSON($infosource);
//                                                        $this->widget('booster.widgets.TbSelect2', array(
//                                                            'model' => $model->bkgAddInfo,
//                                                            'attribute' => 'bkg_info_source',
//                                                            'val' => "'" . $model->bkgAddInfo->bkg_info_source . "'",
//                                                            'asDropDownList' => FALSE,
//                                                            'options' => array('data' => new CJavaScriptExpression($datainfo)),
//                                                            'htmlOptions' => array('style' => 'width:100%;margin-bottom:10px', 'placeholder' => 'Select Infosource ')
//                                                        ));
											?>
																																					</div>
																																			</div>
																																	</div>
																															</div>-->
											<?php $sourceDescShow	 = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other Media') ? '' : 'hide'; ?>
											<div class="col-md-4">
												<div class="row">
													<div class="form-group <?= $sourceDescShow ?> " id="source_desc_show">
														<label for="inputEmail" class="control-label">&nbsp;</label>
														<div class="col-12 mt20">
															<?= $form->textField($model->bkgAddInfo, 'bkg_info_source_desc', ['placeholder' => "", 'class' => "form-control"]) ?>                      
														</div>
													</div>
												</div>
											</div>
										</div>
										<h3 class="hide">&nbsp;<br>Journey Details: </h3>
										<?php
										$j				 = 0;
										$cntRt			 = sizeof($model->bookingRoutes);
										foreach ($model->bookingRoutes as $key => $brtRoute)
										{
											if ($j == 0)
											{
												?>       
												<div class="row hide">
													<div class = "form-group mb15">
														<label for="pickup_address" class="control-label col-12 col-md-5 pt10">Pickup Pincode for <?= $brtRoute->brtFromCity->cty_name ?></label>
														<div class="col-12 col-sm-7">
															<?= $form->numberField($brtRoute, "brt_from_pincode", ['placeholder' => "Pincode (Optional)", 'class' => "form-control"]) ?>
														</div>
													</div>
												</div>
												<?php
											}
											$j++;
											$opt = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
											?>
											<div class = "row hide">
												<div class = "form-group mb15">
													<label for="pickup_address" class="control-label col-12 col-md-5 pt10">Drop Pincode for <?= $brtRoute->brtToCity->cty_name ?></label>
													<div class="col-12 col-md-7">
														<?= $form->numberField($brtRoute, "brt_to_pincode", ['placeholder' => "Pincode (Optional)", 'class' => "form-control"]) ?>
													</div>
												</div>
											</div>
											<?php
										}
										?>
									</div>
									<div class="clear"></div>
									<div class="row">	
										<div class="col-7 heading-part mb10">&nbsp;</div>
										<div class="col-5 heading-part mb10 mt15"><b>
												<button type="button" class="btn btn-effect-ripple btn-success" id="additiondetails" >Save Special Requests</button></b>				
										</div>
									</div>
									<div class="row">
										<div class="col-4 heading-part mb10">&nbsp;</div>
										<div class="col-8 heading-part mb10">
											<span id="msg" class="hide" style="font-weight: bold;color: #FF6700;font-size: 12px">SPECIAL REQUESTS SAVED.</span>
										</div>
									</div>
									<div class="clear"></div>
									<!--<button type="button" class="btn btn-primary btn-lg pl40 pr40 proceed-new-btn mt0" id ="additiondetails">Save</button>-->

								</div>
							</div>
							<?php
//$vehicletype = VehicleTypes::model()->findByPk($model->bkgAddInfo->baddInfoBkg->bkg_vehicle_type_id);
							$capacity		 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_capacity;
							$bagCapacity	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_small_bag_capacity;
							$bigBagCapacity	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_big_bag_capacity;
							?>
							<?php $this->endWidget(); ?>
						</div>
					</div> 

					<div class="col-12">
						<?php
						$cancelTimes_new = CancellationPolicy::initiateRequest($model);
						$this->renderPartial("bkCanInfo", ["model" => $model, "cancelTimes_new" => $cancelTimes_new], false, false);
						?>
					</div>

				</div>
			</div>
			<div class="col-lg-5">


				<div id ="driverCabDetails">
					<?php
					if ($model->bkg_status == 5)
					{
						?>

						<?php
						if ($model->bkgTrack->btk_drv_details_viewed == 0)
						{
							?>
							<div id="viewDrvDetails">
								<div class="mb20 " ><b><button type="button" class="btn btn-effect-ripple btn-success" title="View Driver Details" onclick="viewDriverContact(<?= $model->bkg_id ?>)" >Click here to view Driver & Cab Details</button></b>
									<br><span> <div class="text-center alert alert-danger p10 mb10 mt20">** Free cancellation period ends (Cancellation charges will apply) as soon as you view driver details</div></span>
								</div>
							</div>
							<?php
						}
						else
						{
							?>
							<div class="row" id="driverDetails">

								<div class="row">
									<div class="col-12">
										<div class="bg-white-box">
											<div class="mb10 text-uppercase font-weight-bold"><b>Driver & Car Details</b></div>
											<b>Driver Name:</b>
											<span>
												<?php
												if ($model->bkgBcb->bcb_driver_id != '' && $model->bkgBcb->bcb_driver_id != NULL)
												{
													?>
													<?= $model->bkgBcb->bcbDriver->drv_name ?>
													<?php
												}
												else
												{
													echo "To be assigned.";
												}
												?>
											</span><br>
											<span><b>Driver Phone:</b>
												<?php
												if ($model->bkgBcb->bcb_driver_id != '' && $model->bkgBcb->bcb_driver_id != NULL)
												{
													$drvContactId	 = $model->bkgBcb->bcbDriver->drv_contact_id;
													$driver_phone	 = Yii::app()->params['customerToDriver'];
													?>
													<?php
													echo $driver_phone;
													?>
													<?php
												}
												else
												{
													echo "To be assigned.";
												}
												?>
											</span><br>

											<b>Car License Plate: </b>
											<span><?php //= $model->bkgUserInfo->bkg_country_code                        ?>
												<?php
												if ($model->bkgBcb->bcb_cab_id != '' && $model->bkgBcb->bcb_cab_id != NULL)
												{
													?>
													<?= $model->bkgBcb->bcbCab->vhc_number; ?>
													<?php
												}
												else
												{
													echo "To be assigned.";
												}
												?>
											</span><br>
											<div class="col-sm-6 pl0">
												<b>Make:</b> <span><?php //= $model->bkgUserInfo->bkg_country_code                         ?>
													<?php
													if ($model->bkgBcb->bcb_cab_id != '' && $model->bkgBcb->bcb_cab_id != NULL)
													{
														?>
														<?= $model->bkgBcb->bcbCab->vhcType->vht_make; ?>
														<?php
													}
													else
													{
														echo "To be assigned.";
													}
													?>
												</span><br>
											</div>
											<div class="col-sm-6 pl0">
												<b>Model: </b><span><?php //= $model->bkgUserInfo->bkg_country_code                          ?>
													<?php
													if ($model->bkgBcb->bcb_cab_id != '' && $model->bkgBcb->bcb_cab_id != NULL)
													{
														?>
														<?= $vehicleModel; ?>
														<?php
													}
													else
													{
														echo "To be assigned.";
													}
													?>
												</span><br>
											</div>
											<div class="text-center alert alert-danger p10 mb10 mt20">
												<b>** Cancellation charges will now apply if booking is cancelled</b>
											</div>

										</div>
									</div>
								</div>

							</div>
							<?php
						}
					}
					else
					{
						?>
						<?php
						if (($model->bkg_status != 6) && ($model->bkg_status!= 7))
						{
							?>
							<div class="row">
								<div class="col-12">
									<div class="bg-white-box">
										<div class="mb10 text-uppercase font-weight-bold"><b>Driver & Car Details</b></div>
										<b>Driver Name:</b>
										<span>
											<?php
											if ($model->bkgBcb->bcb_driver_id != '' && $model->bkgBcb->bcb_driver_id != NULL)
											{
												?>
												<?= $model->bkgBcb->bcbDriver->drv_name ?>
												<?php
											}
											else
											{
												echo "To be assigned.";
											}
											?>
										</span><br>
										<span><b>Driver Phone:</b>
											<?php
											if ($model->bkgBcb->bcb_driver_id != '' && $model->bkgBcb->bcb_driver_id != NULL)
											{
												$drvContactId	 = $model->bkgBcb->bcbDriver->drv_contact_id;
												$driver_phone	 = Yii::app()->params['customerToDriver'];
												?>
												<?php
												echo $driver_phone;
												?>
												<?php
											}
											else
											{
												echo "To be assigned.";
											}
											?>
										</span><br>

										<b>Car License Plate: </b>
										<span><?php //= $model->bkgUserInfo->bkg_country_code                        ?>
											<?php
											if ($model->bkgBcb->bcb_cab_id != '' && $model->bkgBcb->bcb_cab_id != NULL)
											{
												?>
												<?= $model->bkgBcb->bcbCab->vhc_number; ?>
												<?php
											}
											else
											{
												echo "To be assigned.";
											}
											?>
										</span><br>
										<div class="col-sm-6 pl0">
											<b>Make:</b> <span><?php //= $model->bkgUserInfo->bkg_country_code                         ?>
												<?php
												if ($model->bkgBcb->bcb_cab_id != '' && $model->bkgBcb->bcb_cab_id != NULL)
												{
													?>
													<?= $model->bkgBcb->bcbCab->vhcType->vht_make; ?>
													<?php
												}
												else
												{
													echo "To be assigned.";
												}
												?>
											</span><br>
										</div>
										<div class="col-sm-6 pl0">
											<b>Model: </b><span><?php //= $model->bkgUserInfo->bkg_country_code                          ?>
												<?php
												if ($model->bkgBcb->bcb_cab_id != '' && $model->bkgBcb->bcb_cab_id != NULL)
												{
													?>
													<?= $vehicleModel; ?>
													<?php
												}
												else
												{
													echo "To be assigned.";
												}
												?>
											</span><br>
										</div>
										<div class="text-center alert alert-primary p10 mb10 mt20">
											<b>Do not board the cab if the cab or driver information does not match.</b>
										</div>
										<?php
										if ($model->bkgPref->bkg_trip_otp_required == 1)
										{
											?>
											<div class="text-center alert alert-primary p10 mb10 ">
												<b>Please use OTP: <?= $model->bkgTrack->bkg_trip_otp ?> at the time of pickup. Don't share OTP before boarding the cab.</b>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>

				<div class="p10">	
					<div class="mb5"><h4 class="text-warning">Get Instant updates about this trip. Download our app now!</h4></div>
					<div class="mb20">
						<a href="https://play.google.com/store/apps/details?id=com.aaocab.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="aaocab APP"></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img src="/images/app_store.png?v1.2" alt="aaocab APP"></a>
					</div>
				</div>
				<div class="mt20">

					<div class="row">
						<?php
						$this->renderPartial("bkSummaryBilling", ["model" => $model, "isredirct" => $isredirct, "refcode" => $refcode, "whatappShareLink" => $whatappShareLink], false, false);

						$arrPromoTemplateData						 = array();
						$arrPromoTemplateData['model']				 = $model;
						$arrPromoTemplateData['isPromoApplicable']	 = $isPromoApplicable;
						$arrPromoTemplateData['creditVal']			 = $creditVal;
						$arrPromoTemplateData['userCreditStatus']	 = $userCreditStatus;
						$arrPromoTemplateData['gozocoinApply']		 = $gozocoinApply;
						$arrPromoTemplateData['promoArr']			 = $promoArr;
						$arrPromoTemplateData['classPromo']			 = '';
						$arrPromoTemplateData['walletBalance']		 = $walletBalance;
						?>
						<!-- Promo start here-->
						<?php
						if (($isPromoApplicable || ($creditVal > 0 && ($model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == 0 || $userCreditStatus == 1))) && $model->bkg_flexxi_type != 2)
						{
							$classPromo							 = " main_time border-blueline ";
							$arrPromoTemplateData['classPromo']	 = ' main_time border-blueline ';
						}

						if ($isPromoApplicable && ($model->bkgInvoice->bkg_discount_amount == 0 || $model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == 0 || $userCreditStatus == 1) && ($model->bkgInvoice->bkg_advance_amount == 0 || $gozocoinApply == 1) && ($model->bkg_status < 6 || $model->bkg_status == 15) && $model->bkg_booking_type != 7)
						{
							if ($model->bkg_agent_id == NULL && $model->bkg_booking_type != 7)
							{
								?>
								<?php $this->renderPartial("bkSummaryPromo", $arrPromoTemplateData, false, false); ?>
								<?php
							}
						}
						if ($applicableAddons)
						{
							$this->renderPartial("bkSummaryAddons", ["model" => $model, 'applicableAddons' => $applicableAddons, 'routeRatesArr' => $routeRatesArr], false, false);
						}
						?>
						<?php
						if (($model->bkgUserInfo->bkg_phone_verified == 0 && $model->bkgUserInfo->bkg_email_verified == 0) && ($model->bkg_agent_id == '' || $model->bkgTrail->bkg_platform == 5 || ($model->bkg_agent_id != '' && $model->bkgTrail->bkg_platform == 4 && $model->bkgInvoice->bkg_corporate_remunerator != 2)))
						{
							?>
							<div class="row">
								<div class="panel-body">
									<?php
									$form = $this->beginWidget('CActiveForm', array(
										'id'					 => 'manualverifyotp', 'enableClientValidation' => FALSE,
										'clientOptions'			 => array(
											'validateOnSubmit'	 => true,
											'errorCssClass'		 => 'has-error'
										),
										'enableAjaxValidation'	 => false,
										'errorMessageCssClass'	 => 'help-block',
										'htmlOptions'			 => array(
											'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
										),
									));
									?>
									<?php $this->endWidget(); ?>
								</div> 
							</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<?php
			$dboApplicable = Filter::dboApplicable($model);
			if ($dboApplicable)
			{
				?>
				<div class="col-sm-6">
					<div class="col-12 ">
						<div class="main_time1 mb30">
							<div class="row">
								<a href="/terms/doubleback" target="_blank"><img src="/images/dbo_banner.jpg" alt="" width="550" style="margin: 20px;"></a>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="col-sm-6"></div>


		</div>
	</div>

	<div class="container">
		<div class="row text-justify">

			<?php
			if ($model->bkg_agent_id == 1249 || !$model->bkg_agent_id > 0)
			{
				if (!$isMobile)
				{
					$this->renderPartial("paywidget", ["model" => $model1, 'isredirct' => $isredirct, 'walletBalance' => $walletBalance], false);
				}
			}
			else
			{
				echo '<div class="row m0 col-12 mt20 bg-white-box pay-widget">
This booking was created by a Gozo Agent Partner. For payment & other inquiries, contact the Gozo Agent Partner.</div>';
			}
			?>

			<?php
			$this->renderPartial("bookingInfo", ["model"				 => $model, "bookingtype"		 => $model->bkg_booking_type,
				'bkg_trip_distance'	 => $model->bkg_trip_distance, 'cab_type_id'		 => $model->bkg_vehicle_type_id, 'note'				 => $note], false);
			?>
		</div>
	</div>

	<?php
}
else
{
	?>
	<?php
	echo "<br><h4><b>This booking is not active.</b></h4><br>";
}
?>
<script type="text/javascript">
	function luggage_info(largebag, vcatid, sccid, smallbag)
	{
		var largebag = largebag;
		var vcatid = vcatid;
		var sccid = sccid;
		var smallbag = smallbag;
		var sbag = Math.floor(smallbag - (largebag * 2));
		$("#BookingAddInfo_bkg_num_small_bag").empty();
		for (var i = 0; i <= sbag; i++)
		{
			var id = i;
			var name = i;
			$("#BookingAddInfo_bkg_num_small_bag").append("<option value='" + id + "'>" + name + "</option>");
		}
	}
	function opentns()
	{
		$href = '<?= Yii::app()->createUrl('index/tns') ?>';
		jQuery.ajax({type: 'GET', url: $href,
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'large',
					onEscape: function ()
					{
						box.modal('hide');
						box.css('display', 'none');
						$('.modal-backdrop').remove();
					}
				}).removeClass('fade').css('display', 'block');
			}
		});
	}
	function openflexxiterms()
	{
		$href = '<?= Yii::app()->createUrl('index/flexxiterms') ?>';
		jQuery.ajax({type: 'GET', url: $href,
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'large',
					onEscape: function ()
					{
						box.modal('hide');
					}
				});
			}
		});

	}



	function confirmOTP()
	{
		var bid = $('#bkg_id5').val();
		var hsh = $('#hash5').val();
		var href1 = '<?= Yii::app()->createUrl('booking/confirmmobile') ?>';
		jQuery.ajax({'type': 'GET', 'url': href1,
			'data': {'bid': bid, 'hsh': hsh},
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'medium',
					onEscape: function ()
					{
					}
				});
			}
		});
	}
	function showTcGozoCoins1()
	{
		var href1 = '<?= Yii::app()->createUrl('index/discadv') ?>';
		jQuery.ajax({type: 'GET', url: href1,
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'large',
					onEscape: function ()
					{
						box.modal('hide');
					}
				});
			}
		});
	}

	function showTcGozoCoins()
	{
		var href1 = '<?= Yii::app()->createUrl('index/tnsgozocoins') ?>';
		jQuery.ajax({type: 'GET', url: href1,
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'large',
					onEscape: function ()
					{
						box.modal('hide');
					}
				});
			}
		});
	}

	function showTcGozoCoins2()
	{
		var href1 = '<?= Yii::app()->createUrl('index/cashbackadv') ?>';
		jQuery.ajax({type: 'GET', url: href1,
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'large',
					onEscape: function ()
					{
						box.modal('hide');
					}
				});
			}});
	}

	function showTcGozoCoins25()
	{
		var href1 = '<?= Yii::app()->createUrl('index/cashbackadv25') ?>';
		jQuery.ajax({type: 'GET', url: href1,
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'large',
					onEscape: function ()
					{
						box.modal('hide');
					}
				});
			}
		});
	}
	function showTcGozoCoins2p5()
	{
		var href1 = '<?= Yii::app()->createUrl('index/discadv2p5') ?>';
		jQuery.ajax({type: 'GET', url: href1,
			success: function (data)
			{
				box = bootbox.dialog({
					message: data,
					title: '',
					size: 'large',
					onEscape: function ()
					{
						box.modal('hide');
					}
				});
			}
		});
	}

	function verifyOTP(bkgId, hash)
	{
		$.ajax({type: 'POST',
			url: '<?= Yii::app()->createUrl("booking/verifycontact"); ?>',
			data: {"otpvalue": $("#otpvalue").val(), 'id': bkgId, 'hash': hash, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
			dataType: "json",
			success: function (data1)
			{
				if (data1.success)
				{
					alert(data1.errors);
					window.location.reload(true);
				} else
				{
					alert(data1.errors);
				}
			},
		});
		return false;
	}
	function reconfirmUpdate()
	{
		var bkgId = bid;
		var x = $("input[name='bkg_reconfirm_flag']:checked").val();
		if (x == 3)
		{
			if (confirm('Are you sure! you want to Cancel this booking?'))
			{
				$("#payment").hide();
				$("#promo").hide();
				$("input[name=bkg_reconfirm_flag]").attr('disabled', true);
				updateReconfirm(bkgId, x);
			} else
			{
				$("#bkg_reconfirm_flag_0").prop('checked', true);
				$("#bkg_reconfirm_flag_0").click();
			}
		}
		if (x == 1)
		{
			if (confirm('Are you sure! you want to Reconfirm this booking?'))
			{
				$("#payment").show();
				updateReconfirm(bkgId, x);
			}

		}
	}

	function updateReconfirm(bkgId, rType)
	{
		$.ajax({type: 'POST',
			url: '<?= Yii::app()->createUrl("booking/updateReconfirm"); ?>',
			data: {'id': bkgId, 'type': rType, "YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
			dataType: "json",
			success: function (data)
			{
				if (data)
				{
					box = bootbox.dialog({
						message: data.message,
						title: 'Booking Reconfirmed',
						size: 'small',
						buttons: {
							confirm: {
								label: 'OK',

							},

						},
						onEscape: function ()
						{
							box.modal('hide');
						}
					});
				}
			},
		});
	}



	$(document).ready(function ()
	{
		$("#BookingAddInfo_bkg_info_source").change(function ()
		{
			var infosource = $("#BookingAddInfo_bkg_info_source").val();
			extraAdditionalInfo(infosource);
		});

		if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
		{
			$('addmytrip').attr('disabled', '');
		}
	});

	$(window).ready(function ()
	{

		$('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').attr('disabled', '');
		$('#bkg_spl_req_lunch_break_time').attr('disabled', true);
		$('#BookingAddInfo_bkg_spl_req_carrier').attr('disabled', true);
	});

	$('#additiondetails').click(function (event)
	{
		$(".error").css('color', 'rgb(212, 103, 103)');
		var noPerson = $('#BookingAddInfo_bkg_no_person').val();
		var smallBag = $('#BookingAddInfo_bkg_num_small_bag').val();
		var bigBag = $('#BookingAddInfo_bkg_num_large_bag').val();
		var vhCapacity = '<?= $capacity ?>';
		var smallbagCapacity = '<?= $bagCapacity ?>';
		var bigbagCapacity = '<?= $bigBagCapacity ?>';
		var href = '<?= Yii::app()->createUrl('booking/summaryadditionalinfo') ?>';
		var bkgid = $('#bkg_id').val();
		var hash = '<?= $hash ?>';
		var noPassenger = $('#BookingAddInfo_bkg_no_person').val();
		var noLargeBag = $('#BookingAddInfo_bkg_num_large_bag').val();
		var noSmallBag = $('#BookingAddInfo_bkg_num_small_bag').val();
		var fromPincode = $('#BookingRoute_brt_from_pincode').val();
		var toPincode = $('#BookingRoute_brt_to_pincode').val();
		var infosource = $('#BookingAddInfo_bkg_info_source').val();

		var tripType = $('input[name="BookingAddInfo[bkg_user_trip_type]"]:checked').val();

		var seniorCitizen = $('#BookingAddInfo_bkg_spl_req_senior_citizen_trvl').is(":checked");
		var kidsTravel = $('#BookingAddInfo_bkg_spl_req_kids_trvl').is(":checked");
		var womanTravel = $('#BookingAddInfo_bkg_spl_req_woman_trvl').is(":checked");
		var carrierReq = $('#BookingAddInfo_bkg_spl_req_carrier').is(":checked");
		var engSepeakingDriver = $('#BookingAddInfo_bkg_spl_req_driver_english_speaking').is(":checked");
		var othersInfo = $('#Booking_bkg_chk_others').is(":checked");
		var addTrip = $('#Booking_bkg_add_my_trip').is(":checked");
		var discountAmount = $('.discountAmount').html();
		var walletUsed = $('.walletUsed').html();
		var creditUsed = $('.creditUsed').html();

		//var additionalInfo = $('#bookingadditionalinfo').serialize();
		if (othersInfo == true)
		{
			var splreq = $.trim($("#BookingAddInfo_bkg_spl_req_other").val());
		} else
		{
			var splreq = '';
		}
		if (addTrip == true)
		{
			var breakTime = $('#BookingAddInfo_bkg_spl_req_lunch_break_time').val();
			if (breakTime == 0)
			{
				$('#BookingAddInfo_bkg_spl_req_lunch_break_time_em_').html('Please select journey break time');
				$("#BookingAddInfo_bkg_spl_req_lunch_break_time_em_").css('display', 'block');
				return false;
			}
		}
		if (noPassenger <= 0)
		{
			alert('Please Enter number of Passenger');
			return false;
		} else if (noLargeBag < 0)
		{
			alert('Please Enter number of large bag you want to take');
			return false;
		} else if (noSmallBag < 0)
		{
			alert('Please Enter number of small bag you want to take');
			return false;
		}
		if ((infosource == '5') || (infosource == '6'))
		{
			var infosourcedesc = $('#BookingAddInfo_bkg_info_source_desc').val();
		}
		if (seniorCitizen == true)
		{
			seniorCitizen = 1;
		} else
		{
			seniorCitizen = 0;
		}

		if (kidsTravel == true)
		{
			kidsTravel = 1;
		} else
		{
			kidsTravel = 0;
		}

		if (womanTravel == true)
		{
			womanTravel = 1;
		} else
		{
			womanTravel = 0;
		}

		if (carrierReq == true)
		{
			carrierReq = 1;
		} else
		{
			carrierReq = 0;
		}

		if (engSepeakingDriver == true)
		{
			engSepeakingDriver = 1;
		} else
		{
			engSepeakingDriver = 0;
		}
		if (creditUsed > 0)
		{
			discountAmount = 0;
		}
		//debugger;
		jQuery.ajax({type: 'GET',
			url: href,
			data: {id: bkgid, hash: hash, BookingAddInfo: {bkg_spl_req_senior_citizen_trvl: seniorCitizen, bkg_spl_req_kids_trvl: kidsTravel,
					bkg_spl_req_woman_trvl: womanTravel, bkg_spl_req_carrier: carrierReq, bkg_spl_req_driver_english_speaking: engSepeakingDriver,
					bkg_spl_req_other: splreq, bkg_spl_req_lunch_break_time: breakTime,
					bkg_user_trip_type: tripType, bkg_no_person: noPassenger, bkg_num_large_bag: noLargeBag, bkg_num_small_bag: noSmallBag,
					bkg_info_source: infosource, bkg_info_source_desc: infosourcedesc}, BookingRoute: {"<?= $brtRoute->brt_id ?>": {brt_from_pincode: fromPincode, brt_to_pincode: toPincode}}, discountamount: discountAmount, walletused: walletUsed, creditUsed: creditUsed},
			success: function (data)
			{
				obj = jQuery.parseJSON(data);
				if (obj.success != true)
				{
					if ((parseInt(noPerson) > parseInt(vhCapacity)) && vhCapacity != '')
					{
						$('#BookingAddInfo_bkg_no_person_em_').html('Your selected cab can accomodate ' + vhCapacity + ' passengers');
						$("#BookingAddInfo_bkg_no_person_em_").css('display', 'block');
					}
					if ((parseInt(smallBag) > parseInt(smallbagCapacity)) && smallbagCapacity != '')
					{
						$('#BookingAddInfo_bkg_num_small_bag_em_').html('The selected cab can accomodate ' + smallbagCapacity + ' small bags');
						$("#BookingAddInfo_bkg_num_small_bag_em_").css('display', 'block');
					}
					if ((parseInt(bigBag) > parseInt(bigbagCapacity)) && bigbagCapacity != '')
					{
						$('#BookingAddInfo_bkg_num_large_bag_em_').html('The selected cab can accomodate ' + bigbagCapacity + ' big bags');
						$("#BookingAddInfo_bkg_num_large_bag_em_").css('display', 'block');
					}
					event.preventDefault();
				} else
				{
					$('.etcAmount').html(obj.totalAmount);
					$('.taxAmount').text(obj.servicetax).change();

					$('.bkgamtdetails111').html(obj.totalAmount - obj.walletAmount - obj.creditUsed);



					$('#max_amount').val(obj.dueAmount).change();
					if (obj.additionalAmount != '' && obj.additionalAmount != '0')
					{
						$(".additionalcharge").removeClass("hide");
						$('.extracharge').html('&#x20B9;' + obj.additionalAmount);
						//$('.extrachargeremark').text(obj.additionalAmountremarks).change();
					}
					//$('.additionalinfo').hide();
					$('#additiondetails').hide();
					$("#msg").removeClass("hide");
					$("#request_status").val('1');
					$('.additionalinfoadd').text();
					$(".additionalinfoadd").removeClass("hide");
					//$('.payBoxMinAmount').text(obj.minPay);
					var totalAmount = obj.dueAmount - obj.walletAmount - obj.creditUsed;
					var minAmount = obj.minPay;
					$('#BookingInvoice_partialPayment').attr('max', totalAmount);
					$('#BookingInvoice_partialPayment').attr('min', minAmount);
					$('.payBoxMinAmount').text(minAmount);
					//obj.minPay = minAmount;
					//alert(minAmount);
					$('.payBoxDueAmount').text(obj.dueAmount - obj.walletAmount - obj.creditUsed);
					$('.payBoxTotalAmount').text(obj.totalAmount);
					$.each($('input[name="payChk"]'), function (key, val)
					{
						if ($(val).is(':checked') == true)
						{
							if ($(val).attr('id') == 'minPayChk')
							{
								$('.payBoxBtnAmount').text($('.payBoxMinAmount').text());
								// $('#BookingInvoice_partialPayment').val(obj.minPay);
								$('#BookingInvoice_partialPayment').val(minAmount);

							} else
							{
								$('.payBoxBtnAmount').text($('.payBoxDueAmount').text());
								$('#BookingInvoice_partialPayment').val(totalAmount);
								$('#BookingInvoice_partialPayment').attr('max', totalAmount);
							}

						}
					});
					$(".additionalinfo").css("border", "1px solid blue");
					$('html, body').animate({scrollTop: 0}, 'slow');
				}
				return false;

			}

		});
	});
	function extraAdditionalInfo(infosource)
	{
		$("#source_desc_show").addClass('hide');
		if (infosource == '5')
		{
			$("#source_desc_show").removeClass('hide');
			$("#agent_show").addClass('hide');
			$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
		} else if (infosource == '6')
		{
			$("#source_desc_show").removeClass('hide');
			$("#agent_show").addClass('hide');
			$("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
		}
	}
	$('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function ()
	{
		if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
		{
			$("#othreq").show();
		} else
		{
			$("#othreq").hide();
		}
	});
	$('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').change(function ()
	{
		if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
		{
			$("#addmytrip").show();
		} else
		{
			$("#addmytrip").hide();
		}
	});

	$('#BookingAddInfo_bkg_spl_req_senior_citizen_trvl,#BookingAddInfo_bkg_spl_req_kids_trvl,#BookingAddInfo_bkg_spl_req_woman_trvl,#BookingAddInfo_bkg_spl_req_driver_english_speaking,#Booking_bkg_chk_others,#Booking_bkg_add_my_trip,#BookingAddInfo_bkg_user_trip_type_0,#BookingAddInfo_bkg_user_trip_type_1,#BookingAddInfo_bkg_info_source').click(function ()
	{
		$('#additiondetails').removeClass("hide");
	})

	$('#BookingAddInfo_bkg_no_person,#BookingAddInfo_bkg_num_large_bag,#BookingAddInfo_bkg_num_small_bag').focus(function ()
	{
		$('#additiondetails').removeClass("hide");
	});

	function viewDriverContact(booking_id)
	{
		$href = "<?= Yii::app()->createUrl('booking/viewCustomerDetails') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": booking_id, "type": 1},
			success: function (data)
			{
				var obj = $.parseJSON(data);
				if (obj.success == true)
				{
					$("#driverDetails").show();
					$("#viewDrvDetails").hide();
					$("#driverCabDetails").load(window.location.href + " #driverCabDetails");
					//window.location.reload();
				}
			}
		});
	}
</script>
