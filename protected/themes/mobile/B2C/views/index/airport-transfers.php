<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="container content-style mb0 mobile-type tab-styles">
	<div class="above-overlay">
		<div class="tab-style tabs pt10">
			<div class="t-style" data-active-tab-pill-background="bg-green-dark">
				<a href="#" data-tab-pill="tab-pill-1a" class="devPrimaryTab3 mainTab active" style="width:49%;float:left;">Airport Transfer</a>
				<a href="#" data-tab-pill="tab-pill-5a" class="devPrimaryTab5 mainTab" style="width:49.6%;">Outstation Cab</a>

			</div>
			<div class="tab-pill-content p10">
				<div class="tab-item devSecondaryTab3" id="tab-pill-5a" style="display:none;">
					<div class="inner-tab">
						<a href="/bknw/oneway/<?php echo strtolower($cmodel->cty_alias_path) ?>" data-sub-tab="tab-pill-1a" class="sub-tab  active-tab-pill-button active" style="width: calc(48% - 5px);">One-Way</a>
						<a href="/bknw/multitrip/<?php echo strtolower($cmodel->cty_alias_path) ?>" data-sub-tab="tab-pill-4a" class="sub-tab" style="width: calc(48% - 5px);">Round Trip or<br/>Multi Way</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="overlay bg-gray-light2 opacity-90"></div>
</div>

<?php
if (empty($topTenRoutes))
{
	echo '<div class="content-boxed-widget p5">No Airport available</div>';
	goto end;
}
?>

<?php
$cabData	 = VehicleTypes::model()->getMasterCarDetails();
$sedanCab	 = $cabData[1];
$suvCab		 = $cabData[2];
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
<div class="content-boxed-widget p5">
	<div class="one-half text-center">
		<img src="/<?= $sedanCab['vct_image']; ?>" alt="" width="150" class="preload-image responsive-image bottom-5">
		<span class="font-18"><b class="color-blue2">SEDAN</b> <span>&#x20b9</span><?= $localPrice['sedan'] ?></span>
	</div>
	<div class="one-half last-column text-center">
		<img src="/<?= $suvCab['vct_image']; ?>" alt="" width="150" class="preload-image responsive-image bottom-5">
		<span class="font-18"><b class="color-green3-dark">SUV</b> <span>&#x20b9</span><?= $localPrice['suv'] ?></span><sup class="font-16">*</sup> 
	</div>
	<div class="clear"></div>
	<div class="content top-10 p10 bottom-0 line-height16">
		<span class="font-14 uppercase"><b><?= $cmodel->cty_name; ?>(within City) Airport transfer</b></span><br>
		<?php //echo $cab['vht_model'];  ?>
		<span class="font-12 color-gray-dark"> <?= $localPrice['tripDistance'] ?>km/<?= $hours . "." . $next_half ?> hrs (30min complimentary waiting at airport)</span>
	</div>
    <div class="content top-10 p10 bottom-0 text-center"> <a href="/bknw/airport/<?= $cmodel->cty_name; ?>" class="uppercase btn-green-blue airportbook text-center" value="Book Now">Book Now</a></div>
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
	<input type="hidden" name="step" value="1">
	<div class="content-boxed-widget p5">
		<div class="one-half text-center">
			<img src="/<?= $sedanCab['vct_image']; ?>" alt="" width="150" class="preload-image responsive-image bottom-5">
			<span class="font-18"><b class="color-blue2">SEDAN</b> <span>&#x20b9</span><?= $top['seadan_price'] ?></span>
		</div>
		<div class="one-half last-column text-center">
			<img src="/<?= $suvCab['vct_image']; ?>" alt="" width="150" class="preload-image responsive-image bottom-5">
			<span class="font-18"><b class="color-green3-dark">SUV</b> <span>&#x20b9</span><?= $top['suv_price'] ?><sup class="font-16">*</sup></span> 
		</div>
		<div class="clear"></div>
		<div class="content top-10 p10 bottom-0 line-height16">
			<span class="font-14 uppercase"><b><?= $top['from_city']; ?> Airport to <?= $top['to_city']; ?> transfer</b></span><br>
			<?php //echo $cab['vht_model'];  ?>
			<span class="font-12 color-gray-dark"> <?= $top['distance'] ?>km/<?= $hours . "." . $next_half ?> hrs (30min complimentary waiting at airport)</span>
		</div>
           <div class="content top-10 p10 bottom-0 text-center"> <a href="/bknw/oneway/<?= strtolower($airport_path); ?>/<?= strtolower($top['to_city']); ?>" class="uppercase btn-green-blue airportbook text-center" value="Book Now">Book Now</a></div>

	</div>
	<?php
}
?>
<div class="content-boxed-widget">
	

	<p class="font-14 bottom-10"><b><?= $cmodel->cty_name; ?>  Airport Transfers, with Gozo’s airport cab service</b></p>
	
	<p>
		<?php
		if ($cmodel->is_luxury_city)
		{
			?>

		<div class="content link-two">
			<img src="/images/car-bmw.jpg" alt="book airport taxi or cab online" class="preload-image responsive-image bottom-5">
			<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>">Luxury Car Rental in <?= $cmodel->cty_name ?></a>
		</div>
<?php } ?>
	<p>Gozo Offers a hassle-free <b>airport transfer cabs</b> booking option at a cheaper price from <?= $cmodel->cty_name; ?> Airport to anywhere you want, be it in intercity, one-way or outstation or anything Gozo covers it all by just clicking Gozo app from your smartphone or from aaocab official website. Book online the cheapest and best Airport cab booking service from the airport to anywhere in India with Gozo. 
</p>
	When arriving in <?= $cmodel->cty_name; ?>   Airport, why wait in long lines for cabs or try to figure out the local transport options when you can have your driver waiting at the airport to pick you up. We can have you driver be waiting for you at arrivals, help you with your luggage and answer any questions you may have about your journey. All our our airport pickup drivers are local and in many cases, can understand English. You will receive the phone contact details of your driver my email and SMS, so you can be worry-free. <a href="https://support.aaocab.com">Gozo’s 24x7 customer support</a> is available by phone or chat should you need any assistance.</p>

<div class="content p0 bottom-15">
	<img src="/images/airport-transfers-img2.png?v1.1" alt="book airport taxi or cab online" class="preload-image responsive-image bottom-5">
	<h2>Gozo’s Airport transfers for <?= $cmodel->cty_name; ?>  - <span class="orange-color">Lowest cost & best service</span></h2>

	<div class="one-half text-center bottom-15">
		<img src="/images/img6.png?v=2.02" alt="One Way Drop" class="preload-image responsive-image bottom-5">
		<p class="font-16 bottom-0"><b>Meet & greet</b></p>
		Your driver will waiting to meet you no matter what happens
	</div>
	<div class="one-half last-column text-center bottom-15">
		<img src="/images/img7.png?v=2.02" alt="One Way Drop" class="preload-image responsive-image bottom-5">
		<p class="font-16 bottom-0"><b>Value</b></p>
		Enjoy a high-quality transfer experience at surprisingly low prices
	</div>
	<div class="clear"></div>

	<div class="one-half text-center">
		<img src="/images/img8.png?v=2.02" alt="One Way Drop" class="preload-image responsive-image bottom-5">
		<p class="font-16 bottom-0"><b>Speedy</b></p>
		No queues, no delays - we'll get you to your destination quickly
	</div>
	<div class="one-half last-column text-center">
		<img src="/images/img9.png?v=2.02" alt="One Way Drop" class="preload-image responsive-image bottom-5">
		<p class="font-16 bottom-0"><b>Door-to-Door</b></p>
		For complete peace of mind we'll take you directly to your hotel door
	</div>
	<div class="clear"></div>
</div>
<?php
$airport = $cmodel->getAirportByCity($cmodel->cty_name);
?>

<!--	<div class="col-xs-12 col-sm-10 col-md-8 marginauto float-none mb30">
           <button type="submit" class="btn btn-primary col-xs-3 btn-lg bkbtn" 
            id="218" fcity="<? //= $airport['val'];   ?>" tcity ="">Book Now</button>
    </div>-->
<h2>Popular airport transfers in <?=$cmodel->cty_name?></h2>
<p class="bottom-15">Here are some cheap pricing lists which you can book through Gozo cab. We offer a wide range of airport cab and taxi booking options throughout the city with just few clicks.</p>
<span class="font-16"><b>Flights delayed?</b></span><br>
<p class="bottom-15">Don’t worry our drivers are tasked with tracking your flight and will plan to be at the airport when you’re flight arrives. Just remember to let us know of your flight details.</p>

<span class="font-16"><b>Why book with us</b></span>
<ul class="bottom-15">
	<li><i class="fa fa-thumbs-up"></i> Excellent reputation</li>
	<li><i class="fa fa-credit-card"></i> No credit card fees</li>
	<li><i class="fa fa-road"></i> Tolls included</li>
	<li><i class="fa fa-check-circle"></i> Free cancellation</li>
	<li><i class="fa fa-user"></i> Professional drivers</li>
    <li><i class="fa fa-thumbs-up"></i> Hassle-free booking</li>
</ul>
<h3 class="bottom-5"><b>Gozo is available to you for your travel to and beyond <?= $cmodel->cty_name; ?></b></h3>
<p>Gozo is with you on your travel to or From <?= $cmodel->cty_name; ?>  and  throughout India. Our cabs are available for simple one-way intercity and outstation drops to cities like  
	<?php
	foreach ($top_city_arr as $top_city)
	{
		//echo $top_city;
		echo '<a href="/book-taxi/' . $cmodel->cty_name . '-' . strtolower($top_city) . '">' . $top_city . '</a>, ';
	}
	?>
	and many more. You can also book transfers straight to <?= $cmodel->cty_name; ?>  Airport from 
	<?php
	foreach ($top_city_arr as $top_city)
	{
		//echo $top_city;
		echo '<a href="/book-taxi/' . strtolower($top_city) . '-' . $cmodel->cty_name . '">' . $top_city . '</a>, ';
	}
	?> Travel to and from <?= $cmodel->cty_name; ?>  can be done by booking your one-way trip, round-trip, customized multi-city itinerary in a AC sedan, SUVs or for larger groups to take tempo travellers (minibus) in <?= $cmodel->cty_name; ?> . Backpackers or the adventurous travellers may option for booking <a href="http://www.aaocab.com/GozoSHARE">shared outstation cabs</a></p>
<p class="bottom-15">Gozo can be on stand-by for you throughout your travels in India. Booking your chauffeur service with Gozo couldn’t be easier. Simply visit Gozo’s website or use the Gozo mobile app to book from your smartphone.
</p>
<?php
//$city_has_airport = $top_city_arr;
if (count($city_has_airport) > 0)
{
	$count = 0;
	?>                                       
	<p class="bottom-15">Gozo’s chauffeur driven airport pickup and dropoff is available for  
		<?php
		foreach ($city_has_airport as $top_city)
		{
			//echo $top_city;
			if ($count == (count($city_has_airport) - 1))
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
		You can take advantage of Gozo’s nationwide reach and network by booking our intercity cab or taking a packaged trip anywhere in India.</p>

<?php } ?>
<h3><b>Luxury chauffeur driven car rentals and limos in <?= $cmodel->cty_name; ?></b></h3>
<p>You can also book luxury vehicles of various classes and brands like Hondas, Toyotas, Audis, BMWs, Mercedes in Delhi for airport transfers or day-based rentals. <a href="mailto:quotations@aaocab.in?subject=Luxury+Vehicle+rental+in+<?= $cmodel->cty_name; ?>">Simply contact us to check for availability and pricing for Luxury vehicles.</a> </p>
</div>

<?php
end:
?>

<script type="text/javascript">
    $('.bkbtn').click(function (e) {
        rtid = this.id;
        fct = this.getAttribute("fcity");
        tct = this.getAttribute("tcity");

        $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').val(fct);
        $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').val(tct);

    });

    function validateForm1(obj) {
        var vht = $(obj).attr("value");
        if (vht > 0) {
            $('#Booking_bkg_vehicle_type_id').val(vht);
        }
    }

    $('.airportbook').click(function (event) {
        var tcity = $(event.currentTarget).data('tcity');
        $('#<?= CHtml::activeId($brtModel, "brt_to_city_id") ?>').val(tcity);
        $('#airportSform').submit();
    });



</script>