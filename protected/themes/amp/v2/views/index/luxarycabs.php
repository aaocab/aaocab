<?php
$cities        = ($count['countCities'] > 500) ? 500 : $count['countCities'];
$routes        = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
$topCitiesByKm = '';
$ctr           = 1;
foreach ($topCitiesKm as $top)
{
    $topCitiesByKm .= '<a href="/outstation-cabs/' . strtolower($top['cty_alias_path']) . '" style=" text-decoration: none; color: #282828;" target="_blank">' . $top['city'] . '</a>';
    $topCitiesByKm .= (count($topCitiesKm) == $ctr) ? " " : ", ";
    $ctr++;
}
$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';
?>

<section id="section2">
	<div id="desc" class="feature">
		<div class="newline">
			<h3 class="text-center mt0">Luxury Car rental service in <?=$city?></h3>
		</div>
		<div class="wrraper mt10">
		<h3 class="mt0">Pricing and options for Luxury Car Rentals in <?=$city?></h3>
		<div class="table-view flex">
			<div class="table-view-left"><h3 class="mt0">Car</h3></div> 
			<div class="table-view-right"><h3 class="mt0">Fare / (Km)</h3></div>
		</div>		
		<div class="table-view flex">
			<div class="table-view-left"><strong>Audi</strong></div> 
			<div class="table-view-right">
			<a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20"  style="width: 20px; height: 20px;"></amp-img></a>
			</div>
		</div>
		<div class="table-view flex">
			<div class="table-view-left"><strong>Mercedes</strong></div> 
			<div class="table-view-right">
			<a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20" style="width: 20px; height: 20px;"></amp-img></a>
			</div>
		</div>
		<div class="table-view flex">
			<div class="table-view-left"><strong>Jaguar</strong></div> 
			<div class="table-view-right">
			<a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20" style="width: 20px; height: 20px;"></amp-img></a>
			</div>
		</div>
		<div class="table-view flex">
			<div class="table-view-left"><strong>BMW</strong></div> 
			<div class="table-view-right">
			<a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20" style="width: 20px; height: 20px;"></amp-img></a>
			</div>
		</div>
		<div class="table-view flex">
			<div class="table-view-left"><strong>Rolls Royce</strong></div> 
			<div class="table-view-right">
			<a href="tel:9051877000"><amp-img src="/images/call.png" alt="" width="20" height="20"   style="width: 20px; height: 20px;"></amp-img></a>
			</div>
		</div>		
		<p>Luxury Cars are a comfort & style statement. 
		When Gozo’s team first thought of this, we know we have to fulfill customer’s desire for service and class by letting them hire luxury cars on rent in Delhi and all major cities in India. 
		Luxury cars that we provide for rent range from a wide variety that cater to choices and tastes of our customers. 
		To add to your special day, we provide the best luxury cars rentals in <?=$city?></p>

		<h3 class="mb0">Why book with Gozo for Luxury car rental in <?=$city?></h3>
		<p>Gozo is india’s leader for luxury car rental services in top 50 of the 750 cities that we serve in India. Gozo's booking and billing process is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. In addition you get instant booking confirmations, electronic invoices, 24x7 support and top quality for the best price. If you have any special needs, you can always contact our customer helpdesk and we will be happy to support you.Our services are top rated in almost all cities across India. If you are looking to make an impression on your clients, or if you are serving the business elite you can call on Gozo to arrange pick up or drop facilities in luxury cars. You can also use Gozo’s luxury car rental services for a city tour through the day.
		</p>
		<h3 class="mb0">Hire Premium & luxury cars for wedding in <?=$city?></h3>
		<p>To make your memorable day even more special, Gozo offers you an opportunity to choose from wide spectra of luxury cars. Due to the specific and less frequent nature of these requirements we request customers to make their reservations well in advance. This provides us sufficient advance notice to arrange a vehicle for your needs.</p>
		</div>
	</div>
</section>