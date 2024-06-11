<style>
	body{ color:#475F7B;}
	h2, h3{ font-weight: 700; color: #475F7B;}
	.table th, .table td{ font-family: 'Roboto'}
	.ui-page ul{ list-style-type: none; padding-left: 0;}
	.ui-page ul li{ list-style-type: none;}
</style>
<?php
if (isset($cityJsonProductSchema) && trim($cityJsonProductSchema) != '') {
	?>
	<script type="application/ld+json">
	<?php echo $cityJsonProductSchema;
	?>
	</script>
<?php } ?>
<?php
if (isset($cityJsonMarkupData) && trim($cityJsonMarkupData) != '') {
	?>
	<script type="application/ld+json">
	<?php echo $cityJsonMarkupData;
	?>
	</script>
<?php } ?>
<?php
if (isset($cityBreadMarkupData) && trim($cityBreadMarkupData) != '') {
	?>
	<script type="application/ld+json">
	<?php
	echo $cityBreadMarkupData;
	?>
	</script>
<?php } ?>
<?php
//if (isset($jsonproviderStructureMarkupData) && trim($jsonproviderStructureMarkupData) != '') {
?>
<!--    <script type="application/ld+json">
<?php
// echo $jsonproviderStructureMarkupData;
?>
</script>-->
<?php // } ?>

<?
$this->newHome = true;
/* @var $cmodel Cities */
?>
<div class="row">
<?= $this->renderPartial('application.themes.desktop.v2.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
	<?= $this->renderPartial('application.themes.desktop.v2.views.booking.fblikeview') ?>
<!--<div class="row gray-bg-new">
    <div class="col-lg-10 col-sm-10 col-md-8 text-center flash_banner float-none marginauto ml50 border bg-white">
        <span class="h3 mt0 mb5 flash_red text-warning">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Taxi and Car rentals all over India – Gozo’s online cab booking service</span><br>
        Gozocabs is your one-stop shop for hiring chauffeur driven taxi in India for inter-city, airport transfers and even local daily tours, specializing in one-way taxi trips all over India at transparent fares. Gozo’s coverage extends across <b>2,000</b> locations in India, with over <b>20,000</b> vehicles, more than <b>75,000</b> satisfied customers and above <b>10</b> Million kms driven in a year alone. Book by web, phone or app 24x7! 
    </div>
</div>-->

<div class="container mt30">
    <div class="row flash_banner hide" style="background: #ffc864;">
        <div class="col-lg-12 p0 hidden-sm hidden-xs text-center">     
            <figure><img src="/images/flash_lg1.jpg?v=1.1" alt="Flash Sale"></figure>		
        </div>
        <div class="col-sm-12 p0 hidden-lg hidden-md hidden-xs text-center">      
            <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>		
        </div>
        <div class="col-12 p0 hidden-lg hidden-md hidden-sm text-center">
<? /* /?><a target="_blank" href="https://twitter.com/gozocabs"><?/ */ ?>
            <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>
			<? /* /?></a><?/ */ ?>
        </div>
    </div>

<?php
if ($type == 'city') {
	$cities = ($count['countCities'] > 500) ? 500 : $count['countCities'];
	$routes = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
	$topCitiesByKm = '';
	$ctr = 1;
	foreach ($topCitiesKm as $top) {
		$topCitiesByKm .= '<a href="/car-rental/' . strtolower($top['cty_alias_path']) . '" style=" text-decoration: none; color: #282828;" target="_blank">' . $top['city'] . '</a>';
		$topCitiesByKm .= (count($topCitiesKm) == $ctr) ? " " : ", ";
		$ctr++;
	}

	for ($i = 1; $i <= 5; $i++) {
		$city_arr [] = $topCitiesByRegion[$i]['cty_alias_path'];
	}

	$nearby_city = implode(",", $city_arr);

	$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';

	//check fleaxi available or not

	$flexi_count = 0;

	foreach ($topTenRoutes as $flex_check) {
		if ($flex_check[flexi_price] != 0) {
			$flexi_count = $flexi_count + 1;
		}
	}

	$cmodel->cty_alias_path
	?>
		<div id="section2" itemscope="" itemtype="https://schema.org/FAQPage" >
			<div class="row">
				<div class="col-12">
					<h1 class="font-24 mb0">Book Sanitized & Disinfected car rental in <?php echo $cmodel->cty_name; ?> with driver for local and outstation trips with Gozo</h1>
					<p>Book sanitized & disinfected car rental in the city with a driver for safe travel locally IN-THE-CITY or on outstation trips India wide with Gozocabs.</p>
					<p>Gozo Cabs is taking precautionary measures during the pandemic to ensure you have the safest journey by disinfecting and sanitizing the cars before and after every ride.</p>

					<p>Book Local Hourly car rental for IN-THE-CITY ride and outstation cab service for intercity travel at an affordable price with Gozocabs.</p>
					<p>Check our hourly rental cab and outstation cab fares below. Our fares update dynamically in response to market demand and supply conditions so booking in advance is always best.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 class="font-16 mb0" itemprop="name">COVID-19 Pandemic Update for Gozo Cabs travel</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">
									<p>Gozo is known for its always on-time, guaranteed cab service across India. After the Corona Virus
										pandemic of 2020, We have instituted a process to ensure clean and sanitized conditions in our cabs.
										Starting April 2020, Gozo drivers are now disinfecting and sanitizing the Gozo cabs after arriving at your
										place for pickup. Our goal is to give you peace of mind and have you be sure that the cab has been
										cleaned to your satisfaction. Safety of our drivers & customers is of utmost importance to us. Our driver
										will practice safety measures and we request and require that our passengers do so too!</p>

									<p>Exchanging currency notes is not a good idea during the pandemic. So we require that you plan on
										paying for your cab fare in full online. You can make a part payment before your trip starts and the
										remainder of the payment will also need to be paid by you online</p>
								</div>
							</div>
						</div>
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 class="font-16 mb0" itemprop="name">Hourly car rental fares for local trips with GozoCabs Day Rental!</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">
									<p>Hey <?= $cmodel->cty_name ?>! Now You can now request for GozoCabs at unbelievably attractive prices for local rentals and outstation cab services.
										With cab fares starting from ₹<?= $dayRentalprice[9][1]; ?> (includes 4 hr & 40 kms) for local day rentals.</p>

									<p>By booking Gozocabs for local rentals you have a cab & driver for a fixed number of hours and take as many stops as we drive you around the city as you like during the time of your booking 
										Whether you want to go for  shopping, for back to back meetings, weddings or sightseeing, GozoCabs is at your disposal, waiting for you, just like your own car.</p>
									<p>And the best part – you have the option to choose the package that you like. Our local rental prices are</p>


									<div class="table-responsive">
										<table class="table table-striped table-bordered text-center">
											<thead>
												<tr>
													<th scope="col" class='col-xs-1'><b>Package Details</b></th>
													<th scope="col" class='col-xs-1'><b>Compact </b></th>
													<th scope="col" class='col-xs-1'><b>Sedan </b></th>
													<th scope="col" class='col-xs-1'><b>SUV  </b></th>
												</tr></thead><tbody>
	<?php
	foreach ($dayRentalprice as $key => $dayrental) {
		$rentalPackage = ($key == 9) ? "4 Hrs & 40 Kms local rental" : (($key == 10) ? "8 Hrs & 80 Kms local rental" : "12 Hrs & 120 Kms local rental");
		?>
													<tr>
														<td scope="row" align="center"><?php echo $rentalPackage; ?></td>
														<td align="center"><?php echo Filter::moneyFormatter($dayRentalprice[$key][1]) . " + tax"; ?></td>
														<td align="center"><?php echo Filter::moneyFormatter($dayRentalprice[$key][3]) . " + tax"; ?></td>
														<td align="center"><?php echo Filter::moneyFormatter($dayRentalprice[$key][2]) . " + tax"; ?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 class="font-16 mb0" itemprop="name">Why book a day rental with Gozo?</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">
									Get the same high quality and great prices that you have come to expect from Gozo. Now for local city rentals too.

									<ul class="pl15">
										<li class="mb10">
											<h3 class="font-14 mb0 inline-block">Cabs at your Disposal:</h3> 
											<p style="display: inline;">With GozoCabs Day Rentals you get cabs at your disposal for as long as you want and travel to multiple stop points with just one booking within city limits.</p></li>
										<li class="mb10">
											<h3 class="font-14 mb0 inline-block">Affordable Packages:</h3>
											<p style="display: inline;">Packages start for 4 hours and can extend up to 12 hours! Also, with some nominal additional charges cabs can be retained beyond package limits.</p></li>
										<li class="mb10">
											<h3 class="font-14 mb0 inline-block">Flexible Bookings:</h3>
											<p style="display: inline;">Easily plan a day out without having to worry about conveyance as with GozoCabs Day Rentals you can book a cab in advance and ride as per your convenience.</p></li>
										<li class="mb10">
											<h3 class="font-14 mb0 inline-block">Pay Cash or go Cashless:</h3>
											<p style="display: inline;">Now go cashless and travel easy. We have multiple payment option for your hassle-free transaction.</p></li>
										<li class="mb10">
											<h3 class="font-14 mb0 inline-block">No waiting or surge charges:</h3>
											<p style="display: inline;">Unlike point to point services you get a cab and driver at your disposal for the length of time of your rental. If you know you are going to have a busy day traveling around town, just book a car and driver for the day and go where you want in town</p></li>	
									</ul>
								</div>
							</div>
						</div>
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 class="font-16" itemprop="name" >Outstation Car rental fares for popular places to visit around <?= $cmodel->cty_name ?>
								<!-- Rating start here -->
	<?php
	if ($ratingCountArr['ratings'] > 0) {
		?>
									<a href="<?= Yii::app()->createUrl('city-rating/' . $cmodel->cty_name); ?>"><small title="<?php echo $ratingCountArr['ratings'] . ' rating from ' . $ratingCountArr['cnt'] . ' reviews'; ?>">
		<?php
		$strRating = '';

		$rating_star = floor($ratingCountArr['ratings']);
		if ($rating_star > 0) {
			$strRating .= '(';
			for ($s = 0; $s < $rating_star; $s++) {
				$strRating .= '<i class="fa fa-star orange-color"></i>';
			}
			if ($ratingCountArr['ratings'] > $rating_star) {
				$strRating .= '<i class="fa fa-star-half orange-color"></i> ';
			}
			$strRating .= ' ' . $ratingCountArr['cnt'] . ' reviews)';
		}
		echo $strRating;
		?>
										</small>
									</a>
										<?php } ?><!--fb like button-->
								<div class="fb-like pull-right mb30" data-href="https://facebook.com/gozocabs" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
								<!--fb like button--></h2>
							<!-- Rating start here -->
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">
									<div class="table-responsive">
										<table class="table table-striped table-bordered text-center">
											<thead>
												<tr>
													<th scope="col" class='col-xs-2'><b>Route (Starting at)</b></th>
	<?php
	if ($flexi_count > 0) {
		?>
														<th scope="col" class='col-2'><b>Shared Taxi</b></th>
		<?php
	}
	?>
													<th scope="col" class='col-xs-1'><b>Compact</b></th>
													<th scope="col" class='col-xs-1'><b>Sedan</b></th>
													<th scope="col" class='col-xs-1'><b>SUV</b></th>
													<th scope="col" class='col-xs-1'><b>Tempo traveler<br> (9 seater)</b></th>
													<th scope="col" class='col-xs-1'><b>Tempo traveler<br> (12 seater)</b></th>
													<th scope="col" class='col-xs-1'><b>Tempo traveler<br> (15 seater)</b></th>
												</tr></thead><tbody>
													<?php
													if (count($topTenRoutes) > 0) {
														foreach ($topTenRoutes as $top) {
															?>        
														<tr>
															<td scope="row"><a href="<?= Yii::app()->createAbsoluteUrl("/book-taxi/" . $top['rut_name']); ?>" target="_blank" class="color-black"><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</a></td>
															<?php
															if ($flexi_count > 0) {
																?>
																<td align="center"><?= ($top['flexi_price'] > 0) ? Filter::moneyFormatter($top['flexi_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
				<?php
			}
			?>
															<td align="center"><?= ($top['compact_price'] > 0) ? Filter::moneyFormatter($top['compact_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
															<td align="center"><?= ($top['seadan_price'] > 0) ? Filter::moneyFormatter($top['seadan_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
															<td align="center"><?= ($top['suv_price'] > 0) ? Filter::moneyFormatter($top['suv_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
															<td align="center"><?= ($top['tempo_9seater_price'] > 0) ? Filter::moneyFormatter($top['tempo_9seater_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
															<td align="center"><?= ($top['tempo_12seater_price'] > 0) ? Filter::moneyFormatter($top['tempo_12seater_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
															<td align="center"><?= ($top['tempo_15seater_price'] > 0) ? Filter::moneyFormatter($top['tempo_15seater_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
														</tr>
			<?php
		}
	} else {
		?>
													<tr><td align="center" colspan="7">No routes yet found.</td></tr>
														<?php
													}
													?>
											</tbody>    </table>
									</div>
									<p>Gozo's booking and billing process is <a href="http://www.aaocab.com/blog/billing-transparency/" class="color-black">completely transparent</a> and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.</p>
									<p>On the Gozo platform you can also create a multi-day tour package by customizing your itinerary. These are created a round trip bookings between 2 or more cities</p>
								</div>
							</div>
						</div>               
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 class="font-16 mb0" itemprop="name">Travel precautions being taken during the corona virus pandemic</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">
									<ol class="pl15">
										<li class="mb10">
											Health and safety of our employees, driver partners and customers is of utmost importance
											for travel in the presence of COVID-19 across the country</li>
										<li class="mb10">Before you book your travel – check government guidelines to make sure that your vehicle 
											can be routed from the source address to the destination address. Our teams will check for
											routing also after we receive your booking. In some cases, we may need to cancel your
											booking if the routing is not possible due to travel restrictions.
										</li>    
										<li class="mb10">Once you have booked a cab, we will provide you with the cab and driver information as soon
											as we can. You may use this information to get an electronic travel pass issued from the 
											authorities. In many parts of the country, it is required that a customer have a travel
											authorization (travel pass) before we can serve your trip.
										</li>       
										<li class="mb10">Gozo will provide you with a sanitized taxi cab for your travel. For your satisfaction and 
											peace, the driver will sanitize the vehicle in your presence after arriving for pickup. Our carse
											are disinfected at the start and end of every trip, however for your mental satisfaction its 
											important that our driver disinfects & sanitizes the car in your presence as well.
										</li>
										<li class="mb10">It is REQUIRED that both drivers and passengers have the Aarogya setu app installed on theirs
											phones. A Gozo driver may refuse to provide service if the customer cannot show proof that
											they have the Aarogya Setu app installed. Cost of cancellation for such reasons shall be
											borne by the traveller.
										</li>
										<li class="mb10">In light of these additional precautionary and sanitization measures, Gozo’s taxi cab rates
											may be slightly elevated than other taxi operators.
										</li>
										<li class="mb10">Our customer service centers are available to answer any questions for you. For quick and
											timely service, we recommend that you communicate to us by chat or email during this time.
										</li>
									</ol>
								</div>
							</div>
						</div>
					</section>
				</div>

				<div class="col-12">
					<h3 class="font-24">FAQs About <?= $cmodel->cty_name ?> Cabs</h3>
					<div class="pt10">
	<?php
	$faqArray = Faqs::getDetails(2);
	foreach ($faqArray as $key => $value) {
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
		<article>

			<section>
				<h2 class="font-16 mb0">Rent a Car in <?php echo $cmodel->cty_name; ?> for outstation travel, day-based rentals and airport transfers</h2>

				<p>Rent a Gozo cab with driver for local and outstation travel in <?= $cmodel->cty_name; ?>. Gozo provides taxis with driver on rent in <?php echo $cmodel->cty_name; ?>, including one way outstation taxi drops, outstation roundtrips and outstation shared taxi to nearby cities or day-based hourly rentals in or around <?php echo $cmodel->cty_name; ?>. You can also book cabs for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns. Car rental services in <?php echo $cmodel->cty_name; ?> are also available for flexible outstation packages that are billed by custom package tour itinerary or by day</p>
				<p>Gozo is India’s leader in outstation car rental. We provide economy, premium and luxury cab rental services for outstation and local travel over 3000 towns & cities and on over 50,000 routes all around India. 
					If you prefer to hire specific brands of cars like Innova or Swift Dzire then book our Assured Sedan or Assured SUV cab categories. You are guaranteed a Toyota Innova when you book an Assured SUV or guaranteed a Swift Dzire when you book an Assured Sedan. 
					With Gozo you can book a one way cab with driver from  <?= $cmodel->cty_name; ?> to <?= $nearby_city ?> and many more. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x365. Gozo partners with regional taxi operators in <?php echo $cmodel->cty_name; ?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We also provide local sightseeing trips and tours in or around the city. Our customers include domestic & international tourists, large event groups and business travellers who take rent cars for outstation trips and also for local taxi requirements in the <?= $cmodel->cty_name; ?> area.
				</p>

			</section>
			<section>
				<h2 class="font-16 mb0">Outstation shared taxi and shuttle services are also available in <?= $cmodel->cty_name ?></h2>

				<p>In September of 2018, Gozo has introduced the facility to hire a AC shared taxi by seat. We call this service Gozo SHARED. There are two types of services available. Gozo runs regular SHARED TAXI shuttle services on popular routes. Book a seat in our a shared taxi shuttle  at our book a Shared taxi Shuttle page.  Or you can book a seat in our Gozo FLEXXI AC outstation shared services.</p><p> With Gozo FLEXXI you are going to carpool with a person who has booked a full taxi and is willing to share his seats. Gozo FLEXXI is available in all major cities and on all popular outstation taxi routes across India. Gozo FLEXXI is much cheaper than traveling by an AC bus</p>
				<p>If you have firm plans and prefer to rent your own taxi, you can simply book a FLEXXI option and choose to sell your unused seats. Gozo cabs will help promote your unused seats to riders who are willing to travel in a shared taxi. </p>

			</section>
			<section>
	<?php
	if ($cmodel['cty_has_airport'] == 1) {
		?>
					<h2 class="font-16 mb0">Hire Airport taxi with driver in <?= $cmodel->cty_name; ?> with meet and greet services</h2>

					Car rentals are available for outstation travel and airport pickup or drop at <?= $cmodel->cty_alias_path; ?> Airport.  Many business and international  travellers use our chauffeur driven airport pickup and drop taxi services. These airport taxi transfers can be arranged with meet and greet services enabling smooth transportation to or from <?= $cmodel->cty_name; ?> airport to your office, hotel or address of choice. Typical airport transfer trips are between <?= $cmodel->cty_name; ?> city center and <?= $cmodel->cty_name; ?> airport. We also serve transportation from <?= $cmodel->cty_name; ?> Airport to cities nearby. If you have a special requirement, simply ask our customer service team who will do their best to support your needs.

	<?php }
	?>
			</section>
			<section>
				<div class="float-right mt30">
					<div class="main_time text-center">
						<div class="car_box2"><img src="/images/cabs/tempo_9_seater.jpg" width="130" height="73" alt="Tempo 9 Seater"></div>
						<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
					</div>
					<div class="main_time text-center">
						<div class="car_box2"><img src="/images/cabs/car-etios.jpg" width="130" height="73" alt="Etios"></div>
						<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
					</div>
				<?php
				$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
				if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)), $selected_cities)) {
					?>
						<div class="main_time text-center">
							<div class="car_box2"><img src="/images/cabs/car-etios.jpg" width="130" height="73" alt="Etios"></div>
							<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>">Luxury car rental in <?= $cmodel->cty_name; ?></a>
						</div>
					<?php
				}

				if ($cmodel['cty_has_airport'] == 1) {
					?>
						<div class="main_time text-center">
							<div class="car_box2"><img src="/images/cabs/car-etios.jpg" width="130" height="73" alt="Etios"></div>
							<a href="/airport-transfer/<?= strtolower($cmodel->cty_alias_path) ?>">Airport transfer in <?= $cmodel->cty_name; ?></a>
						</div>
		<?php
	}
	?>
				</div>
					<?php
					if ($cmodel->cty_city_desc != "") {
						?>

					<h2 class="font-16 mb0 mt30">Little about <?= $cmodel->cty_name; ?> </h2>

					<p><?= $cmodel->cty_city_desc; ?></p>

						<?php
					}
					?>
					<?php
					$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';
					if ($text1 != "") {
						?><p>If You are Foodie, <?=
				$text1;
			}
			?>:</p>
			</section>
			<section>
				<h2 class="font-16 mb0">Best time for renting a car in <?= $cmodel->cty_name; ?></h2>
				<p><?= $cmodel->cty_name; ?> enjoys a maritime climate throughout the year and to be very realistic you can visit the city in any time of the year. However, the months between September and February see the highest tourist inflow to the city because the weather becomes even more pleasant during the Winter season. The winter months in <?= $cmodel->cty_name; ?> are the best time to visit the city.</p>
			</section>
			<section>
				<h2 class="font-16 mb10">Things to look at while booking an outstation cab to and from  <?= $cmodel->cty_name; ?> </h2>

				<ol class="pl15">
					<li class="mb10">Ensure that a commercial taxi is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>
					<li class="mb10">Find if the route you will travel on has tolls. If yes, are tolls included in your booking quote</li>
					<li class="mb10">When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same</li>
					<li class="mb10">Price is great but service is what matters. So try to focus on service at a reasonable price. When you go for the cheapest, you will ending up getting what you pay for.<br><br></li>
				</ol>
			</section>
		</article>
	</div>
				<?php
			}
			?>


