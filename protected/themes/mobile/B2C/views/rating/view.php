<?
$error		 = '';
$errorshow	 = ($error == '') ? 'hide' : '';
?>
<div class="menu-title">
	<h2 class="font18"><b class="font-14">Your Review</b> <br><b class="color-green3-dark"></b></h2>
</div>
<div class="menu-page p10 line-height18">
	<?
	if ($model->rtg_customer_overall)
	{
		?> 
		<div class="content bottom-10 pl0">

			<?= $model->getAttributeLabel('rtg_customer_overall') ?><br/>
			<!--			<div role="text" aria-label="" class="star-rating rater-0 star-rating-applied star-rating-readonly star-rating-on" id="Ratings_rtg_customer_overall_0"><a title="1">1</a></div>-->
			<?=
			$model->rtg_customer_overall;
			?>
			&nbsp;
			<?= star ?>

		</div>

		<?
	}
	if ($model->rtg_customer_driver)
	{
		?> 
		<div class="content bottom-10 pl0">
			<?= $model->getAttributeLabel('rtg_customer_driver') ?><br/>

			<?=
			$model->rtg_customer_driver;
			?>
			&nbsp;
			<?= star ?>
		</div>
		<?
	}
	if ($model->rtg_customer_csr)
	{
		?> <div class="content bottom-10 pl0">
			<?= $model->getAttributeLabel('rtg_customer_csr') ?><br/>
			<?= $model->rtg_customer_csr
			?>
			&nbsp;
			<?= star ?>
		</div><?
	}
	if ($model->rtg_customer_car)
	{
		?> <div class="content bottom-10 pl0">
			<?= $model->getAttributeLabel('rtg_customer_car') ?><br/>

			<?=
			$model->rtg_customer_car;
			?>
			&nbsp;
			<?= star ?>
		</div>
		<?
	}
	if ($model->rtg_customer_review)
	{
		?> 
		<div class="content bottom-10 pl0">
			<?= $model->getAttributeLabel('rtg_customer_review') ?> 
		</div>
		<div class="rounded ">
			<?= $model->rtg_customer_review;
			?>
		</div>
		<?
	}
	?>
</div>



