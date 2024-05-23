<?php
$this->beginContent('//layouts/head');
?>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T73295" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->



	<div id="page-transitions" class="page-build light-skin highlight-blue">

		<?php $this->renderPartial('/layouts/header'); ?>

		<?php 
	//	$this->renderDynamic('checkForMobileTheme');
		$this->renderDynamic('renderPartial',"/layouts/menu", null, true); ?>
		<?php $this->renderPartial('/layouts/notification'); ?>
		<div class="page-content header-clear-large tab-styles" style="min-height: auto; padding-bottom: 60px">
			<?php echo $content; ?>
		</div>
		<?php echo $this->renderPartial("/index/footer"); ?>
	</div>
<!--	<div id="preloader" class="preloader-light">
		<h1></h1>
		<div id="preload-spinner"></div>
		<p>India's Leader in Outstation Taxi Travel</p>
		<em>© 2019 Gozo Technologies Pvt. Ltd. All Rights Reserved.</em>
	</div>-->
</body>
<?php
$this->endContent();
