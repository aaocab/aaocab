<style>
	.tooltip-inner{
		background: #f3f3f3;
		color: #000;
	}
	.bs-tooltip-top .arrow::before{
		border-top-color: #f3f3f3;
	}
	@media (min-width: 576px) {
		.modal-dialog {
			max-width: 600px !important;
		}
}
</style>

	<?php

	/** @var BookFormRequest $objPage */
	$objPage	 = $this->pageRequest;
	/** @var Stub\common\Booking $objBooking */
	$objBooking	 = $objPage->booking;

	$isGozoNow			 = ($objBooking->isGozoNow == '') ? 0 : $objBooking->isGozoNow;
	$tncIds				 = TncPoints::getTncIdsByStep($step);
	$tncArr				 = TncPoints::getTypeContent($tncIds);
	$tncArr1			 = json_decode($tncArr, true);
	$serviceClassDesc	 = Config::get('booking.service.class.description');
	$objServiceClassDesc = json_decode($serviceClassDesc);
	$prefCategoryData	 = Booking::getPrefferedTripData($objPage);

	$fcity			 = $objBooking->routes[0]->source->code;
	$city = Cities::model()->findByPk($fcity);
	$isAirport = $city->cty_is_airport;

	$nextStep	 = ($objBooking->isGozoNow == 1) ? '8' : '9';
	/** @var CActiveForm $form */
	$form		 = $this->beginWidget('CActiveForm', array(
		'id'					 => 'cabcategory',
		'enableClientValidation' => false,
		'clientOptions'			 => [
			'validateOnSubmit'	 => false,
			'errorCssClass'		 => 'has-error',
		],
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'action'				 => Yii::app()->createUrl('booking/tierQuotes'),
		'htmlOptions'			 => array(
			'class'			 => 'form-horizontal',
			'autocomplete'	 => 'off',
		),
	));
	if($objBooking->agentId == Config::get('Kayak.partner.id') && $this->pageRequest->quote==null)
	{
		$this->pageRequest->populateQuote();
	}

	if ($objBooking->tripType != 4 && $objBooking->tripType != 12)
	{
		$tiers		 = $this->pageRequest->categorizeQuote(true);
		$sortedTiers = array_column($tiers, 'catClassRank');
		array_multisort($sortedTiers, SORT_ASC, $tiers);
	}
	Filter::writeLog("View categorizeQuote", $traceLog);

	$quotes2 = $this->pageRequest->sortCategory();
	Filter::writeLog("View pageRequest->sortCategory", $traceLog);
	if (!$quotes2)
	{
		goto skipQuotes;
	}

	$coinCanUseArr	 = [];
	$maxCoin		 = '';
	if (UserInfo::getUserId() > 0)
	{
		foreach ($quotes2 as $cabRate)
		{
			$key	 = 'percentage';
			$usage	 = Config::getValue("gozocoin.promo.usage", $key);

			$coinCanUse	 = 0;
			$objFare	 = $cabRate->fare;
			if (UserInfo::getUserId() > 0)
			{
				$userCredituse	 = UserCredits::getMaxApplicablePromoCredits(UserInfo::getUserId(), $objFare->baseFare, $usage / 100);
				$coinCanUse		 = $userCredituse['totalMaxApplicable'];
				$coinCanUseArr[] = $coinCanUse;
			}
		}
	}
	?>
	<div class="col-12  mt10 n p0">
		<div class="alert alert-danger mb-2 text-center hide alertcab" role="alert"></div>

		<div class="cabtravellerinfo">
			<?php
			if (!Yii::app()->user->isGuest && $objBooking->agentId != Config::get('Mobisign.partner.id'))
			{
				$isUserName = 0;
				if (Yii::app()->user->loadUser()->usr_name == '' || Yii::app()->user->loadUser()->usr_lname == '')
				{
					$isUserName = 1;
				}
				?>
				<div class="btn-group mr-1 mb-1">
					<span class="pt5">Booking for:</span> <div class="dropdown">
						<button type="button" class="btn btn-sm dropdown-toggle font-14 travellerinfo traveller-box" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="clsusername"><?php echo Yii::app()->user->loadUser()->usr_name . '&nbsp;' . Yii::app()->user->loadUser()->usr_lname; ?></span>
						</button>
						<input type="hidden" name="isUserName" value="<?= $isUserName ?>">
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
	if ($isGozoNow == 1)
	{
		?>
		<div class="col-12 col-lg-8 offset-lg-2">
			<div class="alert color-black mb-2 text-center" role="alert" style="color:#475F7B!important;border: #475F7B 1px solid!important;">
				<p class="font-18 weight600 mb5">Inventory is limited & prices are changing too fast for your date & time of travel</p>
				<p class="mb5">we will show you price ranges for cars. As always, we will provide you a final price before you book.</p>
				<p class="weight400 hide mb5">Dear customer, since your current pick up time falls during off hours, a cab may not be available. 
					You may change the pickup time to 8:00 a.m. or later to ensure the cab is confirmed.</p>
			</div>
		</div>
	<?php } ?>
	<div class="col-12 p0">
		<?php
		if (UserInfo::getUserId() > 0 && $isGozoNow != 1 && $userCredit > 0)
		{
			?>
			<p class="mb5 text-center"><span class="coin-text mb-1">Great! You have <img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> <span class="weight600"><?php echo $userCredit; ?></span> Gozo coins. You can save upto <span class="weight600">₹<?php echo max($coinCanUseArr); ?></span> using these Gozo coins.</span></p>
		<?php } ?>
		<p class="merriw heading-line text-center" id="cabcatheading">Select your preferred cab category</p>
		<div class="row">
			<div class="col-12 col-sm-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
				<div class="row m0 justify-content-center">
					<ul class="list-unstyled mb0">
						<?php
						$tierchecked = '';
						foreach ($tiers as $tier)
						{
							if ($tier->scvVehicleServiceClass == 6)
							{
								$tierchecked = "checked";
							}
							else if ($tier->scvVehicleServiceClass == 1 && $tierchecked == "")
							{
								$tierchecked = "checked";
							}
							else
							{
								$tierchecked = "";
							}

							if ($tier->scvVehicleServiceClass == 1)
							{
								$tierInfo = $tncArr1[78];
							}
							if ($tier->scvVehicleServiceClass == 2)
							{
								$tierInfo = $tncArr1[79];
							}
							if ($tier->scvVehicleServiceClass == 4)
							{
								$tierInfo = $tncArr1[80];
							}
							if ($tier->scvVehicleServiceClass == 6)
							{
								$tierInfo = $tncArr1[81];
							}
							?>

							<li class="d-inline-block mr-2 mb-1">
								<div class="checkbox checkbox-success tooltip-panel">
									<input type="checkbox" id="checkbox<?= $tier->scvVehicleServiceClass ?>" class="checkbox-input"  name="tierCheckbox[]" value="<?= $tier->scvVehicleServiceClass ?>" <?php echo $tierchecked; ?> >
									<label for="checkbox<?= $tier->scvVehicleServiceClass ?>" class="text-uppercase font-13 weight500"><?= $tier->catClass ?></label>
									<span data-toggle="tooltip" data-html="true" data-placement="top" title='<?= $tierInfo ?>'><img src="/images/bx-info-circle.svg" alt="img" width="14" height="14"></span>
								</div>
							</li>

						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container mb-2">



		<div class="col-12 text-center mb-2 style-widget-1">
	<!--	    <p class="merriw heading-line" id="cabcatheading">Select your preferred cab category</p>-->
		</div>
		<?php if($objBooking->tripType!=4 && $objBooking->tripType!=12){?>
		<div  class="col-12 text-center mb-2 style-widget-1font-24 merriw font-24 danger hide" id="errClassSelect">Please select class</div>
		<?}?>
		<div class="row" style="display: flex;flex-wrap: wrap;justify-content:center;">
			<?php
			if ($objBooking != null && $objBooking->cab != null)
			{
				$selectedValue = $objBooking->cab->categoryId;
			}

			$prefCategory = ($prefCategoryData->serviceClassId != '') ? $prefCategoryData->serviceClassId : 1;

			if (!$selectedValue)
			{
				$selectedValue = $prefCategory;
			}

			$arrCabCatDisplayed = [];
			foreach ($quotes2 as $key => $value)
			{
				$key1 = substr($key, strrpos($key, '_') + 1);
				if ($key1 == '')
				{
					continue;
				}


				$value1 = $this->pageRequest->sortServiceTier($key1);
				foreach ($value1 as $key => $cab)
				{
					
					//$val	 = array_values($cab);
					$cabRate = $cab; //$val[0];
					if ($cabRate->cab->cabCategory->scvVehicleId == 1 && $cabRate->cab->cabCategory->scvVehicleServiceClass == 4)
					{
						continue;
					}
					$objFare = $cabRate->fare;
					if ($cabRate->discountedFare != null)
					{
						$objFare = $cabRate->discountedFare;
					}


					$cabCategory = $cabRate->cab->cabCategory->scvVehicleId;
					if (!$cabCategory || (($objBooking->tripType == 4 || $objBooking->tripType == 12)  && (!in_array($cabCategory, [1,2,3]) || in_array($cabCategory,$arrCabCatDisplayed))))
					{
						continue;
					}
					$hideCab = 'hide';
					//for airports
					if($objBooking->tripType == 4 || $objBooking->tripType == 12){
						$arrCabCatDisplayed[] = $cabCategory;
						$hideCab = '';
					}
					
					$selected = ($selectedValue == $cabCategory) ? "checked" : "";
					if ($firstItem == '')
					{
						$firstItem = $cabCategory;
					}

					$vctModel	 = VehicleCategory::model()->findByPk($cabRate->cab->cabCategory->scvVehicleId);
					$cabCls		 = "cabcategory" . $cabRate->cab->cabCategory->scvVehicleServiceClass;

					if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 1)
					{
						$colorclass = "bg-orange color-white";

						$defaultval = $tncArr1[78];
					}
					if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 2)
					{
						$colorclass	 = "bg-blue color-white";
						$defaultval	 = $tncArr1[79];
					}
					if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 4)
					{
						$colorclass	 = "bg-blue5 color-white";
						$defaultval	 = $tncArr1[80];
					}
					if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 6)
					{
						$colorclass	 = "bg-green2 color-white";
						$defaultval	 = $tncArr1[81];
					}

					$key	 = 'percentage';
					$usage	 = Config::getValue("gozocoin.promo.usage", $key);

					$coinCanUse = 0;
					if (UserInfo::getUserId() > 0)
					{
						$userCredituse	 = UserCredits::getMaxApplicablePromoCredits(UserInfo::getUserId(), $objFare->baseFare, $usage / 100);
						$coinCanUse		 = $userCredituse['totalMaxApplicable'];
					}
					$cardClick = '';
					if($objBooking->agentId != Config::get('Mobisign.partner.id'))
					{
						$cardClick = 'onclick="checkTierQuotes('.$cabRate->cab->id.');" style="cursor: pointer;"';
					}
					?>
					<div class="col-xl-3 col-md-4 col-sm-12 flex2 cb-none ct-1 ct-2  <?=$hideCab?> <?= $cabCls ?>">
						<div class="card text-center pt-1" <?=$cardClick?> >
							<div class="cat-top">
								<?php
								$gozoRecomends = ServiceClass::getUpperClass($prefCategory);
								if ($gozoRecomends == $cabRate->cab->cabCategory->scvVehicleServiceClass && $prefCategoryData->VehicleCategoryId == $cabRate->cab->cabCategory->scvVehicleId)
								{
									?>
									<div class="cat-1"><span>Gozo recommends</span></div>
									<?php
								}

								if ($prefCategory == $cabRate->cab->cabCategory->scvVehicleServiceClass && $prefCategoryData->VehicleCategoryId == $cabRate->cab->cabCategory->scvVehicleId)
								{
									?>
									<div class="cat-1"><span>Most commonly chosen</span></div>
								<?php } ?>
							</div>
							<span class="info-right"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $defaultval . 'Accomodates upto ' . $cabRate->cab->bagCapacity . ' mid-sized bags and ' . $cabRate->cab->seatingCapacity . ' adults' ?>" class="btn color-green p5"><img src="/images/bx-info-circle2.svg" alt="img" width="24" height="24"></a></span>
							<span class="text-center mt-2"> <img src="<?= "/" . $vctModel->vct_image ?>" width="150" class="img-fluid" alt="singleminded"></span>
							<div class="card-header text-center p10 pb0" style="display: inline-block;">
								<p class="text-center heading-line-2 mb0 text-uppercase"><?= $vctModel->vct_label ?> 
								<?php 
									if($objBooking->agentId != Config::get('Mobisign.partner.id')) 
									{
	                             ?>
								<span class="badge badge-pill <?= $colorclass ?> badge-new"><?= $cabRate->cab->cabCategory->catClass ?></span>
									<?php } ?>
								</p>
							</div>
							<div class="card-body p10 pt0">
								<div class="d-flex">

									<div class="flex-fill">
										<p>
											<span data-toggle="tooltip" data-placement="top" title="<?php echo $vctModel->vct_capacity . " passengers"; ?>"><img src="/images/bxs-group.svg" alt="img" width="14" height="14"><span class="font-16 weight600 pr5"><?= $vctModel->vct_capacity; ?></span></span>
											<span data-toggle="tooltip" data-placement="top" title="<?php echo $cabRate->cab->bagCapacity . " bags"; ?>"><img src="/images/bxs-shopping-bag.svg" alt="img" width="14" height="14"><span class="font-16 weight600 pr5"><?= $cabRate->cab->bagCapacity; ?></span></span>
											<img src="/images/bxs-tachometer.svg" alt="img" width="14" height="14"><span class="font-14 weight600"><?= $cabRate->distance; ?> km</span>
										</p>
									</div>

								</div>

								<?php $details = $this->renderPartial("fareBrkup", ['serviceTier' => $cabRate, 'routes' => $objBooking->routes], true); ?>


								<?php
								if ($isGozoNow != 1)
								{
									$discountedBaseFare = $objFare->baseFare - $objFare->discount;

									if ($objBooking->agentId == Config::get('Mobisign.partner.id'))
									{
										$objFare->discount = 0;
										$discountedBaseFare = $objFare->totalAmount;
									}
									?>
									<p class="mb-0">
										<span class="font-13 del-diagonal color-gray <?= ($cabRate->fare->baseFare == ($objFare->baseFare - $objFare->discount)) ? "hide" : "" ?>"><?php echo Filter::moneyFormatter($cabRate->fare->baseFare); ?></span>
										<span class="font-16 weight600"><?php echo Filter::moneyFormatter($discountedBaseFare); ?></span>
										<span data-toggle="tooltip" data-html="true" data-placement="top" title='<?= $details ?>'><img src="/images/bx-info-circle.svg" alt="img" width="14" height="14"></span>
									</p>
									<?php
								}
								if ($coinCanUse > 0 && $isGozoNow != 1 && $objBooking->agentId != Config::get('Mobisign.partner.id'))
								{
									?>
									<p class="mb-0">or Pay <span class="weight600"><?php echo Filter::moneyFormatter($objFare->baseFare - $coinCanUse) . ' + ' . '<img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> ' . $coinCanUse; ?></span></p>
									<?php
								}
								$cntRoutes		 = count($objBooking->routes);
								$tcity			 = ($cntRoutes > 1) ? $objBooking->routes[$cntRoutes - 1]->destination->code : $objBooking->routes[0]->destination->code;
								$fcity			 = $objBooking->routes[0]->source->code;
								$tripType		 = $objBooking->tripType;
								$cancelRuleId	 = CancellationPolicy::getCancelRuleId(null, $cabRate->cab->id, $fcity, $tcity, $tripType);
								if ($cancelRuleId)
								{
									$cancelText = CancellationPolicy::getCancelTimeText($cancelRuleId, $objBooking->getPickupDate());
								}

								if ($model->bkg_is_gozonow == 1 || $cabRate->fare->minBaseFare > 0)
								{
									?>
									<p class="font-18 weight600"><?php echo Filter::moneyFormatter($cabRate->fare->minBaseFare) . ' - ' . Filter::moneyFormatter($cabRate->fare->maxBaseFare); ?></p>
									<?php
								}
								else
								{
									if ($objBooking->agentId != Config::get('Mobisign.partner.id'))
									{
									?>
									<p class="font-11 mb5 text-muted mb0 mr10 mt10">+<?php echo Filter::moneyFormatter($objFare->totalAmount - ($objFare->baseFare - $objFare->discount)) ?> in tolls, state tax, allowances, GST</p>									
									<?php
									}
									if ($cancelText != false && $cancelText != '')
									{
										?>
										<p class="font-11 mb5 text-muted mb0 mr10">Free cancellation <?= $cancelText ?></p>
										<?php
									}
									if (Yii::app()->user->isGuest && $objBooking->agentId != Config::get('Mobisign.partner.id'))
									{
										?> 
										<br /><span><b>Login to save upto 20%</b></span>
										<?php
									}
									?>
									</p>
								<?php } ?>

								<!--						<div class="radio-style3">
															<div class="radio">
																<input id="cabcategory<?= $cabCategory ?>" value="<?= $cabCategory ?>" type="radio" name="cabcategory" class="cabcategory">
																<label for="cabcategory<?= $cabCategory ?>"></label>
															</div>
														</div>-->
								<p class="weight400 mb5 lineheight18 bk-docs">
									<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">
									<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">
									<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">
									<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">
									<?php
									if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 4)
									{
										?>
										<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">

										<?php
									}
									else if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 2)
									{
										?>
										<img src="/images/bxs-star-half.svg" alt="Rating" width="18" height="18">
										<?php
									}
									else
									{
										?>
										<img src="/images/bx-star2.svg" alt="Rating" width="18" height="18">
									<?php } ?>
									<br>
									<?php
									$scvClass = $cabRate->cab->cabCategory->scvVehicleServiceClass;
									echo $objServiceClassDesc->$scvClass;
									?>
								</p>	
								<?php if($objBooking->agentId == Config::get('Mobisign.partner.id')){?>
									<div class="col-12 text-center mt10"><div class="badge badge-pill bg-orange border-none color-white weight500 mb5 font-14 pt10 pb10" type="button" onclick="checkTierQuotes(<?= $cabRate->cab->id ?>);">Book Now</div></div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-4 p0 col-sm-12 flex2 cs-none ct-1 ct-2  <?=$hideCab?> <?= $cabCls ?>" <?=$cardClick?>>
						<div class="card mb-2"  >
							<div class="radio-style7">
								<div class="radio">
									<label>
										<div class="row m0">
											<div class="col-12 ct-2 p0">
												<p class="heading-line-2 mb0"><?= $vctModel->vct_label ?><span class="mb0 text-center cabInfoTooltip"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= $defaultval . 'Accomodates upto ' . $cabRate->cab->bagCapacity . ' mid-sized bags and ' . $cabRate->cab->seatingCapacity . ' adults' ?>" class="btn color-green p0 pl5 align-middle lineheight36"><img src="/images/bx-info-circle2.svg" alt="img" width="20" height="20"></a></span></p>
											</div>
											<div class="cat-main">
												<?php
												if ($gozoRecomends == $cabRate->cab->cabCategory->scvVehicleServiceClass && $prefCategoryData->VehicleCategoryId == $cabRate->cab->cabCategory->scvVehicleId)
												{
													?>
													<div class="cat-1"><span>Gozo recommends</span></div>

													<?php
												}
												if ($prefCategory == $cabRate->cab->cabCategory->scvVehicleServiceClass && $prefCategoryData->VehicleCategoryId == $cabRate->cab->cabCategory->scvVehicleId)
												{
													?>
													<div class="cat-2"><span>Most commonly chosen</span></div>
												<?php } ?>
											</div>
											<div class="col-5 p0"><span class="text-center"> <img src="<?= "/" . $vctModel->vct_image ?>" width="100" class="img-fluid" alt="singleminded"></span></div>
											<div class="col-7 p0 text-right" style="margin-top: -30px;">
											<?php 
												if($objBooking->agentId != Config::get('Mobisign.partner.id')) 
												{
	                                        ?>
												<div class="badge badge-pill <?= $colorclass ?> weight500 mb5 pl10 pr10 font-10"><?= $cabRate->cab->cabCategory->catClass ?></div>
											<?php } ?>
												<?php
												if ($model->bkg_is_gozonow == 0)
												{
														$discountedBaseFare = $objFare->baseFare - $objFare->discount;

														if ($objBooking->agentId == Config::get('Mobisign.partner.id'))
														{
															$objFare->discount = 0;
															$discountedBaseFare = $objFare->totalAmount;
														}
													?>
													<p class="mb-0 ">
														<span class="font-13 del-diagonal color-gray <?= ($cabRate->fare->baseFare == ($objFare->baseFare - $objFare->discount)) ? "hide" : "" ?>"><?php echo Filter::moneyFormatter($cabRate->fare->baseFare); ?></span>&nbsp;
														<span class="font-18 weight600"><?php echo Filter::moneyFormatter($discountedBaseFare); ?></span>
														<span class="cabInfoTooltip" data-toggle="tooltip" data-html="true" data-placement="top" title='<?= $details ?>'><img src="/images/bx-info-circle.svg" alt="img" width="14" height="14"></span>
													</p>
													<?php
												}
												if ($coinCanUse > 0 && $model->bkg_is_gozonow == 0)
												{
													?>
													<p class="mb-0">or Pay <span class="weight600"><?php echo Filter::moneyFormatter($objFare->baseFare - $coinCanUse) . ' + ' . '<img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> ' . $coinCanUse; ?></span></p>
													<?php
												}
												if ($model->bkg_is_gozonow == 1)
												{
													?>
													<p class="font-18 weight600"><?php echo Filter::moneyFormatter($cabRate->fare->minBaseFare) . ' - ' . Filter::moneyFormatter($cabRate->fare->maxBaseFare); ?></p>
													<?php
												}
												else
												{
													if ($objBooking->agentId != Config::get('Mobisign.partner.id'))
													{
													?>
													<p class="font-11 mb5 text-muted mb0 mr10">+<?php echo Filter::moneyFormatter($objFare->totalAmount - ($objFare->baseFare - $objFare->discount)) ?> taxes & fees</p>
													<?php
													}
													//if (Yii::app()->user->isGuest)
													//{
													?> 
			<!--														<br /><span><b>Login to save upto 20%</b></span>-->
													<?php
													//}
												}
												?>



											</div>
											<div class="col-12 text-center mt10">
												<?php
												if ($model->bkg_is_gozonow == 0 && Yii::app()->user->isGuest && $objBooking->agentId != Config::get('Mobisign.partner.id'))
												{
													?>
													<p class="mb0"><b>Login to save upto 20%</b></p>

												<?php } ?>
												
												<p class="weight400 mb5 lineheight18 bk-docs hide">
													<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">
													<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">
													<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">
													<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">
													<?php
													if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 4)
													{
														?>
														<img src="/images/bxs-star.svg" alt="Rating" width="18" height="18">

														<?php
													}
													else if ($cabRate->cab->cabCategory->scvVehicleServiceClass == 2)
													{
														?>
														<img src="/images/bxs-star-half.svg" alt="Rating" width="18" height="18">
														<?php
													}
													else
													{
														?>
														<img src="/images/bx-star2.svg" alt="Rating" width="18" height="18">
													<?php } ?>
													<br>
													<?php
													$scvClass = $cabRate->cab->cabCategory->scvVehicleServiceClass;
											//		echo $objServiceClassDesc->$scvClass;
													?>
												</p>
											</div>
											<?php
											if ($cancelText != false && $cancelText != '')
											{
												?>
<!--												<div class="col-12 font-11 mb5 text-muted mb0 mr10 text-center">Free cancellation <?= $cancelText ?></div>-->
												<?php
											}
											?>
											<?php if($objBooking->agentId == Config::get('Mobisign.partner.id')){?>
											<div class="col-7 pl0 mt20">
													<span data-toggle="tooltip" data-placement="top" title="<?php echo $vctModel->vct_capacity . " passengers"; ?>"><img src="/images/bxs-group.svg" alt="img" width="18" height="18"><span class="font-14 weight600 pr5 align-middle"><?= $vctModel->vct_capacity; ?></span></span>
													<span data-toggle="tooltip" data-placement="top" title="<?php echo $cabRate->cab->bagCapacity . " bags"; ?>"><img src="/images/bxs-shopping-bag.svg" alt="img" width="18" height="18"><span class="font-14 weight600 pr5"><?= $cabRate->cab->bagCapacity; ?></span></span>
													<img src="/images/bxs-tachometer.svg" alt="img" width="18" height="18"><span class="font-14 weight600"><?= $cabRate->distance; ?> km</span>
											</div>
											<div class="col-5 mt10 pr0 text-right"><div class="badge badge-pill bg-orange border-none color-white weight500 mb5 font-16 pt10 pb10" type="button" onclick="checkTierQuotes(<?= $cabRate->cab->id ?>);">Book Now</div></div>
								<?php } ?>
										</div>
									</label>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}
			?>

			<div class="col-12 cc-1 ">
				<div class="row m0 justify-center cc-2">
					<div class="col-xl-12 text-center">
						<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
	<!--					<input type="button" value="Go back" step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">-->
						<input type="hidden" name="pageID" id="pageID" value="7">
	<!--					<input type="submit" value="Next" name="yt0" id="servicetypebtn" class="btn btn-primary pl-5 pr-5 showcabdetails">-->
						<input type="hidden" name="step" value="<?= $pageid ?>">
						<input type="hidden" name="cabclass" id="cabsvcId" value="0">
					</div>
					<div class="col-12 col-lg-10 offset-lg-1 mt10 hide">
						<div class="row">
							<div class="col-2 col-lg-2 roundimage hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
							<div class="col-10 col-lg-10 d-lg-none d-xl-none"><marquee class="cabcontent" direction="up" height="50px" scrollamount="1"></marquee></div>
							<div class="col-10 col-lg-10 cabcontent d-none d-lg-block"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12">
				<div class="row mb-1">

				</div>
			</div>
		</div>
	</div>
	<?php
	skipQuotes:
	$this->endWidget();

	if (!$quotes2)
	{
		?>
		<div class="row justify-center">
			<div class="col-12">
				<div class="row mb-1 justify-center">
					<div class="col-12 col-md-6 col-xl-5 text-center mt-2">
						<div class="alert border-primary alert-dismissible mb-2 font-16 line-height24">
							Sorry, there is no cab available for that date and time you have requested. Our contact center team can help plan your trip.
						</div>

					</div>
				</div>
			</div>
			<div class="col-12 col-md-10 col-xl-8 text-center mt-2">
				<a type="button" class="btn btn-primary btn-float font-12 pl10 pr10 hvr-push" onClick="return reqCMB(1)">Tap here to request a call back from our team</a>
			</div>
		</div>
	<?php } ?>
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
	<script type="text/javascript">

		isAirport = '<?php echo $objPage->booking->tripType; ?>';
        $(document).ready(function ()
        {
			
			step = <?= $step ?>;
			tabURL = "<?= $this->getURL($objPage->getQuoteURL()) ?>";
			pageTitle = "";
			tabHead = "<?= $this->pageRequest->getItineraryDesc() ?>";
			toggleStep(step, 5, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);
			getTraveller(<?php echo $objPage->booking->id;?>);
<?php
if ($quotes2)
{
	?>
	            var isMobile = '<?php echo Yii::app()->mobileDetect->isMobile(); ?>';
	            if (isMobile == 1)
	            {
	                $('#1cabcategory<?= $gozoRecomends ?>').click();
	            } else
	            {
	                $('#cabcategory<?= $gozoRecomends ?>').click();
	            }
<?php } ?>
            showBack();

            onTierSelected();

            $('.cabInfoTooltip').click(function (evt)
            {
                evt.stopPropagation();
            });
            $("body").removeClass('modal-open');
        });

        $("input[type=radio][name='cabcategory']").change(function ()
        {
            var val = $("input[name='cabcategory']:checked").val();

            if (val == 1)
            {
                var defaultVal = 74;
            } else if (val == 2)
            {
                var defaultVal = 75;
            } else if (val == 3)
            {
                var defaultVal = 76;
            } else if (val == 4)
            {
                var defaultVal = 77;
            }

            var tncval = JSON.parse('<?= $tncArr ?>');
            $('.cabcontent').html(tncval[defaultVal]);
            $('.roundimage').removeClass('hide');
        });


        function checkTierQuotes(cabSelected)
        {

            var isUserName = $('input[name=isUserName]').val();
            if (isUserName == 1)
            {
                $('.travellerinfo').click();
                return false;
            }
            $('#cabsvcId').val(cabSelected).trigger('change');
            var form = $("form#cabcategory");
            $.ajax({
                "type": "POST",
                "dataType": "html",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/tierQuotes')) ?>",
                "data": form.serialize(),
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
						$('#myAddressModal .modal-body1').html(data2);
						$('#myAddressModal .modal-body1').show();
						$('#myAddressModal .modal-body').hide();
						$('#myAddressModal').removeClass('full-screen');
						$('#myAddressModal').addClass('bootbox');
						$('#myAddressModal').addClass('fade');
						$('#myAddressModal').addClass('show');
						$('.modal-backdrop').last().css("display", "block");
						$('#myAddressModal').modal().show();
					}
					else
					{	
						if(data.success && data.isAddressSaved == 1)
						{
							paymentreview(data.hash);
							return;
						}

						if (data.success)
						{
							location.href = data.data.url;
							return;
						}
						
						var errors = data.errors;
						msg = JSON.stringify(errors);
						settings = form.data('settings');
						$.each(settings.attributes, function(i)
						{
							$.fn.yiiactiveform.updateInput(settings.attributes[i], errors, form);
						});
						$.fn.yiiactiveform.updateSummary(form, errors);
						messages = errors;
						content = '';
						var summaryAttributes = [];
						for (var i in settings.attributes)
						{
							if (settings.attributes[i].summary)
							{
								summaryAttributes.push(settings.attributes[i].id);
							}
						}
						displayFormError(form, messages);
					}
				},
				error: function(xhr, ajaxOptions, thrownError)
				{	
					if (xhr.status == "403")
					{
						handleException(xhr, function()
						{
							
						});
					}
				}
			});
			return false;
		}

        $("input[type=checkbox][name='tierCheckbox[]']").change(function ()
        {
            onTierSelected();
        });

        function onTierSelected()
        {
            
            var selected = [];
            if (!$('#cabcatheading').hasClass('hide'))
            {
                $('#cabcatheading').addClass('hide');
            }
            if (!$('#errClassSelect').hasClass('hide'))
            {
                $('#errClassSelect').addClass('hide');
            }
            $("input[type=checkbox][name='tierCheckbox[]']").each(function ()
            {
                let tier = $(this).attr('value');
                if (!$('.cabcategory' + tier).hasClass('hide'))
                {
                    $('.cabcategory' + tier).addClass('hide');
                }
                if ($(this).is(":checked"))
                {
                    $('.cabcategory' + tier).removeClass('hide');
                    $('#cabcatheading').removeClass('hide');
                    selected.push($(this).val());
                }
            });
            if (selected.length == 0)
            {
                $('#errClassSelect').removeClass('hide');
            }
			if(isAirport == 4 || isAirport == 12)
			{
				$('#cabcatheading').removeClass('hide');
			}
        }

        $(function ()
        {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $(".travellerinfo").click(function ()
        {

            var href2 = "<?php echo Yii::app()->createUrl('booking/travellerinfo') ?>";
            var rdata = $("#cabcategory").closest("form").find("INPUT[name=rdata]").val();
            $.ajax({
                "url": href2,
                data: {'rdata': rdata, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
                "type": "POST",
                "dataType": "html",
                //async:false,
                "success": function (data)
                {

                    //clsusername
                    $('#bkCommonModelHeader').text('Traveller Info');
                    $('#bkCommonModelBody').html(data);
                    $('#bkCommonModel').modal('show');
                },
                "error": function (xhr, ajaxOptions, thrownError)
                {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
            return false;

        });
		
		
		
		function getTraveller(booking_id)
		{
			var href2 = "<?php echo Yii::app()->createUrl('booking/traveller') ?>";
			$.ajax({
				"url": href2,
				data: {'booking_id': booking_id, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
				"type": "POST",
				"dataType": "json",
				"success": function(data)
				{
					
					$('.clsusername').text(data.username);
				},
				"error": function(xhr, ajaxOptions, thrownError)
				{
					alert(xhr.status);
					alert(thrownError);
				}
			});
		}
		
		function launchRazorpay1(data3)
		{
			var options = {
				"key": data3.key, // Enter the Key ID generated from the Dashboard
				"amount": data3.amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
				"currency": data3.currency,
				"name": "Gozocabs",
				"description": "Make Payment",
				"image": "https://www.gozocabs.com/images/gozo_svg_logo.svg?v0.1",
				"order_id": data3.order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
				"handler": function(response)
				{
					onResponseRecieved(response, data3.callbackUrl);
				},
				"prefill": {
					"name": data3.name,
					"email": data3.email,
					"contact": data3.contact
				},
				"notes": {
					"trnsCode": data3.trnsCode
				},
				"theme": {
					"color": "#F37254"
				}
			};
			var rzp1 = new Razorpay(options);
			rzp1.on('payment.failed', function(response)
			{
				onResponseRecieved(response, data3.callbackUrl);

			});
			$('.razorpay-payment-button').click();
			$('.razorpay-payment-button').hide();
			rzp1.open();
		}
	
		function onResponseRecieved(response, url)
		{
			var stringResponse = JSON.stringify(response);
			$.ajax({
				"type": "POST",
				"dataType": "json",
				"url": url,
				"beforeSend": function()
				{
					ajaxindicatorstart("Please wait while we confirm your payment status");
				},
				"data": {"response": stringResponse},
				"success": function(data)
				{
					ajaxindicatorstart("Redirecting...");
					window.location.href = data.url;
				},
				"error": function(xhr, ajaxOptions, thrownError)
				{
					ajaxindicatorstop();

					var msg = '';
					if (xhr.status === 0)
					{
						msg = 'Not connect.\n Verify Network.';
					}
					else if (xhr.status == 404)
					{
						msg = 'Requested page not found. [404]';
					}
					else if (xhr.status == 500)
					{
						msg = 'Internal Server Error [500].';
					}
					else if (exception === 'parsererror')
					{
						msg = 'Requested JSON parse failed.';
					}
					else if (exception === 'timeout')
					{
						msg = 'Time out error.';
					}
					else if (exception === 'abort')
					{
						msg = 'Ajax request aborted.';
					}
					else
					{
						msg = 'Uncaught Error.\n' + xhr.responseText;
					}
					var txt = "<ul style='list-style:none'>";
					
					txt += "<li>" + msg + "</li>";
					
					txt += "</ul>";
					$(".alertpayment").html(txt);
					$(".alertpayment").removeClass('hide');
					$(".alertpayment").show();
				}
			});
		}
		
		function paymentreview(hash)
		{
			var form = $("form#cabcategory");
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
				"success": function(data)
				{
							$('#myAddressModal .modal-body1').html(data);
							$('#myAddressModal .modal-body1').show();
							$('#myAddressModal .modal-body').hide();
							$('#myAddressModal').removeClass('full-screen');
							$('#myAddressModal').addClass('bootbox');
							$('#myAddressModal').addClass('fade');
							$('#myAddressModal').addClass('show');
							$('.modal-backdrop').last().css("display", "block");
							$('#myAddressModal').modal().show();
					
				},
				"error": function(xhr, ajaxOptions, thrownError)
				{
					alert(xhr.status);
					alert(thrownError);
				}
			});
		}
	</script>