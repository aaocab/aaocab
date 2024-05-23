<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>
<div class="row pt30 pb30 bg-gray">
    <div class="col-12">
    <div class="container">
    <div class="row flex">
        <?
        /* @var $model Ratings */
        foreach ($model as $data) {
            $toCities = Booking::model()->getToCities($data['rtg_booking_id']);
            ?>
            <div class="col-12 col-sm-6 col-md-6 mb40">
                <div class="card height100 bg-white-box">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-3 col-md-2 p0">
                                <div class="test-name gradient-green-blue"><?= $data['initial'] ?></div>
                            </div>
                            <div class="col-12 col-sm-9 col-md-10">
                                <?= $data['rtg_customer_review'] ?>
                                <p class="m0 block-color3"><i><b>- <?= $data['user_name'] ?></b></i></p>
                                <p class="m0"><b><?= $toCities; ?>,</b> <i><?= Booking::model()->getBookingType($data['bkg_booking_type']); ?></i></p>
                                <p class="m0 block-color3"><i><b><?= date('jS M Y', strtotime($data['rtg_customer_date'])) ?></b></i></p>
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

