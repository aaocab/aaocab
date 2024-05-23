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
?>


<section id="section2">
	<div id="desc" class="col-xs-12 col-sm-7 col-md-8 feature">
		<div class="newline">
			<h3> Tempo traveller fares for rentals in and around <?= $cmodel->cty_name; ?></h3>
		</div>
          
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
						?> 	
						<section <?= $class ?>>
							<h4><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</h4>
							<div class="accordion-style2">
								<div class="table-view flex">
									<div class="table-view-left">Tempo traveler(9 seater)</div> 
									<div class="table-view-right"><?= ($top['tempo_9seater_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['tempo_9seater_price'] : '<amp-img src="/images/call.png" alt="" width="20" height="20">'; ?> </div>
								</div>
								<div class="table-view flex">
									<div class="table-view-left">Tempo traveler(12 seater)</div> 
									<div class="table-view-right"><?= ($top['tempo_12seater_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['tempo_12seater_price'] : '<amp-img src="/images/call.png" alt="" width="20" height="20">'; ?> </div>
								</div>
								<div class="table-view flex">
									<div class="table-view-left">Tempo traveler(15 seater)</div> 
									<div class="table-view-right"><?= ($top['tempo_15seater_price'] > 0) ? '<i class="fa fa-inr"></i>' . $top['tempo_15seater_price'] : '<amp-img src="/images/call.png" alt="" width="20" height="20">'; ?></amp-img></a> </div>
								</div>
								<div class="table-view flex">
									<div class="table-view-left">&nbsp;</div> 
									<div class="table-view-right"><div class="btn-book text-right pt10"><a href="<?=Yii::app()->createAbsoluteUrl("/amp/book-taxi/".strtolower($top['from_city_alias_path']).'-'.strtolower($top['to_city_alias_path']));?>" >Book</a></div> </div>
								</div>
							</div>
						</section>
						<?php
					}
				}
				?>
			</amp-accordion>
        </div>
       <div class="wrraper mt20">
          <h3> Best Tempo Traveller on rent in <?= $cmodel->cty_name; ?> for lowest prices </h3>
        <p>When Gozo's compact, sedan and SUV category of vehicles is a perfect fit for customers looking to hire outstation cabs, day rentals or airport transfers we find that larger groups can rent larger vehicles like minivans, tempo travellers or buses. In order to address the needs of large families traveling together or business groups attending company meetings or events Gozo provides Budget Tempo Traveller rentals at cheapest prices in all top cities across India.
        <p>With Gozo you can hire tempo traveller from <?=$cmodel->cty_name?> to nearby cities  <?=$city_str;?> and many more. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available to book online 24x7x365 . Gozo uses local operators in <?=$cmodel->cty_name?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. You can also rent tempo travellers for local sightseeing trips and tours in or around the <?=$cmodel->cty_name?>. Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?=$cmodel->cty_name?>.</p>
       </div>
		<div class="wrraper mt20">

			<div class="main_time border-blueline text-center">
				<div class="car_box2">
					<amp-img width="125px" height="63px" class="lozad" src="/images/cabs/car-etios.jpg" ></amp-img>
				</div>
				<a href="<?=Yii::app()->createAbsoluteUrl("/amp/car-rental/".strtolower($top['from_city_alias_path']));?>">Book car rental in <?= $cmodel->cty_name; ?></a>
			</div>
			<div id="desc" class="main_time text-center">
				<div class="car_box2">
					<amp-img width="125px" height="63px" class="lozad" src="/images/cabs/car-etios.jpg" ></amp-img>
				</div>
				<a href="<?=Yii::app()->createAbsoluteUrl("/amp/outstation-cabs/".strtolower($top['from_city_alias_path']));?>">Book outstation taxi rental in <?= $cmodel->cty_name; ?></a>
			</div>
		</div>
		<div class="wrraper mt20">
			<h3 class="m0">WHY HIRE TEMPO TRAVELLER FROM GOZO IN <?= strtoupper($cmodel->cty_name); ?></h3>
			<p>Gozo is india’s leader in tempo traveller rentals and provides services in over 3000 towns & cities along over 50,000 intercity routes in the nation.</p>
<p>When you rent a tempo traveller from Gozo you get transparent billing, low prices and 24x7 support along with our nationwide reach. 
You can rent tempo traveller for round trips and If you have any special requirements like deluxe or luxury tempo travellers you can 
always contact our customer service helpdesk. Gozo can arrange 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 24, 26, 27 seaters 
Tempo Travellers on Hire in our fleet across India.</p>
			<p>With a fleet of hundreds of tempo travellers nationwide, we promise to provide you with an unmatched variety of both luxury and non-luxury tempo traveller rentals.
 Our tempo travellers come equipped with pushback or recliner seats with ample leg and moving spaces that ensure a comfortable travel experience. 
 Other common amenities like luggage storage, water bottle, blankets, charging point, reading light, central TV, etc. may also be provided (varies from vehicle to vehicle). 
 All our tempo travellers available on rent in <?= $cmodel->cty_name; ?> are safety compliant.</p>
<p>In addition to tempo travellers, Gozo provides various services in <?= $cmodel->cty_name; ?> including one way taxi drops or short roundtrips to nearby cities. 
You can also book cabs for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns.
For those not from India, Tempo traveller is the brand for a Minibus and is available generally in 3 different type of seating configurations with various capacity. 
We provide 9 seater, 12 seater or 15 seater Tempo travellers.</p>
<p>Due to the specific and less frequent nature of these requirements we request customers to make their reservations as early as at least 10 days in advance. This provides us sufficient advance notice to arrange a vehicle for your journey.</p>
<h3>HIRE A TEMPO TRAVELLER ONLINE WITH GOZO CABS </h3>
<p>Gozo's booking and billing process is <a href="https://www.gozocabs.com/blog/billing-transparency/">completely transparent</a> and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.
With Gozo you can book a taxi anywhere in India. Our services are top rated in almost all cities across India.
</p>

		</div>
	</div>
</section>