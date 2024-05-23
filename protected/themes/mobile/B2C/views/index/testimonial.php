
<?
/* @var $model Ratings */
foreach ($model as $data)
{
	$toCities = Booking::model()->getToCities($data['rtg_booking_id']);
	?>
	<div class="content-boxed-widget testmonial">
		<div class="content p0 bottom-0">
			<div class="test-name mb5"><?= $data['initial'] ?></div>
		</div>
		<div class="content p0 bottom-0 text-center line-height16">
	<?= $data['rtg_customer_review'] ?>
			<p class="m0 mt10 block-color3"><i><b>- <?= $data['user_name'] ?></b></i></p>
			<p class="m0"><b><?= $toCities; ?>,</b> <i><?= Booking::model()->getBookingType($data['bkg_booking_type']); ?></i></p>
			<p class="m0 mt10 block-color3"><b><?= date('jS M Y', strtotime($data['rtg_customer_date'])) ?></b></p>
		</div>
	</div>
	<? } ?>
<div class="pagination top-20 bottom-0">
	<?php
	// the pagination widget with some options to mess
	$this->widget('booster.widgets.TbPager', array('pages' => $usersList));
	?>
</div>

