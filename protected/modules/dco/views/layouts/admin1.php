<?php
Yii::app()->clientScript->registerPackage('style');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="WRP">
        <meta name="author" content="aaocab">

        <link rel="icon" type="image/png"  href="/images/favicon/favicon1.ico"/>
        <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.ico"/>
        <link href='https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'/>	
        <link href="/assets/css/admin.css" rel="stylesheet" type="text/css"/>
        <script src="/assets/js/admin.js"></script>
        <script src="/assets/plugins/jquery-counterup/jquery.counterup.min.js"></script>
        <link href="/assets/toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
        <script src="/assets/toastr/toastr.min.js"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
        <!--[if lt IE 9]>
            <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- <?= print_r($_SERVER) ?> -->
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/assets/plugins/form-toggle/toggles.css' />
        <script type="text/javascript">
            var $baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
            $adminUrl = "<?= Yii::app()->createAbsoluteUrl('admin') ?>";
            function ajaxindicatorstart(text)
            {
                if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading') {
                    jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif"><div>' + text + '</div></div><div class="bg"></div></div>');
                }

                jQuery('#resultLoading').css({
                    'width': '100%',
                    'height': '100%',
                    'position': 'fixed',
                    'z-index': '10000000',
                    'top': '0',
                    'left': '0',
                    'right': '0',
                    'bottom': '0',
                    'margin': 'auto'
                });

                jQuery('#resultLoading .bg').css({
                    'background': '#ddd',
                    'opacity': '0.6',
                    'width': '100%',
                    'height': '100%',
                    'position': 'absolute',
                    'top': '0'
                });

                jQuery('#resultLoading>div:first').css({
                    'width': '250px',
                    'height': '75px',
                    'text-align': 'center',
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'right': '0',
                    'bottom': '0',
                    'margin': 'auto',
                    'font-size': '16px',
                    'z-index': '10',
                    'color': '#111'

                });

                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeIn(100);
                jQuery('body').css('cursor', 'wait');
            }

            function ajaxindicatorstop()
            {
                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeOut(100);
                jQuery('body').css('cursor', 'default');
            }


            jQuery(document).ajaxStart(function () {
                //show ajax indicator
                ajaxindicatorstart('loading data.. please wait..');
            }).ajaxStop(function () {
                //hide ajax indicator
                ajaxindicatorstop();
            });
            jQuery(window).load(function () {
                // will first fade out the loading animation
                jQuery("#status").fadeOut();
                // will fade out the whole DIV that covers the website.
                jQuery("#preloader").delay(100).fadeOut("slow");
            })


            $(document).ready(function ()
            {
                /*
                 timeOutJob = setInterval(function ()
                 {
                 $.ajax({
                 type: "POST",
                 dataType: "json",
                 async: true,
                 global: false,
                 url: "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/callmecustomers')) ?>",
                 success: function (data)
                 {
                 $.each( data, function( index, value ){
                 var total = "New call request"+"<br>";
                 var logphone = value['bkg_log_phone'];
                 if( logphone != null ){
                 total += " LogPhone : "+logphone+"<br>";
                 }
                 var phone = value['bkg_contact_no'];
                 if( phone != null ){
                 total += " Phone : "+phone+"<br>";
                 }
                 var logemail = value['bkg_log_email'];
                 if( logemail != null ){
                 total += " LogEmail : "+logemail+"<br>";
                 }
                 var email = value['bkg_user_email'];
                 if( email != null ){
                 total += " Email : "+email+"<br>";
                 }
                 var username = value['bkg_user_name']+" "+value['bkg_user_lname'];
                 if( value['bkg_user_name'] != null || value['bkg_user_lname']){
                 total += " Username : "+username;
                 }
                 toastr.info(total);       
                 });                           
                 },
                 });
                 
                 }, 60000);
                 */

            });

            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": function () {
                    window.location.href = '<?= Yii::app()->createUrl("admin/lead/report"); ?>';
                },
                "timeOut": "0",
                "extendedTimeOut": "0",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "tapToDismiss": true,
                "hideMethod": "fadeOut"
            }
        </script>
        <style>

            .toolbar.navbar-nav {
                margin-top: 5px
            }
            header.navbar {
                height: 50px;
            }
            footer{
                height: 32px;
                bottom:0;
                position: fixed;
            }
            #preloader  {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #fefefe;
                z-index: 99;
                height: 100%;
            }

            #status  {
                width: 200px;
                height: 200px;
                position: absolute;
                left: 50%;
                top: 50%;
                background-image: url(/images/ajax-loader.gif);
                background-repeat: no-repeat;
                background-position: center;
                margin: -100px 0 0 -100px;
            }
        </style>
    </head>

    <body  class="page-header-fixed compact-menu page-horizontal-bar  page-sidebar-fixed">
        <div class="overlay"></div>
        <div id="preloader">
            <div id="status">&nbsp;</div>
        </div>
        <!-- BEGIN HEADER -->
        <main class="page-content content-wrap">
            <!-- BEGIN TOP NAVIGATION BAR -->
            <div class="navbar">
                <div class="navbar-inner container">
                    <div class="sidebar-pusher">
                        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic push-sidebar">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <div class="logo-box">

                        <a  href="<?= Yii::app()->createUrl('admin/index/dashboard') ?>" 
                            style="font: inherit !important;  padding:15px">

                            <img style="width: 140px;margin-top: 5px" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gozo-logo.png" />
                        </a>
                    </div><!-- Logo Box -->
                    <div class="search-button">
                        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i class="fa fa-search"></i></a>
                    </div>
                    <div class="topmenu-outer">
                        <div class="top-menu">
                            <ul class="nav navbar-nav navbar-left">

                            </ul>
                        </div><!-- Top Menu -->
                    </div>
                </div>
            </div>
            <!-- END LOGO -->
            <div class="page-sidebar sidebar horizontal-bar">
                <div class="page-sidebar-inner">
                    <ul class="menu accordion-menu">
                        <li class="nav-heading"><span>Navigation</span></li>

						<?php
						if (!Yii::app()->user->isGuest)
						{
							?>

							<li class="droplink">
								<a href="#"><span class="menu-icon fa fa-car"></span><p class="">Booking</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/booking/create') ?>" >
											<div class="center-block">New Booking</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/booking/list') ?>" >
											<div class="center-block">Booking History</div></a>
									</li>
								</ul>
							</li>
							<li class="">
								<a href="<?= Yii::app()->createUrl('admin/user/list') ?>"><span class="menu-icon fa fa-user"></span><p class="">Users</p></a>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-cab"></span><p class="">Vehicle</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vehicle/add') ?>" >
											<div class="center-block">Add new vehicle</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vehicle/list') ?>" >
											<div class="center-block">View list</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vehicle/addtype') ?>" >
											<div class="center-block">Add new vehicle type</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vehicle/typelist') ?>" >
											<div class="center-block">View model list</div></a>
									</li>
								</ul>
							</li>

							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-wheelchair"></span><p class="">Drivers</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/driver/add') ?>" >
											<div class="center-block">Add driver</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/driver/list') ?>" >
											<div class="center-block">View list</div></a>
									</li>
								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-circle"></span><p class="">Cities</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/city/add') ?>" >
											<div class="center-block">Add City</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/city/list') ?>" >
											<div class="center-block">View list</div></a>
									</li>
								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-map-marker"></span><p class="">Routes</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/route/add') ?>" >
											<div class="center-block">Add Route</div></a>
									</li>
									<li class="hide">
										<a href="<?= Yii::app()->createUrl('admin/route/addreturn') ?>" >
											<div class="center-block">Add return route</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/route/list') ?>" >
											<div class="center-block">View list</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/rate/add') ?>" >
											<div class="center-block">Add route rate</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/rate/list') ?>" >
											<div class="center-block">View rate list</div></a>
									</li>
								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-envelope"></span><p class="">Messages</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/message/list') ?>" >
											<div class="center-block">Sms log</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/email/list') ?>" >
											<div class="center-block">Email log</div></a>
									</li>
								</ul>
							</li>                            
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-briefcase"></span><p class="">Vendors</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/add') ?>" >
											<div class="center-block">Add new Vendor</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/list') ?>" >
											<div class="center-block">Vendors list</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/joining') ?>" >
											<div class="center-block">Vendors Joining list</div></a>
									</li>
								</ul>
							</li>
							<li class="droplink">
								<a href="#"><span class="menu-icon fa fa-search-plus"></span><p class="">Leads</p><span class="arrow"></span></a>            
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/lead/leadfollow') ?>" >
											<div class="center-block">Create Lead</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/lead/report') ?>" >
											<div class="center-block">Lead Report</div></a>
									</li>

								</ul>
							</li>
							<li class="droplink">
								<a href="#"><span class="menu-icon fa fa-pie-chart"></span><p class="">Reports</p><span class="arrow"></span></a>            
								<ul class="sub-menu">

									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/booking') ?>" >
											<div class="center-block">Booking Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/pickup') ?>" >
											<div class="center-block">Pickup Report</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/vendorweekly') ?>" >
											<div class="center-block">Vendor Weekly Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/weekly') ?>" >
											<div class="center-block">Weekly Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/daily') ?>" >
											<div class="center-block">Daily Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/runningtotal') ?>" >
											<div class="center-block">Running Total Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/cabdetails') ?>" >
											<div class="center-block">Cab Details Report</div></a>
									</li>
								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-diamond"></span><p class="">Promotions</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/promo/add') ?>" >
											<div class="center-block">Add new Promotion</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/promo/list') ?>" >
											<div class="center-block">Promo List</div></a>
									</li>
								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-bar-chart"></span><p class="">Ratings</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/rating/list') ?>" >
											<div class="center-block">Rating List</div></a>
									</li>
								</ul>
							</li>


							<?php
						}
						?>


                    </ul>
                </div>
            </div>

            <div class="page-inner" >
                <div class="page-title">
                    <h3><?= $this->pageTitle ?></h3>
                </div>
				<?php
				if (!Yii::app()->user->isGuest)
				{
					if ($this->id == "index" && $this->action->id == "dashboard")
					{
						$dasboard = "active";
					}
					else if ($this->id == "staff")
					{
						$staff = "active";
					}
					elseif ($this->id == "student" && $this->action->id == "attendance")
					{
						$student_attendance = "active";
					}
					else if ($this->id == "student")
					{
						$student = "active";
					}
					else if ($this->id == "schedule")
					{
						$schedule = "active";
					}
					else if ($this->id == "message")
					{
						$message = "active";
					}
					else if ($this->id == "report")
					{
						$report = "active";
					}
					else
					{
						$biz = "active";
					}
					?>

					<? } ?>
					<div id="main-wrapper" class="container-fluid   ">
						<!-- BEGIN PAGE CONTAINER-->

						<?php if (isset($this->breadcrumbs)): ?>
							<?php
							$this->widget('zii.widgets.CBreadcrumbs', array(
								'links' => $this->breadcrumbs,
							));
							?><!-- breadcrumbs -->
						<?php endif ?>

						<div class="" >

							<?php echo $content; ?>
						</div>


					</div>
					<div class="page-footer">
						<div class="container" >

							<p>
								Copyright &copy; <?php echo date('Y'); ?> by aaocab.
								All Rights Reserved.
							</p>
						</div>
					</div><!-- footer --></div>

				<!-- page -->
        </main>
    </body>
</html>
