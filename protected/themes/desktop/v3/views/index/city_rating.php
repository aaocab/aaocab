<div class="container mt-2">
	<div class="row">
		<div class="col-12 merriw heading-line text-center">
			<?php echo $this->pageTitle; ?>
		</div>
	</div>
</div>
<div class="container mt-2">
<div class="row flex-top">                                        
    <?php
    if (count($model) > 0) {
        foreach ($model as $data) {
            ?>
	<div class="col-12 col-md-6 col-xl-4 mb30">
				<div class="card flex4">
					<div class="card-body p15">
		                <div class="row">								
		                    <div class="col-2 col-xl-2 col-md-2">
		                        <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50"><?= $data['initial']; ?>									
		                        </div>
		                    </div>								      
		                    <div class="col-10 col-xl-10 col-md-10">      
								<?php
								if ($data['rtg_customer_overall'] >= 4)
								{
									echo $data['rtg_customer_review'];
								}
								else
								{
									if (strlen($data['rtg_customer_review']) <= 50)
									{
										echo $data['rtg_customer_review'];
									}
								}
								?>
								<?php
								$strRating	 = '&nbsp;<br>';
								$rating_star = floor($data['rtg_customer_overall']);
								if ($rating_star > 0)
								{
									$strRating .= '';
									for ($s = 0; $s < $rating_star; $s++)
									{
										$strRating .= '<img src="/images/bxs-star3.svg" alt="img" width="12" height="12">';
									}
									if ($data['rtg_customer_overall'] > $rating_star)
									{
										$strRating .= '<img src="/images/bxs-star-half2.svg" alt="img" width="12" height="12"> ';
									}
								}
								$strRating .= '';
								?>                           
		                        <p class="mt10 font-16"><b>- <?= $data['user_name']; ?></b>&nbsp;<?= $strRating ?></p>
		                        <p class="mb0"><b><?= $data['cities']; ?>,</b> <i><?= Booking::model()->getBookingType($data['bkg_booking_type']); ?></i></p>
		                        <p class="m0 text-muted"><?= date('jS M Y', strtotime($data['rtg_customer_date'])) ?></p>
		                    </div>
		                </div>
					</div>
				</div>
			</div>
        <? } ?>
    </div>
    <div class="row">
        <div class="col-12 mb20">
            <?php
            // the pagination widget with some options to mess
            $this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination, 'displayFirstAndLast' => false, 'maxButtonCount' => 0, 'nextPageLabel' => 'Next', 'prevPageLabel' => 'Previous'));
            ?>
        </div>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="col-12">
            <?php echo 'No Records Found.'; ?>
        </div>
    </div>
<?php } ?>
</div>

