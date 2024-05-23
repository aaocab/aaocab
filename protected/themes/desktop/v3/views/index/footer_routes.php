<?php
$topCities = [];
$arrFCityData = Route::getCitiesForUrl();

$topRoutes = Route::getTopRouteByType(1, $arrFCityData);
if(count($arrFCityData) <= 0)
{
	$topCities = Route::getTopRouteByType(2);
}
?>
<div class="container-fluid pt-3">
	<div class="container p0 list-view-content mb-5">
		<?php if(count($topRoutes) > 0) { ?>
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 merriw mb5"><b>Popular outstation cab routes</b></p></div>
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
		<?php } if(count($topCities) > 0) { ?>
		<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 merriw mb5"><b>Top cities</b> <span class="font-12">(Hourly Rentals, Airport Transfers, Outstation)</span></p></div>
			<div class="col-12">
				<ul>
					<?php
					foreach ($topCities as $topcity)
					{
						$cityUrl = $this->getAbsoluteURL(["index/cities", "city" => $topcity['fromCityAliasPath']]);
						?>
						<li><img src="/images/img_trans.gif" alt="Car Rental from <?= $topcity['fromCityFullName'] ?>" width="1" height="1" class="route-icon"><a href="<?= $cityUrl; ?>" target="_blank" title="Car Rental from <?= $topcity['fromCityFullName'] ?>"><?= $topcity['fromCityName'] ?></a></li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
		<?php } ?>
		<!--<div class="row mb-1">
			<div class="col-12"><p class="font-a mt-1 merriw mb5"><b>Airport Transfer</b> <span class="font-12">(Pickup & drop)</span></p></div>
			<div class="col-12">
				<ul>	
					<?php
					foreach ($topAirportTransfer as $topairport)
					{
						?>
						<li><a href="<?= Yii::app()->createAbsoluteUrl("/airport-transfer/" . strtolower(str_replace(' ', '-', $topairport['trc_type_path']))); ?>" target="_blank" title="Airport taxi in <?= $topairport['fromCityName'] ?>"><?= $topairport['fromCityName'] ?></a></li>
						<?php
					}
					?>	
				</ul>
			</div>
		</div>-->
	</div>
</div>