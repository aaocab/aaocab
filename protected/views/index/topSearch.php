<style type="text/css">

    .checkbox-inline {
        padding-top: 0 !important;      
        padding-left: 30px;
        margin-top: -5px !important;      
    }

    .selectize-dropdown-content {
        overflow-y: auto;
        max-height: 200px;
    }

    .selectize-dropdown, .selectize-dropdown.form-control {
        border-radius: 0;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    }

    .selectize-dropdown [data-selectable], .selectize-dropdown .optgroup-header {
        padding: 6px 12px;
        border-bottom: solid 1px #aaa;
    }
    .nav-tabs>li.active ,.nav-tabs>li.active>a, li.active,.nav-tab li:active{
        color: #fff !important;
		/*background: #f13016 !important;*/
		background: #ff4f00 !important;
    }
    .timer-control {
        min-width: 100%;
    }
    .home-search,.home-search1{

    }
    .search-form-panel label{
        margin-bottom: 0;
        font-weight: normal;
    }
    .selectize-dropdown-content{
		padding: 0;
    }

	.cookies_panel{ position: absolute; bottom: 0; z-index: 9999;}
	/*.mob-out-banner img{ width: 100%;}*/
	.mob-app-img a{ width: 47%;}
	.search-pad{ padding-top: 20px!important ;padding-bottom: 18px!important; }
	.search-pad:hover{ padding-top: 20px!important ;padding-bottom: 18px!important;}
	.search-sub-text{ font-size: 0.8em; padding-bottom: 7px!important }

	.logo-section-box{ background: #fff; padding: 15px 10px 8px 10px; display: inline-block; border-radius: 4px; font-size: 12px; font-weight: 500;}
	.logo-fst{ width: 150px; float: left;}
	.logo-fst img{ width: 100%;}
	.logo-sec{ float: left;}
	.stop-menu .navbar-nav li a{ font-size: 14px;}
	.stop-menu .navbar-nav li{ padding: 3px!important;}
	.select-font{ font-weight: 900;}
	.bootbox{ z-index: 9999!important; position: absolute; top: 0;}
	/*.modal-body{ height: 500px;}*/
	@media (min-width: 991px) and (max-width: 1200px) {
		.logo-section-box{ padding: 8px;}
		.logo-fst{ width: 100px;}
		.logo-fst img{ width: 100px;}
		.logo-sec img{ width: 20%;}
	}
	@media (min-width: 768px) and (max-width: 1024px) {
		.logo-fst{ width: 80px;}
		.logo-fst img{ width: 100%;}
		.logo-sec{ font-size: 8px; width: 130px;}
		.logo-sec img{ width: 30%;}
		.stop-menu .navbar-nav li{ padding: 0!important;}
		.stop-menu .navbar-nav li a{ font-size: 10px; line-height: 18px!important; padding: 2px 5px!important;}

	}
	@media (min-width: 320px) and (max-width: 767px) {
		.logo-section-box{ padding: 8px; background: none;}
		.logo-fst{ width: 100px;}
		.logo-fst img{ width: 100px;}
		.logo-sec{ margin-top: -10px; font-size: 11px;}
		.logo-sec img{ width: 23%;}
	}
	.datepicker {
		padding: 5px
	}
	.btn-rounded.active{
		background-color: #4fb9a7;
		color:#fff;
	}
	.autoMarkerLoc{
		font-size: 30px;
		color:red;
		cursor: pointer;
	}

</style>
<script>
    function openNav()
    {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav()
    {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
<?php
if (Yii::app()->user->isGuest)
{
	$uname		 = '';
	$isLoggedin	 = false;
	?>

	<?php
}
else
{
	$isLoggedin	 = true;
	$uname		 = Yii::app()->user->loadUser()->usr_name;
	?>

<?php }
?>

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
$api					 = Yii::app()->params['googleBrowserApiKey'];

$autoAddressJSVer = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/hyperLocation.js?v=$autoAddressJSVer");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
<script>var hyperModel = new HyperLocation();</script>
<div id='covid'style="position:fixed; top: 0; left: 0; right: 0; z-index: 9999999; text-align: center; color: #fff; font-size: 18px;
width: 100%;
">
<div class="alert alert-warning alert-dismissible pt5 pb5" role="alert" style="background: #fcc521; border-radius: 0;">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 36px;">&times;</span></button>
  GozoCabs' Response to COVID-19 <a class="btn btn-primary" href="https://www.gozocabs.com/blog/sanitized-cars-ensuring-safe-ride/" target="_blank" role="button">Know More</a>
</div>
</div>


<header>
    <div class="col-xs-12 top-bar">
        <div class="row">
            <span class="hidden-sm hidden-md hidden-lg" style="font-size:20px;cursor:pointer; position:absolute; padding: 5px; top: 16px; right: 11px; z-index: 99;" onclick="openNav()"><i class="fa fa-bars"></i></span>
            <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2 pt10 logo-panel">
				<div class="row ml0"><figure>
						<span class="logo-section-box">
							<div class="logo-fst">
								<a class="" href="/"><img src="/images/logo2_outstation.png?v1.2" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></a>
							</div>

						</span></figure>
				</div>

            </div>
			<div class="col-md-1 col-lg-1 text-right mt10 pr0"><a href="/flashsale"><img src="/images/flashsale.gif?v=0.1" alt="" class="img-responsive"></a></div>
            <div class="col-xs-4 col-sm-6 col-md-6 col-lg-6 pl0 pr0">
                <div id="mySidenav" class="sidenav">
                    <a href="javascript:void(0)" class="closebtn border-none" onclick="closeNav()">&times;</a>
					<?php
					if ($isLoggedin)
					{
						?>
						<a href="#" class="dropdown-toggle">Hello <?= $uname ?><i class="fa fa-user" style="padding-left: 10px"></i></a>


						<a href="<?= Yii::app()->createUrl('users/view') ?>"><i class="fa fa-user pr10"></i> My Profile</a>
						<a href="<?= Yii::app()->createUrl('booking/list') ?>"><i class="fa fa-list pr10"></i> Booking list</a> 
						<a href="<?= Yii::app()->createUrl('users/refer'); ?>"><i class="fa fa-users"></i> Refer friends</a>
						<a href="<?= Yii::app()->createUrl('users/creditlist'); ?>"><i class="fa fa-book"></i> Gozo Coins</a>
						<a href="<?= Yii::app()->createUrl('users/changePassword') ?>"><nobr><i class="fa fa-pencil pr10"></i> Change Password</nobr></a> 
					<?php } ?>


                    <a href="/agent/join"><i class="fa fa-star mr5"></i> Become an Agent1111</a>
                    <a href="/vendor/join"><i class="fa fa-user mr5"></i>Attach Your Taxi</a>

                    <a href="/index/testimonial"><i class="fa fa-quote-left mr5"></i>Testimonials</a>
                    <a href="/blog"><i class="fa fa-comments-o mr5"></i> Our Blog</a>
					<?php
					if (!$isLoggedin)
					{
						?>
						<a href="/signin"><i class="fa fa-sign-in mr5"></i> Sign In</a>
						<?php
					}
					else
					{
						?>
						<a href="<?= Yii::app()->createUrl('users/logout') ?>"><i class="fa fa-sign-out pr10"></i>Log Out</a> 
					<?php } ?>
					<a href="#" class="dropdown-toggle helpline" data-toggle="dropdown" role="button" 
					   style="text-decoration: none;font-size: 1.4em"
					   aria-haspopup="true" aria-expanded="false" >Support Helpline</a>
                </div>

                <div class="stop-menu hidden-xs">
                    <nav class="navbar">
                        <div class="pl0">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed pull-right" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
<!--									<li><a href="/flashsale"><img src="/images/flashsale.gif?v=0.1" alt="" width="110"></a></li>-->
                                    <li><a href="/agent/join">Become an agent</a></li>
                                    <li><a href="/vendor/join">Attach Your Taxi</a></li>
                                    <li><a href="/blog">Blog</a></li>

                                    <li class="dropdown" id="navbar_sign">
										<?php
										Logger::create("Rendering Navbarsign: " . Filter::getExecutionTime());
										$this->renderDynamic('renderPartial', "/users/navbarsign", null, true);
										?>					 
                                    </li>
									<li><a href="javascript:void(0)" class="dropdown-toggle helpline" data-toggle="dropdown" role="button"   aria-haspopup="true" aria-expanded="false" >Contact Us</a></li>


                                </ul></div>
                            <!-- /.navbar-collapse -->
                        </div><!-- /.container-fluid -->

                    </nav>
                </div>
            </div>


            <div class="col-xs-3 text-right hidden-sm hidden-md hidden-lg mt20 n pr20">
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9 semail pt10 top-right-menu">
                        <div class="row">
                            <a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-phone"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">24x7 Support number</h4>
                        </div>
                        <div class="modal-body modal-call">
                            <div class="row">
                                <div class="col-xs-12 col-md-6 text-center">
                                    <a href="tel:+919051877000" style="text-decoration: none;">
                                        <img class="lozad" data-src="/images/india-flag.png" alt="India"> 
                                        (+91) 90518-77-000 
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-6 text-center">
                                    <a href="tel:+16507414696" style="text-decoration: none">
                                        <img class="lozad" data-src="/images/worl-icon.png" alt="International"> (+1) 650-741-GOZO
                                    </a>
                                </div> </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>
<div class="row m0 full-banner">
	<?php
	$detect		 = Yii::app()->mobileDetect;
	// call methods
	$isMobile	 = $detect->isMobile();
	if ($isMobile)
		goto skipMyCarousel;
	?>
    <div class="col-xs-12 col-sm-12 p0 banner_panel hidden-xs">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner banner-carousel" role="listbox" data-interval="10000">
                <div class="item active">
                    <figure><a href="/"><img class="lozad" data-src="/images/banner61.jpg?v=0.4" alt="India's Leader in Outstation Taxi Travel" title="India's Leader in Outstation Taxi Travel"></a></figure>
					<div class="carousel-caption hidden-xs hidden-sm hidden-md">
					</div>
                </div>
                <div class="item">
                    <figure><a href="/"><img class="lozad" data-src="/images/banner53.jpg?v=0.4" alt="India's Leader in Outstation Taxi Travel" title="India's Leader in Outstation Taxi Travel"></a></figure>
					<div class="carousel-caption hidden-xs hidden-sm hidden-md">
					</div>
                </div>
                <div class="item">
					<figure><a href="/"><img class="lozad" data-src="/images/banner45.jpg?v=0.5" alt="Outstation rides for 199*" title="Outstation rides for 199*"></a></figure>
					<div class="carousel-caption hidden-xs hidden-sm hidden-md">
					</div>
                </div>
				<div class="item">                
                    <figure><a href="/"><img class="lozad" data-src="/images/banner34.jpg?v=1.12" alt="Going Outstation? Go Gozo | 2,500+ Cities, 35,000+ Routes | Think Travel" title="Going Outstation? Go Gozo | 2,500+ Cities, 35,000+ Routes | Think Travel"></a></figure>
                    <div class="carousel-caption hidden-xs hidden-sm hidden-md">
                    </div>
                </div>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="fa fa-angle-left fa-3x" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="fa  fa-angle-right fa-3x" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
	<?php
	skipMyCarousel:
	?>

    <div class="col-xs-12 search-box">
        <div class="row">

            <div class="container search-form-panel">
                <div class="row">
                    <div class="col-xs-12 p0 hidden-xs">
                        <ul class="nav nav-tabs  ">
                            <li class="p0 text-center otrip active"><a href="#menu4" class="full-width" data-toggle="tab"  >One-way<br><span class="search-sub-text">FULL CAB</span></a></li>
<!--                            <li class="p0 text-center ostrip"><a href="#menu4" class="full-width  " data-toggle="tab">One-way<br><span class="search-sub-text">SHARED CAB</span></a></li>-->
							<!--							<li class="p0 text-center rtrip"><a href="#menu5" class="full-width  search-pad  " data-toggle="tab">Round Trip </a></li>-->
                            <li class="p0 text-center mtrip" style="white-space: nowrap"><a href="#menu6" class="full-width" data-toggle="tab">Round Trip or<br/>Multi City</a></li>
                            <li class="p0 text-center ttrip" style="white-space: nowrap"><a href="#menu7" class="full-width search-pad  " data-toggle="tab">Airport Transfer</a></li>
							<li class="p0 text-center ptrip" style="white-space: nowrap"><a href="#menu8" class="full-width search-pad  " data-toggle="tab">Tour Packages</a></li>
							<li class="p0 text-center strip" style="white-space: nowrap"><a href="#menu9" class="full-width search-pad  " data-toggle="tab">Shuttle</a></li>
                            <li class="p0 text-center drtrip" style="white-space: nowrap"><a href="#menu10" class="full-width search-pad" data-toggle="tab">Day Rental</a></li>
						</ul>

                    </div>
                    <div class="col-xs-12 mobile-menu p0 hidden-lg hidden-md hidden-sm">
                        <ul class="nav nav-tabs">
                            <li class="active p0 text-center col-xs-6 otrip"><a href="#menu4" data-toggle="tab">One-ways (Full Cab)</a></li>
							<!--                            <li class="p0 text-center col-xs-6 ostrip"><a href="#menu4"  data-toggle="tab">One-way (Shared Cabs)</a></li>-->
							<!--							<li class="p0 text-center col-xs-4 rtrip"><a href="#menu5" data-toggle="tab">Round Trip </a></li>-->
                            <li class="p0 text-center col-xs-3 mtrip"><a href="#menu6" data-toggle="tab">Round Trip or<br/>Multi City</a></li>
                            <li class="p0 text-center col-xs-5 ttrip" style="white-space: nowrap"><a href="#menu7" class="" data-toggle="tab">Airport Transfer</a></li>
							<!--<li class="p0 text-center hide ptrip" style="white-space: nowrap"><a href="#menu8" class="full-width search-pad  " data-toggle="tab">Package</a></li>-->

						</ul>

                    </div>

                    <div class="tab-content col-xs-12" style="height: 100%">
                        <div class="tab-pane active home-search mt10 mb5" id="menu4">
							<?php
							/* @var $form TbActiveForm|CWidget */
							$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'bookingSform',
								'enableClientValidation' => true,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error',
									'afterValidate'		 => 'js:function(form,data,hasError){
                            if(!hasError){
                            var success = false;
								$.ajax({
									"type":"POST",
									"async":false,
									"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')) . '",
									"data":form.serialize(),
									"dataType": "json",
									"success":function(data1){
										if(data1.success)
										{
										success = true;
										}
										else{
										var errors = data1.errors;
										var content = "";
										for(var key in errors){
											$.each(errors[key], function (j, message) {
											content = content + message + \'\n\';
											});
										}
										alert(content);
										}
									},
									});
												return success;                                
									}
								}'
								),
								'enableAjaxValidation'	 => false,
								'errorMessageCssClass'	 => 'help-block',
								'action'				 => Yii::app()->createUrl('booking/booknow'),
								'htmlOptions'			 => array(
									'class' => 'form-horizontal',
								),
							));
							/* @var $form TbActiveForm */
							/** @var BookingTemp $model */
							$brtModel	 = $model->bookingRoutes[0];
							if (trim($brtModel->brt_from_city_id) == "")
							{
								$brtModel->brt_from_city_id = $model->bkg_from_city_id;
							}
							?>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-lg-6">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
											<?php //= $form->errorSummary($brtModel); ?>
											<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 1, 'id' => 'bkg_booking_type1']); ?>
											<?= $form->hiddenField($model, 'bktyp', ['value' => 1, 'id' => 'bktyp1']); ?>
											<?= $form->hiddenField($model, 'bkg_transfer_type', ['value' => 0, 'id' => 'bkg_transfer_type1']); ?>
                                            <input type="hidden" id="step11" name="step" value="1">
                                            <label> Going From</label>
											<?php
											$this->widget('ext.yii-selectize.YiiSelectize', array(
												'model'				 => $brtModel,
												'attribute'			 => 'brt_from_city_id',
												'useWithBootstrap'	 => true,
												"placeholder"		 => "Select City",
												'fullWidth'			 => false,
												'htmlOptions'		 => array('width' => '50%', ''
												),
												'defaultOptions'	 => $selectizeOptions + array(
											'onInitialize'	 => "js:function(){
													populateSource(this, '{$brtModel->brt_from_city_id}');
													$('.selectize-control INPUT').attr('autocomplete','new-password');
												}",
											'load'			 => "js:function(query, callback){
													loadSource(query, callback);
												}",
											'onChange'		 => "js:function(value) {
													changeDestination(value, \$dest_city);
												}",
											'render'		 => "js:{
												option: function(item, escape){                      
												return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
												},
												option_create: function(data, escape){
												return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
													}
												}",
												),
											));
											echo $form->hiddenField($brtModel, "brt_from_location", ['id' => 'Onelocation0']);
											echo $form->hiddenField($brtModel, "brt_from_latitude", ['id' => 'OnelocLat0']);
											echo $form->hiddenField($brtModel, "brt_from_longitude", ['id' => 'OnelocLon0']);
											echo $form->hiddenField($brtModel, "brt_from_formatted_address", ['id' => 'OnelocFAdd0']);
											echo $form->hiddenField($brtModel, 'brt_from_is_airport', ['id' => 'OneisAirport0']);
											?>
                                            <span class="has-error"><?php //echo $form->error($brtModel, 'brt_from_city_id');                                ?></span>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <label>Going To</label>
											<?php
											$this->widget('ext.yii-selectize.YiiSelectize', array(
												'model'				 => $brtModel,
												'attribute'			 => 'brt_to_city_id',
												'useWithBootstrap'	 => true,
												"placeholder"		 => "Select City",
												'fullWidth'			 => false,
												'htmlOptions'		 => array('width' => '50%'
												),
												'defaultOptions'	 => $selectizeOptions + array(
											'onInitialize'	 => "js:function(){ 
														$('.selectize-control INPUT').attr('autocomplete','new-password');                            
														\$dest_city=this;
												}",
											'render'		 => "js:{
												option: function(item, escape){
												return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
												},
												option_create: function(data, escape){
												 return '<div>' +'<span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(data.text) + '</span></div>';
											   }
											   }",
												),
											));
											echo $form->hiddenField($brtModel, "brt_to_location", ['id' => 'Onelocation1']);
											echo $form->hiddenField($brtModel, "brt_to_latitude", ['id' => 'OnelocLat1']);
											echo $form->hiddenField($brtModel, "brt_to_longitude", ['id' => 'OnelocLon1']);
											echo $form->hiddenField($brtModel, "brt_to_formatted_address", ['id' => 'OnelocFAdd1']);
											echo $form->hiddenField($brtModel, 'brt_to_is_airport', ['id' => 'OneisAirport1']);
											?>
                                            <span class="has-error"><?php echo $form->error($brtModel, ' brt_to_city_id'); ?></span>
                                            <span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_date'); ?></span>
                                            <span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_time'); ?></span>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-lg-4">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <label>Journey Date</label>
											<?php
											$defaultDate	 = date('Y-m-d H:i:s', strtotime('+2 days'));
											$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+3 days'));
											$minDate		 = date('Y-m-d H:i:s ', strtotime('+4 hour'));
											$pdate			 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $brtModel->brt_pickup_date_date;
											?>
											<?=
											$form->datePickerGroup($brtModel, 'brt_pickup_date_date', array('label'			 => '',
												'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
														'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
														'value'			 => $pdate,
														'class'			 => 'form-control border-radius')),
												'groupOptions'	 => ['class' => 'm0'],
												'prepend'		 => '<i class = "fa fa-calendar"></i>'));
											?>
                                        </div>
										<div class="col-xs-12 col-sm-6 col-md-6">
                                            <label>Journey Time</label>
                                            <div class="input-group timer-control">
												<?php
												$this->widget('ext.timepicker.TimePicker', array(
													'model'			 => $brtModel,
													'id'			 => 'brt_pickup_date_time_1' . date('mdhis'),
													'attribute'		 => 'brt_pickup_date_time',
													'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
													'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius')
												));
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-2 col-lg-2  pb20 text-center">
                                    <button type="submit" class="btn btn-primary proceed-new-btn hide">proceed</button>
									<button type="button" class="btn btn-primary proceed-new-btn" id="onewaybtn">proceed</button>
                                </div>
                            </div>
							<?php $this->endWidget(); ?>
                        </div>
                        <div class="tab-pane home-search mt10 mb5" id="menu5">
                            <div id='returnform'>
								<?php
								$form1			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'bookingRform',
									'enableClientValidation' => true,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'errorCssClass'		 => 'has-error',
										'afterValidate'		 => 'js:function(form, data, hasError){
										if(!hasError){
										var success = false;
										$.ajax({
										"type":"POST",
										"async":false,
										"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')) . '",
										"data":form.serialize(),
										"dataType": "json",
										"success":function(data1){
										if(data1.success)
										{
										success = true;
										}
										else{
										var errors = data1.errors;
										var content = "";
										for(var key in errors){
										$.each(errors[key], function (j, message) {
										content = content + message + \'\n\';
												});
												}
													alert(content); }  },
											});
											return success;
											}
										}'
									),
									'enableAjaxValidation'	 => false,
									'errorMessageCssClass'	 => 'help-block',
									'action'				 => Yii::app()->createUrl('booking/booknow'),
									'htmlOptions'			 => array(
										'class' => 'form-horizontal',
									),
								));
								/* @var $form1 TbActiveForm */
								?>
                                <div class="row">
                                    <div class="col-xs-12 col-md-12 col-lg-5">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
												<?php //= $form1->errorSummary($model);  ?>
                                                <div id='bkt'>
													<?= $form1->hiddenField($model, 'bkg_booking_type', ['value' => 2, 'id' => 'bkg_booking_type2']); ?>
													<?= $form1->hiddenField($model, 'bktyp', ['value' => 2, 'id' => 'bktyp2']); ?>
													<?= $form1->hiddenField($brtModel, 'brt_return_date_time', ['value' => '10:00 PM']); ?>
                                                    <input type="hidden" id="step12" name="step" value="1">
                                                    <input type="hidden" id="step22" name="step2" value="2">
                                                </div>
                                                <div class="input-group col-xs-12">
                                                    <label>Source</label>
													<?php
													$this->widget('ext.yii-selectize.YiiSelectize', array(
														'model'				 => $brtModel,
														'attribute'			 => 'brt_from_city_id',
														'useWithBootstrap'	 => true,
														"placeholder"		 => "Source City",
														'fullWidth'			 => false,
														'htmlOptions'		 => array('width'	 => '50%', 'id'	 => 'bkg_from_city_id1',
														),
														'defaultOptions'	 => $selectizeOptions + array(
													'onInitialize'	 => "js:function(){
														populateSource(this, '{$brtModel->brt_from_city_id}');
														$('.selectize-control INPUT').attr('autocomplete','new-password');                            

													}",
													'load'			 => "js:function(query, callback){
														loadSource(query, callback);
													 }",
													'onChange'		 => "js:function(value) {
														changeDestination(value, \$dest_city1);
													}",
													'render'		 => "js:{
															option: function(item, escape){                      
																	return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
															},
															option_create: function(data, escape){
																 return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
														   }
														}",
														),
													));
													?>
                                                    <span class="has-error"><?php echo $form->error($brtModel, 'brt_from_city_id'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group col-xs-12">
                                                    <label>Destination</label>
													<?php
													$this->widget('ext.yii-selectize.YiiSelectize', array(
														'model'				 => $brtModel,
														'attribute'			 => 'brt_to_city_id',
														'useWithBootstrap'	 => true,
														"placeholder"		 => "Select Destination",
														'fullWidth'			 => false,
														'htmlOptions'		 => array('id'	 => 'bkg_to_city_id1', 'width'	 => '50%'
														),
														'defaultOptions'	 => $selectizeOptions + array(
													'onInitialize'	 => "js:function(){
														$('.selectize-control INPUT').attr('autocomplete','new-password');                            
													\$dest_city1=this;
													}",
													'render'		 => "js:{
                                         option: function(item, escape){                      
                                                 return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
                                         },
                                         option_create: function(data, escape){
                                              return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
						}
					    }",
														),
													));
													?>
                                                    <span class="has-error"><?php echo $form1->error($brtModel, 'brt_to_city_id1'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-12 col-lg-7">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-4 col-lg-3">

                                                <label>Start Date</label>
												<?=
												$form1->datePickerGroup($brtModel, 'brt_pickup_date_date', array('label'			 => '',
													'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
															'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
															'value'			 => $pdate, 'id'			 => 'Booking_bkg_pickup_date_date1',
															'class'			 => 'border-radius ')), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>
                                                <span class="has-error"><?php echo $form1->error($model, 'bkg_pickup_date_date1'); ?></span>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-lg-3">
                                                <label>Start Time</label>
												<?php
												$this->widget('ext.timepicker.TimePicker', array(
													'model'			 => $brtModel,
													'id'			 => 'brt_pickup_date_time_2' . date('mdhis'),
													'attribute'		 => 'brt_pickup_date_time',
													'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
													'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius')
												));
												?>
                                            </div>
                                            <span class="has-error"><?php echo $form1->error($model, 'brt_pickup_date_date1'); ?></span>
                                            <span class="has-error"><?php echo $form1->error($model, 'brt_pickup_date_time1'); ?></span>

                                            <div class="col-xs-12 col-sm-4 col-lg-3">

                                                <label>Return Date</label>
												<?php
												echo $form1->datePickerGroup($brtModel, 'brt_return_date_date', array('label'			 => '',
													'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
															'format'	 => 'dd/mm/yyyy'),
														'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Return Date',
															'value'			 => DateTimeFormat::DateTimeToDatePicker($defaultRDate), 'id'			 => 'Booking_bkg_return_date_date',
															'class'			 => 'border-radius ')), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>
                                            </div>
                                            <div class="col-sm-4 col-md-6 hide col-lg-3">
                                                <label>Return Time</label>

                                            </div>

                                            <span class="has-error"><?php echo $form1->error($brtModel, 'brt_pickup_date_date1'); ?></span>
                                            <span class="has-error"><?php echo $form1->error($model, 'brt_pickup_date_time1'); ?></span>
                                            <div class="col-sm-12 col-lg-3 pb20 text-center">
                                                <button type="submit" class="btn btn-primary proceed-new-btn">proceed</button>
                                            </div>
                                        </div>
                                    </div>
									<?php $this->endWidget(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane home-search mt10 mb5" id="menu6">
                            <div id='multiform'>
								<?php
								$form2			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'bookingMform',
									'enableClientValidation' => true,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'errorCssClass'		 => 'has-error',
										'afterValidate'		 => 'js:function(form,data,hasError){
					    if(!hasError){
					    var success = false;
						$.ajax({
						    "type":"POST",
						    "async":false,
						    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateSearch')) . '",
						    "data":form.serialize(),
							"dataType": "json",
							"success":function(data1){
							    if(data1.success)
							    {
								success = true;
							    }
							    else{
								var errors = data1.errors;
								var content = "";
								for(var key in errors){
								    $.each(errors[key], function (j, message) {
									content = content + message + \'\n\';
								    });
								}
								alert(content);
							    }
							},
						    });
						return success;

					    }
					}'
									),
									'enableAjaxValidation'	 => false,
									'errorMessageCssClass'	 => 'help-block',
									'action'				 => Yii::app()->createUrl('booking/booknow'),
									'htmlOptions'			 => array(
										'class' => 'form-horizontal',
									),
								));
								/* @var $form1 TbActiveForm */
								?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-3">
										<?= $form2->errorSummary($model); ?>
                                        <div id='bkt'>
											<?= $form2->hiddenField($model, 'bkg_booking_type', ['value' => 3, 'id' => 'bkg_booking_type3']); ?>
											<?= $form2->hiddenField($model, 'bktyp', ['value' => 3, 'id' => 'bktyp3']); ?>
                                            <input type="hidden" id="step23" name="step2" value="2">
                                            <input type="hidden" id="step13" name="step" value="1">

                                        </div>
                                        <div class="input-group col-xs-12">
                                            <label>Going From</label>
											<?php
											$this->widget('ext.yii-selectize.YiiSelectize', array(
												'model'				 => $brtModel,
												'attribute'			 => 'brt_from_city_id',
												'useWithBootstrap'	 => true,
												"placeholder"		 => "Select City",
												'fullWidth'			 => false,
												'htmlOptions'		 => array('width'	 => '50%', 'id'	 => 'bkg_from_city_id_1'
												),
												'defaultOptions'	 => $selectizeOptions + array(
											'onInitialize'	 => "js:function(){
												$('.selectize-control INPUT').attr('autocomplete','new-password');                            
							populateSource(this, '{$model->bkg_from_city_id}');
						}",
											'load'			 => "js:function(query, callback){
							loadSource(query, callback);
						}",
											'onChange'		 => "js:function(value) {
							changeDestination(value, \$dest_city_1);
						}",
											'render'		 => "js:{
							    option: function(item, escape){                      
								return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
							    },
							    option_create: function(data, escape){
								return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
							   }
							}",
												),
											));
											?>
                                            <span class="has-error"><?php echo $form->error($model, 'bkg_from_city_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-3">
                                        <div class="input-group col-xs-12">
                                            <label>Going To</label>
											<?php
											$this->widget('ext.yii-selectize.YiiSelectize', array(
												'model'				 => $brtModel,
												'attribute'			 => 'brt_to_city_id',
												'useWithBootstrap'	 => true,
												"placeholder"		 => "Select City",
												'fullWidth'			 => false,
												'htmlOptions'		 => array('id'	 => 'bkg_to_city_id_1', 'width'	 => '50%'
												),
												'defaultOptions'	 => $selectizeOptions + array(
											'onInitialize'	 => "js:function(){
												$('.selectize-control INPUT').attr('autocomplete','new-password');                            
                                \$dest_city_1=this;
                            }",
											'render'		 => "js:{
                                option: function(item, escape){                      
                                        return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
                                },
                                option_create: function(data, escape){
                                     return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                               }
                           }",
												),
											));
											?>
                                            <span class="has-error"><?php echo $form1->error($model, 'bkg_to_city_id1'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-4">
                                                <label>Start Date</label>
												<?=
												$form2->datePickerGroup($brtModel, 'brt_pickup_date_date', array('label'			 => '',
													'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
															'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
															'value'			 => $pdate, 'id'			 => 'Booking_bkg_pickup_date_date_1',
															'class'			 => 'border-radius ')), 'groupOptions'	 => ['class' => 'm0'], 'prepend'		 => '<i class="fa fa-calendar"></i>'));
												?>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <label>Start Time</label>

												<?php
												$this->widget('ext.timepicker.TimePicker', array(
													'model'			 => $brtModel,
													'id'			 => 'brt_pickup_date_time_3' . date('mdhis'),
													'attribute'		 => 'brt_pickup_date_time',
													'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
													'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius')
												));
												?>
                                            </div>
                                            <span class="has-error"><?php echo $form2->error($model, 'brt_pickup_date_date_1'); ?></span>
                                            <span class="has-error"><?php echo $form2->error($model, 'brt_pickup_date_time_1'); ?></span>
                                            <div class="col-sm-12 col-md-4">
                                                <div class="input-group col-xs-12 pb20  text-center">
                                                    <button type="submit" class="btn btn-primary proceed-new-btn">Add more city</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<?php $this->endWidget(); ?>
                            </div>
                        </div>
                        <div class="tab-pane home-search mt10 mb5" id="menu7">
							<?php
							/* @var $form TbActiveForm|CWidget */
							$form3			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'bookingTrform',
								'enableClientValidation' => true,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error',
									'afterValidate'		 => 'js:function(form,data,hasError){
					 
								if(!hasError){
								return true;

								}
								}'
								),
								'enableAjaxValidation'	 => false,
								'errorMessageCssClass'	 => 'help-block',
								'action'				 => Yii::app()->createUrl('booking/booknow'),
								'htmlOptions'			 => array(
									'class' => 'form-horizontal',
								),
							));
							/* @var $form TbActiveForm */
							?>

							<?= $form3->errorSummary($model); ?>
							<?= $form3->hiddenField($model, 'bkg_booking_type', ['value' => 4, 'id' => 'bkg_booking_type4']); ?>
							<?= $form3->hiddenField($model, 'bktyp', ['value' => 4, 'id' => 'bktyp4']); ?>
							<?= $form3->hiddenField($brtModel, 'brt_from_city_id', ['id' => 'ctyIdAir0']); ?>
							<?= $form3->hiddenField($brtModel, 'brt_to_city_id', ['id' => 'ctyIdAir1']); ?>
							<input type="hidden" id="step14" name="step" value="1">
                            <div class="row">
								<div class="col-xs-12 col-sm-2 col-lg-2">
                                    Pickup Type<br>

                                    <div class="btn-group" data-toggle="buttons">
										<?php
										echo $form->dropDownList($model, 'bkg_transfer_type', array("1" => "From the Airport", "2" => "To the Airport"), array('class' => "form-control selectize-input items not-full select-font"));
										?>
                                    </div>
                                </div>


                                <div class="col-xs-12 col-sm-6 col-lg-5" id="ttype" >
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-lg-6 pb10" id="s1">
                                            <label id="slabel">From the Airport</label>
											<?php
											$this->widget('ext.yii-selectize.YiiSelectize', array(
												'model'				 => $model,
												'attribute'			 => 'bkgAirport',
												'useWithBootstrap'	 => true,
												"placeholder"		 => "Select Airport",
												'fullWidth'			 => false,
												'htmlOptions'		 => array('width' => '50%'
												),
												'defaultOptions'	 => $selectizeOptions + array(
											'onInitialize'	 => "js:function(){
															populateAirportList(this, '{$model->bkgAirport}');
														}",
											'load'			 => "js:function(query, callback){
															loadAirportSource(query, callback);
														}",
											'onChange'		 => "js:function(value) {
																			hyperModel.changeTrDestination(value,  {$brtModel->brt_from_city_id});
														}",
											'render'		 => "js:{
																option: function(item, escape){                      
																return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
																},
																option_create: function(data, escape){
																return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
															   }
															}",
												),
											));
											?>
											<span class="has-error"><?php echo $form->error($model, 'bkg_from_city_id'); ?></span>
											<?php
											echo $form3->hiddenField($brtModel, "brt_from_location", ['id' => "brt_location0"]);
											echo $form3->hiddenField($brtModel, "brt_from_latitude", ['id' => 'locLat0']);
											echo $form3->hiddenField($brtModel, "brt_from_longitude", ['id' => 'locLon0']);
											echo $form3->hiddenField($brtModel, "brt_from_place_id", ['id' => 'locPlaceid0']);
											echo $form3->hiddenField($brtModel, "brt_from_formatted_address", ['id' => 'locFAdd0']);
											echo $form3->hiddenField($brtModel, 'brt_from_is_airport', ['id' => 'isAirport0']);
											echo $form3->hiddenField($brtModel, "brt_from_location_cpy", ['class' => 'cpy_loc_0']);
											?>
                                            <span class="has-error"><?php echo $form->error($brtModel, 'brt_from_city_id'); ?></span>
                                        </div>
										<!--div class="col-xs-12 col-sm-2 col-lg-2 pb10" style="text-align: center;display:table;height: 81px;cursor: pointer;">
											<i class="fa fa-exchange" aria-hidden="true" style="display: table-cell;vertical-align: middle;font-size: 18px;" onclick="hyperModel.swap()"></i>
										</div-->
                                        <div class="col-xs-12 col-sm-6 col-lg-6 pb10" id="s2">
                                            <label id="dlabel">To Address</label>
											<div class="row">
												<div class="col-xs-10">
													<?php
													echo $form3->textFieldGroup($brtModel, "brt_to_location", array('label' => '', 'widgetOptions' => array('htmlOptions' => ['id' => "brt_location1", 'class' => "form-control autoComLoc", "autocomplete" => "section-new", 'placeholder' => "Location", 'onblur' => "hyperModel.clearAddress(this,'airport')"])));
													?>
												</div>
												<div class="col-xs-2">
													<span class="autoMarkerLoc" data-lockey="1" data-toggle="tooltip" title="Select destination location on map"><img data-src="/images/locator_icon4.png" class="lozad" alt="Precise location" width="30" height="30"></span>
												</div>
											</div>
											<?= $form3->hiddenField($brtModel, "brt_to_latitude", ['id' => "locLat1"]); ?>
											<?= $form3->hiddenField($brtModel, "brt_to_longitude", ['id' => "locLon1"]); ?>
											<?= $form3->hiddenField($brtModel, "brt_to_place_id", ['id' => "locPlaceid1"]); ?>
											<?= $form3->hiddenField($brtModel, "brt_to_formatted_address", ['id' => "locFAdd1"]); ?>
											<?= $form3->hiddenField($brtModel, 'brt_to_is_airport', ['id' => 'isAirport1', 'value' => 0]); ?>
											<?= $form3->hiddenField($brtModel, 'brt_to_location_cpy', ['class' => 'cpy_loc_1']); ?>
                                            <span class="has-error"><?php echo $form3->error($brtModel, 'brt_to_city_id'); ?></span>
                                            <span class="has-error"><?php echo $form3->error($brtModel, 'brt_pickup_date_date'); ?></span>
                                            <span class="has-error"><?php echo $form3->error($brtModel, 'brt_pickup_date_time'); ?></span>
                                        </div>
                                    </div>
                                </div>

								<div class="col-xs-12 col-sm-4 col-lg-3">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-7">
                                            <label>Journey Date</label>
											<?php
											$defaultDate	 = date('Y-m-d H:i:s', strtotime('+7 days'));
											$defaultRDate	 = date('Y-m-d H:i:s', strtotime('+8 days'));
											$minDate		 = date('Y-m-d H:i:s', strtotime('+4 hour'));
											$pdate			 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $model->bkg_pickup_date_date;
											?>
											<?=
											$form3->datePickerGroup($brtModel, 'brt_pickup_date_date', array('label'			 => '',
												'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
														'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
														'value'			 => $pdate, 'id'			 => 'Booking_brt_pickup_date_date_11',
														'class'			 => 'form-control border-radius')),
												'groupOptions'	 => ['class' => 'm0'],
												'prepend'		 => '<i class="fa fa-calendar"></i>'));
											?>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-5">
                                            <label>Journey Time</label>
                                            <div class="input-group full-width">
												<?php
												$this->widget('ext.timepicker.TimePicker', array(
													'model'			 => $brtModel,
													'id'			 => 'brt_pickup_date_time_4' . date('mdhis'),
													'attribute'		 => 'brt_pickup_date_time',
													'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
													'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius')
												));
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<div class="col-xs-12 col-lg-2 text-center mt20">
									<button type="button" class="btn btn-primary proceed-new-btn mt0" id="btnTransfer">proceed</button>
								</div>
							</div>
							<script>
                                hyperModel.initializeplAirport();
                                $('.autoComLoc').change(function () {
                                    $('#btnTransfer').attr('disabled', true);
                                    $('#btnTransfer').text('Loading...');
                                    hyperModel.findAddressAirport(this.id);
                                });
                                $('#btnTransfer').click(function () {
                                    $.ajax({
                                        "type": "POST",
                                        "async": false,
                                        "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateAirport')) ?>',
                                        "data": $('#bookingTrform').serialize(),
                                        "dataType": "json",
                                        "success": function (data1)
                                        {
                                            if (data1.success)
                                            {
                                                if (data1.hasOwnProperty("errors"))
                                                {
                                                    $("#bkg_booking_type4").val(1);
                                                }
                                                $('#bookingTrform').submit();
                                            } else
                                            {
                                                var errors = data1.errors;
                                                var content = "";
                                                for (var key in errors)
                                                {
                                                    $.each(errors[key], function (j, message) {
                                                        content = content + message + '\n';
                                                    });
                                                }
                                                alert(content);
                                            }
                                        }

                                    });

                                });

                                $('select[name="BookingTemp[bkg_transfer_type]"]').change(function (event) {
                                    var radVal = $(event.currentTarget).val();
                                    var dlabel = (radVal == 2) ? 'From Address' : 'To Address';
                                    var slabel = (radVal == 1) ? 'From the Airport' : 'To the Airport';
                                    $('#slabel').text(slabel);
                                    $('#dlabel').text(dlabel);
                                    $('#trslabel').text(slabel);
                                    $('#trdlabel').text(dlabel);
                                    if (radVal == 2)
                                    {
                                        $('.autoMarkerLoc').attr('data-original-title', 'Select source location on map');
                                    } else
                                    {
                                        $('.autoMarkerLoc').attr('data-original-title', 'Select destination location on map');
                                    }
                                });

                                $('.autoMarkerLoc').click(function (event) {
                                    var locKey = $(event.currentTarget).data('lockey');
                                    var lat = $('#locLat1').val();
                                    var long = $('#locLon1').val();
                                    var isAirport = $('#isAirport1').val();
                                    if (lat == '' || long == '')
                                    {
                                        lat = $('#locLat0').val();
                                        long = $('#locLon0').val();

                                    }
                                    if (lat == '' || long == '')
                                    {
                                        alert("Please select airport first");
                                    } else
                                    {
                                        var transferType = $('#BookingTemp_bkg_transfer_type').val();
                                        var Loclabel = (transferType == 1) ? "Enter approximate destination location and then move pin to exact location" : "Enter approximate source location and then move pin to exact location";
                                        var locSearch = (transferType == 1) ? "destination" : "source";
                                        $('#mapModalLabel').html(Loclabel);
                                        $.ajax({
                                            "type": "POST",
                                            "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/autoMarkerAddress')) ?>',
                                            "data": {"ctyLat": lat, "ctyLon": long, "bound": '', "isCtyAirport": isAirport, "isCtyPoi": 0, "locKey": locKey, "location": locSearch, "airport": 1, "YII_CSRF_TOKEN": $("input[name='YII_CSRF_TOKEN']").val()},
                                            "dataType": "HTML",
                                            "success": function (data1)
                                            {
                                                $('#mapModelContent').html(data1);
                                                $('#mapModal').modal('show');
                                            }

                                        });
                                    }
                                });

							</script>
							<?php $this->endWidget(); ?>
                        </div>

						<div class="tab-pane   home-search mt10 mb5 " id="menu8">
							<div class="col-xs-12">
								<!--<a href="/packages" class="btn btn-primary">Go to Packages</a> -->
								<?php
								$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'driver-register-form', 'enableClientValidation' => FALSE,
									'action'				 => array('/packages'),
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'errorCssClass'		 => 'has-error'
									),
									'enableAjaxValidation'	 => false,
									'errorMessageCssClass'	 => 'help-block',
									'htmlOptions'			 => array(
										'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
									),
								));
								/* @var $form TbActiveForm */

								$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
									'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
									'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
									'openOnFocus'		 => true, 'preload'			 => false,
									'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
									'addPrecedence'		 => false,];
								?>

								<div class="col-xs-3 mb20">Going From
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'from_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width' => '100%',
										//  'id' => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
										$('.selectize-control INPUT').attr('autocomplete','new-password');                            
				  populateSourceCityPackage(this, '{$model->from_city}');
								}",
									'load'			 => "js:function(query, callback){
				loadSourceCityPackage(query, callback);
				}",
									'render'		 => "js:{
				option: function(item, escape){
				return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
				},
				option_create: function(data, escape){
				return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
				}
				}",
										),
									));
									?>
								</div>
								<div class="col-xs-5">
									<?php
									$model->min_nights	 = 0;
									$model->max_nights	 = 10;
									?>
									<div class="col-xs-6 pr0 mr0"><div class="col-xs-12">Min No. of Nights</div><div class="col-xs-6"><?php echo $form->numberFieldGroup($model, 'min_nights', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "", 'width' => '10px;', 'min' => 0]), 'groupOptions' => ['class' => 'm0'])); ?></div></div>
									<div class="col-xs-6 pl0 ml0"><div class="col-xs-12">Max No. of Nights</div><div class="col-xs-6"><?php echo $form->numberFieldGroup($model, 'max_nights', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "", 'width' => '10px;', 'min' => 0]), 'groupOptions' => ['class' => 'm0'])); ?></div></div>
								</div>
								<div class="col-xs-2 pt5"><input type="submit" class="btn btn-primary proceed-new-btn" value="PROCEED"></div>

								<?php $this->endWidget(); ?>
							</div>
                        </div>
						<div class="tab-pane home-search mt10 mb5 " id="menu9">

							<!--<a href="/packages" class="btn btn-primary">Go to Packages</a> -->
							<?php
							$form7				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'shuttleform',
								'enableClientValidation' => true,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error',
									'afterValidate'		 => 'js:function(form,data,hasError){
					 
								if(!hasError){
								return true;

								}
								}'
								),
								'enableAjaxValidation'	 => false,
								'errorMessageCssClass'	 => 'help-block',
								'action'				 => Yii::app()->createUrl('booking/booknow'),
								'htmlOptions'			 => array(
									'class' => 'form-horizontal',
								),
							));
							/* @var $form TbActiveForm */
							?>
							<?= $form2->hiddenField($model, 'bkg_booking_type', ['value' => 7, 'id' => 'bkg_booking_type3']); ?>
							<?= $form2->hiddenField($model, 'bktyp', ['value' => 7, 'id' => 'bktyp7']); ?>
							<input type="hidden" id="step27" name="step2" value="2">
							<input type="hidden" id="step17" name="step" value="1">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-2   ">

									<label>Depart date</label>
									<div class="input-group col-xs-12">


										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<?php
										$this->widget('booster.widgets.TbDatePicker', array(
											'model'			 => $brtModel,
											'attribute'		 => 'brt_pickup_date_date',
											//'val' => $date,
											//  'label' => '',
											'options'		 => ['autoclose' => true, 'startDate' => "js:new Date('$minDate')", 'format' => 'dd/mm/yyyy'],
											'htmlOptions'	 => array('id' => 'brt_pickup_date_date_shuttle', 'value' => $brtModel->brt_pickup_date_date, 'min' => $brtModel->brt_min_date, 'placeholder' => 'Pickup Date', 'class' => 'form-control datePickup border-radius')
										));
										?>

									</div>

									<input type='hidden' id="<?= 'brt_pickup_date_time_' . date('mdhis') ?>" name="BookingRoute[brt_pickup_date_time]"  value="<?= $brtModel->brt_pickup_date_time ?>" >

								</div> 
								<div class="col-xs-12 col-sm-6 col-md-4    " >
									<div class="input-group col-xs-12">

										<label   id='trslabel'>Going From</label><br>
										<select class="form-control inputSource " name="BookingRoute[brt_from_city_id]"
												placeholder="Pickup City"
												id="brt_from_city_id_shuttle" onchange="populateDropCity('<?= $brtModel->brt_to_city_id ?>')">
										</select>

										<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>
									</div>
								</div>

								<div class="col-xs-12 col-sm-6 col-md-4    ">
									<div class="input-group col-xs-12">
										<label   id='trdlabel'>Going To</label><br>
										<select class="form-control destSource " name="BookingRoute[brt_to_city_id]"  
												id="brt_to_city_id_shuttle"  >
										</select>
									</div>
								</div>
								<div class="col-xs-2 col-md-1 pb20 text-center">
									<input type="submit" class="btn btn-primary proceed-new-btn" value="PROCEED">
								</div>
							</div><?php $this->endWidget(); ?>

                        </div>

						<!-------Daily Rental Start--->
                        <div class="tab-pane   home-search mt10 mb5 " id="menu10">

							<?php
							$form1				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
								'id'					 => 'bookingRentalform',
								'enableClientValidation' => true,
								'clientOptions'			 => array(
									'validateOnSubmit'	 => true,
									'errorCssClass'		 => 'has-error',
									'afterValidate'		 => 'js:function(form, data, hasError){
										 if(!hasError){
                                                                                    var success = false;
                                                                                        $.ajax({
                                                                                            "type":"POST",
                                                                                            "async":false,
                                                                                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateDayRentalSearch')) . '",
                                                                                            "data":form.serialize(),
                                                                                                "dataType": "json",
                                                                                                "success":function(data1){
                                                                                                    if(data1.success)
                                                                                                    {
                                                                                                        success = true;
                                                                                                    }
                                                                                                    else{
                                                                                                        var errors = data1.errors;
                                                                                                        var content = "";
                                                                                                        for(var key in errors){
                                                                                                            $.each(errors[key], function (j, message) {
                                                                                                                content = content + message + \'\n\';
                                                                                                            });
                                                                                                        }
                                                                                                        alert(content);
                                                                                                    }
                                                                                                },
                                                                                            });
                                                                                return success;
                                                                            }
                                                                        }'
								),
								'enableAjaxValidation'	 => false,
								'errorMessageCssClass'	 => 'help-block',
								'action'				 => Yii::app()->createUrl('booking/booknow'),
								'htmlOptions'			 => array(
									'class' => 'form-horizontal',
								),
							));
							/* @var $form TbActiveForm */

							$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
								'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
								'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
								'openOnFocus'		 => true, 'preload'			 => false,
								'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
								'addPrecedence'		 => false,];
							?>
							<div class="row">
								<div class="col-xs-3 mb20">Going From

									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $brtModel,
										'attribute'			 => 'brt_from_city_id',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width'	 => '50%', 'id'	 => 'bkg_from_city_id',
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
													populateRentalSource(this, '{$brtModel->brt_from_city_id}');
													$('.selectize-control INPUT').attr('autocomplete','new-password');
												}",
									'render'		 => "js:{
												option: function(item, escape){                      
												return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
												},
												option_create: function(data, escape){
												return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
													}
												}",
										),
									));
									echo $form->hiddenField($brtModel, "brt_from_location", ['id' => 'Onelocation0']);
									echo $form->hiddenField($brtModel, "brt_from_latitude", ['id' => 'OnelocLat0']);
									echo $form->hiddenField($brtModel, "brt_from_longitude", ['id' => 'OnelocLon0']);
									echo $form->hiddenField($brtModel, "brt_from_formatted_address", ['id' => 'OnelocFAdd0']);
									echo $form->hiddenField($brtModel, 'brt_from_is_airport', ['id' => 'OneisAirport0']);
									echo $form->hiddenField($brtModel, 'brt_to_city_id', ['id' => 'bkg_to_city_id']);
									?>
									<input  id="bkg_booking_type_rental" name="BookingTemp[bkg_booking_type]" type="hidden">
									<input  id="bktyp_rental" name="BookingTemp[bktyp]" type="hidden">
									<input type="hidden" id="step11" name="step" value="1">
									<span class="has-error"><?php //echo $form->error($brtModel, 'brt_from_city_id');                               ?></span>
								</div>
								<div class="col-xs-3">
									<div class="col-xs-12 pr0 mr0"><label>Rental Type</label>
										<?php
										$rentalTypeArr		 = Booking::model()->rental_types;
										$this->widget('booster.widgets.TbSelect2', array
											(
											'model'			 => $model,
											'attribute'		 => "bkg_booking_type",
											'val'			 => $rentalTypeArr,
											'asDropDownList' => true,
											'data'			 => $rentalTypeArr,
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Rental Types', 'id' => 'BookingTemp_bkg_booking_type_rental')
										));
										?>
									</div>
								</div>
								<div class="col-xs-2">
									<label>Journey date</label>
									<div class="input-group col-xs-12">
										<?php
										$defaultDate		 = date('Y-m-d H:i:s', strtotime('+2 days'));
										$defaultRDate		 = date('Y-m-d H:i:s', strtotime('+3 days'));
										$minDate			 = date('Y-m-d H:i:s ', strtotime('+4 hour'));
										$pdate				 = ($brtModel->brt_pickup_date_date == '') ? DateTimeFormat::DateTimeToDatePicker($defaultDate) : $brtModel->brt_pickup_date_date;
										?>
										<?=
										$form->datePickerGroup($brtModel, 'brt_pickup_date_date', array('label'			 => '',
											'widgetOptions'	 => array('options'		 => array('autoclose'	 => true, 'startDate'	 => $minDate,
													'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Pickup Date',
													'value'			 => $pdate, 'id'			 => 'Booking_bkg_pickup_date_date_rental',
													'class'			 => 'form-control border-radius')),
											'groupOptions'	 => ['class' => 'm0'],
											'prepend'		 => '<i class = "fa fa-calendar"></i>'));
										?>

									</div>

									<input type='hidden' id="<?= 'brt_pickup_date_time_' . date('mdhis') ?>" name="BookingRoute[brt_pickup_date_time]"  value="<?= $brtModel->brt_pickup_date_time ?>" >

								</div>

								<div class="col-xs-2">
									<label>Journey Time</label>
									<div class="input-group timer-control">
										<?php
										$this->widget('ext.timepicker.TimePicker', array(
											'model'			 => $brtModel,
											'id'			 => 'brt_pickup_date_time_rental' . date('mdhis'),
											'attribute'		 => 'brt_pickup_date_time',
											'options'		 => ['widgetOptions' => array('options' => array()), 'startTime' => '00:00', 'dynamic' => false],
											'htmlOptions'	 => array('required' => true, 'placeholder' => 'Pickup Time', 'class' => 'form-control border-radius')
										));
										?>
									</div>
								</div>
								<span class="has-error"><?php echo $form->error($brtModel, ' brt_to_city_id'); ?></span>
								<span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_date'); ?></span>
								<span class="has-error"><?php echo $form->error($brtModel, ' brt_pickup_date_time'); ?></span>

								<div class="col-xs-2 pt5">
									<button type="button" class="btn btn-primary proceed-new-btn" id="dayrentalbtn">proceed</button>
								</div>

								<?php $this->endWidget(); ?>
							</div>
                        </div>
                        <!-------Daily Rental End----->
						<?php
						Logger::create("Form Render Completed: " . Filter::getExecutionTime());
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row m0">
    <div class="col-xs-12 hidden-lg hidden-md hidden-sm text-center p0 mt50 pt40 mob-out-banner">
		<div class="event-fest2">
			<span style="color:#000; font-weight: 500;">OFFICIAL TRAVEL PARTNER</span><br>
			<a href="/e/kumbh" style="text-decoration: none;font-size: 1.2em"><img data-src="/images/partners-logo3.png?v=1.1" class="lozad" alt="International"></a>
			<a href="/e/kumbh" style="text-decoration: none;font-size: 1.2em"><img data-src="/images/kumbh-logo.png?v=1.1" class="lozad" alt="Sula Fest" title="Sula Fest"></a>
			<a href="/e/sulafest" style="text-decoration: none;font-size: 1.2em"><img data-src="/images/partners-logo5.png?v=1.1" class="lozad" alt="International"></a>
		</div>
    </div>
</div>
<div class="row m0">
    <div class="col-xs-12 hidden-lg hidden-md hidden-sm text-center mt20 pb20 mob-app-img">
        <b class="m0 mt10">Book with Gozo cabs mobile app</b>
        <div class="mt10"><figure><a href="https://play.google.com/store/apps/details?id=com.gozocabs.client" target="_blank"><img class="lozad" data-src="/images/GooglePlay.png?v=1.1" alt="Gozocabs App - PlayStore"></a> 
				<a href="https://itunes.apple.com/app/id1398759012?mt=8" target="_blank"><img class="lozad" data-src="/images/app_store.png?v1.2" alt="Gozocabs APP"></a></figure></div>
    </div>
</div>
<script>

    $("#brt_location1").blur(function () {
        if ($("#locLat0").val() == "")
        {
            alert("Please select proper source address");
        }
    });
    $("#brt_location0").focus(function () {
        //if($("#locLat0").val()=="")
        //{
        $('#brt_location1').val('');
        //}
    });
    $fromCity = '<?= $datacity ?>';
    var toCity = [];
    var toCity1 = [];
    var toCity2 = [];
    var toCity4 = [];
    var airportList = [];
    var trlocList = [];

    $destCity = null;
    $(function ()
    {
        $(window).on("scroll", function ()
        {
            if ($(window).scrollTop() > 50)
            {
                $(".top-menu").addClass("white-header");
            } else
            {
                $(".top-menu").removeClass("white-header");
            }
        });
    });
    $(document).ready(function ()
    {
        $("#bkg_pickup_date_time1").selectize();
        $("#bkg_pickup_date_time2").selectize();
        $("#bkg_pickup_date_time3").selectize();
        $("#bkg_pickup_date_time4").selectize();
        $("#bkg_pickup_date_time5").selectize();
        //        populateData();
        //        populateDataR();
        //        populateDataM();
        if (window.location.hash == '#airport-transfer')
        {
            $('.otrip').removeClass('active');
            $('.home-search').removeClass('active');
            $('.home-search1').removeClass('active');
            $('#ttrip').addClass('active');
            $('#menu7').addClass('active');
        }

<?php
if (strtoupper($tripType) == 'DAY-RENTAL')
{
	?>
	        $('.drtrip a').click();
<?php } ?>

<?php
if (strtoupper($tripType) == 'SHUTTLE')
{
	?>
	        $('.strip a').click();

	<?php
}
if (strtoupper($tripType) == 'AIRPORT-TRANSFERS')
{
	?>
	        $('.ttrip a').click();
<?php } ?>
        populateShuttleSource('<?= $brtModel->brt_from_city_id ?>');
    });
    $sourceList = null;


    function loadSource(query, callback)
    {

        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }
    function loadAirportSource(query, callback)
    {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }
    function loadTime(query, callback)
    {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/timedrop')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }

    function populateSource(obj, cityId)
    {
        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>',
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue('<?= $model->bkg_from_city_id ?>');
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue('<?= $model->bkg_from_city_id ?>');
            }
        });
    }

    function populatePackage(obj, pckid)
    {
        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/package')) ?>',
                    dataType: 'json',
                    data: {
                        pckid: pckid
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue('<?= $model->bkg_package_id ?>');
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue('<?= $model->bkg_package_id ?>');
            }
        });
    }
    function loadPackage(query, callback)
    {

        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/package')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }

    function populateAirportList(obj, cityId)
    {
        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>',
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue('<?= $model->bkgAirport ?>');
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue('<?= $model->bkgAirport ?>');
            }
        });
    }
    function changeDestination(value, obj)
    {
        if (!value.length)
            return;
        var existingValue = obj.getValue();
        if (existingValue == '')
        {
            existingValue = '<?= $model->bkg_to_city_id ?>';
        }
        obj.disable();
        obj.clearOptions();
        obj.load(function (callback)
        {
            //  xhr && xhr.abort();
            xhr = $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
                dataType: 'json',
                success: function (results)
                {
                    obj.enable();
                    callback(results);
                    obj.setValue(existingValue);
                },
                error: function ()
                {
                    callback();
                }
            });
        });
    }


    $('#bookingtimform1').submit(function (event)
    {

        fcity = $('#Booking_bkg_from_city_id').val();
        tcity = $('#Booking_bkg_to_city_id').val();
        // alert(tcity);
    });

    $('#rtrip').click(function ()
    {
        $('#bkt #bkg_booking_type2').val(2);
        $('#bkt #bktyp2').val(2);
    });
    $('#mtrip').click(function ()
    {
        $('#bkt #bkg_booking_type3').val(3);
        $('#bkt #bktyp3').val('3');
    });
    $('#ptrip').click(function ()
    {
        $('#bkt #bkg_booking_type5').val(5);
        $('#bkt #bktyp5').val('5');
    });

    $('#BookingTemp_bkg_booking_type_rental').change(function () {
        $('#bkg_booking_type_rental').val($('#BookingTemp_bkg_booking_type_rental').val());
        $('#bktyp_rental').val($('#BookingTemp_bkg_booking_type_rental').val());
    });

    function viewList(obj)
    {
        var href2 = $(obj).attr("href");

        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details',
                    size: 'large',
                    onEscape: function ()
                    {
                        // user pressed escape
                    },
                });
            }
        });
        return false;
    }

    $sourceList22 = null;
    function populateSourceCityPackage(obj, cityId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList22 == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList22 = results;
                        obj.enable();
                        callback($sourceList22);
                        obj.setValue(cityId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList22);
                obj.setValue(cityId);
            }
        });
    }
    function loadSourceCityPackage(query, callback)
    {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }

    $('#brt_pickup_date_date_shuttle').change(function () {
        $('.destSource').val('');
        populateShuttleSource();

    });

    function populateShuttleSource(fromCityId) {
        dateVal = $('#brt_pickup_date_date_shuttle').val();

        $('.destSource').html('');

        $.ajax({
            "type": "POST",
            dataType: 'json',
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getpickupcitylist')) ?>",
            data: {
                'dateVal': dateVal
            },
            "async": false,
            "success": function (data1)
            {
                $('.inputSource').html('');
                $('.inputSource').children('option').remove();
                $(".inputSource").append('<option value="">Select City</option>');
                $.each(data1, function (key, value) {
                    $('.inputSource').append($("<option></option>").attr("value", key).text(value));
                });
                if (fromCityId > 0)
                {
                    $('.inputSource').val(fromCityId).change();
                }
            }
        });
    }

    function populateRentalSource(obj, cityId) {
        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/dayrentalcitylist')) ?>',
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue('<?= $model->bkg_from_city_id ?>');
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue('<?= $model->bkg_from_city_id ?>');
            }
        });
    }

    function populateDropCity(toCityId) {

        dateVal = $('#brt_pickup_date_date_shuttle').val();
        fcityVal = $('.inputSource').val();

        $.ajax({
            "type": "POST",
            dataType: 'json',
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('shuttle/getdropcitylist')) ?>",
            data: {
                'dateVal': dateVal, 'fcityVal': fcityVal
            },
            "async": false,
            "success": function (data1)
            {
                $('.destSource').html('');
                $('.destSource').children('option').remove();
                $(".destSource").append('<option value="">Select City</option>');
                $.each(data1, function (key, value) {
                    $('.destSource').append($("<option></option>").attr("value", key).text(value));
                });
                if (toCityId > 0)
                {
                    $('.destSource').val(toCityId).change();
                }
            }
        });
    }

    $('#dayrentalbtn').click(function () {
        $.ajax({
            "type": "GET",
            "async": false,
            "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateDayRental')) ?>',
            "data": {'fromCityId': $('#bkg_from_city_id').val(), 'bkType': $('#bkg_booking_type_rental').val()},
            "dataType": "json",
            "success": function (data1)
            {
                if (data1.success == true)
                {
                    $('#bkg_booking_type_rental').val(data1.bkType);
                    $('#bktyp_rental').val(data1.bkType);

                    $('#OnelocLat0').val(data1.from.cty_lat);
                    $('#OnelocLon0').val(data1.from.cty_long);
                    $('#OnelocFAdd0').val(data1.from.cty_garage_address);
                    $('#Onelocation0').val(data1.from.cty_garage_address);
                    $('#OneisAirport0').val(data1.from.cty_is_airport);

                    $('#bookingRentalform').submit();
                } else
                {
                    $('#bkg_booking_type_rental').val(data1.bkType);
                    $('#bktyp_rental').val(data1.bkType);

                    $('#OnelocLat0').val('');
                    $('#OnelocLon0').val('');
                    $('#OnelocFAdd0').val('');
                    $('#Onelocation0').val('');
                    $('#OneisAirport0').val('');

                    if (($("#BookingTemp_bkg_booking_type_rental").val() == '' || $("#BookingTemp_bkg_booking_type_rental").val() == null || $("#BookingTemp_bkg_booking_type_rental").val() == undefined) || ($("#bkg_from_city_id").val() == '' || $("#bkg_from_city_id").val() == null || $("#bkg_from_city_id").val() == undefined)) {
                        var content = "You Should Enter City/Rental Trip Type.";
                        if (data1.errorMsg != '' || data1.errorMsg != undefined)
                        {
                            content = data1.errorMsg;
                        }
                        alert(content);
                    }
                }
            }
        });
    });


    $('#onewaybtn').click(function () {
        $.ajax({
            "type": "GET",
            "async": false,
            "url": '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/validateOneway')) ?>',
            "data": {'fromCityId': $('#BookingRoute_brt_from_city_id').val(), 'toCityId': $('#BookingRoute_brt_to_city_id').val()},
            "dataType": "json",
            "success": function (data1)
            {
                if (data1.success == true)
                {
                    $('#bkg_booking_type1').val(data1.bkType);
                    $('#bkg_transfer_type1').val(data1.transferType);
                    $('#OnelocLat0').val(data1.from.cty_lat);
                    $('#OnelocLon0').val(data1.from.cty_long);
                    $('#OnelocFAdd0').val(data1.from.cty_garage_address);
                    $('#Onelocation0').val(data1.from.cty_garage_address);
                    $('#OneisAirport0').val(data1.from.cty_is_airport);

                    $('#OnelocLat1').val(data1.to.cty_lat);
                    $('#OnelocLon1').val(data1.to.cty_long);
                    $('#OnelocFAdd1').val(data1.to.cty_garage_address);
                    $('#Onelocation1').val(data1.to.cty_garage_address);
                    $('#OneisAirport1').val(data1.to.cty_is_airport);
                } else
                {
                    $('#bkg_booking_type1').val(1);
                    $('#bkg_transfer_type1').val(0);
                    $('#OnelocLat0').val('');
                    $('#OnelocLon0').val('');
                    $('#OnelocFAdd0').val('');
                    $('#Onelocation0').val('');
                    $('#OneisAirport0').val('');

                    $('#OnelocLat1').val('');
                    $('#OnelocLon1').val('');
                    $('#OnelocFAdd1').val('');
                    $('#Onelocation1').val('');
                    $('#OneisAirport1').val('');
                }
                $('#bookingSform').submit();
            }

        });
    });
    $('.helpline').click(function () {
        openhelpline();
    });
    function openhelpline() {

        var href2 = "<?= Yii::app()->createUrl('scq/helpline') ?>";
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                bootbox.dialog({
                    message: data,

                    size: 'small',
                    className: "smallwidth",

                });
            }
        });
        return false;

    }
</script>
<?php
$script = "$(document).ready(function(){
	$('input[name=YII_CSRF_TOKEN]').val('" . $this->renderDynamicDelay('Filter::getToken') . "');
});";
Yii::app()->clientScript->registerScript('updateYiiCSRF', $script, CClientScript::POS_END);
?>