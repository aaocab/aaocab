<style>
h2, h3{ font-weight: 700;}
.ui-page ul{ list-style-type: none; padding-left: 0;}
.ui-page ul li{ list-style-type: none;}
.table thead th{ color: #fff;}
.bg-primary2{ background: #10457d;}
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
if (isset($routeBreadcumbStructureMarkupData) && trim($routeBreadcumbStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $routeBreadcumbStructureMarkupData; ?>
	</script>
<?php } ?>
<?php
if (isset($jsonproviderStructureMarkupData) && trim($jsonproviderStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonproviderStructureMarkupData; ?>
	</script>
<?php } ?>
<?php
//if (isset($jsonStructureFAQSchema) && trim($jsonStructureFAQSchema) != '')
//{
?>
<!--	<script type="application/ld+json">
<?php //echo $jsonStructureFAQSchema; ?>
</script>-->
<?php //} ?>	
<?php
$this->newHome		 = true;
$rut_url			 = $aliash_path;
$arr_url			 = explode("-", $rut_url);
?>

<?= $this->renderPartial('application.themes.desktop.v2.views.booking.fblikeview') ?>
<div class="row">

	<?php
	echo $this->renderPartial('application.themes.desktop.v2.views.index.topSearch', array('model' => $model), true, FALSE);
	?>
</div>
<?php
$imageVersion		 = Yii::app()->params['imageVersion'];
$has_shared_sedan	 = 0;
if ($type == 'route')
{
	/* @var $rmodel Route */
	?>
	<section id="section2">
		<div class="hide container">
			<div class="row">
				<div class="col-xs-12 col-sm-3">
					<h4>Pickup or Drop anywhere in <?= $rmodel->rutFromCity->cty_name ?></h4>
					<div class="span3 feature">
						<?= $rmodel->rutFromCity->cty_pickup_drop_info ?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<h4>Other Parts of NCR?</h4>
					<div class="span3 feature">
						<?= $rmodel->rutFromCity->cty_ncr ?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<h4>Pickup or Drop anywhere in <?= $rmodel->rutToCity->cty_name ?></h4>
					<div class="span3 feature">
						<?= $rmodel->rutToCity->cty_pickup_drop_info ?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<h4>Discount for Return Trip</h4>
					<div class="span3 feature">
						Get a flat &#x20B9;  200/- discount for return transfer with the same vehicle and the same way.
					</div>
				</div>
			</div>
		</div>

		<div class="container mt30" itemscope="" itemtype="https://schema.org/FAQPage">
			<div class="row">
				<div class="col-12">
					<h1 class="font-24" title="Travel from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>"><b>
							Book <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Cabs online with aaocab 
							<?php
							if ($ratingCountArr['ratings'] > 0)
							{
								?>
								<a href="<?= Yii::app()->createUrl('route-rating/' . $rmodel->rut_name); ?>" class="color-black"><small title="<?php echo $ratingCountArr['ratings'] . ' rating from ' . $ratingCountArr['cnt'] . ' reviews'; ?>">
										<?php
										$strRating = '';

										$rating_star = floor($ratingCountArr['ratings']);
										if ($rating_star > 0)
										{
											$strRating .= '(';
											for ($s = 0; $s < $rating_star; $s++)
											{
												$strRating .= '<i class="fa fa-star color-orange"></i>';
											}
											if ($ratingCountArr['ratings'] > $rating_star)
											{
												$strRating .= '<i class="fa fa-star-half color-orange"></i> ';
											}
											$strRating .= ' ' . $ratingCountArr['cnt'] . ' reviews)';
										}
										echo $strRating;
										?>
									</small>
								</a>
							<?php } ?>
							<!--fb like button-->
							<div class="fb-like pull-right mb30" data-href="https://facebook.com/aaocab" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div></b>
						<!--fb like button-->
					</h1>
				</div>
				<!--   <div class="col-xs-12 col-sm-7 col-md-8 feature"><h1> Book <? //= $rmodel->rutFromCity->cty_name     ?> to <? //= $rmodel->rutToCity->cty_name     ?> Cabs online with aaocab</h1></br></br></div>-->
				</div>
				<div class="row">
					<div id="desc" class="col-md-8 feature">
					<p>Looking for a reliable and affordable way to book a cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>? Look no
						further than Gozo Cabs! We offer a wide variety of cabs to choose from, including sedans,
						SUVs, Innova and tempo travellers. We also have a team of experienced drivers who will get
						you to your destination safely and on time.</p>
					<section>						
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">

							<h2 class="font-16" itemprop="name">Why Choose aaocab?</h2>

							<div class="mb20" itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text" class="ui-page">
										<ul>
											<li>
												<h3 class="font-14 mb0 inline-block">1. Convenient and Easy Booking:</h3>
									<p style="display: inline;">Our cab booking platform is user-friendly, allowing you to book a cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> with just a few clicks. Say goodbye to long wait times and queues!</p>
											</li>
											<li>
												<h3 class="font-14 mb0 mt15 inline-block">2. Affordable Fares:</h3>
									<p style="display: inline;">We understand the value of your money, and our cab fares are budget friendly. Enjoy a cost-effective journey without compromising on comfort and safety.</p>
											</li>
											<li>				
												<h3 class="font-14 mb0 mt15 inline-block">3. Reliable and Safe Travel:</h3>
									<p style="display: inline;">Your safety is our priority. Our fleet of cabs is well-maintained, and our experienced drivers ensure a secure and stress-free travel experience.</p>
											</li>
											<li>				
												<h3 class="font-14 mb0 mt15 inline-block">4. 24/7 Availability:</h3>
									<p style="display: inline;">Whether it's an early morning or late-night journey, we are available round-the-clock to serve you. Plan your trip as per your convenience and schedule.</p>
											</li>
											<li>					
												<h3 class="font-14 mb0 mt15 inline-block">5. Comfort:</h3>
									<p style="display: inline;">We have a variety of cabs to choose from, so you can find the perfect one for your needs.</p>
											</li>
											<li>
												<h3 class="font-14 mb0 mt15 inline-block">6. Experienced Drivers:</h3>
									<p class="mb40" style="display: inline;">Your safety is our utmost concern. Our drivers are experienced, licensed, and knowledgeable about the routes, making your travel secure and pleasant.</p>
											</li>									
										</ul>							
								</div>
							</div>
						</div>
					</section>
					<section>
							<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
								<h2 class="font-16" itemprop="name">How to Book a Cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?></h2>
								<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
									<div itemprop="text" class="ui-page">
<ul>					
										<li class="mb0">Booking a cab with us is quick and straightforward. Follow these simple steps:</li>
										<li class="mb10">Step 1: Visit our <a href="http://www.aaocab.com" target="_blank">website</a> or <a href="http://www.aaocab.com/app" target="_blank">download</a> our user-friendly mobile app.</li>
										<li class="mb10">Step 2: Enter your travel details, including the date, time, and pick-up/drop-off locations (<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>).</li>
										<li class="mb10">Step 3: Select the <a href="/book-cab" target="_blank">cab type</a> that suits your requirements from our wide range of options.</li>
										<li class="mb10">Step 4: Review the fare details and make a secure online payment.</li>
										<li class="mb10">Step 5: Receive an instant confirmation with all the booking details.</li>
</ul>
									</div>
								</div>
							</div>
						</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 class="font-16 mb0" itemprop="name">What are the available cab options for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">Cab options usually include hatchback, sedan, SUV, Innova and tempo travellers. You can choose based on your preferences and group size.</div>
							</div>
						</div>				
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 class="font-16 mb0" itemprop="name">How much does it cost to book a cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>

							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">The cost of booking a cab can vary based on different cab type. However, the minimum base fare starts from <?= Filter::moneyFormatter($minPrice) ?>. It's best to check our booking platform for accurate pricing.</div>
							</div>
						</div>				
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2  itemprop="name" class="font-16 mb0">Can I book a one-way cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">Yes, we offer one-way bookings for routes like <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>. You can choose to either book a <a href="/book-cab/one-way/<?php echo strtolower($arr_url[0]); ?>/<?php echo strtolower($arr_url[1]); ?>">one-way</a> trip or a <a href="/book-cab/round-trip/<?php echo strtolower($arr_url[0]); ?>/<?php echo strtolower($arr_url[1]); ?>">round-trip</a>, based on your travel needs.</div>
							</div>
						</div>				
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 itemprop="name" class="font-16 mb0">Can I pre-book a cab for a specific date and time?</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">Absolutely, we allow you to pre-book a cab for a specific date and time. This is especially useful if you want to ensure availability during peak travel periods.</div>
							</div>
						</div>				
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">					
							<h2 itemprop="name" class="font-16 mb0">Can I make stops or detours during the journey from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">We allow you to make stops or detours during the journey, but it's recommended to inform the driver in advance and discuss any additional charges that might apply. For round trips and multi-city trip, these are non-chargeable.</div>
							</div>
						</div>				
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 itemprop="name" class="font-16 mb0">How long does it take to travel from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> by cab?</h2>

							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">The travel time can vary depending on traffic, weather, and the specific route taken. On average, the journey could take around 

									<?= floor(($rmodel->rut_estm_time / 60)); ?> hours
									<?php
									if (($rmodel->rut_estm_time % 60) > 0)
									{
										?> and <?= ($rmodel->rut_estm_time % 60); ?> minutes<?php } ?>
									.</div>
							</div>
						</div>
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 itemprop="name" class="font-16 mb0">What is the distance between <?= $rmodel->rutFromCity->cty_name ?> and <?= $rmodel->rutToCity->cty_name ?>?</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">The approximate distance is usually around <?= $model->bkg_trip_distance; ?> kilometers, depending on the route taken.</div>
							</div>
						</div>				
					</section>
					<section>
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
							<h2 itemprop="name" class="font-16 mb0">Are toll charges included in the cab fare?</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">Most of the time Toll charges are typically included in the initial fare for one way service. It will be mentioned in the fare breakup. If it is not included in the fare breakup, you'll need to pay them separately during the journey.</div>
							</div>
						</div>			
					</section>
					<section>
							<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">							
								<h2 itemprop="name" class="font-16 mb0">Can I choose a specific vehicle model?</h2>
								<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
									<div itemprop="text">We allow you to request a specific vehicle, but it's not guaranteed. It depends on the availability at the time of booking. For any specific request you call our <a href="javascript:void(0)" class="helpline">24X7 customer support</a>.</div>
								</div>
							</div>					
						</section>
					<section>
							<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">	
								<h2 itemprop="name" class="font-16 mb0">Is it safe to travel by cab, especially for long distances?</h2>
								<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
									<div itemprop="text">We prioritize passenger safety. Drivers are usually verified, and vehicles undergo safety checks.</div>
								</div>
							</div>				
						</section>
					<section>
							<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">							
								<h2 itemprop="name" class="font-16 mb0">Can I cancel or reschedule my booking?</h2>
								<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
									<div itemprop="text">Yes, you can usually cancel or reschedule your booking, but there might be cancellation fees depending on how close it is to the pickup time.</div>
								</div>
							</div>		
						</section>
					<section>
							<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
								<h2 itemprop="name" class="font-16 mb0">How do I pay for the cab ride?</h2>
								<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
									<div itemprop="text">We accept various forms of payment, including credit/debit cards, mobile wallets, UPI and sometimes cash. Payment options are usually available on the <a href="http://www.aaocab.com/app" target="_blank">app</a> or <a href="http://www.aaocab.com" target="_blank">website</a>. You can make a full or partial payment at the time of booking.</div>
								</div>
							</div>				
						</section>
					<section>
							<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">							
								<h2 itemprop="name" class="font-16 mb0">Do I need to carry any identification during the journey?</h2>
								<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
									<div itemprop="text">Although it is not required but it's advisable to carry a government-issued ID card for verification purposes.</div>
								</div>
							</div>
						</section>
					<section>
							<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">							
								<h2 itemprop="name" class="font-16 mb0">What if I have additional passengers or luggage?</h2>
								<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
									<div itemprop="text">When booking, you can specify the number of passengers and amount of luggage. Different cab types have varying capacities for passengers and luggage.</div>
								</div>
							</div>				
						</section>
					<!--							<h2 itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="font-16 mb0">Explore Raniganj's Delights:</h2>
												<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">						
													<p>As you journey from <?= $rmodel->rutFromCity->cty_name ?> to Raniganj, immerse yourself in the region's attractions:</p>
													<h3 class="font-16 mb0">1. Deer Park, Raniganj:</h3>
													<p>Experience nature's beauty at the Deer Park, a serene spot for relaxation and wildlife enthusiasts.</p>
													<h3 class="font-16 mb0">Siddheswari Kali Bari Temple:</h3>
													<p>Visit the Siddheswari Kali Bari Temple and soak in the spirituality and architectural brilliance.</p>
													<h3 class="font-16 mb0">Gar Panchakot:</h3>
													<p>Explore the ruins of Gar Panchakot, an offbeat destination offering a glimpse into history.</p>
												</div>-->
<!--					<p>Ready for a seamless journey from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>? Our cab service ensures affordability, comfort, and safety every step of the way. Book your cab today and make the most of your travel experience. Experience convenience and affordability - reserve your cab now!</p>-->
					<section>
							<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mb20">
								<h2 class="mt0 mb0 font-16" itemprop="name" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi"><?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> Cab Rental Prices & Options</h2>
								<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">					
					<div itemprop="text">					
						<p>
							The cheapest car rental from  <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> cab ​will cost you <?= Filter::moneyFormatter($minPrice); ?> ​for a one way cab journey and for a round trip cab fare from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> will cost you <?= Filter::moneyFormatter($allQuot[1]->routeRates->ratePerKM) ?> /km.
							A one way chauffeur-driven car rental saves you money vs having to pay for a round trip. It is also much more comfortable and convenient as you have a driver driving you in your dedicated car. 
							Also you can book for <a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $arr_url[0])); ?>" class="color-black weight500">Car Rental in <?= $rmodel->rutFromCity->cty_name ?></a> and <a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $arr_url[1])); ?>" class="color-black weight500">Car rental in <?= $rmodel->rutToCity->cty_name ?></a> for local sightseeing and hourly taxi bookings services with Gozo.
						</p>
					</div>		
								</div>
							</div>
						</section>		
					<div class="table-responsive">
						<table class="table table-striped table-bordered">
							<thead>
								<tr class="bg-primary2 color-white">
									<th scope="col" class="color-white"><b>Vehicle Type</b></th>
									<th scope="col"><b>Model Type</b></th>
									<th scope="col"><b>Passenger Capacity</b></th>
									<th scope="col" align="center"><b>Luggage Capacity</b></th>
									<th scope="col" align="center"><b>Rate/km (Round Trip)</b></th>
									<th scope="col" align="center"><b>Fare (One way)</b></th>
								</tr></thead><tbody>
								<?php
								//$cabType	 = VehicleTypes::model()->getCarType();
								//$flexiKey = array_search ('Flexxi Sedan', $cabType);
								$flexiKey	 = VehicleCategory::SHARED_SEDAN_ECONOMIC;
								$cabData	 = SvcClassVhcCat::getVctSvcList("allDetail");
								$arrayVctId	 = array();
								foreach ($allQuot as $cabKey => $baseQuot)
								{
									$cab			 = $cabData[$cabKey];
									$scvIdsWithVht	 = SvcClassVhcCat::getSvcsWithVhcModel();
									if (in_array($cabKey, explode(",", $scvIdsWithVht)))
									{
										continue;
									}
									if ($baseQuot->success)
									{	
										//print'<pre>';print_r($baseQuot->routeRates);
										if ($cabKey == VehicleCategory::SHARED_SEDAN_ECONOMIC)
										{
											$has_shared_sedan = 1;
										}

										if ($cab['scc_id'] == 4)
										{
											if (!in_array($cab['vct_id'], $arrayVctId))
											{
												array_push($arrayVctId, $cab['vct_id']);
												$carModelsSelectTier = ServiceClassRule::model()->getCabByClassId($cab['scc_id'], $baseQuot->routeRates->baseAmount, $cab['vct_id']);
												$carModelsSelectTier = CJSON::decode($carModelsSelectTier);
												foreach ($carModelsSelectTier as $key => $value)
												{
													$scvData	 = SvcClassVhcCat::getByVhtAndTier($value['id'], $cab['scc_id']);
													$scvIdVht	 = $scvData['scv_id'];
													?>
													<tr>
														<td scope="row"><?= $cab['vct_label'] . "<br />(<small>" . $cab['scc_label'] . "</small>)"; ?></td>
														<td><?php echo $value['text']; ?></td>
														<td><?php ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? print('Buy seats in a shared taxi. <a href="http://www.aaocab.com/GozoSHARE">Gozo FLEXXI</a>') : print($cab['vct_capacity'] . 'passengers and driver') ?> </td>
														<td align="center">
															<?php
															if ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC)
															{
																echo '1 bag pack';
															}
															else
															{
																if ($cab['vct_big_bag_capacity'] > 0)
																{
																	echo $cab['vct_big_bag_capacity'] . " big ";
																}
																if ($cab['vct_small_bag_capacity'] > 0)
																{
																	echo $cab['vct_small_bag_capacity'] . ' small';
																}
															}
															?>
														</td>
														<td align="center"><?php ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? print('Fixed prices per seat') : print('&#x20B9;' . $baseQuot->routeRates->ratePerKM) ?></td>
														<td align="center"><span>&#x20B9</span><?php ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? print($allQuot[$cabKey]->flexxiRates[1]['subsBaseAmount']) : print($allQuot[$scvIdVht]->routeRates->baseAmount - $baseQuot->routeRates->discount); ?>
													</tr>
													<?php
												}
											}
										}
										else
										{	
											$priceRule = PriceRule::getByCity($baseQuot->sourceCity, 2, $cab['scv_id'], $baseQuot->destinationCity);
											$ratePerKm = $priceRule->attributes['prr_rate_per_km'];
											if($cab['scv_id'] == 72)
											{
												$minRatePerKm = $ratePerKm;
											}
											?>
											<tr>
												<td><?= $cab['vct_label'] . "<br />(<small>" . $cab['scc_label'] . "</small>)"; ?></td>
												<td><?php echo $cab['vct_desc']; ?></td>
												<td><?php ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? print('Buy seats in a shared taxi. <a href="http://www.aaocab.com/GozoSHARE">Gozo FLEXXI</a>') : print($cab['vct_capacity'] . 'passengers and driver') ?> </td>
												<td align="center">
													<?php
													if ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC)
													{
														echo '1 bag pack';
													}
													else
													{
														if ($cab['vct_big_bag_capacity'] > 0)
														{
															echo $cab['vct_big_bag_capacity'] . " big ";
														}
														if ($cab['vct_small_bag_capacity'] > 0)
														{
															echo $cab['vct_small_bag_capacity'] . ' small';
														}
													}
													?>
												</td>
												<td align="center"><?php ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? print('Fixed prices per seat') : print('&#x20B9;' . $ratePerKm) ?></td>
												<td align="center"><span></span><?php ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? print($allQuot[$cabKey]->flexxiRates[1]['subsBaseAmount']) : print(Filter::moneyFormatter($baseQuot->routeRates->baseAmount)); ?>
											</tr>
											<?php
										}
									}
								}
								?>
							</tbody></table>
					</div>
					<div class="mb40">
						<a href="/book-cab/one-way/<?php echo strtolower($arr_url[0]); ?>/<?php echo strtolower($arr_url[1]); ?>" class="btn text-uppercase gradient-green-blue font-20 border-none mt15">Book Now</a>
	<!--<a href="/bknw/oneway/<?php //echo strtolower($baseQuot->routeDistance->routeDesc[0]);       ?>/<?php //echo strtolower($baseQuot->routeDistance->routeDesc[1]);       ?>" class="btn text-uppercase gradient-green-blue font-20 border-none mt15">Book Now</a>-->
					</div>
				</div>
				<?php
				//$rut_url = $mpath_url;

				if (!empty($arr_url))
				{
					$fromUrl = $arr_url[0];
					$toUrl	 = $arr_url[1];
					?>
					<div class="col-md-4">
						<!--<div  id="map_canvas" style="height: 633px;"></div>
						<p></p>-->
						<div class="row mt20">
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img class="lozad img-fluid" data-src="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" alt="Book cabs in <?= $rmodel->rutFromCity->cty_name; ?>" width="130" height="80"></div>
									<!--<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $rmodel->rutFromCity->cty_name)); ?>">Book cabs in <?= $rmodel->rutFromCity->cty_name; ?></a>-->
									<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>" class="font12">Book cabs in <?= $rmodel->rutFromCity->cty_name; ?></a>
								</div>
							</div>
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img  class="lozad img-fluid"  data-src="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" alt="Book cabs in <?= $rmodel->rutToCity->cty_name ?>" width="130" height="80"></div>
									<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>" class="font-12">Book cabs in <?= $rmodel->rutToCity->cty_name ?></a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img class="lozad img-fluid" data-src="/images/cabs/tempo_9_seater.jpg?v=<?= $imageVersion; ?>" alt="Book Tempo Traveller in <?= $rmodel->rutFromCity->cty_name ?>" width="200" height="113"></div>
									<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>" class="font-12">Book Tempo Traveller in <?= $rmodel->rutFromCity->cty_name ?></a>
								</div>
							</div>
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img  class="lozad img-fluid" data-src="/images/cabs/tempo_12_seater.jpg?v=<?= $imageVersion; ?>" alt="Book Tempo Traveller in <?= $rmodel->rutToCity->cty_name ?>" width="200" height="113"></div>
									<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>" class="font-12">Book Tempo Traveller in <?= $rmodel->rutToCity->cty_name ?></a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img style="min-height: 80px"  class="lozad img-fluid" data-src="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" alt="Outstation taxi rental in <?= $rmodel->rutFromCity->cty_name; ?>" width="130" height="80"></div>
									<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>" class="font-12">Outstation taxi rental in <?= $rmodel->rutFromCity->cty_name; ?></a>
								</div>
							</div>
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img style="min-height: 80px"  class="lozad img-fluid" data-src="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" alt="Outstation taxi rental in <?= $rmodel->rutToCity->cty_name ?>" width="130" height="80"></div>
									<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>" class="font-12">Outstation taxi rental in <?= $rmodel->rutToCity->cty_name ?></a>
								</div>
							</div>
						</div>
						<div class="row">
							<?
							if ($rmodel->rutFromCity->cty_has_airport)
							{
							?>
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img style="min-height: 80px"  class="lozad img-fluid" data-src="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" alt="Airport transfer in <?= $rmodel->rutFromCity->cty_name ?>" width="130" height="80"></div>
									<a href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>" class="font-12">Airport transfer in <?= $rmodel->rutFromCity->cty_name ?></a>
								</div>
							</div>
							<? } ?>
							<?
							if ($rmodel->rutToCity->cty_has_airport)
							{
							?>
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img style="min-height: 80px"  class="lozad img-fluid" data-src="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" alt="Airport transfer in <?= $rmodel->rutToCity->cty_name ?>" width="130" height="80"></div>
									<a href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>" class="font-12">Airport transfer in <?= $rmodel->rutToCity->cty_name ?></a>
								</div>
							</div>
							<? } ?>
						</div>
						<div class="row">
							<?
							if ($model->is_luxury_from_city)
							{
							?>
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img style="min-height: 80px" class="lozad img-fluid" data-src="/images/car-bmw.jpg?v=<?= $imageVersion; ?>" alt="Luxury Car Rental in <?= $rmodel->rutFromCity->cty_name ?>" width="130" height="80"></div>
									<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>" class="font-12">Luxury Car Rental in <?= $rmodel->rutFromCity->cty_name ?></a>
								</div>
							</div>
							<? } ?>
							<?
							if ($model->is_luxury_to_city)
							{
							?>
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img style="min-height: 80px"  class="lozad img-fluid" data-src="/images/car-bmw.jpg?v=<?= $imageVersion; ?>" alt="Luxury Car Rental in <?= $rmodel->rutToCity->cty_name ?>" width="130" height="80"></div>
									<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>" class="font-12">Luxury Car Rental in <?= $rmodel->rutToCity->cty_name ?></a>
								</div>
							</div>
							<? } ?>
						</div>
						<?php
						if ($has_shared_sedan == 1)
						{
							?>
							<div class="col-6 mb20">
								<div class="main_time text-center">
									<div class="car_box2"><img style="min-height: 80px"  class="lozad img-fluid" data-src="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" alt="Shared Sedan in <?= $rmodel->rutFromCity->cty_name . '-' . $rmodel->rutToCity->cty_name ?>" width="130" height="80"></div>
									<a href="/shared-taxi/<?php echo $mpath_url; ?>" class="font-12">Shared Sedan in <br/><?= $rmodel->rutFromCity->cty_name . '-' . $rmodel->rutToCity->cty_name ?></a>
								</div>
							</div>
						<?php } ?>

					</div>
					<?php
				}
				?>
				</div>
				<div class="row">
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="col-12 mb15">
							<h2 class="font-16" itemprop="name">What things to look for when you book an outstation cab from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?></h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">				
				<ul>
										<li class="mb10">You should ensure that a commercial taxi (yellow license plate) is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>								
										<li class="mb10">Find if the route you will travel on has tolls. If yes, are tolls included in your booking quotation</li>
										<li class="mb10">When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same
					</li>
										<li class="mb10">Price is great but service is what matters. So try to focus on service at a reasonable price. When you go for the cheapest, you will ending up getting what you pay for.</li>
				</ul>
								</div>
							</div>
						</div>
				</div>
				<div class="row">
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="col-12 mb15">
							<h2 class="font-16 mb0" itemprop="name">Why is Gozo Cabs the best cab service for travel in India?</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">
									<p>Gozo is continuously focused on being and staying as India's best taxi service for inter-city or outstation car hire with a driver. 
					Gozo cabs are the best cab service to hire <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> cab service. Gozo is generally the cheapest in most regions as we keep our margins low and we keep our quality high by ensuring that our cabs and providers are inspected regularly. At the time of onboarding, the taxi operators are whetted for proper licenses and their ability to meet our quality bar. We also provide ongoing training to our drivers.
					But most importantly Gozo strives to be the best with our support and customer service. Gozo has great reviews on Google & <a href="http://www.tripadvisor.com/Attraction_Review-g304551-d9976364-Reviews-Gozo_Cabs-New_Delhi_National_Capital_Territory_of_Delhi.html" class="color-black weight500">TripAdvisor</a>. Gozo was started with the focus of simplifying car hire for outstation trips and we specialize in one way cabs, round trip journeys and even multi city trips. Car rentals in <?= $rmodel->rutFromCity->cty_name ?> or Car rentals in <?= $rmodel->rutToCity->cty_name ?> are also provided. We offer daily car rentals and also airport transfers in most cities across India.</p>

								</div>
							</div>
						</div>
					</div>
				<div class="row">
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="col-12 mb15">
							<h2 class="font-16 mb0" itemprop="name">Which is the best <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?> taxi service?</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">
									<p>There are many outstation taxi services that you can book either offline or online. Best is a relative term and it depends on what you prefer as a traveller. Most travelers prefer comfort, quality service at a reasonable price. 
					Be careful when trying to haggle for the lowest priced or cheapest cab as you could open yourself to the risk of operators cutting corners in service and also over laying with hidden charges. 
					Booking <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> taxi with aaocab offers hassle less and worry free online Taxi options.
				</p>
								</div>
							</div>
						</div>
					</div>
				<div class="row">
						<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="col-12 mb15">
							<h2 class="mt0 mb0 font-16" itemprop="name"><?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> travel options</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
								<div itemprop="text">
				<p>There are many ways to travel from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?>. This includes travel by cabs, flight, bus, train or in a personal taxi or a shared cab / carpool</p>
								</div>
							</div>
						</div>
					</div>			
				<div id="desc1" class="row">
					<div class="col-12">
						<h3 class="mb0 font-24">FAQs About <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Cabs</h3>
						<div class="pt10">
							<?php 
							$faqArray = Faqs::getDetails(1);
							foreach ($faqArray as $key => $value)
							{
								//print'<pre>';print_r($value);
								$fromCityQueReplace	 = str_replace('{#fromCity#}', $rmodel->rutFromCity->cty_name, $value['faq_question']);
								$toCityQueReplace	 = str_replace('{#toCity#}', $rmodel->rutToCity->cty_name, $fromCityQueReplace);

								$fromCityAnsReplace	 = str_replace('{#fromCity#}', $rmodel->rutFromCity->cty_name, $value['faq_answer']);
								$toCityAnsReplace	 = str_replace('{#toCity#}', $rmodel->rutToCity->cty_name, $fromCityAnsReplace);
								$bookingAmount	 = str_replace('{#bookingAmount#}', Filter::moneyFormatter($minPrice), $toCityAnsReplace);
								//$perKmCharge	 = str_replace('{#perKmCharge#}', $allQuot[1]->routeRates->ratePerKM, $bookingAmount);
								$perKmCharge	 = str_replace('{#perKmCharge#}', Filter::moneyFormatter($minRatePerKm), $bookingAmount);
								$tripDistance	 = str_replace('{#tripDistance#}', $model->bkg_trip_distance, $perKmCharge);
								$tripDuration	 = floor(($rmodel->rut_estm_time / 60)) . ' hours ';
								if (($rmodel->rut_estm_time % 60) > 0)
								{
									$tripDuration .= 'and ' . ($rmodel->rut_estm_time % 60) . ' minutes';
								}

								$tripDuration = str_replace('{#tripDuration#}', $tripDuration, $tripDistance);
								?>
								<div>
									<div class="mb20" itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
										<div>
											<p itemprop="name" class="font-14 mb0"><b><?php echo $toCityQueReplace; ?></b></p>
										</div>
										<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="">
											<div itemprop="text">
												<?php echo $tripDuration; ?>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>

					Here are some more pages related to the <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> route. 
					<br><a href="/outstation-cabs/<?= $rmodel->rutFromCity->cty_name ?>">Outstation Car rental options and prices in <?= $rmodel->rutFromCity->cty_name ?></a><br>
					<a href="/outstation-cabs/<?= $rmodel->rutToCity->cty_name ?>"> Outstation taxi options and prices in <?= $rmodel->rutToCity->cty_name ?></a><br><br><br>
				</div>
			
		</div>

	</section>
	<?php
}
if ($type == 'city')
{
	?>
	<section id="section2">
		<div class="row register_path p20">
			<div class="col-12">
				<h3>Booking a taxi in <?= $cmodel->cty_name; ?></h3>
				<p>You can book a taxi anytime in <?= $cmodel->cty_name; ?> with Gozo. Gozo has various services in <?= $cmodel->cty_name; ?> including one way taxi drops to nearby cities <?php
					if ($cmodel->cty_has_airport > 0)
					{
						echo ", chauffeur driven airport pickups and transfers to anywhere within " . $cmodel->cty_name;
					}
					?> and you can also book cars for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns. </p>
				<p>Gozo provides premium outstation taxi services in over <?= $count['countCities']; ?>  cities and <?= $count['countRoutes']; ?>  routes all around India. </p>
				<p>With Gozo you can book a one way cab to many nearby cities from Mumbai. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x365. Gozo uses local taxi operators in the city who maintain highest level of service quality and have a very good knowledge of the local roads. We can also provide local sightseeing trips and tours in or around the city. Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?= $cmodel->cty_name; ?>. If you have a special requirement , simply ask and we will do our best to help. </p>
				<h3>Taxi fares for most popular 10 routes to or from <?= $cmodel->cty_name; ?></h3>
				<div class="table-responsive">
					<table class="table table-striped">
						<tr>
							<th scope="col"><b>Route</b></th>
							<th scope="col"><b>Compact</b></th>
							<th scope="col"><b>Sedan</b></th>
							<th scope="col"><b>SUV</b></th>
							<th scope="col"><b>Tempo traveler</b></th>
						</tr>
						<?php
						if (count($topRoutes) > 0)
						{
							foreach ($topRoutes as $top)
							{
								?>        
								<tr>
									<td scope="row"><?= $top['from_city']; ?> to <?= $top['to_city']; ?></td>
									<td><?php
										if ($top['compact_amount'] > 0)
										{
											?> starting at <?= $top['compact_amount']; ?> <?php } ?></td>
									<td><?php
										if ($top['seadan_amount'] > 0)
										{
											?> starting at <?= $top['seadan_amount']; ?> <?php } ?></td>
									<td><?php
										if ($top['suv_amount'] > 0)
										{
											?> starting at <?= $top['suv_amount']; ?> <?php } ?></td>
									<td><?php
										if ($top['tempo_amount'] > 0)
										{
											?> starting at <?= $top['tempo_amount']; ?> <?php } ?></td>
								</tr>
								<?php
							}
						}
						?>
					</table>
				</div>
				<p>Gozo's booking process is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.</p>
				<p>On the Gozo platform you can also create a multi-day tour package by customizing your itinerary. These are created a round trip bookings between 2 or more cities.</p>
				<!--<p>Top  places to visit and things to do in <?= $cmodel->cty_name; ?> are – </p>
				<p>Top places to visit including weekend getaways and things to do from <?= $cmodel->cty_name; ?> are – </p>-->
				<p>With Gozo you can book a taxi anywhere in India. Our services are top rated in almost all cities across India. You can book a car with Gozo in X, Y, Z, A, B, C, D and many more</p>
			</div>

		</div>
		<p>city_details</p>
	</section>    
	<?php
}
?>
<?php $api = Yii::app()->params['googleBrowserApiKey']; ?>

<script type="text/javascript">
    function mapInitialize()
    {
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
        //$('#map_canvas').css('height', $('#desc').height());
        var start = '<?= $fcitystate ?>';
        var end = '<?= $tcitystate ?>';
        var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function (response, status)
        {
            if (status == google.maps.DirectionsStatus.OK)
            {
                directionsDisplay.setDirections(response);
                var leg = response.routes[0].legs[0];
            }
        });
    }
    function loadScript()
    {
        var script = document.createElement('script');
        script.async = true;
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
                'callback=mapInitialize&key=<?= $api ?>';
        //document.body.appendChild(script);
    }
    //   window.onload = loadScript;


    function add_links(city_id, cat)
    {

        var href1 = '<?= Yii::app()->createUrl("city/citylinks") ?>';
        jQuery.ajax({'type': 'GET', 'url': href1,
            'data': {'cid': city_id, 'cat': cat},
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'medium',
                    onEscape: function ()
                    {
                        box.modal('hide');
                        box.css('display', 'none');
                        $('.modal-backdrop').remove();
                        $("body").removeClass("modal-open");
                    }
                }).removeClass('fade').css('display', 'block');
            }
        });
        return false;
    }

    function add_place(city_id, cat)
    {

        var href1 = '<?= Yii::app()->createUrl("city/cityplaces") ?>';
        jQuery.ajax({'type': 'GET', 'url': href1,
            'data': {'cid': city_id, 'cat': cat},
            success: function (data)
            {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'medium',
                    onEscape: function ()
                    {
                        box.modal('hide');
                        box.css('display', 'none');
                        $('.modal-backdrop').remove();
                        $("body").removeClass("modal-open");
                    }
                }).removeClass('fade').css('display', 'block');
            }
        });
        return false;
    }

    function book_now()
    {
        jQuery('#bookingSform').submit();
    }


    const obz = lozad(document.getElementById("map_canvas"), {
        load: function (el)
        {
            //loadScript();
        }
    });
//obz.observe();

</script>
