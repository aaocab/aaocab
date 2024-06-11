<?= $this->renderPartial('oneWaySearch', array('model' => $model), true, FALSE); ?> 
<div class="row bs-example">

	<div class="col-xs-12">
		<h4>Go GozoCabs for One Way Taxi Drops on 2000+ Routes in India</h4>

		<p>Gozo was founded on the core idea of simplifying taxi travel for all Indians. Since starting in November 2015, We have consistently enabled one-way taxi travel on more and more routes across India. As of 2018, Gozo has served across states in India and we are now the outstation travel provider with the widest reach. Our principals remain the same - to bring you the Best prices, with Great quality, 24x7 and  Nationwide.  Taking a one-way cab can be sometimes upto 50% cheaper than traveling one-way. We encourage customers to book their one-way trips atleast 7 days ahead of the trip so it improves our ability to offer you the lowest prices and also find you the best car suited to your needs. 
		</p>
		<p>
			With Gozo you have the option to <a href="http://www.aaocab.com/">book one-way, round trip and multi-way transfers</a> across the nation.<br>
			If you are looking for airport or railway drop services, then Gozo can serve you all over India and is the best car rental platform to hire One way taxi, airport drops or railway station pickups at the most reasonable rates. The above list of routes is just a sample of popular routes in each major region of India. If there is anything we can do to serve you better, please call our customer support center or message us via the website chat. We are India’s leader in one-way travel where you will Pay one-way when you Travel one-way. Hassle free at the best prices.
		</p>
		<h4>Popular One Way Taxi Services in India</h4>
		<p>Gozo is an outstation taxi services specialist across India. In addition to one-way AC cab and <a href="<?= Yii::app()->baseUrl; ?>/GozoSHARE">AC outstation shared taxi</a> services we also provide round trips, packaged tours and all sorts of customization to suit your travel needs across all cities and towns in India. 
			<br>Just as an example, When traveling from Bangalore to Mysore you can consider all choices for travel from <a href="<?= Yii::app()->baseUrl; ?>/book-taxi/bangalore-mysore">Bangalore to Mysore,</a> book one-way cabs, <a href="<?= Yii::app()->baseUrl; ?>/tempo-traveller-rental/bangalore">book tempo travelers from Bangalore</a> or <a href="<?= Yii::app()->baseUrl; ?>/car-rental/mysore">Car rentals in or around Mysore</a> and even arrange airport or railway station transfers. 
		</p>
	</div>
    <div class="col-xs-6">
		<div class="panel-group" >
			<h3 class="pl5">Book one way cab service in North India </h3>
			<?php
			foreach ($modelNorthRegion as $val)
			{
				?>
				<div class="panel panel-default ">
					<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $val['ctyId']; ?>">
							<h4 class="panel-title">
								<?php echo $val['ctyName']; ?>
							</h4>
						</a>
					</div>
					<div id="<?php echo $val['ctyId']; ?>" class="panel-collapse collapse">
						<?php
						$ctyId		 = $val['ctyId'];
						$topRoute	 = Yii::app()->cache->get("getPriceByRoute$ctyId");
						if ($topRoute === false)
						{
							$topRoute = Route::model()->getTopRouteByCity($ctyId);
							Yii::app()->cache->set("getPriceByRoute$ctyId", $topRoute, 21600);
						}
						?>
						<div class="panel-body pl10">
							<div class="row"><div class="col-xs-12"><h5 class="mt0 pl10">Route  <span class="text-right pull-right">Starting Price</span></h5></div></div>
							<table class="table table-striped mb0">
								<?php
								foreach ($topRoute as $topRouteCitis)
								{
                                    $topRouteCitiesId=$topRouteCitis['rut_id'];
									$routePrice = Yii::app()->cache->get("getPriceByRoute$topRouteCitiesId");
									if ($routePrice === false)
									{
										$routePrice = Route::model()->getPriceByRoute($topRouteCitis['rut_id']);
										Yii::app()->cache->set("getPriceByRoute$topRouteCitiesId", $routePrice, 21600);
									}
									?>        
									<tr>
										<td><a href="/book-taxi/<?php echo $topRouteCitis['rut_name']; ?>" style="font-weight:600; color: #333"><?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> one way cabs</a></td>
										<td align="right"><i class="fa fa-inr"></i><b><?php echo $routePrice['rteAmount']; ?></b></td>
									</tr>  
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
    </div>
    <div class="col-xs-6">
		<div class="panel-group" >
			<h3 class="pl5">Book one way cab service in West India</h3>
			<?php
			foreach ($modelWestRegion as $val)
			{
				?>
				<div class="panel panel-default ">
					<div class="panel-heading">
						<a data-toggle="collapse" style="display:block" data-parent="#accordion" href="#<?php echo $val['ctyId']; ?>">
							<h4 class="panel-title">
								<?php echo $val['ctyName']; ?>
							</h4>
						</a>
					</div>
					<div id="<?php echo $val['ctyId']; ?>" class="panel-collapse collapse">
						<?php
						$ctyId		 = $val['ctyId'];
						$topRoute	 = Yii::app()->cache->get("getPriceByRoute$ctyId");
						if ($topRoute === false)
						{
							$topRoute = Route::model()->getTopRouteByCity($ctyId);
							Yii::app()->cache->set("getPriceByRoute$ctyId", $topRoute, 21600);
						}
						?>
						<div class="panel-body pl10">
							<div class="row"><div class="col-xs-12"><h5 class="mt0 pl10">Route  <span class="text-right pull-right">Starting Price</span></h5></div></div>
							<table class="table table-striped mb0">
								<?php
								foreach ($topRoute as $topRouteCitis)
								{
									$topRouteCitiesId=$topRouteCitis['rut_id'];
									$routePrice = Yii::app()->cache->get("getPriceByRoute$topRouteCitiesId");
									if ($routePrice === false)
									{
										$routePrice = Route::model()->getPriceByRoute($topRouteCitis['rut_id']);
										Yii::app()->cache->set("getPriceByRoute$topRouteCitiesId", $routePrice, 21600);
									}
									?> 
									<tr>
										<td><a href="/book-taxi/<?php echo $topRouteCitis['rut_name']; ?>" style="font-weight:600; color: #333"><?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> One way cabs</a></td>
										<td align="right"><i class="fa fa-inr"></i><b><?php echo $routePrice['rteAmount']; ?></b></td>
									</tr>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
    </div>
    <div class="col-xs-6">
		<div class="panel-group" >
			<h3 class="pl5">Book one way cab service in South India</h3>
			<?php
			foreach ($modelSouthRegion as $val)
			{
				?>
				<div class="panel panel-default ">
					<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $val['ctyName']; ?>">
							<h4 class="panel-title">
								<?php echo $val['ctyName']; ?>
							</h4>
						</a>
					</div>
					<div id="<?php echo $val['ctyName']; ?>" class="panel-collapse collapse">
						<?php
						$ctyId		 = $val['ctyId'];
						$topRoute	 = Yii::app()->cache->get("getPriceByRoute$ctyId");
						if ($topRoute === false)
						{
							$topRoute = Route::model()->getTopRouteByCity($ctyId);
							Yii::app()->cache->set("getPriceByRoute$ctyId", $topRoute, 21600);
						}
						?>
						<div class="panel-body pl10">
							<div class="row"><div class="col-xs-12"><h5 class="mt0 pl10">Route  <span class="text-right pull-right">Starting Price</span></h5></div></div>
							<table class="table table-striped mb0">
								<?php
								foreach ($topRoute as $topRouteCitis)
								{
									$topRouteCitiesId=$topRouteCitis['rut_id'];
									$routePrice = Yii::app()->cache->get("getPriceByRoute$topRouteCitiesId");
									if ($routePrice === false)
									{
										$routePrice = Route::model()->getPriceByRoute($topRouteCitis['rut_id']);
										Yii::app()->cache->set("getPriceByRoute$topRouteCitiesId", $routePrice, 21600);
									}
									?> 
									<tr>
										<td><a href="/book-taxi/<?php echo $topRouteCitis['rut_name']; ?>" style="font-weight:600; color: #333"><?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> One way cabs</a> </td>
										<td align="right"><i class="fa fa-inr"></i><b><?php echo $routePrice['rteAmount']; ?></b></td>
									</tr> 
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
    </div>
    <div class="col-xs-6">
		<div class="panel-group" >
			<h3 class="pl5">Book one way cab service in East India</h3>
			<?php
			foreach ($modelEastRegion as $val)
			{
				?>
				<div class="panel panel-default ">
					<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $val['ctyId']; ?>">
							<h4 class="panel-title">
								<?php echo $val['ctyName']; ?>
							</h4>
						</a>
					</div>
					<div id="<?php echo $val['ctyId']; ?>" class="panel-collapse collapse">
						<?php
						$ctyId		 = $val['ctyId'];
						$topRoute	 = Yii::app()->cache->get("getPriceByRoute$ctyId");
						if ($topRoute === false)
						{
							$topRoute = Route::model()->getTopRouteByCity($ctyId);
							Yii::app()->cache->set("getPriceByRoute$ctyId", $topRoute, 21600);
						}
						?>
						<div class="panel-body pl10">
							<div class="row"><div class="col-xs-12"><h5 class="mt0 pl10">Route  <span class="text-right pull-right">Starting Price</span></h5></div></div>
							<table class="table table-striped mb0">
								<?php
								foreach ($topRoute as $topRouteCitis)
								{
									$topRouteCitiesId=$topRouteCitis['rut_id'];
									$routePrice = Yii::app()->cache->get("getPriceByRoute$topRouteCitiesId");
									if ($routePrice === false)
									{
										$routePrice = Route::model()->getPriceByRoute($topRouteCitis['rut_id']);
										Yii::app()->cache->set("getPriceByRoute$topRouteCitiesId", $routePrice, 21600);
									}
									?> 
									<tr>
										<td><a href="/<?php echo $topRouteCitis['rut_name']; ?>" style="font-weight:600; color: #333"><?php echo $topRouteCitis['from_city']; ?> to <?php echo $topRouteCitis['to_city']; ?> One way cabs</a></td>
										<td align="right"><i class="fa fa-inr"></i><b><?php echo $routePrice['rteAmount']; ?></b></td>
									</tr> 
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="col-xs-12">
		<h4>Outstation travel in India</h4>
		<p>Gozo is focused completely on providing the most convenient and cost efficient travel between cities all over India. We work with local providers to enable low-cost, high service taxi travel which we strive to make cheaper and more convenient that traveling by bus or train. When traveling alone you can use GozoSHARE outstation shared taxi services and <a href="<?= Yii::app()->baseUrl; ?>/GozoSHARE">travel across India even on a low budget.</a><br>
			If you are planning to travel in large numbers along with your friends or family, then booking a SUV or tempo-traveller as your One way taxi service will be the best option. Gozo offers you the various options for cars - Compact, Sedan, SUV, Tempo-traveller or Luxury vehicles - based on your budget and the number of people travelling.
		</p>
		<h4>Common questions when taking a One Way Private or Shared Taxi Service</h4>
		<p>At Gozo, we want you to be completely at ease and are happy to help you decide on the best options for your outstation / intercity travel.</br>
			Read our comprehensive FAQ for more <a href="<?= Yii::app()->baseUrl; ?>/faq">answers about outstation travel and booking a taxi in India</a>
		</p>
		<h4>One Way Taxi Fares</h4>
		<p>Most people opt for one-way taxi travel for one of the following reasons. </p>
		<ol>
			<li>Your return plans are not confirmed</li>
			<li>You are a regular commuter who takes intercity trips for business or college or other official purposes</li>
			<li>Your return plans are not confirmed</li>
			<li>You are visiting your family in other towns</li>
			<li>You are looking to save money compared to a round trip service</li>
			<li>You are looking to travel comfortably door to door from your home to your final destination. Traveling by bus or train can be very time consuming and can be more expensive than traveling by a one-way shared taxi. 
			</li>
		</ol>
		<p>Gozo today is the most popular taxi service for outstation travel in India due to our convenience, best value for money, <a href="http://www.aaocab.com/blog/billing-transparency/">billing transparency</a> and amazing 24x7x365 customer support.</p>
	</div>
</div>
<div class="col-xs-12">

</div>
<script>$('#accordion').on('show.bs.collapse', function () {
        if (active)
            $('#accordion .in').collapse('show');
    });</script>




