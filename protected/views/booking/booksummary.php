<?php
$isPromoApplicable	 = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
$minPayPercent		 = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id,$model->bkgPref->bkg_is_gozonow);
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

<style type="text/css">
    .trip_plan table { 
        width: 100%; 
        border-collapse: collapse;
        font-size: 13px;
    }
    /* Zebra striping */
    .trip_plan tr:nth-of-type(odd) { 
        background: #f1f1f1; 
    }
    .trip_plan th { 
        background: #333; 
        color: white; 
        font-weight: bold; 
    }
    .trip_plan td { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
    .trip_plan th { 
        padding: 6px; 
        border: 1px solid #ccc; 
        text-align: left; 
    }
    .border_box2{ background: #333333!important; color: #fff!important;}
    .border_box1{ background: #f1f1f1; color: #0858a0!important; border-bottom: #fff 1px solid;}
    .green-bg2{ background: #5cb85c; color: #fff;}
    @media (max-width: 767px)
    {

        /* Force table to not be like tables anymore */
        .trip_plan table, thead, tbody, th, td, tr { 
            display: block; 
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .trip_plan thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .trip_plan tr{ border: 1px solid #ccc; }

        .trip_plan td{ 
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #d5d5d5; 
            position: relative;
            padding-left: 50%; 
        }

        .trip_plan td:before { 
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            top: 6px;
            left: 6px;
            width: 45%; 
            padding-right: 10px; 
            white-space: nowrap;
        }

        /*
        Label the data
        */
        .trip_plan td:nth-of-type(1):before { content: "From"; }
        .trip_plan td:nth-of-type(2):before { content: "To"; }
        .trip_plan td:nth-of-type(3):before { content: "Departure Date"; }
        .trip_plan td:nth-of-type(4):before { content: "Time"; }
        .trip_plan td:nth-of-type(5):before { content: "Distance"; }
        .trip_plan td:nth-of-type(6):before { content: "Duration"; }
        .trip_plan td:nth-of-type(7):before { content: "Days"; }

    }

    /* Smartphones (portrait and landscape) ----------- */

	.proceed-make-btn{
		display: none;
	}
	.tr {
		display: flex;
	}
	.th, .td {
		border-top: 1px solid #ccc;
		border-right: 1px solid #ccc;
		padding: 4px 8px;
		flex: 1;
		font-size:14px;
		overflow:auto;
		word-wrap: break-word;
		max-width:305px;
	}
	.bigCol
	{
		max-width:65%;
	}
	.smallCol
	{
		max-width:15%;		
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
<?
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
//$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
//if ($response->getStatus())
//{
$contactNo	 = $model->bkgUserInfo->bkg_contact_no;
$countryCode = $model->bkgUserInfo->bkg_country_code;
$email		 = $model->bkgUserInfo->bkg_user_email;
//}
?>

<?
if ($isredirct)
{
if ($model->bkg_agent_id != '')
{
$row = '';
}
$routeCityList	 = $model->getTripCitiesListbyId();
$ct				 = implode(' &#10147; ', $routeCityList);
?>
<div class="<?= $row ?>">
	<div class="col-xs-12">
		<?php
		if ($model->bkg_agent_id != '')
		{
			/* @var $agentModel Agents */
			$agentModel		 = Agents::model()->findByPk($model->bkg_agent_id);
			$acceptPayment	 = $agentModel->agt_use_gateway;
			?>
			<div class="row">
				<div class="col-xs-6 text-left"><h1><?= $agentModel->agt_company ?></h1></div>
				<div class="col-xs-6 text-right mt5"><img src="<?= Yii::app()->baseUrl ?>/images/logo4.png"/></div>
			</div>
			<h4 class=" text-center mb20"><?= $this->pageTitle ?></h4>
			<? } ?>
			<?
			if ($model->bkgInvoice->bkg_advance_amount < 1 && ($model->bkg_agent_id != NULL || $model->bkg_agent_id != ''))
			{
			?>
			<div class=" text-center mb20 alert alert-danger"><?php
				if (in_array($model->bkg_booking_type, [9, 10, 11]))
				{
					?>RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast  <?php echo $minPayPercent; ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed by <?
					}
					else
					{
					?>RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast  <?php echo $minPayPercent; ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed by <?php } ?><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)); ?> , <?= date('h:i A', strtotime($model->bkg_pickup_date)); ?></div>
			<div class=" text-center mb20">
				<?= CHtml::radioButtonList('bkg_reconfirm_flag', $model->bkg_reconfirm_flag, array(1 => 'Yes, confirm this booking as soon as payment is received', 3 => 'No, I didnt request this booking. Please cancel it'), array('id' => "reconfirm", 'separator' => "    ", 'onclick' => "reconfirmUpdate()")); ?>
			</div>
			<?
			}
			else if ($model->bkgInvoice->bkg_advance_amount < 1 && $model->bkgTrail->bkg_platform == 2)
			{
			?>
			<div class=" text-center mb20 alert alert-danger"><?php
				if (in_array($model->bkg_booking_type, [9, 10, 11]))
				{
					?>RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast <?php echo $minPayPercent; ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed <?
					}
					else
					{
					?> RECONFIRMATION & PAYMENT NEEDED: Make payment of atleast <?php echo $minPayPercent; ?>% below to reconfirm booking. Booking will be auto-cancelled if not reconfirmed<?php } ?></div>
			<div class=" text-center mb20">
				<?= CHtml::radioButtonList('bkg_reconfirm_flag', $model->bkg_reconfirm_flag, array(1 => 'Yes, confirm this booking as soon as payment is received', 3 => 'No, I didnt request this booking.Please cancel it'), array('id' => "reconfirm", 'separator' => "    ", 'onclick' => "reconfirmUpdate()")); ?>
			</div>
			<? } ?>

			<?php
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

					<?
					if ($paymentdone)
					{
					if ($succ == 'success')
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
					}
					?>
				</div>
				<? } ?>
			</div>
		</div>

		<? } ?>


		<div class="mt20">
			<?php
			if (!empty($note))
			{
				?>
				<div class="row">
					<div class="col-xs-12" id="linkedusers"><div class="panel panel-primary panel-border compact">
							<div class="panel-heading heading_box" style="background:#0d47a1">Special instructions & advisories that may affect your planned travel</div>

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

								</div>
								<?php
								for ($i = 0; $i < count($note); $i++)
								{
									?>  
									<div class="tr" role="row">
										<div class="th smallCol" role="rowheader">
											<?php if ($note[$i]['dnt_area_type'] == 1)
											{
												?>
												<?= ($note[$i]['dnt_zone_name']) ?>
											<?php } ?>
											<?php if ($note[$i]['dnt_area_type'] == 3)
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

		<!----Start new design------->
		<div class="row">
			<div class="col-sm-6">
				<div class="row">
					<div class="col-xs-12 mb20">
						<div class="heading-part mb10"><b>Traveller Information</b></div>
						<div class="main_time border-blueline">
							<div class="row">
								<div class="col-xs-12 col-sm-6">
									Passenger Name: <span class="black-color"><?= $model->bkgUserInfo->getUsername() ?></span><br>
									Email: <span class="black-color">
		<?= $email; ?>
										<img src="<?= $isEmailVerified; ?>">
									</span><br>
									Phone: <span class="black-color">+<?= $countryCode ?><?= $contactNo ?>
										<img src="<?= $isPhoneVerified; ?>">
									</span><br>
								</div>
								<div class="col-xs-12 col-sm-6">
									Cab Type: <span class="black-color"><?= $model1->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . " (" . $model1->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ")-" . $model->bkgVehicleType->vht_make . ' ' . $model->bkgVehicleType->vht_model; ?></span><br>
									Trip Type: <span class="black-color"><?= ucwords($model->getBookingType($model->bkg_booking_type, 'Trip')) ?></span><br>
									Pickup Time: <span class="black-color"><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?>, <?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></span><br>
								</div>

							</div>
						</div>
					</div>

		<?php
		if ($model->bkg_booking_type == 5)
		{
			$packName	 = Package::getNamebyId($model->bkg_package_id);
			?>
						<div class="col-xs-12 mb20">
							<div class="heading-part ">Package: <strong><?= $packName ?></strong></div>
						</div>
						<? } ?>
						<div class="col-xs-12 mb20">
							<div class="heading-part mb10"><b>Your Trip Plan</b></div>
							<div class="main_time border-blueline p5">
								<div class="container-time pb15">
									<?php
									$last		 = 0;
									$tdays		 = 0;
									$cntBrt		 = count($model->bookingRoutes);
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

										<ul>
											<li><span></span>
												<div>
													<?
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
													<span class="timeing-box"><?= round($brt->brt_trip_duration / 60) . ' hours'; ?></span></span>
											</li>
											<?
											if ($k == ($cntBrt - 1))
											{
											?>
											<?
											$expArrivedate	 = date('Y-m-d H:i:s', strtotime($model->bookingRoutes[$cntBrt - 1]->brt_pickup_datetime . ' + ' . $model->bookingRoutes[$cntBrt - 1]->brt_trip_duration . ' MINUTE'));
											?>
											<li>
												<div><span></span>
													<?
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
											<? } ?>

										</ul>

										<? } ?>
									</div>
								</div>
							</div>
							<? if (($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') && $model->bkg_booking_type != 4)
							{ ?>
							<div class="col-xs-12 ">
								<div class="heading-part mb10"><b>UPDATE ADDRESSES</b></div>
								<div class="col-xs-12  main_time border-blueline  mb20">
									<div class="col-xs-12">
								<?= $this->renderPartial('autoAddressWidget', ['model' => $model], false, true); ?>
									</div>
								</div>
							</div>
							<input type="hidden" value="<? echo ($model->bkgFromCity->cty_garage_address == $model->bkg_pickup_address || $model->bkg_pickup_address == '' || $model->bkgToCity->cty_garage_address == $model->bkg_drop_address || $model->bkg_drop_address == '') ? '0' : '1' ?>" class="isPickupAdrsCls" name="isPickupAdrsCls">
							<? } ?>

							<div class="col-xs-12">
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
								$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
								/* @var $form TbActiveForm */
								?>
								<div class="row">
									<div class="col-xs-12">
										<div class="main_time border-blueline additionalinfo mb20">
											<div class="heading-part mb10 col-xs-12 col-sm-7">Special Requests</div>
											<div class="heading-part mb10 col-xs-12 col-sm-5" align="right"><b><button type="button" class="btn btn-effect-ripple btn-success" id="additiondetails" >Save Special Request</button></b><span id="msg" class="hide" style="font-weight: bold;color: #FF6700;font-size: 12px">SPECIAL REQUESTS SAVED.</span></div>
											<!--		<div class="main_time border-blueline">-->
											<div class="mb0 col-xs-12 col-sm-12">  
												<p><b>Please provide additional information to help us to serve you better.</b></p>
											</div>
				<?=
				$form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id4']);
				$form->hiddenField($model, 'hash', ['id' => 'hash4', 'class' => 'clsHash', 'value' => $hash]);
				?>
											<div id="error_div" style="display: none" class="alert alert-block alert-danger"></div>
											<div class="row">
												<div class="col-xs-12">
													<div class="row">
														<div class="col-xs-4"><?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_senior_citizen_trvl', ['label' => 'Senior citizen traveling', 'groupOptions' => ["class" => ""]]) ?></div>
														<div class="col-xs-4"><?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_kids_trvl', []) ?></div>
														<div class="col-xs-4"><?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_woman_trvl', []) ?></div>

													</div>
													<!--<div class="row">
														<div class="col-xs-3"><?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_woman_trvl', []) ?></div>
														<div class="col-xs-3">
				<?
				if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
				{
					echo $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_carrier', []);
				}
				?>
														</div>
													</div>-->
													<div class="row">
														<div class="col-xs-4"><?= $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_driver_english_speaking', ['label' => 'English-speaking driver required',]) ?></div>
														<div class="col-xs-4"><?= $form->checkboxGroup($model, 'bkg_chk_others', ['label' => 'Others']) ?></div>
														<div class="col-xs-4">
															<?
															if ($scvVctId != VehicleCategory::SEDAN_ECONOMIC)
															{
															echo $form->checkboxGroup($model->bkgAddInfo, 'bkg_spl_req_carrier', []);
															}
															?>
														</div>
													</div>
													<div class="row">
														<div class="col-xs-6"></div>
														<div class="col-xs-6"></div>
													</div>
													<div class="row">
														<div class="col-xs-12" id="othreq" style="display: <?= $otherExist ?>">
															<?= $form->textAreaGroup($model->bkgAddInfo, 'bkg_spl_req_other', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Other Requests"]))) ?>  
														</div>
													</div>
													<div class="row">
														<div class="col-xs-5">
				<?= $form->checkboxGroup($model, 'bkg_add_my_trip', ['label' => 'Add a journey break (?150/30mins)']) ?>
														</div>
														<div class="col-xs-7">
				<?= $form->dropDownListGroup($model->bkgAddInfo, 'bkg_spl_req_lunch_break_time', ['label' => '', 'widgetOptions' => ['data' => ['0' => 'Minutes', '30' => '30', '60' => '60', '90' => '90', '120' => '120', '150' => '150', '180' => '180'], 'htmlOptions' => ['id' => 'bkg_spl_req_lunch_break_time']]]) ?>
															<div id="addmytrip" class="font11">First 15min free. Unplanned journey breaks are not allowed for one-way trips</div>
														</div>
													</div> 
												</div>
											</div>
											<!--		</div>
												</div>
												<div class="  main_time border-blueline additionalinfo  mb20">-->
											<div class="heading-part mb10">Additional Details</div>
											<div class="special_request">
												<div class="row mb10">
													<div class="col-sm-12">
														<div class="row">
															<div class="form-group">
																<label for="inputEmail" class="control-label col-xs-12">Personal Or Business Trip?</label>
																<div class="col-xs-12 pl0">
																	<input type="hidden" id="request_status" value="">
				<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id']);
				?>
				<?=
				$form->radioButtonListGroup($model->bkgAddInfo, 'bkg_user_trip_type', array(
					'label'			 => '', 'widgetOptions'	 => array(
						'data'	 => Booking::model()->userTripList, 'class'	 => 'bkg_user_trip_type',
					), 'inline'		 => true,));
				?>
																</div>
															</div>
														</div>
													</div>
				<?php
				$readOnly				 = [];
				if (in_array($model->bkg_flexxi_type, [1, 2]))
				{
					$readOnly = ['readOnly' => 'readOnly'];
				}
				?>
													<div class="col-sm-4">
														<div class="row">
															<div class="form-group">
																<label for="inputEmail" class="control-label col-xs-12">Number of Passengers</label>
																<div class="col-xs-6">
				<?= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_no_person', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "0", 'min' => 1, 'max' => $bdata['vct_capacity']] + $readOnly), 'groupOptions' => [])) ?>                      
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="row">
															<div class="form-group">
																<label for="inputEmail" class="control-label col-xs-12"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_large_bag') ?></label>
																<div class="col-xs-6">
																	<? //= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_large_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "large suitcases", 'min' => 0, 'max' => $bdata['vct_big_bag_capacity']] + $readOnly), 'groupOptions' => []))  ?>                      
																		<?php
																		$vct_Id		 = $model->bkgSvcClassVhcCat->scv_vct_id;
																		$scc_Id		 = $model->bkgSvcClassVhcCat->scv_scc_id;
																		$sbagRecord	 = VehicleCatSvcClass::smallbagBycategoryClass($model->bkgSvcClassVhcCat->scv_vct_id, $model->bkgSvcClassVhcCat->scv_scc_id);
																		$lbag		 = floor($sbagRecord['vcsc_small_bag'] / 2);
																		?>
																	<select class="form-control" id="BookingAddInfo_bkg_num_large_bag" name="BookingAddInfo[bkg_num_large_bag]" onchange="luggage_info(this.value,<?php echo $vct_Id ?>,<?php echo $scc_Id ?>,<?php echo $sbagRecord['vcsc_small_bag'] ?>);">
				<?php for ($i = 0; $i <= $lbag; $i++)
				{
					?>
																			<option value="<?php echo $i ?>"><?php echo $i ?></option>
				<?php } ?>		
																	</select>													

																</div>
															</div>
														</div>
													</div>


													<div class="col-sm-4">
														<div class="row">
															<div class="form-group">
																<label for="inputEmail" class="control-label col-xs-12"><?= $model->bkgAddInfo->getAttributeLabel('bkg_num_small_bag') ?></label>
																<div class="col-xs-6">
																	<? //= $form->numberFieldGroup($model->bkgAddInfo, 'bkg_num_small_bag', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "small bags", 'min' => 0, 'max' => $bdata['vct_small_bag_capacity']] + $readOnly), 'groupOptions' => []))  ?>                      


																	<select class="form-control" id="BookingAddInfo_bkg_num_small_bag" name="BookingAddInfo[bkg_num_small_bag]">
				<?php for ($i = 1; $i <= $sbagRecord['vcsc_small_bag']; $i++)
				{
					?>
																			<option value="<?php echo $i ?>"><?php echo $i ?></option>
				<?php } ?>		
																	</select>														
																</div>								
															</div>	
														</div>
													</div>
												</div>
												<div class="row">

													<!--<div class="col-sm-4">
														<div class="row">
															<div class="form-group">
																<label for="inputEmail" class="control-label col-xs-12">How did you hear about Gozo cabs? </label>

																<div class="col-xs-12">
													<?php
													/* 	$datainfo		 = VehicleTypes::model()->getJSON($infosource);
													  $this->widget('booster.widgets.TbSelect2', array(
													  'model'			 => $model->bkgAddInfo,
													  'attribute'		 => 'bkg_info_source',
													  'val'			 => "'" . $model->bkgAddInfo->bkg_info_source . "'",
													  'asDropDownList' => FALSE,
													  'options'		 => array('data' => new CJavaScriptExpression($datainfo)),
													  'htmlOptions'	 => array('style' => 'width:100%;margin-bottom:10px', 'placeholder' => 'Select Infosource ')
													  )); */
													?>
																</div>
															</div>
														</div>
													</div> -->
													<? $sourceDescShow	 = ($model->bkgAddInfo->bkg_info_source == 'Friend' || $model->bkgAddInfo->bkg_info_source == 'Other Media') ? '' : 'hide'; ?>
													<div class="col-sm-4">
														<div class="row">
															<div class="form-group <?= $sourceDescShow ?> " id="source_desc_show">
																<label for="inputEmail" class="control-label">&nbsp;</label>
																<div class="col-xs-12 mt20">
				<?= $form->textFieldGroup($model->bkgAddInfo, 'bkg_info_source_desc', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => ""]),)) ?>                      
																</div>
															</div>
														</div>
													</div>
												</div>
												<h3 class="hide">&nbsp;<br>Journey Details: </h3>
												<?
												$j				 = 0;
												$cntRt			 = sizeof($model->bookingRoutes);
												foreach ($model->bookingRoutes as $key => $brtRoute)
												{
												if ($j == 0)
												{
												?>       
												<div class="row hide">
													<div class = "form-group mb15">
														<label for="pickup_address" class="control-label col-xs-12 col-sm-5 pt10">Pickup Pincode for <?= $brtRoute->brtFromCity->cty_name ?></label>
														<div class="col-xs-12 col-sm-7">
				<?= $form->numberFieldGroup($brtRoute, "brt_from_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]), 'groupOptions' => ['class' => ''])) ?>
														</div>
													</div>
												</div>
												<?
												}
												$j++;
												$opt = (($key + 1) == $cntRt) ? 'Required' : 'Optional';
												?>
												<div class = "row hide">
													<div class = "form-group mb15">
														<label for="pickup_address" class="control-label col-xs-12 col-sm-5 pt10">Drop Pincode for <?= $brtRoute->brtToCity->cty_name ?></label>
														<div class="col-xs-12 col-sm-7">
				<?= $form->numberFieldGroup($brtRoute, "brt_to_pincode", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Pincode (Optional)"]))) ?>
														</div>
													</div>
												</div>
												<?
												}
												?>
											</div>
											<!--<button type="button" class="btn btn-primary btn-lg pl40 pr40 proceed-new-btn mt0" id ="additiondetails">Save</button>-->

										</div>					
									</div>
								</div>
				<?php
//$vehicletype = VehicleTypes::model()->findByPk($model->bkgAddInfo->baddInfoBkg->bkg_vehicle_type_id);
				$capacity			 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_capacity;
				$bagCapacity		 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_small_bag_capacity;
				$bigBagCapacity		 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_big_bag_capacity;
				?>
												<?php $this->endWidget(); ?>
							</div> 

							<div class="col-xs-12 mb20">
								<div class="heading-part mb10"><b>You have booked a <?= $model1->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label ?> <img src="/images/icon/<?= $model1->bkgSvcClassVhcCat->scc_ServiceClass->scc_label ?>.png"> car</b></div>
								<div class="main_time border-blueline">
									<div class="row">
										<div class="col-xs-12">
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

						</div>
					</div>
					<div class="col-sm-6">
						<div>	
							<div class="mt20 mb5"><h3 class="text-warning">Get Instant updates about this trip. Download our app now!</h3></div>
							<div class="mb20">
								<a href="https://play.google.com/store/apps/details?id=com.gozocabs.client" target="_blank"><img src="/images/GooglePlay.png?v1.1" alt="Gozocabs APP"></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img src="/images/app_store.png?v1.2" alt="Gozocabs APP"></a>
							</div>
						</div>
							<?php
							if ($model->bkgPref->bkg_trip_otp_required == 1)
							{
								?>
							<div class="text-center bg-danger p10 mb10 ">
								<b>Please use OTP: <?= $model->bkgTrack->bkg_trip_otp ?> at the time of pickup. Don't share OTP before boarding the cab.</b>
							</div>12
						<div id ="driverCabDetails">
							<div class="heading-part mb10">Driver & Car Details</div>
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
											<br><span><font color="red">** Free cancellation period ends (Cancellation charges will apply) as soon as you view driver details</font></span>
										</div>
									</div>
								<?php
								}
								else
								{
								?>
									<div id="driverDetails">
										<div class="main_time border-blueline" style="background: #0d47a1;padding-bottom:5px;"> 
											<div class="row">
												<div class="col-xs-12 col-sm-12">
													<b class="white-color">Driver Name:</b>
													<span class="white-color">
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
													<b class="white-color">Driver Phone:</b>
													<span class="white-color"> 
														<?php
														if ($model->bkgBcb->bcb_driver_id != '' && $model->bkgBcb->bcb_driver_id != NULL)
														{
															$drvContactId	 = $model->bkgBcb->bcbDriver->drv_contact_id;
															$driver_phone	 = $model->bkgBcb->bcbDriver->drvContact->getContactDetails($drvContactId); //Yii::app()->params['customerToDriver'];
															$phone_no        =  '+' . $driver_phone['phn_phone_country_code'] . $driver_phone['phn_phone_no'];
							                                $driver_phone_1	 = BookingPref::getDriverNumber($model, $phone_no);
															?>
															<?php		
																echo $phone_no;
															?>
															<?php
														}
														else
														{
															echo "To be assigned.";
														}
														?>
													</span><br>

													<b class="white-color">Car License Plate: </b>
													<span class="white-color"><? //= $model->bkgUserInfo->bkg_country_code           ?>
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
													<b class="white-color">Make:</b> 
													<span class="white-color"><? //= $model->bkgUserInfo->bkg_country_code            ?>
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

													<b class="white-color">Model: </b>
													<span class="white-color"><? //= $model->bkgUserInfo->bkg_country_code            ?>
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
													<span><font color="red">** Cancellation charges will now apply if booking is cancelled</font></span>

												</div>	

											</div>
											<br>

										</div>

									</div>
								<?php
								}
				}
				else
				{
				?>
								<div class="main_time border-blueline" style="background: #0d47a1;padding-bottom:5px;"> 
									<div class="row">
										<div class="col-xs-12 col-sm-12">
											<b class="white-color">Driver Name:</b>
											<span class="white-color">
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
											<span class="white-color">Driver Phone: 
												<?php
												if ($model->bkgBcb->bcb_driver_id != '' && $model->bkgBcb->bcb_driver_id != NULL)
												{
													$drvContactId	 = $model->bkgBcb->bcbDriver->drv_contact_id;
													//$driver_phone	 = Yii::app()->params['customerToDriver'];
													$driver_phone	 = BookingPref::getDriverNumber($model, $model->bkgBcb->bcb_driver_phone);
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

											<b class="white-color">Car License Plate: </b>
											<span class="white-color"><? //= $model->bkgUserInfo->bkg_country_code           ?>
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
											<div class="col-sm-6" style="padding-left:1px;">
												<b class="white-color">Make:</b> <span class="white-color"><? //= $model->bkgUserInfo->bkg_country_code            ?>
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
											<div class="col-sm-6">
												<b class="white-color">Model: </b><span class="white-color"><? //= $model->bkgUserInfo->bkg_country_code            ?>
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
										</div>	
									</div>
								</div>
				<?php }
				?>
						</div>

					</div>

				<?php
				$dboApplicable = Filter::dboApplicable($model);
				if ($dboApplicable)
				{
					?>
						<div class="col-sm-6">
							<div class="col-xs-12 ">
								<div class="main_time1 mb30">
									<div class="row">
										<a href="/terms/doubleback" target="_blank"><img src="/images/dbo_banner.jpg" alt="" width="550" style="margin: 20px;"></a>
									</div>
								</div>
							</div>
						</div>
							<?php } ?>
					<div class="col-sm-6"></div>
					<div class="col-sm-6">

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
							<?
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
							<?
							}
							}
							?>
							<?
							if (($model->bkgUserInfo->bkg_phone_verified == 0 && $model->bkgUserInfo->bkg_email_verified == 0) && ($model->bkg_agent_id == '' || $model->bkgTrail->bkg_platform == 5 || ($model->bkg_agent_id != '' && $model->bkgTrail->bkg_platform == 4 && $model->bkgInvoice->bkg_corporate_remunerator != 2)))
							{
							?>
							<div class="row">
								<div class="panel-body">
									<?php
									$form										 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
							<? } ?>
						</div>
					</div>

				</div>

				<div class="row text-justify">
					<div class="col-xs-12">
						<?php
						if (!$isMobile)
						{
							$this->renderPartial("paywidget", ["model" => $model1, 'isredirct' => $isredirct], false);
						}
						?>
					</div>
					<div class="col-xs-12">
				<?php
				$this->renderPartial("bookingInfo", ["bookingtype"		 => $model->bkg_booking_type,
					'bkg_trip_distance'	 => $model->bkg_trip_distance, 'cab_type_id'		 => $model->bkg_vehicle_type_id, 'note'				 => $note], false);
				?>
					</div>
				</div>
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
				                    }
				                });
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



				    $(document).ready(function () {
				        $("#BookingAddInfo_bkg_info_source").change(function () {
				            var infosource = $("#BookingAddInfo_bkg_info_source").val();
				            extraAdditionalInfo(infosource);
				        });

				        if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
				        {
				            $('addmytrip').attr('disabled', '');
				        }
				    });

				    $(window).ready(function () {

				        $('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').attr('disabled', '');
				        $('#bkg_spl_req_lunch_break_time').attr('disabled', true);
				        $('#BookingAddInfo_bkg_spl_req_carrier').attr('disabled', true);
				    });

				    $('#additiondetails').click(function (event) {
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
				        } else {
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
				        if (noPassenger <= 0) {
				            alert('Please Enter number of Passenger');
				            return false;
				        } else if (noLargeBag < 0) {
				            alert('Please Enter number of large bag you want to take');
				            return false;
				        } else if (noSmallBag < 0) {
				            alert('Please Enter number of small bag you want to take');
				            return false;
				        }
				        if ((infosource == '5') || (infosource == '6')) {
				            var infosourcedesc = $('#BookingAddInfo_bkg_info_source_desc').val();
				        }
				        if (seniorCitizen == true)
				        {
				            seniorCitizen = 1;
				        } else {
				            seniorCitizen = 0;
				        }

				        if (kidsTravel == true)
				        {
				            kidsTravel = 1;
				        } else {
				            kidsTravel = 0;
				        }

				        if (womanTravel == true)
				        {
				            womanTravel = 1;
				        } else {
				            womanTravel = 0;
				        }

				        if (carrierReq == true)
				        {
				            carrierReq = 1;
				        } else {
				            carrierReq = 0;
				        }

				        if (engSepeakingDriver == true)
				        {
				            engSepeakingDriver = 1;
				        } else {
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
				                if (obj.success != true) {
				                    if ((parseInt(noPerson) > parseInt(vhCapacity)) && vhCapacity != '')
				                    {
				                        $('#BookingAddInfo_bkg_no_person_em_').html('Your selected cab can accomodate ' + vhCapacity + ' passengers');
				                        $("#BookingAddInfo_bkg_no_person_em_").css('display', 'block');
				                    }
				//                    if ((parseInt(smallBag) > parseInt(smallbagCapacity)) && smallbagCapacity != '')
				//                    {
				//                        $('#BookingAddInfo_bkg_num_small_bag_em_').html('The selected cab can accomodate ' + smallbagCapacity + ' small bags');
				//                        $("#BookingAddInfo_bkg_num_small_bag_em_").css('display', 'block');
				//                    }
				//                    if ((parseInt(bigBag) > parseInt(bigbagCapacity)) && bigbagCapacity != '')
				//                    {
				//                        $('#BookingAddInfo_bkg_num_large_bag_em_').html('The selected cab can accomodate ' + bigbagCapacity + ' big bags');
				//                        $("#BookingAddInfo_bkg_num_large_bag_em_").css('display', 'block');
				//                    }
				                    event.preventDefault();
				                } else
				                {
				                    $('.etcAmount').html(obj.totalAmount);
				                    $('.taxAmount').text(obj.servicetax).change();

				                    $('.bkgamtdetails111').html(obj.totalAmount - obj.walletAmount - obj.creditUsed);



				                    $('#max_amount').val(obj.dueAmount).change();
				                    if (obj.additionalAmount != '' && obj.additionalAmount != '0') {
				                        $(".additionalcharge").removeClass("hide");
				                        $('.extracharge').html('<i class="fa fa-inr"></i>' + obj.additionalAmount);
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
				<?php
				if (in_array($model->bkg_booking_type, [9, 10, 11]))
				{
					?>
					                    var minAmount = Math.round((totalAmount * 50) / 100);
					<?php
				}
				else
				{
					?>
					                    var minAmount = Math.round((totalAmount * 15) / 100);
				<?php } ?>
				                    $('#BookingInvoice_partialPayment').attr('max', totalAmount);
				                    $('#BookingInvoice_partialPayment').attr('min', minAmount);
				                    $('.payBoxMinAmount').text(minAmount);
				                    //obj.minPay = minAmount;
				                    //alert(minAmount);
				                    $('.payBoxDueAmount').text(obj.dueAmount - obj.walletAmount - obj.creditUsed);
				                    $('.payBoxTotalAmount').text(obj.totalAmount);
				                    $.each($('input[name="payChk"]'), function (key, val) {
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
				        if (infosource == '5') {
				            $("#source_desc_show").removeClass('hide');
				            $("#agent_show").addClass('hide');
				            $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "Friend's email please");
				        } else if (infosource == '6') {
				            $("#source_desc_show").removeClass('hide');
				            $("#agent_show").addClass('hide');
				            $("#BookingAddInfo_bkg_info_source_desc").attr('placeholder', "");
				        }
				    }
				    $('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').change(function () {
				        if ($('#<?= CHtml::activeId($model, "bkg_chk_others") ?>').is(':checked'))
				        {
				            $("#othreq").show();
				        } else {
				            $("#othreq").hide();
				        }
				    });
				    $('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').change(function () {
				        if ($('#<?= CHtml::activeId($model, "bkg_add_my_trip") ?>').is(':checked'))
				        {
				            $("#addmytrip").show();
				        } else {
				            $("#addmytrip").hide();
				        }
				    });

				    $('#BookingAddInfo_bkg_spl_req_senior_citizen_trvl,#BookingAddInfo_bkg_spl_req_kids_trvl,#BookingAddInfo_bkg_spl_req_woman_trvl,#BookingAddInfo_bkg_spl_req_driver_english_speaking,#Booking_bkg_chk_others,#Booking_bkg_add_my_trip,#BookingAddInfo_bkg_user_trip_type_0,#BookingAddInfo_bkg_user_trip_type_1,#BookingAddInfo_bkg_info_source').click(function () {
				        $('#additiondetails').removeClass("hide");
				    })

				    $('#BookingAddInfo_bkg_no_person,#BookingAddInfo_bkg_num_large_bag,#BookingAddInfo_bkg_num_small_bag').focus(function () {
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
