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
<?php } 

$detect			 = Yii::app()->mobileDetect;
		$isMobileDetect	 = $detect->isMobile();
if($isMobileDetect == 1)
{
 $this->renderPartial('application.themes.desktop.v3.views.index.topSearch', array('model' => $model), true, FALSE);
}  
?>
<!--<div class="row gray-bg-new">
    <div class="col-lg-10 col-sm-10 col-md-8 text-center flash_banner float-none marginauto ml50 border bg-white">
        <span class="h3 mt0 mb5 flash_red text-warning">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Taxi and Car rentals all over India – Gozo’s online cab booking service</span><br>
        aaocab is your one-stop shop for hiring chauffeur driven taxi in India for inter-city, airport transfers and even local daily tours, specializing in one-way taxi trips all over India at transparent fares. Gozo’s coverage extends across <b>2,000</b> locations in India, with over <b>20,000</b> vehicles, more than <b>75,000</b> satisfied customers and above <b>10</b> Million kms driven in a year alone. Book by web, phone or app 24x7! 
    </div>
</div>-->

<article class="container">
    <div class="row flash_banner hide" style="background: #ffc864;">
        <div class="col-lg-12 p0 hidden-sm hidden-xs text-center">     
            <figure><img src="/images/flash_lg1.jpg?v=1.1" alt="Flash Sale"></figure>		
        </div>
        <div class="col-sm-12 p0 hidden-lg hidden-md hidden-xs text-center">      
            <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>		
        </div>
        <div class="col-12 p0 hidden-lg hidden-md hidden-sm text-center">
			<? /* /?><a target="_blank" href="https://twitter.com/aaocab"><?/ */ ?>
            <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>
			<? /* /?></a><?/ */ ?>
        </div>
    </div>

	<?php
	if ($type == 'city')
	{
		$tncType		 = TncPoints::getTncIdsByStep(4);
		$tncArr			 = TncPoints::getTypeContent($tncType);
		$tncArr1		 = json_decode($tncArr, true);
		$cabtype		 = 2;
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

		for ($i = 1; $i <= 5; $i++)
		{
			$city_arr [] = $topCitiesByRegion[$i]['cty_alias_path'];
		}

		$nearby_city = implode(",", $city_arr);

		$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';

		//check fleaxi available or not

		$flexi_count = 0;

		foreach ($topTenRoutes as $flex_check)
		{
			if ($flex_check[flexi_price] != 0)
			{
				$flexi_count = $flexi_count + 1;
			}
		}

		$cmodel->cty_alias_path
		?>
		<div id="section2">
			<div class="row d-lg-none">
				<div class="col-12">
					<div class="container content-padding p10 pt0 bottom-0">
						<div class="above-overlay text-center">
							<h1 class="top-0 font-16 weight600">Rent a Car in <?= $cmodel->cty_name; ?></h1>
							<?php
							if ($ratingCountArr['ratings'] > 0)
							{
								?>
								<p class="display-ini mb10">
									<a href="<?= Yii::app()->createUrl('city-rating/' . $cmodel->cty_name); ?>"><small title="<?php echo $ratingCountArr['ratings'] . ' rating from ' . $ratingCountArr['cnt'] . ' reviews'; ?>">
											<?php
											$strRating	 = '';
											$rating_star = floor($ratingCountArr['ratings']);

											if ($rating_star > 0)
											{
												$strRating = '';
												for ($s = 0; $s < $rating_star; $s++)
												{
													$strRating .= '<img src="/images/star-amp.png"  class="preload-image" alt="Review" width="24" height="24">';
												}
												if ($ratingCountArr['ratings'] > $rating_star)
												{
													$strRating .= '<img src="/images/star-amp2.png"  class="preload-image" alt="Review" width="24" height="24"></img>';
												}
											}
											echo $strRating;
											?>

										</small></a>
								</p>
								<p class="mb0"><?= $ratingCountArr['cnt'] ?> reviews</p>
							<?php } ?>
						</div>
						<div class="overlay"></div>
					</div>
					<div class="row">
						<div class="col-12 cr-box">
							<div class="col-12 col-xl-10 offset-xl-1 tab-view">
								<ul class="nav nav-tabs justify-content-center pl10 text-center d-flex" role="tablist">
									<li class="nav-item mr0 flex-fill">
										<a class="cabsegmentation nav-link text-center  <?php echo ($cabtype == 1) ? 'active' : '' ?>" id="local-tab-center" data-value="1" data-toggle="tab" href="#local-center" aria-controls="local-center" role="tab" aria-selected="true">
											Local
										</a>
									</li>
									<li class="nav-item mr0 flex-fill">
										<a class="cabsegmentation nav-link text-center <?php echo ($cabtype == 2) ? 'active' : '' ?>" id="outstation-tab-center" data-value="2" data-toggle="tab" href="#outstation-center" aria-controls="outstation-center" role="tab" aria-selected="false">
											Outstation
										</a>
									</li>
									<li class="nav-item mr0 flex-fill">
										<a class="cabsegmentation nav-link text-center" id="airport-tab-center" data-value="2" data-toggle="tab" href="#airport-center" aria-controls="airport-center" role="tab" aria-selected="false">
											Airport
										</a>
									</li>
								</ul>
								<div class="tab-content ui-box-main pl0">
									<div class="tab-pane <?php echo ($cabtype == 1) ? 'active' : '' ?>" id="local-center" aria-labelledby="local-tab-center" role="tabpanel">
										<div class="row radio-style6 justify-center">
											<div class="col-12 col-lg-3 col-md-3 ui-facetune">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('9', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-5.png" alt="" class="lozad img-fluid img-no">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">Daily Rental on hourly basis</div>
															</div>
														</a>
														<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[66] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>
											<div class="col-12 col-md-4 col-lg-3 ui-facetune">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('4_1', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-3.png" alt="" class="lozad img-fluid img-no">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">Pick-up from airport</div>
															</div>
														</a>
														<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[64] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>


											<div class="col-12 col-lg-3 col-md-3 ui-facetune">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('4_2', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-4.png" alt="" class="lozad img-fluid img-no">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">Drop-off to airport</div>
															</div>
														</a>
														<p class="mt10 d-none d-lg-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[65] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>

										</div>

										<div class="inner-tab">
										<table class="table table-striped shadow-small mb10">
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
										<div class="text-center mb15">
											<a class="btn btn-primary text-uppercase font-16" href="<?= Yii::app()->createAbsoluteUrl('/bknw/dayrental/' . strtolower($cmodel->cty_alias_path)) ?>">Book</a>
										</div>
				<!--							<p class="mt0 mb0 pl10">* &nbsp;Excluding Tax</p>-->
									</div>
									</div>
									<div class="tab-pane <?php echo ($cabtype == 2) ? 'active' : '' ?>" id="outstation-center" aria-labelledby="outstation-tab-center" role="tabpanel">
										<div class="row radio-style6 justify-center">
											<div class="col-12 col-md-4 col-lg-3">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('1', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-7.png" alt="One-way trip" width="150" height="150" class="img-fluid img-no lozad">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">One-way trip</div>
															</div>
														</a>
														<p class="mt10 d-none d-md-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[61] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>
											<div class="col-12 col-md-4 col-lg-3">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('2', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-8.png" alt="Round trip" width="150" height="150" class="img-fluid img-no lozad">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">Round trip</div>
															</div>
														</a>
														<p class="mt10 d-none d-md-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[63] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>
											<div class="col-12 col-md-4 col-lg-3">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('3', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-6.png" alt="Multi-city multi-day trip" width="150" height="150" class="img-fluid img-no lozad">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">Multi-city multi-day trip</div>
															</div>
														</a>
														<p class="mt10 d-none d-md-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[62] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>
										</div>

										<div class="row">
										<div class="col-12 mb10 pt20"><h2 class="font-16"><b>Top places to visit from <?= $cmodel->cty_name; ?></b></h2></div>
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
												<div class="col-12">

													<div class="card mb20">
														<div class="card-body">
															<span class="weight500"><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Cab</span>
															<div class="row">
																<div class="col-8 pr0">
																	<img src="/images/bxs-tachometer.svg" alt="img" width="14" height="14"> <?= $top['rut_distance']; ?> kms | <img src="/images/bx-time-five.png" alt="img" width="13" height="13"> <?= floor($top['rut_time'] / 60) . " hours"; ?>
																	<p class="mt20 mb0 weight500 color-orange2"><?= $top['rut_distance']; ?> kms included</p>
																	<p class="font-13 weight400 color-gray mb0">Charges after 184 Km @ ₹25/km</p>
																</div>
																<div class="col-4 pl5 text-right font-20">
																	<?= ($top['seadan_price'] > 0) ? '<span>&#x20b9</span><b>' . $top['seadan_price'] . '</b>' : '<a href="javascript:helpline();"><img src="/images/img-2022/bxs-phone-call.svg" alt="Call" width="20" height="20" class="preload-image" style="display:inline!important;"></a>'; ?>
																	<p class="font-13 weight400">Onwards</p>
																	<p class="mb0"><a class="btn btn-primary btn-sm text-uppercase font-16" href="<?= Yii::app()->createAbsoluteUrl('/book-taxi/' . $top['rut_name']) ?>">Book</a></p>
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
											<div class="content-boxed-widget" style="overflow: hidden;">
												<span align="center">No routes yet found.</span>
											</div>
											<?php
										}
										?>
									</div>
									</div>
									<div class="tab-pane <?php echo ($cabtype == 3) ? 'active' : '' ?>" id="airport-center" aria-labelledby="airport-tab-center" role="tabpanel">
										<div class="row mb-2 radio-style6 justify-center">
											<div class="col-12 col-md-4 col-lg-3">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('4_1', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no lozad">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">Pick-up from airport (Local)</div>
															</div>
														</a>
														<p class="mt10 d-none d-md-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[64] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>
											<div class="col-12 col-md-4 col-lg-3">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('4_2', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no lozad">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">Drop-off to airport (Local)</div>
															</div>
														</a>
														<p class="mt10 d-none d-md-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[65] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>
											<div class="col-12 col-md-4 col-lg-3">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('1_1', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no lozad">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">Pick-up from airport (Outstation)</div>
															</div>
														</a>
														<p class="mt10 d-none d-md-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[82] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>
											<div class="col-12 col-md-4 col-lg-3">
												<div class="ui-box d-flex">
													<div class="ui-inner-facetune flex-grow-1" onclick="submitServiceType('1_2', '<?php echo strtolower($cmodel->cty_alias_path) ?>')">
														<a href="javascript:void(0);">
															<img data-src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no lozad">
															<div class="ui-text-facetune">
																<div class="mb-0 font-16">Drop-off to airport (Outstation)</div>
															</div>
														</a>
														<p class="mt10 d-none d-md-block"><a href="javascript:void(0);" class="btn btn-primary font-12 mt5 pl10 pr10 color-white hvr-push btn-ride">Book your ride</a></p>
													</div>
													<div class="face-info"><a href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<?= $tncArr1[83] ?>"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="d-none d-lg-block">
						<h1 class="font-24 mb0">Book Sanitized & Disinfected car rental in <?php echo $cmodel->cty_name; ?> with driver for local and outstation trips with Gozo</h1>
						<p>Book sanitized & disinfected car rental in the city with a driver for safe travel locally IN-THE-CITY or on
							outstation trips India wide with aaocab.</p>
						<p>Gozo Cabs is taking precautionary measures during the pandemic to ensure you have the safest journey by disinfecting and sanitizing the cars before and after every ride.</p>

						<p>Book Local Hourly car rental for IN-THE-CITY ride and outstation cab service for intercity travel at an
							affordable price with aaocab.</p>
						<p>Check our hourly rental cab and outstation cab fares below. Our fares
							update dynamically in response to market demand and supply conditions so booking in advance is
							always best.</p>
						<section>
							<h2 class="font-22 mb0 mt30">COVID-19 Pandemic Update for Gozo Cabs travel</h2>
							<p>Gozo is known for its always on-time, guaranteed cab service across India. After the Corona Virus
								pandemic of 2020, We have instituted a process to ensure clean and sanitized conditions in our cabs.
								Starting April 2020, Gozo drivers are now disinfecting and sanitizing the Gozo cabs after arriving at your
								place for pickup. Our goal is to give you peace of mind and have you be sure that the cab has been
								cleaned to your satisfaction. Safety of our drivers & customers is of utmost importance to us. Our driver
								will practice safety measures and we request and require that our passengers do so too!</p>

							<p>Exchanging currency notes is not a good idea during the pandemic. So we require that you plan on
								paying for your cab fare in full online. You can make a part payment before your trip starts and the
								remainder of the payment will also need to be paid by you online</p>
						</section>
					</div>
					<section class="d-none d-lg-block">
						<h3 class="font-22 mb0 mt30">Hourly car rental fares for local trips with aaocab Day Rental!</h3>
						<p>Hey <?= $cmodel->cty_name ?>! Now You can now request for aaocab at unbelievably attractive prices for local rentals and outstation cab services.
							With cab fares starting from ₹<?= $dayRentalprice[9][1]; ?> (includes 4 hr & 40 kms) for local day rentals.</p>

						<p>By booking aaocab for local rentals you have a cab & driver for a fixed number of hours and take as many stops as we drive you around the city as you like during the time of your booking 
							Whether you want to go for  shopping, for back to back meetings, weddings or sightseeing, aaocab is at your disposal, waiting for you, just like your own car.</p>
						<p>And the best part – you have the option to choose the package that you like. Our local rental prices are</p>


						<div class="card table-responsive">
							<div class="card-body p10">
								<table class="table table-striped mb-0 table-bordered text-center">
									<thead>
										<tr>
											<td class='col-xs-1'><b>Package Details</b></td>
											<td class='col-xs-1'><b>Compact </b></td>
											<td class='col-xs-1'><b>Sedan </b></td>
											<td class='col-xs-1'><b>SUV  </b></td>
										</tr></thead><tbody>
										<?php
										foreach ($dayRentalprice as $key => $dayrental)
										{
											$rentalPackage = ($key == 9) ? "4 Hrs & 40 Kms local rental" : (($key == 10) ? "8 Hrs & 80 Kms local rental" : "12 Hrs & 120 Kms local rental");
											?>
											<tr>
												<td align="center"><?php echo $rentalPackage; ?></td>
												<td align="center"><?php echo "&#x20B9; " . $dayRentalprice[$key][1] . " + tax"; ?></td>
												<td align="center"><?php echo "&#x20B9; " . $dayRentalprice[$key][3] . " + tax"; ?></td>
												<td align="center"><?php echo "&#x20B9; " . $dayRentalprice[$key][2] . " + tax"; ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</section>
					<section class="d-none d-lg-block">
						<h3 class="font-22 mb0 mt30">Why book a day rental with Gozo?</h3>
						Get the same high quality and great prices that you have come to expect from Gozo. Now for local city rentals too.<br/>

						<ul class="mt15 pl15">
							<li class="mb15"><strong> Cabs at your Disposal:</strong> With aaocab Day Rentals you get cabs at your disposal for as long as you want and travel to multiple stop points with just one booking within city limits.<br/></li>
							<li class="mb15"><strong>Affordable Packages:</strong> Packages start for 4 hours and can extend up to 12 hours! Also, with some nominal additional charges cabs can be retained beyond package limits.<br/></li>
							<li class="mb15"><strong>Flexible Bookings:</strong>  Easily plan a day out without having to worry about conveyance as with aaocab Day Rentals you can book a cab in advance and ride as per your convenience.<br/></li>
							<li class="mb15"><strong>Pay Cash or go Cashless:</strong>  Now go cashless and travel easy. We have multiple payment option for your hassle-free transaction.<br/> </li>
							<li class="mb15"><strong>No waiting or surge charges:</strong> Unlike point to point services you get a cab and driver at your disposal for the length of time of your rental. If you know you are going to have a busy day traveling around town, just book a car and driver for the day and go where you want in town</li>	
						</ul>
					</section>
					<section class="d-none d-lg-block">
						<h4 class="font-24 mb0 mt30">Outstation Car rental fares for popular places to visit around <?= $cmodel->cty_name ?>
							<!-- Rating start here -->
							<?php
							if ($ratingCountArr['ratings'] > 0)
							{
								?>
								<a href="<?= Yii::app()->createUrl('city-rating/' . $cmodel->cty_name); ?>"><small title="<?php echo $ratingCountArr['ratings'] . ' rating from ' . $ratingCountArr['cnt'] . ' reviews'; ?>">
										<?php
										$strRating = '';

										$rating_star = floor($ratingCountArr['ratings']);
										if ($rating_star > 0)
										{
											$strRating .= '(';
											for ($s = 0; $s < $rating_star; $s++)
											{
												$strRating .= '<i class="fa fa-star orange-color"></i>';
											}
											if ($ratingCountArr['ratings'] > $rating_star)
											{
												$strRating .= '<i class="fa fa-star-half orange-color"></i> ';
											}
											$strRating .= ' ' . $ratingCountArr['cnt'] . ' reviews)';
										}
										echo $strRating;
										?>
									</small>
								</a>
							<?php } ?><!--fb like button-->
							<div class="fb-like pull-right mb30" data-href="https://facebook.com/aaocab" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
							<!--fb like button--></h4>
						<!-- Rating start here -->
						<div class="card table-responsive">
							<div class="card-body p10">
								<table class="table table-striped mb-0 table-bordered text-center">
									<thead>
										<tr>
											<td class='col-xs-2'><b>Route (Starting at)</b></td>
											<?php
											if ($flexi_count > 0)
											{
												?>
												<td class='col-2'><b>Shared Taxi</b></td>
												<?php
											}
											?>
											<td class='col-xs-1'><b>Compact</b></td>
											<td class='col-xs-1'><b>Sedan</b></td>
											<td class='col-xs-1'><b>SUV</b></td>
											<td class='col-xs-1'><b>Tempo traveler<br> (9 seater)</b></td>
											<td class='col-xs-1'><b>Tempo traveler<br> (12 seater)</b></td>
											<td class='col-xs-1'><b>Tempo traveler<br> (15 seater)</b></td>
										</tr></thead><tbody>
										<?php
										if (count($topTenRoutes) > 0)
										{
											foreach ($topTenRoutes as $top)
											{
												?>        
												<tr>
													<td><a href="<?= Yii::app()->createAbsoluteUrl("/book-taxi/" . $top['rut_name']); ?>" target="_blank" class="color-black"><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</a></td>
													<?php
													if ($flexi_count > 0)
													{
														?>
														<td align="center"><?= ($top['flexi_price'] > 0) ? '&#x20B9;' . $top['flexi_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
														<?php
													}
													?>
													<td align="center"><?= ($top['compact_price'] > 0) ? '&#x20B9;' . $top['compact_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
													<td align="center"><?= ($top['seadan_price'] > 0) ? '&#x20B9;' . $top['seadan_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
													<td align="center"><?= ($top['suv_price'] > 0) ? '&#x20B9;' . $top['suv_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
													<td align="center"><?= ($top['tempo_9seater_price'] > 0) ? '&#x20B9;' . $top['tempo_9seater_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
													<td align="center"><?= ($top['tempo_12seater_price'] > 0) ? '&#x20B9;' . $top['tempo_12seater_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
													<td align="center"><?= ($top['tempo_15seater_price'] > 0) ? '&#x20B9;' . $top['tempo_15seater_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
												</tr>
												<?php
											}
										}
										else
										{
											?>
											<tr><td align="center" colspan="7">No routes yet found.</td></tr>
											<?php
										}
										?>
									</tbody>    
								</table>
							</div>
						</div>
						<div class="mt15">Gozo's booking and billing process is <a href="http://www.aaocab.com/blog/billing-transparency/" class="color-black">completely transparent</a> and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.</div>
						<p>On the Gozo platform you can also create a multi-day tour package by customizing your itinerary. These are created a round trip bookings between 2 or more cities</p>
					</section>
					<section class="d-none d-lg-block">
						<h4 class="font-22 mb0 mt30">TRAVEL PRECAUTIONS BEING TAKEN DURING THE CORONA VIRUS PANDEMIC</h4>
						<ol class="pl15">
							<li class="mb15">
								Health and safety of our employees, driver partners and customers is of utmost importance
								for travel in the presence of COVID-19 across the country</li>
							<li class="mb15">Before you book your travel – check government guidelines to make sure that your vehicle 
								can be routed from the source address to the destination address. Our teams will check for
								routing also after we receive your booking. In some cases, we may need to cancel your
								booking if the routing is not possible due to travel restrictions.
							</li>    
							<li class="mb15">Once you have booked a cab, we will provide you with the cab and driver information as soon
								as we can. You may use this information to get an electronic travel pass issued from the 
								authorities. In many parts of the country, it is required that a customer have a travel
								authorization (travel pass) before we can serve your trip.
							</li>       
							<li class="mb15">Gozo will provide you with a sanitized taxi cab for your travel. For your satisfaction and 
								peace, the driver will sanitize the vehicle in your presence after arriving for pickup. Our carse
								are disinfected at the start and end of every trip, however for your mental satisfaction its 
								important that our driver disinfects & sanitizes the car in your presence as well.
							</li>
							<li class="mb15">It is REQUIRED that both drivers and passengers have the Aarogya setu app installed on theirs
								phones. A Gozo driver may refuse to provide service if the customer cannot show proof that
								they have the Aarogya Setu app installed. Cost of cancellation for such reasons shall be
								borne by the traveller.
							</li>
							<li class="mb15">In light of these additional precautionary and sanitization measures, Gozo’s taxi cab rates
								may be slightly elevated than other taxi operators.
							</li>
							<li class="mb15">Our customer service centers are available to answer any questions for you. For quick and
								timely service, we recommend that you communicate to us by chat or email during this time.
							</li>
						</ol>
					</section>
					<section style="word-wrap: break-word;">
						<h4 class="font-22 mb0">Rent a Car in <?php echo $cmodel->cty_name; ?> for outstation travel, day-based rentals and airport transfers</h4>
						<p>Rent a Gozo cab with driver for local and outstation travel in <?= $cmodel->cty_name; ?>. Gozo provides taxis with driver on rent in <?php echo $cmodel->cty_name; ?>, including one way outstation taxi drops, outstation roundtrips and outstation shared taxi to nearby cities or day-based hourly rentals in or around <?php echo $cmodel->cty_name; ?>. You can also book cabs for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns. Car rental services in <?php echo $cmodel->cty_name; ?> are also available for flexible outstation packages that are billed by custom package tour itinerary or by day</p>
						<p>Gozo is India’s leader in outstation car rental. We provide economy, premium and luxury cab rental services for outstation and local travel over 3000 towns & cities and on over 50,000 routes all around India. 
							If you prefer to hire specific brands of cars like Innova or Swift Dzire then book our Assured Sedan or Assured SUV cab categories. You are guaranteed a Toyota Innova when you book an Assured SUV or guaranteed a Swift Dzire when you book an Assured Sedan. 
							With Gozo you can book a one way cab with driver from  <?= $cmodel->cty_name; ?> to <?= $nearby_city ?> and many more. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x365. Gozo partners with regional taxi operators in <?php echo $cmodel->cty_name; ?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We also provide local sightseeing trips and tours in or around the city. Our customers include domestic & international tourists, large event groups and business travellers who take rent cars for outstation trips and also for local taxi requirements in the <?= $cmodel->cty_name; ?> area.
						</p>
					</section>
					<section>
						<h4 class="font-22 mb0 mt30">Outstation shared taxi and shuttle services are also available in <?= $cmodel->cty_name ?></h4>
						<p>In September of 2018, Gozo has introduced the facility to hire a AC shared taxi by seat. We call this service Gozo SHARED. There are two types of services available. Gozo runs regular SHARED TAXI shuttle services on popular routes. Book a seat in our a shared taxi shuttle  at our book a Shared taxi Shuttle page.  Or you can book a seat in our Gozo FLEXXI AC outstation shared services.</p><p> With Gozo FLEXXI you are going to carpool with a person who has booked a full taxi and is willing to share his seats. Gozo FLEXXI is available in all major cities and on all popular outstation taxi routes across India. Gozo FLEXXI is much cheaper than traveling by an AC bus</p>
						<p>If you have firm plans and prefer to rent your own taxi, you can simply book a FLEXXI option and choose to sell your unused seats. Gozo cabs will help promote your unused seats to riders who are willing to travel in a shared taxi. </p> 
						<?php
						if ($cmodel['cty_has_airport'] == 1)
						{
							?>
							<h4 class="font-22 mb0 mt30">Hire Airport taxi with driver in <?= $cmodel->cty_name; ?> with meet and greet services</h4>
							Car rentals are available for outstation travel and airport pickup or drop at <?= $cmodel->cty_alias_path; ?> Airport.  Many business and international  travellers use our chauffeur driven airport pickup and drop taxi services. These airport taxi transfers can be arranged with meet and greet services enabling smooth transportation to or from <?= $cmodel->cty_name; ?> airport to your office, hotel or address of choice. Typical airport transfer trips are between <?= $cmodel->cty_name; ?> city center and <?= $cmodel->cty_name; ?> airport. We also serve transportation from <?= $cmodel->cty_name; ?> Airport to cities nearby. If you have a special requirement, simply ask our customer service team who will do their best to support your needs.

						<?php }
						?>
						<div class="col-12 col-lg-3 float-right mt30">
							<div class="card text-center">
								<div class="card-body p10">
									<div class="car_box2"><img src="/images/cabs/tempo_9_seater.jpg" width="130" height="73" alt="Tempo 9 Seater"></div>
									<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>" class="color-black">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
								</div>
							</div>
							<div class="card text-center">
								<div class="card-body p10">
									<div class="car_box2"><img src="/images/cabs/car-etios.jpg" width="130" height="73" alt="Etios"></div>
									<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>" class="color-black">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
								</div>
							</div>
							<?php
							$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
							if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)), $selected_cities))
							{
								?>
								<div class="card text-center">
									<div class="card-body p10">
										<div class="car_box2"><img src="/images/cabs/car-etios.jpg" width="130" height="73" alt="Etios"></div>
										<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>" class="color-black">Luxury car rental in <?= $cmodel->cty_name; ?></a>
									</div>
								</div>
								<?php
							}

							if ($cmodel['cty_has_airport'] == 1)
							{
								?>
								<div class="card text-center">
									<div class="card-body p10">
										<div class="car_box2"><img src="/images/cabs/car-etios.jpg" width="130" height="73" alt="Etios"></div>
										<a href="/airport-transfer/<?= strtolower($cmodel->cty_alias_path) ?>" class="color-black">Airport transfer in <?= $cmodel->cty_name; ?></a>
									</div>
								</div>
								<?php
							}
							?>
						</div>
						<?php
						if ($cmodel->cty_city_desc != "")
						{
							?>

							<h4 class="mt30">Little about <?= $cmodel->cty_name; ?> </h4>
							<p><?= $cmodel->cty_city_desc; ?></p>
							<?php
						}
						?>
						<?php
						$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';
						if ($text1 != "")
						{
							?><p>If You are Foodie, <?=
								$text1;
							}
							?>:</p>

						<!--------------------------------->
						<h4 class="font-22 mb0 mt30">Best time for renting a car in <?= $cmodel->cty_name; ?></h4>
						<p><?= $cmodel->cty_name; ?> enjoys a maritime climate throughout the year and to be very realistic you can visit the city in any time of the year. However, the months between September and February see the highest tourist inflow to the city because the weather becomes even more pleasant during the Winter season. The winter months in <?= $cmodel->cty_name; ?> are the best time to visit the city.</p>
					</section>
					<section>
						<h4 class="font-22 mb10 mt30">Things to look at while booking an outstation cab to and from  <?= $cmodel->cty_name; ?> </h4>
						<ol class="ul-style-e pl15">
							<li class="mb15">Ensure that a commercial taxi is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>
							<li class="mb15">Find if the route you will travel on has tolls. If yes, are tolls included in your booking quote</li>
							<li class="mb15">When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same</li>
							<li class="mb15">Price is great but service is what matters. So try to focus on service at a reasonable price. When you go for the cheapest, you will ending up getting what you pay for.<br><br></li>
						</ol>
					</section>
				</div>

				<div itemscope="" itemtype="https://schema.org/FAQPage" class="col-12">
					<h4>FAQs About <?= $cmodel->cty_name ?> Cabs</h4>
					<div class="pt10">
						<?php
						$faqArray = Faqs::getDetails(2);
						foreach ($faqArray as $key => $value)
						{
							?>
							<div>
								<div class="mb20" itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
									<div>
										<p itemprop="name" class="font-14 mb0"><b><?php echo $value['faq_question']; ?></b></p>
									</div>
									<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="">
										<div itemprop="text">
											<?php echo $value['faq_answer']; ?>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</article>
	<?php
}
?>


<script type="text/javascript">
function submitServiceType(transfertype, fcity)
	{
		//debugger;
		var ttype = 0;
		if (transfertype.indexOf('_') > -1)
		{
			let arrytrnsfrtype = transfertype.split('_');
			$('#BookingTemp_bkg_transfer_type').val(arrytrnsfrtype[1]);
			ttype = arrytrnsfrtype[1];
			transfertype = arrytrnsfrtype[0];
		}
		$('#BookingTemp_bkg_booking_type').val(transfertype);
		$(".alertsericetype").html('');
		$(".alertsericetype").hide();

		if (ttype > 0)
		{
			window.location.href = "<?= Yii::app()->createUrl('booking/itinerary') ?>" + '/bkgType/' + transfertype + '/type/' + ttype;
		}
		else
		{
			window.location.href = "<?= Yii::app()->createUrl('booking/itinerary') ?>" + '/bkgType/' + transfertype + '/fcity/' + fcity;
		}
	}
</script>