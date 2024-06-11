<style>
	.badge-danger {
		color: #FFFFFF;
		background-color: #FF5B5C;
	}
</style>

<?php
/** @var Booking $model */
$isPromoApplicable	 = BookingSub::model()->getApplicable($model->bkg_from_city_id, $model->bkg_to_city_id, 1);
$scvMapModel		 = SvcClassVhcCat:: getVctSvcList("object", 0, 0, $model->bkg_vehicle_type_id);

$tncType = TncPoints::getTncIdsByStep(11);
$tncArr	 = TncPoints::getTypeContent($tncType);

$ratingModel	 = Ratings::model()->getRatingbyBookingId($model->bkg_id);
$minDiff		 = $model->getPaymentExpiryTimeinMinutes();
$lastPaymentInfo = PaymentGateway::getLastPaymentStatus($model->bkg_id);
$paymentIssueCBR = false;
if($lastPaymentInfo && $minDiff != 0)
{
	$cntSuccess	 = $lastPaymentInfo['cntSuccess'];
	$cntFailed	 = $lastPaymentInfo['cntFailed'];
	$lastStatus	 = $lastPaymentInfo['lastStatus'];
	if($cntSuccess == 0 && $lastStatus <> 1 && in_array($model->bkg_status, [1, 15]))
	{
		$paymentIssueCBR = true;
	}
}

if($model->bkgInvoice->bkg_extra_discount_amount == 0 && $model->bkg_status == 15 && $isPromoApplicable)
{
	$isPromoApplicable = true;
}
else
{
	$isPromoApplicable = false;
}
if($model->bkgInvoice->bkg_discount_amount > 0 && $model->bkgTrail->bkg_platform == 2)
{
	$isPromoApplicable = false;
}
if(($model->bkg_booking_type == 1 && $model->bkg_flexxi_type == 2) || $model->bkg_cav_id > 0)
{
	$isPromoApplicable = false;
}
if($model->bkgInvoice->bkg_addon_details != "")
{
	$addonDetails	 = json_decode($model->bkgInvoice->bkg_addon_details, true);
	$key			 = array_search(1, array_column($addonDetails, 'adn_type'));
	if($addonDetails[$key]['adn_value'] < 0 && $addonDetails[$key]['adn_value'] != null)
	{
		$isPromoApplicable = false;
	}
}
$minPerc = Config::getMinAdvancePercent($model->bkg_agent_id, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $model->bkgPref->bkg_is_gozonow);
if($model->bkg_cav_id != NULL && $model->bkg_cav_id > 0)
{
	$minPerc = 50;
}
$minamount	 = Filter::moneyFormatter(round($minPerc * $model->bkgInvoice->bkg_total_amount * 0.01));
$payButton	 = "Proceed to Pay<br>";
$payButton	 .= '<small style="text-transform: none; font-size: 0.95em">(Mininum <span class="payBoxBtnAmount">' . $minamount . '</span>)</small>';
$payAmount	 = "Net Payable";
if(in_array($model->bkg_status, [2, 3, 4, 5]))
{
	$payButton	 = "Make payment";
	$payAmount	 = "Amount due";
}

if($model->bkg_agent_id > 0)
{
	$model->bkgInvoice->bkg_promo1_id	 = 0;
	$isPromoApplicable					 = false;
}
$pModel		 = Promos::model()->findByPk($model->bkgInvoice->bkg_promo1_id);
$model->bkgInvoice->calculateTotal();
$reviewText	 = Booking::getPayMessageByStatus($model->bkg_id, $model->bkg_status, $model->bkg_reconfirm_flag, $model->bkgPref->bkg_is_gozonow);
if($isRescheduled == 1)
{
	$prevBkgId			 = Booking::model()->getCodeById($model->bkgPref->bpr_rescheduled_from);
	$reviewText['text']	 = "Booking Rescheduled!";
	$paymentLink		 = Yii::app()->createAbsoluteUrl('bkpn/' . $model->bkgPref->bpr_rescheduled_from . '/' . Yii::app()->shortHash->hash($model->bkgPref->bpr_rescheduled_from));
	$reviewText['text1'] = "(rescheduled previous booking id: <a href='$paymentLink' target='_blank' >" . Filter::formatBookingId($prevBkgId) . "</a>)";
}
if($isRescheduled == 2)
{
	$reviewText['text']	 = "Reschedule Failed!";
	$reviewText['text1'] = "Payment reverted to wallet, as ride already started, failed to reschedule";
}
if($isRescheduled == 3)
{
	$prevBkgId			 = Booking::model()->getCodeById($model->bkgPref->bpr_rescheduled_from);
	$reviewText['text']	 = "Quote Created!";
	$paymentLink		 = Yii::app()->createAbsoluteUrl('bkpn/' . $model->bkgPref->bpr_rescheduled_from . '/' . Yii::app()->shortHash->hash($model->bkgPref->bpr_rescheduled_from));
	$reviewText['text1'] = "Reschedule failed! (previuous booking id: <a href='$paymentLink'  target='_blank'>" . Filter::formatBookingId($prevBkgId) . "</a>)";
}

if($isRescheduled == 0)
{
	$reviewText['text1'] = "";
	$prevModel			 = Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
	$paymentLink		 = Yii::app()->createAbsoluteUrl('bkpn/' . $model->bkgPref->bpr_rescheduled_from . '/' . Yii::app()->shortHash->hash($model->bkgPref->bpr_rescheduled_from));
	$reviewText['text1'] = ($model->bkgPref->bpr_rescheduled_from > 0 && $prevModel->bkg_status == 9) ? "(rescheduled previous booking id: <a href='$paymentLink'  target='_blank'>" . Filter::formatBookingId(Booking::model()->getCodeById($model->bkgPref->bpr_rescheduled_from)) . "</a>)" : "";
	if($reviewText['text1'] == "")
	{
		$bkgPrefModel = BookingPref::model()->findBySql("SELECT bpr_bkg_id FROM booking_pref WHERE bpr_rescheduled_from = {$model->bkg_id}");
		if($bkgPrefModel != '')
		{
			$paymentLink		 = Yii::app()->createAbsoluteUrl('bkpn/' . $bkgPrefModel->bpr_bkg_id . '/' . Yii::app()->shortHash->hash($bkgPrefModel->bpr_bkg_id));
			$reviewText['text1'] = "(rescheduled new booking id: <a href='$paymentLink'  target='_blank'>" . Filter::formatBookingId(Booking::model()->getCodeById($bkgPrefModel->bpr_bkg_id)) . "</a>)";
		}
	}
}


$urlRulesKey = $model->bkg_booking_type;
$urlRules	 = Booking::model()->bkgUrl[$urlRulesKey];
if($model->bkg_booking_type == 4)
{
	$urlRulesKey = 4;
	$urlRules	 = Booking::model()->bkgUrl[$urlRulesKey][$model->bkg_transfer_type];
}
//if ($model->bkg_booking_type == 12)
//{
//	
//$urlRulesKey = 4;
//	$isAirportfrom_city	 = Cities::model()->getDetailsByCityId($model->bookingRoutes[0]->brt_from_city_id);
//
//	if ($isAirportfrom_city['cty_is_airport'] == 1)
//	{
//		$urlRules = Booking::model()->bkgUrl[$urlRulesKey][2];
//	}
//	else
//	{
//		$urlRules = Booking::model()->bkgUrl[$urlRulesKey][1];
//	}
//}
?>
<script>
	var pageInitialized = false;
	var huiObj = null;
	var prmObj = null;
	$(document).ready(function ()
	{

		let payUrl = '<?= Yii::app()->createAbsoluteUrl('bkpn/' . $model->bkg_id . '/' . $hash); ?>';

		let currentUrl = window.location.href;
		let currentPath = window.location.pathname;

		var drvdetailsviewed = '<?= $model->bkgTrack->btk_drv_details_viewed ?>';

		if (currentUrl != payUrl)
		{
			trackPage(currentPath);
		}

		history.pushState(null, null, payUrl);

		var tncval = JSON.parse('<?= $tncArr ?>');
		$('.summarycontent').html(tncval[87]);
		$('.round-2').removeClass('hide');

		if (pageInitialized)
			return;
		pageInitialized = true;

		huiObj = new HandleUI();
		huiObj.bkgId = '<?= $model->bkg_id ?>';
		showSpecialDetails();
<?php
if($pModel->prm_code != '')
{
	?>
			huiObj.additionalParams.code = '<?= $pModel->prm_code ?>';
	<?php
}
if($model->bkgInvoice->bkg_temp_credits > 0)
{
	?>
			huiObj.additionalParams.coins = '<?= $model->bkgInvoice->bkg_temp_credits ?>';
<?php }
?>
		prmObj = new Promotion(huiObj);
<?php
if($pModel->prm_code != '')
{
	?>
			prmObj.applyParams(1, '<?= $pModel->prm_code ?>');
	<?php
}
?>
<?php
if($isPromoApplicable)
{
	?>
			ajaxindicatorstart("");

	<?php
	if($model->bkgInvoice->bkg_promo1_id > 0 || $pModel != false)
	{
		?>
				prmObj.applyPromo(1, '<?= $pModel->prm_code; ?>');
		<?php
	}
	if($model->bkgInvoice->bkg_temp_credits > 0)
	{
		?>
				prmObj.applyPromo(0, '<?= $model->bkgInvoice->bkg_temp_credits; ?>');
		<?php
	}
	if($model->bkgInvoice->bkg_promo1_id == 0 && $model->bkgTrail->bkg_create_user_type != 4)
	{
		?>
				getPromoById();
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
<?php
if($isPromoApplicable)
{
	?>
			ajaxindicatorstop();
<?php } ?>
		var additionalTime = '<?= $model->bkgAddInfo->bkg_spl_req_lunch_break_time ?>';
		if (additionalTime != 0)
		{
			$('#BookingAddInfo_bkg_spl_req_lunch_break_time').attr('disabled', true);
		}
		$('.coupondiscount').text('Promo/Gozo coins');
		var status = '<?= $model->bkg_status ?>';
		if (status == 2)
		{
			$('#accordion14').addClass('show');
		}
		var passenger = '<?= $model->bkgAddInfo->bkg_no_person ?>';
		if (passenger > 0)
		{
			$('#accordion15').removeClass('show');
			//$('.moreDetails').removeClass('btn btn-light-success');
		} else
		{
			$('#accordion15').addClass('show');
			//$('.moreDetails').addClass('btn btn-light-success');
		}

		if (drvdetailsviewed != 0)
		{
			$('#accordion13').addClass('show');
			$('.drvcontact').attr('aria-expanded', true);
		} else
		{
			$('#accordion13').removeClass('show');
		}

		var ridestart = '<?= $model->bkgTrack->bkg_ride_start ?>';
		var ridecomplete = '<?= $model->bkgTrack->bkg_ride_complete ?>';

		if (ridestart == 1 && status != 6)
		{
			$('#accordion18').addClass('show');
			$('.triptracking').addClass('bg-green color-white');
			$('.triptracking').attr('aria-expanded', true);
		} else if (status == 6 || status == 7)
		{
			$('#accordion18').removeClass('show');
			$('.triptracking').removeClass('bg-green');
			$('#accordion19').addClass('show');
		} else if (status == 15)
		{
			$('.reviewcontent').removeClass('hide');
		} else if (status != 6 || status != 7)
		{
			$('#accordion16').addClass('show');
		}

		var agentId = '<?= $model->bkg_agent_id ?>';
		var configAgentId = '<?= Config::get('Kayak.partner.id') ?>';
		if (agentId == configAgentId)
		{
			$('.requestcall').addClass('hide');
		} else
		{
			$('.requestcall').removeClass('hide');
		}


		getLocation();
	});



</script>
<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/v3/promo.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/v3/promotion.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/v3/handleUI.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/v3/addon.js?v=' . $version);
?>
<?php
$bhash	 = Yii::app()->shortHash->hash($model->bkg_id);

if($model->bkg_agent_id == Config::get('Kayak.partner.id') || $model->bkg_agent_id == Config::get('Mobisign.partner.id'))
{
	$actionclass = 'hide';
	if($model->bkg_agent_id == Config::get('Kayak.partner.id') && in_array($model->bkg_status, [2, 3, 5]))
	{
		$actionclass = '';
	}
}
?>

<div class="container mb-2 cntPayment" >
	<div class="row">
		<?
		if($paymentIssueCBR)
		{
			?>
			<div class="col-12 text-center container  " id="cbrbtnPayIssue"  > 
				Facing payment issue&quest;
				<a type="button" class="badge badge-pill badge-danger pt10 pb10" 
				   onclick="return reqPaymentFailedCBR()" ><img src="/images/bxs-phone.png" alt="" width="12" height="12"  ><b> Request a call back</b></a> 
			</div>
		<? } ?>
		<div class="col-12 col-lg-10 offset-lg-1 text-right actionmenu <?= $actionclass ?>" style="z-index: 999;">
			<a class="dropdown-toggle nav-link dropdown-user-link btn btn-outline-primary btn-sm font-12 pl10 pr10 inline-block" href="#" data-toggle="dropdown">Action</a>
			<div class="dropdown-menu dropdown-menu-right pb-0">
				<a class="dropdown-item" href="javascript:void(0);" onclick="travellerInfo('<?php echo $model->bkg_status; ?>')"><img src="/images/bx-user-check.svg" alt="" width="16" height="16" class="mr10"> Change Traveller Info</a>
				<?php if($model->bkgUserInfo->bkg_user_id == Yii::app()->user->id && $model->bkgUserInfo->bkg_user_id > 0)
				{ ?>
					<a class="dropdown-item" id="reschedule" href="#"  onclick="reschedulePopup(<?php echo $model->bkg_id; ?>,<?php echo $model->bkg_status; ?>)" title="Reschedule Booking"><img src="/images/bx-calendar-edit.svg" alt="" width="16" height="16" class="mr10">Reschedule Booking</a>
					<? } ?>
				<span class="cancelbooking">
					<?php
					if(!in_array($model->bkg_status, [9, 10]) && $model->bkg_agent_id != Config::get('Mobisign.partner.id') && $model->bkg_agent_id != Config::get('Kayak.partner.id'))
					{
						?>		
						<a class="dropdown-item" href="javascript:void(0);" onclick="checkTripStatus('<?php echo $model->bkg_status; ?>')"><img src="/images/bx-x-circle.svg" alt="" width="16" height="16" class="mr10"> Cancel booking</a>
						<?php
					}
					if($model->bkg_agent_id == Config::get('Kayak.partner.id') && in_array($model->bkg_status, [2, 3, 5]) && Yii::app()->user->isGuest)
					{
						?>  
						<a class="dropdown-item" href="javascript:void(0);" onclick="partnerCanBooking(<?php echo $model->bkg_id ?>)"><img src="/images/bx-x-circle.svg" alt="" width="16" height="16" class="mr10"> Cancel booking</a>

<?php } ?>
				</span>
				<a class="dropdown-item" href="/users/refer"><img src="/images/bxs-refer.svg" alt="" width="16" height="16" class="mr10"> Refer a friend</a>
				<?php
				$uniqueid	 = Booking::model()->generateLinkUniqueid($model->bkg_id);
				$link		 = Yii::app()->createAbsoluteUrl('/' . '/r/' . $uniqueid);
				?>
				<a class="dropdown-item" href="javascript:void(0);" onclick="reviewTab('<?php echo $model->bkg_status; ?>')"><img src="/images/bxs-star-half3.svg" alt="" width="16" height="16" class="mr10"> Reviews</a>
				<a class="dropdown-item" href="javascript:void(0);" onclick="receipt('<?php echo $model->bkg_id ?>', '<?php echo Yii::app()->shortHash->hash($model->bkg_id) ?>', '<?php echo $model->bkg_status; ?>')"><img src="/images/bx-spreadsheeta.svg" alt="" width="16" height="16" class="mr10"> Download Invoice</a>
				<?php
				$bookAgain	 = "Book Return Trip";
				if($model->bkg_booking_type == 1)
				{
					$bookReturnTripUrl = $this->getOneWayUrl($model->bkg_to_city_id, $model->bkg_from_city_id);
				}
				if(in_array($model->bkg_booking_type, [4, 12]))
				{
					if($model->bkg_transfer_type == 1)
					{
						$transferType	 = 'book-cab/airport-drop';
						$cityAliasName	 = Cities::getAliasPath($model->bkg_from_city_id);
					}
					else
					{
						$transferType	 = 'book-cab/airport-pickup';
						$cityAliasName	 = Cities::getAliasPath($model->bkg_to_city_id);
					}
					//$transferType = ($model->bkg_transfer_type == 1)? 'book-cab/airport-drop': 'book-cab/airport-pickup';

					$bookReturnTripUrl = Yii::app()->baseUrl . '/' . $transferType . '/' . $cityAliasName;
				}

				if(in_array($model->bkg_booking_type, [2, 3, 9, 10, 11]))
				{
					$bookAgain = "Book Again";
					if(in_array($model->bkg_booking_type, [2, 3]))
					{
						$bookReturnTripUrl = Yii::app()->createUrl("booking/itinerary/bkgType/" . $model->bkg_booking_type, array("bid" => $model->bkg_id));
					}
					if(in_array($model->bkg_booking_type, [9, 10, 11]))
					{
						$cityAliasName		 = Cities::getAliasPath($model->bkg_from_city_id);
						$bookReturnTripUrl	 = Yii::app()->baseUrl . "/booking/itinerary/bkgType/" . $model->bkg_booking_type . "/fcity/" . $cityAliasName;
					}
				}
				if($model->bkg_booking_type == 8)
				{
					$bookReturnTripUrl = Yii::app()->baseUrl . "/book-cab";
				}

				//$data	 = Users::getBookingsByUserId($model->bkgUserInfo->bkg_user_id);
				$sosFlag = $model->bkgTrack->bkg_sos_sms_trigger;
				//$sosFlag = ($data['bkg_id'] > 0) ? $data['bkg_sos_sms_trigger'] : 0;
				
				if(in_array($sosFlag, [0, 1]))
				{
					$sosDisplay = 'SOS';
				}
				else if($sosFlag == 2)
				{
					$sosDisplay = 'SOS OFF';
				}
				?>
				<a class="dropdown-item" href="<?= $bookReturnTripUrl ?>" target="_blank"><img src="/images/bxs-edit-location.svg" alt="" width="16" height="16" class="mr10"> <?php echo $bookAgain; ?> </a>
	<!--        <a class="dropdown-item" href="javascript:addToFavorites()"><img src="/images/bxs-save.svg" alt="" width="16" height="16" class="mr10"> Save this page</a>-->
				<a class="dropdown-item" href="javascript:void(0);" onclick="sosCall(<?= $model->bkg_id ?>);"><img src="/images/sos.svg" alt="" width="16" height="16" class="mr10"><span class="sostrigger"><?= $sosDisplay ?></span></a>
				<a class="dropdown-item" href="javascript:void(0);" onclick="reportIssue('<?= $model->bkg_id ?>');"><img src="/images/bxs-edit-location.svg" alt="" width="16" height="16" class="mr10">Report An Issue</a>
				<?php
				if($model->bkg_agent_id != Config::get('Mobisign.partner.id') && $model->bkg_agent_id != Config::get('Kayak.partner.id'))
				{
					?>
					<a class="dropdown-item" onClick="return reqCMB(2, '<?= $model->bkg_id ?>')" href="<?= Yii::app()->createUrl("scq/existingBookingCallBack", array("reftype" => 2)) ?>"><img src="/images/bx-support2.svg" alt="" width="16" height="16" class="mr10"> Contact Support</a>
					<?php
				}
				?>
			</div>
		</div>

		<div class="col-12 text-center mt5">

			<?php
			$dboSettings			 = Config::get('dbo.settings');
			$data					 = CJSON::decode($dboSettings);
			$getDboConfirmEndTime	 = Filter::getDboConfirmEndTime($model->bkg_pickup_date, $model);
			if($getDboConfirmEndTime != '' && $model->bkg_status == 15)
			{
				?>
	<!--			<p class="mb5 text-center">
				<span class="coin-text mb5" data-toggle="modal" data-target="#exampleModalLong">You are eligible for double back offer if you confirm before <? //= date('D, jS M, h:i A', strtotime($getDboConfirmEndTime))  ?></span>
				</p>-->
				<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">Double Back offer terms and conditions</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body text-left">
								<?php
								$this->renderPartial("doubleBackOffer", ["getDboConfirmEndTime" => $getDboConfirmEndTime], false);
								?>
							</div>
						</div>
					</div>
				</div>

				<div><p class="text-center" data-toggle="modal" data-target="#exampleModalLong" style="cursor: pointer;"><img src="/images/dbo.png" class="img-fluid"></p></div>
				<?php
			}
			?>
			<h2 class="merriw heading-line">
				<span class="<?= $reviewText['txtColor'] ?>"> <?= $reviewText['text']; ?> </span>
			</h2>
			<h6 class="text-danger"><?= $reviewText['text1'] ?></h6>
			<h5>Booking Id: <span class="<?= $reviewText['txtColor'] ?>"><?= Filter::formatBookingId($model->bkg_booking_id) ?>
					<?php
					if($model->bkg_status == 15 && $model->bkg_booking_type != 12)
					{
						?>
						<a href ="<?php echo Yii::app()->baseUrl . '/' . $urlRules; ?>?bid=<?= $model->bkg_id ?>"><img src="/images/bx-edit-alt.svg" alt="img" width="20" height="20" " class="mr-1"></a>
			<?php } ?></span></h5>
			<?php
			if($reviewText['slink'] != '')
			{
				?>
				<span class="font-16">Save this page <a href="<?php echo $reviewText['slink'] ?>" target="_blank"><?php echo $reviewText['slink']; ?></a> for cab, driver details & other updates</span>

		<?php } ?>
		</div>
		<?php
		//if ($model->bkg_agent_id == Config::get('Kayak.partner.id') && in_array($model->bkg_status, [2, 3, 5]) && Yii::app()->user->isGuest)
		//{
		?>
		<!--			<div class="col-12 text-center partnercanbooking">
						<button type="button" class="btn btn-outline-danger font-12 mb5 pl10 pr10" id="cancel" onclick="partnerCanBooking(<?php echo $model->bkg_id ?>)" title="Cancel Booking"><img src="/images/bx-x.svg" alt="img" width="14" height="14"> Cancel Booking</button>
					</div>-->
<?php //}  ?>

		<!--=============================new promo ==========-->
		<?php
		if(!in_array($model->bkg_status, [1, 15, 9, 10]))
		{
			$rulePrm		 = Config::get('booking.promo.settings');
			$validDateArr	 = json_decode($rulePrm);
			if($validDateArr->enabled == 1 && ($model->bkg_agent_id == 1249 || $model->bkg_agent_id == null || $model->bkg_agent_id == 0))
			{
				$promoRow	 = PromoUsers::checkPromobyUser($validDateArr->promoId, $model->bkgUserInfo->bkg_user_id, 0);
				$prmDetail	 = Promos::model()->findByPk($validDateArr->promoId);
				$formcity	 = Cities::model()->findByPk($model->bkg_from_city_id)->cty_name;
				$tocity		 = Cities::model()->findByPk($model->bkg_to_city_id)->cty_name;
				if($promoRow['pru_id'] > 0)
				{


					$datetime = new DateTime($promoRow['pru_valid_upto']);
					?>


					<div class="col-12 col-lg-6 offset-lg-3 float-right float-none-1">
						<div class="card bg-blue4 border-none mb15" style="border: #aee4fb 1px solid!important;">
							<div class="card-body p15">
								<div class="pb10">
									<span class="weight500 font-14">Book your next trip</span>
									<span class="float-right mt5 n"><a href="<?= Yii::app()->baseUrl ?>/book-cab/one-way/<?= $tocity ?>/<?= $formcity ?>"  target="blank" class="btn btn-primary font-12 mt5 pl10 pr10">Book now!</a></span></div>
								   <!--					<div class="weight500 font-18 mb5"><?= $tocity ?> to <?= $formcity ?></div>-->
								<div class="font-14 mt5">Use promo code 
									<span class="weight500 color-orange2">“<?php echo $prmDetail->prm_code ?>”</span> to get upto 20% discount.Offer valid till <?php echo $datetime->format('d/m/Y G:i') ?>.</div>

							</div>
						</div>
					</div>
					<?php
				}
			}
		}
		?>
		<!--=========================-->

		<?php
		if(in_array($model->bkg_status, [1, 9, 10]))
		{
			$timeLeftForPickup = Filter::getTimeDiff($model->bkg_pickup_date, Filter::getDBDateTime());
			?>
			<div class="col-12 text-center mb20 p0"><a data-toggle="ajaxModal" id="exisbook" rel="popover" data-placement="left" class="btn btn-outline-primary btn-sm mt-1 pl5 pr5 text-uppercase hvr-push" title="New Booking" onClick="return reqCMB(1, '<?= $model->bkg_id ?>')" href="<?= Yii::app()->createUrl("scq/newBookingCallBack", array("reftype" => 1, "bkgId" => $model->bkg_id)) ?>"><img src="/images/bxs-phone.svg" alt="img" width="16" height="16">Request a call</a>
				<?php //if($timeLeftForPickup > 0 ){     ?>
	<!--			<a data-toggle="ajaxModal" id="exisbook" rel="popover" data-placement="left" class="btn btn-lg btn-outline-primary btn-sm mt-1  pl5 pr5 text-uppercase hvr-push" title="New Booking" onClick="return refreshQuote()" href=""><i class='bx bx-refresh font-16'></i>Refresh Quote</a>-->
	<?php //}   ?>
				<a data-toggle="ajaxModal" id="modifytrip" rel="popover" data-placement="left" class="btn btn-outline-primary btn-sm mt-1  pl5 pr5 text-uppercase hvr-push" title="Modify Trip" onClick="" href="<?= Yii::app()->createUrl("booking/itinerary/bkgType/" . $model->bkg_booking_type, array("bid" => $model->bkg_id)) ?>"><img src="/images/bxs-edit-location.svg" alt="img" width="14" height="14">Book Again</a>
			</div>
			<?php
		}
		?>
		<div class="col-12 col-lg-10 offset-lg-1">
			<!--			<div class="row">
			<?php
			//$this->renderPartial("bkTripInfo", ["model" => $model], false);
			?>
						</div>-->
			<div class="row accordion-widget accordion collapse-icon accordion-icon-rotate mb20 accordion-content">
				<div class="col-12" id="accordion-icon-wrapper">
                    <div class="accordion collapse-icon accordion-icon-rotate" id="accordionWrapa2" data-toggle-hover="true">
						<?php
						if($model->bookingRoutes[0]->brt_from_location != null || $model->bookingRoutes[0]->brt_from_location != " ")
						{

							$existdisplay	 = 'block';
							$adddisplay		 = 'none';
						}
						else
						{
							$existdisplay = 'none';
						}
						$isValidAddress = BookingRoute::validateAddress($model);

						if(!$isValidAddress)
						{
							$adddisplay		 = 'block';
							$existdisplay	 = 'none';
						}
						?>
						<div class="card collapse-header">
							<div id="heading21" class="card-header" data-toggle="collapse" role="button" data-target="#accordion21" aria-expanded="true" aria-controls="accordion21">
								<span class="collapse-title">
									<span class="align-middle weight500">Booking details</span>
								</span>
							</div>
							<div id="accordion21" role="tabpanel" aria-labelledby="heading21" class="collapse" aria-expanded="true" style="">
								<?php
								$this->renderPartial("bkTripInfo", ["model" => $model], false);
								?>
							</div>
						</div>

						<?php
//}     

						if(in_array($model->bkg_status, [2, 3, 5]))
						{
							?>
							<div class="card collapse-header viewDriverContact" id="drivercontact">
								<div id="heading13" class="card-header bg-blue-yonder collapsed" data-toggle="collapse" data-target="#accordion13" aria-expanded="false" aria-controls="accordion13" role="tablist">
									<span class="collapse-title">
										<span class="align-middle weight500">Driver & Cab details</span><br>
									</span>
								</div>
								<div id="accordion13" role="tabpane13" aria-labelledby="heading13" class="collapse" style="">
									<?php
									if($model->bkgTrack->btk_drv_details_viewed == 1)
									{
										$this->renderPartial("bkDriverCabDetails", ["model" => $model], false);
									}
									?>
								</div>
							</div>
<?php } ?>

						<div class="card collapse-header">
							<div id="heading16" class="card-header" data-toggle="collapse" role="button" data-target="#accordion16" aria-expanded="true" aria-controls="accordion16">
								<span class="collapse-title">
									<span class="align-middle weight500">Pickup/Drop location</span>
								</span>
							</div>
							<div id="accordion16" role="tabpanel" aria-labelledby="heading16" class="collapse show" aria-expanded="true" style="">
								<?php
								$this->renderPartial("bkConfirmAddress", ["model" => $model, "adddisplay" => $adddisplay, "existdisplay" => $existdisplay], false);
								?>
							</div>
						</div>

						<?php
						if($model->bkgTrack->bkg_ride_complete == 1 || (in_array($model->bkg_status, [6, 7])))
						{
							?>
							<!--							<a name="triprating"/>-->
							<div class="card collapse-header" id="heading19">
								<div id="heading19" class="card-header collapsed" data-toggle="collapse" data-target="#accordion19" aria-expanded="false" aria-controls="accordion19" role="tablist">
									<span class="collapse-title" id="rating">
										<span class="align-middle weight500"><?= ($ratingModel->rtg_id == '') ? 'Rate this trip' : 'Review received' ?></span>
									</span>
								</div>
								<div id="accordion19" role="tabpanel" aria-labelledby="heading19" class="collapse" style="">
									<?php
									$this->renderPartial("tripRating", ["bkmodel" => $model, "refcode" => $refcode], false);
									?>
								</div>
							</div>
							<?php
						}
						$bkgTrackLogModel = BookingTrackLog::model()->getInfoByEvent($model->bkg_id);
						if($bkgTrackLogModel)
						{
							?>
							<div class="card collapse-header" id="heading18">
								<div id="heading18" class="card-header collapsed triptracking" data-toggle="collapse" data-target="#accordion18" aria-expanded="false" aria-controls="accordion18" role="tablist">
									<span class="collapse-title">
										<span class="align-middle weight500">Live trip tracking status</span>
									</span>
								</div>
								<div id="accordion18" role="tabpanel" aria-labelledby="heading18" class="collapse" style="">
									<?php
									Yii::app()->runController('booking/track/booking_id/' . $model->bkg_id);
									?>
								</div>
							</div>
							<?php
						}

						if(in_array($model->bkg_status, [2, 3, 5, 6, 7, 9]))
						{
							?>
							<div class="card collapse-header">
								<div id="heading14" class="card-header" data-toggle="collapse" data-target="#accordion14" aria-expanded="false" aria-controls="accordion14" role="tablist">
									<span class="collapse-title">
										<span class="align-middle weight500">Fare Breakup</span><br>
									</span>
								</div>
								<div id="accordion14" role="tabpane14" aria-labelledby="heading14" class="collapse" style="">
									<?php
									$this->renderPartial("bkBillingDetails", ["model" => $model], false);
									?>
								</div>
							</div>
						<?php } ?>
						<?php
						$showPromo = true;
						if($model->bkgInvoice->bkg_addon_details != "")
						{
							$addonDetails	 = json_decode($model->bkgInvoice->bkg_addon_details, true);
							$key			 = array_search(1, array_column($addonDetails, 'adn_type'));
							if($addonDetails[$key]['adn_value'] < 0 && $addonDetails[$key]['adn_value'] != null)
							{
								$showPromo = false;
							}
						}
						if($model->bkg_agent_id == Config::get('Kayak.partner.id'))
						{
							$showPromo = false;
						}

						if(!$isPromoApplicable || $isPromoApplicable == false)
						{
							$showPromo = false;
						}
						if($model->bkg_reconfirm_flag == 0 && $model->bkgInvoice->bkg_advance_amount == 0 && $showPromo && $model->bkgPref->bkg_is_gozonow == 0 && !in_array($model->bkg_status, [1, 9, 10]) && $model->bkg_agent_id != Config::get('Mobisign.partner.id') && $model->bkg_agent_id != Config::get('Kayak.partner.id') && $model->bkgInvoice->bkg_extra_discount_amount == 0)
						{
							?>
							<div class="card collapse-header">
								<div id="heading6" class="card-header" data-toggle="collapse" role="button" data-target="#accordion6" aria-expanded="true" aria-controls="accordion6">
									<span class="collapse-title">
										<div class="d-flex align-items-center"><span class="align-middle coupondiscount weight500"></span> <span><img src="/images/bxs-offer.svg" alt="img" width="20" height="20" class="ml10"></span></div>
										<p class="font-12 mb0 text-muted">
											<span class="applydiscount"></span>
										</p>
									</span>
								</div>
								<div id="accordion6" role="tabpanel" aria-labelledby="heading6" class="collapse show" aria-expanded="true">
									<?php
									$this->renderPartial("bkDiscounts", ["model" => $model, "creditVal" => $creditVal], false);
									?>
								</div>
							</div>
<?php } ?>
						<div class="card collapse-header">
                            <div id="heading5" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#accordion5" aria-expanded="false" aria-controls="accordion5">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">Fare inclusions/exclusions</span>
                                </span>
                            </div>
                            <div id="accordion5" role="tabpanel" aria-labelledby="heading5" class="collapse" aria-expanded="false" style="">
								<?php
								$this->renderPartial("bkCanInfo", ["model" => $model], false);
								?>
                            </div>
                        </div>
						<div class="card collapse-header">
                            <div id="heading15" class="card-header bg-lighten-5 collapsed moreDetails" data-toggle="collapse" data-target="#accordion15" aria-expanded="true" aria-controls="accordion15" role="button">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">Additional details</span>
									<p class="font-12 mb0 text-muted">
										<span class="srcitizentrvl">Senior citizen traveling,</span>
										<span class="womantrvl">Women traveling,</span>
										<span class="kidstrvl">Kids on board,</span>
										<span class="drvenglishspeaking">Prefer english speaking driver,</span>
                                        <span class="carrierReq">Overhead carrier</span>
									</p>
								</span>
                            </div>
                            <div id="accordion15" role="tabpanel" aria-labelledby="heading15" class="collapse show"  aria-expanded="true" style="">
								<div class="row m0">
									<div class="col-12 col-lg-6">
										<?php
										$this->renderPartial("bkAdditionalDetails", ["model" => $model], false);
										?>
									</div>
									<div class="col-12 col-lg-6">
										<?php
										$this->renderPartial("bkSpecialRequest", ["model" => $model], false);
										?>
									</div>

								</div>
                            </div>
                        </div>
						<?php
						/** @var Booking $model */
						$addonDetails	 = json_decode($model->bkgInvoice->bkg_addon_details, true);
						$key			 = array_search(2, array_column($addonDetails, 'adn_type'));
						$vehicleTypeId	 = ($addonDetails[$key]['adn_type'] == 2) ? AddonCabModels::model()->findByPk($addonDetails[$key]['adn_id'])->acm_scv_id_from : $model->bkg_vehicle_type_id;
						$scvSccId		 = SvcClassVhcCat::model()->findByPk($vehicleTypeId)->scv_scc_id;
						$isGozoNow		 = $model->bkgPref->bkg_is_gozonow;
						$defCanRuleId	 = CancellationPolicy::getCancelRuleId(null, $vehicleTypeId, $model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_booking_type, $isGozoNow);
						$addons			 = AddonCancellationPolicy::getByCtyVehicleType($model->bkg_from_city_id, $model->bkg_to_city_id, $vehicleTypeId, $model->bkg_booking_type, $defCanRuleId);
						if(($model->bkg_status == 1 || $model->bkg_status == 15) && count($addons) > 0)
						{
							?>
							<div class="card collapse-header">
								<div id="heading19" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#accordion19" aria-expanded="false" aria-controls="accordion19">
									<span class="collapse-title">
										<span class="align-middle weight500">Change Cancellation Policy</span>
										<p class="font-12 mb0 text-muted">
											<span class="applydaddons"></span>
										</p>
									</span>
								</div>
								<div id="accordion19" role="tabpanel" aria-labelledby="heading19" class="collapse" aria-expanded="false" style="">
									<?php
									$this->renderPartial("bkAddonsReview", ["model" => $model, "addons" => $addons, "defCanRuleId" => $defCanRuleId], false);
									?>
								</div>
							</div>
						<?php } ?>
						<?php
						$addonsCMdata = AddonCabModels::getByCtyVehicleType($model->bkg_from_city_id, $model->bkg_to_city_id, $vehicleTypeId, $model->bkg_booking_type);
						if(($model->bkg_status == 1 || $model->bkg_status == 15) && count($addonsCMdata) > 0)
						{
							?>
							<div class="card collapse-header">
								<div id="heading20" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#accordion20" aria-expanded="false" aria-controls="accordion20">
									<span class="collapse-title">
										<span class="align-middle weight500">Change Cab Type</span>
										<p class="font-12 mb0 text-muted">
											<span class="applydcabmodeladdons"></span>
										</p>
									</span>
								</div>
								<div id="accordion20" role="tabpanel" aria-labelledby="heading20" class="collapse" aria-expanded="false" style="">
									<?php
									$this->renderPartial("bkCMAddonsReview", ["model" => $model, "addons" => $addonsCMdata], false);
									?>
								</div>
							</div>
<?php } ?>
						<div class="card collapse-header">
                            <div id="heading22" class="card-header bg-lighten-5 collapsed moreDetails" data-toggle="collapse" data-target="#accordion22" aria-expanded="true" aria-controls="accordion22" role="button">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">Billing Details</span>
                                </span>
                            </div>
                            <div id="accordion22" role="tabpanel" aria-labelledby="heading22" class="collapse"  aria-expanded="true" style="">
								<?php
								$this->renderPartial("bkGSTNDetails", ["model" => $model], false);
								?>
                            </div>
                        </div>
						<div class="card collapse-header">
                            <div id="heading17" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#accordion17" aria-expanded="false" aria-controls="accordion17">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">Your trip plan</span>
                                </span>
                            </div>
                            <div id="accordion17" role="tabpanel" aria-labelledby="heading17" class="collapse" aria-expanded="false" style="">
								<?php
								if(in_array($model->bkg_booking_type, [2, 3]) && $model->bkg_agent_id == Config::get('Kayak.partner.id'))
								{
									$this->renderPartial("bkTripPlan_K", ["model" => $model], false);
								}
								else
								{
									$this->renderPartial("bkTripPlan", ["model" => $model], false);
								}
								?>
                            </div>
                        </div>
						<div class="card collapse-header">
                            <div id="heading8" class="card-header collapsed" data-toggle="collapse" role="button" data-target="#accordion8" aria-expanded="false" aria-controls="accordion8">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">Cancellation terms</span>
                                </span>
                            </div>
                            <div id="accordion8" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading8" class="collapse" aria-expanded="false" style="">
								<?php
								$this->renderPartial("bkCanPolicy", ["model" => $model], false);
								?>
                            </div>
                        </div>

                        <div class="card collapse-header">
                            <div id="heading9" class="card-header" data-toggle="collapse" role="button" data-target="#accordion9" aria-expanded="false" aria-controls="accordion9">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">Pre-boarding guidelines</span>
                                </span>
                            </div>
                            <div id="accordion9" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading9" class="collapse" aria-expanded="false">
								<?php
								$this->renderPartial("bkBoardingCheck", ["model" => $model], false);
								?>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading10" class="card-header" data-toggle="collapse" role="button" data-target="#accordion10" aria-expanded="false" aria-controls="accordion10">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">On trip do's &amp; don'ts</span>
                                </span>
                            </div>
                            <div id="accordion10" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading10" class="collapse" aria-expanded="false">
								<?php
								$this->renderPartial("bkDonts", ["model" => $model], false);
								?>
                            </div>
                        </div>
                        <div class="card collapse-header">
                            <div id="heading11" class="card-header" data-toggle="collapse" role="button" data-target="#accordion11" aria-expanded="false" aria-controls="accordion11">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">Other terms</span>
                                </span>
                            </div>
                            <div id="accordion11" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading11" class="collapse" aria-expanded="false">
								<?php
								$this->renderPartial("bkAdvisory", ["model" => $model], false);
								?>
                            </div>
                        </div>
						<div class="card collapse-header hide">
                            <div id="heading12" class="card-header" data-toggle="collapse" role="button" data-target="#accordion12" aria-expanded="false" aria-controls="accordion12" style="border-bottom: #e8e8e8 1px solid!important;">
                                <span class="collapse-title">
                                    <span class="align-middle weight500">Travel advisories &amp; restrictions</span>
                                </span>
                            </div>
                            <div id="accordion12" role="tabpanel" data-parent="#accordionWrapa2" aria-labelledby="heading12" class="collapse" aria-expanded="false">
								<?php
								$this->renderPartial("bkTravelAdvisories", ["model" => $model, "note" => $note], false);
								?>
                            </div>
                        </div>
						<div style="border-top: #e8e8e8 1px solid!important;">
							<?php
							$this->renderPartial("bkFareDetails", ["model" => $model], false, false);
							?>
						</div>
                    </div>
                </div>
			</div>
			<?php
			if($model->bkgInvoice->bkg_due_amount > 0 && !in_array($model->bkg_status, [9, 10]))
			{
				?>
				<div class="col-12 cc-4">
					<div class="row widget-pay pt10 pb0">
						<div class="container p0">
							<div class="col-12 col-xl-10 offset-xl-1">
								<?php
								if(in_array($model->bkg_status, [15]))
								{
									?>
									<div class="row">
										<div class="col-12 font-13"><p class="mb2 lineheight14">By proceeding to book, I Agree to aaocab's <a class="fmodal" href="<?= Yii::app()->createUrl('index/privacy') ?>" target="_blank">Privacy Policy</a>, User Agreement and <a class="fmodal" href="<?= Yii::app()->createUrl('index/terms') ?>" target="_blank">Terms of service</a></p></div>
									</div>
	<?php } ?>
								<div class="row justify-center">
									<div class="col-5">
										<p class="lineheight14 payline mb10"><span class="text-uppercase"><?= $payAmount ?>:</span>
											<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" class="fair-breakup-modal link-section"><span class="font-24 weight600 txtDueAmount"><?php echo Filter::moneyFormatter($model->bkgInvoice->bkg_due_amount); ?></span><span class="font-12 color-blue3 weight500">View details</span></a></p>
									</div>
									<div class="col-7">
										<?php
										if(in_array($model->bkg_status, [2, 3, 5, 15]) && $minDiff > 0)
										{
											?>
											<div class="mb5 text-right">
												<buttton type="button" onclick="process()" class="btn btn-primary text-uppercase proceedpay-btn" style="line-height: 1.1em"><?= $payButton ?></button>
											</div>
									<?php } ?>	
									</div>
									<?php
									//<div class="col-12 col-lg-8 col-xl-8 mt5 reviewcontent hide">
									//<div class="row">
									//<div class="col-2 col-lg-2"><div class="round-2 hide mb0"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
									//<div class="col-10 col-lg-10 d-lg-none d-xl-none"><marquee class="summarycontent" direction="up" height="50px" scrollamount="1"></marquee></div>
									//<div class="col-10 col-lg-10 summarycontent  d-none d-lg-block"></div>
									//</div>
									//</div>
									?>

								</div>
							</div>
						</div>
					</div>
				</div>
<?php } ?>
			<input type="hidden"  class="clsAdditionalParams" name="additionalParams" id="additionalParams" value='{"code":"","coins":0,"wallet":0}'/>
			<input type="hidden" name="hash" value="<?php echo $bhash; ?>">
			<input type="hidden" name="pageID" id="pageID" value="<?= $pageId ?>">
			<input type="hidden" name="minamount" id="minamount" value="">
			<input type="hidden" name="dueamount" id="dueamount" value="">
            <input type="hidden" name="discountamount" id="discountamount" value="">
			<input type="hidden" name="drvdetailsview" id="drvdetailsview">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

		</div>
	</div>
</div>

<?php //$this->endWidget();      ?>
<div class="modal fade modalView modal-widget-1" id="cancellationModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="bg-blue-yonder modal-header pl20">
				<h5 class="modal-title white" id="cancellationModelHeader"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<img src="/images/bx-x.svg" alt="img" width="18" height="18">
				</button>
			</div>
			<div class="modal-body p30" id="cancellationModelBody">
				<p class="mb-0"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="close btn btn-light" data-dismiss="modal" aria-label="Close">
					<img src="/images/bx-x.svg" alt="img" width="18" height="18" class="d-block d-sm-none">
					<span class="d-none d-sm-block text-uppercase">Go Back</span>
				</button>
				<button type="button" class="btn btn-danger ml-1" data-dismiss="modal">
					<img src="/images/bx-check.svg" alt="img" width="14" height="14" onclick="viewDriverDetails()">
					<span class="d-none d-sm-block text-uppercase" onclick="viewDriverDetails()">Proceed to view details</span>
				</button>
			</div>

		</div>
	</div>
</div>
<div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelBookingModalLabel">Cancel Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cancelBookingModelContent">
                <div class="row"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal full-screen" id="bkPaymentModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="display: inline-block; padding: 5px 10px 0; border-bottom: 0px">
				<button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body p15">
			</div>
			<div class="modal-body1 p15">

			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="rescheduleBooking" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-16" id="rescheduleBookingLabel">Reschedule Booking</h5>
				<button type="button" class="close mt30 n pt0" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="rescheduleBookingContent">
				<div class="row"></div>
			</div>
			<div class="modal-body hide" id="rescheduleBookingDeatils">
				<div class="row"></div>
			</div>
		</div>
	</div>
</div> 
<div class="modal fade" id="reportIssueModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="p10 pl0 hide reportissuelist" style="cursor: pointer;" onclick="reportIssue('<?= $model->bkg_id ?>');"><img src="/images/bx-left-arrow-alt.svg" alt="" width="24" ></div>
				<h5 class="modal-title text-center font-16" id="reportIssueModalLabel">Report An Issue<br><p class="font-18 m0 reportIssueCatagory"></p></h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="reportIssueModelContent">
				<div class="row"></div>
			</div>
			<div class="modal-body hide" id="reportIssueModelDetails">
				<div class="row"></div>
			</div>
		</div>
	</div>
</div>
<? ?>
<script type="text/javascript">
<?php
if($model->bkg_status == 2 && $model->bkg_reconfirm_flag == 1 && $model->bkg_agent_id == null)
{
	$data = \Beans\ga4\Ecommerce::purchase($model);
	?>
		$(document).ready(function ()
		{
			trackPurchase(<?= json_encode(Filter::removeNull($data)) ?>);
		});
	<?
}
?>


	$('.fair-breakup-modal').click(function (event)
	{
		$('#bkFareDetailsModel').removeClass('fade');
		$('#bkFareDetailsModel').css("display", "block");
		$('#bkFareDetailsModel').modal('show');
	});
	$("button[data-dismiss=modal]").click(function ()
	{
		$("#cancellationModel").hide();
	});
	$('#heading7').bind('accordionchange',
			function ()
			{
				alert('Active tab index: ');
			});


	function saveSpecialRequest(callback = null)
	{
		var href = '<?= Yii::app()->createUrl('booking/finalPay') ?>';
		var dtform = $('#bookingsplrequest').serialize();
		var form = $("form#bookingsplrequest");
		$.ajax({type: 'POST',
			url: href,
			data: form.serialize() + "&id=<?= $model->bkg_id ?>&hash=<?= $bhash ?>",
			datatype: 'html',
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				if (callback == null)
				{
					unBlockForm(form);
				}
			},
			success: function (data)
			{
				obj = jQuery.parseJSON(data);
				if (obj.success == true)
				{
					showSpecialDetails();
					$('.additionalcharge').removeClass('hide');
					$('.extracharge').text(obj.additionalAmount);
					$(".txtGstAmount").text(obj.servicetax);
					$("#dueamount").val(obj.dueAmount);
					$('.payBoxTotalAmount').text(obj.dueAmount);
					$('.txtEstimatedAmount').text(obj.dueAmount);
					saveAdditionalRequest();
				} else
				{
					unBlockForm(form);
					var errors = "Please contact customer support";
					if (data.hasOwnProperty("errors"))
					{
						errors = data.errors.join("</li><li>");
					}

					var message = "<div class='errorSummary'><ul><li>" + errors + "</li></ul></div>";
					toastr['error'](message, 'Failed to process!', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				}
			},
			error: function (xhr)
			{
				unBlockForm(form);
			}

		});
	}
	function saveAdditionalRequest(callback = null)
	{
		var href = '<?= Yii::app()->createUrl('booking/finalPay') ?>';
		var dtform = $('#bookingadditionalinfo').serialize();
		var form = $("form#bookingadditionalinfo");
		jQuery.ajax({type: 'POST',
			url: href,
			data: form.serialize() + "&id=<?= $model->bkg_id ?>&hash=<?= $bhash ?>",
			dataType: "json",
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				if (callback == null)
				{
					unBlockForm(form);
				}
			},
			success: function (data)
			{
				if (data.success == true)
				{

					$('.additionalcharge').removeClass('hide');
					$('.extracharge').text(obj.additionalAmount);
					$(".txtGstAmount").text(obj.servicetax);
					$("#dueamount").val(obj.dueAmount);
					$('.payBoxTotalAmount').text(obj.dueAmount);
					$('.txtEstimatedAmount').text(obj.dueAmount);
				} else
				{
					unBlockForm(form);
					var errors = "Please contact customer support";
					if (data.hasOwnProperty("errors"))
					{
						errors = data.errors.join("</li><li>");
					}

					var message = "<div class='errorSummary'><ul><li>" + errors + "</li></ul></div>";
					toastr['error'](message, 'Failed to process!', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				}
			},
			error: function (xhr)
			{
				unBlockForm(form);
			}
		});
	}

	function saveRequest(isForword)
	{

		var href = '<?= $this->getURL(['booking/finalPay', "id" => $model->bkg_id, "hash" => $bhash]) ?>';
		var adtData = $('#bookingadditionalinfo').serialize();
		var splData = $('#bookingsplrequest').serialize();
		var addData = $('#frmBkgAddress').serializeArray();
		var gstnData = $('#bookinggstninfo').serialize();

		//alert(JSON.stringify(addData));
		var csrf = $('#bookingsplrequest').find("INPUT[name=YII_CSRF_TOKEN]").val();
		var promoData = prmObj.getParams();
		var form = $(".cntPayment");

		jQuery.ajax({
			type: 'POST',
			url: href,
			data: {adtData: adtData, splData: splData, addData: addData, promoData: promoData, YII_CSRF_TOKEN: csrf, isForword: isForword, gstnData: gstnData},
			dataType: "json",
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			success: function (data)
			{
				obj = data.data;

				if (data.success == true)
				{
					$('.additionalcharge').removeClass('hide');
					$('.extracharge').text(obj.additionalAmount);
					$(".txtGstAmount").text(obj.servicetax);
					$("#dueamount").val(obj.dueAmount);
					$('.payBoxTotalAmount').text(obj.dueAmount);
					$('.txtEstimatedAmount').text(obj.dueAmount);

					huiObj.updateBillingInvoice(data.data, obj.promos[0], 0);
					if (isForword != 1)
					{
						//location.href = data.url;
						dataArray = data.url.split('/');
						hash = dataArray[dataArray.length - 1];
						paymentreview(hash);
						return;
					} else
					{
						var message = "<div class='info'><ul><li>" + errors + "</li></ul></div>";
						toastr['info']('Address updated successfully', {
							closeButton: true,
							tapToDismiss: false,
							timeout: 500000
						});
					}
				} else
				{
					var errors = "Please contact customer support";
					if (data.hasOwnProperty("errors"))
					{
						errors = data.errors.join("</li><li>");
					}
					var message = "<div class='errorSummary'><ul><li>" + errors + "</li></ul></div>";
					toastr['error'](message, 'Failed to process!', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				}
			},
			error: function (xhr)
			{
				unBlockForm(form);
			}
		});
	}

	function paymentreview(hash)
	{
		var form = $(".cntPayment");
		var href2 = "<?php echo Yii::app()->createUrl('booking/paymentreview') ?>";
		$.ajax({
			"url": href2,
			data: {'hash': hash, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
			"type": "POST",
			"dataType": "html",
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			"success": function (data2)
			{

				trackPage("<?= CHtml::normalizeUrl($this->getURL('booking/paymentreview')) ?>");
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				} catch (e)
				{

				}
				if (!isJSON)
				{
					$('#bkPaymentModal .modal-body1').html(data2);
					$('#bkPaymentModal .modal-body1').show();
					$('#bkPaymentModal .modal-body').hide();
					$('#bkPaymentModal').removeClass('full-screen');
					$('#bkPaymentModal').addClass('bootbox');
					$('#bkPaymentModal').addClass('fade');
					$('#bkPaymentModal').addClass('show');
					$('.modal-backdrop').last().css("display", "block");
					$('#bkPaymentModal').modal().show();
					$('.clsAdditionalParams').val('{"code":"","coins":0,"wallet":0}');
				} else
				{
					if (data.success)
					{
						location.href = data.data.url;
						return;
					}
				}
			},
			"error": function (xhr, ajaxOptions, thrownError)
			{
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function evalCharges()
	{
		var href = '<?= Yii::app()->createUrl('booking/evalCharges', ["id" => $model->bkg_id, "hash" => $bhash]) ?>';
		var adtData = $('#bookingadditionalinfo').serialize();
		var splData = $('#bookingsplrequest').serialize();
		var csrf = $('#bookingsplrequest').find("INPUT[name=YII_CSRF_TOKEN]").val();
		var promoData = prmObj.getParams();
		var form = $(".cntPayment");

		jQuery.ajax({type: 'POST',
			url: href,
			data: {adtData: adtData, splData: splData, promoData: promoData, YII_CSRF_TOKEN: csrf},
			dataType: "json",
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			success: function (data)
			{
				obj = data.data;
				if (data.success == true)
				{
					$('.additionalcharge').removeClass('hide');
					$('.extracharge').text(obj.additionalCharge);
					$(".txtGstAmount").text(obj.servicetax);
					$("#dueamount").val(obj.dueAmount);
					$('.payBoxTotalAmount').text(obj.dueAmount);
					$('.txtEstimatedAmount').text(obj.dueAmount);
					huiObj.updateBillingInvoice(data.data, null, 0);
				} else
				{
					var errors = "Please contact customer support";
					if (data.hasOwnProperty("errors"))
					{
						errors = data.errors.join("</li><li>");
					}

					var message = "<div class='errorSummary'><ul><li>" + errors + "</li></ul></div>";
					toastr['error'](message, 'Failed to process!', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				}

			},
			error: function (xhr)
			{
				unBlockForm(form);
			}
		});
	}

	function saveDiscount()
	{

		var href = '<?= Yii::app()->createUrl('booking/finalPay') ?>';

		var discountform = $("form#bookingdiscount");

		jQuery.ajax({type: 'POST',
			url: href,
			data: discountform.serialize() + "&id=<?= $model->bkg_id ?>&hash=<?= $bhash ?>",
			dataType: "json",
			"beforeSend": function ()
			{
				blockForm(discountform);

			},
			"complete": function ()
			{
//                if (callback == null)
//                {

				unBlockForm(discountform);

				// }
			},
			success: function (data)
			{
				if (data.success == true)
				{

					$('.additionalcharge').removeClass('hide');
					$('.extracharge').text(obj.additionalAmount);
					$(".txtGstAmount").text(obj.servicetax);
					$("#dueamount").val(obj.dueAmount);
					$('.payBoxTotalAmount').text(obj.dueAmount);
					$('.txtEstimatedAmount').text(obj.dueAmount);
					payNow();
				} else
				{

					unBlockForm(discountform);
					var errors = "Please contact customer support";
					if (data.hasOwnProperty("errors"))
					{
						errors = data.errors.join("</li><li>");
					}

					var message = "<div class='errorSummary'><ul><li>" + errors + "</li></ul></div>";
					toastr['error'](message, 'Failed to process!', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});

				}
			},
			error: function (xhr)
			{
				unBlockForm(discountform);

			}
		});
	}

	function payNow()
	{

		var href = '<?= Yii::app()->createUrl('booking/paymentreview') ?>';

		var minamount = $("#minamount").val();
		var dueamount = $("#dueamount").val();
		var additionalParams = $("#additionalParams").val();

		window.location.href = href + "?hash=" + '<?= $bhash ?>';


	}
	function process()
	{
		saveRequest();
	}
	function blockForm(form)
	{
		block_ele = form.closest('div.container');

		$(block_ele).block({
			message: '<div class="loader"></div>',
			overlayCSS: {
				backgroundColor: "#FFF",
				opacity: 0.8,
				cursor: 'wait'
			},
			css: {
				border: 0,
				padding: 0,
				backgroundColor: 'transparent'
			}
		});
	}

	function unBlockForm()
	{
		$(block_ele).unblock();
	}

	$('.viewDriverContact').click(function (event)
	{
		checkCancellation();


	});

	function viewDriverDetails()
	{
		var booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('booking/viewCustomerDetails') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": booking_id, "type": 1},
			success: function (data)
			{
				var obj = $.parseJSON(data);
				if (obj.success)
				{
					showDriverDetails();
					$(".freecancellation").addClass('hide');
					$(".cancharge").addClass('hide');
					$('.drvdetailsviewedtime').text(obj.drvviewtime);
					$("#cancellationModel").addClass('hide');
					$('#accordion13').addClass('show');
					$('#drvdetailsview').val('1');
				}
			}
		});
	}

	function showDriverDetails()
	{
		var booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('booking/showDriverDetails') ?>";
		jQuery.ajax({type: 'GET',
			"dataType": "html",
			url: $href,
			data: {"bookingid": booking_id, "type": 1},
			success: function (data)
			{
				$("#accordion13").html(data);
				$("#driverDetails").removeClass('hide');
			}
		});
	}
	function showSpecialDetails()
	{
		//
		var seniorCitizen =<?= $model->bkgAddInfo->bkg_spl_req_senior_citizen_trvl ?>;
		var kidsTravel = <?= $model->bkgAddInfo->bkg_spl_req_kids_trvl ?>;
		var womanTravel = <?= $model->bkgAddInfo->bkg_spl_req_woman_trvl ?>;
		var carrierReq = <?= $model->bkgAddInfo->bkg_spl_req_carrier ?>;
		var engSepeakingDriver = <?= $model->bkgAddInfo->bkg_spl_req_driver_english_speaking ?>;


		(seniorCitizen > 0) ? $(".srcitizentrvl").show() : $(".srcitizentrvl").hide();
		(kidsTravel > 0) ? $(".kidstrvl").show() : $(".kidstrvl").hide();
		(womanTravel > 0) ? $(".womantrvl").show() : $(".womantrvl").hide();
		(carrierReq > 0) ? $(".carrierReq").show() : $(".carrierReq").hide();
		(engSepeakingDriver > 0) ? $(".drvenglishspeaking").show() : $(".drvenglishspeaking").hide();
	}

	function getPromoById()
	{
		var booking_id = '<?= $model->bkg_id ?>';
		var hash = '<?= $bhash ?>';
		$href = "<?= Yii::app()->createUrl('booking/getpromobyid') ?>";
		jQuery.ajax({type: 'GET',
			"dataType": "json",
			url: $href,
			data: {"id": booking_id, "hash": hash},
			success: function (data)
			{
				if (data.success)
				{
					prmcode = data.prmcode;
					prmObj.applyPromo(1, prmcode);
				} else
				{
					if (typeof data.count === 'undefined' && data.message.length > 0)
					{
						var message = data.message;
						toastr['error'](message, 'Failed to process!', {
							closeButton: true,
							tapToDismiss: false,
							timeout: 500000
						});
					}
				}
			}
		});
	}

	function refreshQuote()
	{
		var bookingId = '<?= $model->bkg_id ?>';
		var hash = '<?= $bhash ?>';
		$href = "<?= Yii::app()->createUrl('booking/refreshQuote') ?>";
		jQuery.ajax({type: 'GET',
			"dataType": "json",
			url: $href,
			data: {"bid": bookingId, "hash": hash},
			success: function (data)
			{
				if (data.success)
				{
					prmcode = data.prmcode;
					prmObj.applyPromo(1, prmcode);
				} else
				{
					var message = data.message;
					toastr['error'](message, 'Failed to process!', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				}
			}
		});
	}


	function partnerCanBooking(booking_id)
	{
		$href = "<?php echo Yii::app()->createUrl('booking/Canbooking') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id, "bkpnlogin": 1},
			success: function (data)
			{
				$('#cancelBookingModal').removeClass('fade');
				$('#cancelBookingModal').css('display', 'block');
				$('#cancelBookingModelContent').html(data);
				$('#cancelBookingModal').modal('show');
			},
			error: function (xhr, ajaxOptions, thrownError)
			{

				//alert('here');
			}

		});
	}

	this.checkTripStatus = function (bkgstatus)
	{
		var bkg_status = bkgstatus;
		if ($.inArray(bkg_status, ['15', '2', '3', '5']) == -1)
		{
			toastr['error']("This booking cannot be cancelled.", 'Failed to process!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
			return false;
		}

		let booking_id = '<?= $model->bkg_id ?>';
		//$href = "<?php echo Yii::app()->createUrl('booking/CheckTripStatus') ?>";
		//var $booking_id = booking_id;
		$.ajax({type: 'GET',
			url: '/booking/CheckTripStatus',
			data: {"booking_id": booking_id},
			success: function (data)
			{
				var dt = JSON.parse(data);
				var msg = dt.message;
				if (dt.success == false)
				{
					var retVal = confirm(msg);
				}
				if (dt.success == true)
				{
					canBooking(booking_id);
					return true;
				}
				if (retVal == true && dt.success == false)
				{
					scqBooking(booking_id, msg);
					return true;
				} else
				{
					// needtoknow();
					return false;
				}
			}
		});

	};

	this.canBooking = function (booking_id)
	{
		//var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			"url": "/booking/canbooking",
			data: {"booking_id": booking_id, "bkpnaction": 1},
			success: function (data)
			{
				$('#cancelBookingModal').removeClass('fade');
				$('#cancelBookingModal').css('display', 'block');
				$('#cancelBookingModelContent').html(data);
				$('#cancelBookingModal').modal('show');
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				if (xhr.status == "403")
				{
					handleException(xhr, function ()
					{

					});
				}
			}
		});
	};

	//For cancel booking
	function scqBooking(booking_id, msg) {
		$href = "<?php echo Yii::app()->createUrl('booking/autofurcustomer') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"booking_id": $booking_id, "message": msg},
			success: function (data1)
			{
				data = JSON.parse(data1);
				if (data.success)
				{
					toastr['info']('Your call back request has been generated', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				}
			}
		});
	}


	function scrollToAnchor(aid)
	{
		var aTag = $("a[name='" + aid + "']");
		$('html,body').animate({
			scrollTop: aTag.offset().top
		}, 'slow');
	}

	function showReview(bkgStatus)
	{
		if ($.inArray(bkgStatus, ['6', '7']) == -1)
		{
			toastr['error']("Review is given by only for completed booking", 'Failed to process!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
			return false;
		}
		scrollToAnchor('triprating');
	}
	function reviewTab(bkgStatus)
	{
		showLogin(function ()
		{
			showReview(bkgStatus);
		});
		return false;
	}

	function travellerInfo(bkgStatus)
	{
		if (bkgStatus != 15 && bkgStatus != 2 && bkgStatus != 3 && bkgStatus != 5)
		{
			toastr['error']("Traveller Info change only for Quotation and Confirm booking", 'Failed to process!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
			return false;
		}
		var href2 = "<?php echo Yii::app()->createUrl('booking/travellerinfo') ?>";
		var bkgId = "<?php echo $model->bkg_id ?>";
		$.ajax({
			"url": href2,
			data: {bkg_id: bkgId, 'isbkpn': 1, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
			"type": "GET",
			"dataType": "html",
			//async:false,
			"success": function (data)
			{
				$('#bkCommonModelHeader').text('Traveller Info');
				$('#bkCommonModelBody').html(data);
				$('#bkCommonModel').modal('show');
			},
			"error": function (xhr, ajaxOptions, thrownError)
			{

				if (xhr.status == "403")
				{
					handleException(xhr, function ()
					{

					});
				}
			}
		});
		return false;
	}
	function reschedulePopup(booking_id, bkg_status)
	{
		$href = "<?php echo Yii::app()->createUrl('booking/reschedulebooking') ?>";
		var $booking_id = booking_id;
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"bkg_id": $booking_id},
			success: function (data2)
			{    //debugger;   

				var data = "";
				var isJSON = false;

				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				} catch (exception)
				{

				}

				if (!isJSON)
				{
					$('#rescheduleBooking').removeClass('fade');
					$('#rescheduleBooking').css('display', 'block');
					$('#rescheduleBookingContent').html(data2);
					if (!$('#rescheduleBookingDeatils').hasClass('hide'))
					{
						$('#rescheduleBookingDeatils').addClass("hide");
					}
					$('#rescheduleBookingContent').removeClass("hide");
					$('#rescheduleBookingLabel').html("Reschedule Booking");
					$('#rescheduleBooking').modal('show');
				} else
				{
					var error = data.errors.bkg_id;
					$.each(error, function (key, value)
					{
						message = value;
					});

					toastr['error'](message, 'Failed to process!', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
					return false;
				}
			},
			"error": function (xhr, ajaxOptions, thrownError)
			{

				if (xhr.status == "403")
				{
					handleException(xhr, function ()
					{

					});
				}
			}
		});
	}

	function downloadInvoice(booking_id, bkgStatus)
	{
		if ($.inArray(bkgStatus, ['6', '7']) == -1)
		{
			toastr['error']("Download invoice only for completed booking", 'Failed to process!', {
				closeButton: true,
				tapToDismiss: false,
				timeout: 500000
			});
			return false;
		}
		$href = "<?php echo Yii::app()->createUrl('booking/receipt') ?>";
		window.open($href + "/bkg/" + booking_id + "/hsh/" + hsh, '_blank');
	}

	function receipt(booking_id, hsh, bkgStatus)
	{
		showLogin(function ()
		{
			downloadInvoice(booking_id, bkgStatus);
		});
	}

	function checkCancellation()
	{
		var booking_id = '<?= $model->bkg_id ?>';
		$href = "<?= Yii::app()->createUrl('booking/checkCancellation') ?>";
		jQuery.ajax({type: 'GET',
			"dataType": "json",
			"url": $href,
			"data": {"bookingid": booking_id},
			"success": function (data)
			{
				if (data.showAlert == 1)
				{
					var drvdetailsviewed = '<?= $model->bkgTrack->btk_drv_details_viewed ?>';
					var drvId = '<?= $model->bkgBcb->bcbDriver->drv_contact_id ?>';
					var drvview = $('#drvdetailsview').val();
					if ($("#heading13").hasClass("collapsed") == true)
					{
						if (drvId != '')
						{
							if (drvdetailsviewed == 0 && drvview == '')
							{
								var data = 'We usually share cab & driver details approx. 2 hours before pickup time. \n\
                                            If you choose to view these details now, this booking will become non-refundable.';
								$('.modal').modal('hide');
								$('#cancellationModel').removeClass('fade');
								$('#cancellationModelHeader').text('Driver & Cab details');
								$('#cancellationModel').css("display", "block");
								$('#cancellationModelBody').html(data);

								return false;
							} else
							{
								$("#driverDetails").removeClass('hide');
							}
						} else
						{
							showDriverDetails();
						}
					}
				} else
				{
					showDriverDetails();
				}
			}
		});
	}

	function sosTrigger(bkgId, eventType, isIssue)
	{   debugger;
		let latitude = $('#latitude').val();
		let longitude = $('#longitude').val();
		//alert('latitude'+latitude + 'longitude '+ longitude);

		var href = '<?php echo Yii::app()->createUrl('issue/SosTrigger') ?>';
		$.ajax({
			type: 'POST',
			url: href,
			data: {'booking_id': bkgId, 'event_type': eventType, latitude: latitude, longitude: longitude, 'isIssue': isIssue, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
			// datatype:'json',
			success: function (data2)
			{
				//debugger;
				//data = JSON.parse(data2);
				console.log(JSON.parse(data2));
				var data = "";
				var isJSON = false;

				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				} catch (exception)
				{

				}

				if (!isJSON)
				{
					$('#reportIssueModal').removeClass('fade');
					$('#reportIssueModal').css('display', 'block');
					$('#reportIssueModelContent').html(data2);
					$('#reportIssueModelContent').removeClass("hide");
					$('#reportIssueModal').modal('show');
				} else
				{
					if (data.success)
					{
						toastr['info'](data.message, {
							closeButton: true,
							tapToDismiss: false,
							timeout: 500000
						});

						$('.sostrigger').html('SOS ON');
						if (data.data.sosFlag == 2)
						{
							$('.sostrigger').html('SOS OFF');
						}

					}

				}
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				if (xhr.status == "403")
				{
					handleException(xhr, function ()
					{

					});
				}

			}
		});
	}

	function sosCall(bkgId,isIssue = null)
	{  
		
		 //debugger;
		var href = '<?php echo Yii::app()->createUrl('issue/SosTrigger') ?>';
		
		$.ajax({
			type: 'GET',
			url: href,
			
			data: {'is_sos': 1,'booking_id': bkgId},
			datatype: 'json',
			success: function (data2)
			{   //debugger;
				data = JSON.parse(data2);
				
				bkgId = data.sosBkgId;
				//alert(data.isSosFlag);
				if (bkgId > 0 && (data.isSosFlag == 0 || data.isSosFlag == 1))
				{
					if (confirm("Do you want to ON SOS emergency alert?"))
					{
						sosTrigger(bkgId, 301, isIssue);
					}
					return false;
				}

				if (bkgId > 0 && data.isSosFlag == 2)
				{
					if (confirm("Do you want to OFF SOS emergency alert?"))
					{
						sosTrigger(bkgId, 302, isIssue);
					}
					return false;
				}

				if (data.isSosFlag == 0)
				{
					toastr['error']('SOS cannnot be activated as there is no ongoing Trips!', {
						closeButton: true,
						tapToDismiss: false,
						timeout: 500000
					});
				}
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				if (xhr.status == "403")
				{
					handleException(xhr, function ()
					{

					});
				}
			}
		});
	}

	function reqPaymentFailedCBR() {
		var booking_id = '<?= $model->bkg_id ?>';
		return reqCMB(2, booking_id, 'I am facing payment issue');
	}

	function getLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		} else
		{
			alert("Geolocation is not supported by this browser.");
		}
	}

	function showPosition(position) {
		$('#latitude').val(position.coords.latitude);
		$('#longitude').val(position.coords.longitude);
		//return position.coords.latitude;
	}

	function reportIssue(bkgId)
	{
		let href = "<?php echo Yii::app()->createUrl('issue/ReportIssue'); ?>";
		$.ajax({
			type: 'GET',
			url: href,
			data: {'booking_id': bkgId},
			success: function (data2)
			{   //debugger;
				var data = "";
				var isJSON = false;

				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				} catch
				{

				}

				if (!isJSON)
				{
					$('#reportIssueModal').removeClass('fade');
					$('#reportIssueModal').css('display', 'block');
					$('#reportIssueModelContent').html(data2);
					$('#reportIssueModal').modal('show');
					$('.reportissuelist').addClass('hide');
					$('.reportIssueCatagory').addClass('hide');
				} else
				{
					if (!data.success)
					{
						var error = data.errors;
						message = error;
						toastr['error'](message, 'Failed to process!', {
							closeButton: true,
							tapToDismiss: false,
							timeout: 500000
						});
					}
				}
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				if (xhr.status == "403")
				{
					handleException(xhr, function ()
					{

					});
				}
			}
		});
	}
</script>
