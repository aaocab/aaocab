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
</style>
<?
$this->newHome = true;
/* @var $cmodel Cities */
?>
<?= $this->renderPartial('application.views.booking.fblikeview')?>
<div class="row">
    <?= $this->renderPartial('application.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
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
$cities        = ($count['countCities'] > 500) ? 500 : $count['countCities'];
$routes        = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
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
<section id="section2">
    <div class="row p20">
        <div class="col-xs-12 col-sm-10 col-md-10 float-none marginauto">
			<h1>Book Outstation cabs for travel to or from <?= $cmodel->cty_name; ?>
			<!--fb like button-->
				<div class="fb-like pull-right mb30" data-href="https://facebook.com/aaocab" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
			<!--fb like button-->
			</h1>
            <h3>Outstation cab rental with driver for <?= $cmodel->cty_name; ?> - starting at Rs. <?=$minRate?>/km</h3>
			
            <p>Gozo provides outstation cab booking services with driver in <?= $cmodel->cty_name; ?> for day-based rentals, one way, round trips, multicity travel and many more that are billed by custom itinerary or by day.
			<div class="col-xs-6 col-sm-3 col-md-2 mb20 pull-right">
				<div class="main_time border-blueline text-center">
					<div class="car_box2"><img src="/images/cabs/tempo_9_seater.jpg" alt=""></div>
					<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)) ?>">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
				</div>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2 mb20 pull-right">
				<div class="main_time border-blueline text-center">
					<div class="car_box2"><img src="/images/cabs/car-etios.jpg" alt=""></div>
					<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)) ?>">Car rental option in <?= $cmodel->cty_name; ?></a>
				</div>
			</div>
			</p>
            <p>Our customers book outstation taxi services in <?= $cmodel->cty_name; ?> for trips to most nearby cities. 
             AC cabs are the most comfortable way to travel to or from <?= $cmodel->cty_name; ?>. Since the driver is a local, they are familiar with the routes and this makes it much more convenient than hiring a self-drive car. Most business travelers rent cabs for flexible travel between <?= $cmodel->cty_name; ?> and nearby towns. Gozo provides compact, sedan and SUV outstation car rentals in addition to minibus for groups (tempo traveller) and luxury cars for executives. Shared outstation taxis are also available between <?= $cmodel->cty_name; ?> and<?=$topCitiesByKm;?>. Gozo’s cab rental services are available in over 1000 cities and on 50,000+ routes across the country. Gozo’s Outstation taxi charges are among the most reasonable, as Gozo cabs specializes in inter-city travel which gives us the greatest reach, largest supply and strong relationships with taxi operators who can deliver to you the best services with courteous and well-informed drivers. 
	         Drivers working with Gozo are familiar with the routes in <?= $cmodel->cty_name; ?> and the various regions surrounding <?= $cmodel->cty_name; ?> area. 
			</p>
			<h2>Book Shared outstation cabs from or to <?= $cmodel->cty_name; ?></h2>
			<p>Gozo Cabs offers a unique facility called Gozo FLEXXI outstation shared taxi / carpool service in which customers can share a cab for outstation journeys. 
              Gozo FLEXXI SHARE is available on many routes to and from <?= $cmodel->cty_name; ?>. Instead of booking a bus ticket to or from <?= $cmodel->cty_name; ?> you should take a look at Gozo FLEXXI SHARE.
               Visit our booking page to see the availability of shared seats in cabs to and from <?= $cmodel->cty_name; ?>. All frequently traveled routes from <?= $cmodel->cty_name; ?> generally have seats available in a shared taxi.</p>
			<p>Visit our booking page to see availability of shared seats in cabs to and from <?= $cmodel->cty_name; ?>. All frequently travelled routes from <?= $cmodel->cty_name; ?> generally have seats available in shared taxi.</p>
			
			
			
            <h2>Hire a taxi with driver for the day in <?= $cmodel->cty_name; ?></h2>
            <p>Most of our corporate clients prefer to have a chauffeur driver car at their disposal for when they are visiting <?= $cmodel->cty_name; ?> for either a few hours, full day or a few days. Having a outstation car hire available works out to be very convenient and also time & cost efficient for business travelers. Various businesses  use our corporate travel services to avail these services in the cities and towns they travel to frequently. Our services of outstation cab in India will serve you all through the roads of India (the major cities). With Gozo you always get transparent billing, fair prices, 24x7 support and nationwide reach. If you have any special requirements you can always contact our customer helpdesk and we will be happy to support you.</p>
			<p>With Gozo you always get transparent billing, fair prices, 24x7 support and nationwide reach. If you have any special requirements you can always contact our customer helpdesk and we will be happy to support you.</p>

            <h3>Outstation cab prices for trips to or from <?= $cmodel->cty_name; ?></h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <td><b>Route (Starting at)</b></td>
                        <td align="center"><b>Total kms</b></td>
						<td align="center"><b>Shared Taxi</b></td>
                        <td align="center"><b>Compact</b></td>
                        <td align="center"><b>Sedan</b></td>
                        <td align="center"><b>SUV</b></td>
                        <td align="center"><b>Tempo traveler<br> (9 seater)</b></td>
                        <td align="center"><b>Tempo traveler<br> (12 seater)</b></td>
                        <td align="center"><b>Tempo traveler<br> (15 seater)</b></td>
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
                                <td><a href="<?=Yii::app()->createAbsoluteUrl("/book-taxi/".strtolower($top['from_city_alias_path']).'-'.strtolower($top['to_city_alias_path']));?>" target="_blank" ><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</a></td>
                                <td align="center"><?= $top['rut_distance']; ?></td>
								<td align="center"><?= ($top['flexi_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['flexi_price'] : 'Call us'; ?></td>
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
            <h2>Outstation cabs in <?= $cmodel->cty_name; ?></h2>
            <p>With Gozo you can book an outstation cabs 24x7 from <?= $cmodel->cty_name; ?> to <?=$topCitiesByKm;?>. </p>
            <h4>Here are some common questions that you should ask your provider when booking an outstation cab.</h4>

            <h4><u>Can I book an outstation cab in <?= $cmodel->cty_name; ?> on-demand and on-the-fly using my app? </u></h4>
            <p>Due to the nature of outstation travel, most trips are pre-scheduled and pre-booked enabling us to arrange for the right vehicle for your journey. We encourage customers to make reservations at least 3 weeks ahead of your date of travel as prices have a tendency to rise as vehicle supply becomes limited. Gozo can arrange for vehicles for outstation travel from <?=$cmodel->cty_name;?> even at the last minute but in most cases please anticipate at least 2hours of time between you making your reservation and the vehicle arriving for pickup in <?=$cmodel->cty_name;?>. We have been able to deliver vehicles in much shorter times but please take this as a general guidance.</p>

            <h4><u>What is the daily allowance for the driver when renting an outstation cab in <?=$cmodel->cty_name;?>?</u></h4>
            <p>When renting a vehicle for outstation cab booking while you travel in India, the driver is traveling out of town and hence his daily expenses like food, lodging etc are covered with the daily allowance. 
, the driver is traveling out of town and hence his daily expenses like food, lodging etc are covered with the daily allowance. The drivers daily allowance.</p>
            
            <h4><u>What are the different types of trips that a person can take for outstation travel from <?= $cmodel->cty_name;?>?</u></h4> 
            <p>You can book a one-way trip, round trip or plan a itinerary for travel to multiple cities from <?= $cmodel->cty_name;?>. When traveling in a outstation taxi from <?= $cmodel->cty_name;?> you are renting outstation car with driver. Gozo provides with well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x36 for outstation taxi booking from <?=$cmodel->cty_name;?>.</p>
            <p>What is included as part of the charges for an outstation trip? </p>
            <p>As part of an outstation journey your charges include a certain number of pre-allocated kms and the applicable taxes for the journey.</p>
            <p>For one-way trips from <?= $cmodel->cty_name;?>- we may include the applicable tolls as your route of travel is pre-defined. </p>
            <p>In the case of round trips or multi-day journeys starting in <?= $cmodel->cty_name;?>, the customer is expected to pay for all applicable tolls along the way.  For a roundtrip or multi-day journeys for outstation cab from <?= $cmodel->cty_name;?>, you may use the vehicle upto the allocated time and number of kms without any extra km driving charges.</p>
            <p>Typically a one-way trip is a point to point transfer between two cities. We need you to provide us the exact pickup and drop addresses. Your price quotation is based on the estimated kms of travel between the two locations assuming there will be no intermediate stops or waypoints or sightseeing. Its simply a intercity transfer from A to B in a cab that is for your use. You do not share the cab with anyone else.</p>

            <h4><u>Are the drivers knowledgeable about the highways and the journey from <?= $cmodel->cty_name; ?>?</u></h4>
            <p>Gozo uses local taxi operators in <?= $cmodel->cty_name; ?>  for its <?= $cmodel->cty_name; ?> outstation taxi service who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We can also provide local sightseeing trips and tours in or around the city if you require any day rental needs.regarding our cab for outstation from <?= $cmodel->cty_name;?> </p> 

            <h4><u>How clear is the pricing and billing?</u></h4>
            <p>Gozo's booking and billing process for outstation car hire from <?= $cmodel->cty_name; ?> is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price. We encourage you to read the terms and conditions on your booking confirmation. With Gozo you can book a taxi anywhere in India. Our services are top rated in almost all cities across India.</p> 

            <h4><u>If I rent a outstation cab, can it be used for sightseeing in <?= $cmodel->cty_name; ?>? </u></h4>
            <p>Outstation cabs can be used for sight-seeing as well. Typically we allocate between 250 and 300 kms per day for you to use the vehicle for either outstation transportation or sightseeing during your journey. When you rent a vehicle for <?= $cmodel->cty_name; ?> outstation cab or in <?= $cmodel->cty_name; ?> for a certain number of days, you are pre-purchasing a certain number of kilometers to be driven on your journey. 
If you are renting a vehicle for outstation cab <?= $cmodel->cty_name; ?> for a one-way journey then it is not possible to use the vehicle for sight-seeing trips. One-way trips are pre-scheduled and booked based on transfers from a fixed pickup point to a fixed drop point. 
Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?= $cmodel->cty_name; ?>. For any special needs, cab for outstation from <?= $cmodel->cty_name; ?>  simply call our service center and we will do our best to help.</p>
		</div>
	</div>
	<!--<p class="link-panel">
				<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_name)); ?>">Book Tempo Traveller online with Gozo</a>
	</p>-->
 </section>    
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