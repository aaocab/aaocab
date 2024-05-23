<?php
$this->beginContent('//layouts/head_mobile');
?>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T73295" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

	<div id="preloader" class="preloader-light">
		<h1></h1>
		<div id="preload-spinner"></div>
		<p>India's Leader in Outstation Taxi Travel</p>
		<em>© 2019 Gozo Technologies Pvt. Ltd. All Rights Reserved.</em>
	</div>
        
	<div id="page-transitions" class="page-build light-skin highlight-blue">

		<?php $this->renderPartial('/layouts/mobile_header'); ?>

		<?php $this->renderPartial('/layouts/mobile_menu'); ?>

		<?php echo $content; ?>

		<?php echo $this->renderPartial("/index/footer_mobile"); ?>
    </div>

</body>
<?php
$this->endContent();
