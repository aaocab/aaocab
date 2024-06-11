<?php
if (isset($organisationSchema) && trim($organisationSchema) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $organisationSchema;
	?>
	</script>
<?php
}
if (isset($airportSchemaStructureMarkupData) && trim($airportSchemaStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $airportSchemaStructureMarkupData;
	?>
	</script>
<?php
}
if (isset($breadcrumbStructureMarkupData) && trim($breadcrumbStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php
	echo $breadcrumbStructureMarkupData;
	?>
	</script>
<?php }
?>

<div class="row title-widget">
    <div class="col-12">
        <div class="container">
<?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12 bg-black p0 text-center">
        <img src="/images/airport-transfers-img.png?v=0.3" alt="book online <?= $cmodel->cty_name; ?> airport transfer cab" title="book online <?= $cmodel->cty_name; ?> airport transfer cab" class="img-fluid">
    </div>
</div>

<div class="container">
    <div class="row">

		<div align="center" class="col-12">
			<?php
			if (empty($topTenRoutes))
			{
				echo '<p class="h2 mt30">No Airport available</p>';
				goto end;
			}
			?>
		</div>
		<?
		$form		 = $this->beginWidget('CActiveForm', array(
			'id'					 => 'airportSform',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
	if(!hasError){
	return true;

	}
	}'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => Yii::app()->createUrl('booking/booknow'),
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form CActiveForm */
		?>
		<?= $form->errorSummary($model); ?>
		<?= CHtml::errorSummary($model); ?>
		<input type="hidden" id="step" name="step" value="0">
		<?= $form->hiddenField($model, "bkg_from_city_id"); ?>
		<?= $form->hiddenField($model, "bkg_to_city_id"); ?>
		<?= $form->hiddenField($model, "bkg_booking_type", ['value' => 4]); ?>
		<?= $form->hiddenField($model, "bkg_pickup_date_date") ?>
<?= $form->hiddenField($model, "bkg_pickup_date_time", ['value' => date('h:i A', strtotime('6 AM'))]); ?>
		<div class="col-12">
			<div class="row mt30">
				<div class="col-12 col-md-9">
					<h1 class="font-24"><?= $cmodel->cty_name; ?>  Airport Transfers, with Gozo’s airport cab service</h1>
					<p>Gozo Offers a hassle-free <b>airport transfer cabs</b> booking option at a cheaper price from <?= $cmodel->cty_name; ?> Airport to anywhere you want, be it in intercity, one-way or outstation or anything Gozo covers it all by just clicking Gozo app from your smartphone or from Gozocabs official website. Book online the cheapest and best Airport cab booking service from the airport to anywhere in India with Gozo.</p>              
					<p>When arriving in <?= $cmodel->cty_name; ?> Airport, why wait in long lines for taxi or try to figure out the local transport options when you can have your driver waiting at the airport to pick you up. We can have you driver be waiting for you at arrivals, help you with your luggage and answer any questions you may have about your journey. All our our airport pickup drivers are local and in many cases, can understand English. You will receive the phone contact details of your driver my email and SMS, so you can be worry-free. <a href="https://support.aaocab.com">Gozo’s 24x7 customer support</a> is available by phone or chat should you need any assistance.</p>


					<h2 class="font-24 mt30">Popular airport transfers in <span class="orange-color"><?= $cmodel->cty_name; ?> </span></h2>
					<p>Here are some cheap pricing lists which you can book through Gozo cab. We offer a wide range of airport cab and taxi booking options throughout the city with just few clicks.</p>

					<table class="table table-striped table-bordered">
						<tr class="airport-table">
							<td>Route</td>
							<td>Sedan</td>
							<td>SUV</td>
							<td>Distance & time</td>
							<td width="15%">Action</td>
						</tr>
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
						<tr>
							<td><?= $cmodel->cty_name; ?>(within City) Airport transfer </td>
							<td>₹<?= $localPrice['sedan'] ?></td>
							<td>₹<?= $localPrice['suv'] ?></td>
							<td><?= $localPrice['tripDistance'] ?>km/<?= $hours . "." . $next_half ?> hrs (30min complimentary waiting at airport)</td>
							<td><a href="/bknw/airport/<?= $cmodel->cty_name; ?>" class="btn btn-primary proceed-new-btn mt0 bkbtn">Book Now</a></td>
						</tr>
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
							<tr>
								<td><?= $top['from_city']; ?> Airport to <?= $top['to_city']; ?>  transfer</td>
								<td>₹<?= $top['seadan_price']; ?></td>
								<td>₹<?= $top['suv_price']; ?></td>
								<td><?= $top['distance'] ?>km/<?= $hours . "." . $next_half ?> hrs (30min complimentary waiting at airport)</td>
								<td><a href="/bknw/oneway/<?= strtolower($airport_path); ?>/<?= strtolower($top['to_city']); ?>" class="btn btn-primary proceed-new-btn mt0 bkbtn">Book Now</a></td>
							</tr>
							<?php
						}
						//echo implode(",",$top_city_arr);
						?>
					</table>

					<!--<div class="col-12 mb30">
						<button type="submit" class="btn btn-primary proceed-new-btn mt0 bkbtn" id="218" fcity="<?= $airport['val']; ?>" tcity ="">Book Now</button>
					</div>-->

				</div>
				<div class="col-12 col-sm-4 col-md-3 airport-img mt30">
					<img src="/images/airport-transfers-img2.png?v1.1" alt="hire airport cab or taxi" class="img-fluid">
					<div class="main_time text-center m0 mt30">
						<div class="car_box2"><img src="/images/car-bmw.jpg" alt="book airport taxi or cab online" class="img-fluid"></div>
						<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_name)); ?>">Luxury Car Rental in <?= $cmodel->cty_name ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row bg-gray mt30 mb30 pb40">
	<div class="col-12 mt30 mb20"><h3 class="font-24 text-center">Gozo’s Airport transfers for <?= $cmodel->cty_name; ?>  - <span class="orange-color">Lowest cost & best service</span></h3></div>
	<div class="col-12 col-sm-6 col-md-3 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;" data-wow-delay="0.4s">
		<div class="advance-panel"><figure><img src="/images/img6.png?v=2.02" alt="One Way Drop"></figure></div>
		<h3>Meet & greet</h3>
		Your driver will waiting to meet you no matter what happens
	</div>
	<div class="col-12 col-sm-6 col-md-3 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;" data-wow-delay="0.4s">
		<div class="advance-panel"><figure><img src="/images/img7.png?v=2.02" alt="One Way Drop"></figure></div>
		<h3>Value</h3>
		Enjoy a high-quality transfer experience at surprisingly low prices
	</div>
	<div class="col-12 col-sm-6 col-md-3 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;" data-wow-delay="0.4s">
		<div class="advance-panel"><figure><img src="/images/img8.png?v=2.02" alt="One Way Drop"></figure></div>
		<h3>Speedy</h3>
		No queues, no delays - we'll get you to your destination quickly
	</div>
	<div class="col-12 col-sm-6 col-md-3 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.4s">
		<div class="advance-panel"><figure><img src="/images/img9.png?v=2.02" alt="One Way Drop"></figure></div>
		<h3>Door-to-Door</h3>
		For complete peace of mind we'll take you directly to your hotel door
	</div>
</div>

<div class="container mb40">
    <div class="row">
		<?php
		$airport = $cmodel->getAirportByCity($cmodel->cty_name);
		?>


		<div class="col-12 mt20">
            <h3 class="font-22">Flights delayed?</h3>
			<p>Don’t worry our drivers are tasked with tracking your flight and will plan to be at the airport when you’re flight arrives. Just remember to let us know of your flight details.</p>
		</div>
		<div class="col-12">
			<div class="row">
				<div class="col-12 col-md-9">
					<h3 class="font-22">Gozo is available to you for your travel to and beyond <?= $cmodel->cty_name; ?> </h3>
					<p>Gozo is with you on your travel to or From <?= $cmodel->cty_name; ?>  and  throughout India. Our cabs are available for simple one-way intercity and outstation drops to cities like  
						<?php
						foreach ($top_city_arr as $top_city)
						{
							//echo $top_city;
							echo '<a href="/book-taxi/' . $cmodel->cty_name . '-' . strtolower($top_city) . '">' . $top_city . '</a>, ';
						}
						?>
					<top 10 cities> and many more. You can also book transfers straight to <?= $cmodel->cty_name; ?>  Airport from 
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
							$count = 0;
							?>                                       
							<p>Gozo’s chauffeur driven airport pickup and dropoff is available for  
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
								You can take advantage of Gozo’s nationwide reach and network by booking our intercity taxi or taking a packaged trip anywhere in India.</p>

<?php } ?>
						<h3 class="font-22">Luxury chauffeur driven car rentals and limos in <?= $cmodel->cty_name; ?> </h3>
						<p>You can also book luxury vehicles of various classes and brands like Hondas, Toyotas, Audis, BMWs, Mercedes in Delhi for airport transfers or day-based rentals. <a href="mailto:quotations@gozocabs.in?subject=Luxury+Vehicle+rental+in+<?= $cmodel->cty_name; ?>">Simply contact us to check for availability and pricing for Luxury vehicles. </a></p>
				</div>
				<div class="col-12 col-md-3">
					<ul class="list-group">
						<li class="list-group-item active font-18"><b>Why book with us</b></li>
						<li class="list-group-item"><i class="fa fa-thumbs-up mr10"></i> Excellent reputation</li>
						<li class="list-group-item"><i class="fa fa-credit-card mr10"></i> No credit card fees</li>
						<li class="list-group-item"><i class="fa fa-road mr10"></i> Tolls included</li>
						<li class="list-group-item"><i class="fa fa-check-circle mr10"></i> Free cancellation</li>
						<li class="list-group-item"><i class="fa fa-user mr10"></i> Professional drivers</li>
						<li class="list-group-item"><i class="fas fa-thumbs-up mr10"></i> Hassle-free booking</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->endWidget(); ?>
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


</script>