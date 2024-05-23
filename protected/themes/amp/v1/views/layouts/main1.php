<?php
$this->beginContent('//layouts/head');
?>
<body>
    <div class="main-panel">
        <header class="header">
            <div class="container">

                <div class="row">
					<?= $this->renderPartial("/index/toprow" . $this->layoutSufix); ?>
                </div>
            </div>
			<!--            <div class="inner-top-mune">
							<div class="container hidden-xs">
								<h2 class="mt0 mb0"></h2>
							</div>
						</div>-->
        </header>
		<?php
		$time = Filter::getExecutionTime();

		$GLOBALS['time97']	 = $time;
		?>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
					<?= $content ?>
                </div>
            </div>
        </div>
		<?php
		$time				 = Filter::getExecutionTime();

		$GLOBALS['time98'] = $time;
		?>
		<?= $this->renderPartial("/index/footer" . $this->layoutSufix); ?>
    </div>
</body>
<?php $this->endContent(); ?>

