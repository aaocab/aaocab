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
	.main_time2{ min-height: 178px; line-height:18px; font-size:12px;}
</style>
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
$this->newHome		 = true;
$rut_url = $aliash_path;
$arr_url = explode("-", $rut_url);
?>

	<?= $this->renderPartial('application.views.booking.fblikeview') ?>
<div class="row">

<?= $this->renderPartial('application.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
<div class="row gray-bg-new">
    <div class="col-lg-7 col-sm-11 col-md-9 text-center flash_banner float-none marginauto hidden">
        <span class="h3 mt0 mb5 flash_red">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Taxi and Car rentals all over India – Gozo’s online cab booking service</span><br>
        aaocab is your one-stop shop for hiring chauffeur driven taxi in India for inter-city, airport transfers and even local daily tours, specializing in one-way taxi trips all over India at transparent fares. Gozo’s coverage extends across <b>2,000</b> locations in India, with over <b>20,000</b> vehicles, more than <b>75,000</b> satisfied customers and above <b>10</b> Million kms driven in a year alone. Book by web, phone or app 24x7! 
    </div>
</div>
<div class="row flash_banner hide" style="background: #ffc864;">
    <div class="col-lg-12 p0 hidden-sm hidden-xs text-center">     
        <figure><img  class="lozad" data-src="/images/flash_lg1.jpg?v=1.1" alt="Flash Sale"></figure>		
    </div>
    <div class="col-sm-12 p0 hidden-lg hidden-md hidden-xs text-center">      
        <figure><img  class="lozad"  data-src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>		
    </div>
    <div class="col-xs-12 p0 hidden-lg hidden-md hidden-sm text-center">
		<? /* /?><a target="_blank" href="https://twitter.com/aaocab"><?/ */ ?>
			<figure><img  class="lozad"  data-src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>
			<? /* /?></a><?/ */ ?>
    </div>
</div>

<?php
$has_shared_sedan = 0;
if ($type == 'route')
{
	/* @var $rmodel Route */
	?>
	<section id="section2">
		<div class="row gray-bg-new">
			<div class="col-xs-12 col-sm-11 float-none marginauto customer-box">
				<div class="hidden-xs">
					<div class="col-xs-12">
						<div class="row mt40 n">
							<div class="col-xs-12 text-right">
								<a class="btn arrow-part left-arrow-part" href="#myCarouselTestimonial" role="button" data-slide="prev">
									<span class="fa fa-angle-left" aria-hidden="true"></span>
									<span class="sr-only">Previous</span>
								</a>
								<a class="btn arrow-part right-arrow-part" href="#myCarouselTestimonial" role="button" data-slide="next">
									<span class="fa fa-angle-right" aria-hidden="true"></span>
									<span class="sr-only">Next</span>
								</a>

							</div>
						</div>
						<div id="myCarouselTestimonial" class="carousel slide mt20 " data-ride="carousel" data-interval="false">
							<div class="carousel-inner" role="listbox"> 
								<?php
								$rows = Yii::app()->cache->get("getTopRatings-" . $rmodel->rut_id);
								if ($rows === false)
								{
									$route	 = trim($_GET['route']);
									/* @var $modelTestimonial Ratings */
									$rows	 = Ratings::model()->getTop3Ratings($route, 6);
									Yii::app()->cache->set("getTopRatings-" . $rmodel->rut_id, $rows, 7200);
								}

								$active	 = "active";
								$i		 = 0;
								foreach ($rows as $row)
								{
									$r			 = $i % 3;
									$toCities	 = $row['cities'];
									if ($r == 0)
									{
										?>
										<div class="item <?= $active ?>">
											<div class="row flex">
												<? } ?>
												<div class="col-xs-12 col-sm-4 mb10">
													<div class="panel panel-default customer-panel">
														<div class="panel-body">
															<div class="text-center mb10"><img  class="lozad"  data-src="/images/commas.png" alt="" ></div>
															<div class="text-center mb20 user-review">
			<?= $row['rtg_customer_review'] ?>

															</div>
															<div class="row">
																<div class="col-xs-12 pull-left mr10">
																	<div class="row">
																		<div class="col-xs-12 col-sm-4 col-md-3">
																			<div class="test-name mb20 mr15" style="float: left"><?= $row['initial'] ?></div>
																		</div>
																		<div class="col-xs-9 col-sm-8 col-md-9 pl0">
																			<p class="m0"><i><b>- <?= $row['user_name'] ?></b></i></p>
																			<p class="m0"><b><?= $toCities; ?>,</b> <i><?= Booking::model()->getBookingType($row['bkg_booking_type']); ?></i></p>
																			<p class="m0 block-color3"><i><b><?= date('jS M Y', strtotime($row['rtg_customer_date'])) ?></b></i></p>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?
												$i++;
												$active = "";
												if ($r == 2)
												{
												?>
											</div>
										</div>
										<?
										}
										}
										?>
									</div>              
								</div>
								<a class="pull-right" href="<?= Yii::app()->createUrl('index/testimonial'); ?>">more review</a>
							</div>
						</div>
					</div>
				</div>
                     
				<div  class="hide container">
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

				<div class="newline mt20">
					<div class="row">
						<div class="col-xs-12 col-sm-10 col-md-10 float-none marginauto">
							<h1 class="ml15" title="Travel from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>">
								Travel from <?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> 
								<?php
								if ($ratingCountArr['ratings'] > 0)
								{
									?>
									<a href="<?= Yii::app()->createUrl('route-rating/' . $rmodel->rut_name); ?>"><small title="<?php echo $ratingCountArr['ratings'] . ' rating from ' . $ratingCountArr['cnt'] . ' reviews'; ?>">
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
			<?php } ?>
								<!--fb like button-->
								<div class="fb-like pull-right mb30" data-href="https://facebook.com/aaocab" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
								<!--fb like button-->
							</h1>

                             <div class="col-xs-12 col-sm-7 col-md-8 feature"><b> Best way for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> travel</b></br></div>

							<div id="desc" class="col-xs-12 col-sm-7 col-md-8 feature">
                               Gozo cabs offer an online cab booking service for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> travel at an affordable price. Book online in advance for the best price and offers. Book one-way, multicity, roundtrip, package trips and many more for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> and vice versa with Gozo.</br></br>
								There are many ways to travel from<?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?>. This includes travel by flight, bus, train or in a personal taxi or a shared cab / carpool<br/><br/>
								India’s railway network is one the largest in the world. However our railway infrastructure is overburdened by our massive population and fast growing economy. 
								We strongly suggest booking your train tickets well ahead in time. Traveling from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> by train can be an amazing experience if you are prepared to be patient and go along for the experience. Railways are facing stiff competition from road transport. 
								<br/><br/>
								Travel by bus from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> can be a great option. There are various bus services available from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>. 
								The time to travel by bus is dependant on India’s traffic, roads and climatic conditions.
								<br/><br/>
								Traveling from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> by Taxi is the most comfortable option. The most comfortable and speediest option for traveling short distances (150-300km) is to get a outstation taxi rental. 
								However if you are looking to go on a one way journey it's best to hire a chauffeur-driven one way cab. There are options available to book a shared taxi if you are on a budget trip. If you are looking for a taxi that is dedicated to your use you can find that as well.  If you are keen on an low-cost option a shared taxi / carpooling  from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> can be cheaper than going by bus or train. 
								A group of 2-3 travelers can travel by a sedan car and this most generally is a quick door-to-door transport,  
								most comfortable and cheaper than that same group buying AC train or AC bus tickets. When booking a one-way taxi from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>, to get the cheapest rates you must book atleast 5-10days in advance. This enables us to have the time to find you a taxi most suited for your travel plans. See our <a href="<?= Yii::app()->getBaseUrl(true) ?>/cheapest-oneway-rides">cheapest one-way taxi</a> rates for traveling across India


								<!--The most comfortable and speediest
								option is to get a outstation taxi rental. However if you are looking to go on a one way
								journey it's best to hire a chauffeur-driven one way cab. There are options to book a
								shared taxi if you are on a budget trip. If you are looking for a taxi that is dedicated to
								your use you can find that as well. -->
								<br/><br/><br/>

								<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Distance and time for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> travel and vice versa
</h3>
								<div class="row">
									<div class="col-xs-12">
										Distance from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?> ​by car is around <?= $model->bkg_trip_distance; ?> ​Kms. Estimated travel time traveling
										from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?> ​by a dedicated car is ​<?= floor(($rmodel->rut_estm_time / 60)); ?> hours<?php
										if (($rmodel->rut_estm_time % 60) > 0)
										{
											?> and <?= ($rmodel->rut_estm_time % 60); ?> minutes<?php } ?>. Please budget
										between 30-60 Minutes for delay in traffic. <br/><br/><br/>
									</div>
								</div>
								<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi"><?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> ​​Car Rental Prices & Options</h3>
								<div class="row">
									<div class="col-xs-12">
										The cheapest way to travel from <?= $rmodel->rutFromCity->cty_name ?> ​To <?= $rmodel->rutToCity->cty_name ?> ​oneway travel ​will cost you  ​Rs. <?= $basePriceOW; ?> ​for a one way cab
										journey. A one way chauffeur-driven car rental saves you money vs having to pay for a
										round trip. It is also much more comfortable and convenient as you have a driver driving
										you in your dedicated car. <br/><br/><br/>
										<div></div>
										<div class="table-responsive">
											<table class="table table-striped table-bordered">
												<tr>
													<td><b>Vehicle Type</b></td>
													<td><b>Model Type</b></td>
													<td><b>Passenger Capacity</b></td>
													<td><b>Luggage Capacity</b></td>
													<td><b>Rate/km</b></td>
													<td><b>Fare</b></td>
												</tr>
												<?php
                                               
												//$cabType	 = VehicleTypes::model()->getCarType();
												//$flexiKey = array_search ('Flexxi Sedan', $cabType);
												$flexiKey	 = VehicleCategory::SHARED_SEDAN_ECONOMIC;
												$cabData = SvcClassVhcCat::getVctSvcList("allDetail");

												foreach ($allQuot as $cabKey => $baseQuot)
												{
													$cab = $cabData[$cabKey];
													if ($baseQuot->success)
													{

														if ($cabKey == VehicleCategory::SHARED_SEDAN_ECONOMIC)
														{
															$has_shared_sedan = 1;
														}
														?>
														<tr>
															<td class="col-md-2"><?=$cab['vct_label'] . "<br />(<small>" . $cab['scc_label'] . "</small>)"; ?></td>
															<td class="col-md-4"><?php echo $cab['vct_desc']; ?></td>
															<td class="col-md-2"><?php ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? print('Buy seats in a shared taxi. <a href="http://www.aaocab.com/GozoSHARE">Gozo FLEXXI</a>') : print($cab['vct_capacity'] . 'passengers and driver') ?> </td>
															<td class="col-md-2">
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
															<td class="col-md-1"><?php ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? print('Fixed prices per seat') : print('<i class="fa fa-inr"></i>' . $baseQuot->routeRates->ratePerKM) ?></td>
															<td class="col-md-1"><i class="fa fa-inr"></i><?php ($cab['scv_id'] == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? print($allQuot[$cabKey]->flexxiRates[1]['subsBaseAmount']) : print($baseQuot->routeRates->baseAmount); ?>
														</tr>
														<?php
													}
												}
												?>
											</table>
										</div>
										<div class="mb20">
                                            <a  href="/bknw/oneway/<?php echo strtolower($arr_url[0]); ?>/<?php echo strtolower($arr_url[1]); ?>" class="btn next-btn">Book Now</a>
											<!--<a href="/bknw/oneway/<?php echo strtolower($baseQuot->routeDistance->routeDesc[0]); ?>/<?php echo strtolower($baseQuot->routeDistance->routeDesc[1]); ?>" class="btn next-btn">Book Now</a>-->
                                         </div>

										<br/><br/>
									</div>
								</div>

								<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Which is the best Taxi service for <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?>?</h3>
								<div class="row">
									<div class="col-xs-12">
										There are many outstation taxi services that you can book either offline or online. Best
										is a relative term and it depends on what you prefer as a traveler. Most travelers prefer
										comfort, quality service at a reasonable price. Be careful when trying to haggle for the
										lowest priced or cheapest cab as you could open yourself to the risk of operators cutting
										corners in service and also over laying with hidden charges. <br/><br/><br/>
									</div>
								</div>
								<h3>Why is Gozo Cabs the best cab service for travel in India?</h3>
								<div class="row">
									<div class="col-xs-12">
										<p>Gozo is continuously focused on being and staying as India's best taxi service for inter-city or outstation car hire with a driver. 
											Gozo cabs is the best cab service for car hire from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>. Gozo is generally the cheapest in most regions as we keep our margins low and we keep our quality high by ensure that our cabs and providers are inspected regularly. At the time of onboarding, the taxi operators are whetted for proper licenses and their ability to meet our quality bar. We also provide ongoing training to our drivers.  But most importantly Gozo strives to be the best with our support and customer service. Gozo has great reviews on Google & <a href="http://www.tripadvisor.com/Attraction_Review-g304551-d9976364-Reviews-Gozo_Cabs-New_Delhi_National_Capital_Territory_of_Delhi.html">TripAdvisor</a>. 
											Gozo was started with the focus of simplifying car hire for outstation trips and we specialize in one way cabs, round trip journeys and even multi city trips. Car rentals in <?= $rmodel->rutFromCity->cty_name ?> or Car rentals in <?= $rmodel->rutToCity->cty_name ?> are also provided. We offer daily car rentals and also airport transfers in most cities across India.
											<br/><br/><br/>
										</p>						</div>
								</div>
								<?php
								if (isset($allQuot[$flexiKey]->flexxiRates))
								{
									?>
									<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Can I book shared taxi from  <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?>?</h3>
									<div class="row">
										<div class="col-xs-12">
											Yes, you can book <a href="http://www.aaocab.com/shared-taxi/<?=$arr_url[0] ?>-<?= $arr_url[1] ?> ">AC shared taxi and shuttle services from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?></a>. Gozo SHARE is a outstation shared taxi service which you can use to sell unused seats in the taxi that you have already booked with us or if you are looking to buy unused seats and carpool in a cab that someone else has booked. 
											Gozo SHARE is our way to help customers save even more money when you are traveling by Gozo Cabs. If your travel plans are firm, book a Sedan Cab and use the option to “Book now & sell your unused seats”. If someone else is selling unused seats in their car, then you can simply book the seats that are being offered on our website.
											<br/><br/><br/>
										</div>
									</div>
									<?php
								}
								?>
								<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">What are the package available for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> travel ?</h3>
								<div class="row">
									<div class="col-xs-12">
										There are many packages available for different types of interval as you want. <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> travel can also done in one to two days as well as you can also take long duration for travelling <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>. Taking <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> travel by road will give you an immersive experience which you will surely going to love. Check our <a href="http://www.aaocab.com/packages">package tour</a> page for more information. 
										<br/><br/><br/>
									</div>
								</div>
							</div>
						<?php
                       
						//$rut_url = $mpath_url;
                        
						if (!empty($arr_url))
						{
							$fromUrl	 = $arr_url[0];
							$toUrl = $arr_url[1];
						?>
							<div class="col-xs-12 col-sm-5 col-md-4 offset1">
								<div  id="map_canvas" style="height: 633px;"></div>
								<p></p>
								<div class="row mt20">
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img   class="lozad"  data-src="/images/cabs/car-etios.jpg" alt=""></div>
											<!--<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $rmodel->rutFromCity->cty_name)); ?>">Book cabs in <?= $rmodel->rutFromCity->cty_name; ?></a>-->
										<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Book cabs in <?= $rmodel->rutFromCity->cty_name; ?></a>
                                        </div>
									</div>
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img  class="lozad"  data-src="/images/cabs/car-etios.jpg" alt=""></div>
											<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Book cabs in <?= $rmodel->rutToCity->cty_name ?></a>
										</div>
									</div>
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img style="min-height: 80px"  class="lozad"  data-src="/images/cabs/tempo_9_seater.jpg" alt=""></div>
											<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Book Tempo Traveller in <?= $rmodel->rutFromCity->cty_name ?></a>
										</div>
									</div>
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img style="min-height: 80px"  class="lozad"  data-src="/images/cabs/tempo_12_seater.jpg" alt=""></div>
											<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Book Tempo Traveller in <?= $rmodel->rutToCity->cty_name ?></a>
										</div>
									</div>
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img style="min-height: 80px"  class="lozad"  data-src="/images/cabs/car-etios.jpg" alt=""></div>
											<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Outstation taxi rental in <?= $rmodel->rutFromCity->cty_name; ?></a>
										</div>
									</div>
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img style="min-height: 80px"  class="lozad"  data-src="/images/cabs/car-etios.jpg" alt=""></div>
											<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Outstation taxi rental in <?= $rmodel->rutToCity->cty_name ?></a>
										</div>
									</div>
									<? if ($rmodel->rutFromCity->cty_has_airport)
									{
									?>
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img style="min-height: 80px"  class="lozad"  data-src="/images/cabs/car-etios.jpg" alt=""></div>
											<a href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Airport transfer in <?= $rmodel->rutFromCity->cty_name ?></a>
										</div>
									</div>
									<? } ?>
									<? if ($rmodel->rutToCity->cty_has_airport)
									{
									?>
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img style="min-height: 80px"  class="lozad"  data-src="/images/cabs/car-etios.jpg" alt=""></div>
											<a href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Airport transfer in <?= $rmodel->rutToCity->cty_name ?></a>
										</div>
									</div>
									<? } ?>
									<? if ($model->is_luxury_from_city)
									{
									?>
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img style="min-height: 80px" class="lozad"  data-src="/images/car-bmw.jpg" alt=""></div>
											<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Luxury Car Rental in <?= $rmodel->rutFromCity->cty_name ?></a>
										</div>
									</div>
									<? } ?>
									<? if ($model->is_luxury_to_city)
									{
									?>
									<div class="col-xs-6 mb20">
										<div class="main_time border-blueline text-center main_time2">
											<div class="car_box2"><img style="min-height: 80px"  class="lozad"  data-src="/images/car-bmw.jpg" alt=""></div>
											<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Luxury Car Rental in <?= $rmodel->rutToCity->cty_name ?></a>
										</div>
									</div>
									<? } ?>
									<?php
									if ($has_shared_sedan == 1)
									{
										?>
										<div class="col-xs-6 mb20">
											<div class="main_time border-blueline text-center main_time2">
												<div class="car_box2"><img style="min-height: 80px"  class="lozad"  data-src="/images/cabs/car-etios.jpg" alt=""></div>
												<a href="/shared-taxi/<?php echo $mpath_url; ?>">Shared Sedan in <br/><?= $rmodel->rutFromCity->cty_name . '-' . $rmodel->rutToCity->cty_name ?></a>
											</div>
										</div>
								<?php } ?>
								</div>
							</div>
						<?php
						}
						?>

							<div id="desc1" class="col-xs-12 feature">
								<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Why aaocab for <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?>?</h3>
								<div class="row">
									<div class="col-xs-12">
										Our mission is to simplify inter-city travel
										Gozo means DELIGHT and JOY! Travelling with aaocab, you will be overjoyed. For
										we know what runs in your mind while booking an inter-city taxi: <br/><br/>

										<ul class="list">
											<li>You need to search for reliable inter-city taxi providers.</li>
											<li>You need to talk to at least 3-4 Car Operators, compare and get the one with the
												best price and reputation.</li>
											<li>You must decide if that operator will provide good service and honour time
												commitments.</li>
											<li>You must ensure if the car will be in good condition, comfortable, commercially
												licensed with all the requisite permits.</li>
											<li>Finally, you need to satisfy yourself if the driver will be well behaved,
												knowledgeable, and experienced.</li>
										</ul>

										Stop worrying and go Gozo!<br/><br/>
										Find the best prices, best services, well maintained & commercially licensed vehicles
										and courteous drivers with us! With our carefully & diligently selected network of reliable
										operators, we not only ensure easy bookings, quality service and best prices but also
										eliminate cancellations. All this with the ease of self-booking process through web and
										mobile app backed by a 24x7 tele helpline. So just book with us and allow us to delight
										you :) <br/><br/><br/>
									</div>
								</div>

								<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">What is the best time for renting a car with driver from 
			<?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?></h3>
								<div class="row">
									<div class="col-xs-12">
										When renting a car for <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?>, its best to book atleast 1-2 weeks ahead so you
										can get the best prices for a quality service. Last minute rentals are always expensive
										and there is a high chance that service would be compromised as even the taxi provider
										is limited to whatever vehicle is available at their disposal. <br/><br/><br/>
									</div>
								</div>
								<h3>What things to look for when you book an outstation cab from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?> ​​</h3>
								<div class="row">
									<div class="col-xs-12">
										<ol>
											<li>You should ensure that a commercial taxi (yellow license plate) is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.
											</li>								<li>Find if the route you will travel on has tolls. If yes, are tolls included in your booking quotation</li>
											<li>When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same
											</li>
											<li>Price is great but service is what matters. So try to focus on service at a reasonable price. When you go for the cheapest, you will ending up getting what you pay for.
											</li>
										</ol>
										<div>​<a href="http://www.aaocab.com/blog">Please read our blog on common taxi travel scams in India to learn more</a><br/><br/><br/></div>
									</div>
								</div>


								<?php
								if ($rmodel->rutToCity->cty_city_desc != "")
								{
									?>
									<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Little about <?= $rmodel->rutToCity->cty_name ?></h3>
									<div class="row">
										<div class="col-xs-12">
				<?= $rmodel->rutToCity->cty_city_desc ?>
											<br/><br/>
											<b>For More info about <?= $rmodel->rutToCity->cty_name ?>:</b> <br/>
											<?php
											$citylinks1 = CityLinks::model()->getCitylinks($model->bkg_to_city_id, 1);
											foreach ($citylinks1 as $citylink1)
											{
												?>
												<a href="<?= $citylink1->cln_url ?>" target="_blank"><?= $citylink1->cln_title ?></a> &nbsp; 
				<?php } ?>

											<!-- Agra Wikipedia Page​ ​Hotels in Agra​ ​Administration of Agra <br/>--> <br/>
											If you know of good resources about visit <?= $rmodel->rutToCity->cty_name ?>, please submit them <a href="javascript:void(0);" onclick="add_links(<?= $model->bkg_to_city_id ?>, 1);">here</a>. <br/><br/><br/>
										</div>
									</div>
									<?
									}
									?>
									<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">The Journey From <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?> ​​by Car</h3>
									<div class="row">
										<div class="col-xs-12">
											Let's be honest here. Who does not love road trips? We've been fascinated with road trips
											ever since ​<b>Dil Chahta Hai</b>. ​​Add ​<b>Zindagi Na Milegi Dobara</b> ​​to it and voila, you have what is
											known as ​<b>"Dream Trip"​​</b>. The beauty of ​<b>road trip</b> ​​cannot be captured in words. <br/><br/>
											<b>If You are Foodie , Then this is where you need to go​​ :</b> <br/>

											<?php
											$cityplaces1 = CityPlaces::model()->getCityplaces($model->bkg_to_city_id, 1);
											$ctr		 = 1;
											foreach ($cityplaces1 as $cityplace1)
											{
												if (($cityplace1->cpl_url) != '')
												{
													?>
													<a href="<?= $cityplace1->cpl_url ?>" target="_blank"><?= $cityplace1->cpl_places ?></a><?= (count($cityplaces1) == $ctr) ? "." : ", "; ?> &nbsp; 
													<?php
												}
												else
												{
													?>
													<?= $cityplace1->cpl_places ?><?= (count($cityplaces1) == $ctr) ? "." : ", "; ?> &nbsp; 
													<?php
												} $ctr++;
											}
											?>

											​​<!-- Vadapav Junction,Chicken Inn,Woodbox Cafe, Mad Monkey  <br/>--> <br/>
											If you know of good resources about places to eat in <?= $rmodel->rutToCity->cty_name ?>, please submit them <a href="javascript:void(0);" onclick="add_place(<?= $model->bkg_to_city_id ?>, 1);">here</a>. <br/><br/>
											<b>If You Love Our Culture & History ,​​ Then this is where you need to go:</b> <br/>

											<?php
											$cityplaces2 = CityPlaces::model()->getCityplaces($model->bkg_to_city_id, 2);
											$ctr		 = 1;
											foreach ($cityplaces2 as $cityplace2)
											{
												if (($cityplace2->cpl_url) != '')
												{
													?>
													<a href="<?= $cityplace2->cpl_url ?>" target="_blank"><?= $cityplace2->cpl_places ?></a><?= (count($cityplaces2) == $ctr) ? "." : ", "; ?> &nbsp; 
													<?php
												}
												else
												{
													?>
													<?= $cityplace2->cpl_places ?><?= (count($cityplaces2) == $ctr) ? "." : ", "; ?> &nbsp; 
													<?php
												} $ctr++;
											}
											?>

											<!-- Taj Mahal,Jama Masjid, Agra, Akbar's tomb,Mehtab Bagh,keetham Lake  <br/>--> <br/>
											If you have suggestions for history & culture resources about visiting <?= $rmodel->rutToCity->cty_name ?>, please submit them <a href="javascript:void(0);" onclick="add_place(<?= $model->bkg_to_city_id ?>, 2);">here</a>.<br/><br/>

											With GOZO Cab Driver you don't have to bother about anything. Just sit back and enjoy the beauty. <br/><br/><br/>
										</div>
									</div>
									<!--<div class="row">
										<div class="col-sm-12 link-panel">
											<a href="/car-rental/<?php echo strtolower($rmodel->rutFromCity->cty_name); ?>">Book cabs in <?= $rmodel->rutFromCity->cty_name; ?></a>
											<a href="/car-rental/<?php echo strtolower($rmodel->rutToCity->cty_name); ?>">Book cabs in <?= $rmodel->rutToCity->cty_name ?></a>
											<a href="/tempo-traveller-rental/<?php echo strtolower($rmodel->rutFromCity->cty_name); ?>">Book Tempo Traveller in <?= $rmodel->rutFromCity->cty_name ?></a>
											<a href="/tempo-traveller-rental/<?php echo strtolower($rmodel->rutToCity->cty_name); ?>">Book Tempo Traveller in <?= $rmodel->rutToCity->cty_name ?></a>
										</div>
									</div>-->

									<?php
									$citylinks2 = CityLinks::model()->getCitylinks($model->bkg_to_city_id, 2);
									if (count($citylinks2) > 0)
									{
										?>
										<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Popular Destinations from <?= $rmodel->rutToCity->cty_name ?></h3>
										<div class="row">
											<div class="col-xs-12">

												<?php
												foreach ($citylinks2 as $citylink2)
												{
													?>
													<div class="col-xs-6">
														<a href="<?= $citylink2->cln_url ?>" target="_blank"><?= $citylink2->cln_title ?></a> &nbsp; 
													</div>
					<?php } ?>


												<!-- Agra To Bharatpur Cab​ ​ Agra To Mathura Taxi Service<br/>
												Agra To New Delhi Taxi​ ​ Agra To Ratangarh Taxi Price<br/>
												Agra To Jaipur Taxi One Way​ ​Agra To Bareilly Car Hire<br/>
												Agra To Kanpur Taxi Round Trip ​ ​Agra To Dehradun Car Rental<br/>
												Agra To Gwalior Car Price​ ​Agra To Jhansi Cab Service<br/>
												Agra To Ghaziabad Taxi Rental ​ ​ Agra To Varanasi Taxi Cost<br/>
												Taxi Service From Agra To Ludhiana ​ ​Agra To Haridwar Cab Hire<br/>
												Cabs Agra To Allahabad Fare ​ ​ Agra To Rishikesh Taxi<br/>
												Agra To Lucknow Fare by Car​ ​Taxi Service From Agra To Fatehpur  <br/><br/><br/>--> <br/><br/>
											</div>
										</div>
				<?php } ?>
									Here are some more pages related to the <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> route. 
									<br><a href="/outstation-cabs/<?= $rmodel->rutFromCity->cty_name ?>">Outstation Car rental options and prices in <?= $rmodel->rutFromCity->cty_name ?></a><br>
									<a href="/outstation-cabs/<?= $rmodel->rutToCity->cty_name ?>"> Outstation taxi options and prices in <?= $rmodel->rutToCity->cty_name ?></a>
								</div>

							</div>
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
						<div class="col-xs-12">
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
										<td><b>Route</b></td>
										<td><b>Compact</b></td>
										<td><b>Sedan</b></td>
										<td><b>SUV</b></td>
										<td><b>Tempo traveler</b></td>
									</tr>
									<?php
									if (count($topRoutes) > 0)
									{
										foreach ($topRoutes as $top)
										{
											?>        
											<tr>
												<td><?= $top['from_city']; ?> to <?= $top['to_city']; ?></td>
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
			        //$('#map_canvas').css('height', $('#desc').height());
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
			        script.async = true;
			        script.type = 'text/javascript';
			        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
			                'callback=mapInitialize&key=<?= $api ?>';
			        document.body.appendChild(script);
			    }
			    //   window.onload = loadScript;


			    function add_links(city_id, cat) {

			        var href1 = '<?= Yii::app()->createUrl("city/citylinks") ?>';
			        jQuery.ajax({'type': 'GET', 'url': href1,
			            'data': {'cid': city_id, 'cat': cat},
			            success: function (data) {
			                box = bootbox.dialog({
			                    message: data,
			                    title: '',
			                    size: 'medium',
			                    onEscape: function () {
			                    }
			                });
			            }
			        });
			        return false;
			    }

			    function add_place(city_id, cat) {

			        var href1 = '<?= Yii::app()->createUrl("city/cityplaces") ?>';
        jQuery.ajax({'type': 'GET', 'url': href1,
            'data': {'cid': city_id, 'cat': cat},
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'medium',
                    onEscape: function () {
                    }
                });
            }
        });
        return false;
    }

    function book_now() {
        jQuery('#bookingSform').submit();
    }


    const obz = lozad(document.getElementById("map_canvas"), {
        load: function (el) {
            loadScript();
        }
    });
    obz.observe();

</script>
