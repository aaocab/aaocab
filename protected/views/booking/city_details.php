<style type="text/css">
    .h3_36{ font-size: 36px !important; line-height: normal;}
    .h3_30{ font-size: 30px !important; line-height: normal;}
    .h3_18{ font-size: 18px !important; line-height: normal;}
</style>
<?php
/*$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Description set inside view',
]);*/
?>
<?
$this->newHome = true;
/* @var $cmodel Cities */
?>
<div class="row">
    <?= $this->renderPartial('application.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
<div class="row gray-bg-new">
    <div class="col-lg-7 col-sm-11 col-md-9 text-center flash_banner float-none marginauto">
        <span class="h3 mt0 mb5 flash_red">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Outstation Taxi rentals and Airport transfers all over India </span><br>
        GozoCabs is India's leader in chauffeur-driven taxi travel for one-way drops, outstation trips, airport transfers and day-based rentals with complete billing transparency. Gozo’s coverage reaches over <b>1,000</b> towns & cities in India, with over <b>20,000</b> vehicles, over <b>100,000</b> satisfied customers and over <b>10</b> Million kms driven each year. Book your Gozo by web, phone or app 24x7! 
    </div>
</div>
<div class="row flash_banner hide" style="background: #ffc864;">
    <div class="col-lg-12 p0 hidden-sm hidden-xs text-center">     
        <figure><img src="/images/flash_lg1.jpg?v=1.1" alt="Flash Sale"></figure>		
    </div>
    <div class="col-sm-12 p0 hidden-lg hidden-md hidden-xs text-center">      
        <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>		
    </div>
    <div class="col-xs-12 p0 hidden-lg hidden-md hidden-sm text-center">
        <? /* /?><a target="_blank" href="https://twitter.com/gozocabs"><?/ */ ?>
        <figure><img src="/images/flash_sm1.jpg?v=1.1" alt="Flash Sale"></figure>
        <? /* /?></a><?/ */ ?>
    </div>
</div>
<?php

if ($type == 'city') {
    ?>
    <section id="section2">
        <div class="row register_path p20">
            <div class="col-xs-12">
                <h3>Booking a taxi in <?= $cmodel->cty_name; ?></h3>
                <p>You can book a taxi anytime in <?= $cmodel->cty_name; ?> with Gozo. Gozo has various services in <?= $cmodel->cty_name; ?> including one way taxi drops to nearby cities <?php
                    if ($cmodel->cty_has_airport > 0) {
                        echo ", chauffeur driven airport pickups and transfers to anywhere within " . $cmodel->cty_name;
                    }
                    ?> and you can also book cars for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns. </p>
                <p>Gozo provides premium outstation taxi services in over <?= $count['countCities']; ?>  cities and <?= $count['countRoutes']; ?>  routes all around India. </p>
                <p>With Gozo you can book a one way cab to many nearby cities from Mumbai. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x365. Gozo uses local taxi operators in the city who maintain highest level of service quality and have a very good knowledge of the local roads. We can also provide local sightseeing trips and tours in or around the city. Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?= $cmodel->cty_name; ?>. If you have a special requirement , simply ask and we will do our best to help. </p>
                <h3>Taxi fares for most popular <?= count($topRoutes); ?> routes to or from <?= $cmodel->cty_name; ?></h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <td><b>Route</b></td>
                            <td><b>Compact</b></td>
                            <td><b>Sedan</b></td>
                            <td><b>SUV</b></td>
                            <td><b>Tempo traveler</b></td>
                        </tr>
                        <?php
                        if (count($topRoutes) > 0) {
                            foreach ($topRoutes as $top) {
                                ?>        
                                <tr>
                                    <td><?= $top['from_city']; ?> to <?= $top['to_city']; ?></td>
                                    <td><?php if ($top['compact_amount'] > 0) { ?> starting at <?= $top['compact_amount']; ?> <?php } ?></td>
                                    <td><?php if ($top['seadan_amount'] > 0) { ?> starting at <?= $top['seadan_amount']; ?> <?php } ?></td>
                                    <td><?php if ($top['suv_amount'] > 0) { ?> starting at <?= $top['suv_amount']; ?> <?php } ?></td>
                                    <td><?php if ($top['tempo_amount'] > 0) { ?> starting at <?= $top['tempo_amount']; ?> <?php } ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>
                <p>Gozo’s booking process is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.</p>
                <p>On the Gozo platform you can also create a multi-day tour package by customizing your itinerary. These are created a round trip bookings between 2 or more cities.</p>
                <p>Top places to visit including weekend getaways and things to do from <?= $cmodel->cty_name; ?> are – 
                <ul class="list-inline list-unstyled">
                    <?php
                    $ctr = 1;
                    foreach ($topCitiesKm as $top) {
                        ?>
                        <li class="p0 pt5">
                            <a href="/car-rental/<?php echo strtolower($top['city']); ?>" style="font-weight:600; color: #333"><? echo $top['city']; ?></a><?= (count($topCitiesKm) == $ctr) ? "." : ", "; ?>
                        </li>
                        <?php
                        $ctr++;
                    }
                    ?>
                </ul>
                </p>
                <p>With Gozo you can book a taxi anywhere in India. Our services are top rated in almost all cities across India. You can book a car with Gozo in 
                <ul class="list-inline list-unstyled">
                    <?php
                    foreach ($topCitiesByRegion as $top) {
                        ?>
                        <li class="p0 pt5">
                            <a href="/car-rental/<?php echo strtolower($top['city']); ?>" style="font-weight:600; color: #333"><? echo $top['city']; ?></a><?= (count($topCitiesByRegion) == $ctr) ? "." : ", "; ?>
                        </li>
                    <?php }
                    ?>
                    </p>
                </ul>
            </div>
        </div>
    </section>    
    <?php
    } 
    else if ($type == 'route') 
    {
    ?>
    <section id="section2">
        <div class="row">
            <div class="hidden-xs">
                <div class="col-xs-12">
                    <h3 class="text-center wow fadeInUp animated" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;" data-wow-delay="0.6s">Testimonials </h3>
                    <div class="row mt30 n">
                        <div class="col-xs-12 text-right">&nbsp;</div>
                    </div>
                    <div class="mb20">
                        <div class="logo-style text-center mb20">
                            <figure><img src="/images/rount1.jpg?v=1" alt="Gozocabs"></figure>
                        </div>
                    </div>
                    <div id="myCarouselTestimonial" class="carousel slide mt20 " data-ride="carousel" data-interval="false">
                        <div class="carousel-inner" role="listbox"> 
                            <?php
                            $cheapRate = Rate::model()->getCheapRateByRouteId($rmodel->rut_id);
                            $rows = Yii::app()->cache->get("getTopRatings".$route);
                            if ($rows === false) {
                                $route = trim($_GET['route']);
                                /* @var $modelTestimonial Ratings */
                                $rows = Ratings::model()->getTop3Ratings($route, 3);
                                Yii::app()->cache->set("getTopRatings".$route, $rows, 60*60*24, new CacheDependency('getTopRatings'));
                            }

                            $active = "active";
                            $i = 0;
                            foreach ($rows as $row) {
                                $r = $i % 3;
                                $toCities = $row['cities'];
                                if ($r == 0) {
                                    ?>
                                    <div class="item <?= $active ?>">
                                        <div class="container">
                                            <div class="row">
                                            <? } ?>
                                            <div class="col-xs-12 col-sm-4 mb10 pl0">
                                                <div class="pull-left mr10">
                                                    <div class="test-name"><?= $row['initial'] ?></div>
                                                </div>
                                                <div style="padding-left: 68px" class="pr15">
                                                    <?= $row['rtg_customer_review'] ?>
                                                    <p class="m0 orange-color"><i><b>- <?= $row['user_name'] ?></b></i></p>
                                                    <p class="m0"><b><?= $toCities; ?>,</b> <i><?= Booking::model()->getBookingType($row['bkg_booking_type']); ?></i></p>
                                                    <p class="m0 block-color3"><i><b><?= date('jS M Y', strtotime($row['rtg_customer_date'])) ?></b></i></p>
                                                </div>
                                            </div>
                                            <?
                                            $i++;
                                            $active = "";
                                            if ($r == 2) {
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                }
                            }
                            ?>
                        </div>              
                    </div>
                </div>
            </div>
        </div>

        <div  class="hide container">
            <div class="row">
                <div class="col-xs-12 col-sm-3">
                    <h4>Pickup or Drop anywhere in <?= $rmodel->rutFromCity->cty_name ?></h4>
                    <div class="span3 feature">
                        <?= $rmodel->rutFromCity->cty_pickup_drop_info ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <h4>Other Parts of NCR?</h4>
                    <div class="span3 feature">
                        <?= $rmodel->rutFromCity->cty_ncr ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <h4>Pickup or Drop anywhere in <?= $rmodel->rutToCity->cty_name ?></h4>
                    <div class="span3 feature">
                        <?= $rmodel->rutToCity->cty_pickup_drop_info ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <h4>Discount for Return Trip</h4>
                    <div class="span3 feature">
                        Get a flat &#x20B9;  200/- discount for return transfer with the same vehicle and the same way.
                    </div>
                </div>
            </div>
        </div>

        <div class="newline mt20">
            <div class="row">
                <h1 class="ml15" title="<?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> Taxi">Cab from <?= $rmodel->rutFromCity->cty_name ?> To <?= $rmodel->rutToCity->cty_name ?></h1>
                <div id="desc" class="col-xs-12 col-sm-7 col-md-8 feature"><b>Distance</b> from <?= $rmodel->rutFromCity->cty_name; ?> to <?= $rmodel->rutToCity->cty_name; ?> is around <?= $model->bkg_trip_distance; ?> Kms. Estimated travel time is  <?= floor(($rmodel->rut_estm_time / 60)); ?> hours and <?= ($rmodel->rut_estm_time % 60); ?> minutes.<br>
                    The lowest cab fare for cab from <?= $rmodel->rutFromCity->cty_name; ?> to <?= $rmodel->rutToCity->cty_name; ?> is Rs.<?= $cheapRate['rte_amount']; ?> <br>
                    <br/>There are many ways to go from <?= $rmodel->rutFromCity->cty_name; ?> to <?= $rmodel->rutToCity->cty_name; ?>. The most comfortable and speediest option is to get a car rental. However if you are looking to make a one-way trip its even better to rent a chauffeur-driven cab from Gozo.
                    <br/>The cheapest way to travel from <?= $rmodel->rutFromCity->cty_name; ?> To <?= $rmodel->rutToCity->cty_name; ?> will cost you Rs. <?=$cheapRate['rte_amount'];?> for a one-way trip and Rs. <?=$cheapRate['rte_amount'];?> for a one day round trip jouney. A one way chauffeur-driven car rental saves you money vs having to pay for a round trip. It is also much more comfortable and convenient as you have a driver driving you in your dedicated car. While there are a number of sharing options available in the market we believe that having a dedicated car at the best price gives you the most flexibility and comfort. Why carpool, self-drive or rideshare when you can have your own chauffeur-driven car.  Gozo works with local taxi operators who know the local roads, provide you good service and we bring it to you at the most reasonable price.
                    <br/>For <?= $rmodel->rutFromCity->cty_name; ?> to <?= $rmodel->rutToCity->cty_name; ?> trip, cabs are available from all parts of <?= $rmodel->rutFromCity->cty_name; ?>. Worried about your extra luggage? Just inform us and we will arrange for a cab with a carrier for your luggage.        
                    <br/>Book a one way or round trip cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> at the best prices for a quality service. For <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> trip, Cabs available from all parts of <?= $rmodel->rutFromCity->cty_name ?>, <?= $rmodel->rutFromCity->cty_pickup_drop_info ?>. 
                    Worried about your extra luggage? Just inform us and we will arrange for a cab with a carrier for your luggage. 
                    Now book a confirmed One-way cab from <?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> online in four easy steps.<br><br><br>
                    <b>About <?= $rmodel->rutFromCity->cty_name ?> : </b><?= $rmodel->rutFromCity->cty_city_desc ?><br><br><br>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-4 offset1">
                    <div  id="map_canvas" style="height: 350px;"></div>
                </div>
                <div id="desc1" class="col-xs-12 feature">
                    <br><br><b><?= $rmodel->rutFromCity->cty_name ?> to <?= $rmodel->rutToCity->cty_name ?> : </b><?= $rmodel->rutToCity->cty_city_desc ?>
                </div>
            </div>
        </div>
    </section>  
    <?php
    }
    ?>
<? $api = Yii::app()->params['googleBrowserApiKey']; ?>
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
    function loadScript() {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
                'callback=mapInitialize&key=<?= $api ?>';
        document.body.appendChild(script);
    }
    window.onload = loadScript;
</script>