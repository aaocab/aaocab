<div id="menu-hider"></div>

<header class="header">
	<div class="header-line-1 header-hidden header-logo-left">
		<a href="/" class="back-button header-logo-image"></a>
		<a href="#" data-menu="sidebar-right-over" class="header-icon header-icon-4"><i class="fa fa-bars"></i></a>
	</div>
	<div class="header-line-2 header-scroll-effect">
        <a href="/" class="header-title default-link display-inline" style="width: 130px; margin-top: 7px !important" aria-label="aaocab"><img src="/images/gozo_svg_logo.svg" fetchpriority='high' class="preload-image" width="110" height="62" alt="aaocab" title="aaocab"aria-label="Go to home page"></a>
		<a href="/flashsale" class="display-inline header-icon header-icon-3 pt0 pl5 absolute-1" aria-label="Flash Sale"><img data-original="/images/flash_sale.svg?v=0.12" alt="Flash Sale" class="preload-image" width="17" height="20"></a>
		<a href="#" data-menu="sidebar-right-over" class="header-icon header-icon-1" aria-label="Sidebar"><img data-original="/images/side-bar.svg"  class="preload-image ml5" alt="Side bar" width="12" class="ml5"></a>
		<a href="#" class="header-icon header-icon-2 helpline" aria-label="Phone"><img data-original="/images/phone-1.svg" alt="Call" width="12" class="ml5 preload-image"></a>
		<a href="#" data-menu="phonr-hover1" class="header-icon header-icon-2 hide"><img data-original="/images/phone-1.svg" alt="Call" width="12" class="ml5 preload-image"></a>
        <a href="#" class="social-log-e hide" data-menu="menu-login-modal"><i class="far fa-envelope"></i></a>


		<a href="#" class="header-icon header-icon-2 hide" data-menu="menu-addcontact-modal"><i class="far fa-envelope"></i></a>
		<a href="#" class="header-icon header-icon-2 hide" data-menu="menu-verify-modal"><i class="far fa-envelope"></i></a>
		<a href="#" data-menu="sidebar-right-overcallback" class="header-icon header-icon-2 hide"></a>
        <a href="#" data-menu="menu-callmeback" class="header-icon header-icon-2 hide"></a>
		<!--	    <a href="#" class="color-black font-12 float-right">(+91) 90518-77-000 | (+1) 650-741-GOZO</a>  -->
	</div>    
</header>  



<div id="phonr-hover1" data-selected="menu-components" data-width="300" data-height="400" class="menu-box menu-modal">
	<div class="menu-title">
		<h2 class="hide">24x7 Support number</h2>
		<h2 class="mb0 font-20">Contact Us</h2>
		<a href="#" class="menu-hide pt10 pl10 line-height42"><i><img class="preload-image" data-original="/images/x-circle.svg" alt="" width="32" height="32"></i></a>					
	</div>         
    <div id="helplinebody"></div>    
</div>
<div id="menu-login-modal" data-selected="menu-components" data-width="300" data-height="420" class="menu-box menu-modal">
	<div class="menu-title">
		<!--    <h1><b>Request Call back</b></h1>-->
		<a href="#" class="menu-hide pt0 line-height42" style="z-index: 9;"><i class="fa fa-times"></i></a>
	</div>
	<div id="callmebackloginbody"></div>
</div>
<div id="menu-callmeback" data-selected="menu-components" data-width="300" data-height="420" class="menu-box menu-modal">
	<div class="menu-title">
		<h1><b>Request Call back</b></h1>
		<a href="#" class="menu-hide pt0 line-height42" style="z-index: 9;"><i class="fa fa-times"></i></a>
	</div>
	<div id="callmebackbody"></div>
</div>

<div id="sidebar-right-overcallback" data-selected="menu-components" class="menu-box menu-sidebar-right-full" style="transition: all 300ms ease 0s;">
	<div class="menu-title">
		<h1 class="mt10">Request Call back</h1>
		<a href="javascript:void(0);" class="menu-hide" style="line-height: 10px!important;"><i class="fa fa-times"></i></a>
	</div>
	<div id="callmebackmessagebody"></div>
</div>

<div id="menu-addcontact-modal" data-selected="menu-components" data-width="340" data-height="200" class="menu-box menu-modal">
	<div class="menu-title border-none">
		<a href="#" class="menu-hide pt0 line-height42"><i class="fa fa-times"></i></a>					
	</div>         
    <div id="addContactbody" class="menu-list content"></div>    
</div>

<input type="hidden" name="loging_stat" id="loging_stat" value="<?php
	   if (Yii::app()->user->isGuest)
	   {
		   echo '0';
	   }
	   else
	   {
		   echo '1';
	   }
	   ?>">
<script>
	$('.header-title').click(function()
	{
		window.location = "<?= Yii::app()->getBaseUrl(true) ?>";
		return false;
	});
</script>
