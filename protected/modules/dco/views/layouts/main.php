<?php
$this->beginContent('/layouts/head');
?>
<script>
function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 180px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 5px 8px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #f1f1f1}

    .dropdown:hover .dropdown-content {
        display: block;
    }
    li.disabled {
        pointer-events: none;
        cursor: default;
    }
    .page-header {
        height: 120px;
		
    }

    .page-header .page-header-menu .hor-menu .navbar-nav>li>a {
        padding: 12px 16px;
        color: #fff;
    }

    .page-header .page-header-menus {
        height: 45px;
		background: #152b57;
    }
	.page-header .page-header-menus a { color: #fff; font-size: 14px; padding: 12px 12px;}
	.page-header .page-header-menus a:hover { color: #fff; background: #0a1835; height: 45px;}
@media (min-width: 320px) and (max-width: 767px) {
	.page-header .page-header-menus {
        height: 45px;
		background: #152b57;
    }
	.page-header .page-header-top .page-logo{ width: 90px!important; height: auto;}
	.page-logo img{ width: 90%;}
	.profile-usertitle-name{ font-size: 11px!important;}
	/*********************Start Sidenav*********************************/
.sidenav {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 100;
    top: 0;
    right: 0;
    background-color: #152b57;
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 20px;
    -webkit-box-shadow: 5px 0px 8px 0px rgba(219,219,219,1);
    -moz-box-shadow: 5px 0px 8px 0px rgba(219,219,219,1);
    box-shadow: 5px 0px 8px 0px rgba(219,219,219,1);
    /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ffffff+0,ededed+100 */
    border-left: #fff 1px solid;
}
.sidenav a i{ padding-right: 10px;}
.sidenav ul{ background: #152b57; margin-top: 15px;}
.sidenav a {
    padding: 8px 10px;
    text-decoration: none;
    font-size: 14px;
    color: #fff;
    display: block;
    transition: 0.3s;
    margin: 0 20px;
    border-bottom: #27437c 1px solid;
}
.sidenav a:hover {
    color: #fff;
	background: #0a1835!important;
}
.sidenav a:focus {
    color: #fff!important;
	background: #0a1835!important;
}

.sidenav .closebtn {
    position: absolute;
    top: -16px;
    right: -9px;
    font-size: 36px;
    margin-left: 50px;
}
.sidenav .nav>li>a{ padding: 12px 10px;}
}
/***********************End Sidenav*******************************/
@media (min-width: 768px) and (max-width: 991px) {
.page-header .page-header-menus a { color: #fff; font-size: 12px; font-weight: 400; padding: 12px 6px;}
}
</style>
<style>
    .portlet.light>.portlet-title>.nav-tabs>li.active>a, .portlet.light>.portlet-title>.nav-tabs>li:hover>a{ background: none!important; color: #666!important;}
</style>
<body class="page-container-bg-solid page-md">

    <div class="page-wrapper">
        <div class="page-wrapper-row">
            <div class="page-wrapper-top">
                <!-- BEGIN HEADER -->
                <div class="page-header">
                    <!-- BEGIN HEADER TOP -->
                    <div class="page-header-top">
                        <div class="container-fluid">
                            <!-- BEGIN LOGO -->
                            <div class="page-logo">
                                <a href="/agent">
                                    <img src="/images/logo2_new.png" alt="logo" class="logo-default mt10">
                                </a>
                            </div>

                            <!-- END LOGO -->
                            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                            <!--<a href="javascript:;" class="menu-toggler"></a> -->
                            <!-- END RESPONSIVE MENU TOGGLER -->
                            <!-- BEGIN TOP NAVIGATION MENU -->
                            <?
                            $agenets = Agents::model()->findByPk(Yii::app()->user->getAgentId());
                            if ($agenets->agt_approved != 1)
                            {
                                ?>
                                <span class="col-xs-12 col-md-4 col-sm-4 col-md-offset-2 mt10" style="font-size: 12px; text-align: left; color: #FF3F3F">You have not been approved yet.<br> Please contact customer support (+91) 90518-77-000 for approval for any uninterrupted services.</span>
                            <? }
                            ?>

                            <?
                            $modelUser  = Yii::app()->user->loadAgentUser();
                            $agentModel = Agents::model()->find('agt_id = :user', ['user' => Yii::app()->user->getAgentId()]);
                            ?>
                            <ul class="nav navbar-nav pull-right mt10 mr10">
                                <li class="mr10">
                                    <img alt="" class="img-circle" src="<?= ($modelUser->usr_profile_pic_path != '') ? $modelUser->usr_profile_pic_path : '/images/noimg.gif' ?>" width="50px" height="50px"> 
                                </li>
                                <li class="dropdown dropdown-user dropdown-dark mt5" style="text-align: left;">     
                                    <?
                                    if ($agentModel->agt_type == 2)
                                    {
                                        $agtType = "Authorized Reseller";
                                    }
                                    else
                                    {
                                        $agtType = "Travel Agent";
                                    }
                                    $company = $agentModel->agt_company;
                                    if ($company == '')
                                    {
                                        $company = $agentModel->agt_owner_name;
                                    }
                                    if ($company == '')
                                    {
                                        $company = $agentModel->agt_fname . " " . $agentModel->agt_lname;
                                    }
                                    ?>
                                    <span class="profile-usertitle-name" style="color: #5a7391!important"><?= (Yii::app()->user->getCorpCode() != '') ? $company . " (" . Yii::app()->user->getCorpCode() . "- Corporate)" : $company . " (" . $agentModel->agt_agent_id . "-" . $agtType . ")"; ?></span><br><small><?= $modelUser->usr_name . " " . $modelUser->usr_lname; ?></small> <i class="fa fa-angle-down"></i>
                                    <div class="dropdown-content">
                                        <a href="<?= Yii::app()->createUrl('agent/users/editprofile') ?>"><i class="fa fa-user text-warning mr5"></i>My Profile</a>
                                        <a href="<?= Yii::app()->createUrl("agent/users/additionaldetails"); ?>"><i class="fa fa-users text-info mr5"></i>Partner Profile</a>
                                        <a href="#" onclick="changePassword('<?= Yii::app()->createUrl("agent/users/changepassword", ['agent' => Yii::app()->user->getAgentId()]); ?>')"><i class="fa fa-lock text-success mr5"></i>Change Password</a>
                                        <a href="<?= Yii::app()->createUrl("agent/index/logout"); ?>"><i class="fa fa-sign-out text-danger mr5"></i>Logout</a>
                                    </div>
                                </li>
                            </ul>
                            <!-- END TOP NAVIGATION MENU -->
                        </div>
                    </div>
                    <!-- END HEADER TOP -->
                    <!-- BEGIN HEADER MENU -->
                    <div class="page-header-menus hidden-xs">
                        <div class="container-fluid">
                            <!-- BEGIN MEGA MENU -->
                            <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                            <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
							<div class="hor-menu">
                                <ul class="nav navbar-nav">
                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown " >
                                        <a href="<?= Yii::app()->createUrl("agent/index/dashboard"); ?>"><span class="text-warning menu-icon fa fa-tachometer"></span> Dashboard</a>

                                    </li>

                                    <!--  <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown" onclick="alert('You have not been approved yet. Please contact customer support (+91) 90518-77-000 /(+1) 650-741-GOZO for approval.');">
                                           <a href="#" class="disabled"><span class="text-primary menu-icon fa fa-car"></span> New Booking</a>
                                     </li> -->

                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="/agent/booking/createquote"><span class="text-primary menu-icon fa fa-car"></span> New Booking</a>
                                    </li> 

                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown" >
                                        <a href="<?= Yii::app()->createUrl("agent/booking/list"); ?>"><span class="text-success menu-icon fa fa-history"></span> Booking History</a>

                                    </li>
                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown ">
                                        <a href="<?= Yii::app()->createUrl("agent/users/additionaldetails"); ?>"><span class="text-info menu-icon fa fa-user"></span>Partner Profile</a>

                                    </li>

                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="<?= Yii::app()->createUrl("agent/booking/ledgerbooking"); ?>">
                                            <span class="text-danger menu-icon fa fa-list" ></span> My Accounts</a>
                                    </li>
                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="<?= Yii::app()->createUrl("agent/booking/accountsdashboard"); ?>">
                                            <span class="menu-icon fa fa-calculator" ></span> Settlement Report</a>

                                    </li>

                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="<?= Yii::app()->createUrl("agent/recharge/add"); ?>">
                                            <span class="text-warning menu-icon fa fa-money" ></span> Recharge </a>

                                    </li>

									<li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="<?= Yii::app()->createUrl("agent/giftcard/add"); ?>">
                                            <span class="text-warning menu-icon fa fa-gift" ></span> Buy Gift Card </a>

                                    </li>
                                </ul>
                            </div>
                            <!-- END MEGA MENU -->
                        </div>
					</div>
					<div class="page-header-menus hidden-lg hidden-md hidden-sm">
						<div class="container-fluid hidden-sm hidden-lg hidden-md">
                            <!-- BEGIN MEGA MENU -->
                            <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                            <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                            <span class="" style="font-size:20px;cursor:pointer; position:absolute; padding: 5px; top: 2px; color: #f36c31; right: 11px; z-index: 99;" onclick="openNav()"><i class="fa fa-bars"></i></span>
							<div class="sidenav" id="mySidenav">
								<a href="javascript:void(0)" class="closebtn" style="border: none; font-size: 36px;" onclick="closeNav()">&times;</a>
                                <ul class="nav navbar-nav">
                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown " >
                                        <a href="<?= Yii::app()->createUrl("agent/index/dashboard"); ?>"><span class="text-warning menu-icon fa fa-tachometer"></span> Dashboard</a>

                                    </li>

                                    <!--  <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown" onclick="alert('You have not been approved yet. Please contact customer support (+91) 90518-77-000 /(+1) 650-741-GOZO for approval.');">
                                           <a href="#" class="disabled"><span class="text-primary menu-icon fa fa-car"></span> New Booking</a>
                                     </li> -->

                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="/agent/booking/createquote"><span class="text-primary menu-icon fa fa-car"></span> New Booking</a>
                                    </li> 

                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown" >
                                        <a href="<?= Yii::app()->createUrl("agent/booking/list"); ?>"><span class="text-success menu-icon fa fa-history"></span> Booking History</a>

                                    </li>
                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown ">
                                        <a href="<?= Yii::app()->createUrl("agent/users/additionaldetails"); ?>"><span class="text-info menu-icon fa fa-user"></span>Partner Profile</a>

                                    </li>

                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="<?= Yii::app()->createUrl("agent/booking/ledgerbooking"); ?>">
                                            <span class="text-danger menu-icon fa fa-list" ></span> My Accounts</a>
                                    </li>
                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="<?= Yii::app()->createUrl("agent/booking/accountsdashboard"); ?>">
                                            <span class="menu-icon fa fa-calculator" ></span> Settlement Report</a>

                                    </li>

                                    <li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="<?= Yii::app()->createUrl("agent/users/recharge"); ?>">
                                            <span class="text-warning menu-icon fa fa-money" ></span> Recharge </a>

                                    </li>

									<li aria-haspopup="false" class="menu-dropdown classic-menu-dropdown">
                                        <a href="<?= Yii::app()->createUrl("agent/giftcard/add"); ?>">
                                            <span class="text-warning menu-icon fa fa-gift" ></span> Buy Gift Card </a>

                                    </li>
                                </ul>
                            </div>
                            <!-- END MEGA MENU -->
                        </div>
                    </div>
                    <!-- END HEADER MENU -->
                </div>
                <!-- END HEADER -->
            </div>
        </div>
        <div class="page-wrapper-row full-height ">
            <div class="page-wrapper-middle ">
                <div class="page-container">
                    <div class="page-content-wrapper page-style">
                        <?php
                        if ($this->pageTitle != '')
                        {
                            ?>
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1>
                                            <?= $this->pageTitle ?>
                                            <small>
                                                <?= $this->subTitle ?>
                                            </small>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="page-content pt0">
                            <?= $content ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wrapper-row" >
        <div class="page-wrapper-bottom">
            <!-- BEGIN FOOTER -->
            <!-- BEGIN INNER FOOTER -->
            <div class="page-footer">
                <div class="container-fluid text-center">
                    Copyright &copy; <?php echo date('Y'); ?> by Gozocabs.
                    All Rights Reserved.
                </div>
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
            <!-- END INNER FOOTER -->
            <!-- END FOOTER -->
        </div>
    </div>
</div>

<script src="/assets/mtnc/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/mtnc/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="/assets/mtnc/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/assets/mtnc/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/assets/mtnc/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>

<script type="text/javascript">

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
                                            jQuery(window).on('load',function ()
                                            {
                                                // will first fade out the loading animation
                                                jQuery("#status").fadeOut();
                                                // will fade out the whole DIV that covers the website.
                                                jQuery("#preloader").delay(100).fadeOut("slow");
                                            });


                                            function changePassword(href) {
                                                $.ajax({
                                                    "type": "GET",
                                                    "dataType": "html",
                                                    "url": href,
                                                    "success": function (data)
                                                    {
                                                        bootbox.dialog({
                                                            message: data,
                                                            className: "bootbox-xs",
                                                            title: "",
                                                            size: "small",
                                                            callback: function () {

                                                            }
                                                        });
                                                    }
                                                });
                                            }
</script>
<!-- END THEME LAYOUT SCRIPTS -->

</body>
<?php $this->endContent(); ?>
