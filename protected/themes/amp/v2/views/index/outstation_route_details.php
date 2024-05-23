<?php
$tncType = TncPoints::getTncIdsByStep(4);
$tncArr	 = TncPoints::getTypeContent($tncType);
$tncArr1 = json_decode($tncArr, true);

$cities        = ($count['countCities'] > 500) ? 500 : $count['countCities'];
$routes        = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
$topCitiesByKm = '';
$ctr           = 1;
foreach ($topCitiesKm as $top)
{
    $topCitiesByKm .= '<a href="/outstation-cabs/' . strtolower($top['cty_alias_path']) . '" style="color: #282828;" target="_blank">' . $top['city'] . '</a>';
    $topCitiesByKm .= (count($topCitiesKm) == $ctr) ? " " : ", ";
    $ctr++;
}
$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';
?>

<section id="section2">
		<div id="desc" class="newline">
			<h3 class="text-center m0">Book Outstation cabs for travel to or from <?= $cmodel->cty_name; ?></h3>
		</div>
    <amp-selector class="tabs-with-flex" role="tablist">
			<div class="tap-contens">&nbsp;</div>
			<div id="tab2" role="tab" aria-controls="tabpanel2" option selected>Local</div>			
			<div id="tabpanel2" role="tabpanel" aria-labelledby="tab2">
				
                <div class="justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/dayrental4/<?= strtolower($cmodel->cty_alias_path) ?>">Daily Rental on hourly basis</a></div>
							</div>
							
						</div>
							<amp-accordion id="my-accordiondr" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="p15 pt0"><?= $tncArr1[66] ?></div>
								</section>
						</amp-accordion>	

					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/airport-pickup/<?= strtolower($cmodel->cty_alias_path) ?>">Pick-up from airport</a></div>
							</div>
						</div>
	                    <amp-accordion id="my-accordionap" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="p15 pt0"><?= $tncArr1[64] ?></div>
								</section>
						</amp-accordion>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/airport-drop/<?= strtolower($cmodel->cty_alias_path) ?>">Drop-off to airport</a></div>
							</div>
						</div>
	
						<amp-accordion id="my-accordionad" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="p15 pt0"><?= $tncArr1[65] ?></div>
								</section>
						</amp-accordion>
					</div>

				</div>
            </div>
			<div id="tab1" role="tab" aria-controls="tabpanel1" option>Outstation</div>
			<div id="tabpanel1" role="tabpanel" aria-labelledby="tab1">
				
				<div class="justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16">
									<a href="/book-cab/one-way/<?= strtolower($cmodel->cty_alias_path) ?>">One-way trip</a>

								</div>
							</div>

						</div>
						<amp-accordion id="my-accordionow" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<?= $tncArr1[61] ?>
								</section>
						</amp-accordion>
						
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/round-trip/<?= strtolower($cmodel->cty_alias_path) ?>">Round trip</a></div>
							</div>
						</div>
						<amp-accordion id="my-accordionrt" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<?= $tncArr1[63] ?>
								</section>
						</amp-accordion>						
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/multi-city/<?= strtolower($cmodel->cty_alias_path) ?>">Multi-city multi-day trip</a></div>
							</div>
                        </div>
						<amp-accordion id="my-accordionmt" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<?= $tncArr1[62] ?>
								</section>
						</amp-accordion>						
					</div>
				</div>
             </div>

			<div id="tab3" role="tab" aria-controls="tabpanel3" option>Airport</div>
			<div id="tabpanel3" role="tabpanel" aria-labelledby="tab3">
				<div class="mb15 justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/airport-pickup/<?= strtolower($cmodel->cty_alias_path) ?>">Pick-up from airport (Local)</a></div>
							</div>
						</div>
	
					<amp-accordion id="my-accordionapl" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="p15 pt0"><?= $tncArr1[64] ?></div>
								</section>
						</amp-accordion>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/airport-drop/<?= strtolower($cmodel->cty_alias_path) ?>">Drop-off to airport(Local)</a></div>
							</div>
						</div>
	
					<amp-accordion id="my-accordionadl" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="p15 pt0"><?= $tncArr1[65] ?></div>
								</section>
						</amp-accordion>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/inter-city/airport-pickup/<?= strtolower($cmodel->cty_alias_path) ?>">Pick-up from airport (Outstation)</a></div>
							</div>
						</div>
	
					<amp-accordion id="my-accordionapo" disable-session-states>
								<section class="info-accordion">
									<h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="p15 pt0"><?= $tncArr1[82] ?></div>
								</section>
						</amp-accordion>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/inter-city/airport-drop/<?= strtolower($cmodel->cty_alias_path) ?>">Drop-off to airport (Outstation)</a></div>
							</div>
						</div>
	
					<amp-accordion id="my-accordionado" disable-session-states>
								<section class="info-accordion">
									<h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="p15 pt0"><?= $tncArr1[83] ?></div>
								</section>
						</amp-accordion>
					</div>
				</div>
			</div>
		</amp-selector>
	<div class="wrraper mt10">
		
		<div class="sample">
			<?php
			if (count($topTenRoutes) > 0)
			{    $c=0;
				foreach ($topTenRoutes as $top)
				{  
					$c=$c+1;
					if($c==1)
					{
						$class ='expanded';
					}
					else
					{
						$class ='';
					}
						
			?>
            
            
            <div class="card-view">
                        <div class="title-panel"><h3 class="font16"><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</h3></div>

                      <div class="card-view-left">
							<p class="mb5"><?= $top['rut_distance']; ?> kms | <?= floor($top['rut_time'] / 60) . " hours"; ?></p>
							<p class="mt30 mb0 weight500 color-orange"><?= '<b>' . $top['rut_distance'] . '</b>'; ?> kms included</p>
									<p class="font12 weight400 color-gray mb0">Charges after <?= '<b>' . $top['rut_distance'] . '</b>'; ?> Kms @ <span>&#x20b9</span><?= $top['extraKmRate'] ?>/km</p>
					  </div>
                        <div class="card-view-right">
                            
                            <span class="card-text2"><?= ($top['seadan_price'] > 0) ?  '<amp-img src="/images/rupees-amp3.png" alt="" width="9" height="12"></amp-img>'.$top['seadan_price'] : '<a href="tel:9051877000"><amp-img src="/images/img-2022/bx-phone-call.svg" alt="" width="20" height="20"></amp-img></a>'; ?></span>
							<p class="font11">Onwards</p>
                            <div class="btn-book mt10"><a href="<?= $this->getOneWayUrlFromPath($top['from_city_alias_path'], $top['to_city_alias_path']) ?>" >Book</a></div>
                        </div>
                        <div style="width: 100%; float: left; margin-top: 10px;">
                            
                            <div class="" style="width: 70%; float: left;">
									
									
									
								</div>
                            
                            
<!--                            <amp-img src="/images/img-2022/bx-group.svg" alt="" width="12" height="12"></amp-img> <?php  print($cab['vct_capacity'] . '</span>') ?> <span class="pl5 pr5">|</span>
                            <amp-img src="/images/img-2022/bx-briefcase-alt.svg" alt="" width="12" height="12"></amp-img>
                            <span class="pl5 pr5">|</span>-->
                        </div>
                    </div>
            
			<?php
				}
			}
			?>
			</div>
</div>
</section>
		<div class="page-content" itemscope="" itemtype="https://schema.org/FAQPage">
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mt15 mb5" itemprop="name">Looking for a reliable and affordable way to book outstation cab or taxi from <?= $cmodel->cty_name ?>? - starting at ₹<?= $minRate ?>/km</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
			Gozocabs is India's leading online taxi and cab booking app, offering a wide range of services to meet all your travel needs.
            We provide outstation cab or taxi booking services with driver in <?= $cmodel->cty_name; ?> for day-based rentals, one way, round trips, multicity travel and many more that are billed by custom itinerary or by day.
			Gozocabs is the best cab booking app for cheap and reliable taxi booking. We offer competitive fares on all our services, and we also offer a variety of discounts and promotions.
			<div class="main_time border-blueline text-center main_time2">
					<amp-img src="/images/cabs/tempo_9_seater.jpg" alt="Tempo Traveller" width="130" height="67" class="preload-image bottom-5" style="display: inline-block;"></amp-img>
					<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)) ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
				</div>
			
			<div class="main_time border-blueline text-center main_time2"><amp-img src="/images/cabs/car-etios.jpg" alt="Tempo Traveller" width="130" height="67" class="preload-image bottom-5" style="display: inline-block;"></amp-img>
					<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)) ?>" class="color-highlight">Car rental option in <?= $cmodel->cty_name; ?></a>
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
			<h2 class="font-16 mb5" itemprop="name">Book Shared outstation cabs from or to <?= $cmodel->cty_name; ?></h2>
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
			<h2 class="font-16 mb5" itemprop="name">Hire a taxi with driver for the day in <?= $cmodel->cty_name; ?></h2>
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
			<h2 class="font-16 mb5" itemprop="name">Outstation cabs in <?= $cmodel->cty_name; ?></h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">With Gozo you can book an outstation cabs 24x7 from <?= $cmodel->cty_name; ?> to <?= $topCitiesByKm; ?>. </p>
				</div>
			</div>
		</div>
	</section>
		<section>
		<div class="main_time border-blueline text-center">
			<div class="car_box2">
				<amp-img width="125px" height="63px"  src="/images/cabs/car-etios.jpg" ></amp-img>
			</div>
			<a href="<?=Yii::app()->createAbsoluteUrl("/amp/car-rental/".strtolower($top['from_city_alias_path']));?>">Book car rental in <?= $cmodel->cty_name; ?></a>
		</div>
		<div id="desc" class="main_time border-blueline text-center">
			<div class="car_box2">
				<amp-img width="125px" height="63px"  src="/images/cabs/tempo_9_seater.jpg" ></amp-img>
			</div>
			<a href="<?=Yii::app()->createAbsoluteUrl("/amp/tempo-traveller-rental/".strtolower($top['from_city_alias_path']));?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
		</div>	
</section>
<p class="mb10"><b>Here are some common questions that you should ask your provider when booking an outstation cab.</b></p>
		 <section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb5" itemprop="name">Can I book an outstation cab in <?= $cmodel->cty_name; ?> on-demand and on-the-fly using my app?</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">Due to the nature of outstation travel, most trips are pre-scheduled and pre-booked enabling us to arrange for the right vehicle for your journey. We encourage customers to make reservations at least 3 weeks ahead of your date of travel as prices have a tendency to rise as vehicle supply becomes limited. Gozo can arrange for vehicles for outstation travel from <?= $cmodel->cty_name; ?> even at the last minute but in most cases please anticipate at least 2hours of time between you making your reservation and the vehicle arriving for pickup in <?= $cmodel->cty_name; ?>. We have been able to deliver vehicles in much shorter times but please take this as a general guidance.</p>
</div>
			</div>
		</div>
	</section>

	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb5" itemprop="name">What is the daily allowance for the driver when renting an outstation cab in <?= $cmodel->cty_name; ?>?</h2>
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
			<h2 class="font-16 mb5" itemprop="name">What are the different types of trips that a person can take for outstation travel from <?= $cmodel->cty_name; ?>?</h2>
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
			<h2 class="font-16 mb5" itemprop="name">Are the drivers knowledgeable about the highways and the journey from <?= $cmodel->cty_name; ?>?</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">Gozo uses local taxi operators in <?= $cmodel->cty_name; ?>  for its <?= $cmodel->cty_name; ?> outstation taxi service who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We can also provide local sightseeing trips and tours in or around the city if you require any day rental needs.regarding our cab for outstation from <?= $cmodel->cty_name; ?> </p> 
				</div>
			</div>
		</div>
	</section>

	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb5" itemprop="name">How clear is the pricing and billing?</h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
				<div itemprop="text">
					<p class="mb20">Gozo's booking and billing process for outstation car hire from <?= $cmodel->cty_name; ?> is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price. We encourage you to read the terms and conditions on your booking confirmation. With Gozo you can book a taxi anywhere in India. Our services are top rated in almost all cities across India.</p> 
				</div>
			</div>
		</div>
	</section>

	<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
			<h2 class="font-16 mb5" itemprop="name">If I rent a outstation cab, can it be used for sightseeing in <?= $cmodel->cty_name; ?>?</h2>
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
									<p itemprop="name" class="font-14 mb0"><b><?php echo trim($cityQueReplace,"Q: "); ?></b></p>
									</div>
									<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="">
										<div itemprop="text">
										   <?php echo trim($kmChargeAnsReplace,"A."); ?>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
	</section>
		
		</div>
