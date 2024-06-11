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
						$flexiKey	 = VehicleCategory::SHARED_SEDAN_ECONOMIC;
						$cabData	 = SvcClassVhcCat::getVctSvcList("allDetail");
						$sortArray	 = [];
						foreach ($allQuot as $cabKey => $baseQuot)
						{
							$cab = $cabData[$cabKey];
							if ($baseQuot->success)
							{

							$arrQuoteCat = [];
							$catId = $cab['scv_vct_id'];
							$classId = $cab['scv_scc_id'];
							$cabId  = $cab['scv_id'];
							if (!isset($arrQuoteCat[$catId]))
							{
								$arrQuoteCat[$catId] = [];
							}

							if (!isset($arrQuoteCat[$catId][$classId]))
							{
								$arrQuoteCat[$catId][$classId] = [];
							}
							$arrQuoteCat[$catId][$classId][$cabId] = $baseQuot->routeRates->baseAmount;

							
							foreach ($arrQuoteCat as $catId => $catQuotes)
							{
								foreach ($catQuotes as $classId => $cabQuotes)
								{
									foreach ($cabQuotes as $cabId => $rate)
									{
										if (!isset($sortArray[$catId]) || $rate < $sortArray[$catId])
										{
											$sortArray[$catId] = $rate.','.$baseQuot->routeRates->ratePerKM;
										}
									}
								}
							}

						    

								if ($cabKey == VehicleCategory::SHARED_SEDAN_ECONOMIC)
								{
									$has_shared_sedan = 1;
								}
							}
								
						}

							unset($sortArray[5]);
							asort($sortArray);
                            ?>
<?php
$tncType = TncPoints::getTncIdsByStep(4);
$tncArr	 = TncPoints::getTypeContent($tncType);
$tncArr1 = json_decode($tncArr, true);

$has_shared_sedan	 = 0;
$rut_url			 = $aliash_path;
$arr_url			 = explode("-", $rut_url);
if ($type == 'route')
{
	/* @var $rmodel Route */
	?>
	<section id="section2">

		<div id="desc" class="feature">
			<div class="newline pt10">
				<h1 class="text-center mt0 font18">Travel from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?></h1>
				<p class="text-center">Trip Distance: <?= $model->bkg_trip_distance ?> KM  &nbsp;|  Travel time: <?= floor($rmodel->rut_estm_time / 60); ?> Hr</p>
				<p class="mb0 text-center">
					<?php
					if ($ratingCountArr['ratings'] > 0)
					{
						?>
						<a href="<?= Yii::app()->createUrl('route-rating/' . $rmodel->rut_name); ?>" style="color: #475F7B; font-weight: 500;">
							<small title="<?php echo $ratingCountArr['ratings'] . ' rating from ' . $ratingCountArr['cnt'] . ' reviews'; ?>">
								<?php
								$strRating	 = '';
								//print_r($ratingCountArr['ratings']);
								$rating_star = floor($ratingCountArr['ratings']);
								if ($rating_star > 0)
								{
									$strRating = '';
									for ($s = 0; $s < $rating_star; $s++)
									{
										$strRating .= '<amp-img width="24px" height="24px" class="lozad" src="/images/star-amp.png" alt=""></amp-img>';
									}
									if ($ratingCountArr['ratings'] > $rating_star)
									{
										$strRating .= '<amp-img width="24px" height="24px" class="lozad" src="/images/star-amp2.png" alt=""></amp-img><br>';
									}
									$strRating .= $ratingCountArr['cnt'] . ' reviews';
								}
								echo $strRating;
								?>

							</small></a>
	<?php } ?>
				</p>

	<!--					<h4 class="mt0" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi"><?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> ​​
	   Car Rental Prices & Options<br>
	</h4>-->
				<div class="edit-box"><a href="/book-cab/one-way/<?= Cities::getAliasPath($rmodel->rutFromCity->cty_id) ?>/<?= Cities::getAliasPath($rmodel->rutToCity->cty_id) ?>"><amp-img src="/images/img-2022/bx-edit.svg" alt="" width="18" height="18"></amp-img></a></div>
			</div>

			<input type="hidden" name="rutId" value="<?= $rmodel->rut_id ?>">

			<amp-selector class="tabs-with-flex" role="tablist">
			<div class="tap-contens">&nbsp;</div>
            
            <div id="tab1" role="tab" aria-controls="tabpanel1" option>Local</div>			
		
			<div id="tabpanel1" role="tabpanel" aria-labelledby="tab1">	
                <div class="justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/dayrental4/<?= strtolower($arr_url[0]);?>">Daily Rental on hourly basis</a></div>
							</div>
							
						</div>
							<amp-accordion id="my-accordiondr" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<p class="p15 pt0"><?= $tncArr1[66] ?></p>
								</section>
						</amp-accordion>	

					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/airport-pickup/<?= strtolower($arr_url[0]); ?>">Pick-up from airport</a></div>
							</div>
						</div>
	                    <amp-accordion id="my-accordionap" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<p class="p15 pt0"><?= $tncArr1[64] ?></p>
								</section>
						</amp-accordion>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/airport-drop/<?= strtolower($arr_url[0]); ?>">Drop-off to airport</a></div>
							</div>
						</div>
	
						<amp-accordion id="my-accordionad" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<p class="p15 pt0"><?= $tncArr1[65] ?></p>
								</section>
						</amp-accordion>
					</div>

				</div>

				<div class="row">
					<section>
							<h2 itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mt0 mb0 font-16 content" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi"><?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> Cab Rental Prices & Options</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">		
								<p class="content mb10">The cheapest car rental from  <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> cab ​will cost you <?= Filter::moneyFormatter($minPrice) ?> ​for a one way cab journey and for a round trip cab fare from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> will cost you <?= Filter::moneyFormatter($allQuot[1]->routeRates->ratePerKM) ?> /km.
									A one way chauffeur-driven car rental saves you money vs having to pay for a round trip.</p>
							</div>
						</section>					
						<div></div>

						<?php
						
							foreach ($sortArray as $key => $value)
							{
								if(empty($key) || $key == '')
								{
									continue;
								}
								$cab = VehicleCategory::model()->getModelDetailsbyId($key);
								$cabAarry = explode(',', $value);

								?>
								<div class="card-view">
									<div class="title-panel"><?= $cab["vct_label"]; ?></div>

									<div class="card-view-left">

										<amp-img src="/<?= $cab['vct_image']; ?>" alt="" width="130" height="67" ></amp-img>

										<p class="m0"><?php echo $cab['vct_desc']; ?></p>
									</div>
									<div class="card-view-right">
										<span class="card-text1">Base Fare</span><br>
										<span class="card-text2">&#x20B9;<?php ($cabKey == $flexiKey) ? print($allQuot[$cabKey]->flexxiRates[1]['subsBaseAmount']) : print($cabAarry[0]); ?></span>

										<div class="btn-book mt10"><a href="<?php echo Yii::app()->createAbsoluteUrl('/book-cab/one-way/' . strtolower($rmodel->rutFromCity->cty_name) . '/' . strtolower($rmodel->rutToCity->cty_name)); ?>" >Book</a></div>
									</div>
<div style="width: 100%; float: left; margin-top: 10px;"><amp-img src="/images/img-2022/bx-group.svg" alt="" width="12" height="12"></amp-img> <?php ($cabKey == $flexiKey) ? print('1 </span>') : print($cab['vct_capacity'] . '</span>') ?> <span class="pl5 pr5">|</span>
<amp-img src="/images/img-2022/bx-briefcase-alt.svg" alt="" width="12" height="12"></amp-img>
												<?php
													if ($cabKey == $flexiKey)
													{
														echo '1 </span>';
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
													?><span class="pl5 pr5">|</span>
<?php ($cabKey == $flexiKey) ? print('Fixed') : print('&#x20B9;' . $cabAarry[1]) ?> rate/km
</div>
									
								</div>
								<?php
							}
						//}
						?>

						<div class="mb20 mt20">

						</div>
					</div> 
			</div>
            
			<div id="tab2" role="tab" aria-controls="tabpanel2" option selected>Outstation</div>
				<div id="tabpanel2" role="tabpanel" aria-labelledby="tab2">
				
				<div class="justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16">
									<a href="/book-cab/one-way/<?= strtolower($arr_url[0]) ?>/<?= strtolower($arr_url[1]); ?>">One-way trip</a>

								</div>
							</div>

						</div>
						<amp-accordion id="my-accordionow" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<?= $tncArr1[61] ?>
								</section>
						</amp-accordion>
						
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/round-trip/<?= strtolower($arr_url[0]) ?>/<?= strtolower($arr_url[1]); ?>">Round trip</a></div>
							</div>
						</div>
						<amp-accordion id="my-accordionrt" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<?= $tncArr1[63] ?>
								</section>
						</amp-accordion>						
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/multi-city/<?= strtolower($arr_url[0]) ?>/<?= strtolower($arr_url[1]); ?>">Multi-city multi-day trip</a></div>
							</div>
                        </div>
						<amp-accordion id="my-accordionmt" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<?= $tncArr1[62] ?>
								</section>
						</amp-accordion>						
					</div>
				</div>

				<div class="row">
					<section>
							<h2 itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mt0 mb0 font-16 content" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi"><?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> Cab Rental Prices & Options</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">		
								<p class="content mb10">The cheapest car rental from  <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> cab ​will cost you <?= Filter::moneyFormatter($minPrice) ?> ​for a one way cab journey and for a round trip cab fare from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> will cost you <?= Filter::moneyFormatter($allQuot[1]->routeRates->ratePerKM) ?> /km.
									A one way chauffeur-driven car rental saves you money vs having to pay for a round trip.</p>
							</div>
						</section>					
						<div></div>

					
                        <?php
						
							foreach ($sortArray as $key => $value)
							{
								if(empty($key) || $key == '')
								{
									continue;
								}
								$cab = VehicleCategory::model()->getModelDetailsbyId($key);
								$cabAarry = explode(',', $value);
								?>
								<div class="card-view">
									<div class="title-panel"><?= $cab["vct_label"]; ?></div>

									<div class="card-view-left">

										<amp-img src="/<?= $cab['vct_image']; ?>" alt="" width="130" height="67" ></amp-img>

										<p class="m0"><?php echo $cab['vct_desc']?></p>
									</div>
									<div class="card-view-right">
										<span class="card-text1">Base Fare</span><br>
										<span class="card-text2">&#x20B9;<?php ($cabKey == $flexiKey) ? print($allQuot[$cabKey]->flexxiRates[1]['subsBaseAmount']) : print($cabAarry[0]); ?></span><br>

										<div class="btn-book mt10"><a href="<?php echo Yii::app()->createAbsoluteUrl('/book-cab/one-way/' . strtolower($rmodel->rutFromCity->cty_name) . '/' . strtolower($rmodel->rutToCity->cty_name)); ?>" >Book</a></div>
									</div>
<div style="width: 100%; float: left; margin-top: 10px;"><amp-img src="/images/img-2022/bx-group.svg" alt="" width="12" height="12"></amp-img> <?php ($cabKey == $flexiKey) ? print('1 </span>') : print($cab['vct_capacity'] . '</span>') ?> <span class="pl5 pr5">|</span> 
<amp-img src="/images/img-2022/bx-briefcase-alt.svg" alt="" width="12" height="12"></amp-img>
												<?php
													if ($cabKey == $flexiKey)
													{
														echo '1 </span>';
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
													?><span class="pl5 pr5">|</span>
<?php ($cabKey == $flexiKey) ? print('Fixed') : print('&#x20B9;' . $cabAarry[1]) ?> rate/km
</div>
<div></div>
								</div>
								<?php
							}
						//}
						?>

						<div class="mb20 mt20">

						</div>
					</div> 
			</div>
		
            
            
            
            
            
            
            

			<div id="tab3" role="tab" aria-controls="tabpanel3" option>Airport</div>
			<div id="tabpanel3" role="tabpanel" aria-labelledby="tab3">
				<div class="mb15 justify-center">
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
									<div class="mb-0 font-16"><a href="/book-cab/airport-pickup/<?= strtolower($arr_url[0]); ?>">Pick-up from airport (Local)</a></div>
							</div>
						</div>
	
					<amp-accordion id="my-accordionapl" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<p class="p15 pt0"><?= $tncArr1[64] ?></p>
								</section>
						</amp-accordion>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/airport-drop/<?= strtolower($arr_url[0]); ?>">Drop-off to airport(Local)</a></div>
							</div>
						</div>
	
					<amp-accordion id="my-accordionadl" disable-session-states>
								<section class="info-accordion">
									 <h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<p class="p15 pt0"><?= $tncArr1[65] ?></p>
								</section>
						</amp-accordion>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/inter-city/airport-pickup/<?= strtolower($arr_url[0]) ?>">Pick-up from airport (Outstation)</a></div>
							</div>
						</div>
	
					<amp-accordion id="my-accordionapo" disable-session-states>
								<section class="info-accordion">
									<h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<p class="p15 pt0"><?= $tncArr1[82] ?></p>
								</section>
						</amp-accordion>
					</div>
					<div class="ui-box">
						<div class="ui-inner-facetune flex-grow-1" style="width: 90%; position: relative; z-index: 9999;">
							<div class="ui-text-facetune">
								<div class="mb-0 font-16"><a href="/book-cab/inter-city/airport-drop/<?= strtolower($arr_url[0]) ?>">Drop-off to airport (Outstation)</a></div>
							</div>
						</div>
	
					<amp-accordion id="my-accordionado" disable-session-states>
								<section class="info-accordion">
									<h1 class="face-info mr5"><amp-img src="/images/bx-info-circle.png" alt="Info" width="24" height="24"></amp-img></h1>
									<p class="p15 pt0"><?= $tncArr1[83] ?></p>
								</section>
						</amp-accordion>
					</div>
				</div>
				
                <div class="row">
					<section>
							<h2 itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question" class="mt0 mb0 font-16 content" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi"><?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> Cab Rental Prices & Options</h2>
							<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer">		
								<p class="content mb10">The cheapest car rental from  <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> cab ​will cost you <?= Filter::moneyFormatter($minPrice) ?> ​for a one way cab journey and for a round trip cab fare from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> will cost you <?= Filter::moneyFormatter($allQuot[1]->routeRates->ratePerKM) ?> /km.
									A one way chauffeur-driven car rental saves you money vs having to pay for a round trip.</p>
							</div>
						</section>
						<div></div>

						<?php
						
							foreach ($sortArray as $key => $value)
							{
								if(empty($key) || $key == '')
								{
									continue;
								}
								$cab = VehicleCategory::model()->getModelDetailsbyId($key);
								$cabAarry = explode(',', $value);

								?>
								<div class="card-view">
									<div class="title-panel"><?= $cab["vct_label"]; ?></div>

									<div class="card-view-left">

										<amp-img src="/<?= $cab['vct_image']; ?>" alt="" width="130" height="67" ></amp-img>

										<p class="m0"><?php echo $cab['vct_desc']; ?></p>
									</div>
									<div class="card-view-right">
										<span class="card-text1">Base Fare</span><br>
										<span class="card-text2">&#x20B9;<?php ($cabKey == $flexiKey) ? print($allQuot[$cabKey]->flexxiRates[1]['subsBaseAmount']) : print($cabAarry[0]); ?></span>

										<div class="btn-book mt10"><a href="<?php echo Yii::app()->createAbsoluteUrl('/book-cab/one-way/' . strtolower($rmodel->rutFromCity->cty_name) . '/' . strtolower($rmodel->rutToCity->cty_name)); ?>" >Book</a></div>
									</div>
<div style="width: 100%; float: left; margin-top: 10px;"><amp-img src="/images/img-2022/bx-group.svg" alt="" width="12" height="12"></amp-img> <?php ($cabKey == $flexiKey) ? print('1 </span>') : print($cab['vct_capacity'] . '</span>') ?> <span class="pl5 pr5">|</span>
<amp-img src="/images/img-2022/bx-briefcase-alt.svg" alt="" width="12" height="12"></amp-img>
												<?php
													if ($cabKey == $flexiKey)
													{
														echo '1 </span>';
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
													?><span class="pl5 pr5">|</span>
<?php ($cabKey == $flexiKey) ? print('Fixed') : print('&#x20B9;' . $cabAarry[1]) ?> rate/km
</div>
									
								</div>
								<?php
							}
						//}
						?>

						<div class="mb20 mt20">

						</div>
					</div> 
			</div>
		</amp-selector>



			<div class="page-content text-center">
				<a href="https://play.google.com/store/apps/details?id=com.aaocab.client"><amp-img src="/images/app-google.png" alt="" width="137" height="49"></amp-img></a> <a href="https://itunes.apple.com/app/id1398759012?mt=8"><amp-img src="/images/app-store.png" alt="" width="137" height="49"></amp-img></a>
			</div>




<div class="content-boxed-widget" itemscope="" itemtype="https://schema.org/FAQPage">
			<div class="page-content mt30">
				<h2 class="font-18" title="Best way for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>">
			Best way for <?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?> Travel 
		</h2>
		<h3 class="font14 inline-block">Looking for a reliable and affordable way to book a cab or taxi from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h3>
		<p> Look no
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
						<p class="mb10"><b>Step 1:</b> Visit our <a href="http://www.aaocab.com" target="_blank">website</a> or <a href="http://www.aaocab.com/app" target="_blank">download</a> our user-friendly mobile app.</p>

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
				<h2 class="font-16 mb5" itemprop="name">What are the available cab options for <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
				<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
					<div itemprop="text">Cab options usually include hatchback, sedan, SUV, Innova and tempo travellers. You can choose based on your preferences and group size.</div>
				</div>
			</div>	
		</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">							
					<h2 class="font-16 mb5" itemprop="name">How much does it cost to book a cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">The cost of booking a cab can vary based on different cab type. However, the minimum base fare starts from <?= Filter::moneyFormatter($minPrice) ?>. It's best to check our booking platform for accurate pricing.</div>
					</div>
				</div>	
			</section>
			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">							
					<h2 class="font-16 mb5" itemprop="name">Can I book a one-way cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Yes, we offer one-way bookings for routes like <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>. You can choose to either book a <a href="/book-cab/one-way/<?php echo strtolower($arr_url[0]); ?>/<?php echo strtolower($arr_url[1]); ?>" style="display: inline-block;">one-way</a> trip or a <a href="/book-cab/round-trip/<?php echo strtolower($arr_url[0]); ?>/<?php echo strtolower($arr_url[1]); ?>" style="display: inline-block;">round-trip</a>, based on your travel needs.</div>
					</div>
				</div>
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">					
					<h2 class="font-16 mb5" itemprop="name">Can I pre-book a cab for a specific date and time?</h2>

					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Absolutely, we allow you to pre-book a cab for a specific date and time. This is especially useful if you want to ensure availability during peak travel periods.</p>
						</div>
					</div>	
			</section>
			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">				
					<h2 class="font-16 mb5" itemprop="name">Can I make stops or detours during the journey from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?>?</h2>

					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">We allow you to make stops or detours during the journey, but it's recommended to inform the driver in advance and discuss any additional charges that might apply. For round trips and multi-city trip, these are non-chargeable.</div>
					</div>
				</div>
			</section>
			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb5" itemprop="name">How long does it take to travel from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> by cab?</h2>

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
					<h2 class="font-16 mb5" itemprop="name">What is the distance between <?= $rmodel->rutFromCity->cty_name ?> and <?= $rmodel->rutToCity->cty_name ?>?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">The approximate distance is usually around <?= $model->bkg_trip_distance; ?> kilometers, depending on the route taken.</div>
					</div>
				</div>
			</section>
			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb5" itemprop="name">Are toll charges included in the cab fare?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Most of the time Toll charges are typically included in the initial fare for one way service. It will be mentioned in the fare breakup. If it is not included in the fare breakup, you'll need to pay them separately during the journey.</div>
					</div>
				</div>	
			</section>
			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">							
					<h2 class="font-16 mb5" itemprop="name">Can I choose a specific vehicle model?</h2>

					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">We allow you to request a specific vehicle, but it's not guaranteed. It depends on the availability at the time of booking. For any specific request you call our <a href="<?= Yii::app()->createUrl('scq/form') ?>" class="helpline">24X7 customer support</a>.</div>
					</div>
				</div>	
			</section>
			<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">	
					<h2 class="font-16 mb5" itemprop="name">Is it safe to travel by cab, especially for long distances?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">We prioritize passenger safety. Drivers are usually verified, and vehicles undergo safety checks.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb5" itemprop="name">Can I cancel or reschedule my booking?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Yes, you can usually cancel or reschedule your booking, but there might be cancellation fees depending on how close it is to the pickup time.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">							
					<h2 class="font-16 mb5" itemprop="name">How do I pay for the cab ride?</h2>

					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">We accept various forms of payment, including credit/debit cards, mobile wallets, UPI and sometimes cash. Payment options are usually available on the <a href="http://www.aaocab.com/app" target="_blank" style="display: inline-block;">app</a> or <a href="http://www.aaocab.com" target="_blank" style="display: inline-block;">website</a>. You can make a full or partial payment at the time of booking.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb5" itemprop="name">Do I need to carry any identification during the journey?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">Although it is not required but it's advisable to carry a government-issued ID card for verification purposes.</div>
					</div>
				</div>	
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">						
					<h2 class="font-16 mb5" itemprop="name">What if I have additional passengers or luggage?</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
						<div itemprop="text">When booking, you can specify the number of passengers and amount of luggage. Different cab types have varying capacities for passengers and luggage.</div>
					</div>
				</div>
			</section>
		<section>
				<div itemprop="mainEntity" itemscope="" itemtype="https://schema.org/Question">
					<h2 class="font-16 mb5" itemprop="name">What things to look for when you book an outstation cab from <?= $rmodel->rutFromCity->cty_name ?> ​to <?= $rmodel->rutToCity->cty_name ?> ​</h2>
					<div itemprop="acceptedAnswer" itemscope="" itemtype="https://schema.org/Answer" class="mb15">
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
			</div>
		</div>
		<?php
		if (!empty($arr_url))
		{
			$fromUrl = $arr_url[0];
			$toUrl	 = $arr_url[1];
			?>
			<div class="wrraper mt20">
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
					<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Book cabs in <?= $rmodel->rutFromCity->cty_name; ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
					<a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Book cabs in <?= $rmodel->rutToCity->cty_name ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/cabs/tempo_9_seater.jpg" alt=""></amp-img></div>
					<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Book Tempo Traveller in <?= $rmodel->rutFromCity->cty_name ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/cabs/tempo_12_seater.jpg" alt=""></amp-img></div>
					<a href="/tempo-traveller-rental/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Book Tempo Traveller in <?= $rmodel->rutToCity->cty_name ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
					<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Outstation taxi rental in <?= $rmodel->rutFromCity->cty_name; ?></a>
				</div>
				<div class="main_time border-blueline text-center main_time2">
					<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
					<a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Outstation taxi rental in <?= $rmodel->rutToCity->cty_name ?></a>
				</div>
				<?
				if ($rmodel->rutFromCity->cty_has_airport)
				{
					?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
						<a href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $fromUrl)); ?>">Airport transfer in <?= $rmodel->rutFromCity->cty_name ?></a>
					</div>
				<? } ?>
		<?
		if ($rmodel->rutToCity->cty_has_airport)
		{
			?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
						<a href="/airport-transfer/<?php echo strtolower(str_replace(' ', '-', $toUrl)); ?>">Airport transfer in <?= $rmodel->rutToCity->cty_name ?></a>
					</div>
		<? } ?>
		<?
		if ($model->is_luxury_from_city)
		{
			?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/car-bmw.jpg" alt=""></amp-img></div>
						<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $rmodel->rutFromCity->cty_name)); ?>">Luxury Car Rental in <?= $rmodel->rutFromCity->cty_name ?></a>
					</div>
		<? } ?>
		<?
		if ($model->is_luxury_to_city)
		{
			?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/car-bmw.jpg" alt=""></amp-img></div>
						<a href="/Luxury-car-rental/<?php echo strtolower(str_replace(' ', '-', $rmodel->rutToCity->cty_name)); ?>">Luxury Car Rental in <?= $rmodel->rutToCity->cty_name ?></a>
					</div>
		<? } ?>
				<?php
				if ($has_shared_sedan == 1)
				{
					?>
					<div class="main_time border-blueline text-center main_time2">
						<div class="car_box2"><amp-img width="130" height="67" class="lozad" src="/images/cabs/car-etios.jpg" alt=""></amp-img></div>
						<a href="/shared-taxi/<?php echo $mpath_url; ?>">Shared Sedan in <br/><?= $rmodel->rutFromCity->cty_name . '-' . $rmodel->rutToCity->cty_name ?></a>
					</div>
		<?php } ?>
			</div>
		<?php
	}
	?>

		<div class="page-content">
			<p class="mb0 font16 mt20">FAQs About <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Cabs</p>
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
								<h2 itemprop="name" class="font14 mb0"><b><?php echo trim($toCityQueReplace,'Q:'); ?></b></h2>
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

</div>
	</section>
	<?php
}
?>
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
	<div class="page-content list-view-content">
		<?php if(count($topRoutes) > 0) { ?>
		<div class="mb-1">
			<p class="font16 mt-1 merriw mb5"><b>Popular outstation cab routes</b></p>
				<ul class="pl0 mt0">
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
		
	</div>
