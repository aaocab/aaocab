<?php
if (isset($cityJsonProductSchema) && trim($cityJsonProductSchema) != '') {
    ?>
    <script type="application/ld+json">
    <?php echo $cityJsonProductSchema;
    ?>
    </script>
<?php } ?>
<?php if (isset($cityJsonMarkupData) && trim($cityJsonMarkupData) != '')
{ ?>
	<script type="application/ld+json">
	<?php echo $cityJsonMarkupData;
	?>
	</script>
<?php } ?>
<?php if (isset($cityBreadMarkupData) && trim($cityBreadMarkupData) != '')
{ ?>
	<script type="application/ld+json">
	<?php
	echo $cityBreadMarkupData;
	?>
	</script>
<?php } ?>
<?php if (isset($jsonproviderStructureMarkupData) && trim($jsonproviderStructureMarkupData) != '')
{ ?>
	<script type="application/ld+json">
	<?php
	echo $jsonproviderStructureMarkupData;
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
	$cities			 = ($count['countCities'] > 500) ? 500 : $count['countCities'];
	$routes			 = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
	$topCitiesByKm	 = '';
	$ctr			 = 1;
	foreach ($topCitiesKm as $top)
	{
		$topCitiesByKm	 .= '<a href="/car-rental/' . strtolower($top['cty_alias_path']) . '" style=" text-decoration: none; color: #282828;">' . $top['city'] . '</a>';
		$topCitiesByKm	 .= (count($topCitiesKm) == $ctr) ? " " : ", ";
		$ctr++;
	}
	for ($i = 1; $i <= 5; $i++)
	{
		$city_arr [] = $topCitiesByRegion[$i]['cty_alias_path'];
	}

	$nearby_city = implode(" ,", $city_arr);
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
			<div id="tab1"
				 role="tab"
				 aria-controls="tabpanel1"
				 option
				 >Outstation Cab</div>
			<div id="tabpanel1"
				 role="tabpanel"
				 aria-labelledby="tab1">
				<div class="tab-sub-menu active">One Way</div>
				<div class="tab-sub-menu"><a href="/bknw/<?= $multi ?>/<?= strtolower($cmodel->cty_alias_path) ?>">Round Trip/Multi Way</a></div>
				<div class="wrraper">
					<amp-accordion class="sample accordion-style">
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
								$triptype	 = 1;
								$bookUrl	 = Yii::app()->createAbsoluteUrl('/amp/book-taxi/' . strtolower($top['from_city_alias_path']) . "-" . strtolower($top['to_city_alias_path']));
								?>
								<section <?= $class ?>>
									<h4><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</h4>
									<div class="accordion-style2">
			<?php
			if ($flexi_count > 0)
			{
				?>
											<div class="table-view flex">
												<div class="table-view-left">Shared Taxi</div> 
												<div class="table-view-right"><amp-img src="/images/rupees-amp3.png" alt="" width="9" height="12"></amp-img>511</div>
											</div>
				<?php
			}
			?>
										<div class="table-view flex">
											<div class="table-view-left">Compact</div> 
											<div class="table-view-right"><?= ($top['compact_price'] > 0) ? '<amp-img src="/images/rupees-amp3.png" alt="" width="9" height="12"></amp-img>' . $top['compact_price'] : '<a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20"></amp-img></a>'; ?></div>
										</div>
										<div class="table-view flex">
											<div class="table-view-left">Sedan</div> 
											<div class="table-view-right"><?= ($top['seadan_price'] > 0) ? '<amp-img src="/images/rupees-amp3.png" alt="" width="9" height="12"></amp-img>' . $top['seadan_price'] : '<a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20"></amp-img></a>'; ?></div>
										</div>
										<div class="table-view flex">
											<div class="table-view-left">SUV</div> 
											<div class="table-view-right"><?= ($top['suv_price'] > 0) ? '<amp-img src="/images/rupees-amp3.png" alt="" width="9" height="12"></amp-img>' . $top['suv_price'] : '<a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20"></amp-img></a>'; ?></div>
										</div>
										<div class="table-view flex">
											<div class="table-view-left">Tempo traveler(9 seater)</div> 
											<div class="table-view-right"><a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20"></amp-img></a> </div>
										</div>
										<div class="table-view flex">
											<div class="table-view-left">Tempo traveler(12 seater)</div> 
											<div class="table-view-right"><a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20"></amp-img></a> </div>
										</div>
										<div class="table-view flex">
											<div class="table-view-left">Tempo traveler(15 seater)</div> 
											<div class="table-view-right"><a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20"></amp-img></a> </div>
										</div>
										<div class="table-view flex">
											<div class="table-view-left">&nbsp;</div> 
											<div class="table-view-right"><div class="btn-book text-right pt10"><a href="<?= $bookUrl ?>">Book</a></div> </div>
										</div>
										<div class="table-view flex">
			<?php
			#$from_city_id = $top['from_city_id'];
			#$to_city_id = $top['to_city_id'];
			?>
											<!--<form method="post" action-xhr="/bknw" target="_top" class="sample-form i-amphtml-form" novalidate="">						
													<input type="hidden" name="BookingRoute[brt_from_city_id]" value="<?#=$from_city_id?>">
													<input type="hidden" name="BookingRoute[brt_to_city_id]" value="<?#=$to_city_id?>">
													<input type="hidden" name="BookingRoute[brt_pickup_date_date]" value="<?#=date("d/m/Y", strtotime("+2 day"))?>">
													<input type="hidden" name="BookingRoute[brt_pickup_date_time]" value="06:00 AM">
													<input type="hidden" name="BookingRoute[bkg_booking_type]" value="1">										
													<input type="hidden" name="step" value="1">
													<input type="submit"  value="BOOK" >											 																	
											</form>-->

										</div>

									</div>
								</section>
			<?php
		}
	}
	?>
					</amp-accordion>


					<!--------------------------------------->
					<div>aaocab's booking and billing process is <a href="http://www.aaocab.com/blog/billing-transparency/">completely transparent</a> and you will get all the terms and conditions detailed on your booking confirmation. <br>You get instant booking confirmations, electronic invoices and top quality for the best price.</div>	
					<div>On the aaocab platform you can also create a multi-day tour package by customizing your itinerary. These are created a round trip bookings between 2 or more cities<br></div>
					<div class="wrraper mt20">
						<h2 class="mt0">Rent a Car in <?= $cmodel->cty_name; ?> for outstation travel, day-based rentals and airport transfers</h2>
						<p>Rent a aaocab cab with driver for local and outstation travel in <?= $cmodel->cty_name; ?>. aaocab provides taxis with driver on rent in <?php echo $cmodel->cty_name; ?>, including one way outstation taxi drops, outstation roundtrips and outstation shared taxi to nearby cities or day-based hourly rentals in or around <?php echo $cmodel->cty_name; ?>. You can also book cabs for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns. Car rental services in <?php echo $cmodel->cty_name; ?> are also available for flexible outstation packages that are billed by custom package tour itinerary or by day</p>
						<p>aaocab is India’s leader in outstation car rental. We provide economy, premium and luxury cab rental services for outstation and local travel over 3000 towns & cities and on over 50,000 routes all around India. 
							If you prefer to hire specific brands of cars like Innova or Swift Dzire then book our Assured Sedan or Assured SUV cab categories. You are guaranteed a Toyota Innova when you book an Assured SUV or guaranteed a Swift Dzire when you book an Assured Sedan. 
							With aaocab you can book a one way cab with driver from  <?= $cmodel->cty_name; ?> to <?= $nearby_city ?> and many more. aaocab provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x365. aaocab partners with regional taxi operators in <?php echo $cmodel->cty_name; ?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We also provide local sightseeing trips and tours in or around the city. Our customers include domestic & international tourists, large event groups and business travellers who take rent cars for outstation trips and also for local taxi requirements in the <?php echo $cmodel->cty_name; ?> area.
						</p>
						<h2>Outstation shared taxi and shuttle services are also available in <?= $nearby_city ?></h2>
						<p>In September of 2018, aaocab has introduced the facility to hire a AC shared taxi by seat. We call this service aaocab SHARED. There are two types of services available. aaocab runs regular SHARED TAXI shuttle services on popular routes. Book a seat in our a shared taxi shuttle  at our book a Shared taxi Shuttle page.  Or you can book a seat in our aaocab FLEXXI AC outstation shared services. With aaocab FLEXXI you are going to carpool with a person who has booked a full taxi and is willing to share his seats. aaocab FLEXXI is available in all major cities and on all popular outstation taxi routes across India. aaocab FLEXXI is much cheaper than traveling by an AC bus</p>
						<p>If you have firm plans and prefer to rent your own taxi, you can simply book a FLEXXI option and choose to sell your unused seats. aaocab cabs will help promote your unused seats to riders who are willing to travel in a shared taxi. </p> 
	<?php
	if ($cmodel['cty_has_airport'] == 1)
	{
		?>
							<h4>Hire Airport taxi with driver in <?= $cmodel->cty_name; ?> with meet and greet services</h4>
							Car rentals are available for outstation travel and airport pickup or drop at <?= $cmodel->cty_name; ?> Airport.  Many business and international  travellers use our chauffeur driven airport pickup and drop taxi services. These airport taxi transfers can be arranged with meet and greet services enabling smooth transportation to or from <?= $cmodel->cty_name; ?> airport to your office, hotel or address of choice. Typical airport transfer trips are between Bangalore city center and Bangalore airport. We also serve transportation from <?= $cmodel->cty_name; ?> Airport to cities nearby. If you have a special requirement, simply ask our customer service team who will do their best to support your needs.
		<?php }
	?>
						<div class="wrraper mt20">
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="125px" height="63px" class="lozad" src="/images/cabs/tempo_9_seater.jpg" alt=""></amp-img>
								</div>
								<a href="<?= Yii::app()->createAbsoluteUrl("/amp/tempo-traveller-rental/" . strtolower($top['from_city_alias_path'])); ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
							</div>
							<div class="main_time text-center">
								<div class="car_box2">
									<amp-img width="130px" height="80px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
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
										<amp-img width="125px" height="63px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img>
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
									<div class="car_box2"><amp-img width="125px" height="63px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
									<a href="<?= Yii::app()->createAbsoluteUrl("/amp/airport-transfer/" . strtolower($top['from_city_alias_path'])); ?>">Airport transfer in <?= $cmodel->cty_name; ?></a>
								</div>
								<?php
							}
							?>
						</div>
						<div class="wrraper mt20">
							<p>aaocab is India's leader in outstation car rental. We provide economy, premium and luxury outstation taxi cab rental services in over 1000 towns &  <?= $cities; ?> cities and on over <?= number_format($routes, 0); ?> routes all around India.</p>
							<p>If you prefer to hire specific brands of cars like Innova or Swift Dzire then book our Assured Sedan or Assured SUV cab categories. You are guaranteed a Toyota Innova when you book a Assured SUV or guaranteed a Swift Dzire when you book a Assured Sedan.</p>
							<p>With aaocab you can <a href="/one-way-cabs">book a one way cab with driver</a> from <?= $cmodel->cty_name; ?> to <?= $topCitiesByKm; ?> and more. aaocab provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x365. aaocab partners with regional taxi operators in <?= $cmodel->cty_name; ?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We also provide local sightseeing trips and tours in or around the city. Our customers include domestic & international tourists, large event groups and business travellers who take rent cars for outstation trips and also for local taxi requirements in the <?= $cmodel->cty_name; ?> area.</p>
							<?php
							if ($cmodel['cty_has_airport'] == 1)
							{
								?>
								<h3 class="mb0"><?= $cmodel->cty_name ?> Airport Transfers </h3>
								<p>Airport transfers from <?= $cmodel->cty_name ?> to your hotel and to nearby cities like  <?= $topCitiesByKm; ?>
									are available. You can <a href="<?= Yii::app()->createAbsoluteUrl("/amp/airport-transfer/" . strtolower($top['from_city_alias_path'])); ?>">book your taxi for airport transfer to or from <?= $cmodel->cty_name ?> Airport.</a></p>
								<?php
							}
							if ($flexi_count > 0)
							{
								?>
								<p></p>
								<h3 class="mb0"> Outstation shared taxi services are also available in <?= $cmodel->cty_name; ?></h3>
								<p>	In September of 2018, aaocab has introduced the facility to hire a seat in a shared AC outstation taxi. We call this service aaocab FLEXXI.You can learn more about our <a href="http://www.aaocab.com/goFLEXXI" >aaocab FLEXXI AC outstation shared taxi.</a> aaocab FLEXXI is available in all major cities and on all popular oustation taxi routes across India. aaocab FLEXXI is much cheaper than traveling by an AC bus. If you have firm plans and prefer to rent your own taxi, you can simply book a FLEXXI option and choose to sell your unused seats. aaocab cabs will help promote your unused seats to riders who are willing to travel in a shared taxi. 
								</p>		
								<?php
							}
							?>
						</div>
					</div>
					<!--------------------------------------->
					<p>aaocab's booking process is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.</p>
					<p>On the aaocab platform you can also create a multi-day tour package by customizing your itinerary. These are created a round trip bookings between 2 or more cities.</p>
					<p>With aaocab you can book a taxi anywhere in India. Our services are top rated in almost all cities across India. You can book a car with aaocab in 
						<?php
						foreach ($topCitiesByRegion as $top)
						{
							?>
							<a href="/car-rental/<?php echo strtolower($top['cty_alias_path']); ?>" style=" text-decoration: none; color: #282828;"><? echo $top['city']; ?></a><?= (count($topCitiesByRegion) == $ctr) ? "." : ", "; ?>
							<?php
						}
						?>
					</p>
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
						<p>If You are Foodie, <?= $text; ?> <?php foreach ($place as $p)
				{
					echo $p;
				} ?></p> 
	<?php } ?>
					<div></div>
					<h3 class="mb0">Best time for renting a car in <?= $cmodel->cty_name; ?></h3>
					<p><?= $cmodel->cty_name; ?> enjoys a maritime climate throughout the year and to be very realistic you can visit the city in any time of the year. However, the months between September and February see the highest tourist inflow to the city because the weather becomes even more pleasant during the Winter season. The winter months in <?= $cmodel->cty_name; ?> are the best time to visit the city.</p>
					<h3>Things to look at while booking an outstation cab to and from <?= $cmodel->cty_name; ?></h3>
					<p>
					<ul>
						<li>Ensure that a commercial taxi is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>
						<li>Find if the route you will travel on has tolls. If yes, are tolls included in your booking quote.</li>
						<li>When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same.</li>
						<li>Price is great but service is what matters. So try to focus on service at a reasonable price. When you go for the cheapest, you will ending up getting what you pay for.</li>
					</ul>
					</p>
				</div>
			</div>
			<div id="tab2"
				 role="tab"
				 aria-controls="tabpanel2"
				 option selected>Local Cab</div>
			<div id="tabpanel2"
				 role="tabpanel"
				 aria-labelledby="tab2">
				<div class="tab-sub-menu active">Day Rental</div>
				<div class="tab-sub-menu"><a href="/bknw/airport/<?= $rmodel->rutFromCity->cty_name ?><?= strtolower($cmodel->cty_alias_path) ?>">Airport Transfer</a></div>
				<div class="wrraper">
					<amp-accordion class="sample accordion-style">

	<?php
	
	foreach ($dayRentalprice as $key => $dayrental)
	{
		$rentalPackage	 = ($key == 9) ? "4 Hrs & 40 Kms local rental" : (($key == 10) ? "8 Hrs & 80 Kms local rental" : "12 Hrs & 120 Kms local rental");
		$rentalType		 = ($key == 9) ? "4" : (($key == 10) ? "8" : "12");
		
		?>
							<section expanded>
								
								<h4><?= $rentalPackage; ?></h4>
								<div class="accordion-style2">
									<div class="table-view flex">
										<div class="table-view-left">Compact</div> 
										<div class="table-view-right"><?php echo '<amp-img src="/images/rupees-amp3.png" alt="" width="9" height="12"></amp-img>' . $dayRentalprice[$key][1] . " + tax"; ?></div>
									</div>
									<div class="table-view flex">
										<div class="table-view-left">Sedan</div> 
										<div class="table-view-right"><?php echo '<amp-img src="/images/rupees-amp3.png" alt="" width="9" height="12"></amp-img>' . $dayRentalprice[$key][3] . " + tax"; ?></div>
									</div>
									<div class="table-view flex">
										<div class="table-view-left">SUV</div> 
										<div class="table-view-right"><?php echo '<amp-img src="/images/rupees-amp3.png" alt="" width="9" height="12"></amp-img>' . $dayRentalprice[$key][2] . " + tax"; ?></div>
									</div>
									<div class="table-view flex">
										<div class="table-view-left">&nbsp;</div> 
										<div class="table-view-right"><div class="btn-book text-right pt10"><a href="/bknw/<?= $dailyrental . $rentalType ?>/<?= strtolower($cmodel->cty_alias_path) ?>">Book</a></div> </div>
									</div>
								</div>
							</section>
	<?php $c++; } ?>
					</amp-accordion>
					<h1>Hourly local car rental fares for Day Rental cabs in <?php echo $cmodel->cty_name; ?></h1>
					Hey <?= $cmodel->cty_name ?>! Now You can now request for aaocab at unbelievably attractive prices for local rentals and outstation cab services.
					With cab fares starting from ₹<?= $dayRentalprice[9][1]; ?> (includes 4 hr & 40 kms) for local day rentals.

					<br/>By booking aaocab for local rentals you have a cab & driver for a fixed number of hours and take as many stops as we drive you around the city as you like during the time of your booking 
					Whether you want to go for  shopping, for back to back meetings, weddings or sightseeing, aaocab is at your disposal, waiting for you, just like your own car.
					<br/>And the best part – you have the option to choose the package that you like. Our local rental prices are

					<h2>Why book a day rental cab in <?php echo $cmodel->cty_name; ?> with aaocab Cabs?</h2>
					Get the same high quality and great prices that you have come to expect from aaocab. Now for local city rentals too.<br/>

					<ul>
						<li><strong> Cabs at your Disposal:</strong> With aaocab Day Rentals you get cabs at your disposal for as long as you want and travel to multiple stop points with just one booking within city limits.<br/></li>
						<li><strong>Affordable Packages:</strong> Packages start for 4 hours and can extend up to 12 hours! Also, with some nominal additional charges cabs can be retained beyond package limits.<br/></li>
						<li><strong>Flexible Bookings:</strong>  Easily plan a day out without having to worry about conveyance as with aaocab Day Rentals you can book a cab in advance and ride as per your convenience.<br/></li>
						<li><strong>Pay Cash or go Cashless:</strong>  Now go cashless and travel easy. We have multiple payment option for your hassle-free transaction.<br/> </li>
						<li><strong>No waiting or surge charges:</strong> Unlike point to point services you get a cab and driver at your disposal for the length of time of your rental. If you know you are going to have a busy day traveling around town, just book a car and driver for the day and go where you want in town</li>	
					</ul>
				</div>
			</div>
		</amp-selector>
	</section>
	<?php
}
?>