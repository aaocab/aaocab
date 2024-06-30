<?php
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<?php
/* @var $this Controller */

Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/enquire.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.cookie.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.nicescroll.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/moment.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/daterangepicker.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerPackage('style');
$adminModel						 = Admins::model()->findByPk(Yii::app()->user->getId());
$site_js_version				 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/maskFilter.js?v=' . $site_js_version, CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/followUp.js?v=' . $site_js_version);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="aaocab">
		<meta name="robots" content="noindex,nofollow"/>
        <link rel="icon" type="image/png"  href="/images/favicon/favicon1.ico"/>
        <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.ico"/>
        <link href='<?= (Yii::app()->request->getIsSecureConnection() ? "https" : "http") ?>://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'/>
        <link href="<?= ASSETS_URL ?>/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?= ASSETS_URL ?>/css/custom.css?v=4.5" rel="stylesheet" type="text/css"/>
        <link href= "<?= ASSETS_URL ?>/css/admin.css" rel="stylesheet" type="text/css"/>
        <link href="<?= ASSETS_URL ?>/css/daterangepicker.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="/assets/fontawesome-web/css/all.css">
		<style>
			.ui-autocomplete{
				width: 220px;
				z-index: 9999;
				color:#5f5f5f;
				background: #fff;
				border: #ebebeb 1px solid;
				padding-left: 0;
			}
			.ui-autocomplete li a{
				color:#5f5f5f!important;
				padding: 10px 15px;
				display: block;
				cursor: pointer;
				text-decoration: none;
				border-bottom: 1px #ededed solid;
			}
			.ui-autocomplete li a:hover{
				color: #22BAA0!important;
			}
			.ui-autocomplete .ui-menu-item{
				color:#5f5f5f;
			}
			.searchbar{
				position:relative;
				min-width:50px;
				width:0%;
				margin-top: 10px;
				height:40px;
				float:right;
				overflow:hidden;
				-webkit-transition: width 0.3s;
				-moz-transition: width 0.3s;
				-ms-transition: width 0.3s;
				-o-transition: width 0.3s;
				transition: width 0.3s;
			}
			.searchbar-input{
				top: 0;
				right: 0;
				border: 0;
				outline: 0;
				background: #ebebeb;
				width: 100%;
				height: 40px;
				margin: 0;
				padding: 0px 55px 0px 20px;
				font-size: 16px;
				color: #5f5f5f;
			}
			.searchbar-input::-webkit-input-placeholder {
				color: #b0b0b0;
			}
			.searchbar-input:-moz-placeholder {
				color: #fff;
			}
			.searchbar-input::-moz-placeholder {
				color: #fff;
			}
			.searchbar-input:-ms-input-placeholder {
				color: #fff;
			}
			.searchbar-icon,
			.searchbar-submit{
				width:50px;
				height:40px;
				display:block;
				position:absolute;
				top:0;
				font-size:13px;
				right:0;
				padding:0;
				margin:0;
				border:0;
				outline:0;
				line-height:40px;
				text-align:center;
				cursor:pointer;
				color:#fff;
				background:#7a6fbe;
				border-left: 1px solid white;
			}
			.searchbar-open{
				width:100%;
			}

			.ui-menu-item .ui-menu-item-wrapper.ui-state-active {
				background: #efefef !important;
				color: #ffffff !important;
			}

		</style>
        <script src="<?= ASSETS_URL ?>/js/admin.js"></script>
        <script src="<?= ASSETS_URL ?>/plugins/jquery-counterup/jquery.counterup.min.js"></script>
        <link href="<?= ASSETS_URL ?>/toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
        <script src="<?= ASSETS_URL ?>/toastr/toastr.min.js"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
        <!--[if lt IE 9]>
            <script type="text/javascript" src="<?= (Yii::app()->request->getIsSecureConnection() ? "https" : "http") ?>://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- <? if (YII_DEBUG) print_r($_SERVER); ?> -->
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?><?= ASSETS_URL ?>/plugins/form-toggle/toggles.css' />
        <script type="text/javascript">
			var $baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
			var $adminUrl = "<?= Yii::app()->createAbsoluteUrl('admin') ?>";



			$(function()
			{
				getLocation();
			});
			function getLocation()
			{
				if (navigator.geolocation)
				{
					navigator.geolocation.getCurrentPosition(showPosition);
				}
				else
				{
					x.innerHTML = "Geolocation is not supported by this browser.";
				}
			}

			function showPosition(position)
			{
				createCookie("lat_lng", position.coords.latitude + "_" + position.coords.longitude, 365);
			}
			function createCookie(name, value, days)
			{
				var expires;
				if (days)
				{
					var date = new Date();
					date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
					expires = "; expires=" + date.toGMTString();
				}
				else
				{
					expires = "";
				}
				document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
			}

			function ajaxindicatorstart(text)
			{
				if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading')
				{
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


			jQuery(document).ajaxStart(function()
			{
				//show ajax indicator
				ajaxindicatorstart('loading data.. please wait..');
			}).ajaxStop(function()
			{
				//hide ajax indicator
				ajaxindicatorstop();
			});
			jQuery(window).on('load', function()
			{
				// will first fade out the loading animation
				jQuery("#status").fadeOut();
				// will fade out the whole DIV that covers the website.
				jQuery("#preloader").delay(100).fadeOut("slow");
			});

			toastr.options = {
				"closeButton": false,
				"debug": false,
				"newestOnTop": false,
				"progressBar": false,
				"positionClass": "toast-top-right",
				"preventDuplicates": false,
				"onclick": function()
				{
					window.location.href = '<?= Yii::app()->createUrl("admin/lead/report"); ?>';
				},
				"timeOut": "0",
				"extendedTimeOut": "0",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"tapToDismiss": true,
				"hideMethod": "fadeOut"
			};


			$sourceList = null;
			function populateSourceCity(obj, cityId)
			{

				obj.load(function(callback)
				{
					var obj = this;
					if ($sourceList == null)
					{
						xhr = $.ajax({
							url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
							dataType: 'json',
							data: {
								// city: cityId
							},
							//  async: false,
							success: function(results)
							{
								$sourceList = results;
								obj.enable();
								callback($sourceList);
								obj.setValue(cityId);
							},
							error: function()
							{
								callback();
							}
						});
					}
					else
					{
						obj.enable();
						callback($sourceList);
						obj.setValue(cityId);
					}
				});
			}
			function loadSourceCity(query, callback)
			{
				//	if (!query.length) return callback();
				$.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
					type: 'GET',
					dataType: 'json',
					global: false,
					error: function()
					{
						callback();
					},
					success: function(res)
					{
						callback(res);
					}
				});
			}
			$sourceList = null;
			function populateVendor(obj, vndId)
			{
				obj.load(function(callback)
				{
					var obj = this;
					if ($sourceList == null)
					{
						xhr = $.ajax({
							url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery', ['onlyActive' => 0, 'vnd' => ''])) ?>' + vndId,
							dataType: 'json',
							data: {},
							//  async: false,
							success: function(results)
							{
								$sourceList = results;
								obj.enable();
								callback($sourceList);
								obj.setValue(vndId);
							},
							error: function()
							{
								callback();
							}
						});
					}
					else
					{
						obj.enable();
						callback($sourceList);
						obj.setValue(vndId);
					}
				});
			}
			function loadVendor(query, callback)
			{

				//	if (!query.length) return callback();
				$.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery')) ?>?onlyActive=0&q=' + encodeURIComponent(query),
					type: 'GET',
					dataType: 'json',
					global: false,
					error: function()
					{
						callback();
					},
					success: function(res)
					{
						callback(res);
					}
				});
			}
			function populatePartner(obj, agtId)
			{


				obj.load(function(callback)
				{
					var obj = this;
					if ($sourceList == null)
					{
						xhr = $.ajax({
							url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allpartnerbyquery', ['onlyActive' => 1, 'agt' => ''])) ?>' + agtId,
							dataType: 'json',
							type: 'GET',
							data: {},
							//  async: false,
							success: function(results)
							{
								$sourceList = results;
								obj.enable();
								callback($sourceList);
								obj.setValue(agtId);
							},
							error: function()
							{
								callback();
							}
						});
					}
					else
					{
						obj.enable();
						callback($sourceList);
						obj.setValue(agtId);
					}
				});
			}
			function loadPartner(query, callback)
			{
				//	if (!query.length) return callback();
				$.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allpartnerbyquery')) ?>?onlyActive=1&q=' + encodeURIComponent(query),
					type: 'GET',
					dataType: 'json',
					global: false,
					error: function()
					{
						callback();
					},
					success: function(res)
					{
						callback(res);
					}
				});
			}
			function populateRoute(obj, rutId)
			{

				obj.load(function(callback)
				{
					var obj = this;
					if ($sourceList == null)
					{
						xhr = $.ajax({
							url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/routelist')) ?>?rut' + rutId,
							dataType: 'json',
							type: 'GET',
							data: {},
							//  async: false,
							success: function(results)
							{
								$sourceList = results;
								obj.enable();
								callback($sourceList);
								obj.setValue(rutId);
							},
							error: function()
							{
								callback();
							}
						});
					}
					else
					{
						obj.enable();
						callback($sourceList);
						obj.setValue(rutId);
					}
				});
			}
			function loadRoute(query, callback)
			{
				//	if (!query.length) return callback();
				$.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/routelist')) ?>?q=' + encodeURIComponent(query),
					type: 'GET',
					dataType: 'json',
					global: false,
					error: function()
					{
						callback();
					},
					success: function(res)
					{
						callback(res);
					}
				});
			}

			function populateZone(obj, zonId)
			{
				obj.load(function(callback)
				{
					var obj = this;
					if ($sourceList == null)
					{
						xhr = $.ajax({
							url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allzonebyquery', ['onlyActive' => 0, 'zonId' => ''])) ?>' + zonId,
							dataType: 'json',
							data: {},
							success: function(results)
							{
								$sourceList = results;
								obj.enable();
								callback($sourceList);
								obj.setValue(zonId);
							},
							error: function()
							{
								callback();
							}
						});
					}
					else
					{
						obj.enable();
						callback($sourceList);
						obj.setValue(zonId);
					}
				});
			}

			function loadZone(query, callback)
			{
				$.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allzonebyquery')) ?>?onlyActive=0&q=' + encodeURIComponent(query),
					type: 'GET',
					dataType: 'json',
					global: false,
					error: function()
					{
						callback();
					},
					success: function(res)
					{
						callback(res);
					}
				});
			}

        </script>
        <style>
            .cityinput > .selectize-control>.selectize-input{
                width:100% !important;
            }
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
            .horizontal-bar .accordion-menu>li>a, .small-sidebar.page-horizontal-bar .horizontal-bar .accordion-menu>li>a {
                padding: 8px 13px!important;
            }
            .horizontal-bar .accordion-menu>li>ul {
                top: 56px;
            }
            .menu.accordion-menu a span.menu-icon {
                margin-bottom: 6px;
            }
            body:not(.small-sidebar) .horizontal-bar .menu.accordion-menu>li>a>.menu-icon {
                font-size: 15px;
            }
            @media (max-width: 1200px){
                .horizontal-bar .accordion-menu ul {
                    top: 35px!important;
                }
            }

            .sub-menu-scroll {
                overflow-y:scroll;
                max-height: 600px;
            }
            .color-white{
                color: #fff;
            }
        </style>
    </head>

    <body class="page-header-fixed page-horizontal-bar  page-sidebar-fixed">
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
                            style="font: inherit !important;  padding:5px">
                            <img style="width: 110px;margin-top: 10px" src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo2_outstation.png?v1.1" />
                        </a>
                    </div><!-- Logo Box -->
                    <div class="search-button">
                        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i class="fa fa-search"></i></a>
                    </div>
                    <div class="topmenu-outer">
                        <div class="top-menu">
                            <ul class="nav navbar-nav navbar-left">
                                <li>
                                    <a href="javascript:void(0);" class="waves-effect waves-button waves-classic sidebar-toggle"><i class="fa fa-bars"></i></a>
                                </li>
                                <li>
                                    <a href="#cd-nav" class="waves-effect waves-button waves-classic cd-nav-trigger"><i class="fa fa-diamond"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="waves-effect waves-button waves-classic toggle-fullscreen"><i class="fa fa-expand"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">
                                        <i class="fa fa-cogs"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-md dropdown-list theme-settings" role="menu">
                                        <li class="li-group">
                                            <ul class="list-unstyled">
                                                <li class="no-link" role="presentation">
                                                    Fixed Header
                                                    <div class="ios-switch pull-right switch-md">
                                                        <input type="checkbox" class="js-switch pull-right fixed-header-check" checked>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="li-group">
                                            <ul class="list-unstyled">
                                                <li class="no-link" role="presentation">
                                                    Fixed Sidebar
                                                    <div class="ios-switch pull-right switch-md">
                                                        <input type="checkbox" class="js-switch pull-right fixed-sidebar-check" checked>
                                                    </div>
                                                </li>
                                                <li class="no-link" role="presentation">
                                                    Toggle Sidebar
                                                    <div class="ios-switch pull-right switch-md">
                                                        <input type="checkbox" class="js-switch pull-right toggle-sidebar-check">
                                                    </div>
                                                </li>
                                                <li class="no-link" role="presentation">
                                                    Compact Menu
                                                    <div class="ios-switch pull-right switch-md">
                                                        <input type="checkbox" class="js-switch pull-right compact-menu-check" checked>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="no-link"><button class="btn btn-default reset-options">Reset Options</button></li>
                                    </ul>
                                </li>
                                <li>
									<form class="searchbar">
										<input id="tags" type="search" placeholder="Search here" name="search" class="searchbar-input" onkeyup="buttonUp();" required>
										<input type="submit" class="searchbar-submit" value="GO">
										<a class="searchbar-icon" accesskey="s"><i class="fa fa-search" aria-hidden="true"></i></a>
									</form>
								</li>
                                <li><a href="#" id="crtfollow" class="pr0">+Service Request</a></li>
								<?php
								$getCountInternalCBRbyTeam		 = ServiceCallQueue::countInternalActiveCBRbyTeam();
								$getCountInternalCBRbyAdminID	 = ServiceCallQueue::countInternalActiveCBRbyAdminID();
								$fromdate						 = date("Y-m-d", strtotime("-1 month"));
								$todate							 = date("Y-m-d");
								?>
                                <li class="notifi">
                                    <span class="notifi-1" title="Followups assigned to my team"><a href="<?php echo Yii::app()->createUrl('admin/generalReport/serviceRequests'); ?>" class="color-white"><?= $getCountInternalCBRbyTeam ?></a></span>
                                    <span  class="notifi-2 blinking" title="Follow ups assigned to me"><a href="<?php echo Yii::app()->createUrl('admin/generalReport/serviceRequestsOwn'); ?>" class="color-white"><?= $getCountInternalCBRbyAdminID ?></a></span>
                                    <span  class="notifi-1" title="My Request"><a  target="_blank"href="/aaohome/generalReport/cbrdetailsreport/?queueType=&event_id=&event_by=&csrId=&teamId=0&isCreated=1&fromdate=<?php echo $fromdate; ?>&todate=<?php echo $todate ?>" class="color-white">My</a></span>
                                </li>
                            </ul>
							<?php
							$mapModel						 = $adminModel->admProfiles;
							$cdtData						 = json_decode($mapModel->adp_cdt_id);
							$cdtId							 = "";
							foreach ($cdtData as $cdt)
							{
								$cdtId .= $cdt->cdtId . ",";
							}
							$cdt_id		 = CatDepartTeamMap::getCatdepatTeamId(rtrim($cdtId, ","));
							$cdtModel	 = CatDepartTeamMap::model()->findByPk($cdt_id);
							$tName		 = $cdtModel->cdtTea->tea_name;
							$deptName	 = $cdtModel->cdtDpt->dpt_name;
							$catName	 = $cdtModel->cdtCat->cat_name;
							$teamName	 = $tName . " (" . $deptName . "/ " . $catName . ")";
							?>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="pt15">
                                    <span class="user-name"><?= $adminModel->adm_dailer_username ?><br />
                                        <input type='text' id='txtdialerNo' placeholder="Enter dailer Ph No." value="<?= Yii::app()->session['dialerNo'] ?>" style="display:none" >
                                        <a id="dailerBox"><?= Yii::app()->session['dialerNo'] ?></a>
                                    </span>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown" aria-expanded="true">
                                        <span class="user-name"><?= $adminModel->adm_fname; ?><i class="fa fa-angle-down"></i><br><?php echo $teamName; ?></span>
<!--                                         <br /><span id="dailerBox"><?= Yii::app()->session['dialerNo'] ?></span>-->
                                    </a>


                                    <ul class="dropdown-menu dropdown-list" role="menu">
                                        <li role="presentation"><a href="<?= Yii::app()->createUrl('admin/index/changepassword') ?>"><i class="fa fa-pencil m-r-xs"></i>Change Password</a></li>
                                        <li role="presentation"id="dialer"><a id="dialer" style="cursor:pointer;"><i class="fa fa-phone m-r-xs"></i>Dialer Phone No.</a></li>
                                        <li role="presentation"><a href="<?= Yii::app()->createUrl('admin/index/logout') ?>"><i class="fa fa-sign-out m-r-xs"></i>Log out</a></li>
                                    </ul>
                                </li>

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
								<a href="#"><span class="menu-icon fa fa-calculator"></span><p class="">Accounts</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/vendoraccounts') ?>" >
											<div class="center-block">Show Vendor Accounts</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/driver/accountlist') ?>?ledgerId=40" >
											<div class="center-block">Show Driver Accounts</div></a>
									</li>
								</ul>
							</li>

							<li class="droplink">
								<a href="#"><span class="menu-icon fa fa-car"></span><p class="">Booking</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/booking/create') ?>" >
											<div class="center-block">Create Booking (New Version)</div></a>
									</li>
									<!--									<li>
																																																	<a href="<? //= Yii::app()->createUrl('admin/booking/createnew')                                                    ?>" >
																																																			<div class="center-block">New Booking (Old Version)</div></a>
																																													</li>-->
									<li>
										<a href="<?= Yii::app()->createUrl('admin/quoteRequest/create') ?>" >
											<div class="center-block">Request New Quote</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/booking/list') ?>" >
											<div class="center-block">Booking History</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/booking/pendinglist') ?>" >
											<div class="center-block">Pending Booking List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/booking/smartMatchList') ?>" >
											<div class="center-block">SmartMatch</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/booking/matchlist') ?>" >
											<div class="center-block">SmartMatch List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/booking/cancellations') ?>" >
											<div class="center-block">Cancellations List</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/cancellations') ?>" >
											<div class="center-block">Cancellations Analysis</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/gnowOffers') ?>" >
											<div class="center-block">Gozo Now Offers Tracking</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/route/demandreport') ?>" >
											<div class="center-block">Zonal Up-Down demand Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vehicle/availabilitylist') ?>" >
											<div class="center-block">Cab Availability List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/package/list') ?>" >
											<div class="center-block">Package</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/shuttle/add') ?>" >
											<div class="center-block">Add Shuttle</div></a>
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
									<li>
										<a href="<?= Yii::app()->createUrl('admin/lead/dailyleadreport') ?>" >
											<div class="center-block">Daily Lead Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/lead/mycall') ?>" >
											<div class="center-block">My Call Lead</div></a>
									</li>

								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-user"></span><p class="">Customers</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/user/list') ?>" >
											<div class="center-block">Customers List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/credit/list') ?>" >
											<div class="center-block">Gozo Coins History</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/unsubscribe/list') ?>" >
											<div class="center-block">Unsubscribe List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/unsubscribe/add') ?>" >
											<div class="center-block">Add Unsubscribe</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/user/sociallist') ?>" >
											<div class="center-block">Social Link</div></a>
									</li>

								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-user"></span><p class="">Channel Partners</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/agent/form') ?>" >
											<div class="center-block">Add Partner</div></a>
									</li>
									<!--                                    <li>
																																																	<a href="<? //= Yii::app()->createUrl('admin/agent/corporateform')                                                         ?>" >
																																																			<div class="center-block">Add new Corporate</div></a>
																																													</li>-->
									<li>
										<a href="<?= Yii::app()->createUrl('admin/agent/list') ?>" >
											<div class="center-block">Manage Partners</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/agent/markupadd') ?>" >
											<div class="center-block">Add Markup</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/agent/markuplist') ?>" >
											<div class="center-block">Markup List</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/agent/regprogress') ?>" >
											<div class="center-block">Registration Progress Report</div></a>
									</li>
								</ul>
							</li>
							<!--                                                        <li class="droplink">
																																							<a href="#" ><span class="menu-icon fa fa-user"></span><p class="">Corporate</p></a>
																																							<ul class="sub-menu">
																																											<li>
																																															<a href="<? //= Yii::app()->createUrl('admin/corporate/add')                                                             ?>" >
																																																			<div class="center-block">Add new corporate</div></a>
																																											</li>
																																											<li>
																																															<a href="<? //= Yii::app()->createUrl('admin/corporate/list')                                                             ?>" >
																																																			<div class="center-block">Corporate List</div></a>
																																											</li>
																																							</ul>
																																			</li>-->
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-briefcase"></span><p class="">Vendors</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/add') ?>" >
											<div class="center-block">Add Vendor</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/list') ?>" >
											<div class="center-block">Vendors List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/listtoapprove') ?>" >
											<div class="center-block">Vendor Pending Approval</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/unregvendorlist') ?>" >
											<div class="center-block">3rd Party Vendors List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/assignment') ?>" >
											<div class="center-block">Assignment Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/regprogress') ?>" >
											<div class="center-block">Registration Progress Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/document/docapprovallist') ?>" >
											<div class="center-block">Document Pending Approval</div>
										</a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/sociallist') ?>" >
											<div class="center-block">Social Link</div></a>
									</li>

									<li>
										<a href="/aaohome/vendor/duplicatevendor">
											<div class="center-block">Duplicate Vendors</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/paymentstosend') ?>" >
											<div class="center-block">Payment to send</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/louList') ?>" >
											<div class="center-block">LOU List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/packagesList') ?>" >
											<div class="center-block">Packages List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/vendor/agreementApprovalList') ?>" >
											<div class="center-block">Agreement Approval</div></a>
									</li>

								</ul>
							</li>

							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-cab"></span><p class="">Cars</p></a>
								<ul class="sub-menu">
									<li class="droplink">
										<a href="#"><p class="">Cars</p><span class="arrow"></span></a>
										<ul class="sub-menu">
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/add') ?>" >
													<div class="center-block">Add Car</div></a>
											</li>
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/list') ?>" >
													<div class="center-block">View List</div></a>
											</li>
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/approvelist') ?>" >
													<div class="center-block">Cars Pending Approval</div></a>
											</li>
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/docapprovallist') ?>" >
													<div class="center-block">Document Pending Approval</div>
												</a>

											</li>
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/carverifydoclist') ?>" >
													<div class="center-block">Car and Boost verification</div>
												</a>
											</li>
										</ul>
									</li>

									<li class="droplink">
										<a href="#"><p class="">Service Class</p><span class="arrow"></span></a>
										<ul class="sub-menu">
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/serviceclasstype') ?>" >
													<div class="center-block">Add New Service Class</div>
												</a>
											</li>
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/serviceclasslist') ?>" >
													<div class="center-block">Service Class List</div>
												</a>
											</li>
										</ul>
									</li>

									<li class="droplink">
										<a href="#"><p class="">Models</p><span class="arrow"></span></a>
										<ul class="sub-menu">
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/addtype') ?>" >
													<div class="center-block">Add New Model</div></a>
											</li>
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/typelist') ?>" >
													<div class="center-block">View Models</div></a>
											</li>

										</ul>
									</li>

									<li class="droplink">
										<a href="#"><p class="">Category</p><span class="arrow"></span></a>
										<ul class="sub-menu">
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/addcategory') ?>" >
													<div class="center-block">Add New Category</div></a>
											</li>
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/categorylist') ?>" >
													<div class="center-block">Category List</div></a>
											</li>

										</ul>
									</li>

									<li class="droplink">
										<a href="#"><p class="">Map</p><span class="arrow"></span></a>
										<ul class="sub-menu">
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/mapcab', array("type" => 1)) ?>" >
													<div class="center-block">Map Cab Models To Category</div>
												</a>

											</li>
											<li>
												<a href="<?= Yii::app()->createUrl('admin/vehicle/mapcab', array("type" => 2)) ?>" >
													<div class="center-block">Map Cab Category to Class/Tier</div>
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>

							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-wheelchair"></span><p class="">Drivers</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/driver/add') ?>" >
											<div class="center-block">Add Driver</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/driver/list') ?>" >
											<div class="center-block">View List</div></a>
									</li>
									<!--									<li>
																																																	<a href="<?= Yii::app()->createUrl('admin/driver/approvelist') ?>" >
																																																			<div class="center-block">Driver Approval List</div></a>
																																													</li>-->

									<li>
										<a href="<?= Yii::app()->createUrl('admin/driver/csrApproveList') ?>" >
											<div class="center-block">Approved By Member</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/driver/docapprovallist') ?>" >
											<div class="center-block">Document Pending Approval</div>
										</a>

									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/driver/sociallist') ?>" >
											<div class="center-block">Social Link</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/driver/duplicateDriver') ?>" >
											<div class="center-block"> Manage Duplicate Driver</div></a>
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
											<div class="center-block">View List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/report/citycoverage') ?>" >
											<div class="center-block">City Coverage Report</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/city/linkapproval') ?>" >
											<div class="center-block">City LINK Approval</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/city/placeapproval') ?>" >
											<div class="center-block">City Place Approval</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/city/destination') ?>" >
											<div class="center-block">City Popular Destinations</div></a>
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
									<li>
										<a href="<?= Yii::app()->createUrl('admin/route/list') ?>" >
											<div class="center-block">Manage Routes and Rates</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/rate/list') ?>" >
											<div class="center-block">View Rates</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/pricesurge/list') ?>" >
											<div class="center-block">Price Surge</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/rate/partneratlist') ?>" >
											<div class="center-block">Manage Partner Airport Rates</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/rate/locallist') ?>" >
											<div class="center-block">Manage Local Transfer Rates</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/rate/dayRentalPrice') ?>" >
											<div class="center-block">Manage Day Rental Rates</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/CalendarEvent/90DCalendar') ?>" >
											<div class="center-block">Upcoming Weekends/Long Weekends/Holidays</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/cache/list') ?>" >
											<div class="center-block">Clear Cache</div></a>
									</li>
								</ul>
							</li>

							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-cogs"></span><p class="">Zone</p></a>
								<ul class="sub-menu">
									<!--									<li>
																													<a href="<?= Yii::app()->createUrl('admin/zone/add') ?>" >
																															<div class="center-block">Add Zone</div></a>
																											</li>-->
									<li>
										<a href="<?= Yii::app()->createUrl('admin/zone/list') ?>" >
											<div class="center-block">View List</div></a>
									</li>

								</ul>
							</li>


							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-envelope"></span><p class="">Messages</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/chat') ?>" >
											<div class="center-block">Messaging</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/message/list') ?>" >
											<div class="center-block">Sms Log</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/email/list') ?>" >
											<div class="center-block">Email Log</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('admin/notification/list') ?>" >
											<div class="center-block">Notification Log</div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('aaohome/broadcastNotification/add') ?>" >
											<div class="center-block">New Notification </div></a>
									</li>

									<li>
										<a href="<?= Yii::app()->createUrl('aaohome/broadcastNotification/list') ?>" >
											<div class="center-block">Scheduled Notifications </div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('report/notification/WhatsappLog') ?>" >
											<div class="center-block">WhatsApp Log</div></a>
									</li>
								</ul>
							</li>


							<li class="droplink">
								<a href="<?= Yii::app()->createUrl('report/index/dashboard') ?>" target="_blank"><span class="menu-icon fa fa-pie-chart"></span><p class="">Reports</p><span class="arrow"></span></a>
								<ul class="sub-menu sub-menu-scroll">
									<!--									<li class="droplink">
																			<a href="javascript:void(0)"><p class="">Confidential Report</p><span class="arrow"></span></a>
																			<ul class="sub-menu">
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/report/zonewise-count') ?>" target="blank">
																						<div class="center-block">Created Zone-wise Count Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/business') ?>" >
																						<div class="center-block">Business</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/businesstrend') ?>" >
																						<div class="center-block">Business Trend</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/sourcezones') ?>" >
																						<div class="center-block">Source Zones</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/destinationzones') ?>" >
																						<div class="center-block">Destination Zones</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/booking') ?>" >
																						<div class="center-block">Bookings</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/pickup') ?>" >
																						<div class="center-block">Pickups</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/money') ?>" >
																						<div class="center-block">Money Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/dailyassignedreport') ?>" >
																						<div class="center-block">Daily Assigned Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/autoAssign') ?>" >
																						<div class="center-block">Auto Assigned Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/daily') ?>" >
																						<div class="center-block">Daily Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/unapprovedcabdriver') ?>" >
																						<div class="center-block">Unapproved Cab/Driver Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/vendorcollection') ?>" >
																						<div class="center-block">Vendor Collection</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/partnercollection') ?>" >
																						<div class="center-block">Partner Collection</div></a>
																				</li>
									
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/bookingPriceFactor/list') ?>" >
																						<div class="center-block">Surge Quoted Situation Report</div>
																					</a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/leadsAndUnverifiedFeedback') ?>" >
																						<div class="center-block">Leads and Unverified Feedback</div></a>
																				</li>
									
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/cancellations') ?>" >
																						<div class="center-block">Cancellations List</div></a>
																				</li>
									
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/dormantvendor') ?>" >
																						<div class="center-block">Dormant Vendors Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/otpserved') ?>" >
																						<div class="center-block">OTP Verification Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/travellers') ?>" >
																						<div class="center-block">Travellers Report</div></a>
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
																					<a href="<?= Yii::app()->createUrl('admin/report/runningtotal') ?>" >
																						<div class="center-block">Running Total Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/cabdetails') ?>" >
																						<div class="center-block">Cab Details Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/npsscore') ?>" >
																						<div class="center-block">NPS Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/financial') ?>" >
																						<div class="center-block">Financial Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/report/partnerPerformance') ?>" >
																						<div class="center-block">Partner Performance</div></a>
																				</li>
																			</ul>
																		</li>-->
									<!--									<li class="droplink">
																			<a href="javascript:void(0)"><p class="">General Reports</p><span class="arrow"></span></a>
																			<ul class="sub-menu">
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/accountingFlagClosedReport') ?>" >
																						<div class="center-block">Accounting Flag Closed Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/vendorLockedPayment') ?>" >
																						<div class="center-block">Vendor Locked Payment</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/driverBonus') ?>" >
																						<div class="center-block">Driver Bonus</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/penaltyReport') ?>" >
																						<div class="center-block">Penalty Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/generalReport/assignmentSummary') ?>" >
																						<div class="center-block">Assignment Summary</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/penaltySummary') ?>" >
																						<div class="center-block">Penalty Summary</div></a>
																				</li>
																				<li class="droplink">
																					<a href="javascript:void(0)"><p class="">Driver app</p><span class="arrow"></span></a>
																					<ul class="sub-menu">
																						<li>
																							<a href="<?= Yii::app()->createUrl('admin/generalReport/driverAppUsage') ?>" >
																								<div class="center-block">Compliance report</div></a>
																						</li>
																						<li>
																							<a href="<?= Yii::app()->createUrl('admin/generalReport/driverAppNotUsed') ?>" >
																								<div class="center-block">Drilleddown report</div></a>
																						</li>
																						<li>
																							<a href="<?= Yii::app()->createUrl('admin/generalReport/driverappusagereport') ?>" >
																								<div class="center-block">Driver App Usage Summary</div></a>
																						</li>
																					</ul>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/blockedVendor') ?>" >
																						<div class="center-block">Blocked Vendors</div></a>
																				</li>
									
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/profitability') ?>" >
																						<div class="center-block">Zone Profitability</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/referralBonous') ?>" >
																						<div class="center-block">Referral Bonous</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/inventoryShortage') ?>" >
																						<div class="center-block">Inventory Shortage</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/zonesupplydensity') ?>" >
																						<div class="center-block">Zone Supply Density</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/zeroInventory') ?>" >
																						<div class="center-block">Zero Inventory City/Zone</div></a>
																				</li>
																				<li>
																						<a href="<? #= Yii::app()->createUrl('aaohome/generalReport/csrLeadPerformanceReport/')                             ?>" >
																								<div class="center-block">CSR Lead Performance Details Report</div></a>
																				</li>
																				<li class="droplink">
																					<a href="javascript:void(0)"><p class="">Completed Booking Count</p><span class="arrow"></span></a>
																					<ul class="sub-menu">
																						<li>
																							<a href="<?= Yii::app()->createUrl('admin/generalReport/zoneWiseCountBooking') ?>" >
																								<div class="center-block">Zone Wise Count</div></a>
																						</li>
																						<li>
																							<a href="<?= Yii::app()->createUrl('admin/generalReport/vendorWiseCountBooking') ?>" >
																								<div class="center-block">Vendor Wise Count</div></a>
																						</li>
																					</ul>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/processedPayments') ?>" >
																						<div class="center-block">Processed Payments</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/manualAssignmentCount') ?>" >
																						<div class="center-block">Manual Assignment Count</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/assignmentReport') ?>" >
																						<div class="center-block">Assignment Stats</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/ratingReport') ?>" >
																						<div class="center-block">Rating Stats</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/stickyCarCount') ?>" >
																						<div class="center-block">Sticky Car Count</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/stickyVendorCount') ?>" >
																						<div class="center-block">Sticky Vendor Count</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/tierCount') ?>" >
																						<div class="center-block">Vendor / Car Count By Tier</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/directAcceptReport') ?>" >
																						<div class="center-block">Direct Accepted Booking Stats</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/partnerWiseCountBooking') ?>" >
																						<div class="center-block">Partner Booking(B2B Other)</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/lourequired') ?>" >
																						<div class="center-block">LOU Required</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/vendorCancellation') ?>" >
																						<div class="center-block">Vendor Cancellation Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/generalReport/cbrdetailsreport/') ?>" >
																						<div class="center-block">CBR's Details Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/generalReport/serviceRequests') ?>" >
																						<div class="center-block">Service Requests Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/generalReport/bookingtrackdetails') ?>" >
																						<div class="center-block">Booking Track Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/servicePerformance') ?>" >
																						<div class="center-block">Service Performance</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/promoReport') ?>" >
																						<div class="center-block">Promotions Report</div></a>
																				</li>					
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/vendorusagereport') ?>" >
																						<div class="center-block">Vendor Usage Summary Details</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/RegionVendorwiseDriverAppusage') ?>" >
																						<div class="center-block">Vendor/Region wise Driver App Usage Summary </div></a>
																				</li>
									
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/dailyloss') ?>" >
																						<div class="center-block">Daily Loss </div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('admin/generalReport/processbooking') ?>" >
																						<div class="center-block">Process Booking Report </div></a>
																				</li>
																				<li class="droplink">
																					<a href="javascript:void(0)"><p class="">Accounts</p><span class="arrow"></span></a>
																					<ul class="sub-menu">
																						<li>
																							<a href="<?= Yii::app()->createUrl('admin/account/ledgerList') ?>" >
																								<div class="center-block">Ledger Report </div></a>
																						</li>
																						<li>
																							<a href="<?= Yii::app()->createUrl('admin/report/PartnerMonthlyBalance') ?>" >
																								<div class="center-block">Partner Monthly Balance </div></a>
																						</li>
																						<li>
																							<a href="<?= Yii::app()->createUrl('admin/generalReport/paymentSummaryReport') ?>" >
																								<div class="center-block">Payment Summary Report</div></a>
																						</li>
																					</ul>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/generalReport/bookingReport') ?>" >
																						<div class="center-block">Booking Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/generalReport/AttendanceReport') ?>" >
																						<div class="center-block">Attendance Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/scq/fetchlist') ?>" >
																						<div class="center-block">Team queue</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('aaohome/generalReport/csrPerformanceReport') ?>" >
																						<div class="center-block">CSR Performance Report</div></a>
																				</li>
																				<li>
																					<a href="<?= Yii::app()->createUrl('/report/vendor/compensation') ?>" >
																						<div class="center-block">Vendor Compensation</div></a>
																				</li>
																			</ul>
																		</li>-->

									<!--									<li>
																			<a href="<?= Yii::app()->createUrl('admin/zone/volumetrend') ?>" >
																				<div class="center-block">Zonal Volume Trends</div></a>
																		</li>
									
																		<li>
																			<a href="<?= Yii::app()->createUrl('admin/vendor/regionperf') ?>" >
																				<div class="center-block">Region Perf Report</div></a>
																		</li>
																		<li>
																			<a href="<?= Yii::app()->createUrl('admin/dialer/Audioreport') ?>" >
																				<div class="center-block">Call Report</div></a>
																		</li>-->
								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fas fa-gem"></span><p class="">Promotions</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/promos/add') ?>" >
											<div class="center-block">Add Promotion</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/promos/list') ?>" >
											<div class="center-block">Promotions List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/voucher/add') ?>" >
											<div class="center-block">Add Voucher</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/voucher/list') ?>" >
											<div class="center-block">Voucher List</div></a>
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
									<li>
										<a href="<?= Yii::app()->createUrl('admin/rating/nps') ?>" >
											<div class="center-block">NPS Report</div></a>
									</li>
								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-sticky-note"></span><p class="">Notes</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/notes/list') ?>" >
											<div class="center-block">Note List</div></a>
									</li>
									<li>
										<a href="<?= Yii::app()->createUrl('admin/notes/add') ?>" >
											<div class="center-block">Add Note</div></a>
									</li>
								</ul>
							</li>
							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-sticky-note"></span><p class="">Callback list</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('admin/scq/list') ?>" >
											<div class="center-block">Callback list</div></a>
									</li>
								</ul>
							</li>

							<?php
							if (Yii::app()->user->checkAccess('AdminTools'))
							{
								?>	
								<li class="droplink">
									<a href="#"><span class="menu-icon fa fa-briefcase"></span><p class="">Admin Tools</p></a>
									<ul class="sub-menu">
										<li>
											<a href="<?= Yii::app()->createUrl('admin/terms/listPoints') ?>" >
												<div class="center-block">List Points</div></a>
										</li>
										<li>
											<a href="<?= Yii::app()->createUrl('admin/developerReport/query') ?>" >
												<div class="center-block">Query</div></a>
										</li>

									</ul>
								</li>	
							<?php }
							?>

							<!--							<li class="droplink">
																																							<a href="#" ><span class="menu-icon fa fa-book"></span><p class="">Contacts</p></a>
																																							<ul class="sub-menu">
																																											<li>
																																															<a href="<? //= Yii::app()->createUrl('admin/contact/form')                                                       ?>" >
																																																			<div class="center-block">Add Contact</div></a>
																																											</li>
																																											<li>
																																															<a href="<? //= Yii::app()->createUrl('admin/contact/list')                                                       ?>" >
																																																			<div class="center-block">Manage Contacts</div></a>
																																											</li>
																																											
																																											<li>
																																															<a href="<? //= Yii::app()->createUrl('admin/contact/duplicatecontact')                                                       ?>" >
																																																			<div class="center-block">Duplicate Contact</div></a>
																																											</li>
																																											
																																											
																																											<li>
																																															<a href="<? //= Yii::app()->createUrl('admin/document/docsList')                                                       ?>" >
																																																			<div class="center-block">Document Pending Approval</div></a>
																																											</li>
																																																			
																																							</ul>
																																			</li>-->

							<li class="droplink">
								<a href="#" ><span class="menu-icon fa fa-sticky-note"></span><p class="">Live Helper Chat</p></a>
								<ul class="sub-menu">
									<li>
										<a href="#" data-target="#pwdModal" data-toggle="modal">Reset my password</a>
									</li>
								</ul>
							</li>
							<li class="droplink">
								<a href="#"><span class="menu-icon fa fa-briefcase"></span><p class="">Miscellaneous</p></a>
								<ul class="sub-menu">
									<li>
										<a href="<?= Yii::app()->createUrl('aaohome/gozen') ?>">
											<div class="center-block">GOZO INTRANET</div></a>
									</li>
									<li>
										<a href="https://docs.google.com/forms/d/1wyr5a1CQFlRLIYb3FqKdpKquKXd6L-pTgzLVvNH-D3g/edit?usp=sharing" target="_blank"><div class="center-block">LEAVE APPLICATION</div> </a>

									</li>
								</ul>
							</li>
							<div id="pwdModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-body">
											<div class="">
												<div class="panel panel-default">
													<div class="panel-body">
														<div class="text-center">
															<p class="text-info">You can reset your password here.</p>
															<div class="panel-body">
																<fieldset>
																	<div class="form-group">
																		<input class="form-control input-lg" placeholder="Enter Email" id="email" name="email" type="email">
																	</div>           
																	<div class="form-group">
																		<input class="form-control input-lg" placeholder="Enter New Password" id="password" name="password" type="password">
																	</div>    
																	<input class="btn btn-lg btn-primary btn-block" id="submitButton" value="Reset Password" type="button">
																</fieldset>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<div class="col-md-12">
												<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Cancel</button>
											</div>	
										</div>
									</div>
								</div>
							</div>

							<?php
						}
						if (Yii::app()->user->isGuest)
						{
							?>
							<li><a href="<?= Yii::app()->createUrl('admin') ?>">Sign In</a></li>
						<?php } ?>
                    </ul>
                </div>
            </div>
            <div class="page-inner" >
                <div class="page-title hide">
                    <div class="row">

                        <div class="col-xs-12 col-sm-3 col-md-2 hide">
                            <h3><?= $this->pageTitle ?></h3>
                        </div>
                    </div>
                </div>
				<?php
				$urlSegment = Yii::app()->request->url;
				if ($urlSegment == '/aaohome/index/dashboard')
				{
					?>
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-12 col-md-2">
								<div class="form-group" >
									<select name="viewPersonType" id='viewPersonType' class="form-control">
										<option>Lookup vendor or driver profile</option>
										<option value='2'>Vendor</option>
										<option value='3'>Driver</option>
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-md-3" id="person_unique_code" style="display:none">
								<input type="text" name="person_unique_codeTxt" id="person_unique_codeTxt" class="form-control" placeholder="">
							</div>


							<div class="col-xs-12 col-md-2">
								<div class="form-group" >
									<select name="bookingIdType" id='bookingIdType' class="form-control">
										<option>Lookup Booking By</option>
										<option value='1'>Booking ID</option>
										<option value='2'>Booking ID (with prefix)</option>
										<option value='3'>Agent Booking ID </option>
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-md-3" id="booking_unique_code" style="display:none">
								<input type="text" name="booking_unique_codeTxt" id="booking_unique_codeTxt" class="form-control" placeholder="">
							</div>


							<div class="col-xs-12 col-md-2">
								<div class="form-group" >
									<select name="furIdType" id='furIdType' class="form-control">
										<option>Lookup SR/FUR/CBR by ID</option>
										<option value='1'>SR/FUR/CBR </option>
									</select>
								</div>
							</div>
							<div class="col-xs-12 col-md-3" id="fur_unique_code" style="display:none">
								<input type="text" name="fur_unique_codeTxt" id="fur_unique_codeTxt" class="form-control" placeholder="">
							</div>

						</div>
					</div>
				<?php } ?>


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
				<?php } ?>
                <div id="main-wrapper" class="container-fluid   ">
                    <!-- BEGIN PAGE CONTAINER-->
					<?php if (isset($this->breadcrumbs)): ?>
						<?php
						$this->widget('zii.widgets.CBreadcrumbs', array(
							'links' => $this->breadcrumbs,
						));
						?><!-- breadcrumbs -->
					<?php endif ?>
                    <div class="cnt" >
						<?php echo $content; ?>
                    </div>
                </div>
                <div class="page-footer" style="bottom: 0; position: relative">
                    <div class="container" >
                        <p>
                            Copyright &copy; <?php echo date('Y'); ?> by aaocab.
                            All Rights Reserved.
                        </p>
                    </div>
                </div><!-- footer --></div>

            <!--
			<?php
			print_r($GLOBALS['time']);
			?>-->
        </main>
    </body>
    <script>

		$("#dialer").click(function()
		{
			$("#txtdialerNo").show();
			$("#dailerBox").hide();
		});

		$('#txtdialerNo').keypress(function(event)
		{

			var keycode = (event.keyCode ? event.keyCode : event.which);
			if (keycode == '13')
			{
				//alert('You pressed a "enter" key in textbox');
				var dialerNo = $("#txtdialerNo").val();
				$href = '<?= Yii::app()->createUrl('admin/index/saveDialer') ?>';
				jQuery.ajax({type: 'GET', url: $href, data: {"dialerNo": dialerNo},
					success: function(data)
					{
						$("#dailerBox").show();
						$("#dailerBox").text(data);
						$("#txtdialerNo").hide();
					}
				});
			}
		});



		$('#submitButton').click(function()
		{
			var password = $('#password').val().trim();
			var email = $('#email').val().trim();
			$.ajax({
				"type": "POST",
				dataType: 'json',
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('bot/UpdatePassword')) ?>",
				data: {
					'password': password,
					'email': email
				},
				"async": false,
				"success": function(response)
				{
					if (response.success)
					{
						alert('Profile Updated Successfully With New Password');
					}
					else
					{
						alert(response.data);
					}
				}

			});
		});

		var obj1 = new MaskFilter();
		obj1.getnameFilter();
		$followUp = new FollowUp();
		$('#crtfollow').click(function()
		{
			$followUp.createFollowUps();
		});


		$(document).ready(function()
		{

			$flag = 0;
			getBackScq();

		});
		function cnlSubmit()
		{
			$("#scqForm").hide("slow");
		}
		function getBackScq()
		{
			$flag = '<?= $_REQUEST['scq'] ?>';
			if ($flag != '')
			{
				$.ajax({
					"type": "GET",
					"url": $baseUrl + '/aaohome/scq/showCallbackQue',
					"data":
							{"followupId": '<?= $_REQUEST['scq'] ?>'},
					"dataType": "HTML",
					"success": function(data1)
					{
						schedulebox = bootbox.dialog({
							message: data1,
							size: 'large',
							title: '',

						});
						schedulebox.on('hidden.bs.modal', function(e)
						{
							$('body').addClass('modal-open');
						});
					}

				});
			}

		}


		$(document).ready(function()
		{
			$('#viewPersonType').change(function()
			{
				$("#person_unique_code").show("slow");
				var plctext = $("#viewPersonType option:selected").text();
				$("#person_unique_codeTxt").attr("placeholder", plctext + " code");

			});

			$('#person_unique_codeTxt').keypress(function(e)
			{
				if (e.which == 13)
				{
					var personType = $("#viewPersonType").val();
					var personCode = $("#person_unique_codeTxt").val();

					$.ajax({
						"type": "POST",
						"url": '<?= Yii::app()->createUrl("admin/index/redirectPerson"); ?>',
						'dataType': "json",
						"data": {"personType": personType, "personCode": personCode, "YII_CSRF_TOKEN": "<?= Yii::app()->request->csrfToken ?>"},
						"success": function(data1)
						{
							if (data1.success)
							{
								window.open(data1.link);
							}
							else
							{
								alert("Invalid Code.");
							}
						},
					});
					return false;
				}
			});


			$('#bookingIdType').change(function()
			{
				$("#booking_unique_code").show("slow");
				var txt;
				var idType = $("#bookingIdType").val();
				switch (idType)
				{
					case "1":
						txt = "(Ex. 1878315)";
						break;
					case "2":
						txt = "(Ex. TFR101878315/OW101878315)";
						break;
					case "3":
						txt = "(Ex. NC74625558872920)";
						break;
				}
				var plctext = $("#bookingIdType option:selected").text();
				$("#booking_unique_codeTxt").attr("placeholder", txt);
			});


			$('#booking_unique_codeTxt').keypress(function(e)
			{
				var link = "";
				if (e.which == 13)
				{
					var idType = $("#bookingIdType").val();
					var idVal = $("#booking_unique_codeTxt").val();
					switch (idType)
					{
						case "1":
							link = "<?php echo Yii::app()->createUrl('aaohome/booking/view'); ?>?id=" + idVal;
							break;
						case "2":
							link = "<?php echo Yii::app()->createUrl('aaohome/booking/view'); ?>?booking_id=" + idVal;
							break;
						case "3":
							link = "<?php echo Yii::app()->createUrl('aaohome/booking/view'); ?>?partner_ref=" + idVal;
							break;
					}
					window.open(link);
				}
			});


			$('#furIdType').change(function()
			{
				$("#fur_unique_code").show("slow");
				var txt;
				var idType = $("#furIdType").val();
				switch (idType)
				{
					case "1":
						txt = "(Ex. 336058)";
						break;
				}
				var plctext = $("#furIdType option:selected").text();
				$("#fur_unique_codeTxt").attr("placeholder", txt);
			});


			$('#fur_unique_codeTxt').keypress(function(e)
			{
				var link = "";
				if (e.which == 13)
				{
					var idType = $("#furIdType").val();
					var idVal = $("#fur_unique_codeTxt").val();
					switch (idType)
					{
						case "1":
							link = "<?php echo Yii::app()->createUrl('aaohome/scq/view'); ?>?id=" + idVal;
							break;
					}
					window.open(link);
				}
			});




			$('#agent_id').change(function()
			{
				var ag_id = $('#agent_id').val();
				var link = "<?php echo Yii::app()->createUrl('aaohome/agent/view'); ?>?agent=" + ag_id;
				window.open(link);
			});
		});



		function blinker()
		{
			$('.blinking').fadeOut(500);
			$('.blinking').fadeIn(500);
		}
		var getCountInternalCBRbyAdminID = '<?php echo $getCountInternalCBRbyAdminID ?>';
		if (getCountInternalCBRbyAdminID > 0)
		{
			setInterval(blinker, 1000);
		}

		$(document).ready(function()
		{
			var submitIcon = $('.searchbar-icon');
			var inputBox = $('.searchbar-input');
			var searchbar = $('.searchbar');
			var isOpen = false;
			submitIcon.click(function()
			{
				if (isOpen == false)
				{
					searchbar.addClass('searchbar-open');
					inputBox.focus();
					isOpen = true;
				}
				else
				{
					searchbar.removeClass('searchbar-open');
					inputBox.focusout();
					isOpen = false;
				}
			});
			submitIcon.mouseup(function()
			{
				return false;
			});
			searchbar.mouseup(function()
			{
				return false;
			});
			$(document).mouseup(function()
			{
				if (isOpen == true)
				{
					$('.searchbar-icon').css('display', 'block');
					submitIcon.click();
				}
			});
		});
		function buttonUp()
		{
			var inputVal = $('.searchbar-input').val();
			inputVal = $.trim(inputVal).length;
			if (inputVal !== 0)
			{
				$('.searchbar-icon').css('display', 'none');
			}
			else
			{
				$('.searchbar-input').val('');
				$('.searchbar-icon').css('display', 'block');
			}
		}


		$href = '<?= Yii::app()->createUrl('lookup/allReportByQuery/') ?>';
		$("#tags").autocomplete({
			source: function(request, response)
			{
				$.ajax({
					global: false,
					url: $href + '?term=' + request.term,
					dataType: "json",
					"beforeSend": function()
					{
					},
					"complete": function()
					{

					},
					success: function(data)
					{
						response(data.result);
					}
				});
			},
			response: function(event, ui)
			{
				if (!ui.content.length)
				{
					var noResult = {value: "", label: "No results found"};
					ui.content.push(noResult);
				}
			},
			minLength: 2,
			select: function(event, ui)
			{
				$(this).val("");

				if (ui.item.value != "")
				{
					if (event.ctrlKey)
					{
						window.open(ui.item.value, "_blank");
					}
					else
					{
						location.href = ui.item.value;
					}
				}
				return false;
			},
			focus: function(event, ui)
			{
				this.value = ui.item.label;
				event.preventDefault();
			},
			change: function(ev, ui)
			{
				if (!ui.item)
				{
					$(this).val('');
				}
			}
		}).data("ui-autocomplete")._renderItem = function(ul, item)
		{
			return $("<li title='Press ctrl to open in new window'></li>").data("item.autocomplete", item).append("<div><a>" + item.label + "</a></div>").appendTo(ul);
		};


    </script>
</html>
