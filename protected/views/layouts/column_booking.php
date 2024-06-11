<style>
    .bookin_header{
        background: #193651;
    }
    .stop-menu3{ margin-right: -15px; margin-top: 12px;}
    .stop-menu3 .navbar { min-height: 40px!important;}
    .stop-menu3 .navbar-nav li a{ font-size: 14px; text-align: right; font-weight: normal; color: #fff!important; padding: 5px 10px; -webkit-border-radius: 2px; -moz-border-radius: 2px; border-radius: 2px;}
    .stop-menu3 .navbar-nav li a:hover{ background: #315679; color: #fff;}
    .stop-menu3 .navbar-nav li a:focus{ color: #fff;}
    .stop-menu3 .dropdown-menu{ background: #193651;}
</style>
<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
<?php
$detect				 = Yii::app()->mobileDetect;
// call methods
$isMobile			 = $detect->isMobile();
//$isMobile=1;
if($isMobile==1)
{
	$this->beginContent('//layouts/head_mobile');
}
else
{
	$this->beginContent('//layouts/head');
}


//if ($this->layout == 'column1') {;
//    //$style = "background-color: inherit";
//}
//$fixedTop = ($this->fixedTop) ? "navbar-fixed-top" : "";
//$bgBanner = ($this->fixedTop) ? "bg-banner" : "";
?>
<?php
if (Yii::app()->user->isGuest)
{
	$uname		 = '';
	$isLoggedin	 = false;
	?>

	<?
}
else
{
	$isLoggedin	 = true;
	$uname		 = Yii::app()->user->loadUser()->usr_name;
?>

<? } ?>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="http://www.googletagmanager.com/ns.html?id=GTM-T73295"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="container-fluid smain-bg">
        <header class="header bookin_header ml15 mr15 n">
            <div class="container">
				<span class="hidden-sm hidden-md hidden-lg" style="font-size:20px;cursor:pointer; position:absolute; padding: 5px; top: 16px; right: 11px; z-index: 99; color: #fff;" onclick="openNav()"><i class="fa fa-bars"></i></span>
                <div class="row pt20 pb20">
                    <div class="col-sm-6 col-md-6">
                        <a class="" href="/"><img src="/images/logo2_new_white.png?v1.3" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." title="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></a>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div id="mySidenav" class="sidenav">
							<a href="javascript:void(0)" class="closebtn border-none" onclick="closeNav()">&times;</a>
							<?
							if ($isLoggedin)
							{
								?>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" >Hello <?= $uname ?><i class="fa fa-user" style="padding-left: 10px"></i></a>


								<a href="<?= Yii::app()->createUrl('users/view') ?>"><i class="fa fa-user pr10"></i> My Profile</a> 
								<a href="<?= Yii::app()->createUrl('index/index'); ?>"><i class="fa fa-car"></i> New Booking</a>
								<a href="<?= Yii::app()->createUrl('booking/list'); ?>"><i class="fa fa-list"></i> Booking History</a>
								<a href="<?= Yii::app()->createUrl('users/refer'); ?>"><i class="fa fa-users"></i> Refer friends</a>
								<a href="<?= Yii::app()->createUrl('users/creditlist'); ?>"><i class="fa fa-book"></i> Gozo Coins</a>
								<a href="<?= Yii::app()->createUrl('users/changePassword') ?>"><nobr><i class="fa fa-pencil pr10"></i> Change Password</nobr></a> 
							<? } ?>

							<a href="/whygozo"><i class="fa fa-check mr5"></i> Why GozoCabs</a>
							<a href="/faq"><i class="fa fa-star mr5"></i> FAQ</a>
							<a href="/vendor/join"><i class="fa fa-user mr5"></i>Attach Your Taxi</a>
							<a href="/index/testimonial"><i class="fa fa-quote-left mr5"></i>Testimonials</a>
							<a href="/blog"><i class="fa fa-comments-o mr5"></i> Our Blog</a>
							<?
							if (!$isLoggedin)
							{
								?>
								<a href="/signin"><i class="fa fa-sign-in mr5"></i> Sign In</a>
								<?
							}
							else
							{
								?>
								<a href="<?= Yii::app()->createUrl('users/logout') ?>"><i class="fa fa-sign-out pr10"></i>Log Out</a> 
							<? } ?>
						</div>
                        <div class="stop-menu3 hidden-xs pull-right">
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
                                            <li class="dropdown" id="navbar_sign">
												<?php
												$time = Filter::getExecutionTime();

												$GLOBALS['time96']	 = $time;
												?>
												<?php
												$this->renderPartial("/users/navbarsign");
												?>					 
                                            </li>

                                        </ul></div>
                                    <!-- /.navbar-collapse -->
                                </div><!-- /.container-fluid -->
                            </nav>
                        </div>
                    </div>
                </div>

            </div>
        </header>
		<?php
		$time				 = Filter::getExecutionTime();

		$GLOBALS['time97']	 = $time;
		?>
        <div class="container mt20">
            <div class="row">
                <div class="col-xs-12">
					<?= $content ?>
                </div>
            </div>
        </div>
		<?php
		$time				 = Filter::getExecutionTime();

		$GLOBALS['time98']	 = $time;
		?>
		
		<?
		if($isMobile==1)
		{
			$this->renderPartial("/index/footer_mobile");
		}
		else
		{
			$this->renderPartial("/index/footer");
		}
		 ?>
    </div>
</body>
<?php
$time				 = Filter::getExecutionTime();

$GLOBALS['time99'] = $time;
?>
<!--
<?php
//print_r($GLOBALS['time1'] ."==1\n");
//print_r($GLOBALS['time2'] ."==2\n");
//print_r($GLOBALS['time3'] ."==3\n");
//print_r($GLOBALS['time4'] ."==4\n");
//print_r($GLOBALS['time5'] ."==5\n");
//print_r($GLOBALS['time6'] ."==6\n");
//print_r($GLOBALS['time7'] ."==7\n");
//print_r($GLOBALS['time8'] ."==8\n");
//print_r($GLOBALS['time9'] ."==9\n");
?>
-->
<?php $this->endContent(); ?>

