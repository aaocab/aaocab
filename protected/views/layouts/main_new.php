<?php
$this->beginContent('//layouts/head');
if ($this->layout == 'column1')
{
	$style = "background-color: inherit";
}
$fixedTop			 = ($this->fixedTop) ? "navbar-fixed-top" : "";
$bgBanner			 = ($this->fixedTop) ? "bg-banner" : "";
?>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="http://www.googletagmanager.com/ns.html?id=GTM-T73295"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="fixed-menu hidden-xs">
        <a href="http://www.facebook.com/gozocabs" target="_blank" class="social-1 wow fadeInUp animated" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;" data-wow-delay="0.5s" title="Facebook"><i class="fa fa-facebook" data-toggle="tooltip" data-placement="left" title="Tooltip on left"></i></a>
        <a href="https://twitter.com/gozocabs" target="_blank" class="social-2 wow bounceIn animated" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;" data-wow-delay="0.7s" title="Twitter"><i class="fa fa-twitter"></i></a>
        
    </div>

    <div class="container-fluid smain-bg">
        <header class="header">
        </header>
		<?php
		$time				 = Filter::getExecutionTime();
		$GLOBALS['time97']	 = $time;

		echo $content;

		$time				 = Filter::getExecutionTime();
		$GLOBALS['time98']	 = $time;

		echo $this->renderPartial("/index/footer");
		?>
    </div>
</body>
<?php
$time				 = Filter::getExecutionTime();
$GLOBALS['time99']	 = $time;

$this->endContent();
