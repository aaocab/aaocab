<style>
.list-view-content li{ width: 49%; display: inline-block; font-size: 15px;}
</style>
<?php
$topCities = [];
$arrFCityData = Route::getCitiesForUrl();

$topRoutes = Route::getTopRouteByType(1, $arrFCityData);
if(count($arrFCityData) <= 0)
{
	$topCities = Route::getTopRouteByType(2);
}

#$topAirportTransfer	 = Route::getTopRouteByType(3);

//echo "<pre>";
//print_r($arrFCityData);
//print_r($topRoutes);
//print_r($topCities);
?>
	<div class="content-boxed-widget list-view-content mt50 n">
		<?php if(count($topRoutes) > 0) { ?>
		<div class="row mb-1">
			<div class="col-12"><p class="font-16 mt-1 merriw mb5"><b>Popular outstation cab routes</b></p></div>
			<div class="col-12">
				<ul class="pl0">
					<?php
					foreach ($topRoutes as $route)
					{
						?>
						<li><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="route-icon"><a href="<?= Yii::app()->createAbsoluteUrl("/book-taxi/" . strtolower(str_replace(' ', '-', $route['trc_type_path']))); ?>" title="Book taxi from <?= $route['fromCityName']; ?> to <?= $route['toCityName']; ?>" target="_blank" > <?= $route['fromCityName']; ?> to <?= $route['toCityName']; ?></a></li>
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
						?>
						<li><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="route-icon"><a href="<?= Yii::app()->createAbsoluteUrl("/outstation-cabs/" . strtolower(str_replace(' ', '-', $topcity['trc_type_path']))); ?>" target="_blank" title="Outstation cabs from <?= $topcity['fromCityName'] ?>">Outstation cabs from <?= $topcity['fromCityName'] ?></a></li>
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