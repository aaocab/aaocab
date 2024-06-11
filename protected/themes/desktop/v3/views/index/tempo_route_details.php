<style type="text/css">
    .h3_36{ font-size: 36px !important; line-height: normal;}
    .h3_30{ font-size: 30px !important; line-height: normal;}
    .h3_18{ font-size: 18px !important; line-height: normal;}
    .link-panel{ text-align: center;}
    .link-panel a{ 
        display: inline-block; text-align: center;
        background: #ff6700; padding: 5px 10px; margin: 0 5px; color: #fff;
        -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;
        text-decoration: none;
    }
    .link-panel a:hover{ background: #152b57;}
    .car_box2 img{ width: 100%;}
    .main_time2{ min-height: 160px; line-height:18px; font-size:12px;}
</style>
<?php
if (isset($jsonproviderStructureMarkupData) && trim($jsonproviderStructureMarkupData) != '') {
    ?>
    <script type="application/ld+json">
    <?php echo $jsonproviderStructureMarkupData; ?>
    </script>
<?php } ?>
<?
$this->newHome = true;
/* @var $cmodel Cities */
?>
<?//= $this->renderPartial('application.themes.desktop.v2.views.booking.fblikeview') ?>
<div class="row">

    <?= $this->renderPartial('application.themes.desktop.v3.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
<!--<div class="row gray-bg-new">
    <div class="col-lg-10 col-sm-10 col-md-8 text-center flash_banner float-none marginauto ml50 border bg-white">
        <span class="h3 mt0 mb5 flash_red text-warning">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Taxi and Car rentals all over India – Gozo’s online cab booking service</span><br>
        aaocab is your one-stop shop for hiring chauffeur driven taxi in India for inter-city, airport transfers and even local daily tours, specializing in one-way taxi trips all over India at transparent fares. Gozo’s coverage extends across <b>2,000</b> locations in India, with over <b>20,000</b> vehicles, more than <b>75,000</b> satisfied customers and above <b>10</b> Million kms driven in a year alone. Book by web, phone or app 24x7! 
    </div>
</div>-->
<?php
$cities = ($count['countCities'] > 500) ? 500 : $count['countCities'];

$routes = ($count['countRoutes'] > 50000) ? 50000 : $count['countRoutes'];
$topCitiesByKm = '';
$ctr = 1;

foreach ($topCitiesKm as $top) {
    $topCitiesByKm .= '<a href="/tempo-traveller-rental/' . strtolower($top['cty_alias_path']) . '" style=" text-decoration: none; color: #282828;" target="_blank">' . $top['city'] . '</a>';
    $topCitiesByKm .= (count($topCitiesKm) == $ctr) ? " " : ", ";
    $ctr++;
}
$text1 = count($topCitiesByRegion) > 0 ? 'Then this is where you need to go​​' : 'give us suggestions for your favorite places to visit';
?>
<div class="container mt30">
    <section id="section2">
        <div class="row">
            <div class="col-12"> 

                <h1 class="font-24"> Best Tempo Traveller on rent in <?= $cmodel->cty_name; ?> for lowest prices 
                    <!--fb like button-->
                    <div class="fb-like pull-right mb30" data-href="https://facebook.com/aaocab" data-width="1" data-layout="standard" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
                    <!--fb like button-->
                </h1>
<?php
for ($t = 0; $t < count($topTenRoutes); $t++) {
    $city_arr[] = $topTenRoutes[$t]['to_city'];
}
$city_str = implode(",", $city_arr);
?>
                <p>Gozo provides Tempo Traveller rentals at Budget prices in <?= $cmodel->cty_name ?> and all across India. Book tempo traveller with driver on rent online at best price with Gozo, also avail 10% extra discount by booking 10 days in advance and paying online. We have various types of Tempo Travellers ranging from 8 seaters to 22 seaters including luxury and non-luxury according to your choice at an affordable price starting from @<?= $minTempoRate ?>/Km.
                </p>
                <p>When Gozo's compact, sedan and SUV category of vehicles is a perfect fit for customers looking to hire outstation cabs, day rentals or airport transfers we find that larger groups can rent larger vehicles like minivans, tempo travellers or buses. In order to address the needs of large families traveling together or business groups attending company meetings or events Gozo provides Budget Tempo Traveller rentals at cheapest prices in all top cities across India.
                <p>With Gozo you can hire tempo traveller from <?= $cmodel->cty_name ?> to nearby cities  <?= $city_str; ?> and many more. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available to book online 24x7x365 . Gozo uses local operators in <?= $cmodel->cty_name ?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. You can also rent tempo travellers for local sightseeing trips and tours in or around the <?= $cmodel->cty_name ?>. Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?= $cmodel->cty_name ?>.</p>
                <div class="float-right">
                    <div class="main_time text-center">
                        <div class="car_box2"><img src="/images/cabs/car-etios.jpg" alt=""></div>
                        <a href="/car-rental/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>">Book car rental in <?= $cmodel->cty_name; ?></a>
                    </div>
                
                    <div class="main_time text-center">
                        <div class="car_box2"><img src="/images/cabs/car-etios.jpg" alt=""></div>
                        <a href="/outstation-cabs/<?php echo strtolower(str_replace(' ', '-', $cmodel->cty_alias_path)); ?>">Book outstation taxi rental in <?= $cmodel->cty_name; ?></a>
                    </div>
                </div>

<!--<h3>WHY GOZO FOR TEMPO TRAVELLER RENT IN <?= strtoupper($cmodel->cty_name); ?></h3>
<p>Gozo is india’s leader in outstation car rental and provides services in over <?= $cities; ?> cities and on over <?= number_format($routes); ?> routes.</p>
<p>Our promise of transparent billing, fair prices, 24x7 support and nationwide reach applies for tempo travellers too. If you have any special requirements you can always contact our customer helpdesk and we will be happy to support you.</p>
<p>In addition to tempo travellers, Gozo provides various services in <?= $cmodel->cty_name; ?> including one way taxi drops or short roundtrips to nearby cities. You can also book cabs for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns.</p>
<h3>TEMPO TRAVELLER SEATING CAPACITY</h3>
<p>For those not from India. Tempo traveller is the brand for a Minibus and is available generally in 3 different type of seating configurations with various capacity. We provide 9 seater, 12 seater or 15 seater Tempo travellers.</p>
<p>Due to the specific and less frequent nature of these requirements we request customers to make their reservations as early as at least 10 days in advance. This provides us sufficient advance notice to arrange a vehicle for your journey.</p>
<h3>GOZO'S TEMPO TRAVELLER SERVICE IN <?= strtoupper($cmodel->cty_name); ?></h3>
<p>With Gozo you can book a one way cab from <?= $cmodel->cty_name; ?> to <?= $topCitiesByKm; ?>. Gozo provides well maintained air-conditioned (AC) cars, courteous drivers and our services are available 24x7x365. Gozo uses local taxi operators in <?= $cmodel->cty_name; ?> who maintain highest level of service quality and have a very good knowledge of the local roads and highway driving. We also provide local sightseeing trips and tours in or around the city. Our cars and drivers serve tourists, large event groups and business people for outstation trips and also for local taxi requirements in <?= $cmodel->cty_name; ?>.</p>
<p>If you have a special requirement, simply ask and we will do our best to help.</p>-->
<!--<h3>Tempo traveller fares for <? //= count($topTenRoutes);   ?> popular trips to or from <? //= $cmodel->cty_name;   ?></h3>-->
                <h2 class="font-24">Tempo traveller fares for rentals in and around <?= $cmodel->cty_name; ?></h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" width='75%'>
                        <tr>
                            <td><b>Route (Starting at)</b></td>
                            <td align="center"><b>Tempo traveler<br> (9 seater)</b></td>
                            <td align="center"><b>Tempo traveler<br> (12 seater)</b></td>
                            <td align="center"><b>Tempo traveler<br> (15 seater)</b></td>
                        </tr>
<?php
if (count($topTenRoutes) > 0) {
    foreach ($topTenRoutes as $top) {
        ?>        
                                <tr>
                                    <td><a href="<?= Yii::app()->createAbsoluteUrl("/book-taxi/" .$top['rut_name']); ?>" target="_blank" ><?= $top['from_city']; ?> to <?= $top['to_city']; ?> Travel</a></td>
                                    <td align="center"><?= ($top['tempo_9seater_price'] > 0) ? '&#x20B9;' . $top['tempo_9seater_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
                                    <td align="center"><?= ($top['tempo_12seater_price'] > 0) ? '&#x20B9;' . $top['tempo_12seater_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
                                    <td align="center"><?= ($top['tempo_15seater_price'] > 0) ? '&#x20B9;' . $top['tempo_15seater_price'] : '<a href="tel:+919051877000"class="p5 font-12 color-black" style="color:#5a5a5a;"> Call us</a>'; ?></td>
                                </tr>
        <?php
    }
} else {
    ?>
                            <tr><td align="center" colspan="4">No routes yet found.</td></tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <h3 class="font-24 mt30">WHY HIRE TEMPO TRAVELLER FROM GOZO IN <?= strtoupper($cmodel->cty_name); ?></h3>
                <p>Gozo is india’s leader in tempo traveller rentals and provides services in over 3000 towns & cities along over 50,000 intercity routes in the nation.</p>
                <p>When you rent a tempo traveller from Gozo you get transparent billing, low prices and 24x7 support along with our nationwide reach. 
                    You can rent tempo traveller for round trips and If you have any special requirements like deluxe or luxury tempo travellers you can 
                    always contact our customer service helpdesk. Gozo can arrange 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 24, 26, 27 seaters 
                    Tempo Travellers on Hire in our fleet across India.</p>
                <p>With a fleet of hundreds of tempo travellers nationwide, we promise to provide you with an unmatched variety of both luxury and non-luxury tempo traveller rentals.
                    Our tempo travellers come equipped with pushback or recliner seats with ample leg and moving spaces that ensure a comfortable travel experience. 
                    Other common amenities like luggage storage, water bottle, blankets, charging point, reading light, central TV, etc. may also be provided (varies from vehicle to vehicle). 
                    All our tempo travellers available on rent in <?= $cmodel->cty_name; ?> are safety compliant.</p>
                <p>In addition to tempo travellers, Gozo provides various services in <?= $cmodel->cty_name; ?> including one way taxi drops or short roundtrips to nearby cities. 
                    You can also book cabs for your vacation or business trips for single or multiple days within or around <?= $cmodel->cty_name; ?> or to nearby cities and towns.
                    For those not from India, Tempo traveller is the brand for a Minibus and is available generally in 3 different type of seating configurations with various capacity. 
                    We provide 9 seater, 12 seater or 15 seater Tempo travellers.</p>
                <p>Due to the specific and less frequent nature of these requirements we request customers to make their reservations as early as at least 10 days in advance. This provides us sufficient advance notice to arrange a vehicle for your journey.</p>
                <h3 class="font-24 mt30">HIRE A TEMPO TRAVELLER ONLINE WITH GOZO CABS </h3>
                <p>Gozo's booking and billing process is <a href="http://www.aaocab.com/blog/billing-transparency/">completely transparent</a> and you will get all the terms and conditions detailed on your booking confirmation. You get instant booking confirmations, electronic invoices and top quality for the best price.
                    With Gozo you can book a taxi anywhere in India. Our services are top rated in almost all cities across India.
                </p>
                                        <!--<p class="link-panel">
                                        <a href="/car-rental/<?php echo strtolower($cmodel->cty_name); ?>">Book car rental in Gozo</a>
                                        </p>-->
            </div>
            <!--<div  id="map_canvas" style="height: 633px;"></div>-->
        </div>

    </section>
</div>

