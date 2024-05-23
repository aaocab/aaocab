<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            Order Details ( Order Number : <?= $model->vor_number; ?> )
        </div>
    </div>
</div>

<div class="row m0 mb20">
    <div class="col-12 h4 m0 text-uppercase"></div>
</div>
<div class="container">
    <?php
    foreach ($model->voucherOrderDetails as $orderDetails) {
        ?>
            <div class="bg-white-box mb20">
                <div class="row">
                <div class="col-md-4"><?= $orderDetails->vodVch->vch_title; ?> - <?= $orderDetails->vodVch->vch_desc; ?></div>
                <div class="col-md-4 text-center"><?= $orderDetails->vod_vch_qty; ?></div>
                <div class="col-md-4 text-right">&#x20B9;<b><?= $orderDetails->vod_vch_price; ?></b></div>
                </div>
            </div>
    <?php }
    ?>
    <div class="font-22">
    <div class="row">
        <div class="col-lg-12 text-right">&#x20B9;<b><?= $model->vor_total_price; ?></b></div>
    </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12 h4 m0 text-uppercase">Billing Information</div>
    </div>
</div>
    <?php
    if (!$isMobile) {
        /* @var $model VoucherOrder */
        $this->renderPartial("summaryBilling", ["model" => $model, 'isredirct' => $isredirct], false);
    }
    ?>
