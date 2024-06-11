<style>
h2, h3{ font-weight: 700;}
.table th, .table td{ font-family: 'Roboto'}
</style>
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
<?
$this->newHome = true;
/* @var $cmodel Cities */
?>

<?= $this->renderPartial('application.themes.desktop.v2.views.booking.fblikeview') ?>
<div class="row">
    <?= $this->renderPartial('application.themes.desktop.v2.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
<!--<div class="row gray-bg-new">
    <div class="col-lg-10 col-sm-10 col-md-8 text-center flash_banner float-none marginauto ml50 border bg-white">
        <span class="h3 mt0 mb5 flash_red text-warning">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Taxi and Car rentals all over India – Gozo’s online cab booking service</span><br>
        aaocab is your one-stop shop for hiring chauffeur driven taxi in India for inter-city, airport transfers and even local daily tours, specializing in one-way taxi trips all over India at transparent fares. Gozo’s coverage extends across <b>2,000</b> locations in India, with over <b>20,000</b> vehicles, more than <b>75,000</b> satisfied customers and above <b>10</b> Million kms driven in a year alone. Book by web, phone or app 24x7! 
    </div>
</div>-->
<div class="container mt30" itemscope="" itemtype="https://schema.org/FAQPage">
    <?php
    $cities = ($count['countCities'] > 500) ? 500 : $count['countCities'];
    $routes = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
    $topCitiesByKm = '';
    $ctr = 1;
    foreach ($topCitiesKm as $top) {
        $topCitiesByKm .= '<a href="/outstation-cabs/' . strtolower($top['cty_alias_path']) . '" style=" text-decoration: none; color: #282828;" target="_blank">' . $top['city'] . '</a>';
        $topCitiesByKm .= (count($topCitiesKm) == $ctr) ? " " : ", ";
        $ctr++;
    }
    $text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';
    $minRate = (min(array_column($topTenRoutes, 'min_rate')) > 0) ? min(array_column($topTenRoutes, 'min_rate')) : 10;
    ?>
        <div class="row" id="section2">
            <div class="col-12">
                <h1 class="font-24 mb0">Book Outstation cabs for travel to or from <?= $cmodel->cty_name; ?>
                    <!--fb like button-->
                    <div class="fb-like pull-right mb30" data-href="https://facebook.com/aaocab" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
                    <!--fb like button-->
                </h1>
                <p class="font-16">Outstation cab rental with driver for <?= $cmodel->cty_name; ?> - starting at ₹<?= $minRate ?>/km</p>
</div>
</div>
<div class="row">
<div class="col-12">
                <p>Gozo provides outstation cab booking services with driver in <?= $cmodel->cty_name; ?> for day-based rentals, one way, round trips, multicity travel and many more that are billed by custom itinerary or by day.</p>
                <div>
                    <div class="pull-right border-blueline text-center" style="width: 200px;">
                        <div class="car_box2"><img src="/images/cabs/tempo_9_seater.jpg" alt="Img" width="200" height="113"></div>
                        <a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)) ?>" class="color-black">Book Tempo Traveller online in <?= $cmodel->cty_name; ?></a>
                    </div>
                    <div class="pull-right border-blueline text-center" style="width: 200px;">
                        <div class="car_box2"><img src="/images/cabs/car-etios.jpg" alt="Img" width="200" height="113"></div>
                        <a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)) ?>" class="color-black">Car rental option in <?= $cmodel->cty_name; ?></a>
                    </div>
                </div>
                
            <p>Our customers book outstation taxi services in <?= $cmodel->cty_name; ?> for trips to most nearby cities. 
             AC cabs are the most comfortable way to travel to or from <?= $cmodel->cty_name; ?>. Since the driver is a local, they are familiar with the routes and this makes it much more convenient than hiring a self-drive car. Most business travelers rent cabs for flexible travel between <?= $cmodel->cty_name; ?> and nearby towns. Gozo provides compact, sedan and SUV outstation car rentals in addition to minibus for groups (tempo traveller) and luxury cars for executives. Shared outstation taxis are also available between <?= $cmodel->cty_name; ?> and<?=$topCitiesByKm;?>. Gozo’s cab rental services are available in over 1000 cities and on 50,000+ routes across the country. Gozo’s Outstation taxi charges are among the most reasonable, as Gozo cabs specializes in inter-city travel which gives us the greatest reach, largest supply and strong relationships with taxi operators who can deliver to you the best services with courteous and well-informed drivers. 
	         Drivers working with Gozo are familiar with the routes in <?= $cmodel->cty_name; ?> and the various regions surrounding <?= $cmodel->cty_name; ?> area. 
			</p>
</div>
</div>
<div class="row">
<div class="col-12">
			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
					<h2 class="font-16 mb0" itemprop="name">Book Shared outstation cabs from or to <?= $cmodel->cty_name; ?></h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<p>Gozo Cabs offers a unique facility called Gozo FLEXXI outstation shared taxi / carpool service in which customers can share a cab for outstation journeys. 
								Gozo FLEXXI SHARE is available on many routes to and from <?= $cmodel->cty_name; ?>. Instead of booking a bus ticket to or from <?= $cmodel->cty_name; ?> you should take a look at Gozo FLEXXI SHARE.
								Visit our booking page to see the availability of shared seats in cabs to and from <?= $cmodel->cty_name; ?>. All frequently traveled routes from <?= $cmodel->cty_name; ?> generally have seats available in a shared taxi.</p>
							<p>Visit our booking page to see availability of shared seats in cabs to and from <?= $cmodel->cty_name; ?>. All frequently travelled routes from <?= $cmodel->cty_name; ?> generally have seats available in shared taxi.</p>
						</div>
					</div>
				</div>
			</section>

			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
					<h2 class="font-16 mb0" itemprop="name">Hire a taxi with driver for the day in <?= $cmodel->cty_name; ?></h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<p>Most of our corporate clients prefer to have a chauffeur driver car at their disposal for when they are visiting <?= $cmodel->cty_name; ?> for either a few hours, full day or a few days. Having a outstation car hire available works out to be very convenient and also time & cost efficient for business travelers. Various businesses  use our corporate travel services to avail these services in the cities and towns they travel to frequently. Our services of outstation cab in India will serve you all through the roads of India (the major cities). With Gozo you always get transparent billing, fair prices, 24x7 support and nationwide reach. If you have any special requirements you can always contact our customer helpdesk and we will be happy to support you.</p>
							<p>With Gozo you always get transparent billing, fair prices, 24x7 support and nationwide reach. If you have any special requirements you can always contact our customer helpdesk and we will be happy to support you.</p>
						</div>
					</div>
				</div>
			</section>

			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
					<h2 class="font-16 mb0" itemprop="name">Outstation cab prices for trips to or from <?= $cmodel->cty_name; ?></h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<div class="table-responsive">
								<table class="table table-striped table-bordered">
									<tr>
										<th scope="col"><b>Route (Starting at)</b></th>
										<th scope="col" align="center"><b>Total kms</b></th>
										<th scope="col" align="center"><b>Shared Taxi</b></th>
										<th scope="col" align="center"><b>Compact</b></th>
										<th scope="col" align="center"><b>Sedan</b></th>
										<th scope="col" align="center"><b>SUV</b></th>
										<th scope="col" align="center"><b>Tempo traveler<br> (9 seater)</b></th>
										<th scope="col" align="center"><b>Tempo traveler<br> (12 seater)</b></th>
										<th scope="col" align="center"><b>Tempo traveler<br> (15 seater)</b></th>
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
												<td scope="row"><a href="<?= Yii::app()->createAbsoluteUrl("/book-taxi/" . $top['rut_name']); ?>" target="_blank" ><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</a></td>
												<td align="center"><?= $top['rut_distance']; ?></td>
												<td align="center"><?= ($top['flexi_price'] > 0) ? Filter::moneyFormatter($top['flexi_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
												<td align="center"><?= ($top['compact_price'] > 0) ? Filter::moneyFormatter($top['compact_price']) : '<a href="tel:+919051877000"class=p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
												<td align="center"><?= ($top['seadan_price'] > 0) ? Filter::moneyFormatter($top['seadan_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
												<td align="center"><?= ($top['suv_price'] > 0) ? Filter::moneyFormatter($top['suv_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
												<td align="center"><?= ($top['tempo_9seater_price'] > 0) ? Filter::moneyFormatter($top['tempo_9seater_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
												<td align="center"><?= ($top['tempo_12seater_price'] > 0) ? Filter::moneyFormatter($top['tempo_12seater_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
												<td align="center"><?= ($top['tempo_15seater_price'] > 0) ? Filter::moneyFormatter($top['tempo_15seater_price']) : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
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
						</div>
					</div>
				</div>
			</section>

			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
					<h2 class="font-16 mb0" itemprop="name">Outstation cabs in <?= $cmodel->cty_name; ?></h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<p>With Gozo you can book an outstation cabs 24x7 from <?= $cmodel->cty_name; ?> to <?= $topCitiesByKm; ?>.</p>
						</div>
					</div>
				</div>
			</section>

            <p>Here are some common questions that you should ask your provider when booking an outstation cab.</p>

			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
					<h2 class="font-16 mb0" itemprop="name">Can I book an outstation cab in <?= $cmodel->cty_name; ?> on-demand and on-the-fly using my app?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<p>Due to the nature of outstation travel, most trips are pre-scheduled and pre-booked enabling us to arrange for the right vehicle for your journey. We encourage customers to make reservations at least 3 weeks ahead of your date of travel as prices have a tendency to rise as vehicle supply becomes limited. Gozo can arrange for vehicles for outstation travel from <?= $cmodel->cty_name; ?> even at the last minute but in most cases please anticipate at least 2hours of time between you making your reservation and the vehicle arriving for pickup in <?= $cmodel->cty_name; ?>. We have been able to deliver vehicles in much shorter times but please take this as a general guidance.</p>
						</div>
					</div>
				</div>
			</section>

			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
					<h2 class="font-16 mb0" itemprop="name">What is the daily allowance for the driver when renting an outstation cab in <?= $cmodel->cty_name; ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<p>When renting a vehicle for outstation cab booking while you travel in India, the driver is traveling out of town and hence his daily expenses like food, lodging etc are covered with the daily allowance. 
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
							<p>You can book a one-way trip, round trip or plan a itinerary for travel to multiple cities from <?= $cmodel->cty_name; ?>. When traveling in a outstation taxi from <?= $cmodel->cty_name; ?> you are renting outstation car with driver. Gozo provides with well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x36 for outstation taxi booking from <?= $cmodel->cty_name; ?>.</p>
							<p> </p>
							
						</div>
					</div>
				</div>
			</section>

			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
					<h2 class="font-16 mb0" itemprop="name">What is included as part of the charges for an outstation trip?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<ul class="pl15">
								<li class="mb10">As part of an outstation journey your charges include a certain number of pre-allocated kms and the applicable taxes for the journey.</li>
								<li class="mb10">For one-way trips from <?= $cmodel->cty_name; ?>- we may include the applicable tolls as your route of travel is pre-defined. </li>
								<li class="mb10">In the case of round trips or multi-day journeys starting in <?= $cmodel->cty_name; ?>, the customer is expected to pay for all applicable tolls along the way.  For a roundtrip or multi-day journeys for outstation cab from <?= $cmodel->cty_name; ?>, you may use the vehicle upto the allocated time and number of kms without any extra km driving charges.</li>
								<li class="mb10">Typically a one-way trip is a point to point transfer between two cities. We need you to provide us the exact pickup and drop addresses. Your price quotation is based on the estimated kms of travel between the two locations assuming there will be no intermediate stops or waypoints or sightseeing. Its simply a intercity transfer from A to B in a cab that is for your use. You do not share the cab with anyone else.</li>
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
							<p>Gozo uses local taxi operators in <?= $cmodel->cty_name; ?>  for its <?= $cmodel->cty_name; ?> outstation taxi service who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We can also provide local sightseeing trips and tours in or around the city if you require any day rental needs.regarding our cab for outstation from <?= $cmodel->cty_name; ?> </p> 
						</div>
					</div>
				</div>
			</section>

			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
					<h2 class="font-16 mb0" itemprop="name">How clear is the pricing and billing?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<p>Gozo's booking and billing process for outstation car hire from <?= $cmodel->cty_name; ?> is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price. We encourage you to read the terms and conditions on your booking confirmation. With Gozo you can book a taxi anywhere in India. Our services are top rated in almost all cities across India.</p> 
						</div>
					</div>
				</div>
			</section>

			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
					<h2 class="font-16 mb0" itemprop="name">If I rent a outstation cab, can it be used for sightseeing in <?= $cmodel->cty_name; ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<p>Outstation cabs can be used for sight-seeing as well. Typically we allocate between 250 and 300 kms per day for you to use the vehicle for either outstation transportation or sightseeing during your journey. When you rent a vehicle for <?= $cmodel->cty_name; ?> outstation cab or in <?= $cmodel->cty_name; ?> for a certain number of days, you are pre-purchasing a certain number of kilometers to be driven on your journey. 
								If you are renting a vehicle for outstation cab <?= $cmodel->cty_name; ?> for a one-way journey then it is not possible to use the vehicle for sight-seeing trips. One-way trips are pre-scheduled and booked based on transfers from a fixed pickup point to a fixed drop point. 
								Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?= $cmodel->cty_name; ?>. For any special needs, cab for outstation from <?= $cmodel->cty_name; ?>  simply call our service center and we will do our best to help.
							</p>
						</div>
					</div>
				</div>
			</section>
            
</div>
</div>

<div class="row">
			<div class="col-12">
						<h3 class="mb0 font-24">FAQs About <?= $cmodel->cty_name ?> Cabs</h3>
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
									<p itemprop="name" class="font-14 mb0"><b><?php echo $cityQueReplace; ?></b></p>
									</div>
									<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="">
										<div itemprop="text">
										   <?php echo $kmChargeAnsReplace; ?>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
            </div>
</div>
        
        <!--<p class="link-panel">
                                <a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_name)); ?>">Book Tempo Traveller online with Gozo</a>
        </p>-->
</div>
