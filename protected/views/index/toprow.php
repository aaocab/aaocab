<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>

<?
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
<span class="hidden-sm hidden-md hidden-lg" style="font-size:20px;cursor:pointer; position:absolute; padding: 5px; top: 16px; right: 11px; z-index: 99;" onclick="openNav()"><i class="fa fa-bars"></i></span>
<div class="col-xs-6 col-sm-2 col-md-2 pt10 logo-panel">
	<figure><a class="" href="/"><img src="/images/logo2_outstation.png?v1.4" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></a></figure>
</div>

<div class="col-xs-6 col-sm-10 col-md-10 pull-right">
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

		<a href="/agent/join"><i class="fa fa-check mr5"></i> Become an agent</a>
		<a href="/vendor/join"><i class="fa fa-user mr5"></i>Attach Your Taxi</a>
		<a href="/index/testimonial"><i class="fa fa-quote-left mr5"></i>Testimonials</a>
		<a href="/blog"><i class="fa fa-comments-o mr5"></i> Blog</a>
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
		<a href="javascript:void(0)" class="helpline" >CONTACT US</a>
	</div>
	<div class="stop-menu2 hidden-xs pull-right">
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
						<li><a href="/agent/join">Become an agent</a></li>
						<li><a href="/vendor/join">Attach Your Taxi</a></li>
						<li><a href="/index/testimonial">Testimonials</a></li>
						<li><a href="/blog">Blog</a></li>
						<li class="dropdown" id="navbar_sign">
							<?php
							$time = Filter::getExecutionTime();

							$GLOBALS['time96'] = $time;

							$this->renderPartial("/users/navbarsign");
							?>
						</li>
						<li><a href="javascript:void(0)" class="helpline" >CONTACT US</a></li>
					</ul></div>
				<!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
	</div>
</div>
<div class="col-xs-6 text-right hidden-sm hidden-md hidden-lg mt20 n pr20">
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
					<div class="col-xs-12 text-center mb10">
						<a href="tel:+919051877000" style="text-decoration: none;">
							<img src="/images/india-flag.png" alt="India"> 
							(+91) 90518-77-000 
						</a>
					</div>
					<div class="col-xs-12 col-md-6 text-center">
						<a href="tel:+16507414696" style="text-decoration: none">
							<img src="/images/worl-icon.png" alt="International"> (+1) 650-741-GOZO
						</a>
					</div> </div>
			</div>
		</div>
	</div>
</div>
