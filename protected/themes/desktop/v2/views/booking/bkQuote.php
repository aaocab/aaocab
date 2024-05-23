<?php
#$quotes				 = $model->getQuote(null, true);
/* @var $model BookingTemp */
$quoteModel			 = $model->quotes;
$isFlexxiExcluded	 = false;
$excludeCabType		 = BookingSub::getexcludedCabTypes($model->bkg_from_city_id, $model->bkg_to_city_id);
if (in_array(11, $excludeCabType))
{
	$isFlexxiExcluded = true;
}
// Car Master Details
$cabData = VehicleTypes::model()->getMasterCarDetails();

/** @var TbActiveForm $form */
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'cabrate-form1',
	'enableClientValidation' => true,
	'clientOptions'			 => array(),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal',
		'autocomplete'	 => 'off'
	),
		));
?>

<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id3', 'class' => 'clsBkgID']); ?>
<?= $form->hiddenField($model, 'hash', ['id' => 'hash3', 'class' => 'clsHash', 'value' => $model->getHash()]); ?>
<?= $form->hiddenField($model, "bkg_flexxi_type"); ?>    
<?= $form->hiddenField($model, "bkg_vehicle_type_id"); ?>
<?= $form->hiddenField($model, "bkg_trip_distance"); ?>
<?= $form->hiddenField($model, "bkg_trip_duration"); ?>
<?= $form->hiddenField($model, 'bkg_no_person') ?>
<?= $form->hiddenField($model, 'bkg_num_large_bag') ?>
<?= $form->hiddenField($model, 'bkg_num_small_bag') ?>
<?= $form->hiddenField($model, 'bkg_rate_per_km_extra'); ?>
<?= $form->hiddenField($model, 'bkg_package_id'); ?>

<input type="hidden" id="step2" name="step" value="2">

<div class="container mt30">            
	<div id="error-border" style="<?= (CHtml::errorSummary($model) != '') ? "border:2px solid #a94442" : "" ?>" class="route-page1">

			<div class="row">
				<div class="col-sm-12">
					<span class="font-24"><b>
						<?php
						echo implode(' &rarr; ', $quoteModel->routeDistance->routeDesc);
						?> 
						</b></span><br>
					<?php
					if ($quotes)
					{
						?>
						<p class="font-16">Estimated Distance: <b> <?= $quoteModel->routeDistance->tripDistance . " Km" ?></b>,
							Estimated Time: <b><?= $quoteModel->routeDuration->durationInWords ?> </b>(+/- 30 mins for traffic)</p>
						<?php
					}
					else
					{
						?>
						<br/><p><b>Sorry cab is not available for this route.</b></p>
					<?php } ?>
					<!--<h5 class="hide">If there are any issues with your booking we will contact you. Please share your phone and email address below.</h5>-->
				</div> 
			</div>
			<?php
			$i = 0;

			foreach ($quotes as $key => $quote)
			{
				$shareBooking = false;
				if ($model->bkg_booking_type == 1 && !$isFlexxiExcluded && $key == 3)
				{
					$shareBooking = true;
				}
				$flexxRates	 = $quote->flexxiRates;
				/* @var $quote Quote */
				$cab		 = $cabData[$key];

				// Fare Breakup Tooltip
				$details = $this->renderPartial("bkFareBreakup", ['quote' => $quote], true);

				$promoDiscount	 = $quote->routeRates->discount;
				$discBaseAmount	 = $quote->routeRates->baseAmount - $promoDiscount;

				$tolltax_value	 = $quote->routeRates->tollTaxAmount | 0;
				$tolltax_flag	 = $quote->routeRates->isTollIncluded | 0;
				$statetax_value	 = $quote->routeRates->stateTax | 0;
				$statetax_flag	 = $quote->routeRates->isStateTaxIncluded | 0;

				if (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0))
				{
					$taxStr = 'Toll Tax and State Tax included';
				}
				else if ($tolltax_flag == 0 && $statetax_flag == 0)
				{
					$taxStr = 'Toll and State taxes extra as applicable';
				}
				?>
				<div id="flexxi_rates_<?= $cab['vht_id'] ?>" class="hidden" >
					<label>Normal One-Way Fare from <b><?= $fromcity ?></b> to <b><?= $tocity ?> <i class="fa fa-inr ml10"></i><?= $quote->routeRates->baseAmount ?></b></label><br>
					<label>Fare details with <font color='#ff2929'>FLEXXI SHARE:</font></label>
					<table align='center'>
						<tr>
							<th style='padding:8px; border: 1px solid black; font-size: 13px'>Seats You Use</th>   
							<th style='padding:8px; border: 1px solid black; font-size: 13px'>Seats You Share</th>
							<th style='padding:8px; border: 1px solid black; font-size: 13px'>You Pay</th>
							<th style='padding:8px; border: 1px solid black; font-size: 13px'>You Save</th>
							<th style='padding:8px; border: 1px solid black; font-size: 13px'>% savings </th>
						</tr>
						<?php
						for ($i = 1; $i < $cab['vht_capacity']; $i++)
						{
							?>
							<tr>
								<td style='padding:8px; border: 1px solid black; font-size: 13px'><?= $i ?></td>    
								<td style='padding:8px; border: 1px solid black; font-size: 13px'><?= $cab['vht_capacity'] - $i ?></td>
								<td style='padding:8px; border: 1px solid black; font-size: 13px'>as low as <b><i class="fa fa-inr ml10"></i><?= $flexxRates[$i]['flexxiBaseAmount'] ?></b></td>
								<td style='padding:8px; border: 1px solid black; font-size: 13px'><i class="fa fa-inr ml10"></i><?= $flexxRates[$i]['fpsaved'] ?></td>
								<td style='padding:8px; border: 1px solid black; font-size: 13px'><?= round(($flexxRates[$i]['fpsaved'] / $quote->routeRates->baseAmount) * 100) ?>%</td>
							</tr>
						<? } ?>
					</table>

				</div>
				<div class="col-12 bg-white-box-2 mb30">
					<div class="row">
						<div class="col-sm-9">
								<div class="row">
									<div class="col-12 gradient-gray-white text-center font-16 color-green border-gray p5 radius-top-left-10">
										<?php
										$bestPriceRange = '';
										if (isset($quote->pickupDate) && isset($quote->routeRates->bestRateDate))
										{
											$bestPriceRange = "You have found our best price";
											if (date("YmdHis", strtotime($quote->pickupDate)) != date("YmdHis", strtotime($quote->routeRates->bestRateDate)))
											{
												$bestPriceRange = "Get a lower price if you travel on " . date("d/m/y", strtotime($quote->routeRates->bestRateDate));
											}
										}
										if ($bestPriceRange != '')
										{
											?>
												<span><?= $bestPriceRange ?></span>
										<?php } ?>
									</div>
									<div class="col-sm-3 mt30">
										<div class="car_box"><img src="<?= Yii::app()->baseUrl . '/' . $cab['vht_image'] ?>" alt="" class="img-thumbnail border-none"></div>
									</div>
									<div class="col-sm-9 mt15 mb15">
										<span class="font-30 text-uppercase"><b><?= $cab['vht_make'] ?></b></span>
										<p class="mb20"><b><?= $cab['vht_model'] ?></b></p>
										<p class="font-14 mb5"><?= $cab['vht_capacity'] ?> Seats + Driver <?= $cab['vht_big_bag_capacity'] ?><i class="fas fa-circle font-12 color-orange ml10 mr10"></i>Big bag(s) + <?= $cab['vht_bag_capacity'] ?> Small bag(s)<i class="fas fa-circle font-12 color-orange ml10 mr10"></i>AC</p>
<!--										<p>KM in Quote <?= $quote->routeDistance->quotedDistance ?> Km</p>-->
											
										<span class="color-gray"><?= $taxStr ?></span><br>
										<a href="#" class="btn-deep-blue inline-block font-13 mt5 in-ex" data-target="#inEx<?= $key ?>">INCLUSIONS & EXCLUSIONS</a>
										<div id="inEx<?= $key ?>" style="display:none;">
											<?php
											$routeRates1 = $quote->routeRates;
											?>
											<div class="row list-type-1 mt20 mb20">
													<div class="col-sm-12 col-md-6">
														<span class="text-uppercase font-20"><b>included</b></span>
														<ul>
															<li>Upto <?= $quote->routeDistance->tripDistance ?> kms for the exact itinerary listed below</li>
															<li>NO route deviations allowed unless listed in itinerary</li> 
															<?php
															if ($routeRates1->isNightPickupIncluded > 0 && $routeRates1->includeNightAllowance > 0)
															{
																?>
																<li>Night pickup allowance included (pickup time is between 10pm and 6am).</li>
															<?php } ?>
															<?php
															if ($routeRates1->isNightDropIncluded > 0)
															{
																?>
																<li>Night dropoff allowance included (drop off time is between 10am and 6am).</li>
															<?php } ?>
															<li>GST</li>
															<?php
															if ($routeRates1->isTollIncluded > 0)
															{
																?>
																<li>Toll Tax (Not payable by customer)</li>
															<?php }															
															if ($routeRates1->isStateTaxIncluded > 0)
															{
																?>
																<li>State Tax (Not payable by customer)</li>
															<?php }                                                         
                                                            if ($routeRates1->isAirportEntryFeeIncluded > 0) 
                                                            {
                                                                ?>
                                                                <li>Airport Entry Fee (Included)</li>
                                                             <?php } ?>
														</ul>
													</div>
													<div class="col-sm-12 col-md-6">
														<span class="text-uppercase font-20"><b>excluded</b></span>
														<ul>
															<?php
															if ($routeRates1->isTollIncluded <= 0)
															{
																?>
																<li>Toll Tax (Excluded)</li>
															<?php } ?>
															<?php
															if ($routeRates1->isStateTaxIncluded <= 0)
															{
																?>
																<li>State Tax (Excluded)</li>
															<?php } 														
															if ($routeRates1->isNightPickupIncluded <= 0)
															{
																?>
																<li>Night pickup allowance excluded.</li>
															<?php }
															if ($routeRates1->isNightDropIncluded <= 0)
															{
																?>
																<li>Night dropoff allowance	excluded.</li>
															<?php } ?>
														</ul>
													</div>
												</div>
										</div>
									</div>
								</div>
							</div>
						<div class="col-sm-3 bg-gray text-center pt20">
								<span class="m0 text-uppercase text-muted" ><b>Base Fare</b></span><br>
								<?php if ($quote->routeRates->baseAmount > $discBaseAmount)
								{
									?>
									<span style="font-size: 16px; line-height: normal; font-weight: bold;">
										&#x20B9<strike style="font-weight: bold;"><?= $quote->routeRates->baseAmount; ?></strike><br>
									</span>
	<?php } ?>
								<span class="font-30">
									<span>&#x20B9</span><b><?= $discBaseAmount ?></b><sup>*</sup>
								</span><br>
								<?php
									if ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
									{
								   ?>
									<button type="button" value="<?= $cab['vht_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $cab['vht_make'] ?>" name="bookButton" class="btn text-uppercase gradient-green-blue font-20 border-none mt15" onclick="validateForm1(this);">
										<b>Book Now</b>
									</button>
								   <div class="col-12 col-md-7 col-sm-7" style="margin-top: 15px;"> 
									   <a href="javascript:void(0)" onclick="showPackageList('<?= $key?>')" id="btn-package-show<?= $key ?>" class="btn btn-info">Show Package List</a>
									   <a href="javascript:void(0)" onclick="hidePackageList('<?= $key?>')" id="btn-package-hide<?= $key ?>" class="btn btn-default hide">Hide Package List</a>
								   </div>
								   <?php
									}else{
								   ?>
									<button type="button" value="<?= $cab['vht_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $cab['vht_make'] ?>" name="bookButton" class="btn text-uppercase gradient-green-blue font-20 border-none mt15" onclick="validateForm1(this);">
										<b>Book Now</b>
									</button>
									<?php
									}
								?>
								<button type="button" value="<?= $cab['vht_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $cab['vht_make'] ?>" name="bookButton" class="btn text-uppercase gradient-green-blue font-20 border-none mt15" onclick="validateForm1(this);">
									<b>Book Now</b>
								</button>
								<div class="btn-breakup border-top-gray"><a href="javascript:void(0)" class="text-uppercase color-deep-blue fair-breakup-modal" data-target="#fairBreakupDetails<?= $key ?>"><b>Detailed fare breakup</b></a></div>
								
								<div id="fairBreakupDetails<?= $key ?>" style="display:none;">
									<?php echo $this->renderPartial("bkFareBreakup", ['quote' => $quote], true); ?>
								</div>
								
							</div>
					</div>
				</div>
				<div id="packageQuotes<?=$key ?>" style="display:none">
							
				</div>
				<?php
			}
			?>
		</div>
</div>
<?php $this->endWidget(); ?>
<script>
	$bkgId = '<?= $model->bkg_id ?>';
	$hash = '<?= $model->getHash() ?>';
	var bookNow = new BookNow();
	var data = {};
	$(document).ready(function ()
	{
		bookNow.bkQuoteReady($bkgId, $hash);
		hyperModel.initializeplAirport();
	});
	$('#bdate').html('<?= date('\O\N jS M Y \\A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
	function validateForm1(obj)
	{
		data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
		data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
		data.flexiUrl = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail/flexi/2')) ?>";
		data.bkgTripDistance = "<?= CHtml::activeId($model, "bkg_trip_distance") ?>";
		data.bkgTripDuration = "<?= CHtml::activeId($model, "bkg_trip_duration") ?>";
		bookNow.data = data;
		bookNow.validateQuote(obj);
	}

	function flexxiShare_sub()
	{
		data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
		data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
		data.flexiUrl = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail/flexi/2')) ?>";
		bookNow.data = data;
		bookNow.searchFlexi('<?= $model->bkg_id ?>');
	}

	function flexiShare_promo(obj)
	{
		if ($('#noofseats').val() == '' || $('#noofseats').val() == 0)
		{
			box1 = bootbox.dialog({
				message: 'No. of seat is mandatory',
				title: 'Input Error',
				size: 'medium',
				onEscape: function ()
				{
					return false;
				}
			});
		}
		else
		if (parseInt($('#noofseats').val()) > parseInt($('#noofseats').attr('max')))
		{
			box1 = bootbox.dialog({
				message: 'Number of seats can not be greater than ' + $('#noofseats').attr('max') + "<br>",
				title: 'Input Error',
				size: 'medium',
				onEscape: function ()
				{
					return false;
				}
			});

		}
		else
		{
			data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
			data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
			data.bkgTripDistance = "<?= CHtml::activeId($model, "bkg_trip_distance") ?>";
			data.bkgTripDuration = "<?= CHtml::activeId($model, "bkg_trip_duration") ?>";
			$('#<?= CHtml::activeId($model, 'bkg_no_person') ?>').val($('#noofseats').val());
			$('#<?= CHtml::activeId($model, 'bkg_num_small_bag') ?>').val(0);
			$('#<?= CHtml::activeId($model, 'bkg_num_large_bag') ?>').val(0);
			$('#<?= CHtml::activeId($model, 'bkg_flexxi_type') ?>').val(1);
			bookNow.data = data;
			bookNow.sendQuoteToInfo(obj);

			boxFlexxi.modal('hide');
		}
	}
	
	$('.fair-breakup-modal').click(function(event){
		var id = $(event.currentTarget).data('target');
		$('#bkCommonModelHeader').text('Fair Breakup Details');
		$('#bkCommonModelBody').html($(id).html());
		$('#bkCommonModelHeader').parent().removeClass('hide');
		$('#bkCommonModel').modal('show');
	});
	
	$('.in-ex').click(function(event){
		var id = $(event.currentTarget).data('target');
		$('#bkCommonModelHeader').text('Inclusions & Exclusions Details');
		$('#bkCommonModelBody').html($(id).html());
		$('#bkCommonModelHeader').parent().removeClass('hide');
		$('#bkCommonModel').modal('show');
	});
	
	function showPackageDetails(id)
    {
        $href = '<?= Yii::app()->createUrl('booking/showPackage', ['listshow' => true, 'pck_id' => '']) ?>' + id;
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data)
            {
                $('#bkCommonModelHeader').text('Package Details');
				$('#bkCommonModelBody').html(data);
				$('#bkCommonModelHeader').parent().removeClass('hide');
				$('#bkCommonModel').modal('show');
            }
        });
    }
    function showPackageList(key)
    {
		var self = this;
		$.ajax({
			type: "POST",
			url: "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/packageQuote')) ?>",
			data: {'bkgid':$bkgId,'cab':key,'YII_CSRF_TOKEN':$('input[name="YII_CSRF_TOKEN"]').val()},
			success: function (data1)
			{
				$('#packageQuotes'+key).html(data1);
				$('#packageQuotes'+key).show('slow');
				$('#btn-package-hide'+key).removeClass('hide');
				$('#btn-package-show'+key).addClass('hide');
			},
			error: function (error)
			{
				console.log(error);
			}
		});
    }
    function hidePackageList(key)
    {
        $('#packageQuotes'+key).hide('slow');
		$('#btn-package-show'+key).removeClass('hide');
		$('#btn-package-hide'+key).addClass('hide');
    }
</script>
