<?php
($this->newHome) ? $this->beginContent('//layouts/main_new') : $this->beginContent('//layouts/main1');
?>
<div class="row gradient-green-blue mb20">
	<div class="col-12">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="font-20 color-white pt10 text-uppercase"><?= $this->pageTitle ?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<?php //echo $content; ?>

<?php $this->endContent(); ?>