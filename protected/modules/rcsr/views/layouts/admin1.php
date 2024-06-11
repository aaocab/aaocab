<?php
/* @var $this Controller */

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/enquire.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.cookie.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.nicescroll.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/moment.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/daterangepicker.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerPackage('style');
$adminModel = Admins::model()->findByPk(Yii::app()->user->getId());
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
        <link href='<?= (Yii::app()->request->getIsSecureConnection() ? "https" : "http") ?>://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'/>
        <link href="/assets/css/admin.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/css/daterangepicker.css" rel="stylesheet" type="text/css"/>
        <script src="/assets/js/admin.js"></script>
        <script src="/assets/plugins/jquery-counterup/jquery.counterup.min.js"></script>
        <link href="/assets/toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
        <script src="/assets/toastr/toastr.min.js"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
        <!--[if lt IE 9]>
            <script type="text/javascript" src="<?= (Yii::app()->request->getIsSecureConnection() ? "https" : "http") ?>://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- <? if (YII_DEBUG) print_r($_SERVER); ?> -->
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/assets/plugins/form-toggle/toggles.css' />
        <script type="text/javascript">
            var $baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
            var $adminUrl = "<?= Yii::app()->createAbsoluteUrl('rcsr') ?>";
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


            jQuery(document).ajaxStart(function ()
            {
                //show ajax indicator
                ajaxindicatorstart('loading data.. please wait..');
            }).ajaxStop(function ()
            {
                //hide ajax indicator
                ajaxindicatorstop();
            });
            jQuery(window).on('load', function ()
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
                "onclick": function ()
                {
                    window.location.href = '<?= Yii::app()->createUrl("rcsr/lead/report"); ?>';
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
            function populateSourceCity(obj, cityId) {

                obj.load(function (callback) {
                    var obj = this;
                    if ($sourceList == null) {
                        xhr = $.ajax({
                            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                            dataType: 'json',
                            data: {
                                // city: cityId
                            },
                            //  async: false,
                            success: function (results) {
                                $sourceList = results;
                                obj.enable();
                                callback($sourceList);
                                obj.setValue(cityId);
                            },
                            error: function () {
                                callback();
                            }
                        });
                    } else {
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    }
                });
            }
            function loadSourceCity(query, callback) {
                //	if (!query.length) return callback();
                $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
                    type: 'GET',
                    dataType: 'json',
                    global: false,
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            }
	    $sourceList = null;
    function populateVendor(obj, vndId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery', ['onlyActive' => 0, 'vnd' => ''])) ?>' + vndId,
                    dataType: 'json',
                    data: { },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(vndId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(vndId);
            }
        });
    }
    function loadVendor(query, callback) {
    
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allvendorbyquery')) ?>?onlyActive=0&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
    function populatePartner(obj, agtId) {
     
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allpartnerbyquery', ['onlyActive' => 1, 'agt' => ''])) ?>' + agtId,
                    dataType: 'json',
                    type: 'GET',
                    data: { },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(agtId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(agtId);
            }
        });
    }
    function loadPartner(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allpartnerbyquery')) ?>?onlyActive=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
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

                        <a  href="<?= Yii::app()->createUrl('rcsr/index/dashboard') ?>"
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
                            </ul>
                            <ul class="nav navbar-nav navbar-right">

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown" aria-expanded="true">
                                        <span class="user-name"><?= $adminModel->adm_fname; ?><i class="fa fa-angle-down"></i></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-list" role="menu">
                                        <li role="presentation"><a href="<?= Yii::app()->createUrl('rcsr/index/changepassword') ?>"><i class="fa fa-pencil m-r-xs"></i>Change Password</a></li>
                                        <li role="presentation"><a href="<?= Yii::app()->createUrl('rcsr/index/logout') ?>"><i class="fa fa-sign-out m-r-xs"></i>Log out</a></li>
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
                        if (!Yii::app()->user->isGuest) {
                            ?>

<!--                            <li class="droplink">
                                <a href="#"><span class="menu-icon fa fa-calculator"></span><p class="">Accounts</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vendor/vendoraccounts') ?>" >
                                            <div class="center-block">Show Accounts</div></a>
                                    </li>

                                </ul>
                            </li>-->


                              <li class="droplink">
                                <a href="<?= Yii::app()->createUrl('rcsr/booking/create') ?>"><span class="menu-icon fa fa-pencil"></span><p class="">New Booking</p><span class="arrow"></span></a>
                            </li>
							
							<li class="droplink">
                                <a href="<?= Yii::app()->createUrl('rcsr/booking/list') ?>"><span class="menu-icon fa fa-car"></span><p class="">Booking History</p><span class="arrow"></span></a>
                            </li>



                        
                            
                            
                            <li class="droplink">
                                <a href="<?= Yii::app()->createUrl('rcsr/lead/leadfollow') ?>"><span class="menu-icon fa fa-search-plus"></span><p class="">Create Lead</p><span class="arrow"></span></a>
                            </li>
							<li class="droplink">
                                <a href="<?= Yii::app()->createUrl('rcsr/lead/report') ?>"><span class="menu-icon fa fa-bars"></span><p class="">Lead Report</p><span class="arrow"></span></a>
							</li>
							
<!--                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-user"></span><p class="">Customers</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/user/list') ?>" >
                                            <div class="center-block">Customers List</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/credit/list') ?>" >
                                            <div class="center-block">Gozo Coins History</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/unsubscribe/list') ?>" >
                                            <div class="center-block">Unsubscribe List</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/unsubscribe/add') ?>" >
                                            <div class="center-block">Add Unsubscribe</div></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-user"></span><p class="">Channel Partners</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/agent/form') ?>" >
                                            <div class="center-block">Add Partner</div></a>
                                    </li>
                                    <li>
                                        <a href="<?//= Yii::app()->createUrl('rcsr/agent/corporateform') ?>" >
                                            <div class="center-block">Add new Corporate</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/agent/list') ?>" >
                                            <div class="center-block">Manage Partners</div></a>
                                    </li>
				     <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/agent/markupadd') ?>" >
                                            <div class="center-block">Add Markup</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/agent/markuplist') ?>" >
                                            <div class="center-block">Markup List</div></a>
                                    </li>
                                    <li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/agent/regprogress') ?>" >
                                            <div class="center-block">Registration Progress Report</div></a>
                                    </li>
                                </ul>
                            </li>
                    
                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-briefcase"></span><p class="">Vendors</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vendor/add') ?>" >
                                            <div class="center-block">Add Vendor</div></a>
                                    </li>

                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vendor/list') ?>" >
                                            <div class="center-block">Vendors List</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vendor/listtoapprove') ?>" >
                                            <div class="center-block">Vendor Pending Approval</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vendor/assignment') ?>" >
                                            <div class="center-block">Assignment Report</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vendor/regprogress') ?>" >
                                            <div class="center-block">Registration Progress Report</div></a>
                                    </li>
                                     <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vendor/docapprovallist') ?>" >
                                            <div class="center-block">Document Pending Approval</div>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-cab"></span><p class="">Cars</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vehicle/add') ?>" >
                                            <div class="center-block">Add Car</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vehicle/list') ?>" >
                                            <div class="center-block">View List</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vehicle/addtype') ?>" >
                                            <div class="center-block">Add New Model</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vehicle/typelist') ?>" >
                                            <div class="center-block">View Models</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vehicle/approvelist') ?>" >
                                            <div class="center-block">Cars Pending Approval</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vehicle/docapprovallist') ?>" >
                                            <div class="center-block">Document Pending Approval</div>
                                        </a>

                                    </li>
                                </ul>
                            </li>

                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-wheelchair"></span><p class="">Drivers</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/driver/add') ?>" >
                                            <div class="center-block">Add Driver</div></a>
                                    </li>

                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/driver/list') ?>" >
                                            <div class="center-block">View List</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/driver/approvelist') ?>" >
                                            <div class="center-block">Driver Approval List</div></a>
                                    </li>

                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/driver/csrApproveList') ?>" >
                                            <div class="center-block">Approved By Member</div></a>
                                    </li>
                                     <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/driver/docapprovallist') ?>" >
                                            <div class="center-block">Document Pending Approval</div>
                                        </a>

                                    </li>
                                </ul>
                            </li>

                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-circle"></span><p class="">Cities</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/city/add') ?>" >
                                            <div class="center-block">Add City</div></a>
                                    </li>

                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/city/list') ?>" >
                                            <div class="center-block">View List</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/citycoverage') ?>" >
                                            <div class="center-block">City Coverage Report</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/city/linkapproval') ?>" >
                                            <div class="center-block">City LINK Approval</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/city/placeapproval') ?>" >
                                            <div class="center-block">City Place Approval</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/city/destination') ?>" >
                                            <div class="center-block">City Popular Destinations</div></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-map-marker"></span><p class="">Routes</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/route/add') ?>" >
                                            <div class="center-block">Add Route</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/route/list') ?>" >
                                            <div class="center-block">Manage Routes and Rates</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/rate/list') ?>" >
                                            <div class="center-block">View Rates</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/pricesurge/list') ?>" >
                                            <div class="center-block">Price Surge</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/cache/list') ?>" >
                                            <div class="center-block">Clear Cache</div></a>
                                    </li>
                                </ul>
                            </li>

                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-cogs"></span><p class="">Zone</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/zone/add') ?>" >
                                            <div class="center-block">Add Zone</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/zone/list') ?>" >
                                            <div class="center-block">View List</div></a>
                                    </li>

                                </ul>
                            </li>


                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-envelope"></span><p class="">Messages</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/message/list') ?>" >
                                            <div class="center-block">Sms Log</div></a>
                                    </li>

                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/email/list') ?>" >
                                            <div class="center-block">Email Log</div></a>
                                    </li>
                                </ul>
                            </li>


                            <li class="droplink">
                                <a href="#"><span class="menu-icon fa fa-pie-chart"></span><p class="">Reports</p><span class="arrow"></span></a>
                                <ul class="sub-menu">
                                    <li class="droplink">
                                        <a href="#"><p class="">Daily Business Report</p><span class="arrow"></span></a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="<?= Yii::app()->createUrl('rcsr/report/business') ?>" >
                                                    <div class="center-block">Business</div></a>
                                            </li>
                                            <li>
                                                <a href="<?= Yii::app()->createUrl('rcsr/report/businesstrend') ?>" >
                                                    <div class="center-block">Business Trend</div></a>
                                            </li>
                                            <li>
                                                <a href="<?= Yii::app()->createUrl('rcsr/report/sourcezones') ?>" >
                                                    <div class="center-block">Source Zones</div></a>
                                            </li>
                                            <li>
                                                <a href="<?= Yii::app()->createUrl('rcsr/report/destinationzones') ?>" >
                                                    <div class="center-block">Destination Zones</div></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/money') ?>" >
                                            <div class="center-block">Money Report</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/dailyassignedreport') ?>" >
                                            <div class="center-block">Daily Assigned Report</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/daily') ?>" >
                                            <div class="center-block">Daily Report</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/vendorcollection') ?>" >
                                            <div class="center-block">Vendor Collection</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/zone/volumetrend') ?>" >
                                            <div class="center-block">Zonal Volume Trends</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/booking') ?>" >
                                            <div class="center-block">Bookings</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/pickup') ?>" >
                                            <div class="center-block">Pickups</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/travellers') ?>" >
                                            <div class="center-block">Travellers Report</div></a>
                                    </li>
                                    <? /* ?><li>
                                      <a href="<?= Yii::app()->createUrl('rcsr/report/losstrips') ?>" >
                                      <div class="center-block">Loss Trips Report</div></a>
                                      </li><? */ ?>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/vendorweekly') ?>" >
                                            <div class="center-block">Vendor Weekly Report</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/weekly') ?>" >
                                            <div class="center-block">Weekly Report</div></a>
                                    </li>

                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/runningtotal') ?>" >
                                            <div class="center-block">Running Total Report</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/cabdetails') ?>" >
                                            <div class="center-block">Cab Details Report</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/report/npsscore') ?>" >
                                            <div class="center-block">NPS Report</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/vendor/regionperf') ?>" >
                                            <div class="center-block">Region Perf Report</div></a>
                                    </li>

                                </ul>
                            </li>
                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-diamond"></span><p class="">Promotions</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/promo/add') ?>" >
                                            <div class="center-block">Add Promotion</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/promo/list') ?>" >
                                            <div class="center-block">Manage Promotions</div></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="droplink">
                                <a href="#" ><span class="menu-icon fa fa-bar-chart"></span><p class="">Ratings</p></a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/rating/list') ?>" >
                                            <div class="center-block">Rating List</div></a>
                                    </li>
                                    <li>
                                        <a href="<?= Yii::app()->createUrl('rcsr/rating/nps') ?>" >
                                            <div class="center-block">NPS Report</div></a>
                                    </li>
                                </ul>
                            </li>-->
                            <?php
                        }
                        if (Yii::app()->user->isGuest) {
                            ?>
                            <li><a href="<?= Yii::app()->createUrl('admin') ?>">Sign In</a></li>
                        <? } ?>
                    </ul>
                </div>
            </div>
            <div class="page-inner" >
                <div class="page-title">
                    <h3><?= $this->pageTitle ?></h3>
                </div>
                <?php
                if (!Yii::app()->user->isGuest) {
                    if ($this->id == "index" && $this->action->id == "dashboard") {
                        $dasboard = "active";
                    } else if ($this->id == "staff") {
                        $staff = "active";
                    } elseif ($this->id == "student" && $this->action->id == "attendance") {
                        $student_attendance = "active";
                    } else if ($this->id == "student") {
                        $student = "active";
                    } else if ($this->id == "schedule") {
                        $schedule = "active";
                    } else if ($this->id == "message") {
                        $message = "active";
                    } else if ($this->id == "report") {
                        $report = "active";
                    } else {
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
</html>
