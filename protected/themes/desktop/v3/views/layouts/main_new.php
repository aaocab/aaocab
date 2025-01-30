<?php
$this->beginContent('//layouts/head');
if ($this->layout == 'column1')
{
	$style = "background-color: inherit";
}
$fixedTop				 = ($this->fixedTop) ? "navbar-fixed-top" : "";
$bgBanner				 = ($this->fixedTop) ? "bg-banner" : "";
//$version			 = Yii::app()->params['siteJSVersion'];
//Yii::app()->clientScript->registerPackage("uiControls");
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/aao/v3/bookingRoute.js?v=$version", CClientScript::POS_HEAD);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/aao/v3/booking.js?v=$version", CClientScript::POS_HEAD);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/aao/v3/hyperLocation.js?v=$version", CClientScript::POS_HEAD);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/userLogin.js?v=' . $version, CClientScript::POS_HEAD);
?>
<?php
/* @var $model BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/aao/v3/bookingRoute.js?v=$version");

$selectizeOptions = ['create'								 => false,
	'persist'								 => true,
	'selectOnTab'							 => true,
	'createOnBlur'							 => true,
	'dropdownParent'						 => 'body',
	'optg$selectizeOptionsroupValueField'	 => 'id',
	'optgroupLabelField'					 => 'text',
	'optgroupField'							 => 'id',
	'openOnFocus'							 => true,
	'preload'								 => false,
	'labelField'							 => 'text',
	'valueField'							 => 'id',
	'searchField'							 => 'text',
	'closeAfterSelect'						 => true,
	'addPrecedence'							 => false,
	];
if ($sourceCity == "")
{
	$cityList	 = Cities::model()->getJSONAirportCitiesAll();
	$pcityList	 = $cityList;
}
else
{
	$model->brt_from_city_id = $sourceCity;
	$cmodel					 = Cities::model()->getDetails($sourceCity);
	$sourceCityName			 = $cmodel->cty_name . ', ' . $cmodel->ctyState->stt_name;
	$pcityList				 = Cities::model()->getJSONNearestAll($previousCity);
}
if ($model->brt_from_city_id != '')
{
	$cityList = Cities::model()->getJSONNearestAll($model->brt_from_city_id);
}
$sourceDivClass	 = 'col';
$dateDivClass	 = 'col';
if ($btype == 2)
{
	$sourceDivClass	 = 'col-md-6';
	$dateDivClass	 = 'col-md-12';
}
if ($btype == 3)
{
	$mcitiesDiv = "  col-md-4";
}
//echo $model->estArrTime[$index];
$ctr	 = rand(0, 99) . date('mdhis');
$btype	 = ($btype == 0) ? $bmodel->bkg_booking_type : $btype;
?>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="http://www.googletagmanager.com/ns.html?id=GTM-T73295"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->


    <div class="">
        <header class="header">
             <section class="top-form-sec">
        <div class="container">
            <h2 class="text-center">Hi, User<br>
                Where do you want to go?</h2>
                <div class="form-box">
                    <ul class="nav common-tabs justify-content-center mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                          <span
                            class="nav-link"
                            id="transport-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#transport-one"
                            type="button"
                            role="tab"
                            aria-controls="transport-one"
                            aria-selected="true"
                            ><img src="images/local.svg" alt=""> Local</span
                          >
                        </li>
                        <li class="nav-item" role="presentation">
                          <span
                            class="nav-link active"
                            id="transport-two-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#transport-two"
                            type="button"
                            role="tab"
                            aria-controls="transport-two"
                            aria-selected="false"
                            ><img src="images/cab-icon.svg" alt=""> Outstation</span
                          >
                        </li>
                        <li class="nav-item" role="presentation">
                          <span
                            class="nav-link"
                            id="transport-three-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#transport-three"
                            type="button"
                            role="tab"
                            aria-controls="transport-three"
                            aria-selected="false"
                            ><img src="images/airport.svg" alt=""> Airport</span
                          >
                        </li>
                       
                        
                      </ul>
                      <div class="tab-content p-1 p-md-3" id="pills-tabContent">
                        <div
                          class="tab-pane fade"
                          id="transport-one"
                          role="tabpanel"
                          aria-labelledby="transport-one-tab"
                        >
                          
                        <ul class="nav about-tab  justify-content-center mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <span
                                    class="nav-link"
                                    id="local-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#local-one"
                                    type="button"
                                    role="tab"
                                    aria-controls="local-one"
                                    aria-selected="true"
                                    >4 hours / 40 Kms</span
                                >
                            </li>
                          <li class="nav-item" role="presentation">
                            <span
                              class="nav-link active"
                              id="local-two-tab"
                              data-bs-toggle="pill"
                              data-bs-target="#local-two"
                              type="button"
                              role="tab"
                              aria-controls="local-two"
                              aria-selected="false"
                              >8 hours / 80 Kms</span
                            >
                          </li>
                          <li class="nav-item" role="presentation">
                            <span
                              class="nav-link"
                              id="local-three-tab"
                              data-bs-toggle="pill"
                              data-bs-target="#local-three"
                              type="button"
                              role="tab"
                              aria-controls="local-three"
                              aria-selected="false"
                              >12 hours / 120 Kms</span
                            >
                          </li>
                          </ul>
                            
                            
                            
                          <div class="tab-content p-1 p-md-3" id="pills-tabContent">
<!--                              <div
                              class="tab-pane fade"
                              id="local-one"
                              role="tabpanel"
                              aria-labelledby="local-one-tab"
                              >
                              
                              4 hours / 40 Kms
                  
                              </div>-->
                              <div
                              class="tab-pane fade show active"
                              id="local-two"
                              role="tabpanel"
                              aria-labelledby="local-two-tab"
                              >
                              coming Soon
                                  
                              </div>
                             
                              
                          </div>
            
                        </div>
                        <div
                          class="tab-pane fade show active"
                          id="transport-two"
                          role="tabpanel"
                          aria-labelledby="transport-two-tab"
                        >

                        <ul class="nav about-tab  justify-content-center mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <span
                                class="nav-link  active"
                                id="trip-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#trip-one"
                                type="button"
                                role="tab"
                                aria-controls="trip-one"
                                aria-selected="true"
                                >One Way Trip</span
                              >
                            </li>
                            <li class="nav-item" role="presentation">
                              <span
                                class="nav-link"
                                id="trip-two-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#trip-two"
                                type="button"
                                role="tab"
                                aria-controls="trip-two"
                                aria-selected="false"
                                >Round Trip</span
                              >
                            </li>
                            <li class="nav-item" role="presentation">
                              <span
                                class="nav-link"
                                id="trip-three-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#trip-three"
                                type="button"
                                role="tab"
                                aria-controls="trip-three"
                                aria-selected="false"
                                >Multi City / Multi Day Trip</span
                              >
                            </li>
                           
                            
                          </ul>
                            <div class="tab-content p-1 p-md-3" id="pills-tabContent">
                                <div
                                class="tab-pane fade show active"
                                id="trip-one"
                                role="tabpanel"
                                aria-labelledby="trip-one-tab"
                                >
                                
                                <div class="row">
                                  <div class="col-md-6">
                                      <div class="from-input">
                                          <sapn class="icon"><img src="images/location.png" alt=""> </sapn>
                                          <label class="form-label">Pickup Location</label>
                                       
                                          <?php
                                          $model = new BookingRoute();
			$widgetId			 = $ctr . "_" . random_int(99999, 10000000);
         //  $model->brt_from_city_id = 33036;
			$this->widget('application.widgets.BRCities', array(
				'type'				 => 1,
				'enable'			 => ($index == 0),
				'widgetId'			 => $widgetId,
				'model'				 => $model,
				'attribute'			 => 'brt_from_city_id',
				'useWithBootstrap'	 => true,
                'isCookieActive'              =>     true,
                // 'cookieSource'              =>     $cookieSourceCity,
				"placeholder"		 => "Select City",
			));
			?>
			<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="from-input">
                                        <sapn class="icon"><img src="images/map.png" alt=""> </sapn>
                                        <label class="form-label">Drop Location</label>
                                        <input class="form-control" type="text" placeholder="Kolkata">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="from-input">
                                      <input class="form-control" type="date" placeholder="Kolkata">
                                  </div>
                              </div>
                              <div class="col-md-6">
                                <div class="from-input">
                                    <input class="form-control" type="time" placeholder="Kolkata">
                                </div>
                            </div>
                              </div>

                              <div class="text-center pt-3">
                                <button class="btn btn-blue book-now btn-lg">Book Cab</button>
                              </div>
                    
                                </div>
                                <div
                                class="tab-pane fade "
                                id="trip-two"
                                role="tabpanel"
                                aria-labelledby="trip-two-tab"
                                >
                                Coming Soon
                                
                                    
                                </div>
                                <div
                                class="tab-pane fade"
                                id="trip-three"
                                role="tabpanel"
                                aria-labelledby="trip-three-tab"
                                >
                               coming Soon
                                </div>
                                
                            </div>

                        </div>
                        <div
                          class="tab-pane fade"
                          id="transport-three"
                          role="tabpanel"
                          aria-labelledby="transport-three-tab"
                        >
                        <ul class="nav about-tab  justify-content-center mb-3" id="pills-tab" role="tablist">
                          <li class="nav-item" role="presentation">
                            <span
                              class="nav-link active"
                              id="airport-tab"
                              data-bs-toggle="pill"
                              data-bs-target="#airport-one"
                              type="button"
                              role="tab"
                              aria-controls="airport-one"
                              aria-selected="true"
                              >Pick up from Airport</span
                            >
                          </li>
                          <li class="nav-item" role="presentation">
                            <span
                              class="nav-link"
                              id="airport-two-tab"
                              data-bs-toggle="pill"
                              data-bs-target="#airport-two"
                              type="button"
                              role="tab"
                              aria-controls="airport-two"
                              aria-selected="false"
                              >Drop Off Airport</span
                            >
                          </li>
                     
                         
                          
                        </ul>
                          <div class="tab-content p-1 p-md-3" id="pills-tabContent">
                              <div
                              class="tab-pane fade show active"
                              id="airport-one"
                              role="tabpanel"
                              aria-labelledby="airport-one-tab"
                              >
                              
                              Coming Soon
                              </div>
                              <div
                              class="tab-pane fade"
                              id="airport-two"
                              role="tabpanel"
                              aria-labelledby="airport-two-tab"
                              >
                              
                             Coming Soon
                                  
                              </div>
                           
                              
                          </div>
                        </div>
                        
                      </div>
                      
                      
                      

                      
                </div>
        </div>
    </section>
    
            
            
  <?php echo $this->renderPartial("/index/customerRating");?>
<?php echo $this->renderPartial("/index/chooseUs");?>
    

        </header>
		<?php
		$time					 = Filter::getExecutionTime();
		$GLOBALS['time97']		 = $time;
		?>
		<script type="application/ld+json">
<?php
$organisationSchemaRaw	 = StructureData::getOrganisation();
echo json_encode($organisationSchemaRaw, JSON_UNESCAPED_SLASHES);
?>
		</script>
		<?php
		//echo $content;

		$time				 = Filter::getExecutionTime();
		$GLOBALS['time98']	 = $time;

		echo $this->renderPartial("/index/footer");
		?>
    </div>
	<?php
	$time				 = Filter::getExecutionTime();
	$GLOBALS['time99']	 = $time;

	$this->endContent();
	