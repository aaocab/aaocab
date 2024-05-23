<style>

</style>

<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<?php  if(count($model) > 0) {
	foreach ($model as $data) {                      
?>
<div class="content-boxed-widget testmonial">
	<div class="content p0 bottom-0">
            <div class="test-name"><?= $data['initial']; ?></div>
    </div>
	<div class="content p0 bottom-0 text-center line-height16">
		<?php 
			if($data['rtg_customer_overall'] >= 4) {
				echo $data['rtg_customer_review'];
			} else {
				if(strlen($data['rtg_customer_review']) <= 50) {
					echo $data['rtg_customer_review'];
				}
			}
		?>	<br/>
		<?php
			$strRating	 = '&nbsp;';
			$rating_star = floor($data['rtg_customer_overall']);
			if ($rating_star > 0)
			{
				$strRating .= '(';
				for ($s = 0; $s < $rating_star; $s++) {										
					$strRating .= '<i class="fa fa-star orange-color"></i>';
				}
				if ($data['rtg_customer_overall'] > $rating_star) {										
					$strRating .= '<i class="fa fa-star-half orange-color"></i> ';
				}										
			}
			echo $strRating.')';
		?>						 
		<p class="m0 mt10 block-color3"><i><b>- <?= $data['user_name']; ?></b></i></p>
		<p class="m0"><b><?=$data['cities'];?>,</b> <i><?=Booking::model()->getBookingType($data['bkg_booking_type']);?></i></p>
		<p class="m0 mt10 block-color3"><b><?= date('jS M Y', strtotime($data['rtg_customer_date'])) ?></b></p>
	</div>
</div>
<? } ?>
<div class="pagination pagination-2 top-20 bottom-0">
	<?php
	// the pagination widget with some options to mess
	$this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination,'displayFirstAndLast'=> false,'maxButtonCount'=>0,'nextPageLabel'=>'Next','prevPageLabel'=>'Previous'));
	?>
</div>
<?php } else { ?>
<div class="content-boxed-widget testmonial">
<div class="mt10"> 
	  <?php echo 'No Records Found.'; ?>
</div>
</div>
<?php } ?>
