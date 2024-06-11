
<?php
$oneway ="oneway";
$round ="roundtrip";
$multi ="multitrip";
?>
<section id="section2">
	<div id="desc" class="newline text-center">
		<h2 class="mt0"><?= $cmodel->cty_name; ?> Airport Transfers</h2>
	</div>
     <amp-selector class="tabs-with-flex " role="tablist">
			
			<div id="tab2"
				 role="tab"
				 aria-controls="tabpanel2"
					 option selected class="bgactive" >Airport Transfer</div>
            <div id="tab1"
				 role="tab"
				 aria-controls="tabpanel1"
				 option
				  class="bgnormal" ><a  href="/bknw/<?=$oneway?>/<?=strtolower($cmodel->cty_alias_path)?>">Outstation Cab</a></div>
            
	</amp-selector>
    
	<div class="col-xs-12">
		<div class="row mt40">
			<?php
			$minutes	 = $localPrice['tripTime'];
			$hours		 = floor($minutes / 60);
			$min		 = $minutes - ($hours * 60);
			$next_half	 = ceil($min / 30) * 30;
			if ($next_half == 60)
			{
				$next_half	 = 0;
				$hours		 = $hours + 1;
			}
			?>
			
			<div class="card-view card-hedding">
				<h4 class="h4">(within City) Airport transfer</h4>
				<div class="left-card">SEDAN
					<h1 class="mt0"><amp-img src="/images/rupees-amp2.png" alt="" width="10" height="14"></amp-img><?= $localPrice['sedan'] ?></h1>
				</div>
				<div class="right-card">SUV
					<h1 class="mt0"><amp-img src="/images/rupees-amp2.png" alt="" width="10" height="14"></amp-img><?= $localPrice['suv'] ?></h1>
				</div>
				<div class="wrraper text-center">
					<h4 class="m0 orange-color"><?= $localPrice['tripDistance'] ?>km/<?= $hours . "." . $next_half ?> hrs </h4> 
					<span class="gray-color">(30min complimentary waiting at airport)</span>
				</div>
                <div class="btn-book text-center pt20 pb20">
					<a href="/bknw/airport/<?= $cmodel->cty_name; ?>" >Book Now</a></div>
				</div>
			</div>
            <?php
			foreach ($topTenRoutes as $top)
			{
				$minutes	 = $top['duration'];
				$hours		 = floor($minutes / 60);
				$min		 = $minutes - ($hours * 60);
				$next_half	 = ceil($min / 30) * 30;
				if ($next_half == 60)
				{
					$next_half	 = 0;
					$hours		 = $hours + 1;
				}
				$top_city_arr[] = $top['to_city'];
				if ($top['destination_city_has_airport'] == 1)
				{
					$city_has_airport[] = $top['to_city'];
				}
				?>
				<div class="card-view card-hedding">
					<h4 class="h4"><?= $top['from_city']; ?> Airport to <?= $top['to_city']; ?>  transfer</h4>
					<div class="left-card">SEDAN
						<h1 class="mt0"><amp-img src="/images/rupees-amp2.png" alt="" width="10" height="14"></amp-img><?= $top['seadan_price'] ?></h1>
					</div>
					<div class="right-card">SUV
						<h1 class="mt0"><amp-img src="/images/rupees-amp2.png" alt="" width="10" height="14"></amp-img><?= $top['suv_price'] ?></h1>
					</div>
					<div class="wrraper text-center">
						<h4 class="m0 orange-color"><?= $top['distance'] ?>km/<?= $hours . "." . $next_half ?> hrs</h4> 
						<span class="gray-color">(30min complimentary waiting at airport)</span>
					</div>
                    <div class="wrraper text-center">
				<div class="btn-book text-center pt20 pb20">
					<a href="/bknw/oneway/<?= strtolower($airport_path); ?>/<?= strtolower($top['to_city']); ?>" >Book Now</a></div>
				</div>
			</div>
				<?php
			}
			?>
			
		</div>
	</div>

		<div class="row-amp">
			<div>
				<h4><?= $cmodel->cty_name; ?>  Airport Transfers, with Gozo’s airport transfer service</h4>
				<p>  
					<?php if ($cmodel->is_luxury_city)
				    { ?>
					When arriving in <?= $cmodel->cty_name; ?>
						<div class="main_time border-blueline text-center main_time2">
							<amp-img width="120px" height="60px" class="lozad" src="/images/car-bmw.jpg" alt="Airport Transfers1" ></amp-img>
						<a href="<?=Yii::app()->createAbsoluteUrl("/amp/Luxury-car-rental/".strtolower($cmodel->cty_alias_path));?>">Luxury Car Rental in <?= $cmodel->cty_name ?></a>
						</div>
				   <?php } ?>
			</div>
            Gozo Offers a hassle-free airport transfer cabs booking option at a cheaper price from <?= $cmodel->cty_name; ?> Airport to anywhere you want, be it in intercity, one-way or outstation or anything Gozo covers it all by just clicking Gozo app from your smartphone or from aaocab official website. Book online the cheapest and best Airport cab booking service from the airport to anywhere in India with Gozo. 
           <p>Airport, why wait in long lines for taxi or try to figure out the local transport options when you can have your driver waiting at the airport to pick you up. We can have you driver be waiting for you at arrivals, help you with your luggage and answer any questions you may have about your journey. All our our airport pickup drivers are local and in many cases, can understand English. You will receive the phone contact details of your driver my email and SMS, so you can be worry-free. <a href="https://support.aaocab.com">Gozo’s 24x7 customer support</a> is available by phone or chat should you need any assistance.</p>
		   <div class="full-width-img">
			<amp-img width="290px" height="210px" class="lozad" src="/images/airport-transfers-img2.png?v1.1" alt="Airport Transfers2" ></amp-img>
		   </div>
			   <h4 class="text-center mt10">Gozo’s Airport transfers for <?= $cmodel->cty_name; ?>  - Lowest cost & best service</h4>
			   <div class="thumb-box">
				   <div class="advance-panel"><amp-img src="/images/img6.png?v=2.02" alt="Meet & greet" width="100" height="100"></amp-img></div>
				   <h4 class="m0 mt10">Meet & greet</h4>
				   Your driver will waiting to meet you no matter what happens
			   </div>
			   <div class="thumb-box">
				   <div class="advance-panel"><amp-img src="/images/img7.png?v=2.02" alt="Value" width="100" height="100"></amp-img></div>
				   <h4 class="m0 mt10">Value</h4>
				   Enjoy a high-quality transfer experience at surprisingly low prices
			   </div>
			   <div class="thumb-box">
				   <div class="advance-panel"><amp-img src="/images/img8.png?v=2.02" alt="Speedy" width="100" height="100"></amp-img></div>
				   <h4 class="m0 mt10">Speedy</h4>
				   No queues, no delays - we'll get you to your destination quickly
			   </div>
			   <div class="thumb-box">
				   <div class="advance-panel"><amp-img src="/images/img9.png?v=2.02" alt="Door-to-Door" width="100" height="100"></amp-img></div>
				   <h4 class="m0 mt10">Door-to-Door</h4>
				   For complete peace of mind we'll take you directly to your hotel door
			   </div>
		   
	<?php
	if(!empty($cmodel->cty_name))
	{
		$airport = $cmodel->getAirportByCity($cmodel->cty_name);
	}
	
	?>
	<br></br>
	<!--<div class="col-xs-12 col-sm-10 col-md-8 marginauto float-none mb30">
        <div class="mb20"><a href="http://www.aaocab.com/bknw" class="btn next-btn">Book Now</a></div>
    </div>-->
		   <div class="col-xs-12"><h3>Flights delayed?</h3>
		<p>Don’t worry our drivers are tasked with tracking your flight and will plan to be at the airport when you’re flight arrives. Just remember to let us know of your flight details.</p>
	</div>
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12">
					<div class="pull-right book-with">
						<h3>Why book with us</h3>
						<ul>
							<li><i class="fa fa-thumbs-up"></i> Excellent reputation</li>
							<li><i class="fa fa-credit-card"></i> No credit card fees</li>
							<li><i class="fa fa-road"></i> Tolls included</li>
							<li><i class="fa fa-check-circle"></i> Free cancellation</li>
							<li><i class="fa fa-user"></i> Professional drivers</li>
                            <li><i class="fa fa-thumbs-up"></i> Hassle-free booking</li> 
						</ul>
					</div>
					<h3>Gozo is available to you for your travel to and beyond <?= $cmodel->cty_name; ?> </h3>
					Gozo is with you on your travel to or From <?= $cmodel->cty_name; ?>  and  throughout India. Our cabs are available for simple one-way intercity and outstation drops to cities like  
						<?php
						foreach ($top_city_arr as $top_city)
						{
							//echo $top_city;
							echo '<a href="/book-taxi/' . $cmodel->cty_name . '-' . strtolower($top_city) . '">' . $top_city . '</a>, ';
						}
						?>
					   Top 10 cities and many more. You can also book transfers straight to <?= $cmodel->cty_name; ?>  Airport from 
						<?php
						foreach ($top_city_arr as $top_city)
						{
							//echo $top_city;
							echo '<a href="/book-taxi/' . strtolower($top_city) . '-' . $cmodel->cty_name . '">' . $top_city . '</a>, ';
						}
						?> Travel to and from <?= $cmodel->cty_name; ?>  can be done by booking your one-way trip, round-trip, customized multi-city itinerary in a AC sedan, SUVs or for larger groups to take tempo travellers (minibus) in <?= $cmodel->cty_name; ?> . Backpackers or the adventurous travellers may option for booking <a href="http://www.aaocab.com/GozoSHARE">shared outstation cabs</a></p>
						<p>Gozo can be on stand-by for you throughout your travels in India. Booking your chauffeur service with Gozo couldn’t be easier. Simply visit Gozo’s website or use the Gozo mobile app to book from your smartphone.
						</p>
						<?php
						//$city_has_airport = $top_city_arr;
						if (count($city_has_airport) > 0)
						{
							$count=0;
							?>                                       
							<p>Gozo’s chauffeur driven airport pickup and dropoff is available for  
								<?php
								foreach ($city_has_airport as $top_city)
								{
									//echo $top_city;
									if($count == (count($city_has_airport)-1))
									{
									echo '<a href="/airport-transfer/' . strtolower($top_city) . '">' . $top_city . ' Airport </a> . ';
									}
									else
									{
										echo '<a href="/airport-transfer/' . strtolower($top_city) . '">' . $top_city . ' Airport </a>, ';
										$count ++;
									}
								}
								?> 
								 You can take advantage of Gozo’s nationwide reach and network by booking our intercity taxi or taking a packaged trip anywhere in India.</p>

						<?php } ?>
							<h3>Luxury chauffeur driven car rentals and limos in <?= $cmodel->cty_name; ?> </h3>
						<p>You can also book luxury vehicles of various classes and brands like Hondas, Toyotas, Audis, BMWs, Mercedes in Delhi for airport transfers or day-based rentals. <a href="mailto:quotations@aaocab.in?subject=Luxury+Vehicle+rental+in+<?= $cmodel->cty_name; ?>">Simply contact us to check for availability and pricing for Luxury vehicles. </p>
				</div>
				<div class="col-xs-12 col-sm-4"></div>
			</div>
		</div>
		</div><!-- row amp end-->
	</div>
</section>
