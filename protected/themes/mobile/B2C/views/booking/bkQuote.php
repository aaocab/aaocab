<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<h1 class="text-uppercase text-center"><b>Standard</b></h1>
<?php
#$quotes		 = $model->getQuote(null, true);
/* @var $model BookingTemp */
$quoteModel = $model->quotes;

// Special Remarks
$rtInfoArr			 = $model->getRoutesInfobyId();
$specialRemarks		 = $rtInfoArr[0]['rut_special_remarks'];
$isFlexxiExcluded	 = false;
$excludeCabType		 = BookingSub::getexcludedCabTypes($model->bkg_from_city_id, $model->bkg_to_city_id);
if (in_array(11, $excludeCabType))
{
	$isFlexxiExcluded = true;
}
// Car Master Details
//$cabData = VehicleTypes::model()->getMasterCarDetails();
$cabData = SvcClassVhcCat::model()->getVctSvcList('allDetail');
/** @var CActiveForm $form */
$form	 = $this->beginWidget('CActiveForm', array(
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

<?php
if ($quotes)
{
	?>

	<div class="container top-10" id="p_clist">
		<?php $this->renderPartial("bkRouteHeader" . $this->layoutSufix, ['prevStep' => 1, 'model' => $model, 'quoteModel' => $quoteModel]); ?>   
		<?php
		$quotePackagesSorted = [];
		if (count($quotePackages) > 0)
		{

			$noPackages = count($quotePackages);
			?>
			<?php
			foreach ($quotePackages as $packid => $quotePackageData)
			{
				foreach ($quotePackageData as $key => $quotePackage)
				{
					if (!$quotePackage->success)
					{
						$i = 1;
						continue;
					}
					$cab = $cabData[$key];
					/* @var $quote Quote */

					// Fare Breakup Tooltip

					$promoDiscount																 = $quotePackage->routeRates->discount;
					$discBaseAmount[]															 = $quotePackage->routeRates->baseAmount - $promoDiscount;
					$quotePackagesSorted[$quotePackage->routeRates->baseAmount][$packid][$key]	 = $quotePackage;
				}
			}
			ksort($quotePackagesSorted);
			#print_r(min($discBaseAmount));
			?>
			<div class="content-boxed-widget btn-orange font-13 wrapword line-height16" id="pckShowBtn" style="display: "><?php //= $noPackages          ?>Packages from <?= $quoteModel->routeDistance->routeDesc[0] ?> to <?= $quoteModel->routeDistance->routeDesc[1] ?> starts from <span>₹</span> <b><?= min($discBaseAmount) ?></b>. <br>Check Now</br></div>
			<?php
		}
		?>
		<div id="sidebar-right-over-package" data-selected="menu-components" class="menu-box menu-sidebar-right-over" style="transition: all 300ms ease 0s;">
			<div class="menu-title">
				<h1 class="mt10">Packages</h1>
				<a href="#" class="menu-hide mt10" style="margin-top: -2px;"><i class="fa fa-times"></i></a>
			</div>
			<div id="packageQuotes">

			</div>
		</div> 

		<?php
		$j = 0;

		foreach ($quotes as $key => $quote)
		{
			if (!$quote->success)
			{
				$i = 1;
				continue;
			}
			$j++;
			$shareBooking = false;
			if ($model->bkg_booking_type == 1 && !$isFlexxiExcluded && $key == VehicleCategory::SEDAN_ECONOMIC)
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

			<div class="content-padding content-boxed-widget mb10 pb0 p0 list-view-panel" style="overflow: hidden;">
				<div class="content-padding p15 pb0 pt10 text-center border-top">
					<span class="font-18 uppercase"><b>Sedan (Economy)</b></span><br>
					Dzire, Toyota Etios, Tata Indigo or equivalent <br>
				</div>
				<div class="one-half mt10 p5">
					<img src="/images/cabs/car-etios.jpg" alt="" width="150" class="preload-image responsive-image mb0">
				</div>
				<div class="one-half last-column mt10 font-icon-list font-11">
					<ul>
						<li><img src="/images/team.svg" width="15" alt="Seats + Driver" class="inline-block mr5"> 4 Seats + Driver</li>
						<li><img src="/images/briefcase.svg" width="15" alt="Seats + Driver" class="inline-block mr5"> 2 Big bags + 1 Small bag</li>
						<li><img src="/images/air-conditioner.svg" width="15" alt="Seats + Driver" class="inline-block mr5"> AC</li>
						<li><img src="/images/speedometer.svg" width="15" alt="Seats + Driver" class="inline-block mr5"> KM in Quote 210 Km</li>
					</ul>
				</div>
				<div class="clear"></div>
				<div class="content p0 link-list-1 text-center">
					<a href="#" class="bg-blue1">
						<div class="font-18 uppercase"><b>Economy</b></div>
						<input id="box1-fac-radio-full" type="radio" name="rad1" value="1">
						<br/>
						<span class="font-18">&#x20B9<b>580</b></span>
					</a>
					<a href="#" class="bg-blue2">
						<div class="font-18 uppercase"><b>Plus</b></div>
						<input id="box1-fac-radio-full" type="radio" name="rad1" value="1">
						<br/>
						<span class="font-18">&#x20B9<b>580</b></span>
					</a>
					<a href="#" class="bg-blue3">
						<div class="font-18 uppercase"><b>Select</b></div>
						<input id="box1-fac-radio-full" type="radio" name="rad1" value="1">
						<br/>
						<span class="font-18">&#x20B9<b>580</b></span>
					</a>
				</div>
				<div class="content-padding p5 mb10 text-center">                            
					<button type="button" value="300" kmr="10" kms="700" duration="780" data-cabtype="Sedan" data-price="10471" name="bookButton" class="uppercase btn-green p15 pl20 pr20 mr5 font-14" onclick="validateForm1(this);">
						<b>Book Now</b><br>
					</button>
					<a href="javascript:void(0)" type="button" data-menu="sidebar-right-over-package" class="uppercase btn-orange mr5" onclick="showPackageList('<?=VehicleCategory::SEDAN_ECONOMIC?>')">
						Show Packages
					</a>
				</div>
				<div class="content text-center text-uppercase mb0">Economy | Plus | Select</div>
			</div>

			<div class="content-padding content-boxed-widget mb10 pb0 p0 pt0" style="overflow: hidden;">
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
					<div class="headding-part1 text-center"><?= $bestPriceRange ?></div>
				<?php } ?>


				<!--                        <div class="ribbon1 uppercase"></div>-->
				<div class="one-half mt10 p5">
					<img src="<?= Yii::app()->baseUrl . '/' . $cab['vct_image'] ?>" alt="" width="150" class="preload-image responsive-image mb0">
				</div>
				<div class="one-half last-column text-center mt10 pt20">
					<?php
					if ($quote->routeRates->baseAmount > $discBaseAmount)
					{
						?>
						<span style="font-size: 16px; line-height: normal; font-weight: bold;">
							<span>&#x20b9</span><strike style="font-weight: bold;"><?= $quote->routeRates->baseAmount; ?></strike>
						</span>
					<?php } ?>
					<h4 class="mt0"><span class="font30"><span>&#x20b9</span><?= $discBaseAmount; ?>
						</span><sup class="font-16">*</sup> 
					</h4>
				</div>
				<div class="clear"></div>
				<div class="content-padding p15 pt0">
					<span class="font-18 uppercase"><b><?= $cab['label'] ?></b></span><br>
					<?= $cab['vct_desc'] ?> <br>
					<span class="font-12 color-gray-dark"> <?= $cab['vct_capacity'] ?> Seats + Driver  | <?= $cab['vct_big_bag_capacity'] ?> Big bag(s) + <?= $cab['vct_small_bag_capacity'] ?> Small bag(s) | AC</span>
				</div>
				<div class="content-padding p5 mb10 text-center">                            
					<button type="button" value="<?= $cab['scv_id'] ?>" kmr="<?= $quote->routeRates->ratePerKM ?>" kms="<?= $quote->routeDistance->tripDistance ?>" duration="<?= $quote->routeDuration->totalMinutes ?>" data-cabtype="<?= $cab['vct_label'] ?>"  data-price="<?= $quote->routeRates->baseAmount; ?>" name="bookButton" class="uppercase btn-green p15 pl20 pr20 mr5 font-14" onclick="validateForm1(this);">
						<b>Book Now</b><br>
					</button>
					<?php
					if ($model->bkg_booking_type == 1 || $model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
					{
                                        ?>
                                        <a href="javascript:void(0)" type="button" data-menu="sidebar-right-over-package" class="uppercase btn-orange mr5" onclick="showPackageList('<?= $key ?>')">
                                                Show Packages
                                        </a>
                                        <?php
					}
					?>
				</div>
				<div class="accordion accordion-style-1">
					<div class="accordion-path">
						<div class="accordion accordion-style-0 box-text-7">
							<a href="javascript:void(0);" class="font18 uppercase" data-accordion="accordion-1<?= $j ?>">  Detailed fare breakup<i class="fa fa-plus"></i></a>
							<div class="accordion-content" id="accordion-1<?= $j ?>" style="display: none;">
								<div class="accordion-text">
									<?php echo $this->renderPartial("bkFareBreakup", ['quote' => $quote], true); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="accordion-path">
						<div class="accordion accordion-style-0 box-text-7">
							<a href="javascript:void(0);" class="font18 uppercase" data-accordion="accordion-2<?= $j + 1; ?>">  INCLUSIONS & EXCLUSIONS<i class="fa fa-plus"></i></a>
							<div class="accordion-content" id="accordion-2<?= $j + 1; ?>" style="display: none;">
								<div class="accordion-text">
									<div class="pl0 ul-panel">
										<?php
										$routeRates1 = $quote->routeRates;
										?>
										<p class="uppercase mb0"><b>included</b></p>
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
												<li>Toll Tax (Included)</li>
											<?php } ?>
											<?php
											if ($routeRates1->isStateTaxIncluded > 0)
											{
												?>
												<li>State Tax (Included)</li>
											<?php } ?>
										</ul>
										<p class="uppercase mb0"><b>excluded</b></p>
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
											<?php } ?>
											<?php
											if ($routeRates1->isNightPickupIncluded <= 0)
											{
												?>
												<li>Night pickup allowance excluded.</li>
											<?php } ?>
											<?php
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
					<?php
					if (strlen(trim($specialRemarks)) > 0)
					{
						?>
						<div class="accordion-path">
							<div class="accordion accordion-style-0 box-text-7 mb10">
								<a href="javascript:void(0);" class="font18 uppercase" data-accordion="accordion-3<?= $j + 2; ?>">  Important info<i class="fa fa-plus"></i></a>
								<div class="accordion-content" id="accordion-3<?= $j + 2; ?>" style="display: none;">
									<div class="accordion-text">
										<div class="content p0 bottom-0 pl0 ul-panel2">
											<?php echo trim($specialRemarks); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>

			<div class="clear"></div>
			<?php
		}
		?>
		<?php
		//if($i==1){echo "<div><p><b>Sorry cab is not available for this route.</b></p></div>";}
		?>
	</div>     
	<?php
}

$this->endWidget();
?>

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
    function showPackageDetails(id, key)
    {
        //alert(id+"***"+key);
        $href = '<?= Yii::app()->createUrl('booking/showPackage', ['listshow' => true, 'pck_id' => '']) ?>' + id;
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {

                $("#dataDetails" + key).html(data);
            }
        });
    }

    $('#bdate').html('<?= date('\O\N jS M Y \<\b\r/>\A\T h:i A', strtotime($model->bkg_pickup_date)) ?>');
    function validateForm1(obj)
    {
        var pckid = $(obj).attr("pckid");
        if (pckid > 0) {
            $('#BookingTemp_bkg_package_id').val(pckid);
        }
        data.extraRate = "<?= CHtml::activeId($model, "bkg_rate_per_km_extra") ?>";
        data.vehicleTypeId = "<?= CHtml::activeId($model, "bkg_vehicle_type_id") ?>";
        data.flexiUrl = "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail/flexi/2')) ?>";
        data.bkgTripDistance = "<?= CHtml::activeId($model, "bkg_trip_distance") ?>";
        data.bkgTripDuration = "<?= CHtml::activeId($model, "bkg_trip_duration") ?>";
        bookNow.data = data;
        bookNow.validateQuote(obj);
        $("#menu-hider").trigger("click");
    }
    $(document).on('click', '.menu-hide', function () {
        $("#menu-hider").trigger("click");
    });
    $(function () {
        $(".preload-search-image").lazyload({threshold: 0});
    });

    function showPackageList(key)
    {
        alert(key);
        var self = this;
        $.ajax({
            type: "POST",
            url: "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/packageQuote')) ?>",
            data: {'bkgid': $bkgId, 'cab': key, 'YII_CSRF_TOKEN': $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data1)
            {
                $('#packageQuotes').html(data1);
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    }
</script>
