<!--<div class="mr-auto float-left bookmark-wrapper d-flex align-items-center d-none d-xs-block d-none d-sm-block d-none d-md-block">
	<ul class="nav navbar-nav">
		<li class="nav-item mobile-menu mr-auto"><a class="nav-link nav-menu-main menu-toggle" href="javascript:void(0);" onclick="$('.aside_secend').asidebar('open')"><i class="bx bx-menu-alt-left"></i></a></li>
	</ul>
	<div class="aside_secend">
		<div class="aside-header">
			<span class="weight500 font-16 color-black"><?= Yii::app()->user->loadUser()->usr_name.' '.Yii::app()->user->loadUser()->usr_lname; ?></span>
			<span class="close" data-dismiss="aside" aria-hidden="true">×</span>
		</div>
		<div class="aside-contents">
			<ul>
				<li><a href="<?= Yii::app()->createUrl('users/view'); ?>"><i class="bx bx-user-circle mr10 align-middle"></i>My profile</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/GetQRCode'); ?>"><i class='fa fa-qrcode mr15 font-16'></i>QR code</a></li>
				<li><a href="<?= Yii::app()->createUrl('index/index'); ?>"><i class="bx bx-car mr10 align-middle"></i>New booking</a></li>
				<li><a href="<?= Yii::app()->createUrl('booking/list'); ?>"><i class="bx bx-time mr10 align-middle"></i>My bookings</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/creditlist'); ?>"><i class="bx bx-money mr10 align-middle"></i>Accounts details</a></li>
				<li><a href="<?= Yii::app()->createUrl('place/view'); ?>"><i class="bx bx-directions mr10 align-middle"></i>Favourite places</a></li>
				<li><a href="<?= Yii::app()->createUrl('users/refer'); ?>"><i class="bx bx-user-plus mr10 align-middle"></i>Refer friends</a></li>
				<li><a href="<?= Yii::app()->createUrl('voucher/orders'); ?>"><i class="bx bx-time mr10 align-middle"></i>Voucher history</a></li>
				<li><a href="<?= Yii::app()->createUrl('voucher/redeem'); ?>"><i class="bx bx-copy-alt mr10 align-middle"></i>Redeem voucher</a></li>
				<li><a href="/users/changePassword"><i class="bx bx-lock-alt mr10 align-middle"></i> Change password</a></li>
				<li><a class="dropdown-item" href="<?= Yii::app()->createUrl('users/logoutv3') ?>"><i class="bx bx-log-out-circle mr10"></i> Log out</a></li>
			</ul>
		</div>
	</div>
	<div class="aside-backdrop"></div>
</div>-->
<?php 
	$coinhtml	 = "";
	$uname		 = Yii::app()->user->loadUser()->usr_name;
	$coin		 = UserCredits::model()->getUserCoin(Yii::app()->user->getId());
	if ($coin > 0)
	{
		$coinhtml = '&nbsp;'.'  <img src="/images/img-2022/gozo_coin.svg?v=0.2" alt="Gozo Coin" width="14"> ' . $coin;
	}
			if(Yii::app()->user->getId() > 0)
			 {
					$rowUcm =  UserCategoryMaster::getByUserId(Yii::app()->user->getId());
					 if($rowUcm['ucm_id']!='')
					 {
						 $catCss = UserCategoryMaster::getColorByid($rowUcm['ucm_id']);
					 }
			  }
?>
<div class="card sidenav ul-style-c sidenav d-none d-lg-block d-xl-block d-xll-block">
<div class="card-body p15">
<p class="weight500 font-16"><?= Yii::app()->user->loadUser()->usr_name.' '.Yii::app()->user->loadUser()->usr_lname; ?></p>
<?php  if($rowUcm['ucm_id']!=''){ echo '<div class="user-categoty">'."<img src='/images/{$catCss}' alt='' width='25' title='{$rowUcm['ucm_label']}'>".'</div>'; }?>
<ul>
	<li><a href="<?= Yii::app()->createUrl('users/view'); ?>"><img data-src="/images/bx-user2.png" alt="My profile" width="16" height="16" class="mr10 lozad">My profile <?= $coinhtml ?></a></li>
	<li><a href="<?= Yii::app()->createUrl('users/refer'); ?>"><img data-src="/images/bx-user-plus.png" alt="Refer friends" width="16" height="16" class="mr10 lozad">Refer friends</a></li>
	<li><a href="<?= Yii::app()->createUrl('index/index'); ?>"><img data-src="/images/bx-car.png" alt="New booking" width="16" height="16" class="mr10 lozad">	New booking</a></li>
	<li><a href="<?= Yii::app()->createUrl('booking/list'); ?>"><img data-src="/images/bx-spreadsheet.png" alt="My bookings" width="16" height="16" class="mr10 lozad">My bookings</a></li>
	<li><a href="<?= Yii::app()->createUrl('users/creditlist'); ?>"><img data-src="/images/bxl-creative-commons.png" alt="Accounts details" width="16" height="16" class="mr10 lozad">Payments</a></li>
	<li><a href="<?= Yii::app()->createUrl('place/view'); ?>"><img data-src="/images/bx-directions.png" alt="Favourite places" width="16" height="16" class="mr10 lozad">Favourite places</a></li>
	<li><a href="/users/changePassword"><img data-src="/images/bx-lock-alt.png" alt="Change password" width="16" height="16" class="mr10 lozad"> Change password</a></li>
    <li><a class="dropdown-item" href="<?= Yii::app()->createUrl('users/logoutv3') ?>"><img data-src="/images/bx-log-out-circle.png" alt="Log out" width="16" height="16" class="mr10 lozad"> Log out</a></li>
</ul>
</div>
</div>
<script>
	var dropdown = document.getElementsByClassName("dropdown-btn");
	var i;

	for (i = 0; i < dropdown.length; i++) {
		dropdown[i].addEventListener("click", function () {
			this.classList.toggle("active");
			var dropdownContent = this.nextElementSibling;
			if (dropdownContent.style.display === "block") {
				dropdownContent.style.display = "none";
			} else {
				dropdownContent.style.display = "block";
			}
		});
	}
</script>