<?php
/** @var BookFormRequest $objPage */
$objPage	 = $this->pageRequest;
/** @var Stub\common\Booking $objBooking */
$objBooking	 = $objPage->booking;

$tncType = TncPoints::getTncIdsByStep(8);
$tncArr	 = TncPoints::getTypeContent($tncType);

$coinCanUseArr	 = [];
$maxCoin		 = '';
if (UserInfo::getUserId() > 0)
{
	foreach ($tierBrkUp as $tier)
	{
		$key	 = 'percentage';
		$usage	 = Config::getValue("gozocoin.promo.usage", $key);

		$coinCanUse	 = 0;
		$objFare	 = $tier->fare;
		if (UserInfo::getUserId() > 0)
		{
			$userCredituse	 = UserCredits::getMaxApplicablePromoCredits(UserInfo::getUserId(), $objFare->baseFare, $usage / 100);
			$coinCanUse		 = $userCredituse['totalMaxApplicable'];
			$coinCanUseArr[] = $coinCanUse;
		}
	}
}


//$objPage->booking->isGozoNow




/** @var CActiveForm $form */
$form = $this->beginWidget('CActiveForm', array(
	'id'					 => 'catTierForm',
	'enableClientValidation' => false,
	'clientOptions'			 => [
		'validateOnSubmit'	 => false,
		'errorCssClass'		 => 'has-error',
	],
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'action'				 => Yii::app()->createUrl('booking/tierQuotes'),
	'htmlOptions'			 => array(
		"onsubmit"		 => "return checkServiceClass();",
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off',
	),
		));

$cabCategory		 = $objBooking->cab->categoryId;
$tierBrkUp			 = $this->pageRequest->sortServiceTier($cabCategory);
$serviceClassDesc1	 = Config::get('booking.service.class.description');
$objServiceClassDesc = json_decode($serviceClassDesc1);
if (count($tierBrkUp) > 0)
{
	$cabtype	 = "";
	$firstItem	 = array_key_first($tierBrkUp);
	$cabtype	 = $tierBrkUp[$firstItem]->cab->cabCategory->type;
	?>
	<div class="container mb-2">
		<div class="alert alert-danger mb-2 text-center hide alertcabclass" role="alert"></div>

		<div class="col-12 text-center mb-1 style-widget-1">
			<?php
			if (UserInfo::getUserId() > 0 && $model->bkg_is_gozonow != 1 && $userCredit > 0)
			{
				?>
				<p class="mb5"><span class="coin-text">Great! You have <img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> <span class="weight600"><?php echo $userCredit; ?></span> Gozo coins. You can save upto <span class="weight600">₹<?php echo max($coinCanUseArr); ?></span> using these Gozo coins.</span></p>
			<?php } ?>
			<p class="merriw heading-line">Select your preferred class of service for your <?php echo VehicleCategory::model()->getNameById($this->pageRequest->booking->cabServiceCategory); ?></p>



		</div>

		<div class="row justify-center" style="display: flex; flex-wrap: wrap;">
			<?php
			$availablecabs = [];
			foreach ($tierBrkUp as $tier)
			{
				$availablecabs[$tier->cab->cabCategory->id] = $tier->cab->cabCategory->catClass;
			}
			$firstSelected = "";
			if (array_key_exists(2, $availablecabs))
			{
				$firstSelected = 2;
			}
			elseif (array_key_exists(3, $availablecabs))
			{
				$firstSelected = 3;
			}
			else
			{
				$firstElement	 = array_key_first($tierBrkUp);
				$firstSelected	 = $tierBrkUp[$firstElement]->cab->id;
			}

			$prefCategory = ($prefCategory->serviceClassId != '') ? $prefCategory->serviceClassId : 1;
			/** @var \Stub\common\CabRate[] $tierBrkUp */
			foreach ($tierBrkUp as $tier)
			{
				$objFare = $tier->fare;
				if ($tier->discountedFare != null)
				{
					$objFare = $tier->discountedFare;
				}

//echo $tire->cab->cabCategory->catClassRank;
				$class = ($tier->cab->cabCategory->scvVehicleServiceClass == 4 ) ? "scvServiceClass4" : "scvServiceClass";

				//$discountedBaseFare	 = $objFare->baseFare - $objFare->discount;
				$key	 = 'percentage';
				$usage	 = Config::getValue("gozocoin.promo.usage", $key);

				$coinCanUse = 0;
				if (UserInfo::getUserId() > 0)
				{
					$userCredituse	 = UserCredits::getMaxApplicablePromoCredits(UserInfo::getUserId(), $objFare->baseFare, $usage / 100);
					$coinCanUse		 = $userCredituse['totalMaxApplicable'];
				}
				?>
				<div class="hide"><? #= print_r($objFare)                    ?></div>
				<div class="col-xl-3 col-md-6 col-sm-12 flex2 cb-none ct-1 ct-2" id="tooltip-positions">
					<div class="card text-center pt-1">
						<div class="cat-top">
							<?php
							$gozoRecomends = ServiceClass::getUpperClass($prefCategory);
							if ($gozoRecomends == $tier->cab->cabCategory->scvVehicleServiceClass)
							{
								?>
								<div class="cat-1"><span>Gozo recommends</span></div>
								<?php
							}

							if ($prefCategory == $tier->cab->cabCategory->scvVehicleServiceClass)
							{
								?>
								<div class="cat-1"><span>Most commonly chosen</span></div>
							<?php } ?>
						</div>
						<div class="card-header text-center pt10 pb-1 mt-2" style="display: inline-block;">
							<h4 class="card-title text-center weight500 text-uppercase"><?= $tier->cab->cabCategory->catClass ?></h4>
							<img src="/images/bxs-star.svg" alt="img" width="18" height="18">
							<img src="/images/bxs-star.svg" alt="img" width="18" height="18">
							<img src="/images/bxs-star.svg" alt="img" width="18" height="18">
							<img src="/images/bxs-star.svg" alt="img" width="18" height="18">
							<?php
							if ($tier->cab->cabCategory->scvVehicleServiceClass == 4)
							{
								?>
								<img src="/images/bxs-star.svg" alt="img" width="18" height="18">

								<?php
							}
							else if ($tier->cab->cabCategory->scvVehicleServiceClass == 2)
							{
								?>
								<img src="/images/bxs-star-half.svg" alt="img" width="18" height="18">
								<?php
							}
							else
							{
								?>
								<img src="/images/bx-star2.svg" alt="img" width="18" height="18">
							<?php } ?>
						</div>
						<div class="card-body">
							<?php $scvClass		 = $tier->cab->cabCategory->scvVehicleServiceClass; ?>
							<p class="weight400 mb0 lineheight16 bk-docs"><?php echo $objServiceClassDesc->$scvClass; ?></p>
							<div class="col-12 p0 pt5 pb10"><span class="mr15" data-toggle="tooltip" data-placement="top" title="<?php echo $tier->cab->bagCapacity . " bags"; ?>"><?php echo $tier->cab->bagCapacity; ?><img src="/images/bxs-shopping-bag.svg" alt="img" width="14" height="14"></span><span data-toggle="tooltip" data-placement="top" title="<?php echo $tier->cab->seatingCapacity . " passengers"; ?>"><?php echo $tier->cab->seatingCapacity; ?><img src="/images/bxs-group.svg" alt="img" width="14" height="14"></span><img src="/images/bxs-tachometer.svg" alt="img" width="14" height="14"> <span><?= $tier->distance ?> km</span></div>
							<?php
							$cntRoutes		 = count($objBooking->routes);
							$tcity			 = ($cntRoutes > 1) ? $objBooking->routes[$cntRoutes - 1]->destination->code : $objBooking->routes[0]->destination->code;
							$fcity			 = $objBooking->routes[0]->source->code;
							$tripType		 = $objBooking->tripType;
							$cancelRuleId	 = CancellationPolicy::getCancelRuleId(null, $tier->cab->id, $fcity, $tcity, $tripType);
							if ($cancelRuleId)
							{
								$cancelText = CancellationPolicy::getCancelTimeText($cancelRuleId, $objBooking->getPickupDate());
							}

							if ($model->bkg_is_gozonow == 1 || $tier->fare->minBaseFare > 0)
							{
								$taxAmount	 = 0;
								$arrTaxStr	 = [];
								if ($tier->fare->tollIncluded)
								{
									$taxAmount	 += $tier->fare->tollTax;
									$arrTaxStr[] = 'toll tax';
								}
								if ($tier->fare->stateTaxIncluded)
								{
									$taxAmount	 += $tier->fare->stateTax;
									$arrTaxStr[] = 'state tax';
								}
								if ($tier->fare->parkingIncluded)
								{
									$taxAmount	 += $tier->fare->parkingCharge;
									$arrTaxStr[] = "parking charge up to (" . Filter::moneyFormatter($tier->fare->parkingCharge) . ")";
								}
								if ($tier->fare->airportChargeIncluded)
								{
									$taxAmount	 += $tier->fare->airportEntryFee;
									$arrTaxStr[] = 'airport entry fee';
								}

								$strTax = implode(", ", $arrTaxStr);
								?>						 
								<p class="mb0">
									<span class="font-20 weight600">
										<?php // echo Filter::moneyFormatter($tier->fare->minBaseFare) . ' - ' . Filter::moneyFormatter($tier->fare->maxBaseFare); ?>
										<?php echo Filter::moneyFormatter($tier->fare->minTotalAmount) . ' - ' . Filter::moneyFormatter($tier->fare->maxTotalAmount); ?>
									</span>
									<?php
									if ($strTax != '')
									{
										$taxString = $strTax . " included";
										if ($taxAmount > 0)
										{
											$taxString = "included " . Filter::moneyFormatter($taxAmount) . " $strTax";
										}
										echo "<br><span style='font-size: 11px'>($taxString)</span>";
									}
									?>

								</p>
								<?php
							}
							else
							{
								?>
								<span class="font-14 weight500 pr5" ><?php echo Filter::moneyFormatter($objFare->baseFare - $objFare->discount); ?><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" class="fair-breakup-modal" onclick="showFareBreakup('<?= $tier->cab->id ?>');"><img src="/images/bx-info-circle.svg" alt="img" width="13" height="13" class="pt10 pb10"></a></span> <span class="font-13 del-diagonal color-gray <?= ($tier->fare->baseFare == ($objFare->baseFare - $objFare->discount)) ? "hide" : "" ?>"><?php echo Filter::moneyFormatter($tier->fare->baseFare); ?></span>
								<?php
								if ($coinCanUse > 0 && $model->bkg_is_gozonow != 1)
								{
									?>
									<p class="mb0">or Pay 
										<span class="weight600"><?php echo Filter::moneyFormatter($objFare->baseFare - $coinCanUse) . ' + ' . '<img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> ' . $coinCanUse; ?></span></p>
								<?php } ?>
								<p class="font-11 mb5 text-muted mb0 mr10">+<?php echo Filter::moneyFormatter($objFare->totalAmount - ($objFare->baseFare - $objFare->discount)) ?> in tolls, state tax, allowances, GST</p>									
								<?php
								if ($cancelText != false && $cancelText != '')
								{
									?>
									<p class="font-11 mb5 text-muted mb0 mr10">Free cancellation <?= $cancelText ?></p>
									<?php
								}
								if (Yii::app()->user->isGuest)
								{
									?> 
									<br /><span><b>Login to save upto 20%</b></span>
									<?php
								}
								?>

							<?php } ?>
							<div class="radio-style3">
								<div class="radio">
									<input id="cabclass<?= $tier->cab->id ?>" data-class="<?= $tier->cab->cabCategory->scvVehicleServiceClass ?>" value="<?= $tier->cab->id ?>" type="radio" name="cabclass" class="<?= $class ?>">
									<label for="cabclass<?= $tier->cab->id ?>"></label>
									<input type="hidden" value="<?= $tier->cab->cabCategory->scvVehicleServiceClass ?>" class="vehicletype">
								</div>
								<div class="hide trvlbagcapcity<?= $tier->cab->id ?>">Accomodates upto <?php echo $tier->cab->bagCapacity; ?> mid-sized bags and <?php echo $tier->cab->seatingCapacity; ?> adults</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-md-6 p0 col-sm-12 flex2 cs-none ct-1 ct-2" id="tooltip-positions">
					<div class="card mb-2">
						<div class="radio-style7">
							<div class="radio">
								<input id="2cabclass<?= $tier->cab->id ?>" data-class="<?= $tier->cab->cabCategory->scvVehicleServiceClass ?>" value="<?= $tier->cab->id ?>" type="radio" name="cabclass" class="<?= $class ?>">
								<label for="2cabclass<?= $tier->cab->id ?>">
									<div class="cat-main">
										<?php
										$gozoRecomends = ServiceClass::getUpperClass($prefCategory);
										if ($gozoRecomends == $tier->cab->cabCategory->scvVehicleServiceClass)
										{
											?>
											<div class="cat-1"><span>Gozo recommends</span></div>
											<?php
										}

										if ($prefCategory == $tier->cab->cabCategory->scvVehicleServiceClass)
										{
											?>
											<div class="cat-2"><span>Most commonly chosen</span></div>
										<?php } ?>
									</div>
									<div class="row m0">
										<div class="col-4 ct-3 pr0">
											<div class="text-center" style="display: inline-block;">
												<p class="heading-line-2 text-uppercase mb0"><?= $tier->cab->cabCategory->catClass ?></p>
											</div>
											<div class="font-13 mt10"><?php echo $tier->cab->bagCapacity; ?><img src="/images/bxs-shopping-bag.svg" alt="img" width="13" height="13"></span>&nbsp;&nbsp;<span class="font-13"><?php echo $tier->cab->seatingCapacity; ?><img src="/images/bxs-group.svg" alt="img" width="13" height="13"><span class="font-24"><br></span><img src="/images/bxs-tachometer.svg" alt="img" width="13" height="13"><?= $objPage->quote->quotedDistance ?> km</div>
										</div>
										<div class="col-6 pr0 ct-3 text-right pl0 pt40">

											<?php
											if ($model->bkg_is_gozonow == 1)
											{
												?>
												<p class="mb0"><span class="font-20 weight600">
														<?php echo Filter::moneyFormatter($tier->fare->minBaseFare) . ' - ' . Filter::moneyFormatter($tier->fare->maxBaseFare); ?>
													</span></p>
												<?php
											}
											else
											{
												?>

												<p class="mt5 n mb0 text-right lineheight16"><span class="font-13 del-diagonal color-gray <?= ($tier->fare->baseFare == ($objFare->baseFare - $objFare->discount)) ? "hide" : "" ?>"><?php echo Filter::moneyFormatter($tier->fare->baseFare); ?>&nbsp;</span>
													<span class="font-14 weight500"><?php echo Filter::moneyFormatter($objFare->baseFare - $objFare->discount); ?><a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" class="fair-breakup-modal" onclick="showFareBreakup('<?= $tier->cab->id ?>');"><img src="/images/bx-info-circle2.svg" alt="img" width="13" height="13" class="pt10 pb10"></a></span>
												</p> 
												<?php
												if ($coinCanUse > 0 && $model->bkg_is_gozonow != 1)
												{
													?>
													<p class="mb0 font-12">or Pay <span class="weight600"><?php echo Filter::moneyFormatter($objFare->baseFare - $coinCanUse) . ' + ' . '<img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> ' . $coinCanUse; ?></span></p>
												<?php } ?>
												<p class="font-11 mb5 text-muted mb0">+<?php echo Filter::moneyFormatter($objFare->totalAmount - ($objFare->baseFare - $objFare->discount)) ?> taxes & fees</p>													
												<?php
												if (Yii::app()->user->isGuest)
												{
													?><p class="mt5 n mb0 text-right lineheight16"><span><b>Login to save upto 20%</b></span><?php } ?></p>
											<?php } ?>

										</div>

										<div class="col-12 mb10 mt10 text-center">
											<img src="/images/bxs-star.svg" alt="img" width="18" height="18" class="mr5">
											<img src="/images/bxs-star.svg" alt="img" width="18" height="18" class="mr5">
											<img src="/images/bxs-star.svg" alt="img" width="18" height="18" class="mr5">
											<img src="/images/bxs-star.svg" alt="img" width="18" height="18" class="mr5">
											<?php
											if ($tier->cab->cabCategory->scvVehicleServiceClass == 4)
											{
												?>
												<img src="/images/bxs-star.svg" alt="img" width="18" height="18" class="mr10">

												<?php
											}
											elseif ($tier->cab->cabCategory->scvVehicleServiceClass == 2)
											{
												?>
												<img src="/images/bxs-star-half.svg" alt="img" width="18" height="18" class="mr5">
												<?php
											}
											else
											{
												?>
												<img src="/images/bx-star2.svg" alt="img" width="18" height="18">
											<?php } ?>
											<p class="mb-0 lineheight18 bk-docs mb5"><?php echo $objServiceClassDesc->$scvClass; ?></p>
											<?php
											if ($cancelText != false && $cancelText != '')
											{
												?>
												<p class="font-11 mb5 text-muted mb0 mr10">Free cancellation <?= $cancelText ?></p>
											<?php } ?>
										</div>
										<div class="col-12"><?php $scvClass = $tier->cab->cabCategory->scvVehicleServiceClass; ?></div>

										<div class="col-12"><input type="hidden" value="<?= $tier->cab->cabCategory->scvVehicleServiceClass ?>" class="vehicletype"></div>
									</div>

								</label>

							</div>
						</div>

					</div>

				</div>
				<div>
					<?php echo $this->renderPartial("fareBrkup", ['serviceTier' => $tier, 'routes' => $objBooking->routes], true); ?>
				</div>

				<?php
			}
			?>
			<div class="col-12 cc-1">
				<div class="row m0 justify-center cc-2">
					<div class="col-xl-12 text-center">
						<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
						<input type="hidden" name="pageID" id="pageID" value="9">
			<!--<input type="hidden" name="step" id="step" value="8">-->
						<input type="hidden" name="scvSclass" id="scvSclass" value="">
						<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
						<input type="submit" value="Book Now" name="yt0" id="serviceclassbtn" class="btn btn-primary pl-5 pr-5 serviceclassbtn">
						<input type="hidden" name="step" value="<?= $step ?>">
					</div>
					<div class="col-12 col-lg-10 offset-lg-1 mt-2">
						<div class="row">
							<div class="col-2 col-lg-2 cabsegment hide"><div class="round-2"><img src="/images/img-2022/sonia.jpg" alt="Sonia" title="Sonia"></div></div>
							<div class="col-10 col-lg-10 d-lg-none d-xl-none"><marquee class="cabsegmentation" direction="up" height="50px" scrollamount="1"></marquee></div>
							<div class="col-10 col-lg-10 cabsegmentation d-none d-lg-block"></div>
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
}
$this->endWidget();
?>
<script>
	$(document).ready(function ()
	{
		step = <?= $step ?>;
		tabURL = "<?= $this->getURL($objPage->getTierURL()) ?>";
		pageTitle = "";
		tabHead = "<?= $this->pageRequest->getCabServiceCategoryDesc() ?>";
		toggleStep(step, 7, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);
		$('.scvServiceClass4').change(function ()
		{
			$("#scvSclass").val(4);
		});
		$('.scvServiceClass').change(function ()
		{
			$("#scvSclass").val("");
		});
<?php
if (count($tierBrkUp) > 0)
{
	?>
			var isMobile = '<?php echo Yii::app()->mobileDetect->isMobile(); ?>';
			if (isMobile == 1)
			{
				$('#2cabclass<?php echo $firstSelected; ?>').click();
			} else
			{
				$('#cabclass<?php echo $firstSelected; ?>').click();
			}

<?php } ?>
	});


	$("input[type=radio][name='cabclass']").change(function ()
	{
		var val = $("input[name='cabclass']:checked").val();
		var contentType = $(this).closest('div').find(':hidden');
		var sccval = contentType[1].value;
		if (sccval == 6)
		{
			var defaultVal = 81;
		} else if (sccval == 1)
		{
			var defaultVal = 78;
		} else if (sccval == 2)
		{
			var defaultVal = 79;
		} else if (sccval == 4)
		{
			var defaultVal = 80;
		}
		var trvlbagcapacity = $('.trvlbagcapcity' + val).text();
		var tncval = JSON.parse('<?= $tncArr ?>');
		$('.cabsegmentation').html(tncval[defaultVal] + ' ' + trvlbagcapacity);
		$('.cabsegment').removeClass('hide');
	});

	function checkServiceClass()
	{
		if ($('input[name="cabclass"]:checked').length == 0)
		{
			$(".alertcabclass").html('Please Choose atleast one');
			$(".alertcabclass").show();
			return false;
		}

		var form = $("form#catTierForm");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl($this->getURL('booking/tierQuotes')) ?>",
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
			{	//debugger;
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
					let pageid = $($.parseHTML(data2)).find("#pageID").val();
					let sclass = $("form#catTierForm input[name=\"cabclass\"]:checked").data("class");
					if (sclass == 4)
					{
						$("#tab10").html(data2);
					} else
					{
						if (pageid == 12)
						{
							$("#tab12").html(data2);
						} else
						{
							$("#tab13").html(data2);
						}

					}
				} else
				{
					if (data.success)
					{
						window.sessionStorage.setItem('rdata', data.data.rdata);
						location.href = data.data.url;
						return;
					}

					var errors = data.errors;
					msg = JSON.stringify(errors);
					settings = form.data('settings');
					$.each(settings.attributes, function (i)
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
			error: function (xhr, ajaxOptions, thrownError)
			{
				if (xhr.status == "403")
				{
					handleException(xhr, function () {
						checkServiceClass();
					});
				}

				//alert(xhr.status);
				//alert(thrownError);
			}
		});
		return false;
	}

	function showFareBreakup(cabid)
	{

		$('#bkFareDetailsModel' + cabid).removeClass('fade');
		$('#bkFareDetailsModel' + cabid).css("display", "block");
		$('#bkFareDetailsModel' + cabid).modal('show');
	}

</script>
<script>
	$(function ()
	{
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>
