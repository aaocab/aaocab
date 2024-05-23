<?php
$cityfrom = $mpath[0];
$cityto = $mpath[1];	
?>
<div class="content-boxed-widget testmonial">
<div class="content p0 bottom-0 ">
<div class="text">
<div class="panel-collapse collapse">
        <div>
			<h3 class="mt0">
			<?= $cityfrom ?> to <?= $cityto ?> Shared taxi carpool fares
			</h3>
			<p>
				The cheapest way to travel from <?= $cityfrom ?> to <?= $cityto ?> ​will cost you ​<span>&#x20b9</span> <?=$basePriceOW?> ​for a one way cab journey. A one way chauffeur-driven car rental saves you money vs having to pay for a round trip. It is also much more comfortable and convenient as you have a driver driving you in your dedicated car. 
            </p>
        </div>
			<div class="content p0 bottom-5">								
					<div class="pull-right">
						<strong><?=$shared_car_data['vehicle_type']?></strong>							
					</div>
					<strong>Vehicle Type</strong>								
			</div>
			<div class="clear"></div>
			<div class="content p0 bottom-5">								
					<div class="pull-right">
						<?=$shared_car_data['mode_type']?>							
					</div>
					<strong>Model Type</strong>								
			</div>
			<div class="clear"></div>
			<div class="content p0 bottom-5">								
					<div class="pull-right">
						<?=$shared_car_data['passenger_capacity']?>							
					</div>
					<strong>Passenger Capacity</strong>								
			</div>
			<div class="clear"></div>
			<div class="content p0 bottom-5">								
					<div class="pull-right">
						<?=$shared_car_data['lugggage_capacity']?>							
					</div>
					<strong>Luggage Capacity</strong>							
			</div>
			<div class="clear"></div>
			<div class="content p0 bottom-5">								
					<div class="pull-right">
						<?=$shared_car_data['rate_per_km']?>							
					</div>
					<strong>Rate/km</strong>						
			</div>
			<div class="clear"></div>
			<div class="content p0 bottom-5">								
					<div class="pull-right">
						<span>&#x20b9</span><?=$shared_car_data['fare']?>							
					</div>
					<strong>Fare</strong>						
			</div>
			<div class="clear"></div>
	</div>
<div class="text-center mb30 mt10">
		<a href="/bknw" class="uppercase btn-orange shadow-medium">Book Now</a>
	</div>
</div>
<?php	
	if(!empty($shared_car_data) && count($shared_car_data)== '6') 
	{
	?>
    <section>
        <div class="p10">
            <div class="mt0">
				<h3 class="mt0">Shared AC cabs and pay per seat from <?= $cityfrom ?> to <?= $cityto ?></h3>
        <div>
			<p>
				For people looking for economical travel options for <?= $cityfrom ?> to <?= $cityto ?>, Gozo cabs shared cabs and shuttle services can now be rented at prices cheaper than a bus ticket.
</p><p>
Why make a bus reservation when you can book a seat in a cab for almost the same or lower price. Our shared taxi provides you point to door transportation service with pickups happening from many common spots throughout the city. 
</p><p>
If you have flexible plans and are looking for the cheapest price, simply booking an available seat in a shared cab and you can travel an amazingly low price.

			</p>
<h3 class="mt0">Outstation shared taxi and shuttle services are also available in <?= $cityfrom ?></h3>
<p>
In September of 2018, Gozo has introduced the facility to hire a AC shared taxi by seat. We call this service Gozo SHARED taxi service. 
There are two types of services available. Gozo runs regular SHARED TAXI shuttle services on popular routes . 
Book a seat in our a shared taxi shuttle  at our book a Shared taxi Shuttle page.
</p>
<p>
Or you can book a seat in our <a href="/GozoSHARE">Gozo FLEXXI AC outstation shared services</a>. 
With Gozo FLEXXI you are going to carpool with a person who has booked a full taxi and is willing to share his seats. 
Gozo FLEXXI is available in all major cities and on all popular outstation taxi routes across India.
Gozo FLEXXI is much cheaper than traveling by an AC bus.
</p>
<p>
If you have firm plans and prefer to rent your own taxi, you can simply book a FLEXXI option and choose to sell your unused seats. 
Gozo cabs will help promote your unused seats to riders who are willing to travel in a shared taxi. 
When booking a FLEXXI shared taxi from Delhi to Agra, to get the best prices you must book your car atleast 5-10 days in advance and then share your seats for sale. 
Your payable price keeps going down as more people buy your offered unused seats.
</p>
        </div>
		 <div>			
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
	 <section>
        <div class="row p20">
            <div class="col-xs-12 col-sm-10 col-md-8 marginauto float-none">
				<h3 class="mt0">No Share Sedan available.</h3>
    
            </div>
        </div>
    </section>
	<?php } ?>
</div>
</div>
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
  
	
	 function book_now() {
        $('#bookingSform').submit();
    }
</script>