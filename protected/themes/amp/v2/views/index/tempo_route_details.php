<?php
	$cities			 = ($count['countCities'] > 500) ? 500 : $count['countCities'];
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
?>
<?php
//print_r($topTenRoutes);
for ($t = 0; $t < count($topTenRoutes); $t++)
{
	$city_arr[] = $topTenRoutes[$t]['to_city'];
}
$city_str = implode(",", $city_arr);

$tncType = TncPoints::getTncIdsByStep(4);
$tncArr	 = TncPoints::getTypeContent($tncType);
$tncArr1 = json_decode($tncArr, true);
?>


<section id="section2">
	<div id="desc" class="col-xs-12 col-sm-7 col-md-8 feature">
		<div class="newline text-center">
			<h3> Tempo traveller fares for rentals in and around <?= $cmodel->cty_name; ?></h3>
		</div>

		<amp-selector class="tabs-with-flex" role="tablist">
			<div class="tap-contens">&nbsp;</div>
			<div id="tab2" role="tab" aria-controls="tabpanel2" option selected>Local</div>			
			<div id="tabpanel2" role="tabpanel" aria-labelledby="tab2">
				
                <div class="justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/dayrental4/<?= strtolower($cmodel->cty_alias_path) ?>">Daily Rental on hourly basis</a></div>
							</div>
							
						</div>
						<div class="a-2">
							<amp-accordion id="my-accordiondr" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="accordion-content p15 pt0"><?= $tncArr1[66] ?></div>
								</section>
						</amp-accordion>
</div>	

					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/airport-pickup/<?= strtolower($cmodel->cty_alias_path) ?>">Pick-up from airport</a></div>
							</div>
						</div>
<div class="a-2">
	                    <amp-accordion id="my-accordionap" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="accordion-content p15 pt0"><?= $tncArr1[64] ?></div>
								</section>
						</amp-accordion>
</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/airport-drop/<?= strtolower($cmodel->cty_alias_path) ?>">Drop-off to airport</a></div>
							</div>
						</div>
	<div class="a-2">
						<amp-accordion id="my-accordionad" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="accordion-content p15 pt0"><?= $tncArr1[65] ?></div>
								</section>
						</amp-accordion>
</div>
					</div>

				</div>
            </div>
			<div id="tab1" role="tab" aria-controls="tabpanel1" option>Outstation</div>
			<div id="tabpanel1" role="tabpanel" aria-labelledby="tab1">
				
				<div class="justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16">
									<a href="/book-cab/one-way/<?= strtolower($cmodel->cty_alias_path) ?>">One-way trip</a>

								</div>
							</div>

						</div>
<div class="a-2">
						<amp-accordion id="my-accordionow" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									 <div class="accordion-content pt0"><?= $tncArr1[61] ?></div>
								</section>
						</amp-accordion>
</div>
						
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/round-trip/<?= strtolower($cmodel->cty_alias_path) ?>">Round trip</a></div>
							</div>
						</div>
<div class="a-2">
						<amp-accordion id="my-accordionrt" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									 <div class="accordion-content pt0"><?= $tncArr1[63] ?></div>
								</section>
						</amp-accordion>
</div>					
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/multi-city/<?= strtolower($cmodel->cty_alias_path) ?>">Multi-city multi-day trip</a></div>
							</div>
                        </div>
<div class="a-2">
						<amp-accordion id="my-accordionmt" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									 <div class="accordion-content pt0"><?= $tncArr1[62] ?></div>
								</section>
						</amp-accordion>
</div>						
					</div>
				</div>
             </div>

			<div id="tab3" role="tab" aria-controls="tabpanel3" option>Airport</div>
			<div id="tabpanel3" role="tabpanel" aria-labelledby="tab3">
				<div class="mb15 justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/airport-pickup/<?= strtolower($cmodel->cty_alias_path) ?>">Pick-up from airport (Local)</a></div>
							</div>
						</div>
	<div class="a-2">
					<amp-accordion id="my-accordionapl" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="accordion-content p15 pt0"><?= $tncArr1[64] ?></div>
								</section>
						</amp-accordion>
</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/airport-drop/<?= strtolower($cmodel->cty_alias_path) ?>">Drop-off to airport(Local)</a></div>
							</div>
						</div>
	<div class="a-2">
					<amp-accordion id="my-accordionadl" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="accordion-content p15 pt0"><?= $tncArr1[65] ?></div>
								</section>
						</amp-accordion>
</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/inter-city/airport-pickup/<?= strtolower($cmodel->cty_alias_path) ?>">Pick-up from airport (Outstation)</a></div>
							</div>
						</div>
	<div class="a-2">
					<amp-accordion id="my-accordionapo" disable-session-states>
								<section class="info-accordion">
									<h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="accordion-content p15 pt0"><?= $tncArr1[82] ?></div>
								</section>
						</amp-accordion>
</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune a-1" style="width: 87%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/inter-city/airport-drop/<?= strtolower($cmodel->cty_alias_path) ?>">Drop-off to airport (Outstation)</a></div>
							</div>
						</div>
	<div class="a-2">
					<amp-accordion id="my-accordionado" disable-session-states>
								<section class="info-accordion">
									<h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<div class="accordion-content p15 pt0"><?= $tncArr1[83] ?></div>
								</section>
						</amp-accordion>
</div>
					</div>
				</div>
			</div>
		</amp-selector>
          
       <div class="wrraper">
			<div class="sample">
				<?php
				if (count($topTenRoutes) > 0)
				{
					$c = 0;
					foreach ($topTenRoutes as $top)
					{
						$class	 = '';
						$c		 = $c + 1;
						if ($c == 1)
						{
							$class = 'expanded';
						}
						?> 	
						<section>
                         
							<div class="accordion-style2">
								<h4><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</h4>
								<div class="table-view flex">
									<div class="table-view-left">Tempo traveler(9 seater)</div> 
									<div class="table-view-right"><?= ($top['tempo_9seater_price'] > 0) ? '&#x20B9;' . $top['tempo_9seater_price'] : '<a href="'.Yii::app()->createUrl('scq/form').'"><amp-img src="/images/img-2022/bx-phone-call.svg" alt="" width="15" height="15"></a>'; ?> </div>
								</div>
								<div class="table-view flex">
									<div class="table-view-left">Tempo traveler(12 seater)</div> 
									<div class="table-view-right"><?= ($top['tempo_12seater_price'] > 0) ? '&#x20B9;' . $top['tempo_12seater_price'] : '<a href="'.Yii::app()->createUrl('scq/form').'"><amp-img src="/images/img-2022/bx-phone-call.svg" alt="" width="15" height="15"></a>'; ?> </div>
								</div>
								<div class="table-view flex">
									<div class="table-view-left">Tempo traveler(15 seater)</div> 
									<div class="table-view-right"><?= ($top['tempo_15seater_price'] > 0) ? '&#x20B9;' . $top['tempo_15seater_price'] : '<a href="'.Yii::app()->createUrl('scq/form').'"><amp-img src="/images/img-2022/bx-phone-call.svg" alt="" width="15" height="15"></a>'; ?></amp-img></a> </div>
								</div>
								<div class="table-view flex">
									<div class="table-view-left">&nbsp;</div> 
									<div class="table-view-right"><div class="btn-book text-right pt10"><a href="<?= $this->getOneWayUrlFromPath($top['from_city_alias_path'], $top['to_city_alias_path']) ?>" >Book</a></div> </div>
								</div>
							</div>
						</section>
						<?php
					}
				}
				?>
			</div>
        </div>
       <div class="wrraper mt10 p10">
          <h3> Best Tempo Traveller on rent in <?= $cmodel->cty_name; ?> for lowest prices </h3>
        <p>When aaocab's compact, sedan and SUV category of vehicles is a perfect fit for customers looking to hire outstation cabs, day rentals or airport transfers we find that larger groups can rent larger vehicles like minivans, tempo travellers or buses. In order to address the needs of large families traveling together or business groups attending company meetings or events aaocab provides Budget Tempo Traveller rentals at cheapest prices in all top cities across India.
        <p>With aaocab you can hire tempo traveller from <?=$cmodel->cty_name?> to nearby cities  <?=$city_str;?> and many more. aaocab provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available to book online 24x7x365 . aaocab uses local operators in <?=$cmodel->cty_name?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. You can also rent tempo travellers for local sightseeing trips and tours in or around the <?=$cmodel->cty_name?>. Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?=$cmodel->cty_name?>.</p>
       </div>
		<div class="wrraper mt20">

			<div class="main_time border-blueline text-center">
				<div class="car_box2">
					<amp-img width="125px" height="63px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
				</div>
				<a href="<?=Yii::app()->createAbsoluteUrl("/amp/car-rental/".strtolower($top['from_city_alias_path']));?>">Book car rental in <?= $cmodel->cty_name; ?></a>
			</div>
			<div id="desc" class="main_time text-center">
				<div class="car_box2">
					<amp-img width="125px" height="63px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
				</div>
				<a href="<?=Yii::app()->createAbsoluteUrl("/amp/outstation-cabs/".strtolower($top['from_city_alias_path']));?>">Book outstation taxi rental in <?= $cmodel->cty_name; ?></a>
			</div>
		</div>
		<div class="wrraper mt10 p10">
			<h3 class="m0">WHY HIRE TEMPO TRAVELLER FROM aaocab IN <?= strtoupper($cmodel->cty_name); ?></h3>
			<p>aaocab is india’s leader in tempo traveller rentals and provides services in over 3000 towns & cities along over 50,000 intercity routes in the nation.</p>
<p>When you rent a tempo traveller from aaocab you get transparent billing, low prices and 24x7 support along with our nationwide reach. 
You can rent tempo traveller for round trips and If you have any special requirements like deluxe or luxury tempo travellers you can 
always contact our customer service helpdesk. aaocab can arrange 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 24, 26, 27 seaters 
Tempo Travellers on Hire in our fleet across India.</p>
			<p>With a fleet of hundreds of tempo travellers nationwide, we promise to provide you with an unmatched variety of both luxury and non-luxury tempo traveller rentals.
 Our tempo travellers come equipped with pushback or recliner seats with ample leg and moving spaces that ensure a comfortable travel experience. 
 Other common amenities like luggage storage, water bottle, blankets, charging point, reading light, central TV, etc. may also be provided (varies from vehicle to vehicle). 
 All our tempo travellers available on rent in <?= $cmodel->cty_name; ?> are safety compliant.</p>
<p>In addition to tempo travellers, aaocab provides various services in <?= $cmodel->cty_name; ?> including one way taxi drops or short roundtrips to nearby cities. 
You can also book cabs for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns.
For those not from India, Tempo traveller is the brand for a Minibus and is available generally in 3 different type of seating configurations with various capacity. 
We provide 9 seater, 12 seater or 15 seater Tempo travellers.</p>
<p>Due to the specific and less frequent nature of these requirements we request customers to make their reservations as early as at least 10 days in advance. This provides us sufficient advance notice to arrange a vehicle for your journey.</p>
<h3 class="mt20">HIRE A TEMPO TRAVELLER ONLINE WITH aaocab CABS </h3>
<p>aaocab's booking and billing process is <a href="http://www.aaocab.com/blog/billing-transparency/">completely transparent</a> and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.
With aaocab you can book a taxi anywhere in India. Our services are top rated in almost all cities across India.
</p>
		</div>
	</div>
</section>
<?php
                $topCities    = [];
$arrFCityData = Route::getCitiesForUrl();

$topRoutes = Route::getTopRouteByType(1, $arrFCityData);
if (count($arrFCityData) <= 0)
{
    $topCities = Route::getTopRouteByType(2);
}
?>
<div class="page-content list-view-content">
		<?php if(count($topRoutes) > 0) { ?>
		<div class="mb-1">
			<p class="font16 mt-1 merriw mb5"><b>Popular outstation cab routes</b></p>
				<ul class="pl0 mt0">
					<?php
					foreach ($topRoutes as $route)
					{
						?>
						<li><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="route-icon"><a href="<?= Yii::app()->createAbsoluteUrl("/book-taxi/" . strtolower(str_replace(' ', '-', $route['trc_type_path']))); ?>" title="Book taxi from <?= $route['fromCityName']; ?> to <?= $route['toCityName']; ?>" target="_blank" > <?= $route['fromCityName']; ?> to <?= $route['toCityName']; ?></a></li>
						<?php
					}
					?>
				</ul>
		</div>
		<?php } if(count($topCities) > 0) { ?>
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 merriw mb5"><b>Top cities</b> <span class="font-12">(Hourly Rentals, Airport Transfers, Outstation)</span></p></div>
			<div class="col-12">
				<ul>
					<?php
					foreach ($topCities as $topcity)
					{
						?>
						<li><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="route-icon"><a href="<?= Yii::app()->createAbsoluteUrl("/outstation-cabs/" . strtolower(str_replace(' ', '-', $topcity['trc_type_path']))); ?>" target="_blank" title="Outstation cabs from <?= $topcity['fromCityName'] ?>">Outstation cabs from <?= $topcity['fromCityName'] ?></a></li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
		<?php } ?>
		
	</div>