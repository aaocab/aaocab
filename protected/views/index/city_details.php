<style type="text/css">
    .h3_36{ font-size: 36px !important; line-height: normal;}
    .h3_30{ font-size: 30px !important; line-height: normal;}
    .h3_18{ font-size: 18px !important; line-height: normal;}
	.link-panel{ text-align: center;}
	.link-panel a{ 
		display: inline-block; text-align: center;
		background: #ff6700; padding: 5px 10px; margin: 0 5px; color: #fff;
		-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;
		text-decoration: none;
	}
	.link-panel a:hover{ background: #152b57;}
	.car_box2 img{ width: 100%;}
	.main_time2{ min-height: 160px; line-height:18px; font-size:12px;}
	.main_time{ line-height: 16px!important; font-size: 11px;}
	.main_time a{ line-height: 16px!important; font-size: 11px;}
</style>
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

<?
$this->newHome = true;
/* @var $cmodel Cities */
?>
<div class="row">
<?= $this->renderPartial('application.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
<?= $this->renderPartial('application.views.booking.fblikeview') ?>
<div class="row gray-bg-new">
    <div class="col-lg-6 col-sm-10 col-md-8 text-center flash_banner float-none marginauto">
        <span class="h3 mt0 mb5 flash_red">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Taxi and Car rentals all over India – Gozo’s online cab booking service</span><br>
        aaocab is your one-stop shop for hiring chauffeur driven taxi in India for inter-city, airport transfers and even local daily tours, specializing in one-way taxi trips all over India at transparent fares. Gozo’s coverage extends across <b>2,000</b> locations in India, with over <b>20,000</b> vehicles, more than <b>75,000</b> satisfied customers and above <b>10</b> Million kms driven in a year alone. Book by web, phone or app 24x7! 
    </div>
</div>
<div class="row flash_banner hide" style="background: #ffc864;">
    <div class="col-lg-12 p0 hidden-sm hidden-xs text-center">     
        <figure><img src="/images/flash_lg1.jpg?v=1.1" alt="Flash Sale"></figure>		
    </div>
    <div class="col-sm-12 p0 hidden-lg hidden-md hidden-xs text-center">      
        <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>		
    </div>
    <div class="col-xs-12 p0 hidden-lg hidden-md hidden-sm text-center">
<? /* /?><a target="_blank" href="https://twitter.com/aaocab"><?/ */ ?>
        <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>
<? /* /?></a><?/ */ ?>
    </div>
</div>

<?php
if ($type == 'city')
{
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
	<section id="section2">
		<div class="row p20">
			<div class="col-xs-12 col-sm-10 col-md-10 float-none marginauto">
				<h1>Book Sanitized & Disinfected car rental in <?php echo $cmodel->cty_name; ?> with driver for local and outstation trips with Gozo</h1>
	            Book sanitized & disinfected car rental in the city with a driver for safe travel locally IN-THE-CITY or on
	            outstation trips India wide with aaocab. 
	            <br/>Gozo Cabs is taking precautionary measures during the pandemic to ensure you have the safest journey by disinfecting and sanitizing the cars before and after every ride.

	            <br/>Book Local Hourly car rental for IN-THE-CITY ride and outstation cab service for intercity travel at an
	            affordable price with aaocab. 
	            <br/>Check our hourly rental cab and outstation cab fares below. Our fares
	            update dynamically in response to market demand and supply conditions so booking in advance is
	            always best.

	            <h3>COVID-19 Pandemic Update for Gozo Cabs travel</h3>
	            Gozo is known for its always on-time, guaranteed cab service across India. After the Corona Virus
	            pandemic of 2020, We have instituted a process to ensure clean and sanitized conditions in our cabs.
	            Starting April 2020, Gozo drivers are now disinfecting and sanitizing the Gozo cabs after arriving at your
	            place for pickup. Our goal is to give you peace of mind and have you be sure that the cab has been
	            cleaned to your satisfaction. Safety of our drivers & customers is of utmost importance to us. Our driver
	            will practice safety measures and we request and require that our passengers do so too!

	            <br/>Exchanging currency notes is not a good idea during the pandemic. So we require that you plan on
	            paying for your cab fare in full online. You can make a part payment before your trip starts and the
	            remainder of the payment will also need to be paid by you online
				<h2>Hourly car rental fares for local trips with aaocab Day Rental!</h2>
				Hey <?= $cmodel->cty_name ?>! Now You can now request for aaocab at unbelievably attractive prices for local rentals and outstation cab services.
				With cab fares starting from ₹<?= $dayRentalprice[9][1]; ?> (includes 4 hr & 40 kms) for local day rentals.

				<br/>By booking aaocab for local rentals you have a cab & driver for a fixed number of hours and take as many stops as we drive you around the city as you like during the time of your booking 
				Whether you want to go for  shopping, for back to back meetings, weddings or sightseeing, aaocab is at your disposal, waiting for you, just like your own car.
				<br/>And the best part – you have the option to choose the package that you like. Our local rental prices are


				<div class="table-responsive">
					<table class="table table-striped table-bordered text-center">
						<tr>
							<td class='col-xs-1'><b>Package Details</b></td>
							<td class='col-xs-1'><b>Compact </b></td>
							<td class='col-xs-1'><b>Sedan </b></td>
							<td class='col-xs-1'><b>SUV  </b></td>
						</tr>
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

					</table>
				</div>

				<h3>Why book a day rental with Gozo?</h3>
				Get the same high quality and great prices that you have come to expect from Gozo. Now for local city rentals too.<br/>

				<ul>
					<li><strong> Cabs at your Disposal:</strong> With aaocab Day Rentals you get cabs at your disposal for as long as you want and travel to multiple stop points with just one booking within city limits.<br/></li>
					<li><strong>Affordable Packages:</strong> Packages start for 4 hours and can extend up to 12 hours! Also, with some nominal additional charges cabs can be retained beyond package limits.<br/></li>
					<li><strong>Flexible Bookings:</strong>  Easily plan a day out without having to worry about conveyance as with aaocab Day Rentals you can book a cab in advance and ride as per your convenience.<br/></li>
					<li><strong>Pay Cash or go Cashless:</strong>  Now go cashless and travel easy. We have multiple payment option for your hassle-free transaction.<br/> </li>
					<li><strong>No waiting or surge charges:</strong> Unlike point to point services you get a cab and driver at your disposal for the length of time of your rental. If you know you are going to have a busy day traveling around town, just book a car and driver for the day and go where you want in town</li>	
				</ul>  

				<h2>Outstation Car rental fares for popular places to visit around <?= $cmodel->cty_name ?>
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
					<!--fb like button--></h2>
				<!-- Rating start here -->
				<div class="table-responsive">
					<table class="table table-striped table-bordered text-center">
						<tr>
							<td class='col-xs-2'><b>Route (Starting at)</b></td>
							<?php
							if ($flexi_count > 0)
							{
								?>
								<td class='col-xs-2'><b>Shared Taxi</b><br/><small>(Economy)</small></td>
		<?php
	}
	?>
							<td class='col-xs-1'><b>Compact</b><br/><small>(Economy)</small></td>
							<td class='col-xs-1'><b>Sedan</b><br/><small>(Economy)</small></td>
							<td class='col-xs-1'><b>SUV</b><br/><small>(Economy)</small></td>
							<td class='col-xs-1'><b>Tempo traveler<br> (9 seater)</b><br/><small>(Economy)</small></td>
							<td class='col-xs-1'><b>Tempo traveler<br> (12 seater)</b><br/><small>(Economy)</small></td>
							<td class='col-xs-1'><b>Tempo traveler<br> (15 seater)</b><br/><small>(Economy)</small></td>
						</tr>
						<?php
//                        echo "<br>";
//                        echo "time_start->".$time_start = microtime(true);

						if (count($topTenRoutes) > 0)
						{
							foreach ($topTenRoutes as $top)
							{
								?>        
								<tr>
									<td><a href="<?= Yii::app()->createAbsoluteUrl("/book-taxi/" . strtolower($top['from_city_alias_path']) . '-' . strtolower($top['to_city_alias_path'])); ?>" target="_blank" ><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</a></td>
									<?php
									if ($flexi_count > 0)
									{
										?>
										<td align="center"><?= ($top['flexi_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['flexi_price'] : 'Call us'; ?></td>
				<?php
			}
			?>
									<td align="center"><?= ($top['compact_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['compact_price'] : 'Call us'; ?></td>
									<td align="center"><?= ($top['seadan_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['seadan_price'] : 'Call us'; ?></td>
									<td align="center"><?= ($top['suv_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['suv_price'] : 'Call us'; ?></td>
									<td align="center"><?= ($top['tempo_9seater_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['tempo_9seater_price'] : 'Call us'; ?></td>
									<td align="center"><?= ($top['tempo_12seater_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['tempo_12seater_price'] : 'Call us'; ?></td>
									<td align="center"><?= ($top['tempo_15seater_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['tempo_15seater_price'] : 'Call us'; ?></td>
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
					</table>
				</div>
				<div>Gozo's booking and billing process is <a href="http://www.aaocab.com/blog/billing-transparency/">completely transparent</a> and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.</div>
				<p>On the Gozo platform you can also create a multi-day tour package by customizing your itinerary. These are created a round trip bookings between 2 or more cities</p>
				<h3>TRAVEL PRECAUTIONS BEING TAKEN DURING THE CORONA VIRUS PANDEMIC</h3>
				<ol>
					<li>
						Health and safety of our employees, driver partners and customers is of utmost importance
						for travel in the presence of COVID-19 across the country</li>
					<li>Before you book your travel – check government guidelines to make sure that your vehicle 
						can be routed from the source address to the destination address. Our teams will check for
						routing also after we receive your booking. In some cases, we may need to cancel your
						booking if the routing is not possible due to travel restrictions.
					</li>    
					<li>Once you have booked a cab, we will provide you with the cab and driver information as soon
						as we can. You may use this information to get an electronic travel pass issued from the 
						authorities. In many parts of the country, it is required that a customer have a travel
						authorization (travel pass) before we can serve your trip.
					</li>       
					<li>Gozo will provide you with a sanitized taxi cab for your travel. For your satisfaction and 
						peace, the driver will sanitize the vehicle in your presence after arriving for pickup. Our carse
						are disinfected at the start and end of every trip, however for your mental satisfaction its 
						important that our driver disinfects & sanitizes the car in your presence as well.
					</li>
					<li>It is REQUIRED that both drivers and passengers have the Aarogya setu app installed on theirs
						phones. A Gozo driver may refuse to provide service if the customer cannot show proof that
						they have the Aarogya Setu app installed. Cost of cancellation for such reasons shall be
						borne by the traveller.
					</li>
					<li>In light of these additional precautionary and sanitization measures, Gozo’s taxi cab rates
						may be slightly elevated than other taxi operators.
					</li>
					<li>Our customer service centers are available to answer any questions for you. For quick and
						timely service, we recommend that you communicate to us by chat or email during this time.
					</li>
				</ol>
				<h4>Rent a Car in <?php echo $cmodel->cty_name; ?> for outstation travel, day-based rentals and airport transfers</h4>
				<p>Rent a Gozo cab with driver for local and outstation travel in <?= $cmodel->cty_name; ?>. Gozo provides taxis with driver on rent in <?php echo $cmodel->cty_name; ?>, including one way outstation taxi drops, outstation roundtrips and outstation shared taxi to nearby cities or day-based hourly rentals in or around <?php echo $cmodel->cty_name; ?>. You can also book cabs for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns. Car rental services in <?php echo $cmodel->cty_name; ?> are also available for flexible outstation packages that are billed by custom package tour itinerary or by day</p>
				<p>Gozo is India’s leader in outstation car rental. We provide economy, premium and luxury cab rental services for outstation and local travel over 3000 towns & cities and on over 50,000 routes all around India. 
					If you prefer to hire specific brands of cars like Innova or Swift Dzire then book our Assured Sedan or Assured SUV cab categories. You are guaranteed a Toyota Innova when you book an Assured SUV or guaranteed a Swift Dzire when you book an Assured Sedan. 
					With Gozo you can book a one way cab with driver from  <?= $cmodel->cty_name; ?> to <?= $nearby_city ?> and many more. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x365. Gozo partners with regional taxi operators in <?php echo $cmodel->cty_name; ?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We also provide local sightseeing trips and tours in or around the city. Our customers include domestic & international tourists, large event groups and business travellers who take rent cars for outstation trips and also for local taxi requirements in the <?= $cmodel->cty_name; ?> area.
				</p>

				<h4>Outstation shared taxi and shuttle services are also available in <?= $cmodel->cty_name ?></h4>
				<p>In September of 2018, Gozo has introduced the facility to hire a AC shared taxi by seat. We call this service Gozo SHARED. There are two types of services available. Gozo runs regular SHARED TAXI shuttle services on popular routes. Book a seat in our a shared taxi shuttle  at our book a Shared taxi Shuttle page.  Or you can book a seat in our Gozo FLEXXI AC outstation shared services.</p><p> With Gozo FLEXXI you are going to carpool with a person who has booked a full taxi and is willing to share his seats. Gozo FLEXXI is available in all major cities and on all popular outstation taxi routes across India. Gozo FLEXXI is much cheaper than traveling by an AC bus</p>
				<p>If you have firm plans and prefer to rent your own taxi, you can simply book a FLEXXI option and choose to sell your unused seats. Gozo cabs will help promote your unused seats to riders who are willing to travel in a shared taxi. </p> 
				<?php
				if ($cmodel['cty_has_airport'] == 1)
				{
					?>
					<h4>Hire Airport taxi with driver in <?= $cmodel->cty_name; ?> with meet and greet services</h4>
					Car rentals are available for outstation travel and airport pickup or drop at <?= $cmodel->cty_alias_path; ?> Airport.  Many business and international  travellers use our chauffeur driven airport pickup and drop taxi services. These airport taxi transfers can be arranged with meet and greet services enabling smooth transportation to or from <?= $cmodel->cty_name; ?> airport to your office, hotel or address of choice. Typical airport transfer trips are between <?= $cmodel->cty_name; ?> city center and <?= $cmodel->cty_name; ?> airport. We also serve transportation from <?= $cmodel->cty_name; ?> Airport to cities nearby. If you have a special requirement, simply ask our customer service team who will do their best to support your needs.

		<?php }
	?>
				<div class="col-xs-6 col-sm-3 col-md-2 mb20 pull-right">
					<div class="main_time border-blueline text-center">
						<div class="car_box2"><img src="/images/cabs/tempo_9_seater.jpg" alt=""></div>
						<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3 col-md-2 mb20 pull-right">
					<div class="main_time border-blueline text-center">
						<div class="car_box2"><img src="/images/cabs/car-etios.jpg" alt=""></div>
						<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>">Outstation taxi rental in <?= $cmodel->cty_name; ?></a>
					</div>
				</div>
				<?php
				$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur');
				if (in_array(strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)), $selected_cities))
				{
					?>
					<div class="col-xs-6 col-sm-3 col-md-2 mb20 pull-right">
						<div class="main_time border-blueline text-center">
							<div class="car_box2"><img src="/images/cabs/car-etios.jpg" alt=""></div>
							<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>">Luxury car rental in <?= $cmodel->cty_name; ?></a>
						</div>
					</div>
					<?php
				}

				if ($cmodel['cty_has_airport'] == 1)
				{
					?>
					<div class="col-xs-6 col-sm-3 col-md-2 mb20 pull-right">
						<div class="main_time border-blueline text-center">
							<div class="car_box2"><img src="/images/cabs/car-etios.jpg" alt=""></div>
							<a href="/airport-transfer/<?= strtolower($cmodel->cty_alias_path) ?>">Airport transfer in <?= $cmodel->cty_name; ?></a>
						</div>
					</div>
					<?php
				}
				?>
				<?php
				if ($cmodel->cty_city_desc != "")
				{
					?>

					<h3>Little about <?= $cmodel->cty_name; ?> </h3>
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


				<!--------------------------------->
				<h4>Best time for renting a car in <?= $cmodel->cty_name; ?></h4>
	<?= $cmodel->cty_name; ?> enjoys a maritime climate throughout the year and to be very realistic you can visit the city in any time of the year. However, the months between September and February see the highest tourist inflow to the city because the weather becomes even more pleasant during the Winter season. The winter months in <?= $cmodel->cty_name; ?> are the best time to visit the city. 
				<h4>Things to look at while booking an outstation cab to and from  <?= $cmodel->cty_name; ?> </h4>
				<ol>
					<li>Ensure that a commercial taxi is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>
					<li>Find if the route you will travel on has tolls. If yes, are tolls included in your booking quote</li>
					<li>When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same</li>
					<li>Price is great but service is what matters. So try to focus on service at a reasonable price. When you go for the cheapest, you will ending up getting what you pay for.<br><br></li>
				</ol>
			</div>
		</div>
	</section>    
	<?php
}
?>
<? $api = Yii::app()->params['googleBrowserApiKey']; ?>
<script type="text/javascript">
    function mapInitialize() {
        var map;
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var directionsService = new google.maps.DirectionsService();
        var mapOptions = {
            zoom: 6,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: new google.maps.LatLng(30.73331, 76.77942),
            mapTypeControl: false
        }
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        directionsDisplay.setMap(map);
        $('#map_canvas').css('height', $('#desc').height());
        var start = '<?= $fcitystate ?>';
        var end = '<?= $tcitystate ?>';
        var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                var leg = response.routes[0].legs[0];
            }
        });
    }
    function loadScript() {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
                'callback=mapInitialize&key=<?= $api ?>';
        document.body.appendChild(script);
    }
    window.onload = loadScript;
</script>
