<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php $this->layout = column1; ?>
<?php
$ptime					 = date('h:i A', strtotime('6am'));
//$model->bkg_pickup_date_time = $ptime;
$timeArr				 = Filter::getTimeDropArr($ptime);
$ptimePackage			 = Yii::app()->params['defaultPackagePickupTime'];
$timeArrPackage			 = Filter::getTimeDropArr($ptimePackage);
$selectizeOptions		 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
$cityRadius				 = Yii::app()->params['airportCityRadius'];
$emptyTransferDropdown	 = "Please check your transfer type.<br>Let us help you.<br><i>Call now</i> (+91) 90518-77-000";
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/mtnc/global/plugins/bootbox/bootbox.min.js');
$brtModel				 = $model->bookingRoutes[0];
$defaultDate			 = date('Y-m-d H:i:s', strtotime('+2 days'));
$defaultRDate			 = date('Y-m-d H:i:s', strtotime('+3 days'));
$minDate				 = date('Y-m-d H:i:s ', strtotime('+4 hour'));
$pdate					 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $brtModel->brt_pickup_date_date;
$ctr = rand(0, 99) . date('mdhis');
?>

<div class="content-boxed-widget">
	<h2 class="bottom-10 font-20">One-way cabs</h2>
     
     <?= $this->renderPartial('bkOneway', array('model' => $model, 'brtModel' => $brtModel, 'timeArr' => $timeArr, 'selectizeOptions' => $selectizeOptions, 'pdate' => $pdate,'minDate' => $minDate,'ctr' => $ctr), true, false); ?>
</div>
<div class="content-boxed-widget">
	<!--fb like button-->
	<div class="fb-like" data-href="https://facebook.com/gozocabs" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
	<!--fb like button-->
	<p class="font-16 bottom-0"><b>Go GozoCabs for One Way Taxi Drops on 2000+ Routes in India</b></p>
	<p>Gozo was founded on the core idea of simplifying taxi travel for all Indians. Since starting in November 2015, We have consistently enabled one-way taxi travel on more and more routes across India. As of 2018, Gozo has served across states in India and we are now the outstation travel provider with the widest reach. Our principals remain the same - to bring you the Best prices, with Great quality, 24x7 and  Nationwide.  Taking a one-way cab can be sometimes upto 50% cheaper than traveling one-way. We encourage customers to book their one-way trips atleast 7 days ahead of the trip so it improves our ability to offer you the lowest prices and also find you the best car suited to your needs. 
	</p>
	<p>
		With Gozo you have the option to <a href="http://www.aaocab.com/" class="color-highlight default-link">book one-way, round trip and multi-way transfers</a> across the nation.<br>
		If you are looking for airport or railway drop services, then Gozo can serve you all over India and is the best car rental platform to hire One way taxi, airport drops or railway station pickups at the most reasonable rates. The above list of routes is just a sample of popular routes in each major region of India. If there is anything we can do to serve you better, please call our customer support center or message us via the website chat. We are India’s leader in one-way travel where you will Pay one-way when you Travel one-way. Hassle free at the best prices.
	</p>
	<p class="font-16 bottom-0"><b>Popular One Way Taxi Services in India</b></p>
	<p>Gozo is an outstation taxi services specialist across India. In addition to one-way AC cab and <a href="<?= Yii::app()->baseUrl; ?>/GozoSHARE" class="color-highlight default-link">AC outstation shared taxi</a> services we also provide round trips, packaged tours and all sorts of customization to suit your travel needs across all cities and towns in India. 
		<br>Just as an example, When traveling from Bangalore to Mysore you can consider all choices for travel from <a href="<?= Yii::app()->baseUrl; ?>/book-taxi/bangalore-mysore" class="color-highlight default-link">Bangalore to Mysore,</a> book one-way cabs, <a href="<?= Yii::app()->baseUrl; ?>/tempo-traveller-rental/bangalore" class="color-highlight default-link">book tempo travelers from Bangalore</a> or <a href="<?= Yii::app()->baseUrl; ?>/car-rental/mysore" class="color-highlight default-link">Car rentals in or around Mysore</a> and even arrange airport or railway station transfers. 
	</p>
</div>


<div class="content-boxed-widget p0">
	<div class="font-16 bottom-0 p10"><b>Book one way cab service in North India</b></div>

	<?php
	foreach ($modelNorthRegion as $val)
	{
		?>
	<div class="accordion accordion-style-1">
		<div class="accordion-path">
			<div class="accordion box-text-7">
				<div>
					<a href="javascript:void(0)" class="font18" data-accordion="accordion-<?php echo $val['ctyId']; ?>" data-parent="#accordion">

	<?php echo $val['ctyName']; ?>
						<i class="fa fa-plus"></i>
					</a>
				</div>
		<div class="accordion-content accordion-style-4" id="accordion-<?php echo $val['ctyId']; ?>" style="display: none;">
			<div class="accordion-text">
				<div id="<?php echo $val['ctyId']; ?>" class="panel-collapse collapse">
					<?php
					$ctyId		 = $val['ctyId'];
					$topRoute	 = Route::model()->getTopRouteByCity($ctyId);
					?>

					<div class="content p0 bottom-5 color-green3-dark"><b>Route  <span class="text-right pull-right">Starting Price</span></b></div>


						<?php
						foreach ($topRoute as $topRouteCitis)
						{
							$routePrice	 = Yii::app()->cache->get("getPriceByRoute");
							$routePrice	 = Route::model()->getPriceByRoute($topRouteCitis['rut_id']);
							Yii::app()->cache->set("getPriceByRoute", $routePrice, 7200);
							?>
							<div class="content p0 bottom-5">
								<a class="default-link" href="/book-taxi/<?php echo $topRouteCitis['rut_name']; ?>" style=" color: #333">
									<div class="pull-right">
									<?php
									if ($routePrice['rteAmount'] > 0)
									{
										echo '<span>&#x20b9</span><b>' . $routePrice['rteAmount'] . '</b>';
									}
									?>
								</div>
									<?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> one way cabs
								</a>
							</div>
							<div class="clear"></div>

	<?php } ?>
				</div>
			</div>
		</div>
			</div>
		</div>
	</div>
<?php } ?>
</div>
<div class="content-boxed-widget p0">
	<div class="font-16 bottom-0 p10"><b>Book one way cab service in West India</b></div>

	<?php
	foreach ($modelWestRegion as $val)
	{
		?>
	<div class="accordion-path">
			<div class="accordion accordion-style-0">
				<div class="accordion box-text-7">

					<a href="javascript:void(0)" class="font18" data-accordion="accordion-<?php echo $val['ctyId']; ?>" data-parent="#accordion">

						<?php echo $val['ctyName']; ?>
						<i class="fa fa-plus"></i>
					</a>

					<div class="accordion-content accordion-style-4" id="accordion-<?php echo $val['ctyId']; ?>" style="display: none;">
						<div class="accordion-text">
							<div id="<?php echo $val['ctyId']; ?>" class="panel-collapse collapse">
								<?php
								$ctyId		 = $val['ctyId'];
								$topRoute	 = Route::model()->getTopRouteByCity($ctyId);
								?>
								
								<div class="content p0 bottom-5 color-green3-dark"><b>Route  <span class="text-right pull-right">Starting Price</span></b></div>

									<?php
									foreach ($topRoute as $topRouteCitis)
									{
										$routePrice	 = Yii::app()->cache->get("getPriceByRoute");
										$routePrice	 = Route::model()->getPriceByRoute($topRouteCitis['rut_id']);
										Yii::app()->cache->set("getPriceByRoute", $routePrice, 7200);
										?> 

										<div class="content p0 bottom-5">
											<a class="default-link" href="/book-taxi/<?php echo $topRouteCitis['rut_name']; ?>" style="color: #333">
												<span class="pull-right">
												<?php
												if ($routePrice['rteAmount'] > 0)
												{
													echo '<span>&#x20b9</span><b>' . $routePrice['rteAmount'] . '</b>';
												}
												?>
											</span>
											<?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> One way cabs</a>
										</div>
										<div class="clear"></div>
									<?php } ?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		
<?php } ?>

</div>
<div class="content-boxed-widget p0">
	<div class="font-16 bottom-0 p10"><b>Book one way cab service in South India</b></div>

	<?php
	foreach ($modelSouthRegion as $val)
	{
		?>
		<div class="accordion-path">
			<div class="accordion accordion-style-0">
				<div class="accordion box-text-7">

					<a href="javascript:void(0)" class="font18" data-accordion="accordion-<?php echo $val['ctyId']; ?>" data-parent="#accordion">

	<?php echo $val['ctyName']; ?>
						<i class="fa fa-plus"></i>
					</a>
				
		<div class="accordion-content accordion-style-4" id="accordion-<?php echo $val['ctyId']; ?>" style="display: none;">
			<div class="accordion-text">
				<div id="<?php echo $val['ctyName']; ?>" class="panel-collapse collapse">
					<?php
					$ctyId		 = $val['ctyId'];
					$topRoute	 = Route::model()->getTopRouteByCity($ctyId);
					?>
					
					<div class="content p0 bottom-5 color-green3-dark"><b>Route  <span class="text-right pull-right">Starting Price</span></b></div>

						<?php
						foreach ($topRoute as $topRouteCitis)
						{
							$routePrice	 = Yii::app()->cache->get("getPriceByRoute");
							$routePrice	 = Route::model()->getPriceByRoute($topRouteCitis['rut_id']);
							Yii::app()->cache->set("getPriceByRoute", $routePrice, 7200);
							?> 

							<div class="content p0 bottom-5">
								<a class="default-link" href="/book-taxi/<?php echo $topRouteCitis['rut_name']; ?>" style="color: #333">
									<span class="pull-right">
									<?php
									if ($routePrice['rteAmount'] > 0)
									{
										echo '<span>&#x20b9</span><b>' . $routePrice['rteAmount'] . '</b>';
									}
									?>
								</span>
								<?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> one way cabs</a>
								
							</div>
							<div class="clear"></div>
	<?php } ?>

				</div>
			</div>
		</div>
					</div>
			</div>
		</div>
<?php } ?>
</div>


<div class="content-boxed-widget p0">
	<div class="font-16 bottom-0 p10"><b>Book one way cab service in East India</b></div>

<?php
foreach ($modelEastRegion as $val)
{
	?>
		<div class="accordion-path">
			<div class="accordion accordion-style-0">
				<div class="accordion box-text-7">

					<a href="javascript:void(0)" class="font18" data-accordion="accordion-<?php echo $val['ctyId']; ?>" data-parent="#accordion">

	<?php echo $val['ctyName']; ?>
						<i class="fa fa-plus"></i>
					</a>
		<div class="accordion-content accordion-style-4" id="accordion-<?php echo $val['ctyId']; ?>" style="display: none;">
			<div class="accordion-text">
				<div id="<?php echo $val['ctyId']; ?>" class="panel-collapse collapse">
	<?php
	$ctyId		 = $val['ctyId'];
	$topRoute	 = Route::model()->getTopRouteByCity($ctyId);
	?>
					
					<div class="content p0 bottom-5 color-green3-dark"><b>Route  <span class="text-right pull-right">Starting Price</span></b></div>

						<?php
						foreach ($topRoute as $topRouteCitis)
						{
							$routePrice	 = Yii::app()->cache->get("getPriceByRoute");
							$routePrice	 = Route::model()->getPriceByRoute($topRouteCitis['rut_id']);
							Yii::app()->cache->set("getPriceByRoute", $routePrice, 7200);
							?> 

							<div class="content p0 bottom-5">
								<a class="default-link" href="/<?php echo $topRouteCitis['rut_name']; ?>" style=" color: #333">
									<span class="pull-right">
									<?php
									if ($routePrice['rteAmount'] > 0)
									{
										echo '<span>&#x20b9</span><b>' . $routePrice['rteAmount'] . '</b>';
									}
									?>
								</span>
								<?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> One way cabs</a>
							</div>
							<div class="clear"></div>
	<?php } ?>
				</div>
			</div>
		</div>
				</div>
			</div>
		</div>
<?php } ?>
</div>


<div class="content-boxed-widget">
	<h4>Outstation travel in India</h4>
	<p>Gozo is focused completely on providing the most convenient and cost efficient travel between cities all over India. We work with local providers to enable low-cost, high service taxi travel which we strive to make cheaper and more convenient that traveling by bus or train. When traveling alone you can use GozoSHARE outstation shared taxi services and <a href="<?= Yii::app()->baseUrl; ?>/GozoSHARE" class="color-highlight default-link">travel across India even on a low budget.</a><br>
		If you are planning to travel in large numbers along with your friends or family, then booking a SUV or tempo-traveller as your One way taxi service will be the best option. Gozo offers you the various options for cars - Compact, Sedan, SUV, Tempo-traveller or Luxury vehicles - based on your budget and the number of people travelling.
	</p>
	<h4>Common questions when taking a One Way Private or Shared Taxi Service</h4>
	<p>At Gozo, we want you to be completely at ease and are happy to help you decide on the best options for your outstation / intercity travel.</br>
		Read our comprehensive FAQ for more <a href="<?= Yii::app()->baseUrl; ?>/faq" class="color-highlight default-link">answers about outstation travel and booking a taxi in India</a>
	</p>
	<h4>One Way Taxi Fares</h4>
	<p class="bottom-10">Most people opt for one-way taxi travel for one of the following reasons. </p>
	<ol>
		<li>Your return plans are not confirmed</li>
		<li>You are a regular commuter who takes intercity trips for business or college or other official purposes</li>
		<li>Your return plans are not confirmed</li>
		<li>You are visiting your family in other towns</li>
		<li>You are looking to save money compared to a round trip service</li>
		<li>You are looking to travel comfortably door to door from your home to your final destination. Traveling by bus or train can be very time consuming and can be more expensive than traveling by a one-way shared taxi. 
		</li>
	</ol>
	<p>Gozo today is the most popular taxi service for outstation travel in India due to our convenience, best value for money, <a href="http://www.aaocab.com/blog/billing-transparency/" class="color-highlight default-link">billing transparency</a> and amazing 24x7x365 customer support.</p>
</div>

<script>$('#accordion').on('show.bs.collapse', function () {
        if (active)
            $('#accordion .in').collapse('show');
    });</script>
