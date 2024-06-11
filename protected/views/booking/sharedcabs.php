<style type="text/css">
    .h3_36{ font-size: 36px !important; line-height: normal;}
    .h3_30{ font-size: 30px !important; line-height: normal;}
    .h3_18{ font-size: 18px !important; line-height: normal;}
</style>
<?
$this->newHome = true;
/* @var $cmodel Cities */
?>

<div class="row">
    <?= $this->renderPartial('application.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
<div class="row gray-bg-new">
    <div class="col-lg-6 col-sm-10 col-md-8 text-center flash_banner float-none marginauto">
        <span class="h3 mt0 mb5 flash_red">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Outstation Taxi rentals and Airport transfers all over India </span><br>
        aaocab is India's leader in chauffeur-driven taxi travel for one-way drops, outstation trips, airport transfers and day-based rentals with complete billing transparency. Gozo’s coverage reaches over <b>1,000</b> towns & cities in India, with over <b>20,000</b> vehicles, over <b>100,000</b> satisfied customers and over <b>10</b> Million kms driven each year. Book your Gozo by web, phone or app 24x7! 
    </div>
</div>
<div class="container">

<div class="row flash_banner hide" style="background: #ffc864;">
    <div class="col-lg-12 p0 hidden-sm hidden-xs text-center">     
        <figure><img src="/images/flash_lg1.jpg?v=1.1" alt="Flash Sale"></figure>		
    </div>
    <div class="col-sm-12 p0 hidden-lg hidden-md hidden-xs text-center">      
        <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>		
    </div>
    <div class="col-xs-12 p0 hidden-lg hidden-md hidden-sm text-center">
        <? /* /?><a target="_blank" href="https://twitter.com/aaocab"><?/ */ ?>
        <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>
        <? /* /?></a><?/ */ ?>
    </div>
</div>
	<?php	
	$cityfrom = $mpath[0];
	$cityto = $mpath[1];	
	if(!empty($shared_car_data) && count($shared_car_data)== '6') 
	{
	?>
    <section id="section2">
        <div class="row p20">
	            <div class="col-xs-12">
					<h3 class="mt0">Shared AC cabs  and pay per seat from <?= $cityfrom ?> to <?= $cityto ?></h3>
					<div>
						<p>For people looking for economical travel options for <?= $cityfrom ?> to <?= $cityto ?>, Gozo cabs shared cabs and shuttle services can now be rented at prices cheaper than a bus ticket.
							</p><p>
							Why make a bus reservation when you can book a seat in a cab for almost the same or lower price. Our shared taxi provides you point to point and point to door transportation service with pickups happening from many common spots throughout the city. 
						</p><p>
							If you have flexible plans and are looking for the cheapest price, simply booking an available seat in a shared cab and you can travel an amazingly low price.
						</p>
					</div>
					<div>
						<br/>
                        <h3 class="mt0">Outstation shared taxi and shuttle services are also available in <?=$cityfrom ?>
</h3>                   <p>In September of 2018, Gozo has introduced the facility to hire a AC shared taxi by seat. We call this service Gozo SHARED taxi service. There are two types of services available. Gozo runs regular SHARED TAXI shuttle services on popular routes . Book a seat in our a shared taxi shuttle  at our book a Shared taxi Shuttle page.</p>
                        <p>Or you can book a seat in our <a href="/GozoSHARE"> Gozo FLEXXI AC outstation shared services.</a> With Gozo FLEXXI you are going to carpool with a person who has booked a full taxi and is willing to share his seats. Gozo FLEXXI is available in all major cities and on all popular outstation taxi routes across India. Gozo FLEXXI is much cheaper than traveling by an AC bus. 
</p>                    <p>If you have firm plans and prefer to rent your own taxi, you can simply book a FLEXXI option and choose to sell your unused seats. Gozo cabs will help promote your unused seats to riders who are willing to travel in a shared taxi. When booking a FLEXXI shared taxi from <?= $cityfrom ?> to <?= $cityto ?>, to get the best prices you must book your car atleast 5-10days in advance and then share your seats for sale. 
                         Your payable price keeps going down as more people buy your offered unused seats. </p>
					</div>
					<br>
                    <h3 class="mt0"><?= $cityfrom ?> to <?= $cityto ?>  Shared taxi carpool fares</h3>
					<div>
						<p>
							The cheapest way to travel from <?= $cityfrom ?> to <?= $cityto ?> ​will cost you ​Rs. <?= $basePriceOW ?> ​for a one way cab journey. A one way chauffeur-driven car rental saves you money vs having to pay for a round trip. It is also much more comfortable and convenient as you have a driver driving you in your dedicated car. 
						</p>
					</div>
					<div>
						<br/>
					</div> 
					<div class="col-8">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th scope="col">Vehicle Type</th>
									<th scope="col">Model Type</th>
									<th scope="col">Passenger Capacity</th>
									<th scope="col">Luggage Capacity</th>
									<th scope="col">Rate/km</th>
									<th scope="col">Fare</th>
								</tr>
							</thead>
							<tbody>

								<tr>
									<th scope="row"><?= $shared_car_data['vehicle_type'] ?></th>
									<td><?= $shared_car_data['mode_type'] ?></td>
									<td><?= $shared_car_data['passenger_capacity'] ?></td>
									<td><?= $shared_car_data['lugggage_capacity'] ?></td>
									<td><?= $shared_car_data['rate_per_km'] ?></td>
									<td><i class="fa fa-inr"></i><?= $shared_car_data['fare'] ?></td>
								</tr>

							</tbody>
						</table>
					</div>
					<div class="mb20">
						<a href="javascript:void(0);" class="btn next-btn" onclick="book_now()">Book Now</a>
					</div>   


					<h3 class="mt0">Why use Gozo SHARE taxi from <?= $cityfrom ?> to <?= $cityto ?>?</h3>
					<div>
						<p>
							Our mission is to simplify inter-city travel Gozo means DELIGHT and JOY! We have introduced Gozo SHARE outstation cabs so you can get a great price and the best comfort.

						<ul>
							<li>You can book Gozo SHARE well in advance.</li>
							<li>You will travel in an AC cab.</li> 
							<li>We will pick you up from a pre-designated pickup point.</li> 
							<li>Gozo SHARE only matches co-riders of the same gender. Women travel with other women. Men ride with other men.</li>
							<li>Unlike traveling by bus, you do not have to stop at multiple points. We will pick you up in <?= $cityfrom ?> and drive you directly to <?= $cityto ?>. </li>
							<li>You need not worry about going from your destination bus stop to your final destination, we will drop you at your final destination. In <?= $cityto ?>, we will drop each co-rider at their final door step. Its a common pickup point to door service. </li>
							<li>You will get to your destination in almost half the time that it normally takes a bus to get to you from city to city. </li>
						</ul>
						</p>
						<p>
							Stop worrying and book Gozo SHARE now! 
						</p>

						<p>
							Find the best prices, best services, well maintained & commercially licensed vehicles and courteous drivers with us! With our carefully & diligently selected network of reliable operators, we not only ensure easy bookings, quality service and best prices but also eliminate cancellations. All this with the ease of self-booking process through web and mobile app backed by a 24x7 tele helpline. So just book with us and allow us to delight you :) 

						</p>

					</div>
	            </div>
	        </div>
    </section>
	<?php } 
	else {  ?>
	 <section id="section2">
        <div class="row p20">
            <div class="col-xs-12 col-sm-10 col-md-8 marginauto float-none">
				<h3 class="mt0">No Share Sedan available.</h3>
    
            </div>
        </div>
    </section>
	<?php } ?>
</div>
<? $api = Yii::app()->params['googleBrowserApiKey']; ?>
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
        $('#map_canvas').css('height', $('#desc').height());
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
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
                'callback=mapInitialize&key=<?= $api ?>';
        document.body.appendChild(script);
    }
    window.onload = loadScript;
	
	 function book_now() {
        $('#bookingSform').submit();
    }
</script>