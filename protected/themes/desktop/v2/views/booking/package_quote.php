<?php
//$cabData = VehicleTypes::model()->getMasterCarDetails();
$cabData = SvcClassVhcCat::getVctSvcList("allDetail");
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
	$i = 0;
	foreach ($quotePackagesSorted as $quotePackages)
	{
		foreach ($quotePackages as $packid => $quotePackageData)
		{
			foreach ($quotePackageData as $pkey => $quotePackage)
			{
				if (!$quotePackage->success)
				{
					$i = 1;
					continue;
				}

				$cab = $cabData[$pkey];
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
					$taxStr = 'Toll Tax and State Tax not payable by customer';
				}
				else if ($tolltax_flag == 0 && $statetax_flag == 0)
				{
					$taxStr = 'Toll and State taxes extra as applicable';
				}
				?>

				<div class="row flex rowPackage<?= $vhcCategoryId ?>">
					<div class="col-sm-2  eco-widget car-widget-4 text-center pt40"></div>
					<div class="col-sm-6 car-widteg-5 bg-gray2">
						<div class="row flex">
							<div class="col-md-4"><img src="/images/package1.jpg" width="167" height="148" alt="" class="img-responsive border-white"></div>
							<div class="col-md-8 pl0">
								<p class="mb0"><?= $cab['vct_desc'] ?></p>
								<p class="h4 mb5 mt0 black-color" onclick="showPackageDetails(<?= $packid ?>)">
									<?php
									echo $quotePackage->packageName;
									?>
								</p> <?php // echo implode(' &rarr; ', $quotePackage->routeDistance->routeDesc);  ?>
								<p class="mt20 mb10"><a class="btn next3-btn font11 border-none" onclick="showPackageDetails(<?= $packid ?>)">Click to show details</a></p>
							</div>
						</div>
					</div>

					<div class="col-sm-4 bg-navy-3 eco-widget car-widget-4 text-center widget-border pt40">
						<span class="m0 text-uppercase text-muted" ><b>Base Fare</b></span><br>
						<span class="font24">
							<span>&#x20B9</span><b><?= $quotePackage->routeRates->baseAmount ?></b>
						</span><br/>
						<button type="button" value="<?= $cab['scv_id'] ?>" pckid ="<?= $quotePackage->routeRates->packageID ?>" kmr="<?= $quotePackage->routeRates->ratePerKM ?>" kms="<?= $quotePackage->routeDistance->tripDistance ?>" duration="<?= $quotePackage->routeDuration->totalMinutes ?>" data-cabtype="<?= $cab['vht_make'] ?>" name="bookButton" class="btn next2-btn mt10 " onclick="validateForm1(this);">
							<b>Book Package</b> 
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
	?>		<p class="rowPackage<?= $vhcCategoryId ?>" style="text-align: center;font-size:18px;color:red;">No Package Available</p>
	<?php
}
?>

