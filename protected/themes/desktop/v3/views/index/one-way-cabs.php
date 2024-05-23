<style>
	.table th, .table td{
		padding: 8px;
	}
.selectize-input ::placeholder{ color: #475F7B!important;}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-12 pt10 pb10">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<p class="merriw font-16 text-center"><b>The best & economical One way intercity cab services India. Book or get a quote now</b></p>
						<div class="card1 mb0 pt5">
							<div class="card-body1 pb0"><?#= $this->renderPartial('oneWaySearch', array('model' => $model), true, FALSE); ?>
								<?php $this->renderDynamic('renderPartial',"application.themes.desktop.v3.views.index.oneWaySearch", array('model' => $model), true); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-12 mb20 mt30 ul-style-c">
			<div class="bg-white-box">
				<h2 class="font-20 merriw"><b>Go GozoCabs for One Way Taxi Drops on 2000+ Routes in India</b></h2>
				<p>Gozo was founded on the core idea of simplifying taxi travel for all Indians. Since starting in November 2015, We have consistently enabled one-way taxi travel on more and more routes across India. As of 2018, Gozo has served across states in India and we are now the outstation travel provider with the widest reach. Our principals remain the same - to bring you the Best prices, with Great quality, 24x7 and Nationwide. Taking a one-way cab can be sometimes upto 50% cheaper than traveling one-way.</p>
				<p>We encourage customers to book their one-way trips atleast 7 days ahead of the trip so it improves our ability to offer you the lowest prices and also find you the best car suited to your needs.</p>
				<p>With Gozo you have the option to <a href="https://www.gozocabs.com/">book one-way, round trip and multi-way transfers</a> across the nation.<br>
					If you are looking for airport or railway drop services, then Gozo can serve you all over India and is the best car rental platform to hire One way taxi, airport drops or railway station pickups at the most reasonable rates. The above list of routes is just a sample of popular routes in each major region of India. If there is anything we can do to serve you better, please call our customer support center or message us via the website chat. We are India’s leader in one-way travel where you will Pay one-way when you Travel one-way. Hassle free at the best prices.</p>
				<h3 class="font-18 merriw mt30"><b>Popular One Way Taxi Services in India</b></h3>
				<p>Gozo is an outstation taxi services specialist across India. In addition to one-way AC cab and <a href="<?= Yii::app()->baseUrl; ?>/GozoSHARE">AC outstation shared taxi</a> services we also provide round trips, packaged tours and all sorts of customization to suit your travel needs across all cities and towns in India. 
					<br>Just as an example, When traveling from Bangalore to Mysore you can consider all choices for travel from <a href="<?= Yii::app()->baseUrl; ?>/book-taxi/bangalore-mysore">Bangalore to Mysore,</a> book one-way cabs, <a href="<?= Yii::app()->baseUrl; ?>/tempo-traveller-rental/bangalore">book tempo travelers from Bangalore</a> or <a href="<?= Yii::app()->baseUrl; ?>/car-rental/mysore">Car rentals in or around Mysore</a> and even arrange airport or railway station transfers. 
				</p>

				<div class="row">
					<div class="col-12">
						<div class="row">
							<div class="col-12 col-xl-6">
								<div class="card mb15">
									<div class="card-body p15">
										<h3 class="font-14 merriw mb15 weight600">Book one way cab service in North India </h3>
										<?php
										foreach ($modelNorthRegion as $val)
										{
											?>
											<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $val['ctyAliasPath']; ?>">
												<h4 class="panel-title font-14">
													<img src="/images/bx-arrowright.svg" alt="img" width="14" height="14" class="mr5"><?php echo $val['ctyName']; ?>
												</h4>
											</a>
											<div id="<?php echo $val['ctyAliasPath']; ?>" class="panel-collapse collapse">
												<?php
												$ctyId		 = $val['ctyId'];
												$topRoutes	 = Yii::app()->cache->get("getPriceByRoute$ctyId");
												if ($topRoutes === false)
												{
													$topRoutes = Route::model()->getRoutesByCityId($ctyId);
													Yii::app()->cache->set("getPriceByRoute$ctyId", $topRoutes, 21600);
												}
												?>
												<table class="table table-striped mb0">
													<?php
													foreach ($topRoutes as $topRouteCitis)
													{
														$topRouteCitiesId = $topRouteCitis['rut_id'];

														$routeQuot = Yii::app()->cache->get("getPriceByRoute$topRouteCitiesId");
														if ($routeQuot === false)
														{
															$routeQuot = Route::getBasicOnewayQuote($topRouteCitis['rut_from_city_id'], $topRouteCitis['rut_to_city_id']);
															Yii::app()->cache->set("getPriceByRoute$topRouteCitiesId", $routeQuot, 21600);
														}

														$compactPrice	 = ($routeQuot[1]->success && $routeQuot[1]->routeRates->baseAmount > 0) ? $routeQuot[1]->routeRates->baseAmount : 0;
														$rutUrl			 = $this->getAbsoluteURL(["booking/routes", "route" => $topRouteCitis['rut_name']]);
														$bookUrl		 = $this->getOneWayUrlFromPath($topRouteCitis['from_city_alias_path'], $topRouteCitis['to_city_alias_path']);
														?> 
														<tr>
															<td width="60%" style="font-weight:400;"><a href="<?= $rutUrl ?>" target="_blank"><?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> cab</a></td>
															<td align="right">&#x20B9;<b><?php echo $compactPrice; ?></b>&nbsp;<a href="<?= $bookUrl ?>" class="btn btn-primary btn-sm pl10 pr10 mt0 bkbtn ml10">Book Now</a></td>
														</tr>  

													<?php } ?>
												</table>
												<div class="col-12 text-right mt10 mb10"><a href="/book-cab/one-way/<?= $val['ctyAliasPath'] ?>">Check more rates..</a></div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="col-12 col-xl-6" style="display: grid;">
								<div class="card mb15">
									<div class="card-body p15">
										<h3 class="font-14 merriw mb15 weight600">Book one way cab service in South India</h3>
										<?php
										foreach ($modelSouthRegion as $val)
										{
											?>
											<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $val['ctyAliasPath']; ?>">
												<h4 class="panel-title font-14">
													<img src="/images/bx-arrowright.svg" alt="img" width="14" height="14" class="mr5"><?php echo $val['ctyName']; ?>
												</h4>
											</a>
											<div id="<?php echo $val['ctyAliasPath']; ?>" class="panel-collapse collapse">
												<?php
												$ctyId		 = $val['ctyId'];
												$topRoutes	 = Yii::app()->cache->get("getPriceByRoute$ctyId");
												if ($topRoutes === false)
												{
													$topRoutes = Route::model()->getRoutesByCityId($ctyId);
													Yii::app()->cache->set("getPriceByRoute$ctyId", $topRoutes, 21600);
												}
												?>
												<table class="table table-striped mb0">
													<?php
													foreach ($topRoutes as $topRouteCitis)
													{
														$topRouteCitiesId	 = $topRouteCitis['rut_id'];
														$routeQuot			 = Yii::app()->cache->get("getPriceByRoute$topRouteCitiesId");
														if ($routeQuot === false)
														{
															$routeQuot = Route::getBasicOnewayQuote($topRouteCitis['rut_from_city_id'], $topRouteCitis['rut_to_city_id']);
															Yii::app()->cache->set("getPriceByRoute$topRouteCitiesId", $routeQuot, 21600);
														}
														$compactPrice	 = ($routeQuot[1]->success && $routeQuot[1]->routeRates->baseAmount > 0) ? $routeQuot[1]->routeRates->baseAmount : 0;
														$rutUrl			 = $this->getAbsoluteURL(["booking/routes", "route" => $topRouteCitis['rut_name']]);
														$bookUrl		 = $this->getOneWayUrlFromPath($topRouteCitis['from_city_alias_path'], $topRouteCitis['to_city_alias_path']);
														?> 
														<tr>
															<td width="60%" style="font-weight:400;"><a href="<?= $rutUrl ?>" target="_blank"><?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> cab</a></td>
															<td align="right">&#x20B9;<b><?php echo $compactPrice; ?></b>&nbsp;<a href="<?= $bookUrl ?>" class="btn btn-primary btn-sm pl10 pr10 mt0 bkbtn ml10">Book Now</a></td>
														</tr> 
													<?php } ?>
												</table>
												<div class="col-12 text-right mt10 mb10"><a href="/book-cab/one-way/<?= $val['ctyAliasPath'] ?>">Check more rates..</a></div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
				<div class="row">
					<div class="col-12 col-xl-6">
						<div class="card mb15">
							<div class="card-body p15">
								<h3 class="font-14 merriw mb15 weight600">Book one way cab service in West India</h3>
								<?php
								foreach ($modelWestRegion as $val)
								{
									?>
									<a data-toggle="collapse" style="display:block" data-parent="#accordion" href="#<?php echo $val['ctyAliasPath']; ?>">
										<h4 class="panel-title font-14">
											<img src="/images/bx-arrowright.svg" alt="img" width="14" height="14" class="mr5"><?php echo $val['ctyName']; ?>
										</h4>
									</a>
									<div id="<?php echo $val['ctyAliasPath']; ?>" class="panel-collapse collapse">
										<?php
										$ctyId		 = $val['ctyId'];
										$topRoutes	 = Yii::app()->cache->get("getPriceByRoute$ctyId");
										if ($topRoutes === false)
										{
											$topRoutes = Route::model()->getRoutesByCityId($ctyId);
											Yii::app()->cache->set("getPriceByRoute$ctyId", $topRoutes, 21600);
										}
										?>
										<table class="table table-striped mb0">
											<?php
											foreach ($topRoutes as $topRouteCitis)
											{
												$topRouteCitiesId	 = $topRouteCitis['rut_id'];
												$routeQuot			 = Yii::app()->cache->get("getPriceByRoute$topRouteCitiesId");
												if ($routeQuot === false)
												{
													$routeQuot = Route::getBasicOnewayQuote($topRouteCitis['rut_from_city_id'], $topRouteCitis['rut_to_city_id']);
													Yii::app()->cache->set("getPriceByRoute$topRouteCitiesId", $routeQuot, 21600);
												}
												$compactPrice	 = ($routeQuot[1]->success && $routeQuot[1]->routeRates->baseAmount > 0) ? $routeQuot[1]->routeRates->baseAmount : 0;
												$rutUrl			 = $this->getAbsoluteURL(["booking/routes", "route" => $topRouteCitis['rut_name']]);
												$bookUrl		 = $this->getOneWayUrlFromPath($topRouteCitis['from_city_alias_path'], $topRouteCitis['to_city_alias_path']);
												?> 
												<tr>
													<td width="60%" style="font-weight:400;"><a href="<?= $rutUrl ?>" target="_blank"><?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> cab</a></td>
													<td align="right">&#x20B9;<b><?php echo $compactPrice; ?></b>&nbsp;<a href="<?= $bookUrl ?>" class="btn btn-primary btn-sm pl10 pr10 mt0 bkbtn ml10">Book Now</a></td>
												</tr>
											<?php } ?>
										</table>
										<div class="col-12 text-right mt10 mb10"><a href="/book-cab/one-way/<?= $val['ctyAliasPath'] ?>">Check more rates..</a></div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="col-12 col-xl-6" style="display: grid;">
						<div class="card mb15">
							<div class="card-body p15">
								<h3 class="font-14 merriw mb15 weight600" >Book one way cab service in East India</h3>
								<?php
								foreach ($modelEastRegion as $val)
								{
									?>
									<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $val['ctyAliasPath']; ?>">
										<h4 class="font-14">
											<img src="/images/bx-arrowright.svg" alt="img" width="14" height="14" class="mr5"><?php echo $val['ctyName']; ?>
										</h4>
									</a>
									<div id="<?php echo $val['ctyAliasPath']; ?>" class="panel-collapse collapse">
										<?php
										$ctyId		 = $val['ctyId'];
										$topRoutes	 = Yii::app()->cache->get("getPriceByRoute$ctyId");
										if ($topRoutes === false)
										{
											$topRoutes = Route::model()->getRoutesByCityId($ctyId);
											Yii::app()->cache->set("getPriceByRoute$ctyId", $topRoutes, 21600);
										}
										?>
										<table class="table table-striped mb0">
											<?php
											foreach ($topRoutes as $topRouteCitis)
											{
												$topRouteCitiesId	 = $topRouteCitis['rut_id'];
												$routeQuot			 = Yii::app()->cache->get("getPriceByRoute$topRouteCitiesId");
												if ($routeQuot === false)
												{
													$routeQuot = Route::getBasicOnewayQuote($topRouteCitis['rut_from_city_id'], $topRouteCitis['rut_to_city_id']);
													Yii::app()->cache->set("getPriceByRoute$topRouteCitiesId", $routeQuot, 21600);
												}
												$compactPrice	 = ($routeQuot[1]->success && $routeQuot[1]->routeRates->baseAmount > 0) ? $routeQuot[1]->routeRates->baseAmount : 0;
												$rutUrl			 = $this->getAbsoluteURL(["booking/routes", "route" => $topRouteCitis['rut_name']]);
												$bookUrl		 = $this->getOneWayUrlFromPath($topRouteCitis['from_city_alias_path'], $topRouteCitis['to_city_alias_path']);
												?> 
												<tr>
													<td width="60%" style="font-weight:400;"><a href="<?= $rutUrl ?>" target="_blank"><?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> cabs</a></td>
													<td align="right">&#x20B9;<b><?php echo $compactPrice; ?></b>&nbsp;<a href="<?= $bookUrl ?>" class="btn btn-primary btn-sm pl10 pr10 mt0 bkbtn ml10">Book Now</a></td>
												</tr> 
											<?php } ?>
										</table>
										<div class="col-12 text-right mt10 mb10"><a href="/book-cab/one-way/<?= $val['ctyAliasPath'] ?>">Check more rates..</a></div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
                </div>
            </div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-12 mb20 ul-style-c">
			<div class="bg-white-box">
				<h3 class="font-18 merriw"><b>Outstation travel in India</b></h3>
				<p>Gozo is focused completely on providing the most convenient and cost efficient travel between cities all over India. We work with local providers to enable low-cost, high service taxi travel which we strive to make cheaper and more convenient that traveling by bus or train. When traveling alone you can use GozoSHARE outstation shared taxi services and <a href="<?= Yii::app()->baseUrl; ?>/GozoSHARE">travel across India even on a low budget.</a>
					If you are planning to travel in large numbers along with your friends or family, then booking a SUV or tempo-traveller as your One way taxi service will be the best option. Gozo offers you the various options for cars - Compact, Sedan, SUV, Tempo-traveller or Luxury vehicles - based on your budget and the number of people travelling.</p>

				<h3 class="font-18 merriw mt30"><b>Common questions when taking a One Way Private or Shared Taxi Service</b></h3>
				<p>At Gozo, we want you to be completely at ease and are happy to help you decide on the best options for your outstation / intercity travel.</br>
					Read our comprehensive FAQ for more <a href="<?= Yii::app()->baseUrl; ?>/faq">answers about outstation travel and booking a taxi in India</a>
				</p>
				<h3 class="font-18 merriw mt30"><b>One Way Taxi Fares</b></h3>
				<p class="mb0">Most people opt for one-way taxi travel for one of the following reasons.</p>
				<ul>
					<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10">Your return plans are not confirmed</li>
					<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10">You are a regular commuter who takes intercity trips for business or college or other official purposes</li>
					<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10">Your return plans are not confirmed</li>
					<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10">You are visiting your family in other towns</li>
					<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10">You are looking to save money compared to a round trip service</li>
					<li><img src="/images/bx-right-arrow-circle.svg" alt="img" width="18" height="18" class="mr10">You are looking to travel comfortably door to door from your home to your final destination. Traveling by bus or train can be very time consuming and can be more expensive than traveling by a one-way shared taxi. 
					</li>
				</ul>
				<p>Gozo today is the most popular taxi service for outstation travel in India due to our convenience, best value for money, <a href="https://www.gozocabs.com/blog/billing-transparency/">billing transparency</a> and amazing 24x7x365 customer support.</p>
			</div>
		</div>
	</div>
</div>