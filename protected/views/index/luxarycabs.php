<style type="text/css">
    .h3_36{ font-size: 36px !important; line-height: normal;}
    .h3_30{ font-size: 30px !important; line-height: normal;}
    .h3_18{ font-size: 18px !important; line-height: normal;}
</style>
<?
$this->newHome = true;
/* @var $cmodel Cities */
?>
<div class="row">
    <?= $this->renderPartial('application.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
<div class="row gray-bg-new">
    <div class="col-lg-6 col-sm-10 col-md-8 text-center flash_banner float-none marginauto">
        <span class="h3 mt0 mb5 flash_red">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Outstation Taxi rentals and Airport transfers all over India </span><br>
        GozoCabs is India's leader in chauffeur-driven taxi travel for one-way drops, outstation trips, airport transfers and day-based rentals with complete billing transparency. Gozo’s coverage reaches over <b>1,000</b> towns & cities in India, with over <b>20,000</b> vehicles, over <b>100,000</b> satisfied customers and over <b>10</b> Million kms driven each year. Book your Gozo by web, phone or app 24x7! 
    </div>
</div>
<div class="container">

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

    <section id="section2">
        <div class="row p20">
            <div class="col-xs-12">
        <h3 class="mt0">Luxury Car rental service in <?=$city?></h3>
        <div>
        <p>Luxury Cars are a comfort & style statement. When Gozo’s team first thought of this, we know we have to fulfill customer’s desire for service and class by letting them hire luxury cars on rent in <?=$city?> and all major cities in India. 
            Luxury cars that we provide for rent range from a wide variety that cater to choices and tastes of our customers. To add to your special day, we provide the best luxury cars rentals in <?=$city?>.
        </p>
        </div>
        <h3 class="mt0">Pricing and options for Luxury Car Rentals in <?=$city?></h3>
    
         <div class="col-8">
  <table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th scope="col">Luxury Car Type</th>
      <th scope="col">Fare / (Km)</th>     
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Audi</th>
      <td><?php //echo $c_types[1]['vht_estimated_cost']; ?> Call us</td>
      
    </tr>
    <tr>
      <th scope="row">Mercedes</th>
       <td>Call us</td>      
    </tr>
    <tr>
      <th scope="row">Jaguar</th>
      <td>Call us</td>      
    </tr>
    <tr>
      <th scope="row">BMW</th>
      <td><?php //echo $c_types[0]['vht_estimated_cost'];?>Call us</td>      
    </tr>
    <tr>
      <th scope="row">Rolls Royce</th>
      <td>Call us</td>       
    </tr>
  </tbody>
</table>
         </div>
      
      
         
         <h3 class="mt0">Why book with Gozo for Luxury car rental in <?=$city?></h3>
          <div>
         <p>
             Gozo is india’s leader for luxury car rental services in top 50 of the 750 cities that we serve in India. Gozo's booking and billing process is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. In addition you get instant booking confirmations, electronic invoices, 24x7 support and top quality for the best price.
If you have any special needs,  you can always contact our customer helpdesk and we will be happy to support you.Our services are top rated in almost all cities across India.
If you are looking to make an impression on your clients, or if you are serving the business elite you can call on Gozo to arrange pick up or drop facilities in luxury cars. You can also use Gozo’s luxury car rental services for a city tour through the day.

         </p>
          <h3 class="mt0">Hire Premium & luxury cars for wedding in <?=$city?></h3>
         <p>
             To make your memorable day even more special, Gozo offers you an opportunity to choose from wide spectra of luxury cars. Due to the specific and less frequent nature of these requirements we request customers to make their reservations well in advance.
             This provides us sufficient advance notice to arrange a vehicle for your needs.
         </p>
        </div>
            </div>
        </div>
    </section>
</div>
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