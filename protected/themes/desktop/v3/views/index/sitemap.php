<div class="container-fluid">
<div class="container list-view-content">
<div class="row">
<div class="col-12">
<h3 class="font-22">Sitemap</h3>
<?php if(count($topRoutes) > 0) { ?>
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 mb5"><b>Popular Outstation Cab Routes</b> <span class="font-14">(One Way, Round Trip, Multi City Multi Day)</span></p></div>
			<div class="col-12">
				<ul>
					<?php
					foreach ($topRoutes as $route)
					{
						$rutUrl	= $this->getAbsoluteURL(["booking/routes", "route" => $route['rut_name']]);
						?>
						<li><img src="/images/img_trans.gif" alt="Book taxi from <?= $route['fromCityFullName']; ?> to <?= $route['toCityFullName']; ?>" width="1" height="1" class="route-icon"><a href="<?= $rutUrl; ?>" title="Book taxi from <?= $route['fromCityFullName']; ?> to <?= $route['toCityFullName']; ?>" target="_blank" ><?= $route['fromCityName']; ?> to <?= $route['toCityName']; ?></a></li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
</div>
</div>
</div>
</div>

<div class="container-fluid bg-gray">
<div class="container list-view-content">
<div class="row">
<div class="col-12">
<?php } if(count($topCities) > 0) { ?>
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 mb5"><b>Top Cities</b> <span class="font-14">(Outstation)</span></p></div>
			<div class="col-12">
				<ul>
					<?php
					foreach ($topCities as $topcity)
					{
						$cityUrl = $this->getAbsoluteURL(["index/outstationroutes", "city" => $topcity['fromCityAliasPath']]);
						?>
						<li><img src="/images/img_trans.gif" alt="Hire outstation cabs from <?= $topcity['fromCityFullName'] ?>" width="1" height="1" class="route-icon"><a href="<?= $cityUrl; ?>" target="_blank" title="Hire outstation cabs from <?= $topcity['fromCityFullName'] ?>"><?= $topcity['fromCityName'] ?></a></li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
</div>
</div>
</div>
</div>

<div class="container-fluid">
<div class="container list-view-content">
<div class="row">
<div class="col-12">
<?php } if(count($topCities) > 0) { ?>
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 mb5"><b>Car Rental</b> <span class="font-14">(Hourly Rentals, Outstation)</span></p></div>
			<div class="col-12">
				<ul>
					<?php
					foreach ($topCities as $topcity)
					{
						$cityUrl = $this->getAbsoluteURL(["index/cities", "city" => $topcity['fromCityAliasPath']]);
						?>
						<li><img src="/images/img_trans.gif" alt="Car rental in <?= $topcity['fromCityFullName'] ?>" width="1" height="1" class="route-icon"><a href="<?= $cityUrl; ?>" target="_blank" title="Car rental in <?= $topcity['fromCityFullName'] ?>"><?= $topcity['fromCityName'] ?></a></li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
</div>
</div>
</div>
</div>

<div class="container-fluid bg-gray">
<div class="container list-view-content">
<div class="row">
<div class="col-12">
<?php } if(count($topAirportTransfer) > 0) { ?>
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 mb5"><b>Airport Transfer</b> <span class="font-14">(Pickup & Drop)</span></p></div>
			<div class="col-12">
				<ul>	
					<?php
					foreach ($topAirportTransfer as $topairport)
					{
						$airportUrl = $this->getAbsoluteURL(["index/AirportTransfers", "city" => $topairport['fromCityAliasPath']]);
						?>
						<li><img src="/images/img_trans.gif" alt="Pickup & Drop to <?= $topairport['fromCityFullName'] ?>" width="1" height="1" class="route-icon"><a href="<?= $airportUrl; ?>" target="_blank" title="Pickup & Drop to <?= $topairport['fromCityFullName'] ?>"><?= $topairport['fromCityName'] ?></a></li>
						<?php
					}
					?>	
				</ul>
			</div>
		</div>
		<?php } ?>
</div>
</div>
</div>
</div>