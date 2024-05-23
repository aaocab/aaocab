<div class="container">
<div class="row pt20 pb30">
    <div class="col-12">
	<h1 class="merriw heading-line text-center"><?php echo $this->pageTitle; ?></h1>
    <div class="row" style="display: flex; flex-wrap: wrap; justify-content:center;">
        <?
        /* @var $model Ratings */
        foreach ($model as $data) {
            $toCities = Booking::model()->getToCities($data['rtg_booking_id']);
            ?>
            <div class="col-12 col-md-6 col-xl-4 mb30 card-test">
                <div class="card flex4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2 col-sm-3 col-md-2 p0">
                                <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto mb-50"><?= $data['initial'] ?></div>
                            </div>
                            <div class="col-10 col-sm-9 col-md-10">
                                <p class="review-text-2"><?= $data['rtg_customer_review'] ?></p>
                                <p class="mt10 mb0 font-14"><b><?= $data['user_name'] ?></b></p>
                                <p class="m0 font-12"><b><?= $toCities; ?>,</b> <i><?= Booking::model()->getBookingType($data['bkg_booking_type']); ?></i></p>
                                <p class="m0 font-12 text-muted"><?= date('jS M Y', strtotime($data['rtg_customer_date'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <? } ?>
    </div>
    <div class="row">
        <div class="col-12">
            <?php
            // the pagination widget with some options to mess
            $this->widget('booster.widgets.TbPager', array('pages' => $usersList));
            ?>
        </div>
    </div>
</div>
</div>
</div>

