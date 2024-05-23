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
.face-info a{ background: #fff; color: #475F7B; padding: 10px 10px; display: inline-table; border-radius: 0 5px 5px 0;}
.ui-box-main .face-info a{ background: #fff; color: #475F7B; padding: 2px 10px; display: block; border-radius: 0 5px 5px 0;}
.ui-box-main .ui-inner-facetune a{ padding: 8px 15px 12px 15px;}
</style>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>
<?php
if (isset($jsonStructureProductSchema) && trim($jsonStructureProductSchema) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonStructureProductSchema; ?>
	</script>
<?php } ?>
<?php
if (isset($jsonStructureMarkupData) && trim($jsonStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonStructureMarkupData; ?>
	</script>
<?php } ?>
<?php
if (isset($outstationBreadcumbStructureMarkupData) && trim($outstationBreadcumbStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $outstationBreadcumbStructureMarkupData; ?>
	</script>
<?php } ?>
<?php
$this->newHome = true;
/* @var $cmodel Cities */
?>
<?= $this->renderPartial('application.views.booking.fblikeview')?>
<div class="container content-padding p10 bottom-5">
		<div class="above-overlay text-center">
			<h1 class="top-0 font-18">Book Outstation Cabs for travel to or from <?= $cmodel->cty_name; ?></h1>
		</div>
	</div>
<?php
$tncType		 = TncPoints::getTncIdsByStep(4);
$tncArr			 = TncPoints::getTypeContent($tncType);
$tncArr1		 = json_decode($tncArr, true);

#$cities        = ($count['countCities'] > 500) ? 500 : $count['countCities'];
#$routes        = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
$topCitiesByKm = '';
$ctr           = 1;
foreach ($topCitiesKm as $top)
{
    $topCitiesByKm .= '<a href="/outstation-cabs/' . strtolower($top['cty_alias_path']) . '" style=" text-decoration: none; color: #282828;" target="_blank">' . $top['city'] . '</a>';
    $topCitiesByKm .= (count($topCitiesKm) == $ctr) ? " " : ", ";
    $ctr++;
}
$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';
$minRate = (min(array_column($topTenRoutes, 'min_rate')) > 0) ? min(array_column($topTenRoutes, 'min_rate')) : 10;
?>
<div class="mt0">
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



<?php
//                        echo "<br>";
//                        echo "time_start->".$time_start = microtime(true);
	$k=0;
	if (count($topTenRoutes) > 0)
	{
		foreach ($topTenRoutes as $top)
		{
			$k++;
//print_r($top);
			?> 


	<div class="style-box-1 ac-1 out-accordion">
				<div class="card p15">
					<div class="card-body">
						<div class="content p0 mb10">
							<h3 class="font-16"><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</h3>
							<div id="30366">
								<div class="" style="width: 70%; float: left;">
									<p class="mb5"><?= $top['rut_distance']; ?> kms | <?= floor($top['rut_time'] / 60) . " hours"; ?></p>
									<p class="mt20 mb0 weight500 color-orange"><?= '<b>' . $top['rut_distance'] . '</b>'; ?> kms included</p>
									<p class="font-13 weight400 mb0">Charges after <?= '<b>' . $top['rut_distance'] . '</b>'; ?> Kms @ <span>&#x20b9</span><?= $top['extraKmRate'] ?>/km</p>
									
								</div>
								<div class="text-right font-20"  style="width: 30%; float: left;">
									<?= ($top['seadan_price'] > 0) ? '<b>' . Filter::moneyFormatter($top['seadan_price']) . '</b>' : '<a href="javascript:helpline();"><img src="/images/bxs-phone3.svg" width="20"></a>'; ?>
									<p class="font-13 weight400 mb10">Onwards</p>
									<p class="mb0"><a class="ultrabold btn-green-blue default-link uppercase font-16" style="width: 100%;" href="<?= $this->getOneWayUrlFromPath($top['from_city_alias_path'], $top['to_city_alias_path']) ?>">Book</a></p>
								</div>
								<div class="clear"></div>
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

<div class="content" itemscope="" itemtype="https://schema.org/FAQPage">			
			<!--fb like button-->
				<div class="fb-like" data-href="https://facebook.com/gozocabs" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
			<!--fb like button-->
	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mt15 mb0" itemprop="name">Looking for a reliable and affordable way to book outstation cab or taxi from <?= $cmodel->cty_name ?>? - starting at ₹<?= $minRate ?>/km</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
			Gozocabs is India's leading online taxi and cab booking app, offering a wide range of services to meet all your travel needs.
            We provide outstation cab or taxi booking services with driver in <?= $cmodel->cty_name; ?> for day-based rentals, one way, round trips, multicity travel and many more that are billed by custom itinerary or by day.
			Gozocabs is the best cab booking app for cheap and reliable taxi booking. We offer competitive fares on all our services, and we also offer a variety of discounts and promotions.
			<div class="content text-center top-10 text-center">
					<img src="/images/cabs/tempo_9_seater.jpg" alt="Tempo Traveller" width="200" height="113" class="preload-image bottom-5" style="display: inline-block;">
					<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)) ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
				</div>
			
			<div class="content text-center top-10"><img src="/images/cabs/car-etios.jpg" alt="Tempo Traveller" width="200" height="113" class="preload-image bottom-5" style="display: inline-block;">
					<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)) ?>">Car rental option in <?= $cmodel->cty_name; ?></a>
				</div>
					<p class="mb0">Our customers book outstation taxi services in <?= $cmodel->cty_name; ?> for trips to most nearby cities. 
             AC cabs are the most comfortable way to travel to or from <?= $cmodel->cty_name; ?>. Since the driver is a local, they are familiar with the routes and this makes it much more convenient than hiring a self-drive car. Most business travelers rent cabs for flexible travel between <?= $cmodel->cty_name; ?> and nearby towns. Gozo provides compact, sedan and SUV outstation car rentals in addition to minibus for groups (tempo traveller) and luxury cars for executives. Shared outstation taxis are also available between <?= $cmodel->cty_name; ?> and <?=$topCitiesByKm;?>. Gozo’s cab rental services are available in over 1000 cities and on 50,000+ routes across the country. Gozo’s Outstation taxi charges are among the most reasonable, as Gozo cabs specializes in inter-city travel which gives us the greatest reach, largest supply and strong relationships with taxi operators who can deliver to you the best services with courteous and well-informed drivers. 
	         Drivers working with Gozo are familiar with the routes in <?= $cmodel->cty_name; ?> and the various regions surrounding <?= $cmodel->cty_name; ?> area. </p>
				</div>
			</div>
		</div>		
	</section>

	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb0" itemprop="name">Book Shared outstation cabs from or to <?= $cmodel->cty_name; ?></h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">Gozo FLEXXI SHARE is available on many routes to and from <?= $cmodel->cty_name; ?>. Instead of booking a bus ticket to or from <?= $cmodel->cty_name; ?> you should take a look at Gozo FLEXXI SHARE.
               Visit our booking page to see the availability of shared seats in cabs to and from <?= $cmodel->cty_name; ?>. All frequently traveled routes from <?= $cmodel->cty_name; ?> generally have seats available in a shared taxi.</p>
					<p class="mb0">Visit our booking page to see availability of shared seats in cabs to and from <?= $cmodel->cty_name; ?>. All frequently travelled routes from <?= $cmodel->cty_name; ?> generally have seats available in shared taxi.</p>
				</div>
			</div>
		</div>
	</section>
			
	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb0" itemprop="name">Hire a taxi with driver for the day in <?= $cmodel->cty_name; ?></h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">Most of our corporate clients prefer to have a chauffeur driver car at their disposal for when they are visiting <?= $cmodel->cty_name; ?> for either a few hours, full day or a few days. Having a outstation car hire available works out to be very convenient and also time & cost efficient for business travelers. Various businesses  use our corporate travel services to avail these services in the cities and towns they travel to frequently. Our services of outstation cab in India will serve you all through the roads of India (the major cities). With Gozo you always get transparent billing, fair prices, 24x7 support and nationwide reach. If you have any special requirements you can always contact our customer helpdesk and we will be happy to support you.</p>
					<p class="mb20">With Gozo you always get transparent billing, fair prices, 24x7 support and nationwide reach. If you have any special requirements you can always contact our customer helpdesk and we will be happy to support you.</p>
				</div>
			</div>
		</div>
	</section>
			
	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb0" itemprop="name">Outstation cabs in <?= $cmodel->cty_name; ?></h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">With Gozo you can book an outstation cabs 24x7 from <?= $cmodel->cty_name; ?> to <?= $topCitiesByKm; ?>. </p>
				</div>
			</div>
		</div>
	</section>
			
	<p class="mb10"><b>Here are some common questions that you should ask your provider when booking an outstation cab.</b></p>
	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb0" itemprop="name">Can I book an outstation cab in <?= $cmodel->cty_name; ?> on-demand and on-the-fly using my app?</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">Due to the nature of outstation travel, most trips are pre-scheduled and pre-booked enabling us to arrange for the right vehicle for your journey. We encourage customers to make reservations at least 3 weeks ahead of your date of travel as prices have a tendency to rise as vehicle supply becomes limited. Gozo can arrange for vehicles for outstation travel from <?= $cmodel->cty_name; ?> even at the last minute but in most cases please anticipate at least 2hours of time between you making your reservation and the vehicle arriving for pickup in <?= $cmodel->cty_name; ?>. We have been able to deliver vehicles in much shorter times but please take this as a general guidance.</p>
</div>
			</div>
		</div>
	</section>

	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb0" itemprop="name">What is the daily allowance for the driver when renting an outstation cab in <?= $cmodel->cty_name; ?>?</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">When renting a vehicle for outstation cab booking while you travel in India, the driver is traveling out of town and hence his daily expenses like food, lodging etc are covered with the daily allowance. 
, the driver is traveling out of town and hence his daily expenses like food, lodging etc are covered with the daily allowance. The drivers daily allowance.</p>
				</div>
			</div>
		</div>
	</section>
            
	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb0" itemprop="name">What are the different types of trips that a person can take for outstation travel from <?= $cmodel->cty_name; ?>?</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text"> 
					<p class="mb20">You can book a one-way trip, round trip or plan a itinerary for travel to multiple cities from <?= $cmodel->cty_name; ?>. When traveling in a outstation taxi from <?= $cmodel->cty_name; ?> you are renting outstation car with driver. Gozo provides with well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x36 for outstation taxi booking from <?= $cmodel->cty_name; ?>.</p>
					<p class="mb0">What is included as part of the charges for an outstation trip?</p>
					<ul>
						<li>As part of an outstation journey your charges include a certain number of pre-allocated kms and the applicable taxes for the journey.</li>
						<li>For one-way trips from <?= $cmodel->cty_name; ?>- we may include the applicable tolls as your route of travel is pre-defined. </li>
						<li>In the case of round trips or multi-day journeys starting in <?= $cmodel->cty_name; ?>, the customer is expected to pay for all applicable tolls along the way.  For a roundtrip or multi-day journeys for outstation cab from <?= $cmodel->cty_name; ?>, you may use the vehicle upto the allocated time and number of kms without any extra km driving charges.</li>
						<li>Typically a one-way trip is a point to point transfer between two cities. We need you to provide us the exact pickup and drop addresses. Your price quotation is based on the estimated kms of travel between the two locations assuming there will be no intermediate stops or waypoints or sightseeing. Its simply a intercity transfer from A to B in a cab that is for your use. You do not share the cab with anyone else.</li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb0" itemprop="name">Are the drivers knowledgeable about the highways and the journey from <?= $cmodel->cty_name; ?>?</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">Gozo uses local taxi operators in <?= $cmodel->cty_name; ?>  for its <?= $cmodel->cty_name; ?> outstation taxi service who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We can also provide local sightseeing trips and tours in or around the city if you require any day rental needs.regarding our cab for outstation from <?= $cmodel->cty_name; ?> </p> 
				</div>
			</div>
		</div>
	</section>

	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb0" itemprop="name">How clear is the pricing and billing?</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">Gozo's booking and billing process for outstation car hire from <?= $cmodel->cty_name; ?> is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price. We encourage you to read the terms and conditions on your booking confirmation. With Gozo you can book a taxi anywhere in India. Our services are top rated in almost all cities across India.</p> 
				</div>
			</div>
		</div>
	</section>

	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb0" itemprop="name">If I rent a outstation cab, can it be used for sightseeing in <?= $cmodel->cty_name; ?>?</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">Outstation cabs can be used for sight-seeing as well. Typically we allocate between 250 and 300 kms per day for you to use the vehicle for either outstation transportation or sightseeing during your journey. When you rent a vehicle for <?= $cmodel->cty_name; ?> outstation cab or in <?= $cmodel->cty_name; ?> for a certain number of days, you are pre-purchasing a certain number of kilometers to be driven on your journey. 
If you are renting a vehicle for outstation cab <?= $cmodel->cty_name; ?> for a one-way journey then it is not possible to use the vehicle for sight-seeing trips. One-way trips are pre-scheduled and booked based on transfers from a fixed pickup point to a fixed drop point. 
Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?= $cmodel->cty_name; ?>. For any special needs, cab for outstation from <?= $cmodel->cty_name; ?>  simply call our service center and we will do our best to help.</p>
				</div>
			</div>
		</div>
	</section>
	
<section>
		<h4 class="mb0">FAQs About <?= $cmodel->cty_name ?> Cabs</h4>
						<div class="pt10">
						<?php 
	                        $faqArray = Faqs::getDetails(3);
							foreach ($faqArray as $key => $value)
							{
								$cityQueReplace     = str_replace('{#fromCity#}', $cmodel->cty_name, $value['faq_question']);
								$cityAnsReplace     = str_replace('{#fromCity#}', $cmodel->cty_name, $value['faq_answer']);
								$kmChargeAnsReplace = str_replace('{#perKmCharge#}', $minRate, $cityAnsReplace);
						?>
							<div>
								<div class="mb20" itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
									<div>
									<p itemprop="name" class="font-14 mb0"><b><?php echo trim($cityQueReplace,'Q:'); ?></b></p>
									</div>
									<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="">
										<div itemprop="text">
										   <?php echo trim($kmChargeAnsReplace,'A.'); ?>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
	</section>
            </div>

	<!--<p class="link-panel">
				<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_name)); ?>">Book Tempo Traveller online with Gozo</a>
	</p>-->
    
<script type="text/javascript">
function helpline()
{
	//$('.helpline').click();
	reqCMB(1);
}

</script>		