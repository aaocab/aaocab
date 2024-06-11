<style>
	h2, h3{ font-weight: 700;}
	.out-accordion .accordion a{ width: 100%; line-height: 24px; text-align: left;}
	.out-accordion .accordion .button{ width: 30%; display: inline-block;}
	.call-cust a{ text-align: center!important; display: inline-block;}
	td{ padding: 5px 0; text-align: left; color: #475F7B;} th{ line-height: 35px; color: #475F7B;}
	.tab-styles .t-style{ background: #EEF3FA; width: 90%; margin: 0 auto; border-radius: 50px; border: #ccd9eb 1px solid; padding: 3px;}
	.tab-style a{ border-radius: 50px;}
	.tab-style a.active{ background: #5A8DEE;}
	.tab-styles .inner-tab a{ width: auto;}
	.style-box-1 .card{ box-shadow: 0 0 0 0!important; border: #ddd 1px solid; border-radius: 5px; margin: 10px 20px;}
	.ui-inner-facetune a{ width: 100%; padding: 16px 15px 16px 15px; border-radius: 5px 0 0 5px; color: #475F7B; font-size: 15px; font-weight: 500;  text-align: left;}
	.ui-box{ width: 90%; margin: 0 auto; margin-top: 10px; box-shadow: none; border: #ddd 1px solid; min-height:inherit; border-radius: 5px; padding-bottom: 0px; position: relative;}
	.ui-box2{ margin-bottom: 10px; box-shadow: none; border: #ddd 1px solid; min-height:inherit; border-radius: 5px; padding-bottom: 0px; display: block; position: relative;}
	.info-2{ width: 26px; height: 26px; background: url(/images/css_sprites1.png?v=0.5) -39px -40px;}
	.face-info{ position: initial; text-align: left; position: absolute; top: 0; right: 0;}
	.face-info a{ background: #fff; color: #475F7B; padding: 10px 10px; display: inline-table; border-radius: 0 5px 5px 0;}
	.ui-box-main .face-info a{ background: #fff; color: #475F7B; padding: 2px 10px; display: block; border-radius: 0 5px 5px 0;}
	.ui-box-main .ui-inner-facetune a{ padding: 8px 15px 12px 15px;}
</style>

<?php
if ($_REQUEST['amp'] == 1)
{
	?>
	<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
	<script async custom-template="amRent a Car in p-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>
	<?php
}
$this->layout = column1;
?>
<?php
if (isset($cityJsonProductSchema) && trim($cityJsonProductSchema) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $cityJsonProductSchema;
	?>
	</script>
<?php } ?>
<?php
if (isset($cityJsonMarkupData) && trim($cityJsonMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $cityJsonMarkupData;
	?>
	</script>
<?php } ?>
<?php
if (isset($cityBreadMarkupData) && trim($cityBreadMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php
	echo $cityBreadMarkupData;
	?>
	</script>
<?php } ?>
<?php /* if (isset($jsonproviderStructureMarkupData) && trim($jsonproviderStructureMarkupData) != '')
  {
  ?>
  <script type="application/ld+json">
  <?php
  #echo $jsonproviderStructureMarkupData;
  ?>
  </script>
  <?php } */ ?>

<?php
$this->newHome = true;
/* @var $cmodel Cities */
?>

<?php
if ($type == 'city')
{
	$tncType = TncPoints::getTncIdsByStep(4);
	$tncArr	 = TncPoints::getTypeContent($tncType);
	$tncArr1 = json_decode($tncArr, true);

	$cities			 = ($count['countCities'] > 500) ? 500 : $count['countCities'];
	$routes			 = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
	$topCitiesByKm	 = '';
	$ctr			 = 1;
	foreach ($topCitiesKm as $top)
	{
		$topCitiesByKm	 .= '<a href="/car-rental/' . strtolower($top['cty_alias_path']) . '" style=" text-decoration: none; color: #282828;" target="_blank">' . $top['city'] . '</a>';
		$topCitiesByKm	 .= (count($topCitiesKm) == $ctr) ? " " : ", ";
		$ctr++;
	}
	//echo '<pre>';
	//print_r($topCitiesByRegion);
	$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';
	for ($i = 1; $i <= 5; $i++)
	{
		$city_arr [] = $topCitiesByRegion[$i]['cty_alias_path'];
	}

	$nearby_city = implode(" ,", $city_arr);
	//check fleaxi available or not

	$flexi_count = 0;

	foreach ($topTenRoutes as $flex_check)
	{
		if ($flex_check[flexi_price] != 0)
		{
			$flexi_count = $flexi_count + 1;
		}
	}

	//print_r($topTenRoutes[0][flexi_price]);
	?>

	<!---- ----------->
	<main>

		<div class="container content-padding p10 bottom-0">
			<div class="above-overlay text-center">
				<h1 class="bold top-0 font-16">Reliable and affordable cab service in <?= $cmodel->cty_name; ?></h1>
				<?php
				if ($ratingCountArr['ratings'] > 0)
				{
					?>
					<p class="display-ini">
						<a href="<?= Yii::app()->createUrl('city-rating/' . $cmodel->cty_name); ?>"><small title="<?php echo $ratingCountArr['ratings'] . ' rating from ' . $ratingCountArr['cnt'] . ' reviews'; ?>">
								<?php
								$strRating	 = '';
								$rating_star = floor($ratingCountArr['ratings']);

								if ($rating_star > 0)
								{
									$strRating = '';
									for ($s = 0; $s < $rating_star; $s++)
									{
										$strRating .= '<img data-original="/images/star-amp.png"  class="preload-image" alt="Review" width="24" height="24">';
									}
									if ($ratingCountArr['ratings'] > $rating_star)
									{
										$strRating .= '<img data-original="/images/star-amp2.png"  class="preload-image" alt="Review" width="24" height="24"></img>';
									}
								}
								echo $strRating;
								?>

							</small></a>
					</p>
					<p class="bottom-0"><?= $ratingCountArr['cnt'] ?> reviews</p>
				<?php } ?>
			</div>
			<div class="overlay"></div>
		</div>
		<div class="container mb0 mobile-type tab-styles">

			<div class="tab-style tabs pt10">
				<div class="t-style" data-active-tab-pill-background="bg-green-dark">
					<a href="#" data-tab-pill="tab-pill-1a" class="devPrimaryTab3 " style="width:32.6%;">Local</a>
					<a href="#" data-tab-pill="tab-pill-1a1" class="devPrimaryTab4 mainTab active" style="width:32.4%;">Outstation</a>
					<a href="#" data-tab-pill="tab-pill-1a2" class="devPrimaryTab5" style="width:32.4%;float:right;">Airport</a>
				</div>

				<div class="tab-pill-content" >
					<div class="tab-item devSecondaryTab3" id="tab-pill-1a1" style="display: block;" >

						<div class="justify-center">
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getOneWayUrl($cmodel->cty_id) ?>">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">One-way trip</div>
										</div>
									</a>

								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="one-way-trip"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="one-way-trip" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div class="p15"><?= $tncArr1[61] ?></div>    
								</div>
							</div>
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getRoundTripUrl($cmodel->cty_id) ?>">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Round trip</div>
										</div>
									</a>

								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="round-trip"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="round-trip" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div  class="p15"><?= $tncArr1[63] ?></div>    
								</div>
							</div>
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getMultiTripUrl($cmodel->cty_id) ?>">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Multi-city multi-day trip</div>
										</div>
									</a>

								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="multi-trip"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="multi-trip" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div  class="p15"><?= $tncArr1[62] ?></div>    
								</div>
							</div>
						</div>

						<div class="inner-tab">
							<h2 class="font-16 ml20 mr20 mt20">Outstation Car rental fares for popular places to visit around <?= $cmodel->cty_name ?></h2>
							<?php
							$c = 0;
							if (count($topTenRoutes) > 0)
							{
								foreach ($topTenRoutes as $top)
								{
									$c = $c + 1;
									if ($c > 7)
									{
										break;
									}
									?>   

									<input type="hidden" name="step" value="1">
									<!--- --->
									<div class="style-box-1 ac-1 out-accordion">
										<div class="card p15">
											<div class="card-body">
												<div class="content p0 mb10">
													<h3 class="font-16"><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Cab</h3>
													<div class="" style="width: 70%; float: left;">
														<p class="mb5"><?= $top['rut_distance']; ?> kms | <?= floor($top['rut_time'] / 60) . " hours"; ?></p>
														<p class="mt20 mb0 weight500 color-orange"><?= $top['rut_distance']; ?> kms included</p>
														<p class="font-13 weight400 color-gray mb0">Charges after <?= $top['rut_distance'] ?> Km @ ₹<?= $top['extraKmRate'] ?>/km</p>
													</div>
													<div class="text-right font-20"  style="width: 30%; float: left;">
														<?= ($top['seadan_price'] > 0) ? '<b>' . Filter::moneyFormatter($top['seadan_price']) . '</b>' : '<a href="javascript:helpline();"><img src="/images/img-2022/bxs-phone-call.svg" alt="Call" width="20" height="20" class="preload-image" style="display:inline!important;"></a>'; ?>
														<p class="font-13 weight400 mb10">Onwards</p>
														<p class="mb0"><a class="ultrabold btn-green-blue default-link uppercase font-16" style="width: 100%;" href="<?= $this->getOneWayUrlFromPath($top['from_city_alias_path'], $top['to_city_alias_path']) ?>">Book</a></p>

													</div>
													<div class="clear"></div>
												</div>
											</div>
										</div>
										<div class="clear"></div>
									</div>
									<?php
								}
							}
							else
							{
								?>
								<div class="content-boxed-widget" style="overflow: hidden;">
									<span align="center">No routes yet found.</span>
								</div>
								<?php
							}
							?>
						</div>
					</div>
					<div class="tab-item devSecondaryTab4" id="tab-pill-1a" style="display: none;">


						<div class="justify-center">
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getDailyRentalUrl($cmodel->cty_id) ?>">
										<img data-src="/images/img-2022/g-icon-5.png" alt="" class="lozad img-fluid img-no">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Daily Rental on hourly basis</div>
										</div>
									</a>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="dayrental-trip"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="dayrental-trip" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div  class="p15"><?= $tncArr1[66] ?></div>    
								</div>
							</div>
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getAirportLocalUrl($cmodel->cty_id, '', 1) ?>">
										<img data-src="/images/img-2022/g-icon-3.png" alt="" class="lozad img-fluid img-no">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Pick-up from airport</div>
										</div>
									</a>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="pickup-from-airport"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="pickup-from-airport" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div  class="p15"><?= $tncArr1[64] ?></div>    
								</div>
							</div>
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getAirportLocalUrl($cmodel->cty_id, '', 2) ?>">
										<img data-src="/images/img-2022/g-icon-4.png" alt="" class="lozad img-fluid img-no">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Drop-off to airport</div>
										</div>
									</a>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="dropoff-to-airport"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="dropoff-to-airport" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div  class="p15"><?= $tncArr1[65] ?></div>    
								</div>
							</div>

						</div>

						<div class="inner-tab">
							<h2 class="font-16 ml20 mr20 mt20">Hourly car rental packages</h2>
							<div class="style-box-1 ac-1 out-accordion">
								<div class="card p0">
									<div class="card-body">
										<div class="content p0 mb10">
											<table class="pl15 pr15 bg-white mb10">
												<tr>
													<th align="left" class="bg-white weight600">Hrs & kms</th>
													<th align="left" class="bg-white weight600">Compact*</th>
													<th align="left" class="bg-white weight600">Sedan*</th>
													<th align="left" class="bg-white weight600">SUV*</th>
												</tr>
												<?php
												foreach ($dayRentalprice as $key => $dayrental)
												{
													$rentalPackage = ($key == 9) ? "4 Hrs & 40 Kms" : (($key == 10) ? "8 Hrs & 80 Kms" : "12 Hrs & 120 Kms");
													?>
													<tr>
														<td align="left" class="weight500"><?php echo $rentalPackage; ?></td>
														<td align="left" class="weight500"><?php echo Filter::moneyFormatter($dayRentalprice[$key][1]); ?></td>
														<td align="left" class="weight500"><?php echo Filter::moneyFormatter($dayRentalprice[$key][3]); ?></td>
														<td align="left" class="weight500"><?php echo Filter::moneyFormatter($dayRentalprice[$key][2]); ?></td>
													</tr>
												<?php } ?>
											</table>


											<div class="clear"></div>
										</div>
									</div>
								</div>
								<div class="clear"></div>
							</div>

							<div class="clear"></div>
							<div class="text-center">
								<a class="ultrabold btn-green-blue default-link uppercase font-16 top-10 mb10" href="<?= Yii::app()->createAbsoluteUrl('/bknw/dayrental/' . strtolower($cmodel->cty_alias_path)) ?>">Book</a>
							</div>
	<!--							<p class="mt0 mb0 pl10">* &nbsp;Excluding Tax</p>-->
						</div>
					</div>
					<div class="tab-item devSecondaryTab3" id="tab-pill-1a2" style="display: none;" >
						<div class="mb15 justify-center">
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getAirportLocalUrl($cmodel->cty_id, '', 1) ?>">
										<img data-src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Pick-up from airport (Local)</div>
										</div>
									</a>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="pickup-from-airport-local"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="pickup-from-airport-local" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div  class="p15"><?= $tncArr1[64] ?></div>    
								</div>
							</div>
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getAirportLocalUrl($cmodel->cty_id, '', 2) ?>">
										<img data-src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Drop-off to airport (Local)</div>
										</div>
									</a>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="dropoff-to-airport-local"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="dropoff-to-airport-local" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div  class="p15"><?= $tncArr1[65] ?></div>    
								</div>
							</div>
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getAirportOutstationUrl($cmodel->cty_id, '', 1) ?>">
										<img data-src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Pick-up from airport (Outstation)</div>
										</div>
									</a>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="pickup-from-airport-outstation"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="pickup-from-airport-outstation" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div  class="p15"><?= $tncArr1[82] ?></div>    
								</div>
							</div>
							<div class="ui-box">
								<div class="ui-inner-facetune flex-grow-1">
									<a href="<?= $this->getAirportOutstationUrl($cmodel->cty_id, '', 2) ?>">
										<img data-src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no lozad">
										<div class="ui-text-facetune">
											<div class="mb-0 font-16">Drop-off to airport (Outstation)</div>
										</div>
									</a>
								</div>
								<div class="face-info"><a href="javascript:void(0);" data-menu="dropoff-to-airport-outstation"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
								<div id="dropoff-to-airport-outstation" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
									<div class="menu-title">&nbsp;
										<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
									</div>         
									<div  class="p15"><?= $tncArr1[83] ?></div>    
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="overlay opacity-90"></div>
			</div>
			<!--- --->
			<?php
			$c = 1;
			?>

			<!--	<div class="accordion-path">
					<div class="accordion accordion-style-0 ac-1">
						<div class="accordion top-5">
							<a href="javascript:void(0)" class="font-16 bg-gray pl10 heightA1" data-accordion="accordion-<?= $c ?>" data-parent="#accordion">
								Day Rental
								<span class="float-right" style="display: inline-block; padding-right: 10px; padding-top: 10px;"><img class="preload-image" data-original="/images/plus-1.svg?v=0.2" alt="Plus" width="10" height="10"></span>
							</a>
							<div class="accordion-content accordion-style-4 bg-white" id="accordion-<?= $c ?>" style="display: <?= ($c == 1 ? 'block' : 'none') ?>;">
								<table class="table-borders-dark shadow-small mb10">
									<tr>
										<th align="center" class="bg-white weight600">Package Details</th>
										<th align="center" class="bg-white weight600">Compact*</th>
										<th align="center" class="bg-white weight600">Sedan*</th>
										<th align="center" class="bg-white weight600">SUV*</th>
									</tr>
			<?php
			foreach ($dayRentalprice as $key => $dayrental)
			{
				$rentalPackage = ($key == 9) ? "4 Hrs & 40 Kms" : (($key == 10) ? "8 Hrs & 80 Kms" : "12 Hrs & 120 Kms");
				?>
															<tr>
																<td align="center" class="weight500"><?php echo $rentalPackage; ?></td>
																<td align="center" class="weight500"><?php echo "&#x20B9; " . $dayRentalprice[$key][1]; ?></td>
																<td align="center" class="weight500"><?php echo "&#x20B9; " . $dayRentalprice[$key][3]; ?></td>
																<td align="center" class="weight500"><?php echo "&#x20B9; " . $dayRentalprice[$key][2]; ?></td>
															</tr>
			<?php } ?>
								</table>
								<div class="clear"></div>
								<a class="button button-center-small uppercase ultrabold btn-green-blue default-link font-16 text-center top-20" href="<?= Yii::app()->createAbsoluteUrl('/bknw/dayrental/' . strtolower($cmodel->cty_alias_path)) ?>">Book</a>	
								<p class="mt0 mb0 pl10">* &nbsp;Excluding Tax</p>
							</div>
						</div>
					</div>
				</div>-->


		</div>
		<div class="content" itemscope="" itemtype="https://schema.org/FAQPage">
			<p>
				If you are looking for a reliable, convenient, and affordable cab service from <?= $cmodel->cty_name ?>, look no further 
				than Gozo cabs. Gozo cabs is the best <a href="/app">cab booking app</a> that offers you a wide range of options to choose from, such as compact, sedans, SUVs, and tempo travellers. 
				You can book a taxi service near you in just a few clicks and enjoy a hassle-free ride with professional drivers, 
				and <a href="http://www.aaocab.com/blog/billing-transparency/">transparent pricing</a>. 
				Whether you need a cab for a local trip, an outstation journey, or an airport transfer, Gozo cabs has you covered. 
				Gozo cabs is the best cab booking app for cheap and reliable taxi booking. You can also save money by availing of the various discounts and offers that Gozocabs provides to its customers.
			</p>				
			<section>
				<h4 class="">FAQs About <?= $cmodel->cty_name ?> Cabs</h4>
				<div class="pt10">
					<?php
					$faqArray = Faqs::getDetails(2);
                                            foreach ($faqArray as $key => $value)
                                            {
                                              
                                                $question = str_replace('{#fromCity#}', $cmodel->cty_name, $value['faq_question']);
                                              
                                               
                                                $answer   = str_replace('{#fromCity#}', $cmodel->cty_name, $value['faq_answer']);
                                             if($airport =='' && $value['faq_id']== 17)
                                                {
                                                    $answer1  = str_replace('<p><b>Airport Transfers</b>: Enjoy a hassle-free and dependable airport transfer service to and from the {#airport#}. You can choose from a variety of vehicles, including economy cars, SUVs, and luxurious sedans. This service is tailored to travelers seeking a stress-free alternative to taxi or public transport at the airport, with rates starting at ₹{#minRate#} (inclusive of {#km#} kilometers) for Digha airport transfers.</p>', " ", $answer);
                                                }
                                                else{
                                                $answer1  = str_replace('{#airport#}', $airport, $answer);
                                                }
                                                
                                                
                                                
                                                $answer2  = str_replace('{#startingPrice#}', $dayRentalprice[9][1], $answer1);
                                                $answer3  = str_replace('{#minRate#}', $airportRate['pat_total_fare'], $answer2);
                                                $answer4  = str_replace('{#km#}', $airportRate['pat_minimum_km'], $answer3);
                                                $answer5  = str_replace('{#ratePerKM#}', $ratePerKM, $answer4);
                                                
                                                
                                                ?>
						
						<div>
							<div class="mb20" itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
								<div>
									<p itemprop="name" class="font-14 mb0"><b><?php echo trim($question, 'Q: '); ?></b></p>
								</div>
								<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="">
									<div itemprop="text">
		 <?php echo trim($answer5, 'A. '); ?>
									</div>
								</div>
							</div>
						</div>
	<?php } ?>
				</div>
			</section>
		</div>
		<article>
			<div class="content">
				<section>
						<h2 class="top-16 font-16">Best time for renting a car in <?= $cmodel->cty_name; ?></h2>
	<?= $cmodel->cty_name; ?> enjoys a maritime climate throughout the year and to be very realistic you can visit the city in any time of the year. However, the months between September and February see the highest tourist inflow to the city because the weather becomes even more pleasant during the Winter season. The winter months in <?= $cmodel->cty_name; ?> are the best time to visit the city.
							
				</section>
				<section>
						<h2 class="top-20 font-16">Things to look at while booking an outstation cab to and from <?= $cmodel->cty_name; ?></h2>
								<ol>
									<li>Ensure that a commercial taxi is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>
									<li>Find if the route you will travel on has tolls. If yes, are tolls included in your booking quote</li>
									<li>When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same</li>
									<li>Price is great but service is what matters. So try to focus on service at a reasonable price. When you go for the cheapest, you will ending up getting what you pay for.<br><br></li>
								</ol>
				</section>
				<section>
					<h2 class="bottom-5 font-16 top-20">Rent a Car in <?= $cmodel->cty_name; ?> for outstation travel, day-based rentals and airport transfers
						<!--fb like button-->
						<div class="fb-like" data-href="https://facebook.com/gozocabs" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
						<!--fb like button-->
					</h2>
					Rent a Gozo cab with driver for local and outstation travel in  <?= $cmodel->cty_name; ?>. Gozo provides taxis with driver on rent in  <?= $cmodel->cty_name; ?>, including one way outstation taxi drops, outstation roundtrips and outstation shared taxi to nearby cities or day-based hourly rentals in or around  <?= $cmodel->cty_name; ?>. You can also book cabs for your vacation or business trips for single or multiple days within or around  <?= $cmodel->cty_name; ?> or to nearby cities and towns. Car rental services in  <?= $cmodel->cty_name; ?> are also available for flexible outstation packages that are billed by custom package tour itinerary or by day
					Gozo cab is India’s leader in outstation car rental. We provide economy, premium and luxury cab rental services for outstation and local travel over 3000 towns & cities and on over 50,000 routes all around India. 
					If you prefer to hire specific brands of cars like Innova or Swift Dzire then book our Assured Sedan or Assured SUV cab categories. You are guaranteed a Toyota Innova when you book an Assured SUV or guaranteed a Swift Dzire when you book an Assured Sedan. 
					With Gozo you can book a one way cab with driver from  <?= $cmodel->cty_name; ?> to <?= $nearby_city ?> and many more. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x365. Gozo partners with regional taxi operators in <?= $cmodel->cty_name; ?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We also provide local sightseeing trips and tours in or around the city. Our customers include domestic & international tourists, large event groups and business travellers who take rent cars for outstation trips and also for local taxi requirements in the  <?= $cmodel->cty_name; ?> area.
					<div class="content text-center top-10">
						<img data-original="/images/cabs/tempo_9_seater.jpg" alt="Tempo 9 Seater" class="preload-image bottom-5" style="margin: 0 auto;" width="200" height="113">
						<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>" class="default-link">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
					</div>
					<div class="content text-center">
						<img data-original="/images/cabs/car-etios.jpg" alt="Etios" class="preload-image bottom-5" style="margin: 0 auto;" width="200" height="113">
						<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>" class="default-link">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
					</div>
	<?php
	$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
	if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_name)), $selected_cities))
	{
		?>
						<div class="content text-center">
							<img data-original="/images/cabs/car-etios.jpg" alt="Etios" class="preload-image bottom-5" style="margin: 0 auto;" width="200" height="113">
							<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>" class="default-link" width="200" height="113">Luxury car rental in <?= $cmodel->cty_name; ?></a>
						</div>
		<?php
	}
	?>
					<?php
					if ($cmodel['cty_has_airport'] == 1)
					{
						?>
						<div class="content text-center">
							<img data-original="/images/cabs/car-etios.jpg" alt="Etios" class="preload-image bottom-5" style="margin: 0 auto;" width="200" height="113">
							<a href="/airport-transfer/<?= strtolower($cmodel->cty_name) ?>" class="default-link">Airport transfer in <?= $cmodel->cty_name; ?></a>
						</div>
		<?php
	}
	?>
				</section>

					<?php
					if ($cmodel['cty_has_airport'] == 1)
					{
						?>
					<section>
						<h2 class="bottom-5 font-16">Hire Airport taxi with driver in <?= $cmodel->cty_name ?> with meet and greet services</h2>
						Car rentals are available for outstation travel and airport pickup or drop at <?= $cmodel->cty_name ?> Airport.  Many business and international  travellers use our chauffeur driven airport pickup and drop taxi services. These airport taxi transfers can be arranged with meet and greet services enabling smooth transportation to or from <?= $cmodel->cty_name ?> airport to your office, hotel or address of choice. Typical airport transfer trips are between <?= $cmodel->cty_name ?> city center and <?= $cmodel->cty_name ?> airport. We also serve transportation from <?= $cmodel->cty_name ?> Airport to cities nearby. If you have a special requirement, simply ask our customer service team who will do their best to support your needs.
					</section>

					<section>
		<?php
	}

	if ($cmodel->cty_city_desc != "")
	{
		?>
						<h2 class="top-20 font-16">Little about <?= $cmodel->cty_name; ?> </h2>
						<?= $cmodel->cty_city_desc; ?>

						<?
					}
					?>

					<?php
					$text = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';
					if ($place != "")
					{
						?>
						<p>If You are Foodie, <?= $text; ?> <?php
						foreach ($place as $p)
						{
							echo $p;
						}
						?></p> 
						<?php } ?>
				</section>
			</div>
		</article>
	<?php
}
?>
</main>
	<?php $api = Yii::app()->params['googleBrowserApiKey']; ?>
<script type="text/javascript">
	function helpline()
	{
		reqCMB(1);
	}

</script>	
