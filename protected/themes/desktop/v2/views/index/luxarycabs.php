<?
$this->newHome = true;
/* @var $cmodel Cities */
?>
<div class="row">
    <?= $this->renderPartial('application.themes.desktop.v2.views.index.topSearch', array('model' => $model), true, FALSE); ?>
</div>
<!--<div class="row gray-bg-new">
    <div class="col-lg-10 col-sm-10 col-md-8 text-center flash_banner float-none marginauto ml50 border bg-white">
        <span class="h3 mt0 mb5 flash_red text-warning">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Outstation Taxi rentals and Airport transfers all over India </span><br>
        GozoCabs is India's leader in chauffeur-driven taxi travel for one-way drops, outstation trips, airport transfers and day-based rentals with complete billing transparency. Gozo’s coverage reaches over <b>1,000</b> towns & cities in India, with over <b>20,000</b> vehicles, over <b>100,000</b> satisfied customers and over <b>10</b> Million kms driven each year. Book your Gozo by web, phone or app 24x7! 
    </div>
</div>-->
<div class="container">
    <section id="section2">
        <div class="row p20">
            <div class="col-xs-12">
                <h1 class="mt0 font-24">Luxury Car rental service in <?= $city ?></h1>
                <div>
                    <p>Luxury Cars are a comfort & style statement. When Gozo’s team first thought of this, we know we have to fulfill customer’s desire for service and class by letting them hire luxury cars on rent in <?= $city ?> and all major cities in India. 
                        Luxury cars that we provide for rent range from a wide variety that cater to choices and tastes of our customers. To add to your special day, we provide the best luxury cars rentals in <?= $city ?>.
                    </p>
                </div>
                <h2 class="font-24 mt30">Pricing and options for Luxury Car Rentals in <?= $city ?></h2>

                <div class="col-8 pl0">
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
                                <td><?php //echo $c_types[1]['vht_estimated_cost'];  ?> Call us</td>

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
                                <td><?php //echo $c_types[0]['vht_estimated_cost']; ?>Call us</td>      
                            </tr>
                            <tr>
                                <th scope="row">Rolls Royce</th>
                                <td>Call us</td>       
                            </tr>
                        </tbody>
                    </table>
                </div>



                <h2 class="font-24 mt30">Why book with Gozo for Luxury car rental in <?= $city ?></h2>
                <div>
                    <p>
                        Gozo is india’s leader for luxury car rental services in top 50 of the 750 cities that we serve in India. Gozo's booking and billing process is completely transparent and you will get all the terms and conditions detailed on your booking confirmation. In addition you get instant booking confirmations, electronic invoices, 24x7 support and top quality for the best price.
                        If you have any special needs,  you can always contact our customer helpdesk and we will be happy to support you.Our services are top rated in almost all cities across India.
                        If you are looking to make an impression on your clients, or if you are serving the business elite you can call on Gozo to arrange pick up or drop facilities in luxury cars. You can also use Gozo’s luxury car rental services for a city tour through the day.

                    </p>
                    <h2 class="font-24 mt30">Hire Premium & luxury cars for wedding in <?= $city ?></h2>
                    <p>
                        To make your memorable day even more special, Gozo offers you an opportunity to choose from wide spectra of luxury cars. Due to the specific and less frequent nature of these requirements we request customers to make their reservations well in advance.
                        This provides us sufficient advance notice to arrange a vehicle for your needs.
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

