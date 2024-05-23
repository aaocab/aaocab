<style>
	.call-cust a{ float: right;}
	td{ padding: 5px 0;}

	.out-accordion .accordion a{ width: 100%; line-height: 24px; text-align: left;}
	.out-accordion .accordion .button{ width: 30%; display: inline-block;}
	.call-cust a{ text-align: center!important; display: inline-block;}
	td{ padding: 5px 0;}
	.tab-styles .t-style{ background: #EEF3FA; width: 90%; margin: 0 auto; border-radius: 50px; border: #ccd9eb 1px solid; padding: 3px;}
	.tab-style a{ border-radius: 50px;}
	.tab-style a.active{ background: #5A8DEE;}
	.style-box-1 .card{ box-shadow: 0 0 0 0!important; border: #ddd 1px solid; border-radius: 5px; margin: 10px 20px;}
	.ui-inner-facetune a{ width: 100%; padding: 16px 15px 16px 15px; border-radius: 5px 0 0 5px; color: #475F7B; font-size: 15px; font-weight: 500;  text-align: left;}
	.ui-box{ width: 90%; margin: 0 auto; margin-top: 10px; box-shadow: none; border: #ddd 1px solid; min-height:inherit; border-radius: 5px; padding-bottom: 0px; position: relative;}
	.ui-box2{ margin-bottom: 10px; box-shadow: none; border: #ddd 1px solid; min-height:inherit; border-radius: 5px; padding-bottom: 0px; display: block; position: relative;}
	.info-2{ width: 26px; height: 26px; background: url(/images/css_sprites1.png?v=0.5) -39px -40px;}
	.face-info{ position: initial; text-align: left; position: absolute; top: 0; right: 0;}
	.face-info a{ background: #fff; color: #475F7B; padding: 10px 10px; display: block; border-radius: 0 5px 5px 0;}
	.ui-box-main .face-info a{ background: #fff; color: #475F7B; padding: 2px 10px; display: block; border-radius: 0 5px 5px 0;}
	.ui-box-main .ui-inner-facetune a{ padding: 8px 15px 12px 15px;}
</style>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php
$this->newHome = true;
/* @var $cmodel Cities */

$tncType = TncPoints::getTncIdsByStep(4);
$tncArr	 = TncPoints::getTypeContent($tncType);
$tncArr1 = json_decode($tncArr, true);
?>
<div class="container content-padding p10 bottom-0">
	<div class="above-overlay text-center">
		<h1 class="bold top-0 font-16">Tempo traveller fares for rentals in and around <?= $cmodel->cty_name; ?></h1>
	</div>
</div>
<?= $this->renderPartial('application.views.booking.fblikeview') ?>
<div>

	<?//= $this->renderPartial('application.views.index.topSearch', array('model' => $model), true, FALSE); ?>
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
</div>
<div>
	<?php
	$c		 = 0;
	if (count($topTenRoutes) > 0)
	{
		foreach ($topTenRoutes as $top)
		{
			$c		 = $c + 1;
			$rutname = $top['rut_name'];
			?>

			<input type="hidden" name="step" value="1">   
			<!--- --->
			<div class="style-box-1 ac-1 out-accordion">
				<div class="card p15">
					<div class="card-body">
						<div class="content p0 mb0">
							<h3 class="font-16"><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Cab</h3>
							<div class="one-sixth mr5 mb10">Tempo traveler (9 seater)</div>
							<div class="one-half last-column text-right font-16"><?= ($top['tempo_9seater_price'] > 0) ? '<span>&#x20b9</span><b>' . $top['tempo_9seater_price'] . '</b>' : '<a href="javascript:void(0)" onclick="reqCMB(1,9,' . "'$rutname'" . ')"><img src="/images/call.png" alt="" style=display:inline;></a>'; ?></div>
							<div class="clear"></div>
							<div class="one-sixth mr5 mb10">Tempo traveler (12 seater)</div>
							<div class="one-half last-column text-right font-16"><?= ($top['tempo_12seater_price'] > 0) ? '<span>&#x20b9</span><b>' . $top['tempo_12seater_price'] . '</b>' : '<a href="javascript:void(0)" onclick="reqCMB(1,12,' . "'$rutname'" . ')"><img src="/images/call.png" alt="" style=display:inline;></a>' ?></div>
							<div class="clear"></div>
							<div class="one-sixth mr5 mb10">Tempo traveler (15 seater)</div>
							<div class="one-half last-column text-right font-16"><?= ($top['tempo_15seater_price'] > 0) ? '<span>&#x20b9</span><b>' . $top['tempo_15seater_price'] . '</b>' : '<a href="javascript:void(0)" onclick="reqCMB(1,15,' . "'$rutname'" . ')"><img src="/images/call.png" alt="" style=display:inline;></a>'; ?></div>
							<div class="clear"></div>
							<?php
							if ($top['tempo_9seater_price'] > 0 || $top['tempo_12seater_price'] > 0 || $top['tempo_15seater_price'] > 0)
							{
								?>
								<div class="text-right">
									<a class="ultrabold btn-green-blue default-link uppercase font-16" href="<?= $this->getOneWayUrlFromPath($top['from_city_alias_path'], $top['to_city_alias_path']) ?>">Book</a>
								</div>
								<?php
							}
							?>
							<div class="clear"></div>
						</div>
					</div>
				</div>

			</div>
			<!--- ------->

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


<!--<div class="content-boxed-widget gradient-green-blue">
        <h1 class="font-20">Save upto 50% on every booking*</h1>
        <span class="h5 text-uppercase mt0 mb5 mt10">Taxi and Car rentals all over India – Gozo’s online cab booking service</span><br>
        Gozocabs is your one-stop shop for hiring chauffeur driven taxis in India for inter-city, airport transfers and even local daily tours, specializing in one-way taxi trips all over India at transparent fares. Gozo’s coverage extends across <b>2,000</b> locations in India, with over <b>20,000</b> vehicles, more than <b>75,000</b> satisfied customers and above <b>10</b> Million kms driven in a year alone. Book by web, phone or app 24x7! 
</div>-->
<div class="content">
	<?php
	$cities = ($count['countCities'] > 500) ? 500 : $count['countCities'];

	$routes			 = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
	$topCitiesByKm	 = '';
	$ctr			 = 1;

	foreach ($topCitiesKm as $top)
	{
		$topCitiesByKm	 .= '<a href="/tempo-traveller-rental/' . strtolower($top['cty_alias_path']) . '" style=" text-decoration: none; color: #282828;" target="_blank">' . $top['city'] . '</a>';
		$topCitiesByKm	 .= (count($topCitiesKm) == $ctr) ? " " : ", ";
		$ctr++;
	}
	$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';


	for ($t = 0; $t < count($topTenRoutes); $t++)
	{
		$city_arr[] = $topTenRoutes[$t]['to_city'];
	}
	$city_str = implode(",", $city_arr);
	?>
	<section id="section2" itemscope="" itemtype="https://schema.org/FAQPage">
		<!--fb like button-->
		<div class="fb-like top-10 left" data-href="https://facebook.com/gozocabs" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
		<!--fb like button-->


		<div class="content p0 bottom-0 text-center">
			<img src="/images/cabs/car-etios.jpg" alt="" class="preload-image responsive-image mb0">
			<a class="default-link" href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>" class="color-highlight">Book car rental in <?= $cmodel->cty_name; ?></a>
		</div>
		<div class="content p0 bottom-0 text-center">
			<img src="/images/cabs/car-etios.jpg" alt="" class="preload-image responsive-image mb0">
			<a class="default-link" href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>" class="color-highlight">Book outstation taxi rental in <?= $cmodel->cty_name; ?></a>
		</div>
		<section>
			<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
				<h2 class="font-16 mb0" itemprop="name">Best Tempo Traveller on rent in <?= ucfirst($cmodel->cty_name); ?> for lowest prices</h2>
				<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
					<div itemprop="text">
						<p class="mb15">When Gozo's compact, sedan and SUV category of vehicles is a perfect fit for customers looking to hire outstation cabs, day rentals or airport transfers we find that larger groups can rent larger vehicles like minivans, tempo travellers or buses. In order to address the needs of large families traveling together or business groups attending company meetings or events Gozo provides Budget Tempo Traveller rentals at cheapest prices in all top cities across India.</p>
						<p class="mb15">With Gozo you can hire tempo traveller from <?= $cmodel->cty_name ?> to nearby cities  <?= $city_str; ?> and many more. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available to book online 24x7x365 . Gozo uses local operators in <?= $cmodel->cty_name ?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. You can also rent tempo travellers for local sightseeing trips and tours in or around the <?= $cmodel->cty_name ?>. Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?= $cmodel->cty_name ?>.</p>
					</div>
				</div>
			</div>
		</section>

		<section>
			<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
				<h2 class="font-16 mb0" itemprop="name">WHY HIRE TEMPO TRAVELLER FROM GOZO IN <?= strtoupper($cmodel->cty_name); ?></h2>
				<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
					<div itemprop="text">
						<p class="mb15">Gozo is india’s leader in tempo traveller rentals and provides services in over 3000 towns & cities along over 50,000 intercity routes in the nation.</p>
						<p class="mb15">When you rent a tempo traveller from Gozo you get transparent billing, low prices and 24x7 support along with our nationwide reach. 
							You can rent tempo traveller for round trips and If you have any special requirements like deluxe or luxury tempo travellers you can 
							always contact our customer service helpdesk. Gozo can arrange 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 24, 26, 27 seaters 
							Tempo Travellers on Hire in our fleet across India.</p>
						<p class="mb15">With a fleet of hundreds of tempo travellers nationwide, we promise to provide you with an unmatched variety of both luxury and non-luxury tempo traveller rentals.
							Our tempo travellers come equipped with pushback or recliner seats with ample leg and moving spaces that ensure a comfortable travel experience. 
							Other common amenities like luggage storage, water bottle, blankets, charging point, reading light, central TV, etc. may also be provided (varies from vehicle to vehicle). 
							All our tempo travellers available on rent in <?= $cmodel->cty_name; ?> are safety compliant.</p>
						<p class="mb15">In addition to tempo travellers, Gozo provides various services in <?= $cmodel->cty_name; ?> including one way taxi drops or short roundtrips to nearby cities. 
							You can also book cabs for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns.
							For those not from India, Tempo traveller is the brand for a Minibus and is available generally in 3 different type of seating configurations with various capacity. 
							We provide 9 seater, 12 seater or 15 seater Tempo travellers.</p>
						<p class="mb15">Due to the specific and less frequent nature of these requirements we request customers to make their reservations as early as at least 10 days in advance. This provides us sufficient advance notice to arrange a vehicle for your journey.</p>
					</div>
				</div>
			</div>	
		</section>

		<section>
			<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
				<h2 class="font-16 mb0" itemprop="name">HIRE A TEMPO TRAVELLER ONLINE WITH GOZO CABS</h2>
				<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
					<div itemprop="text">
						<p class="mb15">Gozo's booking and billing process is <a href="https://www.gozocabs.com/blog/billing-transparency/">completely transparent</a> and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.
							With Gozo you can book a taxi anywhere in India. Our services are top rated in almost all cities across India.
						</p>
					</div>
				</div>
			</div>
		</section>
	</section>
</div>   
<div id="callmebackmessagebody"></div>