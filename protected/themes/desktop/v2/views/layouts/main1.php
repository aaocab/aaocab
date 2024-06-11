<?php
$this->beginContent('//layouts/head');

?>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="http://www.googletagmanager.com/ns.html?id=GTM-T73295"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="container-fluid">
		<div class="container">
		<nav class="row navbar navbar-expand-lg navbar-light pl0 pr0">
			<div class="col-sm-4">
				<a class="" href="/"><img src="/images/gozo_svg_logo.svg?v0.1" width="150" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." title="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></a>
			</div>
			<div class="col-sm-8 collapse navbar-collapse text-uppercase head-menu">
				<ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="/agent/join">Become an agent</a></li>
					<li class="nav-item"><a class="nav-link" href="/vendor/join">Attach Your Taxi</a></li>
                    <li class="nav-item"><a class="nav-link" href="/index/testimonial">Testimonials</a></li>
					<li class="nav-item"><a class="nav-link" href="/blog">Blog</a></li>
					<li class="nav-item"><a href="javascript:void(0)" class="helpline nav-link">Contact</a></li>
					<li class="nav-item dropdown" id="navbar_sign">
						<?php $this->renderDynamic('renderPartial',"application.themes.desktop.v2.views.users.navbarsign", [], true); ?>
					</li>
				</ul>
			</div>
        </nav>
		</div>
		<?php
		$time				 = Filter::getExecutionTime();

		$GLOBALS['time97']	 = $time;
		?>
            
			<?= $content ?>
               
		<?php
		$time				 = Filter::getExecutionTime();

		$GLOBALS['time98']	 = $time;
		?>
		<?= $this->renderPartial("/index/footer"); ?>
    </div>
</body>
<?php
$time				 = Filter::getExecutionTime();

$GLOBALS['time99'] = $time;
?>

<?php $this->endContent(); ?>

