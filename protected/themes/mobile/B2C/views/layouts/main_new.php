<?php
$this->beginContent('//layouts/head');
?>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="http://www.googletagmanager.com/ns.html?id=GTM-T73295" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

<!--	<div id="preloader" class="preloader-light">
		<h1></h1>
		<div id="preload-spinner"></div>
		<p>India's Leader in Outstation Taxi Travel</p>
		<em>© <?= date('Y') ?> Gozo Technologies Pvt. Ltd. All Rights Reserved.</em>
	</div>-->

	<div id="page-transitions" class="page-build light-skin highlight-blue">
		<?php $this->renderPartial('/layouts/header'); ?>
		<?php 
//		$this->renderDynamic('checkForMobileTheme');
		$this->renderDynamic('renderPartial',"/layouts/menu", null, true); ?>
		<div class="page-content header-clear-large" style="min-height: 100%; padding-bottom: 60px">
			<?php echo $content; ?>
		
		</div>
		<?php $this->renderPartial('/layouts/notification'); ?>
		<?php echo $this->renderPartial("/index/footer"); ?>
    </div>

</body>

<?php
$this->endContent();
