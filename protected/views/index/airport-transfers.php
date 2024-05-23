<style>
	.big-font{ font-size: 76px; line-height: normal;}
	.route_box{
		-webkit-border-top-right-radius: 100px;
		-moz-border-radius-topright: 100px;
		border-top-right-radius: 100px;
		background: #0c5cbf;
		color: #fff;
		padding: 30px 20px 20px 20px;
		margin-bottom: 30px;
	}
	.orange-bg2 {
		background: #f77026 none repeat scroll 0 0;
	}
	.route-part{
		-webkit-border-radius: 30px;
		-moz-border-radius: 30px;
		border-radius: 30px;
		background: #efefef;
		-webkit-box-shadow: 0px 0px 24px 0px rgba(0,0,0,0.38);
		-moz-box-shadow: 0px 0px 24px 0px rgba(0,0,0,0.38);
		box-shadow: 0px 0px 24px 0px rgba(0,0,0,0.38);
	}
	.route-pbtn a{
		-webkit-border-bottom-right-radius: 10px;
		-webkit-border-bottom-left-radius: 10px;
		-moz-border-radius-bottomright: 10px;
		-moz-border-radius-bottomleft: 10px;
		border-bottom-right-radius: 10px;
		border-bottom-left-radius: 10px;
		background: #dfdfdf;
		font-size: 30px; font-weight: 700; color: #636363; padding: 20px 55px; text-transform: uppercase; line-height: normal; text-decoration: none;
		-webkit-box-shadow: 0px 12px 24px -3px rgba(0,0,0,0.26);
		-moz-box-shadow: 0px 12px 24px -3px rgba(0,0,0,0.26);
		box-shadow: 0px 12px 24px -3px rgba(0,0,0,0.26);
	}
	.route-pbtn a:hover{ background: #0c5cbf; color: #fff;}
	[type="radio"]:checked,
	[type="radio"]:not(:checked) {
		position: absolute;
		left: -9999px;
	}
	[type="radio"]:checked + label,
	[type="radio"]:not(:checked) + label
	{
		position: relative;
		padding-left: 28px;
		cursor: pointer;
		line-height: 20px;
		display: inline-block;
		color: #666;
	}
	[type="radio"]:checked + label:before,
	[type="radio"]:not(:checked) + label:before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		width: 30px;
		height: 30px;
		border: 1px solid #ddd;
		border-radius: 100%;
		background: #fff;
	}
	[type="radio"]:checked + label:after,
	[type="radio"]:not(:checked) + label:after {
		content: '';
		width: 24px;
		height: 24px;
		background: #f36d33;
		position: absolute;
		top: 3px;
		left: 3px;
		border-radius: 100%;
		-webkit-transition: all 0.2s ease;
		transition: all 0.2s ease;
	}
	[type="radio"]:not(:checked) + label:after {
		opacity: 0;
		-webkit-transform: scale(0);
		transform: scale(0);
	}
	[type="radio"]:checked + label:after {
		opacity: 1;
		-webkit-transform: scale(1);
		transform: scale(1);
	}
	.banner-ani img{ width: 100%;}
	.share_on{ background: #58a39f; padding: 30px 0; font-size: 60px; font-weight: bold;}
	.share_on a{ font-size: 60px; color: #fff; padding: 0 30px;}
	.share_on a:hover{ color: #ffbd2e;}

	.airport-img img{ width: 100%;}
	.airport-table td{ background-color: #1a4ea2!important; color: #fff!important; font-weight: 400;}
	.book-with{ background: #fff; border: #e1e1e1 1px solid; width: 30%; padding: 15px; margin-bottom: 10px;
				-webkit-box-shadow: -7px 10px 20px -6px rgba(0,0,0,0.17);
				-moz-box-shadow: -7px 10px 20px -6px rgba(0,0,0,0.17);
				box-shadow: -7px 10px 20px -6px rgba(0,0,0,0.17);
				margin-top: 45px; margin-left: 15px;
	}
	.book-with ul{ list-style-type: none; display: block; padding: 0;}
	.book-with li{ display: block; padding: 10px 0; font-size: 15px; color: #000; border-bottom: #ededed 1px solid;}
	.book-with li:last-child{ border-bottom: none;}
	.book-with i{ color: #5ad54d; padding-right: 5px;}
	@media (min-width: 320px) and (max-width: 767px) { 
		.book-with{ width: 100%;}
	}

</style>


<div class="row">
	<div class="col-xs-12 airport-img">
		<img src="/images/airport-transfers-img.png?v1.1"  alt="book online <?= $cmodel->cty_name; ?> airport transfer cab">
	</div>

	<div align="center" class="col-xs-12">
		<?php
		if (empty($topTenRoutes))
		{
			echo '<p class="h2 mt30">No Airport available</p>';
			goto end;
		}
		?>
	</div>
	<?
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id' => 'airportSform',
	'enableClientValidation' => true,
	'clientOptions' => array(
	'validateOnSubmit' => true,
	'errorCssClass' => 'has-error',
	'afterValidate' => 'js:function(form,data,hasError){
	if(!hasError){
	return true;

	}
	}'
	),
	'enableAjaxValidation' => false,
	'errorMessageCssClass' => 'help-block',
	'action' => Yii::app()->createUrl('booking/booknow'),
	'htmlOptions' => array(
	'class' => 'form-horizontal',
	),
	));
	/* @var $form TbActiveForm */
	?>
	<?= $form->errorSummary($model); ?>
	<?= CHtml::errorSummary($model); ?>
	<input type="hidden" id="step" name="step" value="0">
	<?= $form->hiddenField($model, "bkg_from_city_id"); ?>
	<?= $form->hiddenField($model, "bkg_to_city_id"); ?>
	<?= $form->hiddenField($model, "bkg_booking_type", ['value' => 4]); ?>
	<?= $form->hiddenField($model, "bkg_pickup_date_date") ?>
	<?= $form->hiddenField($model, "bkg_pickup_date_time", ['value' => date('h:i A', strtotime('6 AM'))]); ?>
	<div class="col-xs-12">
		<div class="row mt20">
			<div class="col-xs-12 col-sm-8 col-md-9">
				<h1><?= $cmodel->cty_name; ?>  Airport Transfers, with Gozo’s airport cab service</h1>
                <p>Gozo Offers a hassle-free <b>airport transfer cabs</b> booking option at a cheaper price from <?= $cmodel->cty_name; ?> Airport to anywhere you want, be it in intercity, one-way or outstation or anything Gozo covers it all by just clicking Gozo app from your smartphone or from Gozocabs official website. Book online the cheapest and best Airport cab booking service from the airport to anywhere in India with Gozo.</p>              
				<p>When arriving in <?= $cmodel->cty_name; ?> Airport, why wait in long lines for taxi or try to figure out the local transport options when you can have your driver waiting at the airport to pick you up. We can have you driver be waiting for you at arrivals, help you with your luggage and answer any questions you may have about your journey. All our our airport pickup drivers are local and in many cases, can understand English. You will receive the phone contact details of your driver my email and SMS, so you can be worry-free. <a href="https://support.gozocabs.com">Gozo’s 24x7 customer support</a> is available by phone or chat should you need any assistance.</p>
            
				<div class="row mt10">
					<h2 class="col-xs-12 h3 text-center">Popular airport transfers in <span class="orange-color"><?= $cmodel->cty_name; ?> </span></h2>
                      <p>Here are some cheap pricing lists which you can book through Gozo cab. We offer a wide range of airport cab and taxi booking options throughout the city with just few clicks.</p>
					<div class="col-xs-12">
						<table class="table table-striped table-bordered">
							<tr class="airport-table">
								<td>Route</td>
								<td>Sedan</td>
								<td>SUV</td>
								<td>Distance & time</td>
                                <td>Action</td>
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
					</div>
					<!--<div class="col-xs-12 mb30">
						<button type="submit" class="btn btn-primary proceed-new-btn mt0 bkbtn" id="218" fcity="<?= $airport['val']; ?>" tcity ="">Book Now</button>
					</div>-->
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 airport-img">
				<img src="/images/airport-transfers-img2.png?v1.1" alt="hire airport cab or taxi" >
				<div class="main_time border-blueline text-center main_time2 mt20">
					<div class="car_box"><img src="/images/car-bmw.jpg" alt="book airport taxi or cab online"></div>
					<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_name)); ?>">Luxury Car Rental in <?= $cmodel->cty_name ?></a>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xs-12">
		<div class="row mt10">
			<div class="col-xs-12 h2 text-center">Gozo’s Airport transfers for <?= $cmodel->cty_name; ?>  - <span class="orange-color">Lowest cost & best service</span></div>
			<div class="col-xs-12 col-sm-6 col-md-3 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;" data-wow-delay="0.4s">
				<div class="advance-panel"><figure><img src="/images/img6.png?v=2.02" alt="One Way Drop"></figure></div>
				<h3>Meet & greet</h3>
				Your driver will waiting to meet you no matter what happens
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;" data-wow-delay="0.4s">
				<div class="advance-panel"><figure><img src="/images/img7.png?v=2.02" alt="One Way Drop"></figure></div>
				<h3>Value</h3>
				Enjoy a high-quality transfer experience at surprisingly low prices
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;" data-wow-delay="0.4s">
				<div class="advance-panel"><figure><img src="/images/img8.png?v=2.02" alt="One Way Drop"></figure></div>
				<h3>Speedy</h3>
				No queues, no delays - we'll get you to your destination quickly
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3 text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;" data-wow-delay="0.4s">
				<div class="advance-panel"><figure><img src="/images/img9.png?v=2.02" alt="One Way Drop"></figure></div>
				<h3>Door-to-Door</h3>
				For complete peace of mind we'll take you directly to your hotel door
			</div>
		</div>
	</div>

	<?php
	$airport = $cmodel->getAirportByCity($cmodel->cty_name);
	?>

	
	<div class="col-xs-12 mt50"><span class="h4">Flights delayed?</span><br>
		<p>Don’t worry our drivers are tasked with tracking your flight and will plan to be at the airport when you’re flight arrives. Just remember to let us know of your flight details.</p>
	</div>
	<div class="col-xs-12">
		<div class="row">
			<div class="col-xs-12">
				<div class="pull-right book-with">
					<span class="h4" style="color:#000;">Why book with us</span>
					<ul>
						<li><i class="fa fa-thumbs-up"></i> Excellent reputation</li>
						<li><i class="fa fa-credit-card"></i> No credit card fees</li>
						<li><i class="fa fa-road"></i> Tolls included</li>
						<li><i class="fa fa-check-circle"></i> Free cancellation</li>
						<li><i class="fa fa-user"></i> Professional drivers</li>
                        <li><i class="fa fa-thumbs-o-up"></i> Hassle-free booking</li> 
					</ul>
				</div>
				<h3>Gozo is available to you for your travel to and beyond <?= $cmodel->cty_name; ?> </h3>
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
					?> Travel to and from <?= $cmodel->cty_name; ?>  can be done by booking your one-way trip, round-trip, customized multi-city itinerary in a AC sedan, SUVs or for larger groups to take tempo travellers (minibus) in <?= $cmodel->cty_name; ?> . Backpackers or the adventurous travellers may option for booking <a href="http://www.gozocabs.com/GozoSHARE">shared outstation cabs</a></p>
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
					<h3>Luxury chauffeur driven car rentals and limos in <?= $cmodel->cty_name; ?> </h3>
					<p>You can also book luxury vehicles of various classes and brands like Hondas, Toyotas, Audis, BMWs, Mercedes in Delhi for airport transfers or day-based rentals. <a href="mailto:quotations@gozocabs.in?subject=Luxury+Vehicle+rental+in+<?= $cmodel->cty_name; ?>">Simply contact us to check for availability and pricing for Luxury vehicles. </a></p>
			</div>
			<div class="col-xs-12 col-sm-4"></div>
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