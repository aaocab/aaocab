<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
<?php
$this->beginContent('//layouts/head');

?>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="http://www.googletagmanager.com/ns.html?id=GTM-T73295"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="container-fluid header-height">
		<div class="container">
		<nav class="row navbar navbar-expand-lg navbar-light pl0 pr0">
			<div class="col-sm-6">
				<a class="" href="/"><img src="/images/gozo_svg_logo.svg?v0.1" width="150" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." title="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></a>
			</div>
			<div class="col-sm-6 collapse navbar-collapse text-uppercase head-menu">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown" id="navbar_sign">
						<?= $this->renderPartial("/users/navbarsign"); ?>
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
<!--
<?php
//print_r($GLOBALS['time1'] ."==1\n");
//print_r($GLOBALS['time2'] ."==2\n");
//print_r($GLOBALS['time3'] ."==3\n");
//print_r($GLOBALS['time4'] ."==4\n");
//print_r($GLOBALS['time5'] ."==5\n");
//print_r($GLOBALS['time6'] ."==6\n");
//print_r($GLOBALS['time7'] ."==7\n");
//print_r($GLOBALS['time8'] ."==8\n");
//print_r($GLOBALS['time9'] ."==9\n");
?>
-->
<?php $this->endContent(); ?>

