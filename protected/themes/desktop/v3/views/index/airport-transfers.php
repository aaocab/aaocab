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



<div class="container-fluid mt20 n">
	<div class="row">
		<div class="col-12 p0 text-center">
			<img src="/images/airport-transfers-img.webp?v=0.6" alt="book online <?= $cmodel->cty_name; ?> airport transfer cab" title="book online <?= $cmodel->cty_name; ?> airport transfer cab" class="lozad img-fluid" width="1401" height="314">
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-12 text-center mt-2">
			<p class="merriw heading-line"><?php echo $this->pageTitle; ?></p>
		</div>
	</div>
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
			<div class="row mt20">
				<div class="col-12 col-md-9">
					<h1 class="h5 merriw"><b><?= $cmodel->cty_name; ?>  Airport Transfers, with Gozo’s airport cab service</b></h1>
					<p>Gozo Offers a hassle-free <b>airport transfer cabs</b> booking option at a cheaper price from <?= $cmodel->cty_name; ?> Airport to anywhere you want, be it in intercity, one-way or outstation or anything Gozo covers it all by just clicking Gozo app from your smartphone or from aaocab official website. Book online the cheapest and best Airport cab booking service from the airport to anywhere in India with Gozo.</p>              
					<p>When arriving in <?= $cmodel->cty_name; ?> Airport, why wait in long lines for taxi or try to figure out the local transport options when you can have your driver waiting at the airport to pick you up. We can have you driver be waiting for you at arrivals, help you with your luggage and answer any questions you may have about your journey. All our our airport pickup drivers are local and in many cases, can understand English. You will receive the phone contact details of your driver my email and SMS, so you can be worry-free. <a href="https://support.aaocab.com">Gozo’s 24x7 customer support</a> is available by phone or chat should you need any assistance.</p>


					<h2 class="h5 merriw mt-2"><b>Popular airport transfers in <span class="orange-color"><?= $cmodel->cty_name; ?> </span></b></h2>
					<p>Here are some cheap pricing lists which you can book through Gozo cab. We offer a wide range of airport cab and taxi booking options throughout the city with just few clicks.</p>
					<div class="row d-lg-none">

						<?php
						foreach ($topTenRoutes as $top)
						{
							$top_city_arr[] = $top['to_city'];
							if ($top['destination_city_has_airport'] == 1)
							{
								$city_has_airport[] = $top['to_city'];
							}
							?>
							<div class="col-12">
								<div class="card mb20">
									<div class="card-body p15">
										<div class="content p0 mb10">
											<h3 class="font-16 weight500"><?= $top['from_city']; ?> Airport to <?= $top['to_city']; ?>  transfer </h3>
											<div class="" style="width: 70%; float: left;">
												<p class="mb5"><?= $top['distance'] ?> kms </p>
												<p class="mt20 mb0 weight500 color-orange"><?= $top['distance'] ?> kms included</p>
												<p class="font-13 weight400 color-gray mb0">Charges after <?= $top['distance'] ?> Km @ ₹<?= $top['extraKmRate'] ?>/km</p>
												<p class="font-13 weight400 color-gray mb0">(30 min complimentary waiting at airport)</p>
											</div>
											<div class="text-right font-20"  style="width: 30%; float: left;">
												<?= ($top['seadan_price'] > 0) ? '<span>&#x20b9</span><b>' . $top['seadan_price'] . '</b>' : '<a href="javascript:helpline();"><img src="/images/img-2022/bxs-phone-call.svg" alt="Call" width="20" height="20" class="preload-image" style="display:inline!important;"></a>'; ?>
												<p class="font-13 weight400 mb10">Onwards</p>
												<p class="mb0"><a class="btn btn-primary btn-sm pl10 pr10 bkbtn" style="width: 100%;" href="<?//= $this->getOneWayUrlFromPath($top['from_city_alias_path'], $top['to_city_alias_path']) ?>">Book</a></p>

											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>					
					<div class="row d-none d-lg-block">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-bordered" width="100%">
											<tr class="thead-dark">
												<th>Route</th>
												<th>Sedan</th>
												<th>SUV</th>
												<th>Distance</th>
												<th width="15%">Action</th>
											</tr>
											<tr>
												<td><?= $cmodel->cty_name; ?> (within City) Airport transfer </td>
												<td>₹<?= $localPrice['sedan'] ?></td>
												<td>₹<?= $localPrice['suv'] ?></td>
												<td><?= $localPrice['tripDistance'] ?> km after that ₹<?= $localPrice['extraKmRate'] ?> per km (30 min complimentary waiting at airport)</td>
												<td><a href="/book-cab/airport-pickup/<?= $airport_path; ?>" class="btn btn-primary btn-sm pl10 pr10 bkbtn">Book Now</a></td>
											</tr>
											<?php
											foreach ($topTenRoutes as $top)
											{

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
													<td><?= $top['distance'] ?> km after that ₹<?= $top['extraKmRate'] ?> per km (30 min complimentary waiting at airport)</td>
													<td><a href="/book-cab/one-way/<?= strtolower($airport_path); ?>/<?= strtolower($top['to_city']); ?>" class="btn btn-primary btn-sm pl10 pr10 bkbtn">Book Now</a></td>
												</tr>
												<?php
											}
											//echo implode(",",$top_city_arr);
											?>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--<div class="col-12 mb30">
						<button type="submit" class="btn btn-primary proceed-new-btn mt0 bkbtn" id="218" fcity="<?= $airport['val']; ?>" tcity ="">Book Now</button>
					</div>-->

				</div>
				<div class="col-12 col-sm-12 col-md-3">
					<img src="/images/airport-transfers-img2.webp?v1.3" alt="hire airport cab or taxi" class="img-fluid lozad" width="255" height="255">
					<div class="card mt30">
						<div class="card-body">
							<div class="car_box2"><img src="/images/car-bmw.jpg" alt="book airport taxi or cab online" class="img-fluid lozad" width="200" height="113"></div>
							<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_name)); ?>" class="color-black">Luxury Car Rental in <?= $cmodel->cty_name ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid bg-gray pt-2 pb-3">
	<div class="container">
		<div class="row">
			<div class="col-12 mt30 mb20"><h3 class="h5 merriw text-center"><b>Gozo’s Airport transfers for <?= $cmodel->cty_name; ?>  - <span class="color-blue">Lowest cost & best service</span></b></h3></div>
			<div class="col-12 col-sm-6 col-md-3 text-center">
				<div class="hvr-float-shadow rounded-1 mb-1"><figure><img src="/images/img-2022/icon19.png?v=2.03" alt="One Way Drop" width="96" height="96" class="lozad"></figure></div>
				<h4 class="merriw h5">Meet & greet</h4>
				Your driver will waiting to meet you no matter what happens
			</div>
			<div class="col-12 col-sm-6 col-md-3 text-center">
				<div class="hvr-float-shadow rounded-1 mb-1"><figure><img src="/images/img-2022/icon19.png?v=2.03" alt="One Way Drop" width="96" height="96" class="lozad"></figure></div>
				<h4 class="merriw h5">Value</h4>
				Enjoy a high-quality transfer experience at surprisingly low prices
			</div>
			<div class="col-12 col-sm-6 col-md-3 text-center">
				<div class="hvr-float-shadow rounded-1 mb-1"><figure><img src="/images/img-2022/icon25.png?v=2.03" alt="One Way Drop" width="96" height="96" class="lozad"></figure></div>
				<h4 class="merriw h5">Speedy</h4>
				No queues, no delays - we'll get you to your destination quickly
			</div>
			<div class="col-12 col-sm-6 col-md-3 text-center">
				<div class="hvr-float-shadow rounded-1 mb-1"><figure><img src="/images/img-2022/icon27.png?v=2.04" alt="One Way Drop" width="96" height="96" class="lozad"></figure></div>
				<h4 class="merriw h5">Door-to-Door</h4>
				For complete peace of mind we'll take you directly to your hotel door
			</div>
		</div>
	</div>
</div>

<div class="container mb40">
    <div class="row">
		<?php
		$airport = $cmodel->getAirportByCity($cmodel->cty_name);
		?>


		<div class="col-12 mt-2">
            <h3 class="merriw h5"><b>Flights delayed?</b></h3>
			<p>Don’t worry our drivers are tasked with tracking your flight and will plan to be at the airport when you’re flight arrives. Just remember to let us know of your flight details.</p>
		</div>
		<div class="col-12">
			<div class="row">
				<div class="col-12 col-md-8 col-xl-8">
					<h3 class="merriw h5 mt-1"><b>Gozo is available to you for your travel to and beyond <?= $cmodel->cty_name; ?></b></h3>
					<p>Gozo is with you on your travel to or From <?= $cmodel->cty_name; ?>  and  throughout India. Our cabs are available for simple one-way intercity and outstation drops to cities like  
						<?php
						foreach ($topTenRoutes as $top_city)
						{
							//echo $top_city;
							$tcity = Cities::getAliasPath($top_city['brt_to_city_id']);
							echo '<a href="/book-cab/one-way/' . $cmodel->cty_name . '/' . strtolower($tcity) . '">' . $top_city['to_city'] . '</a>, ';
						}
						?>
					<top 10 cities> and many more. You can also book transfers straight to <?= $cmodel->cty_name; ?>  Airport from 
						<?php
						foreach ($topTenRoutes as $top_city)
						{
							//echo $top_city;
							$tcity = Cities::getAliasPath($top_city['brt_to_city_id']);
							echo '<a href="/book-cab/one-way/' . strtolower($tcity) . '/' . $cmodel->cty_name . '">' . $top_city['to_city'] . '</a>, ';
						}
						?> Travel to and from <?= $cmodel->cty_name; ?>  can be done by booking your one-way trip, round-trip, customized multi-city itinerary in a AC sedan, SUVs or for larger groups to take tempo travellers (minibus) in <?= $cmodel->cty_name; ?> . </p>
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
						<h3 class="merriw h5 mt-2"><b>Luxury chauffeur driven car rentals and limos in <?= $cmodel->cty_name; ?></b></h3>
						<p>You can also book luxury vehicles of various classes and brands like Hondas, Toyotas, Audis, BMWs, Mercedes in Delhi for airport transfers or day-based rentals. <a href="mailto:quotations@aaocab.in?subject=Luxury+Vehicle+rental+in+<?= $cmodel->cty_name; ?>">Simply contact us to check for availability and pricing for Luxury vehicles. </a></p>
				</div>
				<div class="col-12 col-md-4 col-xl-4">
					<div class="card">
						<div class="card-header pb0"><b>Why book with us</b></div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item"><img src="/images/bxs-tachometer.svg" alt="img" width="14" height="14" class="mr10"> Excellent reputation</li>
							<li class="list-group-item"><img src="/images/bx-credit-card-front.svg" alt="img" width="14" height="14" class="mr10"> No credit card fees</li>
							<li class="list-group-item"><img src="/images/bx-directions.svg" alt="img" width="14" height="14" class="mr10"> Tolls included</li>
							<li class="list-group-item"><img src="/images/bx-check-circle.svg" alt="img" width="14" height="14" class="mr10"> Free cancellation</li>
							<li class="list-group-item"><img src="/images/bx-id-card.svg" alt="img" width="14" height="14" class="mr10"> Professional drivers</li>
							<li class="list-group-item"><img src="/images/bx-car.svg" alt="img" width="14" height="14" class="mr10"> Hassle-free booking</li>
						</ul>
					</div>
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