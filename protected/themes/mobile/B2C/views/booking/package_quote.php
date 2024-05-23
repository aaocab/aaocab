<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
//$cabData = VehicleTypes::model()->getMasterCarDetails();
$cabData = SvcClassVhcCat::model()->getVctSvcList('allDetail');
if (count($quotePackages) > 0)
{
	$quotePackagesSorted = [];
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
}
?>

<?php
if (count($quotePackages) > 0)
{
	$i	 = 0;
	$j	 = 0;
	foreach ($quotePackagesSorted as $quotePackages)
	{
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
				$j	 = $j + 1;
				/* @var $quote Quote */

				// Fare Breakup Tooltip

				$promoDiscount	 = $quotePackage->routeRates->discount;
				$discBaseAmount	 = $quotePackage->routeRates->baseAmount - $promoDiscount;

				$tolltax_value	 = $quotePackage->routeRates->tollTaxAmount | 0;
				$tolltax_flag	 = $quotePackage->routeRates->isTollIncluded | 0;
				$statetax_value	 = $quotePackage->routeRates->stateTax | 0;
				$statetax_flag	 = $quotePackage->routeRates->isStateTaxIncluded | 0;

				if (($tolltax_flag == 1 && $tolltax_value == 0) && ($statetax_flag == 1 && $statetax_value == 0))
				{
					$taxStr = 'Toll Tax and State Tax included';
				}
				else if ($tolltax_flag == 0 && $statetax_flag == 0)
				{
					$taxStr = 'Toll and State taxes extra as applicable';
				}
				# print_r($quotePackage);
				?>

				<div class="content-padding content-boxed-widget mb10 pb0 p0 pt0" style="overflow: hidden;">
					<div class="font-12 uppercase">
						<a href="javascript:void(0);" class="headding-part1 text-center pl10 pr10" data-menu="menu-edit-modal<?= $packid ?>" data-id="<?= $packid ?>" onclick="showPackageDetails(<?= $packid ?>)">
							<?php
							echo implode(' &rarr; ', $quotePackage->routeDistance->routeDesc);
							?>
							<br><b><?= ($quotePackage->routeDuration->calendarDays); ?> Days Tour</b></br>

						</a>
					</div>     
					<div class="content p0 bottom-0">
						<img src="/images/package1.jpg" alt="" height="150" class="preload-image responsive-image">
					</div>
					<div class="content bottom-0 pl15">
							<h4 class="mt0"></h4>
						
							<a href="javascript:void(0);" class="font12 uppercase color-black mt10" data-accordion="accordion-p<?= $j ?>" onclick="showPackageDetails(<?= $packid ?>,<?= $j ?>)"> <span class="font30"><span>₹</span><b><?= $quotePackage->routeRates->baseAmount ?></b></span><sup class="font-16">*</sup> <b>View package details</b> <i class="fa fa-plus"></i></a>
					</div>
					<div class="clear"></div>
					<div class="content-padding p15 pt0">
						<p class="line-height16 color-black bottom-0"><?= $cab['vct_desc'] ?></p>
					</div>
					<div class="accordion accordion-style-1">
						<div class="accordion-path">
							<div class="accordion accordion-style-0 box-text-7">
								
								<div class="accordion-content" id="accordion-p<?= $j ?>" style="display: none;">
									<div class="accordion-text">
										<div id="dataDetails<?= $j ?>"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					 <!--<div id="dataDetails<?= $packid ?>"></div>-->
					<div class="content-padding p5 mb10 text-center" >                            
						<button type="button" value="<?= $cab['scv_id'] ?>" pckid ="<?= $quotePackage->routeRates->packageID ?>" kmr="<?= $quotePackage->routeRates->ratePerKM ?>" kms="<?= $quotePackage->routeDistance->tripDistance ?>" duration="<?= $quotePackage->routeDuration->totalMinutes ?>" data-cabtype="<?= $cab['vht_make'] ?>" name="bookButton" name="bookButton" class="uppercase btn-green p15 mr5 font-13" onclick="validateForm1(this);">
							<b>Book Package</b><br>
						</button>
					</div>
				</div>
				<?php
			}
		}
	}
}
else
{
	?>
	<p style="text-align: center;font-size:18px;color:red;">No Package Available</p>
<?php } ?>
