<style>
	p{ font-size: 14px; margin-bottom: 15px;}
	h1, h2, h3{ font-weight: 700; line-height: 22px;}
	.color-black{ color: #475f7b!important;}
	.out-accordion .accordion a{ width: 100%; line-height: 24px; text-align: left;}
	.out-accordion .accordion .button{ width: 30%; display: inline-block;}
	.call-cust a{ text-align: center!important; display: inline-block;}
	td{ padding: 5px 0;}
	.tab-styles .t-style{ background: #EEF3FA; width: 90%; margin: 0 auto; border-radius: 50px; border: #ccd9eb 1px solid; padding: 3px;}
	.tab-style a{ border-radius: 50px;}
	.tab-style a.active{ background: #5A8DEE;}
	.style-box-1 .card{ box-shadow: 0 0 0 0!important; border: #ddd 1px solid; border-radius: 5px; margin: 10px 20px;}
	.ui-inner-facetune a{ width: 100%; padding: 16px 15px 16px 15px; border-radius: 5px 0 0 5px; color: #475F7B; font-size: 15px; font-weight: 500;  text-align: left;}
	.ui-box{ width: 90%; margin: 0 auto; margin-top: 10px; box-shadow: none; border: #ddd 1px solid; min-height:inherit; border-radius: 5px; padding-bottom: 0px; position: relative;}
	.ui-box2{ margin-bottom: 10px; box-shadow: none; border: #ddd 1px solid; min-height:inherit; border-radius: 5px; padding-bottom: 0px; display: block; position: relative;}
	.info-2{ width: 26px; height: 26px; background: url(/images/css_sprites1.png?v=0.5) -42px -40px;}
	.face-info{ position: initial; text-align: left; position: absolute; top: 0; right: 0;}
	.face-info a{ background: #fff; color: #475F7B; padding: 10px 10px; display: inline-table; border-radius: 0 5px 5px 0;}
	.ui-box-main .face-info a{ background: #fff; color: #475F7B; padding: 2px 10px; display: block; border-radius: 0 5px 5px 0;}
	.ui-box-main .ui-inner-facetune a{ padding: 8px 15px 12px 15px;}
</style>

<?php
if (isset($jsonStructureProductSchema) && trim($jsonStructureProductSchema) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonStructureProductSchema; ?>
	</script>
<?php } ?><?php
if (isset($jsonStructureMarkupData) && trim($jsonStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonStructureMarkupData; ?>
	</script>
<?php } ?>
<?php
if (isset($routeBreadcumbStructureMarkupData) && trim($routeBreadcumbStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $routeBreadcumbStructureMarkupData; ?>
	</script>
<?php } ?>
<?php
if (isset($jsonproviderStructureMarkupData) && trim($jsonproviderStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonproviderStructureMarkupData; ?>
	</script>
<?php } ?>
<?php
$this->newHome	 = true;
$version		 = Yii::app()->params['siteJSVersion'];
$imageVersion	 = Yii::app()->params['imageVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/bookNow.min.js?v=' . $version);
$rut_url		 = $aliash_path;
$arr_url		 = explode("-", $rut_url);

$tncType = TncPoints::getTncIdsByStep(4);
$tncArr	 = TncPoints::getTypeContent($tncType);
$tncArr1 = json_decode($tncArr, true);
?>

<div class="container content-padding p10 bottom-0">
    <div class="above-overlay text-center">
		<?php
		/* @var $form CActiveForm|CWidget */
		$form1	 = $this->beginWidget('CActiveForm', array(
			'id'					 => 'bookingSosform-0',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => '',
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'action'				 => Yii::app()->createUrl('/index'),
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		/* @var $form CActiveForm */
		?>
        <input type="hidden" name="rutId" value="<?= $rmodel->rut_id ?>">

        <div class="text-right" style="position: absolute; right: 4px; top: 30px;">
            <a href="/book-cab/one-way/<?= Cities::getAliasPath($rmodel->rutFromCity->cty_id) ?>/<?= Cities::getAliasPath($rmodel->rutToCity->cty_id) ?>" style="z-index:9999;" class="color-black font-16"><img data-original="/images/img-2022/bx-edit.svg" width="18" height="18" alt="" class="preload-image inline-block"></a>
        </div>
		<?php $this->endWidget(); ?>

        <h1 class="top-0 font-18 mb0">Travel from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?></h1>

        <p class="bottom-0">
            Trip Distance: <?= $model->bkg_trip_distance ?> KM  &nbsp;|  Travel time: <?= floor($rmodel->rut_estm_time / 60); ?> Hr

        </p>
		<?php
		if ($ratingCountArr['ratings'] > 0)
		{
			?>
			<p class="display-ini">
				<a href="<?= Yii::app()->createUrl('route-rating/' . $rmodel->rut_name); ?>" aria-label="Rating"><small title="<?php echo $ratingCountArr['ratings'] . ' rating from ' . $ratingCountArr['cnt'] . ' reviews'; ?>">
						<?php
						$strRating	 = '';
						$rating_star = floor($ratingCountArr['ratings']);
						if ($rating_star > 0)
						{
							$strRating = '';
							for ($s = 0; $s < $rating_star; $s++)
							{
								$strRating .= '<img  class="preload-image" data-original="/images/star-amp.png" alt="Rating" width="36" height="36">';
							}
							if ($ratingCountArr['avgrating'] > $rating_star)
							{
								$strRating .= '<img  class="preload-image" data-original="/images/star-amp2.png" alt="Rating" width="36" height="36"></img>';
							}
						}
						echo $strRating;
						?>

					</small></a>
			</p>
			<p class="bottom-0"><?= $ratingCountArr['cnt'] ?> reviews</p>
		<?php } ?>
    </div>
    <div class="overlay bg-white"></div>
</div>
<?php
/* @var $form CActiveForm|CWidget */
$form				 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingSosform-1',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => '',
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
<?= $form->hiddenField($brtModel, 'brt_from_city_id', ['value' => $rmodel->rutFromCity->cty_id]); ?>
<?= $form->hiddenField($brtModel, 'brt_to_city_id', ['value' => $rmodel->rutToCity->cty_id]); ?>
<?= $form->hiddenField($brtModel, 'brt_pickup_date_date', ['value' => date("d/m/Y", strtotime("+2 day"))]); ?>
<?= $form->hiddenField($brtModel, 'brt_pickup_date_time', ['value' => '06:00 AM']); ?>
<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => '1']); ?>
<?php $this->endWidget(); ?>

<div class="container  mb0 mobile-type tab-styles">
	<div class="tab-style tabs pt10">
		<div class="t-style" data-active-tab-pill-background="bg-green-dark">
			<a href="#" data-tab-pill="tab-pill-1a" class="devPrimaryTab3 " style="width:32.6%;">Local</a>
			<a href="#" data-tab-pill="tab-pill-1a1" class="devPrimaryTab4 mainTab active" style="width:32.4%;">Outstation</a>
			<a href="#" data-tab-pill="tab-pill-1a2" class="devPrimaryTab5" style="width:32.4%;float:right;">Airport</a>
		</div>

		<div class="tab-pill-content" >
			<div class="tab-item devSecondaryTab3" id="tab-pill-1a1" style="display: block;" >

				<div class="justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getOneWayUrl($rmodel->rutFromCity->cty_id, $rmodel->rutToCity->cty_id) ?>">

								<div class="ui-text-facetune">
									<div class="mb-0 font-16">One-way trip</div>
								</div>
							</a>

						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="one-way-trip"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="one-way-trip" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div class="p15"><?= $tncArr1[61] ?></div>    
						</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getRoundTripUrl($rmodel->rutFromCity->cty_id, $rmodel->rutToCity->cty_id) ?>">

								<div class="ui-text-facetune">
									<div class="mb-0 font-16">Round trip</div>
								</div>
							</a>

						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="round-trip"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="round-trip" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div  class="p15"><?= $tncArr1[63] ?></div>    
						</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getMultiTripUrl($rmodel->rutFromCity->cty_id, $rmodel->rutToCity->cty_id) ?>">

								<div class="ui-text-facetune">
									<div class="mb-0 font-16">Multi-city multi-day trip</div>
								</div>
							</a>

						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="multi-trip"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="multi-trip" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div  class="p15"><?= $tncArr1[62] ?></div>    
						</div>
					</div>
				</div>


			</div>
			<div class="tab-item devSecondaryTab4" id="tab-pill-1a" style="display: none;">


				<div class="justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getDailyRentalUrl($rmodel->rutFromCity->cty_id, $rmodel->rutToCity->cty_id) ?>">
								<img data-src="/images/img-2022/g-icon-5.png" alt="" class="lozad img-fluid img-no">
								<div class="ui-text-facetune">
									<div class="mb-0 font-16">Daily Rental on hourly basis</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="dayrental-trip"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="dayrental-trip" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div  class="p15"><?= $tncArr1[66] ?></div>    
						</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getAirportLocalUrl($rmodel->rutFromCity->cty_id, '', 1) ?>">
								<img data-src="/images/img-2022/g-icon-3.png" alt="" class="lozad img-fluid img-no">
								<div class="ui-text-facetune">
									<div class="mb-0 font-16">Pick-up from airport</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="pickup-from-airport"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="pickup-from-airport" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div  class="p15"><?= $tncArr1[64] ?></div>    
						</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getAirportLocalUrl($rmodel->rutFromCity->cty_id, '', 2) ?>">
								<img data-src="/images/img-2022/g-icon-4.png" alt="" class="lozad img-fluid img-no">
								<div class="ui-text-facetune">
									<div class="mb-0 font-16">Drop-off to airport</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="dropoff-to-airport"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="dropoff-to-airport" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div  class="p15"><?= $tncArr1[65] ?></div>    
						</div>
					</div>

				</div>


			</div>
			<div class="tab-item devSecondaryTab3" id="tab-pill-1a2" style="display: none;" >
				<div class="mb15 justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getAirportLocalUrl($rmodel->rutFromCity->cty_id, '', 1) ?>">
								<img data-src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no lozad">
								<div class="ui-text-facetune">
									<div class="mb-0 font-16">Pick-up from airport (Local)</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="pickup-from-airport-local"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="pickup-from-airport-local" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div  class="p15"><?= $tncArr1[64] ?></div>    
						</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getAirportLocalUrl($rmodel->rutFromCity->cty_id, '', 2) ?>">
								<img data-src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no lozad">
								<div class="ui-text-facetune">
									<div class="mb-0 font-16">Drop-off to airport (Local)</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="dropoff-to-airport-local"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="dropoff-to-airport-local" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div  class="p15"><?= $tncArr1[65] ?></div>    
						</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getAirportOutstationUrl($rmodel->rutFromCity->cty_id, $rmodel->rutToCity->cty_id, 1) ?>">
								<img data-src="/images/img-2022/g-icon-3.png" alt="" class="img-fluid img-no lozad">
								<div class="ui-text-facetune">
									<div class="mb-0 font-16">Pick-up from airport (Outstation)</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="pickup-from-airport-outstation"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="pickup-from-airport-outstation" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div  class="p15"><?= $tncArr1[82] ?></div>    
						</div>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1">
							<a href="<?= $this->getAirportOutstationUrl($rmodel->rutFromCity->cty_id, $rmodel->rutToCity->cty_id, 2) ?>">
								<img data-src="/images/img-2022/g-icon-4.png" alt="" class="img-fluid img-no lozad">
								<div class="ui-text-facetune">
									<div class="mb-0 font-16">Drop-off to airport (Outstation)</div>
								</div>
							</a>
						</div>
						<div class="face-info"><a href="javascript:void(0);" data-menu="dropoff-to-airport-outstation"><img src="/images/img_trans.gif" alt="Info" width="1" height="1" class="info-2"></a></div>
						<div id="dropoff-to-airport-outstation" data-selected="menu-components" data-width="300" data-height="250" class="menu-box menu-modal">
							<div class="menu-title">&nbsp;
								<a href="#" class="menu-hide pt10 pl10"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
							</div>         
							<div  class="p15"><?= $tncArr1[83] ?></div>    
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="overlay opacity-90"></div>
	</div>
</div>
	<section class="mt10">
		<h2 itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mt0 mb0 font-16 content" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi"><?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> Cab Rental Prices & Options</h2>
<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">		
<p class="content mb10">The cheapest car rental from  <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> cab ​will cost you <?= Filter::moneyFormatter($minPrice) ?> ​for a one way cab journey and for a round trip cab fare from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> will cost you <?= Filter::moneyFormatter($allQuot[1]->routeRates->ratePerKM) ?> /km.
						A one way chauffeur-driven car rental saves you money vs having to pay for a round trip.</p>
	

<?php
$has_shared_sedan	 = 0;
if ($type == 'route')
{
	$flexiKey	 = VehicleCategory::SHARED_SEDAN_ECONOMIC;
	$cabData	 = SvcClassVhcCat::getVctSvcList("allDetail");

	foreach ($allQuot as $cabKey => $baseQuot)
	{
		$cab = $cabData[$cabKey];
		if ($baseQuot->success)
		{

			if ($cabKey == VehicleCategory::SHARED_SEDAN_ECONOMIC)
			{
				$has_shared_sedan = 1;
			}
			if($cab['scv_id'] == 72)
			{
				$priceRule = PriceRule::getByCity($baseQuot->sourceCity, 2, $cab['scv_id'], $baseQuot->destinationCity);
				$minRatePerKm = $priceRule->attributes['prr_rate_per_km'];
			}
			
			?>
			<input type="hidden" name="step" value="1">
			<div class="content-boxed-widget" style="overflow: hidden;">
				<div class="left-text"><span class="font-15"><b><?= $cab['label'] ?></b></span></div>
				<div class="one-half p5">
					<img data-original="/<?= $cab['vct_image']; ?>?v=<?= $imageVersion; ?>" alt="<?php echo $cab['vct_desc']; ?>" title="<?php echo $cab['vct_desc']; ?>" width="125" height="70" class="preload-image mb0 lozad">
				</div>
				<div class="one-half last-column text-right">
					<span class="uppercase" aria-label="Base Fare">Base Fare</span>
					<p class="mt0 mb0 font-20 weight500"><span></span><?php ($cabKey == $flexiKey) ? print($allQuot[$cabKey]->flexxiRates[1]['subsBaseAmount']) : print(Filter::moneyFormatter($baseQuot->routeRates->baseAmount)); ?></p>
					<a class="uppercase btn-green-blue default-link uppercase font-14 text-center" href="<?= $this->getOneWayUrlFromPath($arr_url[0], $arr_url[1]) ?>">Book</a>
				</div>
				<div class="clear"></div>
				<div class="left-text">
					<span class="font-13"><?php echo $cab['vct_desc']; ?></span> <br>
					<span class="font-13"><img data-original="/images/img-2022/bx-group.svg" alt="Person" class="inline-block preload-image" width="14" height="8"> <?php ($cabKey == $flexiKey) ? print('1 </span> Seat') : print($cab['vct_capacity'] . '+1</span>') ?>  | <img data-original="/images/img-2022/bx-briefcase-alt.svg" alt="Bag" class="inline-block preload-image" width="14" height="8"> <?php
						if ($cabKey == $flexiKey)
						{
							echo '1 </span> bag';
						}
						else
						{
							if ($cab['vct_big_bag_capacity'] > 0)
							{
								echo $cab['vct_big_bag_capacity'];
							}
							if ($cab['vct_small_bag_capacity'] > 0)
							{
								echo '+' . $cab['vct_small_bag_capacity'];
							}
							echo '</span>';
						}
						?></span>
				</div>
				<div class="content p0 bottom-15 text-center">

				</div>



			</div>
			<?php
		}
	}
	?>
</div>
</section>
	<div class="content-boxed-widget text-center pb5">
		<span class="display-ini"><a href="https://play.google.com/store/apps/details?id=com.aaocab.client"><img data-original="/images/app-google.png" class="preload-image" alt="Google play store" title="Google play store" width="150" height="49"></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8"><img data-original="/images/app-store.png"  class="preload-image" alt="App store" title="App store" width="150" height="49"></a></span>
	</div>
	<div class="content-boxed-widget" itemscope="" itemtype="https://schema.org/FAQPage">




		<h2 class="font-18" title="Best way for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>">
			Best way for <?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> Travel 
		</h2>


		<h3 class="font-14 inline-block">Looking for a reliable and affordable way to book a cab or taxi from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h3><p> Look no
			further than Gozo Cabs! We are India's leading online taxi and cab booking app, offering a wide range of services to meet all your travel needs. We offer a wide variety of cabs to choose from, including sedans,
			SUVs, Innova and tempo travellers. We also have a team of experienced drivers who will get
			you to your destination safely and on time. aaocab is the best cab booking app for cheap taxi booking. We offer competitive fares on all our services, and we also offer a variety of discounts and promotions.</p>

		<section>						
			<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">

				<h2 class="font-16" itemprop="name">Why choose aaocab?</h2>

				<div class="mb20" itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
					<div itemprop="text">					
							<h3 class="font-14 mb0 inline-block">1. Convenient and Easy Booking:</h3>
							<p style="display: inline;">Our cab booking platform is user-friendly, allowing you to book a cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> with just a few clicks. Say goodbye to long wait times and queues!</p><br>

							<h3 class="font-14 mb0 mt10 inline-block">2. Affordable Fares:</h3>
							<p style="display: inline;">We understand the value of your money, and our cab fares are budget friendly. Enjoy a cost-effective journey without compromising on comfort and safety.</p><br>
							<h3 class="font-14 mb0 mt10 inline-block">3. Reliable and Safe Travel:</h3>
							<p style="display: inline;">Your safety is our priority. Our fleet of cabs is well-maintained, and our experienced drivers ensure a secure and stress-free travel experience.</p><br>
							<h3 class="font-14 mb0 mt10 inline-block">4. 24/7 Availability:</h3>
							<p style="display: inline;">Whether it's an early morning or late-night journey, we are available round-the-clock to serve you. Plan your trip as per your convenience and schedule.</p><br>
							<h3 class="font-14 mb0 mt10 inline-block">5. Comfort:</h3>
							<p style="display: inline;">We have a variety of cabs to choose from, so you can find the perfect one for your needs.</p><br>
							<h3 class="font-14 mb0 mt10 inline-block">6. Experienced Drivers:</h3>
							<p class="mb40" style="display: inline;">Your safety is our utmost concern. Our drivers are experienced, licensed, and knowledgeable about the routes, making your travel secure and pleasant.</p><br>
						</div>				
</div>
			</div>
		</section>		

		<section>
		<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
			<h2 class="font-16" itemprop="name">How to Book a Cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?></h2>
			<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">						
				<div itemprop="text">				
						<p class="mb5">Booking a cab with us is quick and straightforward. Follow these simple steps:</p>
						<p class="mb10"><b>Step 1:</b> Visit our <a href="http://www.aaocab.com/book-cab" target="_blank">website</a> or <a href="http://www.aaocab.com/app" target="_blank">download</a> our user-friendly mobile app.</p>

						<p class="mb10"><b>Step 2:</b> Enter your travel details, including the date, time, and pick-up/drop-off locations (<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>).</p>

						<p class="mb10"><b>Step 3:</b> Select the <a href="/book-cab" target="_blank">cab type</a> that suits your requirements from our wide range of options.</p>

						<p class="mb10"><b>Step 4:</b> Review the fare details and make a secure online payment.</p>

						<p><b>Step 5:</b> Receive an instant confirmation with all the booking details.</p>
					</div>
			</div>
			</div>
		</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
					<h2 class="font-16 mb0" itemprop="name">What are the available cab options for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Cab options usually include hatchback, sedan, SUV, Innova and tempo travellers. You can choose based on your preferences and group size.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">							
					<h2 class="font-16 mb0" itemprop="name">How much does it cost to book a cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">The cost of booking a cab can vary based on different cab type. However, the minimum base fare starts from <?= Filter::moneyFormatter($minPrice) ?>. It's best to check our booking platform for accurate pricing.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">							
					<h2 class="font-16 mb0" itemprop="name">Can I book a one-way cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Yes, we offer one-way bookings for routes like <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>. You can choose to either book a <a href="/book-cab/one-way/<?php echo strtolower($arr_url[0]); ?>/<?php echo strtolower($arr_url[1]); ?>" style="display: inline-block;">one-way</a> trip or a <a href="/book-cab/round-trip/<?php echo strtolower($arr_url[0]); ?>/<?php echo strtolower($arr_url[1]); ?>" style="display: inline-block;">round-trip</a>, based on your travel needs.</div>
					</div>
				</div>
			</section>
		<section>
<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">					
			<h2 class="font-16 mb0" itemprop="name">Can I pre-book a cab for a specific date and time?</h2>
			
<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
<div itemprop="text">Absolutely, we allow you to pre-book a cab for a specific date and time. This is especially useful if you want to ensure availability during peak travel periods.</p>
</div>
</div>	
</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">				
					<h2 class="font-16 mb0" itemprop="name">Can I make stops or detours during the journey from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>

					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">We allow you to make stops or detours during the journey, but it's recommended to inform the driver in advance and discuss any additional charges that might apply. For round trips and multi-city trip, these are non-chargeable.</div>
					</div>
				</div>
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb0" itemprop="name">How long does it take to travel from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> by cab?</h2>

					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">The travel time can vary depending on traffic, weather, and the specific route taken. On average, the journey could take around 

							<?= floor(($rmodel->rut_estm_time / 60)); ?> hours
							<?php
							if (($rmodel->rut_estm_time % 60) > 0)
							{
								?> and <?= ($rmodel->rut_estm_time % 60); ?> minutes<?php } ?>
							.</div>
					</div>
				</div>
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
					<h2 class="font-16 mb0" itemprop="name">What is the distance between <?= $rmodel->rutFromCity->cty_name ?> and <?= $rmodel->rutToCity->cty_name ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">The approximate distance from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> is usually around <?= $model->bkg_trip_distance; ?> kilometers, depending on the route taken.</div>
					</div>
				</div>
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb0" itemprop="name">Are toll charges included in the cab fare?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Most of the time Toll charges are typically included in the initial fare for one way service. It will be mentioned in the fare breakup. If it is not included in the fare breakup, you'll need to pay them separately during the journey.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">							
					<h2 class="font-16 mb0" itemprop="name">Can I choose a specific vehicle model?</h2>

					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">We allow you to request a specific vehicle, but it's not guaranteed. It depends on the availability at the time of booking. For any specific request you call our <a href="javascript:void(0)" class="helpline inline-block">24X7 customer support</a>.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">	
					<h2 class="font-16 mb0" itemprop="name">Is it safe to travel by cab, especially for long distances?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">We prioritize passenger safety. Drivers are usually verified, and vehicles undergo safety checks.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb0" itemprop="name">Can I cancel or reschedule my booking?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Yes, you can usually cancel or reschedule your booking, but there might be cancellation fees depending on how close it is to the pickup time.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">							
					<h2 class="font-16 mb0" itemprop="name">How do I pay for the cab ride?</h2>

					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">We accept various forms of payment, including credit/debit cards, mobile wallets, UPI and sometimes cash. Payment options are usually available on the <a href="http://www.aaocab.com/app" target="_blank" style="display: inline-block;">app</a> or <a href="http://www.aaocab.com/book-cab" target="_blank" style="display: inline-block;">website</a>. You can make a full or partial payment at the time of booking.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb0" itemprop="name">Do I need to carry any identification during the journey?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Although it is not required but it's advisable to carry a government-issued ID card for verification purposes.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb0" itemprop="name">What if I have additional passengers or luggage?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">When booking, you can specify the number of passengers and amount of luggage. Different cab types have varying capacities for passengers and luggage.</div>
					</div>
				</div>
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
					<h2 class="font-16 mb5" itemprop="name">What things to look for when you book an outstation cab from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?> ​</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">
						<div itemprop="text">				
							<p>You should ensure that a commercial taxi (yellow license plate) is being provided. Only commercial vehicles can legally transport passengers from one city to another. Commercial passenger vehicles have yellow colored license plates and are required to have the necessary transport permits.</p>								
							<p>Find if the route you will travel on has tolls. If yes, are tolls included in your booking quotation</p>
							<p>When you are crossing state boundaries, state taxes are due. Preferably get a quotation with state taxes included else you are at the risk of being scammed again. Most taxi operators will pay for monthly or annual state tax on routes that they are commonly serving. By getting an inclusive quote, you are getting this benefit passed on to you. By getting a quote where taxes are excluded, you are going to have to pay the taxi operator and will most likely not get a receipt for the same
							</p>
							<p>Price is great but service is what matters. So try to focus on service at a reasonable price. When you go for the cheapest, you will ending up getting what you pay for.</p>
						</div>
					</div>
				</div>
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
					<h2 class="font-16 mb5" itemprop="name">Why is Gozo Cabs the best cab service for travel in India?</h2>

					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Gozo is continuously focused on being and staying as India's best taxi service for inter-city or outstation car hire with a driver. 
							Gozo cabs are the best cab service to hire <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> cab service. Gozo is generally the cheapest in most regions as we keep our margins low and we keep our quality high by ensuring that our cabs and providers are inspected regularly. At the time of onboarding, the taxi operators are whetted for proper licenses and their ability to meet our quality bar. We also provide ongoing training to our drivers.
							But most importantly Gozo strives to be the best with our support and customer service. Gozo has great reviews on Google & <a href="http://www.tripadvisor.com/Attraction_Review-g304551-d9976364-Reviews-Gozo_Cabs-New_Delhi_National_Capital_Territory_of_Delhi.html" class="color-black weight500">TripAdvisor</a>. Gozo was started with the focus of simplifying car hire for outstation trips and we specialize in one way cabs, round trip journeys and even multi city trips. Car rentals in <?= $rmodel->rutFromCity->cty_name ?> or Car rentals in <?= $rmodel->rutToCity->cty_name ?> are also provided. We offer daily car rentals and also airport transfers in most cities across India.</div>
					</div>
				</div>		
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
					<h2 class="font-16 mb5" itemprop="name">Which is the best <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?> taxi service?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">				
							There are many outstation taxi services that you can book either offline or online. Best is a relative term and it depends on what you prefer as a traveller. Most travelers prefer comfort, quality service at a reasonable price. 
							Be careful when trying to haggle for the lowest priced or cheapest cab as you could open yourself to the risk of operators cutting corners in service and also over laying with hidden charges. 
							Booking <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> taxi with aaocab offers hassle less and worry free online Taxi options.

						</div>
					</div>
				</div>
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
					<h2 class="mb5 font-16" itemprop="name"><?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> travel options</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">				
							There are many ways to travel from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?>. This includes travel by cabs, flight, bus, train or in a personal taxi or a shared cab / carpool
						</div>
					</div>
				</div>		
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
					<h2 class="font-16 mb5" itemprop="name">Ready for a seamless journey from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">		
						<div itemprop="text">		
							Our cab service ensures affordability, comfort, and safety every step of the way. Book your cab today and make the most of your travel experience. Experience convenience and affordability - reserve your cab now!
						</div>
					</div>
				</div>
			</section>


		<?php
		if (isset($allQuot[$flexiKey]->flexxiRates))
		{
			?>
			<h3 class="font-16" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Can I book shared taxi from  <?= $rmodel->rutFromCity->cty_name ?> ​​to <?= $rmodel->rutToCity->cty_name ?>?</h3>
<div itemprop="text">			
<p class="bottom-10">Yes, you can book <a href="http://www.aaocab.com/shared-taxi/<?= $arr_url[0] ?>-<?= $arr_url[1] ?> " class="color-black">AC shared taxi and shuttle services from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>.</a> Gozo SHARE is a outstation shared taxi service which you can use to sell unused seats in the taxi that you have already booked with us or if you are looking to buy unused seats and carpool in a cab that someone else has booked. 
				Gozo SHARE is our way to help customers save even more money when you are traveling by Gozo Cabs. If your travel plans are firm, book a Sedan Cab and use the option to “Book now & sell your unused seats”. If someone else is selling unused seats in their car, then you can simply book the seats that are being offered on our website.  </p>
</div>
			<br/>
			<?php
		}
		?>
		<?php
		$rut_url = $aliash_path;
		$arr_url = explode("-", $rut_url);
		if (!empty($arr_url))
		{
			$fromUrl = $arr_url[0];
			$toUrl	 = $arr_url[1];
			?>
			<div class="content p0">
			<div class="one-half text-center gallery bottom-20 ml5 mr5">
				<div class="polaroid-effect line-height16 height-01 font-11">
					<img data-original="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Book cabs in <?= $rmodel->rutFromCity->cty_name; ?>" title="Book cabs in <?= $rmodel->rutFromCity->cty_name; ?>">
					<a class="default-link color-black" href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>" class="color-highlight color-black">Book cabs in <?= $rmodel->rutFromCity->cty_name; ?></a>
				</div>
			</div>
			<div class="one-half text-center gallery bottom-20 ml5 mr5">
				<div class="polaroid-effect line-height16 height-01 font-11">
					<img data-original="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Book cabs in <?= $rmodel->rutToCity->cty_name ?>" title="Book cabs in <?= $rmodel->rutToCity->cty_name ?>">
					<a class="default-link color-black" href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>" class="color-highlight color-black">Book cabs in <?= $rmodel->rutToCity->cty_name ?></a>
				</div>
			</div>
</div>
<div class="clear"></div>
<div class="content p0">
			<div class="one-half text-center gallery bottom-20 ml5 mr5">
				<div class="polaroid-effect line-height16 height-01 font-11">
					<img data-original="/images/cabs/tempo_9_seater.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Book Tempo Traveller in <?= $rmodel->rutFromCity->cty_name ?>" title="Book Tempo Traveller in <?= $rmodel->rutFromCity->cty_name ?>">
					<a class="default-link color-black" href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>" class="color-highlight color-black">Book Tempo Traveller in <?= $rmodel->rutFromCity->cty_name ?></a>
				</div>
			</div>
			<div class="one-half text-center gallery bottom-20 ml5 mr5">
				<div class="polaroid-effect line-height16 height-01 font-11">
					<img data-original="/images/cabs/tempo_12_seater.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Book Tempo Traveller in <?= $rmodel->rutToCity->cty_name ?>" title="Book Tempo Traveller in <?= $rmodel->rutToCity->cty_name ?>">
					<a class="default-link color-black" href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>" class="color-highlight color-black">Book Tempo Traveller in <?= $rmodel->rutToCity->cty_name ?></a>
				</div>
			</div>
</div>
<div class="clear"></div>
<div class="content p0">
			<div class="one-half text-center gallery bottom-20 ml5 mr5">
				<div class="polaroid-effect line-height16 height-01 font-11">
					<img data-original="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Outstation taxi rental in <?= $rmodel->rutFromCity->cty_name; ?>" title="Outstation taxi rental in <?= $rmodel->rutFromCity->cty_name; ?>">
					<a class="default-link color-black" href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>" class="color-highlight color-black">Outstation taxi rental in <?= $rmodel->rutFromCity->cty_name; ?></a>
				</div>
			</div>
			<div class="one-half text-center gallery bottom-20 ml5 mr5">
				<div class="polaroid-effect line-height16 height-01 font-11">
					<img data-original="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Outstation taxi rental in <?= $rmodel->rutToCity->cty_name ?>" title="Outstation taxi rental in <?= $rmodel->rutToCity->cty_name ?>">
					<a class="default-link color-black" href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>" class="color-highlight color-black">Outstation taxi rental in <?= $rmodel->rutToCity->cty_name ?></a>
				</div>
			</div>
</div>
<div class="clear"></div>
<div class="content p0">
			<?php
			if ($rmodel->rutFromCity->cty_has_airport)
			{
				?>
				<div class="one-half text-center gallery bottom-20 ml5 mr5">
					<div class="polaroid-effect line-height16 height-01 font-11">
						<img data-original="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Airport transfer in <?= $rmodel->rutFromCity->cty_name ?>" title="Airport transfer in <?= $rmodel->rutFromCity->cty_name ?>">
						<a class="default-link color-black" href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Airport transfer in <?= $rmodel->rutFromCity->cty_name ?></a>
					</div>
				</div>
				<?php
			}
			if ($rmodel->rutToCity->cty_has_airport)
			{
				?>
				<div class="one-half text-center gallery bottom-20 ml5 mr5">
					<div class="polaroid-effect line-height16 height-01 font-11">
						<img data-original="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Airport transfer in <?= $rmodel->rutToCity->cty_name ?>" title="Airport transfer in <?= $rmodel->rutToCity->cty_name ?>">
						<a class="default-link color-black" href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Airport transfer in <?= $rmodel->rutToCity->cty_name ?></a>
					</div>
				</div>
				<?php
			}
			if ($model->is_luxury_from_city)
			{
				?>
</div>
<div class="clear"></div>
<div class="content p0">
				<div class="one-half text-center gallery bottom-20 ml5 mr5">
					<div class="polaroid-effect line-height16 height-01 font-11">
						<img data-original="/images/car-bmw.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Luxury Car Rental in <?= $rmodel->rutFromCity->cty_name ?>" title="Luxury Car Rental in <?= $rmodel->rutFromCity->cty_name ?>">
						<a class="default-link color-black" href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Luxury Car Rental in <?= $rmodel->rutFromCity->cty_name ?></a>
					</div>
				</div>
				<?php
			}
			if ($model->is_luxury_to_city)
			{
				?>
				<div class="one-half text-center gallery bottom-20 ml5 mr5">
					<div class="polaroid-effect line-height16 height-01 font-11">
						<img data-original="/images/car-bmw.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="Luxury Car Rental in <?= $rmodel->rutToCity->cty_name ?>" title="Luxury Car Rental in <?= $rmodel->rutToCity->cty_name ?>">
						<a class="default-link color-black" href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Luxury Car Rental in <?= $rmodel->rutToCity->cty_name ?></a>
					</div>
				</div>
				<?php
			}
			if ($has_shared_sedan == 1)
			{
				?>
				<div class="one-half text-center gallery bottom-20 ml5 mr5">
					<div class="polaroid-effect line-height16 height-01 font-11">
						<img data-original="/images/cabs/car-etios.jpg?v=<?= $imageVersion; ?>" width="125" height="70" class="preload-image bottom-0 lozad" alt="<?= $rmodel->rutFromCity->cty_name . '-' . $rmodel->rutToCity->cty_name ?>" title="<?= $rmodel->rutFromCity->cty_name . '-' . $rmodel->rutToCity->cty_name ?>">
						<a class="default-link color-black" href="/shared-taxi/<?php echo $mpath_url; ?>">Shared Sedan in <br/><?= $rmodel->rutFromCity->cty_name . '-' . $rmodel->rutToCity->cty_name ?></a>
					</div>
				</div>
				<?php
			}
			?>
</div>
			<div class="clear"></div>
			<?php
		}
		?>	

		<div>
			<p class="mb0 font-16">FAQs About <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Cabs</p>
			<div class="pt10">
                				<?php
				$faqArray = Faqs::getDetails(1);
				foreach ($faqArray as $key => $value)
				{
					//print'<pre>';print_r($value);
					$fromCityQueReplace	 = str_replace('{#fromCity#}', $rmodel->rutFromCity->cty_name, $value['faq_question']);
					$toCityQueReplace	 = str_replace('{#toCity#}', $rmodel->rutToCity->cty_name, $fromCityQueReplace);

					$fromCityAnsReplace	 = str_replace('{#fromCity#}', $rmodel->rutFromCity->cty_name, $value['faq_answer']);
					$toCityAnsReplace	 = str_replace('{#toCity#}', $rmodel->rutToCity->cty_name, $fromCityAnsReplace);

					$bookingAmount	 = str_replace('{#bookingAmount#}', Filter::moneyFormatter($minPrice), $toCityAnsReplace);
					//$perKmCharge	 = str_replace('{#perKmCharge#}', $allQuot[1]->routeRates->ratePerKM, $bookingAmount);
					$perKmCharge	 = str_replace('{#perKmCharge#}', Filter::moneyFormatter($minRatePerKm), $bookingAmount);
					$tripDistance	 = str_replace('{#tripDistance#}', $model->bkg_trip_distance, $perKmCharge);
					$tripDuration	 = floor(($rmodel->rut_estm_time / 60)) . ' hours ';
					if (($rmodel->rut_estm_time % 60) > 0)
					{
						$tripDuration .= 'and ' . ($rmodel->rut_estm_time % 60) . ' minutes';
					}

					$tripDuration = str_replace('{#tripDuration#}', $tripDuration, $tripDistance);
					?>
					<div>
						<div class="mb15" itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
							<div>
								<h2 itemprop="name" class="font-14 mb0"><b><?php echo trim($toCityQueReplace,'Q:'); ?></b></h2>
							</div>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="">
								<div itemprop="text">
									<?php echo trim($tripDuration,'A.'); ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>

		<?php
		$citylinks2 = CityLinks::model()->getCitylinks($model->bkg_to_city_id, 2);
		if (count($citylinks2) > 0)
		{
			?>
			<h3 class="font-16" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Popular Destinations from <?= $rmodel->rutToCity->cty_name ?></h3>


			<?php
			foreach ($citylinks2 as $citylink2)
			{
				?>

				<a href="<?= $citylink2->cln_url ?>" target="_blank"><?= $citylink2->cln_title ?></a> &nbsp; 

			<?php } ?>

			<br/><br/>

		<?php } ?>

	</div>

	<?php
}
?>

<div id="menu-list-modal3" data-selected="menu-components" data-width="300" data-height="420" class="menu-box menu-modal">
    <div class="menu-title border-none pt0"><a href="#" class="menu-hide mt25 n" style="z-index: 9;"><i class="fa fa-times"></i></a>
    </div>
    <div id="addLinkDetails"></div>
</div>
<div id="menu-list-modal4" data-selected="menu-components" data-width="300" data-height="420" class="menu-box menu-modal">
    <div class="menu-title border-none pt0"><a href="#" class="menu-hide mt25 n" style="z-index: 9;"><i class="fa fa-times"></i></a>
    </div>
    <div id="addPlaceDetails"></div>
</div>

<script>
	var booknow = new BookNow();
	function mapInitialize() {
		var map;
		var directionsDisplay = new google.maps.DirectionsRenderer();
		var directionsService = new google.maps.DirectionsService();
		var mapOptions = {
			zoom: 6,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: new google.maps.LatLng(30.73331, 76.77942),
			mapTypeControl: false
		};
		map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		directionsDisplay.setMap(map);
		//$('#map_canvas').css('height', $('#desc').height());
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
		script.async = true;
		script.type = 'text/javascript';
		script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
				'callback=mapInitialize&key=<?= $api ?>';
		document.body.appendChild(script);
	}
	//   window.onload = loadScript;


	function add_links(city_id, cat) {

		var href1 = '<?= Yii::app()->createUrl("city/citylinks") ?>';
		jQuery.ajax({'type': 'GET', 'url': href1,
			'data': {'cid': city_id, 'cat': cat},
			success: function (data) {
				document.getElementById("addLinkDetails").innerHTML = data;
			}
		});
		return false;
	}

	function add_place(city_id, cat) {

		var href1 = '<?= Yii::app()->createUrl("city/cityplaces") ?>';
		jQuery.ajax({'type': 'GET', 'url': href1,
			'data': {'cid': city_id, 'cat': cat},
			success: function (data) {
				//alert("gfdg");
				document.getElementById("addPlaceDetails").innerHTML = data;
			}
		});
		return false;
	}

	function book_now() {
		jQuery('#bookingSform').submit();
	}


//    const obz = lozad(document.getElementById("map_canvas"), {
//        load: function (el) {
//            loadScript();
//        }
//    });
//    obz.observe();

	jQuery(document).on("click", "#sbmtbtn", function () {
		var ctitle = $('#CityLinks_cln_title').val();
		var curl = $('#CityLinks_cln_url').val();
		var url_validate = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		if (!url_validate.test(curl) || ctitle == '' || curl == '') {
			booknow.showErrorMsg("Please Enter valid name and url.");
		} else {
			$.ajax({
				"type": "POST",
				"dataType": "json",
				"url": " <?= CHtml::normalizeUrl(Yii::app()->createUrl('city/citylinks')) ?> ",
				"data": jQuery('#citylinks-form').serialize(),
				"success": function (data2) {
					booknow.showErrorMsg("Your resources has been added successfully.");
					//console.log(data2);
					location.reload();
				},
				error: function (xhr, ajaxOptions, thrownError)
				{
					booknow.showErrorMsg(xhr.status);
					booknow.showErrorMsg(thrownError);
				}
			});
		}
	});

	jQuery(document).on("click", "#sbmtbtn2", function () {
		var cplace = $('#CityPlaces_cpl_places').val();
		var curl = $('#CityPlaces_cpl_url').val();
		var url_validate = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		if (!url_validate.test(curl) || cplace == '' || curl == '') {
			booknow.showErrorMsg("Please Enter valid name and url.");
		} else {
			$.ajax({
				"type": "POST",
				"dataType": "json",
				"url": " <?= CHtml::normalizeUrl(Yii::app()->createUrl('city/cityplaces')) ?> ",
				"data": jQuery('#cityplace-form').serialize(),
				"success": function (data2) {
					booknow.showErrorMsg("Your place has been added successfully.");
					//console.log(data2);
					location.reload();
				},
				error: function (xhr, ajaxOptions, thrownError)
				{
					booknow.showErrorMsg(xhr.status);
					booknow.showErrorMsg(thrownError);
				}
			});
		}
	});
</script>
