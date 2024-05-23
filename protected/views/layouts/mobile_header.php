<div id="menu-hider"></div>

<div class="header">
	<div class="header-line-1 header-hidden header-logo-left">
		<a href="#" class="back-button header-logo-image"></a>
		<a href="#" data-menu="sidebar-right-over" class="header-icon header-icon-4"><i class="fa fa-bars"></i></a>
	</div>
	<div class="header-line-2 header-scroll-effect">
		<a href="#" class="header-pretitle color-highlight"><!--Date will Appear Here --></a>
		<a href="<?= Yii::app()->getBaseUrl(true) ?>" class="header-title"><img src="<?= ASSETS_URL ?>images/mobile/logo_outstation.png" alt="GozoCabs"></a>
		<a href="#" data-menu="sidebar-right-over" class="header-icon header-icon-1"><i class="fa fa-bars"></i></a>
	</div>  
</div>
<input type="hidden" name="loging_stat" id="loging_stat" value="<? if (Yii::app()->user->isGuest){echo '0';}else{echo '1';} ?>">
