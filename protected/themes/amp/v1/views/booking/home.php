<?php
if (isset($jsonStructureProductSchema) && trim($jsonStructureProductSchema) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonStructureProductSchema; ?>
	</script>
<?php } ?><?php
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
$has_shared_sedan	 = 0;
$rut_url			 = $aliash_path;
$arr_url			 = explode("-", $rut_url);
if ($type == 'route')
{
	/* @var $rmodel Route */
	?>
	<section id="section2">

		<div id="desc" class="feature">
			<div class="newline pt0">
				<h1 class="text-center mt0 font18">Travel from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?></h1>
				<h4 class="mb0 text-center">
					<?php
					if ($ratingCountArr['ratings'] > 0)
					{
						?>
						<a href="<?= Yii::app()->createUrl('route-rating/' . $rmodel->rut_name); ?>" style="color: #fff;">
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
				</h4>

	<!--					<h4 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi"><?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> ​​
	   Car Rental Prices & Options<br>
	</h4>-->
				<div class="edit-box"><a href="/"><amp-img src="/images/pencil.svg" alt="" width="25" height="25px"></amp-img></a></div>
			</div>

			<input type="hidden" name="rutId" value="<?= $rmodel->rut_id ?>">



			<amp-selector class="tabs-with-flex" role="tablist">
				<div id="tab1"
					 role="tab"
					 aria-controls="tabpanel1"
					 option selected
					 >Outstation Cab</div>
				<div id="tabpanel1"
					 role="tabpanel"
					 aria-labelledby="tab1">
					<div class="tab-sub-menu active"><a href="/bknw/oneway/<?= strtolower($arr_url[0]) ?>/<?= strtolower($arr_url[1]); ?>">One Way</a></div>
					<div class="tab-sub-menu"><a href="/bknw/multitrip/<?= strtolower($arr_url[0]) ?>/<?= strtolower($arr_url[1]); ?>">Round Trip/Multi Way</a></div>
					<div class="row">						
						<div></div>

						<?php
						$flexiKey	 = VehicleCategory::SHARED_SEDAN_ECONOMIC;
						$cabData	 = SvcClassVhcCat::getVctSvcList("allDetail");
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
								<div class="card-view">
									<div class="title-panel"><?= $cab["scv_label"]; ?></div>

									<div class="card-view-left">

										<amp-img src="/<?= $cab['vct_image']; ?>" alt="" width="130" height="80" ></amp-img>

										<p class="m0"><?php echo $cab['vct_desc']?></p>
									</div>
									<div class="card-view-right">
										<span class="card-text1">Base Fare</span><br>
										<span class="card-text2"><amp-img src="/images/rupees-amp.png" alt="" width="16" height="22"></amp-img><?php ($cabKey == $flexiKey) ? print($allQuot[$cabKey]->flexxiRates[1]['subsBaseAmount']) : print($baseQuot->routeRates->baseAmount); ?></span><br>
										<div class="btn-book mt30"><a href="<?php echo Yii::app()->createAbsoluteUrl('/bknw/oneway/' . strtolower($rmodel->rutFromCity->cty_name) . '/' . strtolower($rmodel->rutToCity->cty_name)); ?>" >Book</a></div>
									</div>
									<div class="card-view-mid flex">
										<ul>
											<li><span><?php ($cabKey == $flexiKey) ? print('1 </span><br>Seat') : print($cab['vct_capacity'] . '+1</span><br> Seats + Driver') ?> </li>
											<li><span><?php
													if ($cabKey == $flexiKey)
													{
														echo '1 </span><br>bag';
													}
													else
													{
														if ($cab['vct_big_bag_capacity'] > 0)
														{
															echo $cab['vct_big_bag_capacity'];
														}
														if ($cab['vct_small_bag_capacity'] > 0)
														{
															echo '+' . $cab['vct_small_bag_capacity'];
														}
														echo '</span><br>Big + Small bags';
													}
													?></li>
											<li><span>AC</span><br>&nbsp;<br>&nbsp;</li>
											<li><span><?php ($cabKey == $flexiKey) ? print('Fixed') : print('<amp-img src="/images/rupees-amp2.png" alt="" width="10" height="14"></amp-img>' . $baseQuot->routeRates->ratePerKM) ?></span><br>Rate/km</li>
										</ul>
									</div>

									<div class="note-panel">Note: Ext. Chrg. After <?= $baseQuot->routeDistance->quotedDistance ?> Kms. as applicable.</div>
								</div>
								<?php
							}
						}
						?>

						<div class="mb20 mt20">

						</div>
					</div> 
				</div>
				<div id="tab2"
					 role="tab"
					 aria-controls="tabpanel2"
					 option >Local Cab</div>
				<div id="tabpanel2"
					 role="tabpanel"
					 aria-labelledby="tab2">
					<div class="tab-sub-menu active"><a href="/bknw/dayrental/<?= strtolower($arr_url[0]);?>">Day Rental</a></div>
					<div class="tab-sub-menu"><a href="/bknw/airport/<?= strtolower($arr_url[0]) ?>/<?= strtolower($arr_url[1]); ?>">Airport Transfer</a></div>
					<div class="row">						
						<div></div>

						<?php
						$flexiKey	 = VehicleCategory::SHARED_SEDAN_ECONOMIC;
						$cabData	 = SvcClassVhcCat::getVctSvcList("allDetail");
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
								<div class="card-view">
									<div class="title-panel"><?= $cab["scv_label"]; ?></div>

									<div class="card-view-left">

										<amp-img src="/<?= $cab['vct_image']; ?>" alt="" width="130" height="80" ></amp-img>

										<p class="m0"><?php echo $cab['vct_desc']; ?></p>
									</div>
									<div class="card-view-right">
										<span class="card-text1">Base Fare</span><br>
										<span class="card-text2"><amp-img src="/images/rupees-amp.png" alt="" width="16" height="22"></amp-img><?php ($cabKey == $flexiKey) ? print($allQuot[$cabKey]->flexxiRates[1]['subsBaseAmount']) : print($baseQuot->routeRates->baseAmount); ?></span><br>
										<div class="btn-book mt30"><a href="<?php echo Yii::app()->createAbsoluteUrl('/bknw/oneway/' . strtolower($rmodel->rutFromCity->cty_name) . '/' . strtolower($rmodel->rutToCity->cty_name)); ?>" >Book</a></div>
									</div>
									<div class="card-view-mid flex">
										<ul>
											<li><span><?php ($cabKey == $flexiKey) ? print('1 </span><br>Seat') : print($cab['vct_capacity'] . '+1</span><br> Seats + Driver') ?> </li>
											<li><span><?php
													if ($cabKey == $flexiKey)
													{
														echo '1 </span><br>bag';
													}
													else
													{
														if ($cab['vct_big_bag_capacity'] > 0)
														{
															echo $cab['vct_big_bag_capacity'];
														}
														if ($cab['vct_small_bag_capacity'] > 0)
														{
															echo '+' . $cab['vct_small_bag_capacity'];
														}
														echo '</span><br>Big + Small bags';
													}
													?></li>
											<li><span>AC</span><br>&nbsp;<br>&nbsp;</li>
											<li><span><?php ($cabKey == $flexiKey) ? print('Fixed') : print('<amp-img src="/images/rupees-amp2.png" alt="" width="10" height="14"></amp-img>' . $baseQuot->routeRates->ratePerKM) ?></span><br>Rate/km</li>
										</ul>
									</div>

									<div class="note-panel">Note: Ext. Chrg. After <?= $baseQuot->routeDistance->quotedDistance ?> Kms. as applicable.</div>
								</div>
								<?php
							}
						}
						?>

						<div class="mb20 mt20">

						</div>
					</div> 
				</div>
			</amp-selector>





			<div class="page-content text-center">
				<a href="https://play.google.com/store/apps/details?id=com.gozocabs.client"><amp-img src="/images/app-google.png" alt="" width="137" height="49"></amp-img></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8"><amp-img src="/images/app-store.png" alt="" width="137" height="49"></amp-img></a>
			</div>





			<div class="page-content">
				<h2 title="Best way for  <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>">
					Best way for  <?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> 
				</h2>
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

				<br/><br/>

				<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Distance and time for travel between ​​<?= $rmodel->rutFromCity->cty_name ?> and <?= $rmodel->rutToCity->cty_name ?></h3>
				Distance from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?> ​by car is around <?= $model->bkg_trip_distance; ?> ​Kms. Estimated travel time traveling
				from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?> ​by a dedicated car is ​<?= floor(($rmodel->rut_estm_time / 60)); ?> hours<?php
			if (($rmodel->rut_estm_time % 60) > 0)
			{
				?> and <?= ($rmodel->rut_estm_time % 60); ?> minutes<?php } ?>. Please budget
				between 30-60 Minutes for delay in traffic. <br/><br/>


				<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Which is the best Taxi service for <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?>?</h3>
				There are many outstation taxi services that you can book either offline or online. Best
				is a relative term and it depends on what you prefer as a traveller. Most travellers prefer
				comfort, quality service at a reasonable price. Be careful when trying to haggle for the
				lowest priced or cheapest cab as you could open yourself to the risk of operators cutting
				corners in service and also over laying with hidden charges. <br/><br/>
				<?php
				if (isset($allQuot[$flexiKey]->flexxiRates))
				{
					?>
					<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Can I book shared taxi from  <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?>?</h3>
					Yes, you can book AC <a href="http://www.aaocab.com/shared-taxi/<?= $arr_url[0] ?>-<?= $arr_url[1] ?>">shared taxi and shuttle services from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>.</a> Gozo SHARE is a outstation shared taxi service which you can use to sell unused seats in the taxi that you have already booked with us or if you are looking to buy unused seats and carpool in a cab that someone else has booked. 
					<a href="http://www.aaocab.com/GozoSHARE">Gozo SHARE</a> is our way to help customers save even more money when you are traveling by Gozo Cabs. 
					If your travel plans are firm, book the taxi and use the FLEXXI SHARE option to sell your unused seats. If someone else is selling unused seats in their car, then you can simply book the seats that are offered on the website.
					<br/>
		<?php
	}
	?>
			</div>
		</div>
		<?php
		if (!empty($arr_url))
		{
			$fromUrl = $arr_url[0];
			$toUrl	 = $arr_url[1];
			?>
			<div class="wrraper mt20">
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130px" height="80px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
					<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Book cabs in <?= $rmodel->rutFromCity->cty_name; ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130px" height="80px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
					<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Book cabs in <?= $rmodel->rutToCity->cty_name ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="150px" height="80px" class="lozad" src="/images/cabs/tempo_9_seater.jpg" alt=""></amp-img></div>
					<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Book Tempo Traveller in <?= $rmodel->rutFromCity->cty_name ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130px" height="80px" class="lozad" src="/images/cabs/tempo_12_seater.jpg" alt=""></amp-img></div>
					<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Book Tempo Traveller in <?= $rmodel->rutToCity->cty_name ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130px" height="80px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
					<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Outstation taxi rental in <?= $rmodel->rutFromCity->cty_name; ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130px" height="80px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
					<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Outstation taxi rental in <?= $rmodel->rutToCity->cty_name ?></a>
				</div>
				<?
				if ($rmodel->rutFromCity->cty_has_airport)
				{
					?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="150px" height="80px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
						<a href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Airport transfer in <?= $rmodel->rutFromCity->cty_name ?></a>
					</div>
				<? } ?>
		<?
		if ($rmodel->rutToCity->cty_has_airport)
		{
			?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="130px" height="80px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
						<a href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Airport transfer in <?= $rmodel->rutToCity->cty_name ?></a>
					</div>
		<? } ?>
		<?
		if ($model->is_luxury_from_city)
		{
			?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="130px" height="80px" class="lozad" src="/images/car-bmw.jpg" alt=""></amp-img></div>
						<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $rmodel->rutFromCity->cty_name)); ?>">Luxury Car Rental in <?= $rmodel->rutFromCity->cty_name ?></a>
					</div>
		<? } ?>
		<?
		if ($model->is_luxury_to_city)
		{
			?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="130px" height="80px" class="lozad" src="/images/car-bmw.jpg" alt=""></amp-img></div>
						<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $rmodel->rutToCity->cty_name)); ?>">Luxury Car Rental in <?= $rmodel->rutToCity->cty_name ?></a>
					</div>
		<? } ?>
				<?php
				if ($has_shared_sedan == 1)
				{
					?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="130px" height="80px" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
						<a href="/shared-taxi/<?php echo $mpath_url; ?>">Shared Sedan in <br/><?= $rmodel->rutFromCity->cty_name . '-' . $rmodel->rutToCity->cty_name ?></a>
					</div>
		<?php } ?>
			</div>
		<?php
	}
	?>

		<div id="desc1" class="page-content">
			<h3 class="mt20 mb0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Why use Gozo Cabs from  <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?>?</h3>
			Our mission is to simplify inter-city travel
			Gozo means DELIGHT and JOY! Travelling with Gozocabs, you will be overjoyed. For
			we know what runs in your mind while booking an inter-city taxi: <br/>

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

			<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">What is the best time for renting a car with driver from <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?></h3>
			When renting a car for <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?>, its best to book atleast 1-2 weeks ahead so you
			can get the best prices for a quality service. Last minute rentals are always expensive
			and there is a high chance that service would be compromised as even the taxi provider
			is limited to whatever vehicle is available at their disposal. <br/><br/>

			<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">What things to look for when you book an outstation cab from<?= $rmodel->rutFromCity->cty_name ?>
				to <?= $rmodel->rutToCity->cty_name ?> ​​or any outstation route</h3>

			<ol>
				<li>You should ensure that a commercial taxi (yellow license plate) is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</li>
				<li>Find if the route you will travel on has tolls. If yes, are tolls included in your booking quotation</li>
				<li>When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same</li>
				<li>Price is great but service is what matters. So try to focus on service at a reasonable price. When you go for the cheapest, you will ending up getting what you pay for.</li>
			</ol>

			​<a href="/blog">Please read our blog on taxi travel scam​</a> to learn more <br/><br/>
	<?php
	if ($rmodel->rutToCity->cty_city_desc != "")
	{
		?>
				<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Little about <?= $rmodel->rutToCity->cty_name ?></h3>

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
				<br/>
		<?php
	}
	?>
			<br/><br/>
			<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">The Journey From <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?> ​​by Car</h3>
			Let's be honest here. Who does not love road trips? We've been fascinated with road trips
			ever since ​<b>Dil Chahta Hai</b>. ​​Add ​<b>Zindagi Na Milegi Dobara</b> ​​to it and voila, you have what is
			known as ​<b>"Dream Trip"​​</b>. The beauty of ​<b>road trip</b> ​​cannot be captured in words. <br/><br/>


			<?php
			$cityplaces1 = CityPlaces::model()->getCityplaces($model->bkg_to_city_id, 1);
			$ctr		 = 1;
			foreach ($cityplaces1 as $cityplace1)
			{
				if (($cityplace1->cpl_url) != '')
				{
					?><b>If You are Foodie , Then this is where you need to go​​ :</b> <br/>
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

			​​<br/>


			<?php
			$cityplaces2 = CityPlaces::model()->getCityplaces($model->bkg_to_city_id, 2);
			$ctr		 = 1;
			foreach ($cityplaces2 as $cityplace2)
			{
				if (($cityplace2->cpl_url) != '')
				{
					?><b>If You Love Our Culture & History ,​​ Then this is where you need to go:</b> <br/>
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

			<br/>
			With GOZO Cab Driver you don't have to bother about anything. Just sit back and enjoy the beauty. <br/><br/><br/>
			<?php
			$citylinks2 = CityLinks::model()->getCitylinks($model->bkg_to_city_id, 2);
			if (count($citylinks2) > 0)
			{
				?>
				<h3 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Popular Destinations from <?= $rmodel->rutToCity->cty_name ?></h3>


				<?php
				foreach ($citylinks2 as $citylink2)
				{
					?>
					<div class="col-xs-6">
						<a href="<?= $citylink2->cln_url ?>" target="_blank"><?= $citylink2->cln_title ?></a> &nbsp; 
					</div>
		<?php } ?>

				<br/><br/>
	<?php } ?>
		</div>

	</section>
	<?php
}
?>