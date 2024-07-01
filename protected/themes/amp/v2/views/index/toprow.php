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

<!--<amp-sidebar id="sidebar-right"
  class="sample-sidebar"
  layout="nodisplay"
  side="right">
  <button on="tap:sidebar-right.close" class="btn-close">X</button>
  <nav toolbar="(min-width: 3000px)"
    toolbar-target="target-element-right">
    <ul id="menu">
		<li>
			<ul class="hidden">
<?php
if ($isLoggedin)
{
?>
				<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" >Hello <?= $uname ?><i class="fa fa-user" style="padding-left: 10px"></i></a></li>
				<li><a href="<?= Yii::app()->createUrl('users/view') ?>">My Profile</a></li> 
				<li><a href="<?= Yii::app()->createUrl('index/index'); ?>">New Booking</a></li>
				<li><a href="<?= Yii::app()->createUrl('booking/list'); ?>">Booking History</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/refer'); ?>">Refer friends</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/creditlist'); ?>">aaocab Coins</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/changePassword') ?>"><nobr>Change Password</nobr></a></li> 
<? } ?>
				<li><a href="/agent/join">Become an agent</a></li>
				<li><a href="/vendor/join">Attach Your Taxi</a></li>
				<li><a href="/index/testimonial">Testimonials</a></li>
				<li><a href="/blog">Blog</a></li>
<?php
if (!$isLoggedin) {
?>
				<li><a href="/signin">Sign In</a></li>
<?php } else { ?>
				<li><a href="<?= Yii::app()->createUrl('users/logout') ?>">Log Out</a></li>
<?php } ?>
			</ul>
		</li>
	</ul>
  </nav>
</amp-sidebar>
<button on="tap:sidebar-right.toggle" class="menu-icon"><amp-img src="/images/ando_nav2.png" width="18" height="12" ></amp-img></button>
<div id="target-element-right">
</div>-->

<span class="hidden-sm hidden-md hidden-lg" style="font-size:20px;cursor:pointer; position:absolute; padding: 5px; top: 16px; right: 11px; z-index: 99;"><i class="fa fa-bars"></i></span>

<div class="logo-panel">
	<a class="" href="/"><amp-img width="100" height="36" src="/images/aaocab-white.svg?v1.5" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></amp-img></a>
	
		
		
	
	
<!--<div class="side-bar">
	<ul id="menu">
		<li>
			<a href="#"><amp-img src="/images/ando_nav.png" width="18" height="12" ></amp-img></a>
			<ul class="hidden">
<?php
if ($isLoggedin)
{
?>
				<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" >Hello <?= $uname ?><i class="fa fa-user" style="padding-left: 10px"></i></a></li>
				<li><a href="<?= Yii::app()->createUrl('users/view') ?>">My Profile</a></li> 
				<li><a href="<?= Yii::app()->createUrl('index/index'); ?>">New Booking</a></li>
				<li><a href="<?= Yii::app()->createUrl('booking/list'); ?>">Booking History</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/refer'); ?>">Refer friends</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/creditlist'); ?>">aaocab Coins</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/changePassword') ?>"><nobr>Change Password</nobr></a></li> 
<? } ?>
				<li><a href="/agent/join">Become an agent</a></li>
				<li><a href="/vendor/join">Attach Your Taxi</a></li>
				<li><a href="/index/testimonial">Testimonials</a></li>
				<li><a href="/blog">Blog</a></li>
<?php
if (!$isLoggedin) {
?>
				<li><a href="/signin">Sign In</a></li>
<?php } else { ?>
				<li><a href="<?= Yii::app()->createUrl('users/logout') ?>">Log Out</a></li>
<?php } ?>
			</ul>
		</li>
	</ul>
</div>-->
</div>






<?php
//<div class="top-right-menu">
//	<a href="tel:+919051877000" style="text-decoration: none;">
//		<amp-img width="12" height="14" src="/images/img-2022/bx-phone-call.svg" alt="India"></amp-img>
//		(+91) 90518-77000 
//	</a>
//<!--	<a href="tel:+16507414696" style="text-decoration: none">
//		<amp-img width="16" height="15" src="/images/worl-icon.png" alt="International"></amp-img> (+1) 650-741-aaocab
//	</a>-->
//</div>
?>

