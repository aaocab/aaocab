<?php
if (isset($cityJsonProductSchema) && trim($cityJsonProductSchema) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $cityJsonProductSchema;
	?>
	</script>
<?php } ?>
<?php if (isset($cityJsonMarkupData) && trim($cityJsonMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $cityJsonMarkupData;
	?>
	</script>
<?php } ?>
<?php if (isset($cityBreadMarkupData) && trim($cityBreadMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php
	echo $cityBreadMarkupData;
	?>
	</script>
<?php } ?>
<?php if (isset($jsonproviderStructureMarkupData) && trim($jsonproviderStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php
	$jsonproviderStructureMarkupData;
	?>
	</script>
<?php } ?>
<?php
$has_shared_sedan	 = 0;
?>

<?php
$oneway				 = "oneway";
$round				 = "roundtrip";
$multi				 = "multitrip";
$dailyrental		 = "dayrental";
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
		$topCitiesByKm	 .= '<a href="/car-rental/' . strtolower($top['cty_alias_path']) . '" style=" text-decoration: none; color: #1a73e8;">' . $top['city'] . '</a>';
		$topCitiesByKm	 .= (count($topCitiesKm) == $ctr) ? " " : ", ";
		$ctr++;
	}
	for ($i = 1; $i <= 5; $i++)
	{
		$city_arr [] = $topCitiesByRegion[$i]['cty_alias_path'];
	}

	$nearby_city = implode(", ", $city_arr);
	//echo '<pre>';
	//print_r($topCitiesByRegion);
	$text1		 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';

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
	<section id="section2">
		<div id="desc" class="newline text-center">
			<h2 class="mt0">Rent a Car in <?= $cmodel->cty_name; ?> </h2>
	<?php
	if ($ratingCountArr['ratings'] > 0)
	{
		?>
				<a href="<?= Yii::app()->createUrl('city-rating/' . $cmodel->cty_name); ?>" style="color: #fff;">
					<small title="<?php echo $ratingCountArr['ratings'] . ' rating from ' . $ratingCountArr['cnt'] . ' reviews'; ?>">
						<?php
						$strRating	 = '';
						//print_r($ratingCountArr['ratings']);
						$rating_star = floor($ratingCountArr['ratings']);
						if ($rating_star > 0)
						{
							$strRating = '';
							for ($s = 0; $s < $rating_star; $s++)
							{
								$strRating .= '<amp-img width="24px" height="24px" class="lozad" src="/images/star-amp.png" alt=""></amp-img>';
							}
							if ($ratingCountArr['ratings'] > $rating_star)
							{
								$strRating .= '<amp-img width="24px" height="24px" class="lozad" src="/images/star-amp2.png" alt=""></amp-img><br>';
							}
							$strRating .= $ratingCountArr['cnt'] . ' reviews';
						}
						echo $strRating;
						?>

					</small></a>
	<?php } ?>
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

				<div class="">
                    	<div class="inner-tab">
							<h2 class="font-16 mt20">Hourly car rental packages</h2>
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
										</div>
									</div>
								</div>
							</div>
							<div class="btn-book mt20 text-center">
								<a href="<?= Yii::app()->createAbsoluteUrl('/bknw/dayrental/' . strtolower($cmodel->cty_alias_path)) ?>">Book</a>
							</div>
	<!--							<p class="mt0 mb0 pl10">* &nbsp;Excluding Tax</p>-->
						</div>
<!--					<div class="sample mt20">

						<?php
						foreach ($dayRentalprice as $key => $dayrental)
						{
							$rentalPackage	 = ($key == 9) ? "4 Hrs & 40 Kms local rental" : (($key == 10) ? "8 Hrs & 80 Kms local rental" : "12 Hrs & 120 Kms local rental");
							$rentalType		 = ($key == 9) ? "4" : (($key == 10) ? "8" : "12");
							?>
							<section>


								<div class="accordion-style2">
									<h4><?= $rentalPackage; ?></h4>
									<div class="table-view flex">
										<div class="table-view-left">Compact</div> 
										<div class="table-view-right"><?php echo '&#x20B9;' . $dayRentalprice[$key][1] . " + tax"; ?></div>
									</div>
									<div class="table-view flex">
										<div class="table-view-left">Sedan</div> 
										<div class="table-view-right"><?php echo '&#x20B9;' . $dayRentalprice[$key][3] . " + tax"; ?></div>
									</div>
									<div class="table-view flex">
										<div class="table-view-left">SUV</div> 
										<div class="table-view-right"><?php echo '&#x20B9;' . $dayRentalprice[$key][2] . " + tax"; ?></div>
									</div>
									<div class="table-view flex">
										<div class="table-view-left">&nbsp;</div> 
										<div class="table-view-right"><div class="btn-book text-right pt10"><a href="/bknw/<?= $dailyrental . $rentalType ?>/<?= strtolower($cmodel->cty_alias_path) ?>">Book</a></div> </div>
									</div>
								</div>
							</section>
		<?php $c++;
	} ?>
					</div>-->
					
				</div>
                	<div class="content" itemscope="" itemtype="https://schema.org/FAQPage">
						<p class="mt20">
								If you are looking for a reliable, convenient, and affordable cab service from <?= $cmodel->cty_name ?>, look no further 
								than Gozo cabs. Gozo cabs is the best <a href="/app">cab booking app</a> that offers you a wide range of options to choose from, such as compact, sedans, SUVs, and tempo travellers. 
								You can book a taxi service near you in just a few clicks and enjoy a hassle-free ride with professional drivers, 
								and <a href="http://www.gozocabs.com/blog/billing-transparency/">transparent pricing</a>. 
								Whether you need a cab for a local trip, an outstation journey, or an airport transfer, Gozo cabs has you covered. 
								Gozo cabs is the best cab booking app for cheap and reliable taxi booking. You can also save money by availing of the various discounts and offers that Gozocabs provides to its customers.
							</p>
							<section>
									<h4 class="mb0 mt20">FAQs About <?= $cmodel->cty_name ?> Cabs</h4>
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
								<section>
						<h2 class="top-16 font-16">Best time for renting a car in <?= $cmodel->cty_name; ?></h2>
	<?= $cmodel->cty_name; ?> enjoys a maritime climate throughout the year and to be very realistic you can visit the city in any time of the year. However, the months between September and February see the highest tourist inflow to the city because the weather becomes even more pleasant during the Winter season. The winter months in <?= $cmodel->cty_name; ?> are the best time to visit the city.
							
				</section>
				<section class="mt20">
						<h2 class="font-16">Things to look at while booking an outstation cab to and from <?= $cmodel->cty_name; ?></h2>
								<ol class="pl15 mb0">
									<li class="mb10">Ensure that a commercial taxi is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>
									<li class="mb10">Find if the route you will travel on has tolls. If yes, are tolls included in your booking quote</li>
									<li class="mb10">When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same</li>
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
					<div class="mt20">
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="50px" class="lozad" src="/images/cabs/tempo_9_seater.jpg" alt=""></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/tempo-traveller-rental/" . strtolower($top['from_city_alias_path'])); ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
							</div>
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/outstation-cabs/" . strtolower($top['from_city_alias_path'])); ?>">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
							</div>
							<?php
							$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
							if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_name)), $selected_cities))
							{
								?>
								<div class="main_time text-center">
									<div class="car_box2">
										<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
									</div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/Luxury-car-rental/" . strtolower($top['from_city_alias_path'])); ?>">Luxury car rental in <?= $cmodel->cty_name; ?></a>
								</div>
								<?php
							}
							?>
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
								<div class="main_time border-blueline text-center">
									<div class="car_box2"><amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/airport-transfer/" . strtolower($top['from_city_alias_path'])); ?>">Airport transfer in <?= $cmodel->cty_name; ?></a>
								</div>
		<?php
	}
	?>
						</div>
				</section>
				<section style="display: inline-block;">
					<div class="mt20">
						
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
							<h4>Hire Airport taxi with driver in <?= $cmodel->cty_name; ?> with meet and greet services</h4>
							Car rentals are available for outstation travel and airport pickup or drop at <?= $cmodel->cty_name; ?> Airport.  Many business and international  travellers use our chauffeur driven airport pickup and drop taxi services. These airport taxi transfers can be arranged with meet and greet services enabling smooth transportation to or from <?= $cmodel->cty_name; ?> airport to your office, hotel or address of choice. Typical airport transfer trips are between Bangalore city center and Bangalore airport. We also serve transportation from <?= $cmodel->cty_name; ?> Airport to cities nearby. If you have a special requirement, simply ask our customer service team who will do their best to support your needs.
	<?php }
	?>
						<div class="mt20">
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="50px" class="lozad" src="/images/cabs/tempo_9_seater.jpg" alt=""></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/tempo-traveller-rental/" . strtolower($top['from_city_alias_path'])); ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
							</div>
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/outstation-cabs/" . strtolower($top['from_city_alias_path'])); ?>">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
							</div>
							<?php
							$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
							if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_name)), $selected_cities))
							{
								?>
								<div class="main_time text-center">
									<div class="car_box2">
										<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
									</div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/Luxury-car-rental/" . strtolower($top['from_city_alias_path'])); ?>">Luxury car rental in <?= $cmodel->cty_name; ?></a>
								</div>
								<?php
							}
							?>
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
								<div class="main_time border-blueline text-center">
									<div class="car_box2"><amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/airport-transfer/" . strtolower($top['from_city_alias_path'])); ?>">Airport transfer in <?= $cmodel->cty_name; ?></a>
								</div>
		<?php
	}
	?>
						</div>
					</div>
					</section>
			<section class="mt20" style="display: inline-block;">		
	<?php
	if ($cmodel->cty_is_airport > 0)
	{
		?>
						<h3>Hire Airport taxi in <?= $cmodel->cty_name; ?> with meet and greet services</h3>
						<p>Car rentals are available for outstation travel and airport transfers from <?= $cmodel->cty_name; ?>. 
							The <?= $cmodel->cty_name; ?> Airport is also known as <?= $cmodel->cty_name; ?>. Many business and international  travellers use our chauffeur driven airport pick and drop services. 
							These airport transfers can be arranged with meet-and-greet services enabling smooth transportation to or from Bangalore airport to your office, hotel or address of choice. 
							Typical airport transfer trips are between Bangalore city center and Bangalore airport. We also serve transportation from Bangalore Airport to cities nearby. 
							If you have a special requirement, simply ask our customer service team who will do their best to support your needs.</p>
						<?php
					}
					if ($cmodel->cty_city_desc != "")
					{
						?>
						<div></div> 
						<h3 class="mb0">Little about <?= $cmodel->cty_name; ?> </h3>
						<p><?= $cmodel->cty_city_desc; ?></p>
						<?php
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

				<div class="">
					<div class="inner-tab">
							<h2 class="font16 mt30">Outstation Car rental fares for popular places to visit around <?= $cmodel->cty_name ?></h2>
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
									
								
                                    
									<div class="card-view">
													<div class="title-panel mb5"><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Cab</div>
													<div class="card-view-left">
														<p class="mb5"><?= $top['rut_distance']; ?> kms | <?= floor($top['rut_time'] / 60) . " hours"; ?></p>
														<p class="mt20 mb0 weight500 color-orange"><?= $top['rut_distance']; ?> kms included</p>
														<p class="font12 weight400 color-gray">Charges after <?= $top['rut_distance'] ?> Km @ ₹<?= $top['extraKmRate'] ?>/km</p>
													</div>
													<div class="card-view-right mb20">
														
														<span class="card-text2">	<?= ($top['seadan_price'] > 0) ? '<b>' . Filter::moneyFormatter($top['seadan_price']) . '</b>' : '<a href='.Yii::app()->createUrl("scq/form").'><amp-img src="/images/img-2022/bxs-phone-call.svg" alt="Call" width="20" height="20" class="preload-image" style="display:inline"></amp-img></a>'; ?></span>
														<p class="">Onwards</p>

														<div class="btn-book mt10"><a href="<?= $this->getOneWayUrlFromPath($top['from_city_alias_path'], $top['to_city_alias_path']) ?>" >Book</a></div>
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


					<!--------------------------------------->
					<div class="content" itemscope="" itemtype="https://schema.org/FAQPage">
						<p>
								If you are looking for a reliable, convenient, and affordable cab service from <?= $cmodel->cty_name ?>, look no further 
								than Gozo cabs. Gozo cabs is the best <a href="/app">cab booking app</a> that offers you a wide range of options to choose from, such as compact, sedans, SUVs, and tempo travellers. 
								You can book a taxi service near you in just a few clicks and enjoy a hassle-free ride with professional drivers, 
								and <a href="http://www.gozocabs.com/blog/billing-transparency/">transparent pricing</a>. 
								Whether you need a cab for a local trip, an outstation journey, or an airport transfer, Gozo cabs has you covered. 
								Gozo cabs is the best cab booking app for cheap and reliable taxi booking. You can also save money by availing of the various discounts and offers that Gozocabs provides to its customers.
							</p>
							<section>
									<h4 class="mb0 mt30">FAQs About <?= $cmodel->cty_name ?> Cabs</h4>
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
								<section>
						<h2 class="top-16 font-16">Best time for renting a car in <?= $cmodel->cty_name; ?></h2>
	<?= $cmodel->cty_name; ?> enjoys a maritime climate throughout the year and to be very realistic you can visit the city in any time of the year. However, the months between September and February see the highest tourist inflow to the city because the weather becomes even more pleasant during the Winter season. The winter months in <?= $cmodel->cty_name; ?> are the best time to visit the city.
							
				</section>
				<section class="mt20">
						<h2 class="font-16">Things to look at while booking an outstation cab to and from <?= $cmodel->cty_name; ?></h2>
								<ol class="pl15 mb0">
									<li class="mb10">Ensure that a commercial taxi is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>
									<li class="mb10">Find if the route you will travel on has tolls. If yes, are tolls included in your booking quote</li>
									<li class="mb10">When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same</li>
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
					<div class="mt20">
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="50px" class="lozad" src="/images/cabs/tempo_9_seater.jpg" alt=""></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/tempo-traveller-rental/" . strtolower($top['from_city_alias_path'])); ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
							</div>
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/outstation-cabs/" . strtolower($top['from_city_alias_path'])); ?>">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
							</div>
							<?php
							$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
							if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_name)), $selected_cities))
							{
								?>
								<div class="main_time text-center">
									<div class="car_box2">
										<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
									</div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/Luxury-car-rental/" . strtolower($top['from_city_alias_path'])); ?>">Luxury car rental in <?= $cmodel->cty_name; ?></a>
								</div>
								<?php
							}
							?>
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
								<div class="main_time border-blueline text-center">
									<div class="car_box2"><amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/airport-transfer/" . strtolower($top['from_city_alias_path'])); ?>">Airport transfer in <?= $cmodel->cty_name; ?></a>
								</div>
		<?php
	}
	?>
						</div>
				</section>

							
					</div>
					
					<section style="display: inline-block;">
					<div class="mt20">
						
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
							<h4>Hire Airport taxi with driver in <?= $cmodel->cty_name; ?> with meet and greet services</h4>
							Car rentals are available for outstation travel and airport pickup or drop at <?= $cmodel->cty_name; ?> Airport.  Many business and international  travellers use our chauffeur driven airport pickup and drop taxi services. These airport taxi transfers can be arranged with meet and greet services enabling smooth transportation to or from <?= $cmodel->cty_name; ?> airport to your office, hotel or address of choice. Typical airport transfer trips are between Bangalore city center and Bangalore airport. We also serve transportation from <?= $cmodel->cty_name; ?> Airport to cities nearby. If you have a special requirement, simply ask our customer service team who will do their best to support your needs.
	<?php }
	?>
						<div class="mt20">
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="50px" class="lozad" src="/images/cabs/tempo_9_seater.jpg" alt=""></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/tempo-traveller-rental/" . strtolower($top['from_city_alias_path'])); ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
							</div>
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/outstation-cabs/" . strtolower($top['from_city_alias_path'])); ?>">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
							</div>
							<?php
							$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
							if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_name)), $selected_cities))
							{
								?>
								<div class="main_time text-center">
									<div class="car_box2">
										<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
									</div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/Luxury-car-rental/" . strtolower($top['from_city_alias_path'])); ?>">Luxury car rental in <?= $cmodel->cty_name; ?></a>
								</div>
								<?php
							}
							?>
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
								<div class="main_time border-blueline text-center">
									<div class="car_box2"><amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/airport-transfer/" . strtolower($top['from_city_alias_path'])); ?>">Airport transfer in <?= $cmodel->cty_name; ?></a>
								</div>
		<?php
	}
	?>
						</div>
					</div>
					</section>

					
	<?php
	if ($cmodel->cty_is_airport > 0)
	{
		?>
<section style="display: inline-block;">	
						<h3>Hire Airport taxi in <?= $cmodel->cty_name; ?> with meet and greet services</h3>
						<p>Car rentals are available for outstation travel and airport transfers from <?= $cmodel->cty_name; ?>. 
							The <?= $cmodel->cty_name; ?> Airport is also known as <?= $cmodel->cty_name; ?>. Many business and international  travellers use our chauffeur driven airport pick and drop services. 
							These airport transfers can be arranged with meet-and-greet services enabling smooth transportation to or from Bangalore airport to your office, hotel or address of choice. 
							Typical airport transfer trips are between Bangalore city center and Bangalore airport. We also serve transportation from Bangalore Airport to cities nearby. 
							If you have a special requirement, simply ask our customer service team who will do their best to support your needs.</p>
</section>
						<?php
					}
					if ($cmodel->cty_city_desc != "")
					{
						?>
						<section class="mt20" style="display: inline-block;">	
						<h3 class="mb0">Little about <?= $cmodel->cty_name; ?> </h3>
						<p><?= $cmodel->cty_city_desc; ?></p>
						<?php
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
			</div>
			<?php
           
            if($airport!='')
            {
            ?>

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
                	<div class="content" itemscope="" itemtype="https://schema.org/FAQPage">
						<p>
								If you are looking for a reliable, convenient, and affordable cab service from <?= $cmodel->cty_name ?>, look no further 
								than Gozo cabs. Gozo cabs is the best <a href="/app">cab booking app</a> that offers you a wide range of options to choose from, such as compact, sedans, SUVs, and tempo travellers. 
								You can book a taxi service near you in just a few clicks and enjoy a hassle-free ride with professional drivers, 
								and <a href="http://www.gozocabs.com/blog/billing-transparency/">transparent pricing</a>. 
								Whether you need a cab for a local trip, an outstation journey, or an airport transfer, Gozo cabs has you covered. 
								Gozo cabs is the best cab booking app for cheap and reliable taxi booking. You can also save money by availing of the various discounts and offers that Gozocabs provides to its customers.
							</p>
                            <section>
                                    <h4 class="mb0 mt30">FAQs About <?= $cmodel->cty_name ?> Cabs</h4>
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
								<section>
						<h2 class="top-16 font-16">Best time for renting a car in <?= $cmodel->cty_name; ?></h2>
	<?= $cmodel->cty_name; ?> enjoys a maritime climate throughout the year and to be very realistic you can visit the city in any time of the year. However, the months between September and February see the highest tourist inflow to the city because the weather becomes even more pleasant during the Winter season. The winter months in <?= $cmodel->cty_name; ?> are the best time to visit the city.
							
				</section>
				<section class="mt20">
						<h2 class="font-16">Things to look at while booking an outstation cab to and from <?= $cmodel->cty_name; ?></h2>
								<ol class="pl15 mb0">
									<li class="mb10">Ensure that a commercial taxi is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>
									<li class="mb10">Find if the route you will travel on has tolls. If yes, are tolls included in your booking quote</li>
									<li class="mb10">When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same</li>
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
					<div class="mt20">
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="50px" class="lozad" src="/images/cabs/tempo_9_seater.jpg" alt="img"></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/tempo-traveller-rental/" . strtolower($top['from_city_alias_path'])); ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
							</div>
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt="img"></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/outstation-cabs/" . strtolower($top['from_city_alias_path'])); ?>">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
							</div>
							<?php
							$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
							if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_name)), $selected_cities))
							{
								?>
								<div class="main_time text-center">
									<div class="car_box2">
										<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt="img"></amp-img>
									</div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/Luxury-car-rental/" . strtolower($top['from_city_alias_path'])); ?>">Luxury car rental in <?= $cmodel->cty_name; ?></a>
								</div>
								<?php
							}
							?>
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
								<div class="main_time border-blueline text-center">
									<div class="car_box2"><amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/airport-transfer/" . strtolower($top['from_city_alias_path'])); ?>">Airport transfer in <?= $cmodel->cty_name; ?></a>
								</div>
		<?php
	}
	?>
						</div>
				</section>

							
					</div>
					<section style="display: inline-block;">
					<div class="mt20">
						
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
							<h4>Hire Airport taxi with driver in <?= $cmodel->cty_name; ?> with meet and greet services</h4>
							Car rentals are available for outstation travel and airport pickup or drop at <?= $cmodel->cty_name; ?> Airport.  Many business and international  travellers use our chauffeur driven airport pickup and drop taxi services. These airport taxi transfers can be arranged with meet and greet services enabling smooth transportation to or from <?= $cmodel->cty_name; ?> airport to your office, hotel or address of choice. Typical airport transfer trips are between Bangalore city center and Bangalore airport. We also serve transportation from <?= $cmodel->cty_name; ?> Airport to cities nearby. If you have a special requirement, simply ask our customer service team who will do their best to support your needs.
	<?php }
	?>
						<div class="mt20">
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="50px" class="lozad" src="/images/cabs/tempo_9_seater.jpg" alt="img"></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/tempo-traveller-rental/" . strtolower($top['from_city_alias_path'])); ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
							</div>
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt="img"></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/outstation-cabs/" . strtolower($top['from_city_alias_path'])); ?>">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
							</div>
							<?php
							$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
							if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_name)), $selected_cities))
							{
								?>
								<div class="main_time text-center">
									<div class="car_box2">
										<amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt="img"></amp-img>
									</div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/Luxury-car-rental/" . strtolower($top['from_city_alias_path'])); ?>">Luxury car rental in <?= $cmodel->cty_name; ?></a>
								</div>
								<?php
							}
							?>
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
								<div class="main_time border-blueline text-center">
									<div class="car_box2"><amp-img width="110px" height="58px" class="lozad" src="/images/cabs/car-etios.jpg" alt="img"></amp-img></div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/airport-transfer/" . strtolower($top['from_city_alias_path'])); ?>">Airport transfer in <?= $cmodel->cty_name; ?></a>
								</div>
		<?php
	}
	?>
						</div>
					</div>
					</section>
					
	<?php
	if ($cmodel->cty_is_airport > 0)
	{
		?>
<section style="display: inline-block;">	
						<h3>Hire Airport taxi in <?= $cmodel->cty_name; ?> with meet and greet services</h3>
						<p>Car rentals are available for outstation travel and airport transfers from <?= $cmodel->cty_name; ?>. 
							The <?= $cmodel->cty_name; ?> Airport is also known as <?= $cmodel->cty_name; ?>. Many business and international  travellers use our chauffeur driven airport pick and drop services. 
							These airport transfers can be arranged with meet-and-greet services enabling smooth transportation to or from Bangalore airport to your office, hotel or address of choice. 
							Typical airport transfer trips are between Bangalore city center and Bangalore airport. We also serve transportation from Bangalore Airport to cities nearby. 
							If you have a special requirement, simply ask our customer service team who will do their best to support your needs.</p>
</section>
						<?php
					}
					if ($cmodel->cty_city_desc != "")
					{
						?>
						<section class="mt20" style="display: inline-block;">	
						<h3 class="mb0">Little about <?= $cmodel->cty_name; ?> </h3>
						<p><?= $cmodel->cty_city_desc; ?></p>
						<?php
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
            <? }?>
		</amp-selector>
	</section>
	<?php
}
?>
<?php
$topCities = [];
$arrFCityData = Route::getCitiesForUrl();

$topRoutes = Route::getTopRouteByType(1, $arrFCityData);
if(count($arrFCityData) <= 0)
{
	$topCities = Route::getTopRouteByType(2);
}

#$topAirportTransfer	 = Route::getTopRouteByType(3);

//echo "<pre>";
//print_r($arrFCityData);
//print_r($topRoutes);
//print_r($topCities);
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


